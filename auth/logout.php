<?php
    session_start();
    unset($_SESSION['user']);
    header('Location: /dish-list/auth/login.php');
?>