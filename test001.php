<?php
$str1 = "haha ni hao 123 456 789";
$pa = '/(\d+)/i';
echo preg_replace($pa,"x",$str1);
?>