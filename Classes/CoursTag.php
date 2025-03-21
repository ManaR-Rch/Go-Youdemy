<?php
class CoursTag {
    private $db;
    private $table = 'coursetags';
    private $course_id;
    private $tag_id;



    public function __construct($db, $course_id = null, $tag_id = null) {
        $this->db = $db;
        $this->course_id = $course_id;
        $this->tag_id = $tag_id;
    }

  
    public function attacherTag() {
        if (empty($this->course_id) || empty($this->tag_id)) {
            echo "Erreur : L'ID du cours et l'ID du tag sont requis.\n";
            return false;
        }

       
        $query = "SELECT * FROM {$this->table} WHERE course_id = :course_id AND tag_id = :tag_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':course_id' => $this->course_id,
            ':tag_id' => $this->tag_id
        ]);
        $existingAssociation = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($existingAssociation) {
            echo "Erreur : Ce tag est déjà associé à ce cours.\n";
            return false;
        }

   
        $query = "INSERT INTO {$this->table} (course_id, tag_id) VALUES (:course_id, :tag_id)";
        $stmt = $this->db->prepare($query);
        $result = $stmt->execute([
            ':course_id' => $this->course_id,
            ':tag_id' => $this->tag_id
        ]);

        if ($result) {
            
            return true;
        } else {
            
            return false;
        }
    }

    // all tags in one cors
    public function getTagsByCourse($course_id) {
        $query = "SELECT t.* 
                  FROM tags t
                  JOIN coursetags ct ON t.tag_id = ct.tag_id
                  WHERE ct.course_id = :course_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':course_id' => $course_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // all cours one tag
    public function getCoursesByTag($tag_id) {
        $query = "SELECT c.* 
                  FROM courses c
                  JOIN coursetags ct ON c.course_id = ct.course_id
                  WHERE ct.tag_id = :tag_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':tag_id' => $tag_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    
    public function getCourseId() {
        return $this->course_id;
    }

    public function setCourseId($course_id) {
        $this->course_id = $course_id;
    }

    public function getTagId() {
        return $this->tag_id;
    }

    public function setTagId($tag_id) {
        $this->tag_id = $tag_id;
    }
}
?>