<?php

require_once 'Database.php';

class ModelUpdateCarpool {
    private $db;

    public function __construct() {
        $this->db = Database::connect();
    }

    public function updateEtatCovoiturage($covoiturage_id, $etat) {
        $stmt = $this->db->prepare("UPDATE covoiturage SET statut = :etat WHERE covoiturage_id = :id");
        $success = $stmt->execute([
    ':etat' => $etat,
    ':id' => $covoiturage_id
    ]);

        // Vérification de l'erreur

    if (!$success) {
        $errorInfo = $stmt->errorInfo();
        error_log("Erreur SQL : " . print_r($errorInfo, true));
    }

    return $success;
    }

    // Fonction pour supprimer un covoiturage après que le chauffeur clique sur "Annuler"
    public function deleteCarpool($covoiturage_id) {
    // Démarrer une transaction pour garantir l'intégrité des données
    $this->db->beginTransaction();
    
    try {
        // Supprimer la ligne correspondante dans la table voiture_utilise_covoiturage
        $stmt1 = $this->db->prepare("DELETE FROM voiture_utilise_covoiturage WHERE id_covoiturage_utilise_voiture = :id");
        $stmt1->bindValue(':id', $covoiturage_id);
        $stmt1->execute();
        
        // Supprimer le covoiturage dans la table covoiturage
        $stmt2 = $this->db->prepare("DELETE FROM covoiturage WHERE covoiturage_id = :id");
        $stmt2->bindValue(':id', $covoiturage_id);
        $stmt2->execute();
        
        // Commit de la transaction si tout se passe bien
        $this->db->commit();
        return true;
    } catch (Exception $e) {
        // En cas d'erreur, rollback de la transaction
        $this->db->rollBack();
        error_log("Erreur lors de la suppression du covoiturage et de la ligne associée : " . $e->getMessage());
        return false;
    }
}

//Commit ou Rollback : Si toutes les suppressions réussissent, la transaction est validée (commit). Si une erreur survient pendant l'exécution de l'une des requêtes, la transaction est annulée (rollback), ce qui garantit qu'aucune donnée partielle ne soit enregistrée.
public function getPrixPersonneByCovoiturageId($id_covoiturage) {
    $stmt = $this->db->prepare("SELECT prix_personne FROM covoiturage WHERE covoiturage_id = :id");
    $stmt->bindValue(':id', $id_covoiturage);
    $stmt->execute();
    return $stmt->fetchColumn(); // retourne directement le prix
}

public function getAllPassengersByCarpoolId($id_covoiturage) {
    $requete = "
        SELECT 
            utilisateur.utilisateur_id, 
            utilisateur.prenom, 
            utilisateur.email 
        FROM utilisateur_participe_covoiturage
        JOIN utilisateur 
            ON utilisateur_participe_covoiturage.id_utilisateur = utilisateur.utilisateur_id
        WHERE utilisateur_participe_covoiturage.id_covoiturage = :id_covoiturage
    ";

    $statement = $this->db->prepare($requete);
    $statement->bindParam(':id_covoiturage', $id_covoiturage, PDO::PARAM_INT);
    $statement->execute();
    
    return $statement->fetchAll(PDO::FETCH_ASSOC);
}





// public function getAllPassengersByCarpoolId($id_covoiturage) {
//     $stmt = $this->db->prepare("SELECT id_utilisateur FROM utilisateur_participe_covoiturage 
//     JOIN utilisateur ON utilisateur_participe_covoiturage.id_utilisateur = utilisateur.utilisateur_id
//     WHERE id_covoiturage = :id_covoiturage");
//     $stmt->bindParam(':id_covoiturage', $id_covoiturage, PDO::PARAM_INT);
//     $stmt->execute();
//     return $stmt->fetchAll(PDO::FETCH_ASSOC);
// }

public function getCarpoolDetailsById($id_covoiturage) {
    $sql = "SELECT lieu_depart, lieu_arrivee, date_depart, heure_depart FROM covoiturage WHERE covoiturage_id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id_covoiturage, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// récupère les informations des passagers qui n'ont pas encore envoyé d'avis
public function getParticipantsNonNotifies($id_covoiturage) {
    $sql = "
        SELECT utilisateur.utilisateur_id, utilisateur.prenom, utilisateur.nom, utilisateur.email 
        FROM utilisateur
        JOIN utilisateur_participe_covoiturage ON utilisateur.utilisateur_id = id_utilisateur
        WHERE utilisateur_participe_covoiturage.id_covoiturage = :id AND utilisateur_participe_covoiturage.avis_envoye = FALSE  
    ";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':id', $id_covoiturage);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
// Visn tagguer si un avis a déjà été envoyé
public function marquerAvisEnvoye($id_utilisateur, $id_covoiturage) {
    $sql = "
        UPDATE utilisateur_participe_covoiturage
        SET avis_envoye = FALSE   
        WHERE id_utilisateur = :id_utilisateur AND id_covoiturage = :id_covoiturage
    "; //Vérifier que le FASLE fonctionne bien en laissant s'afficher les avis ---------------------------------------------------------------------------
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':id_utilisateur', $id_utilisateur);
    $stmt->bindValue(':id_covoiturage', $id_covoiturage);
    
    $stmt->execute();
}

}