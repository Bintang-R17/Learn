<?php
require_once 'koneksi.php';
require_once '../model/user.php';

class AuthController {
    private $conn;

    public function __construct() {
        $db = new Koneksi();
        $this->conn = $db->getKoneksi();
    }

    public function login(User $user) {
        $nama = $this->conn->real_escape_string($user->nama);

        $query = "SELECT * FROM user WHERE nama = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->bind_param("s", $nama);
        $stmt->execute();

        $result = $stmt->get_result();
        $data = $result->fetch_assoc();

        if ($data && password_verify($user->password, $data['password'])) {
            session_start();
            $_SESSION['user_id'] = $data['id'];
            $_SESSION['nama'] = $data['nama'];
            return true;
        } else {
            return false;
        }
    }

    public function register(User $user) {
        $nama = $user->nama;
        $password = $user->password;

        if (empty($nama) || empty($password) ) {
            echo "Semua field harus diisi!";
            return;
        }

        // Cek apakah nama sudah dipakai
        $stmt = $this->conn->prepare("SELECT * FROM user WHERE nama = ?");
        $stmt->bind_param("s", $nama);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            echo "Username sudah digunakan!";
            return;
        }

        // Enkripsi password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Simpan data ke database
        $stmt = $this->conn->prepare("INSERT INTO user (nama, password) VALUES (?, ?)");
        $stmt->bind_param("ss", $nama, $hashedPassword);

        if ($stmt->execute()) {
            echo "Pendaftaran berhasil!";
        } else {
            echo "Terjadi kesalahan saat mendaftar.";
        }

        $stmt->close();
    }    
    
    public function isLoggedIn() {
        session_start();
        return isset($_SESSION['user_id']);
    }

    public function logout() {
        session_start();
        session_destroy();
    }
}

