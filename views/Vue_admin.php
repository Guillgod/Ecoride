<?php
require_once '../models/ModelAdmin.php';
require_once '../controllers/Admin_Controller.php';

$controller = new CreationGraph(new ModelAdmin());
$graphInfo = $controller->showGraphPage();
$data = $graphInfo['data'];
$month = $graphInfo['month'];
$year = $graphInfo['year'];

?>



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
        <h2>Covoiturages par jour - Mois  </h2>
            <?php foreach ($data as $row): ?>
            <p><?= $row['jour'] ?> : <?= $row['total'] ?> covoiturages</p>
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
        <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>" style="font-size: 24px;">⬅️</a>
        <h2 style="margin: 0;">Covoiturages par jour - <?= sprintf("%02d", $month) ?>/<?= $year ?></h2>
        <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>" style="font-size: 24px;">➡️</a>
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
<h2>Nb de crédits par jour - <?= sprintf("%02d", $creditGraph['month']) ?>/<?= $creditGraph['year'] ?></h2>
<div style="display: flex; align-items: center; justify-content: center; gap: 20px; margin: 30px 0;">
    <a href="?year=<?= $prevCreditYear ?>&month=<?= $prevCreditMonth ?>" style="font-size: 24px;">⬅️</a>
    <h2>Nb de crédits par jour - <?= sprintf("%02d", $creditMonth) ?>/<?= $creditYear ?></h2>
    <a href="?year=<?= $nextCreditYear ?>&month=<?= $nextCreditMonth ?>" style="font-size: 24px;">➡️</a>
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

</body>
</html>
