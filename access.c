#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h> // - standard symbolic constants and types
#include <signal.h>
#include <sys/wait.h>
#include <fcntl.h>

int main(int argc, char const *argv[])
{
	if (argc != 2) {
		printf("%s\n", "参数错误");
	}
	if (access(argv[1],R_OK)) {
		printf("%s\n", "access error");
	} else {
		printf("%s\n", "access ok");
	}

	if (open(argv[1],O_RDONLY) <0) {
		printf("%s\n","open error" );
	} else {
		printf("%s\n", "opening for reading");
	}

	return 0;
}