### swoole process

 - 注意在子进程中，数据库链接、redis链接等都应该在子进程里面初始化。如果在父进程初始化，这些资源在子进程不能共享。
 - 父进程可以使用wait()方法，检测子进程的exit状态。这个特性，可以实现：当子进程任务处理完毕，父进程去重新启动新的进程。
 - 管道
 ```
<?php
$process = new swoole_process('callback_function', true);//第2个参数，重定向子进程的标准输入、输出到管道。
$pid = $process->start();

function callback_function(swoole_process $worker)
{
    $worker->exec('/usr/local/bin/php', array(__DIR__.'/stdin_stdout.php'));
}

echo "From Worker: ".$process->read();
$process->write("hello worker\n");
echo "From Worker: ".$process->read();

$ret = swoole_process::wait();
var_dump($ret);

//stdin_stdout.php
<?php
fwrite(STDOUT, "Hello master.\n");
sleep(5);
fwrite(STDOUT, "Master write: ".fgets(STDIN)."\n");//这里会阻塞，直到$process->write()执行。
 ```
 - swoole_process::alarm  微秒级别的定时器。

 - swoole_process::useQueue 使用消息队列实现进程间通信。

   > 消息队列与管道以及有名管道相比，具有更大的灵活性，首先，它提供有格式字节流，有利于减少开发人员的工作量；其次，消息具有类型，在实际应用中，可作为优先级使用。这两点是管道以及有名管道所不能比的。同样，消息队列可以在几个进程间复用，而不管这几个进程是否具有亲缘关系，这一点与有名管道很相似；但消息队列是随内核持续的，与有名管道（随进程持续）相比，生命力更强，应用空间更大。


```
<?php
$workers = [];
$worker_num = 2;

for($i = 0; $i < $worker_num; $i++)
{
    $process = new swoole_process('callback_function', false, false);
    $process->useQueue();
    $pid = $process->start();
    $workers[$pid] = $process;
    //echo "Master: new worker, PID=".$pid."\n";
}

function callback_function(swoole_process $worker)
{
    //echo "Worker: start. PID=".$worker->pid."\n";
    //recv data from master
    while(true)
    {
        $recv = $worker->pop();
        echo "From Master: $recv\n";
    }

    sleep(2);
    $worker->exit(0);
}

while(true)
{
    /**
     * @var $process swoole_process
     */
    $pid = array_rand($workers);
    $process = $workers[$pid];
    $process->push("hello worker[$pid]\n");
    sleep(1);
}

for($i = 0; $i < $worker_num; $i++)
{
    $ret = swoole_process::wait();
    $pid = $ret['pid'];
    unset($workers[$pid]);
    echo "Worker Exit, PID=".$pid.PHP_EOL;
}

```

