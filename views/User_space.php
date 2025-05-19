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

<p>Bienvenue dans votre espace utilisateur !</p>

<?php
require_once '../models/ModelUser.php';
require_once '../controllers/UserController.php';
require_once '../controllers/Creation_Avis_Controller.php';


$modeluser = new ModelUser();
$userController = new UserController($modeluser);
$modelavis = new ModelCreateAvis();
$avisController = new Creation_Avis_Controller($modelavis);

$resultats=$userController->getUserInformationFromDatabase($_SESSION['user']['email']);
// $resultats2=$userController->getUserInformationWithoutCarFromDatabase($_SESSION['user']['email']);
$resultatscovoiturageterminé=$avisController->getFinishedCarpoolFromDatabase();
$resultatsavis=$avisController->getAvisEnCours();

// Gestion de la création d'un avis soumis à validation lors du clic sur le bouton "Envoyer l'avis"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentaire_en_cours'], $_POST['note_en_cours'], $_POST['id_covoiturage'], $_POST['id_chauffeur'])) {
    // Récupérer les données
    $commentaire_en_cours = $_POST['commentaire_en_cours'];
    $note_en_cours = $_POST['note_en_cours'];
    $id_covoiturage = $_POST['id_covoiturage'];
    $id_chauffeur = $_POST['id_chauffeur'];

    // Récupérer le covoiturage terminé (on suppose qu’il n’y en a qu’un ici)
    $covoituragesTermines = $avisController->getFinishedCarpoolFromDatabase();

        

        // Créer l’avis temporaire
        $avisController->createAvisEnCours($id_covoiturage, $id_chauffeur, $commentaire_en_cours,$note_en_cours);
         
        header("Location: User_space.php");

        echo '<p style="color:green;">Votre avis a bien été soumis pour validation.</p>';
        exit();
    }  

