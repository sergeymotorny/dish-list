<?php
    require '../auth/check-auth.php';

    require_once '../model/autorun.php';
    $myModel = Model\Data::makeModel(Model\Data::FILE);
    $myModel -> setCurrentUser($_SESSION['user']);
    if(!$users = $myModel -> readUsers()) {
        die($myModel -> getError());
    }

    require_once '../view/autorun.php';
    $myView = \View\DishListView::makeView(\View\DishListView::SIMPLEVIEW);

    $myView -> showAdminForm($users);
?>