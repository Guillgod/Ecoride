<?php

// controllers/CarpoolController.php
require_once '../models/ModelUpdateCarpool.php';

class UpdateCarpool_Controller  {
    private $model;

    public function __construct(ModelUpdateCarpool $model) {
        $this->model = $model;
    }

    public function changerEtatCovoiturage($idCovoiturage, $nouvelEtat) {
        return $this->model->updateEtatCovoiturage($idCovoiturage, $nouvelEtat);
    }

    public function supprimerCovoiturage($idCovoiturage) {
    return $this->model->deleteCarpool($idCovoiturage);
}
    
}