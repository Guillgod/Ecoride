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
    session_start(); // Démarrage de la session
    require_once 'header.php';
    require_once 'Barre_de_recherche.php';

    if (isset($_SESSION['resultats'])) {
        $resultats = $_SESSION['resultats'];

        if (!empty($resultats)) {
            foreach ($resultats as $ligne) {
                $chemin_photo = '../uploads/';
                echo '<div>';
                echo '<img src="' . $chemin_photo . htmlspecialchars($ligne['photo']) . '" alt="Photo de ' . htmlspecialchars($ligne['pseudo']) . '">';
                echo '<p>' . htmlspecialchars($ligne['pseudo']) . '</p>';
                echo '<p>' . htmlspecialchars($ligne['note']) . '</p>';
                echo '<p>' . htmlspecialchars($ligne['nb_place']) . '</p>';
                echo '<p>' . htmlspecialchars($ligne['prix_personne']) . '</p>';
                echo '<p>' . htmlspecialchars($ligne['date_depart']) . '</p>';
                echo '<p>' . htmlspecialchars($ligne['heure_depart']) . '</p>';
                echo '<p>' . htmlspecialchars($ligne['heure_arrivee']) . '</p>';
                echo '<p>' . htmlspecialchars($ligne['energie']) . '</p>';
                echo '<button class="button"><a href="carpool_detail.php">Participer</a></button>';
                echo '</div>';
            }
        }
    }
    ?>

    



    </body>
</html>
