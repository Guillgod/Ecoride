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

    <section class="container1">
        
        
        <div id="carousel" class="carousel slide" data-bs-ride="carousel">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="img/Voiture.jpg" class="imgcaroussel" alt="Voiture">
                </div>
                <div class="carousel-item">
                    <img src="img/autoroute.jpg" class="imgcaroussel" alt="Autoroute">
                </div>
                <div class="carousel-item">
                    <img src="img/autoroutenuit.jpg" class="imgcaroussel" alt="Autoroute de nuit">
                </div>
            </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Précédent</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Suivant</span>
        </button>
            
    
        </div>

        

        <div class="Presentation">
            <h1>
                Bienvenue chez Ecoride !
            </h1>
            <h2>
                Notre Mission
            </h2>
            <p>Écoride s'engage à rendre vos trajets quotidiens plus responsables en offrant une solution de covoiturage durable qui réduit l'empreinte carbone.</p>

            <h2>Pourquoi Nous Choisir ?</h2>
            <p>Application Simple : Connectez-vous facilement avec d'autres utilisateurs pour planifier vos trajets. Impact Écologique : Chaque trajet inclut une estimation de l'empreinte carbone. Incentives Écologiques : Gagnez des points pour des réductions sur des produits écoresponsables.</p>
            
            
            <h2>Notre Engagement</h2>
            <p>Tous Types de Véhicules : Nous accueillons les trajets en véhicules électriques, hybrides et non électriques. Sensibilisation : Participez à nos campagnes pour promouvoir le covoiturage. Impact Local : Soutenez l’économie locale en privilégiant les trajets régionaux.
            <h2>Rejoignez le Mouvement !</h2>
            <p>Ecoride, chaque trajet compte. Ensemble, construisons un avenir plus vert !</p>
            
        </div>
    
    </section>


    <?php
    require_once 'Barre_de_recherche.php';
    ?>

    </section>
    
    <?php
    require_once 'footer.php';
    ?>


    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    </body>
</html>

