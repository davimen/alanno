<div class="sitepath"><?php include("modum/sitePath.php");?></div>
<div class="left">
    <?php include("modum/left.php");?>
</div>
<div class="right">
<?php
include("modum/sitePath.php");
?>
<?php
    // kiem tra neu chuyen khoan
    if($_SESSION["payment_id"]==1)
    {
      include("modum/order/order_bank_transfer.php");

    }else if ($_SESSION["payment_id"]==2)
    {
      //include("order_credit_card.php");
      include("modum/order/order_bank_transfer.php");
    }
    else
    {
      //include("order_paypal.php");
      include("modum/order/order_bank_transfer.php");
    }

?>

</div>
<div class="clear"></div>
