<?php
//ALL extra functions
if(!function_exists('wd_product_by_sku_function')){
	static function wd_prodcut_by_sku_function($sku){
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
			return;
		}
		
		wp_reset_query(); 
		$big_args = array(
			'post_type' => 'product',
			'posts_per_page' => 1,
			'no_found_rows' => 1,
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => '_visibility',
					'value' => array('catalog', 'visible'),
					'compare' => 'IN'
				)
				,array(
					'key' => '_sku',
					'value' => $sku ,
					'compare' => '='
				)
			)
		);
		
		$big_product = new WP_Query( $big_args );
		if ( $big_product->have_posts() ) : 
			global $post;
			$big_product->the_post();
			$product = get_product( $post->ID );
			return $product;
		endif;
		return NULL;		
	}
}


if(!function_exists('wd_product_by_id_function')){
	static function wd_product_by_id_function($id){
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
			return;
		}
		
		wp_reset_query(); 
		$big_args = array(
			'post_type' => 'product',
			'posts_per_page' => 1,
			'no_found_rows' => 1,
			'p' => $id,
			'post_status' => 'publish',
			'meta_query' => array(
				array(
					'key' => '_visibility',
					'value' => array('catalog', 'visible'),
					'compare' => 'IN'
				)
			)
		);
		
		$big_product = new WP_Query( $big_args );
		if ( $big_product->have_posts() ) : 
			global $post;
			$big_product->the_post();
			$product = get_product( $post->ID );
			return $product;
		endif;
		return NULL;		
	}
}
?>