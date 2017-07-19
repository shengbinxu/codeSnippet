-   ubuntu16开始，systemd替代了upstart作为启动项管理工具
     > from ubuntu16.04, "systemd" is used instead of "upstart" as the default service management framework. 

-   查看所有的开机启动项列表：

    systemctl list-unit-files --type=service
    > 注意，一个单元显示为“enabled”，并不等于对应的服务正在运行，而只能说明它可以被开启。要获得某个特定服务的信息，以 apache 为例，输入以下命令：

    ```
    systemctl  status apache2.service
    ● apache2.service - LSB: Apache2 web server
       Loaded: loaded (/etc/init.d/apache2; generated; vendor preset: enabled)
      Drop-In: /lib/systemd/system/apache2.service.d
               └─apache2-systemd.conf
       Active: active (running) since Thu 2017-05-04 01:40:58 PDT; 37min ago
         Docs: man:systemd-sysv-generator(8)
      Process: 832 ExecStart=/etc/init.d/apache2 start (code=exited, status=0/SUCCESS)
        Tasks: 6 (limit: 19660)
       CGroup: /system.slice/apache2.service
               ├─909 /usr/sbin/apache2 -k start
               ├─912 /usr/sbin/apache2 -k start
               ├─913 /usr/sbin/apache2 -k start
               ├─914 /usr/sbin/apache2 -k start
               ├─915 /usr/sbin/apache2 -k start
               └─916 /usr/sbin/apache2 -k start

    May 04 01:40:57 ubuntu systemd[1]: Starting LSB: Apache2 web server...
    May 04 01:40:57 ubuntu apache2[832]:  * Starting Apache httpd web server apache2
    May 04 01:40:57 ubuntu apache2[832]: AH00558: apache2: Could not reliably determine the server's fully qualified
    May 04 01:40:58 ubuntu apache2[832]:  *
    May 04 01:40:58 ubuntu systemd[1]: Started LSB: Apache2 web server.
    ```

- 其他常用命令：
    ```
    立即激活单元：
    # systemctl start <单元>
    立即停止单元：
    # systemctl stop <单元>
    重启单元：
    # systemctl restart <单元>
    重新加载配置：
    # systemctl reload <单元>
    输出单元运行状态：
    $ systemctl status <单元>
    检查单元是否配置为自动启动：
    $ systemctl is-enabled <单元>
    开机自动激活单元：
    # systemctl enable <单元>
    取消开机自动激活单元：
    # systemctl disable <单元>
    ```

-  可以通过修改/etc/rc.local的方式来添加开机启动项
    > 优点是通用性比较强，能兼容debian、ubuntu。可以用代码控制，比较灵活。

    ```
    #!/bin/sh
    # 本文件经由查违章git发布, 请勿擅自修改

    # This script will be executed *after* all the other init scripts.
    # You can put your own initialization stuff in here if you don't
    # want to do the full Sys V style init stuff.

    touch /var/lock/subsys/local

    ### Add the iptables-sh rule at 2015-11-12 15:11:48
    /etc/iptables/init.sh

    /etc/init.d/php-fpm start
    sleep 35
    /etc/init.d/nginx start

    #/home/violation/deploy/Tor/start.sh

    /usr/bin/3proxy /etc/3proxy.cfg

    #su violation -c "/usr/local/php_tsf/bin/php /data/www/wwwroot/tsf_chaweizhang/framework/bin/swoole.php chaweizhangHttpServ start"

    su - violation -c "VBoxManage startvm --type headless genymotion_vbox86p_4.1.1_150610_092200"
    su - violation -c "adb connect localhost"

    /usr/bin/rsync --daemon --config /etc/rsyncd.conf

    alias "crontab"="echo Please update '/var/spool/cron/*' with chaweizhang git repo then reload crond\!"

    /usr/bin/python /usr/bin/supervisord -c /home/violation/config/supervisor/supervisord.conf
    ```


    ​```

-  把一个程序添加到开机启动项，以openresty为例:

    - 新建文件/etc/init.d/nginx,文件内容https://gist.githubusercontent.com/vdel26/8805927/raw/249f907e465e98ac099437025218a15e55a34b4c/nginx
    - chmod 755 /etc/init.d/nginx
    - sudo update-rc.d nginx defaults 95 (95是启动顺序，可以任意指定)
    - 接下来

        ```
        systemctl list-unit-files|grep nginx 

        systemctl is-enabled nginx           

        systemctl status nginx    

        sudo systemctl start nginx.service

        pstree
        ```

-   使用systemd管理服务--**最重要的是实现服务挂掉自启动**

    > 使用systemd管理swoole服务
    >
    > Swoole的服务器程序可以编写一段service脚本，交由systemd进行管理。实现故障重启、开机自启动等功能。
    >
    > 具体参见https://wiki.swoole.com/wiki/page/699.html