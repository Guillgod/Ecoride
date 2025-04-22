<?php
require_once '../models/ModelPayment.php';
//Gère la création du covoiturage, l'affichage des trajets et le détail d'un trajet
class Payment_Controller 
{
    private $modelPayment;

    public function __construct(ModelPayment $modelPayment)
    {
        $this->modelPayment = $modelPayment;
    }

    public function stockPaymentCarpool($id_chauffeur,$id_passager,$id_covoiturage_paye, $nb_credit_paye) {
        $modelPayment = new ModelPayment();
        return $modelPayment->stockPaymentCarpool($id_chauffeur,$id_passager,$id_covoiturage_paye, $nb_credit_paye);
    }
    public function decreaseCreditPassenger($id_passager, $prix_personne) {
        $modelPayment = new ModelPayment();
        return $modelPayment->decreaseCreditPassenger($id_passager, $prix_personne);
    }


}