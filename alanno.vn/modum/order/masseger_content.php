<?php
  $str="<html>
      <head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
      <meta http-equiv='Content-Language' content=\en-us' />
      <style type='text/css'>
       body{font-family: Arial;font-size: 12px; color:#333333;}
      .clear { font-size:1px; clear:both;}
      .aroundEmail {width:980px; min-height:500px; background:#fff; padding:20px;}
      .header_mail {}
      .header_mail_left { float:left; width:70%; padding:20px 0px 20px}
      .header_mail_right { float:right; width:30%;padding:20px 0px 20px; text-align:right}
      .space1 {height:600px;}
      .space1 {height:20px;}
      .tableSendEmail .tdParent{ border-bottom: 1px solid #EBF1F6; height: 25px; text-align: left; vertical-align: middle; padding-left:10px;}
      .tableSendEmail .tdParent div{ clear:both; text-align: left; padding-left:20px; background: url(../Images/bullet_sanpham.jpg) left center no-repeat; text-transform: uppercase; font-weight: bold;}
      .tableSendEmail .tdParent div .link{ text-decoration: none;  color: #333333;}
      .tableSendEmail .tdParent div .link:hover{ color: #F5841E; text-decoration: none;}
      .tableSendEmail .tdItem{ border-bottom: 1px solid #EBF1F6; text-align: left; vertical-align: middle; padding:5px;}
      .tableSendEmail .tdItem_right{ border-bottom: 1px solid #EBF1F6; text-align: left; vertical-align: middle; padding:5px; border-right:1px solid #B4CDD7;}
      .tableSendEmail .tdItem_right .linkTitle{ font-size:13px; font-weight:bold; color:#333333; text-decoration: none;}
      .tableSendEmail .tdItem_right .linkTitle:hover{ font-size:13px; font-weight:bold; color:#F5841E; text-decoration: none;}
      </style>
      </head>
      <body>
      <div class='aroundEmail' style='clear:both; text-align:left;'>";
  $str.="
        <div class='header_mail'>
            <div class='header_mail_left'>
                ".$info["ADDRESS_MAIL_ORDER"]."
            </div>
            <div class='header_mail_right'>
                <h1>Receipt</h1>
                <h3>04/11/2012</h3>
            </div>
            <div class='clear'></div>
            <div class='space1'></div>

            <div class='header_mail_left'>
                <h3>MR JIM</h3>
                <h3></h3>

            </div>
            <div class='header_mail_right'>
                <h3>$1,600.00</h3>
            </div>
            <div class='clear'></div>
            <div class='space2'></div>

            <div class='header_mail_left' style='font-size:14px;'>
                 <strong>Payment Method: Cash</strong> <br/>
                 <strong>Invoice Number: 10018</strong>
            </div>
            <div class='header_mail_right'></div>
            <div class='clear'></div>
            <div class='space2'></div>
        </div>
  ";

  $str.="<table width='100%' height='100%' cellpadding='1' cellspacing='1' bgcolor='#000' style='min-height:400px;'>
        <tr style='background:#FFFFFF'>
          <td class='title' width='10%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold;'>Qty</td>
          <td class='title' width='30%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold;'>Item</td>
          <td class='title' width='30%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold;'>Description</td>
          <td class='title' width='15%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold; text-align:right'>Unit Price</td>
          <td class='title' width='15%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold; text-align:right'>Total</td>
        </tr>";

   $str.="
        <tr style='background:#EBF1F6'>
          <td class='title' width='10%' style='background: #fff; color: #000; height: 30px;'>Qty</td>
          <td class='title' width='30%' style='background: #fff; color: #000; height: 30px;'>Item</td>
          <td class='title' width='30%' style='background: #fff; color: #000; height: 30px;'>Description</td>
          <td class='title' width='10%' style='background: #fff; color: #000; height: 30px;text-align:right'>Unit Price</td>
          <td class='title' width='10%' style='background: #fff; color: #000; height: 30px;text-align:right'>Total</td>
        </tr>";
    $str.="
        <tr style='background:#EBF1F6'>
          <td class='title' width='10%' style='background: #fff; color: #000; height: 30px;'>Qty</td>
          <td class='title' width='30%' style='background: #fff; color: #000; height: 30px;'>Item</td>
          <td class='title' width='30%' style='background: #fff; color: #000; height: 30px;'>Description</td>
          <td class='title' width='15%' style='background: #fff; color: #000; height: 30px;text-align:right'>Unit Price</td>
          <td class='title' width='15%' style='background: #fff; color: #000; height: 30px;text-align:right'>Total</td>
        </tr>";

    $str.="
        <tr style='background:#EBF1F6'>
          <td colspan='4' class='title' width='85%' style='background: #fff; color: #000; height: 30px; font-weight: bold; text-align:right'>
            <h3>Subtotal:</h3>
            <h3>Shipping cost:</h3>
            <h3>Tax:</h2>
          </td>
          <td class='title' width='15%' style='background: #fff; color: #000; height: 30px; font-weight: bold; text-align:right'>
            <h3>$1,454.55</h3>
            <h3>$1,600.00</h3>
            <h3>$1,600.00</h2>
          </td>
        </tr>";
   $str.="</table>";
   $str.="<h3>Please contact us for more information about this receipt.</h3>";
   $str.="<h3>Thank you for your business.</h3>";
   $str.="</div>
      </body></html>";
  echo $str;
?>
