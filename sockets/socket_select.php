#!/usr/local/bin/php
<?php
/**
 *
 */

$port = 9050;
// create a streaming socket, of type TCP/IP
$sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
// set the option to reuse the port
socket_set_option($sock, SOL_SOCKET, SO_REUSEADDR, 1);
// "bind" the socket to the address to "localhost", on port $port
// so this means that all connections on this port are now our resposibility to send/recv data, disconnect, etc..
socket_bind($sock, 0, $port);
// start listen for connections
socket_listen($sock);
// create a list of all the clients that will be connected to us..
// add the listening socket to this list
$clients = array($sock);

while (true) {
    //sleep(1);
    // create a copy, so $clients doesn't get modified by socket_select()
    $read = $clients;
    // get a list of all the clients that have data to be read from
    // if there are no clients with data, go to next iteration
    $write = NULL;
    $except = NULL;
    echo 'read:' . "\r\n";
    var_dump($read);
    //初始状态，select监听服务端socket的可读状态：当有新的client connection到来，服务端socket状态可读。
    //因此，服务端socket负责处理客户端连接。
    if (socket_select($read, $write, $except, 5) < 1){//tv_sec 为0，函数立马返回.
        continue;
    }
    // check if there is a client trying to connect
    if (in_array($sock, $read)) {
        // accept the client, and add him to the $clients array
        //socket_accept返回一个客户端socket，负责与socket server或者其他客户端进行通信。
        //把新的socket client加入select的可读fd监控列表。
        $clients[] = $newsock = socket_accept($sock);
        // send the client a welcome message
        socket_write($newsock, "no noobs, but ill make an exception :)\n".
            "There are ".(count($clients) - 1)." client(s) connected to the server\n");

        socket_getpeername($newsock, $ip);
        echo "New client connected: {$ip}\n";

        // remove the listening socket from the clients-with-data array
        //把服务端socket(listening socket剔除，后面的$read array就是所有可读的client socket的处理逻辑)
        $key = array_search($sock, $read);
        unset($read[$key]);
    }
    //所有可读的client socket的处理逻辑
    // loop through all the clients that have data to read from
    foreach ($read as $read_sock) {
        // read until newline or 1024 bytes
        // socket_read while show errors when the client is disconnected, so silence the error messages
        $data = @socket_read($read_sock, 1024, PHP_NORMAL_READ);

        // check if the client is disconnected
        if ($data === false) {
            // remove client for $clients array
            $key = array_search($read_sock, $clients);
            unset($clients[$key]);
            echo "client disconnected.\n";
            // continue to the next client to read from, if any
            continue;
        }

        // trim off the trailing/beginning white spaces
        $data = trim($data);
        echo 'data:' . $data . "\r\n";
        // check if there is any data after trimming off the spaces
        if (!empty($data)) {

            // send this to all the clients in the $clients array (except the first one, which is a listening socket)
            foreach ($clients as $send_sock) {

                // if its the listening sock or the client that we got the message from, go to the next one in the list
                if ($send_sock == $sock || $send_sock == $read_sock)
                    continue;
                //给其他socket客户端发送消息。
                // write the message to the client -- add a newline character to the end of the message
                socket_write($send_sock, $data."\n");

            } // end of broadcast foreach

        }

    } // end of reading foreach
}

// close the listening socket
socket_close($sock);
?>