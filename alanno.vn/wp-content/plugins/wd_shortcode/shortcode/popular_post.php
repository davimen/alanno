<?php 
if(!function_exists ('wd_meta_blogs_functions')){
	function wd_meta_blogs_functions($atts,$content = false){
		extract(shortcode_atts(array(
			'category'		=>	''
			,'columns'		=> 1
			,'number_posts'	=> 4
			,'cus_type'		=> 'popular'
			,'show_type' 	=> 'list-posts'
			,'show_type_isotope' => 1
			,'title'		=> 'yes'
			,'thumbnail'	=> 'yes'
			,'meta'			=> 'yes'
			,'excerpt'		=> 'yes'
			,'read_more'	=> 'yes'
			,'view_more'	=> 'yes'
			,'thumb_auto'	=> 'no'
			,'view_more_link'	=> ''
			,'excerpt_words'=> 10
		),$atts));

		wp_reset_query();	

		$args = array(
				'post_type' 			=> 'post',
				'ignore_sticky_posts' 	=> 1,
				'showposts' 			=> $number_posts,
				'meta_key'				=> 'wd_total_views',
				'orderby'				=> array(
					'meta_value_num'	=> 'DESC',
					'title' 			=> 'ASC'
				),
		);
		if( strcmp( $cus_type, 'popular' ) == 0 ) {
			$args['meta_key'] = 'wd_total_views';
			$args['orderby'] = array(
								'meta_value_num'	=> 'DESC',
								'title' 			=> 'ASC',
								);
		} else {
			$args['meta_query'] = array(
				array(
					'key'		=> 'wd_post_recommended',
					'value'		=> '1',
					'compare'	=> '=',
				),
			);
			
		}
		
		if( strlen($category) > 0 ){	
			$args['category_name'] = $category;
		}
		
		
		$title 		= strcmp('yes',$title) == 0 ? 1 : 0;
		$show_type_isotope 	= strcmp('yes',$show_type_isotope) == 0 ? 1 : 0;
		$thumbnail 	= strcmp('yes',$thumbnail) == 0 ? 1 : 0;
		$meta 		= strcmp('yes',$meta) == 0 ? 1 : 0;
		$excerpt 	= strcmp('yes',$excerpt) == 0 ? 1 : 0;
		$read_more 	= strcmp('yes',$read_more) == 0 ? 1 : 0;
		$view_more 	= strcmp('yes',$view_more) == 0 ? 1 : 0;
		
		//$span_class = "col-sm-".(24/$columns);
		
		$span_class = "col-lg-".(24/$columns);
		$span_class .= ' col-md-'.(24/round( $columns * 992 / 992));
		$span_class .= ' col-sm-'.(24/round( $columns * 768 / 992));
		$span_class .= ' col-xs-24';
		$span_class .= ' col-mb-24';
		
		$res = new WP_Query( $args );
		$num_count = count( $res );	
		if( $res->have_posts() ) :
			$id_widget = 'recent-blogs-shortcode'.rand(0,1000);
			ob_start();
			
			if($show_type !== "widget"){
				echo '<div id="'. $id_widget .'" class="shortcode-recent-blogs display-flex '.$show_type.' columns-'.$columns.'">';
				$i = 0;
				while($res->have_posts()) {
					$res->the_post();
					global $post;
					
					$_post_config = get_post_meta($post->ID,THEME_SLUG.'custom_post_config',true);
					if( strlen($_post_config) > 0 ){
						$_post_config = unserialize($_post_config);
					}
					
					?>
					<div class="item <?php echo $span_class ?><?php if( $i == 0 || $i % $columns == 0 ) echo ' first';?><?php if( $i == $num_count-1 || $i % $columns == $columns-1 ) echo ' last';?>">
					
						<div class="item-content">
							<div class="post-info-thumbnail display-flex <?php if(!$thumbnail) echo "hidden-element"?>">
								<div class="post-icon-box">
									<?php if(isset($_post_config['post_type'])):?>
									<?php 
										switch($_post_config['post_type']){
											case 'video':
												?><div class="sticky-post video"><i class="fa fa-film"></i></div><?php 
												break;
											case 'audio':
												?><div class="sticky-post audio"><i class="fa fa-play-circle-o"></i></div><?php 
												break;
											case 'shortcode':
												?><div class="sticky-post shortcode"><i class="fa fa-cog"></i></div><?php 
												break;
										}
									?>
									<?php endif;?>
								</div>
								
								<a class="thumbnail effect_color" href="<?php the_permalink(); ?>">
									<?php 
										if($show_type == 'list-posts') $post_thumbnail_type = "blog_shortcode";
										else {
											$post_thumbnail_type = strcmp(trim($thumb_auto),'yes') == 0? "blog_shortcode_auto": "blog_shortcode";
										}
										the_post_thumbnail( $post_thumbnail_type,array('class' => 'thumbnail-effect-1') );
									?>
									<!--div class="effect_hover_image"></div-->
								</a>
							</div>
							<div class="meta-post post-info-content <?php if(!$thumbnail) echo ' noimage';?>">
								<h3 class="heading-title <?php if(!$title) echo 'hidden-element'; ?>"><a href="<?php echo get_permalink($post->ID); ?>" class="wpt_title" title="<?php echo esc_attr(get_the_title($post->ID));?>" ><?php echo get_the_title($post->ID); ?></a></h3>
								<div class="post-info-meta-top post-info-meta">
									<div class="entry-date"><?php echo get_the_date('F d, Y') ?></div>
									<div class="comments-count"><?php $comments_count = wp_count_comments($post->ID);
							if(absint($comments_count->approved) == 0) echo $comments_count->approved . ' ' . __('Comment', 'wpdance');
							else echo $comments_count->approved . ' ' . _n( "Comment", "Comments", absint($comments_count->approved), 'wpdance');?></div>
								</div>
								
								<p class="excerpt <?php if(!$excerpt) echo 'hidden-element'; ?>"><?php the_excerpt_max_words($excerpt_words); ?></p>
								
								<?php if($read_more):?>
									<a class="button" href="<?php the_permalink(); ?>"><?php _e("Read more", "wpdance");?></a>
								<?php endif;?>
								
							</div>	
						</div>
					</div>
					
					
			<?php
					$i++;
					
				}
				echo '</div>';
				?>
				<?php 
				
				/*if( get_option( 'show_on_front' ) == 'page' ) $blog_url = get_permalink( get_option('page_for_posts' ) );
				else $blog_url = bloginfo('url');*/

				?>
				
				<?php if($view_more && $view_more_link!==''):?>
					<p style="text-align:center"><a class="button" href="<?php echo esc_url($view_more_link);?>"><?php esc_attr_e("View more post", "wpdance");?></a></p>
				<?php endif;?>
				
				<?php 
				
			} else {
				echo '<ul class="shortcode-recent-blogs widget" id="'.$id_widget.'">';
				$i = 0;
				while( $res->have_posts()) {$res->the_post();global $post;
					?>
					<li class="item<?php if($i == 0) echo ' first';?><?php if(++$i == $num_count) echo ' last';?>">
						<div class="media">
							<?php if($thumbnail):?>
							<div class="wd_post_thumbnail">
								<a class="thumbnail effect_color effect_color_1" href="<?php the_permalink(); ?>">
									<?php if(has_post_thumbnail()): ?>
									<?php echo get_the_post_thumbnail( $post->ID, 'blog_recent');?>
									<?php else:?>
										<img alt="<?php the_title(); ?>" height="98" width="240" title="<?php the_title();?>" src="<?php echo get_template_directory_uri(); ?>/images/no-image-blog.gif"/>
									<?php endif; ?>
								</a>
							</div>
							<?php endif;?>
							<div class="detail">
								<?php if($title):?>
								<div class="entry-title">
									<a href="<?php the_permalink(); ?>" title="<?php printf( esc_attr__( 'Permalink to %s', 'wpdance' ), the_title_attribute( 'echo=0' ) ); ?>" rel="bookmark">
										<?php echo esc_attr(get_the_title()); ?>
									</a>
								</div>
								<?php endif;?>
								<?php if($meta):?>
								<p class="entry-meta">
									<?php echo get_the_date('F d, Y') ?>
								</p>
								<?php endif;?>
							</div><!-- .detail -->
						</div>
					</li>
					
					<?php
				}
				echo '</ul>';
			}
			?>
			<?php if($show_type === 'grid-posts' && $show_type_isotope):?>
			<script type='text/javascript'>
			//<![CDATA[
			jQuery(document).ready(function() {
				"use strict";
				jQuery('#<?php echo $id_widget;?>').isotope({
					itemSelector: '.item',
				});
				
				jQuery('#<?php echo $id_widget;?> img').on('load',function(){
					jQuery('#<?php echo $id_widget;?>').isotope({
						itemSelector: '.item',
					});
				});
			});
			//]]>	
			</script>
			
			<?php
			endif;
			$ret_html = ob_get_contents();
			ob_end_clean();
			//ob_end_flush();
		endif;
		wp_reset_query();
		return $ret_html;
	}
} 
add_shortcode('wd_meta_blogs','wd_meta_blogs_functions');





?>