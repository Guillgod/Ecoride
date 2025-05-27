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

public function getCreditsByDay($year, $month) {
    $stmt = $this->db->prepare("
        SELECT 
            DATE(date_de_paiement) AS jour, 
            SUM(gain) AS total_credits
        FROM gain_plateforme
        WHERE YEAR(date_de_paiement) = :year AND MONTH(date_de_paiement) = :month
        GROUP BY jour
        ORDER BY jour
    ");
    $stmt->bindValue(':year', $year, PDO::PARAM_INT);
    $stmt->bindValue(':month', $month, PDO::PARAM_INT);
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

public function getTotalCreditsGagnes() {
    $stmt = $this->db->prepare("SELECT COALESCE(SUM(gain), 0) AS total_credits FROM gain_plateforme");
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total_credits'];
}

public function getAnAccount($email) {
    $stmt = $this->db->prepare("SELECT * FROM utilisateur
    WHERE email = :email");
    $stmt->bindValue(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

//Supend l'utilisateur
public function suspendUser($email) {
    $stmt = $this->db->prepare("UPDATE utilisateur SET parametre = 'suspendu' WHERE email = :email");
    return $stmt->execute(['email' => $email]);
}
//Désuspend l'utilisateur
public function offSuspendUser($email) {
    $stmt = $this->db->prepare("UPDATE utilisateur SET parametre = 'valide' WHERE email = :email");
    return $stmt->execute(['email' => $email]);
}
}
