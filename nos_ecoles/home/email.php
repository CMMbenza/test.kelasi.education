<?php
declare(strict_types=1);

/**
 * CONFIG EMAIL
 */
$MAIL_FROM      = 'no-reply@kelasi.education';
$MAIL_FROM_NAME = 'Kelasi';
$MAIL_REPLY_TO  = 'contact@kelasi.education';

/**
 * URL LOGIN (auto)
 */
if (!function_exists('kelasi_login_url')) {
    function kelasi_login_url(): string {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme.'://'.$host.'/mykelasi/login';
    }
}

/**
 * CORE MAIL SENDER (HTML)
 */
if (!function_exists('mail_html')) {
    function mail_html(string $to, string $subject, string $html): bool {

        $from     = $GLOBALS['MAIL_FROM'];
        $fromName = $GLOBALS['MAIL_FROM_NAME'];
        $replyTo  = $GLOBALS['MAIL_REPLY_TO'];

        $headers  = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= 'From: =?UTF-8?B?'.base64_encode($fromName)."?= <{$from}>\r\n";
        $headers .= "Reply-To: {$replyTo}\r\n";
        $headers .= "X-Mailer: PHP/".phpversion()."\r\n";

        return @mail(
            $to,
            '=?UTF-8?B?'.base64_encode($subject).'?=',
            $html,
            $headers,
            "-f ".$from
        );
    }
}

/**
 * ✅ ENVOI IDENTIFIANTS ÉLÈVE (USER + PASSWORD)
 * (C'est celle que tu utilises dans inscription_eleve.php)
 */
if (!function_exists('send_student_credentials')) {
    function send_student_credentials(string $to, array $data): bool {

        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) return false;

        $subject = "Vos accès MyKelasi - Compte élève";

        $loginUrl = $data['login_url'] ?? kelasi_login_url();

        $first = htmlspecialchars($data['first'] ?? '', ENT_QUOTES, 'UTF-8');
        $last  = htmlspecialchars($data['last'] ?? '', ENT_QUOTES, 'UTF-8');
        $user  = htmlspecialchars($data['username'] ?? '', ENT_QUOTES, 'UTF-8');
        $pass  = htmlspecialchars($data['password'] ?? '', ENT_QUOTES, 'UTF-8');
        $ecole = htmlspecialchars($data['ecole_name'] ?? '', ENT_QUOTES, 'UTF-8');

        $html = "
        <div style='font-family:Arial;padding:15px;color:#333'>
            <h2>Bienvenue {$first} {$last}</h2>

            <p>Votre compte élève a été créé sur <strong>{$ecole}</strong>.</p>

            <p><strong>Identifiants de connexion :</strong></p>

            <ul>
                <li><b>Nom d'utilisateur :</b> {$user}</li>
                <li><b>Mot de passe :</b> {$pass}</li>
            </ul>

            <p>
                <a href='{$loginUrl}' style='display:inline-block;padding:10px 15px;background:#0d6efd;color:#fff;text-decoration:none;border-radius:5px'>
                    Se connecter
                </a>
            </p>

            <p style='color:#888;font-size:12px'>
                Changez votre mot de passe après connexion.
            </p>
        </div>";

        return mail_html($to, $subject, $html);
    }
}

/**
 * ✅ NOTIFICATION ADMIN (corrigée)
 */
if (!function_exists('notify_admins_new_student')) {
    function notify_admins_new_student(PDO $pdo, string $code_ecole, array $data): int {

        $subject = "Nouvelle inscription élève - ".$data['ecole_name'];

        $html = "
        <div style='font-family:Arial'>
            <h3>Nouvelle inscription</h3>
            <p>École : <b>{$data['ecole_name']}</b></p>

            <ul>
                <li>Élève : {$data['first']} {$data['last']}</li>
                <li>Email : {$data['email']}</li>
                <li>Téléphone : {$data['phone']}</li>
                <li>Classe ID : {$data['class_id']}</li>
            </ul>
        </div>";

        // récupérer admins
        $stmt = $pdo->prepare("
            SELECT email
            FROM users
            WHERE code_ecole = :ec
            AND role IN ('admin','administrateur','promoteur')
        ");

        $stmt->execute([':ec' => $code_ecole]);
        $admins = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $count = 0;

        foreach ($admins as $a) {
            $email = trim($a['email']);

            if ($email && filter_var($email, FILTER_VALIDATE_EMAIL)) {
                if (mail_html($email, $subject, $html)) {
                    $count++;
                }
            }
        }

        return $count;
    }
}