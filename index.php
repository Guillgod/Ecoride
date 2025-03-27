<?php


require 'Models/ModelUser.php';
require 'controllers/UserController.php';
require 'Models/ModelCreateUser.php';
require 'controllers/Creation_User_controller.php';


$model= new ModelUser();
$controller = new UserController($model);
$controller->login();

$modelCreateUser = new ModelCreateUser();
$controllerCreateUser = new Creation_user_controller($modelCreateUser);
$controllerCreateUser->createUserInDatabase();



