<?php
/**
 * Copyright (C) 2017 Baidu, Inc. All Rights Reserved.
 */

/**
 * 鸭子父类
 * Class Duck
 * @property FlyBehavior $flyBehavior;
 * @property QuackBehavior $quackBehavior;
 */
abstract class Duck
{
    public $flyBehavior;//飞行行为
    public $quackBehavior;//叫行为
    public abstract function display();
    public  function swim(){
      echo 'All ducks float, even decoys!';
    }
    public function setFlyBehavior( FlyBehavior $behavior){
        $this->flyBehavior =  $behavior;
    }
    public function setQuackBehavior(QuackBehavior $behavior) {
        $this->quackBehavior = $behavior;
    }
    public function performFly(){
        $this->flyBehavior->fly();
    }
    public function performQuack(){
        $this->quackBehavior->quack();
    }
}