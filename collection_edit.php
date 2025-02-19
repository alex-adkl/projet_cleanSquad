<?php
include 'securite.php';
require 'config.php';

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: index.php");
    exit;
}

$id = $_GET['id'];

// on récupère les infos de la collecte
$stmt = $pdo->prepare("SELECT * FROM collectes WHERE id = ?");  //prépare la requête
$stmt->execute([$id]);                                          //éxecute la requete
$collecte = $stmt->fetch();                                     //return le résultat

if (!$collecte) {
    header("Location: index.php");
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
    if (isset($_POST["date"], $_POST["lieu"], $_POST["benevole"])) { //isset détermine si une variable est déclarée et est différente de null
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
    header("Location: index.php");
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
<body class="bg-gray-100 text-gray-900" style="background: url('beach2.svg') no-repeat center center fixed; background-size: cover;">
    <div class="flex h-screen">
        <?php 
        require('menu.php');
        ?>
        <div class="flex-1 p-8 overflow-y-auto">
            <h1 class="text-4xl font-bold text-cyan-50 mb-6">Modifier une collecte</h1>
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <form method="POST" class="space-y-4">
                    <div>
                        <label class="block text-base font-medium text-gray-700">Date :</label>
                        <input type="date" name="date" value="<?= $collecte['date_collecte'] ?>" required class="w-full p-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-base font-medium text-gray-700">Lieu :</label>
                        <input type="text" name="lieu" value="<?= $collecte['lieu'] ?>" required class="w-full p-2 border border-gray-300 rounded-lg">
                    </div>
                    <div>
                        <label class="block text-base font-medium text-gray-700">Bénévole :</label>
                        <select name="benevole" required class="w-full p-2 border border-gray-300 rounded-lg">
                            <?php foreach ($benevoles as $benevole): ?>
                                <option value="<?= $benevole['id'] ?>" <?= $benevole['id'] == $collecte['id_benevole'] ? 'selected' : '' ?>><?= $benevole['nom'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <h2 class="block text-base font-bold text-cyan-700">Déchêts collectés</h2>
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
                                <label class="block text-base font-medium text-gray-700"><?= ucfirst($type_dechet) ?> (en kg) :</label>
                                <input type="number" name="quantite_kg[]" value="<?= $quantite ?>" class="pr-2 pl-2 w-40 border border-gray-300 rounded-lg" placeholder="Quantité en kg" step="0.1" min="0" max="99">
                                <input type="hidden" name="type_dechet[]" value="<?= $type_dechet ?>">
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="flex justify-end space-x-4">
                        <button href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-gray-700 transition duration-200">Annuler</button>
                        <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white px-4 py-2 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-cyan-700 transition duration-200">Confirmer les modifications</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>
</html>