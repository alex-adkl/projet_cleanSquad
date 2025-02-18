<?php

include 'securite.php';
    include "config.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Bénévoles</title>
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
        <h1 class="text-4xl font-bold text-sky-700 mb-6">Liste des Bénévoles</h1>

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
// SUM de quantité total  par BENEVOLE 
$sql = "SELECT benevoles.id, benevoles.nom, benevoles.email, benevoles.role, 
        SUM(dechets_collectes.quantite_kg) AS total_dechets
        FROM benevoles
        LEFT JOIN collectes ON benevoles.id = collectes.id_benevole
        LEFT JOIN dechets_collectes ON collectes.id = dechets_collectes.id_collecte
        GROUP BY benevoles.id, benevoles.nom, benevoles.email, benevoles.role";
// Recupere + Fetch dans users 
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
                class='bg-cyan-500 hover:bg-cyan-700 text-white px-4 py-2 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-cyan-700 transition duration-200'>
                Modifier le compte
            </a>
            <a href='volunteer_delete.php?id={$benevole['id']}'
                class='bg-amber-500 hover:bg-amber-700 text-white px-4 py-2 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-amber-700 transition duration-200'
                onclick=\"return confirm('Voulez-vous vraiment supprimer ce bénévole ?');\">
                Supprimer le compte
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

