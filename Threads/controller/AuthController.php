<?php 

class AuthController {
    public static function check(){
        if (isset($_SESSION['user_id'])){
            header('location: index.php?action=dashboard');
        }
        exit();
    }
}

?>