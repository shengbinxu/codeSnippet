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
    'worker_num' => 4,
//    'daemonize' => true,
    'backlog' => 128,
));
$serv->on('Connect', 'my_onConnect');
$serv->on('Close', 'my_onClose');
function my_onConnect(){
    echo 'on connect' . "\r\n";
}

function my_onClose(){
    echo 'on close' . "\r\n";
}

$process = new swoole_process(function($process) use ($serv) {
    swoole_set_process_name(sprintf('php-ps:%s', 'subProcess'));
    while (true) {
        $msg = $process->read();
        foreach($serv->connections as $conn) {
            $serv->send($conn, $msg);
        }
    }
});

$serv->addProcess($process);
$serv->on('Receive',function ($serv, $fd, $from_id, $data) use($process){
    $serv->send($fd, 'Swoole: '.$data);
    $serv->close($fd);
    echo 'on receive' . "\r\n";
    $process->write($data);
} );

//$process->write(time());
$serv->start();
