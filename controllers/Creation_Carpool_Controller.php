<?php
require_once '../models/ModelCreateCarpool.php';
//Gère la création du covoiturage, l'affichage des trajets et le détail d'un trajet
class Creation_Carpool_Controller
{
    private $modelCreateCarpool;

    public function __construct(ModelCreateCarpool $modelCreateCarpool)
    {
        $this->modelCreateCarpool = $modelCreateCarpool;
    }


    public function createCarpoolInDatabase()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {


            $adresse_depart = $_POST['adresse_depart'];
            $lieu_depart = $_POST['lieu_depart'];
            $date_depart = $_POST['date_depart'];
            $heure_depart = $_POST['heure_depart'];
            $adresse_arrivee = $_POST['adresse_arrivee'];
            $lieu_arrivee = $_POST['lieu_arrivee'];
            $date_arrivee = $_POST['date_arrivee'];
            $heure_arrivee = $_POST['heure_arrivee'];
            $prix_personne = $_POST['prix_personne'];
            $voiture_id = $_POST['voiture_id'];
            $nb_place_dispo = $this->modelCreateCarpool->getNbPlaceVoiture($voiture_id);
            if (strtotime($date_arrivee)<strtotime($date_depart)){
                echo "La date d'arrivée doit être supérieure à la date de départ";
                return false;
            }elseif (strtotime($date_arrivee)==strtotime($date_depart) && strtotime($heure_arrivee)<strtotime($heure_depart)){
                echo "L'heure d'arrivée doit être supérieure à l'heure de départ";
                return false;
            } 
            $covoiturage_id=$this->modelCreateCarpool->createCarpool($adresse_depart, $lieu_depart, $date_depart, $heure_depart, $adresse_arrivee, $lieu_arrivee, $date_arrivee, $heure_arrivee,$prix_personne,$nb_place_dispo);
            // $utilisateur_id=$_SESSION['user']['utilisateur_id'];
            // $voiture_id=$this->modelCreateCarpool->getUserCarId($utilisateur_id);
            
            $this->modelCreateCarpool-> AddCarpoolToCar($voiture_id, $covoiturage_id);
            // Call the model method to create the user
            
            return $covoiturage_id; // Return the result of the creation
            
        
        }}
    


    public function displayCarpool(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $lieu_depart = $_POST['lieu_depart'];
            $lieu_arrivee = $_POST['lieu_arrivee'];
            $date_depart = $_POST['date_depart'];

            //calculer la plage de date (par exemple, 2 jours avant et 3 jour après)
            $date_depart_min = date('Y-m-d', strtotime($date_depart . ' -2 days'));
            $date_depart_max = date('Y-m-d', strtotime($date_depart . ' +3 days'));
            
            $displayedCarpool = $this->modelCreateCarpool->getCarpools($lieu_depart, $lieu_arrivee, $date_depart_min, $date_depart_max); // Appel de la méthode pour récupérer les trajets
    
            return $displayedCarpool; // Appel de la méthode pour récupérer les trajets
        }
        return null; // Retournez null si la méthode n'est pas appelée par POST
    }
    
    public function getCarpoolDetailsResult($covoiturage_id)
    {        
        return $this->modelCreateCarpool->getCarpoolDetails($covoiturage_id); // Appel de la méthode pour récupérer les détails du covoiturage
    }

    public function participerCarpool($utilisateur_id, $covoiturage_id) {
        // Vérifie si l'utilisateur est déjà inscrit à ce covoiturage (optionnel, mais utile pour éviter les doublons)
        if ($this->modelCreateCarpool->checkIfUserAlreadyJoined($utilisateur_id, $covoiturage_id)) {
            return false; // L'utilisateur est déjà inscrit
        }

        // Essaye d'ajouter l'utilisateur au covoiturage
        $result = $this->modelCreateCarpool->addUserToCarpool($utilisateur_id, $covoiturage_id);
        
        if ($result) {
            return true; // Succès
        } else {
            return false; // Échec de l'ajout
        }
    }

    public function decreaseNb_Seat_Carpool_In_Database($id_covoiturage, $nb_place_dispo)
    {        
        return $this->modelCreateCarpool->decreaseNb_Seat_Carpool($id_covoiturage,$nb_place_dispo); // Appel de la méthode pour récupérer les détails du covoiturage
    }
    

}