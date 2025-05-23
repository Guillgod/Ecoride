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

        // Étapes 4 et 5 : Supprimer la ligne de paiement en cours et de l'avis en cours
        $stmt = $this->db->prepare("DELETE FROM paiement_en_cours WHERE id_covoiturage_paye = :id_covoiturage");
        $stmt->execute([':id_covoiturage' => $id_covoiturage]);

        $stmt = $this->db->prepare("DELETE FROM avis_en_cours WHERE id_avis_en_cours = :id");
        $stmt->execute([':id' => $id_avis_en_cours]);

        // Étape 6 : Créditer le chauffeur avec les crédits du paiement
        $stmt = $this->db->prepare("
        UPDATE utilisateur 
        JOIN paiement ON utilisateur.utilisateur_id = paiement.id_chauffeur_paye_ok
        SET utilisateur.credit = utilisateur.credit + paiement.nb_credit_paye_ok - 2
        WHERE paiement.id_covoiturage_paye_ok = :id_covoiturage 
        ");

        $stmt->execute([':id_covoiturage' => $id_covoiturage]);
        echo "Crédits ajoutés au chauffeur<br>";

        // Etape 7 : Implémenter la nouvelle note du chauffeur.
        $stmt = $this->db->prepare("
        UPDATE utilisateur  
        JOIN avis ON utilisateur.utilisateur_id = avis.id_chauffeur_validé
        SET utilisateur.note = (SELECT ROUND(AVG(note_validé), 2) FROM avis
        WHERE id_chauffeur_validé = utilisateur.utilisateur_id)
        WHERE avis.id_covoiturage_validé = :id_covoiturage");

        $stmt->execute([':id_covoiturage' => $id_covoiturage]);



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

    
      
    
 // Afficher les avis dans CarpoolDetail.php l'avis validé dans le détail du covoiturage. 


    // La suppression de la ligne paiement_en_cours doit être différencier (à vérifier). Actuellement, toutes les lignes pour lesquelles le covoiturage est le même sont supprimées. Il faut que la suppression ne se fasse que pour la ligne de paiement_en_cours qui correspond à l'avis validé.
    // Egalement modifier l'affichage des covoiturages. Actuellement, même les covoiturages terminés sont affichés.


    //Attention, les covoiturages terminés apparaissent dans l'affichage des covoiturages. Il faut que seuls les covoiturages prévu apparaissent.
}