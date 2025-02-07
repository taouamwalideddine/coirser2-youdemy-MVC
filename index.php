<?php
require_once 'config/Database.php';
require_once 'models/UserFactory.php';
require_once 'Router.php';
require_once 'Controllers/controllers_HomeController.php';
require_once 'Controllers/controllers_AuthController.php';
require_once 'Controllers/controllers_CourseController.php';
require_once 'Controllers/controllers_TeacherController.php';
require_once 'Controllers/controllers_AdminController.php';

session_start();

$router = new Router();

$router->addRoute('GET', '/login', 'AuthController@showLogin');
$router->addRoute('POST', '/login', 'AuthController@login');   
$router->addRoute('GET', '/register', 'AuthController@showRegister'); 
$router->addRoute('POST', '/register', 'AuthController@register');   
$router->addRoute('GET', '/logout', 'AuthController@logout');

$router->addRoute('GET', '/', 'HomeController@index');
$router->addRoute('GET', '/course/{id}', 'CourseController@show');
$router->addRoute('GET', '/search', 'HomeController@search');
$router->addRoute('GET', '/course/{id}', 'CourseController@show');
$router->addRoute('POST', '/course/delete/{id}', 'CourseController@delete');
$router->addRoute('GET', '/course/edit/{id}', 'CourseController@edit');
$router->addRoute('POST', '/course/update/{id}', 'CourseController@update');

$router->addRoute('GET', '/student/my-courses', 'StudentController@myCourses');

$router->addRoute('GET', '/teacher/dashboard', 'TeacherController@dashboard');
$router->addRoute('GET', '/teacher/courses', 'TeacherController@courses');
$router->addRoute('GET', '/teacher/add-course', 'TeacherController@showAddCourse');
$router->addRoute('POST', '/teacher/add-course', 'TeacherController@store');
$router->addRoute('GET', '/course/members/{id}', 'TeacherController@members');

$router->addRoute('GET', '/admin/dashboard', 'AdminController@dashboard');
$router->addRoute('GET', '/admin/users', 'AdminController@users');
// Admin routes
$router->addRoute('GET', '/admin/approve/{id}', 'AdminController@manageUser');
$router->addRoute('GET', '/admin/suspend/{id}', 'AdminController@manageUser');
$router->addRoute('GET', '/admin/delete/{id}', 'AdminController@manageUser');
$router->addRoute('POST', '/admin/course/{id}/{action}', 'AdminController@manageCourse');
$router->addRoute('POST', '/admin/category/add', 'AdminController@addCategory');
$router->addRoute('POST', '/admin/tag/add', 'AdminController@addTag');
$router->addRoute('GET', '/admin/deleteTag/{id}', 'AdminController@deleteTag');
$router->addRoute('GET', '/admin/deleteCategory/{id}', 'AdminController@deleteCategory');

$router->dispatch($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);
