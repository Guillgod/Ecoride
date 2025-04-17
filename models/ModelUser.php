<?php
//Modèle de connexion d'utilisateur
class ModelUser {
    private PDO $db;

    
    public function __construct() {
        $this->db = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');
    }
    
    public function  authenticate($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if($user && ($user['password'] === $password)) {
            return $user;
        }
        return false;
    }

//Retourne tous les information de l'utilisateur (Ajouter les voitures liées à l'utilisateur)
    public function getUserInformation($email) {
        $stmt = $this->db->prepare("SELECT * FROM voiture 
        LEFT JOIN utilisateur ON voiture.gere = utilisateur.utilisateur_id
        LEFT JOIN covoiturage ON voiture.utilise = covoiturage.covoiturage_id
        LEFT JOIN utilisateur_participe_covoiturage ON utilisateur.utilisateur_id = utilisateur_participe_covoiturage.id_utilisateur
        WHERE email = :email ");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserInformationWithoutCar($email) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getPassengerCovoiturages($userId) {
        $stmt = $this->db->prepare("
            SELECT covoiturage.*
            FROM utilisateur_participe_covoiturage
            JOIN covoiturage ON utilisateur_participe_covoiturage.id_covoiturage = covoiturage.covoiturage_id
            WHERE utilisateur_participe_covoiturage.id_utilisateur = :userId");
        $stmt->bindValue(':userId', $userId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}