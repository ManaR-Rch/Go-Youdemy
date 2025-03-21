<?php
session_start();

require_once(__DIR__ . '/../../Classes/Database.php');
require_once(__DIR__ . '/../../Classes/Cours.php');
require_once(__DIR__ . '/../../Classes/CoursTag.php');
require_once(__DIR__ . '/../../Classes/Enrollement.php');


$database = new Database();
$db = $database->getConnection();


if (!isset($_SESSION['user_id'])) {
    header("Location: ./../Auth/sign-in.php");
    exit();
}

$teacher_id = $_SESSION['user_id'];


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_course'])) {
    $title = $_POST['title'];
    $description = $_POST['description'];
    $content = $_POST['content']; 
    $category_id = $_POST['category_id'];
    $tags = $_POST['tags'];

   
    $cours = new Cours($db, null, $title, $description, $content, $teacher_id, $category_id);
    if ($cours->create()) {
        $course_id = $db->lastInsertId();

       
        foreach ($tags as $tag_id) {
            $coursTag = new CoursTag($db, $course_id, $tag_id);
            $coursTag->attacherTag();
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_course'])) {
    $course_id = $_POST['course_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $content = $_POST['content'];
    $category_id = $_POST['category_id'];

    
    $cours = new Cours($db, $course_id, $title, $description, $content, $_SESSION['user_id'], $category_id);
    $cours->update();
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_course'])) {
    $course_id = $_POST['course_id'];
    $cours = new Cours($db, $course_id);
    $cours->delete();
}

// Récupérer tous les cours de l'enseignant
$query = "SELECT * FROM courses WHERE teacher_id = :teacher_id";
$stmt = $db->prepare($query);
$stmt->execute([':teacher_id' => $teacher_id]);
$courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer toutes les catégories pour le formulaire d'ajout
$query = "SELECT * FROM categories";
$stmt = $db->prepare($query);
$stmt->execute();
$categories = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer tous les tags pour le formulaire d'ajout
$query = "SELECT * FROM tags";
$stmt = $db->prepare($query);
$stmt->execute();
$tags = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer le nombre total d'étudiants inscrits aux cours de l'enseignant
$query = "SELECT COUNT(DISTINCT student_id) AS total_students 
          FROM enrollments 
          WHERE course_id IN (SELECT course_id FROM courses WHERE teacher_id = :teacher_id)";
$stmt = $db->prepare($query);
$stmt->execute([':teacher_id' => $teacher_id]);
$total_students = $stmt->fetchColumn();

// Récupérer tous les cours de l'enseignant avec le nom de la catégorie et le nombre d'inscriptions
$cours = new Cours($db);
$courses = $cours->getCoursesWithCategory($teacher_id);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNeon Academy - Espace Enseignant</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto flex justify-between items-center p-4">
            <div class="flex items-center">
                <a href="index.html" class="text-2xl font-bold text-cyan-600 hover:text-cyan-700">TechNeon Academy</a>
                <span class="ml-4 text-gray-500">| Espace Enseignant</span>
            </div>
            <div class="flex items-center">
                <a href="./../Auth/logout.php" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-full hover:bg-gray-300 transition-colors">Déconnexion</a>
            </div>
        </nav>
    </header>

    <main class="flex-grow container mx-auto p-4">
        <!-- Dashboard Stats -->
        <section class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-gray-500 mb-2">Total Étudiants</h3>
                <p class="text-3xl font-bold text-gray-800"><?php echo $total_students; ?></p>
            </div>
            <div class="bg-white p-6 rounded-lg shadow-sm">
                <h3 class="text-gray-500 mb-2">Cours Actifs</h3>
                <p class="text-3xl font-bold text-gray-800"><?php echo count($courses); ?></p>
            </div>
        </section>

        <!-- Liste des Cours -->
        <section class="mb-8">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold text-gray-800">Mes Cours</h2>
            </div>

            <!-- Affichage des Cours -->
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Titre</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Catégorie</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Contenu</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Inscriptions</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php foreach ($courses as $course): ?>
                                <tr>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($course['title']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($course['category_name']); ?></td>
                                    <td class="px-6 py-4">
                                        <?php
                                        // Vérifier si le contenu est une URL
                                        if (filter_var($course['content'], FILTER_VALIDATE_URL)) {
                                            // Afficher le contenu dans une iframe
                                            echo "<iframe src='{$course['content']}' width='100%' height='300' style='border: none;'></iframe>";
                                        } else {
                                            // Afficher le contenu texte brut
                                            echo htmlspecialchars($course['content']);
                                        }
                                        ?>
                                    </td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($course['student_count']); ?> étudiants</td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-sm">Publié</span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <!-- Bouton pour ouvrir le modal de modification -->
                                            <button onclick="openEditModal(
                                                '<?php echo $course['course_id']; ?>',
                                                '<?php echo htmlspecialchars($course['title'], ENT_QUOTES); ?>',
                                                '<?php echo htmlspecialchars($course['description'], ENT_QUOTES); ?>',
                                                '<?php echo htmlspecialchars($course['content'], ENT_QUOTES); ?>',
                                                '<?php echo $course['category_id']; ?>'
                                            )" class="text-cyan-600 hover:text-cyan-800">
                                                Modifier
                                            </button>
                                            <br>
                                            <form method="POST" action="" class="inline">
                                                <input type="hidden" name="course_id" value="<?php echo $course['course_id']; ?>">
                                                <button type="submit" name="delete_course" class="text-red-600 hover:text-red-800">Supprimer</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Formulaire d'ajout de cours -->
        <section class="bg-white rounded-lg shadow-sm p-6 mb-8">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Ajouter un Nouveau Cours</h2>
            <form method="POST" action="">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-gray-700 mb-2">Titre du Cours</label>
                        <input type="text" name="title" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Catégorie</label>
                        <select name="category_id" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">Description</label>
                        <textarea name="description" rows="4" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required></textarea>
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-gray-700 mb-2">Lien du Contenu (Vidéo ou Document)</label>
                        <input type="text" name="content" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required>
                        <p class="text-sm text-gray-500 mt-1">Insérez un lien YouTube, Vimeo, ou un lien direct vers un document (PDF).</p>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Tags</label>
                        <select name="tags[]" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" multiple required>
                            <?php foreach ($tags as $tag): ?>
                                <option value="<?php echo $tag['tag_id']; ?>"><?php echo htmlspecialchars($tag['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Annuler</button>
                    <button type="submit" name="add_course" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">Sauvegarder le Cours</button>
                </div>
            </form>
        </section>
    </main>

    <!-- Modal de modification -->
    <div id="editModal" class="fixed inset-0 bg-black bg-opacity-50 hidden justify-center items-center">
        <div class="bg-white rounded-lg shadow-lg p-6 w-full max-w-md">
            <h2 class="text-2xl font-bold text-gray-800 mb-6">Modifier le Cours</h2>
            <form id="editCourseForm" method="POST" action="">
                <input type="hidden" name="course_id" id="editCourseId">
                <div class="space-y-4">
                    <div>
                        <label class="block text-gray-700 mb-2">Titre du Cours</label>
                        <input type="text" name="title" id="editTitle" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Description</label>
                        <textarea name="description" id="editDescription" rows="4" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required></textarea>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Lien du Contenu (Vidéo ou Document)</label>
                        <input type="text" name="content" id="editContent" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required>
                    </div>
                    <div>
                        <label class="block text-gray-700 mb-2">Catégorie</label>
                        <select name="category_id" id="editCategoryId" class="w-full p-2 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?php echo $category['category_id']; ?>"><?php echo htmlspecialchars($category['name']); ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-4">
                    <button type="button" onclick="closeEditModal()" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">Annuler</button>
                    <button type="submit" name="update_course" class="px-4 py-2 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">Enregistrer</button>
                </div>
            </form>
        </div>
    </div>

    <footer class="bg-white border-t border-gray-200">
        <div class="container mx-auto py-4 px-4 text-center">
            <p class="text-gray-600">&copy; 2025 TechNeon Academy. Tous droits réservés.</p>
        </div>
    </footer>

    <script>
    // Fonction pour ouvrir le modal et remplir les champs
    function openEditModal(courseId, title, description, content, categoryId) {
        document.getElementById('editCourseId').value = courseId;
        document.getElementById('editTitle').value = title;
        document.getElementById('editDescription').value = description;
        document.getElementById('editContent').value = content;
        document.getElementById('editCategoryId').value = categoryId;
        document.getElementById('editModal').classList.remove('hidden');
    }

    // Fonction pour fermer le modal
    function closeEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }

    // Fermer le modal si l'utilisateur clique en dehors
    window.onclick = function(event) {
        const modal = document.getElementById('editModal');
        if (event.target === modal) {
            closeEditModal();
        }
    };
    </script>
</body>
</html>