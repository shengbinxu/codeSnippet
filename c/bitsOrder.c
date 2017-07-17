#include <stdio.h>
#include<iostream>  
#include <WinSock2.h>#include <arpa/inet.h>
using namespace std;
int main() {
	int a = 0x04030201;
	printf("%i\n",a);
	int n_a = htonl(a);
	char *p = (char *)&a;
	//char * s = "hello World!";
	printf("%i\n", p[0]);
	printf("%x\n", p[1]);
	printf("%x\n", p[2]);
	printf("%i\n", p[3]);
	getchar();
	return 0;
}