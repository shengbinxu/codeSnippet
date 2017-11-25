<?php
namespace ObserverPattern;
include '../autoload.php';

/**
 * Copyright (C) 2017 Baidu, Inc. All Rights Reserved.
 */
class Logger implements Subject{
    public $observers;
    public $message;
    public function __construct()
    {
        $this->observers = new \ArrayObject();
    }
    public function log($message) {
        $this->message = $message;
        $this->nofityObserver();
    }

    public function registerObserver(Observer $o)
    {
        $this->observers->append($o);
    }
    public function removeObserver(Observer $o)
    {
    }
    public function nofityObserver()
    {
        foreach ($this->observers as $o) {
            $o->export($this->message);
        }
    }
}

$logger = new Logger();
new FileTarget($logger);
new EmailTarget($logger);
new DbTarget($logger);
$logger->log('fatal error variable $a not defined');