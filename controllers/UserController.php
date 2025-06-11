<?php

//Gère la connexion de l'utilisateur
class UserController {
    private $modeluser;
    
    public function __construct(ModelUser $modeluser) {
        $this->modeluser = $modeluser;
    }

    public function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $password = $_POST['password'];
        $user = $this->modeluser->authenticate($email, $password);

        if ($user) {
            if ($user['parametre'] === 'suspendu') {
                // L'utilisateur est suspendu, session détruite + message
                session_destroy();
                echo '<p style="color:red;">Votre compte est suspendu.</p>';
                return;
            }

            // Sinon, utilisateur valide
            $_SESSION['user'] = $user;
            header('Location: Page_accueil.php');
            exit();
        } else {
            echo '<p style="color:red;">Email ou mot de passe incorrect.</p>';
        }
    }
}
    // public function login() {
         
    //     // Vérifie si le formulaire de connexion a été soumis
    //     if($_SERVER['REQUEST_METHOD'] === 'POST') {
    //         $email = $_POST['email'];
    //         $password = $_POST['password'];
    //         $user = $this->modeluser->authenticate($email, $password);

    //         if($user) {
    //             $_SESSION['user']=$user; //Enregistre l'utilisateur connecté dans la session
    //             header('Location: Page_accueil.php');
    //             exit();
                
    //         } else {
    //             echo 'Email ou mot de passe incorrect';
    //         }
    //     }else "échec";
    //}


    public function logout() {
        session_start(); // Démarre la session pour accéder aux informations de l'utilisateur
    
        session_destroy(); // Détruit la session pour déconnecter l'utilisateur
        header('Location: Page_accueil.php'); // Redirige vers la page d'accueil après la déconnexion
        exit; // Assurez-vous de sortir après la redirection
    }

    public function getUserInformationFromDatabase($email) {
        return $this->modeluser->getUserInformation($email);
    }

     

    public function getPassengerCovoiturageFromDatabase($userId) {
    return $this->modeluser->getPassengerCovoiturages($userId);
    }

    public function updateUserInDatabase() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $telephone = $_POST['telephone'];
            $adresse = $_POST['adresse'];
            $date_naissance = $_POST['date_naissance'];
            $pseudo = $_POST['pseudo'];
            $role = $_POST['role'];
            $userId = $_SESSION['user']['utilisateur_id'];
            $preferences = $_POST['preferences'];
            $fumeur = $_POST['fumeur'];  
            $animal = $_POST['animal'];  

            $photo = null;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $photo = file_get_contents($_FILES['photo']['tmp_name']); // ← contenu binaire à stocker en BDD
}
                // Créer l'utilisateur mis à jour
                $userUpdated = $this->modeluser->updateUser($pseudo, $nom, $prenom, $email, $telephone, $adresse, $date_naissance, $photo, $role,$userId,$preferences,$fumeur,$animal);
    
                if ($userUpdated) {
                    // Requête pour récupérer les nouvelles données de l'utilisateur
                    $updatedUser = $this->modeluser->getUserById($userId);

                    // Mettre à jour la session après modification des infos utilisateur.
                    $_SESSION['user'] = $updatedUser;

                    echo 'Vos données ont bien été modifiées !';
                }
            } 
    }
}
