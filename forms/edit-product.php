<?php
    include(__DIR__ . '/../auth/check-auth.php');

    require_once '../model/autorun.php';
    $myModel = Model\Data::makeModel(Model\Data::FILE);
    $myModel -> setCurrentUser($_SESSION['user']);

    if ($_POST) {
        $product = (new \Model\Product())
            -> setId($_GET['file'])
            -> setDishId($_GET['dish'])
            -> setProduct($_POST['products_name'])
            -> setWeight($_POST['products_weight'])
            -> setCalories($_POST['products_calories'])
            -> setLiquidVolumeWeight();
        if($_POST['products_mass'] == 'мл') {
            $product -> setGramVolumeWeight();
        }
        if(!$myModel -> writeProduct($product)) {
            die($myModel -> getError());
        } else {
            header('Location: ../index.php?dish=' . $_GET['dish']); 
            // Выше можно заменить в ссылке на product= и ['product'], в случае ошибок.
        }
    }

    $product = $myModel -> readProduct($_GET['dish'], $_GET['file']); 

    require_once '../view/autorun.php';
    $myView = \View\DishListView::makeView(\View\DishListView::SIMPLEVIEW);
    $myView -> setCurrentUser($myModel -> getCurrentUser());

    $myView -> showProductEditForm($product);
?>