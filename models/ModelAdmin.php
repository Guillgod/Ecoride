<?php
//Modèle de création d'avis'
class ModelAdmin
{
    private PDO $db;
    

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');
       
    }

    public function getCovoituragesByDay($year, $month) {
    $stmt = $this->db->prepare("
        SELECT 
            DATE(date_depart) AS jour, 
            COUNT(*) AS total
        FROM covoiturage
        WHERE YEAR(date_depart) = :year AND MONTH(date_depart) = :month
        GROUP BY jour
        ORDER BY jour
    ");
    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    $stmt->bindValue(':month', $month, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}
}