<?php
    include(__DIR__ . '/../auth/check-auth.php');
    if (!checkRight('dish', 'create')) {
        die('Ви не маєте права на виконання цієї операції!');
    }

    #number last dish
    $dishTpl = '/^dish-\d\d\z/';
    $path = __DIR__ . "/../data/";
    $conts = scandir($path);
    foreach($conts as $node) {
        if (preg_match($dishTpl, $node)) {
            $last_dish = $node;
        }
    }

    #index last file, +1 
    $dish_index = (string)((int)substr($last_dish, -1, 2) +1);
    if (strlen($dish_index) == 1) {
        $dish_index = "0" . $dish_index;
    }
    #new file
    $newDishName = "dish-" . $dish_index;

    mkdir(__DIR__ . "/../data/" . $newDishName);
    $f = fopen(__DIR__ . "/../data/" . $newDishName . "/dish.txt", "w");
    $grStr = implode(";", $grArr);
    fwrite($f, "New; ; ");
    fclose($f);
    header('Location: ../index.php?dish=' . $newDishName);
?>