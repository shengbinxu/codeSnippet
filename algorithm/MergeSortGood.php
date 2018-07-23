<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2018/4/3
 * Time: 11:05
 */

//global $temp;

function merge(&$a, $lo, $mid, $hi)
{
    $i = $lo;
    $j = $mid + 1;
//    global $temp;
    for ($k = $lo; $k <= $hi; $k++) {
        $temp[$k] = $a[$k];
    }
    for ($k = $lo; $k <= $hi; $k++) {
        if ($i > $mid) {
            $a[$k] = $temp[$j++];
        } else if ($j > $hi) {
            $a[$k] = $temp[$i++];
        } else if ($temp[$i] > $temp[$j]) {
            $a[$k] = $temp[$j++];
        } else {
            $a[$k] = $temp[$i++];
        }
    }
}

//print_r(merge([1, 4, 5,6], [2,4,6,7]));

function mergeSort(&$a, $lo, $hi)
{
    $length = $hi - $lo;
    if ($hi <= $lo) {
        return '';
    }
    $mid = intval($length / 2) + $lo;
    mergeSort($a, $lo, $mid);
    mergeSort($a, $mid + 1, $hi);
    merge($a, $lo, $mid, $hi);
}

$a = [3, 7, 9, 4, 2, 10, 8];
$a = range(0, 100000);
//$temp = $a;
shuffle($a);
//$start = microtime(true);
//mergeSort($a, 0, count($a) - 1);
//echo 'time:' . (microtime(true) - $start) . "\r\n";

//print_r($a);