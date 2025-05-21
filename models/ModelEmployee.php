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
        avis_en_cours.id_avis_en_cours,
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

 public function ValiderAvis($id_avis_en_cours) {
    $this->db->beginTransaction();

    try {
        echo "Début validation...<br>";

        // Étape 1
        $stmt = $this->db->prepare("INSERT INTO avis (id_utilisateur_validé, id_chauffeur_validé, id_covoiturage_validé, note_validé, commentaire_validé)
            SELECT id_utilisateur_en_cours, id_chauffeur_en_cours, id_covoiturage_en_cours, note_en_cours, commentaire_en_cours
            FROM avis_en_cours
            WHERE id_avis_en_cours = :id");
        $stmt->execute(['id' => $id_avis_en_cours]);
        echo "Insertion avis réussie<br>";

        // Étape 2
        $stmt = $this->db->prepare("SELECT id_covoiturage_en_cours FROM avis_en_cours WHERE id_avis_en_cours = :id");
        $stmt->execute([':id' => $id_avis_en_cours]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            throw new Exception("Covoiturage introuvable pour cet avis.");
        }
        $id_covoiturage = $row['id_covoiturage_en_cours'];
        echo "ID covoiturage récupéré : $id_covoiturage<br>";

        // Étape 3
        $stmt = $this->db->prepare("
            INSERT INTO paiement (id_chauffeur_paye_ok, id_passager_paye_ok, id_covoiturage_paye_ok, nb_credit_paye_ok)
            SELECT id_chauffeur, id_passager, id_covoiturage_paye, nb_credit
            FROM paiement_en_cours
            WHERE id_covoiturage_paye = :id_covoiturage
        ");

        $stmt->execute([':id_covoiturage' => $id_covoiturage]);
        echo "Paiement validé<br>";

        // Étapes 4 et 5
        $stmt = $this->db->prepare("DELETE FROM paiement_en_cours WHERE id_covoiturage_paye = :id_covoiturage");
        $stmt->execute([':id_covoiturage' => $id_covoiturage]);

        $stmt = $this->db->prepare("DELETE FROM avis_en_cours WHERE id_avis_en_cours = :id");
        $stmt->execute([':id' => $id_avis_en_cours]);

        $this->db->commit();
        echo "Avis supprimé<br>";
    } catch (Exception $e) {
        $this->db->rollBack();
        echo "Erreur : " . $e->getMessage();
    }
}

    public function RefuserAvis($id_avis_en_cours) {
    $stmt = $this->db->prepare("DELETE FROM avis_en_cours WHERE id_avis_en_cours = :id");
    $stmt->execute([':id' => $id_avis_en_cours]);
    }

    

}