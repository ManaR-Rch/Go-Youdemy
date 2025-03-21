<?php

class Cours {
    private $db;
    private $table = 'courses';
    private $id;
    private $title;
    private $description;
    private $content;
    private $teacher_id; 
    private $category_id; 

    public function __construct($db, $id = null, $title = null, $description = null, $content = null, $teacher_id = null, $category_id = null) {
        $this->db = $db;
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->content = $content;
        $this->teacher_id = $teacher_id;
        $this->category_id = $category_id; 
    }

    public function create() {

        $query = "INSERT INTO {$this->table} (title, description, content, teacher_id, category_id) 
                  VALUES (:title, :description, :content, :teacher_id, :category_id)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':title' => $this->title,
            ':description' => $this->description,
            ':content' => $this->content,
            ':teacher_id' => $this->teacher_id, 
            ':category_id' => $this->category_id
        ]);
    }


  
    public function update() {
        $query = "UPDATE {$this->table} 
                  SET title = :title, description = :description, content = :content, category_id = :category_id 
                  WHERE course_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':id' => $this->id,
            ':title' => $this->title,
            ':description' => $this->description,
            ':content' => $this->content,
            ':category_id' => $this->category_id
        ]);
    }

   
    public function delete() {
        $query = "DELETE FROM {$this->table} WHERE course_id = :id";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([':id' => $this->id]);
    }

   
    public function getAll() {
        $query = "
            SELECT 
                c.course_id, 
                c.title, 
                c.description, 
                cat.name AS category_name, 
                u.username AS teacher_name
            FROM courses c
            LEFT JOIN categories cat ON c.category_id = cat.category_id
            LEFT JOIN users u ON c.teacher_id = u.user_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function getOne($id) {
        $query = "
            SELECT 
                c.course_id, 
                c.title, 
                c.description, 
                c.content, 
                cat.name AS category_name, 
                u.username AS teacher_name
            FROM courses c
            LEFT JOIN categories cat ON c.category_id = cat.category_id
            LEFT JOIN users u ON c.teacher_id = u.user_id
            WHERE c.course_id = :id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':id' => $id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getCoursesWithCategory($teacher_id) {
        $query = "
            SELECT 
                c.course_id, 
                c.title, 
                c.description, 
                c.content, 
                c.category_id, 
                cat.name AS category_name, 
                COUNT(e.student_id) AS student_count
            FROM courses c
            LEFT JOIN categories cat ON c.category_id = cat.category_id
            LEFT JOIN enrollments e ON c.course_id = e.course_id
            WHERE c.teacher_id = :teacher_id
            GROUP BY c.course_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':teacher_id' => $teacher_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

   
    public function getId() {
        return $this->id;
    }

    public function setId($id) {
        $this->id = $id;
    }

    public function getTitle() {
        return $this->title;
    }

    public function setTitle($title) {
        $this->title = $title;
    }

    public function getDescription() {
        return $this->description;
    }

    public function setDescription($description) {
        $this->description = $description;
    }

    public function getContent() {
        return $this->content;
    }

    public function setContent($content) {
        $this->content = $content;
    }

    public function getType() {
        return $this->type;
    }

    public function setType($type) {
        $this->type = $type;
    }
}
?>