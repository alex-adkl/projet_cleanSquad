<?php
require 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: collection_list.php");
    exit;
}

$id = $_GET['id'];

// on récupère les infos de la collecte
$stmt = $pdo->prepare("SELECT * FROM collectes WHERE id = ?");
$stmt->execute([$id]);
$collecte = $stmt->fetch();

if (!$collecte) {
    header("Location: collection_list.php");
    exit;
}

// on récupère les bénévoles
$stmt_benevoles = $pdo->prepare("SELECT id, nom FROM benevoles ORDER BY nom");
$stmt_benevoles->execute();
$benevoles = $stmt_benevoles->fetchAll();

// on récupère les déchets déjà enregistrés pour cette collecte
$stmt_dechets = $pdo->prepare("SELECT * FROM dechets_collectes WHERE id_collecte = ?");
$stmt_dechets->execute([$id]);
$dechets = $stmt_dechets->fetchAll();

// on met a jour la collecte et les déchets
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (isset($_POST["date"], $_POST["lieu"], $_POST["benevole"])) {
        $date = $_POST["date"];
        $lieu = $_POST["lieu"];
        $benevole_id = $_POST["benevole"];

        $stmt = $pdo->prepare("UPDATE collectes SET date_collecte = ?, lieu = ?, id_benevole = ? WHERE id = ?");
        $stmt->execute([$date, $lieu, $benevole_id, $id]);
    }

    // on supprime les anciens déchets
    $pdo->prepare("DELETE FROM dechets_collectes WHERE id_collecte = ?")->execute([$id]);

    // on insère les nouveaux déchets
    if (!empty($_POST["type_dechet"]) && !empty($_POST["quantite_kg"])) {
        $stmt = $pdo->prepare("INSERT INTO dechets_collectes (id_collecte, type_dechet, quantite_kg) VALUES (?, ?, ?)");
        foreach ($_POST["type_dechet"] as $index => $type) {
            $quantite = $_POST["quantite_kg"][$index];
            if (!empty($type) && is_numeric($quantite) && $quantite > 0) {
                $stmt->execute([$id, $type, $quantite]);
            }
        }
    }

    header("Location: collection_list.php");
    exit;
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
        <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">Tableau de bord</a></li>
        <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">Liste des bénévoles</a></li>
        <li><a href="user_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">Ajouter un bénévole</a></li>
        <li><a href="my_account.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">Mon compte</a></li>
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
                        <?php foreach ($benevoles as $benevole): ?>
                            <option value="<?= $benevole['id'] ?>" <?= $benevole['id'] == $collecte['id_benevole'] ? 'selected' : '' ?>><?= $benevole['nom'] ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <h2 class="text-lg font-bold mt-4">Déchets collectés</h2>
                <div id="dechets-container">
                    <?php
                    $types_dechets = ['plastique', 'verre', 'metal', 'organique', 'papier'];
                    foreach ($types_dechets as $i => $type_dechet) :
                        $quantite = '';
                        foreach ($dechets as $dechet) {
                            if ($dechet['type_dechet'] == $type_dechet) {
                                $quantite = $dechet['quantite_kg'];
                                break;
                            }
                        }
                    ?>
                        <div class="flex space-x-4 mb-2">
                            <label class="w-32 text-sm font-medium text-gray-700"><?= ucfirst($type_dechet) ?> :</label>
                            <input type="number" name="quantite_kg[]" value="<?= $quantite ?>" class="p-2 border border-gray-300 rounded-lg" placeholder="Quantité" step="0.1" min="0" max="99">
                            <input type="hidden" name="type_dechet[]" value="<?= $type_dechet ?>">
                        </div>
                    <?php endforeach; ?>
                </div>
                <a href="collection_list.php" class="bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">Annuler</a>
                <button type="submit" class="bg-cyan-500 text-white px-4 py-2 rounded-lg">Modifier</button>
               
            </form>
        </div>
    </div>
</div>
</body>
</html>