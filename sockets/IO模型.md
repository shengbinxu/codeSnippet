### IO模型
> 参考 http://www.masterraghu.com/subjects/np/introduction/unix_network_programming_v1.3/ch06lev1sec2.html

- 阻塞IO

  缺点：同一时刻只能处理一个来自客户端的请求。可以通过多进程或者多线程来处理并发请求，性能较差。

- 非阻塞IO 

  对应php的 socket_set_nonblock()方法，参考`noblock_socket.php`。
  缺点：需要不断地轮询，来检测每个客户端socket是否可读。

- IO多路复用

  定义参考知乎上一段说明：

  > I/O多路复用(又被称为“事件驱动”)，首先要理解的是，操作系统为你提供了一个功能，当你的某个socket可读或者可写的时候，它可以给你一个通知。这样当配合非阻塞的socket使用时，只有当系统通知我哪个描述符可读了，我才去执行read操作，可以保证每次read都能读到有效数据而不做纯返回-1和EAGAIN的无用功。写操作类似。操作系统的这个功能通过select/poll/epoll/kqueue之类的系统调用函数来使用，这些函数都可以同时监视多个描述符的读写就绪状况，这样，多个描述符的I/O操作都能在一个线程内并发交替地顺序完成，这就叫I/O多路复用，这里的“复用”指的是复用同一个线程。作者：晨随链接：https://www.zhihu.com/question/28594409/answer/52763082来源：知乎著作权归作者所有。商业转载请联系作者获得授权，非商业转载请注明出处。

  对应php的socket_select方法，参考`socket_select.php`

  注意一点：**IO 多路复用要和非阻塞 IO一起使用**， 原因参见

  > https://www.zhihu.com/question/37271342-

- #####  Signal-Driven I/O model

- ##### Asynchronous I/O Model