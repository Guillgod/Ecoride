<?php
//Gère la création de voiture
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
            $marque = $_POST['marque'];
            $nb_place_voiture = $_POST['nb_place_voiture']; // Valeur par défaut si non spécifiée
             

            // Call the model method to create the user
            $carcreated = $this->modelCreateCar->createCar($modele, $immatriculation, $energie, $couleur, $date_premiere_immatriculation,$marque,$nb_place_voiture ); 
            echo "Voiture créé avec succès";
        } else {
            echo "échec à la création de la voiture";
        }
    }

    public function getLastInsertId() {
        return $this->modelCreateCar->getLastInsertId();
    }
}