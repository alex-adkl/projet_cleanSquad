<?php
include 'securite.php';
require 'config.php';

// on vérifie si un ID de collecte est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: collection_list.php");
    exit;
}

$id = $_GET['id'];

// on récupère les informations de la collecte
$stmt = $pdo->prepare("SELECT * FROM collectes WHERE id = ?");
$stmt->execute([$id]);
$collecte = $stmt->fetch();

if (!$collecte) {
    header("Location: collection_list.php");
    exit;
}

// on récupère la liste des bénévoles
$stmt_benevoles = $pdo->prepare("SELECT id, nom FROM benevoles ORDER BY nom");
$stmt_benevoles->execute();
$benevoles = $stmt_benevoles->fetchAll();

// Mise a jour collecte
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["date"], $_POST["lieu"], $_POST["benevole"])) {
        $date = $_POST["date"];
        $lieu = $_POST["lieu"];
        $benevole_id = $_POST["benevole"];

        $stmt = $pdo->prepare("UPDATE collectes SET date_collecte = ?, lieu = ?, id_benevole = ? WHERE id = ?");
        $stmt->execute([$date, $lieu, $benevole_id, $id]);
        header("Location: collection_list.php");
        exit;
    }
        // Insertion des dechets dans table 
    if (isset($_POST["type_dechet"], $_POST["quantite_kg"])) {
        $type_dechet = $_POST["type_dechet"];
        $quantite_kg = $_POST["quantite_kg"];

        $stmt = $pdo->prepare("INSERT INTO dechets_collectes (id_collecte, type_dechet, quantite_kg) VALUES (?, ?, ?)");
        $stmt->execute([$id, $type_dechet, $quantite_kg]);
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une collecte</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

<div class="flex h-screen">
    <div class="bg-cyan-500 text-white w-64 p-6">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

            <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
            <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
            <li><a href="user_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fas fa-plus-circle mr-3"></i> Ajouter un bénévole</a></li>
            <li><a href="my_account.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fas fa-cogs mr-3"></i> Mon compte</a></li>

        <div class="mt-6">
            <button  class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg shadow-md">
            <a href="logout.php" > Déconnexion</a>
            </button>
        </div>
    </div>

    <div class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-4xl font-bold text-blue-900 mb-6">Modifier une collecte</h1>

        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date :</label>
                    <input type="date" name="date" value="<?= $collecte['date_collecte'] ?>" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu :</label>
                    <input type="text" name="lieu" value="<?= $collecte['lieu'] ?>" required class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bénévole :</label>
                    <select name="benevole" required class="w-full p-2 border border-gray-300 rounded-lg">
                        <option value="" disabled selected>Sélectionnez un·e bénévole</option>
                        <?php foreach ($benevoles as $benevole): ?>
                            <option value="<?= $benevole['id'] ?>" <?= $benevole['id'] == $collecte['id_benevole'] ? 'selected' : '' ?>><?= $benevole['nom'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-end space-x-4">
                    <a href="collection_list.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Annuler</a>
                    <button type="submit" class="bg-cyan-500 text-white px-4 py-2 rounded-lg">Modifier</button>
                </div>
            </form>
        </div>

        <div class="bg-white p-6 rounded-lg shadow-lg mt-6">
            <form method="POST">
                <label for="type_dechet">Type de déchet :</label>
                <select name="type_dechet" id="type_dechet" required>
                    <option value="" disabled selected>Choisissez</option>
                    <!-- Option Value (menu deroulant html) -->
                    <option value="plastique">plastique</option>
                    <option value="verre">verre</option>
                    <option value="metal">métal</option>
                    <option value="organique">organique</option>
                    <option value="papier">papier</option>
                </select>
                <label for="quantite_kg">Poids (kg) :</label>
                <input type="number" id="quantite_kg" name="quantite_kg" placeholder="1.0" step="0.1" min="0" max="99" required />
                <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg">Ajouter</button>
            </form>
        </div>
    </div>
</div>

</body>
</html>