<?php
require_once 'header.php';
require_once '../models/ModelUser.php';
require_once '../controllers/UserController.php';


$modelUser = new ModelUser();
$userController = new UserController($modelUser);

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->updateUserInDatabase();
    header('Location: User_space.php');
    exit;
}$userData =$userController->getUserInformationWithoutCarFromDatabase($_SESSION['user']['email']);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier vos informations</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<?php require_once 'header.php'; ?>
<h2>Modifier vos informations</h2>

<form  action="Modify_user_information.php" method="post" enctype="multipart/form-data">
    <label>Pseudo : <input type="text" name="pseudo" value="<?= htmlspecialchars($userData['pseudo']) ?>"></label><br>
    <label>Nom : <input type="text" name="nom" value="<?= htmlspecialchars($userData['nom']) ?>"></label><br>
    <label>Prénom : <input type="text" name="prenom" value="<?= htmlspecialchars($userData['prenom']) ?>"></label><br>
    <label>Email : <input type="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>"></label><br>
    <label>Téléphone : <input type="text" name="telephone" value="<?= htmlspecialchars($userData['telephone']) ?>"></label><br>
    <label>Adresse : <input type="text" name="adresse" value="<?= htmlspecialchars($userData['adresse']) ?>"></label><br>
    <label>Date de naissance : <input type="date" name="date_naissance" value="<?= htmlspecialchars($userData['date_naissance']) ?>"></label><br>
    <label>Photo : <input type="file" name="photo"></label><br>
    <label>Rôle : 
        <select name="role" id="role">
            <option value="chauffeur" <?= $userData['role'] == 'chauffeur' ? 'selected' : '' ?>>Chauffeur</option>
            <option value="passager" <?= $userData['role'] == 'passager' ? 'selected' : '' ?>>Passager</option>
            <option value="passager&chauffeur">Passager et Chauffeur</option>
        </select>
        <div id="form-voiture"></div>
    <button type="submit" class="button">Enregistrer</button>
</form>

<script>
document.getElementById('role').addEventListener('change', function () {
    const role = this.value;
    const container = document.getElementById('form-voiture');

    if (role === 'chauffeur' || role === 'passager&chauffeur') {
        fetch('creation_car.php')
            .then(response => response.text())
            .then(html => {
                container.innerHTML = html;
            })
            .catch(error => {
                console.error('Erreur lors du chargement du formulaire voiture:', error);
            });
    } else {
        container.innerHTML = ''; // Vide si rôle ≠ chauffeur
    }
});
</script>

</body>
</html>


