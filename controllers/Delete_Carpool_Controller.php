<?php
require_once '../models/ModelUpdateCarpool.php';
require_once 'UpdateCarpool_Controller.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_covoiturage'])) {
    $id = $_POST['id_covoiturage'];

    $model = new ModelUpdateCarpool();  // Pas besoin d'instancier le contrôleur ici
    
    // Appel directement à la méthode deleteCarpool du modèle
    if ($model->deleteCarpool($id)) {  // Utilisez deleteCarpool ici
        echo "ok";
    } else {
        echo "erreur";
    }
}
?>