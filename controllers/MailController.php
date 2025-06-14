<?php
namespace App\Controllers;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

class MailController {
    /**
     * Configure et retourne une instance PHPMailer prête à envoyer via Gmail SMTP.
     */
    private static function getMailer(): PHPMailer {
    $mail = new PHPMailer(true);
    // SMTP…
    $mail->isSMTP();
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = getenv('GMAIL_USER');
    $mail->Password   = getenv('GMAIL_PASS');
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;
    $mail->CharSet    = 'UTF-8';

    // — Fallback sur GMAIL_FROM si non défini —
    $from = getenv('GMAIL_FROM') ?: 'EcoRide <contact@ecoride.com>';
    if (preg_match('/^(.+)<(.+)>$/', $from, $m)) {
        $name  = trim($m[1]);
        $email = trim($m[2]);
    } else {
        $name  = 'EcoRide';
        $email = $from;
    }
    $mail->setFrom($email, $name);

    return $mail;
}

    /**
     * Envoie une invitation à laisser un avis après covoiturage.
     */
    public static function sendReviewInvitation(string $email, string $prenom, string $nom, int $id_covoiturage): bool {
        $mail = self::getMailer();
        try {
            $mail->addAddress($email);
            $mail->Subject = "Laissez votre avis sur votre covoiturage";
            $mail->isHTML(true);

            // Génération du lien dynamique en production
            $host = $_SERVER['HTTP_HOST'];
            $url  = "https://{$host}/views/User_space.php?covoiturage={$id_covoiturage}";

            $mail->Body = "
                <p>Bonjour {$prenom} {$nom},</p>
                <p>Votre covoiturage vient de se terminer.<br>
                Nous aimerions connaître votre avis sur cette expérience.</p>
                <p><a href=\"{$url}\">Laissez un avis</a></p>
                <p>Merci et à bientôt sur EcoRide !</p>
            ";

            return (bool)$mail->send();
        } catch (Exception $e) {
            error_log("PHPMailer (invitation) error: {$mail->ErrorInfo}");
            return false;
        }
    }

    /**
     * Envoie un e-mail depuis le formulaire de contact.
     */
    public static function sendContactEmail(string $email, string $name, string $prenom, string $Message): bool {
        $mail = self::getMailer();
        try {
            $mail->addAddress('guill.job@hotmail.fr', 'Équipe EcoRide');
            $mail->Subject = "Nouveau message de contact";
            $mail->isHTML(true);

            // Sécurisation du contenu
            $safeMsg = nl2br(htmlspecialchars($Message, ENT_QUOTES, 'UTF-8'));

            $mail->Body = "
                <p>Vous avez reçu un nouveau message via le formulaire de contact :</p>
                <p>
                  <strong>Nom :</strong> {$name} {$prenom}<br>
                  <strong>Email :</strong> {$email}
                </p>
                <p><strong>Message :</strong><br>{$safeMsg}</p>
            ";

            return (bool)$mail->send();
        } catch (Exception $e) {
            error_log("PHPMailer (contact) error: {$mail->ErrorInfo}");
            return false;
        }
    }
}