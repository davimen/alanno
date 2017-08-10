<?php
session_start();
include str_replace('\\','/',dirname(__FILE__)).'/class/defineConst.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.BUSINESSLOGIC.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.CSS.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.JAVASCRIPT.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.HTML.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.utilities.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.SINGLETON_MODEL.php';

$dbf=SINGLETON_MODEL::getInstance("BUSINESSLOGIC");
$html=SINGLETON_MODEL::getInstance("HTML");
$css=SINGLETON_MODEL::getInstance("CSS");
$js=SINGLETON_MODEL::getInstance("JAVASCRIPT");
$utl=SINGLETON_MODEL::getInstance("UTILITIES");

$maphieu = $_GET["maphieu"];
$rst = $dbf->getDynamic("phieugiamgia","maphieu = '".$maphieu."' and status=1","id desc limit 0,1");
$total = $dbf->totalRows($rst);
if($total>0)
{
while($row = $dbf->nextData($rst)){

   $price       = $row["price"];
   $price_format = $utl->format($price);
   $price_start = $row["price_start"];
   $price_start_format = $utl->format($price_start);
   $date_end    = $row["date_end"];
   $today = time();
   if($today<=$date_end)
   {
     echo "Phiếu giảm giá có giá trị: <b>".$price_format."</b> VNĐ";
     echo ". Áp dụng cho đơn hàng có giá trị từ :<b>".$price_start_format."</b> VNĐ trở lên";
     echo ". Xin quý khách lưu ý.";
     $_SESSION["phieugiamgia_id"]= $row["id"];
     $_SESSION["price_phieugiamgia"]= $price;
     $_SESSION["price_start_phieugiamgia"]= $price_start;

   }else
   {
     echo "Phiếu giảm giá đã hết hạn. Xin quý khách vui lòng kiểm tra lại";
   }
}
}else
{
  echo "Phiếu giảm giá không đúng. Xin quý khách vui lòng kiểm tra lại";
}
?>