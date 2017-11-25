<?php
/**
 * Copyright (C) 2017 Baidu, Inc. All Rights Reserved.
 */

/**
 * 鸭子叫行为
 * Interface QuackBehavior
 */
interface QuackBehavior{
    public function quack();
}

/**
 * 不会叫-哑巴鸭子
 * Class MuteQuack
 */
class MuteQuack implements QuackBehavior{
    public function quack()
    {
        echo '<< slience >>' . "\n";
        // TODO: Implement quack() method.
    }
}

/**
 *吱吱叫
 */
class Squeak implements QuackBehavior{
    public function quack()
    {
        echo 'Squeak' . "\r\n";
        // TODO: Implement quack() method.
    }
}