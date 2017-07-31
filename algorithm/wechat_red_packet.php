<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2017/7/31
 * Time: 16:03
 */

function getMoney($left_total,$left_amount){
    if($left_amount == 1){
        return $left_total;
    }
    $min = 0.01;
    $max = round(($left_total / $left_amount),2) * 2;
    $money = round(rand($min * 100,$max * 100) /100,2);
    return $money;
}

$total = 100;
$amount = 5;
$left_total  = $total;
$left_amount = $amount;
//微信红包算法。
$check_sum = 0;
for ($i = 0; $i < $amount; $i++){
    $money = getMoney($left_total,$left_amount);
    $check_sum+=$money;
    echo 'money:' . $money . "\r\n";
    $left_total -= $money;
    $left_amount--;
}
echo 'check_sum:' . $check_sum;