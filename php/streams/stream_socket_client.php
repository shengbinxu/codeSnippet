<?php
/**
 * Copyright (C) 2018 Baidu, Inc. All Rights Reserved.
 */
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2018/7/23
 * Time: 8:59
 */

$fp = stream_socket_client('tcp://127.0.0.1:80',$errno,$errstr,5);

if (!$fp) {
    echo $errstr . "\r\n";
    exit();
}

$headers = <<<EOF
GET /remote-sign-in/get-sign-data HTTP/1.1\r\nHost: local.out.bee.baidu.com\r\nContent-Type: application/json; charset=UTF-8\r\nAccept: *\r\n\r\n
EOF;
fwrite($fp,$headers);
while (!feof($fp)) {
    echo fgets($fp, 1024);
}
fclose($fp);