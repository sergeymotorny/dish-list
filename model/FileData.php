<?php 

namespace Model;

class FileData extends Data {
    const DATA_PATH = __DIR__ . '/../data/';
    const PRODUCT_FILE_TEMPLATE = '/^product-\d\d.txt\z/';
    const DISH_FILE_TEMPLATE = '/^dish-\d\d\z/';

    protected function getProducts($dishId) {
        $Products = array();
        $conts = scandir(self::DATA_PATH . $dishId);
        foreach($conts as $node) {
            if(preg_match(self::PRODUCT_FILE_TEMPLATE, $node)) {
                $Products[] = $this -> getProduct($dishId, $node);
            }
        }
        return $Products;
    }

    protected function getProduct($dishId, $id) {
        $f = fopen(self::DATA_PATH . $dishId . "/" . $id, "r");
        $rowStr = fgets($f);
        $rowArr = explode(";", $rowStr);
        $Product = (new Product())
            ->setId($id)
            ->setProduct($rowArr[0])
            ->setWeight($rowArr[1])
            ->setCalories($rowArr[2]);
        if($rowArr[3] == 'мл') {
            $Product -> setLiquidVolumeWeight();
        } else {
            $Product -> setGramVolumeWeight();
        }
        fclose($f);
        return $Product;
    }


    protected function getDishs() {
        $dishs = array();
        $conts = scandir(self::DATA_PATH);
        foreach($conts as $node) {
            if(preg_match(self::DISH_FILE_TEMPLATE, $node)) {
                $dishs[] = $this -> getDish($node);
            }
        }
        return $dishs;
    }

    protected function getDish($id) {
        $f = fopen(self::DATA_PATH . $id . "/dish.txt", "r");
        $rowStr = fgets($f);
        $rowArr = explode(";", $rowStr);
        fclose($f);
        $dish = (new Dish())
            ->setId($id)
            ->setNameOfTheDish($rowArr[0])
            ->setType($rowArr[1])
            ->setPortionWeight($rowArr[2]);
        return $dish;
    }


    protected function getUsers() {
        $users = array();
        $f = fopen(self::DATA_PATH . "users.txt", "r");
        while(!feof($f)) {
            $rowStr = fgets($f);
            $rowArr = explode(";", $rowStr);
            if(count($rowArr) == 3) {
                $user = (new User())
                    ->setUserName($rowArr[0])
                    ->setPassword($rowArr[1])
                    ->setRights(substr($rowArr[2], 0, 9));
                $users[] = $user;
            }
        }
        fclose($f);
        return $users;
    }

    protected function getUser($id) {
        $users = $this -> getUsers();
        foreach($users as $user) {
            if($user -> getUserName() == $id) {
                return $user;
            }
        }
        return false;
    }

    protected function setProduct(Product $product) {
        $f = fopen(self::DATA_PATH . $product -> getDishId() . "/" . $product -> getId(), "w");
        $volumeWeight = 'мл';
        if($product -> isVolumeWeightLiquid()) {
            $volumeWeight = "грам";
        }
        
        $grArr = array($product -> getProduct(), $product -> getWeight(), $product -> getCalories(), $volumeWeight, );
        $grStr = implode(";", $grArr);
        fwrite($f, $grStr);
        fclose($f);
    }
    protected function delProduct(Product $product) {
        unlink(self::DATA_PATH . $product -> getDishId() . "/" . $product -> getId());
    }


    protected function insProduct(Product $product) {
        //Определение последнего файла продукта в блюде.
        $path = self::DATA_PATH . $product -> getDishId();
        $conts = scandir($path);
        $i = 0;
        foreach($conts as $node) {
            if(preg_match(self::PRODUCT_FILE_TEMPLATE, $node)) {
                $last_file = $node;
            }
        }

        //Получаем index последнего файла и увеличиваем его на +1.
        $file_index = (String)(((int)substr($last_file, -6, 2)) + 1);
        if(strlen($file_index) == 1) {
            $file_index = "0" . $file_index;
        }

        //Формируем имя нового файла.
        $newFileName = "product-" . $file_index . ".txt";

        $product -> setId($newFileName);
        $this -> setProduct($product);
    }

    protected function setDish(Dish $dish) {
        $f = fopen(self::DATA_PATH . $dish -> getId() . "/dish.txt", "w");
        $grArr = array($dish -> getNameOfTheDish(), $dish -> getType(), $dish -> getPortionWeight(), );
        $grStr = implode(";", $grArr);
        fwrite($f, $grStr);
        fclose($f);
    }


    protected function setUser(User $user) {
        $users = $this -> getUsers();
        $found = false;
        foreach($users as $key => $oneUser) {
            if($user -> getUserName() == $oneUser -> getUserName()) {
                $found = true;
                break;
            }
        }
        if($found) {
            $users[$key] = $user;
            $f = fopen(self::DATA_PATH . "users.txt", "w");
            foreach($users as $oneUser) {
                $grArr = array($oneUser -> getUserName(), $oneUser -> getPassword(), $oneUser -> getRights() . "\r\n", );
                $grStr = implode(";", $grArr);
                fwrite($f, $grStr);
            }
            fclose($f);
        }
    }

    protected function delDish($dishId) {
        $dirName = self::DATA_PATH . $dishId;
        $conts = scandir($dirName);
        $i = 0;
        foreach($conts as $node) {
            @unlink($dirName . "/" . $node);
        }
        @rmdir($dirName);
    }

    protected function insDish() {
        //Определяем последнюю папку блюда
        $path = self::DATA_PATH;
        $conts = scandir($path);
        foreach($conts as $node) {
            if(preg_match(self::DISH_FILE_TEMPLATE, $node)) {
                $last_dish = $node;
            }
        }

        //Получаем индекс последней папки и увеличиваем на +1
        $dish_index = (String)(((int)substr($last_dish, -1, 2)) +1);
        if(strlen($dish_index) == 1) {
            $dish_index = "0" . $dish_index;
        }

        //Формируем имя новой папки
        $newDishName = "dish-" . $dish_index;

        mkdir($path . $newDishName);
        $f = fopen($path . $newDishName . "/dish.txt", "w");
        fwrite($f, "New; ;");
        fclose($f);
    
    }
}