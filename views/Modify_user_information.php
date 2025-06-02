<?php
require_once 'header.php';
require_once '../models/ModelUser.php';
require_once '../controllers/UserController.php';
require_once '../models/ModelCreateCar.php';
require_once '../controllers/Creation_Car_Controller.php';
require_once '../controllers/Creation_User_Controller.php';
require_once '../models/ModelCreateUser.php';


$modelUser = new ModelUser();
$userController = new UserController($modelUser);
$modelCreateCar=new ModelCreateCar();
$carController=new Creation_Car_Controller($modelCreateCar);
$modelCreateUser=new ModelCreateUser();
$createUserController=new Creation_user_controller($modelCreateUser);
 

$userData =$userController->getUserInformationFromDatabase($_SESSION['user']['email']);
 $userData = $userData[0]; 

if($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userController->updateUserInDatabase();
     
    if(isset($_POST['role']) && ($_POST['role'] === 'chauffeur' || $_POST['role'] === 'passager&chauffeur' )&& isset($_POST['ajout_vehicule']) ){ 

        $carController->createCarInDatabase();
        $AddedCar=$modelCreateUser-> addCarToUser($userData['utilisateur_id'], $carController->getLastInsertId());
    }

    header('Location: User_space.php');
    exit;
}// On prend le premier résultat, car on s'attend à un seul utilisateur par email
 //Ajouter la voiture à l'utilisateur
 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Modifier vos informations</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <section>
<?php require_once 'header.php'; ?>
<div class="Modify-information">
<div><h2>Modifier vos informations</h2></div>



    <form action="Modify_user_information.php" method="post" enctype="multipart/form-data">
        <div class="dispo-modify-information">
            <!-- Colonne 1 -->
            <div class="colonne">
                <div class="champ-voiture">
                <label><strong>Pseudo :</strong> <input type="text" name="pseudo" value="<?= htmlspecialchars($userData['pseudo']) ?>"></label><br>
                <label><strong>Nom :</strong> <input type="text" name="nom" value="<?= htmlspecialchars($userData['nom']) ?>"></label><br>
                <label><strong>Prénom :</strong> <input type="text" name="prenom" value="<?= htmlspecialchars($userData['prenom']) ?>"></label><br>
                <label><strong>Email :</strong> <input type="email" name="email" value="<?= htmlspecialchars($userData['email']) ?>"></label><br>
                <label><strong>Téléphone :</strong> <input type="text" name="telephone" value="<?= htmlspecialchars($userData['telephone']) ?>"></label><br>
                <label><strong>Adresse :</strong> <input type="text" name="adresse" value="<?= htmlspecialchars($userData['adresse']) ?>"></label><br>
                <label><strong>Date de naissance :</strong> <input type="date" name="date_naissance" value="<?= htmlspecialchars($userData['date_naissance']) ?>"></label><br>
            </div>
            </div>

            <!-- Colonne 2 -->
            <div class="colonne">
            <div class="champ-voiture">
                <label><strong>Photo :</strong> <input type="file" name="photo"></label><br>
                <label><strong>Préférences :</strong></label><br>
                <textarea name="preferences" rows="5" cols="50"><?= htmlspecialchars($userData['preferences']) ?></textarea><br>

                <label><strong>Accepte les fumeurs :</strong>
                    <select name="fumeur" id="fumeur">
                        <option value="oui" <?= $userData['fumeur'] == 'oui' ? 'selected' : '' ?>>Oui</option>
                        <option value="non" <?= $userData['fumeur'] == 'non' ? 'selected' : '' ?>>Non</option>
                    </select>
                </label><br>

                <label><strong>Accepte les animaux :</strong>
                    <select name="animal" id="animal">
                        <option value="oui" <?= $userData['animal'] == 'oui' ? 'selected' : '' ?>>Oui</option>
                        <option value="non" <?= $userData['animal'] == 'non' ? 'selected' : '' ?>>Non</option>
                    </select>
                </label><br>

                <label><strong>Rôle :</strong></label>
                <select name="role" id="role">
                    <option value="chauffeur" <?= $userData['role'] == 'chauffeur' ? 'selected' : '' ?>>Chauffeur</option>
                    <option value="passager" <?= $userData['role'] == 'passager' ? 'selected' : '' ?>>Passager</option>
                    <option value="passager&chauffeur" <?= $userData['role'] == 'passager&chauffeur' ? 'selected' : '' ?>>Passager et Chauffeur</option>
                </select><br>
            </div>
            </div>
        </div>

       
        <!-- En-dessous des colonnes -->
         
        <div id="ajout-vehicule-container" style="display: none;" class="checkbox-wrapper ">
            <div class="button-container">
            <label class="custom-checkbox">
                <input type="checkbox" id="ajout-vehicule" name="ajout_vehicule">
                <span class="checkmark"></span>
                Ajouter un nouveau véhicule
            </label>
            </div>
        </div>

        <div class="center-horizontal" id="form-voiture"></div>
        <div class="button-container">
        <button type="submit" class="button">Enregistrer</button>

        </div>

        
    </form>
</div> <!-- .dispo-modify-information -->

<?php
require_once 'footer.php';
?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('role');
    const ajoutVehiculeContainer = document.getElementById('ajout-vehicule-container');
    const ajoutVehiculeCheckbox = document.getElementById('ajout-vehicule');
    const formVoiture = document.getElementById('form-voiture');

    function updateAjoutVehiculeVisibility() {
        const role = roleSelect.value;
        if (role === 'chauffeur' || role === 'passager&chauffeur') {
            ajoutVehiculeContainer.style.display = 'block';
        } else {
            ajoutVehiculeContainer.style.display = 'none';
            ajoutVehiculeCheckbox.checked = false;
            formVoiture.innerHTML = '';
        }
    }

    function updateFormVoitureVisibility() {
        if (ajoutVehiculeCheckbox.checked) {
            fetch('creation_car.php')
                .then(response => response.text())
                .then(html => {
                    formVoiture.innerHTML = html;
                })
                .catch(error => {
                    console.error('Erreur lors du chargement du formulaire voiture:', error);
                });
        } else {
            formVoiture.innerHTML = '';
        }
    }

    roleSelect.addEventListener('change', updateAjoutVehiculeVisibility);
    ajoutVehiculeCheckbox.addEventListener('change', updateFormVoitureVisibility);

    // Initialisation lors du chargement de la page
    updateAjoutVehiculeVisibility();
});


</script>
</div> <!-- .Modify-information -->
</section>
</body>
</html>


