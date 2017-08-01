<?php 
	if(!function_exists('wd_grid_product_cat_function')){
		if(!function_exists('wd_shortcode_subcategory_thumbnail2')){
			function wd_shortcode_subcategory_thumbnail2( $category ) {
				$small_thumbnail_size  	= apply_filters( 'single_product_small_thumbnail_size', 'shop_catalog_image_size' );
				$dimensions    			= wc_get_image_size( $small_thumbnail_size );
				$thumbnail_id  			= get_woocommerce_term_meta( $category->term_id, 'thumbnail_id', true  );

				if ( $thumbnail_id ) {
					$image = wp_get_attachment_image_src( $thumbnail_id, $small_thumbnail_size  );
					$image = $image[0];
				} else {
					//$image = wc_placeholder_img_src();
					$image = false;
				}

				if ( $image ) {
					// Prevent esc_url from breaking spaces in urls for image embeds
					// Ref: http://core.trac.wordpress.org/ticket/23605
					$image = str_replace( ' ', '%20', $image );

					return '<img src="' . esc_url( $image ) . '" alt="' . esc_attr( $category->name ) . '" width="' . esc_attr( $dimensions['width'] ) . '" height="' . esc_attr( $dimensions['height'] ) . '" />';
				} else return false;
			}
		}
		
		
		function wd_grid_product_cat_function($atts,$content){
			extract(shortcode_atts(array(
				'columns' 		=> 3,
				'limit'			=> 6,
				'style'			=> 'style-3',
				'backg_hover'	=> 'dark',
				'hide_empty'	=> 1
			),$atts));
			
			$hide_empty = absint($hide_empty);
			
			$args = array(
				'number'     => $limit,
				'orderby'    => "name",
				'order'      => "ASC",
				'hide_empty' => $hide_empty
			);
			
			$terms = get_terms('product_cat', $args);
			ob_start();
			$shortcode_id = "shortcode_grid_categories_" . rand(0,1000);
			echo "<div class=\"grid_product-catagory product-catagory-slider\">";
			echo "<ul class=\"archive-product-subcategories wd-shortcode ".$style."\" id=\"".$shortcode_id."\">";
			$current_row = 0;
			
			$_columns = $columns;
			$_sub_class = " wd-col-lg-".$_columns;
			$_sub_class .= ' wd-col-md-'.floor($_columns * 992 / 1200);
			$_sub_class .= ' wd-col-sm-'.max(2,floor($_columns * 768 / 1200));
			$_sub_class .= ' wd-col-xs-1';
			$_sub_class .= ' wd-col-mb-1';
	
			foreach($terms as $cat){
				$image = wd_shortcode_subcategory_thumbnail2($cat);
			?>
			<li class="product-category<?php echo $_sub_class?>">
				
				<a href="<?php echo esc_url(get_term_link($cat->slug, "product_cat"))?>">
					<?php echo $image;?>
				</a>
				<div class="product-category-info <?php echo $backg_hover;?>"><div class="display-table"><div>
					<?php if(strcmp(trim($style), 'style-2') === 0):?>
					<h3 class="h1"><?php echo esc_html($cat->name);?></h3>
					<?php else:?>
					<h3 class="h1"><?php echo esc_html($cat->name);?></h3>
					<div class="line line-30 line-margin"></div>
					<p><?php if ( $cat->count > 0 ) echo apply_filters( 'woocommerce_subcategory_count_html', ' <mark class="count">' . $cat->count . ' ' .__('Products', 'wpdance'). '</mark>', $cat );?></p>
					<a href="<?php echo esc_url(get_term_link($cat->slug, "product_cat"))?>"><?php _e('shop now','wpdance');?></a>
					<?php endif;?>
				</div></div></div>
				
			</li>
			<?php
			}
			echo "</ul></div>";
			
			$output = ob_get_contents();
			ob_end_clean();
			rewind_posts();
			wp_reset_query();
			return $output;
		}
	}
	add_shortcode('wd_grid_product_cat','wd_grid_product_cat_function');
?>