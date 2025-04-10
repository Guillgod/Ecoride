
<!DOCTYPE html5>
<html lang="fr">
    <head>
        <title>Ecoride - Covoiturage responsable </title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="../css/style.css" rel="stylesheet">
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Bebas+Neue&family=Noto+Sans+JP:wght@100..900&family=Permanent+Marker&family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    </head>

    <body>
  
        <section class="filtre">
                
                <form method="get" action="carpool_list.php">
                    <ul class="menu">
                        <li>
                            <label for="vehicletype">Type de véhicule:</label>

                            <select name="Ecologique" id="Ecologique">
                                <option value="">--Type de véhicule--</option>
                                <option value="Electrique" <?= (isset($_GET['Ecologique']) && $_GET['Ecologique'] === 'Electrique') ? 'selected' : '' ?>>Électrique</option> 
                                <option value="Thermique"><?= (isset($_GET['Ecologique']) && $_GET['Ecologique'] === 'Thermique') ? 'selected' : '' ?>Diesel/Essence</option>
                            </select>

                        </li>

                        <li>
                            <label for="Prixmax">Prix max (€):</label>
                            <input type="number" id="Prixmax" name="Prixmax" minlength="1" maxlength="50" size="30" placeholder="Entrez votre prix max" value="<?= htmlspecialchars($_GET['Prixmax'] ?? '') ?>">
                        </li>
                        <li>
                            <label for="Dureemax">Durée max du trajet :</label>
                            <input type="time" id="Dureemax" name="Dureemax"   minlength="1" maxlength="50" size="30" placeholder="Entrez la durée max" value="<?= htmlspecialchars($_GET['Dureemax'] ?? '') ?>">
                            <button type="button" onclick="document.getElementById('Dureemax').value = ''" style="background: none; border: none; color: red; font-size: 1.2em; cursor: pointer; margin-left: 5px;">🗑️</button>
                        </li>
                        <li>
                            <label for="Notemin">Note minimale :</label>
                            <select name="Notemin" id="Notemin">
                                <option value="">--Note minimale--</option>
                                <option value="1" <?= (isset($_GET['Notemin']) && $_GET['Notemin'] === '1') ? 'selected' : '' ?>>1</option>
                                <option value="2" <?= (isset($_GET['Notemin']) && $_GET['Notemin'] === '2') ? 'selected' : '' ?>>2</option>
                                <option value="3" <?= (isset($_GET['Notemin']) && $_GET['Notemin'] === '3') ? 'selected' : '' ?>>3</option>
                                <option value="4" <?= (isset($_GET['Notemin']) && $_GET['Notemin'] === '4') ? 'selected' : '' ?>>4</option>
                                <option value="5" <?= (isset($_GET['Notemin']) && $_GET['Notemin'] === '5') ? 'selected' : '' ?>>5</option>
                            </select>
                        </li>
                        <button class="button" type="submit">Appliquer</button>
                    </ul>
                </form>

            </section>

    </body>
</html>