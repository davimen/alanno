<?php
/**
 * @package WordPress
 * @since WD_GoMarket
 */

if(!function_exists('wd_sale_products')){
	function wd_sale_products($atts,$content){
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
			return;
		}
		global $woocommerce_loop, $woocommerce,$wd_data;
		$atts['columns'] = absint($atts['columns']);
		$atts['per_page'] = absint($atts['per_page']);			
		extract(shortcode_atts(array(
			'title' 			=> '',
			'desc' 				=> '',
			'box_style'			=> 'style-1',
			'columns' 			=> 4,
			'per_page'			=> 12,
			'orderby'			=> 'date',
			'order'				=> 'desc',
			'style'				=> 'grid',
			'show_number' 		=> 0,
			'show_image' 		=> 1,
			'show_title' 		=> 1,
			'show_sku' 			=> 1,
			'show_price' 		=> 1,
			'show_except' 		=> 1,
			'show_rating' 		=> 1,
			'show_label' 		=> 1,
			'show_prod_buttons'	=> 1,
			'show_countdown'	=> 0,
			'shortc_limit'		=> 15,
			'deals_filter'		=> '',
			'show_nav' 			=> 1,
			'show_nav_pos' 		=> 'top_right',
			'show_icon_nav' 	=> 0,
			'auto_sale_repeat'	=> 0,
			'auto_sale_ids'		=> '',
		),$atts));
		$shortc_limit = (isset($shortc_limit) && $shortc_limit !== '')? $shortc_limit: 15;
		// Get products on sale
		
		if(strlen(trim($deals_filter)) > 0) {
			$today = getdate();
			switch($deals_filter) {
				case 'd':
					$fil_date = strtotime ( "+1 day", strtotime ("Today") );
					break;
				case 'w':
					$fil_date = strtotime ("next Monday");
					break;
				case 'mo':
					$fil_date = strtotime("{$today['year']}-{$today['mon']} +1 month last day");
					break;
				case 'ye':
					$fil_date = strtotime("{$today['year']}-{$today['mon']} +1 year last day");
					break;
			}
			$meta_query = array(
				array(
					'key' => '_sale_price_dates_to',
					'value' => $fil_date,
					'compare' => '<=',
					'type' => 'numeric'
				),
				array(
					'key' => '_sale_price_dates_to',
					'value' => 0,
					'compare' => '>',
					'type' => 'numeric'
				)
			);
			
		} else {
			$meta_query = WC()->query->get_meta_query();
		}
		
		if( absint($auto_sale_repeat) > 0 && strlen($auto_sale_ids) ) {
			wd_update_sales( $auto_sale_ids , $fil_date);
		}
		
		
		$product_ids_on_sale = wc_get_product_ids_on_sale();
		$per_page = strcmp($style, 'big_prod') == 0? 3: $per_page;
		
		$args = array(
			'posts_per_page'	=> $per_page,
			'orderby' 			=> $orderby,
			'order' 			=> $order,
			'no_found_rows' 	=> 1,
			'post_status' 		=> 'publish',
			'post_type' 		=> 'product',
			'meta_query' 		=> $meta_query,
			'post__in'			=> array_merge( array( 0 ), $product_ids_on_sale )
		);
		
		$data = array('show_image' => $show_image,
						'show_title' => $show_title,
						'show_rating' => $show_rating,
						'show_price' => $show_price,
						'show_label' => $show_label,
						'show_add_to_cart' => $show_prod_buttons,
						'show_countdown'	=> $show_countdown,
					);
		
		shop_loop_prod_remove_action($data);
		
		ob_start();

		$products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );
		
		$woocommerce_loop['columns'] = strcmp($style,'big_prod') !== 0? $columns: 1;
		
		$slide_inMobile = (wp_is_mobile() && $style !== 'widget')? true: false;
		if(absint($columns) <= 2) $slide_inMobile = false;
		
		//$style = $slide_inMobile? 'grid':  $style;
		$slide_inMobile = true;
		
		if ( $products->have_posts() ) : ?>
			
			<div class="products_shortcode_wrapper <?php echo esc_attr($box_style);?>">
			<?php if( strlen(trim($title)) > 0 ):?>
				<h3 class='heading-title slider-title'><?php echo $title;?></h3>
				<div class='line line-30'></div>
				<?php 
				if(absint($show_nav) && trim($show_nav_pos) == 'top_right'): 
					$show_nav = 0; $show_icon_nav = 0; ?>
					<div class="owl-controls owl-heading-pos">
						<div class="owl-nav">
							<div class="owl-prev" id="<?php echo $_random_id;?>_prev">&lt;</div>
							<div class="owl-next" id="<?php echo $_random_id;?>_next">&gt;</div>
						</div>
					</div>
				<?php endif;?>
			<?php endif;?>
			<?php if($slide_inMobile):
			$_random_id = 'recent_product_wrapper_'.rand();?>
			<div id="<?php echo esc_attr($_random_id);?>" class="row <?php echo esc_attr($show_nav_pos);?>"><!--wd-loading-->
			<?php endif;?>
			
			<?php 
			$wd_style = ( $style == 'big_prod' )? 'list': $style;
			wd_woocommerce_product_loop_start($wd_style); ?>
				<?php if($style == 'widget'):?>
				<div class="products_group">
				<?php endif;?>
				<?php 
				global $wd_count; $wd_count = 1;
				$i = 1;
				if(absint($show_number)) add_action('wd_product_loop_before', 'wd_add_number_product_list');?>
				
				<?php while ( $products->have_posts() ) : $products->the_post(); ?>
					
					<?php 
					if( $style == 'big_prod') { 
						$woocommerce_loop['columns'] = 1;
						$columns = 1;
						if( $i == 1 ){
					?>
					<div class="wd_big_prodcut_shortcode">
					<div class="col-md-15 col-sm-24">
						<?php wc_get_template( "content-product.php", array( 'shortc_limit' => $shortc_limit ) ); ?>
					</div>
					<?php } else {
							if( $i == 2 ) echo "<div class=\"col-md-9 col-sm-24\"><div class=\"wd_product_slider\">";
							
							//if($i % 2 == 0) echo '<div class="products_group">';
							wc_get_template( "content-product-small.php", array( 'shortc_limit' => $shortc_limit ) );
							//if($i % 2 == 1 || $i == $products->post_count) echo "</div><!--.products_group-->";
							
							if( $i == $products->post_count ) echo "</div></div></div><!--end .wd_big_prodcut_shortcode-->";
						}
					
					} else {
						$content_type = strcmp(trim($style), 'widget') == 0 ? 'list-product' : 'product';
						wc_get_template( "content-{$content_type}.php", array( 'shortc_limit' => $shortc_limit ) ); 
					} 
					$i++;
					?>
					
					<?php //$content_type = strcmp(trim($style), 'widget') == 0 ? 'list-product' : 'product';?>
					<?php //wc_get_template( "content-{$content_type}.php", array( 'shortc_limit' => $shortc_limit ) ); ?>	

				<?php endwhile; // end of the loop. ?>
				<?php remove_action('wd_product_loop_before', 'wd_add_number_product_list');?>
				<?php if($style == 'widget'):?>
				</div>
				<?php endif;?>
				
			<?php woocommerce_product_loop_end(); ?>
			<?php if($slide_inMobile):?>
			</div>
			<script type='text/javascript'>
				//<![CDATA[
					jQuery(document).ready(function() {
						"use strict";
						var temp_visible = <?php echo $columns;?>;
						
						var row = 1;

						var item_width = 180;
						
						var show_nav = <?php if($show_nav): ?> true <?php else: ?> false <?php endif;?>;

						var show_icon_nav = false;
						
						var object_selector = "#<?php echo $style == 'big_prod'? $_random_id . ' .wd_big_prodcut_shortcode .wd_product_slider' : $_random_id . ' .products'; ?>";

						var autoplay = false;
						//generate_horizontal_slide(temp_visible,row,item_width,show_nav,show_icon_nav,autoplay,object_selector, false);
					});
				//]]>
				</script>
				<?php endif;?>
				</div>
		<?php endif;
		
		wp_reset_postdata();
		
		shop_loop_prod_add_action();
		
		return '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';
			
		}
		
		function wd_update_sales( $ids, $datetime ){
			
			$id_args = explode( ',', trim($ids) );
			$trans = false;
			if(!is_array($id_args)) return false;
			foreach( $id_args as $data ){
				$d = explode( '|', trim($data) );
				
				if(!is_array($d)) continue;
				if( !get_post_meta( absint(trim($d[0])), '_sale_price', true ) ) {
					update_post_meta( absint(trim($d[0])), '_sale_price', wc_format_decimal($d[1]) );
					update_post_meta( absint(trim($d[0])), '_price', wc_format_decimal($d[1]) );
					$trans = true;
				}
				
				if( get_post_meta( absint(trim($d[0])), '_sale_price_dates_to', true ) !== $datetime ) {
					update_post_meta( absint(trim($d[0])), '_sale_price_dates_from', strtotime ("Today") );
					update_post_meta( absint(trim($d[0])), '_sale_price_dates_to', $datetime );
				}
			}
			if( $trans ) delete_transient( 'wc_products_onsale' );
		}
	}		
	add_shortcode('wd_sale_products','wd_sale_products');
	
	
	
?>