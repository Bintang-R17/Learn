<?php
// File: controller/UserController.php

include __DIR__ . '/../model/user.php';

class UserController {
    private $model; 

    public function __construct($db) {
        $this->model = new User($db);
    }

    public function addUser() {
        include __DIR__ . '/../view/addUser.php';
    }

    public function addUserProcess() {
        if (isset($_POST['nama'], $_POST['jurusan'], $_POST['angkatan'])) {
            $result = $this->model->addUser($_POST['nama'], $_POST['jurusan'], $_POST['angkatan']);
            if ($result > 0) {
                header("Location: index.php?action=listUser&status=success");
                exit;
            } else {
                echo "Data Gagal Ditambahkan";
            }
        } else {
            echo "Data tidak lengkap";
        } 
    }
    
    public function listUser() {
        $users = $this->model->getUser();
        include __DIR__ . '/../view/listUser.php';
    }
    
    public function editUser() {
        if (isset($_GET['id'])) {
            $id = $_GET['id'];
            $user = $this->model->getUserById($id);
            include __DIR__ . '/../view/editUser.php';
        }
    }
    
    public function updateUserProcess() {
        if (isset($_POST['id'], $_POST['nama'], $_POST['jurusan'], $_POST['angkatan'])) {
            $result = $this->model->updateUser($_POST['id'], $_POST['nama'], $_POST['jurusan'], $_POST['angkatan']);
            if ($result > 0) {
                header("Location: index.php?action=listUser&status=updated");
                exit;
            }
        }
    }
    
    public function deleteUser() {
        if (isset($_GET['id'])) {
            $result = $this->model->deleteUser($_GET['id']);
            if ($result > 0) {
                header("Location: index.php?action=listUser&status=deleted");
                exit;
            }
        }
    }
}
?>