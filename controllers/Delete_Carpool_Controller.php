<?php
require_once '../models/ModelUpdateCarpool.php';
require_once 'UpdateCarpool_Controller.php';
require_once '../models/ModelPayment.php';
require_once 'Payment_Controller.php';

 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_covoiturage'])) {
    $id = $_POST['id_covoiturage'];

    $model = new ModelUpdateCarpool();  // Pas besoin d'instancier le contrôleur ici
    $modelpayment = new ModelPayment();   
    $modelPaymentController = new Payment_Controller($modelpayment);
    $passagers = $model->getAllPassengersByCarpoolId($id);
    $prix_personne = $model->getPrixPersonneByCovoiturageId($id);
    


    // Appel directement à la méthode deleteCarpool du modèle
    if ($model->deleteCarpool($id)) {
        foreach ($passagers as $passager) {
            $modelPaymentController->increaseCreditPassenger($passager['id_utilisateur'], $prix_personne);
        }
        
        echo "ok";
    } else {
        
        echo "erreur";
    }
}
?>