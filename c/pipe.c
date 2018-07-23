#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h> // - standard symbolic constants and types
#include <signal.h>
#include <sys/wait.h>

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
	if (pipe(fd2) == -1) {
		fprintf(stderr, "%s\n", "Pipe Failed");
	}

	scanf("%s",input_str);

	p = fork(); // fork之后，哪些会在子进程中复制，哪些会共享？

	if (p <0) {
		fprintf(stderr, "%s\n", "fork Failed");
	}

	printf("pid:%d\n", p);
	if (p >0) {
		char concat_str[100];
		close(fd1[0]);// 如果不关闭，会怎么样？
		write(fd1[1],input_str,100);
		close(fd1[1]); //写之后关闭，代表写入结束，读的时候会返回一个'\0'，代表读完

		wait(NULL); // 等待子进程结束了，再执行后面的程序

		close(fd2[1]);
		printf("%s\n","before read");
		read(fd2[0], concat_str, 100);// read()会阻塞，直到管道中有了数据.
		printf("%s\n","wait");
        printf("Concatenated string %s\n", concat_str);
        close(fd2[0]);
	} else {
		close(fd1[1]);
		printf("%s\n", "clild");
		char concat_str[100];
		read(fd1[0],concat_str,100);
		printf("receive str:%s\n", concat_str);
		close(fd1[0]);

		close(fd2[0]);

		int k = strlen(concat_str);
		int i =0;
		for (i = 0; i < strlen(fixed_str); ++i)
		{
			concat_str[k++] = fixed_str[i];
		}
		concat_str[k] = '\0';
		write(fd2[1],concat_str,strlen(concat_str) + 1);
		close(fd2[1]);

		sleep(2);

		exit(0);
	}

	return 0;
}
