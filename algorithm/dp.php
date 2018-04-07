<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2018/4/7
 * Time: PM11:20
 */

$n = 5;
for ($i = 0; $i <= $n; $i++) {
	$min[$i] = 999999;
}
$min[0] = 0;
$icons = [
	1, 3, 5
];
for ($i = 1; $i <= $n; $i++) {
	for ($j = 0; $j < count($icons); $j++) {
		if ($icons[$j] <= $i && $min[$i - $icons[$j]] + 1 < $min[$i]) {
			$min[$i] = $min[$i - $icons[$j]] + 1;
		}
	}
}


print_r($min);