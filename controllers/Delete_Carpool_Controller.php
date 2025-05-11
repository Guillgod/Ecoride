<?php
require_once '../models/ModelUpdateCarpool.php';
require_once 'UpdateCarpool_Controller.php';
require_once '../models/ModelPayment.php';
require_once 'Payment_Controller.php';

function envoyerEmailAnnulation($email, $prenom, $lieu_depart, $lieu_arrivee, $date_depart, $heure_depart) {
    $sujet = "Annulation du covoiturage";
    $message = "Bonjour $prenom,\n\n";
    $message .= "Nous vous informons que le covoiturage prévu de $lieu_depart à $lieu_arrivee le $date_depart à $heure_depart a été annulé par le chauffeur.\n";
    $message .= "Un remboursement de votre paiement a été effectué sur votre compte.\n\n";
    $message .= "Merci de votre compréhension.\n";
    $message .= "L'équipe Ecoride";

    $headers = "From: contact@ecoride.com" . "\r\n" .
               "Reply-To: contact@ecoride.com" . "\r\n" .
               "Content-Type: text/plain; charset=UTF-8";

    mail($email, $sujet, $message, $headers);
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_covoiturage'])) {
    $id = $_POST['id_covoiturage'];

    $model = new ModelUpdateCarpool();  // Pas besoin d'instancier le contrôleur ici
    $modelpayment = new ModelPayment();   
    $modelPaymentController = new Payment_Controller($modelpayment);
    $passagers = $model->getAllPassengersByCarpoolId($id);
    $covoiturage = $model->getCarpoolDetailsById($id);  // Tu dois créer cette méthode dans le modèle
    $prix_personne = $model->getPrixPersonneByCovoiturageId($id);
    


    // Appel directement à la méthode deleteCarpool du modèle
    if ($model->deleteCarpool($id)) {
    foreach ($passagers as $passager) {
        $modelPaymentController->increaseCreditPassenger($passager['utilisateur_id'], $prix_personne);
        envoyerEmailAnnulation(
            $passager['email'],
            $passager['prenom'],
            $covoiturage['lieu_depart'],
            $covoiturage['lieu_arrivee'],
            $covoiturage['date_depart'],
            $covoiturage['heure_depart']
        );
    }
    echo "ok";
}



}
?>