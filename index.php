<?php
    require('auth/check-auth.php');

    require_once 'model/autorun.php';
    $myModel = Model\Data::makeModel(Model\Data::FILE);
    $myModel -> setCurrentUser($_SESSION['user']);

    require_once 'view/autorun.php';
    $myView = \View\DishListView::makeView(\View\DishListView::SIMPLEVIEW);
    $myView -> setCurrentUser($myModel -> getCurrentUser());

    $dishs = array();
    if ($myModel -> checkRight('dish', 'view')) {
        $dishs = $myModel -> readDishs();
    }
    $dish = new \Model\Dish();
    if ($_GET['dish'] && $myModel -> checkRight('dish', 'view')) {
        $dish = $myModel -> readDish($_GET['dish']);
    }
    $products = array();
    if ($_GET['dish'] && $myModel -> checkRight('product', 'view')) {
        $products = $myModel -> readProducts($_GET['dish']);
    }

    $myView -> showMainForm($dishs, $dish, $products);
?>