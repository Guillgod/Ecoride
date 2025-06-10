# Ecoride
ECF


Déploiement de l’application en local
Pour déployer l’application ECORIDE en local, j’ai suivi les étapes suivantes :
1.	Installation des outils nécessaires
o	XAMPP : pour disposer d’un serveur web local (Apache) et d’un système de gestion de base de données (MySQL).
o	Visual Studio Code : comme éditeur de code principal pour développer les fichiers PHP, HTML, CSS et JavaScript.
o	Navigateur Web : pour visualiser l'application (ex. : Google Chrome, Firefox).
2.	Configuration de XAMPP
o	Lancement de Apache pour faire fonctionner le serveur web.
o	Lancement de MySQL pour accéder à la base de données via phpMyAdmin.
o	Utilisation des ports par défaut : 80 pour Apache et 3306 pour MySQL.
3.	Création de la base de données
o	Ouverture de http://localhost/phpmyadmin.
o	Création d’une base de données nommée ecoride.
o	Importation des tables nécessaires via un script .sql (préparé au préalable).
4.	Placement du projet dans le répertoire adéquat
o	Copie du dossier du projet ECORIDE dans le dossier htdocs de XAMPP :
C:\xampp\htdocs\ecoride
5.	Configuration de l’application
o	Création et configuration du fichier de connexion à la base de données (Model.php ou équivalent) avec les bons identifiants :
1.	Nom d’utilisateur par défaut : root
2.	Mot de passe aucun.
Un exemple de PDO accédant à la base de données ci-dessous  
$pdo = new PDO('mysql:host=localhost;dbname=ecoride', 'root', '');

o	Vérification que les chemins d’accès, les routes et les inclusions de fichiers sont corrects.
6.	Lancement de l’application
o	Dans le navigateur, accès au projet via :
http://localhost/ecoride
o	Navigation dans les différentes pages, vérification des connexions, des requêtes, des formulaires, etc.
