<?php
add_image_size('testimonial',100,100, true);
	if(!function_exists('testimonial')){
		function testimonial($atts,$content){
			extract(shortcode_atts(array(
				'style'		=> 'style1',
				'limit'		=> '20',
				'number_items'=>''
			),$atts));
			ob_start();
			?>
			<ul class="testimonial-contanner testimonial-<?php echo $style;?>">	
			<?php
			$about=new wp_query(array('post_type'=>'testimonials','posts_per_page'=>$number_items));
			if($about->have_posts()){
				while($about->have_posts()){
					$about->the_post();
					$count ++;
					global $post;
					?>
						<li class="estimonial-item <?php if($count==$about->post_count) echo " last";?>">
							<?php if($style=='style1'){?>							
							<div class="detail">
								<h3><?php the_title();?></h3>
								<div class="testimonial-content"><?php the_content();?></div>
								<div class="name">
										<a href="#"><?php echo get_post_meta($post->ID,THEME_SLUG.'nametestimonial',true);?></a>
								</div>	
							</div>						
							<?php }?>
							<?php  if($style=='style2'){?>							
							<div class="detail">
								<div class="name">
										<a href="#"><?php echo get_post_meta($post->ID,THEME_SLUG.'nametestimonial',true);?></a>
								</div>
								<div class="job">
										<a href="#"><?php echo get_post_meta($post->ID,THEME_SLUG.'jobtestimonial',true);?></a>
								</div>
								<div class="testimonial-content"><?php the_content();?></div>
							</div>						
							<?php }?>
							<?php if($style=='style3'){?>	
							<div class="avartar">
								<a href="#"><?php the_post_thumbnail('testimonial');?></a>
							</div>							
							<div class="detail">
								<h3><?php the_title();?></h3>
								<div class="testimonial-content"><?php the_content();?></div>
								<div class="name">
										<a href="#"><?php echo get_post_meta($post->ID,THEME_SLUG.'nametestimonial',true);?></a>
								</div>
							</div>						
							<?php }?>
						</li>
					<?php
				}
			}
			?>
			</ul>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			wp_reset_query();
			return $output;
		}
	}
	add_shortcode('testimonial','testimonial');
?>
