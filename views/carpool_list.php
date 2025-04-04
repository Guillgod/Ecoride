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

    
        session_start(); // Assurez-vous de démarrer la session
        require_once 'header.php';
        require_once 'Barre_de_recherche.php';

        // Vérifiez si des résultats sont stockés dans la session
        if (isset($_SESSION['resultats'])) {
            $resultats = $_SESSION['resultats'];

            if (!empty($resultats)) {

                 
                // Affichage des covoiturages disponibles
                echo '<h2>Résultats de la recherche :</h2>';
                foreach ($resultats as $ligne) {
                    $chemin_photo = '../uploads/';
                    echo '<div>';
                    echo '<img src="' . $chemin_photo . htmlspecialchars($ligne['photo']) . '" alt="Photo de ' . htmlspecialchars($ligne['pseudo']) . '">';
                    echo '<p> Pseudo du chauffeur :' . htmlspecialchars($ligne['pseudo']) . '</p>';
                    echo '<p> Note du chauffeur :' . htmlspecialchars($ligne['note']) . '</p>';
                    echo '<p> Nb de place :' . htmlspecialchars($ligne['nb_place']) . '</p>';
                    echo '<p> Prix par personne :' . htmlspecialchars($ligne['prix_personne']) . '</p>';
                    echo '<p> Date de départ :' . htmlspecialchars($ligne['date_depart']) . '</p>';
                    echo '<p> Heure de départ :' . htmlspecialchars($ligne['heure_depart']) . '</p>';
                    echo '<p> Heure d\'arrivée :' . htmlspecialchars($ligne['heure_arrivee']) . '</p>';
                    echo '<p> Energie du véhicule :' . htmlspecialchars($ligne['energie']) . '</p>';
                    echo '<button class="button"><a href="carpool_detail.php?covoiturage_id=' . $ligne['covoiturage_id'] . '">Détails</a></button>';
                    echo '</div>';
                }
            } else {
                echo '<p>Aucun trajet trouvé.</p>';
            }
        }

            // Une fois les résultats affichés, je les supprime de la session pour éviter de les afficher à nouveau lors du rechargement de la page
            unset($_SESSION['resultats']);
        

    ?>
    
    </body>
</html>