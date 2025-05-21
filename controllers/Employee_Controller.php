<?php

require_once '../models/ModelEmployee.php';
//Gère l'affichage des avis à valider
class Employee_Controller {

    private $modelEmployee;

    public function __construct(ModelEmployee $modelEmployee) {
        $this->modelEmployee = $modelEmployee;
    }



    public function LoadAvisCarpoolEnCoursFromDatabase ()  {
        $modelEmployee = new ModelEmployee();
        return $modelEmployee->LoadAvisCarpoolEnCours();
    }
}
?>