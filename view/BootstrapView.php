<?php

namespace View;

class BootstrapView extends DishListView
{
    const ASSET_FOLDER = 'view/bootstrap-view/';

    private function showUserInfo() {
    ?>
    <div class="container user-info">
        <header class="d-flex flex-wrap align-items-center justify-content-center justify-content-md-between py-3 mb-4 border-bottom">
            <div class="col-md-3 mb-2 mb-md-0">
                <a href="/dish-list" class="d-inline-flex link-body-emphasis text-decoration-none">
                    <span>Hello, <?php echo $_SESSION['user']; ?>!</span>
                </a>
            </div>
            <div class="col-md-3 text-end">
                <div class="btn-group">
                    <?php if ($this->checkRight('user', 'admin')) : ?>
                        <a class="btn btn-outline-secondary" href="?action=admin">Administration</a> 
                    <?php endif; ?>
                        <a class="btn btn-outline-secondary" href="?action=logout">Logout</a>
                </div>
            </div>
        </header>
    </div>
        <!-- <div class="container user-info">
            <div class="row">
                <div class="col align-self-end lead">
                <span>Hello, <?php echo $_SESSION['user']; ?>!</span>
                    <div class="btn-group">
                        <?php if ($this->checkRight('user', 'admin')) : ?>
                            <a class="btn btn-outline-secondary" href="?action=admin">Administration</a> 
                        <?php endif; ?>
                            <a class="btn btn-outline-secondary" href="?action=logout">Logout</a>
                    </div>
                </div>
            </div>
        </div> -->
    <?php
    }

    public function showDishs($dishs) {
    ?>
        <div class="container dish-list">
            <div class="row">
                <form name="dish-form" method="get" class="offset-2 col-8 offset-sm-5-col-sm-6">
                    <div class="mb-3 col-lg-4 offset-md-4">
                        
                        <label for="dish">Страва:</label>
                        <select class="form-select" name="dish" onchange="document.forms['dish-form'].submit();">
                            <option value=""></option>
                            <?php
                            foreach ($dishs as $curdish) {
                                echo "<option " . (($curdish->getId() == $_GET['dish']) ? "selected" : "") .
                                    " value='" . $curdish->getId() . "'>" . $curdish->getNameOfTheDish() . "</option>";
                            }
                            ?>
                        </select>

                    </div>
                    
                    <div class="mb-3">
                        <?php if ($this->checkRight('dish', 'create')) : ?>
                            <div class="col-xs-12 text-center">
                                <a class="btn btn-outline-secondary" href="?action=create-dish">Додати страву</a>
                            </div>
                        <?php endif; ?>
                    </div>
                    
                </form>
            </div>
        </div>
    <?php
    }

    private function showDish(\Model\Dish $dish) {
    ?>
        <div class="container dish-info"> <!-- Ниже убраны: class="nameOfTheDish" class="type" class="portionWeight" -->
            <div class="row text-center">
                <div class="mb-3">
                    <h4 class="fw-light">Назва: <span class="text-body-tertiary"><?php echo $dish->getNameOfTheDish(); ?></span></h4>
                    <h4 class="col-md-offset-1 fw-light">Тип: <span class="text-body-tertiary"><?php echo $dish->getType(); ?></span></h4>
                    <h4 class="fw-light">Вага порції: <span class="text-body-tertiary"><?php echo $dish->getPortionWeight(); ?></span></h4>
                </div>
                <div class="btn-group col-5 mx-auto" class="fs-6">
                    <?php if ($this->checkRight('dish', 'edit')) : ?>
                        <a class="btn btn-outline-secondary" href="?action=edit-dish-form&dish=<?php echo $_GET['dish']; ?>">Редагувати страву</a>
                    <?php endif; ?>
                    <?php if ($this->checkRight('dish', 'delete')) : ?>
                        <a class="btn btn-outline-danger" href="?action=delete-dish&dish=<?php echo $_GET['dish']; ?>">Видалити страву</a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    <?php
    }

