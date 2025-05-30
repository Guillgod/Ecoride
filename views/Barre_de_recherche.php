<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); 
}
?>

<!DOCTYPE html5>
<html lang="fr">
    <head>
        <title>Ecoride - Covoiturage responsable </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Noto+Sans+JP:wght@100..900&family=Permanent+Marker&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>


    <body>


   <section class="Barrerecherche">    <!--  Avec maintien de l'affichage des champs rentrés par l'utilisateur -->
        <form action="../controllers/Handle_Search_Controller.php" method="post">
            <input type="hidden" name="form_type" value="Barre_de_recherche.php">
            <div class="form-recherche">
                <li><input type="text" id="lieu_depart" name="lieu_depart" required minlength="1" maxlength="50" size="20" placeholder="Départ"  value="<?= isset($_SESSION['form_data']['lieu_depart']) ? htmlspecialchars($_SESSION['form_data']['lieu_depart']) : '' ?>"></li>
                    
                <li><input type="text" id="lieu_arrivee" name="lieu_arrivee" required minlength="1" maxlength="50" size="20" placeholder="Destination" value="<?= isset($_SESSION['form_data']['lieu_arrivee']) ? htmlspecialchars($_SESSION['form_data']['lieu_arrivee']) : '' ?>"></li>
                    
                <li><input type="date" id="date_depart" name="date_depart" required minlength="4" maxlength="8" size="10" value="<?= isset($_SESSION['form_data']['date_depart']) ? htmlspecialchars($_SESSION['form_data']['date_depart']) : '' ?>"></li>
                    


                <button class="button" type="submit">Rechercher</button>
            </div>
        </form>
    </section>
    

            

    </body>
</html>
