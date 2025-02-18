<?php
//include 'securite.php';
function hashpassword($password){
    return password_hash($password, PASSWORD_DEFAULT);
}
// $password = "test";
// $password = "test2";
// $hash = password_hash($password, PASSWORD_DEFAULT);
// echo "mot de passe hashé : ".$hash;

?>

