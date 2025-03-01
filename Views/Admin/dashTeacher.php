<?php
require_once(__DIR__ . '/../../Classes/Database.php');
require_once(__DIR__ . '/../../Classes/Enseignant.php');

// Connexion à la base de données
$database = new Database();
$db = $database->getConnection();


$enseignant = new Enseignant($db, null, null, null, null);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['validate_teacher'])) {
    $teacher_id = $_POST['teacher_id'];
    $estValide = $_POST['est_valide']; 

    try {
        $enseignant->setId($teacher_id);
        if ($enseignant->setEstValide($estValide)) {
       
        }
    } catch (Exception $e) {
       
    }
}


$query = "SELECT * FROM users WHERE role = 'teacher'";
$stmt = $db->prepare($query);
$stmt->execute();
$teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Techeon Academy - Gestion des Enseignants</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex bg-gray-50">
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
    
    <main class="ml-64 flex-grow p-8">
        <header class="flex justify-between items-center mb-8">
            <h1 class="text-2xl font-bold text-gray-800">Gestion des Enseignants</h1>
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" placeholder="Rechercher un enseignant..." 
                           class="w-64 p-2 bg-white text-gray-800 rounded-lg border focus:outline-none focus:border-cyan-500">
                </div>
            </div>
        </header>

        <!-- Teachers List -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Enseignant</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Spécialité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <?php if (!empty($teachers)) : ?>
                            <?php foreach ($teachers as $teacher) : ?>
                                <tr>
                                    <td class="px-6 py-4 flex items-center">
                                        <div>
                                            <div class="font-medium"><?php echo htmlspecialchars($teacher['username']); ?></div>
                                    </td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($teacher['email']); ?></td>
                                    <td class="px-6 py-4"><?php echo htmlspecialchars($teacher['specialite'] ?? 'N/A'); ?></td>
                                    <td class="px-6 py-4">
                                        <span class="px-2 py-1 <?php echo $teacher['est_valide'] ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800'; ?> rounded-full text-sm">
                                            <?php echo $teacher['est_valide'] ? 'Validé' : 'En attente'; ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex space-x-2">
                                            <form method="POST" action="" class="inline">
                                                <input type="hidden" name="teacher_id" value="<?php echo $teacher['user_id']; ?>">
                                                <input type="hidden" name="est_valide" value="1">
                                                <button type="submit" name="validate_teacher" class="px-3 py-1 bg-green-500 text-white rounded hover:bg-green-600">
                                                    Valider
                                                </button>
                                            </form>
                                            <form method="POST" action="" class="inline">
                                                <input type="hidden" name="teacher_id" value="<?php echo $teacher['user_id']; ?>">
                                                <input type="hidden" name="est_valide" value="0">
                                                <button type="submit" name="validate_teacher" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                    Refuser
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Aucun enseignant trouvé.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <!-- Pagination -->
            <div class="flex items-center justify-between p-4 border-t">
                <div class="text-gray-500 text-sm">
                    Affichage de 1-10 sur <?php echo count($teachers); ?> enseignants
                </div>
                <div class="flex space-x-2">
                    <button class="px-3 py-1 border rounded hover:bg-gray-100">Précédent</button>
                    <button class="px-3 py-1 bg-cyan-600 text-white rounded hover:bg-cyan-700">1</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-100">2</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-100">3</button>
                    <button class="px-3 py-1 border rounded hover:bg-gray-100">Suivant</button>
                </div>
            </div>
        </div>
    </main>
</body>
</html>