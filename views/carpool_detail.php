<!DOCTYPE <!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body>
    
    
    <?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

    session_start(); // Démarrage de la session
    require_once 'header.php';
    require_once 'Barre_de_recherche.php';
    require '../models/ModelCreateCarpool.php';
    require '../controllers/Creation_Carpool_Controller.php';
    
    if (isset($_GET['covoiturage_id'])) {
        $covoiturage_id = $_GET['covoiturage_id'];

        $modelCreateCarpool = new ModelCreateCarpool();
        $controllerAffichageCarpoolDetail = new Creation_Carpool_Controller($modelCreateCarpool);
        $carpoolDetails = $controllerAffichageCarpoolDetail->getCarpoolDetailsResult($covoiturage_id);

        
         

        if ($carpoolDetails) {
            
                $chemin_photo = '../uploads/';
                echo '<div>';
                echo '<img src="' . $chemin_photo . htmlspecialchars($carpoolDetails['photo']) . '" alt="Photo de ' . htmlspecialchars($carpoolDetails['pseudo']) . '">';
                echo '<p>Pseudo du chauffeur :' . htmlspecialchars($carpoolDetails['pseudo']) . '</p>';
                echo '<p>Note du chauffeur :' .  $carpoolDetails['note'] . '</p>';
                echo '<p>Nb de place :' .  $carpoolDetails['nb_place'] . '</p>';
                echo '<p>Prix par personne :' . htmlspecialchars($carpoolDetails['prix_personne']) . '</p>';
                echo '<p>Date de départ :' . htmlspecialchars($carpoolDetails['date_depart']) . '</p>';
                echo '<p>Heure de départ :' . htmlspecialchars($carpoolDetails['heure_depart']) . '</p>';
                echo '<p>Heure d\'arrivée :' . htmlspecialchars($carpoolDetails['heure_arrivee']) . '</p>';
                echo '<p>Energie du véhicule :' . htmlspecialchars($carpoolDetails['energie']) . '</p>';
                echo '</div>';
            }else {
                echo '<p>Aucun identifiant de covoiturage spécifié.</p>';
            }
            
    }
    ?>

    



    </body>
</html>
