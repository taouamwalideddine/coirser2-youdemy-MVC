<?php
require_once 'admin/TeacherManager.php';
class CourseController {
    private $courseFactory;
    private $teacherManager;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->courseFactory = new CourseFactory($db);
        $this->teacherManager = new TeacherManager($db);
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
}
