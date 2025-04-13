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

if ($resultats) {
    $chemin_photo = '../uploads/';
    echo '<div>';
    echo '<img src="../uploads/' . htmlspecialchars($resultats['photo']) . '" alt="Photo de ' . htmlspecialchars($resultats['pseudo']) . '" width="auto" height="300">';
    echo '<p>Pseudo du chauffeur :' . htmlspecialchars($resultats['pseudo']) . '</p>';
    echo '<p>Nom :' . htmlspecialchars($resultats['nom']) . '</p>';
    echo '<p>Prénom :' . htmlspecialchars($resultats['prenom']) . '</p>';
    echo '<p>Email :' . htmlspecialchars($resultats['email']) . '</p>';
    echo '<p>Téléphone :' . htmlspecialchars($resultats['telephone']) . '</p>';
    echo '<p>Adresse :' . htmlspecialchars($resultats['adresse']) . '</p>';
    echo '<p>Date de naissance :' . htmlspecialchars($resultats['date_naissance']) . '</p>';
    echo '<p>Rôle :' . htmlspecialchars($resultats['role']) . '</p>';
    echo '<p>id véhicule géré à modifier plus tard :' . htmlspecialchars($resultats['gere']) . '</p>';
    echo '<p>Note du chauffeur :' .  $resultats['note'] . '</p>';
    echo '</div>';
} else {
    echo '<p>Aucun utilisateur spécifié.</p>';
}

?>






<!-- Zone où le formulaire voiture sera injecté -->



</body>
</html>
