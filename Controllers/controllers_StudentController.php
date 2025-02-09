<?php
require_once 'models/CourseFactory.php';

class StudentController {
    private $courseFactory;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->courseFactory = new CourseFactory($db);
    }

    public function myCourses() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
            header('Location: /Croiser2/login');
            return;
        }

        $enrolledCourses = $this->courseFactory->getCoursesByStudent($_SESSION['user']['id']);
        require 'views/student/my_courses.php';
    }
}
