

<table class="table review margin-top" width="100%" cellpadding="0" cellspacing="0" border="0">
  <tbody>
  <tr class="table-row">
    <td colspan="2" class="left">Sản phẩm trong đơn hàng của bạn</td>
  </tr>
   <?php
    //echo "ma phieu giam hang ".$_SESSION["phieugiamgia_id"];
    $rst = $dbf->getDynamic("shoppingcart","user = '".$_SESSION['login']."'","");
    if($dbf->totalRows($rst)>0)
    {
    $totalgrand=0;
    $i=1;
	while($rowcom=$dbf->nextdata($rst))
	{
        $id          = $rowcom["id"];
        $productid   = $rowcom["productid"];
        $infoProduct = $dbf->getInfoColum("article",$productid);
        $catProduct  =  $dbf->getInfoColum("article_category",$infoProduct["article_category_id"]);


          if($catProduct["parentid"]==0)
          {
            $pagecattitle0 = $catProduct["title_rewrite"];
            $pagecattitle1 = $catProduct["title_rewrite"];
          }
          else
          {
             $infoCatPro2= $dbf->getInfoColum("article_category",$catProduct["parentid"]);
             $pagecattitle0 = $infoCatPro2["title_rewrite"];
             $pagecattitle1 = $catProduct["title_rewrite"];
          }

        //$price       = $rowcom["price"];
        $quantity    = $rowcom["quantity"];
        $dateorder   = $rowcom["dateorder"];

        $pro_code = stripcslashes($infoProduct["pro_code"]);
        $price = stripcslashes($infoProduct["price"]);
        $price_format = $utl->format($price);
        $discout = stripcslashes($infoProduct["discout"]);
        $price_discout = $price -(($price * $discout)/100);
        $price_discout_format = $utl->format($price_discout);

         $totalprice_item =  $price_discout * $quantity;
         $totalprice_item_format = $utl->format($totalprice_item);
         $totalgrand+= $totalprice_item;

   ?>
   <tr class='<?=(($i%2==0)?"table-row":"")?>'>
  	<td width="60%">
         <a href="/<?=$pagecattitle0?>/<?=$pagecattitle1?>/<?=$infoProduct['title_rewrite']?>.html" class="product-title"><img style="float: left; margin-right: 5px;" src="modum/image.php/image.jpg?image=<?=$infoProduct["picture_thumbnail"]?>&width=50&height=50" border="0" align="absmiddle"></a>
         <a href="/<?=$pagecattitle0?>/<?=$pagecattitle1?>/<?=$infoProduct['title_rewrite']?>.html" class="product-title"><?=$infoProduct["title"]?></a>
  		 <p class="sku" style="padding: 0px; margin: 0px">Mã sản phẩm: <?=$pro_code?></p>
  	 </td>
  	<td width="40%" class="center"><strong><?=$quantity?>&nbsp;x&nbsp;<?=$price_discout_format?>&nbsp;<?=CURRENCY?></strong>&nbsp;=&nbsp;<span><b><?=$totalprice_item_format?>&nbsp;<?=CURRENCY?></b></span></td>
  </tr>

  <?php
        $i++;
     }
     }
  ?>
  </tbody>
  </table>


   <?php echo $html->normalForm("frm_place_order",array("action"=>"checkout-complete.html","method"=>"post"));?>

     <table width="100%" cellpadding="3" cellspacing="0" border="1">

       <tr valign="top">
            <td width="60%">
                <p>Nếu bạn có bất kỳ ghi chú về trình tự, vui lòng nhập chúng ở đây: </p>
                <textarea class="input-textarea checkout-textarea" name="customer_notes" cols="60" rows="8"></textarea>
            </td>
            <td width="40%">
                <div class="content_total_price">
                     <p>Thành tiền:&nbsp;&nbsp;<?=$utl->format($totalgrand)?>&nbsp;<?=CURRENCY?></p>
                     <?php if($totalgrand>2000000)
                           {
                             $price_shipping=0;
                           }

                           if(!isset($_SESSION["payment_shipping"]) || $_SESSION["payment_shipping"]==1 )
                           {

                           if($price_shipping!=0)
                           {
                           ?>
                           <p>Phí vận chuyển:&nbsp;&nbsp;<?=$utl->format($price_shipping)?>&nbsp;<?=CURRENCY?></p>
                            <?php
                           }else
                           {

                              echo "<p>Phí vận chuyển: Miễn phí</p>";
                           }
                           }else
                           {
                             $price_shipping=0;
                             echo "<p>Nhận hàng tại trụ sở chính: xin vui lòng gọi số Hotline 0908934376 để hẹn giờ lấy hàng</p>";
                           }



                      ?>
                     <p style="display: none"><input onclick="select_tax()" type="checkbox" id="tax_order" name="tax_order" value="1" class="checkbox valign">Nếu bạn yêu cầu xuất hóa đơn VAT bạn sẽ chịu thêm (<?=(int)$info["tax"]?>%) tổng giá trị đơn hàng</p>
                     <p style="display: none">Thuế:&nbsp;&nbsp;(<?=(int)$info["tax"]?>%) = <?=$utl->format(($totalgrand*$info["tax"])/100)?>&nbsp;<?=CURRENCY?></p>

                </div>
                <div class="total_code" id="total_code_tax" style="display: none">
                       Tổng cộng: <?=$utl->format($totalgrand+$price_shipping+(($totalgrand*(int)$info["tax"])/100))?> &nbsp;<?=CURRENCY?>
                </div>

                <?php
                     if(isset($_SESSION["phieugiamgia_id"]) && $_SESSION["phieugiamgia_id"]!=0 && $_SESSION["price_start_phieugiamgia"]<=$totalgrand)
                     {
                       $totalgrand = $totalgrand - $_SESSION["price_phieugiamgia"];
                ?>
                <div class="total_code">
                     Phiếu giảm giá: <?=$utl->format($_SESSION["price_phieugiamgia"])?> &nbsp;<?=CURRENCY?>
                </div>

                <?php } ?>

                <div class="total_code" id="total_code_no_tax">
                       Tổng cộng: <?=$utl->format($totalgrand+$price_shipping)?> &nbsp;<?=CURRENCY?>
                </div>

                <script language="JavaScript" type="text/javascript">
                /*<![CDATA[*/
                function select_tax()
                {

                  if(document.getElementById('total_code_tax').style.display=='none')
                  {
                      document.getElementById('total_code_tax').style.display='block'
                      document.getElementById('total_code_no_tax').style.display='none'
                  }else
                  {
                      document.getElementById('total_code_tax').style.display='none'
                      document.getElementById('total_code_no_tax').style.display='block'
                  }

                }
                /*]]>*/
                </script>

            </td>
       </tr>
       <tr valign="top">
		 <td>
			<div class="select-field margin-top">
			    <label for="id_accept_terms" class="valign cm-custom (check_agreement)"><input type="checkbox" id="accept_terms" name="accept_terms" value="Y" class="checkbox valign">Lựa chọn hộp kiểm này để chấp nhận các Điều khoản và Điều kiện.</label>
			 </div>
		  </td>
		  <td valign="bottom">
                 <input class="dathang" id="place_order" type="submit" name="cmd_place_order" value="Đặt Hàng">
                 <div class="clear"></div>
		  </td>
		</tr>

     </table>
     <div class="clear"></div>

   <?php
     echo $html->closeForm();
   ?>

   <script type="text/javascript">

$().ready(function() {
$("#frm_place_order").validate({
            debug: false,
            errorElement: "em",
            success: function(label) {
    				label.text("").addClass("success");
    		},
    		rules: {


              accept_terms:
              {
                required: true

              }


    		},
            messages:
            {
               accept_terms:
              {
                required: "&nbsp;"

              }
            }

	});
});

</script>

