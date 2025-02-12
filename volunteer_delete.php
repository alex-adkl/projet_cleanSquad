<?php
include "config.php"; 

// Vérifie si l'ID est bien passé et est un nombre
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']); // Convertie l'ID en entier

    try {
        // garantie les suppressions, s'il y a une suppression qui echoue,aucun changement n'est appliqué
        $pdo->beginTransaction();

        // Supprimer dechets liés au bénévole via collectes
        $sql = "DELETE FROM dechets_collectes WHERE id_collecte IN (SELECT id FROM collectes WHERE id_benevole = :id)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        //  Supprimer les collectes associées au bénévole
        $sql = "DELETE FROM collectes WHERE id_benevole = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Supprimer le bénévole
        $sql = "DELETE FROM benevoles WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([':id' => $id]);

        // Valider
        $pdo->commit();

        // Redirige vers la liste des bénévoles après suppression
        header("Location: volunteer_list.php");
        exit;
    } catch (PDOException $e) {
        // En cas d'erreur, annulation
        $pdo->rollBack();
        die("Erreur : " . $e->getMessage());
    }
} else {
    // Si l'ID est invalide
    die("ID invalide.");
}
?>
