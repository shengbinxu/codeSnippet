### po使用aio可能遇到的问题：

> Before we look at the different ways to use asynchronous I/O, we need to discuss
> the costs. When we decide to use asynchronous I/O, we complicate the design of our
> application by choosing to juggle multiple concurrent operations. A simpler approach
> may be to use multiple threads, which would allow us to write the program using a
> synchronous model, and let the threads run asynchronous to each other 

### 不同linux版本的aio实现

- system V aio

  > System V provides a limited form of asynchronous I/O that works only with STREAMS
  > devices and STREAMS pipes. The System V asynchronous I/O signal is SIGPOLL.
  > To enable asynchronous I/O for a STREAMS device, we have to call ioctl with a
  > second argument (request) of I_SETSIG. The third argument is an integer value formed
  > from one or more of the constants in Figure 14.18. These constants are defined in
  > <stropts.h>. 

- bsd aio

  ​	也是根据信号来实现的，此处省略。

- posix aio

  ​	参考https://www.ibm.com/developerworks/cn/linux/l-async/