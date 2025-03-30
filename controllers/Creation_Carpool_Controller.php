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
            $resultats = $displayedCarpool; // Appel de la méthode pour récupérer les trajets

            if ($resultats == null) {
                
                echo "Aucun trajet trouvé.";
                return;
            }


            foreach ($resultats as $ligne) {
                $chemin_image='uploads/';
                echo '<div>'; // Ajoute une classe CSS si nécessaire
                // Affiche les données de la ligne, par exemple :
                echo '<img src="' . htmlspecialchars($chemin_image.$ligne['photo']).'"alt="photo du conducteur" >'; 
                echo '<p>' . htmlspecialchars($ligne['pseudo']) . '</p>'; 
                echo '<p>' . htmlspecialchars($ligne['note']) . '</p>'; 
                echo '<p>' . htmlspecialchars($ligne['nb_place']) . '</p>'; 
                echo '<p>' . htmlspecialchars($ligne['prix_personne']) . '</p>'; 
                echo '<p>' . htmlspecialchars($ligne['date_depart']) . '</p>'; 
                echo '<p>' . htmlspecialchars($ligne['heure_depart']) . '</p>'; 
                echo '<p>' . htmlspecialchars($ligne['heure_arrivee']) . '</p>'; 
                echo '<p>' . htmlspecialchars($ligne['energie']) . '</p>'; 
                echo '<button class="button"><a href="views/carpool_detail.php">Détail</a></button>';
                echo '</div>';
                
            }
            
        } else {
            echo "Erreur lors de la récupération des données.";
            
        }

        
        }







    }
