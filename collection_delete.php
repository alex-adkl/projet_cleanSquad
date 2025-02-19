<<?php
include 'securite.php';
require 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];                                            //on convertit d'ID en entier
    try {
        $pdo->beginTransaction();                                         //on démarre la transaction                         

        // on supprime les déchets  de la collecte
        $stmt = $pdo->prepare("DELETE FROM dechets_collectes WHERE id_collecte = :id"); //on prépare la requête
        $stmt->execute([':id' => $id]);                                                 //on éxécute

        // on supprime la collecte 
        $stmt = $pdo->prepare("DELETE FROM collectes WHERE id = :id"); //on prépare la requête
        $stmt->execute([':id' => $id]);                                //on éxécute

        $pdo->commit();                                                //on valide la transaction

        header("Location: index.php");
        exit;  

    } catch (PDOException $e) {
        $pdo->rollBack();
        die(" Erreur SQL : " . $e->getMessage());
    }
} else {
    echo " ID invalide.";
}
?>

