<?php
session_start();            //demarre une session
session_unset();            //détruit toutes les variables d'une session
session_destroy();          //détruit toutes les données associées à une session en cours
setcookie(session_name(), '', time() - 3600, '/'); //détruit les cookies d'une session

header('Location: login.php'); 
exit();


?>

