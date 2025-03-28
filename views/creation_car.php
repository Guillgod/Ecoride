<!DOCTYPE <!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
    </head>


    <body>
 
    <h1>Renseignement Véhicule</h1>
 
    <form method="POST" action="../index.php">
    <input type="hidden" name="form_type" value="creation_car.php">
        <label for="modele">Modele :</label>
        <input type="text" name="modele" required>
        <br>
        <label for="immatriculation">Immatriculation :</label>
        <input type="text" name="immatriculation" required>
        <br>
        <label for="energie">energie :</label>
        <input type="text" name="energie" required>
        <br>
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
</body>
</html>