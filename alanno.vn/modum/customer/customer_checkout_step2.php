<?php  
	
    if(isset($_POST["customer_address_checkout"]))
    {
       // Billing address
    	$firstname  =   addslashes($_POST['firstname']);
        $lastname   =   addslashes($_POST['lastname']);
        $phone      =   addslashes($_POST['phone']);
        $fax        =   addslashes($_POST['fax']);
        $address       =   addslashes($_POST["address"]);
        $home_flat_number       =   addslashes($_POST["home_flat_number"]);
        $street_number          =   addslashes($_POST["street_number"]);
        $street_name            =   addslashes($_POST["street_name"]);

        $country_id =   addslashes($_POST["country_id"]);
        $state      =   $_POST["state"];
        $postcode   =   addslashes($_POST["postcode"]);

        //Shipping address

        $shipping_firstname  =   addslashes($_POST['shipping_firstname']);
        $shipping_lastname   =   addslashes($_POST['shipping_lastname']);
        $shipping_phone      =   addslashes($_POST['shipping_phone']);
        $shipping_fax        =   addslashes($_POST['shipping_fax']);
        $shipping_address       =   addslashes($_POST["shipping_address"]);
        $shipping_home_flat_number       =   addslashes($_POST["shipping_home_flat_number"]);
        $shipping_street_number          =   addslashes($_POST["shipping_street_number"]);
        $shipping_street_name            =   addslashes($_POST["shipping_street_name"]);

        $shipping_country_id =   addslashes($_POST["shipping_country_id"]);
        $shipping_state      =   $_POST["shipping_state"];
        $shipping_postcode   =   addslashes($_POST["shipping_postcode"]);

        $array_col_billing_address=array("firstname"=>$firstname,"lastname"=>$lastname,
                  "phone"=>$phone,"fax"=>$fax,"address"=>$address,"home_flat_number"=>$home_flat_number,"street_number"=>$street_number,"street_name"=>$street_name,"country_id"=>$country_id,"state"=>$state,
                  "postcode"=>$postcode,"datecreated"=>time());

        //print_r($array_col_billing_address);
        if(isset($_SESSION['member_id']) && $_SESSION['member_id']!="" )
        {

            $dbf->updateTable("member",$array_col_billing_address,"id='".$_SESSION['member_id']."'");

        }else
        {

          $dbf->insertTable("member",$array_col_billing_address);
          $_SESSION['member_id']= mysql_insert_id();
        }


        $rstcheck_billing=$dbf->getDynamic("shipping_address","member_id=".$_SESSION["member_id"]."","");
        $total_check_billing = $dbf->totalRows($rstcheck_billing);

        if($total_check_billing==0)
        {
          $array_col_shipping_address=array("member_id"=>$_SESSION["member_id"],"firstname"=>$shipping_firstname,"lastname"=>$shipping_lastname,
                  "phone"=>$shipping_phone,"fax"=>$shipping_fax,"address"=>$shipping_address,"home_flat_number"=>$shipping_home_flat_number,"street_number"=>$shipping_street_number,"street_name"=>$shipping_street_name,"country_id"=>$shipping_country_id,"state"=>$shipping_state,
                  "postcode"=>$shipping_postcode);
                  $dbf->insertTable("shipping_address",$array_col_shipping_address);

        }
        else
        {

           $array_col_shipping_address=array("firstname"=>$shipping_firstname,"lastname"=>$shipping_lastname,
                  "phone"=>$shipping_phone,"fax"=>$shipping_fax,"address"=>$shipping_address,"home_flat_number"=>$shipping_home_flat_number,"street_number"=>$shipping_street_number,"street_name"=>$shipping_street_name,"country_id"=>$shipping_country_id,"state"=>$shipping_state,
                  "postcode"=>$shipping_postcode);
                  $dbf->updateTable("shipping_address",$array_col_shipping_address,"member_id='".$_SESSION['member_id']."'");
        }

    }

    if(isset($_POST["cmdupdate_payment_method"]))
    {
      $_SESSION["payment_id"] = $_POST["payment_id"];
      $_SESSION["payment_shipping"] = $_POST["payment_shipping"];
    }
