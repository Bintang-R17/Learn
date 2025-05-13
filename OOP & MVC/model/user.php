<?php

class User {
    private $conn;

    public function __construct($db) {
        $this->conn = $db->getConnection();
    }

    public function getUser() {
        $sql = "SELECT * FROM user";
        $result = $this->conn->query($sql);
        return $result; 
    }

    public function addUser($nama, $jurusan, $angkatan) {
        // Perbaikan: bind_param harus sesuai dengan jumlah parameter
        $stmt = $this->conn->prepare("INSERT INTO user (nama, jurusan, angkatan) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $nama, $jurusan, $angkatan);
        $stmt->execute();
        return $stmt->affected_rows;
    }
    
    public function getUserById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->get_result();
    }
    
    public function updateUser($id, $nama, $jurusan, $angkatan) {
        $stmt = $this->conn->prepare("UPDATE user SET nama = ?, jurusan = ?, angkatan = ? WHERE id = ?");
        $stmt->bind_param("sssi", $nama, $jurusan, $angkatan, $id);
        $stmt->execute();
        return $stmt->affected_rows;
    }
    
    public function deleteUser($id) {
        $stmt = $this->conn->prepare("DELETE FROM user WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        return $stmt->affected_rows;
    }
}
?>