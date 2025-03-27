<?php
class UserController {
    private $modeluser;
    
    public function __construct(ModelUser $modeluser) {
        $this->modeluser = $modeluser;
    }

    public function login() {
        if($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = $_POST['email'];
            $password = $_POST['password'];
            $user = $this->modeluser->authenticate($email, $password);

            if($user) {
                header('Location: views/Page_accueil.php');
                
            } else {
                echo 'Email ou mot de passe incorrect';
            }
        }else "échec";
    }
}