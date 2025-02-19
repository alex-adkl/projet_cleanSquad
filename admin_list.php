<?php
require 'securite.php';
require 'config.php'
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Bénévoles</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900" style="background: url('beach2.svg') no-repeat center center fixed; background-size: cover;">
    <div class="flex h-screen">
        <?php 
        require('menu.php');
        ?>
        <!-- Contenu principal -->
        <div class="flex-1 p-8 overflow-y-auto">
            <!-- Titre -->
            <h1 class="text-4xl font-bold text-cyan-50 mb-6">Liste des Bénévoles</h1>
            <!-- Tableau des admin -->
            <div class="overflow-hidden rounded-lg shadow-lg bg-white">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-sky-700 text-white">
                    <tr>
                        <th class="py-3 px-4 text-left">Nom</th>
                        <th class="py-3 px-4 text-left">Email</th>
                        <th class="py-3 px-4 text-left">Rôle</th>
                        <th class="py-3 px-4 text-left">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                    <tr class="hover:bg-gray-100 transition duration-200">
                        <td class="py-3 px-4">Nom de l'admin</td>
                        <td class="py-3 px-4">email@example.com</td>
                        <td class="py-3 px-4">Admin</td>
                        <td class="py-3 px-4 flex space-x-2">
                            <a href="#"
                                class="bg-cyan-500 hover:bg-cyan-700 text-white px-4 py-2 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-cyan-700 transition duration-200">
                                Modifier le compte
                            </a>
                            <a href="#"
                                class="bg-amber-500 hover:bg-amber-700 text-white px-4 py-2 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-amber-700 transition duration-200">
                                Supprimer le compte
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>