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
        ?>
        <h1>Ajouter une voiture</h1>
         
        <p>Veuillez remplir le formulaire ci-dessous pour ajouter une voiture.</p>

    <form action="AjoutVoiture.php" method="post" enctype="multipart/form-data">
        <?php 
        require_once '../models/ModelCreateCar.php';
        require_once '../controllers/Creation_Car_Controller.php';
        require_once '../models/ModelUser.php';
        require_once '../controllers/UserController.php';
        require_once '../controllers/Creation_User_Controller.php';
        require_once '../models/ModelCreateUser.php';



        $modelCreateCar = new ModelCreateCar();
        $carController = new Creation_Car_Controller($modelCreateCar);
        $modelUser = new ModelUser();
        $userController = new UserController($modelUser);
        $modelCreateUser = new ModelCreateUser();
        $userController = new Creation_User_Controller($modelCreateUser);
        $id = $_SESSION['user']['utilisateur_id'];
        $userData = $modelUser->getUserById($id);

        require_once 'creation_car.php';
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
        
        
            if(isset($_POST['modele']) && isset($_POST['marque']) && isset($_POST['immatriculation'])&& isset($_POST['nb_place_voiture']) && isset($_POST['energie']) && isset($_POST['couleur']) && isset($_POST['date_premiere_immatriculation']) ) { 
        
                $carController->createCarInDatabase();
                $AddedCar=$modelCreateUser-> addCarToUser($userData['utilisateur_id'], $carController->getLastInsertId());
            }
        }
        
        ?>
        <button type="submit" class="button">Enregistrer</button>

    </form>




    </body>
</html>