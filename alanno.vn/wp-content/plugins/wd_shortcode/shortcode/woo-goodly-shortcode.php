<?php
/**
 * @package WordPress
 * @since WD_GoMarket
 */
	if(!function_exists('wd_custom_products_category_function')){
		function wd_custom_products_category_function($atts,$content){
			$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
			if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
				return;
			}
			global $woocommerce, $woocommerce_loop;
			if ( empty( $atts ) ) return;
			extract( shortcode_atts( array(
				'per_page' 		=> '4'
				,'orderby'   	=> 'title'
				,'order'     	=> 'desc'
				,'category'		=> ''
				,'show_image' 	=> 1
				,'show_title' 	=> 1
				,'show_sku' 	=> 1
				,'show_price'	=> 1
				,'show_rating' 	=> 1
				,'show_label' 	=> 1
				,'show_categories' 	=> 1
				,'show_short_content' => 1	
				), $atts ) );
			//remove_action( 'woocommerce_before_shop_loop_item_title', 'custom_product_thumbnail', 10 );
			if(!(int)$show_image){
				remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );
			}	
			if(!(int)$show_categories)
				remove_action( 'woocommerce_after_shop_loop_item', 'get_product_categories', 2 );
			if(!(int)$show_title)
				remove_action( 'woocommerce_after_shop_loop_item', 'add_product_title', 3 );
			if(!(int)$show_rating)
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 4 );	
			if(!(int)$show_sku)
				remove_action( 'woocommerce_after_shop_loop_item', 'add_sku_to_product_list', 5 );
			if(!(int)$show_short_content)
				remove_action( 'woocommerce_after_shop_loop_item', 'add_short_content', 6 );
			if(!(int)$show_price)
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 7 );
							
			if(!(int)$show_label)
				remove_action( 'woocommerce_before_shop_loop_item_title', 'add_label_to_product_list', 5 );		
				
		
			if ( ! $category ) return;
			wp_reset_query(); 
			global $featured_datas,$wd_data,$product,$post;
			$temp_add_to_cart_data = '';
			$_featured_prod = wd_featured_by_category_function($category,1);
			
			if(isset($_featured_prod)){
				//$_featured_prod->get_upsells( );
				$featured_datas = array(
					'id' => $_featured_prod->id
					,'show_image' => $show_image
					,'show_title' => $show_title
					,'show_sku' => $show_sku
					,'show_price' => $show_price
					,'show_rating' => $show_rating
					,'show_label' => $show_label
					
				);
				$per_page = $per_page;
				$temp_add_to_cart_data = do_shortcode('[add_to_cart style="" show_price="false" id="'.$_featured_prod->id.'"]');
			}else{
				$featured_datas = array(
					'id' => ''
					,'show_image' => $show_image
					,'show_title' => $show_title
					,'show_sku' => $show_sku
					,'show_price' => $show_price
					,'show_rating' => $show_rating
					,'show_label' => $show_label
					
				);			
				$per_page = $per_page + 1;
			}

			
			// Default ordering args
			$ordering_args = $woocommerce->query->get_catalog_ordering_args( $orderby, $order );

			$args = array(
				'post_type'				=> 'product'
				,'post__not_in' 		=> array($featured_datas['id'])
				,'post_status' 			=> 'publish'
				,'ignore_sticky_posts'	=> 1
				,'orderby' 				=> $ordering_args['orderby']
				,'order' 				=> $ordering_args['order']
				,'posts_per_page' 		=> $per_page
				,'meta_query' 			=> array(
					array(
						'key' 			=> '_visibility'
						,'value' 		=> array('catalog', 'visible')
						,'compare' 		=> 'IN'
					)
				)
				,'tax_query' 			=> array(
					array(
						'taxonomy' 		=> 'product_cat'
						,'terms' 		=> array( esc_attr($category) )
						,'field' 		=> 'slug'
						,'operator' 		=> 'IN'
					)
				)
				
			);

			if ( isset( $ordering_args['meta_key'] ) ) {
				$args['meta_key'] = $ordering_args['meta_key'];
			}

			ob_start();
			$_old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			if(isset($wd_data['wd_prod_cat_column']) && absint($wd_data['wd_prod_cat_column']) > 0 ){
				$_old_wd_prod_cat_column = $wd_data['wd_prod_cat_column'];
				$wd_data['wd_prod_cat_column'] = 3;
			} 
			
			$products = new WP_Query( $args );
			$woocommerce_loop['columns'] = 3;
			$_count = 0;
			
			
			$_prod_cat = get_term_by('slug', esc_attr($category), 'product_cat');
			if( isset($_prod_cat) ){
				$title = $_prod_cat->name;
			}
			
			$_random_id = 'custom_category_shortcode_'.rand(); 
			if ( $products->have_posts() ) : ?>
			<div class="featured_product_slider_wrapper custom_category_shortcode" id="<?php echo $_random_id; ?>" data_column="3">
			
			<?php
				remove_action( 'woocommerce_product_thumbnails', 'wd_template_shipping_return', 30 );
				remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
				$check_feature = 0;
				
			?>
					<?php while ( $products->have_posts() ) : ?>
					
					<?php
						
						$products->the_post();
						
						if($_count == 0){
							$old_prod = $product;
							$old_post = $post;
							echo '<div class="row">';
							echo '<div class="col-lg-12">';
							$old_data = $wd_data['wd_prod_cloudzoom'];
							$wd_data['wd_prod_cloudzoom'] = 0;
							if(!isset($_featured_prod)){
								$featured_datas['id'] = $product->id;
								//woocommerce_get_product_thumbnail('shop_catalog');
								$product = get_product( $post->ID );
								$post = $product->post;
								$image_title 		= esc_attr( $product->get_title() );
								$product_link 		= esc_url( $product->get_permalink());
								$image       		= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ),array( 'alt' => $image_title, 'title' => $image_title ) );								
								echo sprintf( '<div class="wd_image">%s<a class="wd_readmore" title="%s" href="%s">'.__('read more','wpdance').'</a></div>',$image,$image_title,$product_link );
								//woocommerce_show_product_images();
								
							} else {
								$product = get_product( $featured_datas['id'] );
								$post = $product->post;
								$image_title 		= esc_attr( $product->get_title() );
								$product_link 		= esc_url( $product->get_permalink());
								$image       		= get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ),array( 'alt' => $image_title, 'title' => $image_title ) );								
								echo sprintf( '<div class="wd_image">%s<a class="wd_readmore" title="%s" href="%s">'.__('read more','wpdance').'</a></div>',$image,$image_title,$product_link );
								//woocommerce_get_product_thumbnail('shop_catalog');
								//woocommerce_show_product_images();
							}
							?>
							<?php 
								global $wd_data;
								if(!isset($wd_data['wd_catelog_mod']) || (isset($wd_data['wd_catelog_mod']) && $wd_data['wd_catelog_mod'] == 1)){
							?>
								<div class='list_add_to_cart'>								
									<?php 
										//for feature product
										if($temp_add_to_cart_data != ''){  
											echo $temp_add_to_cart_data; 
										} else {
											echo do_shortcode('[add_to_cart id="'.$product->id.'"]');
										}
									?>
								</div>
							<?php
							}		
							echo '<input type="hidden" value="'.$product->id.'" class="hidden_product_id product_hidden_'.$product->id.'">';
							$wd_data['wd_prod_cloudzoom'] = $old_data;
							echo '</div><div class="col-lg-12">';
							$temp_class = '';
							if($product->is_on_sale() || $product->is_featured()){
								$temp_class = 'has_label';
							}
							
							echo '<div class="wd_product_heading '.$temp_class.'"><a class="wd_feature_title" title="'.$product->post->post_title.'"  href="'.get_permalink($product->post->ID).'">'.$product->post->post_title.'</a>';
							$span_label = '<div class="product_label">';
							if($product->is_on_sale()){
								$span_label .= '<span class="onsale product_label">Sale</span>';
							}
							if($product->is_featured()){
								$span_label .= '<span class="featured product_label">Featured</span>';
							}
							$span_label .= '</div>';
							echo $span_label;
							echo '</div><div class="wd_description">'.$product->post->post_excerpt.'</div>';
							
							if(isset($_featured_prod)){	
								$product = $old_prod;
								$post = $old_post;
								if($check_feature == 0){
									$_count++;
									$check_feature = 1;
								}
							}
							echo '<div class="wd_wrapper_products">';
							echo '<div class="height_auto">';
							woocommerce_product_loop_start();
						} 
						if($_count > 0 &&  $_count < $per_page && function_exists('woocommerce_get_template_part') ){
							woocommerce_get_template_part( 'content', 'product' );
						}	

						$_count++;
					?>
						
					<?php endwhile; // end of the loop. ?>

				<?php woocommerce_product_loop_end(); ?>
				
				<?php if($per_page > 4 && $products->post_count > 4):?>
						<div class="wd_slider_control">
							<a title="prev" id="<?php echo $_random_id; ?>_prev" class="prev" href="#">&lt;</a>
							<a title="next" id="<?php echo $_random_id; ?>_next" class="next" href="#">&gt;</a>
						</div>
						<?php endif;?>
					<?php echo '</div>';?>		
					<?php echo '</div>';?>		
				<?php echo '</div>';?>
				<?php echo '</div>';?>
			</div>	
			<script type='text/javascript'>
				//<![CDATA[
					jQuery(document).ready(function() {
						"use strict";
						// Using custom configuration
						var is_mobile = 0;
						<?php if(wp_is_mobile()): ?>
							is_mobile = 1;
						<?php endif;?>
						var temp_visible = 3;
						if(is_mobile && jQuery(window).width() >= 768 && jQuery(window).width() <= 1024){
							temp_visible = temp_visible - 1;
							
						}
						if(is_mobile && jQuery(window).width() < 768){
							//temp_visible = '{ min		: 1,max	: <?php echo $columns;?>}';
							temp_visible = 1;
						}
						var _slider_datas =	
						{
							items 				: {
								/*width: 140
								,height: <?php echo strcmp($layout,'small') == 0 ? 240 : 650 ;?>*/
								width: <?php echo wp_is_mobile() ? 300 : 200 ;?>
								,height: 'auto'
								,visible: temp_visible				
							}
							,direction			: "left"
							,responsive 		: true	
							,swipe				: { /*onMouse: true, */onTouch: true }		
							,scroll				: <?php if( !wp_is_mobile() ) : ?>
													{ items :1,
													duration : 500
													, pauseOnHover:true
													, easing : "linear"}
													<?php else :?>
														1
													<?php endif;?>
							,width				: '100%'
							,height				: '100%'
							,circular			: true
							,infinite			: true
							,auto				: false
							,onCreate			: function(){
													//alert(jQuery(this).html());
												}
							<?php if($per_page > 4 && $products->post_count > 3):?>
							,prev				: '#<?php echo $_random_id; ?>_prev'
							,next				: '#<?php echo $_random_id; ?>_next'								
							<?php endif;?>
							//<?php if($show_icon_nav):?>
							//,pagination 		: '#<?php echo $_random_id;?>_pager'
							//<?php endif;?>							
						};
						//setTimeout(function(){
							jQuery("#<?php echo $_random_id; ?> div.products").carouFredSel(_slider_datas);	
						//},10000);
						
						var temp_custom = jQuery("#<?php echo $_random_id;?>").closest(".tabbable").addClass('has_slider');
						jQuery(temp_custom).bind('tabs_change',jQuery.debounce( 250, function(event, id){
							var my_id = '<?php echo $_random_id;?>';
							if(id == my_id){
								var _item_width = jQuery(window).width() < 600 ?  300: 200;
								_slider_datas.items.width = _item_width;
								_slider_datas.items.visible = temp_visible;
								//jQuery("#<?php echo $_random_id?> > .featured_product_slider_wrapper_inner ul.products").trigger('destroy',true);
								jQuery("#<?php echo $_random_id?> div.products").trigger('configuration ',["items.width", 200, true]);
								setTimeout(function(){
									jQuery("#<?php echo $_random_id?>  div.products").carouFredSel(_slider_datas);	
									jQuery("#<?php echo $_random_id?> div.products li").show();
									//jQuery("#<?php echo $_random_id?>").closest(".tab-pane").css('visibility','visible');
									jQuery("#<?php echo $_random_id?>").closest(".tab-pane").css('height','auto');
									jQuery(temp_custom).children('.tab-content').removeClass('wd_loading');
								},200);
							}
						} ));
					});
				//]]>	
				</script>
				
			<?php endif;

			wp_reset_postdata();

			//add all the hook removed
			add_action ('woocommerce_after_shop_loop_item','open_div_style',1);
			add_action ('woocommerce_after_shop_loop_item','get_product_categories',2);
			add_action ('woocommerce_after_shop_loop_item','add_product_title',3);
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 4 );
			add_action ('woocommerce_after_shop_loop_item','add_sku_to_product_list',5);
			add_action ('woocommerce_after_shop_loop_item','add_short_content',6);
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 7 );
						
			
			add_action( 'woocommerce_before_shop_loop_item_title', 'add_label_to_product_list', 5 );			
			add_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );			
			add_action( 'woocommerce_product_thumbnails', 'wd_template_shipping_return', 30 );
			add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
			//end			
			$woocommerce_loop['columns'] = $_old_woocommerce_loop_columns ;
			if(isset($_old_wd_prod_cat_column) && absint($_old_wd_prod_cat_column > 0 )){
				$wd_data['wd_prod_cat_column'] = $_old_wd_prod_cat_column  ;
			}
			return '<div class="woocommerce">' . ob_get_clean() . '</div>';		
		
		}
	}	
	add_shortcode('custom_products_category','wd_custom_products_category_function');

	
	if(!function_exists('wd_custom_products_category_style2_function')){
		function wd_custom_products_category_style2_function($atts,$content){
			$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
			if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
				return;
			}
			global $woocommerce, $woocommerce_loop,$wd_data;
			if ( empty( $atts ) ) return;
			extract( shortcode_atts( array(
				'per_page' 		=> '4'
				,'orderby'   	=> 'title'
				,'color'		=> ''
				,'image_url'	=> ''
				,'order'     	=> 'desc'
				,'category'		=> ''
				,'show_image' 	=> 1
				,'show_title' 	=> 1
				,'show_sku' 	=> 1
				,'show_price'	=> 1
				,'show_rating' 	=> 1
				,'show_label' 	=> 1
				,'show_categories' 	=> 1
				,'show_short_content' => 1	
				), $atts ) );
			$arr_color = array('pink','red','blue','green','orange','black','indigo');
			if(!in_array($color,$arr_color)){
				$color = '';
			} else {
				$color ='wd-'.$color;
			}
			
			if(!(int)$show_image){
				remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );
			}	
			if(!(int)$show_categories)
				remove_action( 'woocommerce_after_shop_loop_item', 'get_product_categories', 2 );
			if(!(int)$show_title)
				remove_action( 'woocommerce_after_shop_loop_item', 'add_product_title', 3 );
			if(!(int)$show_rating)
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 4 );	
			if(!(int)$show_sku)
				remove_action( 'woocommerce_after_shop_loop_item', 'add_sku_to_product_list', 5 );
			if(!(int)$show_short_content)
				remove_action( 'woocommerce_after_shop_loop_item', 'add_short_content', 6 );
			if(!(int)$show_price)
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 7 );
							
			if(!(int)$show_label)
				remove_action( 'woocommerce_before_shop_loop_item_title', 'add_label_to_product_list', 5 );		
				
		
			if ( ! $category ) return;
			wp_reset_query(); 


			
			// Default ordering args
			$ordering_args = $woocommerce->query->get_catalog_ordering_args( $orderby, $order );

			$args = array(
				'post_type'				=> 'product'
				,'post_status' 			=> 'publish'
				,'ignore_sticky_posts'	=> 1
				,'orderby' 				=> $ordering_args['orderby']
				,'order' 				=> $ordering_args['order']
				,'posts_per_page' 		=> $per_page
				,'meta_query' 			=> array(
					array(
						'key' 			=> '_visibility'
						,'value' 		=> array('catalog', 'visible')
						,'compare' 		=> 'IN'
					)
				)
				,'tax_query' 			=> array(
					array(
						'taxonomy' 		=> 'product_cat'
						,'terms' 		=> array( esc_attr($category) )
						,'field' 		=> 'slug'
						,'operator' 		=> 'IN'
					)
				)
				
			);

			if ( isset( $ordering_args['meta_key'] ) ) {
				$args['meta_key'] = $ordering_args['meta_key'];
			}

			ob_start();
			if(isset($wd_data['wd_prod_cat_column']) && absint($wd_data['wd_prod_cat_column']) > 0 ){
				$_old_wd_prod_cat_column = $wd_data['wd_prod_cat_column'];
				$wd_data['wd_prod_cat_column'] = 2;
			} 
			
			$_old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			$woocommerce_loop['columns'] = 2;
			$_old_woocommerce_loop_loops = $woocommerce_loop['loop'];
			$woocommerce_loop['loop'] = 0;
			
			$products = new WP_Query( $args );
			$_count = 0;
			$top_content = 4;
			$_prod_cat = get_term_by('slug', esc_attr($category), 'product_cat');
			if( isset($_prod_cat) ){
				$title = $_prod_cat->name;
				$thumb_id = get_woocommerce_term_meta( $_prod_cat->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_image( $thumb_id,array(300,200) );
			}
			
			if ( $products->have_posts() ) : 
				global $product,$wd_data;
				$_random_id = 'custom_category_shortcode_style2_items_wrapper_'.rand();
			?>
				<div class="custom_category_shortcode_style2 <?php echo $color; ?>" id="<?php echo $_random_id;?>">
				
					<?php
						//remove_action( 'woocommerce_before_shop_loop_item_title', 'custom_product_thumbnail', 10 );
						remove_action( 'woocommerce_product_thumbnails', 'wd_template_shipping_return', 30 );
						remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
					?>
					<div class="wd_heading">
						<a href="<?php echo get_term_link($_prod_cat,'product_cat');?>" class="heading-title custom-category-title"><?php echo $title;?></a>
					</div>
					<div class="top_content media">
						<div class="pull-left">
							<div class="thumbnail"><img alt="<?php echo $title; ?>" src="<?php echo $image_url; ?>"/></div>
						</div>
						<div>
							<?php woocommerce_product_loop_start(); ?>
							<?php while ( $products->have_posts() && $top_content > 0 ) : ?>
							<?php
								
								$products->the_post();
								
									if(function_exists('woocommerce_get_template_part') ){
										woocommerce_get_template_part( 'content', 'product' );
									}	
								$_count++;
								$woocommerce_loop['loop'] = ($_count % 2);
								$top_content --;
							?>						
							<?php endwhile; // end of the loop. ?>

						<?php woocommerce_product_loop_end(); ?>

						<?php echo '</div>';?>
					</div>	
					<?php if($products->post_count >= 6) : ?>
						<div class="bottom_content height_auto">
							<?php woocommerce_product_loop_start(); ?>
							<?php while ( $products->have_posts() && $top_content <= 0 ) : ?>
								<?php								
									$products->the_post();	
									global $product;
									remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );
									add_action( 'woocommerce_before_shop_loop_item_title', 'wd_custom_product_thumbnail', 10 );
									if(function_exists('wc_get_template_part') ){
										wc_get_template_part( 'content', 'product' );
									}
									remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_custom_product_thumbnail', 10 );
									add_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );									
									$_count++;
									$woocommerce_loop['loop'] = ($_count % 2);
									$top_content --;
								?>	
							<?php endwhile; // end of the loop. ?>
							<?php woocommerce_product_loop_end(); ?>
							<?php if($products->post_count >= 6) : ?>
								<div class="wd_slider_control">
									<a class="prev" id="wd_custom_grid_prev" href="#">&lt;</a>
									<a class="next" id="wd_custom_grid_next" href="#" >&gt;</a> 
								</div>
							<?php endif; ?>		
						</div>
					<?php endif;?>
				</div>	
				<?php if($products->post_count >= 6) : ?>
					<script type="text/javascript" language="javascript">
						//<![CDATA[
						jQuery(document).ready(function() {
							_slider_datas = {				
							responsive: true
							,width	: '100%'
							,height	: 'auto'
							,scroll  : {
								items	: 1,
							}
							,auto    : false
							,swipe	: { onMouse: true, onTouch: true }	
							,items   : { 
								visible	:  2		
							}	
							,prev    : '#<?php echo $_random_id;?> .bottom_content .wd_slider_control #wd_custom_grid_prev'
							,next    : '#<?php echo $_random_id;?> .bottom_content .wd_slider_control #wd_custom_grid_next'
						};
							jQuery("div.bottom_content div.products div.product").removeClass('first').removeClass('last');
							jQuery("#<?php echo $_random_id;?> div.bottom_content div.products").carouFredSel(_slider_datas);
						});	
						//]]>	
					</script>
				<?php endif; ?>		
			<?php endif;

			wp_reset_postdata();

			//add all the hook removed
			add_action ('woocommerce_after_shop_loop_item','open_div_style',1);
			add_action ('woocommerce_after_shop_loop_item','get_product_categories',2);
			add_action ('woocommerce_after_shop_loop_item','add_product_title',3);
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 4 );
			add_action ('woocommerce_after_shop_loop_item','add_sku_to_product_list',5);
			add_action ('woocommerce_after_shop_loop_item','add_short_content',6);
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 7 );
						
			
			add_action( 'woocommerce_before_shop_loop_item_title', 'add_label_to_product_list', 5 );			
			add_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );			
			add_action( 'woocommerce_product_thumbnails', 'wd_template_shipping_return', 30 );
			add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
			
			//end			
			$woocommerce_loop['columns'] = $_old_woocommerce_loop_columns ;
			if(isset($_old_wd_prod_cat_column) && absint($_old_wd_prod_cat_column > 0 )){
				$wd_data['wd_prod_cat_column'] = $_old_wd_prod_cat_column  ;
			}
			$woocommerce_loop['loop'] = $_old_woocommerce_loop_loops;
			return '<div class="woocommerce">' . ob_get_clean() . '</div>';		
		
		}
	}	
	add_shortcode('custom_products_category_grid_image','wd_custom_products_category_style2_function');
	

	
	
	if(!function_exists('wd_popular_product_function')){
		function wd_popular_product_function($atts,$content){
			$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
			if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
				return;
			}
			global $woocommerce_loop, $woocommerce;
			extract(shortcode_atts(array(
				'columns' 			=> 4
				,'layout' 			=> 'small'
				,'icon'				=> ''
				,'color'			=> ''
				,'title' 			=> ''
				,'category'			=> ''
				,'orderby'   	=> 'title'
				,'order'     	=> 'desc'
				,'product_tag' 		=> ''
				,'show_image' 		=> 1
				,'show_title' 		=> 1
				,'show_sku' 		=> 1
				,'show_price' 		=> 1
				,'show_rating' 		=> 1
				,'show_label' 		=> 1
				,'show_availability'=> 1
				,'show_categories'	=> 1	
				,'show_related'		=> 1
				,'show_short_content' => 1				
			),$atts));
			
			$arr_color = array('pink','red','blue','green','orange','black','indigo');
			if(!in_array($color,$arr_color)){
				$color = '';
			}else {
				$color ='wd-'.$color;
			}
			//add_action( 'woocommerce_after_shop_loop_item', 'wd_template_single_availability', 2 );	
			add_action ('woocommerce_after_shop_loop_item','wd_related',10001);
			if(!(int)$show_image){
				remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );
			}
			if(!(int)$show_categories)
				remove_action( 'woocommerce_after_shop_loop_item', 'get_product_categories', 2 );
			if(!(int)$show_title)
				remove_action( 'woocommerce_after_shop_loop_item', 'add_product_title', 3 );
			if(!(int)$show_rating)
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 4 );	
			if(!(int)$show_sku)
				remove_action( 'woocommerce_after_shop_loop_item', 'add_sku_to_product_list', 5 );
			if(!(int)$show_short_content)
				remove_action( 'woocommerce_after_shop_loop_item', 'add_short_content', 6 );
			if(!(int)$show_price)
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 7 );
							
			if(!(int)$show_label)
				remove_action( 'woocommerce_before_shop_loop_item_title', 'add_label_to_product_list', 5 );				
			if(!(int)$show_related){
				remove_action ('woocommerce_after_shop_loop_item','wd_related',10001);
			}

			//if(!(int)$show_availability){
			//	remove_action( 'woocommerce_after_shop_loop_item', 'wd_template_single_availability', 2 );
			//}
			
			wp_reset_query(); 
			
			// Default ordering args
			$ordering_args = $woocommerce->query->get_catalog_ordering_args( $orderby, $order );

			$args = array(
				'post_type'				=> 'product'
				,'post_status' 			=> 'publish'
				,'ignore_sticky_posts'	=> 1
				,'orderby' 				=> $ordering_args['orderby']
				,'order' 				=> $ordering_args['order']
				,'posts_per_page' 		=> 1
				,'meta_query' 			=> array(
					array(
						'key' 			=> '_visibility'
						,'value' 		=> array('catalog', 'visible')
						,'compare' 		=> 'IN'
					)
				)
				,'tax_query' 			=> array(
					array(
						'taxonomy' 		=> 'product_cat'
						,'terms' 		=> array( esc_attr($category) )
						,'field' 		=> 'slug'
						,'operator' 		=> 'IN'
					)
				)
				
			);

			if ( isset( $ordering_args['meta_key'] ) ) {
				$args['meta_key'] = $ordering_args['meta_key'];
			}
			
			ob_start();
			
			add_filter( 'posts_clauses', 'wd_order_by_rating_post_clauses' );

			$products = new WP_Query( $args );

			remove_filter( 'posts_clauses', 'wd_order_by_rating_post_clauses' );
			
			$_old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			$woocommerce_loop['columns'] = $columns;
			
	
			remove_action( 'woocommerce_product_thumbnails', 'wd_template_shipping_return', 30 );
			remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
			
			if ( $products->have_posts() ) : ?>
				<?php $_random_id = 'wd_popular_product_item_wrapper_'.rand(); 
					$_prod_cat = get_term_by('slug', esc_attr($category), 'product_cat');
					if( strlen($title) <= 0 ){
						$title = $_prod_cat->name;
					}
				?>
				<div class="wd_popular_product_wrapper <?php echo $color; ?>" id="<?php echo $_random_id;?>">
					<div class="wd_popular_product_wrapper_meta"> 
						<?php
							if(strlen(trim($icon)) > 0){
								echo '<img alt="icon" class="icon" src="'.$icon.'" />';
							}
							if(strlen(trim($title)) >0)
								echo '<a href="'.get_term_link($_prod_cat,'product_cat').'" class="heading-title slider-title">'.$title.'</a>';
							if(strlen(trim($desc)) >0)	
								echo "<p class='slider-desc-wrapper'>{$desc}</p>";
						?>
					</div>
					<div class="wd_popular_product_wrapper_inner">
						<?php woocommerce_product_loop_start(); ?>

							<?php while ( $products->have_posts() ) : $products->the_post(); ?>

								<?php woocommerce_get_template_part( 'content', 'product' ); ?>
							<?php endwhile; // end of the loop. ?>
						<?php woocommerce_product_loop_end(); ?>
					</div>
				</div>
			<?php endif;

			wp_reset_postdata();

			
			
			//add all the hook removed
			add_action ('woocommerce_after_shop_loop_item','open_div_style',1);
			add_action ('woocommerce_after_shop_loop_item','get_product_categories',2);
	//		remove_action( 'woocommerce_after_shop_loop_item', 'wd_template_single_availability', 2 );
			add_action ('woocommerce_after_shop_loop_item','add_product_title',3);
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 4 );
			add_action ('woocommerce_after_shop_loop_item','add_sku_to_product_list',5);
			add_action ('woocommerce_after_shop_loop_item','add_short_content',6);
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 7 );
						
			
			add_action( 'woocommerce_before_shop_loop_item_title', 'add_label_to_product_list', 5 );				
			add_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );			
			//end
			
			remove_action ('woocommerce_after_shop_loop_item','wd_related',10001);
			$woocommerce_loop['columns'] = $_old_woocommerce_loop_columns ;
			return '<div class="woocommerce">' . ob_get_clean() . '</div>';		
			
		}
	}		
	add_shortcode('wd_popular_product','wd_popular_product_function');
	
	if(!function_exists('wd_product_category_list_function')){
		function wd_product_category_list_function($atts,$content){
			$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
			if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
				return;
			}
			global $woocommerce, $woocommerce_loop,$wd_data;
			if ( empty( $atts ) ) return;
			extract( shortcode_atts( array(
				'per_page' 		=> '5'
				,'color'		=> ''		
				,'orderby'   	=> 'title'
				,'order'     	=> 'desc'
				,'category'		=> ''
				,'show_image' 	=> 1
				,'show_title' 	=> 1
				,'show_sku' 	=> 1
				,'show_price'	=> 1
				,'show_rating' 	=> 1
				,'show_label' 	=> 1
				,'show_categories' 	=> 1
				,'show_short_content' => 1	
				), $atts ) );
			
			$arr_color = array('pink','red','blue','green','orange','black','indigo');
			if(!in_array($color,$arr_color)){
				$color = '';
			}	else {
				$color = 'wd-'.$color;
			}
			add_action( 'woocommerce_before_shop_loop_item_title', 'wd_popular_shorcode_loop_product_thumbnail', 10 );
			if(!(int)$show_image){
				remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );
				remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_popular_shorcode_loop_product_thumbnail', 10 );
			}	
			if(!(int)$show_categories)
				remove_action( 'woocommerce_after_shop_loop_item', 'get_product_categories', 2 );
			if(!(int)$show_title)
				remove_action( 'woocommerce_after_shop_loop_item', 'add_product_title', 3 );
			if(!(int)$show_rating)
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 4 );	
			if(!(int)$show_sku)
				remove_action( 'woocommerce_after_shop_loop_item', 'add_sku_to_product_list', 5 );
			if(!(int)$show_short_content)
				remove_action( 'woocommerce_after_shop_loop_item', 'add_short_content', 6 );
			if(!(int)$show_price)
				remove_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 7 );
							
			if(!(int)$show_label)
				remove_action( 'woocommerce_before_shop_loop_item_title', 'add_label_to_product_list', 5 );		
				
		
			if ( ! $category ) return;
			wp_reset_query(); 


			
			// Default ordering args
			$ordering_args = $woocommerce->query->get_catalog_ordering_args( $orderby, $order );

			$args = array(
				'post_type'				=> 'product'
				,'post_status' 			=> 'publish'
				,'ignore_sticky_posts'	=> 1
				,'orderby' 				=> $ordering_args['orderby']
				,'order' 				=> $ordering_args['order']
				,'posts_per_page' 		=> $per_page
				,'meta_query' 			=> array(
					array(
						'key' 			=> '_visibility'
						,'value' 		=> array('catalog', 'visible')
						,'compare' 		=> 'IN'
					)
				)
				,'tax_query' 			=> array(
					array(
						'taxonomy' 		=> 'product_cat'
						,'terms' 		=> array( esc_attr($category) )
						,'field' 		=> 'slug'
						,'operator' 		=> 'IN'
					)
				)
				
			);

			if ( isset( $ordering_args['meta_key'] ) ) {
				$args['meta_key'] = $ordering_args['meta_key'];
			}

			ob_start();
			
			
			
			$_old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			$woocommerce_loop['columns'] = 2;
			
			$products = new WP_Query( $args );
			$_count = 0;
			$_prod_cat = get_term_by('slug', esc_attr($category), 'product_cat');
			if( isset($_prod_cat) ){
				$title = $_prod_cat->name;
				$thumb_id = get_woocommerce_term_meta( $_prod_cat->term_id, 'thumbnail_id', true );
				$image = wp_get_attachment_image( $thumb_id,array(300,200) );
			}
			if ( $products->have_posts() ) : ?>
			<?php $_random_id = 'featured_product_slider_wrapper123_'.rand(); ?>
			<div class="featured_product_slider_content">
				<div class="featured_product_slider_wrapper123 wd_product_category_list_shortcode <?php echo $color; ?>" id="<?php echo $_random_id;?>" data_column="5">
				
				<?php
					remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );			
					remove_action( 'woocommerce_before_shop_loop_item_title', 'woocommerce_template_loop_product_thumbnail', 10 );			
					remove_action( 'woocommerce_product_thumbnails', 'wd_template_shipping_return', 30 );
					remove_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
					
					//remove_action( 'woocommerce_before_shop_loop_item_title', 'custom_product_thumbnail', 10 );
					
				?>
					<div class="top_title">
						<a href="<?php echo get_term_link($_prod_cat,'product_cat'); ?>" class="heading-title custom-category-title"><?php echo $title;?></a>
					</div>
					<div class="wd_content">
						<?php woocommerce_product_loop_start(); ?>
						<?php while ( $products->have_posts() ) : ?>
						<?php
							global $product,$wd_data;
							$products->the_post();
								if(function_exists('woocommerce_get_template_part') ){
									woocommerce_get_template_part( 'content', 'product' );
								}	
							$_count++;
							$woocommerce_loop['loop'] = ($_count % 2);
						?>
							
						<?php endwhile; // end of the loop. ?>
						<?php woocommerce_product_loop_end(); ?>
						<?php if($per_page > 5 && $products->post_count > 5):?>
							<div class="wd_slider_control">
								<a title="prev" id="<?php echo $_random_id; ?>_prev" class="prev" href="#">&lt;</a>
								<a title="next" id="<?php echo $_random_id; ?>_next" class="next" href="#">&gt;</a>
							</div>
						<?php endif;?>
					<?php echo '</div>';?>
				</div>	
			</div>
			<script type='text/javascript'>
				//<![CDATA[
					jQuery(document).ready(function() {
						"use strict";
						// Using custom configuration
						var temp123 = {
							direction: 'up',
							items: { visible: {
									min: 5,
									max: 5 }
								},
							debug       : false,
							auto: false
							,scroll: {
								items : 1
							}
							
							<?php if($per_page > 5 && $products->post_count > 5 ):?>
							,prev				: '#<?php echo $_random_id; ?>_prev'
							,next				: '#<?php echo $_random_id; ?>_next'								
							<?php endif;?>
						};
						
						setTimeout(function(){
							jQuery("#<?php echo $_random_id;?> div.products").carouFredSel(temp123);						
						},1000);
						var temp_tab = jQuery("#<?php echo $_random_id?>").closest(".tabbable").addClass('has_slider');
						jQuery(temp_tab).bind('tabs_change',jQuery.debounce( 250, function(event, id){
							//jQuery("#<?php echo $_random_id?> .wd_content div.products").trigger('configuration ',["items.width", 200, true]);
							var my_id = '<?php echo $_random_id;?>';
							if(id == my_id){
								setTimeout(function(){
									jQuery("#<?php echo $_random_id?> > .wd_content div.products").carouFredSel(temp123);	
									jQuery("#<?php echo $_random_id?> > .wd_content div.products li").show(0);
									jQuery("#<?php echo $_random_id?>").closest(".tab-pane").css('visibility','visible');
									jQuery("#<?php echo $_random_id?>").closest(".tab-pane").css('height','auto');
									jQuery(temp_tab).children('.tab-content').removeClass('wd_loading');
								},200);
							}
						} ));
						jQuery(window).bind('resize orientationchange',jQuery.debounce( 250, function(){	
								jQuery('#<?php echo $_random_id;?> div.products').trigger('destroy',true);
								jQuery('#<?php echo $_random_id;?> div.products').carouFredSel(temp123);
						}));
					});
				//]]>	
				</script>	
			<?php endif;

			wp_reset_postdata();

			//add all the hook removed
			add_action ('woocommerce_after_shop_loop_item','open_div_style',1);
			add_action ('woocommerce_after_shop_loop_item','get_product_categories',2);
			add_action ('woocommerce_after_shop_loop_item','add_product_title',3);
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_rating', 4 );
			add_action ('woocommerce_after_shop_loop_item','add_sku_to_product_list',5);
			add_action ('woocommerce_after_shop_loop_item','add_short_content',6);
			add_action( 'woocommerce_after_shop_loop_item', 'woocommerce_template_loop_price', 7 );
						
			
			add_action( 'woocommerce_before_shop_loop_item_title', 'add_label_to_product_list', 5 );			
			add_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );			
			//remove_action( 'woocommerce_before_shop_loop_item_title', 'custom_product_thumbnail', 10 );
			add_action( 'woocommerce_product_thumbnails', 'wd_template_shipping_return', 30 );
			add_action( 'woocommerce_product_thumbnails', 'woocommerce_show_product_thumbnails', 20 );
			remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_popular_shorcode_loop_product_thumbnail', 10 );
			//end			
			$woocommerce_loop['columns'] = $_old_woocommerce_loop_columns ;
			if(isset($_old_wd_prod_cat_column) && absint($_old_wd_prod_cat_column > 0 )){
				$wd_data['wd_prod_cat_column'] = $_old_wd_prod_cat_column  ;
			}
			$woocommerce_loop['loop'] = $_old_woocommerce_loop_loops;
			return '<div class="woocommerce">' . ob_get_clean() . '</div>';		
		
		}
	}
	add_shortcode('wd_product_category_list_slider','wd_product_category_list_function');
	
	
	function wd_related(){
		global $post, $product,$product_datas;
		$arr = $product->get_related('5');
		$_random_id = 'related_'.rand(); 
		ob_start();
		$temp = '<div class="wd_products" id="'.$_random_id.'">';
		$count = 0;
		$max = 5;
		if(wp_is_mobile()){	
			$max = 3;
		}
		foreach($arr as $arr_item){
			if(	$count == $max)
				break;
			$temp .= '<div>';
			$temp .= '<a href='.esc_url(get_permalink($arr_item)).'>'.(get_the_post_thumbnail($arr_item,'shop_thumbnail')).'</a>';
			$temp .= '</div>';
			$count++;
		}
		$temp .= '</div>';
		?>
		<div class="wd_related height_auto" id="<?php echo $_random_id; ?>">
			<?php echo $temp; ?>
			<?php if(count($arr) >= 3) : ?>
				<div class="wd_slider_control">
					<a class="prev" id="wd_related_prev" href="#">&lt;</a>
					<a class="next" id="wd_related_next" href="#" >&gt;</a> 
				</div>
			<?php endif; ?>	
		</div>
		<script type="text/javascript" language="javascript">
			//<![CDATA[
			jQuery(document).ready(function() {
				var 
				_slider_datas = {				
					responsive: true
					//,width	: '100%'
					//,height	: 'auto'
					,scroll  : {
						items	: 1,
					}
					,auto    : false
					,swipe	: { onMouse: true, onTouch: true }	
					,items   : { 
						visible	:  3		
					}	
					<?php if(count($arr) >= 3) : ?>
					,prev    : '#<?php echo $_random_id?> .wd_slider_control #wd_related_prev'
					,next    : '#<?php echo $_random_id?> .wd_slider_control #wd_related_next'
					<?php endif; ?>	
				};
				jQuery("div#<?php echo $_random_id; ?> div.wd_products").carouFredSel(_slider_datas);
			});	
			//]]>	
		</script>
		<?php
		$result = ob_get_clean();
		echo $result;
	}
	function wd_popular_shorcode_loop_product_thumbnail(){
		echo woocommerce_get_product_thumbnail('shop_thumbnail');
	}
	function wd_readmore(){
		global $post, $product,$product_datas;
		$_uri = esc_url(get_permalink($post->ID));
		echo '<div class="wd_readmore">';
		echo "<a href='{$_uri}'>More</a>";
		echo '</div>';
	}
	function wd_custom_product_thumbnail(){
		global $product,$post;
		echo '<div class="product-image-front">';
		echo woocommerce_get_product_thumbnail('shop_thumbnail');
		echo '</div>';
		
	}
?>