<?php
//Model de création d'utilisateur
class ModelCreateUser
{
    private PDO $db;
     

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');
       
    }

   

    public function createUser($nom, $prenom, $email, $password, $telephone, $adresse,$date_naissance, $pseudo, $photo,$role, $preferences, $fumeur, $animal) 
    
    {
        $stmt = $this->db->prepare("INSERT INTO utilisateur (nom, prenom, email, password, telephone, adresse,date_naissance, pseudo,photo,role,credit,preferences,fumeur,animal) VALUES (:nom, :prenom, :email, :password, :telephone, :adresse, :date_naissance, :pseudo,:photo,:role,:credit,:preferences, :fumeur,:animal)"); 
        $stmt->bindValue(':nom', $nom);
        $stmt->bindValue(':prenom', $prenom);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':telephone', $telephone);
        $stmt->bindValue(':adresse', $adresse);
        $stmt->bindValue(':date_naissance', $date_naissance); 
        $stmt->bindValue(':pseudo', $pseudo);
        $stmt->bindValue(':photo', $photo);
        $stmt->bindValue(':role', $role); // Rôle par défaut
        $stmt->bindValue(':credit', 20); // Crédit par défaut
        $stmt->bindValue(':preferences', $preferences); // Préférences par défaut
        $stmt->bindValue(':fumeur', $fumeur);
        $stmt->bindValue(':animal', $animal);
           

        return $stmt->execute();
    }
    // Fonction à supprimer après création de la table utilisateur
    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }

    public function addCarToUser($utilisateur_id, $voiture_id) {
        // Connexion à la base de données (assurez-vous d'utiliser une méthode propre pour se connecter)
          // Méthode pour se connecter à la DB

         
        $stmt = $this->db->prepare("INSERT INTO utilisateur_possede_voiture (id_utilisateur_possede_voiture, id_voiture_possede_utilisateur) VALUES (:utilisateur_id, :voiture_id)");
        $stmt->bindValue(':utilisateur_id', $utilisateur_id);
        $stmt->bindValue(':voiture_id', $voiture_id);
        return $stmt->execute();
}
    
public function checkIfCarAlreadyJoinedThisUser($utilisateur_id, $voiture_id) {
     

   
    $stmt = $this->db->prepare("SELECT * FROM utilisateur_possede_voiture WHERE id_utilisateur = :id_utilisateur2 AND id_voiture = :id_voiture2");
    $stmt->bindParam(':id_utilisateur2', $utilisateur_id);
    $stmt->bindParam(':id_voiture2', $voiture_id);

    $stmt->execute();

    return $stmt->rowCount() > 0; // Si la voitureest déjà déclarée par l'utilisateur
}

public function emailExists($email): bool {
    $stmt = $this->db->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = :email");
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    return $stmt->fetchColumn() > 0;
}

}