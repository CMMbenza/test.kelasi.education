<?php
// admin/fixation-frais-scolaire.php — Checkbox par classe, Tout sélectionner, CSRF, Aperçu (AJAX)
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

// --- DB connect ---
$pdo = null;
foreach ([__DIR__.'/../database/db_connect.php', __DIR__.'/../../database/db_connect.php'] as $p) {
    if (file_exists($p)) { require_once $p; break; }
}
if (!isset($pdo) || !($pdo instanceof PDO)) { http_response_code(500); exit('Erreur serveur (DB).'); }

// --- Auth de base ---
$userId   = (int)($_SESSION['user_id'] ?? 0);
$role     = strtolower((string)($_SESSION['role'] ?? ''));
$isAdmin  = in_array($role, ['admin','administrateur'], true);

// code_ecole
$codeEcole = (string)($_SESSION['code_ecole'] ?? '');
if ($userId && !$codeEcole) {
    $st = $pdo->prepare("SELECT code_ecole FROM users WHERE id = :id LIMIT 1");
    $st->execute([':id'=>$userId]);
    $codeEcole = (string)($st->fetchColumn() ?: '');
    if ($codeEcole) $_SESSION['code_ecole'] = $codeEcole;
}
if (!$isAdmin || !$codeEcole) { http_response_code(403); exit('Accès refusé.'); }

// CSRF
if (empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(32));
$csrf = $_SESSION['csrf'];

// --- Classes de CETTE école ---
$stmt = $pdo->prepare("
    SELECT c.id, c.classe, c.description AS desc_classe,
           n.description AS niveau, s.description AS section, o.description AS opt
    FROM classes c
    LEFT JOIN niveau  n ON c.niveau    = n.id
    LEFT JOIN section s ON c.section   = s.id
    LEFT JOIN options o ON c.options   = o.id
    WHERE c.code_ecole = :code
    ORDER BY n.description, s.description, o.description, c.classe, c.description
");
$stmt->execute([':code'=>$codeEcole]);
$classes = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Message simple
$msg = '';
if (!empty($_GET['msg'])) {
    $map = [
        'created'  => '✅ Enregistrement effectué.',
        'error'    => '❌ Une erreur est survenue.',
        'badclass' => '⚠️ Classe invalide ou non liée à cette école.',
        'invalid'  => '⚠️ Champs requis manquants.',
    ];
    $msg = $map[$_GET['msg']] ?? '';
}
function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }
?>
<!doctype html>
<html class="no-js" lang="fr">

<head>
    <meta charset="utf-8">
    <title>MyKelasi | Fixation des frais</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.png">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../fonts/flaticon.css">
    <link rel="stylesheet" href="../css/animate.min.css">
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="../style.css">
    <script src="../js/modernizr-3.6.0.min.js"></script>
    <style>
    .apercu-loading {
        padding: 20px;
        text-align: center;
        font-style: italic;
        opacity: .85
    }

    .nav-actions {
        display: flex;
        gap: .5rem;
        align-items: center;
        margin-left: auto
    }

    .hint {
        color: #6b7280;
        font-size: .9rem
    }

    .classes-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(260px, 1fr));
        gap: .5rem
    }

    .classes-grid .item {
        border: 1px solid #e5e7eb;
        border-radius: .5rem;
        padding: .5rem;
        background: #fff
    }

    .form-check-inline {
        margin-right: 1rem
    }

    .soft-note {
        font-size: .85rem;
        color: #6b7280
    }
    </style>
</head>

