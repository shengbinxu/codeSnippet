<?php
/**
 * Copyright (C) 2017 Baidu, Inc. All Rights Reserved.
 */
include 'Duck.php';
include 'FlyBehavior.php';
include 'QuackBehavior.php';
include 'MallardDuck.php';
include 'ModelDuck.php';

$mallardDuck = new MallardDuck();
$mallardDuck->display();
$mallardDuck->performFly();
$mallardDuck->performQuack();

$modelDuck = new ModelDuck();
$modelDuck->display();
$modelDuck->performFly();
$modelDuck->setFlyBehavior(new FlyWithWings());
$modelDuck->performFly();