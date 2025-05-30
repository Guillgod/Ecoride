<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas déjà démarrée
}
?>


<!DOCTYPE html5>
<html lang="fr">
    <head>
        <title>Ecoride - Covoiturage responsable </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Noto+Sans+JP:wght@100..900&family=Permanent+Marker&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>


    <body>
        <header>
            <div class="header_content">
    <div class="logo">
        <a href="Page_accueil.php"><img src="../img/Logo.png" alt="logo" width="100" height="100"></a>
        <p>Ecoride</p>
    </div>

    <!-- Bouton burger -->
    <div class="burger" onclick="toggleMenu()">&#9776;</div>

    <!-- Menu principal -->
    <nav>
        <ul class="menu">
            <li><a href="Page_accueil.php">Accueil</a></li>
            <li><a href="carpool_list.php">Covoiturage</a></li>
            <li><a href="User_space.php">Utilisateurs</a></li>
            <?php if(isset($_SESSION['user']) && $_SESSION['user']['compte_employee'] == 'employé'): ?>
                <li><a href="Vue_employee.php">Employé</a></li>
            <?php endif; ?>
            <li><a href="">Contact</a></li>
            <li>
                <?php if(isset($_SESSION['user'])): ?>
                    <button class="button"><a href="logout.php">Déconnexion</a></button>
                <?php else: ?>
                    <button class="button"><a href="login.php">Connexion</a></button>
                <?php endif; ?>
            </li>
        </ul>

        <!-- Menu mobile (initialement caché) -->
        <ul class="menu-mobile">
            <li><a href="Page_accueil.php">Accueil</a></li>
            <li><a href="carpool_list.php">Covoiturage</a></li>
            <li><a href="User_space.php">Utilisateurs</a></li>
            <?php if(isset($_SESSION['user']) && $_SESSION['user']['compte_employee'] == 'employé'): ?>
                <li><a href="Vue_employee.php">Employé</a></li>
            <?php endif; ?>
            <li><a href="">Contact</a></li>
            <li>
                <?php if(isset($_SESSION['user'])): ?>
                    <button class="button"><a href="logout.php">Déconnexion</a></button>
                <?php else: ?>
                    <button class="button"><a href="login.php">Connexion</a></button>
                <?php endif; ?>
            </li>
        </ul>
    </nav>
</div>
        </header>

        <script>
        function toggleMenu() {
            const mobileMenu = document.querySelector('.menu-mobile');
            mobileMenu.classList.toggle('active');
        }
        </script>        


        
    </body>

</html>