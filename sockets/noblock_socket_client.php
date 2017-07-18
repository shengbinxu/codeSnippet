<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2017/7/18
 * Time: 17:42
 */
$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
socket_connect($socket, '127.0.0.1', 1235);
socket_write($socket,'open');
while ($data = socket_read($socket,1024)){
    echo 'revice_data:' . $data . "\r\n";
}