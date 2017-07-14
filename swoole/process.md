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

