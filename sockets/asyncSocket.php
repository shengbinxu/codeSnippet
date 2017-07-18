<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2017/7/18
 * Time: 16:06
 */
$socket = socket_create_listen(1234);
socket_set_nonblock($socket);
$result = socket_accept($socket);
var_dump($result);
