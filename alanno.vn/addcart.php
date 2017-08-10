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

$product_id = $_GET["product_id"];
$quantity   = $_GET["quantity"];
$price      = $_GET["price"];
$time_dk    = time();

$rst = $dbf->getDynamic("shoppingcart","user = '".$_SESSION['login']."' and productid=".$product_id." ","");
if($dbf->totalRows($rst)>0)
{
            $row_item_cart = $dbf->nextData($rst);
            $quantity_cart = $row_item_cart["quantity"];
            // lay quntity_product
            $info_product = $dbf->getInfoColum("article",$product_id);

            if($info_product["pro_quantity"]>($quantity_cart+$quantity))
            {
              $value = array("quantity"=>"quantity+".$quantity);
            }else
            {
              $value = array("quantity"=>$info_product["pro_quantity"]);
            }


            $action = "";
            $where = "user = '".$_SESSION["login"]."' and productid = ".$product_id."";
            $affect=$dbf->updateTable("shoppingcart",$value,$where);
            //$affect=$dbf->updateTable("shoppingcart",array("quantity"=>"quantity"+$quantity),"username='".$_SESSION["login"]."'");
}else
{

$col=array("productid"=>$product_id,"price"=>$price,"quantity"=>$quantity,"user"=>$_SESSION["login"],"dateorder"=>$time_dk);
$affect=$dbf->insertTable("shoppingcart",$col);

}

$rst = $dbf->getDynamic("shoppingcart","user = '".$_SESSION['login']."'","");
$total_cart = 0;
while($row = $dbf->nextData($rst)){
    $total_cart = $total_cart + $row['quantity'];
}
echo $total_cart;

?>