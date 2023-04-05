<?php 
    include(__DIR__ . '/../auth/check-auth.php');
    
    require_once '../model/autorun.php';
    $myModel = Model\Data::makeModel(Model\Data::FILE);
    $myModel -> setCurrentUser($_SESSION['user']);

    $product = (new \Model\Product()) -> setId($_GET['file']) -> setDishId($_GET['dish']);
    if(!$myModel -> removeProduct($product)) {
        die($myModel -> getError());
    } else {
        header('Location: ../index.php?dish=' . $_GET['dish']);
    }
?>