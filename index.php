<?php
    require 'controller/autorun.php';
    $controller = new \Controller\DishListApp(\Model\Data::FILE, \View\DishListView::SIMPLEVIEW);
    $controller -> run();