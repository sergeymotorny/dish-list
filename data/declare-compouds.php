<?php 
    $titleTpl = '/^compoud-\d\d.txt\z/';
    $path = __DIR__ . "/" . $dishFolder;
    $conts = scandir($path);

    $i = 0;
    foreach ($conts as $node) {
        if(preg_match($titleTpl, $node)) {
            $data['compouds'][$i] = require __DIR__ . '../declare-compoud.php';
            $i++;
        }
    }
?>