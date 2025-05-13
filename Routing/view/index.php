<?php
require_once '../controller/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama = htmlspecialchars($_POST['nama']);
    $password = $_POST['password'];

    $user = new User($nama, $password);
    $auth = new AuthController();

    if ($_POST['action'] === 'login') {
        if ($auth->login($user)) {
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Username atau password salah!";
        }
    } elseif ($_POST['action'] === 'register') {
        $auth->register($user);
    }
}
?>

<!-- Form Login -->
<form method="post">
    <input type="text" name="nama" required placeholder="Username"><br>
    <input type="password" name="password" required placeholder="Password"><br>
    <button name="action" type="submit">Login</button>
    <button name="action" type="submit" value="register">Register</button>
    <?php if (isset($error)) echo "<p style='color:red;'>$error</p>"; ?>
</form>
