
<?php

// UpdateStatutCarpool.php créé la route AJAX pour changer le statut d'un covoiturage
require_once '../controllers/UpdateCarpool_Controller.php';
require_once '../models/ModelUpdateCarpool.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_covoiturage'] ?? null;
    $etat = $_POST['nouvel_etat'] ?? null;

    if ($id && $etat) {
        $controller = new UpdateCarpool_Controller(new ModelUpdateCarpool());
        $success = $controller->changerEtatCovoiturage($id, $etat);
        echo $success ? "ok" : "erreur";
    } else {
        echo "données manquantes";
    }
}