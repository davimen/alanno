<?php

session_start();
include '../../class/defineConst.php';
include '../../class/class.BUSINESSLOGIC.php';
include '../../class/class.CSS.php';
include '../../class/class.JAVASCRIPT.php';
include '../../class/class.HTML.php';
include '../../class/class.utilities.php';
include '../../class/class.SINGLETON_MODEL.php';

$dbf=SINGLETON_MODEL::getInstance("BUSINESSLOGIC");
$html=SINGLETON_MODEL::getInstance("HTML");
$css=SINGLETON_MODEL::getInstance("CSS");
$js=SINGLETON_MODEL::getInstance("JAVASCRIPT");
$utl=SINGLETON_MODEL::getInstance("UTILITIES");


$totalgrand=0;
$mang=array();
$msg="";
?>

<style type="text/css">
/*<![CDATA[*/
.contentMain {
  font-size: 12px;
  font-family: Arial;
}
a.news:link, a.news:visited {
  text-decoration: none;
  color: #E40141;
  text-transform: uppercase;
  font-size: 12px;
}
a.news:hover {
  text-decoration: underline;
}

.table_cart {
  background: #ccc;
  border: 2px solid #ccc;
}

.table_cart tr {
  background: #fff;
  margin: 1px;
}
.table_cart tr td {
  background: #fff;
}
/*]]>*/
</style>

<script language="JavaScript" type="text/javascript">
/*<![CDATA[*/
function nhapso(evt,objectid){

		var key=(!window.ActiveXObject)?evt.which:window.event.keyCode;
		var values=document.getElementById(objectid).value;
        //alert(key);
       /* if((key<48 || key >57) && (key!=8 || key!=46 || key!=0)) return false;*/
       if((key<48 || key >57) && key!=8 && key!=0) return false;


		return true;
}

 var foo = "bar";
	var xmlRequest = null;

	function initRequest(url){
		if (window.ActiveXObject){
			xmlRequest = new ActiveXObject("Microsoft.XMLHTTP");
		}
		else if(window.XMLHttpRequest) {
			xmlRequest = new XMLHttpRequest();
		}
	}

function updatecart(cart_id)
   {
        var quantity = document.getElementById('cart_'+cart_id).value;
        var pro_quantity = document.getElementById('cart_'+cart_id+'_quantity').value;

        if(parseInt(quantity)>0)
        {
         if(parseInt(quantity) > parseInt(pro_quantity))
         {
           alert("Lưu ý: Hiện sản phẩm chỉ còn có "+pro_quantity+" cái. Quý khách sẽ mua với số lượng còn lại?");
           quantity = pro_quantity;
         }

        var url = "/updatecart.php";
        url+='?cart_id='+cart_id+'&quantity='+quantity;
		initRequest(url);
		xmlRequest.open("GET", url, true);
		xmlRequest.onreadystatechange = callback;
		xmlRequest.send(null);
        }else
        {
           alert("Quý khách vui lòng nhập lại số lượng sản phẩm cần mua.");
        }
   };

function deletecart(cart_id)
 {

      var url = "/deletecart.php";
      url+='?cart_id='+cart_id;
      initRequest(url);
      xmlRequest.open("GET", url, true);
      xmlRequest.onreadystatechange = callback;
      xmlRequest.send(null);
 };


   function callback(){
		if (xmlRequest.readyState == 4) {
			if (xmlRequest.status == 200) {
                var data = xmlRequest.responseText;
                location.reload();
			 } else if (xmlRequest.status == 204){
				alert("Error! update to cart Error");
			}
		}

	}

/*]]>*/
</script>

<form name="frmcart" action="" method="post" class="table_cart">
  <div id="contentMain">
    <table width="100%" cellpadding="0" cellspacing="1" border="0">
      <tr  valign="bottom" align="center">
        <td width="15%"  class="boxGrey"><b>Mã sản phẩm</b></td>
        <td width="25%" align="left"  class="boxGrey"   style="padding-left:20px;"><b>Sản phẩm</b></td>
        <td width="10%"  class="boxGrey"><b>Số lượng</b></td>
        <td width="15%"  class="boxGrey"><b>Thành tiền</b></td>
        <td width="20%"  class="boxGrey" style="text-align:center;" ></td>
      </tr>
      <tr>
        <td colspan="5" height="5"></td>
      </tr>

