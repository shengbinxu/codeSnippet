<?php
/**
 * Copyright (C) 2017 Baidu, Inc. All Rights Reserved.
 */
/**
 * 绿头鸭 - 一种野鸭
 * Class MallardDuck
 */
class MallardDuck extends Duck{
    public function __construct()
    {
        $this->flyBehavior = new FlyWithWings();
        $this->quackBehavior = new Squeak();
    }

    public function display()
    {
        echo "I'm a reak Mallard duck\n";
        // TODO: Implement display() method.
    }
}