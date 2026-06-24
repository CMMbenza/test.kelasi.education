<?php
// email.php — utilitaires d'envoi d'e-mails HTML (mail() natif)
declare(strict_types=1);

$MAIL_FROM      = 'no-reply@kelasi.education';
$MAIL_FROM_NAME = 'Kelasi';
$MAIL_REPLY_TO  = 'contact@kelasi.education';

// URL de connexion (adapte le chemin si besoin)
if (!function_exists('kelasi_login_url')) {
    function kelasi_login_url(): string {
        $scheme = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
        $host   = $_SERVER['HTTP_HOST'] ?? 'localhost';
        return $scheme.'://'.$host.'/mykelasi/login';
    }
}

if (!function_exists('mail_html')) {
    function mail_html(
    string $to,
    string $subject,
    string $html,
    string $from,
    string $fromName,
    ?string $replyTo = null
): bool {

    $headers  = "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    $headers .= "From: ".$fromName." <".$from.">\r\n";

    if (!empty($replyTo)) {
        $headers .= "Reply-To: ".$replyTo."\r\n";
    }

    $headers .= "X-Mailer: PHP/".phpversion()."\r\n";

    return mail(
        $to,
        '=?UTF-8?B?'.base64_encode($subject).'?=',
        $html,
        $headers,
        "-f ".$from
    );
}
}

if (!function_exists('send_student_credentials')) {
    /**
     * Envoie l'email d'accueil à l'élève (identifiants).
     * $data: ['first','last','username','password','code_ecole','ecole_name','login_url']
     */
    function send_student_credentials(string $to, array $data): bool {
        if ($to === '' || !filter_var($to, FILTER_VALIDATE_EMAIL)) return false;
        $from      = $GLOBALS['MAIL_FROM'];
        $fromName  = $GLOBALS['MAIL_FROM_NAME'];
        $replyTo   = $GLOBALS['MAIL_REPLY_TO'];

        $subject = "Bienvenue sur {$data['ecole_name']} - Vos accès";
        $esc = fn($s)=>htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');
        $html = '<div style="font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#333">
                    <p>Bonjour '.$esc($data['first'].' '.$data['last']).',</p>
                    <p>Votre inscription à <strong>'.$esc($data['ecole_name']).'</strong> a bien été enregistrée.</p>
                    <p>Voici vos identifiants de connexion :</p>
                    <ul>
                      <li><strong>Nom d’utilisateur</strong> : '.$esc($data['username']).'</li>
                      <li><strong>Mot de passe</strong> : '.$esc($data['password']).'</li>
                    </ul>
                    <p>Code école : <strong>'.$esc($data['code_ecole']).'</strong></p>
                    <p>Connectez-vous ici : <a href="'.$esc($data['login_url']).'">'.$esc($data['login_url']).'</a></p>
                    <p style="color:#888">Par sécurité, modifiez votre mot de passe après la première connexion.</p>
                    <hr>
                    <p style="font-size:12px;color:#666">Ceci est un message automatique. Merci de ne pas y répondre.</p>
                 </div>';

        return mail_html($to, $subject, $html, $from, $fromName, $replyTo);
    }
}

if (!function_exists('notify_admins_new_student')) {
    /**
     * Notifie tous les admins/promoteurs de l'école (code_ecole) d'une nouvelle inscription.
     * $data: [
     *   'first','last','email','phone','father','mother','email_resp','phone_resp','class_id',
     *   'username','password','ecole_name','code_ecole'
     * ]
     */
    function notify_admins_new_student(PDO $pdo, string $code_ecole, array $data): int {

    $from      = $GLOBALS['MAIL_FROM'];
    $fromName  = $GLOBALS['MAIL_FROM_NAME'];
    $replyTo   = $GLOBALS['MAIL_REPLY_TO'];

    $esc = fn($s) => htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');

    $subject = "Nouvelle inscription élève - ".$data['ecole_name'];

    $html = '
    <div style="font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#333">
        <h2>Nouvelle inscription</h2>

        <p>Une nouvelle inscription a été effectuée dans <b>'.$esc($data['ecole_name']).'</b>.</p>

        <table cellpadding="6" cellspacing="0" border="1" style="border-collapse:collapse;width:100%;">
            <tr>
                <td><b>Élève</b></td>
                <td>'.$esc($data['first'].' '.$data['last']).'</td>
            </tr>

            <tr>
                <td><b>Email élève</b></td>
                <td>'.$esc($data['email']).'</td>
            </tr>

            <tr>
                <td><b>Téléphone</b></td>
                <td>'.$esc($data['phone']).'</td>
            </tr>

            <tr>
                <td><b>Responsable</b></td>
                <td>'.$esc($data['father']).' '.$esc($data['mother']).'</td>
            </tr>

            <tr>
                <td><b>Email responsable</b></td>
                <td>'.$esc($data['email_resp']).'</td>
            </tr>

            <tr>
                <td><b>Téléphone responsable</b></td>
                <td>'.$esc($data['phone_resp']).'</td>
            </tr>

            <tr>
                <td><b>Username</b></td>
                <td>'.$esc($data['username']).'</td>
            </tr>

            <tr>
                <td><b>Mot de passe</b></td>
                <td>'.$esc($data['password']).'</td>
            </tr>

            <tr>
                <td><b>Code école</b></td>
                <td>'.$esc($data['code_ecole']).'</td>
            </tr>
        </table>

        <br>

        <p>
            <a href="'.kelasi_login_url().'"
            style="background:#0d6efd;color:#fff;padding:10px 18px;text-decoration:none;border-radius:5px;">
                Accéder à MyKelasi
            </a>
        </p>

    </div>';

    $stmt = $pdo->prepare("
        SELECT email
        FROM users
        WHERE code_ecole = :ec
        AND role IN ('admin','administrateur','promoteur')
        AND email IS NOT NULL
        AND email <> ''
    ");

    $stmt->execute([
        ':ec' => $code_ecole
    ]);

    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    $sent = 0;

    foreach ($rows as $row) {

        $to = trim($row['email']);

        if (!filter_var($to, FILTER_VALIDATE_EMAIL)) {
            continue;
        }

        $ok = mail_html(
            $to,
            $subject,
            $html,
            $from,
            $fromName,
            $replyTo
        );

        error_log("Notification admin ".$to." => ".($ok ? "OK" : "ECHEC"));

        if ($ok) {
            $sent++;
        }
    }

    return $sent;
}
}