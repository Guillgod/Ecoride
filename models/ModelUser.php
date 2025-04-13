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
        $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}