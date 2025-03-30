<?php

class Creation_Carpool_Controller
{
    private $modelCreateCarpool;

    public function __construct(ModelCreateCarpool $modelCreateCarpool)
    {
        $this->modelCreateCarpool = $modelCreateCarpool;
    }


    public function createCarpoolInDatabase()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $adresse_depart = $_POST['adresse_depart'];
            $lieu_depart = $_POST['lieu_depart'];
            $date_depart = $_POST['date_depart'];
            $heure_depart = $_POST['heure_depart'];
            $adresse_arrivee = $_POST['adresse_arrivee'];
            $lieu_arrivee = $_POST['lieu_arrivee'];
            $date_arrivee = $_POST['date_arrivee'];
            $heure_arrivee = $_POST['heure_arrivee'];
            $prix_personne = $_POST['prix_personne'];
            

            // Call the model method to create the user
            $carpoolcreated = $this->modelCreateCarpool->createCarpool($adresse_depart, $lieu_depart, $date_depart, $heure_depart, $adresse_arrivee, $lieu_arrivee, $date_arrivee, $heure_arrivee,$prix_personne); 
            echo "Trajet créé avec succès";
        } else {
            echo "échec à la création du trajet";
        }
    }


    public function displayCarpool(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $lieu_depart = $_POST['lieu_depart'];
            $lieu_arrivee = $_POST['lieu_arrivee'];
            $date_depart = $_POST['date_depart'];


            $displayedCarpool = $this->modelCreateCarpool->getCarpools($lieu_depart, $lieu_arrivee, $date_depart); 
            return $displayedCarpool; // Appel de la méthode pour récupérer les trajets


        }
        return null; // Retournez null si la méthode n'est pas appelée par POST
    }







    }