<body>
    <div id="preloader" class="d-none"></div>
    <div id="wrapper" class="wrapper bg-ash">
        <?php require_once('layout/navbar.php'); ?>
        <div class="dashboard-page-one">
            <?php require_once('layout/sidebar.php'); ?>

            <div class="dashboard-content-one">
                <?php if ($msg): ?>
                <div class="alert alert-info mt-3"><?= h($msg) ?></div>
                <?php endif; ?>

                <!-- ================== BLOC UNIQUE AVEC NAV TABS ================== -->
                <div class="row mt-4">
                    <div class="col-12">
                        <div class="card height-auto">
                            <div class="card-body">
                                <div class="heading-layout1 d-flex align-items-center">
                                    <div class="item-title">
                                        <h3>Fixation des frais (sélection par cases à cocher)</h3>
                                        <div class="hint">Coche une ou plusieurs classes puis applique un montant (et
                                            une description pour “Autres frais”). Aucun champ n’est exigé dans les
                                            onglets non soumis.</div>
                                    </div>
                                    <div class="nav-actions">
                                        <button id="btn-apercu" type="button"
                                            class="btn btn-lg btn-outline-primary apercu-btn" data-type="inscription"
                                            data-form="#form-inscription" data-toggle="modal"
                                            data-target="#apercu-modal">
                                            Aperçu des frais actuels
                                        </button>
                                    </div>
                                </div>

                                <!-- Nav -->
                                <ul class="nav nav-tabs mt-3" id="fraisTabs" role="tablist">
                                    <li class="nav-item">
                                        <a class="nav-link active" id="tablink-inscription" data-toggle="tab"
                                            href="#tab-inscription" role="tab" aria-controls="tab-inscription"
                                            aria-selected="true" data-type="inscription" data-form="#form-inscription">
                                            Inscription
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tablink-minerval" data-toggle="tab" href="#tab-minerval"
                                            role="tab" aria-controls="tab-minerval" aria-selected="false"
                                            data-type="minerval" data-form="#form-minerval">
                                            Minerval
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" id="tablink-autres" data-toggle="tab" href="#tab-autres"
                                            role="tab" aria-controls="tab-autres" aria-selected="false"
                                            data-type="autres" data-form="#form-autres">
                                            Autres frais
                                        </a>
                                    </li>
                                </ul>

                                <!-- Contenu des onglets -->
                                <div class="tab-content pt-3" id="fraisTabsContent">
                                    <!-- INSCRIPTION -->
                                    <div class="tab-pane fade show active" id="tab-inscription" role="tabpanel"
                                        aria-labelledby="tablink-inscription">
                                        <form id="form-inscription" class="new-added-form"
                                            action="service/fixation-frais-scolaire.php" method="POST"
                                            autocomplete="off" novalidate>
                                            <input type="hidden" name="_csrf" value="<?= h($csrf) ?>">
                                            <div class="row">
                                                <div class="col-xl-8 form-group">
                                                    <label class="form-check-inline"><input type="checkbox"
                                                            id="insc-all">Tout sélectionner </label>
                                                    <div class="classes-grid" id="insc-classes">
                                                        <?php foreach ($classes as $c):
                                                        $lbl = trim(($c['classe'] ?? '').' '.($c['desc_classe'] ?? '').' '.($c['niveau'] ?? '').' '.($c['section'] ?? '').' '.($c['opt'] ?? '')); ?>
                                                        <label class="item">
                                                            <input type="checkbox" name="classes[]"
                                                                value="<?= (int)$c['id'] ?>">
                                                            <?= h($lbl ?: 'Classe #'.(int)$c['id']) ?>
                                                        </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 form-group">
                                                    <label>Montant $ <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" min="0" class="form-control"
                                                        name="montant" required>
                                                    <small class="soft-note">Obligatoire pour Inscription uniquement
                                                        lors de l’envoi.</small>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-12 form-group">
                                                    <button type="submit"
                                                        class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark"
                                                        name="save-inscription">Appliquer aux classes cochées</button>
                                                    <button type="reset"
                                                        class="btn-fill-lg bg-blue-dark btn-hover-yellow">Annuler</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- MINERVAL -->
                                    <div class="tab-pane fade" id="tab-minerval" role="tabpanel"
                                        aria-labelledby="tablink-minerval">
                                        <form id="form-minerval" class="new-added-form"
                                            action="service/fixation-frais-scolaire.php" method="POST"
                                            autocomplete="off" novalidate>
                                            <input type="hidden" name="_csrf" value="<?= h($csrf) ?>">
                                            <div class="row">
                                                <div class="col-xl-8 form-group"> <label
                                                        class="form-check-inline"><input type="checkbox" id="min-all">
                                                        Tout sélectionner </label>
                                                    <div class="classes-grid" id="min-classes">
                                                        <?php foreach ($classes as $c):
                                                        $lbl = trim(($c['classe'] ?? '').' '.($c['desc_classe'] ?? '').' '.($c['niveau'] ?? '').' '.($c['section'] ?? '').' '.($c['opt'] ?? '')); ?>
                                                        <label class="item">
                                                            <input type="checkbox" name="classes[]"
                                                                value="<?= (int)$c['id'] ?>">
                                                            <?= h($lbl ?: 'Classe #'.(int)$c['id']) ?>
                                                        </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 form-group">
                                                    <label>Montant $ <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" min="0" class="form-control"
                                                        name="montant" required>
                                                    <small class="soft-note">Obligatoire pour Minerval uniquement lors
                                                        de l’envoi.</small>
                                                </div>
                                            </div>
                                            <div class="row mt-2">
                                                <div class="col-12 form-group">
                                                    <button type="submit"
                                                        class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark"
                                                        name="save-minerval">Appliquer aux classes cochées</button>
                                                    <button type="reset"
                                                        class="btn-fill-lg bg-blue-dark btn-hover-yellow">Annuler</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>

                                    <!-- AUTRES FRAIS -->
                                    <div class="tab-pane fade" id="tab-autres" role="tabpanel"
                                        aria-labelledby="tablink-autres">
                                        <form id="form-autres" class="new-added-form"
                                            action="service/fixation-frais-scolaire.php" method="POST"
                                            autocomplete="off" novalidate>
                                            <input type="hidden" name="_csrf" value="<?= h($csrf) ?>">
                                            <div class="row">
                                                <div class="col-xl-8 form-group"><label class="form-check-inline"><input
                                                            type="checkbox" id="autres-all"> Tout sélectionner </label>
                                                    <div class="classes-grid" id="autres-classes">
                                                        <?php foreach ($classes as $c):
                                                        $lbl = trim(($c['classe'] ?? '').' '.($c['desc_classe'] ?? '').' '.($c['niveau'] ?? '').' '.($c['section'] ?? '').' '.($c['opt'] ?? '')); ?>
                                                        <label class="item">
                                                            <input type="checkbox" name="classes[]"
                                                                value="<?= (int)$c['id'] ?>">
                                                            <?= h($lbl ?: 'Classe #'.(int)$c['id']) ?>
                                                        </label>
                                                        <?php endforeach; ?>
                                                    </div>
                                                </div>
                                                <div class="col-xl-4 form-group">
                                                    <label>Montant $ <span class="text-danger">*</span></label>
                                                    <input type="number" step="0.01" min="0" class="form-control"
                                                        name="montant" required>
                                                    <small class="soft-note">Obligatoire pour Autres frais uniquement
                                                        lors de l’envoi.</small>
                                                </div>
                                                <div class="col-xl-12 form-group">
                                                    <label>Description <span class="text-danger">*</span></label>
                                                    <input type="text" class="form-control" name="description"
                                                        placeholder="Ex: Frais de laboratoire" required>
                                                </div>
                                            </div>
                                            <div class="row mt-5">
                                                <div class="col-12 form-group">
                                                    <button type="submit"
                                                        class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark"
                                                        name="save-autre">Appliquer aux classes cochées</button>
                                                    <button type="reset"
                                                        class="btn-fill-lg bg-blue-dark btn-hover-yellow">Annuler</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div><!-- /tab-content -->
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /BLOC UNIQUE -->

                <?php require_once('layout/footer.php'); ?>
            </div>
        </div>
    </div>

    <!-- ============== MODAL APERÇU ============== -->
    <div class="modal fade" id="apercu-modal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="apercu-title">Aperçu</h5>
                    <button type="button" class="close" data-dismiss="modal"
                        aria-label="Close"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div id="apercu-body" class="py-2">
                        <div class="apercu-loading">Chargement…</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/plugins.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.dataTables.min.js"></script>
    <script src="../js/main.js"></script>

    <script>
    $(function() {
        // Helpers: coche/décoche toutes les cases d'un conteneur
        function bindSelectAll(masterId, containerId) {
            const $m = $(masterId),
                $c = $(containerId);
            if (!$m.length || !$c.length) return;
            $m.on('change', function() {
                const checked = this.checked;
                $c.find('input[type="checkbox"][name="classes[]"]').prop('checked', checked);
            });
        }
        bindSelectAll('#insc-all', '#insc-classes');
        bindSelectAll('#min-all', '#min-classes');
        bindSelectAll('#autres-all', '#autres-classes');

        // Validation légère au submit : ne pas forcer tout remplir dans les autres onglets
        // Ici on empêche juste l'envoi si AUCUNE classe n'est cochée dans l'onglet soumis.
        function requireAtLeastOneClass($form) {
            const anyChecked = $form.find('input[name="classes[]"]:checked').length > 0;
            if (!anyChecked) {
                alert('Veuillez cocher au moins une classe.');
                return false;
            }
            return true;
        }

        $('#form-inscription').on('submit', function(e) {
            if (!requireAtLeastOneClass($(this))) {
                e.preventDefault();
                return;
            }
            // montant est required en HTML; pas d’autre blocage ici
        });
        $('#form-minerval').on('submit', function(e) {
            if (!requireAtLeastOneClass($(this))) {
                e.preventDefault();
                return;
            }
        });
        $('#form-autres').on('submit', function(e) {
            if (!requireAtLeastOneClass($(this))) {
                e.preventDefault();
                return;
            }
            // description est required en HTML
        });

        function setTitle(type, selectedText) {
            let base = 'Aperçu des frais actuels';
            if (type === 'inscription') base += ' — Inscription';
            else if (type === 'minerval') base += ' — Minerval';
            else if (type === 'autres') base += ' — Autres frais';
            $('#apercu-title').text(base + (selectedText ? ' (' + selectedText + ')' : ''));
        }

        // Quand on change d’onglet, on met à jour le bouton Aperçu
        $('#fraisTabs a[data-toggle="tab"]').on('shown.bs.tab', function(e) {
            const $link = $(e.target);
            $('#btn-apercu')
                .data('type', $link.data('type'))
                .data('form', $link.data('form'));
            setTitle($link.data('type'), '');
        });

        // Clic sur le bouton Aperçu : récupère les IDs cochés du formulaire de l’onglet actif
        $('#btn-apercu').on('click', function() {
            const type = $(this).data('type'); // inscription | minerval | autres
            const formSel = $(this).data('form'); // #form-inscription | #form-minerval | #form-autres
            const $form = $(formSel);
            const ids = $form.find('input[name="classes[]"]:checked').map(function() {
                return this.value;
            }).get();

            // petit label si une seule classe cochée
            let label = '';
            if (ids.length === 1) {
                const $lab = $form.find('input[name="classes[]"][value="' + ids[0] + '"]').closest(
                    'label').text().trim();
                label = $lab.replace(/^\s*|\s*$/g, '');
            }
            setTitle(type, label);
            $('#apercu-body').html('<div class="apercu-loading">Chargement…</div>');

            $.ajax({
                url: 'service/ajax/frais_apercu.php',
                method: 'POST',
                dataType: 'json',
                data: {
                    type: type,
                    classe_ids: ids
                },
                success: function(resp) {
                    if (resp && resp.status === 'ok' && resp.html) {
                        $('#apercu-body').html(resp.html);
                    } else {
                        $('#apercu-body').html(
                            '<div class="text-danger">Aucune donnée.</div>');
                    }
                },
                error: function(xhr) {
                    $('#apercu-body').html(
                        '<div class="text-danger">Erreur de chargement (' + xhr.status +
                        ').</div>');
                }
            });
        });

        // Reset modal
        $('#apercu-modal').on('hidden.bs.modal', function() {
            $('#apercu-body').html('<div class="apercu-loading">Chargement…</div>');
            $('#apercu-title').text('Aperçu');
        });
    });
    </script>
</body>

</html>