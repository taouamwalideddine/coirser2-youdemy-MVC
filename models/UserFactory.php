<?php
require_once 'Student.php';
require_once 'Teacher.php';
require_once 'Admin.php';

class UserFactory {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function createUser($role, $userData) {
        switch ($role) {
            case 'student':
                return new Student($this->db, $userData);
            case 'teacher':
                return new Teacher($this->db, $userData);
            case 'admin':
                return new Admin($this->db, $userData);
            default:
                throw new Exception("Invalid user role");
        }
    }

    public function registerUser($role, $userData) {
        $sql = "INSERT INTO users (username, email, password, role, status)
                VALUES (:username, :email, :password, :role, :status)";
        $stmt = $this->db->prepare($sql);

        $hashedPassword = password_hash($userData['password'], PASSWORD_DEFAULT);

        $stmt->bindParam(':username', $userData['username']);
        $stmt->bindParam(':email', $userData['email']);
        $stmt->bindParam(':password', $hashedPassword);
        $stmt->bindParam(':role', $userData['role']);
        $stmt->bindParam(':status', $userData['status']);

        try {
            if ($stmt->execute()) {
                return $this->getUser($this->db->lastInsertId());
            }
        } catch (PDOException $e) {
            error_log("Error creating user: " . $e->getMessage());
        }

        return null;
    }
    public function getUser($id) {
        $sql = "SELECT * FROM users WHERE id_user  = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData) {
            return $this->createUser($userData['role'], $userData);
        }
        return null;
    }
    public function authenticate($username, $password) {
        $sql = "SELECT * FROM users WHERE username = :username";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $username);

        if (!$stmt->execute()) {
            return null;
        }

        $userData = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($userData && password_verify($password, $userData['password'])) {
            return $this->createUser($userData['role'], $userData);
        }
        return null;
    }
    public function getAllTeachers() {
        $sql = "SELECT * FROM users WHERE role = 'teacher'";
        $stmt = $this->db->query($sql);
        $teachers = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $list = [];
        foreach ($teachers as $teacher) {
            $list[] = $this->createUser($teacher['role'], $teacher);
        }
        return $list;
    }
}

