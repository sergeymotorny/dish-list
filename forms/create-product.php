<?php
    include(__DIR__ . '/../auth/check-auth.php');

    if ($_POST) {
        require_once '../model/autorun.php';
        $myModel = Model\Data::makeModel(Model\Data::FILE);
        $myModel -> setCurrentUser($_SESSION['user']);

        $product = (new \Model\Product())
            -> setDishId($_GET['dish'])
            -> setProduct($_POST['products_name'])
            -> setWeight($_POST['products_weight'])
            -> setCalories($_POST['products_calories'])
            -> setLiquidVolumeWeight();
        if($_POST['products_mass'] == 'мл') {
            $product -> setGramVolumeWeight();
        }
        if(!$myModel -> addProduct($product)) {
            die($myModel -> getError());
        } else {
            header('Location: ../index.php?dish=' . $_GET['dish']); 
            // Выше можно заменить в ссылке на product= и ['product'], в случае ошибок.
        }
    }
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/edit-prod-style.css">
    <title>Create product</title>
</head>

<body>

    <form name="create-product" method="post">
        <div class="text-field">
            <div>
                <label class="text-field__label" for="products_name">Продукт: </label>
                <input class="text-field__input" type="text" name="products_name" pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" placeholder="Молоко, М'ясо" required>
            </div>
            <div>
                <label class="text-field__label" for="products_weight">Вага: </label>
                <input class="text-field__input" type="text" name="products_weight" pattern="[0-9]{1,4}" placeholder="100" required>
            </div>
            <div>
                <label class="text-field__label" for="products_calories">Калорій: </label>
                <input class="text-field__input" type="text" name="products_calories" pattern="[0-9]{1,5}" placeholder="41" required>
            </div>
            <div>
                <label class="text-field__label" for="products_mass">Об'єм/Маса: </label>
                <select name="products_mass">
                    <option value="грам">грам</option>
                    <option value="мл">мл</option>
                </select>
            </div>
        </div>

        <br>

        <div class="btn-group">
            <input class="button" type="submit" name="ok" value="Додати">
            <input class="button" type="submit" formaction="../index.php" name="ok" value="На головну">
        </div>
    </form>


</body>
</html>