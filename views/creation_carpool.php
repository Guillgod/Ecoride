<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
    </head>


    <body>
 
    <h1>Renseignez votre trajet</h1>
 
    <form method="POST" action="../index.php">
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
</body>
</html>