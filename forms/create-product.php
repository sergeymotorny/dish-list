<?php
    include(__DIR__ . '/../auth/check-auth.php');

    if ($_POST) {
        require_once '../model/autorun.php';
        $myModel = Model\Data::makeModel(Model\Data::FILE);
        $myModel -> setCurrentUser($_SESSION['user']);

        $product = (new \Model\Product())
            -> setDishId($_GET['dish'])
            -> setProduct($_POST['products_name'])
            -> setWeight($_POST['products_weight'])
            -> setCalories($_POST['products_calories'])
            -> setLiquidVolumeWeight();
        if($_POST['products_mass'] == 'мл') {
            $product -> setGramVolumeWeight();
        }
        if(!$myModel -> addProduct($product)) {
            die($myModel -> getError());
        } else {
            header('Location: ../index.php?dish=' . $_GET['dish']); 
            // Выше можно заменить в ссылке на product= и ['product'], в случае ошибок.
        }
    }

    require_once '../view/autorun.php';
    $myView = \View\DishListView::makeView(\View\DishListView::SIMPLEVIEW);

    $myView -> showProductCreateForm();
?>