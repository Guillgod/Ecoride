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
        $stmt = $this->db->prepare("SELECT * FROM utilisateur 
        LEFT JOIN utilisateur_possede_voiture ON utilisateur_possede_voiture.id_utilisateur_possede_voiture = utilisateur.utilisateur_id
        LEFT JOIN voiture ON utilisateur_possede_voiture.id_voiture_possede_utilisateur = voiture.voiture_id
        LEFT JOIN covoiturage ON voiture.utilise = covoiturage.covoiturage_id
        LEFT JOIN utilisateur_participe_covoiturage ON utilisateur.utilisateur_id = utilisateur_participe_covoiturage.id_utilisateur
        WHERE email = :email ");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getUserInformationWithoutCar($email) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE email = :email");
        // -- LEFT JOIN utilisateur_possede_voiture ON id_voiture_possede_voiture = voiture.voiture_id");
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

    public function updateUser($pseudo, $nom, $prenom, $email, $telephone, $adresse, $date_naissance, $photo, $role, $id) {
        // Requête de base
        $sql = "
            UPDATE utilisateur SET 
            pseudo = :pseudo,
            nom = :nom,
            prenom = :prenom,
            email = :email,
            telephone = :telephone,
            adresse = :adresse,
            date_naissance = :date_naissance,
            role = :role";
    
        // Ajout conditionnel de la photo
        if ($photo !== null) {
            $sql .= ", photo = :photo";
        }
    
        $sql .= " WHERE utilisateur_id = :id";
    
        $stmt = $this->db->prepare($sql);
    
        // Bind des valeurs obligatoires
        $stmt->bindValue(':pseudo', $pseudo);
        $stmt->bindValue(':nom', $nom);
        $stmt->bindValue(':prenom', $prenom);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':telephone', $telephone);
        $stmt->bindValue(':adresse', $adresse);
        $stmt->bindValue(':date_naissance', $date_naissance);
        $stmt->bindValue(':role', $role);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
    
        // Bind de la photo seulement si fournie
        if ($photo !== null) {
            $stmt->bindValue(':photo', $photo);
        }
    
        return $stmt->execute();
    }

    //Met à jour de la session après modification des informations de l'utilisateur
    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE utilisateur_id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}