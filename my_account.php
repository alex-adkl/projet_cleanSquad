<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres</title>
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
        <h1 class="text-4xl font-bold text-sky-700 mb-6">Mon compte</h1>

        <!-- Message de succès ou d'erreur -->
        <div class="text-green-600 text-center mb-4" id="success-message" style="display:none;">
            Vos paramètres ont été mis à jour avec succès.
        </div>
        <div class="text-red-600 text-center mb-4" id="error-message" style="display:none;">
            Le mot de passe actuel est incorrect.
        </div>

        <form id="settings-form" class="space-y-6">
            <!-- Champ Email -->
            <div>
                <label for="email" class="block text-base font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="exemple@domaine.com" required
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Champ Mot de passe actuel -->
            <div>
                <label for="current_password" class="block text-base font-medium text-gray-700">Mot de passe
                    actuel</label>
                <input type="password" name="current_password" id="current_password" required
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Champ Nouveau Mot de passe -->
            <div>
                <label for="new_password" class="block text-base font-medium text-gray-700">Nouveau mot de passe</label>
                <input type="password" name="new_password" id="new_password"
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Champ Confirmer le nouveau Mot de passe -->
            <div>
                <label for="confirm_password" class="block text-base font-medium text-gray-700">Confirmer le mot de
                    passe</label>
                <input type="password" name="confirm_password" id="confirm_password"
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Boutons -->
            <div class="flex justify-end space-x-4">
            <button href="collection_list.php" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-gray-700 transition duration-200">Annuler</button>
                <button type="button" onclick="updateSettings()"
                        class="bg-cyan-500 hover:bg-cyan-700 text-white px-4 py-2 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-cyan-700 transition duration-200">
                    Mettre à jour mes informations
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>

