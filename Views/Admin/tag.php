<?php
require_once(__DIR__ . '/../../Classes/Database.php');
require_once(__DIR__ . '/../../Classes/Tag.php');


$database = new Database();
$db = $database->getConnection();


$tag = new Tag($db, null, null);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_tag'])) {
    $name = $_POST['name'];

    try {
        $tag->setTitre($name); 
        if ($tag->create()) {
           
        }
    } catch (Exception $e) {
        
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_tag'])) {
    $tag_id = $_POST['tag_id'];

    try {
        $tag->setId($tag_id);
        if ($tag->delete()) {
            
        }
    } catch (Exception $e) {
        
    }
}


$tags = $tag->getAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNeon Academy - Gestion des Tags</title>
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
                <h1 class="text-2xl font-bold text-gray-800">Gestion des Tags</h1>
                <div class="flex items-center space-x-4">
                    <input type="text" placeholder="Rechercher un tag..." 
                           class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
            </header>

            <!-- Formulaire de création de tag -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Créer un nouveau Tag</h2>
                <form method="POST" action="">
                    <div class="mb-4">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom du Tag</label>
                        <input type="text" id="name" name="name" 
                               class="mt-1 block w-full p-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500" 
                               placeholder="Entrez le nom du tag" required>
                    </div>
                    <button type="submit" name="create_tag" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700">
                        Créer le Tag
                    </button>
                </form>
            </div>

            <!-- Liste des Tags -->
            <div class="bg-white rounded-lg shadow-sm">
                <h2 class="text-xl font-bold text-gray-800 p-6">Liste des Tags</h2>
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
                            <?php if (!empty($tags)) : ?>
                                <?php foreach ($tags as $tag) : ?>
                                    <tr>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($tag['tag_id']); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($tag['name']); ?></td>
                                        <td class="px-6 py-4">
                                            <form method="POST" action="" class="inline">
                                                <input type="hidden" name="tag_id" value="<?php echo $tag['tag_id']; ?>">
                                                <button type="submit" name="delete_tag" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="3" class="px-6 py-4 text-center text-gray-500">Aucun tag trouvé.</td>
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