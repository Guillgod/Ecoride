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
    require '../controllers/Creation_User_Controller.php';
    $modelCreateUser = new ModelCreateUser();
    $controllerCreateUser = new Creation_user_controller($modelCreateUser);
    $controllerCreateUser->createUserInDatabase();
}
?>

<h1>Renseignez vos informations</h1>
<div class="form-voiture-container"> 
<form id="user-form" method="POST" action="creation_user.php" enctype="multipart/form-data">
    <div class="champ-voiture">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required>
    </div>
    <div class="champ-voiture">
        <label for="prenom">Prenom :</label>
        <input type="text" name="prenom" required>
    </div>
    <div class="champ-voiture">
        <label for="email">E-mail :</label>
        <input type="text" name="email" id="email" required>
        <p id="email-feedback" style="margin: 0;"></p>
    </div>
    <div class="champ-voiture">
        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>
        <p id="form-error" role="alert" style="color:red; display:none;">Veuillez corriger les erreurs de mot de passe avant de continuer.</p>
        <ul id="password-conditions" style="list-style-type: none; padding-left: 0;">
            <li id="length" class="invalid">❌ Le mot de passe doit contenir au moins 8 caractères (!, ?, @, #, $, %, ^, &, *)</li>
            <li id="uppercase" class="invalid">❌ Il doit inclure au moins une lettre majuscule.</li>
            <li id="special" class="invalid">❌ Un caractère spécial est requis.</li>
            <li id="number" class="invalid">❌ Il doit également comporter au moins un chiffre.</li>
        </ul>
    </div>
    <div class="champ-voiture">
        <label for="confirm_password">Répétez le mot de passe :</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <span id="password-match-error" style="color: red; display: none;">Le mot de passe n'est pas identique.</span>
    </div>
    <div class="champ-voiture">
        <label for="telephone">Téléphone :</label>
        <input type="number" name="telephone" required>
    </div>
    <div class="champ-voiture">
        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" required>
    </div>
    <div class="champ-voiture">
        <label for="date_naissance">Date de naissance :</label>
        <input type="date" name="date_naissance" required>
    </div>
    <div class="champ-voiture">
        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" required>
    </div>
    <div class="champ-voiture">
        <label for="preferences">Préférences :</label>
        <textarea name="preferences" rows="5" cols="30"></textarea>
    </div>
    <div class="champ-voiture">
        <label for="photo">Photo :</label>
        <input type="file" name="photo" accept="image/*" required id="photo">
            <script>
            document.getElementById('photo').addEventListener('change', function () {
                if (this.files[0].size > 2000000) {
                    alert("Le fichier est trop lourd. Merci de choisir une image de moins de 2 Mo.");
                    this.value = ""; // Réinitialise le champ
                }
            });
            </script>

    </div>
    <div class="champ-voiture">
        <label for="role">Rôle :</label>
        <select name="role" id="role" required>
            <option value ="">--Sélectionnez un rôle--</option>
            <option value="chauffeur">Chauffeur</option>
            <option value="passager">Passager</option>
            <option value="passager&chauffeur">Passager et Chauffeur</option>
        </select>
    </div>
    <div class="champ-voiture">
        <label for="animal">Acceptez-vous les animaux ? :</label>
        <select name="animal" id="animal" required>
            <option value ="">--Sélectionnez votre choix--</option>
            <option value="oui animal">Oui</option>
            <option value="non animal">Non</option>
        </select>
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
        <input class="button" id="submitBtn" type="submit" value="Créer le compte">
    </div>
</form>
</div>
</section>

<?php require_once 'footer.php'; ?>

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

const passwordInput = document.getElementById("password");
const confirmPasswordInput = document.getElementById("confirm_password");
const passwordMatchError = document.getElementById("password-match-error");
const form = document.getElementById("user-form");
const errorMessage = document.getElementById("form-error");
const submitBtn = document.getElementById("submitBtn");

const conditions = {
    length: document.getElementById("length"),
    uppercase: document.getElementById("uppercase"),
    special: document.getElementById("special"),
    number: document.getElementById("number")
};

passwordInput.addEventListener("input", updatePasswordConditions);
confirmPasswordInput.addEventListener("input", validatePasswordsMatch);
passwordInput.addEventListener("input", validatePasswordsMatch); 

function updatePasswordConditions() {
    const value = passwordInput.value;

    updateCondition(conditions.length, value.length >= 8);
    updateCondition(conditions.uppercase, /[A-Z]/.test(value));
    updateCondition(conditions.special, /[!@#$%^&*?]/.test(value));
    updateCondition(conditions.number, /\d/.test(value));

    validatePasswordsMatch();
}

function updateCondition(element, isValid) {
    element.classList.toggle("valid", isValid);
    element.classList.toggle("invalid", !isValid);
    element.innerHTML = (isValid ? "✅ " : "❌ ") + element.textContent.slice(2);
}

function validatePasswordsMatch() {
    const match = passwordInput.value === confirmPasswordInput.value;
    passwordMatchError.style.display = match ? "none" : "inline";
    confirmPasswordInput.classList.toggle("invalid", !match);
}

form.addEventListener("submit", function(event) {
    const password = passwordInput.value;
    const confirmPassword = confirmPasswordInput.value;

    const isLongEnough = password.length >= 8;
    const hasUppercase = /[A-Z]/.test(password);
    const hasSpecialChar = /[!@#$%^&*?]/.test(password);
    const hasNumber = /\d/.test(password);
    const passwordsMatch = password === confirmPassword;

    const allValid = isLongEnough && hasUppercase && hasSpecialChar && hasNumber && passwordsMatch;

    if (!allValid) {
        event.preventDefault();
        errorMessage.style.display = "block";
        passwordMatchError.style.display = passwordsMatch ? "none" : "inline";

        setTimeout(() => {
            passwordInput.closest('.champ-voiture').scrollIntoView({ behavior: "smooth", block: "center" });
            passwordInput.focus();
        }, 100);
    }
});

//Vérifie que l'email n'est pas déjà utilisé
document.addEventListener('DOMContentLoaded', function () {
    const emailInput = document.getElementById('email');
    const emailFeedback = document.getElementById('email-feedback');

    emailInput.addEventListener('input', function () {
    const email = emailInput.value;

    if (email.length > 3) {
        fetch(`../controllers/check_email.php?email=${encodeURIComponent(email)}`)
            .then(response => response.json())
            .then(data => {
                if (data.exists) {
                    emailFeedback.textContent = "❌ Cette adresse e-mail est déjà utilisée.";
                    emailFeedback.style.color = "red";
                } else {
                    emailFeedback.textContent = "✅ Adresse e-mail disponible.";
                    emailFeedback.style.color = "green";
                }
            })
            .catch(error => {
                console.error('Erreur de vérification e-mail :', error);
                emailFeedback.textContent = "";
            });
    } else {
        emailFeedback.textContent = "";
    }
    });
});
</script>
</body>
</html>
