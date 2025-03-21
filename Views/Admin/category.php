<?php
require_once(__DIR__ . '/../../Classes/Database.php');
require_once(__DIR__ . '/../../Classes/Category.php');


$database = new Database();
$db = $database->getConnection();


$category = new Category($db, null, null, null);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_category'])) {
    $name = $_POST['name'];

    try {
        $category->setNom($name); 
        if ($category->create()) {
           
        }
    } catch (InvalidArgumentException $e) {
        
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $category_id = $_POST['category_id'];

    try {
        $category->setId($category_id);
        if ($category->delete()) {
           
        }
    } catch (InvalidArgumentException $e) {
        
    }
}

// Récupérer toutes les catégories pour les afficher
$categories = $category->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNeon Academy - Gestion des Catégories</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50">
    <div class="flex min-h-screen">
        <!-- Sidebar -->
        <aside class="w-64 bg-white shadow-sm fixed h-full">
            <div class="px-6 py-4 border-b">
                <h2 class="text-2xl font-bold text-cyan-600">TechNeon Admin</h2>
            </div>
            <nav class="mt-6">
                <ul class="space-y-2 px-4">
                    <li>
                        <a href="dashboardAdmin.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">
                            📊 Dashboard
                        </a>
                    </li>
                    <li>
                        <a href="dashTeacher.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">
                            👩‍🏫 Enseignants
                        </a>
                    </li>
                    <li>
                        <a href="dashUser.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">
                            👥 Utilisateurs
                        </a>
                    </li>
                    <li>
                        <a href="category.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">
                            📂 Catégories
                        </a>
                    </li>
                    <li>
                        <a href="tag.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">
                            🏷️ Tags
                        </a>
                    </li>
                    <li>
                        <a href="courses.php" class="flex items-center px-4 py-3 text-gray-700 hover:bg-gray-50 rounded-lg">
                            📚 Cours
                        </a>
                    </li>
                </ul>
            </nav>
        </aside>

        <!-- Main Content -->
        <main class="ml-64 flex-1 p-8">
            <header class="flex justify-between items-center mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Gestion des Catégories</h1>
                <div class="flex items-center space-x-4">
                    <input type="text" placeholder="Rechercher une catégorie..." 
                           class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
            </header>

            <!-- Formulaire de création de catégorie -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Créer une nouvelle Catégorie</h2>
                <form method="POST" action="">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom de la Catégorie</label>
                        <input type="text" id="name" name="name" 
                               class="mt-1 block w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500" 
                               placeholder="Entrez le nom de la catégorie" required>
                    </div>
                    <button type="submit" name="create_category" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">
                        Créer la Catégorie
                    </button>
                </form>
            </div>

            <!-- Liste des catégories -->
            <div class="bg-white rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 p-6">Liste des Catégories</h2>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (!empty($categories)) : ?>
                                <?php foreach ($categories as $cat) : ?>
                                    <tr>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($cat['category_id']); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($cat['name']); ?></td>
                                        <td class="px-6 py-4">
                                            <form method="POST" action="" class="inline">
                                                <input type="hidden" name="category_id" value="<?php echo $cat['category_id']; ?>">
                                                <button type="submit" name="delete_category" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Aucune catégorie trouvée.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </main>
    </div>
</body>
</html>