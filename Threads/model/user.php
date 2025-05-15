<?php

class User {
    private $db;

    // Konstruktor menerima instance Database, lalu ambil objek mysqli
    public function __construct($database) {
        $this->db = $database->getConnection();
    }

    public function getUserById($userId) {
        $stmt = $this->db->prepare("SELECT * FROM profil WHERE id = ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getAllUsersExcept($userId) {
        $stmt = $this->db->prepare("SELECT id, username FROM profil WHERE id != ?");
        $stmt->bind_param("i", $userId);
        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    public function login($username, $password) {
        $stmt = $this->db->prepare("SELECT * FROM profil WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();
        $user = $result->fetch_assoc();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }

    public function addUser($username, $password, $status) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("INSERT INTO profil (username, password, status) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $username, $hashedPassword, $status);
        return $stmt->execute();
    }

    public function updateUser($userId, $username, $password, $status) {
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $this->db->prepare("UPDATE profil SET username = ?, password = ? WHERE id = ?");
        $stmt->bind_param("ssi", $username, $hashedPassword,$status, $userId);
        return $stmt->execute();
    }
}
