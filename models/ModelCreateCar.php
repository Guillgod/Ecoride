<?php

class ModelCreateCar
{
    private PDO $db;
    private string $modele;
    private string $immatriculation;
    private string $energie;
    private string $couleur;
    private string $date_premiere_immatriculation;

    private string $utilise;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');
       
    }


    public function createCar($modele, $immatriculation, $energie, $couleur, $date_premiere_immatriculation)  
    
    {
        $stmt = $this->db->prepare("INSERT INTO voiture (modele, immatriculation, energie, couleur, date_premiere_immatriculation) VALUES (:modele, :immatriculation, :energie, :couleur, :date_premiere_immatriculation)");  
        $stmt->bindValue(':modele', $modele);
        $stmt->bindValue(':immatriculation', $immatriculation);
        $stmt->bindValue(':energie', $energie);
        $stmt->bindValue(':couleur', $couleur);
        $stmt->bindValue(':date_premiere_immatriculation', $date_premiere_immatriculation);
         

        return $stmt->execute();
    }

}