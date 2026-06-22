<?php
// mykelasi/admin/nos_cours.php

declare(strict_types=1);

header('Content-Type: text/html; charset=utf-8');

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// ===============================
// DB
// ===============================
$pdo = null;

foreach (
    [
        __DIR__ . '/../database/db_connect.php',
        __DIR__ . '/../../database/db_connect.php',
        __DIR__ . '/database/db_connect.php'
    ] as $dbFile
) {
    if (file_exists($dbFile)) {
        require_once $dbFile;
        break;
    }
}

if (!isset($pdo) || !($pdo instanceof PDO)) {
    http_response_code(500);
    exit('Erreur connexion DB');
}

// ===============================
// SESSION
// ===============================
$code_ecole = $_SESSION['code_ecole'] ?? null;

// ===============================
// HELPERS
// ===============================
function e($str): string
{
    return htmlspecialchars((string)$str, ENT_QUOTES, 'UTF-8');
}

function classeLabel(array $r): string
{
    return trim(
        ($r['classe'] ?? '') . ' ' .
        ($r['classe_desc'] ?? '') . ' - ' .
        ($r['niveau'] ?? '') . ' ' .
        ($r['section'] ?? '') . ' ' .
        ($r['opt'] ?? '')
    );
}

// ===============================
// DELETE COURS
// ===============================
if (
    isset($_GET['delete_cours']) &&
    ctype_digit($_GET['delete_cours'])
) {

    $deleteId = (int) $_GET['delete_cours'];

    try {

        $pdo->beginTransaction();

        // Supprimer contenus des leçons
        $sql = "
        DELETE lc
        FROM lecon_contenus lc
        INNER JOIN lecons l ON l.id = lc.lecon_id
        WHERE l.cours_id = :cours
        ";

        $stmt = $pdo->prepare($sql);

        $stmt->execute([
            ':cours' => $deleteId
        ]);

        // Supprimer leçons
        $stmt = $pdo->prepare("
            DELETE FROM lecons
            WHERE cours_id = :cours
        ");

        $stmt->execute([
            ':cours' => $deleteId
        ]);

        // Supprimer quiz liés à la classe/prof du cours
        $stmtCoursInfo = $pdo->prepare("
            SELECT class, teacher_user_id
            FROM cours
            WHERE id = :id
            LIMIT 1
        ");

        $stmtCoursInfo->execute([
            ':id' => $deleteId
        ]);

        $coursInfo = $stmtCoursInfo->fetch(PDO::FETCH_ASSOC);

        if ($coursInfo) {

            $stmtQuiz = $pdo->prepare("
                DELETE FROM quizzes
                WHERE class_id = :class_id
                AND teacher_user_id = :teacher
            ");

            $stmtQuiz->execute([
                ':class_id' => $coursInfo['class'],
                ':teacher'  => $coursInfo['teacher_user_id']
            ]);
        }

        // Supprimer cours
        $stmt = $pdo->prepare("
            DELETE FROM cours
            WHERE id = :id
            AND code_ecole = :code_ecole
        ");

        $stmt->execute([
            ':id'          => $deleteId,
            ':code_ecole' => $code_ecole
        ]);

        $pdo->commit();

        header('Location: nos_cours.php?success=deleted');
        exit;

    } catch (Throwable $e) {

        $pdo->rollBack();

        die($e->getMessage());
    }
}

// ===============================
// PARAMS
// ===============================
$cours_id = isset($_GET['cours_id']) ? (int)$_GET['cours_id'] : 0;
$lecon_id = isset($_GET['lecon_id']) ? (int)$_GET['lecon_id'] : 0;

// ===============================
// COURS
// ===============================
$sqlCours = "
SELECT
    crs.id,
    crs.nom,
    crs.created_at,
    crs.teacher_user_id,

    u.first_name,
    u.last_name,

    c.classe,
    c.description AS classe_desc,

    n.description AS niveau,
    s.description AS section,
    o.description AS opt

FROM cours crs

INNER JOIN classes c
ON c.id = crs.class

LEFT JOIN users u
ON u.id = crs.teacher_user_id

LEFT JOIN niveau n
ON n.id = c.niveau

LEFT JOIN section s
ON s.id = c.section

LEFT JOIN options o
ON o.id = c.options

WHERE crs.code_ecole = :code_ecole

ORDER BY crs.id DESC
";

$stmtCours = $pdo->prepare($sqlCours);

$stmtCours->execute([
    ':code_ecole' => $code_ecole
]);

$cours = $stmtCours->fetchAll(PDO::FETCH_ASSOC);

// ===============================
// COURS SELECT
// ===============================
$currentCours = null;

if ($cours_id > 0) {

    $sql = "
    SELECT
        crs.*,

        c.classe,
        c.description AS classe_desc,

        n.description AS niveau,
        s.description AS section,
        o.description AS opt

    FROM cours crs

    INNER JOIN classes c
    ON c.id = crs.class

    LEFT JOIN niveau n
    ON n.id = c.niveau

    LEFT JOIN section s
    ON s.id = c.section

    LEFT JOIN options o
    ON o.id = c.options

    WHERE crs.id = :id
    LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':id' => $cours_id
    ]);

    $currentCours = $stmt->fetch(PDO::FETCH_ASSOC);
}

// ===============================
// LECONS
// ===============================
$lecons = [];

if ($cours_id > 0) {

    $sql = "
    SELECT *
    FROM lecons
    WHERE cours_id = :cours
    ORDER BY ordre ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':cours' => $cours_id
    ]);

    $lecons = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// ===============================
// CONTENUS
// ===============================
$contenus = [];

if ($lecon_id > 0) {

    $sql = "
    SELECT *
    FROM lecon_contenus
    WHERE lecon_id = :lecon
    ORDER BY ordre ASC
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':lecon' => $lecon_id
    ]);

    $links = $stmt->fetchAll(PDO::FETCH_ASSOC);

    foreach ($links as $link) {

        $type = $link['type_contenu'];
        $cid  = (int)$link['contenu_id'];

        $row = null;

        // PDF
        if ($type === 'pdf') {

            $s = $pdo->prepare("
                SELECT id, title, description
                FROM pdfs
                WHERE id = :id
            ");

            $s->execute([':id' => $cid]);

            $row = $s->fetch(PDO::FETCH_ASSOC);

            if ($row) {

                $contenus[] = [
                    'type'  => 'PDF',
                    'icon'  => 'fa-file-pdf text-danger',
                    'title' => $row['title'],
                    'desc'  => $row['description'],
                    'url'   => '../view_pdf.php?id=' . $cid
                ];
            }
        }

        // VIDEO
        if ($type === 'video') {

            $s = $pdo->prepare("
                SELECT id, title, description
                FROM videos
                WHERE id = :id
            ");

            $s->execute([':id' => $cid]);

            $row = $s->fetch(PDO::FETCH_ASSOC);

            if ($row) {

                $contenus[] = [
                    'type'  => 'VIDEO',
                    'icon'  => 'fa-video text-primary',
                    'title' => $row['title'],
                    'desc'  => $row['description'],
                    'url'   => '../view_video.php?id=' . $cid
                ];
            }
        }

        // AUDIO
        if ($type === 'audio') {

            $s = $pdo->prepare("
                SELECT id, titre, description
                FROM audios
                WHERE id = :id
            ");

            $s->execute([':id' => $cid]);

            $row = $s->fetch(PDO::FETCH_ASSOC);

            if ($row) {

                $contenus[] = [
                    'type'  => 'AUDIO',
                    'icon'  => 'fa-headphones text-success',
                    'title' => $row['titre'],
                    'desc'  => $row['description'],
                    'url'   => '../view_audio.php?id=' . $cid
                ];
            }
        }

        // IMAGE
        if ($type === 'image') {

            $s = $pdo->prepare("
                SELECT id, title, description
                FROM images
                WHERE id = :id
            ");

            $s->execute([':id' => $cid]);

            $row = $s->fetch(PDO::FETCH_ASSOC);

            if ($row) {

                $contenus[] = [
                    'type'  => 'IMAGE',
                    'icon'  => 'fa-image text-warning',
                    'title' => $row['title'],
                    'desc'  => $row['description'],
                    'url'   => '../view_image.php?id=' . $cid
                ];
            }
        }
    }
}
?>

<!doctype html>
<html lang="fr">

<head>

    <meta charset="utf-8">

    <title>Nos cours | MyKelasi</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.png">
    <link rel="stylesheet" href="../css/normalize.css">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/all.min.css">
    <link rel="stylesheet" href="../fonts/flaticon.css">
    <link rel="stylesheet" href="../css/fullcalendar.min.css">
    <link rel="stylesheet" href="../css/animate.min.css">
    <link rel="stylesheet" href="../style.css">

    <script src="../js/modernizr-3.6.0.min.js"></script>
    <script src="../js/Chart.min.js"></script>

    <style>
    .card-box {
        border-radius: 18px;
        border: 0;
        box-shadow: 0 5px 25px rgba(0, 0, 0, .05);
    }

    .course-item {
        transition: .2s;
    }

    .course-item:hover {
        background: #f8f9fa;
    }

    .lesson-card {
        border-left: 4px solid #007bff;
    }

    .content-card {
        transition: .2s;
    }

    .content-card:hover {
        transform: translateY(-2px);
    }

    .action-btns .btn {
        margin-left: 5px;
    }
    </style>

</head>

<body>

    <div id="wrapper" class="wrapper bg-ash">

        <?php include 'layout/navbar.php'; ?>

        <div class="dashboard-page-one">

            <?php include 'layout/sidebar.php'; ?>

            <div class="dashboard-content-one">

                <div class="container-fluid py-4">

                    <!-- HEADER -->
                    <div class="mb-4 d-flex justify-content-between align-items-center flex-wrap gap-3">

                        <div>
                            <h3 class="mb-1 fw-bold">
                                <i class="fas fa-book text-primary me-2"></i>
                                Nos cours
                            </h3>

                            <p class="text-muted mb-0">
                                Gestion des cours, leçons et contenus pédagogiques
                            </p>
                        </div>

                        <a href="add-edit-cours.php" class="btn btn-primary btn-md px-4">
                            <i class="fas fa-plus-circle me-2"></i>
                            Nouveau cours
                        </a>
                    </div>

                    <?php if(isset($_GET['success'])): ?>

                    <div class="alert alert-success">
                        Suppression effectuée avec succès.
                    </div>

                    <?php endif; ?>

                    <div class="row">

                        <!-- COURS -->
                        <div class="col-lg-4 mb-4">

                            <div class="card card-box">

                                <div class="card-body">

                                    <h5 class="mb-4">
                                        <i class="fas fa-layer-group"></i>
                                        Liste des cours
                                    </h5>

                                    <?php if(!$cours): ?>

                                    <div class="alert alert-info">
                                        Aucun cours trouvé.
                                    </div>

                                    <?php else: ?>

                                    <div class="list-group">

                                        <?php foreach($cours as $c): ?>

                                        <div class="list-group-item <?= ($cours_id == $c['id']) ? 'active':'' ?>">

                                            <div class="d-flex justify-content-between align-items-start">

                                                <div>

                                                    <div class="font-weight-bold">
                                                        <?= e($c['nom']) ?>
                                                    </div>

                                                    <small>
                                                        <?= e(classeLabel($c)) ?>
                                                    </small>

                                                    <br>

                                                    <small>
                                                        Prof :
                                                        <?= e($c['first_name'] . ' ' . $c['last_name']) ?>
                                                    </small>
                                                </div>

                                            </div>
                                            <div class="action-btns">

                                                <!-- EDIT -->
                                                <a href="add-edit-cours.php?id=<?= (int)$c['id'] ?>"
                                                    class="btn btn-sm btn-secondary text-white">

                                                    <i class="fas fa-edit"></i>

                                                </a>

                                                <!-- DELETE -->
                                                <a href="?delete_cours=<?= (int)$c['id'] ?>"
                                                    class="btn btn-sm btn-danger"
                                                    onclick="return confirm('Supprimer ce cours, ses leçons et quiz ?')">

                                                    <i class="fas fa-trash"></i>

                                                </a>

                                                <!-- OPEN -->
                                                <a href="?cours_id=<?= (int)$c['id'] ?>" class="btn btn-sm btn-primary">
                                                    <i class="fas fa-folder-open"></i>
                                                </a>

                                            </div>
                                        </div>

                                        <?php endforeach; ?>

                                    </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                        <!-- LECONS -->
                        <div class="col-lg-4 mb-4">

                            <div class="card card-box">

                                <div class="card-body">

                                    <h5 class="mb-4">
                                        <i class="fas fa-list"></i>
                                        Leçons
                                    </h5>

                                    <?php if(!$currentCours): ?>

                                    <div class="alert alert-light">
                                        Sélectionnez un cours.
                                    </div>

                                    <?php elseif(!$lecons): ?>

                                    <div class="alert alert-warning">
                                        Aucune leçon trouvée.
                                    </div>

                                    <?php else: ?>

                                    <?php foreach($lecons as $l): ?>

                                    <div class="card lesson-card mb-3">

                                        <div class="card-body">

                                            <div class="d-flex justify-content-between align-items-center">

                                                <div>

                                                    <h6 class="mb-1">
                                                        <?= e($l['titre']) ?>
                                                    </h6>

                                                    <small class="text-muted">
                                                        Ordre : <?= (int)$l['ordre'] ?>
                                                    </small>

                                                </div>

                                                <a href="?cours_id=<?= $cours_id ?>&lecon_id=<?= (int)$l['id'] ?>"
                                                    class="btn btn-primary btn-sm">

                                                    Voir

                                                </a>

                                            </div>

                                        </div>

                                    </div>

                                    <?php endforeach; ?>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                        <!-- CONTENUS -->
                        <div class="col-lg-4 mb-4">

                            <div class="card card-box">

                                <div class="card-body">

                                    <h5 class="mb-4">
                                        <i class="fas fa-folder-open"></i>
                                        Contenus
                                    </h5>

                                    <?php if(!$lecon_id): ?>

                                    <div class="alert alert-light">
                                        Sélectionnez une leçon.
                                    </div>

                                    <?php elseif(!$contenus): ?>

                                    <div class="alert alert-warning">
                                        Aucun contenu disponible.
                                    </div>

                                    <?php else: ?>

                                    <div class="row">

                                        <?php foreach($contenus as $c): ?>

                                        <div class="col-12 mb-3">

                                            <div class="card content-card">

                                                <div class="card-body">

                                                    <div class="d-flex">

                                                        <div class="mr-3">

                                                            <i class="fas <?= e($c['icon']) ?> fa-2x"></i>

                                                        </div>

                                                        <div class="flex-fill">

                                                            <h6 class="mb-1">
                                                                <?= e($c['title']) ?>
                                                            </h6>

                                                            <p class="text-muted small mb-2">
                                                                <?= e($c['desc']) ?>
                                                            </p>

                                                            <a href="<?= e($c['url']) ?>" target="_blank"
                                                                class="btn btn-outline-primary btn-sm">

                                                                Ouvrir

                                                            </a>

                                                        </div>

                                                    </div>

                                                </div>

                                            </div>

                                        </div>

                                        <?php endforeach; ?>

                                    </div>

                                    <?php endif; ?>

                                </div>

                            </div>

                        </div>

                    </div>

                </div>

                <?php include 'layout/footer.php'; ?>

            </div>

        </div>

    </div>

    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/plugins.js"></script>
    <script src="../js/popper.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
    <script src="../js/jquery.counterup.min.js"></script>
    <script src="../js/moment.min.js"></script>
    <script src="../js/jquery.waypoints.min.js"></script>
    <script src="../js/jquery.scrollUp.min.js"></script>
    <script src="../js/fullcalendar.min.js"></script>
    <script src="../js/Chart.min.js"></script>
    <script src="../js/main.js"></script>

</body>

</html>