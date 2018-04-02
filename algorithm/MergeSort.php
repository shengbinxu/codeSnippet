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
function merge($a, $b)
{
	$i = 0;
	$j = 0;
	$result = [];
	for ($k = 0; $k < count($a) + count($b); $k++)
	{
		if ($i >= count($a)) {
			$result[$k] = $b[$j++];
		} else if ($j >= count($b)) {
			$result[$k] = $a[$i++];
		} else if ($a[$i] > $b[$j]) {
			$result[$k] = $b[$j++];
		} else {
			$result[$k] = $a[$i++];
		}
	}
	return $result;
}

//print_r(merge([1, 4, 5,6], [2,4,6,7]));

function mergeSort($a) {
	$length = count($a);
	if ($length === 1) {
		return $a;
	}
	$mid = intval($length / 2);
	$left = mergeSort(array_slice($a, 0 , $mid));
	$right = mergeSort(array_slice($a, $mid));
	return merge($left, $right);
}

require  'InsertSort.php';
$start = microtime(true);
$a = range(0, 100000);
insertSort($a);
echo 'time:' . (microtime(true) - $start);

$start = microtime(true);
$a = range(0, 100000);
mergeSort($a);
echo 'time:' . (microtime(true) - $start);