<?php
/**
 * Copyright (C) 2018 Baidu, Inc. All Rights Reserved.
 */
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2018/7/23
 * Time: 9:37
 */

$socket = stream_socket_server('tcp://127.0.0.1:9999', $errno, $errstr, STREAM_SERVER_BIND | STREAM_SERVER_LISTEN);
stream_set_blocking($socket, 0);

if (!$socket) {
    echo "$errstr ($errno)<br />\n";
} else {
    // Accept a connection on a socket created by stream_socket_server()
    $clients = [];
    $read = [];
    $write = null;
    $except = null;
    while (true) {
        $conn = stream_socket_accept($socket, 10, $peername);
        if ($conn) {
            echo $peername . "\r\n";
            $clients[$peername] = $conn;
            echo $conn . "\r\n";
        }

//        $command = fgets($conn, 1024);
//        echo 'command:' . $command . "\r\n";
//        fwrite($conn, $command . ' the local time is ' . date('Y-m-d H:i:s') . "\r\n");
//        fclose($conn);
        $read = $clients;
        print_r($read);

        if ($read) {
            if (stream_select($read, $write, $except, 1)) {
                /* At least on one of the streams something interesting happened */
                foreach ($read as $index => $conn) {
                    $peername = stream_socket_get_name($conn, true);
                    $command = fgets($conn, 1024);
                    echo 'command:' . $command . "\r\n";
                    fwrite($conn, $command . ' the local time is ' . date('Y-m-d H:i:s') . "\r\n");
                    fclose($conn);
                    unset($clients[$peername]);
                    $read = $clients;
                }
            }
        }
    }
}

fclose($socket);