#include <stdio.h>
#include <arpa/inet.h>
int main() {
	int a = 0x04030201;
	printf("%i\n",a);
	char *p = (char *)&a;
	//int n_a = htonl(a);
	//char *p = (char *)&n_a;
	//char * s = "hello World!";
	printf("%i\n", p[0]);
	printf("%x\n", p[1]);
	printf("%x\n", p[2]);
	printf("%i\n", p[3]);
	getchar();
	return 0;
}
