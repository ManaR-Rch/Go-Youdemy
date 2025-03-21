<?php

class Database {
    private $host = "localhost";
    private $dbname = "youdemy";
    private $username = "root";
    private $password = "";
    private $conn;

    public function getConnection() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->dbname}",
                $this->username,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->conn;
        } catch(PDOException $e) {
            echo "Connection error: " . $e->getMessage();
            return null;
        }
    }

   
    public function getTotalCourses() {
        $conn = $this->getConnection();
        $stmt = $conn->query("SELECT COUNT(*) AS total_courses FROM Courses");
        return $stmt->fetchColumn();
    }

  
    public function getTotalCategories() {
        $conn = $this->getConnection();
        $stmt = $conn->query("SELECT COUNT(DISTINCT category_id) AS total_categories FROM Courses");
        return $stmt->fetchColumn();
    }

    //cours with plus etudiant
    public function getMostPopularCourse() {
        $conn = $this->getConnection();
        $stmt = $conn->query("
            SELECT C.title, COUNT(E.student_id) AS student_count
            FROM Courses C
            JOIN Enrollments E ON C.course_id = E.course_id
            GROUP BY C.course_id
            ORDER BY student_count DESC
            LIMIT 1
        ");
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    //top 3
    public function getTopTeachers() {
        $conn = $this->getConnection();
        $stmt = $conn->query("
            SELECT U.username AS teacher_name, COUNT(E.student_id) AS student_count
            FROM Users U
            JOIN Courses C ON U.user_id = C.teacher_id
            JOIN Enrollments E ON C.course_id = E.course_id
            WHERE U.role = 'teacher'
            GROUP BY U.user_id
            ORDER BY student_count DESC
            LIMIT 3
        ");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>