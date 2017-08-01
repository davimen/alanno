<?php
/**
 * @package WordPress
 * @since WD_GoMarket
 */

if(!function_exists('wd_recent_products')){
	function wd_recent_products($atts,$content){
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
			'small_prod'		=> 0,
			'show_number' 		=> 0,
			'show_image' 		=> 1,
			'show_title' 		=> 1,
			'show_sku' 			=> 1,
			'show_price' 		=> 1,
			'show_except' 		=> 1,
			'show_rating' 		=> 1,
			'show_label' 		=> 1,
			'show_prod_buttons'	=> 1,
			'shortc_limit'		=> 15,
		),$atts));
		$shortc_limit = (isset($shortc_limit) && $shortc_limit !== '')? $shortc_limit: 15;
		$meta_query = WC()->query->get_meta_query();
			
		$args = array(
			'post_type'				=> 'product',
			'post_status'			=> 'publish',
			'ignore_sticky_posts'	=> 1,
			'posts_per_page' 		=> $per_page,
			'orderby' 				=> $orderby,
			'meta_query' 			=> $meta_query
		);
		
		if($orderby !== 'rand'){
			$args['order'] = $order;
		}
		
		$data = array('show_image' => $show_image,
						'show_title' => $show_title,
						'show_rating' => $show_rating,
						'show_price' => $show_price,
						'show_label' => $show_label,
						'show_add_to_cart' => $show_prod_buttons,
					);
		shop_loop_prod_remove_action($data);
		
		ob_start();

		$products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

		$woocommerce_loop['columns'] = $columns;
		
		$slide_inMobile = (wp_is_mobile() && $style !== 'widget')? true: false;
		if(absint($columns) <= 2) $slide_inMobile = false;
		
		$style = $slide_inMobile? 'grid':  $style;
		
		$slide_inMobile = false;
		
		if ( $products->have_posts() ) : ?>
			
			<div class="products_shortcode_wrapper <?php echo esc_attr($box_style);?>">
			<?php if( strlen(trim($title)) > 0 ):?>
				<h3 class='heading-title slider-title'><?php echo $title;?></h3>
				<div class='line line-30'></div>
			<?php endif;?>
			<?php if($slide_inMobile):
			$_random_id = 'recent_product_wrapper_'.rand();?>
			<div id="<?php echo esc_attr($_random_id);?>" class="row bottom_center wd-loading">
			<?php endif;?>
			
			<?php wd_woocommerce_product_loop_start($style); ?>
				<?php if($style == 'widget'):?>
				<div class="products_group">
				<?php endif;?>
				<?php 
				global $wd_count; $wd_count = 1;
				if(absint($show_number)) add_action('wd_product_loop_before', 'wd_add_number_product_list');?>
				<?php while ( $products->have_posts() ) : $products->the_post(); ?>
					
					<?php $content_type = strcmp(trim($style), 'widget') == 0 ? 'list-product' : 'product';?>
					<?php if( absint($small_prod) == 1 ) $content_type = 'product-small';?>
					<?php wc_get_template( "content-{$content_type}.php", array( 'shortc_limit' => $shortc_limit ) ); ?>					

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
						
						var show_nav = false;

						var show_icon_nav = false;
						
						var object_selector = "#<?php echo $_random_id?> .products";

                       var autoplay = false;
                        generate_horizontal_slide(temp_visible,row,item_width,show_nav,show_icon_nav,autoplay,object_selector, true, [2,2,3,4,4]);
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
	}		
	add_shortcode('wd_recent_products','wd_recent_products');
	
	
	
if( !function_exists('wd_onepage_product') ) {
	function wd_onepage_product( $atts, $content ){
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) return;
		if ( empty( $atts ) ) return '';
		global $woocommerce_loop, $woocommerce,$wd_data;
		
		extract(shortcode_atts(array(
			'id'				=> '',
			'sku'				=> '',
			'small_prod'		=> 0,
			'show_number' 		=> 0,
			'show_image' 		=> 1,
			'show_title' 		=> 1,
			'show_sku' 			=> 1,
			'show_price' 		=> 1,
			'show_except' 		=> 1,
			'show_rating' 		=> 1,
			'show_label' 		=> 1,
			'show_prod_buttons'	=> 1,
			'shortc_limit'		=> 15,
		),$atts));
		
		$meta_query = WC()->query->get_meta_query();
		
		$args = array(
			'post_type'      => 'product',
			'posts_per_page' => 1,
			'no_found_rows'  => 1,
			'post_status'    => 'publish',
			'meta_query'     => $meta_query
		);
		
		if ( isset( $atts['sku'] ) ) {
			$args['meta_query'][] = array(
				'key' 		=> '_sku',
				'value' 	=> $sku,
				'compare' 	=> '='
			);
		}
		
		if ( isset( $atts['id'] ) ) {
			$args['p'] = $id;
		}
		
		$data = array('show_image' => $show_image,
						'show_title' => $show_title,
						'show_rating' => $show_rating,
						'show_price' => $show_price,
						'show_label' => $show_label,
						'show_add_to_cart' => 0,
					);
		shop_loop_prod_remove_action($data);
		
		ob_start();

		$products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );

		$woocommerce_loop['columns'] = 1;
		
		if ( $products->have_posts() ) : ?>
			
			<div class="products_shortcode_wrapper wd_onepage_product">
			
			<?php wd_woocommerce_product_loop_start('list'); ?>
				<?php 
				global $wd_count; $wd_count = 1;
				if(absint($show_number)) add_action('wd_product_loop_before', 'wd_add_number_product_list');?>
				<?php while ( $products->have_posts() ) : $products->the_post(); ?>
					
					<?php $content_type = 'product';?>
					<?php wc_get_template( "content-{$content_type}.php", array( 'shortc_limit' => $shortc_limit ) ); ?>					
					<?php 
					if( absint( $show_prod_buttons ) ) {?>
					<a href="<?php echo get_permalink();?>" class="button"><?php _e( 'Shop now', 'wpdance' );?></a>
					<?php }?>
				<?php endwhile; // end of the loop. ?>
				<?php remove_action('wd_product_loop_before', 'wd_add_number_product_list');?>
				
				
			<?php woocommerce_product_loop_end(); ?>
				</div>
		<?php endif;
		
		wp_reset_postdata();
		
		shop_loop_prod_add_action();
		
		return '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';
	}
	
	add_shortcode('wd_onepage_product','wd_onepage_product');
}
	
?>