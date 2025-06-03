<?php
class MailController {
    public static function sendReviewInvitation($email, $prenom, $nom, $id_covoiturage) {
        $to = $email;
        $subject = "Laissez votre avis sur votre covoiturage";
        $message = "
            Bonjour $prenom $nom,<br><br>
            Votre covoiturage vient de se terminer.<br>
            Nous aimerions connaître votre avis sur cette expérience.<br><br>
            <a href='http://127.0.0.1/Ecoride/views/User_space.php'>Laissez un avis</a><br><br>
            Merci et à bientôt sur EcoRide !
        ";
        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: contact@ecoride.com\r\n";

        return mail($to, $subject, $message, $headers);
    }

    public static function sendContactEmail($email, $name, $prenom, $Message) {
        $to = "guill.job@hotmail.fr"; // Adresse de destination à modifier en fonction
        $subject = "Nouveau message de contact";
        $body = "
            <p>Vous avez reçu un nouveau message via le formulaire de contact :</p>
            <p><strong>Coorodonnées :</strong> $name $prenom $email</p>
            <p><strong>Message :</strong> $Message</p>";

        $headers = "MIME-Version: 1.0\r\n";
        $headers .= "Content-type: text/html; charset=UTF-8\r\n";
        $headers .= "From: $email\r\n";

        return mail($to, $subject, $body, $headers);
    }
}
?>