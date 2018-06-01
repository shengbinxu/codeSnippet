<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2018/4/2
 * Time: PM9:34
 */

/**
 * 3 5 8 2 4
 * 插入排序
 * @param $a
 */
function insertSort($a)
{
    $len = count($a);
    for ($i = 1; $i < $len; $i++) {
        for ($j = $i - 1; $j >= 0 && $a[$i] < $a[$j]; $j--) {
            exch($a[$i], $a[$j]);
        }
    }
    return $a;
}

if (!function_exists('exch')) {
    function exch(&$a, &$b)
    {
        $temp = $a;
        $a = $b;
        $b = $temp;
    }
}

