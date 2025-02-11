<?php
function hashPassword($password){
    return password_hash($password, PASSWORD_DEFAULT); 
}

// $password = "test"; // Change le mot de passe ici
// $hash = password_hash($password, PASSWORD_DEFAULT);

// echo "Mot de passe hashé : " . $hash;
?>
