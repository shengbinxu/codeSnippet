<?php
/**
 * Copyright (C) 2017 Baidu, Inc. All Rights Reserved.
 */

/**
 * 鸭子飞行行为
 * Interface FlyBehavior
 */
interface FlyBehavior{
    public function fly();
}

class FlyWithWings implements FlyBehavior{
    public function fly()
    {
        echo "I'm flying!\n";
    }
}

class FlyNoWay implements FlyBehavior{
    public function fly()
    {
        echo "I can't fly\n";
        // TODO: Implement fly() method.
    }
}