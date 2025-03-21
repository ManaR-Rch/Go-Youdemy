<?php
require_once 'User.php';
class Admin extends User {
    private $db;
    private $table = 'users';


    public function __construct($db, $id = null, $name = null, $email = null, $password = null) {
        parent::__construct($id, $name, $email, $password, 'admin');
        $this->db = $db;
    }

   
    public function login($email, $password) {
        $query = "SELECT * FROM {$this->table} WHERE email = :email ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            $this->id = $user['user_id'];
            $this->name = $user['username'];
            $this->email = $user['email'];
            $this->password = $user['password_hash'];
            return $user;
        }
        return false;
    }

    public function getUsers() {
        $query = "SELECT * FROM users";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
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
            return false;
        }

        $query = "UPDATE {$this->table} SET " . implode(', ', $updates) . " WHERE user_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute($params);
    }

   
    public function logout() {
       
        session_destroy();
        echo "Admin {$this->name} logged out.\n";
    }

    
    public function validerEnseignant($enseignant_id, $est_valide = true) {
        $query = "SELECT * FROM {$this->table} WHERE user_id = :id AND role = 'teacher'";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $enseignant_id]);
        $enseignant = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($enseignant) {
            $query = "UPDATE {$this->table} SET est_valide = :est_valide WHERE user_id = :id";
            $stmt = $this->db->prepare($query);
            $stmt->execute([
                ':id' => $enseignant_id,
                ':est_valide' => $est_valide ? 1 : 0
            ]);

            $statut = $est_valide ? "validé" : "invalidé";
            echo "Enseignant {$enseignant['username']} a été {$statut} par l'administrateur {$this->name}.\n";
            return true;
        } else {
            echo "Erreur : L'utilisateur n'est pas un enseignant.\n";
            return false;
        }
    }
}
?>