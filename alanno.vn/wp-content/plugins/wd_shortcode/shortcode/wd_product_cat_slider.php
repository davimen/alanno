<?php 
	if(!function_exists('wd_product_cat_slider_function')){
		if(!function_exists('wd_shortcode_subcategory_thumbnail')){
			function wd_shortcode_subcategory_thumbnail( $category ) {
				$small_thumbnail_size  	= apply_filters( 'single_product_small_thumbnail_size', 'wd_categories_thumbnail' );
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
		
		
		function wd_product_cat_slider_function($atts,$content){
			extract(shortcode_atts(array(
				'columns' 		=> 3,
				'rows' 			=> 1,
				'limit'			=> 6,
				'slider'		=> 1,
				'style'			=> 'style-1',
				'backg_hover'	=> 'dark',
				'show_nav'		=> 1,
				'autoplay'		=> 0,
				'hide_empty'	=> 1
			),$atts));
			
			$slider 	= absint($slider);
			$show_nav 	= absint($show_nav);
			$autoplay 	= absint($autoplay);
			$hide_empty = absint($hide_empty);
			
			$args = array(
				'number'     => $limit,
				'orderby'    => "name",
				'order'      => "ASC",
				'hide_empty' => $hide_empty
			);
			
			$terms = get_terms('product_cat', $args);
			ob_start();
			$shortcode_id = "shortcode_categories_" . rand(0,1000);
			echo "<div class=\"product-catagory-slider wd-loading\">";
			echo "<ul class=\"archive-product-subcategories wd-shortcode ".$style."\" id=\"".$shortcode_id."\">";
			$current_row = 0;
			foreach($terms as $cat){
				$image = wd_shortcode_subcategory_thumbnail($cat);
				if($image!== false):
			?>
			<?php
				if($rows > 1 && ($current_row % $rows == 0)){
					echo '<div class="products_group">';
				}
				?>
			<li class="product-category">
				
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
					<?php endif;?>
				</div></div></div>
				
			</li>
			<?php
					if($rows > 1 && (($current_row % $rows + 1== $rows) || ($current_row + 1 == count((array)$terms) ) )){
						echo '</div>';
					}
							
					$current_row++;
				?>
			<?php
				endif;
			}
			echo "</ul></div>";
			if($slider):
			?>
			<script type='text/javascript'>
			//<![CDATA[
				jQuery(document).ready(function() {
					"use strict";
					var temp_visible = <?php echo absint($columns);?>;
					
					var row = 1;
					var item_width = 270;
					
					var show_nav = <?php echo ($show_nav)? 'true': 'false'?>;
					var prev,next,pagination;
					var show_icon_nav =<?php echo (!$show_nav)? 'true': 'false'?>;
					
					var object_selector = "#<?php echo $shortcode_id;?>";

                    var autoplay = <?php echo ($autoplay)? 'true': 'false'?>;
					var responsive = [1,2,Math.round(temp_visible * 768 /1200),Math.round(temp_visible * 992 /1200),temp_visible];
                    generate_horizontal_slide(temp_visible,row,item_width,show_nav,show_icon_nav,autoplay,object_selector, true, responsive);
				});
			//]]>	
			</script>
			<?php endif;
			
			$output = ob_get_contents();
			ob_end_clean();
			rewind_posts();
			wp_reset_query();
			return $output;
		}
	}
	add_shortcode('wd_product_cat_slider','wd_product_cat_slider_function');
?>