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
    function mail_html(string $to, string $subject, string $html, string $from, string $fromName, ?string $replyTo=null): bool {
        $headers = [];
        $headers[] = 'MIME-Version: 1.0';
        $headers[] = 'Content-type: text/html; charset=UTF-8';
        $headers[] = 'From: '.sprintf('"%s" <%s>', '=?UTF-8?B?'.base64_encode($fromName).'?=', $from);
        if ($replyTo) $headers[] = 'Reply-To: '.$replyTo;
        $headers[] = 'X-Mailer: PHP/'.phpversion();
        return @mail($to, '=?UTF-8?B?'.base64_encode($subject).'?=', $html, implode("\r\n",$headers));
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
        $esc = fn($s)=>htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8');

        $subject = "Nouvelle inscription élève - ".$data['ecole_name'];
        $html = '<div style="font-family:Arial,Helvetica,sans-serif;font-size:15px;color:#333">
                    <p>Bonjour,</p>
                    <p>Une <strong>nouvelle inscription</strong> a été soumise pour l’école <strong>'.$esc($data['ecole_name']).'</strong> (code école <strong>'.$esc($data['code_ecole']).'</strong>).</p>
                    <ul>
                      <li>Élève : <strong>'.$esc($data['first'].' '.$data['last']).'</strong></li>
                      <li>Email élève : '.($data['email']!==''?$esc($data['email']):'<em>Non fourni</em>').'</li>
                      <li>Téléphone élève : '.($data['phone']!==''?$esc($data['phone']):'<em>Non fourni</em>').'</li>
                      <li>Responsable : '.$esc($data['father']).' / '.$esc($data['mother']).'</li>
                      <li>Email resp. : '.$esc($data['email_resp']).'</li>
                      <li>Téléphone resp. : '.$esc($data['phone_resp']).'</li>
                      <li>Classe ID : '.($data['class_id']!==null?(int)$data['class_id']:'<em>Non renseigné</em>').'</li>
                    </ul>
                    <p>Identifiants générés pour l’élève :</p>
                    <ul>
                      <li>Username : <strong>'.$esc($data['username']).'</strong></li>
                      <li>Password (temporaire) : <strong>'.$esc($data['password']).'</strong></li>
                    </ul>
                    <hr>
                    <p style="font-size:12px;color:#666">Notification automatique MyKelasi.</p>
                 </div>';

        $sent = 0;
        $stmt = $pdo->prepare("SELECT email FROM users
                               WHERE code_ecole=:ec AND role IN ('admin','administrateur','promoteur')
                                     AND email IS NOT NULL AND email<>''");
        $stmt->execute([':ec'=>$code_ecole]);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];
        foreach ($rows as $r) {
            $to = $r['email'];
            if ($to && filter_var($to, FILTER_VALIDATE_EMAIL)) {
                if (mail_html($to, $subject, $html, $from, $fromName, $replyTo)) { $sent++; }
            }
        }
        return $sent;
    }
}
