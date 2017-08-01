<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2017/7/31
 * Time: 16:03
 */

/**
 * 算法一：根据剩余平均金额，随机分配。
 * @param $left_total
 * @param $left_amount
 * @return float
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


/**
 * 算法二：红包总额total,发放份数m,  生成m个范围在 1~total 随机种子。然后求和sum。 再按比缩放： 100/sum * money(1...m-1)
 */
function getMoney2($total,$m){
    $a_money = [];
    for ($i = 0;$i<$m;$i++){
        $a_money[] = rand(0,$total);
    }
    $rand_total = array_sum($a_money);
    $ratio = 100/$rand_total; //缩放比例
    $result = [];
    $tmp_total = 0;
    for ($j = 0; $j < count($a_money)-1; $j++){
        $money = round($a_money[$j] * $ratio,2);
        $result[] = $money;
        $tmp_total += $money;
    }
    //有一个问题就是对金额是0的红包做微调。这里省略。
    //为了解决小数点的问题，最后一个红包的金额等于总金额-前m-1个红包金额。
    $result[] = round($total - $tmp_total,2);
    return $result;
}
$a_money = getMoney2(100,10);
print_r($a_money);
echo 'check_sum:' . array_sum($a_money) . "\r\n";


/**
 * 算法三：对红包金额设定限额：比如100个红包，10份，每份金额在6-12元之间:平均值的0.6倍 ~ 1.2倍之间。
 * 参考http://www.cnblogs.com/hanganglin/p/6496422.html
 * 算法思想：
 */
/*
100块钱  5份。
每个红包金额10-40之间（0.5倍--2倍之间）
假如已经发放了3个红包，剩下2个红包。
10,10,20,?,?
总100元，已发放40，剩余60元。第5个不能大于40，因此第四个红包要大于20。即第4个红包的区间范围：

bonus(4)        >       totalBonus     -     sendedBonus       -       rdMax
第4个元素的值   大于    (总红包金额100   减去  已发放红包金额40    减去   剩下红包的最大金额：第五个红包的最大金额40)

bonus(4)        <       totalBonus     -     sendedBonus       -       rdMin
第4个元素的值   小于    (总红包金额100   减去  已发放红包金额40    减去   剩下红包的最小金额：第五个红包的最小金额10)

得到最4个红包的大小范围     50 > bonus(4) > 20
加上前面的条件，红包大小在(10,40)之间， 得到           40 > bonus(4) > 20
然后在(20,40)之间随机取一个值，假如是30。
此时，已发放红包金额70，这是最后一个红包了。那么参照上面的逻辑，得到bouus(5) = 30；
推而广之，就能得到代码中的算法逻辑

 */
function createBonusList($totalBonus,$totalNum){
    $sendedBonus = 0;
    $sendedNum = 0;
    $rdMin = (int) ($totalBonus/$totalNum * 0.6);
    $rdMax = (int) ($totalBonus/$totalNum * 1.2);
    $bonusList=[];
    while ($sendedNum < $totalNum){
        $bonus = randomBonusWithSpecifyBound($totalBonus,$totalNum,$sendedBonus,$sendedNum,$rdMin,$rdMax);
        $bonusList[] = $bonus;
        $sendedNum++;
        $sendedBonus+=$bonus;
    }
    return $bonusList;
}
function randomBonusWithSpecifyBound($totalBonus,$totalNum,$sendedBonus,$sendedNum,$rdMin,$rdMax){
    $boundMin = max($totalBonus-$sendedBonus-($totalNum-$sendedNum-1) * $rdMax,$rdMin);
    $boundMax = min($totalBonus-$sendedBonus-($totalNum-$sendedNum-1) * $rdMin,$rdMax);
    echo 'boundMin:' . $boundMin . " boundMax:" . $boundMax . "\r\n";
    return rand($boundMin,$boundMax);
}

$bonusList = createBonusList(100,10);
echo "算法三：\r\n";
print_r($bonusList);
echo 'check_sum:' . array_sum($bonusList) . "\r\n";