<?php
//Model de création d'utilisateur
class ModelCreateUser
{
    private PDO $db;
    private string $nom;
    private string $prenom;
    private string $email;
    private string $password;
    private string $telephone;
    private string $adresse;

    private string $date_de_naissance;
    private string $pseudo;
    
    private string $gere;
    private string $photo;

    private $credit;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');
       
    }

   

    public function createUser($nom, $prenom, $email, $password, $telephone, $adresse,$date_naissance, $pseudo, $photo,$role,$gere) 
    
    {
        $stmt = $this->db->prepare("INSERT INTO utilisateur (nom, prenom, email, password, telephone, adresse,date_naissance, pseudo,photo,role,gere) VALUES (:nom, :prenom, :email, :password, :telephone, :adresse, :date_naissance, :pseudo,:photo,:role,:gere)"); 
        $stmt->bindValue(':nom', $nom);
        $stmt->bindValue(':prenom', $prenom);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':telephone', $telephone);
        $stmt->bindValue(':adresse', $adresse);
        $stmt->bindValue(':date_naissance', $date_naissance); 
        $stmt->bindValue(':pseudo', $pseudo);
        $stmt->bindValue(':photo', $photo);
        $stmt->bindValue(':role', $role); // Rôle par défaut
        $stmt->bindValue(':gere', $gere); // Rôle par défaut

        return $stmt->execute();
    }

    
    
}