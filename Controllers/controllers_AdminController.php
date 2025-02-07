<?php
require_once 'admin/AdminManager.php'; 

class AdminController {
    private $userFactory;
    private $adminManager;
    private $categoryManager;
    private $tagManager;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->userFactory = new UserFactory($db);
        $this->adminManager = new AdminManager($db);
        $this->categoryManager = new CategoriesManager($db);
        $this->tagManager = new TagsManager($db);
    }

    public function dashboard() {
        if (!$this->checkAdminAuth()) return;
    
        // Fetch data
        $admin = $this->userFactory->createUser('admin', $_SESSION['user']);
        $statistics = $admin->getSpecificData();
        $categories = $this->categoryManager->listcategory();
        $tags = $this->tagManager->listTags();
        $listUsers = $admin->getAll();
        
        // Fetch pending courses (ensure it's always an array)
        $pendingCourses = $this->adminManager->getPendingCourses() ?? []; // <-- Fix here
        
        $globalStatistics = $this->adminManager->getGlobalStatistics();
        $listTeachers = $this->userFactory->getAllTeachers();
    
        // Pass variables to the view
        require 'views/admin/admin_dashboard.php';
    }

    public function users() {
        if (!$this->checkAdminAuth()) return;

        $admin = $this->userFactory->createUser('admin', $_SESSION['user']);
        $listUsers = $admin->getAll();
        require 'views/admin/listUsers.php';
    }

    public function manageUser($id) {
        if (!$this->checkAdminAuth()) return;
    
        $action = strpos($_SERVER['REQUEST_URI'], 'approve') !== false ? 'accept' :
                  (strpos($_SERVER['REQUEST_URI'], 'suspend') !== false ? 'suspend' : 'delete');
    
        $success = $this->adminManager->manageUser($id, $action);
    
        if ($success) {
            header('Location: /Croiser2/admin/dashboard');
        } else {
            header('Location: /Croiser2/admin/dashboard?error=action_failed');
        }
        exit;
    }

    public function manageCourse($courseId, $action) {
        if (!$this->checkAdminAuth()) return;

        $success = $this->adminManager->manageCourse($courseId, $action);
        
        if ($success) {
            header('Location: /Croiser2/admin/dashboard');
        } else {
            header('Location: /Croiser2/admin/dashboard?error=action_failed');
        }
        exit;
    }

    public function addTag() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = ['name' => $_POST['name']];
            $this->adminManager->InsertTag($data);
            header('Location: /Croiser2/admin/dashboard');
        }
    }

    public function deleteTag($id) {
        $this->adminManager->deleteTag($id);
        header('Location: /Croiser2/admin/dashboard');
    }

    public function addCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $data = ['name' => $_POST['name']];
            $this->adminManager->InsertCategory($data);
            header('Location: /Croiser2/admin/dashboard');
        }
    }

    public function deleteCategory($id) {
        $this->adminManager->deleteCategory($id);
        header('Location: /Croiser2/admin/dashboard');
    }

    private function checkAdminAuth() {
        if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            header('Location: /login');
            return false;
        }
        return true;
    }
}