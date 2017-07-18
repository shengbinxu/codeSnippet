<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2017/7/18
 * Time: 18:22
 */
$clients = array();
$port = 1236;
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socket,'127.0.0.1',$port);
socket_listen($socket);
//$socket = socket_create_listen(1234);
while(true)
{
    //socket_accept会阻塞，同一时间只能处理一个来自客户端的请求。
    if(($newc = socket_accept($socket)) !== false)
    {
        echo "Client $newc has connected\n";
        $clients[] = $newc;
        $data = socket_read($newc,1024);
        echo 'receive:' . $data;
        echo "\r\n";
        if($data == 'open'){
            socket_write($newc,'open finished');
        }
    }
}
