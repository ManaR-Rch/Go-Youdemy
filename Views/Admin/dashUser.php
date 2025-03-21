<?php
require_once(__DIR__ . '/../../Classes/Database.php');
require_once(__DIR__ . '/../../Classes/Etudiant.php');


$database = new Database();
$db = $database->getConnection();


$etudiant = new Etudiant($db, null, null, null, null, null); 


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['suspend_user'])) {
    $user_id = $_POST['user_id'];
    $suspended = $_POST['suspended']; 

    try {
        $etudiant->setId($user_id);
        $etudiant->setSuspended($suspended);
        if ($etudiant->updateProfile()) {
           
        }
    } catch (Exception $e) {
       
    }
}


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];

    try {
        $etudiant->setId($user_id);
        if ($etudiant->delete()) {
           
        }
    } catch (Exception $e) {
      
    }
}


$users = $etudiant->getUsers(); 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TechNeon Academy - Gestion des Utilisateurs</title>
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
                <h1 class="text-2xl font-bold text-gray-800">Gestion des Utilisateurs</h1>
                <div class="flex items-center space-x-4">
                    <input type="text" placeholder="Rechercher un utilisateur..." 
                           class="px-4 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-cyan-500">
                </div>
            </header>

            <!-- Filtres -->
            <div class="mb-6 flex space-x-4">
                <button class="px-4 py-2 bg-cyan-600 text-white rounded-lg">Tous (<?php echo count($users); ?>)</button>
                <button class="px-4 py-2 border text-gray-600 rounded-lg hover:bg-gray-50">Actifs (<?php echo count(array_filter($users, function($user) { return !$user['suspended']; })); ?>)</button>
                <button class="px-4 py-2 border text-gray-600 rounded-lg hover:bg-gray-50">Suspendus (<?php echo count(array_filter($users, function($user) { return $user['suspended']; })); ?>)</button>
            </div>

            <!-- Users List -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Utilisateur</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Date d'inscription</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Cours suivis</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            <?php if (!empty($users)) : ?>
                                <?php foreach ($users as $user) : ?>
                                    <tr>
                                        <td class="px-6 py-4">
                                            <div class="flex items-center">
                                                <div class="ml-4">
                                                    <div class="text-sm font-medium text-gray-900"><?php echo htmlspecialchars($user['username']); ?></div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($user['created_at'] ?? 'N/A'); ?></td>
                                        <td class="px-6 py-4 text-sm text-gray-500"><?php echo htmlspecialchars($user['courses_followed'] ?? '0'); ?> cours</td>
                                        <td class="px-6 py-4">
                                            <span class="px-2 py-1 <?php echo $user['suspended'] ? 'bg-yellow-100 text-yellow-800' : 'bg-green-100 text-green-800'; ?> rounded-full text-sm">
                                                <?php echo $user['suspended'] ? 'Suspendu' : 'Actif'; ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4">
                                            <div class="flex space-x-2">
                                                <form method="POST" action="" class="inline">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                                    <input type="hidden" name="suspended" value="<?php echo $user['suspended'] ? '0' : '1'; ?>">
                                                    <button type="submit" name="suspend_user" class="px-3 py-1 <?php echo $user['suspended'] ? 'bg-green-500' : 'bg-yellow-500'; ?> text-white rounded hover:<?php echo $user['suspended'] ? 'bg-green-600' : 'bg-yellow-600'; ?>">
                                                        <?php echo $user['suspended'] ? 'Réactiver' : 'Suspendre'; ?>
                                                    </button>
                                                </form>
                                                <form method="POST" action="" class="inline">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['user_id']; ?>">
                                                    <button type="submit" name="delete_user" class="px-3 py-1 bg-red-500 text-white rounded hover:bg-red-600">
                                                        Supprimer
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else : ?>
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">Aucun utilisateur trouvé.</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="px-6 py-4 flex items-center justify-between border-t">
                    <div class="text-sm text-gray-500">
                        Affichage de 1-10 sur <?php echo count($users); ?> utilisateurs
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