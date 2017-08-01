<?php
/*
Plugin Name: WD Countdown
Plugin URI: http://www.wpdance.com/
Description: Help you to sell certain products with discount during limited time
Author: Wpdance
Version: 1.0.0
Author URI: http://www.wpdance.com/
*/

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {

	/**
	 * WD_Countdown class
	 **/
	if (!class_exists('WD_Countdown')) {

		class WD_Countdown {

			public function __construct() {
				
				if( !preg_match('/(?i)msie [1-8]/', $_SERVER['HTTP_USER_AGENT']) ){
			
					// Constant
					define('WD_COUNTDOWN_IMG', plugins_url() . '/wd_countdown/assets/img');
					define('WD_COUNTDOWN_TEMPLATES', dirname(__FILE__) . '/templates');
					
					// Hooks
					add_action( 'wp' , array( $this, 'setup_countdown' ) , 20);
					
					// Define the custom box
					add_action( 'add_meta_boxes', array( $this, 'wd_countdown_add' ) );
					
					// Do something with the data entered
					add_action( 'save_post', array( $this, 'wd_countdown_save' ) );
				}
			}

			/*-----------------------------------------------------------------------------------*/
			/* Class Functions */
			/*-----------------------------------------------------------------------------------*/
			
			// Init settings
			function wd_countdown_add() {
				add_meta_box("product_details", "Product Countdown Settings", array( $this, "wd_countdown_layout" ), "product", "normal", "high");
			}
			
			// Prints the box content
			function wd_countdown_layout() {
				require_once WD_COUNTDOWN_TEMPLATES.'/countdown_layout.php';
			}
			
			// When the post is saved, saves our custom data
			function wd_countdown_save( $post_id ) { 
				// verify if this is an auto save routine. 
				// If it is our form has not been submitted, so we dont want to do anything
				if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) 
					return $post_id;
				
				// OK, we're authenticated: we need to find and save the data
				if ( isset ( $_POST['wd_countdown_use'] ) && isset ( $_POST['wd_countdown_text'] ) ) {
					
					$_countdown_config = array(
						'countdown_use' 			=> $_POST['wd_countdown_use']
						,'countdown_text' 			=> $_POST['wd_countdown_text']	
					);	
					$_countdown = serialize($_countdown_config);
					update_post_meta( $post_id, 'wd_countdown', $_countdown);	
				}
			}


			// Setup
			function setup_countdown() {			
				add_action( 'wp_enqueue_scripts', array( $this, 'setup_scripts_styles' ), 20);
				add_action( 'wp_enqueue_scripts', array( $this, 'setup_scripts_script' ), 20);
				
				//add_action( 'woocommerce_after_shop_loop_item', array( $this, 'countdown_open_div_style' ), 1 );
				//add_action( 'woocommerce_after_shop_loop_item', array( $this, 'countdown_set_default_view' ) );
				//add_action( 'woocommerce_after_shop_loop_item', array( $this, 'countdown_close_div_style' ), 1 );


				add_action( 'woocommerce_single_product_summary', array( $this, 'countdown_set_default_view' ), 28 );
			}
			
			
			
			// Scripts & styles
			function setup_scripts_styles() {
				wp_enqueue_style( 'jquery.countdown', plugins_url( '/assets/css/countdown.css', __FILE__ ) );
			}
			function setup_scripts_script() {
				wp_enqueue_script( 'plugin.countdown', plugins_url( '/assets/js/jquery.plugin.min.js', __FILE__ ), array( 'jquery' ) );
				wp_enqueue_script( 'jquery.countdown', plugins_url( '/assets/js/jquery.countdown.min.js', __FILE__ ), array( 'jquery' ) );
				wp_enqueue_script( 'script.countdown', plugins_url( '/assets/js/countdown.js', __FILE__ ), array( 'jquery' ) );
			}
			
			function countdown_open_div_style(){
				echo "<div class='count_holder_big'>";
			}
			
			function countdown_close_div_style(){
				echo "</div>";
			}
			
			function get_time_difference( $start, $end ){
				$uts['start'] = strtotime( $start );
				$uts['end'] = strtotime( $end );
				if( $uts['start']!==-1 && $uts['end']!==-1 ){
					if( $uts['end'] >= $uts['start'] ){
						$diff = $uts['end'] - $uts['start'];
						if( $days=intval((floor($diff/86400))) )$diff = $diff % 86400;
						if( $hours=intval((floor($diff/3600))) )$diff = $diff % 3600;
						if( $minutes=intval((floor($diff/60))) )$diff = $diff % 60;
						$diff = intval( $diff );
						return( array('days'=>$days, 'hours'=>$hours, 'minutes'=>$minutes, 'seconds'=>$diff) );
					}
				}else{
					return( false );
				};
			}

			function countdown_set_default_view() {
				global $post, $product;
				
				$_countdown_config = get_post_meta( $post->ID, 'wd_countdown', true );
				
				// if not exists config
				if( strlen($_countdown_config) > 0 ){
					$_countdown_config = unserialize($_countdown_config);
		
					$wd_countdown_use = $_countdown_config['countdown_use'];
					
					// if don't use for this product
					if (!isset($wd_countdown_use) || $wd_countdown_use == 0)
						return false;
						
					$wd_countdown_text = $_countdown_config['countdown_text'];
				
					// check product's type
					switch($product->product_type){
						case 'simple':
						case 'external':
							
							// if product's out of stock
							if ( ! $product->is_in_stock() )
								return false;
								
							//get the sale price of the product whether it be simple, grouped or variable
							$sale_price = get_post_meta( $post->ID, '_price', true);
							 
							//get the regular price of the product, but of a simple product
							$regular_price = get_post_meta( $post->ID, '_regular_price', true);
							
							if( $regular_price <= $sale_price )
								return false;
								
							$date_from 				= get_post_meta( $post->ID, '_sale_price_dates_from', true );
							$date_to 				= get_post_meta( $post->ID, '_sale_price_dates_to', true );
							
							$sale_price_dates_from 	= $date_from ? date( 'm/d/Y H:i:s', $date_from ) : '';
							$sale_price_dates_to 	= $date_to ? date( 'm/d/Y H:i:s', $date_to ) : '';
							
							$today = date("m/d/Y H:i:s");
							$remain_from = $this->get_time_difference($sale_price_dates_from, $today);
							$remain_to = $this->get_time_difference($today, $sale_price_dates_to);
							
							if(is_array($remain_from) && is_array($remain_to)){
								$_random_id = $post->ID . rand();
							?>
								<div class="hidden-xs">
									<div class="count_holder_small">
										<div class="count_info"><?php echo $wd_countdown_text; ?></div>
										<div id="countdown<?php echo $_random_id;?>"></div>
									</div>
								</div>
								
								<script type="text/javascript">
									jQuery(document).ready(function ($) {    
										$('#countdown<?php echo $_random_id; ?>').countdown({
											until: new Date(<?php echo date( 'Y', $date_to ); ?>, <?php echo date( 'm', $date_to ); ?> - 1, <?php echo date( 'd', $date_to ); ?>)
										});
									});
								 </script>
							
							<?php
							}
							
						break;
						
						case 'grouped':
							
						break;
						
						case 'variable':
							$available_variations = $product->get_available_variations();
							
							for( $i = 0; $i < count($available_variations); $i++){
								$variation_id = $available_variations[$i]['variation_id'];
								
								$variable_product = new WC_Product_Variation( $variation_id );
								
								// if variable product's out of stock
								if ( ! $variable_product->is_in_stock() )
									continue;
								
								$sale_price = $variable_product->sale_price;
								$regular_price = $variable_product->regular_price;
								
								if( $regular_price <= $sale_price )
									continue;
								
								$date_from 				= get_post_meta( $variable_product->get_variation_id(), '_sale_price_dates_from', true );
								$date_to 				= get_post_meta( $variable_product->get_variation_id(), '_sale_price_dates_to', true );
								
								$sale_price_dates_from 	= $date_from ? date( 'm/d/Y H:i:s', $date_from ) : '';
								$sale_price_dates_to 	= $date_to ? date( 'm/d/Y H:i:s', $date_to ) : '';
								
								$today = date("m/d/Y H:i:s");
								$remain_from = $this->get_time_difference($sale_price_dates_from, $today);
								$remain_to = $this->get_time_difference($today, $sale_price_dates_to);
								
								if(is_array($remain_from) && is_array($remain_to)){
									$_random_id = $variable_product->ID . rand();
								?>
									<div class="hidden-xs">
										<div class="count_holder_small">
											<div class="count_info"><?php echo $wd_countdown_text; ?></div>
											<div id="countdown<?php echo $_random_id;?>"></div>
										</div>
									</div>
									
									<script type="text/javascript">
										jQuery(document).ready(function ($) {    
											$('#countdown<?php echo $_random_id; ?>').countdown({
												until: new Date(<?php echo date( 'Y', $date_to ); ?>, <?php echo date( 'm', $date_to ); ?> - 1, <?php echo date( 'd', $date_to ); ?>)
											});
										});
									 </script>
								
								<?php
									break;
								}
							}
						break;
					}
				}
			}
		}
		$WD_Countdown = new WD_Countdown();
	}
}
