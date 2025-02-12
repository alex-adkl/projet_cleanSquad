<?php
require 'config.php';

// Vérifier si un ID de collecte est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: collection_list.php");
    exit;
}

$id = $_GET['id'];

// Récupération des informations de la collecte en base de données
$stmt = $pdo->prepare("SELECT * FROM collectes WHERE id = ?");
$stmt->execute([$id]);
$collecte = $stmt->fetch();

// Vérifier si la collecte existe bien en base
if (!$collecte) {
    // Si aucune collecte trouvée, rediriger vers la liste des collectes
    header("Location: collection_list.php");
    exit;
}

// Récupérer la liste des bénévoles pour l'affichage dans le formulaire
$stmt_benevoles = $pdo->prepare("SELECT id, nom FROM benevoles ORDER BY nom");
$stmt_benevoles->execute();
$benevoles = $stmt_benevoles->fetchAll();

// Vérifier si le formulaire de mise à jour a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["date"], $_POST["lieu"], $_POST["benevole"])) {
    // Sécurisation des entrées utilisateur
    $date = htmlspecialchars($_POST["date"]);
    $lieu = htmlspecialchars($_POST["lieu"]);
    $benevole_id = (int) $_POST["benevole"];

    // Mettre à jour les informations de la collecte dans la base de données
    $stmt = $pdo->prepare("UPDATE collectes SET date_collecte = ?, lieu = ?, id_benevole = ? WHERE id = ?");
    $stmt->execute([$date, $lieu, $benevole_id, $id]);

    // Rediriger vers la liste des collectes après la mise à jour
    header("Location: collection_list.php");
    exit;
}

// Vérifier si le formulaire d'ajout de déchets a été soumis
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["type_dechet"], $_POST["quantite_kg"])) {
        // Sécurisation des entrées utilisateur
        $type_dechet = htmlspecialchars($_POST["type_dechet"]);
        $quantite_kg = (float) $_POST["quantite_kg"];

        
            // Insérer les informations du déchet collecté dans la base de données
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
    <!-- Inclusion de Tailwind CSS pour le style -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

<div class="flex h-screen">
    <!-- Barre latérale (Dashboard) -->
    <div class="bg-cyan-500 text-white w-64 p-6">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
        <ul>
            <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">📊 Tableau de bord</a></li>
            <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">👥 Liste des bénévoles</a></li>
            <li><a href="user_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">➕ Ajouter un bénévole</a></li>
            <li><a href="my_account.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">⚙️ Mon compte</a></li>
        </ul>
        <div class="mt-6">
            <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg shadow-md">
                Déconnexion
            </button>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-4xl font-bold text-blue-900 mb-6">Modifier une collecte</h1>

        <!-- Formulaire pour modifier la collecte -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date :</label>
                    <input type="date" name="date" value="<?= htmlspecialchars($collecte['date_collecte']) ?>" required
                           class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu :</label>
                    <input type="text" name="lieu" value="<?= htmlspecialchars($collecte['lieu']) ?>" required
                           class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bénévole :</label>
                    <select name="benevole" required class="w-full p-2 border border-gray-300 rounded-lg">
                        <option value="" disabled selected>Sélectionnez un·e bénévole</option>
                        <?php foreach ($benevoles as $benevole): ?>
                            <option value="<?= $benevole['id'] ?>" <?= $benevole['id'] == $collecte['id_benevole'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($benevole['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="flex justify-end space-x-4">
                    <a href="collection_list.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Annuler</a>
                    <button type="submit" class="bg-cyan-500 text-white px-4 py-2 rounded-lg">Modifier</button>
                </div>
            </form>
        </div>

        <!-- Formulaire pour ajouter un déchet -->
        <div class="bg-white p-6 rounded-lg shadow-lg mt-6">
            <form method="POST">
                <input type="hidden" name="action" value="ajouter_dechet">
                <label for="type_dechet">Type de déchet :</label>
                <select name="type_dechet" id="type_dechet" required>
                    <option value="">--Choisissez--</option>
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

                    <!-- https://www.conseil-webmaster.com/formation/php/09-variables-post-php.php#envoyer_post -->
<!-- <form method="POST">
    <label>Type de déchet :</label>
    <input type="text" name="type_dechet">
    <label>Quantité (kg) :</label>
    <input type="number" name="quantite_kg" step="0.1"> -->
    <!-- https://developer.mozilla.org/fr/docs/Web/HTML/Attributes/step -->
    <!-- <button type="submit">Ajouter</button>
</form> -->