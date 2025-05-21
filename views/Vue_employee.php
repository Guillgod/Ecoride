<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start(); // Démarre la session si elle n'est pas déjà démarrée
}
require_once '../controllers/Employee_Controller.php';
require_once '../models/ModelEmployee.php';
?>


<!DOCTYPE <!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
    </head>
    <body>
        
        <?php
        require_once 'header.php';
        ?>
        
        <h2>Vous trouverez ci-dessous les avis à valider</h2>
        <?php
        $modelEmployee = new ModelEmployee();
        $avis_en_cours_controller = new Employee_Controller($modelEmployee);
        $resultats_avis_en_cours= $avis_en_cours_controller->LoadAvisCarpoolEnCoursFromDatabase();
        
        if ($resultats_avis_en_cours){
            foreach ($resultats_avis_en_cours as $avis_en_cours) {
                echo "<div class='avis_en_cours'>";
                echo "<form method='post' action='../controllers/Process_avis.php'>";
                echo "<input type='hidden' name='id_avis_en_cours' value='" . htmlspecialchars($avis_en_cours['id_avis_en_cours']) . "'>";

                echo "<strong>Passager:</strong> " . htmlspecialchars($avis_en_cours['passager_prenom']) . " " . htmlspecialchars($avis_en_cours['passager_nom']) . " " . htmlspecialchars($avis_en_cours['passager_email']) . "<br>";
                echo "<strong>Chauffeur:</strong> " . htmlspecialchars($avis_en_cours['chauffeur_prenom']) . " " . htmlspecialchars($avis_en_cours['chauffeur_nom']) . " " . htmlspecialchars($avis_en_cours['chauffeur_email']) . "<br>";
                echo "<strong>Trajet:</strong> " . ucfirst(htmlspecialchars($avis_en_cours['lieu_depart'])) . " à " . ucfirst(htmlspecialchars($avis_en_cours['lieu_arrivee'])) . "<br>";
                echo "<strong>Note:</strong> " . htmlspecialchars($avis_en_cours['note_en_cours']) . "<br>";
                echo "<strong>Commentaire:</strong> " . htmlspecialchars($avis_en_cours['commentaire_en_cours']) . "<br><br>";

                echo "<button class='button' type='submit' name='action' value='valider'>Valider</button> ";
                echo "<button class='button' type='submit' name='action' value='refuser'>Refuser</button>";
                echo "</form>";
                echo "</div>";
            }
        }
        
        
        
        
        ?>

    </body>
</html>