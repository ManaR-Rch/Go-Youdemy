<?php
class Tag {
    private $db;
    private $table = 'tags';
    private $id;
    private $titre;


    public function __construct($db, $id = null, $titre = null) {
        $this->db = $db;
        $this->id = $id;
        $this->titre = $titre;
    }

   
    public function create() {
        if (empty($this->titre)) {
           
        }

        $query = "INSERT INTO {$this->table} (name) VALUES (:titre)";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([':titre' => $this->titre]);

        if ($result) {
            $this->id = $this->db->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    
    public function update() {
        if (empty($this->id) || empty($this->titre)) {
          
            return false;
        }

        $query = "UPDATE {$this->table} SET name = :titre WHERE tag_id = :id";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':id' => $this->id,
            ':titre' => $this->titre
        ]);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }


    public function delete() {
        if (empty($this->id)) {
            return false;
        }

        $query = "DELETE FROM {$this->table} WHERE tag_id = :id";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([':id' => $this->id]);

        if ($result) {
            return true;
        } else {
            return false;
        }
    }

    
    public function getAll() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function getOne($id = null) {
        if (!empty($id)) {
            $this->id = $id; 
        }

        if (empty($this->id)) {
           
            return false;
        }

        $query = "SELECT * FROM {$this->table} WHERE tag_id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $this->id]);
        $tag = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($tag) {
            $this->id = $tag['tag_id'];
            $this->titre = $tag['name'];
            return $tag;
        } else {
            return false;
        }
    }

   
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitre() {
        return $this->titre;
    }

    public function setTitre($titre) {
        $this->titre = $titre;
    }
}
?>