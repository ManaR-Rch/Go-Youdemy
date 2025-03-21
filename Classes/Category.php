<?php

class Category {
    private $db;
    private $table = 'categories';

   
    private $id;
    private $nom;
    private $description;

  
    public function __construct($db, $id , $nom , $description ) {
        $this->db = $db;
        $this->id = $id;
        $this->nom = $nom;
        $this->description = $description;
    }

   
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getNom() {
        return $this->nom;
    }

    public function setNom($nom) {
        if (empty($nom)) {
            throw new InvalidArgumentException("Le nom de la catégorie ne peut pas être vide.");
        }
        $this->nom = $nom;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

   
    public function create() {
        if (empty($this->nom)) {
            throw new InvalidArgumentException("Le nom de la catégorie est requis.");
        }
    
        $query = "INSERT INTO {$this->table} (name) VALUES (:name)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':name' => $this->getNom()
        ]);
    }


   
    public function delete() {
        if (empty($this->id)) {
            throw new InvalidArgumentException("L'ID de la catégorie est requis.");
        }
    
        $query = "DELETE FROM {$this->table} WHERE category_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $this->getId()]);
    }

   
    public function getAll() {
        $query = "SELECT * FROM {$this->table}";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getById() {
        if (empty($this->id)) {
            throw new InvalidArgumentException("L'ID de la catégorie est requis.");
        }

        $query = "SELECT * FROM {$this->table} WHERE id = :id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $this->getId()]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}