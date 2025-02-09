<?php
require_once 'CourseFactory.php';
require_once 'UserFactory.php';

class Statistics {
    private $db;
    private $courseFactory;
    public function __construct($db) {
        $this->db = $db;
        $this->courseFactory  = new CourseFactory($db);
    }

    public function getTotalCourses() {
        $sql = "SELECT COUNT(*) as total FROM courses";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['total'];
    }
    public function getTotalCoursesbyTeacher($id) {
        $sql = "SELECT COUNT(*) as total FROM courses where teacher_id=:id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function getCoursesByCategory() {
        $sql = "SELECT categories.name, COUNT(courses.id) as count 
                FROM categories 
                LEFT JOIN courses ON categories.id_categorie = courses.category_id 
                GROUP BY categories.id_categorie";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMostPopularCourse() {
        $sql = "SELECT courses.*, COUNT(enrollments.id) as student_count 
                FROM courses 
                LEFT JOIN enrollments ON courses.id = enrollments.course_id 
                GROUP BY courses.id 
                ORDER BY student_count DESC 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function getMostPopularCoursebyTeacher($id) {
        $sql = "SELECT courses.*, COUNT(enrollments.id) as student_count 
                FROM courses 
                LEFT JOIN enrollments ON courses.id = enrollments.course_id  where teacher_id=:id
                GROUP BY courses.id 
                ORDER BY student_count DESC 
                LIMIT 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id',$id);
        $stmt->execute();
        $course = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($course) {
            return $this->courseFactory->createCourse($course["type"], $course);
        }
        return null;
    }
    public function getStudents($teacherId) {
        try {
            $query = "SELECT COUNT(DISTINCT e.student_id) as total
                      FROM enrollments e
                      JOIN courses c ON e.course_id = c.id
                      WHERE c.teacher_id = :teacher_id";
            
            $stmt = $this->db->prepare($query);
            $stmt->bindParam(':teacher_id', $teacherId);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result['total'] ?? 0;
        } catch (PDOException $e) {
            error_log("Error getting student count: " . $e->getMessage());
            return 0;
        }
    }

    public function getTopTeachers() {
        $sql = "SELECT users.*, COUNT(courses.id) as course_count 
                FROM users 
                JOIN courses ON users.id_user = courses.teacher_id 
                WHERE users.role = 'teacher' 
                GROUP BY users.id_user
                ORDER BY course_count DESC 
                LIMIT 3";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}

