<?php 

namespace Model;

class Dish {
    private $id;
    private $nameOfTheDish;
    private $type;
    private $portionWeight;

    public function getId() {
        return $this -> id;
    }
    public function setId($id) {
        $this -> id = $id;
        return $this;   
    }

    public function getNameOfTheDish() {
        return $this -> nameOfTheDish;
    }
    public function setNameOfTheDish($nameOfTheDish) {
        $this -> nameOfTheDish = $nameOfTheDish;
        return $this;   
    }

    public function getType() {
        return $this -> type;
    }
    public function setType($type) {
        $this -> type = $type;
        return $this;   
    }

    public function getPortionWeight() {
        return $this -> portionWeight;
    }
    public function setPortionWeight($portionWeight) {
        $this -> portionWeight = $portionWeight;
        return $this;   
    }

}