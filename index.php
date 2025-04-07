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
             
            case 'creation_user.php':
                $modelCreateUser = new ModelCreateUser();
                $controllerCreateUser = new Creation_user_controller($modelCreateUser);
                $controllerCreateUser->createUserInDatabase();
                break;
            // case 'creation_car.php':
            //     $modelCreateCar = new ModelCreateCar();
            //     $controllerCreateCar = new Creation_Car_Controller($modelCreateCar);
            //     $controllerCreateCar->createCarInDatabase();
            //     break;
             

            
        }
    }
}
