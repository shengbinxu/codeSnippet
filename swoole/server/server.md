### swoole server进程模型

- reactor类似于redis的reactor模型。负责监测socket链接（tcp、udp）的可读、可写状态，然后把任务投递(dispatch)给空闲的worker进程。参见

  ```
  worker进程数据包分配模式
  dispatch_mode = 1 //1平均分配，2按FD取摸固定分配，3抢占式分配，默认为取模(dispatch=2)

  抢占式分配，每次都是空闲的worker进程获得数据。很合适SOA/RPC类的内部服务框架
  当选择为dispatch=3抢占模式时，worker进程内发生onConnect/onReceive/onClose/onTimer会将worker进程标记为忙，不再接受新的请求。reactor会将新请求投递给其他状态为闲的worker进程
  如果希望每个连接的数据分配给固定的worker进程，dispatch_mode需要设置为2
  ```

- task进程

  > 为了避免长时间阻塞worker进程，把耗时的任务交给task进程处理。


    ​```
  <?php
  /**
   * swoole server
   * Created by PhpStorm.
   * User: xushengbin
   * Date: 2017/7/16
   * Time: 9:48
        */

  $serv = new swoole_server('127.0.0.1', 9501, SWOOLE_PROCESS,SWOOLE_SOCK_TCP);
  $serv->set(array(
      'worker_num' => 1,
      'backlog' => 128,
      'task_worker_num'=>2
  ));
  $serv->on('Connect', 'my_onConnect');
  $serv->on('Close', 'my_onClose');
  function my_onConnect(){
      echo 'on connect' . "\r\n";
  }
  function my_onClose(){
      echo 'on close' . "\r\n";
  }

  $serv->on('Receive',function ($serv, $fd, $from_id, $data){
      echo 'on received' . "\r\n";
      //sleep(5);
      $serv->task($fd . '_' . $data);
  });

  //on task
  $serv->on('Task',function (swoole_server $serv, $task_id, $from_id, $data){
      list($fd,$data) = explode('_',$data);
      sleep(5);
      echo date('Y-m-d H:i:s') . "Task[PID=".$serv->worker_pid."]: task_id=$task_id.':data:'.$data.".PHP_EOL;
      $serv->send($fd,'task:' . $serv->worker_pid . ' finished' . "\r\n");
  });
  $serv->on('Finish',function (swoole_server $serv, $task_id, $from_id, $data){
      echo "Task[PID=".$serv->worker_pid."]: task_id=$task_id.':data:'.$data.'FINISH'.".PHP_EOL;
  });

  $serv->start();

    ​```

  这个例子中，设置worker进程的数量为1，task_worker进程的数量为2，在onReceive中启动一个task进程，onTask中耗时5秒执行一个任务，由于task函数是非阻塞的，这样，同一个worker进程就可以并发处理多个客户端请求。反之，如果把5秒耗时操作放在worker进程中执行，那么这个server每5秒内只能处理一个并发请求。

  > 由此，联想到redis的reactor模型：
  >
  > redis是单线程的，在redis中任意一个耗时的请求，就会导致其他请求在排队。因为，在redis中客户端要尽力避免执行超过10ms的命令。
  >
  > 同样的，在swoole的worker进程中，是不是也尽量不要处理耗时的任务，或者说，当不得不处理耗时任务的时候，需要把worker_num调大呢？ 
  >
  > 补充一点：reactor模型基于epool，每一个socket链接都是一个fd，reactor负责检测fd的可读、可写状态，然后把请求调度(dispatch)给对应的worker进程。那么，如果worker进程超负荷，就会导致没有空闲worker进程可供reactor调度。
  >
  > ​