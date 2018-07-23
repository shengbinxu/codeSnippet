<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2018/4/2
 * Time: 13:19
 */

function merge_sort($arr)
{
    echo 'params:' . "\r\n";
    print_r($arr);
    $len = count($arr);
    if ($len <= 1)
        return $arr;
    $half = ($len >> 1) + ($len & 1);
    $arr2d = array_chunk($arr, $half);
    echo  "递归" . "\r\n";
    $left = merge_sort($arr2d[0]);
    echo 'left' . "\r\n";
    print_r($left);
    echo 'left over' . "\r\n";
    $right = merge_sort($arr2d[1]);
    echo 'right' . "\r\n";
    print_r($right);
    echo 'right over' . "\r\n";
    while (count($left) && count($right))
        if ($left[0] < $right[0])
            $reg[] = array_shift($left);
        else
            $reg[] = array_shift($right);
    return array_merge($reg, $left, $right);
}

$arr = array(21, 34, 3, 32, 82, 55, 89, 50, 37, 5, 64, 35, 9, 70);
$arr = merge_sort($arr);
for ($i = 0; $i < count($arr); $i++) {
    echo $arr[$i] . ' ';
}
