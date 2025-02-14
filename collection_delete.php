<<?php
require 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    try {
        $pdo->beginTransaction();
       // Supprimer les déchets  de la collecte
        $stmt = $pdo->prepare("DELETE FROM dechets_collectes WHERE id_collecte = :id");
        $stmt->execute([':id' => $id]);  
     // Supprimer la collecte 
        $stmt = $pdo->prepare("DELETE FROM collectes WHERE id = :id");
        $stmt->execute([':id' => $id]);
        $pdo->commit();
        header("Location: collection_list.php");
        exit;  
    } catch (PDOException $e) {
        $pdo->rollBack();
        die(" Erreur SQL : " . $e->getMessage());
    }
} else {
    echo " ID invalide.";
}
?>

