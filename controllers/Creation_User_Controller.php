<?php
//Gère la création de l'utilisateur
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
            
            // Gestion du téléchargement de la photo
            $photo = $_FILES['photo']['name'];
            $target_dir = 'uploads/'; // Répertoire cible pour les photos
            $target_file = $target_dir . basename($photo);

        // Vérifiez si le répertoire existe, sinon créez-le
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0755, true);
        }

        // Déplacez le fichier téléchargé
        if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
            
            $usercreated = $this->modelCreateUser->createUser($nom, $prenom, $email, $password, $telephone, $adresse, $date_naissance, $pseudo, $photo);
            header("Location: ../Ecoride/views/Page_accueil.php");
            exit;
        } else {
            echo "Erreur lors du téléchargement de la photo";
        }
    } else {
        echo "Échec à la création du compte.";
    }
    }
}
