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
// $resultats2=$userController->getUserInformationWithoutCarFromDatabase($_SESSION['user']['email']);

 
        if (is_array($resultats)&&count($resultats)>0) {
            $chemin_photo = '../uploads/';
            $utilisateur=$resultats[0] ;
            echo '<div>';
            echo '<h2>Vos informations</h2>';
            echo '<img src="../uploads/' . htmlspecialchars($utilisateur['photo']) . '" alt="Photo de ' . htmlspecialchars($utilisateur['pseudo']) . '" width="auto" height="300">';
            echo '<p>Pseudo du chauffeur :' . htmlspecialchars($utilisateur['pseudo']) . '</p>';
            echo '<p>Nom :' . htmlspecialchars($utilisateur['nom']) . '</p>';
            echo '<p>Prénom :' . htmlspecialchars($utilisateur['prenom']) . '</p>';
            echo '<p>Email :' . htmlspecialchars($utilisateur['email']) . '</p>';
            echo '<p>Téléphone :' . htmlspecialchars($utilisateur['telephone']) . '</p>';
            echo '<p>Adresse :' . htmlspecialchars($utilisateur['adresse']) . '</p>';
            echo '<p>Date de naissance :' . htmlspecialchars($utilisateur['date_naissance']) . '</p>';
            echo '<p>Rôle :' . htmlspecialchars($utilisateur['role']) . '</p>';
            echo '<p>Note du chauffeur :' .  $utilisateur['note'] . '</p>';
            echo '<button class="button" onclick="window.location.href=\'Modify_user_information.php \'">Modifier vos informations</button>';
            
            if ($utilisateur['role'] == 'passager'  ) {
                echo '<p>Vous n\'êtes pas chauffeur, vous ne pouvez pas créer de covoiturage. Veuillez modifier votre rôle.</p>';}
            if ($utilisateur['role'] == 'chauffeur' || $utilisateur['role'] == 'passager&chauffeur') {
            echo '<button class="button" onclick="window.location.href=\'creation_carpool.php\'">Créer un covoiturage</button>';
            echo '<button class="button" onclick="window.location.href=\'AjoutVoiture.php\'">Ajoutez une voiture</button>';
            }
            echo '</div>';
            

            // Afficher les voitures gérées par l'utilisateur
            echo '<h2>Voitures gérées</h2>';
            $voituresAffichees=[]; //Permet de n'afficher qu'une seule fois la voiture
                foreach ($resultats as $voiture) {
                    $idVoiture = $voiture['id_voiture_possede_utilisateur'];
                    if ($idVoiture !== null && !isset($voituresAffichees[$idVoiture])) {
                        echo '<div>';
                        echo '<p>Marque : ' . htmlspecialchars($voiture['marque']) . '</p>';
                        echo '<p>Modèle : ' . htmlspecialchars($voiture['modele']) . '</p>';
                        echo '<p>Immatriculation : ' . htmlspecialchars($voiture['immatriculation']) . '</p>';
                        echo '<p>Nombre de places : ' . htmlspecialchars($voiture['nb_place_voiture']) . '</p>';
                        echo '<p>Type de véhicule : ' . htmlspecialchars($voiture['energie']) . '</p>';
                        echo '<p>Couleur : ' . htmlspecialchars($voiture['couleur']) . '</p>';
                        echo '</div>';
                        $voituresAffichees[$idVoiture]=true;;
                    } 


                
                } 
            
            // Afficher les covoiturages en tant que chauffeur
            echo '<h2>Vos covoiturages comme chauffeur</h2>';
            $idCovoituragesAffiches=[]; //Permet de n'afficher qu'une seule fois le covoiturage
            foreach ($resultats as $resultat) {
                $idCovoiturage =$resultat['id_covoiturage_utilise_voiture'];
                if($idCovoiturage !== null && !isset($idCovoituragesAffiches[$idCovoiturage])) {
                    echo '<div>';
                    echo '<p>Lieu de départ : ' . htmlspecialchars($resultat['lieu_depart']) . '</p>';
                    echo '<p>Lieu d\'arrivée : ' . htmlspecialchars($resultat['lieu_arrivee']) . '</p>';
                    echo '<p>Date de départ : ' . htmlspecialchars($resultat['date_depart']) . '</p>';
                    echo '<p>Heure de départ : ' . htmlspecialchars($resultat['heure_depart']) . '</p>';
                    echo '<p>Date d\'arrivée : ' . htmlspecialchars($resultat['date_arrivee']) . '</p>';
                    echo '<p>Heure d\'arrivée : ' . htmlspecialchars($resultat['heure_arrivee']) . '</p>';
                    echo '<p>Nombre de places disponibles : ' . htmlspecialchars($resultat['nb_place_dispo']) . '</p>';
                    echo '<p>Prix par personne : ' . htmlspecialchars($resultat['prix_personne']) . '</p>';
                    echo '</div>';
                    $idCovoituragesAffiches[$idCovoiturage]=true;;
                } 
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
            if (empty($voituresAffichees)) {
                echo '<p>Aucune voiture gérée actuellement.</p>';
            }
            
            if (empty($idCovoituragesAffiches)) {
                echo '<p>Vous ne participez à aucun covoiturage en tant que chauffeur.</p>';
            }
        }
     
    else {
    '<p>Vous n\'êtes pas identifié. Veuillez vous connecter à votre compte</p>';
    echo '<button class="button" onclick="window.location.href=\'login.php\'">Se connecter</button>';
}
?>






<!-- Zone où le formulaire voiture sera injecté -->



</body>
</html>
