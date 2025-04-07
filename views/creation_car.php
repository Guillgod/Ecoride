<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
    </head>


    <body>
 
    <h1>Renseignement Véhicule</h1>
 
    <form method="POST" action="creation_car.php">
    <input type="hidden" name="form_type" value="creation_car.php">
        <label for="modele">Modele :</label>
        <input type="text" name="modele" required>
        <br>
        <label for="immatriculation">Immatriculation :</label>
        <input type="text" name="immatriculation" required>
        <br>

        <label for="vehicletype">Type de véhicule:</label>

        <select name="energie" required>
            <option value="">--Type de véhicule--</option>
            <option value="Electrique">Écologique</option>
            <option value="Thermique">Diesel/Essence</option>
        </select>

        <label for="couleur">Couleur:</label>
        <input type="text" name="couleur" required>
        <br>
        <label for="date_premiere_immatriculation">Date premiere immatriculation :</label>
        <input type="date" name="date_premiere_immatriculation" required>
        <br>
        <label for="marque">Marque :</label>
        <input type="text" name="marque" required>
        <br>
        <input type="submit" value="Créer la voiture">
    </form>

    <?php
    require '../models/ModelCreateCar.php';
    require '../controllers/Creation_Car_Controller.php';

    if ($_SERVER['REQUEST_METHOD'] === 'POST'  && isset($_POST['form_type']) && $_POST['form_type'] === 'creation_car.php') {
        $modelCreateCar = new ModelCreateCar();
        $controllerCreateCar = new Creation_Car_Controller($modelCreateCar);
        $controllerCreateCar->createCarInDatabase();
    }
    ?>


</body>
</html>