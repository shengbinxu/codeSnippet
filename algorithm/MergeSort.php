<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2018/4/2
 * Time: PM8:57
 */
/**
 * 对两个有序的数组，进行合并
 * @param $a
 * @param $b
 */

require 'InsertSort.php';
require 'SelectSort.php';
require 'MergeSortGood.php';


//function merge($a, $b)
//{
//    $i = 0;
//    $j = 0;
//    $result = [];
//    for ($k = 0; $k < count($a) + count($b); $k++) {
//        if ($i >= count($a)) {
//            $result[$k] = $b[$j++];
//        } else if ($j >= count($b)) {
//            $result[$k] = $a[$i++];
//        } else if ($a[$i] > $b[$j]) {
//            $result[$k] = $b[$j++];
//        } else {
//            $result[$k] = $a[$i++];
//        }
//    }
//    return $result;
//}
//
////print_r(merge([1, 4, 5,6], [2,4,6,7]));
//
//function mergeSort($a)
//{
//    $length = count($a);
//    if ($length === 1) {
//        return $a;
//    }
//    $mid = intval($length / 2);
//    $left = mergeSort(array_slice($a, 0, $mid));
//    $right = mergeSort(array_slice($a, $mid));
//    return merge($left, $right);
//}

$a = range(0, 100000);
shuffle($a);

//$start = microtime(true);
//selectSort($a);
//echo 'time:' . (microtime(true) - $start) . "\r\n";

/**
 * 对比总结：
 * 插入排序性能比较接近近php sort函数
 * 归并排序--这里性能很差，初步排查是array_slice性能瓶颈导致的。下面对此做改进。
 */
$start = microtime(true);
insertSort($a);
echo 'time:' . (microtime(true) - $start) . "\r\n";

//$start = microtime(true);
//mergeSort($a);
//echo 'time:' . (microtime(true) - $start) . "\r\n";

$start = microtime(true);
mergeSort($a, 0, count($a) - 1);
echo 'time:' . (microtime(true) - $start) . "\r\n";

$start = microtime(true);
sort($a);
echo 'time:' . (microtime(true) - $start) . "\r\n";




// array_slice 当offset很大时，性能很差。
//$start = microtime(true);
//$temp = array_slice($a, 0, 100);
//echo 'time:' . (microtime(true) - $start) . "\r\n";
//
//$start = microtime(true);
//$temp = array_slice($a, 99000, 100);
//echo 'time:' . (microtime(true) - $start) . "\r\n";


echo '-----对归并排序进行优化----' . "\r\n";


