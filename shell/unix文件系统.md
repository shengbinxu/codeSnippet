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

```c
#include <stdio.h>
#include <stdlib.h>
#include <sys/types.h>
#include <unistd.h>

int main()
{
   printf("My UID is: %d. My GID is: %d\n", getuid(), getgid());
   printf("My Effective UID is: %d. My GID is: %d\n", geteuid(), getegid());
   //If the process has appropriate privileges, setuid() shall set the real user ID, effective user ID, and the saved set-user-ID of the calling process to uid.
   int result = setuid( 0 );
   printf("set uid result: %i\n",result);
   FILE *f = fopen("/root/suid.txt", "wb");
   if (f == NULL)
   {
       printf("Error opening file!\n");
       exit(1);
   }
   
   /* print some text */
   const char *text = "Write this to the file";
   fprintf(f, "Some text: %s\n", text);
   printf("My UID is: %d. My GID is: %d\n", getuid(), getgid());
   printf("My Effective UID is: %d. My GID is: %d\n", geteuid(), getegid());
   //when we open a file, the kernel performs its access tests based on the effective user and group IDs
   // The access and faccessat functions base their tests on the real user and group IDs
   if(access("/root/suid.txt", R_OK)==0)  printf("READ OK\n");  
   if(access("/root/suid.txt", W_OK)==0)  printf("WRITE OK\n");  
   if(access("/root/suid.txt", X_OK)==0)  printf("EXEC OK\n");  
   if(access("/root/suid.txt", F_OK)==0)   printf("File exist\n");
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
My Effective UID is: 0. My GID is: 0
set uid result: 0
My UID is: 0. My GID is: 1000
My Effective UID is: 0. My GID is: 0
READ OK
WRITE OK
File exist


sudo cat /root/suid.txt
Some text: Write this to the file
```

**使用非sudo权限，往只有root用户才有写入权限的文件写入成功！这就是suid的作用**

## real user ID、effective user ID

每个进程都有一系列IDS

- real user id、real group id    登录用户的id
- effective user id、effective group id 参考下面的介绍

> When we execute a program file, the effective user ID of the process is usually the
> real user ID, and the effective group ID is usually the real group ID. However, we can
> also set a special flag in the file’s mode word (st_mode) that says, ‘‘When this file is
> executed, set the effective user ID of the process to be the owner of the file (st_uid).’’
> Similarly, we can set another bit in the file’s mode word that causes the effective group
> ID to be the group owner of the file (st_gid). These two bits in the file’s mode word
> are called the set-user-ID bit and the set-group-ID bit.
>
> For example, if the owner of the file is the superuser and if the file’s set-user-ID bit
> is set, then while that program file is running as a process, it has superuser privileges.
> This happens regardless of the real user ID of the process that executes the file. As an
> example, the UNIX System program that allows anyone to change his or her password,
> passwd(1), is a set-user-ID program. This is required so that the program can write the
> new password to the password file, typically either /etc/passwd or /etc/shadow,
> files that should be writable only by the superuser. Because a process that is running
> set-user-ID to some other user usually assumes extra permissions, it must be written
> carefully.
>
> Returning to the stat function, the set-user-ID bit and the set-group-ID bit are
> contained in the file’s st_mode value. These two bits can be tested against the
> constants S_ISUID and S_ISGID, respectively.

## umask

