<?php
/**
 * Copyright (C) 2017 Baidu, Inc. All Rights Reserved.
 */
spl_autoload_register('autoload');
function autoload($className){
    list($nameSpace,$class) = explode('\\', $className);
    $file = __DIR__ . DIRECTORY_SEPARATOR . $nameSpace .DIRECTORY_SEPARATOR . $class .  '.php';
    if(is_file($file)){
        require $file;
    }
}