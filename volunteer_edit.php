<?php

include "config.php";
include "hash_password.php";

// on verifie si la requete est de type POST et si le champ ID existe
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['id'])) {
    // on recup les données du formulaire
    $id = intval($_POST['id']); // on convertit la valeur de ID en entier par securité
    $nom = htmlspecialchars($_POST['nom']);
    $email = htmlspecialchars($_POST['email']);
    $mot_de_passe_hash = hashPassword($_POST['mot_de_passe']);
    $role = htmlspecialchars($_POST['role']);

    try {
        // on prepare la requête UPDATE
        $sql = "UPDATE benevoles 
                SET nom = :nom, email = :email, mot_de_passe = :mot_de_passe, role = :role 
                WHERE id = :id";
        
        $stmt = $pdo->prepare($sql);

        // on lie les paramètres à la requête
        $stmt->bindParam(':nom', $nom);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':mot_de_passe', $mot_de_passe_hash);
        $stmt->bindParam(':role', $role);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        // on éxecute la requête
        $stmt->execute();

        // on redirige vers la liste des bénévoles après la maj
    header("Location: volunteer_list.php");
    exit; // après un header() on arrête l'exécution du script

} catch (PDOException $e) {
    echo "<p>Erreur : " . $e->getMessage() . "</p>";
}
    }


// on recup les infos du bénévole pour les afficher dans le formulaire
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $stmt = $pdo->prepare("SELECT * FROM benevoles WHERE id = :id");
    $stmt->bindParam(':id', $id, PDO::PARAM_INT);
    $stmt->execute();
    $benevole = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$benevole) {
        die("Bénévole non trouvé.");
    }
} else {
    die("ID manquant.");
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier un Bénévole</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

<div class="flex h-screen">
<?php 
require('menu.php');
?>

    <div class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-4xl font-bold text-sky-700 mb-6">Modifier un compte Bénévole</h1>

        <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto">
            <form action="volunteer_edit.php" method="POST">
                <input type="hidden" name="id" value="<?= htmlspecialchars($benevole['id']); ?>">

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Nom</label>
                    <input type="text" name="nom" value="<?= htmlspecialchars($benevole['nom']); ?>"
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Email</label>
                    <input type="email" name="email" value="<?= htmlspecialchars($benevole['email']); ?>"
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500"
                           required>
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Nouveau mot de passe (laisser vide pour ne pas changer)</label>
                    <input type="password" name="mot_de_passe"
                           class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <div class="mb-4">
                    <label class="block text-gray-700 font-medium">Rôle</label>
                    <select name="role" class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="participant" <?= ($benevole['role'] == 'participant') ? 'selected' : ''; ?>>Participant</option>
                        <option value="admin" <?= ($benevole['role'] == 'admin') ? 'selected' : ''; ?>>Admin</option>
                    </select>
                </div>

                <div class="mt-6">
                    <button type="submit"
                            class="w-full bg-cyan-500 hover:bg-cyan-600 text-white py-3 rounded-lg shadow-md font-semibold">
                        Modifier le compte bénévole
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>