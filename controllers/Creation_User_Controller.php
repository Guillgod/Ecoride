<?php

class Creation_user_controller
{
    private $modelCreateUser;

    public function __construct(ModelCreateUser $modelCreateUser)
    {
        $this->modelCreateUser = $modelCreateUser;
    }
   

    public function createUserInDatabase()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $password = $_POST['password'];
            $telephone = $_POST['telephone'];
            $adresse = $_POST['adresse'];
            $date_naissance = $_POST['date_naissance'];
            $pseudo = $_POST['pseudo'];
            // $photo = $_FILES['photo']['name'];

            // Upload the photo
            // move_uploaded_file($_FILES['photo']['tmp_name'], 'uploads/' . $photo);

            // Call the model method to create the user
            $usercreated = $this->modelCreateUser->createUser($nom, $prenom, $email, $password, $telephone, $adresse,$date_naissance, $pseudo); 
            header("Location: ../Ecoride/views/Page_accueil.php");
        } else {
            echo "échec à la création du compte";
        }
    }
}