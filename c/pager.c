#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h> // - standard symbolic constants and types
#include <signal.h>
#include <sys/wait.h>

int main(int argc, char const *argv[])
{

	int fd[2];
	FILE *fp;
	pid_t pid;
	char line[1024]; // 为啥这里定义成char * line 就会报错
	char * filename;
	char * pager;

	filename = "./monitor.sql";
	fp = fopen(filename, "r");
	if (fp == NULL) {
		printf("%s\n", "error");
	}

	pipe(fd);
	pid = fork();
	if (pid > 0) {
		close(fd[0]);
		while (fgets(line, 1024, fp) != NULL) {
			printf("%s\n", line);
			write(fd[1], line, strlen(line));
		}
		close(fd[1]);
	} else {
		close(fd[1]);
		// read(fd[0], line, 1024);
		// printf("%s\n", line);
		// https://stackoverflow.com/questions/15102992/what-is-the-difference-between-stdin-and-stdin-fileno
		if (dup2(fd[0], STDIN_FILENO ) != STDIN_FILENO) {
			printf("%s\n", "dup2 error" );
		}

		pager = "/bin/more";
		char * argv0 = "more";
		if (execl(pager, argv0, (char *)0) < 0 ) {
			printf("%s\n", "execl error");
		}
		exit(0);
	}



	return 0;
}