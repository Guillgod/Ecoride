<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas déjà démarrée
}
?>

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

    
        
        require_once 'header.php';
        require_once 'Barre_de_recherche.php';

        // Vérifiez si des résultats sont stockés dans la session
        if (isset($_SESSION['resultats'])) {
            $resultats = $_SESSION['resultats'];
            $date_recherchee =$_SESSION['date_depart_recherchee'];

            $resultats_exacts=[];
            $resultats_proches=[];
            
            // if (isset($_SESSION['resultats'])) {

            // }
            if (!empty($resultats)) {

                // Affichage des covoiturages disponibles
                echo '<h2>Résultats de la recherche :</h2>';
                foreach ($resultats as $ligne) {
                    if($ligne['date_depart'] === $date_recherchee){
                        $resultats_exacts[] = $ligne;
                    }else{
                        $resultats_proches[] = $ligne;
                    }
                }


                    if(!empty($resultats_exacts)) {
                        echo '<h3>Résultats exacts :</h3>';
                        foreach ($resultats_exacts as $ligne) {
                        afficherCovoiturage($ligne);
                        }
                    } else {
                        echo '<p>Aucun covoiturage trouvé à cette date.</p>';
                        if (!empty($resultats_proches)) {
                            echo '<p>Voulez-vous voir les covoiturages avec une date proche ?</p>';
                            echo '<button class="button" id="btn_oui" onclick="afficherCovoituragesProches()">OUI</button>';
                            echo '<button class="button" id="btn_non" onclick="cacherBoutons()">NON</button>';
                        }
                    }

                    if (!empty($resultats_proches)) {
                        echo '<div id="covoiturages_proches" style="display:none;">';
                        echo '<h2>Covoiturages avec une date proche :</h2>';
                        foreach ($resultats_proches as $ligne) {
                            afficherCovoiturage($ligne);
                        }
                        echo '</div>';
                    }
                
            }  else { if (isset($_SESSION['recherche_effectuee']) === true) {
                echo '<p>Aucun covoiturage trouvé entre ces villes.</p>';
            }}
        }
        unset($_SESSION['recherche_effectuee']);

        function afficherCovoiturage($ligne) {
            $chemin_photo = '../uploads/';
            echo '<div>';
            echo '<img src="' . $chemin_photo . htmlspecialchars($ligne['photo']) . '" alt="Photo de ' . htmlspecialchars($ligne['pseudo']) . '">';
            echo '<p> Pseudo du chauffeur : ' . htmlspecialchars($ligne['pseudo']) . '</p>';
            echo '<p> Note du chauffeur : ' . htmlspecialchars($ligne['note']) . '</p>';
            echo '<p> Nb de places : ' . htmlspecialchars($ligne['nb_place']) . '</p>';
            echo '<p> Prix par personne : ' . htmlspecialchars($ligne['prix_personne']) . '</p>';
            echo '<p> Date de départ : ' . htmlspecialchars($ligne['date_depart']) . '</p>';
            echo '<p> Heure de départ : ' . htmlspecialchars($ligne['heure_depart']) . '</p>';
            echo '<p> Heure d\'arrivée : ' . htmlspecialchars($ligne['heure_arrivee']) . '</p>';
            echo '<p> Energie du véhicule : ' . htmlspecialchars($ligne['energie']) . '</p>';
            echo '<button class="button"><a href="carpool_detail.php?covoiturage_id=' . $ligne['covoiturage_id'] . '">Détails</a></button>';
            echo '</div>';
        }
            // Une fois les résultats affichés, je les supprime de la session pour éviter de les afficher à nouveau lors du rechargement de la page
            // unset($_SESSION['resultats']);
        

    ?>
    
    <script>
        function afficherCovoituragesProches() {
            document.getElementById("covoiturages_proches").style.display = "block";
            document.getElementById("btn_oui").style.display = "none"; // Cache le bouton "OUI"
            document.getElementById("btn_non").style.display = "none"; // Cache le bouton "NON"
        }

        function cacherBoutons() {
            document.getElementById("btn_oui").style.display = "none";
            document.getElementById("btn_non").style.display = "none";
        }
    </script>




    </body>
</html>

