<?php
/**
 * Copyright (C) 2017 Baidu, Inc. All Rights Reserved.
 */
/**
 *模型鸭-- 一个模型，不会飞
 */
class ModelDuck extends Duck{
    public function __construct()
    {
        $this->flyBehavior = new FlyNoWay();
        $this->quackBehavior = new MuteQuack();
    }
    public function display()
    {
        echo "I'm a model duck\n";
        // TODO: Implement display() method.
    }
}