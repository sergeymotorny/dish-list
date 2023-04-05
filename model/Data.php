<?php 

namespace Model;

abstract class Data {
    const FILE = 0;

    private $error;
    private $user;

    public function getCurrentUser() {
        return $this -> user;
    }

    public function setCurrentUser($userName) {
        $this -> user = $this -> readUser($userName);
    }

    public function checkRight($object, $right) {
        return $this -> user -> checkRight($object, $right);
    }

    public function readProducts($dishId) {
        if($this -> user -> checkRight('product', 'view')) {
            $this -> error = "";
            return $this -> getProducts($dishId);
        } else {
            $this -> error = "You have no permissions to view products";
            return false;
        }
    }
    protected abstract function getProducts($dishId);

    public function readProduct($dishId, $id) {
        if($this -> checkRight('product', 'view')) {
            $this -> error = "";
            return $this -> getProduct($dishId, $id);
        } else {
            $this -> error = "You have no permissions to view product";
            return false;
        }
    }
    protected abstract function getProduct($dishId, $id);


    public function readDishs() {
        if($this -> checkRight('dish', 'view')) {
            $this -> error = "";
            return $this -> getDishs();
        } else {
            $this -> error = "You have no permissions to view dishs";
            return false;
        }
    }
    protected abstract function getDishs();

    public function readDish($id) {
        if($this -> checkRight('dish', 'view')) {
            $this -> error = "";
            return $this -> getDish($id);
        } else {
            $this -> error = "You have no permissions to view dish";
            return false;
        }
    }
    protected abstract function getDish($id);

    
    public function readUsers() {
        if($this -> checkRight('user', 'admin')) {
            $this -> error = "";
            return $this -> getUsers();
        } else {
            $this -> error = "You have no permissions to administrate users";
            return false;
        }
    }
    protected abstract function getUsers();

    public function readUser($id) {
        $this -> error = "";
        return $this -> getUser($id);
    }
    protected abstract function getUser($id);


    public function writeProduct(Product $product) {
        if($this -> checkRight('product', 'edit')) {
            $this -> error = "";
            $this -> setProduct($product);
            return true;
        } else {
            $this -> error = "You have no permissions to edit products";
            return false;
        } 
    }
    protected abstract function setProduct(Product $product);

    public function writeDish(Dish $dish) {
        if($this -> checkRight('dish', 'edit')) {
            $this -> error = "";
            $this -> setDish($dish);
            return true;
        } else {
            $this -> error = "You have no permissions to edit dishs";
            return false;
        }
    }
    protected abstract function setDish(Dish $dish);


    public function writeUser(User $user) {
        if($this -> checkRight('user', 'admin')) {
            $this -> error = "";
            $this -> setUser($user);
            return true;
        } else {
            $this -> error = "You have no permissions to administrate users";
            return false;
        }
    }
    protected abstract function setUser(User $user);

    public function removeProduct(Product $product) {
        if($this -> checkRight('product', 'delete')) {
            $this -> error = "";
            $this -> delProduct($product);
            return true;
        } else {
            $this -> error = "You have no permissions to delete products";
            return false;
        }
    }
    protected abstract function delProduct(Product $product);


    public function addProduct(Product $product) {
        if($this -> checkRight('product', 'create')) {
            $this -> error = "";
            $this -> insProduct($product);
            return true;
        } else {
            $this -> error = "You have no permissions to create products";
            return false;
        }
    }
    protected abstract function insProduct(Product $product);

    public function removeDish($dishId) {
        if($this -> checkRight('dish', 'delete')) {
            $this -> error = "";
            $this -> delDish($dishId);
            return true;
        } else {
            $this -> error = "You have no permissions to delete dishs";
            return false;
        }
    }
    protected abstract function delDish($dishId);


    public function addDish() {
        if($this -> checkRight('dish', 'create')) {
            $this -> error = "";
            $this -> insDish();
            return true;
        } else {
            $this -> error = "You have no permissions to create dishs";
            return false;
        }
    }
    protected abstract function insDish();


    public function getError() {
        if($this -> error) {
            return $this -> error;
        }
        return false;
    }

    public static function makeModel($type) {
        if($type == self::FILE) {
            return new FileData();
        }
        return new FileData();
    }



}

