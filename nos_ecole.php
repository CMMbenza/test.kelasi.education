<?php
// nos_ecole.php

// 1. Connexion à la base de données
require 'database/db_connect.php';

// 2. Récupérer toutes les écoles approuvées
$stmt = $pdo->prepare("SELECT * FROM ecoles WHERE statut = 'approuver' ORDER BY nom_ecole ASC");
$stmt->execute();
$ecoles = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Écoles - Kelasi</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        crossorigin="anonymous">

    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        crossorigin="anonymous">

    <style>
    body {
        font-family: 'Poppins', sans-serif;
        background: #f8fafc;
    }

    /* HERO */
    .hero {
        position: relative;
        height: 100vh;
        display: flex;
        align-items: center;
        text-align: left;
        color: white;
        background-color: var(--primary-color);
        /* Fallback de sécurité */
        background-image:
            linear-gradient(rgba(35, 33, 77, 0.8), rgba(14, 37, 41, 0.8)),
            url('http://localhost/kelasi-education/nos_ecoles/home/img/ChatGPT%20Image%2011%20juil.%202025,%2000_30_42.png');
        /* Image Unsplash fiable */
        background-position: center;
        background-size: cover;
        background-repeat: no-repeat;
        padding: 0 15px;
    }

    .hero::before {
        content: "";
        position: absolute;
        width: 600px;
        height: 600px;
        background: rgba(255, 255, 255, 0.1);
        border-radius: 50%;
        top: -100px;
        right: -100px;
        filter: blur(80px);
    }

    .hero h1 {
        font-size: clamp(2.8rem, 6vw, 4.5rem);
        font-weight: 700;
    }

    .hero p {
        font-size: 1.3rem;
        opacity: 0.9;
    }

    .hero-section {
        padding: 3rem 1rem;
        text-align: center;
    }

    .hero-section h1 {
        font-weight: 700;
        margin-bottom: 1rem;
        font-size: 2.5rem;
        color: #4f46e5;
    }

    .hero-section p {
        color: #6b7280;
        margin-bottom: 2rem;
    }

    .search-bar {
        max-width: 500px;
        margin: 0 auto 3rem auto;
    }

    .card-ecole {
        border-radius: 20px;
        background: #ffffffcc;
        backdrop-filter: blur(8px);
        transition: all 0.4s ease;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.08);
    }

    .card-ecole:hover {
        transform: translateY(-10px);
        box-shadow: 0 15px 40px rgba(0, 0, 0, 0.15);
    }

    .card-ecole img {
        max-height: 100px;
        object-fit: contain;
    }

    .btn-inscrire {
        border-radius: 50px;
        padding: 8px 25px;
        font-weight: 600;
        background: #4f46e5;
        color: white;
        transition: all 0.3s ease;
    }

    .btn-inscrire:hover {
        background: #3730a3;
        text-decoration: none;
    }

    .ville-pays {
        color: #6b7280;
        font-size: 0.95rem;
        margin-bottom: 0.5rem;
    }

    /* Container général */
    .search-bar {
        max-width: 500px;
        /* Largeur maximale */
        margin: 0 auto;
        /* Centrer horizontalement */
        border-radius: 50px;
        /* Bords arrondis */
        overflow: hidden;
        /* Pour que les bords arrondis prennent effet */
        box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        /* Ombre légère */
    }

    /* L'icône */
    .search-bar .input-group-text {
        background-color: #4CAF50;
        /* Vert agréable */
        color: white;
        border: none;
        /* Supprimer bord par défaut */
        padding: 0.5rem 1rem;
        font-size: 1.1rem;
    }

    /* L'input */
    .search-bar .form-control {
        border: none;
        /* Supprimer bord par défaut */
        padding: 0.5rem 1rem;
        font-size: 1rem;
        transition: all 0.3s ease;
    }

    .search-bar .form-control:focus {
        box-shadow: none;
        /* Supprimer l'ombre de focus par défaut */
        outline: none;
        background-color: #f0f0f0;
        /* Couleur douce au focus */
    }

    /* Pour mobile */
    @media (max-width: 576px) {
        .search-bar {
            max-width: 100%;
        }
    }
    </style>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark bg-light" aria-label="Menu principal">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">
                <img src="img/logo.png" alt="Chargement logo..." srcset="">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMenu"
                aria-controls="navMenu" aria-expanded="false" aria-label="Basculer la navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navMenu">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="btn btn-primary" href="login">Se connecter</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1>Nos Écoles Partenaires</h1>
                    <p class="mt-3 mb-4">Trouvez l'école qui vous convient et inscrivez-vous facilement.</p>
                    <a href="#nos_ecoles" class="btn btn-outline-light rounded-pill px-4 py-2 mb-2 fw-bold"
                        style="border-width: 2px;">Voir les détails</a>
                </div>
            </div>
        </div>
    </section>

    <section class="hero-section mt-5" id="nos_ecoles">
        <!-- <h1>Nos Écoles Partenaires</h1>
        <p>Trouvez l'école qui vous convient et inscrivez-vous facilement.</p> -->

        <div class="search-bar input-group mb-5">
            <span class="input-group-text"><i class="fas fa-search"></i></span>
            <input type="text" id="searchInput" class="form-control" placeholder="Rechercher une école...">
        </div>
    </section>

    <section class="container mb-5">
        <div class="row g-4" id="ecolesContainer">
            <?php foreach ($ecoles as $ecole): ?>
            <div class="col-lg-4 col-md-6 ecole-card">
                <div class="card card-ecole h-100 text-center p-4">
                    <img src="<?php echo htmlspecialchars($ecole['logo'] ?: 'https://png.pngtree.com/png-clipart/20230623/original/pngtree-school-logo-design-template-vector-png-image_9204124.png'); ?>"
                        alt="<?php echo htmlspecialchars($ecole['nom_ecole']); ?>" class="mb-3 mx-auto">
                    <h4><?php echo htmlspecialchars($ecole['nom_ecole']); ?></h4>
                    <div class="ville-pays">
                        <?php echo htmlspecialchars($ecole['ville'] ?? '') . ', ' . htmlspecialchars($ecole['pays'] ?? ''); ?>
                    </div>
                    <p class="text-muted">
                        <?php echo htmlspecialchars(substr($ecole['adress'] ?? '', 0, 80)) . (strlen($ecole['adress'] ?? '') > 80 ? '...' : ''); ?>
                    </p>
                    <div class="d-flex gap-2 mt-3">

                        <a href="nos_ecoles/home/?ecole=<?php echo urlencode($ecole['url_ecole']); ?>"
                            class="btn btn-primary d-flex align-items-center">
                            <i class="fas fa-school me-2"></i>
                            Voir
                        </a>

                        <a href="nos_ecoles/home/inscription.php?ecole=<?php echo urlencode($ecole['url_ecole']); ?>"
                            class="btn btn-success d-flex align-items-center">
                            <i class="fas fa-user-plus me-2"></i>
                            S’inscrire
                        </a>

                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Bootstrap JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous">
    </script>

    <!-- Recherche live JS -->
    <script>
    const searchInput = document.getElementById('searchInput');
    const ecolesContainer = document.getElementById('ecolesContainer');
    const ecoleCards = Array.from(document.querySelectorAll('.ecole-card'));

    searchInput.addEventListener('input', function() {
        const query = this.value.toLowerCase();
        ecoleCards.forEach(card => {
            const nom = card.querySelector('h4').innerText.toLowerCase();
            if (nom.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    });
    </script>

</body>

</html>