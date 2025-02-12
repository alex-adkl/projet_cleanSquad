<?php
    include "config.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Bénévoles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
<div class="flex h-screen">
    <!-- Barre de navigation -->
    <div class="bg-cyan-500 text-white w-64 p-6">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>
            <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
            <li><a href="collection_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fas fa-plus-circle mr-3"></i> Ajouter une collecte</a></li>
            <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
            <li>
                <a href="user_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">
                    <i class="fas fa-user-plus mr-3"></i> Ajouter un bénévole
                </a>
            </li>
            <li><a href="my_account.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                            class="fas fa-cogs mr-3"></i> Mon compte</a></li>
        <div class="mt-6">
            <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg shadow-md">
                Déconnexion
            </button>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <!-- Titre -->
        <h1 class="text-4xl font-bold text-blue-800 mb-6">Liste des Bénévoles</h1>

        <!-- Tableau des bénévoles -->
        <div class="overflow-hidden rounded-lg shadow-lg bg-white">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-blue-800 text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Nom</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Rôle</th>
                    <th class="py-3 px-4 text-left">Nombre total de déchêts collectés</th>
                    <th class="py-3 px-4 text-left">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                
                <?php
$sql = "SELECT benevoles.id, benevoles.nom, benevoles.email, benevoles.role, 
        SUM(dechets_collectes.quantite_kg) AS total_dechets
        FROM benevoles
        LEFT JOIN collectes ON benevoles.id = collectes.id_benevole
        LEFT JOIN dechets_collectes ON collectes.id = dechets_collectes.id_collecte
        GROUP BY benevoles.id, benevoles.nom, benevoles.email, benevoles.role";

        $stmt = $pdo->query($sql);
        $users = $stmt->fetchAll(PDO::FETCH_ASSOC);

// boucle sur chaque benevole récupéré 
foreach ($users as $benevole) { 
        $nom = htmlspecialchars($benevole['nom']); 
        $email = htmlspecialchars($benevole['email']);
        $role = htmlspecialchars($benevole['role']);

// Vérifie si total_dechets est NULL et remplace par 0 si c'est le cas > sinon ERREUR
$total_dechets = $benevole['total_dechets'] !== null ? number_format($benevole['total_dechets'], 2) : "0.00"; 

echo "<tr class='hover:bg-gray-100 transition duration-200'>
        <td class='py-3 px-4'>$nom</td>
        <td class='py-3 px-4'>$email</td>
        <td class='py-3 px-4'>$role</td>
        <td class='py-3 px-4'>$total_dechets kg</td>
        <td class='py-3 px-4 flex space-x-2'>
            <a href='volunteer_edit.php?id={$benevole['id']}'
                class='bg-cyan-500 hover:bg-cyan-600 text-white px-4 py-2 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200'>
                ✏️ Modifier
            </a>
            <a href='volunteer_delete.php?id={$benevole['id']}'
                class='bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200'
                onclick=\"return confirm('Voulez-vous vraiment supprimer ce bénévole ?');\">
                🗑️ Supprimer
            </a>
        </td>
    </tr>";
}
?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

