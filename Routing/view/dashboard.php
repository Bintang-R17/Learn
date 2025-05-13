<?php
require_once 'AuthController.php';
$auth = new AuthController();

if (!$auth->isLoggedIn()) {
    header("Location: login.php");
    exit;
}
?>

<h1>Selamat datang, <?= $_SESSION['username']; ?>!</h1>
<a href="logout.php">Logout</a>
