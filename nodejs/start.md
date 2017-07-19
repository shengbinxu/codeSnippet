### 什么是NodeJS

JS是脚本语言，脚本语言都需要一个解析器才能运行。对于写在HTML页面里的JS，浏览器充当了解析器的角色。而对于需要独立运行的JS，NodeJS就是一个解析器。

每一种解析器都是一个运行环境，不但允许JS定义各种数据结构，进行各种计算，还允许JS使用运行环境提供的内置对象和方法做一些事情。例如运行在浏览器中的JS的用途是操作DOM，浏览器就提供了`document`之类的内置对象。而运行在NodeJS中的JS的用途是操作磁盘文件或搭建HTTP服务器，NodeJS就相应提供了`fs`、`http`等内置对象。

### 有啥用处

NodeJS的作者说，他创造NodeJS的目的是为了实现**高性能Web服务器**，他首先看重的是事件机制和异步IO模型的优越性，而不是JS。但是他需要选择一种编程语言实现他的想法，这种编程语言不能自带IO功能，并且需要能良好支持事件机制。JS没有自带IO功能，天生就用于处理浏览器中的DOM事件，并且拥有一大群程序员，因此就成为了天然的选择。

### socket.io

Socket.IO是一个封装了 Websocket、基于 Node 的 JavaScript 框架，包含 client 的 JavaScript 和 server 的 Node。其屏蔽了所有底层细节，让顶层调用非常简单。

另外，Socket.IO还有一个非常重要的好处。其不仅支持 WebSocket，还支持许多种轮询机制以及其他实时通信方式，并封装了通用的接口。这些方式包含 Adobe Flash Socket、Ajax 长轮询、Ajax multipart streaming 、持久 Iframe、JSONP 轮询等。换句话说，当 Socket.IO检测到当前环境不支持 WebSocket 时，能够自动地选择最佳的方式来实现网络的实时通信。

### socket.io实现多人聊天室。

> https://socket.io/get-started/chat/

这个例子可以很好地印证：websocket协议是有状态、双向通信，服务端可以给客户端push消息。

### websocket原理:

> https://zhuanlan.zhihu.com/p/23467317

































[^参考http://nqdeng.github.io/7-days-nodejs/#1]: 