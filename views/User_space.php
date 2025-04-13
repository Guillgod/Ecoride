<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/style.css" rel="stylesheet">
    <title>Sélection du profil</title>
</head>
<body>
<?php
require_once 'header.php';
?>

<h3>Sélection du profil</h3>
<form method="POST" action="User_space.php" class="radio-container">

    <label>
        <input type="radio" name="selection_role" value="passager">
        Passager
    </label><br>

    <label>
        <input type="radio" name="selection_role" value="chauffeur">
        Chauffeur
    </label><br>

    <label>
        <input type="radio" name="selection_role" value="passager_et_chauffeur">
        Passager et Chauffeur
    </label><br>
    <div id="form-voiture">
        <div id="form-voiture-inner"></div>
    </div>
    <input class="button" type="submit" value="Valider">

</form>

<!-- Zone où le formulaire voiture sera injecté -->


<script>
document.querySelectorAll('input[name="selection_role"]').forEach((radio) => {
  radio.addEventListener('change', function () {
    const role = this.value;
    const container = document.getElementById('form-voiture-inner');

    if (role === 'chauffeur' || role === 'passager_et_chauffeur') {
      fetch('creation_car.php')
        .then(response => response.text())
        .then(html => {
          container.innerHTML = html;
          container.classList.remove('visible'); // Réinitialise
          // Laisse le DOM respirer, puis applique la classe visible
          requestAnimationFrame(() => {
            requestAnimationFrame(() => {
              container.classList.add('visible');
            });
          });
        })
        .catch(error => {
          console.error('Erreur lors du chargement du formulaire voiture:', error);
        });
    } else {
      container.classList.remove('visible');
      setTimeout(() => {
        container.innerHTML = '';
      }, 400);
    }
  });
});
</script>

</body>
</html>
