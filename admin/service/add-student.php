<?php
require '../../database/db_connect.php';
require 'user_connecter.php';
require '../../service/security_helpers.php';

$code_ecole = $_SESSION['code_ecole'] ?? null;

csrf_protect();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    try {
        if ($_SERVER['CONTENT_LENGTH'] > 50 * 1024 * 1024) {
            die("Fichier trop volumineux !");
        }
        // 🔐 START TRANSACTION
        $pdo->beginTransaction();

        // 📥 Données
        $first_name         = $_POST['first_name'];
        $last_name          = $_POST['last_name'];
        $username           = $_POST['username'];
        $gender             = $_POST['gender'];
        $dob_input          = $_POST['date_of_birth'];
        $dob                = date('Y-m-d', strtotime(str_replace('/', '-', $dob_input)));
        $email              = $_POST['email'];
        $phone              = $_POST['phone'];
        $classe             = $_POST['classe'];
        $father             = $_POST['father'];
        $mother             = $_POST['mother'];
        $phone_responsable  = $_POST['phone_responsable'];
        $email_responsable  = $_POST['email_responsable'];
        $password_plain     = $_POST['password'];
        $password           = password_hash($password_plain, PASSWORD_DEFAULT);
        $statut             = 'valide';
        $ecole_provenance   = $_POST['ecole_provenance'];

        // 🔒 Vérifier classe appartient à l’école
        $chk = $pdo->prepare("SELECT COUNT(*) FROM classes WHERE id = ? AND code_ecole = ?");
        $chk->execute([$classe, $code_ecole]);

        if ((int)$chk->fetchColumn() === 0) {
            throw new Exception("Classe invalide.");
        }

        // 🚫 Vérifier doublon username/email dans users
        $checkUser = $pdo->prepare("SELECT COUNT(*) FROM users WHERE username = ? OR email = ?");
        $checkUser->execute([$username, $email]);

        if ($checkUser->fetchColumn() > 0) {
            throw new Exception("Username ou email déjà utilisé.");
        }

        // =========================
        // ✅ INSERT STUDENT
        // =========================
        $sqlStudent = "INSERT INTO students (
            first_name, last_name, username, gender, date_of_birth,
            email, phone, class_id, PASSWORD, father, mother,
            phone_responsable, email_responsable, code_ecole,
            ecole_provenance, statut
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmtStudent = $pdo->prepare($sqlStudent);
        $stmtStudent->execute([
            $first_name, $last_name, $username, $gender, $dob,
            $email, $phone, $classe, $password, $father, $mother,
            $phone_responsable, $email_responsable, $code_ecole,
            $ecole_provenance, $statut
        ]);

        // =========================
        // ✅ INSERT USER (LOGIN)
        // =========================
        $sqlUser = "INSERT INTO users (
            username, PASSWORD, email, role,
            first_name, last_name, phone, code_ecole
        ) VALUES (?, ?, ?, 'eleve', ?, ?, ?, ?)";

        $stmtUser = $pdo->prepare($sqlUser);
        $stmtUser->execute([
            $username,
            $password,
            $email,
            $first_name,
            $last_name,
            $phone,
            $code_ecole
        ]);

        // ✅ VALIDATION
        $pdo->commit();

        header('Location: ../all-students.php?msg=created');
        exit;

    } catch (Exception $e) {

        // ❌ rollback si erreur
        $pdo->rollBack();

        echo "Erreur : " . $e->getMessage();
    }
}
?>