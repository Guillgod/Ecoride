<?php
session_start();

require_once '../models/ModelCreateCarpool.php';
require_once 'Creation_Carpool_Controller.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $modelCreateCarpool = new ModelCreateCarpool();
    $controller = new Creation_Carpool_Controller($modelCreateCarpool);

    $resultats = $controller->displayCarpool();
    $_SESSION['resultats'] = $resultats;

    if (isset($_POST['date_depart'])) {
        $_SESSION['date_depart_recherchee'] = $_POST['date_depart'];
    }
    $_SESSION['recherche_effectuee'] = true;
    header('Location: ../views/carpool_list.php');
    exit;
}
?>