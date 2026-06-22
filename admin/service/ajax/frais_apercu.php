<?php
// admin/service/ajax/frais_apercu.php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }
header('Content-Type: application/json; charset=UTF-8');

// ---------- DB connect (tolérant aux chemins) ----------
$pdo = null;
foreach ([
    __DIR__ . '/../../../database/db_connect.php',
    __DIR__ . '/../../database/db_connect.php',
    __DIR__ . '/../../../database.php', // fallback éventuel
] as $p) {
    if (file_exists($p)) { require_once $p; break; }
}
if (!isset($pdo) || !($pdo instanceof PDO)) {
    http_response_code(500);
    echo json_encode(['status'=>'error','html'=>'<div class="text-danger">Erreur serveur (DB).</div>']);
    exit;
}

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
function money_usd($v){ return number_format((int)$v, 0, ',', ' ') . ' $'; }

// ---------- Auth & contexte école ----------
$userId    = (int)($_SESSION['user_id'] ?? 0);
$codeEcole = (string)($_SESSION['code_ecole'] ?? '');
if ($userId && $codeEcole === '') {
    $st = $pdo->prepare("SELECT code_ecole FROM users WHERE id=:id LIMIT 1");
    $st->execute([':id'=>$userId]);
    $codeEcole = (string)($st->fetchColumn() ?: '');
    if ($codeEcole) $_SESSION['code_ecole'] = $codeEcole;
}
if ($codeEcole === '') {
    echo json_encode(['status'=>'error','html'=>'<div class="text-danger">École inconnue.</div>']);
    exit;
}

// ---------- Inputs ----------
$type       = strtolower(trim((string)($_POST['type'] ?? ''))); // inscription|minerval|autres
$classeIds  = (array)($_POST['classe_ids'] ?? []);
$classeIds  = array_values(array_filter(array_map('intval', $classeIds), fn($v)=>$v>0));

if (!in_array($type, ['inscription','minerval','autres'], true)) {
    echo json_encode(['status'=>'error','html'=>'<div class="text-danger">Type invalide.</div>']);
    exit;
}

// ---------- Charger les classes de l’école ----------
try {
    $sqlClasses = "
        SELECT 
            c.id,
            c.classe         AS classe_code,
            c.description    AS desc_classe,
            n.description    AS niveau_label,
            s.description    AS section_label,
            o.description    AS option_label,
            c.niveau         AS niveau_id,
            c.section        AS section_id,
            c.options        AS option_id
        FROM classes c
        LEFT JOIN niveau  n ON c.niveau  = n.id
        LEFT JOIN section s ON c.section = s.id
        LEFT JOIN options o ON c.options = o.id
        WHERE c.code_ecole = :code
    ";
    // Si des classes sont sélectionnées : on filtre
    $params = [':code'=>$codeEcole];
    if (count($classeIds) > 0) {
        $in = implode(',', array_fill(0, count($classeIds), '?'));
        $sqlClasses .= " AND c.id IN ($in) ";
    }
    $sqlClasses .= " ORDER BY n.description, s.description, o.description, c.classe, c.description";

    $st = $pdo->prepare($sqlClasses);
    $bind = [ $codeEcole ];
    if (count($classeIds) > 0) { $bind = array_merge($bind, $classeIds); }
    $st->execute($bind);
    $classes = $st->fetchAll(PDO::FETCH_ASSOC);

    if (!$classes) {
        echo json_encode(['status'=>'ok','html'=>'<div class="text-muted">Aucune classe trouvée.</div>']);
        exit;
    }

    // ---------- Pour optimiser : précharger les tarifs par clé composite ----------
    // Chaque table a une UNIQUE KEY (code_ecole, niveau, section, OPTION, classe)
    $table   = '';
    $select  = '';
    if ($type === 'inscription') {
        $table  = 'frais_d_inscription';
        $select = 'montant';
    } elseif ($type === 'minerval') {
        $table  = 'minerval';
        $select = 'montant';
    } else { // autres
        $table  = 'autres_frais';
        $select = 'montant, description';
    }

    // Construire un WHERE ... IN sur les tuples (niveau, section, OPTION, classe)
    // Comme MySQL ne permet pas facilement IN sur tuples variables, on charge toute la table de l’école puis on indexe en PHP.
    $sqlFees = "SELECT niveau, section, `OPTION` AS opt, classe, $select
                FROM $table
                WHERE code_ecole = :code";
    $stF = $pdo->prepare($sqlFees);
    $stF->execute([':code'=>$codeEcole]);
    $feesRows = $stF->fetchAll(PDO::FETCH_ASSOC);

    // Index: "niveau|section|opt|classe" => row
    $feesIndex = [];
    foreach ($feesRows as $r) {
        $key = ((int)$r['niveau']).'|'.((int)$r['section']).'|'.((int)$r['opt']).'|'.((int)$r['classe']);
        $feesIndex[$key] = $r;
    }

    // ---------- Construire HTML ----------
    ob_start();
    ?>
<div class="table-responsive">
    <table class="table table-sm table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Classe</th>
                <th>Niveau</th>
                <th>Section</th>
                <th>Option</th>
                <th>Montant fixé</th>
                <?php if ($type === 'autres'): ?><th>Description</th><?php endif; ?>
            </tr>
        </thead>
        <tbody>
            <?php
        $i = 1;
        foreach ($classes as $c) {
            $lblClasse = trim(($c['classe_code'] ?? '').' '.($c['desc_classe'] ?? ''));
            $lblNiv    = (string)($c['niveau_label'] ?? '');
            $lblSec    = (string)($c['section_label'] ?? '');
            $lblOpt    = (string)($c['option_label'] ?? '');
            $key = ((int)$c['niveau_id']).'|'.((int)$c['section_id']).'|'.((int)$c['option_id']).'|'.((int)$c['id']); 
            // ATTENTION : c.classe est un "code classe" stocké en INT dans ta table de config. Si c'est l'ID de la classe,
            // remplace ci-dessus par: ...'|'.((int)$c['id']);

            $montant = '—';
            $descr   = '';
            if (isset($feesIndex[$key])) {
                $row = $feesIndex[$key];
                $montant = money_usd($row['montant'] ?? 0);
                if ($type === 'autres') {
                    $descr = (string)($row['description'] ?? '');
                }
            }
            ?>
            <tr>
                <td><?= $i++ ?></td>
                <td><?= h($lblClasse ?: ('Classe #'.(int)$c['id'])) ?></td>
                <td><?= h($lblNiv ?: '—') ?></td>
                <td><?= h($lblSec ?: '—') ?></td>
                <td><?= h($lblOpt ?: '—') ?></td>
                <td><?= h($montant) ?></td>
                <?php if ($type === 'autres'): ?><td><?= h($descr ?: '—') ?></td><?php endif; ?>
            </tr>
            <?php
        }
        ?>
        </tbody>
    </table>
</div>
<?php
    $html = ob_get_clean();

    echo json_encode(['status'=>'ok','html'=>$html]);
    exit;

} catch (Throwable $e) {
    // error_log('apercu error: '.$e->getMessage());
    echo json_encode(['status'=>'error','html'=>'<div class="text-danger">Erreur: chargement impossible.</div>']);
    exit;
}