<?php

class Creation_Car_Controller
{
    private $modelCreateCar;

    public function __construct(ModelCreateCar $modelCreateCar)
    {
        $this->modelCreateCar = $modelCreateCar;
    }
   

    public function createCarInDatabase()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $modele = $_POST['modele'];
            $immatriculation = $_POST['immatriculation'];
            $energie = $_POST['energie'];
            $couleur = $_POST['couleur'];
            $date_premiere_immatriculation = $_POST['date_premiere_immatriculation'];
            

            // Call the model method to create the user
            $carcreated = $this->modelCreateCar->createCar($modele, $immatriculation, $energie, $couleur, $date_premiere_immatriculation); 
            echo "Voiture créé avec succès";
        } else {
            echo "échec à la création de la voiture";
        }
    }
}