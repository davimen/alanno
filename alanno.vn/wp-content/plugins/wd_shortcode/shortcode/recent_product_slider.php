<?php
/**
 * @package WordPress
 * @since WD_GoMarket
 */

if(!function_exists('wd_recent_product_by_categories_slider_function')){
	function wd_recent_product_by_categories_slider_function($atts,$content){
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
			return;
		}
		global $woocommerce_loop, $woocommerce,$wd_data;
		$atts['columns'] = absint($atts['columns']);
		$atts['row'] = absint($atts['row']);
		$atts['per_page'] = absint($atts['per_page']);			
		extract(shortcode_atts(array(
			'columns' 			=> 4
			,'row' 				=> 1
			,'big_product'		=> ''
			,'per_page' 		=> 8
			,'cat_slug'			=> ''
			,'product_tag'		=> ''
			,'title' 			=> ''
			,'box_style'		=> ''
			,'desc' 			=> ''
			,'show_type' 		=> 'grid'
			,'show_nav' 		=> 1
			,'show_nav_pos' 	=> 'top_right'
			,'show_icon_nav' 	=> 0
			,'autoplay' 		=> 0
			,'show_image' 		=> 1
			,'show_title' 		=> 1
			,'show_sku' 		=> 1
			,'show_price' 		=> 1
			,'show_except' 		=> 1
			,'show_except_limit' 	=> 15
			,'show_rating' 		=> 1
			,'show_label' 		=> 1
			,'show_prod_buttons'	=> 1
			,'slider_base_e'	=> 0
					
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
			
		if(strlen(trim($big_product)) > 0){
			$_big_prod = wd_product_by_id_function($big_product);
			if(isset($_big_prod) && $_big_prod->is_visible() ){
				$temp_add_to_cart_data = do_shortcode('[add_to_cart style="" show_price="false" id="'.$_big_prod->id.'"]');
			}
		}
			
		$args = array(
			'post_type'	=> 'product',
			'post_status' => 'publish',
			'ignore_sticky_posts'	=> 1,
			'posts_per_page' => $per_page,
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
			
		$data = array('show_image' => $show_image,
						'show_title' => $show_title,
						'show_rating' => $show_rating,
						'show_price' => $show_price,
						'show_label' => $show_label,
						'show_add_to_cart' => $show_prod_buttons,
					);
		shop_loop_prod_remove_action($data);
		
			
		if(isset($_big_prod) && $_big_prod->is_visible() && strlen(trim($_big_prod->id)) > 0){
			$args['post__not_in'] = array($_big_prod->id);
		} 	
			
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
			
		if( strlen($product_tag) > 0 && strcmp('all-product-tags',$product_tag) != 0 ){
			$args = array_merge($args, array('product_tag' => $product_tag));
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
		
		if ( $products->have_posts() ) : ?>
			<?php $_random_id = 'recent_product_by_category_slider_wrapper_'.rand(); ?>
			<div class="recent-product-by-category-sc <?php echo esc_attr($box_style);?> " id="<?php echo $_random_id;?>">
				<div class="wd-slider-sc">
					<div class="row">
						<?php if(strlen(trim($title)) >0 || strlen(trim($desc)) >0) : ?>
						<div class="product-slider-head col-sm-24"> 
							<?php 
								
								if(strlen(trim($title)) >0){
									
									echo "<h3 class='heading-title slider-title'><strong>{$title}</strong></h3>";
									echo "<div class='line line-30'></div>";
									if(absint($show_nav) && trim($show_nav_pos) == 'top_right') {
										$show_nav = 0; $show_icon_nav = 0; 
									?>
										<div class="owl-controls owl-heading-pos">
											<div class="owl-nav">
												<div class="owl-prev" id="<?php echo $_random_id;?>_prev">&lt;</div>
												<div class="owl-next" id="<?php echo $_random_id;?>_next">&gt;</div>
											</div>
										</div>
									<?php 
									}
								}
								if(strlen(trim($desc)) >0){
									$cl = ($box_style == 'style-boxed')? 'col-sm-24': '';
									echo "<p class='".$cl." slider-desc-wrapper'>{$desc}</p>";
								}
									
							?>
						</div>
						<?php endif;?>
						<div class="product-slider-body <?php if($box_style == 'style-boxed') echo "col-sm-24";?>">
							<?php 
								if(isset($_big_prod) && $_big_prod->is_visible()){
									$extra_class = " col-sm-12";
									$style_big_prod = 'style="width: 50%; float:left" ';
									$_product = get_product( $_big_prod->id );
									$post = $_product->post;
									$product = wc_setup_product_data( $post );
									
									$image_title 		= esc_attr( $_product->get_title() );
									$product_link 		= esc_url( $_product->get_permalink());
									$image       		= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ),array( 'alt' => $image_title, 'title' => $image_title ) );
									?>
										
								<div class="wd-big-product product<?php echo $extra_class?>">
									<div class="wd_image product-thumbnail-wrapper">
										<?php echo $image;?>
										
									</div>
									<div class="product-meta-wrapper">
										<div class="product-meta-content">
											<h3 class="heading-title product-title"><a href="<?php echo esc_url($product_link);?>"><?php echo esc_html($image_title);?></a></h3>
											<?php echo $_product->get_rating_html();?>
											<span class="price"><?php echo $_product->get_price_html();?></span>
										</div>
									</div>
									
									<div class="big-product-button-list">
									<?php wd_list_template_loop_add_to_cart();?>
									<?php 
										if( class_exists('YITH_WCWL_UI') && class_exists('YITH_WCWL') && function_exists('wd_add_wishlist_button_to_product_list_shortocode') ){
											wd_add_wishlist_button_to_product_list_shortocode();
										}
										if( class_exists( 'YITH_Woocompare_Frontend' ) && class_exists( 'YITH_Woocompare' ) && function_exists('wd_add_compare_link') ){
											wd_add_compare_link();
										}
										if( class_exists( 'WD_Quickshop' ) ){
											global $_wd_quickshop;
											$_wd_quickshop->add_quickshop_button();
										}
									?>
								</div>
								</div>
									<?php
										
									wc_setup_product_data( $post );
								}
							?>
							<div class="product-slider-wrapper<?php echo $extra_class; ?>">	
									
								<div class="product-slider-inner <?php echo esc_attr($show_nav_pos);?> wd-loading">
										
									<?php $current_row = 0; ?>
										
									<?php wd_woocommerce_product_loop_start($show_type); ?>
											
										<?php //$woocommerce_loop['columns'] = 1; ?>
											
										<?php while ( $products->have_posts() ) : $products->the_post(); ?>
												
											<?php
												if($row > 1 && ($current_row % $row == 0)){
													echo '<div class="products_group">';
												}
											?>
												
											<?php 
											$content_type = strcmp(trim($show_type), 'widget') == 0 ? 'list-product' : 'product';
											wc_get_template('content-'.$content_type.'.php', array('shortc_limit' => $show_except_limit));
											?>
												
											<?php
											if($row > 1 && (($current_row % $row + 1== $row) || ($current_row + 1 == $products->post_count ) )){
												echo '</div>';
											}
												
											$current_row++;
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
				
			<script type='text/javascript'>
				//<![CDATA[
					jQuery(document).ready(function() {
						"use strict";
						var temp_visible = <?php echo $columns;?>;
						
						var row = <?php echo $row;?>;

						var item_width = 180;
						
						var show_nav = <?php if(absint($show_nav) == 1): ?> true <?php else: ?> false <?php endif;?>;

						var show_icon_nav = <?php if($show_icon_nav && !$show_nav): ?> true <?php else: ?> false <?php endif;?>;
						
						var object_selector = "#<?php echo $_random_id?> .product-slider-inner .products";

                       var autoplay = <?php if($autoplay): ?> true <?php else: ?> false <?php endif;?>;
					   var slider_base_e = <?php if($slider_base_e): ?> true <?php else: ?> false <?php endif;?>;
					   
                       generate_horizontal_slide(temp_visible,row,item_width,show_nav,show_icon_nav,autoplay,object_selector,slider_base_e);
					});
				//]]>	
				</script>
				
			<?php endif;

			wp_reset_postdata();

			shop_loop_prod_add_action();
			
			$woocommerce_loop['columns'] = $_old_woocommerce_loop_columns;
			if(isset($_old_wd_prod_cat_column) && absint($_old_wd_prod_cat_column > 0 )){
				$wd_data['wd_prod_cat_column'] = $_old_wd_prod_cat_column  ;
			}
			return '<div class="woocommerce">' . ob_get_clean() . '</div>';	
			
		}
	}		
	add_shortcode('recent_product_by_categories_slider','wd_recent_product_by_categories_slider_function');	
?>