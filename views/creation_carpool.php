<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
    </head>
<section> 
<?php
require_once 'header.php';
require_once '../controllers/creation_carpool_controller.php';
require_once '../models/ModelCreateCarpool.php';


    


$modelCreateCarpool = new ModelCreateCarpool();
$voitures =$modelCreateCarpool->getUserCars($_SESSION['user']['utilisateur_id']);
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
        if ($prix_personne < 2) {
    // Option : stocker un message d'erreur dans la session ou afficher directement :
    echo "<script>alert('Erreur : Le prix par personne doit être au minimum de 2 crédits.'); window.history.back();</script>";
    exit;
    }
        $result=$controllerCreateCarpool->createCarpoolInDatabase();

        if($result) {
            echo "<p style='color: green;'>Trajet créé avec succès.</p>";
        } else {
            echo "<p style='color: red;'>Le trajet n'a pas pu être créé.</p>";
        }
    }
    
    ?>

 

    
 
    <h2>Renseignez votre trajet</h2>
    <div class="form-voiture-container">
    <form method="POST" action="creation_carpool.php">
     
    <input type="hidden" name="form_type" value="creation_carpool.php">
    
    <div class="champ-voiture">
        <h3>Renseignez les informations du départ :</h3>
        <label for="adresse_depart">Adresse de départ :</label>
        <input type="text" name="adresse_depart" required>
        <br>
    </div>
    <div class="champ-voiture">
        <label for="lieu_depart">Ville :</label>
        <input type="text" name="lieu_depart" required>
        <br>
    </div>
    
    <div class="champ-voiture">
        <label for="date_depart">Date de départ :</label>
        <input type="date" name="date_depart" required>
        <br>
    </div>
    <div class="champ-voiture">
        <label for="heure_depart">Heure de départ :</label>
        <input type="time" name="heure_depart" required>
        <br>
    </div>
    
    <div class="champ-voiture">
        <h3>Renseignez les informations de l'arrivée :</h3>

        <label for="adresse_arrivee">Adresse d'arrivée :</label>
        <input type="text" name="adresse_arrivee" required>
        <br>
    </div>
    <div class="champ-voiture">
        <label for="lieu_arrivee">Ville :</label>
        <input type="text" name="lieu_arrivee" required>
        <br>
    </div>
    
    <div class="champ-voiture">
        <label for="date_arrivee">Date d'arrivée :</label>
        <input type="date" name="date_arrivee" required>
        <br>
    </div>
    <div class="champ-voiture">
        <label for="heure_arrivee">Heure d'arrivée':</label>
        <input type="time" name="heure_arrivee" required>
        <br>
    </div>
    
    <div class="champ-voiture">
        <h3>Fixez votre prix. 2 crédits seront prélevés pour garantir le fonctionnement de la plate-forme Ecoride &#x1F609;</h3>
        <label for="prix_personne">Prix du trajet par passager :</label>
        <input type="number" name="prix_personne" min="2" required>
        <br>
    </div>
    <div class="champ-voiture">
        <label for="voiture_id">Choisir une voiture :</label>
        <select name="voiture_id" required>
            <option value="">--Sélectionnez une voiture--</option>
            <?php foreach ($voitures as $voiture): ?>
                
                <option value="<?= $voiture['voiture_id'] ?>">
                    <?= $voiture['marque'] . ' ' . $voiture['modele'] . ' (' . $voiture['immatriculation'] . ')' ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br>
    </div>
    
        <div class="button-container">
            <button type="submit" class="button">Créer le trajet</button>
        </div>
    </form>
</div>


    
    





</section>
<?php
require_once 'footer.php';
?>

<script>
document.querySelector("form").addEventListener("submit", function(event) {
    const prix = parseFloat(document.querySelector("input[name='prix_personne']").value);
    if (prix < 2) {
        event.preventDefault(); // bloque l’envoi
        alert("Le prix par personne doit être au minimum de 2 crédits.");
    }
});
</script>
</body>
</html>