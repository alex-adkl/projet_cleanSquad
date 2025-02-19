<?php
include 'securite.php';
include "config.php";
include "hash_password.php";


// on recupere les données de l'utilisateur 
if(isset($_SESSION['user_id']) && !empty($_SESSION['user_id'])){
    $user_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM benevoles WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch();
    $user_email = $user['email'];  
}

//on recupere les données du formulaire et on compare
function updateSettings($pdo, $user){
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = htmlspecialchars($_POST["email"]);
        $current_password = $_POST["current_password"];
        $new_password = $_POST["new_password"];
        $confirm_password= $_POST["confirm_password"];
//on compare le mdp non hashé avec le mdp hashé
    if(password_verify ($current_password, $user['mot_de_passe'])){
        echo "mot de passe conforme";
            if($new_password == $confirm_password){
                try {
                    //on prépare la requête de mise à jour dans la table
                    $sql = "UPDATE benevoles
                    SET mot_de_passe = :new_password
                    WHERE email = :email";

                    $stmt = $pdo->prepare($sql);

                    //on lie un paramètre à un nom de variable spécifique
                    $stmt->bindParam(':new_password', password_hash($new_password, PASSWORD_DEFAULT));
                    $stmt->bindParam(':email', $email);
                    $stmt->execute();

                    // on redirige vers la liste des bénévoles après la maj
                    header("Location: volunteer_list.php");
                    exit; // après un header() on arrête l'exécution du script

                } catch (PDOException $e) {
                    echo "<p>Erreur : " . $e->getMessage() . "</p>";
                }
            }
        }
    }
}

updateSettings($pdo, $user);

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres</title>
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
        <h1 class="text-4xl font-bold text-cyan-50 mb-6">Mon compte</h1>

        <!-- Message de succès ou d'erreur -->
        <div class="text-gray-900 text-center mb-4" id="success-message" style="display:none;">Vos paramètres ont été mis à jour avec succès.</div>
        <div class="text-gray-900 text-center mb-4" id="error-message" style="display:none;">Le mot de passe actuel est incorrect.</div>

        <form id="settings-form" class="space-y-6" method="POST">
            <div class="bg-white p-6 rounded-lg shadow-lg">
                <!-- Champ Email -->
                <div>
                    <label for="email" class="block text-base font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email" value="<?php echo $user['email'] ?>" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <br>

                <!-- Champ Mot de passe actuel -->
                <div>
                    <label for="current_password" class="block text-base font-medium text-gray-700">Mot de passe actuel</label>
                    <input type="password" name="current_password" id="current_password" required class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <br>

                <!-- Champ Nouveau Mot de passe -->
                <div>
                    <label for="new_password" class="block text-base font-medium text-gray-700">Nouveau mot de passe</label>
                    <input type="password" name="new_password" id="new_password" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <br>

                <!-- Champ Confirmer le nouveau Mot de passe -->
                <div>
                    <label for="confirm_password" class="block text-base font-medium text-gray-700">Confirmer le mot de passe</label>
                    <input type="password" name="confirm_password" id="confirm_password" class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>
                <br>

                <!-- Boutons -->
                <div class="flex justify-end space-x-4">
                    <button href="index.php" class="bg-gray-500 hover:bg-gray-700 text-white px-4 py-2 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-gray-700 transition duration-200">Annuler</button>         
                    <button type="submit" class="bg-cyan-500 hover:bg-cyan-700 text-white px-4 py-2 rounded-xl shadow focus:outline-none focus:ring-2 focus:ring-cyan-700 transition duration-200">Mettre à jour mes informations</button>
                </div>
            </div>        
        </form>
    </div>
</body>
</html>

