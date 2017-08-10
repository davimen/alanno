
<?php
    if(isset($_POST["cmd_place_order"]))
    {
        $customer_note = $_POST["customer_notes"];
        $tax_order = $_POST["tax_order"];



        if($tax_order!=1)
        {
           $tax_order=0;
        }

        //write order
        $member_info = $dbf->getInfoColum("member",$_SESSION["member_id"]);

        $state_info = $dbf->getInfoColum("shipping_method",$member_info["state"]);

        $member_shipping_address = $dbf->getInfoColumShipping("shipping_address",$_SESSION["member_id"]);


         // kiem tra shipping
         if($member_shipping_address["state"]!='other')
         {
             $shipping_address_state_info = $dbf->getInfoColum("shipping_method",$member_shipping_address["state"]);
             $price_shipping = $shipping_address_state_info["price"];
         }else
         {
            $price_shipping = 40000;
         }


        $payment_method = $dbf->getInfoColum("payment_method",$_SESSION["payment_id"]);
        $rst = $dbf->getDynamic("shoppingcart","user = '".$_SESSION['login']."'","");
        $totalrow = $dbf->totalRows($rst);

        $array_productid=array($totalrow);
	    $array_quantity=array($totalrow);
        $array_price=array($totalrow);
        $array_infoProduct=array($totalrow);
        $array_totalprice_item=array($totalrow);
        $array_pro_quantity=array($totalrow);

        if($totalrow>0)
        {
            $totalgrand=0;
            $i=0;
        	while($rowcom=$dbf->nextdata($rst))
        	{
                $id          = $rowcom["id"];
                $productid   = $rowcom["productid"];
                $infoProduct = $dbf->getInfoColum("article",$productid);
                //$price       = $rowcom["price"];
                $quantity    = $rowcom["quantity"];
                $dateorder   = $rowcom["dateorder"];

                $pro_code = stripcslashes($infoProduct["pro_code"]);
                $price = stripcslashes($infoProduct["price"]);
                $discout = stripcslashes($infoProduct["discout"]);
                $price_discout = $price -(($price * $discout)/100);

                $totalprice_item =  $price_discout * $quantity;
                $totalgrand+= $totalprice_item;

                 // set array
                 $array_productid[$i] = $productid;
	             $array_quantity[$i]  = $quantity;
                 $array_price[$i]     = $price_discout;
                 $array_infoProduct[$i]     = $infoProduct;
                 $array_totalprice_item[$i]     = $totalprice_item;

                 $array_pro_quantity[$i] = $infoProduct["pro_quantity"];

               $i++;
            }
            // code_order
            $code_order='';

            $yy = date('y');
            $m  = date('M');
            $ww = date('W');
            $d  = date('N');
            $xx = ($dbf->totalRows($dbf->getDynamic("orders","order_week = '".$ww."'","")))+1;
            if($xx<10)
            {
             $xx = "0".$xx;
            }

            $code_order= $yy.$m[0].$ww.$d.$xx;
            //echo $code_order;
            //exit;
            //insert to orders

            if($_SESSION["payment_shipping"]==2)
            {
              $is_payment_shipping=1;
              $price_shipping=0;
            }
            else
            {
              $is_payment_shipping=0;
            }

            if($totalgrand>2000000)
            {
               $price_shipping=0;
            }

            if(isset($_SESSION["phieugiamgia_id"]) && $_SESSION["phieugiamgia_id"]!=0 && $_SESSION["price_start_phieugiamgia"]<=$totalgrand)
            {
               $totalgrand = $totalgrand - $_SESSION["price_phieugiamgia"];
            }


            if($tax_order==1)
            {
              $tonghoadon= $totalgrand + $price_shipping + (($totalgrand*$info["tax"])/100);
            }else
            {
              $tonghoadon= $totalgrand + $price_shipping;
            }

            if(!isset($_SESSION["price_phieugiamgia"]))
            {
              $_SESSION["price_phieugiamgia"]=0;
            }

            $array_col=array("code_order"=>$code_order,"member_id"=>$_SESSION["member_id"],"totalprice"=>$tonghoadon,"orderdate"=>time(),"order_paymode_id"=>$_SESSION["payment_id"],"legal_id"=>1,"phieugiamgia"=>$_SESSION["price_phieugiamgia"],"order_note"=>$customer_note,"order_week"=>$ww,"is_tax"=>$tax_order,"is_payment_shipping"=>$is_payment_shipping);
            $dbf->insertTable("orders",$array_col);
            $orderid=mysql_insert_id();
            $j=0;



  $str="<html>
      <head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
      <meta http-equiv='Content-Language' content=\en-us' />
      <style type='text/css'>
       body{font-family: Arial;font-size: 12px; color:#333333;}
      .clear { font-size:1px; clear:both;}
      .aroundEmail {width:95%; min-height:500px; background:#fff; padding:20px;}
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
            <div class='header_mail_left' style='float:left; width:70%; padding:20px 0px 20px'>
                ".$info["website_contacts"]."
            </div>
            <div class='header_mail_right' style='float:right; width:30%;padding:20px 0px 20px; text-align:right'>
                <h1>Receipt</h1>
                <h3>".date("d/m/Y",time())."</h3>
            </div>
            <div class='clear'></div>
            <div class='space1'></div>

            <div class='header_mail_left' style='float:left; width:70%; padding:20px 0px 20px'>
                <h3>".$member_info["firstname"]."&nbsp;".$member_info["lastname"]."</h3>
                <p>
                   <b>Địa chỉ thanh toán:</b>
                   ".$member_info["address"]."<br/>
                   <b>Điện thoại:</b> ".$member_info["phone"]."<br/>
                   <b>Quận/Tỉnh:</b> ".$state_info["title"]."<br/>
                   <b>Tên tỉnh</b> ".$member_info["postcode"]."<br/>
                </p>
                <p>
                   <b>Địa chỉ giao hàng:</b>
                   ".$member_shipping_address["address"]."<br/>
                   <b>Người nhận:</b>
                   ".$member_shipping_address["firstname"]."&nbsp;".$member_shipping_address["lastname"]."
                   <b>Điện thoại:</b> ".$member_shipping_address["phone"]."<br/>
                   <b>Quận/Tỉnh:</b> ".$shipping_address_state_info["title"]."<br/>
                   <b>Tên tỉnh</b> ".$member_shipping_address["postcode"]."<br/>
                </p>

            </div>
            <div class='header_mail_right' style='float:right; width:30%;padding:20px 0px 20px; text-align:right'>";

            if($tax_order)
            {
              $str.="<h3>".$utl->format($totalgrand+$price_shipping+(($totalgrand*$info["tax"])/100))."&nbsp;".CURRENCY."</h3>";
            }
            else
            {
              $str.="<h3>".$utl->format($totalgrand+$price_shipping)."&nbsp;".CURRENCY."</h3>";
            }

            $str.="</div>
            <div class='clear'></div>
            <div class='space2'></div>

            <div class='header_mail_left' style='font-size:14px;float:left; width:70%; padding:20px 0px 20px'>
                 <strong>Phương Thức Thanh Toán: ".$payment_method["title"]."</strong> <br/>
                 <strong>Mã Hóa Đơn: #".$code_order."</strong>
            </div>
            <div class='header_mail_right' style='float:right; width:30%;padding:20px 0px 20px; text-align:right'></div>
            <div class='clear'></div>
            <div class='space2'></div>
        </div>
  ";

  $str.="<table width='100%' height='100%' cellpadding='1' cellspacing='1' bgcolor='#000' style='min-height:400px;'>
        <tr style='background:#FFFFFF'>
          <td class='title' width='10%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold;'>Mã Sản phẩm</td>
          <td class='title' width='20%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold;'>Sản phẩm</td>
          <td class='title' width='20%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold;'>Sản phẩm</td>
          <td class='title' width='20%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold;'>Mô tả</td>
          <td class='title' width='8%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold;'>Số lượng</td>
          <td class='title' width='12%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold; text-align:right'>Đơn giá</td>
          <td class='title' width='12%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold; text-align:right'>Thành tiền</td>
        </tr>";
    		while($j<$totalrow)
    		{
                $array_col_order_detail=array("orderid"=>$orderid,"productid"=> $array_productid[$j],"quantity"=>$array_quantity[$j],"price"=>$array_price[$j]);
                $dbf->insertTable("orderdetail",$array_col_order_detail);
                //  update lai so luong san pham
                if($array_pro_quantity[$j]<$array_quantity[$j] || $array_pro_quantity[$j]==$array_quantity[$j])
                {
                  $value = array("pro_quantity"=>"pro_quantity-".$array_quantity[$j],"pro_end"=>1);
                }else
                {
                  $value = array("pro_quantity"=>"pro_quantity-".$array_quantity[$j]);
                }

                $where = "id = '".$array_productid[$j]."'";
                $affect=$dbf->updateTable("article",$value,$where);

                $str.="
                    <tr style='background:#EBF1F6'>
                      <td class='title' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold;'>".$array_infoProduct[$j]["pro_code"]."</td>
                      <td class='title' style='background: #fff; color: #000; height: 30px;'>".$array_infoProduct[$j]["title"]."</td>
                      <td class='title' style='background: #fff; color: #000; height: 30px;'><img src='http://designercollection4you.com".$array_infoProduct[$j]["picture_thumbnail"]."' width='50' height='50' border='1'></td>
                      <td class='title' style='background: #fff; color: #000; height: 30px;'>".$array_infoProduct[$j]["description"]."</td>
                      <td class='title' style='background: #fff; color: #000; height: 30px;'>".$array_quantity[$j]."</td>
                      <td class='title' style='background: #fff; color: #000; height: 30px;text-align:right'>".$array_price[$j]."</td>
                      <td class='title' style='background: #fff; color: #000; height: 30px;text-align:right'>".$array_totalprice_item[$j]."</td>
                    </tr>";


                $j++;
    		}

            if($tax_order)
            {
            $str.="
        <tr style='background:#EBF1F6'>
          <td colspan='6' class='title' width='75%' style='background: #fff; color: #000; height: 30px; font-weight: bold; text-align:right'>
            <h3>Tổng:</h3>
            <h3>Phí vận chuyển:</h3>
            <h3>Thuế(VAT):</h3>
            <h2>Tổng hóa đơn:</h2>
          </td>
          <td class='title' width='25%' style='background: #fff; color: #000; height: 30px; font-weight: bold; text-align:right'>
            <h3>".$utl->format($totalgrand)."&nbsp;".CURRENCY."</h3>";
            if($_SESSION["payment_shipping"]==2)
            {
              $str.="<h3>Nhận hàng tại trụ sở chính</h3>";
            }
            else{
              $str.="<h3>".$utl->format($price_shipping)."&nbsp;".CURRENCY."</h3>";
            }
            $str.="<h3>(".(int)$info["tax"]."%) = ".$utl->format(($totalgrand*$info["tax"])/100)."&nbsp;".CURRENCY."</h3>
            <h2>".$utl->format($totalgrand+$price_shipping+(($totalgrand*$info["tax"])/100))." &nbsp;".CURRENCY."</h2>
          </td>
        </tr>";
           }else
           {
               $str.="
        <tr style='background:#EBF1F6'>
          <td colspan='6' class='title' width='75%' style='background: #fff; color: #000; height: 30px; font-weight: bold; text-align:right'>
            <h3>Tổng:</h3>
            <h3>Phí vận chuyển:</h3>";
            if($_SESSION["price_phieugiamgia"]>0)
            {
                $str.="<h3>Phiếu giảm giá:</h3>";
            }
            $str.="<h2>Tổng hóa đơn:</h2>
          </td>
          <td class='title' width='25%' style='background: #fff; color: #000; height: 30px; font-weight: bold; text-align:right'>
            <h3>".$utl->format($totalgrand + $_SESSION["price_phieugiamgia"])."&nbsp;".CURRENCY."</h3>";
            if($_SESSION["payment_shipping"]==2)
            {
              $str.="<h3>Nhận hàng tại trụ sở chính</h3>";
            }
            else{
              $str.="<h3>".$utl->format($price_shipping)."&nbsp;".CURRENCY."</h3>";
            }

            if($_SESSION["price_phieugiamgia"]>0)
            {
                $str.="<h3>".$utl->format($_SESSION["price_phieugiamgia"])." &nbsp;".CURRENCY."</h3>";
            }

              $str.="<h2>".$utl->format($totalgrand+$price_shipping)." &nbsp;".CURRENCY."</h2>
          </td>
        </tr>";
           }
   $str.="</table>";
   $str.="<p>".$info["payment_transfer"]."</p>";
   $str.="<h3>Quý khách có thắc mắc xin vui lòng liên hệ email <a href='mailto:hanhanghieuus@yahoo.com'> hanhanghieuus@yahoo.com</a>  hoặc số  điện thoại 0908934376</h3>";
   $str.="<h3>Xin chân thành cảm ơn quý khách.</h3>";
   $str.="</div>
      </body></html>";
        //echo $str;
      //gui mail
                                $Subject  =  "Hanhanghieu.com Order #".$code_order." has been placed successfully";
                                require("modum/class.phpmailer.php");
                                $mail = new PHPMailer();
                                $SMTP_Host = $arraySMTPSERVER["host"];
                                $SMTP_Port = 25;
                                $SMTP_UserName = $arraySMTPSERVER["user"];
                                $SMTP_Password = $arraySMTPSERVER["password"];

                                //$from = $SMTP_UserName;
                                $from = "hanhanghieuus@yahoo.com";
                                $fromName = "Hanhanghieu.com";
                                $fromName_member = $member_info["firstname"]." ".$member_info["lastname"];
                                if($_SESSION["Free"]==1)
                                {
                                  $to = "hanhanghieuus@yahoo.com";
                                }else
                                {
                                  $to = $member_info["email"];
                                }

                                $mail->IsSMTP();
                                $mail->Host     = $SMTP_Host;
                                $mail->SMTPAuth = true;
                                $mail->Username = $SMTP_UserName;
                                $mail->Password = $SMTP_Password;
                                $mail->From     = $from;
                                $mail->FromName = $fromName;
                                $mail->AddAddress($to);
                                $mail->AddReplyTo($from, $fromName);
                                $mail->AddCC($info["email_booking"],$fromName_member);

                                $mail->WordWrap = 50;
                                $mail->IsHTML(true);
                                $mail->Subject  =  $Subject;
                                $mail->Body     =  $str;
                                $mail->AltBody  =  "This is the text-only body";

                                $mail->Send();



            // insert shipping address
             $array_col_order_shipping_address=array("order_id"=>$orderid,"firstname"=>$member_shipping_address["firstname"],"lastname"=>$member_shipping_address["lastname"],"phone"=>$member_shipping_address["phone"],"fax"=>$member_shipping_address["fax"],"address"=>$member_shipping_address["address"],"home_flat_number"=>$member_shipping_address["home_flat_number"],"street_number"=>$member_shipping_address["street_number"],"street_name"=>$member_shipping_address["street_name"],"country_id"=>$member_shipping_address["country_id"],"state"=>$member_shipping_address["state"],
  "postcode"=>$member_shipping_address["postcode"],"status"=>1,"datecreated"=>time(),"dateupdated"=>$time);
             $dbf->insertTable("orders_shipping_address",$array_col_order_shipping_address);

            // xoa shopping cart
            $where = "user = '".$_SESSION['login']."'";
            $affect=$dbf->deleteDynamic("shoppingcart",$where);

        }




?>



<div class='title_header'>Đặt hàng của Quý khách chúng tôi đã nhận được</div>
 <p>Cảm ơn bạn đã mua hàng của chúng tôi!</p>
 <p>Bạn sẽ nhận được một email xác nhận đơn hàng với các chi tiết đặt hàng của bạn.</p>
 <h1>Đặt hàng</h1>
 <h2 class="subheader">Thông tin thanh toán</h2>
 <?php
    echo $info["payment_transfer"];
 ?>
 <h3>Quý khách có thắc mắc xin vui lòng liên hệ email <a href='mailto:hanhanghieuus@yahoo.com'> hanhanghieuus@yahoo.com</a>  hoặc số  điện thoại 0908934376</h3>
 <h3>Xin chân thành cảm ơn quý khách.</h3>


 <?php
 }
 else
 {
   $html->redirectURL("checkout.html");
 }
 ?>