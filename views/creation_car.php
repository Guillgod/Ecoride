<!-- creation_car.php (modifié) -->

<!-- PAS de doctype, html, head ou body ici -->
<div class="form-voiture-container">
  <div class="champ-voiture">
    <label for="modele">Modèle :</label>
    <input type="text" name="modele" required>
  </div>

  <div class="champ-voiture">
    <label for="immatriculation">Immatriculation :</label>
    <input type="text" name="immatriculation" required>
  </div>

  <div class="champ-voiture">
    <label for="nb_place_voiture">Nombre de places :</label>
    <input type="number" name="nb_place_voiture" required>
  </div>

  <div class="champ-voiture">
    <label for="energie">Type de véhicule :</label>
    <select name="energie" required>
      <option value="">--Type de véhicule--</option>
      <option value="Electrique">Écologique</option>
      <option value="Thermique">Diesel/Essence</option>
    </select>
  </div>

  <div class="champ-voiture">
    <label for="couleur">Couleur :</label>
    <input type="text" name="couleur" required>
  </div>

  <div class="champ-voiture">
    <label for="date_premiere_immatriculation">Date première immatriculation :</label>
    <input type="date" name="date_premiere_immatriculation" required>
  </div>

  <div class="champ-voiture">
    <label for="marque">Marque :</label>
    <input type="text" name="marque" required>
  </div>
</div>