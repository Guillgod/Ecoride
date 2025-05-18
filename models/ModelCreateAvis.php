<?php
//Modèle de création d'avis'
class ModelCreateAvis
{
    private PDO $db;
    

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');
       
    }


        public function getFinishedCarpool(){
            $stmt = $this->db->prepare("SELECT * FROM covoiturage 
            JOIN utilisateur_participe_covoiturage ON utilisateur_participe_covoiturage.id_covoiturage = covoiturage.covoiturage_id
            JOIN voiture_utilise_covoiturage ON voiture_utilise_covoiturage.id_covoiturage_utilise_voiture = covoiturage.covoiturage_id
            JOIN utilisateur_possede_voiture ON utilisateur_possede_voiture.id_voiture_possede_utilisateur = voiture_utilise_covoiturage.id_voiture_utilise_covoiturage
            JOIN utilisateur ON utilisateur.utilisateur_id = utilisateur_possede_voiture.id_utilisateur_possede_voiture
            WHERE statut = 'terminé' AND utilisateur_participe_covoiturage.id_utilisateur = :id_utilisateur");
            $stmt->bindValue(':id_utilisateur', $_SESSION['user']['utilisateur_id']);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    public function createAvisTemp($id_covoiturage_en_cours, $id_chauffeur_en_cours, $commentaire_en_cours, $note_en_cours)
    {
        $stmt = $this->db->prepare("INSERT INTO avis_en_cours (id_covoiturage_en_cours, id_chauffeur_en_cours, commentaire_en_cours, note_en_cours,id_utilisateur_en_cours) VALUES (:id_covoiturage_avis_en_cours, :id_chauffeur_en_cours, :commentaire_en_cours, :note_en_cours, :id_utilisateur_en_cours)");
        $stmt->bindValue(':id_covoiturage_avis_en_cours', $id_covoiturage_en_cours);
        $stmt->bindValue(':id_chauffeur_en_cours', $id_chauffeur_en_cours);
        $stmt->bindValue(':commentaire_en_cours', $commentaire_en_cours);
        $stmt->bindValue(':note_en_cours', $note_en_cours);
        $stmt->bindValue(':id_utilisateur_en_cours', $_SESSION['user']['utilisateur_id']);

        return $stmt->execute();
    }
    

public function getAvisEnCours(){
    $stmt = $this->db->prepare("SELECT * FROM avis_en_cours 
    JOIN utilisateur_participe_covoiturage ON utilisateur_participe_covoiturage.id_covoiturage = avis_en_cours.id_covoiturage_en_cours WHERE id_utilisateur_en_cours = :id_utilisateur");
    $stmt->bindValue(':id_utilisateur', $_SESSION['user']['utilisateur_id']);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

public function InsertAvisInDatabase($id_covoiturage_validé, $id_chauffeur_validé, $commentaire_validé, $note_validé, $utilisateur_validé){
    $stmt = $this->db->prepare("INSERT INTO avis (id_covoiturage_validé, id_chauffeur_validé, commentaire_validé, note_validé, id_utilisateur_validé) VALUES (:id_covoiturage_avis_validé, :id_chauffeur_validé, :commentaire_validé, :note_validé,:id_utilisateur_validé)");
    $stmt->bindValue(':id_covoiturage_avis_en_cours', $id_covoiturage_validé);
    $stmt->bindValue(':id_chauffeur_validé', $id_chauffeur_validé);
    $stmt->bindValue(':commentaire_validé', $commentaire_validé);
    $stmt->bindValue(':note_validé', $note_validé);
    $stmt->bindValue(':id_utilisateur_validé', $utilisateur_validé);

    return $stmt->execute();
    

}
}

     
