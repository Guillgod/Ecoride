<?php
session_start(); // Démarrer la session

require_once 'header.php'; // Afficher le header

if (!isset($_SESSION['user'])) {
    echo '<section>';
    echo '<p class="ajustement" style="color:red;">Vous devez être connecté pour accéder à votre espace utilisateur.</p>';
    
    // Bouton "Se connecter"
    echo '<div class="button-container">';
    echo '<button class="button" onclick="window.location.href=\'login.php\'">Se connecter</button>';
    echo '</div>';
    echo '</section>';
    // Arrêter le reste de la page
    return;
}
?>



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

echo '<section class="user-space">';
    echo '<h1>Bienvenue dans votre espace utilisateur !</h1>';


// Gestion de la création d'un avis soumis à validation lors du clic sur le bouton "Envoyer l'avis"
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['commentaire_en_cours'], $_POST['note_en_cours'], $_POST['id_covoiturage'], $_POST['id_chauffeur'])) {
    // Récupérer les données
    $commentaire_en_cours = $_POST['commentaire_en_cours'];
    $note_en_cours = $_POST['note_en_cours'];
    $id_covoiturage = $_POST['id_covoiturage'];
    $id_chauffeur = $_POST['id_chauffeur'];
    $commentaire_validé = $_POST['commentaire_en_cours'];
    $note_validé = $_POST['note_en_cours'];
    $id_covoiturage_validé = $_POST['id_covoiturage'];
    $id_chauffeur_validé = $_POST['id_chauffeur'];

    // Récupérer le covoiturage terminé (on suppose qu’il n’y en a qu’un ici)
    $covoituragesTermines = $avisController->getFinishedCarpoolFromDatabase();

    // Différenciation des boutons de soumission --------------------------------------------------------------------------
        if (isset($_POST['soumettre_avis_employe'])) {
        // Soumettre à validation de l'employé
        $avisController->createAvisEnCours($id_covoiturage, $id_chauffeur, $commentaire_en_cours, $note_en_cours);
        header("Location: User_space.php");
        echo '<p style="color:green;">Votre avis a bien été soumis pour validation.</p>';
        exit();
    } elseif (isset($_POST['soumettre_avis'])) {
        // Soumettre l'avis directement (ajuster selon la logique si un avis direct doit exister)
        // Exemple : méthode createAvisValide directement
        $avistest=$avisController->createAvisInDatabase($id_covoiturage_validé, $id_chauffeur_validé, $commentaire_validé, $note_validé);
        // header("Location: User_space.php");
        echo '<p style="color:green;">Votre avis a été soumis directement.</p>';
        exit();
    } //------------------------------------------------------------------------------------------------------------------------

        // // Créer l’avis temporaire
        // $avisController->createAvisEnCours($id_covoiturage, $id_chauffeur, $commentaire_en_cours,$note_en_cours);
         
        // header("Location: User_space.php");

        // echo '<p style="color:green;">Votre avis a bien été soumis pour validation.</p>';
        // exit();
    }  


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
    $passengerCovoiturages = $userController->getPassengerCovoiturageFromDatabase($_SESSION['user']['utilisateur_id']);
            

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

    //Affichage des informations de l'utilisateur
            if (is_array($resultats)&&count($resultats)>0) {
                $chemin_photo = '../uploads/';
                $utilisateur=$resultats[0] ;


            echo '<div class="user-info">';
            echo '<h2>Vos informations</h2>';

            echo '<div class="user-info-content">';

            // PHOTO
            echo '<div class="user-photo">';
            echo '<img src="../uploads/' . htmlspecialchars($utilisateur['photo']) . '" alt="Photo de ' . htmlspecialchars($utilisateur['pseudo']) . '" width="auto" height="300">';
            echo '</div>'; 
            
            echo '<div class="user-details">';
            echo '<p><strong>Pseudo du chauffeur : </strong>' . htmlspecialchars($utilisateur['pseudo']) . '</p>';
            echo '<p><strong>Nom : </strong>' . htmlspecialchars($utilisateur['nom']) . '</p>';
            echo '<p><strong>Prénom : </strong>' . htmlspecialchars($utilisateur['prenom']) . '</p>';
            echo '<p><strong>Email : </strong>' . htmlspecialchars($utilisateur['email']) . '</p>';
            echo '<p><strong>Téléphone : </strong>' . htmlspecialchars($utilisateur['telephone']) . '</p>';
            echo '<p><strong>Adresse : </strong>' . htmlspecialchars($utilisateur['adresse']) . '</p>';
            echo '<p><strong>Date de naissance : </strong>' . htmlspecialchars($utilisateur['date_naissance']) . '</p>';
            echo '<p><strong>Rôle : </strong>' . htmlspecialchars($utilisateur['role']) . '</p>';
            echo '<p><strong>Votre note : </strong>' .  $utilisateur['note'] . '</p>';
            echo '<p><strong>Crédits : </strong>' .  $utilisateur['credit'] . '</p>';
            echo '<p><strong>Préférences : </strong>' .  $utilisateur['preferences'] . '</p>';
            echo '<p><strong>Accepte les fumeurs : </strong>' .  $utilisateur['fumeur'] . '</p>';
            echo '<p><strong>Accepte les animaux : </strong>' .  $utilisateur['animal'] . '</p>';

            echo '</div>'; // .user-details
            echo '</div>'; // .user-info-content
            
            echo '<div class="user-actions">';
            if ($utilisateur['role'] == 'passager'  ) {
                echo '<div>';
                echo '<p class="ajustement3">Vous n\'êtes pas chauffeur, vous ne pouvez pas créer de covoiturage. Veuillez modifier votre rôle.</p>';
                echo '<div class="button-container">';
                echo '<button class="button" onclick="window.location.href=\'Modify_user_information.php \'">Modifier vos informations</button>';
                echo '</div>';
                echo '</div>';
            }
            if ($utilisateur['role'] == 'chauffeur' || $utilisateur['role'] == 'passager&chauffeur') {
                echo '<div class="button-container">';
                echo '<button class="button" onclick="window.location.href=\'creation_carpool.php\'">Créer un covoiturage</button>';
                echo '<button class="button" onclick="window.location.href=\'AjoutVoiture.php\'">Ajoutez une voiture</button>';
                
                echo '<button class="button" onclick="window.location.href=\'Modify_user_information.php \'">Modifier vos informations</button>';
                echo '</div>';
            }
            
            echo '</div>';
            echo '</div>'; // .user-info
            
            
            // Afficher les avis à donner
            echo '<div class="avis">';
            if (count($resultatscovoiturageterminé) > 0) {
                echo '<h2>Déposer vos avis</h2>';
                foreach ($resultatscovoiturageterminé as $covoiturage) {
                    // Vérifie si un avis a déjà été donné pour ce covoiturage
                    $avisDejaDonne = false;
                    foreach ($resultatsavis as $avis) {
                        if (!$avisDejaDonne )  {
                            $avisDejaDonne = true;
                            break;
                        }
                    }

                    if (!$avisDejaDonne) {
                        // Affiche le formulaire seulement si aucun avis n’existe encore
                        echo '<div class="avis-box">';
                        echo '<p>Vous avez participé à un covoiturage entre <strong>' . 
                            ucfirst(htmlspecialchars($covoiturage['lieu_depart'])) . '</strong> et <strong>' .
                            ucfirst(htmlspecialchars($covoiturage['lieu_arrivee'])) . '</strong>.';
                        echo ' Vous pouvez donner votre avis sur le chauffeur <strong>' .
                            ucfirst(htmlspecialchars($covoiturage['pseudo'])) . '</strong>.</p>';

                        echo '<form method="POST"  onsubmit="return handleSubmit(this)">';
                        echo '<input type="hidden" name="id_covoiturage" value="' . htmlspecialchars($covoiturage['covoiturage_id']) . '">';
                        echo '<input type="hidden" name="id_chauffeur" value="' . htmlspecialchars($covoiturage['id_utilisateur_possede_voiture']) . '">';

                        echo '<label for="note_en_cours">Note (1 à 5) :</label>';
                        echo '<select name="note_en_cours" required>';
                            for ($i = 1; $i <= 5; $i++) {
                                echo "<option value=\"$i\">$i</option>";
                            }
                        echo '</select><br>';

                        echo '<label for="commentaire_en_cours">Commentaire :</label>';
                        echo '<textarea name="commentaire_en_cours" rows="4" cols="50" required></textarea>';

                        echo '<div class="avis-buttons">';
                        echo '<button class="button" type="submit" name="soumettre_avis" >Soumettre l\'avis</button>';
                        echo '<button type="submit" name="soumettre_avis_employe" class="button">Soumettre l\'avis à l\'employé</button>';
                        echo '</div>';
                        
                        echo '</form>';
                        echo '</div>';
                    }
                }
            }  
            echo '</div>'; // fin .avis
            



            // Affichage des onglets pour la gestion des voitures et covoiturages
            echo '<div class="onglets-container">';
                echo '<div class="onglets-barre">';
                    echo '<button class="onglet-btn actif" data-cible="voiture">Ma voiture</button>';
                    echo '<button class="onglet-btn" data-cible="passager">Covoiturage Passager</button>';
                    echo '<button class="onglet-btn" data-cible="chauffeur">Covoiturage Chauffeur</button>';
                    echo '<button class="onglet-btn" data-cible="historiques">Historiques</button>';
                echo '</div>';
                echo '<hr class="onglet-separator">';






                echo '<div class="contenus-onglets">';
                echo '<div id="contenu-voiture" class="contenu actif">';

                    // Tableau pour stocker les voitures uniques
                    $voituresAffichees = [];

                    // Collecte des voitures uniques par leur ID
                    foreach ($resultats as $voiture) {
                        $idVoiture = $voiture['id_voiture_possede_utilisateur'];
                        if ($idVoiture !== null && !isset($voituresAffichees[$idVoiture])) {
                            $voituresAffichees[$idVoiture] = $voiture;
                        }
                    }

                    // Affichage
                    if (!empty($voituresAffichees)) {
                        echo '<h2>Voitures gérées</h2>';
                        foreach ($voituresAffichees as $voiture) {
                            echo '<div class="voiture-info">';
                            echo '<div class="voiture-details">';
                            echo '<p><strong>Marque : </strong>' . htmlspecialchars($voiture['marque']) . '</p>';
                            echo '<p><strong>Modèle : </strong>' . htmlspecialchars($voiture['modele']) . '</p>';
                            echo '<p><strong>Immatriculation : </strong>' . htmlspecialchars($voiture['immatriculation']) . '</p>';
                            echo '<p><strong>Nombre de places : </strong>' . htmlspecialchars($voiture['nb_place_voiture']) . '</p>';
                            echo '<p><strong>Type de véhicule : </strong>' . htmlspecialchars($voiture['energie']) . '</p>';
                            echo '<p><strong>Couleur : </strong>' . htmlspecialchars($voiture['couleur']) . '</p>';
                            echo '</div>';
                            echo '</div>';
                        }
                    } else {
                        echo '<p class="ajustement2">Aucune voiture gérée actuellement.</p>';
                    }

                echo '</div>'; // fermeture de contenu-voiture






                echo '<div id="contenu-passager" class="contenu">';
                        // Affichage des covoiturages en cours comme passager
                // Affichage des covoiturages en tant que passager
                    if (!empty($passengerCovoiturages)) {
                        echo '<h2>Vos covoiturages en tant que passager</h2>';
                    } else {
                        echo '<p class="ajustement2">Vous ne participez à aucun covoiturage en tant que passager.</p>';
                    }
                        
                    if (!empty($covoituragesEnCours)) {
                        echo '<h3>Vous participez actuellement à ces covoiturages :</h3>';
                        foreach ($covoituragesEnCours as $covoiturage) {
                            echo '<div class="covoiturage-actif-info" >';
                                echo '<div class="covoiturage-details">';
                                    echo '<p><strong>Trajet : </strong>' . htmlspecialchars($covoiturage['lieu_depart']) . ' - ' . htmlspecialchars($covoiturage['lieu_arrivee']) . '</p>';
                                    echo '<p><strong>Date de départ : </strong>' . htmlspecialchars($covoiturage['date_depart']) . ' à ' . htmlspecialchars($covoiturage['heure_depart']) . '</p>';
                                    echo '<p><strong>Date d\'arrivée : </strong>' . htmlspecialchars($covoiturage['date_arrivee']) . ' à ' . htmlspecialchars($covoiturage['heure_arrivee']) . '</p>';
                                    echo '<p><strong>Nombre de places disponibles : </strong>' . htmlspecialchars($covoiturage['nb_place_dispo']) . '</p>';
                                    echo '<p><strong>Prix par personne : </strong>' . htmlspecialchars($covoiturage['prix_personne']) . '</p>';
                                echo '</div>';
                                    
                                echo '<div class="covoiturage-buttons2">';
                                    echo '<button class="button annuler2-btn" onclick="changeColor(this)" data-id="' . $covoiturage['covoiturage_id'] . '" data-user="' . $_SESSION['user']['utilisateur_id'] . '">Annuler</button>';
                                echo '</div>';
                                
                            echo '</div>'; // fin covoiturage-actif-info
                        }
                    }
                echo '</div>'; // fermeture de contenu-passager






                    echo '<div id="contenu-chauffeur" class="contenu">';
                        // Affichage des covoiturages actifs (prévu/en_cours)
                        // Afficher les covoiturages en tant que chauffeur
           
                if (!empty($covoituragesChauffeurActifs)) {
                    echo '<h2>Vos covoiturages comme chauffeur</h2>';
                    foreach ($covoituragesChauffeurActifs as $covoiturage) {
                        $id = $covoiturage['id_covoiturage_utilise_voiture'];
                        echo '<div class="covoiturage-actif-info" id="covoiturage_chauffeur'.$id.'">';
                        echo '<div class="covoiturage-details">';
                        echo '<p><strong>Trajet : </strong>' . htmlspecialchars($covoiturage['lieu_depart']) . ' - ' . htmlspecialchars($covoiturage['lieu_arrivee']) . '</p>';
                        // echo '<p><strong>Lieu d\'arrivée : </strong>' . htmlspecialchars($covoiturage['lieu_arrivee']) . '</p>';
                        echo '<p><strong>Date de départ : </strong>' . htmlspecialchars($covoiturage['date_depart']) . ' à ' . htmlspecialchars($covoiturage['heure_depart']) . '</p>';
                        // echo '<p><strong>Heure de départ : </strong>' . htmlspecialchars($covoiturage['heure_depart']) . '</p>';
                        echo '<p><strong>Date d\'arrivée : </strong>' . htmlspecialchars($covoiturage['date_arrivee']) . ' à ' . htmlspecialchars($covoiturage['heure_arrivee']) . '</p>';
                        // echo '<p><strong>Heure d\'arrivée : </strong>' . htmlspecialchars($covoiturage['heure_arrivee']) . '</p>';
                        echo '<p><strong>Nombre de places disponibles : </strong>' . htmlspecialchars($covoiturage['nb_place_dispo']) . '</p>';
                        echo '<p><strong>Prix par personne : </strong>' . htmlspecialchars($covoiturage['prix_personne']) . '</p>';
                        echo '</div>';

                        echo '<div class="covoiturage-buttons">';
                        if ($covoiturage['statut'] === 'prévu') {
                            echo '<button class="button commencer-btn" onclick="changeColor(this)" data-id="' . $id . '">Commencer</button>';
                            echo '<button class="button annuler-btn" onclick="changeColor(this)" data-id="' . $id . '">Annuler</button>';
                        } elseif ($covoiturage['statut'] === 'en_cours') {
                            echo '<button class="button arrive-btn" onclick="changeColor(this)" data-id="' . $id . '">Arrivé à destination</button>';
                        }

                        echo '</div>';
                        echo '</div>'; // fin covoiturage-actif-info
                    }
                }
                if (empty($idCovoituragesAffiches)) {
                echo '<p class="ajustement2">Vous ne participez à aucun covoiturage en tant que chauffeur.</p>';
            }
                        echo '</div>';




                        // Affichage des covoiturages terminés
                        echo '<div id="contenu-historiques" class="contenu">';

// Affichage des covoiturages terminés comme chauffeur
if (!empty($covoituragesChauffeurTerminés)) {
    echo '<h2>Vos covoiturages comme chauffeur terminés</h2>';
    
    foreach ($covoituragesChauffeurTerminés as $covoiturage) {
        echo '<div class="covoiturage-termine-info">';
            echo '<div class="covoiturage-details">';
                echo '<p><strong>Trajet : </strong>' . htmlspecialchars($covoiturage['lieu_depart']) . ' - ' . htmlspecialchars($covoiturage['lieu_arrivee']) . '</p>';
                echo '<p><strong>Date de départ : </strong>' . htmlspecialchars($covoiturage['date_depart']) . '</p>';
                echo '<p>Ce covoiturage est <strong style="color:green;">terminé</strong>.</p>';
            echo '</div>';
        echo '</div>';
    }
}else {
    echo '<p class="ajustement2">Vous n\'avez pas de covoiturages terminés en tant que chauffeur.</p>';
}

// Affichage des covoiturages terminés comme passager
if (!empty($covoituragesTermines)) {
    echo '<h3>Vous avez participé à ces covoiturages comme passager :</h3>';
    
    foreach ($covoituragesTermines as $covoiturage) {
        echo '<div class="covoiturage-termine-info">';
            echo '<div class="covoiturage-details">';
                echo '<p><strong>Trajet : </strong>' . htmlspecialchars($covoiturage['lieu_depart']) . ' - ' . htmlspecialchars($covoiturage['lieu_arrivee']) . '</p>';
                echo '<p><strong>Date de départ : </strong>' . htmlspecialchars($covoiturage['date_depart']) . '</p>';
                echo '<p><strong>Prix par personne : </strong>' . htmlspecialchars($covoiturage['prix_personne']) . '</p>';
                echo '<p>Ce covoiturage est <strong style="color:green;">terminé</strong>.</p>';
            echo '</div>';
        echo '</div>';
    }
}else {
    echo '<p class="ajustement2">Vous n\'avez pas de covoiturages terminés en tant que passager.</p>';
}

echo '</div>'; // fermeture de contenu-historiques
            }
                echo '</div>';
            echo '</div>';

echo '</section>'; // Fermeture de la section user-space
require_once 'footer.php'; // Afficher le footer
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


document.querySelectorAll('.onglet-btn').forEach(button => {
    button.addEventListener('click', () => {
        // Retirer l’état actif de tous les boutons
        document.querySelectorAll('.onglet-btn').forEach(btn => btn.classList.remove('actif'));

        // Cacher tous les contenus
        document.querySelectorAll('.contenu').forEach(div => div.classList.remove('actif'));

        // Activer le bouton cliqué
        button.classList.add('actif');

        // Afficher le contenu correspondant
        const cible = button.getAttribute('data-cible');
        document.getElementById('contenu-' + cible).classList.add('actif');
    });
});


        document.addEventListener("DOMContentLoaded", function () {
    const buttons = document.querySelectorAll(".avis-buttons .button");

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            // Supprimer la classe 'clicked' des autres boutons
            buttons.forEach(btn => btn.classList.remove("clicked"));
            // Ajouter la classe au bouton cliqué
            this.classList.add("clicked");
        });
    });
});

</script>

</body>
</html>
