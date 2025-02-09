<?php
require_once 'User.php';

class Teacher extends User {
    public function getSpecificData() {
        try {
            $sql = "SELECT c.id, c.title, c.description, c.type 
                   FROM courses c 
                   WHERE c.teacher_id = :teacher_id";
            
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':teacher_id', $this->id);
            $stmt->execute();
            
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if (!$data) {
                return [];
            }
            
            $listCourse = [];
            $courseFactory = new CourseFactory($this->db);
            
            foreach ($data as $course) {
                $courseObj = $courseFactory->createCourse($course["type"] ?? 'default', $course);
                if ($courseObj) {
                    $listCourse[] = $courseObj;
                }
            }
            
            return $listCourse;
        } catch (PDOException $e) {
            error_log("Database error in getSpecificData: " . $e->getMessage());
            throw new Exception("Failed to fetch teacher courses");
        }
    }
}

