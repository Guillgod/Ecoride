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
            WHERE statut = 'terminé' AND utilisateur_participe_covoiturage.id_utilisateur = :id_utilisateur
            AND utilisateur_participe_covoiturage.avis_envoye = 0");
            $stmt->bindValue(':id_utilisateur', $_SESSION['user']['utilisateur_id']);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

    public function createAvisTemp($id_covoiturage_en_cours, $id_chauffeur_en_cours, $commentaire_en_cours,$note_en_cours)
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
    $stmt = $this->db->prepare("INSERT INTO avis (id_covoiturage_validé, id_chauffeur_validé, commentaire_validé, note_validé, id_utilisateur_validé) VALUES (:id_covoiturage_validé, :id_chauffeur_validé, :commentaire_validé, :note_validé,:id_utilisateur_validé)");
    $stmt->bindValue(':id_covoiturage_validé', $id_covoiturage_validé);
    $stmt->bindValue(':id_chauffeur_validé', $id_chauffeur_validé);
    $stmt->bindValue(':commentaire_validé', $commentaire_validé);
    $stmt->bindValue(':note_validé', $note_validé);
    $stmt->bindValue(':id_utilisateur_validé', $utilisateur_validé);

    return $stmt->execute();
    

}

public function createAvis($id_covoiturage_validé, $id_chauffeur_validé, $commentaire_validé,$note_validé, $id_utilisateur_validé)
    {
        
        $this->db->beginTransaction();

    try {
        

        // Étape 1 : Copier le formulaire avis dans la  table "avis"  
        $stmt = $this->db->prepare("INSERT INTO avis (id_covoiturage_validé, id_chauffeur_validé, commentaire_validé, note_validé,id_utilisateur_validé) VALUES (:id_covoiturage_valide, :id_chauffeur_valide, :commentaire_valide, :note_valide, :id_utilisateur_valide)");
        $stmt->bindValue(':id_covoiturage_valide', $id_covoiturage_validé);
        $stmt->bindValue(':id_chauffeur_valide', $id_chauffeur_validé);
        $stmt->bindValue(':commentaire_valide', $commentaire_validé);
        $stmt->bindValue(':note_valide', $note_validé);
        $stmt->bindValue(':id_utilisateur_valide', $id_utilisateur_validé);
        $stmt->execute();
        

        // Étape 2 : Récupérer les identifiants nécessaires
        

        $id_covoiturage = $id_covoiturage_validé;
        $id_passager = $_SESSION['user']['utilisateur_id'];
        $id_chauffeur = $id_chauffeur_validé;
        

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
        

        // Étape 4 : Supprimer le paiement en cours pour ce passager uniquement
        $stmt = $this->db->prepare("
            DELETE FROM paiement_en_cours 
            WHERE id_covoiturage_paye = :id_covoiturage AND id_passager = :id_passager
        ");
        $stmt->execute([
            ':id_covoiturage' => $id_covoiturage,
            ':id_passager' => $id_passager
        ]);

        

        //Etape 5: Taggué l'avis à TRUE comme envoyé dans la table utilisateur_participe_covoiturage
        $stmt = $this->db->prepare("
        UPDATE utilisateur_participe_covoiturage 
        SET avis_envoye = 1 
        WHERE id_utilisateur = :id_utilisateur AND id_covoiturage = :id_covoiturage
        ");
        $stmt->execute([
            ':id_utilisateur' => $id_passager,
            ':id_covoiturage' => $id_covoiturage
        ]);
        


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
         

        $this->db->commit();
        
    } catch (Exception $e) {
        $this->db->rollBack();
        echo "Erreur : " . $e->getMessage();
    }
    }
}

// Revoir la méthode 6.1 pour créditer uniquement la dernière ligne insérée dans la table gain_plateforme
