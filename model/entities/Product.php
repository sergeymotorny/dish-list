<?php 

namespace Model;

class Product {
    const LIQUID = 0;
    const GRAM = 1;


    private $id;
    private $product;
    private $weight;
    private $calories;
    private $volumeWeight;
    private $dishId;

    public function getId() {
        return $this -> id;
    }
    public function setId($id) {
        $this -> id = $id;
        return $this;   
    }

    public function getProduct() {
        return $this -> product;
    }
    public function setProduct($product) {
        $this -> product = $product;
        return $this;   
    }

    public function getWeight() {
        return $this -> weight;
    }
    public function setWeight($weight) {
        $this -> weight = $weight;
        return $this;   
    }

    public function getCalories() {
        return $this -> calories;
    }
    public function setCalories($calories) {
        $this -> calories = $calories;
        return $this;   
    }

    public function isVolumeWeightLiquid() {
        return ($this -> volumeWeight == self::LIQUID);
    }
    public function isVolumeWeightGram() {
        return !($this -> isVolumeWeightLiquid()); 
    }

    public function setLiquidVolumeWeight() {
        $this -> volumeWeight = SELF::LIQUID;
        return $this;
    }
    public function setGramVolumeWeight() {
        $this -> volumeWeight = SELF::GRAM;
        return $this;
    }


    public function getDishId() {
        return $this -> dishId;
    }
    public function setDishId($dishId) {
        $this -> dishId = $dishId;
        return $this;   
    }

}