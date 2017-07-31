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

$total = 100;//100块钱
$amount = 5;//5个红包
$left_total  = $total;
$left_amount = $amount;
//微信红包算法。
$check_sum = 0;
for ($i = 0; $i < $amount; $i++){
    //如果是高并发情况下，怎么保证left_total的一致性。
    //如果把库存（剩余红包总额）放入redis中，由于redis是单线程，命令是串行执行的。
    //可以使用redis的decr()或者pop()方法，他们都是原子操作（个人理解，decr的减一和返回减一后的结果是原子操作）。每个用户来的时候库存减1，然后根据decr() > 0,判断库存是否还有。
    //如果是先get库存，然后红包发放完毕，再去减库存的话：如果剩下1个库存，3个人同时来，get的结果都是1，然后都去发红包，然后库存减1，这样就出问题了。
    $money = getMoney($left_total,$left_amount);
    $check_sum+=$money;
    echo 'money:' . $money . "\r\n";
    $left_total -= $money;
    $left_amount--;
}
echo 'check_sum:' . $check_sum; //检验红包金额之和是不是100.