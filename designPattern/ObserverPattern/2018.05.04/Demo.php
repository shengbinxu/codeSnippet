<?php

interface Observer
{
    function notify($msg);
}

class User implements Observer
{
    public $name;

    public function __construct($name)
    {
        $this->name = $name;
        Msg::getInstance()->register($this);
    }

    public function notify($msg)
    {
        echo $this->name . '-' . 'received msg:' . $msg . "\r\n";
    }
}

class Msg
{
    private $observers = [];
    private static $_instance;

    /**
     * @return Msg
     */
    public static function getInstance()
    {
        if (!self::$_instance) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function publish($msg)
    {
        $this->notify($msg);
    }

    function notify($msg)
    {
        foreach ($this->observers as $observer) {
            $observer->notify($msg);
        }
    }

    function register(Observer $o)
    {
        $this->observers[] = $o;
    }
}

$user1 = new User('xushengbin');
$user2 = new User('zhuqiaozhen');
$msg = Msg::getInstance()->publish('今天要下雨了');