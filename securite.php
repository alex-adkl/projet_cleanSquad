<?php
if (!defined('SECURITE_INCLUDED')) {
    define('SECURITE_INCLUDED', true);
    session_start();
}

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

require 'config.php';
?>
