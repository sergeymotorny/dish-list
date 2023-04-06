<?php 

namespace View;

class MyView extends DishListView {
    private function showDishs($dishs) {
        ?>
        <form name="dish-form" method="get">
            <label for="dish">Блюдо</label>
            <select name="dish">
                <option value=""></option>
                <?php
                foreach ($dishs as $curdish) {
                    echo "<option " . (($curdish->getId() == $_GET['dish']) ? "selected" : "") .
                        " value='" . $curdish->getId() . "'>" . $curdish->getNameOfTheDish() . "</option>";
                    }
                    ?>
            </select>
            <input type="submit" value="ok">
            <?php if ($this -> checkRight('dish', 'create')) : ?>
                <a href="forms/create-dish.php">Додати блюдо</a>
            <?php endif; ?>
        </form>
        <?php
    }
    private function showDish(\Model\Dish $dish) {
        ?>
        <h3>Назва: <span class="nameOfTheDish"><?php echo $dish ->getNameOfTheDish(); ?></span></h3>
        <h3>Тип: <span class="type"><?php echo $dish ->getType(); ?></span></h3>
        <h3>Вага порції: <span class="portionWeight"><?php echo $dish ->getPortionWeight(); ?></span></h3>
        <div class="control">
            <?php if ($this -> checkRight('dish', 'edit')) : ?>
                <a href="forms/edit-dish.php?dish=<?php echo $_GET['dish']; ?>">Edit dish</a>
            <?php endif; ?>
            <?php if ($this -> checkRight('dish', 'delete')) : ?>
                <a href="forms/delete-dish.php?dish=<?php echo $_GET['dish']; ?>">Delete dish</a>
             <?php endif; ?>
        </div>
        <?php
    }
    private function showProducts($products) {
        ?>
        <section>
            <?php if ($_GET['dish']) : ?>
                <?php if ($this -> checkRight('product', 'create')) : ?>
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
                        <?php if (count($products) > 0) : ?>
                            <?php foreach ($products as $key => $product) : ?>
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
                                            <?php if ($this->checkRight('product', 'edit')) : ?>
                                                <a href='forms/edit-product.php?dish=<?php echo $_GET['dish']; ?>&file=<?php echo
                                                        $product->getId(); ?>'>Редагувати</a>
                                            <?php endif; ?>
                                            <?php if ($this->checkRight('product', 'delete')) : ?>
                                                <a href='forms/delete-product.php?dish=<?php echo $_GET['dish']; ?>&file=<?php echo
                                                         $product->getId(); ?>'>Видалити</a>
                                            <?php endif; ?>
                                        </td>
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
        <?php
    }
    public function showMainForm($dishs, \Model\Dish $dish, $products) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Dish-List</title>
            <link rel="stylesheet" type="text/css" href="css/main-style.css">
            <link rel="stylesheet" href="css/liquid-style.css">
            <link rel="stylesheet" href="css/dish-choose-style.css">
        </head>
        <body>
            <header>
                <div class="user-info">
                    <span>Hello <?php echo $_SESSION['user']; ?>!</span>
                    <?php if ($this -> checkRight('user', 'admin')) : ?>
                        <a href="admin/index.php">Адміністрування</a>
                    <?php endif; ?>
                    <a href="auth/logout.php">Logout</a>
                </div>
            <?php
            if($this -> checkRight('dish', 'view')) {
                $this -> showDishs($dishs);
                if ($_GET['dish']) {
                    $this -> showDish($dish);
                }
            }


