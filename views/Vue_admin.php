<?php
require_once '../models/ModelAdmin.php';
require_once '../controllers/Admin_Controller.php';
require_once '../views/header.php';

$controller = new CreationGraph(new ModelAdmin());
$graphInfo = $controller->showGraphPage();
$data = $graphInfo['data'];
$month = $graphInfo['month'];
$year = $graphInfo['year'];



if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['unsuspend'])) {
    $email = $_POST['email'];
    $controller->offSuspendUser($email);
    header("Location: ../views/Vue_admin.php"); // Ou la page admin appropriée
    exit;
}
?>


<?php if(isset($_SESSION['user']) && $_SESSION['user']['compte_employee'] == 'admin'): ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <title>Statistiques Covoiturages</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="../css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>


<!-- Affichage du 1er graphique nb covoiturage par mois  -->
    <section>
       <h1>Statistiques</h1> 
         
            <?php foreach ($data as $row): ?>
            
            <?php endforeach; 
            
    $prevMonth = $month - 1;
    $prevYear = $year;
    if ($prevMonth < 1) {
        $prevMonth = 12;
        $prevYear--;
    }

    $nextMonth = $month + 1;
    $nextYear = $year;
    if ($nextMonth > 12) {
        $nextMonth = 1;
        $nextYear++;
    }
?>

<div style="display: flex; flex-direction: column; align-items: center; margin: 30px 0;">
    <!-- Flèches et titre centrés -->
    <div style="display: flex; align-items: center; justify-content: center; gap: 20px; margin-bottom: 20px;">
        <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>" class="arrow-button" >&#x1F808;</a>
        <h2 style="margin: 0;">Covoiturages par jour - <?= sprintf("%02d", $month) ?>/<?= $year ?></h2>
        <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>" class="arrow-button">&#x1F80A;</a>
    </div>

    <!-- Graphique centré -->
    <div style="max-width: 800px; width: 100%;">
        <canvas id="covoiturageChart"></canvas>
    </div>
</div>

        <script>
            const rawData = <?= json_encode($data) ?>;

            const labels = [];
            const values = [];

            rawData.forEach(row => {
                labels.push(row.jour);
                values.push(row.total);
            });

            const ctx = document.getElementById('covoiturageChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: labels,
                    datasets: [{
                        label: 'Nombre de covoiturages',
                        data: values,
                        backgroundColor: 'rgba(54, 162, 235, 0.6)',
                        borderColor: 'rgba(54, 162, 235, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    scales: {
                        x: {
                            title: {
                                display: true,
                                text: 'Jour du mois'
                            }
                        },
                        y: {
                            beginAtZero: true,
                            title: {
                                display: true,
                                text: 'Nombre de covoiturages'
                            }
                        }
                    }
                }
            });
        </script>





    </section>
<?php
$creditGraph = $controller->showCreditGraph();
$creditData = $creditGraph['data'];
$creditMonth = $creditGraph['month'];
$creditYear = $creditGraph['year'];


// Gestion des flèches
$prevCreditMonth = $creditMonth - 1;
$prevCreditYear = $creditYear;
if ($prevCreditMonth < 1) {
    $prevCreditMonth = 12;
    $prevCreditYear--;
}

$nextCreditMonth = $creditMonth + 1;
$nextCreditYear = $creditYear;
if ($nextCreditMonth > 12) {
    $nextCreditMonth = 1;
    $nextCreditYear++;
}
?>
    <section>
        <h3 style="text-align: center; margin-top: 20px;">
    Total des crédits gagnés par la plateforme : <?= $creditGraph['totalCredits'] ?? 0 ?> crédits
</h3>
 
<div style="display: flex; align-items: center; justify-content: center; gap: 20px; margin: 30px 0;">
    <a href="?year=<?= $prevCreditYear ?>&month=<?= $prevCreditMonth ?>" class="arrow-button">&#x1F808;</a>
    <h2>Nb de crédits par jour - <?= sprintf("%02d", $creditMonth) ?>/<?= $creditYear ?></h2>
    <a href="?year=<?= $nextCreditYear ?>&month=<?= $nextCreditMonth ?>" class="arrow-button">&#x1F80A;</a>
</div>
<div style="max-width: 800px; margin: 0 auto;">
    <canvas id="creditChart"></canvas>
</div>

<script>
    const creditData = <?= json_encode($creditData) ?>;
    const creditLabels = [];
    const creditValues = [];

    creditData.forEach(row => {
        creditLabels.push(row.jour);
        creditValues.push(row.total_credits);
    });

    const creditCtx = document.getElementById('creditChart').getContext('2d');
    new Chart(creditCtx, {
        type: 'bar',
        data: {
            labels: creditLabels,
            datasets: [{
                label: 'Crédits gagnés',
                data: creditValues,
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            scales: {
                x: {
                    title: {
                        display: true,
                        text: 'Date'
                    }
                },
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Nb crédits'
                    }
                }
            }
        }
    });
</script>

    </section>

    <section>
        
        
        <!-- <div class="form-admin"> -->
        <div class="form-admin">
            <h2 class="ajustement3">Supendre un compte utilisateur</h2>
            <div class="form-admin-content">
                
                <form action="" method="post">
                    <div class="form-fields">
                    <label for="email">Email de l'utilisateur :</label>
                    <input type="email" id="email" name="email" required>
                    <div class="button-container">
                    <button class='button' type="submit">Afficher</button>
                    </div>
                    </div>
                </form>
            </div>
        <!-- </div> -->
<?php

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['email'])) {
            echo "<h2>Informations sur l'utilisateur</h2>";
            $email = $_POST['email'];
            $userInfo = $controller->getAnAccount($email);
            if ($userInfo) {
                echo '<div class="form-admin-content">';
                echo "<p>Email: " . htmlspecialchars($userInfo['email']) . "</p>";
                echo "<p>Nom: " . htmlspecialchars($userInfo['nom']) . "</p>";
                echo "<p>Prénom: " . htmlspecialchars($userInfo['prenom']) . "</p>";
                
                // Ajoutez d'autres champs si nécessaire

                if ($userInfo['parametre'] == 'valide') {
                    
                 
                echo "<form action='' method='post'>";
                echo "<input type='hidden' name='suspend_email' value='" . htmlspecialchars($userInfo['email']) . "'>";
                echo '<div class="button-container">';
                echo '<button type="submit" name="suspend" class="button" onclick="return confirm(\'Confirmer la suspension de ce compte ?\');">Suspendre</button>';
                echo '</div>';
                
                echo '</form>';
                echo '</div>';
                }
                

                
                

                elseif ($userInfo['parametre'] == 'suspendu') {
                    echo "<p style='color: red;'>Compte suspendu</p>";
                    echo "<form method='post' action=''>";
                    echo "<input type='hidden' name='email' value='" . htmlspecialchars($userInfo['email']) . "'>";
                    echo '<div class="button-container">';
                    echo "<button type='submit' name='unsuspend' class='button'>Lever la suspension</button>";
                    echo "</div>";
                    echo "</form>";
                    echo "</div>";
                }
            };
        }
        ?>

    
        
    
<?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suspend_email'])) {
    $emailToSuspend = $_POST['suspend_email'];
    $suspensionResult = $controller->suspendAccount($emailToSuspend);
    
    if ($suspensionResult) {
        echo "<p style='color:green;'>Le compte de $emailToSuspend a été suspendu avec succès.</p>";
    } else {
        echo "<p style='color:red;'>Échec de la suspension du compte.</p>";
    }
}
    ?>
<?php endif; ?>
</section>

<?php
require_once '../views/footer.php';
?>
</body>
</html>
