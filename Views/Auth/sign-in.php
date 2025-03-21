<?php
session_start(); // Démarrer la session

require_once(__DIR__ . '/../../Classes/Database.php');
require_once(__DIR__ . '/../../Classes/Etudiant.php');

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $etudiant = new Etudiant($conn);
    $etudiant->setEmail($email);
    $etudiant->setPassword($password);
    $user = $etudiant->login();


    if ($user) {
        
        $_SESSION['user_id'] = $user['user_id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

       
        switch ($user['role']) {
            case 'student':
                header('Location: ./../etudiant/cours.php'); 
                break;
            case 'teacher':
                header('Location: ./../teacher/teacher.php'); 
                break;
            case 'admin':
                header('Location: ./../Admin/dashboardAdmin.php'); 
                break;
            default:
                
                header('Location: index.html');
                break;
        }
        exit(); 
    } else {
        echo "Email ou mot de passe incorrect.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - TechNeon Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto flex justify-between items-center p-4">
            <div class="flex items-center">
                <a href="index.html" class="text-2xl font-bold text-cyan-600 hover:text-cyan-700">TechNeon Academy</a>
            </div>
            
            <div class="flex items-center">
                <a href="signup.html" class="text-gray-600 hover:text-cyan-600 transition-colors">Nouveau ? Inscrivez-vous</a>
            </div>
        </nav>
    </header>

    <main class="flex-grow container mx-auto p-4 flex justify-center items-center">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-sm p-8">
                <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Connexion</h1>
                <form class="space-y-6" action="" method="POST">
                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input type="password" id="password" name="password" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input type="checkbox" id="remember" class="h-4 w-4 text-cyan-600 focus:ring-cyan-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-700">Se souvenir de moi</label>
                        </div>
                        <a href="#" class="text-sm text-cyan-600 hover:text-cyan-700">Mot de passe oublié ?</a>
                    </div>

                    <button type="submit" class="w-full py-3 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                        Se connecter
                    </button>
                </form>
            </div>
        </div>
    </main>

    <footer class="bg-white border-t border-gray-200 mt-8">
        <div class="container mx-auto py-4 px-4 text-center">
            <p class="text-gray-600">&copy; 2025 TechNeon Academy. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>