<?php
require_once 'CrudInterface.php';
require_once 'UserInterface.php';
abstract class User implements CrudInterface, UserInterface {
    protected $db;
    protected $id;
    protected $username;
    protected $email;
    protected $role;
    protected $password;
    protected $status;
    
    public function __construct($db, $userData = null) {
        $this->db = $db;
        if ($userData) {
            $this->id = $userData['id'];
            $this->username = $userData['username'];
            $this->email = $userData['email'] ?? null;
        }
    }
    public function create($data){
        echo "<pre>";
        var_dump($data);
        var_dump($this->db);
        echo "</pre>";
        echo $this->status;
        $sql = "INSERT INTO users(
	     username, email, password, role, status)
	    VALUES (:username, :email, :password, :role,:status)";
        $password = password_hash($data['password'], PASSWORD_DEFAULT);
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':status', $data['status']);
        $stmt->bindParam(':password', $password);
        $stmt->bindParam(':role', $data['role']);
        echo $password;
        try {
            $stmt->execute();
            echo "executed";
        } catch (PDOException $e) {
            echo "Error: " . $e->getMessage();
        }   
    }

    public function read($id) {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id, $data) {
        $sql = "UPDATE users SET username = :username, email = :email WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':username', $data['username']);
        $stmt->bindParam(':email', $data['email']);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    public function delete($id) {
        $sql = "DELETE FROM users WHERE id_user = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }
// In User.php
public function activate($id) {
    $sql = "UPDATE users SET status = 'ACCEPTED' WHERE id_user  = :id"; // Changed id_user to id
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

public function suspend($id) {
    $sql = "UPDATE users SET status = 'SUSPENDED' WHERE id_user  = :id"; // Changed id_user to id
    $stmt = $this->db->prepare($sql);
    $stmt->bindParam(':id', $id);
    return $stmt->execute();
}

    public function getAll() {
        $sql = "SELECT * FROM users WHERE role <> :role";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':role', $this->role);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getUserbyId($id) {
        $sql = "SELECT * FROM users where id_user = :id_user";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_user', $this->role);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }


    public function getRole() {
        return $this->role;
    }
    public function getStatus() {
        return $this->status;
    }

    public function getId() {
        return $this->id;
        }

    public function getUsername() {
        return $this->username;
    }

    public function getEmail() {
        return $this->email;
    }
    public function getPassword() {
        return $this->password;
    }
    
    public function setEmail($email) {
        $this->email =$email;
    }
    public function setPassword($email) {
        $this->email =$email;
    }
    public function setUsername($username) {
        $this->username =$username;
    }

    // Additional methods

    abstract public function getSpecificData();
}

