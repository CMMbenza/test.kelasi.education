<?php
if (session_status() !== PHP_SESSION_ACTIVE) { 
    session_start(); 
}

header('Content-Type: application/json');

require_once __DIR__ . '/../../database/db_connect.php';

$codeEcole = $_SESSION['code_ecole'] ?? '';
$statut    = $_GET['statut'] ?? '';
$eleveId   = (int)($_GET['eleve'] ?? 0);

$response = [
    "montant_total" => 0,
    "total_paye"    => 0,
    "solde_restant" => 0
];

if (empty($statut) || $eleveId <= 0) {
    echo json_encode($response);
    exit;
}

try {
    // 1. Déterminer la table de tarif cible selon le statut
    $tarifTable = '';
    if ($statut === 'Minerval') {
        $tarifTable = 'minerval';
    } elseif ($statut === 'Inscription') {
        $tarifTable = 'frais_d_inscription';
    } else {
        $tarifTable = 'autres_frais';
    }

    // 2. Récupérer le montant fixé pour la classe de l'élève
    // On fait la jointure par s.class_id = t.classe (ID de classe) 
    // OU par correspondance niveau/section/option/classe
    $sql = "
        SELECT t.montant 
        FROM students s
        JOIN classes c ON s.class_id = c.id
        JOIN {$tarifTable} t 
          ON t.code_ecole = s.code_ecole
         AND (
             t.classe = c.id 
             OR (
                 t.niveau  = c.niveau 
                 AND t.section = IFNULL(c.section, 0) 
                 AND t.OPTION  = IFNULL(c.options, 0)
             )
         )
        WHERE s.id = ? 
          AND s.code_ecole = ?
        LIMIT 1
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$eleveId, $codeEcole]);
    $montantTotal = $stmt->fetchColumn();

    // Si aucune correspondance stricte par ID, fallback sur niveau/section/option
    if ($montantTotal === false) {
        $sqlFallback = "
            SELECT t.montant 
            FROM students s
            JOIN classes c ON s.class_id = c.id
            JOIN {$tarifTable} t 
              ON t.code_ecole = s.code_ecole
             AND t.niveau  = c.niveau 
             AND t.section = IFNULL(c.section, 0) 
             AND t.OPTION  = IFNULL(c.options, 0)
            WHERE s.id = ? 
              AND s.code_ecole = ?
            LIMIT 1
        ";
        $stmtFallback = $pdo->prepare($sqlFallback);
        $stmtFallback->execute([$eleveId, $codeEcole]);
        $montantTotal = $stmtFallback->fetchColumn();
    }

    $response["montant_total"] = (float)($montantTotal ?: 0);

    // 3. Récupérer le cumul déjà payé par cet élève pour ce statut
    $stmtPaye = $pdo->prepare("
        SELECT IFNULL(SUM(montant_paye), 0)
        FROM paiement
        WHERE eleve = ? 
          AND statut = ? 
          AND code_ecole = ?
    ");
    $stmtPaye->execute([$eleveId, $statut, $codeEcole]);
    $response["total_paye"] = (float)$stmtPaye->fetchColumn();

    // 4. Calcul du solde restant
    $solde = $response["montant_total"] - $response["total_paye"];
    $response["solde_restant"] = $solde > 0 ? $solde : 0;

} catch (PDOException $e) {
    $response['error'] = $e->getMessage();
}

echo json_encode($response);