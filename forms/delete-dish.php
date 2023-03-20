<?php
    $dirName = "../data/" . $_GET['dish'];
    $const = scandir($dirName);
    $i = 0;
    foreach($const as $node) {
        @unlink($dirName . "/" . $node);
    }
    @rmdir($dirName);
    header('Location: ../index.php')
?>