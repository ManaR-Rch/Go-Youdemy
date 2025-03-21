<?php
session_start();
require_once(__DIR__ . '/../../Classes/Database.php');
require_once(__DIR__ . '/../../Classes/Cours.php');
require_once(__DIR__ . '/../../Classes/Enrollement.php');


$database = new Database();
$db = $database->getConnection();


$cours = new Cours($db);
$enrollment = new Enrollment($db);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['course_id'])) {
   
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['error'] = "Vous devez être connecté pour vous inscrire à un cours.";
        header("Location: login.php");
        exit();
    }


    $course_id = $_POST['course_id'];

 
    $student_id = $_SESSION['user_id'];


    $enrollment->setStudentId($student_id);
    $enrollment->setCourseId($course_id);

    if ($enrollment->attacherAuCour()) {
        $_SESSION['success'] = "Inscription réussie ";
    } else {
        $_SESSION['error'] = "Erreur lors de l'inscription. Vous êtes peut-être déjà inscrit à ce cours.";
    }


    header("Location: cours.php");
    exit();
}


$courses = $cours->getAll();


$student_id = $_SESSION['user_id'] ?? null;
$my_courses = [];

if ($student_id) {
    $enrollments = $enrollment->getEnrollmentsByStudent($student_id);
    foreach ($enrollments as $enrollment) {
        $course = $cours->getOne($enrollment['course_id']);
        if ($course) {
            $course['category_name'] = $enrollment['category_name'];
            $course['teacher_name'] = $enrollment['teacher_name'];
            $my_courses[] = $course;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNeon Academy - Mes Cours</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto flex justify-between items-center p-4">
            <div class="flex items-center">
                <a href="index.html" class="text-2xl font-bold text-cyan-600 hover:text-cyan-700">TechNeon Academy</a>
                <div class="ml-8 hidden md:flex">

                </div>
            </div>
            
            <div class="flex items-center">
                <div class="relative mx-4 hidden md:block">
                    <input type="text" placeholder="Rechercher un cours..." 
                           class="w-64 p-2 bg-gray-100 text-gray-800 rounded-full border border-gray-200 focus:outline-none focus:border-cyan-500 transition-colors">
                </div>
                <div class="flex items-center space-x-4">
                    <a href="./../Auth/logout.php" class="text-gray-600 hover:text-cyan-600">Déconnexion</a>
                </div>
            </div>
        </nav>
    </header>

    <main class="flex-grow container mx-auto p-4">
        <!-- Afficher les messages de succès ou d'erreur -->
        <?php
        if (isset($_SESSION['success'])) {
            echo "<div class='bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4'>" . $_SESSION['success'] . "</div>";
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['error'])) {
            echo "<div class='bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4'>" . $_SESSION['error'] . "</div>";
            unset($_SESSION['error']);
        }
        ?>

        <!-- Section Mes Cours -->
        <section class="mb-12">
            <h2 class="text-2xl font-bold mb-6 text-gray-800">Mes Cours</h2>
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <?php foreach ($my_courses as $course): ?>
                    <div class="bg-white rounded-lg overflow-hidden shadow-sm p-6">
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
                        <a href="detail.php?id=<?php echo $course['course_id']; ?>" class="block text-center px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                            Détails du cours
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>

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
                                <a href="detail.php?id=<?php echo $course['course_id']; ?>" class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 transition-colors text-center">
                                    Voir les détails
                                </a>
                                <form method="POST" action="cours.php" class="inline">
                                    <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                                    <button type="submit" class="flex-1 px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors text-center">
                                        S'inscrire
                                    </button>
                                </form>
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