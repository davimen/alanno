<?php 
	if(!function_exists('wd_features_function')){
		function wd_features_function($atts,$content){
			extract(shortcode_atts(array(
				'slug'				=>		''
				,'id'				=>		0
				,'limit'			=> 6
				,'wd_query_type'	=> 'simple' 
				,'style'			=> 'style-1'
				,'icon_image'		=> ''
				,'icon_color'		=> ''
				,'text_color'		=> ''
				,'show_icon'		=> 1
				,'show_title'			=>	1
				,'show_excerpt'			=>	1
				,'excerpt_words'	=> 10
			),$atts));
			
			$_actived = apply_filters( 'active_plugins', get_option( 'active_plugins' )  );
			if ( !in_array( "features-by-woothemes/woothemes-features.php", $_actived ) ) {
				return;
			}
			$show_icon 	= absint($show_icon);
			$show_title 	= absint($show_title);
			$show_excerpt 	= absint($show_excerpt);
			
			global $post;
			if($wd_query_type == 'simple'){
				if( absint($id) > 0 ){
					$_feature = woothemes_get_features( array('id' => $id, 'size' => 'feature-thumbnail' ));
				}elseif( strlen(trim($slug)) > 0 ){
					$_feature = get_page_by_path($slug, OBJECT, 'feature');
					if( !is_null($_feature) ){
						$_feature = woothemes_get_features( array('id' => $_feature->ID,'size' => 'feature-thumbnail' ));
					}else{
						return;
					}
				}else{
					$_feature = woothemes_get_features( array('limit' => $limit,'size' => 'feature-thumbnail' ));
				}
			} else {
				$_feature = woothemes_get_features( array('limit' => $limit,'size' => 'feature-thumbnail' ));
			}
			//nothing found
			if( !is_array($_feature) && count($_feature) <= 0 ){
				return;
			}else{
				//global $post;
				//$_feature = $_feature[0];
				//$post = $_feature;
				//setup_postdata( $post ); 
			}
			
			//handle features
			
			ob_start();
			$feature_id = "wd_feature_".rand();
			?>
			<?php $loading = ($wd_query_type == 'slider')? 'wd-loading': '' ?>
			<?php $text_color_style = (trim($text_color)!== '')? ' style="color: '.$text_color.'"' : '' ?>
			<div class="wd-feature-slider <?php echo esc_attr($loading); ?>">
				<div class="row">
					<div class="wd_shortcode_feature <?php echo esc_attr($feature_id);?>">
			<?php 
				if(count($_feature) <= 1) {
					global $post;
					$_feature = $_feature[0];
					$post = $_feature;
					setup_postdata( $post );
					?>
					<div id="post-<?php the_ID(); ?>" <?php post_class('shortcode')?>>
						<div class="container">
							<div class="feature_content_wrapper text-center <?php echo esc_attr($style)?>">	
							<?php if($show_icon) :?>
								<div class="feature_icon">
									<?php $icon_style = (trim($icon_color)!== '')? ' style="color: '.$icon_color.'"' : '' ?>
									<a class="text_color" href="<?php echo esc_url($_feature->url);?>">
										<span class="<?php echo $icon_image ?>" <?php echo $icon_style;?>></span>
									</a>
								</div>
							<?php endif;?>
							
							<?php if($show_title):?>
								<h2 class="feature_title heading_title bold-upper-big">
									<strong><a href="<?php echo esc_url($_feature->url);?>" <?php echo $text_color_style;?>><?php the_title(); ?></a></strong>
								</h2>	
								<div class="line line-30 line-margin"></div>
							<?php endif;?>
							
							<?php if($show_excerpt) :?>
								
								<div class="feature_excerpt text-strong" <?php echo $text_color_style;?>>
									<?php the_excerpt_max_words(absint($excerpt_words)); ?>
								</div>
							<?php endif;?>
							</div>
						</div>
					</div>
					<?php
				}else {
					foreach( $_feature as $feature ){
						$post = $feature;
						setup_postdata( $post );
						?>
						<div class="feature-item feature">
							<div class="avartar">
								<a title="<?php the_title();?>" href="<?php the_permalink() ?>"><?php 
								if(has_post_thumbnail()) the_post_thumbnail('blog_shortcode');
								else {
								?>
								<img alt="no image" src="<?php echo get_template_directory_uri()?>/images/no-image.jpg" />
								<?php
								}
								?></a>
							</div>							
							<div class="detail">
								<h3><?php the_title();?></h3>
								<?php if($show_excerpt) :?>
									<div class="feature_excerpt text-strong">
										<?php the_excerpt_max_words(absint($excerpt_words)); ?>
									</div>
								<?php endif;?>
							</div>
						</div>
						
						<?php
					}
				}
				
			?>
				
					</div>
				</div>
			</div>
			<?php if(count($_feature) > 1):?>
			<script type='text/javascript'>
							//<![CDATA[
							jQuery(document).ready(function() {
								"use strict";
								var temp_visible = 4;
								
								var row = 1;
								var item_width = 450;
								
								var show_nav = true;
								var prev,next,pagination;
								var show_icon_nav = false;
								
								var object_selector = ".<?php echo $feature_id;?>";

                                var autoplay = false;
                                generate_horizontal_slide(temp_visible,row,item_width,show_nav,show_icon_nav,autoplay,object_selector);
									
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
	add_shortcode('wd_feature','wd_features_function');
?>