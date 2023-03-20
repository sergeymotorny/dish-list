<?php 
    unlink(__DIR__ . "/../data/" . $_GET['dish'] . "/" . $_GET['file']);
    header('Location: ../index.php?dish=' . $_GET['dish']);
?>