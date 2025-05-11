<?php
require_once '../models/ModelUser.php';
require_once '../models/ModelPayment.php';

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_covoiturage']) && isset($_POST['id_passager'])) {
    $idCovoiturage = $_POST['id_covoiturage'];
    $idPassager = $_POST['id_passager'];

    $modelUser = new ModelUser();
    $modelPayment = new ModelPayment();

    // 1. Obtenir le prix à rembourser
    $prix = $modelPayment->getPrixPersonneFromCarpool($idCovoiturage);
    if (!$prix) {
        echo "Erreur : prix introuvable";
        exit;
    }

    // 2. Incrémenter le nombre de places
    $modelUser->incrementPlaceDisponible($idCovoiturage);

    // 3. Rembourser le passager
    $modelPayment->refundPassengerCredit($idPassager, $prix);

    // 4. Supprimer la ligne de paiement
    $modelPayment->deletePassengerPayment($idPassager, $idCovoiturage);

    // 5. Supprimer la participation
    $modelUser->deletePassengerParticipation($idPassager, $idCovoiturage);

    echo "ok";
} else {
    echo "Données invalides";
}
?>