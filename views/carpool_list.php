<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas déjà démarrée
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/style.css" rel="stylesheet">
</head>
<body>
<section>
<?php
require_once 'header.php';
require_once 'Barre_de_recherche.php';
require_once 'filtre.php';

// Vérifiez si des résultats sont stockés dans la session



if (isset($_SESSION['resultats'])) {
    $resultats = $_SESSION['resultats'];

    // Récupération des filtres GET
    $filtre_energie = $_GET['Ecologique'] ?? '';
    $filtre_prixmax = $_GET['Prixmax'] ?? '';
    $filtre_dureemax = $_GET['Dureemax'] ?? '';
    $filtre_notemin = $_GET['Notemin'] ?? '';

    // Application des filtres
    $resultats = array_filter($resultats, function ($ligne) use ($filtre_energie, $filtre_prixmax, $filtre_dureemax, $filtre_notemin) {
        if ($ligne['nb_place_dispo'] <= 0 || $ligne['statut'] !== 'prévu') {
            
            return false; // Ignore les lignes où le nombre de places disponibles est inférieur ou égal à 0
        }
        
        
        // Filtre énergie
        if ($filtre_energie && strtolower($ligne['energie']) !== strtolower($filtre_energie)) {
            return false;
        }

        // Filtre prix
        if ($filtre_prixmax !== '' && $ligne['prix_personne'] > floatval($filtre_prixmax)) {
            return false;
        }

        // Filtre durée
        if ($filtre_dureemax !== '') {
            $heure_dep = strtotime($ligne['heure_depart']);
            $heure_arr = strtotime($ligne['heure_arrivee']);
            $duree_covoiturage = $heure_arr - $heure_dep;

            list($h, $m) = explode(':', $filtre_dureemax);
            $duree_max_sec = $h * 3600 + $m * 60;

            if ($duree_covoiturage > $duree_max_sec) {
                return false;
            }
        }

        // Filtre note minimale
        if ($filtre_notemin !== '' && $ligne['note'] < floatval($filtre_notemin)) {
            return false;
        }

        return true;
    });

    

    // Récupération de la date de départ recherchée
    $date_recherchee = $_SESSION['date_depart_recherchee'];
    $resultats_exacts = [];
    $resultats_proches = [];

    // Séparer les résultats exacts et proches
    if (!empty($resultats)) {
        echo '<h2>Résultats de la recherche :</h2>';

        foreach ($resultats as $ligne) {
            if ($ligne['date_depart'] === $date_recherchee) {
                $resultats_exacts[] = $ligne;
            } else {
                $resultats_proches[] = $ligne;
            }
        }

        // Vérifier si les résultats exacts et proches sont vides
        if (empty($resultats_exacts) && empty($resultats_proches)) {
            echo '<p>Aucun trajet ne correspond à votre recherche avec ces filtres.</p>';
            
        } else {
            // Affichage des résultats exacts
            if (!empty($resultats_exacts)) {
                echo '<p class="ajustement2">Nombre de résultats exacts : ' . count($resultats) . '</p>';
                echo '<h3 class="ajustement3">Résultats exacts :</h3>';
                // Débogage: Afficher le nombre de résultats après application des filtres
                
                foreach ($resultats_exacts as $ligne) {
                    afficherCovoiturage($ligne);
                }
            } else {
                echo '<p class="ajustement2">Aucun covoiturage trouvé à cette date exacte.</p>';
                // Débogage: Afficher le nombre de résultats après application des filtres
                echo '<p class="ajustement2">Nombre de résultats proches : ' . count($resultats) . '</p>';
                // if (!empty($resultats_proches)) {
                //     echo '<p>Voulez-vous voir les covoiturages avec une date proche ?</p>';
                //     echo '<button class="button" id="btn_oui" onclick="afficherCovoituragesProches()">OUI</button>';
                //     echo '<button class="button" id="btn_non" onclick="cacherBoutons()">NON</button>';
                // }
            }

            // Affichage des covoiturages proches
            if (!empty($resultats_proches)) {
                // echo '<div id="covoiturages_proches" style="display:none;">';
                echo '<h2>Covoiturages avec une date proche :</h2>';
                foreach ($resultats_proches as $ligne) {
                    afficherCovoiturage($ligne);
                }
                // echo '</div>';
            }
        }
    } else {
        // Si aucun résultat dans la session après filtrage
         
        echo '<p class="ajustement2">Aucun covoiturage trouvé entre ces villes.</p>';// Débogage: Afficher le nombre de résultats après application des filtres
        echo '<p class="ajustement2">Nombre de résultats : ' . count($resultats) . '</p>';
    }
}
unset($_SESSION['recherche_effectuee']);

function afficherCovoiturage($ligne) {
    echo '<div class="user-info">';
    echo '<div class="user-info-content">';
    $chemin_photo = '../uploads/';
    echo '<div class="user-photo">';
    echo '<img src="' . htmlspecialchars($chemin_photo . $ligne['photo']) . '" alt="Photo de ' . htmlspecialchars($ligne['pseudo']) . '" width="auto" height="300">';
    echo '</div>';
    
    echo '<div class="user-details">';
    echo '<p><strong> Pseudo du chauffeur : </strong>' . htmlspecialchars($ligne['pseudo']) . '</p>';
    echo '<p><strong> Note du chauffeur : </strong>' . htmlspecialchars($ligne['note']) . '</p>';
    echo '<p><strong> Nb de places disponible: </strong>' . htmlspecialchars($ligne['nb_place_dispo']) . '</p>';
    echo '<p><strong> Prix par personne : </strong>' . htmlspecialchars($ligne['prix_personne']) . '</p>';
    echo '<p><strong> Date de départ : </strong>' . htmlspecialchars($ligne['date_depart']) . '</p>';
    echo '<p><strong> Heure de départ : </strong>' . htmlspecialchars($ligne['heure_depart']) . '</p>';
    echo '<p><strong> Heure d\'arrivée : </strong>' . htmlspecialchars($ligne['heure_arrivee']) . '</p>';
    echo '<p><strong> Energie du véhicule : </strong>' . htmlspecialchars($ligne['energie']) . '</p>';
    echo '</div>'; // .user-details
    echo '</div>'; // .user-info-content


    echo '<div class="user-actions">';
    if(isset($_SESSION['user'])){
        echo '<button class="button"><a href="carpool_detail.php?covoiturage_id=' . $ligne['covoiturage_id'] . '">Détails</a></button>';
    }else{
        echo '<button class="button"><a href="login.php">Connectez-vous !</a></button>';
    }
    echo '</div>';
    echo '</div>'; // .user-info
}
?>

<!-- <script>
    function afficherCovoituragesProches() {
        document.getElementById("covoiturages_proches").style.display = "block";
        document.getElementById("btn_oui").style.display = "none"; // Cache le bouton "OUI"
        document.getElementById("btn_non").style.display = "none"; // Cache le bouton "NON"
    }

    function cacherBoutons() {
        document.getElementById("btn_oui").style.display = "none";
        document.getElementById("btn_non").style.display = "none";
    }
</script> -->
</section>
</body>
</html>
