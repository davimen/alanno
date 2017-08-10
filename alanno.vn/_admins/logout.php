<?
//ob_start();
session_start();
    include str_replace('\\','/',dirname(__FILE__)).'/class/class.DEFINE.php';
	include str_replace('\\','/',dirname(__FILE__)).'/class/class.DBFUNCTION.php';
	include str_replace('\\','/',dirname(__FILE__)).'/class/class.SINGLETON_MODEL.php';
	$dbf=SINGLETON_MODEL :: getInstance("DBFUNCTION");

if($_SESSION["user_login"]){
    $thoigianlogout = date("d-m-Y h:i:s",time());
    $online = "Offline";
    $dbf->updateTable('webmaster',array('online'=>$online,"thoigianlogout"=>$thoigianlogout),"id=".$_SESSION["user_login"]["id"]."");	
	unset("user_login");	
	echo "<script>window.location.href='login.php'</script>";
		
}
else
{
    $thoigianlogout = date("d-m-Y h:i:s",time());
    $online = "Offline";
    $dbf->updateTable('webmaster',array('online'=>$online,"thoigianlogout"=>$thoigianlogout),"id='".$_SESSION["user_login"]["id"]."'");
    header( "Location: login.php" );
}
//ob_end_flush();

?>