<?php
/**
 * Created by PhpStorm.
 * User: xushengbin
 * Date: 2017/8/1
 * Time: 17:20
 */

class Connection
{
    protected $link;
    private $server, $username, $password, $db;

    public function __construct($server, $username, $password, $db)
    {
        $this->server = $server;
        $this->username = $username;
        $this->password = $password;
        $this->db = $db;
        $this->connect();
    }

    private function connect()
    {
        $this->link = mysqli_connect($this->server, $this->username, $this->password,$this->db);
    }

    public function __sleep()
    {
        return array('server', 'username', 'password', 'db');
    }

    public function __wakeup()
    {
        $this->connect();
    }

    public function getUser(){
        $result_set =  mysqli_query($this->link,'select * from user');
        $data = mysqli_fetch_all($result_set);
        return $data;
    }
}

$a = new Connection('127.0.0.1','root','root','test');
//serialize() 可处理除了 resource 之外的任何类型
$s = serialize($a);
// 把变量$s保存起来以便文件page2.php能够读到
file_put_contents('/tmp/store', $s);