<?php
include_once 'model/database.php';
include_once 'controller/UserController.php';
include_once 'controller/AuthController.php';

$db = new Database('localhost', 'root','', 'portofolio');

$userController = new UserController($db);

$action = $_GET['action'] ?? 'login';

switch ($action){
    case 'login':
        include __DIR__ . '/view/login.php';
        break;
    case 'loginProcess':
        $userController->loginProcess();
        break;
    case 'logout':
        $userController->logout();
        break;
    case 'profile':
        $userController->profile();
        break;
    case 'editProfile':
        $userController->updateProfile();
        break;
    case 'dashboard':
        $userController->index();
        break;
    case 'showProfile':
        $id = $_GET['id'] ?? null;
        if ($id) {
            $userController->showProfile($id);
        } else {
            echo "ID tidak ditemukan";
        }
        break;
    
}
?>