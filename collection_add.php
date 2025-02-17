<?php
require 'config.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// on récupère la liste des bénévoles
$stmt_benevoles = $pdo->query("SELECT id, nom FROM benevoles ORDER BY nom");
$stmt_benevoles->execute();
$benevoles = $stmt_benevoles->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST["date"];
    $lieu = $_POST["lieu"];
    $benevole_id = $_POST["benevole"];  

    // on insère la collecte dans la table collecte avec le bénévole sélectionné
    $stmt = $pdo->prepare("INSERT INTO collectes (date_collecte, lieu, id_benevole) VALUES (?, ?, ?)");
    if (!$stmt->execute([$date, $lieu, $benevole_id])) {
        die('Erreur lors de l\'insertion dans la base de données.');
    }

    // Insertion des déchets
    if (!empty($_POST["type_dechet"]) && !empty($_POST["quantite_kg"])) {
        $stmt_dechets = $pdo->prepare("INSERT INTO dechets_collectes (id_collecte, type_dechet, quantite_kg) VALUES (?, ?, ?)");
        $id_collecte = $pdo->lastInsertId();
        foreach ($_POST["type_dechet"] as $index => $type) {
            $quantite = isset($_POST["quantite_kg"][$index]) && is_numeric($_POST["quantite_kg"][$index]) ? $_POST["quantite_kg"][$index] : 0;
            if (!empty($type) && is_numeric($quantite)) {
                $stmt_dechets->execute([$id_collecte, $type, $quantite]);
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
    <title>Ajouter une collecte</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

<div class="flex h-screen">

<?php 
require('menu.php');
?>

    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <!-- Titre -->
        <h1 class="text-4xl font-bold text-sky-700 mb-6">Ajouter une collecte</h1>

        <!-- Formulaire -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="POST" class="space-y-4">
                <!-- Date -->
                <div>
                    <label class="block text-base font-medium text-gray-700">Date :</label>
                    <input type="date" name="date" required
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Lieu -->
                <div>
                    <label class="block text-base font-medium text-gray-700">Lieu :</label>
                    <input type="text" name="lieu" required
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Bénévole responsable -->
                <div>
                    <label class="block text-base font-medium text-gray-700">Bénévole Responsable :</label>
                    <select name="benevole" required
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner un bénévole</option>
                        <?php foreach ($benevoles as $benevole): ?>
                            <option value="<?= $benevole['id'] ?>" <?= $benevole['id'] ==  'selected' ?>>
                                <?= htmlspecialchars($benevole['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>  
                
                <h2 class="block text-base font-bold text-cyan-700">Déchêts à ajouter</h2>
                <div id="dechets-container">
                    <?php
                    $types_dechets = ['plastique', 'verre', 'metal', 'organique', 'papier'];
                    foreach ($types_dechets as $type_dechet) :
                    ?>
                        <div class="flex space-x-4 mb-2">
                            <label class="block text-base font-medium text-gray-700"><?= ucfirst($type_dechet) ?> (en kg) :</label>
                            <input type="number" name="quantite_kg[]" value="" class="pr-2 pl-2 w-40 border border-gray-300 rounded-lg" placeholder="Quantité en kg" step="0.1" min="0" max="99">
                            <input type="hidden" name="type_dechet[]" value="<?= $type_dechet ?>">
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end space-x-4">
                <button href="collection_list.php" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-gray-700 transition duration-200">Annuler</button>
                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white px-4 py-2 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-cyan-700 transition duration-200">
                        Ajouter la collecte
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

</body>
</html>