//Affichage des informations de l'utilisateur
        if (is_array($resultats)&&count($resultats)>0) {
            $chemin_photo = '../uploads/';
            $utilisateur=$resultats[0] ;
            echo '<div>';
            echo '<h2>Vos informations</h2>';
            echo '<img src="../uploads/' . htmlspecialchars($utilisateur['photo']) . '" alt="Photo de ' . htmlspecialchars($utilisateur['pseudo']) . '" width="auto" height="300">';
            echo '<p>Pseudo du chauffeur :' . htmlspecialchars($utilisateur['pseudo']) . '</p>';
            echo '<p>Nom :' . htmlspecialchars($utilisateur['nom']) . '</p>';
            echo '<p>Prénom :' . htmlspecialchars($utilisateur['prenom']) . '</p>';
            echo '<p>Email :' . htmlspecialchars($utilisateur['email']) . '</p>';
            echo '<p>Téléphone :' . htmlspecialchars($utilisateur['telephone']) . '</p>';
            echo '<p>Adresse :' . htmlspecialchars($utilisateur['adresse']) . '</p>';
            echo '<p>Date de naissance :' . htmlspecialchars($utilisateur['date_naissance']) . '</p>';
            echo '<p>Rôle :' . htmlspecialchars($utilisateur['role']) . '</p>';
            echo '<p>Note du chauffeur :' .  $utilisateur['note'] . '</p>';
            echo '<p>Crédits :' .  $utilisateur['credit'] . '</p>';
            echo '<p>Préférences :' .  $utilisateur['preferences'] . '</p>';
            echo '<p>Accepte les fumeurs :' .  $utilisateur['fumeur'] . '</p>';
            echo '<p>Accepte les animaux :' .  $utilisateur['animal'] . '</p>';
            echo '<button class="button" onclick="window.location.href=\'Modify_user_information.php \'">Modifier vos informations</button>';
            
            if ($utilisateur['role'] == 'passager'  ) {
                echo '<p>Vous n\'êtes pas chauffeur, vous ne pouvez pas créer de covoiturage. Veuillez modifier votre rôle.</p>';}
            if ($utilisateur['role'] == 'chauffeur' || $utilisateur['role'] == 'passager&chauffeur') {
            echo '<button class="button" onclick="window.location.href=\'creation_carpool.php\'">Créer un covoiturage</button>';
            echo '<button class="button" onclick="window.location.href=\'AjoutVoiture.php\'">Ajoutez une voiture</button>';
            }
            echo '</div>';
            

            // Dépôt d'AVIS : Affiche l'encadré avis si covoiturage auquel le participant a participé  est terminé
    if (count($resultatscovoiturageterminé) > 0) {
    echo '<h2>Déposer vos avis</h2>';
    foreach ($resultatscovoiturageterminé as $covoiturage) {
        // Vérifie si un avis a déjà été donné pour ce covoiturage
        $avisDejaDonne = false;
        foreach ($resultatsavis as $avis) {
            if ($avis['id_covoiturage'] == $covoiturage['covoiturage_id']) {
                $avisDejaDonne = true;
                break;
            }
        }

        if (!$avisDejaDonne) {
            // Affiche le formulaire seulement si aucun avis n’existe encore
            echo '<div style="border:1px solid #ccc; padding:10px; margin:10px 0;">';
            echo '<p>Vous avez participé à un covoiturage entre <strong>' . 
                ucfirst(htmlspecialchars($covoiturage['lieu_depart'])) . '</strong> et <strong>' .
                ucfirst(htmlspecialchars($covoiturage['lieu_arrivee'])) . '</strong>.';
            echo ' Vous pouvez donner votre avis sur le chauffeur <strong>' .
                ucfirst(htmlspecialchars($covoiturage['pseudo'])) . '</strong>.</p>';

            echo '<form method="POST" action="User_space.php">';
            echo '<input type="hidden" name="id_covoiturage" value="' . htmlspecialchars($covoiturage['covoiturage_id']) . '">';
            echo '<input type="hidden" name="id_chauffeur" value="' . htmlspecialchars($covoiturage['id_utilisateur_possede_voiture']) . '">';

            echo '<label for="note_en_cours">Note (1 à 5) :</label>';
            echo '<select name="note_en_cours" required>';
            for ($i = 1; $i <= 5; $i++) {
                echo "<option value=\"$i\">$i</option>";
            }
            echo '</select><br><br>';

            echo '<label for="commentaire_en_cours">Commentaire :</label><br>';
            echo '<textarea name="commentaire_en_cours" rows="4" cols="50" required></textarea><br><br>';

            echo '<button type="submit" class="button">Envoyer l\'avis</button>';
            echo '</form>';
            echo '</div>';
            }
    }
    }  
            

            // Afficher les voitures gérées par l'utilisateur
            echo '<h2>Voitures gérées</h2>';
            $voituresAffichees=[]; //Permet de n'afficher qu'une seule fois la voiture
                foreach ($resultats as $voiture) {
                    $idVoiture = $voiture['id_voiture_possede_utilisateur'];
                    if ($idVoiture !== null && !isset($voituresAffichees[$idVoiture])) {
                        echo '<div>';
                        echo '<p>Marque : ' . htmlspecialchars($voiture['marque']) . '</p>';
                        echo '<p>Modèle : ' . htmlspecialchars($voiture['modele']) . '</p>';
                        echo '<p>Immatriculation : ' . htmlspecialchars($voiture['immatriculation']) . '</p>';
                        echo '<p>Nombre de places : ' . htmlspecialchars($voiture['nb_place_voiture']) . '</p>';
                        echo '<p>Type de véhicule : ' . htmlspecialchars($voiture['energie']) . '</p>';
                        echo '<p>Couleur : ' . htmlspecialchars($voiture['couleur']) . '</p>';
                        echo '</div>';
                        $voituresAffichees[$idVoiture]=true;;
                    } 

                
                } 
            
            // Afficher les covoiturages en tant que chauffeur
            $covoituragesChauffeurTerminés = [];
            $covoituragesChauffeurActifs = [];
            $idCovoituragesAffiches = [];

            foreach ($resultats as $resultat) {
                $idCovoiturage = $resultat['id_covoiturage_utilise_voiture'];
                if ($idCovoiturage !== null && !isset($idCovoituragesAffiches[$idCovoiturage])) {
                    if ($resultat['statut'] === 'terminé') {
                        $covoituragesChauffeurTerminés[] = $resultat;
                    } else {
                        $covoituragesChauffeurActifs[] = $resultat;
                    }
                    $idCovoituragesAffiches[$idCovoiturage] = true;
                }
            }

            // Affichage des covoiturages actifs (prévu/en_cours)
            if (!empty($covoituragesChauffeurActifs)) {
                echo '<h2>Vos covoiturages comme chauffeur</h2>';
                foreach ($covoituragesChauffeurActifs as $covoiturage) {
                    $id = $covoiturage['id_covoiturage_utilise_voiture'];
                    echo '<div id="covoiturage_chauffeur'.$id.'">';
                    echo '<p>Lieu de départ : ' . htmlspecialchars($covoiturage['lieu_depart']) . '</p>';
                    echo '<p>Lieu d\'arrivée : ' . htmlspecialchars($covoiturage['lieu_arrivee']) . '</p>';
                    echo '<p>Date de départ : ' . htmlspecialchars($covoiturage['date_depart']) . '</p>';
                    echo '<p>Heure de départ : ' . htmlspecialchars($covoiturage['heure_depart']) . '</p>';
                    echo '<p>Date d\'arrivée : ' . htmlspecialchars($covoiturage['date_arrivee']) . '</p>';
                    echo '<p>Heure d\'arrivée : ' . htmlspecialchars($covoiturage['heure_arrivee']) . '</p>';
                    echo '<p>Nombre de places disponibles : ' . htmlspecialchars($covoiturage['nb_place_dispo']) . '</p>';
                    echo '<p>Prix par personne : ' . htmlspecialchars($covoiturage['prix_personne']) . '</p>';

                    if ($covoiturage['statut'] === 'prévu') {
                        echo '<button class="button commencer-btn" data-id="' . $id . '">Commencer</button>';
                        echo '<button class="button annuler-btn" data-id="' . $id . '">Annuler</button>';
                    } elseif ($covoiturage['statut'] === 'en_cours') {
                        echo '<button class="button arrive-btn" data-id="' . $id . '">Arrivé à destination</button>';
                    }

                    echo '</div>';
                }
            }

            // Affichage des covoiturages terminés
            if (!empty($covoituragesChauffeurTerminés)) {
                echo '<h2>Vos covoiturages comme chauffeur terminés</h2>';
                foreach ($covoituragesChauffeurTerminés as $covoiturage) {
                    echo '<div>';
                    echo '<p>Lieu de départ : ' . htmlspecialchars($covoiturage['lieu_depart']) . '</p>';
                    echo '<p>Lieu d\'arrivée : ' . htmlspecialchars($covoiturage['lieu_arrivee']) . '</p>';
                    echo '<p>Date de départ : ' . htmlspecialchars($covoiturage['date_depart']) . '</p>';
                    echo '<p>Heure de départ : ' . htmlspecialchars($covoiturage['heure_depart']) . '</p>';
                    echo '<p>Date d\'arrivée : ' . htmlspecialchars($covoiturage['date_arrivee']) . '</p>';
                    echo '<p>Heure d\'arrivée : ' . htmlspecialchars($covoiturage['heure_arrivee']) . '</p>';
                    echo '<p>Ce covoiturage est <strong style="color:green;">terminé</strong>.</p>';
                    echo '</div>';
                }
            }

            $passengerCovoiturages = $userController->getPassengerCovoiturageFromDatabase($_SESSION['user']['utilisateur_id']);
            
            if (!empty($passengerCovoiturages)) {
    echo '<h2>Vos covoiturages en tant que passager</h2>';

    $covoituragesEnCours = [];
    $covoituragesTermines = [];

    // Séparer les covoiturages
    foreach ($passengerCovoiturages as $covoiturage) {
        if ($covoiturage['statut'] === 'terminé' || $covoiturage['statut'] === 'en_cours') {
            $covoituragesTermines[] = $covoiturage;
        } else {
            $covoituragesEnCours[] = $covoiturage;
        }
    }

    // Affichage des covoiturages en cours
    if (!empty($covoituragesEnCours)) {
        echo '<h3>Vous participez actuellement à ces covoiturages :</h3>';
        foreach ($covoituragesEnCours as $covoiturage) {
            echo '<div>';
            echo '<p>Lieu de départ : ' . htmlspecialchars($covoiturage['lieu_depart']) . '</p>';
            echo '<p>Lieu d\'arrivée : ' . htmlspecialchars($covoiturage['lieu_arrivee']) . '</p>';
            echo '<p>Date de départ : ' . htmlspecialchars($covoiturage['date_depart']) . '</p>';
            echo '<p>Heure de départ : ' . htmlspecialchars($covoiturage['heure_depart']) . '</p>';
            echo '<p>Date d\'arrivée : ' . htmlspecialchars($covoiturage['date_arrivee']) . '</p>';
            echo '<p>Heure d\'arrivée : ' . htmlspecialchars($covoiturage['heure_arrivee']) . '</p>';
            echo '<p>Nombre de places disponibles : ' . htmlspecialchars($covoiturage['nb_place_dispo']) . '</p>';
            echo '<p>Prix par personne : ' . htmlspecialchars($covoiturage['prix_personne']) . '</p>';
            echo '<button class="button annuler2-btn" data-id="' . $covoiturage['covoiturage_id'] . '" data-user="' . $_SESSION['user']['utilisateur_id'] . '">Annuler</button>';
            echo '</div>';
        }
    }

    // Affichage des covoiturages terminés
    if (!empty($covoituragesTermines)) {
        echo '<h3>Vous avez participé à ces covoiturages :</h3>';
        foreach ($covoituragesTermines as $covoiturage) {
            echo '<div>';
            echo '<p>Lieu de départ : ' . htmlspecialchars($covoiturage['lieu_depart']) . '</p>';
            echo '<p>Lieu d\'arrivée : ' . htmlspecialchars($covoiturage['lieu_arrivee']) . '</p>';
            echo '<p>Date de départ : ' . htmlspecialchars($covoiturage['date_depart']) . '</p>';
            echo '<p>Heure de départ : ' . htmlspecialchars($covoiturage['heure_depart']) . '</p>';
            echo '<p>Date d\'arrivée : ' . htmlspecialchars($covoiturage['date_arrivee']) . '</p>';
            echo '<p>Heure d\'arrivée : ' . htmlspecialchars($covoiturage['heure_arrivee']) . '</p>';
            echo '<p>Nombre de places disponibles : ' . htmlspecialchars($covoiturage['nb_place_dispo']) . '</p>';
            echo '<p>Prix par personne : ' . htmlspecialchars($covoiturage['prix_personne']) . '</p>';
            echo '</div>';
        }
    }

} else {
    echo '<p>Vous ne participez à aucun covoiturage en tant que passager.</p>';
}
            if (empty($voituresAffichees)) {
                echo '<p>Aucune voiture gérée actuellement.</p>';
            }
            
            if (empty($idCovoituragesAffiches)) {
                echo '<p>Vous ne participez à aucun covoiturage en tant que chauffeur.</p>';
            }
        }
     
    else {
    '<p>Vous n\'êtes pas identifié. Veuillez vous connecter à votre compte</p>';
    echo '<button class="button" onclick="window.location.href=\'login.php\'">Se connecter</button>';
}
?>



