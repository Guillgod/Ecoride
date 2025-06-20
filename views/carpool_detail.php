<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body>

    <?php
    session_start(); // Démarrage de la session
    
    require_once 'header.php';
    require_once 'Barre_de_recherche.php';
    require '../models/ModelCreateCarpool.php';
    require '../controllers/Creation_Carpool_Controller.php';
    require '../models/ModelPayment.php';
    require '../controllers/Payment_Controller.php';
    require '../controllers/Creation_User_Controller.php';
    require '../models/ModelCreateAvis.php';
    require '../controllers/Creation_Avis_Controller.php';
    
    if (isset($_GET['covoiturage_id'])) {
        $covoiturage_id = $_GET['covoiturage_id'];
        $modelCreateCarpool = new ModelCreateCarpool();
        $controllerAffichageCarpoolDetail = new Creation_Carpool_Controller($modelCreateCarpool);
        $carpoolDetails = $controllerAffichageCarpoolDetail->getCarpoolDetailsResult($covoiturage_id);
        $modelCreateAvis = new ModelCreateAvis();
        $controllerGetAvis = new Creation_Avis_Controller($modelCreateAvis);

        $getAvis = $controllerGetAvis->getAvisDriverResult($covoiturage_id);
        $modelPayment = new ModelPayment();
        $controllerPayment = new Payment_Controller($modelPayment);
        $id_chauffeur = $carpoolDetails['utilisateur_id'];
        $id_passager = $_SESSION['user']['utilisateur_id'];
        $prix_personne = $carpoolDetails['prix_personne'];
        $nb_place_dispo = $carpoolDetails['nb_place_dispo'];
        
        // Affichage des détails du covoiturage
        echo '<section>';
        echo '<h2>Détail du covoiturage</h2>';
        if ($carpoolDetails) {
            
            echo '<div class="user-info">';
            echo '<div class="user-info-content">';
            echo '<div class="user-photo">';
            echo '<img src="../controllers/display_photo.php?id=' . htmlspecialchars($id_chauffeur) . '" alt="Photo du chauffeur" width="auto" height="300">';
            echo '</div>';

            echo '<div class="user-details">';
            echo '<p>Pseudo du chauffeur :' . htmlspecialchars($carpoolDetails['pseudo']) . '</p>';
            echo '<p>Note du chauffeur :' .  $carpoolDetails['note'] . '</p>';
            echo '<p>Nb de place :' .  $carpoolDetails['nb_place_dispo'] . '</p>';
            echo '<p>Prix par personne :' . htmlspecialchars($carpoolDetails['prix_personne']) . '</p>';
            echo '<p>Date de départ :' . htmlspecialchars($carpoolDetails['date_depart']) . '</p>';
            echo '<p>Heure de départ :' . htmlspecialchars($carpoolDetails['heure_depart']) . '</p>';
            echo '<p>Heure d\'arrivée :' . htmlspecialchars($carpoolDetails['heure_arrivee']) . '</p>';
            echo '<p>Energie du véhicule :' . htmlspecialchars($carpoolDetails['energie']) . '</p>';
            echo '<p>Fumeurs acceptés ? :' . htmlspecialchars($carpoolDetails['fumeur']) . '</p>';
            echo '<p>Animaux acceptés ? :' . htmlspecialchars($carpoolDetails['animal']) . '</p>';
            echo '<p>Préférences conducteurs:' . htmlspecialchars($carpoolDetails['preferences']) . '</p>';
            echo '</div>'; // .user-details
            echo '</div>'; // .user-info-content
        } else {
            echo '<p>Aucun identifiant de covoiturage spécifié.</p>';
        }
        
        echo '<div class="user-actions">';
        // Vérifie si l'utilisateur est connecté
        if (isset($_SESSION['user']['utilisateur_id'])) {
            
            $utilisateur_id = $_SESSION['user']['utilisateur_id']; // Récupère l'ID de l'utilisateur de la session

            // Si le formulaire a été soumis et que l'utilisateur n'a pas encore participé

            if (isset($_POST['participer'])) {
                if($carpoolDetails['prix_personne'] > $_SESSION['user']['credit']){
                    echo '<p>Vous n\'avez pas assez de crédits pour participer à ce covoiturage.</p>';
                    exit;
                }
                $controller = new Creation_Carpool_Controller($modelCreateCarpool);
                $result = $controller->participerCarpool($utilisateur_id, $covoiturage_id);
                

                
                //AJOUTER Fonction payCarpool pour payer le covoiturage et diminuer le crédit de l'utilisateur connecté. Stocker cette valeur dans Paiement_en_cours de la base de données.la base de données


                if ($result) {
                    // Permet de stocker les crédits payés par l'utilisateur dans la table paiement_en_cours
                    // et de diminuer le crédit de l'utilisateur dans la table utilisateur
                    if ($carpoolDetails['prix_personne'] > $_SESSION['user']['credit']) {
                        echo '<p>Vous n\'avez pas assez de crédits pour participer à ce covoiturage.</p>';
                        exit;
                    }
                    $Stockedpayment=$controllerPayment-> stockPaymentCarpool($id_chauffeur,$id_passager,$covoiturage_id, $prix_personne);
                    $decreaseCredit = $controllerPayment->decreaseCreditPassenger($id_passager, $prix_personne);
                    $decreaseNbSeatOfCarpool=$controller->decreaseNb_Seat_Carpool_In_Database($covoiturage_id, $nb_place_dispo);
                    // Empêche la duplication du bouton après l'inscription
                    unset($_POST['participer']);
                    echo '<p style="color:green;">Vous avez bien rejoint ce covoiturage !</p>';
                } else {
                    echo '<p>Vous avez déjà rejoint ce covoiturage.</p>';
                }
            }

            // Affiche le bouton "Participer" uniquement si l'utilisateur n'a pas encore participé
            if (!isset($_POST['participer'])) {
                echo '<form method="POST" action="carpool_detail.php?covoiturage_id=' . $covoiturage_id . '">
                        <input type="hidden" name="covoiturage_id" value="' . $covoiturage_id . '">
                        <button class="button" type="submit" name="participer">Participer</button>
                      </form>';
            }

        } else {
            echo '<p>Vous devez être connecté pour participer à ce covoiturage.</p>';
        }

    } else {
        echo '<p>ID du covoiturage manquant dans l\'URL.</p>';
    }
    echo '</div>'; // .user-actions
                  echo '</div>'; // .user-info
    
    
    

    //Affichage des avis liés au chauffeur  
    echo '<section>';
    echo '<h2>Avis sur le chauffeur</h2>';
    echo '<p class="ajustement2">' . count($getAvis) . ' avis trouvé(s)</p>';
        if (!empty($getAvis)) {
            
            echo '<div class="user-info">';

            foreach ($getAvis as $avis) {
                echo '<div class="user-info-content">';
                echo '<div class="user-details">';
                echo '<p><strong>Pseudo du passager : </strong>' . htmlspecialchars($avis['pseudo']) . '</p>';
                echo '<p><strong>Note : </strong>' . htmlspecialchars($avis['note_validé']) . '</p>';
                echo '<p><strong>Commentaire : </strong>' . htmlspecialchars($avis['commentaire_validé']) . '</p>';
                echo '</div>'; // .user-details
                echo '</div>'; // .user-info-content
                echo '<hr>'; // Séparateur entre les avis
            }
            
        } else {
            echo '<p>Aucun avis disponible.</p>';
        }
        echo '</div>'; // .user-info
        echo '</section>';
        require_once 'footer.php';
        
        ?>

    </body>
</html>
