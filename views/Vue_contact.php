<!DOCTYPE <!DOCTYPE html>
<html lang="fr">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body>
    <?php 
    require_once '../views/header.php';
    ?>
    <section>
    <div class="form-admin">
  
  <div class="form-admin-content">
    <h2 class="titre-contact">Nous Contacter</h2>
    <form action="" method="post">
      <div class="form-fields">
        <div class="contact-nom">
          <input type="text" id="Nom" name="Nom" placeholder="Nom" required>
          <input type="text" id="Prenom" name="Prenom" placeholder="Prénom" required>
        </div>

        <div class="contact-email">
          <input type="email" id="email" name="email" placeholder="Email" required>
          <textarea id="Message" name="Message" placeholder="Message" required></textarea>
        </div>
      </div>

      <div class="button-container">
        <button class="button" type="submit">Envoyer</button>
      </div>
    </form>
  </div>
</div>
    </section>
     <?php
    require_once '../views/footer.php';
    ?>
    </body>
</html>