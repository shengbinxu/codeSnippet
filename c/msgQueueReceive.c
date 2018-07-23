#include <stdio.h>
#include <stdlib.h>
#include <string.h>
#include <unistd.h> // - standard symbolic constants and types
#include <signal.h>
#include <sys/wait.h>
#include <sys/msg.h>

int main(int argc, char const *argv[])
{
	key_t key;
	int msgid;
	struct mesg_buffer
	{
		long mesg_type;
		char mesg_text[100];
	} message;

	/*
	 关于ftok函数，先不去了解它的作用来先说说为什么要用它，共享内存，消息队列，信号量它们三个都是找一个中间介质，来进行通信的，
	 这种介质多的是。就是怎么区分出来，就像唯一一个身份证来区分人一样。你随便来一个就行，就是因为这。
	 只要唯一就行，就想起来了文件的设备编号和节点，它是唯一的，但是直接用它来作识别好像不太好，不过可以用它来产生一个号。ftok()就出场了。ftok函数具体形式如下：
	*/
	key = ftok("progfile",65);
	if (key == -1) {
		fprintf(stderr, "%s\n", "ftok error");
	}
	printf("%d\n",key);

	msgid = msgget(key,  IPC_NOWAIT | IPC_CREAT);
	printf("%d\n", msgid);
	msgrcv(msgid, &message, sizeof(message), 1, 0);
	printf("Data Received is : %s \n", message.mesg_text);
	// to destroy the message queue
	msgctl(msgid, IPC_RMID, NULL);
	return 0;
}