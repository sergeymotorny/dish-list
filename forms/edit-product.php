<?php
    include(__DIR__ . '/../auth/check-auth.php');

    require_once '../model/autorun.php';
    $myModel = Model\Data::makeModel(Model\Data::FILE);
    $myModel -> setCurrentUser($_SESSION['user']);

    if ($_POST) {
        $product = (new \Model\Product())
            -> setId($_GET['file'])
            -> setDishId($_GET['dish'])
            -> setProduct($_POST['products_name'])
            -> setWeight($_POST['products_weight'])
            -> setCalories($_POST['products_calories'])
            -> setLiquidVolumeWeight();
        if($_POST['products_mass'] == 'мл') {
            $product -> setGramVolumeWeight();
        }
        if(!$myModel -> writeProduct($product)) {
            die($myModel -> getError());
        } else {
            header('Location: ../index.php?dish=' . $_GET['dish']); 
            // Выше можно заменить в ссылке на product= и ['product'], в случае ошибок.
        }
    }

    $product = $myModel -> readProduct($_GET['dish'], $_GET['file']); 
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
    // var_dump($product['product']);
    ?>
    <form name="edit-product" method="post">
        <div>
            <label class="text-field__label" for="products_name">Продукт: </label>
            <input class="text-field__input" type="text" name="products_name" pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" 
                    value="<?php echo $product -> getProduct(); ?>" required>
        </div>
        <div>
            <label class="text-field__label" for="products_weight">Вага: </label> 
            <!-- required pattern="\d+" -->
            <input class="text-field__input" type="text" name="products_weight" pattern="[0-9]{1,4}" 
                    value="<?php echo $product -> getWeight(); ?>" required>
        </div>
        <div>
            <label class="text-field__label" for="products_calories">Калорій: </label>
            <input class="text-field__input" type="text" name="products_calories" pattern="[0-9]{1,5}" 
                    value="<?php echo $product -> getCalories(); ?>" required>
        </div>
        <div>
            <label class="text-field__label" for="products_mass">Об'єм/Маса: </label>
            <select name="products_mass">
                <option <?php echo ($product -> isVolumeWeightGram())?"selected":""; ?> value="грам">грам</option>
                <option <?php echo ($product -> isVolumeWeightLiquid())?"selected":""; ?> value="мл">мл</option>
            </select>
        </div>
        <br>  
        <div class="btn-group">
            <input class="button" type="submit" name="ok" value="Змінити"> 
            <input class="button" type="submit" formaction="../index.php" name="ok" value="На головну"> 
        </div>
    </form>
</body>
</html>