    private function showProducts($products) {
        ?>
        <section class="container products">
            <div class="row text-left">
                <?php if ($_GET['dish']) : ?>
                    <?php if ($this->checkRight('product', 'create')) : ?>
                        <div class="control">
                            <a class="btn btn-outline-secondary" href="?action=create-product-form&dish=<?php echo $_GET['dish']; ?>">Додати продукт</a>
                        </div>
                    <?php endif; ?>
                    
            </div>
            <div class="row text-center table-products">
                <table class="table">
                    <thead>
                        <tr class="fw-bolder">
                            <th>№ п.</th>
                            <th>Продукт</th>
                            <th>Вага</th>
                            <th>Калорій</th>
                            <th>Об'єм/Маса</th>
                            <th colspan="2"></th>
                        </tr>
                    </thead>
                    <tbody class="fw-light" class="table-group-divider">
                        <?php if (count($products) > 0) : ?>
                            <?php foreach ($products as $key => $product) : ?>
                                <?php
                                $row_class = '';
                                if ($product->isVolumeWeightLiquid()) {
                                    $row_class = 'bg-info-subtle';
                                }
                                if ($product->isVolumeWeightGram()) {
                                    $row_class = 'bg-light-subtle';
                                }
                                ?>
                                <?php if (!$_POST['productProdFilter'] || mb_stripos(
                                    $product->getProduct(),
                                    $_POST['productProdFilter'],
                                    0,
                                    'UTF-8'
                                ) !== false) : ?>
                                    <tr class="<?php echo $row_class; ?>">
                                        <td><?php echo ($key + 1); ?></td>
                                        <td><?php echo $product->getProduct(); ?></td>
                                        <td><?php echo $product->getWeight(); ?></td>
                                        <td><?php echo $product->getCalories(); ?></td>
                                        <td><?php echo $product->isVolumeWeightLiquid() ? 'мл' : 'грам'; ?></td>
                                        <td>
                                            <div class="btn-group" >
                                                <?php if ($this->checkRight('product', 'edit')) : ?>
                                                    <a class="btn btn-outline-secondary" 
                                                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" 
                                                        href='?action=edit-product-form&dish=<?php echo $_GET['dish']; ?>&file=<?php echo $product->getId(); ?>'>Редагувати</a>
                                                <?php endif; ?>
                                                <?php if ($this->checkRight('product', 'delete')) : ?>
                                                    <a class="btn btn-outline-secondary" 
                                                        style="--bs-btn-padding-y: .25rem; --bs-btn-padding-x: .5rem; --bs-btn-font-size: .75rem;" 
                                                        href='?action=delete-product&dish=<?php echo $_GET['dish']; ?>&file=<?php echo $product->getId(); ?>'>Видалити</a>
                                                <?php endif; ?>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <form name="product-filter" method="post">
                
            <div class="row">
                <div class="col-lg-4 col-md-2">
                    <input class="form-control" type="text" name="productProdFilter" placeholder="Фільтрування по назві продукту" value="<?php echo $_POST['productProdFilter']; ?>">
                </div>
                
                <div class="col-md-6">
                    <input type="submit" value="Фільтрування" class="btn btn-outline-secondary">
                </div>
            </div> <br> <br> <br>
            </form>
        <?php endif; ?>

                                                    

        </section>
    <?php
    }

    public function showMainForm($dishs, \Model\Dish $dish, $products) {
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Список страв</title>
            <meta charset="UTF-8">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSET_FOLDER; ?>css/bootstrap.min.css">
            <script src="<?php echo self::ASSET_FOLDER; ?>js/jquery.min.js"></script>
            <script src="<?php echo self::ASSET_FOLDER; ?>js/bootstrap.min.js"></script>
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSET_FOLDER; ?>css/main.css">
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
        </head>
        <body>
            <header>

            

                <?php
                $this->showUserInfo();
                if ($this->checkRight('dish', 'view')) {
                    $this->showDishs($dishs);
                    if ($_GET['dish']) {
                        $this->showDish($dish);
                    }
                } ?>
            </header>
            <?php
            if ($this->checkRight('product', 'view')) {
                $this->showProducts($products);
            }
            ?>

            <!-- FOOTERS FOOTERS FOOTERS FOOTERS FOOTERS FOOTERS FOOTERS FOOTERS FOOTERS FOOTERS FOOTERS -->
            <div class="container">
            <footer class="d-flex flex-wrap justify-content-between align-items-center py-3 my-4 border-top">
                <div class="col-md-4 d-flex align-items-center">
                <a href="/" class="mb-3 me-2 mb-md-0 text-body-secondary text-decoration-none lh-1">
                    <svg class="bi" width="30" height="24"><use xlink:href="#bootstrap"></use></svg>
                </a>
                <span class="mb-3 mb-md-0 text-body-secondary">© 2023 Dishs, Inc</span>
                </div>
                <ul class="nav col-md-4 justify-content-end list-unstyled d-flex">
                <li class="ms-4"><a class="text-body-secondary" href="#"><i class="bi bi-twitter"></i></a></li>
                <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-instagram"></i></a></li>
                <li class="ms-3"><a class="text-body-secondary" href="#"><i class="bi bi-facebook"></i></a></li>
                </ul>
            </footer>
            </div>
            
        </body>
        </html>
    <?php
    }

