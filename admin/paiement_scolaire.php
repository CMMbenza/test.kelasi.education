<?php
if (session_status() !== PHP_SESSION_ACTIVE) { session_start(); }

foreach ([__DIR__.'/../database/db_connect.php', __DIR__.'/../../database/db_connect.php', __DIR__.'/database/db_connect.php'] as $p) {
    if (file_exists($p)) { require_once $p; break; }
}
if (!isset($pdo) || !($pdo instanceof PDO)) {
    http_response_code(500);
    echo "<!doctype html><html><body><p style='color:#b00'>Erreur serveur : DB indisponible.</p></body></html>";
    exit;
}

function h($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

$codeEcole = $_SESSION['code_ecole'] ?? '';

$students = $pdo->prepare("
    SELECT 
        s.id,
        CONCAT(
            s.first_name, ' ', s.last_name,
            ' - ',
            c.classe, ' ',
            IFNULL(c.description,''), ' ',
            IFNULL(niv.description,''), ' ',
            IFNULL(sec.description,''), ' ',
            IFNULL(opt.description,'')
        ) AS label
    FROM students s
    LEFT JOIN classes c ON c.id = s.class_id 
    LEFT JOIN niveau niv ON niv.id = c.niveau 
    LEFT JOIN section sec ON sec.id = c.section 
    LEFT JOIN options opt ON opt.id = c.options 
    WHERE s.code_ecole = ?
    ORDER BY s.first_name
");

$students->execute([$codeEcole]);
$students = $students->fetchAll(PDO::FETCH_ASSOC);
?>

<!doctype html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>MyKelasi | Effectuer le paiement</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Assets -->
    <link rel="shortcut icon" type="image/x-icon" href="../../img/favicon.png">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../../fonts/flaticon.css">
    <link rel="stylesheet" href="../css/animate.min.css">
    <link rel="stylesheet" href="../css/select2.min.css">
    <link rel="stylesheet" href="../css/datepicker.min.css">
    <link rel="stylesheet" href="../style.css">
    <script src="../js/modernizr-3.6.0.min.js"></script>
</head>

<body>
    <div id="preloader" class="d-none"></div>
    <div id="wrapper" class="wrapper bg-ash">

        <?php
    $nav = __DIR__ . '/layout/navbar.php';  if (file_exists($nav))  require $nav;
    ?>

        <div class="dashboard-page-one">
            <?php $side = __DIR__ . '/layout/sidebar.php'; if (file_exists($side)) require $side; ?>

            <div class="dashboard-content-one">


                <div class="card height-auto">
                    <div class="card-body mt-5">
                        <h3 class="text-uppercase">
                            Effectuer le paiement
                        </h3>
                        <?php if ($flash): ?>
                        <div class="alert alert-info"><?= htmlspecialchars($flash) ?></div>
                        <?php endif; ?>


                        <form method="POST" action="service/save_paiement.php">

                            <div class="row">
                                <div class="col-6">
                                    <!-- TYPE -->
                                    <div class="form-group">
                                        <label>Type de frais</label>
                                        <select name="statut" id="statut" class="form-control" required>
                                            <option value="#" selected disabled>Choisir</option>
                                            <option value="Inscription">Inscription</option>
                                            <option value="Minerval">Minerval</option>
                                            <option value="Autre">Autre</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <!-- ELEVE -->
                                    <div class="form-group">
                                        <label>Élève</label>
                                        <select name="eleve" id="eleve" class="form-control" required>
                                            <option value="#" selected disabled>-- élève --</option>
                                            <?php foreach ($students as $s): ?>
                                            <option value="<?= $s['id'] ?>">
                                                <?= htmlspecialchars($s['label']) ?>
                                            </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <!-- MONTANT TOTAL -->
                                    <div class="form-group">
                                        <label>Montant total $</label>
                                        <input type="number" id="montant_total" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <!-- TOTAL PAYE -->
                                    <div class="form-group">
                                        <label>Total déjà payé $</label>
                                        <input type="number" id="total_paye" class="form-control" readonly>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-6">
                                    <!-- SOLDE RESTANT -->
                                    <div class="form-group">
                                        <label>Solde restant $</label>
                                        <input type="number" id="solde_restant" class="form-control" readonly>
                                    </div>
                                </div>
                                <div class="col-6">
                                    <!-- MONTANT PAYE -->
                                    <div class="form-group">
                                        <label>Montant payé $</label>
                                        <input type="number" name="montant_paye" id="montant_paye" class="form-control"
                                            placeholder="0" required>
                                    </div>
                                </div>
                            </div>

                            <!-- NOUVEAU SOLDE (AUTO) -->
                            <div class="form-group">
                                <label>Nouveau solde $</label>
                                <input type="number" name="solde" id="nouveau_solde" class="form-control" readonly>
                            </div>

                            <button class="btn-fill-lg btn-gradient-yellow btn-hover-bluedark">Valider le
                                paiement</button>

                        </form>

                    </div>
                </div>

                <?php $foot = __DIR__ . '/layout/footer.php'; if (file_exists($foot)) require $foot; ?>
            </div>
        </div>
    </div>

    <!-- JS -->
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/plugins.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/select2.min.js"></script>
    <script src="../js/datepicker.min.js"></script>
    <script src="../js/jquery.scrollUp.min.js"></script>
    <script src="../js/main.js"></script>


    <script>
    let soldeRestant = 0;

    // CHARGEMENT DONNÉES
    function loadMontant() {

        let statut = document.getElementById('statut').value;
        let eleve = document.getElementById('eleve').value;

        if (!statut || !eleve) return;

        fetch(`service/get_montant.php?statut=${statut}&eleve=${eleve}`)
            .then(res => res.json())
            .then(data => {

                document.getElementById('montant_total').value = data.montant_total || 0;
                document.getElementById('total_paye').value = data.total_paye || 0;

                soldeRestant = data.solde_restant || 0;

                document.getElementById('solde_restant').value = soldeRestant;

                calculSolde();
            });
    }

    // CALCUL LIVE
    function calculSolde() {
        let paye = parseFloat(document.getElementById('montant_paye').value || 0);

        let nouveauSolde = soldeRestant - paye;

        if (nouveauSolde < 0) nouveauSolde = 0;

        document.getElementById('nouveau_solde').value = nouveauSolde;
    }

    // EVENTS
    document.getElementById('statut').addEventListener('change', loadMontant);
    document.getElementById('eleve').addEventListener('change', loadMontant);
    document.getElementById('montant_paye').addEventListener('input', calculSolde);
    </script>