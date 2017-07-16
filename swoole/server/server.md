### swoole server进程模型

- reactor类似于redis的reactor模型。负责监测socket链接（tcp、udp）的可读、可写状态，然后把任务投递(dispatch)给空闲的worker进程。参见

  ```
  worker进程数据包分配模式
  dispatch_mode = 1 //1平均分配，2按FD取摸固定分配，3抢占式分配，默认为取模(dispatch=2)

  抢占式分配，每次都是空闲的worker进程获得数据。很合适SOA/RPC类的内部服务框架
  当选择为dispatch=3抢占模式时，worker进程内发生onConnect/onReceive/onClose/onTimer会将worker进程标记为忙，不再接受新的请求。reactor会将新请求投递给其他状态为闲的worker进程
  如果希望每个连接的数据分配给固定的worker进程，dispatch_mode需要设置为2
  ```

