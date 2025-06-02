<?php
    session_start(); // Démarrage de la session
    require_once 'header.php';
    ?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login - Ecoride</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body>
    
    <section>
    <div class="user-info2">
    <div class="form-voiture-container">
    <h2>Connexion</h2>
 
    <form method="POST" action="login.php">
    <input type="hidden" name="form_type" value="login.php">
        <div class="champ-voiture">
        <label for="email">Nom d'utilisateur:</label>
        <input type="text" name="email" required>
        <br>
        </div>
        <div class="champ-voiture">
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" required>
        <br>
        </div>
        <div class="button-container">
        <input class="button" type="submit"  value="Se connecter">
        </div>
    </form>
    
    <?php
    require_once '../models/ModelUser.php';
    require_once '../controllers/UserController.php';
    
    
    $modelUser = new ModelUser();
    $controllerUser = new UserController($modelUser);
    $controllerUser->login();
?>

    <div class="champ-voiture">
    <p>Pas encore inscrit ?</p>
     <a  href="creation_user.php">Inscrivez-vous ici</a>
    </div>
    </form>
    <div class="form-voiture-container"></div>
    </div>
    </section>
</body>
</html>