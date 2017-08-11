<?php
/**
 * The template for displaying the footer.
 *
 * @package Betheme
 * @author Muffin group
 * @link http://muffingroup.com
 */


$back_to_top_class = mfn_opts_get('back-top-top');

if( $back_to_top_class == 'hide' ){
	$back_to_top_position = false;
} elseif( strpos( $back_to_top_class, 'sticky' ) !== false ){
	$back_to_top_position = 'body';
} elseif( mfn_opts_get('footer-hide') == 1 ){
	$back_to_top_position = 'footer';
} else {
	$back_to_top_position = 'copyright';
}

?>

<?php do_action( 'mfn_hook_content_after' ); ?>

<!-- #Footer -->		
<footer id="Footer" class="clearfix">
	
	<?php if ( $footer_call_to_action = mfn_opts_get('footer-call-to-action') ): ?>
	<div class="footer_action">
		<div class="container">
			<div class="column one column_column">
				<?php echo do_shortcode( $footer_call_to_action ); ?>
			</div>
		</div>
	</div>
	<?php endif; ?>
	
	<?php 
		$sidebars_count = 0;
		for( $i = 1; $i <= 4; $i++ ){
			if ( is_active_sidebar( 'footer-area-'. $i ) ) $sidebars_count++;
		}
		
		if( $sidebars_count > 0 ){
			
			$footer_style = '';
				
			if( mfn_opts_get( 'footer-padding' ) ){
				$footer_style .= 'padding:'. mfn_opts_get( 'footer-padding' ) .';';
			}
			
			echo '<div class="widgets_wrapper" style="'. $footer_style .'">';
				echo '<div class="container">';
						
					if( $footer_layout = mfn_opts_get( 'footer-layout' ) ){
						// Theme Options

						$footer_layout 	= explode( ';', $footer_layout );
						$footer_cols 	= $footer_layout[0];
		
						for( $i = 1; $i <= $footer_cols; $i++ ){
							if ( is_active_sidebar( 'footer-area-'. $i ) ){
								echo '<div class="column '. $footer_layout[$i] .'">';
									dynamic_sidebar( 'footer-area-'. $i );
								echo '</div>';
							}
						}						
						
					} else {
						// Default - Equal Width
						
						$sidebar_class = '';
						switch( $sidebars_count ){
							case 2: $sidebar_class = 'one-second'; break;
							case 3: $sidebar_class = 'one-third'; break;
							case 4: $sidebar_class = 'one-fourth'; break;
							default: $sidebar_class = 'one';
						}
						
						for( $i = 1; $i <= 4; $i++ ){
							if ( is_active_sidebar( 'footer-area-'. $i ) ){
								echo '<div class="column '. $sidebar_class .'">';
									dynamic_sidebar( 'footer-area-'. $i );
								echo '</div>';
							}
						}
						
					}
				
				echo '</div>';
			echo '</div>';
		}
	?>


	<?php if( mfn_opts_get('footer-hide') != 1 ): ?>
	
		<div class="footer_copy">
			<div class="container">
				<div class="column one">

					<?php 
						if( $back_to_top_position == 'copyright' ){
							echo '<a id="back_to_top" class="button button_left button_js" href=""><span class="button_icon"><i class="icon-up-open-big"></i></span></a>';
						}
					?>
					
					<!-- Copyrights -->
					<div class="copyright">
						<?php 
							if( mfn_opts_get('footer-copy') ){
								echo do_shortcode( mfn_opts_get('footer-copy') );
							} else {
								echo '&copy; '. date( 'Y' ) .' '. get_bloginfo( 'name' ) .'. All Rights Reserved. <a target="_blank" rel="nofollow" href="http://www.tranvo.vn">Tran Vo Company</a>';
							}
						?>
					</div>
					
					<?php 
						if( has_nav_menu( 'social-menu-bottom' ) ){
	
							// #social-menu
							mfn_wp_social_menu_bottom();
	
						} else {
							
							$target = mfn_opts_get('social-target') ? 'target="_blank"' : false;
	
							echo '<ul class="social">';
								if( mfn_opts_get('social-skype') ) echo '<li class="skype"><a '.$target.' href="'. mfn_opts_get('social-skype') .'" title="Skype"><i class="icon-skype"></i></a></li>';
								if( mfn_opts_get('social-facebook') ) echo '<li class="facebook"><a '.$target.' href="'. mfn_opts_get('social-facebook') .'" title="Facebook"><i class="icon-facebook"></i></a></li>';
								if( mfn_opts_get('social-googleplus') ) echo '<li class="googleplus"><a '.$target.' href="'. mfn_opts_get('social-googleplus') .'" title="Google+"><i class="icon-gplus"></i></a></li>';
								if( mfn_opts_get('social-twitter') ) echo '<li class="twitter"><a '.$target.' href="'. mfn_opts_get('social-twitter') .'" title="Twitter"><i class="icon-twitter"></i></a></li>';
								if( mfn_opts_get('social-vimeo') ) echo '<li class="vimeo"><a '.$target.' href="'. mfn_opts_get('social-vimeo') .'" title="Vimeo"><i class="icon-vimeo"></i></a></li>';
								if( mfn_opts_get('social-youtube') ) echo '<li class="youtube"><a '.$target.' href="'. mfn_opts_get('social-youtube') .'" title="YouTube"><i class="icon-play"></i></a></li>';						
								if( mfn_opts_get('social-flickr') ) echo '<li class="flickr"><a '.$target.' href="'. mfn_opts_get('social-flickr') .'" title="Flickr"><i class="icon-flickr"></i></a></li>';
								if( mfn_opts_get('social-linkedin') ) echo '<li class="linkedin"><a '.$target.' href="'. mfn_opts_get('social-linkedin') .'" title="LinkedIn"><i class="icon-linkedin"></i></a></li>';
								if( mfn_opts_get('social-pinterest') ) echo '<li class="pinterest"><a '.$target.' href="'. mfn_opts_get('social-pinterest') .'" title="Pinterest"><i class="icon-pinterest"></i></a></li>';
								if( mfn_opts_get('social-dribbble') ) echo '<li class="dribbble"><a '.$target.' href="'. mfn_opts_get('social-dribbble') .'" title="Dribbble"><i class="icon-dribbble"></i></a></li>';
								if( mfn_opts_get('social-instagram') ) echo '<li class="instagram"><a '.$target.' href="'. mfn_opts_get('social-instagram') .'" title="Instagram"><i class="icon-instagram"></i></a></li>';
								if( mfn_opts_get('social-behance') ) echo '<li class="behance"><a '.$target.' href="'. mfn_opts_get('social-behance') .'" title="Behance"><i class="icon-behance"></i></a></li>';
								if( mfn_opts_get('social-tumblr') ) echo '<li class="tumblr"><a '.$target.' href="'. mfn_opts_get('social-tumblr') .'" title="Tumblr"><i class="icon-tumblr"></i></a></li>';
								if( mfn_opts_get('social-vkontakte') ) echo '<li class="vkontakte"><a '.$target.' href="'. mfn_opts_get('social-vkontakte') .'" title="VKontakte"><i class="icon-vkontakte"></i></a></li>';
								if( mfn_opts_get('social-viadeo') ) echo '<li class="viadeo"><a '.$target.' href="'. mfn_opts_get('social-viadeo') .'" title="Viadeo"><i class="icon-viadeo"></i></a></li>';
								if( mfn_opts_get('social-xing') ) echo '<li class="xing"><a '.$target.' href="'. mfn_opts_get('social-xing') .'" title="Xing"><i class="icon-xing"></i></a></li>';
								if( mfn_opts_get('social-rss') ) echo '<li class="rss"><a '.$target.' href="'. get_bloginfo('rss2_url') .'" title="RSS"><i class="icon-rss"></i></a></li>';
							echo '</ul>';
					
						}
					?>
							
				</div>
			</div>
		</div>
	
	<?php endif; ?>
	
	
	<?php 
		if( $back_to_top_position == 'footer' ){
			echo '<a id="back_to_top" class="button button_left button_js in_footer" href=""><span class="button_icon"><i class="icon-up-open-big"></i></span></a>';
		}
	?>

	
