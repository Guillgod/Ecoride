<?php

require_once 'Database.php';

class ModelUpdateCarpool {
    private $db;

    public function __construct() {
        $this->db = Database::getConnection();
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


}