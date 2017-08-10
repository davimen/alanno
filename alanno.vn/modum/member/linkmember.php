 <?php
  if($_SESSION["Free"]==1)
  {
  ?>
  <span><a href="/login.html"> Đăng nhập</a> </span><span>&nbsp;hoặc&nbsp;&nbsp;</span><span><a href="register.html">Đăng ký </a></span>
   <?
  }else{
  ?>
   <span>Chào mừng&nbsp;</span><span><b><a href="account.html"><?php echo $_SESSION["member_firstname"]?>&nbsp;<?php echo $_SESSION["member_lastname"]?></a></b></span><span>&nbsp;|&nbsp;</span><span><a href="<?php echo md5("signout".date("dmH"))?>">Thoát</a></span>

   <?
   }
   ?>
   <span>&nbsp;|&nbsp;&nbsp;</span>
   <?php
    $rst = $dbf->getDynamic("shoppingcart","user = '".$_SESSION['login']."'","");
    $total_cart = 0;
    while($row = $dbf->nextData($rst)){
        $total_cart = $total_cart + $row['quantity'];
    }
    if($total_cart==0)
    {
          //echo '<a class="viewcart" href="modum/shopingcart/viewcart.php"><img class="img_cart" src="style/images/designercollection/empty_cart_icon.png" width="30" height="25" alt="" align="absmiddle" />&nbsp;';
          echo'<a class="viewcart" href="modum/shopingcart/viewcart.php"><img style="float:left;padding:0px" src="style/images/designercollection/empty_cart_icon.png" width="40" height="28" alt="" align="absmiddle" />';

    }else
    {     echo'<a class="viewcart" href="modum/shopingcart/viewcart.php"><img style="float:left;padding:0px" src="style/images/designercollection/empty_cart_icon.png" width="40" height="28" alt="" align="absmiddle" />';
    }
   ?>

   <span>&nbsp;Giỏ hàng &nbsp;(</span><span id="itemcart"><?=$total_cart?></span><span>)&nbsp;sản phẩm</span></a>
   <span>&nbsp;|&nbsp;</span>
   <span><a href="checkout.html">Đặt hàng</a></span>
