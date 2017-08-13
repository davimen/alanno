<?php
        session_start();
        if(empty($_SESSION["user_login"])) {        
		echo "<script>window.location.href='login.php'</script>";
        exit;
    	}
		
		define('WP_USE_THEMES', false);
        require('../wp-blog-header.php');

        include str_replace('\\','/',dirname(__FILE__)).'/content_spaw/spaw.inc.php';
        include str_replace('\\','/',dirname(__FILE__)).'/class/class.DEFINE.php';
        include str_replace('\\','/',dirname(__FILE__)).'/class/class.HTML.php';
        include str_replace('\\','/',dirname(__FILE__)).'/class/class.JAVASCRIPT.php';
        include str_replace('\\','/',dirname(__FILE__)).'/class/class.UTILITIES.php';
        include str_replace('\\','/',dirname(__FILE__)).'/class/class.CSS.php';
        include str_replace('\\','/',dirname(__FILE__)).'/class/class.SINGLETON_MODEL.php';
        include str_replace('\\','/',dirname(__FILE__)).'/class/simple_html_dom.php';
        include str_replace('\\','/',dirname(__FILE__)).'/class/class.BUSINESSLOGIC.php';
        include str_replace('\\','/',dirname(__FILE__)).'/class/template.php';
    	include str_replace('\\','/',dirname(__FILE__)).'/Cache_Lite/Lite/Function.php';

        $html=SINGLETON_MODEL::getInstance("HTML");
    	$js=SINGLETON_MODEL::getInstance("JAVASCRIPT");
    	$css=SINGLETON_MODEL::getInstance("CSS");
        $utl=SINGLETON_MODEL::getInstance("UTILITIES");
        $dbf=SINGLETON_MODEL::getInstance("BUSINESSLOGIC");
    	$html->docType();

        $info = $dbf->getConfig();		

        $order_id = $_GET["order_id"];
        //write order
        $order_info = $dbf->getInfoColum("orders",$order_id);

        $member_info = $dbf->getInfoColum("member",$order_info["member_id"]);
        $state_info = $dbf->getInfoColum("shipping_method",$member_info["state"]);



        $member_shipping_address = $dbf->getInfoColumShipping("orders_shipping_address",$order_id);
        
		

         // kiem tra shipping
         if($member_shipping_address["state"]!='other')
         {
             $shipping_address_state_info = $dbf->getInfoColum("shipping_method",$member_shipping_address["state"]);
             $price_shipping = $shipping_address_state_info["price"];
         }else
         {
            $price_shipping = 40000;
         }


        $payment_method = $dbf->getInfoColum("payment_method",$order_info["order_paymode_id"]);



        $rst = $dbf->getDynamic("orderdetail","orderid = '".$order_id."'","");

        $totalrow = $dbf->totalRows($rst);

        $array_productid=array($totalrow);
	    $array_quantity=array($totalrow);
        $array_price=array($totalrow);
        $array_infoProduct=array($totalrow);
        $array_totalprice_item=array($totalrow);

        if($totalrow>0)
        {
            $totalgrand=0;
            $i=0;
        	while($rowcom=$dbf->nextdata($rst))
        	{
				
                $id          = $rowcom["id"];
                $productid   = $rowcom["productid"];
                //$infoProduct = $dbf->getInfoColum("article",$productid);
                $price       = $rowcom["price"];
                $quantity    = $rowcom["quantity"];
				
				
				$args = array('p' => $productid, 'post_type' => 'product');
				$loop = new WP_Query($args);
				while ( $loop->have_posts() ) : $loop->the_post(); 
					global $post;
					global $product;




                //$pro_code = stripcslashes($infoProduct["pro_code"]);

                $totalprice_item =  $price * $quantity;
                $totalgrand+= $totalprice_item;

                 // set array
                 $array_productid[$i] = $productid;
	             $array_quantity[$i]  = $quantity;
                 $array_price[$i]     = $price;
				 $array_image[$i]     = get_the_post_thumbnail_url();
				 $array_title[$i]     = $product->post->post_title;
				 
                 //$array_infoProduct[$i]     = $infoProduct;
				 
                 $array_totalprice_item[$i]     = $totalprice_item;
				 
				 endwhile;

               $i++;
            }
			
			
		
 ?>


<html>
      <head>
      <meta http-equiv='Content-Type' content='text/html; charset=utf-8' />
      <meta http-equiv='Content-Language' content='en-us' />
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
      <div class='aroundEmail' style='clear:both; text-align:left;'>

        <div class='header_mail'>
            <div class='header_mail_left' style='float:left; width:70%; padding:20px 0px 20px'>
                <?=$info["website_contacts"]?>
            </div>
            <div class='header_mail_right' style='float:right; width:30%;padding:20px 0px 20px; text-align:right'>
                <h1>Receipt</h1>
                <h3><?=date("d/m/Y",$order_info["orderdate"])?></h3>
            </div>
            <div class='clear'></div>
            <div class='space1'></div>

            <div class='header_mail_left' style='float:left; width:70%; padding:20px 0px 20px'>
                <h3><?=$member_info["firstname"]."&nbsp;".$member_info["lastname"]?></h3>
                <p>
                   <b>Địa chỉ thanh toán:</b>
                   <?=$member_info["address"]?><br/>
                   <b>Điện thoại:</b> <?=$member_info["phone"]?><br/>
                   <b>Quận/Tỉnh:</b> <?=$state_info["title"]?><br/>
                   <b>Tên tỉnh</b> <?=$member_info["postcode"]?><br/>
                </p>
                <p>
                   <b>Địa chỉ giao hàng:</b>
                   <?=$member_shipping_address["address"]?><br/>
                   <b>Người nhận:</b><?=$member_shipping_address["firstname"]."&nbsp;".$member_shipping_address["lastname"]?><br/>
                   <b>Điện thoại:</b> <?=$member_shipping_address["phone"]?><br/>
                   <b>Quận/Tỉnh:</b> <?=$shipping_address_state_info["title"]?><br/>
                   <b>Tên tỉnh</b> <?=$member_shipping_address["postcode"]?><br/>
                </p>

            </div>
            <div class='header_mail_right' style='float:right; width:30%;padding:20px 0px 20px; text-align:right'>
                <?php
                    if($totalgrand>2000000)
                    {
                       $price_shipping=0;
                    }

                    if($order_info["is_payment_shipping"]==1)
                    {
                      $price_shipping=0;
                    }
                    if($order_info["phieugiamgia"])
                    {
                      $totalgrand = $totalgrand - $order_info["phieugiamgia"];
                    }
                    if($order_info["is_tax"]==1)
                    {
                      echo "<h3>".$utl->format($totalgrand+$price_shipping+(($totalgrand*$info["tax"])/100))."<sup>đ</sup></h3>";
                    }
                    else
                    {
                      echo "<h3>".$utl->format($totalgrand+$price_shipping)."<sup>đ</sup></h3>";
                    }
                ?>

            </div>
            <div class='clear'></div>
            <div class='space2'></div>

            <div class='header_mail_left' style='font-size:14px;float:left; width:70%; padding:20px 0px 20px'>
                 <strong>Phương thức thanh toán: <?=$payment_method["title"]?></strong> <br/>
                 <strong>Mã hóa đơn: #<?=$order_info["code_order"]?></strong>
            </div>
            <div class='header_mail_right' style='float:right; width:30%;padding:20px 0px 20px; text-align:right'></div>
            <div class='clear'></div>
            <div class='space2'></div>
        </div>


  <table width='100%' height='100%' cellpadding='1' cellspacing='1' bgcolor='#000' style='min-height:400px;'>
        <tr style='background:#FFFFFF'>
          
          <td class='title' width='30%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold;'>Sản phẩm</td>
          <td class='title' width='17%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold;'>Hình</td>
          <td class='title' width='10%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold;'>Số lượng</td>
          <td class='title' width='12%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold; text-align:right'>Đơn giá</td>
          <td class='title' width='18%' style='background: #EBF1F6; color: #000; height: 30px; font-weight: bold; text-align:right'>Thành tiền</td>
        </tr>
        <?php
            $j=0;
    		while($j<$totalrow)
    		{
          ?>
                    <tr style='background:#EBF1F6'>                      
                      <td class='title' width='30%' style='background: #fff; color: #000; height: 30px;'><?=$array_title[$j]?></td>
                      <td class='title' width='17%' style='background: #fff; color: #000; height: 30px;'>
                      <img onerror="$(this).hide()" src="<?=$array_image[$j]?>" height="50" border="0">
                      </td>
                      <td class='title' width='10%' style='background: #fff; color: #000; height: 30px;'><?=$array_quantity[$j]?></td>
                      <td class='title' width='12%' style='background: #fff; color: #000; height: 30px;text-align:right'><?=$utl->format($array_price[$j])?><sup>đ</sup></td>
                      <td class='title' width='18%' style='background: #fff; color: #000; height: 30px;text-align:right'><?=$utl->format($array_totalprice_item[$j])?><sup>đ</sup></td>
                    </tr>
<?php

                $j++;
    		}
?>
        <?php

        if($order_info["is_tax"]==1)
            {
            echo"
        <tr style='background:#EBF1F6'>
          <td colspan='4' class='title' width='85%' style='background: #fff; color: #000; height: 30px; font-weight: bold; text-align:right'>
            <h3>Tổng:</h3>
            <h3>Phí vận chuyển:</h3>
            <h3>Thuế(VAT):</h3>
            <h1>Tổng hóa đơn:</h1>
          </td>
          <td class='title' width='15%' style='background: #fff; color: #000; height: 30px; font-weight: bold; text-align:right'>
            <h3>".$utl->format($totalgrand)."&nbsp;VNĐ</h3>";

            if($order_info["is_payment_shipping"]==1)
            {
               echo "<h3>Nhận hàng tại trụ sở chính: xin vui lòng gọi số Hotline 0963 763 079 [Mr. Toàn] để hẹn giờ lấy hàng</h3>";
            }else
            {
              echo "<h3>".$utl->format($price_shipping)."<sup>đ</sup></h3>";
            }


            echo "<h3>(".(int)$info["tax"]."%) = ".$utl->format(($totalgrand*$info["tax"])/100)."<sup>đ</sup></h3>
            <h2>".$utl->format($totalgrand+$price_shipping+(($totalgrand*$info["tax"])/100))." <sup>đ</sup></h2>
          </td>
        </tr>";
           }else
           {
               echo"
        <tr style='background:#EBF1F6'>
          <td colspan='4' class='title' width='85%' style='background: #fff; color: #000; height: 30px; font-weight: bold; text-align:right'>
            <h3>Tổng:</h3>
            <h3>Phí vận chuyển:</h3>";
             if($order_info["phieugiamgia"]>0)
            {
              echo"<h3>Phiếu giảm giá:</h3>";
            }
            echo"<h2>Tổng hóa đơn:</h2>
          </td>
          <td class='title' width='15%' style='background: #fff; color: #000; height: 30px; font-weight: bold; text-align:right'>
            <h3>".$utl->format($totalgrand+$order_info["phieugiamgia"])."<sup>đ</sup></h3>";

            if($order_info["is_payment_shipping"]==1)
            {
               echo "<h4>Nhận hàng tại trụ sở chính</h4>";
            }else
            {
              echo "<h3>".$utl->format($price_shipping)."<sup>đ</sup></h3>";
            }

            if($order_info["phieugiamgia"]>0)
            {
              echo "<h3>".$utl->format($order_info["phieugiamgia"])."<sup>đ</sup></h3>";
            }

            echo"<h2>".$utl->format($totalgrand+$price_shipping)." <sup>đ</sup></h2>
          </td>
        </tr>";
           }

        ?>

   </table>
   <p><?=$info["payment_transfer"]?></p>
   <h3>Quý khách có thắc mắc xin vui lòng liên hệ email <a href='mailto:in@alona.vn'> in@alona.vn</a>  hoặc số  điện thoại (08) 6681 8850</h3>
   <h3>Xin chân thành cảm ơn quý khách.</h3>
   </div>
      </body></html>
<?php
   }
?>

