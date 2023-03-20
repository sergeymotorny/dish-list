<?php
    $f = fopen(__DIR__ . "/" . $dishFolder . "/dish.txt","r");
    $grStr = fgets($f);
    $grArr = explode(";", $grStr);
    fclose($f);

    $data['dish'] = array(
        'nameOfTheDish' => $grArr[0],
        'type' => $grArr[1],
        'portionWeight' => $grArr[2],
    );
?>
