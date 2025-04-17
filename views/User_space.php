<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/style.css" rel="stylesheet">
    <title>Sélection du profil</title>
</head>
<body>
<?php
require_once 'header.php';
?>


<p>Bienvenue dans votre espace utilisateur !</p>

<?php
require_once '../models/ModelUser.php';
require_once '../controllers/UserController.php';

$modeluser = new ModelUser();
$userController = new UserController($modeluser);


$resultats=$userController->getUserInformationFromDatabase($_SESSION['user']['email']);
$resultats2=$userController->getUserInformationWithoutCarFromDatabase($_SESSION['user']['email']);

 
    
     
        if (is_array($resultats)) {
            $chemin_photo = '../uploads/';
            echo '<div>';
            echo '<h2>Vos informations</h2>';
            echo '<img src="../uploads/' . htmlspecialchars($resultats['photo']) . '" alt="Photo de ' . htmlspecialchars($resultats['pseudo']) . '" width="auto" height="300">';
            echo '<p>Pseudo du chauffeur :' . htmlspecialchars($resultats['pseudo']) . '</p>';
            echo '<p>Nom :' . htmlspecialchars($resultats['nom']) . '</p>';
            echo '<p>Prénom :' . htmlspecialchars($resultats['prenom']) . '</p>';
            echo '<p>Email :' . htmlspecialchars($resultats['email']) . '</p>';
            echo '<p>Téléphone :' . htmlspecialchars($resultats['telephone']) . '</p>';
            echo '<p>Adresse :' . htmlspecialchars($resultats['adresse']) . '</p>';
            echo '<p>Date de naissance :' . htmlspecialchars($resultats['date_naissance']) . '</p>';
            echo '<p>Rôle :' . htmlspecialchars($resultats['role']) . '</p>';
            echo '<p>Note du chauffeur :' .  $resultats['note'] . '</p>';
            echo '<button class="button" onclick="window.location.href=\'creation_carpool.php\'">Créer un covoiturage</button>';
            echo '<button class="button" onclick="window.location.href=\'Modify_user_information.php \'">Modifier vos informations</button>';
            echo '</div>';
            

            
            if ($resultats['gere']) {
                echo '<h2>Voitures gérées</h2>';
                echo '<p>Marque : ' . htmlspecialchars($resultats['marque']) . '</p>';
                echo '<p>Modèle : ' . htmlspecialchars($resultats['modele']) . '</p>';
                echo '<p>Immatriculation : ' . htmlspecialchars($resultats['immatriculation']) . '</p>';
                echo '<p>Nombre de places : ' . htmlspecialchars($resultats['nb_place_voiture']) . '</p>';
                echo '<p>Type de véhicule : ' . htmlspecialchars($resultats['energie']) . '</p>';
                echo '<p>Couleur : ' . htmlspecialchars($resultats['couleur']) . '</p>';
            } else {
                echo '<p>Aucune voiture gérée.</p>';
            }

            if($resultats['utilise']) {
                echo '<h2>Vos covoiturages comme chauffeur</h2>';
                echo '<p>Lieu de départ : ' . htmlspecialchars($resultats['lieu_depart']) . '</p>';
                echo '<p>Lieu d\'arrivée : ' . htmlspecialchars($resultats['lieu_arrivee']) . '</p>';
                echo '<p>Date de départ : ' . htmlspecialchars($resultats['date_depart']) . '</p>';
                echo '<p>Heure de départ : ' . htmlspecialchars($resultats['heure_depart']) . '</p>';
                echo '<p>Date d\'arrivée : ' . htmlspecialchars($resultats['date_arrivee']) . '</p>';
                echo '<p>Heure d\'arrivée : ' . htmlspecialchars($resultats['heure_arrivee']) . '</p>';
                echo '<p>Nombre de places disponibles : ' . htmlspecialchars($resultats['nb_place_dispo']) . '</p>';
                echo '<p>Prix par personne : ' . htmlspecialchars($resultats['prix_personne']) . '</p>';
            }

            $passengerCovoiturages = $userController->getPassengerCovoiturageFromDatabase($_SESSION['user']['utilisateur_id']);

            if (!empty($passengerCovoiturages)) {
                echo '<h2>Vos covoiturages en tant que passager</h2>';
                foreach ($passengerCovoiturages as $covoiturage) {
                    echo '<div>';
                    echo '<h3>Vous participer à ce covoiturage :</h3>';
                    echo '<p>Lieu de départ : ' . htmlspecialchars($covoiturage['lieu_depart']) . '</p>';
                    echo '<p>Lieu d\'arrivée : ' . htmlspecialchars($covoiturage['lieu_arrivee']) . '</p>';
                    echo '<p>Date de départ : ' . htmlspecialchars($covoiturage['date_depart']) . '</p>';
                    echo '<p>Heure de départ : ' . htmlspecialchars($covoiturage['heure_depart']) . '</p>';
                    echo '<p>Date d\'arrivée : ' . htmlspecialchars($covoiturage['date_arrivee']) . '</p>';
                    echo '<p>Heure d\'arrivée : ' . htmlspecialchars($covoiturage['heure_arrivee']) . '</p>';
                    echo '<p>Nombre de places disponibles : ' . htmlspecialchars($covoiturage['nb_place_dispo']) . '</p>';
                    echo '<p>Prix par personne : ' . htmlspecialchars($covoiturage['prix_personne']) . '</p>';
                    echo '</div>';
                }
            } else {
                echo '<p>Vous ne participez à aucun covoiturage en tant que passager.</p>';
            }
        }
    elseif(is_array($resultats2) ){
        
            $chemin_photo = '../uploads/';
            echo '<div>';
            echo '<h2>Vos informations</h2> ';
            echo '<img src="../uploads/' . htmlspecialchars($resultats2['photo']) . '" alt="Photo de ' . htmlspecialchars($resultats2['pseudo']) . '" width="auto" height="300">';
            echo '<p>Pseudo :' . htmlspecialchars($resultats2['pseudo']) . '</p>';
            echo '<p>Nom :' . htmlspecialchars($resultats2['nom']) . '</p>';
            echo '<p>Prénom :' . htmlspecialchars($resultats2['prenom']) . '</p>';
            echo '<p>Email :' . htmlspecialchars($resultats2['email']) . '</p>';
            echo '<p>Téléphone :' . htmlspecialchars($resultats2['telephone']) . '</p>';
            echo '<p>Adresse :' . htmlspecialchars($resultats2['adresse']) . '</p>';
            echo '<p>Date de naissance :' . htmlspecialchars($resultats2['date_naissance']) . '</p>';
            echo '<p>Rôle :' . htmlspecialchars($resultats2['role']) . '</p>';
            echo '<p>Note du chauffeur :' .  $resultats2['note'] . '</p>';
            echo '<p>Vous n\'êtes pas chauffeur ? Modifier votre rôle pour créer un covoiturage</p>';
            echo '<button class="button" onclick="window.location.href=\'Modify_user_information.php \'">Modifier vos informations</button>';
            echo '</div>';
            
        }
    else {
    '<p>Vous n\'êtes pas identifié. Veuillez vous connecter à votre compte</p>';
    echo '<button class="button" onclick="window.location.href=\'login.php\'">Se connecter</button>';
}
?>






<!-- Zone où le formulaire voiture sera injecté -->



</body>
</html>
