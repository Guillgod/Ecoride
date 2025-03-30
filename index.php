<?php


require 'models/ModelUser.php';
require 'controllers/UserController.php';
require 'models/ModelCreateUser.php';
require 'controllers/Creation_User_controller.php';
require 'models/ModelCreateCar.php';
require 'controllers/Creation_Car_Controller.php';
require 'models/ModelCreateCarpool.php';
require 'controllers/Creation_Carpool_Controller.php';



if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form_type'])) {
        switch ($_POST['form_type']) {
            case 'login.php':
                $modelUser = new ModelUser();
                $controllerUser = new UserController($modelUser);
                $controllerUser->login();
                break;
            case 'creation_user.php':
                $modelCreateUser = new ModelCreateUser();
                $controllerCreateUser = new Creation_user_controller($modelCreateUser);
                $controllerCreateUser->createUserInDatabase();
                break;
            case 'creation_car.php':
                $modelCreateCar = new ModelCreateCar();
                $controllerCreateCar = new Creation_Car_Controller($modelCreateCar);
                $controllerCreateCar->createCarInDatabase();
                break;
            case 'creation_carpool.php':                
                $modelCreateCarpool = new ModelCreateCarpool();
                $controllerCreateCarpool = new Creation_Carpool_Controller($modelCreateCarpool);
                $controllerCreateCarpool->createCarpoolInDatabase();
                break;
            case 'Barre_de_recherche.php':
                $modelCreateCarpool = new ModelCreateCarpool();
                $controllerAffichageCarpool = new Creation_Carpool_Controller($modelCreateCarpool);
                $resultats = $controllerAffichageCarpool->displayCarpool(); // Récupérer les résultats
                session_start(); // Démarrer la session si ce n'est pas déjà fait

                $_SESSION['resultats'] = $resultats; // Stocker les résultats dans la session
                header('Location: views/carpool_list.php'); // Rediriger vers carpool_list.php
                // exit; // Assurez-vous de sortir après la redirection
                
            
        }
    }
}
