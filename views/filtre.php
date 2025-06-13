
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

    
        
         
<section class="Barrerecherche">

    <!-- Checkbox pour afficher/cacher les filtres -->
    <label style="display: block; margin-bottom: 10px; cursor: pointer;">
        <input type="checkbox" id="toggleFilters">
        <label for="toggleFilters">Filtre</label>
    </label>

    <!-- Section filtres cachée par défaut -->
    <div id="filtersSection" style="display:none;">

        <div class="titre-centrer">
            <h2>Vos filtres :</h2>
        </div>

        <form class="Barrerecherche" method="get" action="carpool_list.php">
            <div class="form-recherche">
                <div class="form-recherche-content">
                
                    <label for="vehicletype">Type de véhicule:</label>
                    <select name="Ecologique" id="Ecologique">
                        <option value="">Type de véhicule</option>
                        <option value="Electrique" <?= (isset($_GET['Ecologique']) && $_GET['Ecologique'] === 'Electrique') ? 'selected' : '' ?>>Électrique</option> 
                        <option value="Thermique" <?= (isset($_GET['Ecologique']) && $_GET['Ecologique'] === 'Thermique') ? 'selected' : '' ?>>Diesel/Essence</option>
                    </select>
                
                </div>
                <div class="form-recherche-content">
                
                    <label for="Prixmax">Prix max (€):</label>
                    <input type="number" id="Prixmax" style="width: 120px;"  name="Prixmax" minlength="1" maxlength="5" size="30" placeholder="Prix" value="<?= htmlspecialchars($_GET['Prixmax'] ?? '') ?>">
                
                </div>
                <div class="form-recherche-content">
                    <label for="Dureemax">Durée max du trajet :</label>
                    <div class="duree-wrapper">
                        <input type="time" id="Dureemax" name="Dureemax" minlength="1" maxlength="50" size="30" placeholder="Entrez la durée max" value="<?= htmlspecialchars($_GET['Dureemax'] ?? '') ?>">
                        <button type="button" onclick="document.getElementById('Dureemax').value = ''" class="reset-time">❌</button>
                    </div>
                </div>
                <!-- <div class="form-recherche-content">
                
                    <label for="Dureemax">Durée max du trajet :</label>
                    <input type="time" id="Dureemax" name="Dureemax"   minlength="1" maxlength="50" size="30" placeholder="Entrez la durée max" value="<?= htmlspecialchars($_GET['Dureemax'] ?? '') ?>">
                    <button type="button" onclick="document.getElementById('Dureemax').value = ''" style="background: none; border: none; color: red; font-size: 1.2em; cursor: pointer; margin-left: 5px;">❌</button>
                
                </div> -->
                <div class="form-recherche-content">
                
                    <label for="Notemin">Note minimale :</label>
                    <select name="Notemin" id="Notemin">
                        <option value="">Note minimale</option>
                        <option value="1" <?= (isset($_GET['Notemin']) && $_GET['Notemin'] === '1') ? 'selected' : '' ?>>1</option>
                        <option value="2" <?= (isset($_GET['Notemin']) && $_GET['Notemin'] === '2') ? 'selected' : '' ?>>2</option>
                        <option value="3" <?= (isset($_GET['Notemin']) && $_GET['Notemin'] === '3') ? 'selected' : '' ?>>3</option>
                        <option value="4" <?= (isset($_GET['Notemin']) && $_GET['Notemin'] === '4') ? 'selected' : '' ?>>4</option>
                        <option value="5" <?= (isset($_GET['Notemin']) && $_GET['Notemin'] === '5') ? 'selected' : '' ?>>5</option>
                    </select>
                
                </div>
                <div class="form-recherche-content-button">
                <button class="button" type="submit">Appliquer</button>
                </div>
            </div>
        </form>

    </div>

</section>

<script>
    // Ciblage checkbox et section filtres
    const toggleFilters = document.getElementById('toggleFilters');
    const filtersSection = document.getElementById('filtersSection');

    // Au changement de la checkbox, afficher ou cacher la section filtres
    toggleFilters.addEventListener('change', () => {
        filtersSection.style.display = toggleFilters.checked ? 'block' : 'none';
    });

    // Optionnel : si tu veux que la checkbox soit cochée si on a déjà des filtres dans l'URL
    window.addEventListener('load', () => {
        // Exemple simple : si au moins un filtre est présent dans l'URL, coche la checkbox et affiche
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.get('Ecologique') || urlParams.get('Prixmax') || urlParams.get('Dureemax') || urlParams.get('Notemin')) {
            toggleFilters.checked = true;
            filtersSection.style.display = 'block';
        }
    });
</script>


    </body>
</html>