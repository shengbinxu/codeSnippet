<?php
/**
 * Copyright (C) 2018 Baidu, Inc. All Rights Reserved.
 */
/**
 * 参考http://www.laruence.com/2015/05/28/3038.html
 * https://www.cnblogs.com/tingyugetc/p/6347286.html
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2018/1/18
 * Time: 9:31
 */

/*
Generator implements Iterator {
    public mixed current(void)
    public mixed key(void)
    public void next(void)
    public void rewind(void)
    // 向生成器传入一个值
    public mixed send(mixed $value)
    public void throw(Exception $exception)
    public bool valid(void)
    // 序列化回调
    public void __wakeup(void)
}
*/

/*

// 迭代器模式伪代码
$iter->rewind();
while( $iter->valid() ){
    $iter->current();
    $iter->next();
}

*/

function xrange($start, $end, $step = 1)
{
    for ($i = $start; $i <= $end; $i += $step) {
        yield $i;
    }
}
$xrange = xrange(1, 1000000);
var_dump($xrange->current());
var_dump($xrange);
$xrange->rewind();
$xrange->next();


foreach (xrange(1, 1000000) as $num) {
//    echo $num, "\n";
}

function logger($fileName)
{
    $fileHandle = fopen($fileName, 'a');
    while (true) {
        fwrite($fileHandle, yield . "\n");
    }
}

$logger = logger(__DIR__ . '/log');
// send方法，用来向生成器传入一个值，并且当做yield表达式的结果，然后继续执行生成器，直到遇到下一个yield后会再次停住。
$logger->send('Foo');
$logger->send('Bar');

function gen()
{
    $ret = (yield 'yield1');
    echo '--point 1--' . "\r\n";
    var_dump($ret);
    $ret = (yield 'yield2');
    var_dump($ret);
}

$gen = gen();
// 调用迭代器的方法一次, 其中的代码运行一次.例如, 如果你调用$range->rewind(),
// 那么xrange()里的代码就会运行到控制流第一次出现yield的地方. 而函数内传递给yield语句的返回值可以通过$range->current()获取.
var_dump($gen->current());
echo '---send---' . "\r\n";
var_dump($gen->send('send1'));

var_dump($gen->send('send2')); //send2 null

// 现在你应当明白协程和任务调度之间的关系：yield指令提供了任务中断自身的一种方法, 然后把控制交回给任务调度器.
// 因此协程可以运行多个其他任务. 更进一步来说, yield还可以用来在任务和调度器之间进行通信.




