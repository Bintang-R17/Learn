<?php

include_once 'model/database.php';
include_once 'model/user.php';
require_once 'controller/AuthController.php';
require_once 'controller/BaseController.php';

class UserController extends BaseController {
    private $model;

    public function __construct($db) {
        $this->model = new User($db);
        if(session_status() == PHP_SESSION_NONE){
            session_start();
        }
    }

    public function addUser(){
        include __DIR__ . '/../view/addUser.php';
    }

    public function loginProcess(){
        if (isset($_POST['username'], $_POST['password'])){
            $username = $_POST['username'];
            $password = $_POST['password'];
            $user = $this->model->login($username, $password);
            if ($user) {
                $_SESSION['user_id'] = $user['id'];
                header('Location: index.php?action=profile&status=success');
                exit;
            } else {
                echo "Login gagal: username atau password salah.";
            }
        } else {
            echo "Data tidak lengkap";
        }
    }   

    public function logout() {
    session_start();
    session_destroy();
    header('Location: index.php?action=login');
    exit;
}

    public function addUserProcess(){
        if (isset($_POST['username'], $_POST['password'], $_POST['status'])){
            $result = $this->model->addUser($_POST['username'], $_POST['password'], $_POST['status']);
            if ($result) {
                header('Location: index.php?action=profile&status=success');
                exit;
            } else {
                echo "Gagal menambahkan user.";
            }
        } else {
            echo "Data tidak lengkap";
        }
    }

    public function updateProfile() {
        AuthController::check(); // pastikan user sudah login

        if (isset($_POST['username'], $_POST['password'])) {
            $userId = $_SESSION['user_id'];
            $username = $_POST['username'];
            $password = $_POST['password'];
            $status = $_POST['status'];

            $result = $this->model->updateUser($userId, $username, $password, $status);
            if ($result) {
                header('Location: index.php?action=profile&status=success');
                exit;
            } else {
                echo "Gagal memperbarui profil.";
            }
        } else {
            echo "Data tidak lengkap";
        }
    }

    public function profile() {
    AuthController::check(); 

    if (isset($_GET['id'])) {
        
        $id = (int)$_GET['id'];
        $user = $this->model->getUserById($id);

        if ($user) {
            $this->view('showprofile', ['user' => $user]);
        } else {
            echo "User tidak ditemukan.";
        }

    } else {
        
        $userId = $_SESSION['user_id'];
        $user = $this->model->getUserById($userId);

        $this->view('user/profile', ['user' => $user]);
    }
}


    public function index() {
        if (!isset($_SESSION['user_id'])) {
            header('Location: index.php?action=login');
            exit;
        }

        $currentUserId = $_SESSION['user_id'];

        // Panggil method model, bukan controller
        $users = $this->model->getAllUsersExcept($currentUserId);

        $this->view('index', ['users' => $users]);
    }

    public function showProfile($id) {
    $user = $this->model->getUserById($id);
    if ($user) {
        include 'view/profile.php'; // file ini akan menampilkan data user
    } else {
        echo "User tidak ditemukan.";
    }
}

}
