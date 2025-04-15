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

    // Débogage: Afficher le nombre de résultats après application des filtres
    echo '<p>Nombre de résultats : ' . count($resultats) . '</p>';

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
                echo '<h3>Résultats exacts :</h3>';
                foreach ($resultats_exacts as $ligne) {
                    afficherCovoiturage($ligne);
                }
            } else {
                echo '<p>Aucun covoiturage trouvé à cette date exacte.</p>';
                if (!empty($resultats_proches)) {
                    echo '<p>Voulez-vous voir les covoiturages avec une date proche ?</p>';
                    echo '<button class="button" id="btn_oui" onclick="afficherCovoituragesProches()">OUI</button>';
                    echo '<button class="button" id="btn_non" onclick="cacherBoutons()">NON</button>';
                }
            }

            // Affichage des covoiturages proches
            if (!empty($resultats_proches)) {
                echo '<div id="covoiturages_proches" style="display:none;">';
                echo '<h2>Covoiturages avec une date proche :</h2>';
                foreach ($resultats_proches as $ligne) {
                    afficherCovoiturage($ligne);
                }
                echo '</div>';
            }
        }
    } else {
        // Si aucun résultat dans la session après filtrage
        echo '<p>Aucun covoiturage trouvé entre ces villes avec les filtres appliqués.</p>';
    }
}
unset($_SESSION['recherche_effectuee']);

function afficherCovoiturage($ligne) {
    $chemin_photo = '../uploads/';
    echo '<div>';
    echo '<img src="' . $chemin_photo . htmlspecialchars($ligne['photo']) . '" alt="Photo de ' . htmlspecialchars($ligne['pseudo']) . '">';
    echo '<p> Pseudo du chauffeur : ' . htmlspecialchars($ligne['pseudo']) . '</p>';
    echo '<p> Note du chauffeur : ' . htmlspecialchars($ligne['note']) . '</p>';
    echo '<p> Nb de places disponible: ' . htmlspecialchars($ligne['nb_place_dispo']) . '</p>';
    echo '<p> Prix par personne : ' . htmlspecialchars($ligne['prix_personne']) . '</p>';
    echo '<p> Date de départ : ' . htmlspecialchars($ligne['date_depart']) . '</p>';
    echo '<p> Heure de départ : ' . htmlspecialchars($ligne['heure_depart']) . '</p>';
    echo '<p> Heure d\'arrivée : ' . htmlspecialchars($ligne['heure_arrivee']) . '</p>';
    echo '<p> Energie du véhicule : ' . htmlspecialchars($ligne['energie']) . '</p>';
    if(isset($_SESSION['user'])){
        echo '<button class="button"><a href="carpool_detail.php?covoiturage_id=' . $ligne['covoiturage_id'] . '">Détails</a></button>';
    }else{
        echo '<button class="button"><a href="login.php">Connectez-vous !</a></button>';
    }
    
    echo '</div>';
}
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
