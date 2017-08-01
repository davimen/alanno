<?php
/**
 * @package WordPress
 * @subpackage Roedok
 * @since WD_Responsive
 */

	
	
	if(!function_exists('site_url_function')){
		function site_url_function($atts,$content){
			extract(shortcode_atts(array(
				'method' => 'return'
			),$atts));
			switch($method){
				case 'echo': echo site_url(); break;
				case 'return': return site_url(); break;
				default: return site_url(); break;
			}
			
		}
	}
	add_shortcode('wd_site_url','site_url_function');
	
	if(!function_exists('wd_theme_url_function')){
		function wd_theme_url_function($atts,$content){
			extract(shortcode_atts(array(
				'method' => 'return'
			),$atts));
			switch($method){
				case 'echo': echo get_template_directory_uri(); break;
				case 'return': return get_template_directory_uri(); break;
				default: return get_template_directory_uri(); break;
			}
			
		}
	}
	add_shortcode('wd_theme_url','wd_theme_url_function');
	
	if(!function_exists('wd_auto_copyright_function')){
		function wd_auto_copyright_function($atts,$content){
			extract(shortcode_atts(array(
				'year' => 'auto',
			),$atts));
			if(!is_numeric($year)) { $res = date('Y'); }
			if(intval($year) == 'auto'){ $year = date('Y'); }
			if(intval($year) >= date('Y')){ $res = date('Y'); }
			if(intval($year) < date('Y')){ $res = intval($year) . ' - ' . date('Y'); }
			return $res;
		}
	}
	add_shortcode('wd_auto_copyright','wd_auto_copyright_function');
	
	if(!function_exists('wd_current_url_function')){
		function wd_current_url_function($atts,$content){
			extract(shortcode_atts(array(
				'method' => 'return'
			),$atts));
			switch($method){
				case 'echo': echo get_permalink(); break;
				case 'return': return get_permalink(); break;
				default: return get_permalink(); break;
			}
			
		}
	}
	add_shortcode('wd_c_url','wd_current_url_function');
	
	if(!function_exists('wd_featured_by_category_function')){
		function wd_featured_by_category_function($cat_slug = '',$per_page = 1){
			$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
			if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
				return;
			}
			wp_reset_query(); 
			$args = array(
				'post_type'	=> 'product'
				,'post_status' => 'publish'
				,'ignore_sticky_posts'	=> 1
				,'posts_per_page' => $per_page
				,'meta_query' => array(
					array(
						'key' => '_visibility',
						'value' => array('catalog', 'visible'),
						'compare' => 'IN'
					)
					,array(
						'key' => '_featured',
						'value' => 'yes'
					)
				)
				,'tax_query' 			=> array(
					array(
						'taxonomy' 		=> 'product_cat',
						'terms' 		=> array( esc_attr($cat_slug) ),
						'field' 		=> 'slug',
						'operator' 		=> 'IN'
					)
				)
			);
			wp_reset_query(); 
			$products = new WP_Query( $args );
			if( $products->have_posts() ){
				global $post;
				$products->the_post();
				$product = get_product( $post->ID );
				return $product;
			}
			return NULL;
		}
	}
	
	function wd_order_by_popularity_post_clauses( $args ) {
		global $wpdb;

		$args['orderby'] = "$wpdb->postmeta.meta_value+0 DESC, $wpdb->posts.post_date DESC";

		return $args;
	}	

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
	

	/*
	*	columns : 3 or 4
	*	layout : small or big
	*	per_page : 4 to 12
	*	title : ""
	*	desc : ""
	*	product_tag : tag of prods
	*	show nav thumb : 1
	* 	show thumb : 1
	*	show title : 1
	* 	show sku : 1
	*	show price
	*	show label
	* 	item slide : 1
	*/
	

	
	/*if(!function_exists('wd_popular_product_slider_function')){
		function wd_popular_product_slider_function($atts,$content){
			$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
			if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
				return;
			}
			global $woocommerce_loop, $woocommerce,$wd_data;
			extract(shortcode_atts(array(
				'columns' 			=> 4
				,'layout' 			=> 'small'
				,'per_page' 		=> 8
				,'title' 			=> ''
				,'desc' 			=> ''
				,'product_tag' 		=> ''
				,'show_nav' 		=> 1
				,'show_icon_nav' 	=> 1
				,'show_image' 		=> 1
				,'show_title' 		=> 1
				,'show_sku' 		=> 1
				,'show_price' 		=> 1
				,'show_rating' 		=> 1
				,'show_label' 		=> 1
                ,'show_categories' 	=> 1
				,'show_short_content' => 1				
			),$atts));
			
			if(!(int)$show_image){
				remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );
			}else{
				if( strcmp($layout,'big') == 0 ){
					remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );
					add_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_big_thumbnail', 10 );
				}
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
			
			wp_reset_query(); 
			
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
		
			
			if( strlen($product_tag) > 0 && strcmp('all-product-tags',$product_tag) != 0 ){
				$args = array_merge($args, array('product_tag' => $product_tag));
			}
			
			ob_start();

	  	add_filter( 'posts_clauses', 'wd_order_by_rating_post_clauses' );

		$products = new WP_Query( $args );

		remove_filter( 'posts_clauses', 'wd_order_by_rating_post_clauses' );
			
			if(isset($wd_data['wd_prod_cat_column']) && absint($wd_data['wd_prod_cat_column']) > 0 ){
				$_old_wd_prod_cat_column = $wd_data['wd_prod_cat_column'];
				$wd_data['wd_prod_cat_column'] = $columns;
			} 
			
			$_old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			$woocommerce_loop['columns'] = $columns;

			if ( $products->have_posts() ) : ?>
				<?php $_random_id = 'featured_product_slider_wrapper_'.rand(); ?>
				<div class="featured_product_slider_wrapper" id="<?php echo $_random_id;?>" data_column="<?php echo $columns; ?>">
					<div class="featured_product_slider_wrapper_meta"> 
						<?php
							if(strlen(trim($title)) >0)
								echo "<h3 class='heading-title slider-title'>{$title}</h3>";
							if(strlen(trim($desc)) >0)	
								echo "<p class='slider-desc-wrapper'>{$desc}</p>";
						?>
					</div>
					<div class="featured_product_slider_wrapper_inner height_auto">
					
						<?php if($show_icon_nav):?>
							<div id="<?php echo $_random_id;?>_pager" class="pager"></div>
						<?php endif;?>
						
						<?php woocommerce_product_loop_start(); ?>

							<?php while ( $products->have_posts() ) : $products->the_post(); ?>

								<?php woocommerce_get_template_part( 'content', 'product' ); ?>

							<?php endwhile; // end of the loop. ?>
						<?php woocommerce_product_loop_end(); ?>
						
						<?php if($show_nav):?>
						<div class="wd_slider_control">
							<a title="prev" id="<?php echo $_random_id;?>_prev" class="prev" href="#">&lt;</a>
							<a title="next" id="<?php echo $_random_id;?>_next" class="next" href="#">&gt;</a>
						</div>
						<?php endif;?>
						
					</div>
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
						var temp_visible = <?php echo $columns;?>;
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
								
								width: <?php echo wp_is_mobile() ? 300 : 140 ;?>
								,height: 'auto'
								,visible: temp_visible
							}
							,direction			: "left"
							,responsive 		: true	
							,swipe				: { onTouch: true }		
							,scroll				: <?php if( !wp_is_mobile() ) : ?>
													{ 
													duration : 1000
													, pauseOnHover:true
													, easing : "easeOutSine"}
													<?php else :?>
														1
													<?php endif;?>
							,width				: '100%'
							,height				: '100%'
							,circular			: true
							,infinite			: true
							,auto				: false
							<?php if($show_nav):?>
							,prev				: '#<?php echo $_random_id;?>_prev'
							,next				: '#<?php echo $_random_id;?>_next'								
							<?php endif;?>
							<?php if($show_icon_nav):?>
							,pagination 		: '#<?php echo $_random_id;?>_pager'
							<?php endif;?>							
						};
						jQuery("#<?php echo $_random_id?> > .featured_product_slider_wrapper_inner > div.products").carouFredSel(_slider_datas);	
						var temp = jQuery("#<?php echo $_random_id;?>").closest(".tabbable").addClass('has_slider');
						jQuery(temp).bind('tabs_change',jQuery.debounce( 250, function(event, id){
							var my_id = '<?php echo $_random_id;?>';
							if(id == my_id){
								var _item_width = jQuery(window).width() < 600 ?  300: 200;
								_slider_datas.items.width = _item_width;
								_slider_datas.items.visible = temp_visible;
								jQuery("#<?php echo $_random_id?> div.products").trigger('configuration ',["items.width", 200, true]);
								setTimeout(function(){
									jQuery("#<?php echo $_random_id?> > .featured_product_slider_wrapper_inner  div.products").carouFredSel(_slider_datas);	
									jQuery("#<?php echo $_random_id?> > .featured_product_slider_wrapper_inner div.products div.product").show();
									jQuery("#<?php echo $_random_id?>").closest(".tab-pane").css('height','auto');
									jQuery(temp).children('.tab-content').removeClass('wd_loading');
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
			if( (int)$show_image && strcmp($layout,'big') == 0 ){
				remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_big_thumbnail', 10 );			
			}
			
			add_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );			
			//end
			if(isset($_old_wd_prod_cat_column) && absint($_old_wd_prod_cat_column > 0 )){
				$wd_data['wd_prod_cat_column'] = $_old_wd_prod_cat_column  ;
			}
			$woocommerce_loop['columns'] = $_old_woocommerce_loop_columns ;
			return '<div class="woocommerce">' . ob_get_clean() . '</div>';		
			
		}
	}	
	add_shortcode('popular_product_slider','wd_popular_product_slider_function');*/	




	/*
	*	columns : 3 or 4
	*	layout : small or big
	*	per_page : 4 to 12
	*	title : ""
	*	desc : ""
	*	product_tag : tag of prods
	*	show nav thumb : 1
	* 	show thumb : 1
	*	show title : 1
	* 	show sku : 1
	*	show price
	*	show label
	* 	item slide : 1
	*/
	

	
	/*if(!function_exists('wd_recent_product_slider_function')){
		function wd_recent_product_slider_function($atts,$content){
			$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
			if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
				return;
			}
			global $woocommerce_loop, $woocommerce,$wd_data;
			extract(shortcode_atts(array(
				'columns' 			=> 4
				,'layout' 			=> 'small'
				,'per_page' 		=> 8
				,'title' 			=> ''
				,'desc' 			=> ''
				,'product_tag' 		=> ''
				,'show_nav' 		=> 1
				,'show_icon_nav' 	=> 1
				,'show_image' 		=> 1
				,'show_title' 		=> 1
				,'show_sku' 		=> 1
				,'show_price' 		=> 1
				,'show_rating' 		=> 1
				,'show_label' 		=> 1
                ,'show_categories' 	=> 1
				,'show_short_content' => 1
				,'auto'				=> 0			
			),$atts));
			
			if(!(int)$show_image){
				remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );
			}else{
				if( strcmp($layout,'big') == 0 ){
					remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );
					add_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_big_thumbnail', 10 );
				}
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
		
			wp_reset_query(); 
			
			if( strlen($product_tag) > 0 && strcmp('all-product-tags',$product_tag) != 0 ){
				$args = array_merge($args, array('product_tag' => $product_tag));
			}
			
			ob_start();

			$products = new WP_Query( $args );
			if(isset($wd_data['wd_prod_cat_column']) && absint($wd_data['wd_prod_cat_column']) > 0 ){
				$_old_wd_prod_cat_column = $wd_data['wd_prod_cat_column'];
				$wd_data['wd_prod_cat_column'] = $columns;
			}
			$_old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			$woocommerce_loop['columns'] = $columns;

			if ( $products->have_posts() ) : ?>
				<?php $_random_id = 'featured_product_slider_wrapper_'.rand(); ?>
				<div class="featured_product_slider_wrapper" id="<?php echo $_random_id;?>" data_column="<?php echo $columns; ?>">
					<div class="featured_product_slider_wrapper_meta"> 
						<?php
							if(strlen(trim($title)) >0)
								echo "<h3 class='heading-title slider-title'>{$title}</h3>";
							if(strlen(trim($desc)) >0)	
								echo "<p class='slider-desc-wrapper'>{$desc}</p>";
						?>
					</div>
					<div class="featured_product_slider_wrapper_inner height_auto">
					
						<?php if($show_icon_nav):?>
							<div id="<?php echo $_random_id;?>_pager" class="pager"></div>
						<?php endif;?>
						
						<?php woocommerce_product_loop_start(); ?>

							<?php while ( $products->have_posts() ) : $products->the_post(); ?>

								<?php woocommerce_get_template_part( 'content', 'product' ); ?>

							<?php endwhile; // end of the loop. ?>
						<?php woocommerce_product_loop_end(); ?>
						
						<?php if($show_nav):?>
						<div class="wd_slider_control">
							<a title="prev" id="<?php echo $_random_id;?>_prev" class="prev" href="#">&lt;</a>
							<a title="next" id="<?php echo $_random_id;?>_next" class="next" href="#">&gt;</a>
						</div>
						<?php endif;?>
						
					</div>
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
						var temp_visible = <?php echo $columns;?>;
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
								
								width: <?php echo wp_is_mobile() ? 300 : 140 ;?>
								,height: 'auto'
								,visible: temp_visible							
							}
							,direction			: "left"
							,responsive 		: true	
							,swipe				: { onTouch: true }		
							,scroll				: <?php if( !wp_is_mobile() ) : ?>
													{ 
													duration : 1000
													, pauseOnHover:true
													, easing : "easeOutSine"}
													<?php else :?>
														1
													<?php endif;?>
							,width				: '100%'
							,height				: '100%'
							,circular			: true
							,infinite			: true
							<?php if($auto):?> ,auto : true <?php else: ?> ,auto : false <?php endif;?>
							<?php if($show_nav):?>
							,prev				: '#<?php echo $_random_id;?>_prev'
							,next				: '#<?php echo $_random_id;?>_next'								
							<?php endif;?>
							<?php if($show_icon_nav):?>
							,pagination 		: '#<?php echo $_random_id;?>_pager'
							<?php endif;?>							
						};
						jQuery("#<?php echo $_random_id?> > .featured_product_slider_wrapper_inner > div.products").carouFredSel(_slider_datas);	
						var temp = jQuery("#<?php echo $_random_id;?>").closest(".tabbable").addClass('has_slider');
						jQuery(temp).bind('tabs_change',jQuery.debounce( 250, function(event, id){
							var my_id = '<?php echo $_random_id;?>';
							if(id == my_id){
								var _item_width = jQuery(window).width() < 600 ?  300: 200;
								_slider_datas.items.width = _item_width;
								_slider_datas.items.visible = temp_visible;
								//jQuery("#<?php echo $_random_id?> > .featured_product_slider_wrapper_inner div.products").trigger('destroy',true);
								//jQuery("#<?php echo $_random_id?> div.products").trigger('configuration ',["items.width", 200, true]);
								setTimeout(function(){
									jQuery("#<?php echo $_random_id?> > .featured_product_slider_wrapper_inner  div.products").carouFredSel(_slider_datas);	
									jQuery("#<?php echo $_random_id?> > .featured_product_slider_wrapper_inner div.products li").show();
									//jQuery("#<?php echo $_random_id?>").closest(".tab-pane").css('visibility','visible');
									jQuery("#<?php echo $_random_id?>").closest(".tab-pane").css('height','auto');
									jQuery(temp).children('.tab-content').removeClass('wd_loading');
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
			if( (int)$show_image && strcmp($layout,'big') == 0 ){
				remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_big_thumbnail', 10 );			
			}
			
			add_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );			
			//end
			$woocommerce_loop['columns'] = $_old_woocommerce_loop_columns ;
			if(isset($_old_wd_prod_cat_column) && absint($_old_wd_prod_cat_column > 0 )){
				$wd_data['wd_prod_cat_column'] = $_old_wd_prod_cat_column  ;
			}
			return '<div class="woocommerce">' . ob_get_clean() . '</div>';		
			
		}
	}		
	add_shortcode('recent_product_slider','wd_recent_product_slider_function');	*/
	
	
	
	/*if(!function_exists('wd_recent_product_by_categories_slider_function')){
		function wd_recent_product_by_categories_slider_function($atts,$content){
			$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
			if ( !in_array( "woocommerce/woocommerce.php", $_actived ) ) {
				return;
			}
			global $woocommerce_loop, $woocommerce,$wd_data;
			extract(shortcode_atts(array(
				'columns' 			=> 4
				,'layout' 			=> 'small'
				,'per_page' 		=> 8
				,'title' 			=> ''
				,'desc' 			=> ''
				,'product_tag' 		=> ''
				,'show_nav' 		=> 1
				,'show_icon_nav' 	=> 1
				,'show_image' 		=> 1
				,'show_title' 		=> 1
				,'show_sku' 		=> 1
				,'show_price' 		=> 1
				,'show_rating' 		=> 1
				,'show_label' 		=> 1
                ,'show_categories' 	=> 1
				,'show_short_content' => 1
				,'cat_slug'			=> ''		
			),$atts));
			
			
			if($cat_slug=='' && has_term( $cat_slug, 'product_cat', 'product' )){
				echo 'cxc';
				return;
			}
			
			if(!(int)$show_image){
				remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );
			}else{
				if( strcmp($layout,'big') == 0 ){
					remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );
					add_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_big_thumbnail', 10 );
				}
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
				,'tax_query' 			=> array(
					array(
						'taxonomy' 		=> 'product_cat',
						'terms' 		=> array( esc_attr($cat_slug) ),
						'field' 		=> 'slug',
						'operator' 		=> 'IN'
					)
				)
			);
		
			wp_reset_query(); 
			
			if( strlen($product_tag) > 0 && strcmp('all-product-tags',$product_tag) != 0 ){
				$args = array_merge($args, array('product_tag' => $product_tag));
			}
			
			ob_start();

			$products = new WP_Query( $args );
			if(isset($wd_data['wd_prod_cat_column']) && absint($wd_data['wd_prod_cat_column']) > 0 ){
				$_old_wd_prod_cat_column = $wd_data['wd_prod_cat_column'];
				$wd_data['wd_prod_cat_column'] = $columns;
			} 
			$_old_woocommerce_loop_columns = $woocommerce_loop['columns'];
			$woocommerce_loop['columns'] = $columns;

			if ( $products->have_posts() ) : ?>
				<?php $_random_id = 'featured_product_slider_wrapper_'.rand(); ?>
				<div class="featured_product_slider_wrapper" id="<?php echo $_random_id;?>" data_column="<?php echo $columns; ?>">
					<div class="featured_product_slider_wrapper_meta"> 
						<?php
							if(strlen(trim($title)) >0)
								echo "<h3 class='heading-title slider-title'>{$title}</h3>";
							if(strlen(trim($desc)) >0)	
								echo "<p class='slider-desc-wrapper'>{$desc}</p>";
						?>
					</div>
					<div class="featured_product_slider_wrapper_inner height_auto">
					
						<?php if($show_icon_nav):?>
							<div id="<?php echo $_random_id;?>_pager" class="pager"></div>
						<?php endif;?>
						
						<?php woocommerce_product_loop_start(); ?>

							<?php while ( $products->have_posts() ) : $products->the_post(); ?>

								<?php woocommerce_get_template_part( 'content', 'product' ); ?>

							<?php endwhile; // end of the loop. ?>
						<?php woocommerce_product_loop_end(); ?>
						
						<?php if($show_nav):?>
						<div class="wd_slider_control">
							<a title="prev" id="<?php echo $_random_id;?>_prev" class="prev" href="#">&lt;</a>
							<a title="next" id="<?php echo $_random_id;?>_next" class="next" href="#">&gt;</a>
						</div>
						<?php endif;?>
						
					</div>
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
						var temp_visible = <?php echo $columns;?>;
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
								
								width: <?php echo wp_is_mobile() ? 300 : 140 ;?>
								,height: 'auto'
								,visible: temp_visible					
							}
							,direction			: "left"
							,responsive 		: true	
							,swipe				: { onTouch: true }		
							,scroll				: <?php if( !wp_is_mobile() ) : ?>
													{ 
													duration : 1000
													, pauseOnHover:true
													, easing : "easeOutSine"}
													<?php else :?>
														1
													<?php endif;?>
							,width				: '100%'
							,height				: '100%'
							,circular			: true
							,infinite			: true
							,auto				: false
							<?php if($show_nav):?>
							,prev				: '#<?php echo $_random_id;?>_prev'
							,next				: '#<?php echo $_random_id;?>_next'								
							<?php endif;?>
							<?php if($show_icon_nav):?>
							,pagination 		: '#<?php echo $_random_id;?>_pager'
							<?php endif;?>							
						};
						jQuery("#<?php echo $_random_id?> > .featured_product_slider_wrapper_inner > div.products").carouFredSel(_slider_datas);	
						var temp = jQuery("#<?php echo $_random_id;?>").closest(".tabbable").addClass('has_slider');
						jQuery(temp).bind('tabs_change',jQuery.debounce( 250, function(event, id){
							var my_id = '<?php echo $_random_id;?>';
							if(id == my_id){
								var _item_width = jQuery(window).width() < 600 ?  300: 200;
								_slider_datas.items.width = _item_width;
								_slider_datas.items.visible = temp_visible;
								//jQuery("#<?php echo $_random_id?> > .featured_product_slider_wrapper_inner div.products").trigger('destroy',true);
								jQuery("#<?php echo $_random_id?> div.products").trigger('configuration ',["items.width", 200, true]);
									setTimeout(function(){
									jQuery("#<?php echo $_random_id?> > .featured_product_slider_wrapper_inner  div.products").carouFredSel(_slider_datas);	
									jQuery("#<?php echo $_random_id?> > .featured_product_slider_wrapper_inner div.products li").show();
									//jQuery("#<?php echo $_random_id?>").closest(".tab-pane").css('visibility','visible');
									jQuery("#<?php echo $_random_id?>").closest(".tab-pane").css('height','auto');
									jQuery(temp).children('.tab-content').removeClass('wd_loading');
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
			if( (int)$show_image && strcmp($layout,'big') == 0 ){
				remove_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_big_thumbnail', 10 );			
			}
			
			add_action( 'woocommerce_before_shop_loop_item_title', 'wd_template_loop_product_thumbnail', 10 );			
			//end
			$woocommerce_loop['columns']= $_old_woocommerce_loop_columns;
			if(isset($_old_wd_prod_cat_column) && absint($_old_wd_prod_cat_column > 0 )){
				$wd_data['wd_prod_cat_column'] = $_old_wd_prod_cat_column  ;
			}
			return '<div class="woocommerce">' . ob_get_clean() . '</div>';		
			
		}
	}		
	add_shortcode('recent_product_by_categories_slider','wd_recent_product_by_categories_slider_function');*/
	
?>