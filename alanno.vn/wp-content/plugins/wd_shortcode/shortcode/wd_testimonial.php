<?php
add_image_size('testimonial',100,100, true);
	if(!function_exists('wd_testimonial_function')){
		function wd_testimonial_function($atts,$content){
			extract(shortcode_atts(array(
				'slug'				=> ''
				,'title' 			=> ''
				,'box_style'		=> 'style-1'
				,'show_nav' 		=> 1
				,'show_nav_pos' 	=> 'top_right'
				,'id'				=> 0
				,'style'			=> 'meta'
				,'limit'			=> 5
				,'wd_query_type'	=> 'simple'
				,'short_limit'		=> 20
				,'show_img'			=> 1
				,'show_date'		=> 1
				,'show_short'		=> 1
			),$atts));
			
			$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
			if ( !in_array( "testimonials-by-woothemes/woothemes-testimonials.php", $_actived ) ) {
				return;
			}
			
			global $post;
			$count = 0;
			if($wd_query_type == 'simple'){
				$slider_loading = '';
				if( absint($id) > 0 ){
					$_testimonial = woothemes_get_testimonials( array('id' => $id,'limit' => 1, 'size' => 100 ));
				}elseif( strlen(trim($slug)) > 0 ){
					$_testimonial = get_page_by_path($slug, OBJECT, 'testimonial');
					if( !is_null($_testimonial) ){
						$_testimonial = woothemes_get_testimonials( array('id' => $_feature->ID,'limit' => 1,  'size' => 100 ));
					}else{
						return;
					}
				}else{
					$_testimonial = woothemes_get_testimonials( array( 'limit' => $limit, 'size' => 100 ));
					//return;
					//invalid input params.
				}
			} else {
				$_testimonial = woothemes_get_testimonials( array('limit' => $limit, 'size' => 100 ));
				$slider_loading = ' wd-loading';
			}
			
			
			if( !is_array($_testimonial) && count($_testimonial) <= 0 ){
				return;
			}else{
				//global $post;
				//$_feature = $_feature[0];
				//$post = $_feature;
				//setup_postdata( $post ); 
			}
			ob_start();
			$testimonial_id = "wd_testimonial_".rand();
			?>
			
			<div class="products_shortcode_wrapper <?php echo esc_attr($box_style);?>">
			<?php if( strlen(trim($title)) > 0 ):?>
				<h3 class='heading-title slider-title'><?php echo $title;?></h3>
				<div class='line line-30'></div>
			<?php endif;?>
			<div class="wd_testimonial_slider <?php echo esc_attr($show_nav_pos);?> <?php echo $slider_loading;?>">
			<div class="<?php echo $testimonial_id?>">
		
			<?php 
			if( !empty($_testimonial) && count($_testimonial) > 0 ){
			
			foreach( $_testimonial as $testimonial ){
				$post = $testimonial;
				setup_postdata( $post );
				$date = get_the_date();
				if($wd_query_type == 'simple'):
			?>
				<div class="testimonial-item testimonial <?php echo esc_attr($style);?>">
					<div class="avartar">
						<?php if(absint($show_img)):?>
							<?php if($style == 'widget'):?>
							<a href="#"><?php the_post_thumbnail('wd_testimonial_widget');?></a>
							<?php else:?>
							<a href="#"><?php the_post_thumbnail('testimonial');?></a>
							<?php endif;?>
						<?php endif;?>
						
						<?php if($style == 'avatar' || $style == 'widget'): ?>
						<h3><?php the_title();?></h3>
						<?php if(absint($show_date)):?>
						<div class="post-info-meta">
							<div class="entry-date"><?php echo $date;?></div>
						</div>
						<?php endif; ?>
						<?php endif; ?>
					</div>							
					<div class="detail">
						<?php if($style == 'meta'): ?>
						<h3><?php the_title();?></h3>
						<?php if(absint($show_date)):?>
						<div class="post-info-meta">
							<div class="entry-date"><?php echo $date;?></div>
						</div>
						<?php endif; ?><?php endif; ?>
						<?php if(absint($show_short)):?>
						<?php 
						$content = get_the_content();
						$content = wp_trim_words( strip_tags($content), $short_limit, $more = null );
						?>
						<div class="testimonial-content"><?php echo $content;?></div>
						<?php endif;?>
					</div>
				</div>
			<?php
				else: 
			?>
				<div class="testimonial-item testimonial">
					<div class="avartar">
						<?php if(absint($show_img)):?>
						<a href="#"><?php the_post_thumbnail('testimonial');?></a>
						<?php endif;?>
					</div>							
					<div class="detail">
						<h3><?php the_title();?></h3>
						<?php if(absint($show_date)):?>
						<div class="post-info-meta">
							<div class="entry-date"><?php echo $date;?></div>
						</div>
						<?php endif;?>
						<?php 
						$content = get_the_content();
						$content = wp_trim_words( strip_tags($content), $short_limit, $more = null );
						?>
						<?php if(absint($show_short)):?>
						<div class="testimonial-content"><?php echo $content;?></div>
						<?php endif;?>
					</div>
				</div>
			<?php 
				endif;
			} } else {
				echo __("Not available!", "wpdance");
			}
			?>
			</div></div></div>
			<?php if($wd_query_type == 'slider'):?>
			<script type='text/javascript'>
				//<![CDATA[
				jQuery(document).ready(function() {
					"use strict";
					var temp_visible = 1;
					
					var row = 1;
					var item_width = 1100;
					
					var show_nav = <?php if($show_nav): ?> true <?php else: ?> false <?php endif;?>;
					var prev,next,pagination;
					var show_icon_nav = <?php if($show_nav): ?> false <?php else: ?> true <?php endif;?>;
					
					var object_selector = ".<?php echo $testimonial_id;?>";
					/*generate_horizontal_slide(temp_visible,row,item_width,show_nav,show_icon_nav,object_selector);*/
					var _slider_datas =	{
						item 			: temp_visible
						,loop			: true
						,nav			: show_nav
						//,navText		: [ '<', '>' ]
						,dots			: show_icon_nav
						,lazyload		:true
						//,itemElement	:'section'
						,pagination 	: true
						,responsive		:{
							0:{
								items:1
							},
							480:{
								items:1
							},
							768:{
								items: 1
							},
							992:{
								items: 1
							},
							1200:{
								items:temp_visible
							}
						}
						,onInitialized: function(){
							jQuery(object_selector).parents('.wd-loading').addClass('wd-loaded').removeClass('wd-loading');
						}
					}
					var owl = jQuery(object_selector);
					owl.owlCarousel(_slider_datas);	
				});
				//]]>	
			</script>
			<?php endif;?>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			rewind_posts();
			wp_reset_query();
			return $output;
		}
	}
	add_shortcode('wd_testimonial','wd_testimonial_function');
?>