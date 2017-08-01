<?php
/**
 * @package WordPress
 * @since WD_GoMarket
 */
if(!function_exists('wd_custom_product_price')){
	function wd_custom_product_price(){
		global $product;
		echo '<div class="price_extra">';
			woocommerce_template_loop_price();
		echo '</div>';
		
	}
}

if(!function_exists('wd_custom_product_by_category_function')){
	function wd_custom_product_by_category_function($atts,$content){
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
			return;
		}
		global $woocommerce_loop, $woocommerce,$wd_data;
		$atts['columns'] = absint($atts['columns']);
		$atts['row'] = absint($atts['row']);
		$atts['per_page'] = absint($atts['per_page']);			
		extract(shortcode_atts(array(
			'columns' 			=> 2
			,'row' 				=> 1
			,'big_product'		=> ''
			,'per_page' 		=> 10
			,'cat_slug'			=> ''
			//,'title' 			=> ''
			,'desc' 			=> ''
			,'show_image' 		=> 1
			,'show_title' 		=> 1
			,'show_sku' 		=> 0
			,'show_price' 		=> 1
			,'show_except' 		=> 0
			,'show_except_limit' 	=> 15
			,'show_rating' 		=> 1
			,'show_label' 		=> 0
			,'show_cat_title'	=> 0		
		),$atts));
		
		if($columns > 8){
			$columns = 8;
		}
		if($row > 4){
			$row = 4;
		}
			
		if($per_page < 1) { $per_page = 8; }
		if($columns < 1) { $columns = 2; }
		if($columns > 8) { $columns = 8; }
		if($row < 1) { $per_page = 2; }
		if($row > 8) { $row = 8; }
			
		$args = array(
			'post_type'	=> 'product',
			'post_status' => 'publish',
			'ignore_sticky_posts'	=> 1,
			'posts_per_page' => 10,//$per_page,
			'orderby' => 'date',
			'order' => 'desc',				
			'meta_query' => array(
				array(
					'key' => '_visibility',
					'value' => array('catalog', 'visible'),
					'compare' => 'IN'
				)
			)
		);
		$show_prod_buttons = 0;	
		$data = array('show_image' => $show_image,
						'show_title' => $show_title,
						'show_rating' => $show_rating,
						'show_price' => $show_price,
						'show_label' => $show_label,
						'show_add_to_cart' => $show_prod_buttons,
					);
		shop_loop_prod_remove_action($data);
		
			
			
		if(trim($cat_slug) != ''){
			$args['tax_query'] 			= array(
				array(
					'taxonomy' 		=> 'product_cat',
					'terms' 		=> array( esc_attr($cat_slug) ),
					'field' 		=> 'slug',
					'operator' 		=> 'IN'
				)
			);
		}

		wp_reset_query(); 
		ob_start();

		if(isset($wd_data['wd_prod_cat_column']) && absint($wd_data['wd_prod_cat_column']) > 0 ){
			$_old_wd_prod_cat_column = $wd_data['wd_prod_cat_column'];
			$wd_data['wd_prod_cat_column'] = $columns;
		} 
			
		$_old_woocommerce_loop_columns = $woocommerce_loop['columns'];
		$products = new WP_Query( $args );

		$woocommerce_loop['columns'] = $columns;
			
		$extra_class = $style_big_prod = '';
		
		$product_count = $current_product = 0;

		$_prod_cat = get_term_by('slug', esc_attr($cat_slug), 'product_cat');
		
		if( isset($_prod_cat) && $_prod_cat){
			$title = '<a href="'.get_term_link($cat_slug,'product_cat').'">'.$_prod_cat->name.'</a>';
		}
		
		remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
		remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );
		add_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail_custom_product_sc', 10 );
		
		add_action ('woocommerce_after_shop_loop_item','wd_custom_product_price',0);
		
		if ( $products->have_posts() ) : ?>
			
			<?php $_random_id = 'custom_product_by_category_wrapper_'.rand(); ?>
			<div class="custom-product-by-category-sc" id="<?php echo $_random_id;?>">
				<div class="wd-custom-sc">
					<div class="row">
						<?php if($show_cat_title == 1 ) : ?>
						<div class="product-custom-head col-sm-24"> 
							<?php 
								
								if(strlen(trim($title)) >0){
									
									echo "<h3 class='heading-title custom-title'>{$title}</h3>";
									echo "<div class='line line-30'></div>";
								}									
							?>
						</div>
						<?php endif;?>
						<div class="product-custom-body">
							<div class="product-custom-wrapper<?php echo $extra_class; ?>">	
									
								<div class="product-custom-inner">
																				
									<?php wd_woocommerce_product_loop_start($show_type); ?>
											
										<?php //$woocommerce_loop['columns'] = 1; ?>
											
										<?php while ( $products->have_posts() ) : $products->the_post(); ?>
												
											<?php
												if($product_count == 0 ){
													echo '<div class="custom_products_group col-lg-12 col-md-12 col-sm-24">';
													echo '<div class="row">';
													$woocommerce_loop['columns'] = 1;
													$woocommerce_loop['loop'] = 0;
												}												
											?>										
											<?php 
											//$content_type = strcmp(trim($show_type), 'widget') == 0 ? 'list-product' : 'product';
											get_template_part('content','custom-product');
											?>
												
											<?php
											if($product_count == 0){
												echo '</div>';//end row
												echo '</div>';
												echo '<div class="custom_products_group col-lg-12 col-md-12 col-sm-24">';
												echo '<div class="row">';
												$woocommerce_loop['columns'] = $columns;
												$woocommerce_loop['loop'] = 0;												
											}
											if(($product_count > 0 && (($product_count )% 4 == 0)) ){
												echo '</div>';// end row
												echo '</div>';								// close 4 small products
												echo '<div class="custom_products_group col-lg-12 col-md-12 col-sm-24">';
												echo '<div class="row">';
												if($product_count + 1 == 9){
													$woocommerce_loop['columns'] = 1;
													$woocommerce_loop['loop'] = 0;
												}
											}
											
											if(($product_count == 9 ) || $current_product + 1 == $products->post_count ) {
												echo '</div>';//end row
												echo '</div>';
											}
												
											$product_count++;
											$current_product ++;
											if($product_count == 10 ){
												$product_count = 0;
											}
											?>
												
										<?php endwhile; // end of the loop. ?>
									<?php woocommerce_product_loop_end(); ?>
										
								</div>
							</div>	
							<div class="clear"></div>
						</div>
					</div>
				</div>
			</div>
					
			<?php endif;

			wp_reset_postdata();

			shop_loop_prod_add_action();
			
			remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );
			remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail_custom_product_sc', 10 );
			add_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );	
			
			remove_action ('woocommerce_after_shop_loop_item','wd_custom_product_price',0);
			
			$woocommerce_loop['columns'] = $_old_woocommerce_loop_columns;
			if(isset($_old_wd_prod_cat_column) && absint($_old_wd_prod_cat_column > 0 )){
				$wd_data['wd_prod_cat_column'] = $_old_wd_prod_cat_column  ;
			}
			return '<div class="woocommerce">' . ob_get_clean() . '</div>';	
			
		}
	}		
	add_shortcode('custom_product_by_category','wd_custom_product_by_category_function');	
?>