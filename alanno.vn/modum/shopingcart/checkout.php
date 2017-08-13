<?php
    if(isset($_POST["cmdeditsigned"]))
    {
	$firstname  =   addslashes($_POST['firstname']);
    $lastname   =   addslashes($_POST['lastname']);
    $phone      =   addslashes($_POST['phone']);

     $array_col=array("firstname"=>$firstname,"lastname"=>$lastname,"phone"=>$phone);
     $affect=$dbf->updateTable("member",$array_col,"id='".$_SESSION['member_id']."'");
    }

    //
    if(isset($_POST["cmdupdate_address_shipping"]))
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
                  "postcode"=>$postcode);

        $dbf->updateTable("member",$array_col_billing_address,"id='".$_SESSION['member_id']."'");

        $array_col_shipping_address=array("firstname"=>$shipping_firstname,"lastname"=>$shipping_lastname,
                  "phone"=>$shipping_phone,"fax"=>$shipping_fax,"address"=>$shipping_address,"home_flat_number"=>$shipping_home_flat_number,"street_number"=>$shipping_street_number,"street_name"=>$shipping_street_name,"country_id"=>$shipping_country_id,"state"=>$shipping_state,
                  "postcode"=>$shipping_postcode);

        $dbf->updateTable("shipping_address",$array_col_shipping_address,"member_id='".$_SESSION['member_id']."'");

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
     //chua dang nhap. yeu cau dang nhap
   ?>

      <div class="content_login">
          <h1 class="title_checkout">1. Vui lòng đăng nhập</h1>
          <div class="content_login_colum">
                <?php include("modum/member/_login_checkout.php")?>
          </div>
          <div class="content_login_colum content_login_colum_right">
                <?php include("modum/member/_register_checkout.php")?>
                <p>Hoặc</p>
                <?php include("modum/customer/_customer_checkout.php")?>
          </div>
          <div class="clear"></div>
      </div>
      <div class="step-container">
        <h1 class="title_checkout">2. Địa chỉ</h1>
      </div>
      <div class="step-container">
        <h1 class="title_checkout">3. Thanh toán và vận Chuyển</h1>
      </div>
      <div class="step-container" style="margin-bottom: 0px;">
        <h1 class="title_checkout">4. Xem xét và đặt hàng</h1>
      </div>

   <?php
   }else
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
        <div class="step-container" style="margin-top: 0px;">
          <h1 class="title_checkout">
                <span style="float: left">
                1.Đăng nhập với <?=$member_info["firstname"]?>&nbsp;<?=$member_info["lastname"]?> - <?=$member_info["email"]?>
                </span>
                <span class="check_step" style="padding: 0px 15px 0px"><img src="style/images/schnap/icon_step_close.gif" width="19" height="17" alt="" border="0" /></span>
                <br class="clear" />
                <span><a href="javascript:void(0)" onclick="showcontent('container_info_user2','change_1','container_info_user1')" id="change_1">Sửa đổi</a></span>
                <br class="clear" />
          </h1>
          <div id="container_info_user1"></div>
          <div id="container_info_user2" style="display: none">
                <?php
                    include("edit_signed.php");
                ?>
          </div>
        </div>

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

                          include("delivered_bulling_address.php");

                    ?>

                    <br class="clear" />
                 </div>
                 <div class="clear"></div>
            </div>

            <div id="container_address2" style="display: none">
                 <?php include("edit_address.php");?>
            </div>
            <div class="clear"></div>

        </div>

        <div class="step-container">
           <?php echo $html->normalForm("frmpayment_method",array("action"=>"checkout/place-order.html","method"=>"post"));?>
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
                        include("payment_method.php");
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
           <script language="JavaScript" type="text/javascript">
          /*<![CDATA[*/
           function kiemtraphieugiamgia(){
                  var maphieu = $('#magiamgia').val()
              	if(maphieu!='')
          		{
                      var url = "/phieugiamgia.php";
                      url+='?maphieu='+maphieu;
                      initRequest(url);
                      xmlRequest.open("GET", url, true);
                      xmlRequest.onreadystatechange = callback2;
                      xmlRequest.send(null);
          		}
                  else
                  {
                     $("#msg_maphieu").html("Vui lòng nhập mã phiếu giảm giá");
                  }
              }

             function callback2(){
          		if (xmlRequest.readyState == 4) {
          			if (xmlRequest.status == 200) {
                          var data = xmlRequest.responseText;
                          $("#msg_maphieu").html(data);
                          //alert(data);
                          //location.reload();
          			 } else if (xmlRequest.status == 204){
          				alert("Error! Phiếu khuyễn mãi không hợp lệ");
          			}
          		}

          	}
          /*]]>*/
          </script>

<fieldset style="padding: 10px 10px 10px; margin: 20px 0px 20px; border: 2px solid #F1F4F6">
    <legend style="font-size:14px;"><h2>Phiếu giảm giá</h2></legend>
    <div class="error" style="color: red;padding:0px 0px 5px" id="msg_maphieu"></div>
    <b>Mã phiếu</b>:<input type="text" name="magiamgia" id="magiamgia" />
    <input type="button" name="bt_kiemtra" value="Kiểm tra" onclick="kiemtraphieugiamgia()" />
</fieldset>


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
        <h1 class="title_checkout">4. Xem xét và đặt hàng</h1>
        <?php
            if(isset($URL[1]) && $URL[1]=='place-order' && isset($_POST["cmdupdate_payment_method"]))
            {
                include("place_order.php");
            }
        ?>
        <div class="clear"></div>
      </div>

     <?php
     }
   }
 ?>
 </div>
 </div>