</footer>

</div><!-- #Wrapper -->

<?php 
	if( $back_to_top_position == 'body' ){
		echo '<a id="back_to_top" class="button button_left button_js '. $back_to_top_class .'" href=""><span class="button_icon"><i class="icon-up-open-big"></i></span></a>';
	}
?>

<?php if( mfn_opts_get('popup-contact-form') ): ?>
	<div id="popup_contact">
		<a class="button button_js" href="#"><i class="<?php mfn_opts_show( 'popup-contact-form-icon', 'icon-mail-line' ); ?>"></i></a>
		<div class="popup_contact_wrapper">
			<?php echo do_shortcode( mfn_opts_get('popup-contact-form') ); ?>
			<span class="arrow"></span>
		</div>
	</div>
<?php endif; ?>

<?php do_action( 'mfn_hook_bottom' ); ?>
	
<!-- wp_footer() -->
<?php wp_footer(); ?>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=633487666792114";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>	
<?php if ( is_front_page() ) { ?>       
<div class="menu-one-page" style="display: none;">
    <div id="sidebar">
        <ul>
            <li><a href="#" id="click" title="Alona"><i class="fa fa-home"></i></a></li>
            <li><a href="#" id="click1" title="Thiết Kế logo"><i class="fa fa-pencil"></i></a></li>
            <li><a href="#" id="click2" title="Thiết Kế Website"><i class="fa fa-globe"></i></a></li>
            <li><a href="#" id="click3" title="Thiết kế Thương hiệu"><i class="fa fa-edit"></i></a></li>
            <li><a href="#" id="click4" title="Dịch vụ In ấn"><i class="fa fa-print"></i></a></li>
            <li><a href="#" id="click5" title="Thi công Quảng cáo"><i class="fa fa-camera"></i></a></li>
        </ul>
    </div>
</div>
<script type="text/javascript">
$ = jQuery;
$(document).ready(function(){

$("#click").click(function() {
    $('html, body').animate({
        scrollTop: $("#Header").offset().top
    }, 1000);
});
$("#click1").click(function() {
    $('html, body').animate({		
        scrollTop: $("#trangchu1").offset().top
    }, 1000);
});
$("#click2").click(function() {
    $('html, body').animate({
        scrollTop: $("#trangchu2").offset().top
    }, 1000);
});
$("#click3").click(function() {
    $('html, body').animate({
        scrollTop: $("#trangchu3").offset().top
    }, 1000);
});
$("#click4").click(function() {
    $('html, body').animate({
        scrollTop: $("#trangchu4").offset().top
    }, 2000);
});
$("#click5").click(function() {
    $('html, body').animate({
        scrollTop: $("#trangchu5").offset().top
    }, 2000);
});
});
</script>	
<?php } else { ?>

<?php } ?>
<div id="dienthoaipopup">
<center>
	<div class="dienthoai"><a id="clickdienthoai" href="#"><i class="fa fa-phone"></i></a></div>
	<div class="noidung">
		<ul>
			<li><a href="tel:+84866818850" class="noidung-item"> <i class="fa fa-phone"></i> (08) 6681 8850</a></li>	
			<li><a href="tel:+84963763079" class="noidung-item"> <i class="fa fa-phone"></i> 0963 763 079</a></li>					
			<li><a href="mailto:in@alona.vn" class="noidung-item"> <i class="fa fa-envelope"></i> in@alona.vn</a></li>
		</ul>
	</div>
</center>
</div>
<script type="text/javascript">
    /* <![CDATA[ */
    var google_conversion_id = 848020635;
    var google_custom_params = window.google_tag_params;
    var google_remarketing_only = true;
    /* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
    <div style="display:inline;">
        <img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/848020635/?guid=ON&amp;script=0"/>
    </div>
</noscript>
<script type="text/javascript">
$ = jQuery;
$(document).ready(function(){

    $("#clickdienthoai").click(function() {
		$("#dienthoaipopup .noidung").slideToggle( "slow" );
	
	});


});
</script>

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
                jQuery(".viewcart").fancybox(
                    {
                      'titleShow'		: false,
                       'width'				: 650,        
						'autoScale'			: true,
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

    jQuery(document).ready(function() {
        jQuery(".viewcart").fancybox({
        'titleShow'		: false,
        'width'				: 650,        
        'autoScale'			: true,
        'overlayOpacity'    : 0.8,
        'overlayColor'      : '#000',
        'transitionIn'	: 'none',
        'transitionOut'	: 'none',
        'type'				: 'iframe'
        });
        });
 
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


</body>
</html>