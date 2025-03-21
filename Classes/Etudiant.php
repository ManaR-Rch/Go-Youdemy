<?php
require_once 'User.php';

class Etudiant extends User {
    protected $db; 
    protected $table = 'users'; 
    private $suspended;

    public function __construct($db, $id = null, $name = null, $email = null, $password = null, $suspended = false) {
      parent::__construct($id, $name, $email, $password, 'student'); 
      $this->db = $db;
      $this->suspended = $suspended;
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
  
      
      $query = "INSERT INTO {$this->table} (username, email, password_hash, role, suspended) 
                VALUES (:name, :email, :password, :role, :suspended)";
      $stmt = $this->db->prepare($query);
      $result = $stmt->execute([
          ':name' => $this->name,
          ':email' => $this->email,
          ':password' => $passwordHash,
          ':role' => $this->role, 
          ':suspended' => $this->suspended ? 1 : 0
      ]);
  
      if ($result) {
          echo "Inscription réussie. Bienvenue, {$this->name}!\n";
          return true;
      } else {
          echo "Erreur lors de l'inscription.\n";
          return false;
      }
  }

   
    public function updateProfile() {
      $updates = [];
      $params = [':id' => $this->id];
  
      if ($this->name) {
          $updates[] = "username = :name";
          $params[':name'] = $this->name;
      }
      if ($this->email) {
          $updates[] = "email = :email";
          $params[':email'] = $this->email;
      }
      if ($this->password) {
          $updates[] = "password_hash = :password";
          $params[':password'] = password_hash($this->password, PASSWORD_BCRYPT);
      }
      if (isset($this->suspended)) {
          $updates[] = "suspended = :suspended";
          $params[':suspended'] = $this->suspended ? 1 : 0;
      }
  
      if (empty($updates)) {
          echo "Aucune mise à jour à effectuer.\n";
          return false;
      }
  
      $query = "UPDATE {$this->table} SET " . implode(', ', $updates) . " WHERE user_id = :id";
      $stmt = $this->db->prepare($query);
      $result = $stmt->execute($params);
  
      if ($result) {
          echo "Profil mis à jour avec succès.\n";
          return true;
      } else {
          echo "Erreur lors de la mise à jour du profil.\n";
          return false;
      }
  }

  public function delete() {
    if (empty($this->id)) {
        throw new Exception("L'ID de l'utilisateur est requis.");
    }

    $query = "DELETE FROM {$this->table} WHERE user_id = :id";
    $stmt = $this->db->prepare($query);
    $result = $stmt->execute([':id' => $this->id]);

    if ($result) {
        return true;
    } else {
        throw new Exception("Erreur lors de la suppression de l'utilisateur.");
    }
}

    public function login() {
        $query = "SELECT * FROM {$this->table} WHERE email = :email ";
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

    
    public function logout() {
       
        session_destroy();
        echo "Etudiant {$this->name} logged out.\n";
    }

    public function getSuspended() {
        return $this->suspended;
    }
    
    public function setSuspended($suspended) {
        $this->suspended = $suspended;
    }
}
?>