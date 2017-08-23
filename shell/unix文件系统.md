## suid

> 参考http://www.linuxnix.com/suid-set-suid-linuxunix/	
>
> **SUID** (**S**et owner **U**ser **ID** up on execution) is a special type of file permissions given to a file. Normally in Linux/Unix when a program runs, it inherit’s access permissions from the logged in user. SUID is defined as giving temporary permissions to a user to run a program/file with the permissions of the file owner rather that the user who runs it

先举一个例子：

ubuntu下，使用`passwd`命令可以修改当前登录用户的密码，修改完之后密码存储在`/etc/shadow`文件中。涉及到的文件权限如下：

```
ls -al /usr/bin/passwd
-rwsr-xr-x 1 root root 54256 Sep 20  2016 /usr/bin/passwd

ls -al /etc/shadow
-rw-r----- 1 root shadow 1485 Aug 23 14:56 /etc/shadow

sudo cat /etc/shadow|grep xushengbin
xushengbin:$6$E9LHZu30$4FoG4e4h79grxHOeJaGAi2F/D3pJeTnu0qct/XFisyd.kQAWRM0u4PJO.2lTeFw9AzowpiYzRQ7ZYGe7qROn2.:17401:0:99999:7:::
```

有两点：

1.  `/usr/bin/passwd` 程序的权限中有个`s`，这个可执行文件的owner是root用户
2.  执行`passwd`命令修改密码时，使用当前用户（非sudo）执行，但是，却有权限往`/etc/shadow` (only root can write) 文件中写入

**关键点来了**：

**suid 给用户一种权限：可以使用文件owner的权限来执行一个程序，而不是使用运行用户的权限来执行**

结合前面的例子：passwd可执行程序，使用的是这个程序的owner（root用户）来执行程序的，而不是使用当前用户（xushengbin） 来执行这个程序。

再看一个我自己写的测试demo:

```
#include <stdio.h>
#include <stdlib.h>
#include <sys/types.h>
#include <unistd.h>

int main()
{
   int current_uid = getuid();
   printf("My UID is: %d. My GID is: %d\n", current_uid, getgid());
   setuid( 0 );
   FILE *f = fopen("/root/suid.txt", "w");
   if (f == NULL)
   {
       printf("Error opening file!\n");
       exit(1);
   }
   
   /* print some text */
   const char *text = "Write this to the file";
   fprintf(f, "Some text: %s\n", text);
   printf("My UID is: %d. My GID is: %d\n", getuid(), getgid());
   return 0;
}

```

```
sudo ls -al /root/suid.txt
-rw-r--r-- 1 root root 34 Aug 23 15:35 /root/suid.txt
```

执行

```
gcc suid.c -o suid
chown root:root suid
chmod +s suid

ls -al suid
-rwsrwsr-x 1 root root 8704 Aug 23 15:34 suid

./suid 
My UID is: 1000. My GID is: 1000
My UID is: 0. My GID is: 1000

sudo cat /root/suid.txt
Some text: Write this to the file
```

**使用非sudo权限，往只有root用户才有写入权限的文件写入成功！这就是suid的作用**

