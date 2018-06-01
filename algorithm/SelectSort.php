<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2018/4/3
 * Time: 9:03
 */

function selectSort($a)
{
    $len = count($a);
    for ($i = 0; $i < $len - 1; $i++) {
        for ($j = $i + 1; $j < $len; $j++) {
            if ($a[$i] > $a[$j]) {
                exch($a[$i], $a[$j]);
            }
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

//$a = range(0, 10000);
//shuffle($a);
//print_r(selectSort($a));