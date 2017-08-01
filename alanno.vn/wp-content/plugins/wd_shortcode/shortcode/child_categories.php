<?php
if( !function_exists('wd_child_categories_shortcode_function')){
	function wd_child_categories_shortcode_function($atts){
		extract(shortcode_atts(array(
			'category'				=> ''
			,'columns'				=> 3
			,'desc'					=> ''
			,'bg_color' 			=> 'transparent'
			,'bg_image'  			=> ''
			,'text_color'			=> ''
			,'height'				=> ''
			,'limit'				=> 5
			,'taxonomy'				=> 'product_cat'
			,'button_text'			=> 'View All Categories'
			,'show_nav'				=> 1
			,'show_nav_pos'			=> 'top_right'
		),$atts));
		
		if( is_admin() ) return;
		
		$category = trim($category);
		if( strlen($category) == 0) return;
		
		$_data_args = explode( ",", $category );
		$_cats_tmp = $_prod_cats = array();
		foreach( $_data_args as $cat ){
			$cat = explode('|',$cat);
			if(!in_array(esc_attr($cat[0]), $_cats_tmp) && $prod_cat = get_term_by('slug', esc_attr($cat[0]), 'product_cat')) {
				$prod_cat->wd_backg = $cat[1];
				array_push($_prod_cats,$prod_cat);
				array_push($_cats_tmp, esc_attr($cat[0]));
			}
		}
		if( count($_prod_cats) == 0 ) return;
		ob_start();
		$rand_id = "wd_child_categories_slider_". rand();
		?>
		<div id="<?php echo esc_attr($rand_id);?>" class="wd_child_categories_slider <?php echo esc_attr($show_nav_pos);?> wd-loading">
			<div class="row list-item">
		<?php 
		foreach($_prod_cats as $cat_slug){
			
			$parent_cat = get_term_by('slug',$cat_slug->slug,$taxonomy);
			
			if( !is_object($parent_cat) )
			return;
			
			$args = array(
						'child_of' 			=> $parent_cat->term_id
						,'number'  			=> $limit
						,'orderby'			=> 'title'
						,'order'			=> 'desc'
						,'hide_empty'  		=> true
						,'hierarchical'  	=> false
					);
			$child_categories = get_terms($taxonomy,$args);
			
			$inline_style = '';
			if( $bg_color != '')
				$inline_style .= 'background-color: '.$bg_color.';';
			if( $cat_slug->wd_backg != ''){
				$bg_image = wp_get_attachment_image_src( $cat_slug->wd_backg, 'full');
				$inline_style .= 'background-image: url('.$bg_image[0].');';
				$inline_style .= 'background-repeat: no-repeat;';
				$inline_style .= 'background-position: center bottom;';
				$inline_style .= 'background-size: cover;';
			}
			if($height !== '') {
				$inline_style .= 'height: '.$height.';';
			}
			$inline_style .= 'width: 100%;margin-left: 15px; margin-right: 15px;';
			$inline_style = 'style="'.$inline_style.'"';
			
			$inline_style_text = '';
			if( $text_color !='' ){
				$inline_style_text .= 'color: '.$text_color.';';
			}
			if( $inline_style_text != '' )
				$inline_style_text = 'style="'.$inline_style_text.'"';
			
			if( $desc == '' ){
				if( function_exists('string_limit_words') )
					$desc = string_limit_words($parent_cat->description, 15);
				else
					$desc = $parent_cat->description;
			}
			$parent_cat_link = get_term_link($parent_cat);
			
			$btn_data_style_hover = '';
			if( $bg_color != '' && $bg_color != 'transparent' && $text_color != '' ){
				$btn_data_style_hover .= 'data-hover="background-color:'.$text_color.';color:'.$bg_color.'"';
			}
			
			
			?>
			<div class="wd_child_categories_shortcode text-center text-uppercase" <?php echo $inline_style; ?>>
				<div class="parent_cat">
					<h3 class="title"><a href="<?php echo $parent_cat_link; ?>" <?php echo $inline_style_text; ?> ><?php echo $parent_cat->name; ?></a></h3>
					<span class="desc" <?php echo $inline_style_text; ?>><?php echo $desc; ?></span>
				</div>
				<div class="child_categories">
					<?php if( count( $child_categories )> 0 ): ?>
						<ul class="list-unstyled">
						<?php foreach( $child_categories as $child_cat ): ?>
							<li><a href="<?php echo get_term_link($child_cat); ?>" <?php echo $inline_style_text; ?> ><?php echo $child_cat->name; ?></a></li>
						<?php endforeach; ?>
						</ul>
					<?php endif; ?>
				</div>
				<div class="cat_button">
					<a class="button" href="<?php echo $parent_cat_link; ?>" <?php echo $inline_style_text; ?> <?php echo $btn_data_style_hover; ?> ><?php echo $button_text; ?></a>
				</div>
			</div>
			
			<?php
		}
		?></div>
		</div>
		<script type='text/javascript'>
			//<![CDATA[
				jQuery(document).ready(function() {
					"use strict";
					var temp_visible = <?php echo $columns;?>;
					
					var row = 1;
					var item_width = 110;
					
					var show_nav = <?php echo absint($show_nav)? "true": "false";?>;
					var prev,next,pagination;

					var show_icon_nav = false;
					
					var object_selector = "#<?php echo $rand_id?> .list-item";
                    var autoplay = false;
					var slider_base_e = false;
					var res_val = [];
					
					generate_horizontal_slide(temp_visible,row,item_width,show_nav,show_icon_nav,autoplay,object_selector,slider_base_e);
				});
			//]]>	
			</script>
		<?php 
		
		return ob_get_clean();
	}
}

add_shortcode('wd_child_categories','wd_child_categories_shortcode_function');

?>