<?php
/// Modèle permettant de charger les avis soumis par les passagers et qui doivent être validés par l'employé
class ModelEmployee
{
    private PDO $db;
    
    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');
       
    }

    public function LoadAvisCarpoolEnCours () {
         
            $stmt = $this->db->prepare(
    "SELECT 
        passager.prenom AS passager_prenom,
        passager.nom AS passager_nom,
        passager.email AS passager_email,
        chauffeur.prenom AS chauffeur_prenom,
        chauffeur.nom AS chauffeur_nom,
        chauffeur.email AS chauffeur_email,
        covoiturage.lieu_depart,
        covoiturage.lieu_arrivee,
        avis_en_cours.note_en_cours,
        avis_en_cours.commentaire_en_cours
    FROM avis_en_cours 
    JOIN covoiturage ON avis_en_cours.id_covoiturage_en_cours = covoiturage.covoiturage_id
    JOIN utilisateur AS passager ON avis_en_cours.id_utilisateur_en_cours = passager.utilisateur_id 
    JOIN utilisateur AS chauffeur ON avis_en_cours.id_chauffeur_en_cours = chauffeur.utilisateur_id"
);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

}