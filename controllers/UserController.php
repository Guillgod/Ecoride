<?php

//Gère la connexion de l'utilisateur
class UserController {
    private $modeluser;
    
    public function __construct(ModelUser $modeluser) {
        $this->modeluser = $modeluser;
    }

    public function login() {
         
        // Vérifie si le formulaire de connexion a été soumis
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = $this->modeluser->authenticate($email, $password);

            if($user) {
                $_SESSION['user']=$user; //Enregistre l'utilisateur connecté dans la session
                header('Location: Page_accueil.php');
                exit();
                
            } else {
                echo 'Email ou mot de passe incorrect';
            }
        }else "échec";
    }


    public function logout() {
        session_start(); // Démarre la session pour accéder aux informations de l'utilisateur
    
        session_destroy(); // Détruit la session pour déconnecter l'utilisateur
        header('Location: Page_accueil.php'); // Redirige vers la page d'accueil après la déconnexion
        exit; // Assurez-vous de sortir après la redirection
    }
}