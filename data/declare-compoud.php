<?php 
    $f = fopen($path . "/" . $node,"r");
    
    $rowStr = fgets($f);
    $rowArr = explode(";", $rowStr);
    $compoud['file'] = $node;
    $compoud["product"] = $rowArr[0];
    $compoud["weight"] = $rowArr[1];
    $compoud["calories"] = $rowArr[2];
    $compoud["volumeWeight"] = $rowArr[3];
    
    fclose($f);

    return $compoud;
?>