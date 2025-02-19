<?php
require_once 'securite.php';
include "config.php";
require_once "hash_password.php";
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter un Bénévole</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900" style="background: url('beach2.svg') no-repeat center center fixed; background-size: cover;">
    <div class="flex h-screen">
        <?php 
        require('menu.php');
        ?>
        <!-- Contenu principal -->
        <div class="flex-1 p-8 overflow-y-auto">
            <h1 class="text-4xl font-bold text-cyan-50 mb-6">Ajouter un bénévole</h1>
            <!-- formulaire -->
            <div class="bg-white p-6 rounded-lg shadow-lg max-w-lg mx-auto">
                <form action="user_add.php" method="POST">
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Nom</label>
                        <input type="text" name="nom" class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Nom du bénévole" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Email</label>
                        <input type="email" name="email" class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Email du bénévole" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Mot de passe</label>
                        <input type="password" name="mot_de_passe" class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500" placeholder="Mot de passe" required>
                    </div>
                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Rôle</label>
                        <select name="role" class="w-full mt-2 p-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500">
                            <option value="participant">Participant</option>
                            <option value="admin">Admin</option>
                        </select>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="w-full bg-cyan-500 hover:bg-cyan-600 text-white py-3 rounded-lg shadow-md font-semibold">Ajouter le bénévole</button>
                    </div>

                    <?php
                    //si requete serveur = POST : on récupère des données du formulaire
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $nom = $_POST['nom'];
                        $email = $_POST['email'];
                        $mot_de_passe_hash = hashPassword($_POST['mot_de_passe']);
                        $role = $_POST['role'];

                        try {
                            //on prépare la requête d'insertion dans la table 
                            $sql = "INSERT INTO benevoles (nom, email, mot_de_passe, role) 
                                    VALUES (:nom, :email, :mot_de_passe, :role)";
                            
                            $stmt = $pdo->prepare($sql);

                            //on lie un paramètre à un nom de variable spécifique
                            $stmt->bindParam(':nom', $nom);
                            $stmt->bindParam(':email', $email);
                            $stmt->bindParam(':mot_de_passe', $mot_de_passe_hash);
                            $stmt->bindParam(':role', $role);

                            //on execute de la requête
                            $stmt->execute();
                            echo "<p>Le bénévole a été ajouté avec succès.</p>";

                        } catch (PDOException $e) {
                            echo "<p>Erreur : " . $e->getMessage() . "</p>";
                        }
                    }
                    ?>
                </form>
            </div>
        </div>
    </div>
</body>
</html>


