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

$modeluser = new ModelUser();
$userController = new UserController($modeluser);


$resultats=$userController->getUserInformationFromDatabase($_SESSION['user']['email']);
// $resultats2=$userController->getUserInformationWithoutCarFromDatabase($_SESSION['user']['email']);


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
            

            //Affiche l'encadré avis si covoiturage auquel le participant a participé 



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
            echo '<h2>Vos covoiturages comme chauffeur</h2>';
            $idCovoituragesAffiches=[]; //Permet de n'afficher qu'une seule fois le covoiturage
            foreach ($resultats as $resultat) {
                $idCovoiturage =$resultat['id_covoiturage_utilise_voiture'];
                if($idCovoiturage !== null && !isset($idCovoituragesAffiches[$idCovoiturage])) {
                    echo '<div id="covoiturage_chauffeur'.$idCovoiturage.'">';
                    echo '<p>Lieu de départ : ' . htmlspecialchars($resultat['lieu_depart']) . '</p>';
                    echo '<p>Lieu d\'arrivée : ' . htmlspecialchars($resultat['lieu_arrivee']) . '</p>';
                    echo '<p>Date de départ : ' . htmlspecialchars($resultat['date_depart']) . '</p>';
                    echo '<p>Heure de départ : ' . htmlspecialchars($resultat['heure_depart']) . '</p>';
                    echo '<p>Date d\'arrivée : ' . htmlspecialchars($resultat['date_arrivee']) . '</p>';
                    echo '<p>Heure d\'arrivée : ' . htmlspecialchars($resultat['heure_arrivee']) . '</p>';
                    echo '<p>Nombre de places disponibles : ' . htmlspecialchars($resultat['nb_place_dispo']) . '</p>';
                    echo '<p>Prix par personne : ' . htmlspecialchars($resultat['prix_personne']) . '</p>';

                        
                        $statut = $resultat['statut'];  

                        if ($statut === 'prévu') {
                            echo '<button class="button commencer-btn" data-id="' . $idCovoiturage . '">Commencer</button>';
                            echo '<button class="button annuler-btn" data-id="' . $idCovoiturage . '">Annuler</button>';
                        } elseif ($statut === 'en_cours') {
                            echo '<button class="button arrive-btn" data-id="' . $idCovoiturage . '">Arrivé à destination</button>';
                        } elseif ($statut === 'terminé') {
                            echo '<p style="color:green;">Ce covoiturage est terminé.</p>';
                        }
                     
                    
                    echo '</div>';
                    $idCovoituragesAffiches[$idCovoiturage]=true;
                } 
            }

            $passengerCovoiturages = $userController->getPassengerCovoiturageFromDatabase($_SESSION['user']['utilisateur_id']);
            
            if (!empty($passengerCovoiturages)) {
                echo '<h2>Vos covoiturages en tant que passager</h2>';
                foreach ($passengerCovoiturages as $covoiturage) {
                    echo '<div>';
                    echo '<h3>Vous participer à ce covoiturage :</h3>';
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
