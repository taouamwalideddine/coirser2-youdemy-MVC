<?php
require_once 'models/CourseFactory.php'; 
require_once 'admin/CategoriesManager.php';
require_once 'admin/TagsManager.php';
require_once 'models/UserFactory.php';
class HomeController {
    private $courseFactory;
    private $categoryManager;
    private $tagManager;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->courseFactory = new CourseFactory($db);
        $this->categoryManager = new CategoriesManager($db);
        $this->tagManager = new TagsManager($db);
    }

    public function index() {
        $page = $_GET['page'] ?? 1;
        $tag = $_GET['tag'] ?? false;

        $courses = $tag ?
            $this->courseFactory->getAllCoursesWithTag($tag) :
            $this->courseFactory->getAllCourses($page);

        $categories = $this->categoryManager->listcategory();
        $tags = $this->tagManager->listTags();

        require 'views/home.php';
    }

    public function search() {
        $keyword = $_GET['keyword'] ?? '';
        $results = $this->courseFactory->searchCourses($keyword);
        require 'views/search_results.php';
    }
}