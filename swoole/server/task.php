<?php
/**
 * swoole server
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2017/7/16
 * Time: 9:48
 */

$serv = new swoole_server('127.0.0.1', 9501, SWOOLE_PROCESS,SWOOLE_SOCK_TCP);
$serv->set(array(
    'worker_num' => 1,
    'backlog' => 128,
    'task_worker_num'=>2
));
$serv->on('Connect', 'my_onConnect');
$serv->on('Close', 'my_onClose');
function my_onConnect(){
    echo 'on connect' . "\r\n";
}
function my_onClose(){
    echo 'on close' . "\r\n";
}

$serv->on('Receive',function ($serv, $fd, $from_id, $data){
    echo 'on received' . "\r\n";
    $serv->task($fd . '_' . $data);
});

//on task
$serv->on('Task',function (swoole_server $serv, $task_id, $from_id, $data){
    list($fd,$data) = explode('_',$data);
    sleep(5);
    echo date('Y-m-d H:i:s') . "Task[PID=".$serv->worker_pid."]: task_id=$task_id.':data:'.$data.".PHP_EOL;
    $serv->send($fd,'task:' . $serv->worker_pid . ' finished' . "\r\n");
});
$serv->on('Finish',function (swoole_server $serv, $task_id, $from_id, $data){
    echo "Task[PID=".$serv->worker_pid."]: task_id=$task_id.':data:'.$data.'FINISH'.".PHP_EOL;
});

$serv->start();
