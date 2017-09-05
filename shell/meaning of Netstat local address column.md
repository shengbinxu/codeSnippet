## netstat命令Local Address字段的意义

> ### 参考https://unix.stackexchange.com/questions/139938/meaning-of-netstat-local-address-column/139950#139950?newreg=40cf5d013b2e4800933890a3197108ac
>
> 



As an example, apache has it's Listen option which tells it which address and port to listen on. Depending on how this is set, apache will listen on any IP address, a specific address:-

```
Listen *:80
Listen 0.0.0.0:80
Listen 127.0.0.1:80
Listen 192.168.0.5:80
```


The above options show up as:-

```
:::80
0.0.0.0:80
127.0.0.1:80
192.168.0.5:80
```


and translate to:-

- Listen on any IP address (IPv4 or IPv6)
- Listen on any IPv4 address on that server
- Listen on IPv4 localhost only
- Listen on external IPv4 address 192.68.0.5

You could configure your service to listen on only the localhost interface if you don't want anyone external to access it. For example, if you're running a LAMP server you'd have apache listening on all IP addresses (so that your users can access it) while a mysql database could be configured to be accessible only from localhost (using it's bind=127.0.0.1 directive). This way php running on the same server will be able to access the database while external (and untrusted) users will not be able to access it.