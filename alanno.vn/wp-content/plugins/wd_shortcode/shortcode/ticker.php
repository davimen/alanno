<?php 
if(!function_exists ('ticker_function')){
	function ticker_function($atts,$content){
		extract(shortcode_atts(array(
			'style'			=>	'left',
		),$atts));
		?>
		<div class="wd_sticker">
			<?php 
				wp_reset_query(); 
				ob_start();
			?>
			<?php $ticker=new wp_query(array('meta_key' => THEME_SLUG.'showbreakingnews','ignore_sticky_posts'=> 1, 'meta_value' => 1));?>
			<?php if($ticker->have_posts()){?>
				<ul id="js-news" class="js-hidden">
					<?php while($ticker->have_posts ()){$ticker->the_post();global $post;?>	
								<li class="news-item"><?php the_title(); ?> <a href="<?php the_permalink(); ?>"><?php _e('view more','wpdance'); ?></a></li>
					<?php }?>
				</ul>
			<?php 
				}
				$result = ob_get_contents();
				ob_end_clean();
			?>
			<?php wp_reset_query(); ?>
		</div>
		<?php
		return $result;
	}
}
add_shortcode('ticker','ticker_function');
?>