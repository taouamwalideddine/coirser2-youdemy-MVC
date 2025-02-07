<?php
require_once 'CrudInterface.php';

class Category implements CrudInterface {
    private $db;
    private $id;
    private $name;

    public function __construct($db ,$id , $name) {
        $this->db = $db;
        $this->id = $id;
        $this->name = $name;
    }

    public function create($data) {
        $sql = "INSERT INTO categories (name) VALUES (:name)";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $data['name']);
        return $stmt->execute();
    }

    public function read($id_categorie) {
        $sql = "SELECT * FROM categories WHERE id_categorie = :id_categorie";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_categorie', $id_categorie);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update($id_categorie, $data) {
        $sql = "UPDATE categories SET name = :name WHERE id_categorie = :id_categorie";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':name', $data['name']);
        $stmt->bindParam(':id_categorie', $id_categorie);
        return $stmt->execute();
    }

    public function delete($id_categorie) {
        $sql = "DELETE FROM categories WHERE id_categorie = :id_categorie";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id_categorie', $id_categorie);
        return $stmt->execute();
    }

    public function getAll() {
        $sql = "SELECT * FROM categories";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function setName($name){
        $this->name = $name;
    }
    public function getId(){
        return $this->id;
    }
    public function getName(){
        return $this->name;
    }
}

