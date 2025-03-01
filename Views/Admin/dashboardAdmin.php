<?php
require_once(__DIR__ . '/../../Classes/Database.php');


$db = new Database();


$totalCourses = $db->getTotalCourses();
$totalCategories = $db->getTotalCategories();
$mostPopularCourse = $db->getMostPopularCourse();
$topTeachers = $db->getTopTeachers();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNeon Academy - Statistiques</title>
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
                            👥 Utilsateurs
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
                <h1 class="text-2xl font-bold text-gray-800">Statistiques Globales</h1>
                <!-- Bouton de déconnexion -->
                <a href="./../Auth/logout.php" class="px-4 py-2 bg-red-500 text-white rounded-lg hover:bg-red-600 transition-colors">Déconnexion</a>
            </header>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="text-gray-500">Nombre Total de Cours</div>
                    <div class="text-3xl font-bold mt-2"><?php echo $totalCourses; ?></div>
                </div>
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="text-gray-500">Répartition par Catégorie</div>
                    <div class="text-3xl font-bold mt-2"><?php echo $totalCategories; ?> catégories</div>
                </div>
                <!-- Cours avec le plus d'étudiants -->
                <div class="bg-white p-6 rounded-lg shadow-sm">
                    <div class="text-gray-500">Cours avec le plus d'étudiants</div>
                    <div class="text-3xl font-bold mt-2">
                        <?php
                        if ($mostPopularCourse) {
                            echo htmlspecialchars($mostPopularCourse['title']);
                        } else {
                            echo "Aucun cours disponible";
                        }
                        ?>
                    </div>
                </div>
            </div>

            <!-- Top 3 Enseignants -->
            <div class="bg-white rounded-lg shadow-sm p-6">
                <h2 class="text-xl font-bold text-gray-800 mb-4">Top 3 Enseignants</h2>
                <div class="space-y-4">
                    <?php foreach ($topTeachers as $teacher): ?>
                        <div class="flex items-center justify-between">
                                    <div class="font-medium"><?php echo $teacher['teacher_name']; ?></div>
                                    <div class="text-sm text-gray-500"><?php echo $teacher['student_count']; ?> étudiants</div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </main>
    </div>
</body>
</html>