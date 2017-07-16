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
//    'daemonize' => true,
    'backlog' => 128,
));
$serv->on('Close', 'my_onClose');
function my_onClose(){
    echo 'on close' . "\r\n";
}

$process = new swoole_process(function($process) use ($serv) {
    swoole_set_process_name(sprintf('php-ps:%s', 'subProcess'));
    while (true) {
        $msg = $process->read();
        foreach($serv->connections as $conn) {
            $serv->send($conn, $msg . 'from server'."\n");
        }
    }
});
//添加子进程
$serv->addProcess($process);

$serv->on('Connect',function ($serv, $fd, $from_id) use($process){
    echo 'on connect' . "\r\n";
});

$serv->on('Receive',function ($serv, $fd, $from_id, $data) use($process){
    echo 'on receive' . "\r\n";
    $process->write($data);
});

$serv->addlistener("127.0.0.1", 9502, SWOOLE_SOCK_UDP);
$serv->start();
