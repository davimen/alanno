<?php
session_start();
error_reporting(0);

define('WP_USE_THEMES', false);
require( dirname( __FILE__ ) . '/wp-blog-header.php' );
		
include str_replace('\\','/',dirname(__FILE__)).'/class/defineConst.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.BUSINESSLOGIC.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.CSS.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.JAVASCRIPT.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.HTML.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.utilities.php';
include str_replace('\\','/',dirname(__FILE__)).'/class/class.SINGLETON_MODEL.php';

$dbf=SINGLETON_MODEL::getInstance("BUSINESSLOGIC");
$html=SINGLETON_MODEL::getInstance("HTML");
$css=SINGLETON_MODEL::getInstance("CSS");
$js=SINGLETON_MODEL::getInstance("JAVASCRIPT");
$utl=SINGLETON_MODEL::getInstance("UTILITIES");



$words = $dbf->getwords('vn', $arrayLang);
 
if(empty($_SESSION["login"])){
    $_SESSION["login"]=session_id();
    $_SESSION["Free"]=1;
}

if($URL[0]==md5("signout".date("dmH")))
    $dbf->signout();
?>

<?php get_header(); ?>

<?php

$arrayCSS=array("style/style.css","/js/fancybox/jquery.fancybox-1.3.1.css");
foreach($arrayCSS as $value) $css->linkCSS($value);

$arrayJS=array("js/jquery.validate.pack.js","js/jquery.form.js","js/fancybox/jquery.fancybox-1.3.1.pack.js","js/openBox.js");
foreach($arrayJS as $value) $js->linkJS($value);
?>

<script language="JavaScript" type="text/javascript">   

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

function addcart(productid,quantity,price)
   {
         var id_quantity_pro = quantity+'_quantity';
         var quantity_pro = document.getElementById(id_quantity_pro).value;
         var quantity_cart = document.getElementById(quantity).value;

        if(parseInt(quantity_cart)>0)
        {
        if(parseInt(quantity_pro)!=0)
         {
             if(parseInt(quantity_cart) > parseInt(quantity_pro))
             {
               alert("Lưu ý: Hiện sản phẩm chỉ còn có "+quantity_pro+" cái. Quý khách sẽ mua với số lượng còn lại?");
               quantity_cart = quantity_pro;
               document.getElementById(id_quantity_pro).value=0;
             }else
             {
               document.getElementById(id_quantity_pro).value=quantity_pro-quantity_cart;
             }
            var url = "addcart.php";
            url+='?product_id='+productid+'&quantity='+quantity_cart+'&price='+price;
            document.getElementById("content_msg").innerHTML="<img src='style/images/loading.gif' border='0' /> Please wait...</div>";
    		initRequest(url);
    		xmlRequest.open("GET", url, true);
    		xmlRequest.onreadystatechange = callback;
    		xmlRequest.send(null);
        }else
        {
          alert("Sản phẩm đã hết. Quý khách vui lòng chọn sản phẩm khác");
        }
        }else
        {
           alert("Quý khách vui lòng nhập lại số lượng sản phẩm cần mua.");
        }
   } ;


   function callback(){
		if (xmlRequest.readyState == 4) {
			if (xmlRequest.status == 200) {
                var data = xmlRequest.responseText;
                document.getElementById("content_msg").style.display='none';
                document.getElementById("itemcart").innerHTML=data;
                $(".viewcart").fancybox(
                    {
                      'titleShow'		: false,
                      'width'				: 600,
                      'height'			: 500,
                      'autoScale'			: false,
                      'overlayOpacity'    : 0.8,
                      'overlayColor'      : '#000',
                      'transitionIn'	: 'none',
                      'transitionOut'	: 'none',
                      'type'				: 'iframe'
                      }
                ).trigger('click');
               // setTimeout('$.fancybox.close()', 5000);
               // setTimeout('location.reload()', 6000);

			 } else if (xmlRequest.status == 204){
			    document.getElementById("content_msg").style.display='none'
				alert("Bị lỗi! Thêm giỏ hàng bị lỗi</i>");
			}
		}

	}
</script>

<!-- #Content -->
<div id="Content">
	<div class="content_wrapper clearfix">

		<!-- .sections_group -->
		<div class="sections_group">
		
			<div class="entry-content" itemprop="mainContentOfPage">
			  <?php 			    	
				include("modum/bodymain.php"); 
			  ?>  
			</div>
		</div>		
		<!-- .four-columns - sidebar -->
		<?php get_sidebar(); ?>
	</div>
</div>
<div id="content_msg"></div>
<script type="text/javascript">
//<![CDATA[

    $(document).ready(function() {
        $(".viewcart").fancybox({
        'titleShow'		: false,
        'width'				: 600,
        'height'			: 500,
        'autoScale'			: false,
        'overlayOpacity'    : 0.8,
        'overlayColor'      : '#000',
        'transitionIn'	: 'none',
        'transitionOut'	: 'none',
        'type'				: 'iframe'
        });
        });
 //]]>
</script>

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

/*]]>*/
</script>
<?php get_footer();