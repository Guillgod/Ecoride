<?php
//Modèle de connexion d'utilisateur
require_once 'Database.php'; // Inclusion du fichier de connexion
class ModelUser {
    private PDO $db;

    
    public function __construct() {
        $this->db = Database::connect(); // Connexion centralisée
    }
    
    public function  authenticate($email, $password) {
        $stmt = $this->db->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

//Retourne tous les information de l'utilisateur (Ajouter les voitures liées à l'utilisateur)
    public function getUserInformation($email) { 

        
        $stmt = $this->db->prepare("SELECT utilisateur.*,voiture.*, covoiturage.*, utilisateur_possede_voiture.id_voiture_possede_utilisateur, voiture_utilise_covoiturage.id_covoiturage_utilise_voiture FROM utilisateur
        LEFT JOIN utilisateur_possede_voiture ON utilisateur_possede_voiture.id_utilisateur_possede_voiture = utilisateur.utilisateur_id
        LEFT JOIN voiture ON utilisateur_possede_voiture.id_voiture_possede_utilisateur = voiture.voiture_id
        LEFT JOIN voiture_utilise_covoiturage ON voiture_utilise_covoiturage.id_voiture_utilise_covoiturage = voiture.voiture_id
        LEFT JOIN covoiturage ON voiture_utilise_covoiturage.id_covoiturage_utilise_voiture = covoiturage.covoiturage_id
        LEFT JOIN utilisateur_participe_covoiturage ON utilisateur.utilisateur_id = utilisateur_participe_covoiturage.id_utilisateur
        WHERE email = :email");
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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

    public function updateUser($pseudo, $nom, $prenom, $email, $telephone, $adresse, $date_naissance, $photo, $role, $id,$preferences, $fumeur, $animal) {
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
            role = :role,
            preferences = :preferences,
            fumeur = :fumeur,
            animal = :animal";
    
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
        $stmt->bindValue(':preferences', $preferences);
        $stmt->bindValue(':fumeur', $fumeur);
        $stmt->bindValue(':animal', $animal);
    
        // Bind de la photo seulement si fournie
        if ($photo !== null) {
        $stmt->bindValue(':photo', $photo, PDO::PARAM_LOB);
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

    public function incrementPlaceDisponible($id_covoiturage) {
    $stmt = $this->db->prepare("UPDATE covoiturage SET nb_place_dispo = nb_place_dispo + 1 WHERE covoiturage_id = :id");
    $stmt->bindValue(':id', $id_covoiturage);
    return $stmt->execute();
}

public function deletePassengerParticipation($id_passager, $id_covoiturage) {
    $stmt = $this->db->prepare("DELETE FROM utilisateur_participe_covoiturage WHERE id_utilisateur = :id_utilisateur AND id_covoiturage = :id_covoiturage");
    $stmt->bindValue(':id_utilisateur', $id_passager);
    $stmt->bindValue(':id_covoiturage', $id_covoiturage);
    return $stmt->execute();
}
}