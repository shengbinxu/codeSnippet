<?php
namespace ObserverPattern;
/**
 * Copyright (C) 2017 Baidu, Inc. All Rights Reserved.
 */
class DbTarget implements Observer{
    public function __construct(Subject $logger)
    {
        $logger->registerObserver($this);
    }

    public function export($message)
    {
        echo "insert log to db:" . $message . "\n";
    }
}