// Di file config.php atau init.php
<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
