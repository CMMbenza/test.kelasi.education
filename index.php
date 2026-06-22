<?php
// 1. INCLUSION DE LA BASE DE DONNÉES
require 'database/db_connect.php';

// 2. LOGIQUE DE ROUTAGE (Vérification de l'URL)
$path = $_SERVER['REQUEST_URI'];
$segments = explode('/', $path);

$atSegment = null;
foreach ($segments as $seg) {
    if (strpos($seg, '@') === 0) {
        // Récupérer le nom de l'école sans le '@'
        $atSegment = substr($seg, 1);
        break;
    }
}

// 3. TRAITEMENT SI UNE ÉCOLE EST DEMANDÉE
if ($atSegment) {
    $nom = htmlspecialchars($atSegment);

    // Vérifier si l'école existe dans la base de données
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM landingpage WHERE url_ecole = ?");
    $stmt->execute([$nom]);
    $exists = $stmt->fetchColumn() > 0;

    if ($exists) {
        // L'école existe : Redirection vers sa page dédiée
        $url_nouveau = urlencode($nom);
        header("Location: /nos_ecoles/home/?ecole=$url_nouveau");
        exit;
    } else {
        // L'école n'existe pas : Redirection vers l'accueil principal
        header("Location: /");
        exit;
    }
}

// Récupérer les écoles approuvées (statut = 'approuver') triées par date de création
$stmt = $pdo->prepare("
    SELECT id, nom_ecole, code_ecole, url_ecole, ville, province_etat, pays, logo, telephone1, telephone2
    FROM ecoles
    WHERE statut = 'approuver'
    ORDER BY date_creation DESC
    LIMIT 10
");
$stmt->execute();
$ecoles = $stmt->fetchAll(PDO::FETCH_ASSOC);

// 🔢 Compter les écoles approuvées
$stmt = $pdo->query("SELECT COUNT(*) FROM ecoles WHERE statut = 'approuver'");
$total_ecoles = $stmt->fetchColumn();

// 🎓 Compter les élèves
$stmt = $pdo->query("SELECT COUNT(*) FROM students");
$total_eleves = $stmt->fetchColumn();

// 👨‍🏫 Compter les professeurs
$stmt = $pdo->query("SELECT COUNT(*) FROM teacher");
$total_profs = $stmt->fetchColumn();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Kelasi - Solution digitale intelligente pour moderniser la gestion des établissements scolaires en Afrique.">
    <title>Kelasi - Accueil</title>
    <!-- Favicon -->
    <link rel="shortcut icon" type="image/x-icon" href="img/favicon.png">
    <!-- Bootstrap avec intégrité (Sécurité SRI) -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        xintegrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">

    <!-- FontAwesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
        crossorigin="anonymous" referrerpolicy="no-referrer">

    <!-- Google Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap" rel="stylesheet">

    <!-- Swiper CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"
        crossorigin="anonymous" />

    <style>
    :root {
        --primary-color: #4f46e5;
        --secondary-color: #06b6d4;
        --dark-color: #111;
    }

    html {
        scroll-behavior: smooth;
    }

    body {
        font-family: 'Poppins', sans-serif;
        overflow-x: hidden;
    }

    /* NAVBAR - Ajout d'un effet Glassmorphism plus moderne */
    .navbar {
        transition: all 0.4s ease;
        padding: 1rem 0;
    }

    .navbar.scrolled {
        background: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(10px);
        /* Effet verre flouté */
        -webkit-backdrop-filter: blur(10px);
        box-shadow: 0 5px 20px rgba(0, 0, 0, 0.08);
        padding: 0.5rem 0;
    }

    .navbar.scrolled .nav-link,
    .navbar.scrolled .navbar-brand,
    .navbar.scrolled .navbar-toggler-icon {
        color: var(--dark-color) !important;
        filter: invert(0);
    }

    /* Menu mobile : fond blanc pour lisibilité si on ouvre le menu en haut de page */
    @media (max-width: 991px) {
        .navbar-collapse {
            background: rgba(255, 255, 255, 0.95);
            padding: 15px;
            border-radius: 10px;
            margin-top: 10px;
        }

        .navbar-collapse .nav-link {
            color: #000 !important;
        }
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

    .btn-premium {
        background: white;
        color: var(--primary-color);
        border-radius: 50px;
        padding: 12px 30px;
        font-weight: 600;
        transition: all 0.3s ease;
        display: inline-block;
        text-decoration: none;
    }

    .btn-premium:hover {
        background: var(--dark-color);
        color: white;
        transform: translateY(-3px);
        /* Effet d'élévation */
    }

    .section-padding {
        padding: 100px 0;
    }

    /* CARD PREMIUM */
    .card-premium {
        border: none;
        border-radius: 20px;
        transition: all 0.4s ease;
        background: #fff;
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.05);
    }

    .card-premium:hover {
        transform: translateY(-10px) scale(1.02);
        box-shadow: 0 25px 50px rgba(0, 0, 0, 0.15);
    }

    /* TESTIMONIAL */
    .testimonial {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 20px;
        height: 100%;
        border-left: 5px solid var(--primary-color);
    }

    /* BLOG IMAGE */
    .blog-img {
        border-radius: 20px 20px 0 0;
        object-fit: cover;
        height: 200px;
    }

    /* COUNTER */
    .counter {
        font-size: 3rem;
        font-weight: bold;
        color: var(--primary-color);
    }

    /* CTA */
    .cta {
        background: linear-gradient(135deg, var(--primary-color), var(--secondary-color));
        color: white;
        border-radius: 20px;
        padding: 60px 20px;
    }

    footer {
        background: var(--dark-color);
        color: white;
        padding: 50px 0 20px;
    }

    footer a {
        color: #bbb;
        text-decoration: none;
        transition: 0.3s;
    }

    footer a:hover {
        color: white;
        padding-left: 5px;
    }

    /* SWIPER */
    .swiper-slide {
        display: flex;
        justify-content: center;
        padding: 20px 0;
    }

    .swiper-pagination-bullet-active {
        background: var(--primary-color) !important;
    }

    .counter-box {
        background: white;
        padding: 40px;
        border-radius: 20px;
        transition: 0.3s;
        /* box-shadow: 0 10px 25px rgba(0, 0, 0, 0.05); */
    }

    .counter-box:hover {
        transform: translateY(-8px);
    }

    .card img {
        transition: 0.3s;
    }

    /* .card:hover img {
        transform: scale(1.1);
    } */

    .cta {
        background: linear-gradient(135deg, #4f46e5, #06b6d4);
        color: white;
        border-radius: 25px;
        padding: 80px 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.2);
    }

    .navbar-brand img {
        height: 45px;
    }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg fixed-top navbar-dark" aria-label="Menu principal">
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
                    <li class="nav-item"><a class="nav-link" href="#about">À propos</a></li>
                    <li class="nav-item"><a class="nav-link" href="#schools">Écoles</a></li>
                    <li class="nav-item"><a class="nav-link" href="#blog">Blog</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <h1>La révolution digitale des écoles</h1>
                    <p class="mt-3 mb-4">Une solution intelligente pour moderniser l’éducation, simplifier
                        l'administration et connecter les parents.</p>
                    <a href="chox-compte.php" class="btn btn-premium me-2 mb-2">Commencer
                        gratuitement</a>
                    <a href="login/" class="btn btn-outline-light rounded-pill px-4 py-2 mb-2 fw-bold"
                        style="border-width: 2px;">Se
                        connecter</a>
                </div>
            </div>
        </div>
    </section>

    <!-- ABOUT -->
    <section id="about" class="section-padding text-center">
        <div class="container">
            <h2 class="fw-bold">Pourquoi choisir Kelasi ?</h2>
            <p class="text-muted mt-3 mb-5">Gestion automatisée, communication simplifiée et statistiques en temps réel.
            </p>

            <div class="row g-4 mt-2">
                <div class="col-md-4">
                    <div class="p-4 card-premium h-100 bg-white">
                        <i class="fas fa-cogs fa-3x mb-3 text-primary"></i>
                        <h5>Gestion Automatisée</h5>
                        <p class="text-muted">Fini les papiers. Gérez les inscriptions et les bulletins en quelques
                            clics.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 card-premium h-100 bg-white">
                        <i class="fas fa-comments fa-3x mb-3 text-info"></i>
                        <h5>Communication Fluide</h5>
                        <p class="text-muted">Connectez instantanément les enseignants, les élèves et les parents
                            d'élèves.</p>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-4 card-premium h-100 bg-white">
                        <i class="fas fa-chart-line fa-3x mb-3 text-success"></i>
                        <h5>Statistiques Détaillées</h5>
                        <p class="text-muted">Suivez les performances scolaires et financières avec des tableaux de bord
                            clairs.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- COUNTER -->
    <section class="section-padding text-center bg-light">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="counter-box">
                        <i class="fas fa-school fa-2x mb-3 text-primary"></i>
                        <div class="counter" data-target="<?= $total_ecoles ?>">0</div>
                        <p>Écoles partenaires</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="counter-box">
                        <i class="fas fa-user-graduate fa-2x mb-3 text-success"></i>
                        <div class="counter" data-target="<?= $total_eleves ?>">0</div>
                        <p>Élèves actifs</p>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="counter-box">
                        <i class="fas fa-chalkboard-teacher fa-2x mb-3 text-info"></i>
                        <div class="counter" data-target="<?= $total_profs ?>">0</div>
                        <p>Enseignants</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- SCHOOLS SLIDER -->
    <section id="schools" class="section-padding bg-white">
        <div class="container">
            <div class="text-center mb-5">
                <h2 class="text-center fw-bold">Nos Écoles Partenaires</h2>
                <p class="text-lead">Retrouver d’autres écoles afin d’obtenir davantage d’informations détaillées à
                    leur sujet, notamment en comparant leurs programmes, leurs conditions d’admission, la qualité de
                    l’enseignement ainsi que les opportunités offertes aux étudiants.</p>
                <a href="nos_ecole.php" class="btn btn-primary">Voir plus d'écoles</a>
            </div>
            <div class="swiper schoolSwiper">
                <div class="swiper-wrapper">

                    <?php foreach ($ecoles as $ecole): ?>
                    <div class="swiper-slide">
                        <div class="card card-premium text-center p-4 w-100 mx-2" style="max-width:300px;">

                            <!-- Logo école -->
                            <img src="<?= htmlspecialchars($ecole['logo'] ?: 'https://png.pngtree.com/png-clipart/20230623/original/pngtree-school-logo-design-template-vector-png-image_9204124.png') ?>"
                                alt="Logo <?= htmlspecialchars($ecole['nom_ecole']) ?>"
                                class="mb-3 rounded-circle mx-auto" style="width:80px;height:80px;object-fit:cover;">

                            <!-- Nom école -->
                            <h5><?= htmlspecialchars($ecole['nom_ecole']) ?></h5>

                            <!-- Ville / pays -->
                            <p class="text-muted small">
                                <?= htmlspecialchars($ecole['ville'] ?? '') ?>
                                <?= htmlspecialchars($ecole['province_etat'] ?? '') ?>
                                <?= htmlspecialchars($ecole['pays'] ?? '') ?>
                            </p>

                            <!-- Téléphones -->
                            <p class="text-muted small mb-1">
                                <i class="fas fa-phone"></i> <?= htmlspecialchars($ecole['telephone1'] ?? '') ?>
                                <?php if (!empty($ecole['telephone2'])): ?>
                                / <?= htmlspecialchars($ecole['telephone2']) ?>
                                <?php endif; ?>
                            </p>

                            <!-- Bouton visiter l’école -->
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
                <!-- Pagination -->
                <div class="swiper-pagination mt-4 position-relative"></div>
            </div>
        </div>
    </section>

    <!-- BLOG -->
    <section id="blog" class="section-padding bg-light">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Derniers Articles</h2>
            <div class="row g-4">

                <!-- BLOG 1 -->
                <div class="col-md-4">
                    <div class="card card-premium h-100">
                        <img src="https://images.unsplash.com/photo-1509062522246-3755977927d7?w=500&auto=format&fit=crop"
                            class="card-img-top blog-img" alt="Étudiants utilisant des tablettes">
                        <div class="card-body d-flex flex-column">
                            <h5 class="fw-bold">La digitalisation des écoles en 2026</h5>
                            <p class="flex-grow-1 text-muted">Découvrez comment la technologie transforme l’éducation et
                                facilite le travail des enseignants au quotidien.</p>
                            <a href="#" class="btn btn-outline-primary mt-2 rounded-pill">Lire l'article <i
                                    class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <!-- BLOG 2 -->
                <div class="col-md-4">
                    <div class="card card-premium h-100">
                        <img src="https://us.123rf.com/450wm/twinsterphoto/twinsterphoto1905/twinsterphoto190500022/124612054-gros-plan-sur-des-enfants-afro-am%C3%A9ricains-%C3%A9l%C3%A9mentaires-dessinant-et-peignant-de-mani%C3%A8re-cr%C3%A9ative.jpg?ver=6"
                            class="card-img-top blog-img" alt="Ordinateur portable sur un bureau">
                        <div class="card-body d-flex flex-column">
                            <h5 class="fw-bold">Pourquoi adopter une plateforme ?</h5>
                            <p class="flex-grow-1 text-muted">Les avantages pour les directeurs et enseignants sont
                                nombreux, allant du gain de temps à la sécurité des données.</p>
                            <a href="#" class="btn btn-outline-primary mt-2 rounded-pill">Lire l'article <i
                                    class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

                <!-- BLOG 3 -->
                <div class="col-md-4">
                    <div class="card card-premium h-100">
                        <img src="https://thumbs.dreamstime.com/b/afro-am%C3%A9ricaine-fille-faire-ses-devoirs-avec-sa-m%C3%A8re-une-belle-femme-noire-qui-aide-%C3%A0-travailler-l-%C3%A9cole-la-maison-un-parent-252028106.jpg"
                            class="card-img-top blog-img" alt="Groupe de diplômés">
                        <div class="card-body d-flex flex-column">
                            <h5 class="fw-bold">Améliorer la communication parents-école</h5>
                            <p class="flex-grow-1 text-muted">Des outils modernes pour une communication fluide,
                                transparente et efficace entre les familles et l'école.</p>
                            <a href="#" class="btn btn-outline-primary mt-2 rounded-pill">Lire l'article <i
                                    class="fas fa-arrow-right ms-1"></i></a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- TESTIMONIAL -->
    <section class="section-padding bg-white">
        <div class="container">
            <h2 class="text-center fw-bold mb-5">Avis des utilisateurs</h2>
            <div class="row g-4">

                <div class="col-md-4">
                    <div class="testimonial shadow-sm">
                        <div class="text-warning mb-2">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="fst-italic">"Une plateforme incroyable qui a totalement simplifié notre gestion
                            scolaire au quotidien."</p>
                        <h6 class="fw-bold mt-3 mb-0">- Jean M.</h6>
                        <small class="text-muted">Directeur d'école</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="testimonial shadow-sm">
                        <div class="text-warning mb-2">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star"></i>
                        </div>
                        <p class="fst-italic">"Le calcul des résultats automatiques me fait gagner un temps énorme en
                            fin de trimestre."</p>
                        <h6 class="fw-bold mt-3 mb-0">- Marie K.</h6>
                        <small class="text-muted">Professeur de Mathématiques</small>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="testimonial shadow-sm">
                        <div class="text-warning mb-2">
                            <i class="fas fa-star"></i><i class="fas fa-star"></i><i class="fas fa-star"></i><i
                                class="fas fa-star"></i><i class="fas fa-star-half-alt"></i>
                        </div>
                        <p class="fst-italic">"La communication est devenue beaucoup plus fluide et transparente avec
                            les parents."</p>
                        <h6 class="fw-bold mt-3 mb-0">- Paul L.</h6>
                        <small class="text-muted">Administrateur système</small>
                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="section-padding">
        <div class="container">
            <div class="cta text-center shadow-lg">
                <h2 class="fw-bold">Prêt à moderniser votre école ?</h2>
                <p class="mt-3 mb-4 lead">Rejoignez des centaines d’établissements déjà connectés à Kelasi.</p>
                <!-- Correction de la balise HTML (a au lieu de button) -->
                <a href="chox-compte.php" class="btn btn-light btn-lg rounded-pill px-5 py-3 fw-bold text-primary">Créer
                    un compte
                    maintenant</a>
            </div>
        </div>
    </section>

    <!-- CONTACT (Sécurisé avec balise Form) -->
    <section id="contact" class="section-padding bg-light">
        <div class="container">
            <div class="row align-items-center g-5">

                <!-- TEXTE -->
                <div class="col-lg-6">
                    <h2 class="fw-bold mb-3">Contactez-nous</h2>
                    <p class="text-muted mb-4">
                        Une question, une demande ou besoin d’assistance ?
                        Notre équipe Kelasi vous répond rapidement.
                    </p>

                    <div class="mb-3">
                        <i class="fas fa-envelope text-primary me-2"></i>
                        support@kelasi.com
                    </div>

                    <div class="mb-3">
                        <i class="fas fa-phone text-success me-2"></i>
                        +243 XXX XXX XXX
                    </div>

                    <div>
                        <i class="fas fa-map-marker-alt text-danger me-2"></i>
                        Kinshasa, RDC
                    </div>
                </div>

                <!-- FORMULAIRE -->
                <div class="col-lg-6">
                    <div class="card border-0 p-4 rounded-4">
                        <h5 class="fw-bold mb-3">Laissez nous un message</h5>
                        <form id="contactForm" method="POST">

                            <div class="mb-3">
                                <input type="text" name="name" class="form-control form-control-lg"
                                    placeholder="Votre nom complet" required>
                            </div>

                            <div class="mb-3">
                                <input type="email" name="email" class="form-control form-control-lg"
                                    placeholder="Votre email" required>
                            </div>

                            <div class="mb-3">
                                <textarea name="message" class="form-control form-control-lg" rows="4"
                                    placeholder="Votre message..." required></textarea>
                            </div>

                            <!-- MESSAGE FEEDBACK -->
                            <div id="formAlert" class="mb-3"></div>

                            <button type="submit" class="btn btn-primary w-100 btn-lg rounded-pill shadow-sm">
                                Envoyer le message
                            </button>

                        </form>

                    </div>
                </div>

            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5 class="fw-bold text-white">Kelasi</h5>
                    <p class="text-muted mt-3">La solution digitale de référence pour la gestion intelligente des
                        établissements scolaires.</p>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold text-white">Liens Utiles</h5>
                    <ul class="list-unstyled mt-3">
                        <li class="mb-2"><a href="#"><i
                                    class="fas fa-chevron-right me-2 text-primary small"></i>Accueil</a></li>
                        <li class="mb-2"><a href="#about"><i class="fas fa-chevron-right me-2 text-primary small"></i>À
                                propos</a></li>
                        <li class="mb-2"><a href="#blog"><i
                                    class="fas fa-chevron-right me-2 text-primary small"></i>Blog</a></li>
                        <li class="mb-2"><a href="#contact"><i
                                    class="fas fa-chevron-right me-2 text-primary small"></i>Contact</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5 class="fw-bold text-white">Suivez-nous</h5>
                    <div class="d-flex gap-3 mt-3">
                        <a href="#" class="text-white fs-4"><i class="fab fa-facebook"></i></a>
                        <a href="#" class="text-white fs-4"><i class="fab fa-twitter"></i></a>
                        <a href="#" class="text-white fs-4"><i class="fab fa-linkedin"></i></a>
                        <a href="#" class="text-white fs-4"><i class="fab fa-instagram"></i></a>
                    </div>
                </div>
            </div>
            <hr class="mt-5 border-secondary">
            <p class="text-center text-muted mb-0">© 2026 Kelasi - Tous droits réservés.</p>
        </div>
    </footer>

    <!-- JS Bootstrap (avec intégrité SRI) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"
        xintegrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous">
    </script>

    <!-- Swiper JS -->
    <script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js" crossorigin="anonymous"></script>

    <script>
    document.getElementById("contactForm").addEventListener("submit", function(e) {
        e.preventDefault();

        let form = this;
        let formData = new FormData(form);
        let alertBox = document.getElementById("formAlert");

        fetch("contact_traitement.php", {
                method: "POST",
                body: formData
            })
            .then(res => res.text())
            .then(data => {
                if (data === "success") {
                    alertBox.innerHTML =
                        `<div class="alert alert-success">✅ Message envoyé avec succès !</div>`;
                    form.reset();
                } else {
                    alertBox.innerHTML = `<div class="alert alert-danger">❌ Une erreur est survenue.</div>`;
                }
            })
            .catch(() => {
                alertBox.innerHTML = `<div class="alert alert-danger">❌ Erreur réseau.</div>`;
            });
    });

    document.querySelectorAll('.card-premium').forEach(card => {
        card.style.opacity = 0;
        card.style.transform = "translateY(40px)";

        setTimeout(() => {
            card.style.transition = "0.6s";
            card.style.opacity = 1;
            card.style.transform = "translateY(0)";
        }, 200);
    });

    // 1. RESTAURATION DU SCRIPT SWIPER (Carrousel)
    var swiper = new Swiper(".schoolSwiper", {
        slidesPerView: 3,
        spaceBetween: 30,
        loop: true,
        autoplay: {
            delay: 2500,
            disableOnInteraction: false,
        },
        pagination: {
            el: ".swiper-pagination",
            clickable: true,
        },
        breakpoints: {
            0: {
                slidesPerView: 1
            },
            768: {
                slidesPerView: 2
            },
            1024: {
                slidesPerView: 3
            }
        }
    });

    // 2. RESTAURATION DU SCRIPT NAVBAR (Effet au scroll)
    window.addEventListener("scroll", function() {
        const navbar = document.querySelector(".navbar");
        if (window.scrollY > 50) {
            navbar.classList.add("scrolled");
        } else {
            navbar.classList.remove("scrolled");
        }
    });

    // 3. SCRIPT DES COMPTEURS INTELLIGENTS (Optimisé)
    const observeCounters = new IntersectionObserver((entries, observer) => {
        entries.forEach(entry => {
            if (entry.isIntersecting) {
                const counter = entry.target;
                const target = +counter.getAttribute('data-target');

                const update = () => {
                    const count = +counter.innerText;
                    const increment = target / 100; // Vitesse de l'animation

                    if (count < target) {
                        counter.innerText = Math.ceil(count + increment);
                        setTimeout(update, 20);
                    } else {
                        counter.innerText = target;
                    }
                };
                update();
                observer.unobserve(counter); // Empêche l'animation de se relancer
            }
        });
    }, {
        threshold: 0.5
    }); // Déclenche quand 50% de la section est visible

    document.querySelectorAll('.counter').forEach(c => observeCounters.observe(c));
    </script>

</body>

</html>