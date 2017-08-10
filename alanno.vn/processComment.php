<?php
session_start();
header("Cache-Control: no-cache, must-revalidate");
header("Expires:-1");
include str_replace('\\','/',dirname(__FILE__)).'/class/defineConst.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.BUSINESSLOGIC.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.SINGLETON_MODEL.php';

$dbf=SINGLETON_MODEL::getInstance("BUSINESSLOGIC");
$msg="fail";
	$txtAddedBy=$_POST["txtAddedBy"];
    $txtAddedByEmail=$_POST["txtAddedByEmail"];
    $txtAddedContent=$_POST["txtAddedContent"];
    $product_id=$_POST["product_id"];

	if(isset($_POST["product_id"]))
	{
        $affect=$dbf->insertTable("product_comment",array("title"=>$txtAddedBy,"email"=>$txtAddedByEmail,"content"=>$txtAddedContent,"product_id"=>$product_id,"datecreated"=>time()));
        $msg="success";
	}
	echo $msg;
?>