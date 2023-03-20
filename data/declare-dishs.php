<?php 
    $titleTpl = '/^dish-\d\d\z/';
    $path = __DIR__ ;
    $conts = scandir($path);

    $i = 0;
    foreach ($conts as $node) {
        if(preg_match($titleTpl, $node)) {
            $dishFolder = $node;
            require (__DIR__ . '/declare-dish.php');
            
            $data['dishs'][$i]['nameOfTheDish'] = $data['dish']['nameOfTheDish'];
            $data['dishs'][$i]['file'] = $dishFolder;
            $i++;
        }
    }
?>