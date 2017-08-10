<?php
$PageSize=15;
$PageNo=(isset($_GET['PageNo'])?$_GET['PageNo']:'');
$Pagenumber=10;
$ModePaging="Full";
$arraySMTPSERVER=array("host"=>"alona.vn","user"=>"info@alona.vn","password"=>"79Qfp3sy","from"=>"alona.vn");
$next1="<b>&nbsp;&rsaquo;&nbsp;</b>";
$next2="<b>&nbsp;&rsaquo;&rsaquo;&nbsp;</b>";

define("CURRENCY",(($lang=='vn')?"VNĐ":"AUD"))
?>