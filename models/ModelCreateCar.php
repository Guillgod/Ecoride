<?php
//Modèle de création de voiture
class ModelCreateCar
{
    private PDO $db;
    

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');
       
    }


    public function createCar($modele, $immatriculation, $energie, $couleur, $date_premiere_immatriculation,$marque)  
    
    {
        $stmt = $this->db->prepare("INSERT INTO voiture (modele, immatriculation, energie, couleur, date_premiere_immatriculation,marque) VALUES (:modele, :immatriculation, :energie, :couleur, :date_premiere_immatriculation,:marque)");  
        $stmt->bindValue(':modele', $modele);
        $stmt->bindValue(':immatriculation', $immatriculation);
        $stmt->bindValue(':energie', $energie);
        $stmt->bindValue(':couleur', $couleur);
        $stmt->bindValue(':date_premiere_immatriculation', $date_premiere_immatriculation);
        $stmt->bindValue(':marque', $marque);
         

        if( $stmt->execute()){
            return $this->db->lastInsertId(); // Retourne l'ID de la voiture créée
        }
        return false; // En cas d'échec de l'insertion
    }
};
    

