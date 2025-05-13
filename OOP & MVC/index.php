<?php
include __DIR__ . '/model/database.php';
include __DIR__ . '/controller/UserController.php';
include __DIR__ . '/controller/DosenController.php';

$db = new Database('localhost', 'root', '', 'oopmvc');

// Inisialisasi semua controller
$userController = new UserController($db);
$dosenController = new DosenController($db);

// Ambil action dari URL, default ke listUser
$action = $_GET['action'] ?? 'listUser';

// Pisahkan routing berdasarkan aksi
switch ($action) {
    // Routing untuk User
    case 'listUser':
    case 'addUser':
    case 'addUserProcess':
    case 'editUser':
    case 'updateUserProcess':
    case 'deleteUser':
        $userController->$action();
        break;
    
    // Routing untuk Dosen
    case 'listDosen':
    case 'addDosen':
    case 'addDosenProcess':
    case 'editDosen':
    case 'updateDosenProcess':
    case 'deleteDosen':
        $dosenController->$action();
        break;
    
    default:
        echo "Halaman tidak ditemukan.";
        break;
}
?>