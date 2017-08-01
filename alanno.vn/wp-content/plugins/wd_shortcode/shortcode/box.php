<?php 
if(!function_exists ('box_function')){
	function box_function($atts,$content){
		extract(shortcode_atts(array(
			'style'			=>	'default',
		),$atts));
		$style = ' '.$style;
		$result = "<div class='wd-box{$style}'>".do_shortcode($content)."</div>";
		return $result;
	}
}
add_shortcode('box','box_function');
?>