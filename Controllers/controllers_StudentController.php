<?php
require_once 'models/CourseFactory.php';

class StudentController {
    private $courseFactory;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->courseFactory = new CourseFactory($db);
    }

// In controllers_StudentController.php
public function myCourses() {
    if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
        header('Location: /login');
        return;
    }

    // Check if 'id_user' exists in the session
    if (!isset($_SESSION['user']['id_user'])) {
        die("Error: User ID not found in session.");
    }

    $studentId = $_SESSION['user']['id_user'];
    $enrolledCourses = $this->courseFactory->getCoursesByStudent($studentId);

    // Ensure $enrolledCourses is always an array
    $enrolledCourses = $enrolledCourses ?: [];

    require 'views/student/my_courses.php';
}
}
