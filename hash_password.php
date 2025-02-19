<?php

function hashpassword($password){
    return password_hash($password, PASSWORD_DEFAULT);
}

?>

