<?php
//Gère la création de l'utilisateur
class Creation_user_controller
{
    private $modelCreateUser;

    public function __construct(ModelCreateUser $modelCreateUser)
    {
        $this->modelCreateUser = $modelCreateUser;
    }


    public function createUserInDatabase() {
        require_once '../models/ModelCreateCar.php';
        require_once '../controllers/Creation_Car_Controller.php';
    
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
            $telephone = $_POST['telephone'];
            $adresse = $_POST['adresse'];
            $date_naissance = $_POST['date_naissance'];
            $pseudo = $_POST['pseudo'];
            $role = $_POST['role'];
            $photo = $_FILES['photo']['name'];
            $target_dir = '../uploads/';
            $target_file = $target_dir . basename($photo);
            $preferences = $_POST['preferences'];
            $fumeur = $_POST['fumeur'];
            $animal = $_POST['animal'];
    
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0755, true);
            }
    
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
                
                
                // Vérification de l'e-mail existante
            if ($this->modelCreateUser->emailExists($email)) {
                echo "<p style='color:red;'>Cette adresse e-mail est déjà utilisée.</p>";
                return; // Stop la création
            }
                // Créer l'utilisateur
                $userCreated = $this->modelCreateUser->createUser(
                    $nom, $prenom, $email, $password, $telephone, $adresse, $date_naissance, $pseudo, $photo, $role,$preferences,$fumeur,$animal);
    
                if ($userCreated) {
                    // Récupérer l'identifiant de l'utilisateur
                    $userId = $this->modelCreateUser->getLastInsertId();
    
                    // Si le rôle inclut 'chauffeur', créer la voiture
                    if (($role === 'chauffeur' || $role === 'passager&chauffeur') &&
                        isset($_POST['modele'], $_POST['immatriculation'], $_POST['energie'], $_POST['couleur'], $_POST['date_premiere_immatriculation'], $_POST['marque'])) {
    
                        $modele = $_POST['modele'];
                        $immatriculation = $_POST['immatriculation'];
                        $energie = $_POST['energie'];
                        $couleur = $_POST['couleur'];
                        $date_premiere_immatriculation = $_POST['date_premiere_immatriculation'];
                        $marque = $_POST['marque'];
                        $nb_place_voiture = $_POST['nb_place_voiture']; // Valeur par défaut si non spécifiée
                        $modelCreateCar = new ModelCreateCar();
                        $controllerCar = new Creation_Car_Controller($modelCreateCar);

    
                        $carCreated = $modelCreateCar->createCar($modele, $immatriculation, $energie, $couleur, $date_premiere_immatriculation, $marque, $nb_place_voiture);
                        
                        if ($carCreated) {
                             $lastCarId = $modelCreateCar->getLastInsertId(); // Méthode à ajouter dans ModelCreateCar si elle n’existe pas
                            $AddedCar = $this->modelCreateUser->addCarToUser($userId, $lastCarId);
                            echo "Votre compte utilisateur et votre voiture ont été créés avec succès !";
                        } else {
                            echo "Votre compte utilisateur a été créé, mais il y a eu une erreur lors de la création de la voiture.";
                        }
                    } else {
                        echo "Votre compte utilisateur a été créé avec succès !";
                    }
                } else {
                    echo "Échec de la création de l'utilisateur.";
                }
            } else {
                if (!move_uploaded_file($_FILES['photo']['tmp_name'], $target_file)) {
    echo "Erreur lors du téléchargement de la photo :<br>";
    print_r(error_get_last());
    print_r($_FILES['photo']);
}
            }
        } else {
            echo "Échec à la création du compte.";
        }
    }
}

