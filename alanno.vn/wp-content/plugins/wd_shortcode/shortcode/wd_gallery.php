<?php 
	if(!function_exists('wd_gallery_function')){
		function wd_gallery_function($atts,$content){
			extract(shortcode_atts(array(
				'images'	=> '',
				'size'		=> '',
				'columns'	=> ''
			),$atts));
			
			
			
			if ($images!==''){
				$imageIDs = explode(',', trim($images));
				//$cover_url = wp_get_attachment_url($cover_url);
			}
			$size_args = array();
			if ($size!==''){
				$size_args = explode(',', trim($size));
			}
			if ($columns!==''){
				$col_args = explode(',', trim($columns));
			}
			
			
			ob_start();
			$gl_shortcode_id = 'wd-'.rand(1000,99999);
			?>
			<div class="wd_gallery_shortcode">
			<?php 
			foreach($imageIDs as $k => $imgID) {
				$image_big_url = wp_get_attachment_url($imgID);
				$img_size = isset($size_args[$k])? 'wd_gallery_'.$size_args[$k]: 'wd_gallery_3';
				$image = wp_get_attachment_image($imgID, $img_size, false);
				$col = isset($col_args[$k])? absint($col_args[$k]): 6;
				$class  = "col-sm-" . $col;
				$class .= " prettyphoto";
				//print_r($image);
			?>
				<a href="<?php echo esc_url($image_big_url);?>" rel="prettyPhoto[<?php echo esc_attr($gl_shortcode_id);?>]" class="<?php echo esc_attr($class);?>" title="<?php echo $img_size;?>" >
					<?php echo $image;?>
				</a>
			<?php 
			}
			?>
			</div><!--.wd_gallery_shortcode-->
			<?php
			
			$output = ob_get_contents();
			ob_end_clean();
			//wp_reset_query();
			return $output;
		}
	}
	add_shortcode('wd_gallery','wd_gallery_function');
?>