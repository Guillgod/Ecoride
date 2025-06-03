

<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login - Ecoride</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body>
    <section>
    <?php
    require_once 'header.php';
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../models/ModelCreateUser.php';
    require '../controllers/Creation_User_controller.php';
    $modelCreateUser = new ModelCreateUser();
    $controllerCreateUser = new Creation_user_controller($modelCreateUser);
    $controllerCreateUser->createUserInDatabase();
    }
    ?>
    
    <h1 >Renseignez vos informations</h1>
    <div class="form-voiture-container"> 
    <form method="POST" action="creation_user.php" enctype="multipart/form-data">

        <div class="champ-voiture">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required>
        <br>
        </div>
        <div class="champ-voiture">
        <label for="prenom">Prenom :</label>
        <input type="text" name="prenom" required>
        <br>
        </div>
        <div class="champ-voiture">
        <label for="email">E-mail :</label>
        <input type="text" name="email" required>
        <br>
        </div>
        <div class="champ-voiture">
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" required>
        <br>
        </div>
        <div class="champ-voiture">
        <label for="telephone">Telephone :</label>
        <input type="number" name="telephone" required>
        <br>
        </div>
        <div class="champ-voiture">
        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" required>
        <br>
        </div>
        <div class="champ-voiture">
        <label for="date_naissance">Date de date_de_naissance :</label>
        <input type="date" name="date_naissance" required>
        <br>
        </div>
        <div class="champ-voiture">
        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" required>
        <br>
        </div>
        <div class="champ-voiture">
        <label for="preferences">Préférences :</label>
        <textarea name="preferences" rows="5" cols="30"></textarea>
        <br>
        </div>
        <div class="champ-voiture">
        <label for="photo">Photo :</label>
        <input type="file" name="photo" required>
        <br>
        </div>
        <div class="champ-voiture">
        <label for="role">Rôle :</label>
        <select name="role" id="role" required>
            <option value ="">--Sélectionnez un rôle--</option>
            <option value="chauffeur">Chauffeur</option>
            <option value="passager">Passager</option>
            <option value="passager&chauffeur">Passager et Chauffeur</option>
        </select>
        <br>
        </div>
        <div class="champ-voiture">
        <label for="animal">Acceptez-vous les animaux ? :</label>
        <select name="animal" id="animal" required>
            <option value ="">--Sélectionnez votre choix--</option>
            <option value="oui animal">Oui</option>
            <option value="non animal">Non</option>
        </select>
        <br>
        </div>
        <div class="champ-voiture">
        <label for="fumeur">Acceptez-vous les fumeurs ? :</label>
        <select name="fumeur" id="fumeur" required>
            <option value ="">--Sélectionnez votre choix--</option>
            <option value="oui">Oui</option>
            <option value="non">Non</option>
        </select>
        </div>
        <div id="form-voiture"></div>

        <div class="button-container">
        <input class="button" type="submit" value="Créer le compte">
        </div>
        <!-- Conteneur où le formulaire voiture sera chargé -->
        
    </form>
    </div>
    <!-- JS pour charger le formulaire du chauffeur -->
    </section>
     
    <?php
    require_once 'footer.php';
    ?>
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

    