    public function showDishEditForm(\Model\Dish $dish) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSET_FOLDER; ?>css/bootstrap.min.css">
            <script src="<?php echo self::ASSET_FOLDER; ?>js/jquery.min.js"></script>
            <script src="<?php echo self::ASSET_FOLDER; ?>js/bootstrap.min.js"></script> 
            <title>Редагування страви</title>
        </head>
        <body>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-2 offset-md-3">
                        <a href="index.php?dish=<?php echo $_GET['dish']; ?>"> На головну</a>
                        <form name='edit-dish' method="post" action="?action=edit-dish&dish=<?php echo $_GET['dish']; ?>">
                            <div class="mb-3">
                                <label for="nameOfTheDish">Назва страви: </label>
                                <input class="form-control" type="text" name="nameOfTheDish" pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" value="<?php echo $dish->getNameOfTheDish(); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="type">Тип: </label>
                                <input class="form-control" type="text" name="type" pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" value="<?php echo $dish->getType(); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="portionWeight">Вага порції: </label>
                                <input class="form-control" type="text" name="portionWeight" placeholder="Example 850" pattern="[0-9]{1,4}" value="<?php echo $dish->getPortionWeight(); ?>" required>
                            </div>
                            <!-- Button -->
                            <div class="container">
                                <div class="row text-center">
                                    <div class="btn-group"  aria-label="Basic outlined example">
                                        <input class="btn btn-outline-primary" type="submit" name="ok" value="Змінити">
                                        <input class="btn btn-outline-primary" type="submit" formaction="/dish-list" name="ok" value="На головну">
                                    </div>
                                </div>
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </body>

