<?php
// controllers/UpdateCarpool_Controller.php

// 1) On charge l’autoloader Composer (qui connait App\Controllers\MailController)
require_once __DIR__ . '/../vendor/autoload.php';

use App\Controllers\MailController;       // 2) On importe la classe
require_once __DIR__ . '/../models/ModelUpdateCarpool.php';

class UpdateCarpool_Controller  {
    private $model;

    public function __construct(ModelUpdateCarpool $model) {
        $this->model = $model;
    }

    public function changerEtatCovoiturage($idCovoiturage, $nouvelEtat) {
    $success = $this->model->updateEtatCovoiturage($idCovoiturage, $nouvelEtat);

    if ($success && $nouvelEtat === 'terminé') {
        // Récupère les passagers qui n'ont pas encore reçu de mail
        $participants = $this->model->getParticipantsNonNotifies($idCovoiturage);

        foreach ($participants as $participant) {
            $email = $participant['email'];
            $prenom = $participant['prenom'];
            $nom = $participant['nom'];
            $id_utilisateur = $participant['utilisateur_id'];

            // Envoi du mail
            if (MailController::sendReviewInvitation($email, $prenom, $nom, $idCovoiturage) ) {  /// Ajouter la condition pour dire seulement quand on a la table avis_en_cours est remplie, on lance la fonction marquerAvisEnvoye
                // Marquer comme envoyé
                $this->model->marquerAvisEnvoye($id_utilisateur, $idCovoiturage);
            }
        }
    }

    return $success;
    }

    public function supprimerCovoiturage($idCovoiturage) {
    return $this->model->deleteCarpool($idCovoiturage);
}
    

}