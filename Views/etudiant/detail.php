<?php
session_start();
require_once(__DIR__ . '/../../Classes/Database.php');
require_once(__DIR__ . '/../../Classes/Cours.php');


$database = new Database();
$db = $database->getConnection();


$course_id = $_GET['id'] ?? null;

if (!$course_id) {
    header("Location: cours.php");
    exit();
}


$cours = new Cours($db);


$course = $cours->getOne($course_id);

if (!$course) {
    header("Location: cours.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNeon Academy - Détails du Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto flex justify-between items-center p-4">
            <div class="flex items-center">
                <a href="index.html" class="text-2xl font-bold text-cyan-600 hover:text-cyan-700">TechNeon Academy</a>
            </div>
            <div class="flex items-center">
                <a href="#" class="flex items-center text-gray-600 hover:text-cyan-600">
                    <img src="/api/placeholder/32/32" alt="Profile" class="w-8 h-8 rounded-full mr-2">
                    <span>Marie Dupont</span>
                </a>
                <a href="index.html" class="text-gray-600 hover:text-cyan-600">Déconnexion</a>
            </div>
        </nav>
    </header>

    <main class="flex-grow container mx-auto p-4">
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6 text-gray-800"><?php echo htmlspecialchars($course['title']); ?></h2>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <p class="text-gray-600 mb-4">
                    <span class="font-semibold">Catégorie :</span> 
                    <?php echo htmlspecialchars($course['category_name'] ?? 'Non spécifiée'); ?>
                </p>
                <p class="text-gray-600 mb-4">
                    <span class="font-semibold">Professeur :</span> 
                    <?php echo htmlspecialchars($course['teacher_name'] ?? 'Inconnu'); ?>
                </p>
                <p class="text-gray-600 mb-4">
                    <span class="font-semibold">Description :</span> 
                    <?php echo htmlspecialchars($course['description']); ?>
                </p>

                <!-- Iframe pour la vidéo ou le document -->
                <div class="mb-4">
                    <h3 class="font-semibold text-gray-800 mb-2">Contenu du cours :</h3>
                    <?php if (filter_var($course['content'], FILTER_VALIDATE_URL)): ?>
                        <!-- Si le contenu est une URL (vidéo YouTube, Vimeo, etc.) -->
                        <iframe src="<?php echo htmlspecialchars($course['content']); ?>" width="100%" height="400" style="border: none;"></iframe>
                    <?php else: ?>
                        <!-- Si le contenu est un document (PDF, etc.) -->
                        <iframe src="<?php echo htmlspecialchars($course['content']); ?>" width="100%" height="400" style="border: none;"></iframe>
                    <?php endif; ?>
                </div>

                <!-- Bouton pour retourner au catalogue -->
                <a href="cours.php" class="block text-center px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                    Retour au catalogue
                </a>
            </div>
        </section>
    </main>

    <footer class="bg-white border-t border-gray-200">
        <div class="container mx-auto py-8 px-4 text-center">
            <p class="text-gray-600">&copy; 2025 TechNeon Academy. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>