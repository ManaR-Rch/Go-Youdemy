<?php
require_once 'User.php';
require_once 'Etudiant.php';
class Enseignant extends Etudiant {
    private $estValide;

   
    public function __construct($db, $id = null, $name = null, $email = null, $password = null, $suspended = false, $estValide = false) {
        parent::__construct($db, $id, $name, $email, $password, $suspended);
        $this->estValide = $estValide;
        $this->setRole('teacher'); 
    }

    public function register() {
      if ($this->password === null) {
          echo "Erreur : Le mot de passe ne peut pas être null.\n";
          return false;
      }
  
      $query = "SELECT * FROM {$this->table} WHERE email = :email";
      $stmt = $this->db->prepare($query);
      $stmt->execute([':email' => $this->email]);
      $existingUser = $stmt->fetch(PDO::FETCH_ASSOC);
  
      if ($existingUser) {
          echo "Erreur : Cet email est déjà utilisé.\n";
          return false;
      }
  
      
      $passwordHash = password_hash($this->password, PASSWORD_DEFAULT);
  
      
      $query = "INSERT INTO {$this->table} (username, email, password_hash, role, suspended, est_valide) 
                VALUES (:name, :email, :password, :role, :suspended, :est_valide)";
      $stmt = $this->db->prepare($query);
      $result = $stmt->execute([
          ':name' => $this->name,
          ':email' => $this->email,
          ':password' => $passwordHash,
          ':role' => $this->role,
          ':suspended' => $this->suspended ? 1 : 0,
          ':est_valide' => $this->estValide ? 1 : 0
      ]);
  
      if ($result) {
          echo "Inscription réussie. Bienvenue, {$this->name}!\n";
          return true;
      } else {
          echo "Erreur lors de l'inscription.\n";
          return false;
      }
  }
  public function login() {
    $query = "SELECT * FROM {$this->table} WHERE email = :email AND role = 'teacher'";
    $stmt = $this->db->prepare($query);
    $stmt->execute([':email' => $this->email]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($this->password, $user['password_hash'])) {
        $this->id = $user['user_id'];
        $this->name = $user['username'];
        $this->email = $user['email'];
        $this->password = $user['password_hash'];
        $this->suspended = (bool)$user['suspended'];
        return $user;
    }
    return false;
}

   
    public function updateProfile($name = null, $email = null, $password = null) {
        $updates = [];
        $params = [':id' => $this->id];

        if ($name) {
            $updates[] = "username = :name";
            $params[':name'] = $name;
            $this->name = $name;
        }
        if ($email) {
            $updates[] = "email = :email";
            $params[':email'] = $email;
            $this->email = $email;
        }
        if ($password) {
            $updates[] = "password_hash = :password";
            $params[':password'] = password_hash($password, PASSWORD_BCRYPT);
            $this->password = $params[':password'];
        }

        if (empty($updates)) {
            echo "Aucune mise à jour à effectuer.\n";
            return false;
        }

        $query = "UPDATE users SET " . implode(', ', $updates) . " WHERE user_id = :id";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute($params);

        if ($result) {
            echo "Profil de l'enseignant mis à jour avec succès.\n";
            return true;
        } else {
            echo "Erreur lors de la mise à jour du profil.\n";
            return false;
        }
    }

   
    public function setEstValide($estValide) {
        $this->estValide = $estValide;
        $query = "UPDATE users SET est_valide = :est_valide WHERE user_id = :id";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':id' => $this->id,
            ':est_valide' => $estValide ? 1 : 0
        ]);

        if ($result) {
            $statut = $estValide ? "validé" : "invalidé";
            echo "Enseignant {$this->name} a été {$statut}.\n";
            return true;
        } else {
            echo "Erreur lors de la mise à jour du statut de validation.\n";
            return false;
        }
    }

    
    public function getEstValide() {
        return $this->estValide;
    }
}
?>