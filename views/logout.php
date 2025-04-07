<?php
session_start();
session_unset();
session_destroy();
header('Location: Page_accueil.php');
exit;
?>