            ?>
        </header>
        <?php
        if($this -> checkRight('product', 'view')) {
            $this -> showProducts($products);
        }
        ?>
        </body>
        </html>
        <?php
    }

    public function showDishEditForm(\Model\Dish $dish) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <link rel="stylesheet" type="text/css" href="../css/edit-dish-style.css">
            <title>Редагування інформації</title>
        </head>
        <body>
            <a href="../index.php?dish=<?php echo $_GET['dish']; ?>"> На головну</a>
            <form name='edit-dish' method="post">
                <div>
                    <label for="nameOfTheDish">Назва страви: </label>
                    <input type="text" name="nameOfTheDish" pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" 
                            value="<?php echo $dish -> getNameOfTheDish(); ?>" required>
                </div>
                <div>
                    <label for="type">Тип: </label>
                    <input type="text" name="type" pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" 
                            value="<?php echo $dish -> getType(); ?>" required>
                </div>
                <div>
                    <label for="portionWeight">Вага порції: </label>
                    <input type="text" name="portionWeight" placeholder="Example 850" pattern="[0-9]{1,4}" 
                            value="<?php echo $dish -> getPortionWeight(); ?>" required>
                </div>
                <br>
                <div class="btn-group">
                    <input class="button" type="submit" name="ok" value="Змінити"> 
                    <input class="button" type="submit" formaction="../index.php" name="ok" value="На головну"> 
                </div>
            </form>
        </body>
        </html>
        <?php
    }
    
    public function showProductEditForm(\Model\Product $product) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <link rel="stylesheet" href="../css/edit-prod-style.css">
            <title>Редагування продукту</title>
        </head>
        <body>
            <?php
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
        <?php
    }

    public function showProductCreateForm() {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <link rel="stylesheet" href="../css/edit-prod-style.css">
            <title>Create product</title>
        </head>
        <body>
            <form name="create-product" method="post">
                <div class="text-field">
                    <div>
                        <label class="text-field__label" for="products_name">Продукт: </label>
                        <input class="text-field__input" type="text" name="products_name" 
                            pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" placeholder="Молоко, М'ясо" required>
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
                </div>  <br>
                <div class="btn-group">
                    <input class="button" type="submit" name="ok" value="Додати">
                    <input class="button" type="submit" formaction="../index.php" name="ok" value="На головну">
                </div>
            </form>
        </body>
        </html>
        <?php
    }

    public function showLoginForm() {
        ?>
        <!DOCTYPE html>
    <html lang="en">
    <head>
        <link rel="stylesheet" href="../css/login-style.css">
        <title>Authentication</title>
    </head>
    <body>
        <form method="post">
            <p>
                <input type="text" name="username" placeholder="username">
            </p>
            <p>
                <input type="password" name="password" placeholder="password">
            </p>
            <p>
                <input type="submit" value="login">
            </p>
        </form>
    </body>
    </html>
    <?php
    }

    public function showAdminForm($users) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Адміністрування</title>
        </head>
        <body>
            <header>
                <a href="../index.php">На головну</a>
                <h1>Адміністрування користувачів</h1>
                <link rel="stylesheet" type="text/css" href="../css/main-style.css">
            </header>
            <section>
                <table>
                    <thead>
                        <tr>
                            <th>Користувач</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach($users as $user):?>
                            <?php if($user ->getUserName() !=$_SESSION['user'] && $user -> getUserName() 
                                != 'admin' && trim($user -> getUserName()) != ''): ?>
                            <tr>
                                <td><a href="edit-user.php?username=<?php echo $user -> getUserName(); ?>"><?php echo $user -> getUserName(); ?></a></td>
                            </tr>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </section>
        </body>
        </html>
        <?php
    }

    public function showUserEditForm(\Model\User $user) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Редагування користувача</title>
            <link rel="stylesheet" type="text/css" href="admin.css">
        </head>
        <body>
            <header>
                <a href="index.php">До списку користувачів</a>
                <form name="edit-user" method="POST">
                    <div class="tbl">
                        <div>
                            <label for="user_name">Username: </label>
                            <input readonly type="text" name="user_name" value="<?php echo $user -> getUserName(); ?>">
                        </div>
                        <div>
                        <label for="user_pwd">Password: </label>
                            <input type="text" name="user_pwd" value="<?php echo $user ->getPassword(); ?>">
                        </div>
                    </div>

                    <div><p>Блюдо:</p>
                        <input type="checkbox" <?php echo ("1" == $user -> getRight(0))?"checked":""; ?> name="right0" 
                        value="1"><span>перегляд</span>
                        <input type="checkbox" <?php echo ("1" == $user -> getRight(1))?"checked":""; ?> name="right1" 
                        value="1"><span>створення</span>
                        <input type="checkbox" <?php echo ("1" == $user -> getRight(2))?"checked":""; ?> name="right2" 
                        value="1"><span>редагування</span>
                        <input type="checkbox" <?php echo ("1" == $user -> getRight(3))?"checked":""; ?> name="right3" 
                        value="1"><span>видалення</span>
                    </div>

                    <div><p>Продукти:</p>
                        <input type="checkbox" <?php echo ("1" == $user -> getRight(4))?"checked":""; ?> name="right4" 
                        value="1"><span>перегляд</span>
                        <input type="checkbox" <?php echo ("1" == $user -> getRight(5))?"checked":""; ?> name="right5" 
                        value="1"><span>створення</span>
                        <input type="checkbox" <?php echo ("1" == $user -> getRight(6))?"checked":""; ?> name="right6" 
                        value="1"><span>редагування</span>
                        <input type="checkbox" <?php echo ("1" == $user -> getRight(7))?"checked":""; ?> name="right7" 
                        value="1"><span>видалення</span>
                    </div>

                    <div><p>Користувачі:</p>
                        <input type="checkbox" <?php echo ("1" == $user -> getRight(8))?"checked":""; ?> name="right8" 
                        value="1"><span>адміністрування</span>
                    </div>
                    <div> <input type="submit" name="ok" value="змінити"></div>
                </form>
        </body>
        </html>
        <?php
    }
}