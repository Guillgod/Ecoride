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
    


    public function createAvisEnCours($id_covoiturage_en_cours, $id_chauffeur_en_cours)
    {
        
             
            $commentaire_en_cours = $_POST['commentaire_en_cours'];
            $note_en_cours = $_POST['note_en_cours'];
             

            // Call the model method to create the user
            $aviscreated = $this->modelCreateAvis->createAvisTemp($id_covoiturage_en_cours, $id_chauffeur_en_cours, $commentaire_en_cours, $note_en_cours); 
            echo "Avis soumis à validation";
        
         
    }
}

