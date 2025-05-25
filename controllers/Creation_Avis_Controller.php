<?php
//Gère la création d'avis soumis à validation
require_once '../models/ModelCreateAvis.php';
class Creation_Avis_Controller
{
    private $modelCreateAvis;

    public function __construct(ModelCreateAvis $modelCreateAvis)
    {
        $this->modelCreateAvis = $modelCreateAvis;
    }

public function getFinishedCarpoolFromDatabase()
    {
        // Récupérer les avis en cours depuis le modèle
        return $this->modelCreateAvis->getFinishedCarpool();
    }
    


    public function createAvisEnCours($id_covoiturage_en_cours, $id_chauffeur_en_cours,$commentaire_en_cours,$note_en_cours)
    {

            // Call the model method to create the user
            $aviscreated = $this->modelCreateAvis->createAvisTemp($id_covoiturage_en_cours, $id_chauffeur_en_cours, $commentaire_en_cours, $note_en_cours); 
            echo "Avis soumis à validation";
        
    }


    public function getAvisEnCours(){
        // Récupérer les avis en cours depuis le modèle
        return $this->modelCreateAvis->getAvisEnCours();
    }


    public function InsertAvisInDatabase($id_covoiturage_validé, $id_chauffeur_validé, $commentaire_validé, $note_validé, $utilisateur_validé){
        // Récupérer les avis en cours depuis le modèle
        return $this->modelCreateAvis->InsertAvisInDatabase($id_covoiturage_validé, $id_chauffeur_validé, $commentaire_validé, $note_validé, $utilisateur_validé);
    }


        public function createAvisInDatabase($id_covoiturage, $id_chauffeur,$commentaire,$note)
    {
        $id_utilisateur_validé = $_SESSION['user']['utilisateur_id']; // Récupérer l'ID de l'utilisateur connecté
            // Call the model method to create the user
            return $aviscreated = $this->modelCreateAvis->createAvis($id_covoiturage, $id_chauffeur, $commentaire, $note, $id_utilisateur_validé ); 
             
        
    }
}

