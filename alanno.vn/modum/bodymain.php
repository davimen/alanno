<?php
    
    switch ($page_url) {
      case "checkout":
            include("shopingcart/checkout.php");
            break;

      case "checkout-complete":
            include("shopingcart/checkout_complete.php");
            break;   

      case "register":            	  
            include("member/_register.php");
            break;

      case "account":
            include("member/_infoAccount.php");
            break;

      case "change-password":
            include("member/_changepwd.php");
            break;

       case "forgot-password":
            include("member/forgot_password.php");
            break;

       case "custormer-checkout":
            include("customer/customer_checkout.php");
            break;

       case "custormer-checkout-step2":
            include("customer/customer_checkout_step2.php");
            break;

      default :
            include("member/_login.php");
            break;
   }
 ?> 