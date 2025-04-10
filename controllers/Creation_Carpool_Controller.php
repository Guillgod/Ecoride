<?php
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
            
            if (strtotime($date_arrivee)<strtotime($date_depart)){
                echo "La date d'arrivée doit être supérieure à la date de départ";
                return false;
            }elseif (strtotime($date_arrivee)==strtotime($date_depart) && strtotime($heure_arrivee)<strtotime($heure_depart)){
                echo "L'heure d'arrivée doit être supérieure à l'heure de départ";
                return false;
            } 
            // Call the model method to create the user
            return $this->modelCreateCarpool->createCarpool($adresse_depart, $lieu_depart, $date_depart, $heure_depart, $adresse_arrivee, $lieu_arrivee, $date_arrivee, $heure_arrivee,$prix_personne); 
            
        
        }}
    


    public function displayCarpool(){
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $lieu_depart = $_POST['lieu_depart'];
            $lieu_arrivee = $_POST['lieu_arrivee'];
            $date_depart = $_POST['date_depart'];

            
            $displayedCarpool = $this->modelCreateCarpool->getCarpools($lieu_depart, $lieu_arrivee, $date_depart); 
    
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
}