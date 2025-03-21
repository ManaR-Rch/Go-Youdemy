<?php
session_start();
require_once(__DIR__ . '/../../Classes/Database.php');
require_once(__DIR__ . '/../../Classes/Cours.php');

$database = new Database();
$db = $database->getConnection();
$cours = new Cours($db);
$courses = $cours->getAll();

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNeon Academy - Catalogue des Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto flex justify-between items-center p-4">
            <div class="flex items-center">
                <a href="visiteur.php" class="text-2xl font-bold text-cyan-600 hover:text-cyan-700">TechNeon Academy</a>
            </div>
            
            <div class="flex items-center">
                <!-- Barre de recherche -->
                <form method="GET" action="visiteur.php" class="relative mx-4 hidden md:block">
                    <input type="text" name="search" placeholder="Rechercher un cours..." 
                           class="w-64 p-2 bg-gray-100 text-gray-800 rounded-full border border-gray-200 focus:outline-none focus:border-cyan-500 transition-colors"
                           value="">
                    <button type="submit" class="absolute right-2 top-2 text-gray-600 hover:text-cyan-600">
                        🔍
                    </button>
                </form>

                <!-- Bouton Sign Up -->
                <a href="./../Auth/sign-up.php" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                    Sign Up
                </a>
            </div>
        </nav>
    </header>

    <main class="flex-grow container mx-auto p-4">
        <!-- Section Catalogue -->
        <section class="mb-12">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Catalogue des Cours</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                <?php foreach ($courses as $course): ?>
                    <div class="bg-white rounded-lg overflow-hidden shadow-sm hover:shadow-md transform transition-all hover:-translate-y-1 p-6">
                        <h3 class="font-bold mb-2 text-gray-800"><?php echo htmlspecialchars($course['title']); ?></h3>
                        <p class="text-gray-600 text-sm mb-2">
                            <span class="font-semibold">Catégorie :</span> 
                            <?php echo htmlspecialchars($course['category_name'] ?? 'Non spécifiée'); ?>
                        </p>
                        <p class="text-gray-600 text-sm mb-2">
                            <span class="font-semibold">Professeur :</span> 
                            <?php echo htmlspecialchars($course['teacher_name'] ?? 'Inconnu'); ?>
                        </p>
                        <p class="text-gray-600 text-sm mb-4">
                            <span class="font-semibold">Description :</span> 
                            <?php echo htmlspecialchars($course['description']); ?>
                        </p>
                        <div class="space-y-3">
                            <div class="flex justify-between gap-2">
                                <a href="./../etudiant/detail.php?id=<?php echo $course['course_id']; ?>" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-center">
                                    Voir les détails
                                </a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
    </main>

    <footer class="bg-white border-t border-gray-200">
        <div class="container mx-auto py-8 px-4">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
                <div>
                    <h3 class="font-bold mb-4 text-gray-800">À Propos</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-cyan-600 transition-colors">Qui sommes-nous</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-cyan-600 transition-colors">Carrières</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-cyan-600 transition-colors">Blog</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-4 text-gray-800">Support</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-cyan-600 transition-colors">Aide</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-cyan-600 transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-bold mb-4 text-gray-800">Légal</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-600 hover:text-cyan-600 transition-colors">Conditions d'utilisation</a></li>
                        <li><a href="#" class="text-gray-600 hover:text-cyan-600 transition-colors">Politique de confidentialité</a></li>
                    </ul>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-gray-200 text-center">
                <p class="text-gray-600">&copy; 2025 TechNeon Academy. Tous droits réservés.</p>
            </div>
        </div>
    </footer>
</body>
</html>