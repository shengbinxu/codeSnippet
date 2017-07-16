<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2017/7/16
 * Time: 10:15
 */
$fp = fsockopen("tcp://127.0.0.1", 9501, $errno, $errstr, 30);
if (!$fp) {
    echo "$errstr ($errno)<br />\n";
} else {
    fwrite($fp, "hello\n");
    echo 'receive:';
    echo fread($fp,100);
    fclose($fp);
}