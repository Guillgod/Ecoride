<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
    </head>

<?php
require_once 'header.php';
?>

    <body>
 
    <h2>Renseignez votre trajet</h2>
 
    <form method="POST" action="creation_carpool.php">
    
    <input type="hidden" name="form_type" value="creation_carpool.php">
        <p>Renseignez votre adresse de départ :</p>

        <label for="adresse_depart">Adresse de départ :</label>
        <input type="text" name="adresse_depart" required>
        <br>
        <label for="lieu_depart">Ville :</label>
        <input type="text" name="lieu_depart" required>
        <br>
        
        <p>Renseignez votre date de départ</p>
        <label for="date_depart">Date de départ :</label>
        <input type="date" name="date_depart" required>
        <br>

        <label for="heure_depart">Heure de départ :</label>
        <input type="time" name="heure_depart" required>
        <br>

        <p>Renseignez votre adresse d'arrivée :</p>

        <label for="adresse_arrivee">Adresse d'arrivée :</label>
        <input type="text" name="adresse_arrivee" required>
        <br>
        <label for="lieu_arrivee">Ville :</label>
        <input type="text" name="lieu_arrivee" required>
        <br>

        <p>Renseignez votre date d'arrivée :</p>
        <label for="date_arrivee">Date d'arrivée :</label>
        <input type="date" name="date_arrivee" required>
        <br>

        <label for="heure_arrivee">Heure d'arrivée':</label>
        <input type="time" name="heure_arrivee" required>
        <br>

        <p>Fixez votre prix. Considérez que 2 crédits seront prélevé sur votre prix pour garantir le fonctionnement de la plate-forme Ecoride &#x1F609;</p>
        <label for="prix_personne">Prix du trajet par passager :</label>
        <input type="int" name="prix_personne" required>
        <br>

        <input type="submit" value="Créer le trajet">
    </form>

    <?php
    require_once '../controllers/creation_carpool_controller.php';
    require_once '../models/ModelCreateCarpool.php';



    $modelCreateCarpool = new ModelCreateCarpool();
    $controllerCreateCarpool = new Creation_Carpool_Controller($modelCreateCarpool);

    
    
    if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_type']) && $_POST['form_type'] === 'creation_carpool.php') {
        $adresse_depart = $_POST['adresse_depart'];
        $lieu_depart = $_POST['lieu_depart'];
        $date_depart = $_POST['date_depart'];
        $heure_depart = $_POST['heure_depart'];
        $adresse_arrivee = $_POST['adresse_arrivee'];
        $lieu_arrivee = $_POST['lieu_arrivee'];
        $date_arrivee = $_POST['date_arrivee'];
        $heure_arrivee = $_POST['heure_arrivee'];
        $prix_personne = $_POST['prix_personne'];
        
        $result=$controllerCreateCarpool->createCarpoolInDatabase();

        if($result) {
            echo "<p>Trajet créé avec succès.</p>";
        } else {
            echo "<p>Le trajet n'a pas pu être créé.</p>";
        }
    }
    
    ?>








</body>
</html>