        </html>
    <?php
    }

    public function showProductEditForm(\Model\Product $product) {
    ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Редагування продукту</title>
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSET_FOLDER; ?>css/bootstrap.min.css">
            <script src="<?php echo self::ASSET_FOLDER; ?>js/jquery.min.js"></script>
            <script src="<?php echo self::ASSET_FOLDER; ?>js/bootstrap.min.js"></script> 
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSET_FOLDER; ?>css/checkbox.css"> 
        </head>
        <body>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-2 offset-md-3">
                        <form name="edit-product" method="post" action="?action=edit-product&file=<?php echo $_GET['file']; ?>&dish=<?php echo $_GET['dish']; ?>">
                            <div class="mb-3">
                                <label for="products_name">Продукт: </label>
                                <input class="form-control" type="text" name="products_name" pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" value="<?php echo $product->getProduct(); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="products_weight">Вага: </label>
                                <!-- required pattern="\d+" -->
                                <input class="form-control" type="text" name="products_weight" pattern="[0-9]{1,4}" value="<?php echo $product->getWeight(); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="products_calories">Калорій: </label>
                                <input class="form-control" type="text" name="products_calories" pattern="[0-9]{1,5}" value="<?php echo $product->getCalories(); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="products_mass">Об'єм/Маса: </label>
                                <select class="form-control" name="products_mass">
                                    <option <?php echo ($product->isVolumeWeightGram()) ? "selected" : ""; ?> value="грам">грам</option>
                                    <option <?php echo ($product->isVolumeWeightLiquid()) ? "selected" : ""; ?> value="мл">мл</option>
                                </select>
                            </div>

                            <!-- Button -->
                            <div class="container">
                                <div class="row text-center">
                                    <div class="btn-group"  aria-label="Basic outlined example">
                                        <input class="btn btn-outline-primary" type="submit" name="ok" value="Змінити">
                                        <input class="btn btn-outline-primary" type="submit" formaction="/dish-list" name="ok" value="На головну">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </body>

        </html>
    <?php
    }

    public function showProductCreateForm() {
    ?>
        <!DOCTYPE html>
        <html lang="en">

        <head>
            <title>Додавання продукту</title>
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSET_FOLDER; ?>css/bootstrap.min.css">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSET_FOLDER; ?>css/checkbox.css">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSET_FOLDER; ?>css/login.css">
            <script src="<?php echo self::ASSET_FOLDER; ?>js/jquery.min.js"></script>
            <script src="<?php echo self::ASSET_FOLDER; ?>js/bootstrap.min.js"></script>
        </head>

        <body>
            <div class="container">
                <div class="row">
                    <div class="col-lg-6 col-md-2 offset-md-3">

                        <form name="create-product" method="post" action="?action=create-product&dish=<?php echo $_GET['dish']; ?>">
                            <div class="text-field">
                                <div class="mb-3">
                                    <input class="form-control" type="text" name="products_name" pattern="^[а-яА-ЯіІїЇєЄёЁa-zA-Z]+([\s-][а-яА-ЯіІїЇєЄёЁa-zA-Z]+)*$" placeholder="Назва продукту" required>
                                </div>
                                <div class="mb-3">
                                    <input class="form-control" type="text" name="products_weight" pattern="[0-9]{1,4}" placeholder="Вага" required>
                                </div>
                                <div class="mb-3">
                                    <input class="form-control" type="text" name="products_calories" pattern="[0-9]{1,5}" placeholder="Калорії" required>
                                </div>
                                <div class="mb-3">
                                    
                                    <select class="form-control" name="products_mass">
                                        <option value="грам">грам</option>
                                        <option value="мл">мл</option>
                                    </select>
                                </div>
                            </div>
                        </form>
                        <!-- Button -->
                        <div class="container">
                            <div class="row text-center">
                                <div class="btn-group"  aria-label="Basic outlined example">
                                    <input class="btn btn-outline-primary" type="submit" name="ok" value="Змінити">
                                    <input class="btn btn-outline-primary" type="submit" formaction="/dish-list" name="ok" value="На головну">
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </body>

        </html>
    <?php
    }

    public function showLoginForm() {
    ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Authentication</title>
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSET_FOLDER; ?>css/login.css">
            <link rel="stylesheet" type="text/css" href="<?php echo self::ASSET_FOLDER; ?>css/bootstrap.min.css">
            <script src="<?php echo self::ASSET_FOLDER; ?>js/jquery.min.js"></script>
            <script src="<?php echo self::ASSET_FOLDER; ?>js/bootstrap.min.js"></script> 
        </head>
        <body>
            <form method="post" action="?action=checkLogin">
                <div class="container">
                    <div class="row text-center">
                        <div class="col-sm-6 col-md-4 col-lg-3 col-md-3 offset-md-4">
                            <div class="mb-3">                          
                                <input name="username" placeholder="username" class="form-control">
                            </div>
                            <div class="mb-3">
                                <input type="password" name="password" placeholder="password" class="form-control">
                            </div>
                            <button type="submit" class="btn btn-outline-secondary" value="login">login</button>
                        </div>
                    </div>
                </div>
            </form>
        </body>
        </html>
    <?php
    }

    // Не реализованные ниже методы! Только перенесены!

    public function showAdminForm($users) {
        ?>
        <!DOCTYPE html>
        <html lang="en">
        <head>
            <title>Адміністрування</title>
        </head>
        <body>
            <header>
                <a href="index.php">На головну</a>
                <h1>Адміністрування користувачів</h1>
                <link rel="stylesheet" type="text/css" href="css/main-style.css">
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
                                <td><a href="?action=edit-user-form&username=<?php echo $user -> getUserName(); ?>"><?php echo $user -> getUserName(); ?></a></td>
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
            <link rel="stylesheet" type="text/css" href="css/admin.css">
        </head>
        <body>
            <header>
                <a href="?action=admin">До списку користувачів</a>
                <form name="edit-user" method="post" action="?action=edit-user&user=<?php echo $_GET['user']; ?>">
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
