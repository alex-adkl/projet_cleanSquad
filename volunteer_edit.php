<?php

require_once  'securite.php';
include "config.php";
require_once  "hash_password.php";

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
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">

<div class="flex h-screen">
    <div class="bg-cyan-500 text-white w-64 p-6">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
        <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">
                <i class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
        <li><a href="collection_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">
                <i class="fas fa-plus-circle mr-3"></i> Ajouter une collecte</a></li>
        <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">
                <i class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
        <li><a href="my_account.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">
                <i class="fas fa-cogs mr-3"></i> Mon compte</a></li>
        <div class="mt-6">
            <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg shadow-md">
            <a href="logout.php" > Déconnexion</a>
            </button>
        </div>
    </div>

    <div class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-4xl font-bold text-blue-800 mb-6">Modifier un compte Bénévole</h1>

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