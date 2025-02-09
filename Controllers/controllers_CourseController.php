<?php
require_once 'admin/TeacherManager.php';
class CourseController {
    private $db;
    private $courseFactory;
    private $teacherManager;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        $this->courseFactory = new CourseFactory($this->db);
        $this->teacherManager = new TeacherManager($this->db);
    }

    public function show($id) {
        $course = $this->courseFactory->getCourse($id);
        if ($course) {
            require 'views/course_deatals.php'; 
        } else {
            header('Location: /Croiser2/');
            exit;
        }
    }

    public function delete($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header('Location: /login');
            return;
        }

        $this->teacherManager->manageCourse($id, 'delete');
        header('Location: /teacher/courses');
    }

    public function update($id) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header('Location: /login');
            return;
        }

        $course = [
            'id' => $id,
            'title' => $_POST['title'],
            'description' => $_POST['description'],
            'category' => $_POST['category_id'],
            'url' => $_POST['image']
        ];

        $this->courseFactory->updateCourse($course);
        header('Location: /teacher/courses');
    }

    public function enroll($courseId) {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
            header('Location: /Croiser2/login');
            return;
        }

        try {
            $sql = "INSERT INTO enrollments (student_id, course_id) VALUES (:student_id, :course_id)";
            $stmt = $this->db->prepare($sql);
            $stmt->bindParam(':student_id', $_SESSION['user']['id'], PDO::PARAM_INT);
            $stmt->bindParam(':course_id', $courseId, PDO::PARAM_INT);
            $stmt->execute();
            
            header('Location: /Croiser2/my-courses');
        } catch (PDOException $e) {
            error_log("Error enrolling in course: " . $e->getMessage());
            header('Location: /Croiser2/course/' . $courseId);
        }
    }
}
