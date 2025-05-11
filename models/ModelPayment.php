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

    public function increaseCreditPassenger ($id_passager, $prix_personne) {
        $stmt = $this->db->prepare("UPDATE utilisateur SET credit = credit + :prix_personne WHERE utilisateur_id = :id_passager");
        $stmt->bindValue(':id_passager', $id_passager);
        $stmt->bindValue(':prix_personne', $prix_personne);
        return $stmt->execute();
    }      

    public function getPrixPersonneFromCarpool($id_covoiturage) {
    $stmt = $this->db->prepare("SELECT prix_personne FROM covoiturage WHERE covoiturage_id = :id");
    $stmt->bindValue(':id', $id_covoiturage);
    $stmt->execute();
    return $stmt->fetchColumn();
}

public function refundPassengerCredit($id_passager, $prix) {
    $stmt = $this->db->prepare("UPDATE utilisateur SET credit = credit + :prix WHERE utilisateur_id = :id");
    $stmt->bindValue(':id', $id_passager);
    $stmt->bindValue(':prix', $prix);
    return $stmt->execute();
}

public function deletePassengerPayment($id_passager, $id_covoiturage) {
    $stmt = $this->db->prepare("DELETE FROM paiement_en_cours WHERE id_passager = :id_passager AND id_covoiturage_paye = :id_covoiturage");
    $stmt->bindValue(':id_passager', $id_passager);
    $stmt->bindValue(':id_covoiturage', $id_covoiturage);
    return $stmt->execute();
}

}