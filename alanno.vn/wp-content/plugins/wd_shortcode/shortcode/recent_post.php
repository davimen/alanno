<?php 
if(!function_exists ('wd_recent_blogs_functions')){
	function wd_recent_blogs_functions($atts,$content = false){
		extract(shortcode_atts(array(
			'category'		=>	''
			,'columns'		=> 1
			,'number_posts'	=> 4
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
			,'show_nav' 		=> 1
			,'show_icon_nav' 	=> 1
		),$atts));

		wp_reset_query();	

		$args = array(
				'post_type' 			=> 'post'
				,'ignore_sticky_posts' 	=> 1
				,'showposts' 			=> $number_posts
		);	
		if( strlen($category) > 0 ){
			$args = array(
				'post_type' 			=> 'post'
				,'ignore_sticky_posts' 	=> 1
				,'showposts' 			=> $number_posts
				,'category_name' 		=> $category
			);	
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
		
		
		$num_count = count(query_posts($args));	
		if( have_posts() ) :
			$id_widget = 'recent-blogs-shortcode'.rand(0,1000);
			ob_start();
			
			if($show_type !== "widget"){
				$temp_class = " display-flex ";
					if($show_type == 'slider' && $num_count > $columns){
						$span_class = '';
						$temp_class = '';
					}
				echo '<div id="'. $id_widget .'" class="shortcode-recent-blogs '.$temp_class.$show_type.' columns-'.$columns.'">';
				if($show_type == 'slider' && $num_count > $columns){
					echo '<div class="blogs-list">';
				}
				$i = 0;
				while(have_posts()) {
					the_post();
					global $post;
					
					$_post_config = get_post_meta($post->ID,THEME_SLUG.'custom_post_config',true);
					if( strlen($_post_config) > 0 ){
						$_post_config = unserialize($_post_config);
					}
					?>
					<div class="item <?php echo $span_class ?><?php if( $i == 0 || $i % $columns == 0 ) echo ' first';?><?php if( $i == $num_count-1 || $i % $columns == $columns-1 ) echo ' last';?>">
					
						<div class="item-content">
							<div class="post-info-thumbnail <?php echo $temp_class; ?><?php if(!$thumbnail) echo "hidden-element"?>">
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
										if($show_type == 'list-posts') { $post_thumbnail_type = "blog_shortcode";
										} elseif($show_type == 'slider'){
											$post_thumbnail_type = 'wd_recent_blog_slider';
										} else {
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
				if($show_type == 'slider' && $num_count > $columns){
					echo '</div>';
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
				while(have_posts()) {the_post();global $post;
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
			
			<?php endif; ?>
			
			<?php if($show_type === 'slider'):?>
			<script type='text/javascript'>
				//<![CDATA[
					jQuery(document).ready(function() {
						"use strict";
						var temp_visible = <?php echo $columns;?>;
						
						var row = 1;

						var item_width = 180;
						
						var show_nav = <?php if($show_nav): ?> true <?php else: ?> false <?php endif;?>;

						var show_icon_nav = <?php if($show_icon_nav): ?> true <?php else: ?> false <?php endif;?>;
						
						var object_selector = "#<?php echo $id_widget?> .blogs-list";
						<?php if($num_count > $row * $columns) :?>	
							generate_blog_slide(temp_visible,row,item_width,show_nav,show_icon_nav,object_selector);
						<?php endif;?>
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
add_shortcode('wd_recent_blogs','wd_recent_blogs_functions');


if(!function_exists ('recent_portfolios_function')){
	function recent_portfolios_function($atts,$content = false){
		extract(shortcode_atts(array(
			'gallery'		=>	''
			,'columns'		=> 1
			,'number_posts'	=> 4
			,'title'		=> 'yes'
			,'thumbnail'	=> 'yes'
			,'meta'			=> 'yes'
			,'excerpt'		=> 'yes'
			,'excerpt_words'=> 10
		),$atts));

		wp_reset_query();	

		$args = array(
				'post_type' 		=> 'portfolio'
				,'showposts' 		=> $number_posts
		);	
		if( strlen($gallery) > 0 ){
			$args = array(
				'post_type' 		=> 'portfolio'
				,'showposts' 		=> $number_posts
				,'tax_query' => array(
					array(
						'taxonomy' => 'gallery'
						,'field' => 'slug'
						,'terms' => $gallery
					)
				)
			);	
		}		
		$title = strcmp('yes',$title) == 0 ? 1 : 0;
		$thumbnail = strcmp('yes',$thumbnail) == 0 ? 1 : 0;
		$meta = strcmp('yes',$meta) == 0 ? 1 : 0;
		$excerpt = strcmp('yes',$excerpt) == 0 ? 1 : 0;
		
		$span_class = "span".(12/$columns);
		
		$num_count = count(query_posts($args));	
		if( have_posts() ) :
			$id_widget = 'recent-portfolios-shortcode'.rand(0,1000);
			ob_start();
			echo '<ul id="'. $id_widget .'"class="shortcode-recent-portfolios columns-'.$columns.'">';
			$i = 0;
			while(have_posts()) {
				the_post();
				global $post;
				$post_title = get_the_title($post->ID);
				$post_url =  get_permalink($post->ID);
				$url_video = get_post_meta($post->ID,THEME_SLUG.'video_portfolio',true);
				$term_list = implode( ' ', wp_get_post_terms($post->ID, 'gallery', array("fields" => "slugs")) );		

				if( strlen( trim($url_video) ) > 0 ){
					if(strstr($url_video,'youtube.com') || strstr($url_video,'youtu.be')){
						$thumburl = array(get_thumbnail_video_src($url_video , 500 ,320));
						$item_class = "thumb-video youtube-fancy";
					}
					if(strstr($url_video,'vimeo.com')){
						$thumburl = array(wp_parse_thumbnail_vimeo(wp_parse_vimeo_link($url_video), 500, 320));	
						$item_class = "thumb-video vimeo-fancy";
					}
					$light_box_url = $url_video;
				}else{
					$thumb=get_post_thumbnail_id($post->ID);
					$thumburl=wp_get_attachment_image_src($thumb,'full');
					$item_class = "thumb-image";
					$light_box_url = $thumburl[0];
				}
									
				$portfolio_slider = get_post_meta($post->ID,THEME_SLUG.'_portfolio_slider',true);
				$portfolio_slider = unserialize($portfolio_slider);
				$slider_thumb = false;
				if( is_array($portfolio_slider) && count($portfolio_slider) > 0 ){
					$slider_thumb = true;
					$item_class = "thumb-slider";
				}

									
				?>
				<li class="item <?php echo $span_class ?><?php if( $i == 0 || $i % $columns == 0 ) echo ' first';?><?php if( $i == $num_count-1 || $i % $columns == $columns-1 ) echo ' last';?>">
					<div>
						<div class="image <?php if(!$thumbnail) echo "hidden-element"?>">
							<?php if($slider_thumb){?>
								<div class="portfolio-slider">
									<ul class="slides">
										<?php foreach( $portfolio_slider as $slide ){ ?>	
											<li><a href="<?php echo $slide['url'];?>"><img src="<?php echo print_thumbnail($slide['image_url'],true,$post_title,500,400,'',false,true); ?>" alt="<?php echo $slide['alt'];?>" title="<?php echo $slide['title'];?>" /></a></li>
										<?php } ?>
									</ul>	
								</div>				
							<?php }else{ ?>
								<?php 
									the_post_thumbnail('blog_shortcode',array('class' => 'thumbnail-effect-1'));
								?>
							<?php }?>
						</div>
						<div class="detail<?php if(!$thumb) echo ' noimage';?>">
							<p class="title <?php if(!$title) echo "hidden-element"?>"><h4 class="heading-title"><a href="<?php echo get_permalink($post->ID); ?>" class="wpt_title"  ><?php echo get_the_title($post->ID); ?></a></h4></p>
							<p class="excerpt <?php if(!$excerpt) echo "hidden-element"?>"><?php the_excerpt_max_words($excerpt_words); ?></p>
							<div class="meta <?php if(!$meta) echo "hidden-element"?>"><span class="author-time"><span class="author"><?php the_author(); ?></span><b>/</b><span class="time"><?php the_time('F d,Y'); ?></span><b>/</b><span class="comment-number"><?php comments_number( '0 Comments','1 Comment','% Comments'); ?></span></span></div>
						</div>	
					</div>
				</li>
		<?php
			$i++;
			}
			echo '</ul>';
			$ret_html = ob_get_contents();
			ob_end_clean();
		endif;
		wp_reset_query();
		return $ret_html;
	}
} 
//add_shortcode('recent_portfolios','recent_portfolios_function');



if(!function_exists ('recent_works_function')){
	function recent_works_function($atts,$content = false){
		extract(shortcode_atts(array(
			'count_items'	=>	'10'
			,'show_items' => 4
			,'gallery' => ''
		),$atts));

		wp_reset_query();	
		
			$args = array(
				'post_type' => 'portfolio'
				,'showposts' => $count_items

			);	
			
		if( strlen($gallery) > 0 ){
			$args = array(
				'post_type' => 'portfolio'
				,'showposts' => $count_items
				,'tax_query' => array(
					array(
						'taxonomy' => 'gallery'
						,'field' => 'slug'
						,'terms' => $gallery
					)
				)
			);
		}
		$num_count = count(query_posts($args));	
		if( have_posts() ) :
			$id_widget = 'recent-works-shortcode'.rand(0,1000);
			ob_start();
?>			
			<div id="<?php echo $id_widget;?>" class="shortcode-recent-works flexslider">
				<div class="slider-div">
					<ul class="slides">
					<?php			
						$i = 0;
						while(have_posts()) {
							the_post();
							global $post;
							?>
							<li class="item<?php if($i == 0) echo ' first';?><?php if(++$i == $num_count) echo ' last';?>">
								<?php
								$thumb_arr = get_thumbnail(300,170,'',get_the_title(),get_the_title());
								$thumb = $thumb_arr["fullpath"];
									$url_video = get_post_meta($post->ID,THEME_SLUG.'video_portfolio',true);
									if( strlen( trim($url_video) ) > 0 ){
										if(strstr($url_video,'youtube.com') || strstr($url_video,'youtu.be')){
											$thumburl = array(get_thumbnail_video_src($url_video , 500 ,500));
											$item_class = "thumb-video youtube-fancy";
										}
										if(strstr($url_video,'vimeo.com')){
											$thumburl = array(wp_parse_thumbnail_vimeo(wp_parse_vimeo_link($url_video), 500, 500));	
											$item_class = "thumb-video vimeo-fancy";
										}
										$light_box_url = $url_video;
									}else{
										$thumb=get_post_thumbnail_id($post->ID);
										$thumburl=wp_get_attachment_image_src($thumb,'full');
										$item_class = "thumb-image";
										$light_box_url = $thumburl[0];
									}								
								?>
								<div class="image <?php echo $id_widget?>">
									<a class="thumbnail" href="<?php the_permalink(); ?>"><?php echo print_thumbnail($thumburl[0],true,$post_title,500,330); ?></a>
									<div class="icons">
										<div>
											<a class="zoom-gallery fancybox" title="<?php echo get_the_title(); ?>" rel="fancybox" href="<?php echo $light_box_url;?>"></a>
											<a class="link-gallery " title="<?php echo get_the_title();?>" href="<?php echo get_permalink();?>"></a>
										</div>
									</div>	
								</div>
								<div class="detail<?php if(!$thumb) echo ' noimage';?>">
									<p class="title"><h4 class="heading-title"><a  class="wpt_title" href="<?php echo get_permalink($post->ID); ?>"><?php echo get_the_title($post->ID); ?></a></h4></p>
									<span class="author-time"><span class="author"><?php the_author(); ?></span><b>/</b><span class="time"><?php the_time('F d,Y'); ?></span><b>/</b><span class="comment-number"><?php comments_number( '0 Comments','1 Comment','% Comments'); ?></span></span>
								</div>							
							</li>
					<?php } ?>
					</ul>
				</div>
			</div>
			<div id="<?php echo $id_widget;?>_clone" style="display:none;" class="shortcode-recent-works flexslider">
			</div>
			
			<script type="text/javascript">
				jQuery(document).ready(function() {
					flex_carousel_slide('#<?php echo $id_widget;?>',<?php echo $show_items?>);
				});
			</script>
			<?php			
			$ret_html = ob_get_contents();
			ob_end_clean();
			//ob_end_flush();
		endif;
		wp_reset_query();
		return $ret_html;
	}
} 
//add_shortcode('recent_works','recent_works_function');

?>