<?php
    include(__DIR__ . '/../auth/check-auth.php');
    if (!checkRight('dish', 'delete')) {
        die('Ви не маєте права на виконання цієї операції!');
    }

    $dirName = "../data/" . $_GET['dish'];
    $const = scandir($dirName);
    $i = 0;
    foreach($const as $node) {
        @unlink($dirName . "/" . $node);
    }
    @rmdir($dirName);
    header('Location: ../index.php')
?>