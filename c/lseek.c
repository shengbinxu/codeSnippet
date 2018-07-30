#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h> // - standard symbolic constants and types
#include <signal.h>
#include <sys/wait.h>
#include <fcntl.h>
#include <errno.h>

int main(int argc, char const *argv[])
{
	char buf1[] = "abcdefghij";
	char buf2[] = "ABCDEFGHIJ";
	int fd;
	// http://man7.org/linux/man-pages/man2/open.2.html
	if ((fd = open("file.hole",O_CREAT |O_RDWR|O_APPEND),S_IRWXU) <0) {
		printf("%s\n", "error");
	}
	write(fd,buf1,10);

	// lseek(fd,16384,SEEK_SET);
	
	write(fd,buf2,10);
	char * enter = "\n";
	write(fd,enter,strlen(enter));
	char line[100];
	printf("%d\n", lseek(fd,0,SEEK_CUR));
	lseek(fd,0,SEEK_SET);
	int n = read(fd,line,10);
	printf("%d\n", n);
	if (n <0) {
		printf("%s\n", "read error");
		fprintf(stderr,"error in CreateProcess %s ",strerror(errno));
	}
	printf("%s\n", line);

	exit(0);

	return 0;
}