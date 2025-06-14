<?php
/// Modèle permettant de charger les avis soumis par les passagers et qui doivent être validés par l'employé
require_once 'Database.php'; // Inclusion du fichier de connexion
class ModelEmployee
{
    private PDO $db;
    
    public function __construct()
    {
        $this->db = Database::connect(); // Connexion centralisée
       
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

        // Étape 1 : Copier l'avis dans la table "avis"
        $stmt = $this->db->prepare("
            INSERT INTO avis (id_utilisateur_validé, id_chauffeur_validé, id_covoiturage_validé, note_validé, commentaire_validé)
            SELECT id_utilisateur_en_cours, id_chauffeur_en_cours, id_covoiturage_en_cours, note_en_cours, commentaire_en_cours
            FROM avis_en_cours
            WHERE id_avis_en_cours = :id
        ");
        $stmt->execute(['id' => $id_avis_en_cours]);
        echo "Insertion avis réussie<br>";

        // Étape 2 : Récupérer les identifiants nécessaires
        $stmt = $this->db->prepare("
            SELECT id_covoiturage_en_cours, id_utilisateur_en_cours, id_chauffeur_en_cours 
            FROM avis_en_cours 
            WHERE id_avis_en_cours = :id
        ");
        $stmt->execute([':id' => $id_avis_en_cours]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception("Avis introuvable.");
        }

        $id_covoiturage = $row['id_covoiturage_en_cours'];
        $id_passager = $row['id_utilisateur_en_cours'];
        $id_chauffeur = $row['id_chauffeur_en_cours'];
        echo "Données récupérées<br>";

        // Étape 3 : Insérer le paiement pour ce passager uniquement
        $stmt = $this->db->prepare("
            INSERT INTO paiement (id_chauffeur_paye_ok, id_passager_paye_ok, id_covoiturage_paye_ok, nb_credit_paye_ok)
            SELECT id_chauffeur, id_passager, id_covoiturage_paye, nb_credit
            FROM paiement_en_cours
            WHERE id_covoiturage_paye = :id_covoiturage AND id_passager = :id_passager
        ");
        $stmt->execute([
            ':id_covoiturage' => $id_covoiturage,
            ':id_passager' => $id_passager
        ]);
        echo "Paiement validé<br>";

        // Étape 4 : Supprimer le paiement en cours pour ce passager uniquement
        $stmt = $this->db->prepare("
            DELETE FROM paiement_en_cours 
            WHERE id_covoiturage_paye = :id_covoiturage AND id_passager = :id_passager
        ");
        $stmt->execute([
            ':id_covoiturage' => $id_covoiturage,
            ':id_passager' => $id_passager
        ]);

        // Étape 5 : Supprimer l'avis en cours validé
        $stmt = $this->db->prepare("
            DELETE FROM avis_en_cours 
            WHERE id_avis_en_cours = :id
        ");
        $stmt->execute([':id' => $id_avis_en_cours]);

        //Etape 5.1 : Taggué l'avis à TRUE comme envoyé dans la table utilisateur_participe_covoiturage
        $stmt = $this->db->prepare("
        UPDATE utilisateur_participe_covoiturage 
        SET avis_envoye = 1 
        WHERE id_utilisateur = :id_utilisateur AND id_covoiturage = :id_covoiturage
        ");
        $stmt->execute([
            ':id_utilisateur' => $id_passager,
            ':id_covoiturage' => $id_covoiturage
        ]);
        echo "Champ avis_envoye mis à jour<br>";


        // Étape 6 : Créditer le chauffeur avec le paiement de ce passager (-2 pour la commission)
        $stmt = $this->db->prepare("
            UPDATE utilisateur 
            JOIN paiement 
            ON utilisateur.utilisateur_id = paiement.id_chauffeur_paye_ok
            SET utilisateur.credit = utilisateur.credit + paiement.nb_credit_paye_ok - 2
            WHERE paiement.id_covoiturage_paye_ok = :id_covoiturage AND paiement.id_passager_paye_ok = :id_passager
        ");
        $stmt->execute([
            ':id_covoiturage' => $id_covoiturage,
            ':id_passager' => $id_passager
        ]);
        echo "Crédit ajouté au chauffeur<br>";

        // Étape 6.1 : Créditer la plateforme
        $stmt = $this->db->prepare("
        INSERT INTO gain_plateforme (date_de_paiement) 
        VALUES (CURRENT_DATE)
        ");
        $stmt->execute();

        $lastId = $this->db->lastInsertId(); // ← récupère l'ID de la ligne insérée

        // Mise à jour de cette ligne
        $updateStmt = $this->db->prepare("
            UPDATE gain_plateforme 
            SET gain = 2 
            WHERE id_gain = :id
        ");
        $updateStmt->bindValue(':id', $lastId, PDO::PARAM_INT);
        $updateStmt->execute();

        echo "Crédit ajouté à la plateforme<br>";

        // Étape 7 : Mettre à jour la moyenne de note du chauffeur
        $stmt = $this->db->prepare("
            UPDATE utilisateur  
            SET note = (
                SELECT ROUND(AVG(note_validé), 2) 
                FROM avis 
                WHERE id_chauffeur_validé = :id_chauffeur
            )
            WHERE utilisateur_id = :id_chauffeur
        ");
        $stmt->execute([':id_chauffeur' => $id_chauffeur]);
        echo "Note moyenne du chauffeur mise à jour<br>";

        $this->db->commit();
        echo "Validation terminée avec succès<br>";
    } catch (Exception $e) {
        $this->db->rollBack();
        echo "Erreur : " . $e->getMessage();
    }
}

    public function RefuserAvis($id_avis_en_cours) {
        $this->db->beginTransaction();

    try {
        echo "Début validation...<br>";
    // Étape 2 : Récupérer les identifiants nécessaires
        $stmt = $this->db->prepare("
            SELECT id_covoiturage_en_cours, id_utilisateur_en_cours, id_chauffeur_en_cours 
            FROM avis_en_cours 
            WHERE id_avis_en_cours = :id
        ");
        $stmt->execute([':id' => $id_avis_en_cours]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            throw new Exception("Avis introuvable.");
        }

        $id_covoiturage = $row['id_covoiturage_en_cours'];
        $id_passager = $row['id_utilisateur_en_cours'];
        $id_chauffeur = $row['id_chauffeur_en_cours'];
        echo "Données récupérées<br>";

        // Étape 3 : Insérer le paiement pour ce passager uniquement
        $stmt = $this->db->prepare("
            INSERT INTO paiement (id_chauffeur_paye_ok, id_passager_paye_ok, id_covoiturage_paye_ok, nb_credit_paye_ok)
            SELECT id_chauffeur, id_passager, id_covoiturage_paye, nb_credit
            FROM paiement_en_cours
            WHERE id_covoiturage_paye = :id_covoiturage AND id_passager = :id_passager
        ");
        $stmt->execute([
            ':id_covoiturage' => $id_covoiturage,
            ':id_passager' => $id_passager
        ]);
        echo "Paiement validé<br>";

        // Étape 4 : Supprimer le paiement en cours pour ce passager uniquement
        $stmt = $this->db->prepare("
            DELETE FROM paiement_en_cours 
            WHERE id_covoiturage_paye = :id_covoiturage AND id_passager = :id_passager
        ");
        $stmt->execute([
            ':id_covoiturage' => $id_covoiturage,
            ':id_passager' => $id_passager
        ]);

        // Étape 5 : Supprimer l'avis en cours validé
        $stmt = $this->db->prepare("
            DELETE FROM avis_en_cours 
            WHERE id_avis_en_cours = :id
        ");
        $stmt->execute([':id' => $id_avis_en_cours]);

        //Etape 5.1 : Taggué l'avis à TRUE comme envoyé dans la table utilisateur_participe_covoiturage
        $stmt = $this->db->prepare("
        UPDATE utilisateur_participe_covoiturage 
        SET avis_envoye = 1 
        WHERE id_utilisateur = :id_utilisateur AND id_covoiturage = :id_covoiturage
        ");
        $stmt->execute([
            ':id_utilisateur' => $id_passager,
            ':id_covoiturage' => $id_covoiturage
        ]);
        echo "Champ avis_envoye mis à jour<br>";


        // Étape 6 : Créditer le chauffeur avec le paiement de ce passager (-2 pour la commission)
        $stmt = $this->db->prepare("
            UPDATE utilisateur 
            JOIN paiement 
            ON utilisateur.utilisateur_id = paiement.id_chauffeur_paye_ok
            SET utilisateur.credit = utilisateur.credit + paiement.nb_credit_paye_ok - 2
            WHERE paiement.id_covoiturage_paye_ok = :id_covoiturage AND paiement.id_passager_paye_ok = :id_passager
        ");
        $stmt->execute([
            ':id_covoiturage' => $id_covoiturage,
            ':id_passager' => $id_passager
        ]);
        echo "Crédit ajouté au chauffeur<br>";

        // Étape 6.1 : Créditer la plateforme
        $stmt = $this->db->prepare("
        INSERT INTO gain_plateforme (date_de_paiement) 
        VALUES (CURRENT_DATE)
        ");
        $stmt->execute();

        $lastId = $this->db->lastInsertId(); // ← récupère l'ID de la ligne insérée

        // Mise à jour de cette ligne
        $updateStmt = $this->db->prepare("
            UPDATE gain_plateforme 
            SET gain = 2 
            WHERE id_gain = :id
        ");
        $updateStmt->bindValue(':id', $lastId, PDO::PARAM_INT);
        $updateStmt->execute();

        echo "Crédit ajouté à la plateforme<br>";

    

    $this->db->commit();
        echo "Validation terminée avec succès<br>";
    } catch (Exception $e) {
            $this->db->rollBack();
            echo "Erreur : " . $e->getMessage();
        }
    }
      
    
 // Afficher les avis dans CarpoolDetail.php l'avis validé dans le détail du covoiturage. 


    // L'avis continue d'être demandé au passager même s'il a déjà été donné. Pour le moment, le formulaire avis n,e s'affiche plus car la table utilisateur_participe_covoiturage a un champ avis_envoye directement à 1 à la place de 0 au moemnt où le chauffeur passe le covoiturage au statut terminé.
    

}