?>

<script language="JavaScript" type="text/javascript">
 /*<![CDATA[*/
    function showcontent(id,id2,id3)
    {
      if(document.getElementById(id).style.display=='none')
      {
         document.getElementById(id).style.display='block';
         document.getElementById(id2).innerHTML="Đóng"
         document.getElementById(id3).style.display="none"

      }else
      {
          document.getElementById(id).style.display='none'
          document.getElementById(id2).innerHTML="Sửa đổi"
          document.getElementById(id3).style.display="block"
      }

    }
 /*]]>*/
 </script>


<div class="section_wrapper mcb-section-inner">
<div class="pro_c">
<div class="content_checkout">
 <?php
   if($_SESSION["Free"]==1)
   {
            $member_info = $dbf->getInfoColum("member",$_SESSION["member_id"]);
           //contruy
           if($member_info["state"]!='other')
           {
              $state_info = $dbf->getInfoColum("shipping_method",$member_info["state"]);
           }else
           {
              $state_info["title"]="Other";
           }

           $country_info = $dbf->getInfoColum("countries",$member_info["country_id"]);
   ?>
            <div class="step-container">
            <h1 class="title_checkout">
                <span style="float: left">
                2. Địa chỉ
                </span>
                <span class="check_step" style="padding: 0px 15px 0px"><img src="style/images/schnap/icon_step_close.gif" width="19" height="17" alt="" border="0" /></span>
                <br class="clear" />
                <span><a href="javascript:void(0)" onclick="showcontent('container_address2','change_2','container_address1')" id="change_2">Sửa đổi</a></span>
                <br class="clear" />
            </h1>
            <div id="container_address1">
                 <div class="address_colum">
                    <span style="text-decoration: underline"><b>Địa chỉ thanh toán:</b></span>
                    <p><b><?=$member_info["firstname"]?>&nbsp;<?=$member_info["lastname"]?></b><br/>
                    <?=$words["ADDRESS"]?>: <b><?=$member_info["address"]?></b> <br/>
                    <?=$words["PHONE"]?>: <b><?=$member_info["phone"]?></b> <br/>
                    Fax: <b><?=$member_info["fax"]?></b> <br/>
                    <?=$words["STATE"]?>: <b><?= $state_info["title"]?></b> <br/>
                    <?=$words["POSTCODE"]?>: <b><?=$member_info["postcode"]?></b> <br/>
                    <?=$words["COUNTRY"]?>: <b><?=$country_info["countries_name"]?></b> <br/>
                    </p>
                    <br class="clear" />
                 </div>
                 <div class="address_colum address_colum_right">
                    <span style="text-decoration: underline"><b>Địa chỉ giao hàng:</b></span>
                    <?php

                          include("customer_delivered_bulling_address.php");

                    ?>

                    <br class="clear" />
                 </div>
                 <div class="clear"></div>
            </div>

            <div id="container_address2" style="display: none">
                 <?php include("customer_address.php");?>
            </div>
            <div class="clear"></div>

        </div>

        <div class="step-container">
           <?php echo $html->normalForm("frmpayment_method",array("action"=>"custormer-checkout-step2/place-order.html","method"=>"post"));?>
           <h1 class="title_checkout"><span style="float: left"> 3. Thanh toán và Vận chuyển  </span>
               <span class="check_step" style="padding: 0px 15px 0px"><img src="style/images/schnap/icon_step_close.gif" width="19" height="17" alt="" border="0" /></span>
                <br class="clear" />
                <span><a href="javascript:void(0)" onclick="showcontent('content_payment_shipping2','change_3','content_payment_shipping1')" id="change_3">Sửa đổi</a></span>
                <br class="clear" />
           </h1>

           <div id="content_payment_shipping1" style="display: block">
                 <div class="payment_shipping_colum">
                     <div style="border-bottom: 1px dotted #ccc; padding-bottom: 5px;"><b><i>Phương thức thanh toán:</i></b> </div>
                     <br class="clear" />
                     <?php
                        if(!isset($_SESSION["payment_id"]))
                        {
                          $_SESSION["payment_id"] = 1;
                          echo "<p>Chuyển khoản</p>";
                        }else
                        {
                            $payment_method = $dbf->getInfoColum("payment_method",$_SESSION["payment_id"]);
                            echo "<p>".$payment_method["title"]."</p>";
                        }
                     ?>
                 </div>
                 <div class="payment_shipping_colum_right">
                    <div style="border-bottom: 1px dotted #ccc;padding-bottom: 5px;"><b><i>Phí vận chuyển</i></b></div>
                    <br class="clear" />
                     <?php
                         // kiem tra shipping
                         if($shipping_address_delivered["state"]!='other')
                         {
                            $price_shipping = $shipping_address_state_info["price"];
                         }else
                         {
                            $price_shipping = 40000;
                         }

                         if(!isset($_SESSION["payment_shipping"]) || $_SESSION["payment_shipping"]==1 )
                         {
                         ?>
                            <p>Phí vận chuyển: từ 1 - 4 ngày (Tùy theo vùng) giá: <b><?=$price_shipping?></b>&nbsp;<?=CURRENCY?></p>
                        <?php

                         }else
                         {
                           $price_shipping = 0;
                         ?>
                         <p>Nhận hàng tại trụ sở chính: xin vui lòng gọi số Hotline 0908934376 để hẹn giờ lấy hàng</p>
                         <?php

                         }
                     ?>
                          <br class="clear" />


                </div>
                <div class="clear"></div>
           </div>

           <div id="content_payment_shipping2"  style="display: none">
                <div class="payment_shipping_colum">
                    <div style="border-bottom: 1px dotted #ccc; padding-bottom: 5px;"><b><i>Phướng thức thanh toán:</i></b> </div>
                    <br class="clear" />
                    <p>
                    <?php
                        include("modum/shopingcart/payment_method.php");
                    ?>
                    </p>
                </div>
                <div class="payment_shipping_colum_right">
                    <div style="border-bottom: 1px dotted #ccc;padding-bottom: 5px;"><b><i>Phí vận chuyển</i></b></div>
                    <br class="clear" />
                     <p><input type="radio" class="radio" name="payment_shipping" value="1" checked="checked">Phí vận chuyển: từ 1 - 4 ngày (Tùy theo vùng) - <?=$price_shipping?>&nbsp;<?=CURRENCY?></p>
                     <br class="clear" />
                     <p><input type="radio" class="radio" name="payment_shipping" value="2" <?=(($_SESSION["payment_shipping"]==2)?"checked='checked'":"")?>>Nhận hàng tại trụ sở chính: xin vui lòng gọi số Hotline 0908934376 để hẹn giờ lấy hàng</p>
                </div>
                <div class="clear"></div>
           </div>



           <?php
            if(!isset($URL[1]))
            {
            ?>
             <input type="submit" name="cmdupdate_payment_method" id="cmdupdate_payment_method" value="<?=$words["CONTINUE_CHECKOUT"]?>" />
            <?php
            }

            ?>
            <?php
              echo $html->closeForm();
            ?>
             <div class="clear"></div>
        </div>

      <div class="step-container" style="margin-bottom: 0px;">
        <h1 class="title_checkout">3. Xem xét và đặt hàng</h1>
        <?php
            if(isset($URL[1]) && $URL[1]=='place-order' && isset($_POST["cmdupdate_payment_method"]))
            {
                include("modum/shopingcart/place_order.php");
            }
        ?>
        <div class="clear"></div>
      </div>

     <?php

   }else
   {
     $html->redirectURL("checkout.html");
   }
 ?>
 </div>
 </div>

