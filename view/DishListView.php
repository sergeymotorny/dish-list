<?php 

namespace View;

abstract class DishListView {
    const SIMPLEVIEW = 0;
    private $user;

    public function setCurrentUser(\Model\User $user) {
        $this -> user = $user;
    }
    public function checkRight($object, $right) {
        return $this -> user -> checkRight($object, $right);
    }

    public abstract function showMainForm($dishs, \Model\Dish $dish, $products);
    public abstract function showDishEditForm(\Model\Dish $dish);
    public abstract function showProductEditForm(\Model\Product $product);
    public abstract function showProductCreateForm();
    public abstract function showLoginForm();
    public abstract function showAdminForm($users);
    public abstract function showUserEditForm(\Model\User $user);

    public static function makeView($type) {
        if($type == self::SIMPLEVIEW) {
            return new MyView();
        }
        return new MyView();
    }
}


?>