## 语法

```
Syntax:	try_files file ... uri;
try_files file ... =code;
Default:	—
Context:	server, location
```

## 定义

> Checks the existence of files in the specified order and uses the first found file for request 
> processing; the processing is performed in the current context. The path to a file is constructed from the file parameter according to the root and alias directives. It is possible to check directory’s existence by specifying a slash at the end of a name, e.g. “$uri/”. If none of the files were found, an internal redirect to the uri specified in the last parameter is made

关键点：

1. 根据顺序，检测文件（包括目录）是否存在，如果存在，就返回文件的内容。
2. 如果都不存在，对最后一个参数做内部跳转，不是直接返回。

## 示例

```shell
index       index.php index.html;
location / {
    # 强制给uri加上index.php
    try_files $uri $uri/ /index.php$is_args$args =404;
    #try_files $uri $uri/ /index.php =404;
}
```

yingyan/ 目录存在，目录下有文件index.html

### 示例1：返回目录中index文件

请求"http://local.bee.test/yingyan/?i=84464&k=86DOfEvtIRQulG3PNq4MX191", 

返回yingyan/index.html文件的内容



### 示例2：fallback 404

请求“http://local.bee.test/noexitsdir?a=1”，返回

```html

<html>
<head><title>404 Not Found</title></head>
<body bgcolor="white">
<center><h1>404 Not Found</h1></center>
<hr><center>nginx/1.10.2</center>
</body>
</html>
```

nginx debug log:

```shell
2017/09/05 04:19:13 [debug] 3491#0: *321 test location: "/"
2017/09/05 04:19:13 [debug] 3491#0: *321 test location: ~ "^/assets/.*\.php$"
2017/09/05 04:19:13 [debug] 3491#0: *321 test location: ~ "\.php$"
2017/09/05 04:19:13 [debug] 3491#0: *321 test location: ~ "/\."
2017/09/05 04:19:13 [debug] 3491#0: *321 using configuration "/"
2017/09/05 04:19:13 [debug] 3491#0: *321 http cl:-1 max:134217728
2017/09/05 04:19:13 [debug] 3491#0: *321 rewrite phase: 3
2017/09/05 04:19:13 [debug] 3491#0: *321 post rewrite phase: 4
2017/09/05 04:19:13 [debug] 3491#0: *321 generic phase: 5
2017/09/05 04:19:13 [debug] 3491#0: *321 generic phase: 6
2017/09/05 04:19:13 [debug] 3491#0: *321 generic phase: 7
2017/09/05 04:19:13 [debug] 3491#0: *321 generic phase: 8
2017/09/05 04:19:13 [debug] 3491#0: *321 access phase: 9
2017/09/05 04:19:13 [debug] 3491#0: *321 access phase: 10
2017/09/05 04:19:13 [debug] 3491#0: *321 post access phase: 11
2017/09/05 04:19:13 [debug] 3491#0: *321 try files phase: 12
2017/09/05 04:19:13 [debug] 3491#0: *321 http script var: "/noexitsdir"
2017/09/05 04:19:13 [debug] 3491#0: *321 trying to use file: "/noexitsdir" "/data/www/bee-platform/web/noexitsdir"
2017/09/05 04:19:13 [debug] 3491#0: *321 http script var: "/noexitsdir"
2017/09/05 04:19:13 [debug] 3491#0: *321 trying to use dir: "/noexitsdir" "/data/www/bee-platform/web/noexitsdir"
2017/09/05 04:19:13 [debug] 3491#0: *321 http script copy: "/index.php"
2017/09/05 04:19:13 [debug] 3491#0: *321 http script var: "?"
2017/09/05 04:19:13 [debug] 3491#0: *321 http script var: "a=1"
2017/09/05 04:19:13 [debug] 3491#0: *321 trying to use file: "/index.php?a=1" "/data/www/bee-platform/web/index.php?a=1"
2017/09/05 04:19:13 [debug] 3491#0: *321 trying to use file: "=404" "/data/www/bee-platform/web=404"
2017/09/05 04:19:13 [debug] 3491#0: *321 http finalize request: 404, "/noexitsdir
?a=1" a:1, c:1
```

debug log 中很关键的一行，**trying to use file: "/index.php?a=1"** ， nginx 把包括参数的部分作为文件名，这个文件当然不存在了，于是就fallback到404，给客户端返回http 404。

如果把try_files指令修改成`try_files $uri $uri/ /index.php =404;` ,去掉参数部分，同样的请求，就会返回index.php文件的内容，而不是404。原理和前面分析的一样。

### 示例3：返回index.php文件内容

请求“http://local.bee.test/noexitsdir”,返回

```
index.php文件的内容
```

原因同上，这时候文件index.php存在（没有参数），同时try_files指令中`try_files $uri $uri/ /index.php$is_args$args =404;`  ， `index.php$is_args$args`不是最后一个参数，因此不会执行内部跳转，所以就直接返回文件的文件。





