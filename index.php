<?php
    require('auth/check-auth.php');

    require_once 'model/autorun.php';
    $myModel = Model\Data::makeModel(Model\Data::FILE);
    $myModel -> setCurrentUser($_SESSION['user']);
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
    <title>DISH-LIST</title>
</head>

<body>
    <header>
        <div class="user-info">
            <span>Hello <?php echo $_SESSION['user']; ?>!</span>
            <?php if ($myModel->checkRight('user', 'admin')) : ?>
                <a href="admin/index.php">Адміністрування</a>
            <?php endif; ?>
            <a href="auth/logout.php">Logout</a>
        </div>
        <?php if ($myModel->checkRight('dish', 'view')) : ?>
            <?php $data['dishs'] = $myModel->readDishs(); ?>
            <form name="dish-form" method="get">
                <label for="dish">Dish</label>
                <select name="dish">
                    <option value=""></option>
                    <?php
                    foreach ($data['dishs'] as $listOfDishes) {
                        echo "<option " . (($listOfDishes->getId() == $_GET['dish']) ? "selected" : "") .
                            " value='" . $listOfDishes->getId() . "'>" . $listOfDishes->getNameOfTheDish() . "</option>";
                    }
                    ?>
                </select>
                <input type="submit" value="ok">
                <?php if ($myModel->checkRight('dish', 'create')) : ?>
                    <a href="forms/create-dish.php">Add dish</a>
                <?php endif; ?>
            </form>
            <?php if ($_GET['dish']) : ?>
                <?php
                $dishFolder = $_GET['dish'];
                $data['dish'] = $myModel->readDish($_GET['dish']);
                ?>
                <h3>Назва: <span class="nameOfTheDish"><?php echo $data['dish']->getNameOfTheDish(); ?></span></h3>
                <h3>Тип: <span class="type"><?php echo $data['dish']->getType(); ?></span></h3>
                <h3>Вага порції: <span class="portionWeight"><?php echo $data['dish']->getPortionWeight(); ?></span></h3>
                <div class="control">
                    <?php if ($myModel->checkRight('dish', 'edit')) : ?>
                        <a href="forms/edit-dish.php?dish=<?php echo $_GET['dish']; ?>">Edit dish</a>
                    <?php endif; ?>
                    <?php if ($myModel->checkRight('dish', 'delete')) : ?>
                        <a href="forms/delete-dish.php?dish=<?php echo $_GET['dish']; ?>">Delete dish</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </header>
    <?php if ($myModel->checkRight('product', 'view')) : ?>
        <?php $data['products'] = $myModel->readProducts($_GET['dish']); ?>
        <section>
            <?php if ($_GET['dish']) : ?>
                <?php if ($myModel->checkRight('product', 'create')) : ?>
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

                        <!-- Убрал ниже count! Т.к. ошибка -->
                        <?php if (count($data['products']) > 0) : ?>
                            <?php foreach ($data['products'] as $key => $product) : ?>
                                <?php
                                $row_class = 'row';
                                if ($product -> isVolumeWeightLiquid()) {
                                    $row_class = 'liquid';
                                }
                                if ($product -> isVolumeWeightGram()) {
                                    $row_class = 'gram';
                                }
                                ?>
                                <?php if (!$_POST['productProdFilter'] || mb_stripos(
                                    $product -> getNameOfTheProduct(), $_POST['productProdFilter'], 0, 'UTF-8') !== false) : ?>
                                    <tr class="<?php echo $row_class; ?>">
                                        <td><?php echo ($key + 1); ?></td>
                                        <td><?php echo $product-> getProduct(); ?></td>
                                        <td><?php echo $product-> getWeight(); ?></td>
                                        <td><?php echo $product-> getCalories(); ?></td>
                                        <td><?php echo $product-> isVolumeWeightLiquid() ? 'мл' : 'грам'; ?></td>
                                        <td>
                                            <?php if ($myModel->checkRight('product', 'edit')) : ?>
                                                <a href='forms/edit-product.php?dish=<?php echo $_GET['dish']; ?>&file=<?php echo
                                                        $product->getId(); ?>'>Редагувати</a>
                                            <?php endif; ?>
                                            <?php if ($myModel->checkRight('product', 'delete')) : ?>
                                                <a href='forms/delete-product.php?dish=<?php echo $_GET['dish']; ?>&file=<?php echo
                                                         $product->getId(); ?>'>Видалити</a>
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
            <form name="product-filter" method="post">
                <div class="btn-group">
                    <input type="text" name="productProdFilter" value="<?php echo $_POST['productProdFilter'] ?>">
                    Фільтрування по назві продукту <br>
                    <input type="submit" value="Фільтрування" class="button">
                </div>
            </form>
        </section>
    <?php endif; ?>
</body>

</html>