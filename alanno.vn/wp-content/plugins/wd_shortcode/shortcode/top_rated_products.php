<?php
/**
 * @package WordPress
 * @since WD_GoMarket
 */
 
if(!function_exists('wd_order_by_rating_post_clauses')){ 
	function wd_order_by_rating_post_clauses( $args ) {
		global $wpdb;

		$args['where'] .= " AND $wpdb->commentmeta.meta_key = 'rating' ";

		$args['join'] .= "
			LEFT JOIN $wpdb->comments ON($wpdb->posts.ID = $wpdb->comments.comment_post_ID)
			LEFT JOIN $wpdb->commentmeta ON($wpdb->comments.comment_ID = $wpdb->commentmeta.comment_id)
		";

		$args['orderby'] = "$wpdb->commentmeta.meta_value DESC";

		$args['groupby'] = "$wpdb->posts.ID";

		return $args;
	}
}
 
 
if(!function_exists('wd_top_rated_products')){
		
		
	function wd_top_rated_products($atts,$content){
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
			return;
		}
		global $woocommerce_loop, $woocommerce,$wd_data;
		$atts['columns'] = absint($atts['columns']);
		$atts['per_page'] = absint($atts['per_page']);			
		extract(shortcode_atts(array(
			'columns' 			=> 4,
			'per_page'			=> 12,
			'orderby'			=> 'title',
			'order'				=> 'asc',
			'style'				=> 'grid',
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
			
		$args = array(
			'post_type' 			=> 'product',
			'post_status' 			=> 'publish',
			'ignore_sticky_posts'   => 1,
			'orderby' 				=> $orderby,
			'order'					=> $order,
			'posts_per_page' 		=> $per_page,
			'meta_query' 			=> array(
				array(
					'key' 			=> '_visibility',
					'value' 		=> array('catalog', 'visible'),
					'compare' 		=> 'IN'
				)
			)
		);
		
		$data = array('show_image' => $show_image,
						'show_title' => $show_title,
						'show_rating' => $show_rating,
						'show_price' => $show_price,
						'show_label' => $show_label,
						'show_add_to_cart' => $show_prod_buttons,
					);
		shop_loop_prod_remove_action($data);
		
		ob_start();

		add_filter( 'posts_clauses', 'wd_order_by_rating_post_clauses' );
		$products = new WP_Query( apply_filters( 'woocommerce_shortcode_products_query', $args, $atts ) );
		remove_filter( 'posts_clauses', 'wd_order_by_rating_post_clauses' );

		$woocommerce_loop['columns'] = $columns;

		if ( $products->have_posts() ) : ?>

			<?php wd_woocommerce_product_loop_start($style); ?>
				
				<?php while ( $products->have_posts() ) : $products->the_post(); ?>

					<?php wc_get_template( 'content-product.php', array( 'shortc_limit' => $shortc_limit ) ); ?>					

				<?php endwhile; // end of the loop. ?>
				
			<?php woocommerce_product_loop_end(); ?>

		<?php endif;
		
		wp_reset_postdata();
		
		shop_loop_prod_add_action();
		
		return '<div class="woocommerce columns-' . $columns . '">' . ob_get_clean() . '</div>';
			
		}
	}		
	add_shortcode('wd_top_rated_products','wd_top_rated_products');	
?>