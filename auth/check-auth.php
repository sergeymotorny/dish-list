<?php
    session_start();
    if(!$_SESSION['user']) {
        header('Location: /dish-list/auth/login.php');
    }

?>