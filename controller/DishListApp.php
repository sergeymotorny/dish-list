<?php 

namespace Controller;

use Model\Data;
use View\DishListView;

class DishListApp {
    private $model;
    private $view;

    public function __construct($modelType, $viewType) {
        session_start();
        $this -> model = Data::makeModel($modelType);
        $this -> view = DishListView::makeView($viewType);
    }

    public function checkAuth() {
        if ($_SESSION['user']) {
            $this -> model -> setCurrentUser($_SESSION['user']);
            $this -> view -> setCurrentUser($this -> model -> getCurrentUser());
        } else {
            header('Location: ?action=login');
        }
    }

    public function run() {
        if (!in_array($_GET['action'], array('login', 'checkLogin'))) {
            $this -> checkAuth();
        }
        if ($_GET['action']) {
            switch ($_GET['action']) {
                case 'login':
                    $this -> showLoginForm();
                    break;
                case 'checkLogin':
                    $this -> checkLogin();
                    break;
                case 'logout':
                    $this -> logout();
                    break;
                case 'create-dish':
                    $this -> createDish();
                    break;
                case 'edit-dish-form':
                    $this -> showEditDishForm();
                    break;
                case 'edit-dish':
                    $this -> editDish();
                    break;
                case 'delete-dish':
                    $this -> deleteDish();
                    break;
                case 'create-product-form':
                    $this -> showCreateProductForm();
                    break;
                case 'create-product':
                    $this -> createProduct();
                    break;
                case 'edit-product-form':
                    $this -> showEditProductForm();
                    break;
                case 'edit-product':
                    $this -> editProduct();
                    break;
                case 'delete-product':
                    $this -> deleteProduct();
                    break;
                case 'admin':
                    $this -> adminUsers();
                    break;
                case 'edit-user-form':
                    $this -> showEditUserForm();
                    break;
                case 'edit-user':
                    $this -> editUser();
                    break;
                default:
                    $this -> showMainForm();
            }
        } else {
            $this -> showMainForm();
        }
    }

    private function showLoginForm() {
        $this -> view -> showLoginForm();
    }

    private function checkLogin() {
        if ($user = $this -> model -> readUser($_POST['username'])) {
            if ($user -> checkPassWord($_POST['password'])) {
                session_start();
                $_SESSION['user'] = $user -> getUserName();
                header('Location: index.php');
            }
        }
    }

    private function logout() {
        unset($_SESSION['user']);
        header('Location: ?action=login');
    }

    private function showMainForm() {
        $dishs = array();
        if ($this -> model -> checkRight('dish', 'view')) {
            $dishs = $this -> model -> readDishs();
        }

        $dish = new \Model\Dish();
        if ($_GET['dish'] && $this -> model -> checkRight('dish', 'view')) {
            $dish = $this -> model -> readDish($_GET['dish']);
        }

        $products = array();
        if ($_GET['dish'] && $this -> model -> checkRight('product', 'view')) {
            $products = $this -> model -> readProducts($_GET['dish']);
        }
        $this -> view -> showMainForm($dishs, $dish, $products);
    }

    private function createDish() {
        if (!$this -> model -> addDish()) {
            die($this -> model -> getError());
        } else {
            header('Location: index.php');
        }
    }

    private function showEditDishForm() {
        if (!$dish = $this -> model -> readDish($_GET['dish'])) {
            die($this -> model -> getError());
        }
        $this -> view -> showDishEditForm($dish);
    }

    private function editDish() {
        if(!$this -> model -> writeDish((new \Model\Dish())
            -> setId($_GET['dish'])
            -> setNameOfTheDish($_POST['nameOfTheDish'])
            -> setType($_POST['type'])
            -> setPortionWeight($_POST['portionWeight'])
        )) {
            die($this -> model -> getError());
        } else {
            header('Location: index.php?dish=' . $_GET['dish']);
        } 
    }

    private function deleteDish() {
        if(!$this -> model -> removeDish($_GET['dish'])) {
            die($this -> model -> getError());
        } else {
            header('Location: index.php');
        }
    }

    private function showEditProductForm() {
        $product = $this -> model -> readProduct($_GET['dish'], $_GET['file']);
        $this -> view -> showProductEditForm($product);
    }

    private function editProduct() {
        $product = (new \Model\Product())
            -> setId($_GET['file'])
            -> setDishId($_GET['dish'])
            -> setProduct($_POST['products_name'])
            -> setWeight($_POST['products_weight'])
            -> setCalories($_POST['products_calories'])
            -> setLiquidVolumeWeight();
        if ($_POST['products_mass'] == 'мл') {
            $product -> setGramVolumeWeight();
        }
        if (!$this -> model -> writeProduct($product)) {
            die($this -> model -> getError());
        } else {
            header('Location: index.php?dish=' . $_GET['dish']); 
            // Выше можно заменить в ссылке на product= и ['product'], в случае ошибок.
        }
    }

    private function showCreateProductForm() {
        $this -> view -> showProductCreateForm();
    }

    private function createProduct() {
        $product = (new \Model\Product())
            -> setDishId($_GET['dish'])
            -> setProduct($_POST['products_name'])
            -> setWeight($_POST['products_weight'])
            -> setCalories($_POST['products_calories'])
            -> setLiquidVolumeWeight();
        if($_POST['products_mass'] == 'мл') {
            $product -> setGramVolumeWeight();
        }
        if(!$this -> model -> addProduct($product)) {
            die($this -> model -> getError());
        } else {
            header('Location: index.php?dish=' . $_GET['dish']); 
            // Выше можно заменить в ссылке на product= и ['product'], в случае ошибок.
        }
    }

    private function deleteProduct() {
        $product = (new \Model\Product()) -> setId($_GET['file']) -> setDishId($_GET['dish']);
        if(!$this -> model -> removeProduct($product)) {
            die($this -> model -> getError());
        } else {
            header('Location: index.php?dish=' . $_GET['dish']);
        }
    }

    private function adminUsers() {
        $users = $this -> model -> readUsers();
        $this -> view -> showAdminForm($users);
    }

    private function showEditUserForm() {
        if(!$user = $this -> model -> readUser($_GET['username'])) {
            die($this -> model -> getError());
        }
        $this -> view -> showUserEditForm($user);
    }

    private function editUser() {
        $rights = "";
        for($i = 0; $i < 9; $i++) {
            if($_POST['right' . $i]) {
                $rights .= "1";
            } else {
                $rights .= "0";
            }
        }
        $user = (new \Model\User())
            -> setUserName($_POST['user_name'])
            -> setPassword($_POST['user_pwd'])
            -> setRights($rights);
        if(!$this -> model -> writeUser($user)) {
            die($this -> model -> getError());
        } else {
            header('Location: ?action=admin ');
        }
    }
}