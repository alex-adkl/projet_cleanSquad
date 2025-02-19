<?php
include 'securite.php';
include "config.php"; 

//vérifie si l'ID est bien passé et est un nombre
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Convertie l'ID en entier

    try { // garantit les suppressions, s'il y a une suppression qui echoue, aucun changement n'est appliqué
        
        //on demarre la transaction
        $pdo->beginTransaction(); 

        //on supprime les déchets liés au bénévole via collectes
        $sql = "DELETE FROM dechets_collectes WHERE id_collecte IN (SELECT id FROM collectes WHERE id_benevole = :id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        //on supprime les collectes associées au bénévole
        $sql = "DELETE FROM collectes WHERE id_benevole = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        //on supprime le bénévole
        $sql = "DELETE FROM benevoles WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        //on valide la transaction
        $pdo->commit(); 

        //on redirige vers la liste des bénévoles après suppression
        header("Location: volunteer_list.php");
        exit;
    } catch (PDOException $e) {
        // en cas d'erreur : annulation
        $pdo->rollBack();
        die("Erreur : " . $e->getMessage());
    }
} else {
    // Si l'ID est invalide
    die("ID invalide.");
}
?>
