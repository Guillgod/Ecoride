<?php
require_once '../models/ModelCreateUser.php';

if (isset($_GET['email'])) {
    $email = $_GET['email'];
    $model = new ModelCreateUser();
    $exists = $model->emailExists($email);
    echo json_encode(['exists' => $exists]);
} else {
    echo json_encode(['exists' => false]);
}
?>