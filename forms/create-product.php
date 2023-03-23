<?php
    include(__DIR__ . '/../auth/check-auth.php');
    if (!checkRight('dish', 'create')) {
        die('Ви не маєте права на виконання цієї операції!');
    }

    if ($_POST) {
        #number last product
        $productTpl = '/^compoud-\d\d.txt\z/';
        $path = __DIR__ . "/../data/" . $_GET['dish'];
        $conts = scandir($path);
        // $i = 0;
        foreach ($conts as $node) {
            if (preg_match($productTpl, $node)) {
                $last_file = $node;
            }
        }

    #index last file, +1
    $file_index = (string)((int)substr($last_file, -6, 2) + 1);
    if (strlen($file_index) == 1) {
        $file_index = "0" . $file_index;
    }
    #new file
    $newFileName = "compoud-" . $file_index . ".txt";

    #save data to file
    $f = fopen("../data/" . $_GET['dish'] . "/" . $newFileName, "w");
    $grArr = array(
        $_POST['products_name'],
        $_POST['products_weight'],
        $_POST['products_calories'],
        $_POST['products_mass'],
    );
    $grStr = implode(";", $grArr);
    fwrite($f, $grStr);
    fclose($f);
    header('Location: ../index.php?dish=' . $_GET['dish']);
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