<?php
    $rst = $dbf->getDynamic("shoppingcart","user = '".$_SESSION['login']."'","");
    if($dbf->totalRows($rst)>0)
    {
	while($rowcom=$dbf->nextdata($rst))
	{
       $id          = $rowcom["id"];
       $productid   = $rowcom["productid"];
       $infoProduct = $dbf->getInfoColum("article",$productid);

          $infoCatPro= $dbf->getInfoColum("article_category",$infoProduct["article_category_id"]);
          if($infoCatPro["parentid"]==0)
          {
            $pagecattitle0 = $infoCatPro["title_rewrite"];
            $pagecattitle1 = $infoCatPro["title_rewrite"];
          }
          else
          {
             $infoCatPro2= $dbf->getInfoColum("article_category",$infoCatPro["parentid"]);
             $pagecattitle0 = $infoCatPro2["title_rewrite"];
             $pagecattitle1 = $infoCatPro["title_rewrite"];
          }

       //$price       = $rowcom["price"];
       $quantity    = $rowcom["quantity"];
       $dateorder   = $rowcom["dateorder"];



          $pro_code = stripcslashes($infoProduct["pro_code"]);
          $price = stripcslashes($infoProduct["price"]);
          $price_format = $utl->format($price);
          $discout = stripcslashes($infoProduct["discout"]);
          $price_discout = $price -(($price * $discout)/100);
          $price_discout_format = $utl->price($price_discout);


           $totalprice_item =  $price_discout * $quantity;
           $totalprice_item_format = $utl->price($totalprice_item);
           $totalgrand+= $totalprice_item;




	?>
      <tr class="txtnho" align="center" valign="bottom">
        <td><?=$infoProduct["pro_code"]?></td>
        <td align="left" style="padding-left:5px;">
            <img style="cursor: pointer" align="absmiddle" onclick="parent.location.href='/<?=$pagecattitle0?>/<?=$pagecattitle1?>/<?=$infoProduct["title_rewrite"]?>.html'" style="float: left; border: 1px solid #ccc; padding: 2px; margin-right: 5px;" src="/modum/image.php?image=<?=$infoProduct["picture_thumbnail"]?>&width=40&height=40" border="0">
            <span style="cursor: pointer" onclick="parent.location.href='/<?=$pagecattitle0?>/<?=$pagecattitle1?>/<?=$infoProduct["title_rewrite"]?>.html'"><?=$infoProduct["title"]?></span></td>
        <td>
            <input type="text" name="txt<?=$id?>" onkeypress="return nhapso(event,'cart_<?=$id?>')" id="cart_<?=$id?>" class="nd1" value="<?=$quantity?>" style="width:50px;" />
            <input type="hidden" name="cart_<?=$id?>_quantity" id="cart_<?=$id?>_quantity" value="<?=$infoProduct["pro_quantity"]?>">

        </td>
        <td align="center" ><?=$totalprice_item_format?>&nbsp;&nbsp;<?=CURRENCY?></td>
        <td style="text-align:right">
              <a href="javascript:void(0)" class="news" onClick="updatecart('<?=$id?>');"><span style="padding-left:5px;">
              Cập nhật
              </span></a>&nbsp;|&nbsp;
              <a href="javascript:void(0)" class="news" onClick="deletecart('<?=$id?>');"><span style="padding-left:5px;">
              Xóa
              </span></a>
        </td>
      </tr>
      <tr>
        <td colspan="5" height="1" bgcolor="#999999"></td>
      </tr>
    <?php
	}
    }else
    {
   ?>
       <tr>
        <td colspan="5"><p>Giỏ hàng rỗng</p></td>
      </tr>
     <?php
    }
?>
      <tr>
        <td colspan="5"><p></p></td>
      </tr>

      <tr>
        <td colspan="5" style="text-align:right">&nbsp;Tổng tiền:&nbsp;<span class="txtdo">
          <?=$utl->price($totalgrand)?>&nbsp;&nbsp;<?=CURRENCY?>
          </span></td>
      </tr>


      <tr>
        <td colspan="5" height="10"><p></p></td>
      </tr>
      <tr>
        <td colspan="5" align="center"><table border="0" cellpadding="0" cellspacing="2" align="center">
            <tr>
              <td><table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="btnleft"></td>
                    <td><input type="button" onClick="location.href='';parent.$.fancybox.close();" class="btncenter" name="sublogin" value="Tiếp tục mua hàng" /></td>
                    <td class="btnright"></td>
                  </tr>
                </table></td>

              <td><table border="0" cellpadding="0" cellspacing="0">
                  <tr>
                    <td class="btnleft"></td>
                    <td><input type="button" onclick="parent.location.href='../../checkout.html'" class="btncenter" name="sublogin" value="Đặt hàng" /></td>
                    <td class="btnright"></td>
                  </tr>
                </table></td>
            </tr>
          </table></td>
      </tr>
      <tr>
        <td colspan="5" align="left"><p></p></td>
      </tr>
    </table>
  </div>
</form>
