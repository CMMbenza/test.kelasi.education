<?php
declare(strict_types=1);

if (session_status() !== PHP_SESSION_ACTIVE) session_start();

// sécurité basique
$userId  = $_SESSION['user_id'] ?? null;
$role    = strtolower(trim($_SESSION['role'] ?? ''));
$isAdmin = in_array($role, ['admin','administrateur'], true);

if (!$userId) {
    exit("Accès refusé");
}
?>

<!doctype html>
<html lang="fr">

<head>
    <meta charset="utf-8">
    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title> MyKelasi | Paramétres</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="../../img/favicon.png">
    <!-- Normalize CSS -->
    <link rel="stylesheet" href="../css/normalize.css">
    <!-- Main CSS -->
    <link rel="stylesheet" href="../css/main.css">
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <!-- Fontawesome CSS -->
    <link rel="stylesheet" href="../css/all.min.css">
    <!-- Flaticon CSS -->
    <link rel="stylesheet" href="../fonts/flaticon.css">
    <!-- Animate CSS -->
    <link rel="stylesheet" href="../css/animate.min.css">
    <!-- Data Table CSS -->
    <link rel="stylesheet" href="../css/jquery.dataTables.min.css">
    <!-- Custom CSS -->
    <link rel="stylesheet" href="../style.css">

    <style>
    .settings-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
        gap: 20px;
    }

    .settings-card {
        background: #fff;
        border-radius: 15px;
        padding: 25px 15px;
        text-align: center;
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.08);
        transition: 0.3s ease;
        text-decoration: none;
        color: #333;
    }

    .settings-card:hover {
        transform: translateY(-6px);
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.12);
    }

    .settings-card i {
        font-size: 35px;
        margin-bottom: 12px;
        display: block;
    }

    .settings-card span {
        font-weight: 600;
        font-size: 15px;
    }

    /* couleurs icônes */
    .blue i {
        color: #007bff;
    }

    .yellow i {
        color: #f7b731;
    }

    .green i {
        color: #20c997;
    }

    .red i {
        color: #dc3545;
    }

    .dark i {
        color: #343a40;
    }
    </style>
</head>

<body>

    <div id="wrapper" class="wrapper bg-ash">

        <?php $nav = __DIR__ . '/layout/navbar.php'; if (file_exists($nav)) require $nav; ?>

        <div class="dashboard-page-one">

            <?php $side = __DIR__ . '/layout/sidebar.php'; if (file_exists($side)) require $side; ?>

            <div class="dashboard-content-one mt-5">
                <div class="card">
                    <div class="card-body">

                        <h3>Paramètres - Configuration du système</h3>

                        <div class="settings-grid">

                            <!-- PROFIL -->
                            <!-- <a href="#" class="settings-card blue">
                                <i class="fas fa-user"></i>
                                <span>Mon Profil</span>
                            </a> -->

                            <!-- UTILISATEURS -->
                            <!-- <?php if ($isAdmin): ?>
                            <a href="#" class="settings-card yellow">
                                <i class="fas fa-users"></i>
                                <span>Utilisateurs</span>
                            </a>
                            <?php endif; ?> -->

                            <!-- ECOLE -->
                            <!-- <a href="#" class="settings-card green">
                                <i class="fas fa-school"></i>
                                <span>École</span>
                            </a> -->

                            <!-- DEPENSE -->
                            <a href="all-depenses.php" class="settings-card red">
                                <i class="fas fa-money-bill-wave"></i>
                                <span>Dépenses</span>
                            </a>

                            <!-- GESTION DE PRESENCE -->
                            <a href="presence_student.php" class="settings-card blue">
                                <i class="fas fa-graduation-cap"></i>
                                <span>Présence des élèves</span>
                            </a>

                            <!-- MATIERES -->
                            <a href="add-horaires.php" class="settings-card yellow">
                                <i class="fas fa-book"></i>
                                <span>Horaires de cours</span>
                            </a>

                            <!-- RAPPORT -->
                            <a href="reports.php" class="settings-card dark">
                                <i class="fas fa-chart-bar"></i>
                                <span>Rapports</span>
                            </a>

                            <!-- CONTROLE FS -->
                            <a href="controle.php" class="settings-card red">
                                <i class="fas fa-lock"></i>
                                <span>Contrôle FS</span>
                            </a>

                        </div>

                    </div>
                </div>

                <?php $foot = __DIR__ . '/layout/footer.php'; if (file_exists($foot)) require $foot; ?>

            </div>
        </div>
    </div>

    <!-- Modernize js -->
    <script src="../js/modernizr-3.6.0.min.js"></script>
    <!-- jquery-->
    <script src="../js/jquery-3.3.1.min.js"></script>
    <!-- Plugins js -->

    <script src="../js/plugins.js"></script>
    <!-- Popper js -->
    <script src="../js/popper.min.js"></script>
    <!-- Bootstrap js -->
    <script src="../js/bootstrap.min.js"></script>
    <!-- Scroll Up Js -->
    <script src="../js/jquery.scrollUp.min.js"></script>
    <!-- Data Table Js -->
    <script src="../js/jquery.dataTables.min.js"></script>
    <!-- Custom Js -->
    <script src="../js/main.js"></script>

</body>

</html>