> 默认情况下的umask值是022(可以用umask命令查看），此时你建立的文件默认权限是644(6-0,6-2,6-2)，建立的目录的默认权限是755(7-0,7-2,7-2)，可以用ls -l验证一下哦　现在应该知道umask的用途了吧，它是为了控制默认权限，不要使默认的文件和目录具有全权而设的
>
> 知道了umask的作用后，你可以修改umask的值了，例如:umask　024则以后建立的文件和目录的默认权限就为642,753了
>
> umask -S
> u=rwx,g=rwx,o=rx



```c
#include <stdio.h>
#include <stdlib.h>
#include <sys/types.h>
#include <unistd.h>
#include <sys/stat.h>
#include <fcntl.h>

#define RWRWRW (S_IRUSR|S_IWUSR|S_IRGRP|S_IWGRP|S_IROTH|S_IWOTH)
int
main(void)
{
    umask(0);
    //The permissions of the created file are (mode & ~umask)
    if (open("foo",O_CREAT|O_RDWR, RWRWRW) < 0)
        printf("creat error for foo");
    umask(S_IRGRP | S_IWGRP | S_IROTH | S_IWOTH);
    if (open("bar",O_CREAT|O_RDWR, RWRWRW) < 0)
        printf("creat error for bar");
    exit(0);
}
```

output:

```
 ls -alt {foo*,bar*}
-rw------- 1 xushengbin xushengbin 0 Aug 23 18:17 bar
-rw-rw-rw- 1 xushengbin xushengbin 0 Aug 23 18:17 foo
```

**open 创建的文件的权限是`mode & ~umask`**

## inode

### link-硬链接

> 参考https://www.ibm.com/developerworks/cn/linux/l-cn-hardandsymb-links/index.html

```c
#include <stdio.h>
#include <stdlib.h>
#include <sys/types.h>
#include <unistd.h>
#include <sys/stat.h>
#include <fcntl.h>

int main(void)
{
    if(linkat(AT_FDCWD,"./umask.c", AT_FDCWD,"./link_umask.c",0) < 0){
       printf("link error"); 
    }
    exit(0);
}
```

output:

```
 ls -ali
total 32
793447 drwxrwxr-x  2 xushengbin xushengbin 4096 Aug 24 11:02 .
262277 drwxrwxrwt 14 root       root       4096 Aug 24 11:04 ..
819763 -rwxrwxr-x  1 xushengbin xushengbin 8504 Aug 24 11:02 a.out
819761 -rw-rw-r--  1 xushengbin xushengbin  263 Aug 24 11:02 inode.c
819758 -rw-rw-r--  2 xushengbin xushengbin  522 Aug 24 10:57 link_umask.c
819758 -rw-rw-r--  2 xushengbin xushengbin  522 Aug 24 10:57 umask.c
//link_umask.c、umask.c的inode number一样

stat link_umask.c
  File: 'link_umask.c'
  Size: 522       	Blocks: 8          IO Block: 4096   regular file
Device: 801h/2049d	Inode: 819758      Links: 2
Access: (0664/-rw-rw-r--)  Uid: ( 1000/xushengbin)   Gid: ( 1000/xushengbin)
Access: 2017-08-24 11:02:28.775246891 +0800
Modify: 2017-08-24 10:57:57.174515790 +0800
Change: 2017-08-24 11:02:21.537418132 +0800
 Birth: -
// link之后，inode links加1，为2

//修改mask.c之后，link_mask.c的内容也跟着变化；反之也是。
```
c中link()函数，对应的就是linux的硬链接：

> 硬链接就是同一个文件使用了多个别名
>
> 硬链接可由命令 link 或 ln 创建
>
> 文件有相同的 inode 及 data block
>
> 不能对目录进行创建，只可对文件创建
>
> 删除一个硬链接文件并不影响其他有相同 inode 号的文件

**注意：不能跨文件系统创建硬链接**

```
df     
Filesystem     1K-blocks    Used Available Use% Mounted on
udev              992556       0    992556   0% /dev
tmpfs             202852   26676    176176  14% /run
/dev/sda1       19478160 8752336   9713344  48% /
tmpfs            1014244     156   1014088   1% /dev/shm
tmpfs               5120       4      5116   1% /run/lock
tmpfs            1014244       0   1014244   0% /sys/fs/cgroup
tmpfs             202848      32    202816   1% /run/user/119
tmpfs             202848       4    202844   1% /run/user/1000

//df命令：print file system type

link umask.c /dev/link_umask.c
link: cannot create link '/dev/link_umask.c' to 'umask.c': Invalid cross-device link
```

**查找有相同 inode 号的文件**

```shell
sudo find / -inum 819758
/tmp/inode_test/link_umask.c
/tmp/inode_test/umask.c
/home/xushengbin/link_umask.c
```

### 软连接

> 软链接有着自己的 inode 号以及用户数据块：见https://www.ibm.com/developerworks/cn/linux/l-cn-hardandsymb-links/index.html
>
> 与硬链接不同，软连接可以跨文件系统创建

## file times

```c
int main(void)
{
	struct stat statbuf;
    if (stat("./sandir.php", &statbuf) == -1) {
	    printf("stat error");
        exit(1);
    }
    printf("last-access time of file data %i\n",statbuf.st_atime);
    printf("last-modification time of file data %i\n",statbuf.st_mtime);
    printf("last-change time of i-node status %i\n",statbuf.st_ctime);
}
```

shell命令查看

```shell
ls -lu sandir.php //look at last-access times
-rwxr-xr-x 1 xushengbin xushengbin 146 Aug 24 13:04 sandir.php
ls -l sandir.php //look at last-modification times
-rwxr-xr-x 1 xushengbin xushengbin 146 Aug 24 13:04 sandir.php
ls -lc sandir.php  //the changed-status times
-rwxr-xr-x 1 xushengbin xushengbin 146 Aug 24 13:47 sandir.php
```