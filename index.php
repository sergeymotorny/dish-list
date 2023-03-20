<?php
    require('auth/check-auth.php');
    require('data/declare-dishs.php')
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/main-style.css">
    <link rel="stylesheet" href="css/liquid-style.css">
    <link rel="stylesheet" href="css/dish-choose-style.css">
    <title>Страва</title>
</head>

<body>
    <header>
        <div class="user-info">
            <span>Hello <?php echo $_SESSION['user']; ?>!</span>
            <a href="auth/logout.php">Logout</a>
        </div>
        <?php if (checkRight('dish', 'view')) : ?>
            <form name="dish-form" method="get">
                <label for="dish">Dish</label>
                <select name="dish">
                    <option value=""></option>
                    <?php
                    foreach ($data['dishs'] as $listOfDishes) {
                        echo "<option " . (($listOfDishes['file'] == $_GET['dish']) ? "selected" : "") .
                            " value='" . $listOfDishes['file'] . "'>" . $listOfDishes['nameOfTheDish'] . "</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="ok">
                <?php if (checkRight('dish', 'create')) : ?>
                        <a href="forms/create-dish.php">Add dish</a>
                <?php endif; ?>
            </form>
            <?php if ($_GET['dish']) : ?>
                <?php
                $dishFolder = $_GET['dish'];
                require('data/declare-data.php');
                ?>
                <h3>Назва: <span class="nameOfTheDish"><?php echo $data['dish']['nameOfTheDish'] ?></span></h3>
                <h3>Тип: <span class="type"><?php echo $data['dish']['type'] ?></span></h3>
                <h3>Вага порції: <span class="portionWeight"><?php echo $data['dish']['portionWeight'] ?></span></h3>
                <div class="control">
                    <?php if (checkRight('dish', 'edit')) : ?>
                        <a href="forms/edit-dish.php?dish=<?php echo $_GET['dish']; ?>">Edit dish</a>
                    <?php endif; ?>
                    <?php if (checkRight('dish', 'delete')) : ?>
                        <a href="forms/delete-dish.php?dish=<?php echo $_GET['dish']; ?>">Delete dish</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </header>
    <?php if (checkRight('product', 'view')) : ?>
        <section>
            <?php if ($_GET['dish']) : ?>
                <?php if (checkRight('product', 'create')) : ?>
                    <div class="control">
                        <a href="forms/create-product.php?dish=<?php echo $_GET['dish']; ?>">Додати продукт</a>
                    </div>
                <?php endif; ?>
                <table>
                    <thead>
                        <tr>
                            <th>№ п.</th>
                            <th>Продукт</th>
                            <th>Вага</th>
                            <th>Калорій</th>
                            <th>Об'єм/Маса</th>
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($data['compouds'])) : ?>
                            <?php foreach ($data['compouds'] as $key => $compoud) : ?>
                                <?php
                                $row_class = 'row';
                                if ($compoud['volumeWeight'] == 'мл') $row_class = 'liquid';
                                if ($compoud['volumeWeight'] == 'грам') $row_class = 'gram';
                                ?>
                                <?php if (!$_POST['compoudProdFilter'] || mb_stripos($compoud['product'], 
                                            $_POST['compoudProdFilter'], 0, 'UTF-8' ) !== false) : ?>
                                    <tr class="<?php echo $row_class; ?>">
                                        <td><?php echo ($key + 1); ?></td>
                                        <td><?php echo $compoud['product']; ?></td>
                                        <td><?php echo $compoud['weight']; ?></td>
                                        <td><?php echo $compoud['calories']; ?></td>
                                        <td><?php echo $compoud['volumeWeight']; ?></td>
                                        <td>
                                            <?php if (checkRight('product', 'edit')) : ?>
                                                <a href='forms/edit-product.php?dish=<?php echo $_GET['dish']; ?>&file=<?php echo
                                                    $compoud['file']; ?>'>Редагувати</a>
                                            <?php endif; ?>
                                            <?php if (checkRight('product', 'delete')) : ?>
                                                <a href='forms/delete-product.php?dish=<?php echo $_GET['dish']; ?>&file=<?php echo
                                                    $compoud['file']; ?>'>Видалити</a>
                                            <?php endif; ?>
                                        </td>
                                        <!-- Переделать в еще один <td></td> -->
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            <form name="compoud-filter" method="post">
                <div class="btn-group">
                    <input type="text" name="compoudProdFilter" value="<?php echo $_POST['compoudProdFilter'] ?>">
                    Фільтрування по назві продукту <br>
                    <input type="submit" value="Фільтрування" class="button">
                </div>
            </form>
        </section>
    <?php endif; ?>
</body>

</html>