<div class="bg-cyan-500 text-white w-64 p-6">
    <img src="logo.svg">
    <h2 class="text-2xl font-bold mb-6"></h2>
    
    <a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-cyan-700 rounded-xl">
        <i class="fas fa-tachometer-alt mr-3"></i> Tableau de bord
    </a>
    <br>
    
    <!-- Ne pas afficher "Ajouter une collecte" si on est sur la page collection_add.php -->
    <?php if (basename($_SERVER['PHP_SELF']) !== 'collection_add.php'): ?>
        <a href="collection_add.php" class="flex items-center py-2 px-3 hover:bg-cyan-700 rounded-xl">
            <i class="fas fa-plus-circle mr-3"></i> Ajouter une collecte
        </a>
        <br>
    <?php endif; ?>

    <!-- Ne pas afficher "Ajouter un bénévole" si on est sur la page user_add.php -->
    <?php if (basename($_SERVER['PHP_SELF']) !== 'user_add.php'): ?>
        <a href="user_add.php" class="flex items-center py-2 px-3 hover:bg-cyan-700 rounded-xl">
            <i class="fa-solid fa-user-plus mr-3"></i> Ajouter un bénévole
        </a>
        <br>
    <?php endif; ?>

    <!-- Lien pour "Liste des bénévoles" : il s'affiche tout le temps -->
    <a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-cyan-700 rounded-xl">
        <i class="fa-solid fa-list mr-3"></i> Liste des bénévoles
    </a>
    <br>

    <!-- Lien pour "Mon compte" : il s'affiche tout le temps -->
    <a href="my_account.php" class="flex items-center py-2 px-3 hover:bg-cyan-700 rounded-xl">
        <i class="fas fa-cogs mr-3"></i> Mon compte
    </a>
    <br>

    <div class="mt-6">
        <button onclick="logout()" class="w-full bg-amber-500 hover:bg-amber-700 text-white py-2 rounded-xl shadow-md">
        <a href="logout.php" > Déconnexion</a> 
        </button>
    </div>
</div>