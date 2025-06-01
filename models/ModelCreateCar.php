<?php
//Modèle de création de voiture
class ModelCreateCar
{
    private PDO $db;
    

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');
       
    }


    public function createCar($modele, $immatriculation, $energie, $couleur, $date_premiere_immatriculation,$marque, $nb_place_voiture)  
    
    {
        $stmt = $this->db->prepare("INSERT INTO voiture (modele, immatriculation, energie, couleur, date_premiere_immatriculation,marque,nb_place_voiture) VALUES (:modele, :immatriculation, :energie, :couleur, :date_premiere_immatriculation,:marque,:nb_place_voiture )");  
        $stmt->bindValue(':modele', $modele);
        $stmt->bindValue(':immatriculation', $immatriculation);
        $stmt->bindValue(':energie', $energie);
        $stmt->bindValue(':couleur', $couleur);
        $stmt->bindValue(':date_premiere_immatriculation', $date_premiere_immatriculation);
        $stmt->bindValue(':marque', $marque);
        $stmt->bindValue(':nb_place_voiture', $nb_place_voiture);
         
         

         return $stmt->execute();
    }
    public function getLastInsertId() {
        return $this->db->lastInsertId();
    }
};
    

