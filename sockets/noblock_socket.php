<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2017/7/18
 * Time: 17:36
 */
$clients = array();
$port = 1235;
$socket = socket_create(AF_INET,SOCK_STREAM,SOL_TCP);
socket_bind($socket,'127.0.0.1',$port);
socket_listen($socket);
//$socket = socket_create_listen(1234);
var_dump($socket);
socket_set_nonblock($socket);

while(true)
{
    //异步非阻塞socket。socket_accept会立马返回。缺点是：需要while true轮询。看看是否有来自客户端的连接。
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
