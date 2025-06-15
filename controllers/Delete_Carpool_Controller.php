<?php
require_once __DIR__ . '/../vendor/autoload.php';         // charge PHPMailer et vos classes
require_once __DIR__ . '/../models/ModelUpdateCarpool.php';
require_once __DIR__ . '/../models/ModelPayment.php';
require_once __DIR__ . '/UpdateCarpool_Controller.php';
require_once __DIR__ . '/Payment_Controller.php';

use App\Controllers\MailController; 




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
        MailController::sendCancellationEmail(
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