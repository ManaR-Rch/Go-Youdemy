<?php

class Enrollment {
    private $db;
    private $table = 'enrollments';
    private $enrollment_id;
    private $student_id;
    private $course_id;


    public function __construct($db, $enrollment_id = null, $student_id = null, $course_id = null) {
        $this->db = $db;
        $this->enrollment_id = $enrollment_id;
        $this->student_id = $student_id;
        $this->course_id = $course_id;
    }

 
    public function attacherAuCour() {
        if (empty($this->student_id) || empty($this->course_id)) {
            return false;
        }
    
        // verify
        $query = "SELECT * FROM {$this->table} WHERE student_id = :student_id AND course_id = :course_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([
            ':student_id' => $this->student_id,
            ':course_id' => $this->course_id
        ]);
        $existingEnrollment = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($existingEnrollment) {
            return false; 
        }
    
        
        $query = "INSERT INTO {$this->table} (student_id, course_id) VALUES (:student_id, :course_id)";
        $stmt = $this->db->prepare($query);
        return $stmt->execute([
            ':student_id' => $this->student_id,
            ':course_id' => $this->course_id
        ]);
    }

    // get all inscrit d'um etud
    public function getEnrollmentsByStudent($student_id) {
        $query = "
            SELECT 
                c.course_id, 
                c.title, 
                c.description, 
                cat.name AS category_name, 
                u.username AS teacher_name
            FROM enrollments e
            JOIN courses c ON e.course_id = c.course_id
            JOIN categories cat ON c.category_id = cat.category_id
            JOIN users u ON c.teacher_id = u.user_id
            WHERE e.student_id = :student_id
        ";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':student_id' => $student_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // get all users inscri
    public function getEnrollmentsByCourse($course_id) {
        $query = "SELECT e.*, u.username as student_name 
                  FROM {$this->table} e
                  JOIN users u ON e.student_id = u.user_id
                  WHERE e.course_id = :course_id";
        $stmt = $this->db->prepare($query);
        $stmt->execute([':course_id' => $course_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

  
    public function getEnrollmentId() {
        return $this->enrollment_id;
    }

    public function setEnrollmentId($enrollment_id) {
        $this->enrollment_id = $enrollment_id;
    }

    public function getStudentId() {
        return $this->student_id;
    }

    public function setStudentId($student_id) {
        $this->student_id = $student_id;
    }

    public function getCourseId() {
        return $this->course_id;
    }

    public function setCourseId($course_id) {
        $this->course_id = $course_id;
    }
}
?>