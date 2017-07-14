#include <stdio.h>
#include <sys/stat.h>
#include <fcntl.h>
int main(int argc, char *argv[])
{
    int fw=open("chinaisbetter.txt", O_APPEND|O_WRONLY);
    //标准输入这个fd指向文件句柄，所以printf会输出到文件中。
    dup2(fw,1);
    printf("Are you kidding me? \n");
}