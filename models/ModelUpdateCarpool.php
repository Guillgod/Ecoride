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
}