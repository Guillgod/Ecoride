<?php

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
    private int $parameter;
    private string $gere;
    private string $photo;

    public function __construct()
    {
        $this->db = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');
       
    }

    public function getNom($nom)
    {
        return $this->nom;
    }
    public function getPrenom($prenom)
    {
        return $this->prenom;
    }
    public function getEmail($email)
    {
        return $this->email;
    }
    public function getPassword($password)
    {
        return $this->password;
    }
    public function getTelephone($telephone)
    {
        return $this->telephone;
    }
    public function getAdresse($adresse)
    {
        return $this->adresse;
    }
    public function getPseudo($pseudo)
    {
        return $this->pseudo;
    }
    public function getPhoto($photo)
    {
        return $this->photo;
    }
    

    public function setNom($nom)
    {
        $this->nom = $nom;
    }
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function setTelephone($telephone)
    {
        $this->telephone = $telephone;
    }
    public function setAdresse($adresse)
    {
        $this->adresse = $adresse;
    }
    public function setPseudo($pseudo)
    {
        $this->pseudo = $pseudo;
    }
    public function setPhoto($photo)
    {
        $this->photo = $photo;
    }

    public function createUser($nom, $prenom, $email, $password, $telephone, $adresse,$date_naissance, $pseudo, $photo) 
    
    {
        $stmt = $this->db->prepare("INSERT INTO utilisateur (nom, prenom, email, password, telephone, adresse,date_naissance, pseudo,photo) VALUES (:nom, :prenom, :email, :password, :telephone, :adresse, :date_naissance, :pseudo,:photo)"); 
        $stmt->bindValue(':nom', $nom);
        $stmt->bindValue(':prenom', $prenom);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':password', $password);
        $stmt->bindValue(':telephone', $telephone);
        $stmt->bindValue(':adresse', $adresse);
        $stmt->bindValue(':date_naissance', $date_naissance); 
        $stmt->bindValue(':pseudo', $pseudo);
        $stmt->bindValue(':photo', $photo);

        return $stmt->execute();
    }

}