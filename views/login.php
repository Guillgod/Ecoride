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
 
    <form method="POST" action="../index.php">
    <input type="hidden" name="form_type" value="login.php">
        <label for="email">Nom d'utilisateur:</label>
        <input type="text" name="email" required>
        <br>
        <label for="password">Mot de passe:</label>
        <input type="password" name="password" required>
        <br>
        <input type="submit" value="Se connecter">
    </form>
</body>
</html>