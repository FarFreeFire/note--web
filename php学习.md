访问对象中的成员
对象中包含成员属性和成员方法，访问对象中的成员和访问数组中的元素类似，只能通过对象的引用来访问对象中的成员。但还要使用一个特殊的运算符号->来完成对象成员的访问，访问对象中成员的语法格式如下所示：
变量名 = new 类名(参数);   //实例化一个类
变量名 -> 成员属性 = 值;   //为成员属性赋值
变量名 -> 成员属性;           //直接获取成员属性的值
变量名 -> 成员方法();        //访问对象中的成员方法

下面通过一个示例来演示一下：
```php
<?php
    class Website{
        public $name, $url, $title;
        public function demo(){
            echo '成员方法 demo()';
        }
    }

    $student = new Website();
    $student -> name = 'C语言中文网';
    $student -> url = 'http://c.biancheng.net/php/';
    $student -> title = '实例化对象';

    echo $student -> name.'<br>';
    echo $student -> url.'<br>';
    echo $student -> title.'<br>';
    $student -> demo();
?>
```

运行结果如下：
C语言中文网
http://c.biancheng.net/php/
实例化对象
成员方法 demo()


php中我们一般是先声明一个类，然后用这个类去实例化对象！$this 的含义是表示实例化后的具体对象！$this->表示在类本身内部使用本类的属性或者方法。‘->’符号是“插入式解引用操作符”（infix dereference operator）。换句话说，它是调用由引用传递参数的子程序的方法（当然，还有其它的作用）。正如我们上面所提到的，在调用PHP的函数的时候，大部分参数都是通过引用传递的。

比如我们声明一个User类！它只含有一个属性 $name;

```php
<?php
class User
{
   public $_name;
}
?>
　　现在，我们给User类加个方法。就用getName()方法，输出$name属性的值吧！

<?php
class User
{
      public $name;
      function getName()
      {
             echo $this->name;
      }
}
//如何使用呢？
$user1 = new User();
$user1->name = '张三';
$user1->getName();        //这里就会输出张三！
$user2 = new User();
$user2->name = '李四';   
$user2->getName();       //这里会输出李四！
?>
```

上面创建了两个User对象。分别是 \$user1 和   \$user2 。

当调用 \$user1->getName()的时候。   上面User类中的代码 echo \$this->name ; 就是相当于是   echo $user1->name
