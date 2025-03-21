<?php
require_once(__DIR__ . '/../../Classes/Database.php');
require_once(__DIR__ . '/../../Classes/Etudiant.php');
require_once(__DIR__ . '/../../Classes/Enseignant.php'); 

$db = new Database();
$conn = $db->getConnection();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $role = $_POST['role']; 

   

    if ($role === 'student') {
     
      $etudiant = new Etudiant($conn, null, $name, $email, $password);
      if ($etudiant->register()) {
        
          header('Location: sign-in.php');
          exit();
      } else {
          echo "Erreur lors de l'inscription.";
      }
  } elseif ($role === 'teacher') {
     
      $enseignant = new Enseignant($conn, null, $name, $email, $password);
      if ($enseignant->register()) {
          
          header('Location: sign-in.php');
          exit();
      } else {
          echo "Erreur lors de l'inscription.";
      }
  }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription - TechNeon Academy</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="min-h-screen flex flex-col bg-gray-50">
    <header class="bg-white shadow-sm">
        <nav class="container mx-auto flex justify-between items-center p-4">
            <div class="flex items-center">
                <a href="index.html" class="text-2xl font-bold text-cyan-600 hover:text-cyan-700">TechNeon Academy</a>
            </div>
            
            <div class="flex items-center">
                <a href="login.html" class="text-gray-600 hover:text-cyan-600 transition-colors">Déjà inscrit ? Connectez-vous</a>
            </div>
        </nav>
    </header>

    <main class="flex-grow container mx-auto p-4 flex justify-center items-center">
        <div class="w-full max-w-md">
            <div class="bg-white rounded-lg shadow-sm p-8">
                <h1 class="text-2xl font-bold mb-6 text-gray-800 text-center">Inscription</h1>
                <form class="space-y-6" action="" method="POST">
                    <div class="space-y-2">
                        <label for="name" class="block text-sm font-medium text-gray-700">Nom</label>
                        <input type="text" id="name" name="name" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required>
                    </div>

                    <div class="space-y-2">
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" id="email" name="email" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required>
                    </div>

                    <div class="space-y-2">
                        <label for="password" class="block text-sm font-medium text-gray-700">Mot de passe</label>
                        <input type="password" id="password" name="password" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required>
                    </div>

                    <!-- Ajout du champ de sélection du rôle -->
                    <div class="space-y-2">
                        <label for="role" class="block text-sm font-medium text-gray-700">Rôle</label>
                        <select id="role" name="role" class="w-full p-3 bg-gray-50 border border-gray-300 rounded-lg focus:outline-none focus:border-cyan-500" required>
                            <option value="student">Étudiant</option>
                            <option value="teacher">Formateur</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-3 bg-cyan-600 text-white rounded-lg hover:bg-cyan-700 transition-colors">
                        S'inscrire
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