<?php 
    include(__DIR__ . '/../auth/check-auth.php');
    
    require_once '../model/autorun.php';
    $myModel = Model\Data::makeModel(Model\Data::FILE);
    $myModel -> setCurrentUser($_SESSION['user']);

    if ($_POST) {
        if(!$myModel -> writeDish((new \Model\Dish())
            -> setId($_GET['dish'])
            -> setNameOfTheDish($_POST['nameOfTheDish'])
            -> setType($_POST['type'])
            -> setPortionWeight($_POST['portionWeight'])
        )) {
            die($myModel -> getError());
        } else {
            header('Location: ../index.php?dish=' . $_GET['dish']);
        } 
        
    }
    if(!$dish = $myModel -> readDish($_GET['dish']) ) {
        die($myModel -> getError());
    }

    require_once '../view/autorun.php';
    $myView = \View\DishListView::makeView(\View\DishListView::SIMPLEVIEW);
    $myView -> setCurrentUser($myModel -> getCurrentUser());

    $myView -> showDishEditForm($dish);

?>