<?php
class pipe{
	public $mpid=0;
    public $workers=[];
    public $max_precess=5;
    public $new_index=0;

	public function __construct(){
        try {
            swoole_set_process_name(sprintf('php-ps:%s', 'master'));
            $this->mpid = posix_getpid();

        }catch (\Exception $e){
            die('ALL ERROR: '.$e->getMessage());
        }
    }


 	public function run(){
        for ($i=0; $i < $this->max_precess; $i++) {
            $this->CreateProcess($i);
        }
		foreach($this->workers as $process)
		{
    		swoole_event_add($process->pipe, [$this,'onReceive']);
		}
    }

    public function CreateProcess($index=null){
        $process = new swoole_process(function(swoole_process $process)use($index){
            swoole_set_process_name(sprintf('php-ps:%s',$index));
            sleep(3);
            $process->write(posix_getpid() . "\r\n");
        });
        $pid=$process->start();
        $this->workers[$process->pipe]=$process;
        return $pid;
    }

	function onReceive($pipe) {
	    $worker = $this->workers[$pipe];
	    $data = $worker->read();
	    echo "RECV: " . $data;
        swoole_event_del($pipe);
	}
}

$obj = new pipe();
$obj->run();
