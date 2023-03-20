<?php
    if ($_POST) {
        $f = fopen("../data/" . $_GET['dish'] . "/" . $_GET['file'], "w");
        
        $grArr = array(
            $_POST['products_name'], 
            $_POST['products_weight'], 
            $_POST['products_calories'],
            $_POST['products_mass'],);
        $grArr = implode(";", $grArr);
        fwrite($f, $grArr);
        fclose($f);
        header('Location: ../index.php?dish=' . $_GET['dish']);
    }
    $path = __DIR__ . "/../data/" . $_GET['dish'];
    $node = $_GET['file'];
    $compoud = require __DIR__ . '/../data/declare-compoud.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/edit-prod-style.css">
    <title>Edit product</title>
</head>
<body>
    <?php
    //var_dump($compoud['product']);
    ?>
    <form name="edit-product" method="post">
        <div>
            <label class="text-field__label" for="products_name">Продукт: </label>
            <input class="text-field__input" type="text" name="products_name" pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" value="<?php echo $compoud['product'] ?>" required>
        </div>
        <div>
            <label class="text-field__label" for="products_weight">Вага: </label> 
            <!-- required pattern="\d+" -->
            <input class="text-field__input" type="text" name="products_weight" pattern="[0-9]{1,4}" value="<?php echo $compoud['weight'] ?>" required>
        </div>
        <div>
            <label class="text-field__label" for="products_calories">Калорій: </label>
            <input class="text-field__input" type="text" name="products_calories" pattern="[0-9]{1,5}" value="<?php echo $compoud['calories'] ?>" required>
        </div>
        <div>
            <label class="text-field__label" for="products_mass">Об'єм/Маса: </label>
            <select name="products_mass">
                <option <?php echo ("грам" == $compoud['volumeWeight'])?"selected":""; ?> value="грам">грам</option>
                <option <?php echo ("мл" == $compoud['volumeWeight'])?"selected":""; ?> value="мл">мл</option>
            </select>
        </div>
        <br>  
        <div class="btn-group">
            <input class="button" type="submit" name="ok" value="Змінити"> 
            <input class="button" type="submit" formaction="../index.php/" name="ok" value="На головну"> 
        </div>
    </form>
</body>
</html>