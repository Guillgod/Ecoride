<?php

require_once '../models/ModelAdmin.php';
class CreationGraph
{
    private $modelAdmin;

    public function __construct(ModelAdmin $modelAdmin)
    {
        $this->modelAdmin = $modelAdmin;
    }

//Gère l'envoi des données pour le graphique nb de covoiturages par jour
public function showGraphPage()
{
    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
    $month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');

    // Corrige mois < 1 ou > 12
    if ($month < 1) {
        $month = 12;
        $year -= 1;
    } elseif ($month > 12) {
        $month = 1;
        $year += 1;
    }

    return [
        'data' => $this->modelAdmin->getCovoituragesByDay($year, $month),
        'month' => $month,
        'year' => $year
    ];
}
//Gère l'envoi des données pour le graphique des crédits par jour
   public function showCreditGraph() {
    $year = isset($_GET['year']) ? (int)$_GET['year'] : date('Y');
    $month = isset($_GET['month']) ? (int)$_GET['month'] : date('m');

    $data = $this->modelAdmin->getCreditsByDay($year, $month);
    return [
        'data' => $data,
        'year' => $year,
        'month' => $month
    ];
}
}