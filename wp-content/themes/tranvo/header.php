<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <?php global $etheme_responsive, $woocommerce;; ?>
	<meta charset="<?php bloginfo( 'charset' ); ?>" />
    <?php if($etheme_responsive): ?>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1"/>
    <?php endif; ?>
	<link rel="shortcut icon" href="<?php echo et_get_favicon(); ?>" />
	<title><?php wp_title( '|', true, 'right' );?></title>
		<?php
			if ( is_singular() && get_option( 'thread_comments' ) )
				wp_enqueue_script( 'comment-reply' );

			wp_head();
		?>
		<meta name="google-site-verification" content="scSEzNU9c8N6nvGfxwnp-uuxvVK-axtivLY20hA0Dhs" />
<div id="fb-root"></div>
<script>(function(d, s, id) {
  var js, fjs = d.getElementsByTagName(s)[0];
  if (d.getElementById(id)) return;
  js = d.createElement(s); js.id = id;
  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&version=v2.5&appId=633487666792114";
  fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));</script>		
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-74584509-1', 'auto');
  ga('send', 'pageview');

</script>
<script type="text/javascript">
$ = jQuery;
$(document).ready(function(){

    $("#clickdienthoai").click(function() {
		$("#dienthoaipopup .noidung").slideToggle( "slow" );
	
	});


});
</script>
</head>

<body <?php body_class(); ?>>
<b:if cond='data:blog.pageType != &quot;item&quot;'>
<div style='display:none;'>
<div itemscope='' itemtype='http://schema.org/Recipe'>
<span itemprop='name'>Công ty in ấn tại TP HCM - VinaPrint - 100% KH hài lòng</span>
<img alt= 'Công ty in ấn tại TP HCM - VinaPrint' itemprop='image'
src= 'http://www.vinaprint.vn/wp-content/uploads/2016/02/logo.png ' title= 'Công ty in ấn tại TP HCM - VinaPrint '/>
<div itemprop='aggregateRating' itemscope=''
itemtype='http://schema.org/AggregateRating'>
<span itemprop='ratingValue'>9</span>/<span itemprop='bestRating'>10</span>
<span itemprop='ratingCount'>1521</span> bình chọn
</div>
</div></div>
</b:if>

<div id="st-container" class="st-container">

	<nav class="st-menu mobile-menu-block">
		<div class="nav-wrapper">
			<div class="st-menu-content">
				<div class="mobile-nav">
					<div class="close-mobile-nav close-block mobile-nav-heading"><i class="fa fa-bars"></i> <?php _e('Navigation', ETHEME_DOMAIN); ?></div>
					<?php 
						et_get_mobile_menu();
					?>
					
					
					<?php if (etheme_get_option('top_links')): ?>
						<div class="mobile-nav-heading"><i class="fa fa-user"></i><?php _e('Account', ETHEME_DOMAIN); ?></div>
						<?php etheme_top_links(array('popups' => false)); ?>
					<?php endif; ?>	
					
					<?php if(!function_exists('dynamic_sidebar') || !dynamic_sidebar('mobile-sidebar')): ?>
						
					<?php endif; ?>	
				</div>
			</div>
		</div>
		
	</nav>
	
	<div class="st-pusher" style="background-color:#fff;">
	<div class="st-content">
	<div class="st-content-inner">
	<div class="page-wrapper fixNav-enabled">
	
		<?php $ht = ''; $ht = apply_filters('custom_header_filter',$ht); ?>
		<?php $hstrucutre = etheme_get_header_structure($ht); ?>

		<?php if (etheme_get_option('fixed_nav')): ?>
			<div class="fixed-header-area fixed-header-type-<?php echo esc_attr($ht); ?>">
				<div class="fixed-header">
					<div class="container">
					
						<div id="st-trigger-effects" class="column">
							<button data-effect="mobile-menu-block" class="menu-icon"></button>
						</div>
						    
						<div class="header-logo">
							<?php etheme_logo(true); ?>
						</div>

						<div class="collapse navbar-collapse">
								
							<?php et_get_main_menu(); ?>
							
						</div><!-- /.navbar-collapse -->
						
						<div class="navbar-header navbar-right">
							<div class="navbar-right">
					            <?php if(class_exists('Woocommerce') && !etheme_get_option('just_catalog') && etheme_get_option('cart_widget')): ?>
				                    <?php etheme_top_cart(); ?>
					            <?php endif ;?>
						            
								<?php if(etheme_get_option('search_form')): ?>
									<?php etheme_search_form(); ?>
								<?php endif; ?>

							</div>
						</div>
						
						<div class="modal-buttons">
							<?php if (class_exists('Woocommerce') && etheme_get_option('top_links')): ?>
	                        	<a href="#cartModal" class="popup-btn shopping-cart-link hidden-lg">&nbsp;</a>
							<?php endif ?>
							<?php if (is_user_logged_in() && etheme_get_option('top_links')): ?>
								<a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" class="my-account-link hidden-lg">&nbsp;</a>
							<?php elseif(etheme_get_option('top_links')): ?>
								<a href="#loginModal" class="popup-btn my-account-link hidden-lg">&nbsp;</a>
							<?php endif ?>
							
							<?php if(etheme_get_option('search_form')): ?>
								<?php etheme_search_form(); ?>
							<?php endif; ?>
						</div>
							
					</div>
				</div>
			</div>
		<?php endif ?>
		
		<?php get_template_part('headers/header-structure', $hstrucutre); ?>