<!-- Gère l'affichages des boutons au clic. Cepependant, je pourrai peut-être changer l'affichage des boutons via le php avec une condition sur l'état du covoiturage en ajoutant une colonne dans la table covoiturage. -->

<!-- Zone où le formulaire voiture sera injecté -->
<script>
console.log("Script JS chargé !");
document.addEventListener("DOMContentLoaded", function () {

    // Commencer un covoiturage
    document.querySelectorAll(".commencer-btn").forEach(function (btn) {
        btn.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            const covoiturageDiv = document.getElementById("covoiturage_chauffeur" + id);
            const self = this;

            fetch('../controllers/Update_Statut_Carpool.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: "id_covoiturage=" + encodeURIComponent(id) + "&nouvel_etat=en_cours"
            })
            .then(res => res.text())
            .then(data => {
                if (data.trim() === "ok") {
                    self.outerHTML = `<button class="button arrive-btn" data-id="${id}">Arrivé à destination</button>`;
                    const annulerBtn = covoiturageDiv.querySelector(".annuler-btn");
                    if (annulerBtn) annulerBtn.remove();
                    attachArriveEvent(); // 🔄 réattache l'événement "arrivé"
                } else {
                    console.error("Échec de la mise à jour :", data);
                }
            })
            .catch(err => console.error("Erreur AJAX :", err));
        });
    });

    // Fonction pour attacher l'événement "Arrivé"
    function attachArriveEvent() {
        document.querySelectorAll(".arrive-btn").forEach(function (btn) {
            btn.addEventListener("click", function () {
                const id = this.getAttribute("data-id");
                const covoiturageDiv = document.getElementById("covoiturage_chauffeur" + id);
                const self = this;

                fetch('../controllers/Update_Statut_Carpool.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: "id_covoiturage=" + encodeURIComponent(id) + "&nouvel_etat=terminé"
                })
                .then(res => res.text())
                .then(data => {
                    if (data.trim() === "ok") {
                        self.outerHTML = `<p style="color:green;">Ce covoiturage est terminé.</p>`;
                    } else {
                        console.error("Échec de la mise à jour :", data);
                    }
                })
                .catch(err => console.error("Erreur AJAX :", err));
            });
        });
    }

    attachArriveEvent(); // appel initial

    // Supprimer un covoiturage
    document.querySelectorAll(".annuler-btn").forEach(function (btn) {
        btn.addEventListener("click", function () {
            const id = this.getAttribute("data-id");
            const covoiturageDiv = document.querySelector(`#covoiturage_chauffeur${id}`);

            if (!covoiturageDiv) {
                console.warn(`Div avec id "covoiturage_chauffeur${id}" introuvable.`);
                return;
            }

            if (confirm("Êtes-vous sûr de vouloir annuler ce covoiturage ?")) {
                fetch('../controllers/Delete_Carpool_Controller.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: "id_covoiturage=" + encodeURIComponent(id)
                })
                .then(res => res.text())
                .then(data => {
                    console.log("Réponse du serveur :", data);
                    if (data.trim() === "ok") {
                        covoiturageDiv.remove(); // ✅ Supprime visuellement
                    } else {
                        console.error("Erreur lors de la suppression :", data);
                    }
                })
                .catch(err => console.error("Erreur AJAX :", err));
            }
        });
    });

});

document.querySelectorAll(".annuler2-btn").forEach(function (btn) {
    btn.addEventListener("click", function () {
        const idCovoiturage = this.getAttribute("data-id");
        const idPassager = this.getAttribute("data-user");
        const div = this.parentElement;

        if (confirm("Voulez-vous annuler votre participation ?")) {
            fetch('../controllers/Delete_Carpool_Passenger.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded'
                },
                body: "id_covoiturage=" + encodeURIComponent(idCovoiturage) + "&id_passager=" + encodeURIComponent(idPassager)
            })
            .then(res => res.text())
            .then(data => {
                if (data.trim() === "ok") {
                    div.remove(); // ✅ Supprime l'affichage
                } else {
                    alert("Erreur : " + data);
                }
            })
            .catch(err => console.error("Erreur AJAX :", err));
        }
    });
});
</script>

</body>
</html>
