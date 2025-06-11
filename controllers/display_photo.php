<?php
require_once '../models/Database.php';

if (!isset($_GET['id'])) {
    http_response_code(400);
    exit('ID manquant');
}

$id = intval($_GET['id']);
$db = Database::connect();

// Récupérer la photo depuis la base
$stmt = $db->prepare("SELECT photo FROM utilisateur WHERE utilisateur_id = :id");
$stmt->bindParam(':id', $id);
$stmt->execute();
$photo = $stmt->fetchColumn();

if ($photo) {
    // Détection du type MIME via getimagesizefromstring()
    $info = getimagesizefromstring($photo);
    if ($info && isset($info['mime'])) {
        header("Content-Type: " . $info['mime']);
        echo $photo;
    } else {
        // Si échec de détection du type MIME
        header("Content-Type: application/octet-stream");
        echo $photo;
    }
} else {
    http_response_code(404);
    echo "Photo non trouvée.";
}
