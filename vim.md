1. **view** filename   or  **vim -R** filename   只读方式打开一个文件

2. **ZZ**  or  **:wq**    save file and exit

   1. > :q!   don`t  save and exit .    ！（感叹号）字符有时用来表示希望忽略某些类型的自动检查。这里，！告诉vim不要检查是否保存了数据。

3. vi命令和ex命令

   1. > 大部分vim命令模式下的命令都是**vi命令**。如w,j,h。   **ex命令**是以:(冒号)开头的命令。如 :%s/a/b/gc

4. **Return**  and    **+**  光标移到下一行的开头         **-**  光标移到上一行的开头

5. **w**     cursor to next word start.        **e**  cursor to next word end.       **b**  cursor to before word start