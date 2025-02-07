<?php
class AuthController {
    private $userFactory;

    public function __construct() {
        $db = Database::getInstance()->getConnection();
        $this->userFactory = new UserFactory($db);
    }

    public function showLogin() {
        require 'views/login.php';
    }
    public function showRegister() {
        // Load the registration form view
        require_once 'views/register.php';
    }

    // controllers/AuthController.php
public function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';

        error_log("Login form data - Username: $username, Password: $password");

        $user = $this->userFactory->authenticate($username, $password);

        if ($user) {
            error_log("User authenticated successfully: " . print_r($user, true));

            $_SESSION['user'] = [
                'id_user' => $user->getId(),
                'username' => $user->getUsername(),
                'role' => $user->getRole(),
            ];

            switch ($user->getRole()) {
                case 'admin':
                    header('Location: /Croiser2/admin/dashboard');
                    break;
                case 'teacher':
                    header('Location: /Croiser2/teacher/dashboard');
                    break;
                case 'student':
                    header('Location: /Croiser2/student/my_courses.php');
                    break;
                default:
                    header('Location: /Croiser2/');
                    break;
            }
            exit; 
        } else {
            // Log failure
            error_log("Authentication failed: Invalid username or password");

            // Show error message
            $error = "Invalid username or password";
            require_once 'views/login.php';
        }
    } else {
        // If not a POST request, show the login form
        require_once 'views/auth/login.php';
    }
}

  // controllers/AuthController.php
public function register() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? 'student'; // Default role

        // Log form data
        error_log("Registration form data - Username: $username, Email: $email, Password: $password, Role: $role");

        // Validate input
        if (empty($username) || empty($email) || empty($password)) {
            $error = "All fields are required.";
            error_log("Validation failed: $error");
            require_once 'views/auth/register.php';
            return;
        }

        // Create user data array
        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $password,
            'role' => $role,
            'status' => 'ACTIVE', // Default status
        ];

        // Log user data
        error_log("User data to be inserted: " . print_r($userData, true));

        // Create the user
        $user = $this->userFactory->createUser($role, $userData);

        if ($user) {
            // Log success
            error_log("User created successfully: " . print_r($user, true));

            // Redirect to login page
            header('Location: /Croiser2/login');
            exit; // Ensure no further code is executed after the redirect
        } else {
            // Log failure
            error_log("User creation failed.");
            header('Location: /Croiser2/login');
            // Show error message
            $error = "Registration failed. Please try again.";
            require_once 'views/register.php';
        }
    } else {
        require_once 'views/register.php';
    }
}
public function logout() {

        $_SESSION = [];

        session_destroy();

        header('Location: /Croiser2/login');
        exit;
    }
}