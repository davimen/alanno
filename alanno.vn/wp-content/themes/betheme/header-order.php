<?php
/**
 * The Header for our theme.
 *
 * @package Betheme
 * @author Thuy.TQ
 * @link http://giaiphaptoiuu.net
 */
?><!DOCTYPE html>
<?php 
	if( $_GET && key_exists('mfn-rtl', $_GET) ):
		echo '<html class="no-js" lang="ar" dir="rtl">';
	else:
?>
<html class="no-js" <?php language_attributes(); ?><?php mfn_tag_schema(); ?>>
<?php endif; ?>

<!-- head -->
<head>
<meta name="google-site-verification" content="sOfESCh5MThayZUpQufZJn1OhtwPm4whf5XGG3ka3r8" />
<!-- meta -->
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<?php 
	if( mfn_opts_get('responsive') ){
		if( mfn_opts_get('responsive-zoom') ){
			echo '<meta name="viewport" content="width=device-width, initial-scale=1">';
		} else {
			echo '<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">';
		}
		 
	}
?>

<?php do_action('wp_seo'); ?>

<link rel="shortcut icon" href="<?php mfn_opts_show( 'favicon-img', THEME_URI .'/images/favicon.ico' ); ?>" />	
<?php if( mfn_opts_get('apple-touch-icon') ): ?>
<link rel="apple-touch-icon" href="<?php mfn_opts_show( 'apple-touch-icon' ); ?>" />
<?php endif; ?>	

<?php

$URL_GOC = explode(".html",$_SERVER["REQUEST_URI"]);
if(count($URL_GOC)>0)
{
   $URL=explode("/",$URL_GOC[0]);
}else
{
  $URL=explode("/",$_SERVER["REQUEST_URI"]);
}
array_shift($URL);
$page_url=strtolower($URL[0]);

switch($page_url)
{
	
	case "checkout":
	$path="Đặt hàng";
	break;

	case "checkout-complete":
	$path="Đặt hàng thành công";
	break;

	case "login":
	$path="Đăng nhập";
	break;

	case "register":
	$path="Đăng ký";
	break;

	case "account":
	$path="Tài khoản";
	break;

	case "change-password":
	$path="Đổi mật khẩu";
	break;

	case "forgot-password":
	$path="Quên mật khẩu";
	break;

	case "custormer-checkout":
	$path="Đặt hàng";
	break;

	case "custormer-checkout-step2":
	$path="Đặt hàng";
	break;

	default:
	$path="Đăng nhập";
	break;
}
?>
<title><?php echo $path;?></title>

<?php wp_head(); ?>

<link rel="stylesheet"  type="text/css" href="<?php echo home_url(); ?>/style/style.css"/>
<link rel="stylesheet"  type="text/css" href="<?php echo home_url(); ?>/js/fancybox/jquery.fancybox-1.3.1.css"/>
<script type="text/javascript" src="<?php echo home_url(); ?>/js/jquery.validate.pack.js"></script>
<script type="text/javascript" src="<?php echo home_url(); ?>/js/jquery.form.js"></script>
<script type="text/javascript" src="<?php echo home_url(); ?>/js/fancybox/jquery.fancybox-1.3.1.pack.js"></script>
<script type="text/javascript" src="<?php echo home_url(); ?>/js/openBox.js"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-87028700-1', 'auto');
  ga('send', 'pageview');

</script>
</head>

<!-- body -->
<body <?php body_class(); ?>>	
		
	<!-- #Wrapper -->
	<div id="Wrapper">
	
		<?php 
			// Header Featured Image ----------
			$header_style = '';
			
			// Image -----
			if( mfn_ID() && ! is_search() ){
				
				if( ( ( mfn_ID() == get_option( 'page_for_posts' ) ) || ( get_post_type( mfn_ID() ) == 'page' ) ) && has_post_thumbnail( mfn_ID() ) ){

					// Pages & Blog Page ---
					$subheader_image = wp_get_attachment_image_src( get_post_thumbnail_id( mfn_ID() ), 'full' );
					$header_style .= ' style="background-image:url('. $subheader_image[0] .');"';

				} elseif( get_post_meta( mfn_ID(), 'mfn-post-header-bg', true ) ){

					// Single Post ---
					$header_style .= ' style="background-image:url('. get_post_meta( mfn_ID(), 'mfn-post-header-bg', true ) .');"';

				}
			}
			
			// Attachment -----
			if( mfn_opts_get('img-subheader-attachment') == 'fixed' ){
				
				$header_style .= ' class="bg-fixed"';
				
			} elseif( mfn_opts_get('img-subheader-attachment') == 'parallax' ){
				
				if( mfn_opts_get( 'parallax' ) == 'stellar' ){
					$header_style .= ' class="bg-parallax" data-stellar-background-ratio="0.5"';
				} else {
					$header_style .= ' class="bg-parallax" data-enllax-ratio="0.3"';
				}
				
			}
		?>
		
		<?php if( mfn_header_style( true ) == 'header-below' ) echo mfn_slider(); ?>

		<!-- #Header_bg -->
		<div id="Header_wrapper" <?php echo $header_style; ?>>
	
			<!-- #Header -->
			<header id="Header">
			    
			    <div class="header-top">
				    <div class="container">
						<div class="wrap-inner clearfix">
							<h1 class="leftBox">CÔNG TY TNHH THIẾT KẾ THƯƠNG HIỆU VÀ IN ẤN ALONA</h1>
							<ul class="header-right rightBox clearfix">
								<li><a href="<?php echo home_url(); ?>/account.html" class="hover"><img src="<?php echo home_url(); ?>/style/images/icon_user.png" alt="user"></a></li>
								<?php
								  if($_SESSION["Free"]==1)
								  {
								  ?>
								  <li><a href="<?php echo home_url(); ?>/login.html" class="hover"><img src="<?php echo home_url(); ?>/style/images/icon_lock.png" alt="lock"></a></li>
								  <?php }else {?>
								  <li><a href="<?php echo home_url(); ?>/logout.html" class="hover"><img src="<?php echo home_url(); ?>/style/images/icon_unlock.png" alt="lock"></a></li>
								  <?php 
								  }
								  ?>
								<li><a href="<?php echo home_url(); ?>/modum/shopingcart/viewcart.php" class="hover viewcart"><img src="<?php echo home_url(); ?>/style/images/icon_cart.png" alt="cart"></a></li>
							</ul>
						</div>
					</div>	
				</div>
							
			
				<?php if( mfn_header_style( true ) != 'header-creative' ) get_template_part( 'includes/header', 'top-area' ); ?>	
				<?php if( mfn_header_style( true ) != 'header-below' ) echo mfn_slider(); ?>
			</header>
				
			<div id="Subheader" style="padding:100px 0; posi">
				<div class="container">
					<div class="column one">
						<h1 class="title"><?php echo $path;?></h1>
					</div>
				</div>
			</div>
		
		</div>	
