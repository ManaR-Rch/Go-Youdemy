<?php
require_once(__DIR__ . '/../../Classes/Database.php');
require_once(__DIR__ . '/../../Classes/Cours.php');


$database = new Database();
$db = $database->getConnection();


$cours = new Cours($db, null, null, null, null);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course'])) {
    $course_id = $_POST['course_id'];

    try {
        $cours->setId($course_id); 
        if ($cours->delete()) {
            echo "<p>Cours supprimé avec succès !</p>";
        }
    } catch (Exception $e) {
        echo "<p>Erreur : " . $e->getMessage() . "</p>";
    }
}


$courses = $cours->getAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNeon Academy - Gestion des Cours</title>
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
                <h1 class="text-2xl font-bold text-gray-800">Gestion des Cours</h1>
                <div class="flex items-center space-x-4">
                    <input type="text" placeholder="Rechercher un cours..." 
                           class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
                    <button class="px-4 py-2 bg-cyan-600 text-white rounded-lg">Ajouter un Cours</button>
                </div>
            </header>

            <!-- Liste des Cours -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Description</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enseignant</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (!empty($courses)) : ?>
                                <?php foreach ($courses as $course) : ?>
                                    <tr>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($course['title']); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($course['description']); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($course['teacher_name']); ?></td>
                                        <td class="px-6 py-4"><?php echo htmlspecialchars($course['category_name']); ?></td>
                                        <td class="px-6 py-4">
                                            <form method="POST" action="" class="inline">
                                                <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                                                <button type="submit" name="delete_course" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                    Supprimer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">Aucun cours trouvé.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 flex items-center justify-between border-t">
                    <div class="text-sm text-gray-500">
                        Affichage de 1-10 sur <?php echo count($courses); ?> cours
                    </div>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 border rounded">Précédent</button>
                        <button class="px-3 py-1 bg-cyan-600 text-white rounded">1</button>
                        <button class="px-3 py-1 border rounded">2</button>
                        <button class="px-3 py-1 border rounded">Suivant</button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</body>
</html>