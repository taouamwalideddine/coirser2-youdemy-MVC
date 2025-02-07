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

        $teacher = $this->userFactory->createUser('teacher', $_SESSION['user']);
        $teacherCourses = $teacher->getSpecificData();
        $statistics = $this->teacherManager->getGlobalStatistics($teacher->getId());
        require 'views/teacher/teacher_dashboard.php';
    }

    public function courses() {
        if (!$this->checkTeacherAuth()) return;

        $teacher = $this->userFactory->createUser('teacher', $_SESSION['user']);
        $courses = $teacher->getSpecificData();
        $categories = $this->categoryManager->listcategory();
        $tags = $this->tagManager->listTags();
        require 'views/teacher/teacher_courses.php';
    }

    public function store() {
        if (!$this->checkTeacherAuth()) return;
        
        $course = $this->courseFactory->createCourse($_POST['content_type'], $_POST);
        $id_course = $course->create($_POST);
        
        $tags = explode(',', $_POST["selected_tags"]);
        foreach ($tags as $id_tag) {
            $this->course_tag->create($id_course, $id_tag);
        }
        header('Location: /teacher/dashboard');
    }

    public function members($courseId) {
        if (!$this->checkTeacherAuth()) return;

        $members = $this->teacherManager->getCourseMembersByCourseId($courseId);
        header('Content-Type: application/json');
        echo json_encode(['members' => $members]);
    }

    private function checkTeacherAuth() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'teacher') {
            header('Location: /login');
            return false;
        }
        return true;
    }
}