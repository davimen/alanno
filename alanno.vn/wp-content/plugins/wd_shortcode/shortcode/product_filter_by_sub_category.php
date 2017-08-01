<?php
/**
 * @package WordPress
 * @since WD_Glory
 */

if(!function_exists('wd_product_filter_by_category_function')){
	function wd_product_filter_by_category_function($atts,$content){
		wp_reset_query(); 
		extract(shortcode_atts(array(
			'columns' 			=> 4,
			'per_page' 			=> 8,
			'title' 			=> '',
			'desc' 				=> '',
			'product_cats' 		=> '',
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
		
		$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
		if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
			return;
		}
		
		$product_cats = trim($product_cats);
		if( strlen($product_cats) == 0) return;
		
		$_prod_cats = $_cats_tmp = array();
		$_prod_cat_args = explode( ",", $product_cats );
		foreach( $_prod_cat_args as $cat ){
			$cat = explode('|',$cat);
			if(!in_array(esc_attr($cat[0]), $_cats_tmp) && $prod_cat = get_term_by('slug', esc_attr($cat[0]), 'product_cat')) {
				$prod_cat->wd_icon = $cat[1];
				array_push($_prod_cats,$prod_cat);
				array_push($_cats_tmp, esc_attr($cat[0]));
			}
		}
		if( count($_prod_cats) == 0 ) return;
		
		ob_start();
		?>
		<?php $_random_id = 'product_category_wrapper_'.rand(); ?>
		<div class="product_sub_category_sc wpdance-slider-sc" id="<?php echo $_random_id;?>">
			<div class="wd-slider-sc">	
				<div class="wd_list_categories"><!--wd-loading-->
					<ul>
					<?php foreach($_prod_cats as $key => $cat){ ?>
						<?php 
						
						if( isset($cat->wd_icon) && trim($cat->wd_icon) !== "" ) {
							 if( strpos($cat->wd_icon, 'http') === false ) {
								$thumbnail_id = $cat->wd_icon;
							 } else {
								$thumbnail_id = false;
							 }
							 if ( $thumbnail_id ) {
								$image = wp_get_attachment_image_src( $thumbnail_id, 'full'  );
								$image = $image[0];
							} else {
								$image = $cat->wd_icon;
							}
						} else {
							$image = false;
						}

						if ( $image ) {
							$image = str_replace( ' ', '%20', $image );
							$image_echo = '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $cat->name ) . '" height="40" />';
						}
						
						?>
					
						<li style="width: <?php echo esc_attr(100/count($_prod_cats));?>%" class="link_cat <?php echo ($key==0)?'current':''; ?>" data-slug="<?php echo $cat->slug; ?>">
							<a href="javascript:void(0)" ><?php echo $image_echo; ?><br /><?php echo esc_html($cat->name); ?></a>
						</li>
					<?php } ?>
					</ul>
					<div class="owl-controls owl-heading-pos">
						<div class="owl-nav">
							<div class="owl-prev" id="<?php echo $_random_id;?>_prev">&lt;</div>
							<div class="owl-next" id="<?php echo $_random_id;?>_next">&gt;</div>
						</div>
					</div>
				</div>
				<div class="product_sub_category_wrapper_inner">	
					<div class="wd-slider-content wd-loading">
						<?php echo wd_product_filter_by_category_load_content($atts, $_prod_cats[0]->slug, $_random_id);?>
					</div>
				</div>
				<script type="text/javascript">
					jQuery(document).ready(function($){
						"use strict";
						
						var _random_id = jQuery('#<?php echo $_random_id; ?>');
						var _shortcode_data_<?php echo $_random_id ?> = new Array();
						
						var current_slug = _random_id.find('.wd_list_categories ul li.link_cat.current').attr('data-slug');
						
						_random_id.find('.wd_list_categories ul li.link_cat').live('click', function(){
							
							if( jQuery(this).hasClass('current') )
								return;
							_random_id.find('.wd_list_categories ul li.link_cat').removeClass('current');
							jQuery(this).addClass('current');
							
							var sub_cat_slug = jQuery(this).attr('data-slug');
							if( _shortcode_data_<?php echo $_random_id ?>[sub_cat_slug] ){
								
								_random_id.find('.product_sub_category_wrapper_inner .wd-slider-content').stop().animate({'opacity': 0.3},200, function(){
									_random_id.find('.product_sub_category_wrapper_inner .wd-slider-content').html(_shortcode_data_<?php echo $_random_id ?>[sub_cat_slug]);
									if( typeof wd_qs_prettyPhoto == 'function' ){
										wd_qs_prettyPhoto();
									}
									_random_id.find('.product_sub_category_wrapper_inner .wd-slider-content').stop().animate({'opacity': 1}, 300);
								});
								return;
							}
							
							var data = {
								action : 'wd_product_filter_by_category_load_content'
								,atts : <?php echo json_encode($atts); ?>
								,sub_cat_slug : sub_cat_slug
								,element: "<?php echo $_random_id ?>"
							};
						
							jQuery.ajax({
								type : "POST",
								timeout : 30000,
								url : _ajax_uri,
								data : data,
								error: function(xhr,err){ 
									_random_id.find('.product_sub_category_wrapper_inner').unblock();
								},
								beforeSend: function(o){
									_random_id.find('.product_sub_category_wrapper_inner').block({message: null, overlayCSS: {background: '#fff url('+wd_loading_icon+') no-repeat center'}});
								},
								success: function(response) { 
									_random_id.find('.product_sub_category_wrapper_inner').unblock();
									_random_id.find('.product_sub_category_wrapper_inner .wd-slider-content').html(response);
									
									_shortcode_data_<?php echo $_random_id ?>[sub_cat_slug] = response;
									if( typeof wd_qs_prettyPhoto == 'function' ){
										wd_qs_prettyPhoto();
									}
								}
							});
							
							console.log(_shortcode_data_<?php echo $_random_id ?>);
						});
						
						jQuery('body').on('wd_on_update_slider', "#<?php echo $_random_id?>", function(){
							var temp_visible = <?php echo absint($columns);?>;
							var row = 1;
							var item_width = 100;
						
							var show_nav = false;
							var prev,next,pagination;

							var show_icon_nav = false;
						
							var object_selector = "#<?php echo $_random_id?> .products";
							var autoplay = false;
							generate_horizontal_slide(temp_visible,row,item_width,show_nav,show_icon_nav,autoplay,object_selector);
						});
						
						//_random_id.trigger('wd_on_update_slider');
						
					});
				</script>
				
				<?php 
				wp_reset_postdata();
				return '<div class="woocommerce">' . ob_get_clean() . '</div>';		
				?>
			</div>
		</div>
		
		<?php 
	}
}

	if( !function_exists('wd_get_sub_categories') ){
		function wd_get_sub_categories($category_id,$show_all=true){
			$args = array(
			   'taxonomy'     => 'product_cat'
			   ,'orderby'      => 'name'
			   ,'order'        => 'asc'
			   ,'hierarchical' => 0
			   ,'title_li'     => ''
			   ,'hide_empty'   => 0
			);
			if($show_all){
				$args['child_of'] = $category_id;
			}
			else{
				$args['parent'] = $category_id;
			}
			return get_categories( $args );
		}
	}

add_shortcode('product_filter_by_sub_category','wd_product_filter_by_category_function');





/* to do */
	add_action("wp_ajax_wd_product_filter_by_category_load_content", "wd_product_filter_by_category_load_content");
	add_action("wp_ajax_nopriv_wd_product_filter_by_category_load_content", "wd_product_filter_by_category_load_content");
	
	function wd_product_filter_by_category_load_content($atts = array(), $sub_cat_slug = '', $element = ''){
		if( isset($_POST['atts']) ){
			$atts = $_POST['atts'];
		}
		if( isset($_POST['sub_cat_slug']) ){
			$sub_cat_slug = $_POST['sub_cat_slug'];
		}
		if( isset($_POST['element']) ){
			$element = $_POST['element'];
		}
		
		extract(shortcode_atts(array(
			'columns' 			=> 4,
			'style' 			=> 1,
			'per_page' 			=> 8,
			'title' 			=> '',
			'desc' 				=> '',
			'product_cats' 		=> '',
			'show_image' 		=> 1,
			'show_title' 		=> 1,
			'show_price' 		=> 1,
			'show_except' 		=> 1,
			'show_rating' 		=> 1,
			'show_label' 		=> 1,
			'show_prod_buttons'	=> 1,
			'shortc_limit'		=> 15,
		),$atts));
			
		
		
		$args = array(
			'post_type'	=> 'product',
			'post_status' => 'publish',
			'ignore_sticky_posts'	=> 1,
			'posts_per_page' => $per_page,
			'order' => 'date',
			'orderby' => 'desc',
			'meta_query' => array(
				array(
					'key' => '_visibility',
					'value' => array('catalog', 'visible'),
					'compare' => 'IN'
				),
			),
			'tax_query' => array(
				array(
					'taxonomy' => 'product_cat',
					'terms' => array( esc_attr($sub_cat_slug) ),
					'field' => 'slug',
					'include_children' => false
				)
			)
		);
		global $woocommerce_loop;
		$_old_woocommerce_loop_columns = $woocommerce_loop['columns'];
		$woocommerce_loop['columns'] = $columns;
		$products = new WP_Query( $args );
		
		$data = array('show_image' => $show_image,
				'show_title' => $show_title,
				'show_rating' => $show_rating,
				'show_price' => $show_price,
				'show_label' => $show_label,
				'show_add_to_cart' => $show_prod_buttons,
		);
		shop_loop_prod_remove_action($data);
		
		ob_start();
		if ( $products->have_posts() ){
			?>
			
				<?php woocommerce_product_loop_start(); ?>

					<?php while ( $products->have_posts() ) : $products->the_post(); ?>

						<?php wc_get_template_part( 'content', 'product' ); ?>

					<?php endwhile; // end of the loop. ?>
				<?php woocommerce_product_loop_end(); ?>
				
			<?php
		} else {
		
		}
		$woocommerce_loop['columns'] = $_old_woocommerce_loop_columns;
		
		?>
		<script type="text/javascript">
			jQuery(document).ready(function(){
				//jQuery('#<?php echo $element;?>').trigger('wd_on_update_slider');
				var temp_visible = <?php echo absint($columns);?>;
				var row = 1;
				var item_width = 100;
						
				var show_nav = false;
				var prev,next,pagination;
				var show_icon_nav = false;
						
				var object_selector = "#<?php echo $element?> .products";
				var autoplay = false;
				generate_horizontal_slide(temp_visible,row,item_width,show_nav,show_icon_nav,autoplay,object_selector, false);
			});
		</script>
		<?php 
		
		$html = ob_get_clean();
		shop_loop_prod_add_action();
		
		if( is_ajax() ){
			die($html);
		}
		else{
			return $html;
		}
	}

?>