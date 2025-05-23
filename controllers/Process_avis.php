<?php
require_once '../models/ModelEmployee.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = $_POST['id_avis_en_cours'] ?? null;
    $action = $_POST['action'] ?? null;

    if (!$id || !$action) {
        die("Requête invalide.");
    }

    $model = new ModelEmployee();

    if ($action === 'valider') {
        $model->ValiderAvis($id);
    } elseif ($action === 'refuser') {
        $model->RefuserAvis($id);
    }

//     if ($action === 'valider') {
//     $model->ValiderAvis($id);
//     echo "Avis validé avec ID: $id";
//     exit; // temporairement désactive la redirection pour voir le message
// }

    header('Location: ../views/Vue_employee.php');
    exit;
}