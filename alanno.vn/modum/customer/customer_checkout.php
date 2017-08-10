<?php include("modum/sitePath.php");?>
<div class="title_header">Kiểm tra đặt hàng</div>
<div class="content_checkout">
 <?php
   if($_SESSION["Free"]==1)
   {

   if($total_cart==0)
     {
       ?>
       <div class="step-container" style="margin-bottom: 0px; margin-top: 0px;">
        <h1 class="title_checkout">Giỏ hàng của bạn là trống rỗng, vì vậy bạn không thể tiến hành để kiểm tra</h1>
      </div>
       <?php

     }else
     {

   ?>
            <div class="step-container">
            <h1 class="title_checkout">1. Địa chỉ </h1>
            <br class="clear" />

            <div id="container_address2">
                 <?php include("customer_address.php");?>
            </div>
            <div class="clear"></div>

        </div>

        <div class="step-container">
           <h1 class="title_checkout"><span style="float: left"> 2. Thanh toán và Vận chuyển</span></h1>
           <div class="clear"></div>
        </div>

      <div class="step-container" style="margin-bottom: 0px;">
        <h1 class="title_checkout">3. Xem xét và đặt hàng</h1>
        <div class="clear"></div>
      </div>

     <?php
   }
   }else
   {
     $html->redirectURL("checkout.html");
   }
 ?>
 </div>

