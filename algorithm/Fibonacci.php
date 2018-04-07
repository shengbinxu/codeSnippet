<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2018/4/7
 * Time: PM9:57
 */

/**
 * 问题：f(n+1)的计算时间是f(n)的约1.6倍。有很多重复计算。比如f(2)的值递归的过程中会计算很多遍。
 * @param $n
 * @return int
 */
function f($n) {
	if ($n < 0) {
		return 0;
	}
	if ($n == 1) {
		return 1;
	}

	return f($n-1) + f($n-2);
}

$start = microtime(true);
$result = f(35);
echo $result . "\r\n";
echo 'time:' . (microtime(true) - $start) . "\r\n";


global $knownF;
function f4($n) {
	global $knownF;
	if (isset($knownF[$n])) {
		return $knownF[$n];
	}
	if ($n < 0) {
		return 0;
	}
	if ($n == 1) {
		return 1;
	}

	$t = f4($n-1) + f4($n-2);
	return $knownF[$n] = $t;
}

$start = microtime(true);
$result = f4(35);
echo $result . "\r\n";
echo 'time:' . (microtime(true) - $start) . "\r\n";

function f2($n) {
	// 数组，把所有f(n)的值保持下来，避免重复计算
	$a = array_fill(0, $n, 0);
	$a[0] = 0;
	$a[1] = 1;
	for ($i = 2 ; $i <= $n; $i ++) {
		$a[$i] = $a[$i-1] + $a[$i-2];
	}
	return $a[$n];
}

$start = microtime(true);
$result = f2(35);
echo $result . "\r\n";
echo 'time:' . (microtime(true) - $start) . "\r\n";


function f3($n) {
	$a0= 0;
	$a1 = 1;
	$i = 2;
	while ($i <= $n) {
		$an = $a1 + $a0;
		$a0 = $a1;
		$a1 = $an;
		$i++;
	}
	return $an;
}

$start = microtime(true);
$result = f3(35);
echo $result . "\r\n";
echo 'time:' . (microtime(true) - $start) . "\r\n";