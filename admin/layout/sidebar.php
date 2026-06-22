<?php 
 '../customs/session_check.php'; 
require_once 'service/user_connecter.php';
// protectPage();
?>

<link rel="stylesheet" href="../../css/normalize.css">
<!-- Main CSS -->
<link rel="stylesheet" href="../../css/main.css">
<!-- Bootstrap CSS -->
<link rel="stylesheet" href="../../css/bootstrap.min.css">
<!-- Fontawesome CSS -->
<link rel="stylesheet" href="../../css/all.min.css">
<!-- Flaticon CSS -->
<link rel="stylesheet" href="../../fonts/flaticon.css">
<!-- Full Calender CSS -->
<link rel="stylesheet" href="../../css/fullcalendar.min.css">
<!-- Animate CSS -->
<link rel="stylesheet" href="../../css/animate.min.css">
<!-- Custom CSS -->
<link rel="stylesheet" href="../../style.css">
<!-- Modernize js -->
<script src="../../js/modernizr-3.6.0.min.js"></script>

<style>
.icon-sidebar {
    color: #ffffff;
}

.nav-sidebar-menu .nav-link {
    transition: all 0.3s ease;
}

/* Hover sur le lien */
.nav-sidebar-menu .nav-link:hover {
    background-color: rgba(255, 106, 0, 0.1);
    /* orange léger */
    transform: translateX(5px);
}

/* Hover sur les icônes */
.nav-sidebar-menu .nav-link:hover .icon-sidebar {
    color: #ff6a00;
}

/* Hover sur le texte */
.nav-sidebar-menu .nav-link:hover span {
    color: #ff6a00;
}
</style>
<div class="sidebar-main sidebar-menu-one sidebar-expand-md sidebar-color">
    <div class="mobile-sidebar-header d-md-none">
        <div class="header-logo">
            <a href="index.php"><img src="../img/logo.png" alt="logo"></a> <!-- Changed from index.html -->
        </div>
    </div>
    <div class="sidebar-menu-content">
        <ul class="nav nav-sidebar-menu sidebar-toggle-view">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link">
                    <i class="icon-sidebar fas fa-tachometer-alt"></i>
                    <span>Tableau de bord</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="all-class.php" class="nav-link">
                    <i class="icon-sidebar fas fa-school"></i>
                    <span>Classes</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="all-teacher.php" class="nav-link">
                    <i class="icon-sidebar fas fa-chalkboard-teacher"></i>
                    <span>Enseignant</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="all-students.php" class="nav-link">
                    <i class="icon-sidebar fas fa-user-graduate"></i>
                    <span>Élève</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="nos_cours.php" class="nav-link">
                    <i class="icon-sidebar fas fa-book-open"></i>
                    <span>Nos cours</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="paiement.php" class="nav-link">
                    <i class="icon-sidebar fas fa-coins"></i>
                    <span>Finances (Scolarité)</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="profil.php" class="nav-link">
                    <i class="icon-sidebar fas fa-user-circle"></i>
                    <span>Mon profil</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="settings.php" class="nav-link">
                    <i class="icon-sidebar fas fa-cog"></i>
                    <span>Paramétres</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="../login/logout.php?msg=logout" class="text-danger nav-link">
                    <i class="fas fa-sign-out-alt"></i>
                    <span class="text-danger">Déconnexion</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- jquery-->
<script src="../../js/jquery-3.3.1.min.js"></script>
<!-- Plugins js -->
<script src="../../js/plugins.js"></script>
<!-- Popper js -->
<script src="../../js/popper.min.js"></script>
<!-- Bootstrap js -->
<script src="../../js/bootstrap.min.js"></script>
<!-- Counterup Js -->
<script src="../../js/jquery.counterup.min.js"></script>
<!-- Moment Js -->
<script src="../../js/moment.min.js"></script>
<!-- Waypoints Js -->
<script src="../../js/jquery.waypoints.min.js"></script>
<!-- Scroll Up Js -->
<script src="../../js/jquery.scrollUp.min.js"></script>
<!-- Full Calender Js -->
<script src="../../js/fullcalendar.min.js"></script>
<!-- Chart Js -->
<script src="../../js/Chart.min.js"></script>
<!-- Custom Js -->
<script src="../../js/main.js"></script>