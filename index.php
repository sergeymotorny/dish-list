<?php
    require_once 'data/config.php';
    require_once 'controller/autorun.php';
    $controller = new \Controller\DishListApp(Config::$modelType, Config::$viewType);
    $controller -> run();