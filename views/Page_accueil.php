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
    
        <?php
        require_once 'header.php';
        ?>

        <div class="container1">
        <h1>🌿 Bienvenue sur Ecoride – Le covoiturage engagé</h1>
        </div>
        <?php
        require_once 'Barre_de_recherche.php';
        ?>

    <section class="container2">
    <div class="image">
            <img src="../img/voiture21.jpg" class="img-fluid" alt="Voiture roulant sur une route par beau temps">
        </div>

    <div class="text">
    <h2>🎯 Notre mission</h2>
    <p>Chez Ecoride, nous croyons qu’il est possible de se déplacer autrement, en alliant praticité, économies et respect de l’environnement.</p>
    <p>Notre plateforme de covoiturage est conçue pour favoriser les trajets responsables, en mettant en avant les comportements écologiques.</p>

    <h2>🚗 Une communauté tournée vers l’avenir</h2>
    <p><strong>Application simple :</strong> Connectez-vous facilement avec d'autres utilisateurs pour planifier vos trajets.</p>
    <p><strong>Impact écologique :</strong> Chaque trajet inclut une estimation de l'empreinte carbone.</p>
    <p><strong>Incentives écologiques :</strong> Gagnez des points pour des réductions sur des produits écoresponsables.</p>
</div>
    
    </section>

    <section class="container2">
   <div class="text">
    <h2>🌍 Nos valeurs</h2>

    
        <p><strong>Écologie pragmatique :</strong> chaque trajet partagé est un pas vers moins d’émissions.</p>
        <p><strong>Accessibilité pour tous :</strong> pas besoin d’avoir une voiture électrique pour être écoresponsable.</p>
        <p><strong>Communauté solidaire :</strong> entraide, confiance et convivialité sont au cœur de notre service.</p>
    

<h2>🌱 Rejoignez le mouvement</h2>

    <p>Avec Ecoride, vous ne faites pas que voyager : vous agissez. Ensemble, rendons la route plus verte, un trajet à la fois.</p>



   </div> 


        <div class="image">
            <img src="../img/voiture.jpeg" class="img-fluid" alt="Image d'une femme relaxée dans une décapotable">
        </div>



    </section>
    

    <section class="contact">
        <div class="text-contact">
        <h2>Contactez-nous</h2>
        <p>Une question ? Un besoin particulier ? Contactez-nous sans hésiter. Nous sommes là pour vous accompagner et vous garantir une réponse dans les meilleurs délais.</p>
        </div>

        <div>
             <button type="button" class="btn-contact" onclick="window.location.href='contact.php'">Nous contacter</button>
        </div>
    </section>
    
    <?php
    require_once 'footer.php';
    ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>

