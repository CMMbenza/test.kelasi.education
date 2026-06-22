<?php
require_once __DIR__.'/../../database/db_connect.php';

$statut = $_GET['statut'] ?? '';
$eleve  = (int)($_GET['eleve'] ?? 0);

$response = [
    "montant_total" => 0,
    "total_paye" => 0,
    "solde_restant" => 0
];

// 1. récupérer montant fixé selon type
if ($statut === "Inscription") {
    $stmt = $pdo->prepare("SELECT montant FROM frais_d_inscription LIMIT 1");
}
elseif ($statut === "Minerval") {
    $stmt = $pdo->prepare("SELECT montant FROM minerval LIMIT 1");
}
else {
    $stmt = $pdo->prepare("SELECT montant FROM autres_frais LIMIT 1");
}

$stmt->execute();
$response["montant_total"] = (float)$stmt->fetchColumn();

// 2. total déjà payé
$stmt = $pdo->prepare("
    SELECT SUM(montant_paye)
    FROM paiement
    WHERE eleve = ? AND statut = ?
");
$stmt->execute([$eleve, $statut]);
$response["total_paye"] = (float)$stmt->fetchColumn();

// 3. calcul solde restant
$response["solde_restant"] =
    $response["montant_total"] - $response["total_paye"];

echo json_encode($response);