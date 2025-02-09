<?php
class TeacherController {
    private $userFactory;
    private $courseFactory;
    private $teacherManager;
    private $categoryManager;
    private $tagManager;
    private $course_tag;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->userFactory = new UserFactory($db);
        $this->courseFactory = new CourseFactory($db);
        $this->teacherManager = new TeacherManager($db);
        $this->categoryManager = new CategoriesManager($db);
        $this->tagManager = new TagsManager($db);
        $this->course_tag = new TagsCourse($db);
    }

    public function dashboard() {
        if (!$this->checkTeacherAuth()) return;

        try {
            $teacher = $this->userFactory->createUser('teacher', $_SESSION['user']);
            $teacherCourses = $teacher->getSpecificData();
            $statistics = $this->teacherManager->getGlobalStatistics($teacher->getId());
            
            // Add these variables for the dashboard
            $totalStudents = $statistics['students'] ?? 0;
            $totalViews = 0; // You'll need to implement this in your Statistics class
            $averageRating = 0; // You'll need to implement this in your Statistics class
            
            require 'views/teacher/teacher_dashboard.php';
        } catch (Exception $e) {
            error_log("Dashboard error: " . $e->getMessage());
            // Handle error appropriately
            header('Location: /error');
        }
    }

    public function members($courseId) {
        if (!$this->checkTeacherAuth()) return;

        try {
            $members = $this->teacherManager->getCourseMembersByCourseId($courseId);
            header('Content-Type: application/json');
            echo json_encode(['members' => array_map(function($member) {
                return [
                    'name' => $member->getUsername(),
                    'email' => $member->getEmail(),
                    'id' => $member->getId()
                ];
            }, $members)]);
        } catch (Exception $e) {
            error_log("Members fetch error: " . $e->getMessage());
            http_response_code(500);
            echo json_encode(['error' => 'Failed to fetch members']);
        }
    }

    private function checkTeacherAuth() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header('Location: /login');
            return false;
        }
        return true;
    }
}