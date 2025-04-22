<?php
/// Modèle de création de covoiturage
class ModelPayment
{
    private PDO $db;
    
    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');
       
    }

    public function stockPaymentCarpool($id_chauffeur,$id_passager,$id_covoiturage_paye,$nb_credit_paye) {
        $stmt = $this->db->prepare("INSERT INTO paiement_en_cours (id_chauffeur, id_passager, id_covoiturage_paye, nb_credit) VALUES (:id_chauffeur, :id_passager, :id_covoiturage_paye, :nb_credit)");
        $stmt->bindValue(':id_chauffeur', $id_chauffeur);
        $stmt->bindValue(':id_passager', $id_passager);
        $stmt->bindValue(':id_covoiturage_paye', $id_covoiturage_paye );
        $stmt->bindValue(':nb_credit', $nb_credit_paye);
        return $stmt->execute();
    }

    public function decreaseCreditPassenger ($id_passager, $prix_personne) {
        $stmt = $this->db->prepare("UPDATE utilisateur SET credit = credit - :prix_personne WHERE utilisateur_id = :id_passager");
        $stmt->bindValue(':id_passager', $id_passager);
        $stmt->bindValue(':prix_personne', $prix_personne);
        return $stmt->execute();
    }      

}