<?php
$next="<b>&nbsp;&rsaquo;&rsaquo;&nbsp;</b>";
$path="";
switch($page)
{
	case "trang-chu":
	$path="Trang chủ";break;	

    case "checkout":
	$path="Đặt hàng";break;

    case "checkout-complete":
	$path="Đặt hàng thành công";break;

    case "login":
	$path="Đăng nhập";break;

    case "register":
	$path="Đăng ký";break;

    case "account":
	$path="Tài khoản";break;

    case "change-password":
    $path="Đổi mật khẩu";break;

    case "forgot-password":
	$path="Quên mật khẩu";break;

    case "custormer-checkout":
	$path="Đặt hàng";break;

    case "custormer-checkout-step2":
	$path="Đặt hàng";break;

	default:$next="";$path="";
}
?>
<div style="padding-top: 10px; padding-bottom: 10px; color: #255887; font-size: 13px; border-bottom: 0px solid #f1f1f1; margin-bottom: 10px">
<a class="sitePath" href="trang-chu.html" id="rootnav">&nbsp;<img src="style/images/hungphu/icon_home1.gif" width="15" height="12" alt="" border="0" />&nbsp;Trang chủ</a>
<?php

       if($page=='lien-he'){
          echo $next2;
          echo'<a class="sitePath" href="lien-he.html" id="rootnav">'.$path.'</a>';
        }
        else if($page=='login')
        {
         echo $next2;
         echo '<a class="sitePath" href="login.html" id="rootnav">'.$path.'</a>';

        }
        else if($page=='change-password')
        {
         echo $next2;
         echo '<a class="sitePath" href="change-password.html" id="rootnav">'.$path.'</a>';

        }
        else if($page=='register')
        {
         echo $next2;
         echo '<a class="sitePath" href="register.html" id="rootnav">'.$path.'</a>';

        }

        else if($page=='checkout')
        {
         echo $next2;
         echo '<a class="sitePath" href="checkout.html" id="rootnav">'.$path.'</a>';

        }

        else if($page=='custormer-checkout')
        {
         echo $next2;
         echo '<a class="sitePath" href="custormer-checkout.html" id="rootnav">'.$path.'</a>';

        }

        else if($page=='custormer-checkout-step2')
        {
         echo $next2;
         echo '<a class="sitePath" href="custormer-checkout.html" id="rootnav">'.$path.'</a>';

        }

        else if($page=='checkout-complete')
        {
         echo $next2;
         echo '<a class="sitePath" href="checkout.html" id="rootnav">'.$path.'</a>';

        }

        else if($page=='account')
        {
         echo $next2;
         echo '<a class="sitePath" href="account.html" id="rootnav">'.$path.'</a>';

        }
        else if($page=='forgot-password')
        {
         echo $next2;
         echo '<a class="sitePath" href="forgot-password.html" id="rootnav">'.$path.'</a>';

        }      

     
?>
</div>
