<?php
if (!defined('SECURITE_INCLUDED')) {    //s'il n'y a pas de securité
    define('SECURITE_INCLUDED', true);  //on la définit
    session_start();                    //on démarre la session
}

if (!isset($_SESSION['user_id'])) {     //s'il n'y a pas de user ID pour la session
    header('Location: login.php');      //on redirige vers la page de login
    exit();
}

require 'config.php';
?>
