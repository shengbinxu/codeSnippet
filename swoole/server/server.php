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
$serv->on('Receive', 'my_onReceive');
$serv->on('Close', 'my_onClose');
function my_onConnect(){
    echo 'on connect' . "\r\n";
}
function my_onReceive(){
    echo 'on receive' . "\r\n";
}
function my_onClose(){
    echo 'on close' . "\r\n";
}
$serv->start();
