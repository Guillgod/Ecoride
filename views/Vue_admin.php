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

<div style="max-width: 800px;">
    <div style="display: flex; align-items: center; justify-content: center; gap: 20px; margin-bottom: 20px;">
        <a href="?year=<?= $prevYear ?>&month=<?= $prevMonth ?>" style="font-size: 24px;">⬅️</a>
        <h2 style="margin: 0;">Covoiturages par jour - <?= sprintf("%02d", $month) ?>/<?= $year ?></h2>
        <a href="?year=<?= $nextYear ?>&month=<?= $nextMonth ?>" style="font-size: 24px;">➡️</a>
    </div>

    <canvas id="covoiturageChart"></canvas>
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

    <section>
        
    </section>

</body>
</html>
