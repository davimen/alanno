<div class="section_wrapper mcb-section-inner">
<div class="pro_c">
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
</div>
<div class="clear"></div>
