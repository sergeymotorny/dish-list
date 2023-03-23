<?php 
    include(__DIR__ . '/../auth/check-auth.php');
    if (!checkRight('dish', 'delete')) {
        die('Ви не маєте права на виконання цієї операції!');
    }

    unlink(__DIR__ . "/../data/" . $_GET['dish'] . "/" . $_GET['file']);
    header('Location: ../index.php?dish=' . $_GET['dish']);
?>