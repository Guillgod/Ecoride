<?php
/// Modèle de création de covoiturage
class ModelCreateCarpool
{
    private PDO $db;
    
    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');
       
    }


    public function createCarpool($adresse_depart, $lieu_depart, $date_depart, $heure_depart, $adresse_arrivee, $lieu_arrivee, $date_arrivee, $heure_arrivee,$prix_personne)  
    
    {
        $stmt = $this->db->prepare("INSERT INTO covoiturage (adresse_depart, lieu_depart, date_depart, heure_depart, adresse_arrivee, lieu_arrivee, date_arrivee, heure_arrivee, prix_personne) VALUES ( :adresse_depart, :lieu_depart, :date_depart, :heure_depart, :adresse_arrivee, :lieu_arrivee, :date_arrivee, :heure_arrivee, :prix_personne)");
        $stmt->bindValue(':adresse_depart', $adresse_depart);
        $stmt->bindValue(':lieu_depart', $lieu_depart);
        $stmt->bindValue(':date_depart', $date_depart);
        $stmt->bindValue(':heure_depart', $heure_depart);
        $stmt->bindValue(':adresse_arrivee', $adresse_arrivee);
        $stmt->bindValue(':lieu_arrivee', $lieu_arrivee);
        $stmt->bindValue(':date_arrivee', $date_arrivee);
        $stmt->bindValue(':heure_arrivee', $heure_arrivee);
        $stmt->bindValue(':prix_personne', $prix_personne);
         

        return $stmt->execute();
    }


    public function getCarpools($lieu_depart, $lieu_arrivee, $date_depart)
    {
        $stmt = $this->db->prepare("SELECT utilisateur.*, voiture.*, covoiturage.*, utilisateur_participe_coviturage * FROM voiture

             
        JOIN covoiturage ON covoiturage.covoiturage_id = voiture.utilise
        JOIN utilisateur ON utilisateur.utilisateur_id = utilisateur_participe_covoiturage.id_utilisateur
        WHERE covoiturage.lieu_depart = :lieu_depart 
        AND covoiturage.lieu_arrivee = :lieu_arrivee 
        AND covoiturage.date_depart >= :date_depart
        ORDER BY ABS(DATEDIFF(covoiturage.date_depart, :date_depart)) ASC");
        $stmt->bindValue(':lieu_depart', $lieu_depart);
        $stmt->bindValue(':lieu_arrivee', $lieu_arrivee);
        $stmt->bindValue(':date_depart', $date_depart);
        $stmt->execute();
        return $resultats = $stmt->fetchAll(PDO::FETCH_ASSOC);


        }

    public function getCarpoolDetails($covoiturage_id)
    {
        $stmt = $this->db->prepare("SELECT utilisateur.*, voiture.*, covoiturage.* FROM voiture
        JOIN utilisateur ON utilisateur_possede_voiture.id_utilisateur_possede_voiture = utilisateur.utilisateur_id
        JOIN voiture ON utilisateur_possede_voiture.id_voiture_possede_utilisateur = voiture.voiture_id
        JOIN covoiturage ON voiture.utilise = covoiturage.covoiturage_id
        WHERE covoiturage.covoiturage_id = :covoiturage_id");
        $stmt->bindValue(':covoiturage_id', $covoiturage_id);
        $stmt->execute();
        return $detailcovoiturage_id=$stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function addUserToCarpool($utilisateur_id, $covoiturage_id) {
        // Connexion à la base de données (assurez-vous d'utiliser une méthode propre pour se connecter)
          // Méthode pour se connecter à la DB

        // Exemple d'insertion dans une table qui associe un utilisateur à un covoiturage
         
        $stmt = $this->db->prepare("INSERT INTO utilisateur_participe_covoiturage (id_utilisateur, id_covoiturage) VALUES (:utilisateur_id, :covoiturage_id)");
        $stmt->bindValue(':utilisateur_id', $utilisateur_id);
        $stmt->bindValue(':covoiturage_id', $covoiturage_id);
        return $stmt->execute();
}

public function checkIfUserAlreadyJoined($utilisateur_id, $covoiturage_id) {
     

   
    $stmt = $this->db->prepare("SELECT * FROM utilisateur_participe_covoiturage WHERE id_utilisateur = :id_utilisateur AND id_covoiturage = :id_covoiturage");
    $stmt->bindParam(':id_utilisateur', $utilisateur_id);
    $stmt->bindParam(':id_covoiturage', $covoiturage_id);

    $stmt->execute();

    return $stmt->rowCount() > 0; // Si l'utilisateur est déjà inscrit
}
}        
    
