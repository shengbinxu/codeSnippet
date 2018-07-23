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

$fp = stream_socket_client('tcp://127.0.0.1:9999', $errno, $errstr, 5);

if (!$fp) {
    echo $errstr . "\r\n";
    exit();
}


$command = fgets(STDIN);
fwrite($fp, $command);
while (!feof($fp)) {
    echo fgets($fp, 1024);
}

fclose($fp);