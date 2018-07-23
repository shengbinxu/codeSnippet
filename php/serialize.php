<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2017/8/1
 * Time: 17:21
 */
//注意这里必须加载对象A的类定义文件。
include './classA.php';
$s = file_get_contents('/tmp/store');
$connection = unserialize($s);

// 现在可以使用对象$a里面的函数 show_one()
$users = $connection->getUser();
print_r($users);