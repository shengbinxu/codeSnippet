#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h> // - standard symbolic constants and types
#include <signal.h>
#include <sys/wait.h>
#include <fcntl.h>

int main(int argc, char const *argv[])
{
	int fd1[2];
	int fd2[2];
	pid_t p;
	char input_str[100];
	char fixed_str[] = "forgeeks.org";

	if (pipe(fd1) == -1) {
		fprintf(stderr, "%s\n", "Pipe Failed");
	}

	// open返回的文件描述符一定是最小的未用描述符数值。如果，关闭stdout,打开文件1.txt，他的文件描述符是1，因此，printf的输出，也写入到文件1.txt中了 
	
	// close(STDOUT_FILENO);
	// int fd = open("1.txt",O_RDWR |O_CREAT| O_TRUNC);
	// printf("%d\n", 11);
	// printf("%s\n", "fd");
	// char * str = "1";
	// write(fd,str,strlen(str));
	// exit(0);


	// pipe会生成两个文件描述符fd[0] fd[1]  fd[1]的输出是fd[0]的输入
	
	// write(fd1[1], "hello", 100);
	// char str[100];
	// read(fd1[0], str, 100);
	// printf("%s\n", str);

	if (pipe(fd2) == -1) {
		fprintf(stderr, "%s\n", "Pipe Failed");
	}

	scanf("%s", input_str);

	p = fork(); // fork之后，哪些会在子进程中复制，哪些会共享？

	if (p < 0) {
		fprintf(stderr, "%s\n", "fork Failed");
	}

	printf("pid:%d\n", p);
	if (p > 0) {
		char concat_str[100];
		close(fd1[0]);// 如果不关闭，会怎么样？
		write(fd1[1], input_str, 100);
		close(fd1[1]); //写之后关闭，代表写入结束，读的时候会返回一个'\0'，代表读完

		wait(NULL); // 等待子进程结束了，再执行后面的程序

		close(fd2[1]);
		printf("%s\n", "before read");
		read(fd2[0], concat_str, 100);// read()会阻塞，直到管道中有了数据.
		printf("%s\n", "wait");
		printf("Concatenated string %s\n", concat_str);
		close(fd2[0]);
	} else {
		close(fd1[1]);
		printf("%s\n", "clild");
		char concat_str[100];
		read(fd1[0], concat_str, 100);
		printf("receive str:%s\n", concat_str);
		close(fd1[0]);

		close(fd2[0]);

		int k = strlen(concat_str);
		int i = 0;
		for (i = 0; i < strlen(fixed_str); ++i)
		{
			concat_str[k++] = fixed_str[i];
		}
		concat_str[k] = '\0';
		write(fd2[1], concat_str, strlen(concat_str) + 1);
		close(fd2[1]);

		sleep(2);

		exit(0);
	}

	return 0;
}
