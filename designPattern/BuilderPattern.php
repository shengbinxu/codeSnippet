<?php
/**
 * Copyright (C) 2018 Baidu, Inc. All Rights Reserved.
 */
/**
 *
 * Builder Pattern  建造着模式、或者 生成器模式
 * http://blog.csdn.net/carson_ho/article/details/54910597
 * https://zh.wikipedia.org/wiki/%E7%94%9F%E6%88%90%E5%99%A8%E6%A8%A1%E5%BC%8F
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2018/2/2
 * Time: 9:28
 */


/**
 * 建造者
 * Class Builder
 */
interface Builder
{
    public function buildCPU();

    public function buildScreen();

    public function buildCamera();

}

/**
 * 指挥者
 * Class Director
 */
class Director
{
    private $buider;

    public function __construct(Builder $builder)
    {
        $this->buider = $builder;
    }

    public function getProduct()
    {
        $this->buider->buildCamera();
        $this->buider->buildCPU();
        $this->buider->buildScreen();
    }

}

class  XiaomiBuilder implements  Builder
{
    public function buildCPU()
    {
        echo "cpu is gaotong\n";
        // TODO: Implement buildCPU() method.
    }

    public function buildScreen()
    {
        echo "screen is sanxing\n";
        // TODO: Implement buildScreen() method.
    }

    public function buildCamera()
    {
        echo "camera is sony\n";
        // TODO: Implement buildCamera() method.
    }

    public function getProduct()
    {
        // TODO: Implement getProduct() method.
    }
}

$director = new Director(new XiaomiBuilder());
$director->getProduct();
