<!DOCTYPE html>
<html lang="en">
    <head>
        <title>Login - Ecoride</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body>
 
    <h1>Connexion</h1>
 
    <form method="POST" action="../index.php" enctype="multipart/form-data">

    <input type="hidden" name="form_type" value="creation_user.php">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" required>
        <br>
        <label for="prenom">Prenom :</label>
        <input type="text" name="prenom" required>
        <br>
        <label for="email">E-mail :</label>
        <input type="text" name="email" required>
        <br>
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" required>
        <br>
        <label for="telephone">Telephone :</label>
        <input type="int" name="telephone" required>
        <br>
        <label for="adresse">Adresse :</label>
        <input type="text" name="adresse" required>
        <br>
        <label for="date_naissance">Date de date_de_naissance :</label>
        <input type="date" name="date_naissance" required>
        <br>
        <label for="pseudo">Pseudo :</label>
        <input type="text" name="pseudo" required>
        <br>
        <label for="photo">Photo :</label>
        <input type="file" name="photo" required>
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>