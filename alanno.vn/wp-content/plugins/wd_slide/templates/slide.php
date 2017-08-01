<?php
if(!function_exists ('wd_slide_function')){
	function wd_slide_function($atts){
		extract(shortcode_atts(array(
			'id'    		 => -1

		),$atts));
		if ( !post_type_exists( 'slide' ) ) {
			return;
		}
		return wd_show_fredsel_slider($id);
	}
}
add_shortcode('slider','wd_slide_function');
	
if(!function_exists ('wd_show_fredsel_slider')){
	function wd_show_fredsel_slider($post_id){
		if( (int)$post_id > 0 ){
			$slider_datas = get_post_meta($post_id,'wd_portfolio_slider',true);
			$slider_datas = unserialize($slider_datas);
			$slider_configs = get_post_meta($post_id,'wd_slide_config',true);
			$slider_configs = wd_array_atts(array(
															"portfolio_slider_config_autoslide" => 1
															,"portfolio_slider_config_size" => 'slider'
														),unserialize($slider_configs));	

			
			$_custom_size = $slider_configs['portfolio_slider_config_size'];
			$_width = 208;
										
			switch ($_custom_size) {
				case 'slideshow':
					$_width = 960;
					break;
				case 'slider':
					$_width = 222;
					break;
				case 'blog_thumb':
					$_width = 280;
					break;
				case 'prod_midium_thumb_1':
					$_width = 510;
					break;
				case 'prod_midium_thumb_2':
					$_width = 366;
					break;
				case 'prod_small_thumb':
					$_width = 141;
					break;
				case 'prod_tini_thumb':
					$_width = 75;
					break;
				case 'slider_thumb_wide':
					$_width = 150;
					break;
				case 'slider_thumb_box':
					$_width = 100;
					break;
				case 'related_thumb':
					$_width = 190;
					break;					
			}							
			if( is_array($slider_datas) && count($slider_datas) > 0 ){
				$_random_id = "fredsel_" . $post_id . "_" . rand();
				ob_start();
				?>
				<div class="featured_product_slider_wrapper shortcode_slider" id="<?php echo $_random_id;?>">
					<div class="fredsel_slider_wrapper_inner">
						<ul>
							<?php
								foreach( $slider_datas as $_slider ){
							?>	
								<li>
									<a href="<?php echo $_slider['url'];?>" title="<?php echo $_slider['slide_title'];?>">
										<?php echo wp_get_attachment_image( $_slider['thumb_id'], $_custom_size , false, array('title' => $_slider['title'], 'alt' => $_slider['title']) ); ?>
									</a>
								</li>
							<?php
								}
							?>						
						</ul>
						<div class="slider_control">
							<a id="<?php echo $_random_id;?>_prev" class="prev" href="#">&lt;</a>
							<a id="<?php echo $_random_id;?>_next" class="next" href="#">&gt;</a>
						</div>
					</div>
				</div>
				<script type='text/javascript'>
				//<![CDATA[
					jQuery(document).ready(function() {
						// Using custom configuration
						jQuery("#<?php echo $_random_id?> > .fredsel_slider_wrapper_inner > ul").carouFredSel({
							items 				: {
								width: <?php echo $_width;?>
								,height: 'auto'<?php //echo $slider_configs['portfolio_slider_config_height'];?>
								,visible: {
									min: 1
									,max: 5
								}							
							}
							,direction			: "left"
							,responsive 		: true	
							,swipe				: { onMouse: false, onTouch: true }		
							,scroll				: { items : 1,
													duration : 1000
													, pauseOnHover:true
													, easing : "easeInOutCirc"}
							,width				: '100%'
							,height				: '100%'<?php //echo $slider_configs['portfolio_slider_config_height'];?>
							,circular			: true
							,infinite			: true
							,auto				: <?php echo (int)$slider_configs['portfolio_slider_config_autoslide'] == 1 ? "true" : "false";?>
							,prev				: '#<?php echo $_random_id;?>_prev'
							,next				: '#<?php echo $_random_id;?>_next'								
							,pagination 		: '#<?php echo $_random_id;?>_pager'						
						});	
					});
					//]]>
				</script>
				<?php	
				return ob_get_clean();
			}
		}		
	}
}
?>