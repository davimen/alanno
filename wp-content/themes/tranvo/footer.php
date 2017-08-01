<?php 
	global $etheme_responsive; 
	$fd = etheme_get_option('footer_demo'); 	
	$fbg = etheme_get_option('footer_bg');
	$fcolor = etheme_get_option('footer_text_color');
	$ft = ''; $ft = apply_filters('custom_footer_filter',$ft);
	$custom_footer = etheme_get_custom_field('custom_footer'); 
?>

    <?php if($custom_footer != 'without'): ?>
		<?php if((is_active_sidebar('footer1') || $fd) && empty($custom_footer)): ?>
			<div class="footer-top footer-top-<?php echo esc_attr($ft); ?>">
				<div class="container">
	                <?php if ( !is_active_sidebar( 'footer1' ) ) : ?>
	               		<?php if($fd) etheme_footer_demo('footer1'); ?>
	                <?php else: ?>
	                    <?php dynamic_sidebar( 'footer1' ); ?>
	                <?php endif; ?>  
				</div>
			</div>
		<?php endif; ?>
		
	
		<?php if((is_active_sidebar('footer2') || $fd) && empty($custom_footer)): ?>
			<footer class="main-footer main-footer-<?php echo esc_attr($ft); ?> text-color-<?php echo $fcolor; ?>" <?php if($fbg != ''): ?>style="background-color:<?php echo $fbg; ?>"<?php endif; ?>>
				<div class="container">
	                <?php if ( !is_active_sidebar( 'footer2' ) ) : ?>
	               		<?php if($fd) etheme_footer_demo('footer2'); ?>
	                <?php else: ?>
	                    <?php dynamic_sidebar( 'footer2' ); ?>
	                <?php endif; ?>
	                <?php do_action('etheme_after_footer_widgets'); ?>
				</div>

			</footer>
		<?php endif; ?>
	
		<?php if(!empty($custom_footer)): ?>
            <footer class="main-footer main-footer-<?php echo esc_attr($ft); ?> text-color-<?php echo $fcolor; ?>" <?php if($fbg != ''): ?>style="background-color:<?php echo $fbg; ?>"<?php endif; ?>>
                <div class="container">
                    <?php echo et_get_block($custom_footer); ?>  
                </div>
            </footer>
        <?php endif; ?>
	
		<?php if((is_active_sidebar('footer9') || is_active_sidebar('footer10') || $fd) && empty($custom_footer)): ?>
		<div class="copyright copyright-<?php echo esc_attr($ft); ?> text-color-<?php echo $fcolor; ?>" <?php if($fbg != ''): ?>style="background-color:<?php echo $fbg; ?>"<?php endif; ?>>
			<div class="container">
				<div class="row-copyrights">
					<div class="pull-left">
						<?php if(is_active_sidebar('footer9')): ?> 
							<?php dynamic_sidebar('footer9'); ?>	
						<?php else: ?>
							<?php if($fd) etheme_footer_demo('footer9'); ?>
						<?php endif; ?>
					</div>
					<div class="clearfix visible-xs"></div>
					<div class="copyright-payment pull-right">

						<?php if(is_active_sidebar('footer10')): ?> 
							<?php dynamic_sidebar('footer10'); ?>	
						<?php else: ?>
							<?php if($fd) etheme_footer_demo('footer10'); ?>
						<?php endif; ?>
					</div>
				</div>
			</div>
		</div>
	    <?php endif; ?>
    <?php endif; ?>
	
	</div> <!-- page wrapper -->
	</div> <!-- st-content-inner -->
	</div>
	</div>
	<?php do_action('after_page_wrapper'); ?>
	</div> <!-- st-container -->
	

    <?php if (etheme_get_option('loader')): ?>
    	<script type="text/javascript">
    		if(jQuery(window).width() > 1200) {
		        jQuery("body").queryLoader2({
		            barColor: "#111",
		            backgroundColor: "#fff",
		            percentage: true,
		            barHeight: 2,
		            completeAnimation: "grow",
		            minimumTime: 500,
		            onLoadComplete: function() {
			            jQuery('body').addClass('page-loaded');
		            }
		        });
    		}
        </script>
	<?php endif; ?>
	
	<?php if (!etheme_get_option('to_top')): ?>
		<div id="back-top" class="back-top">
			<a href="#top" id="hien">
				<span></span>
			</a>
		</div>
	<?php endif ?>


	<?php
		/* Always have wp_footer() just before the closing </body>
		 * tag of your theme, or you will break many plugins, which
		 * generally use this hook to reference JavaScript files.
		 */

		wp_footer();
	?>
<div id="quangcao" style="display: none;">
<img src="http://www.vinaprint.vn/contact.png" width="200px">
</div>

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
var google_conversion_id = 860717358;
var google_custom_params = window.google_tag_params;
var google_remarketing_only = true;
/* ]]> */
</script>
<script type="text/javascript" src="//www.googleadservices.com/pagead/conversion.js">
</script>
<noscript>
<div style="display:inline;">
<img height="1" width="1" style="border-style:none;" alt="" src="//googleads.g.doubleclick.net/pagead/viewthroughconversion/860717358/?guid=ON&amp;script=0"/>
</div>
</noscript>
<!--Start of Tawk.to Script-->
<script type="text/javascript">
var Tawk_API=Tawk_API||{}, Tawk_LoadStart=new Date();
(function(){
var s1=document.createElement("script"),s0=document.getElementsByTagName("script")[0];
s1.async=true;
s1.src='https://embed.tawk.to/58df2c86f7bbaa72709c3970/default';
s1.charset='UTF-8';
s1.setAttribute('crossorigin','*');
s0.parentNode.insertBefore(s1,s0);
})();
</script>
<!--End of Tawk.to Script-->
</body>

</html>