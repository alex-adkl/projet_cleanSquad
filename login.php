<?php
session_start();
require 'config.php';


if ($_SERVER["REQUEST_METHOD"] === "POST") { //on vérifie si la requête est une soumission de formulaire en méthode POST
    $email = $_POST["email"];               //on récupère les données du formulaire
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM benevoles WHERE email = ?");  //on prépare une requête SQL
    $stmt->execute([$email]);                                          //on éxecute
    $user = $stmt->fetch();                                            //on récupère l'utilisateur

    // on vérifie si le mdp saisi(non hashé) correspond au mdp de la BDD (hashé) 
    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION["user_id"] = $user["id"];           //on crée les variables de session de l'utilisateur
        $_SESSION["nom"] = $user["nom"];
        $_SESSION["role"] = $user["role"];

        header("Location: index.php");
        exit;

    } else {
        $error = "Identifiants incorrects";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Connexion</title>
        <script src="https://cdn.tailwindcss.com"></script>
    </head>
    <body class="bg-gray-100 text-gray-900" style="background: url('beach2.svg') no-repeat center center fixed; background-size: cover;">
        <div class="flex justify-center items-center min-h-screen">
            <div class="bg-white p-8 rounded-lg shadow-lg w-full sm:w-96">
                <img src="logo.svg" style="display: block; margin: 0 auto;" alt="Logo Littoral Propre">
                <h1 class="text-3xl font-bold text-blue-800 mb-6 text-center">Connexion</h1>

                <?php if (!empty($error)) : ?>
                    <div class="text-red-600 text-center mb-4">
                        <?= htmlspecialchars($error) ?>
                    </div>
                <?php endif; ?>

                <form method="POST" class="space-y-6">
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input type="password" name="password" id="password" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                    </div>

                    <div class="flex justify-between items-center">
                        <a href="#" class="text-sm text-blue-600 hover:underline">Mot de passe oublié ?</a>
                        <button type="submit" class="bg-cyan-500 hover:bg-cyan-600 text-white px-6 py-2 rounded-lg shadow-md">
                            Se connecter
                        </button>
                    </div>
                </form>
            </div>    
        </div>
    </body>
</html>