<?php 
if(!function_exists ('align_function')){
	function align_function($atts,$content){
		extract(shortcode_atts(array(
			'style'			=>	'left',
		),$atts));
		$result = "<div class='wd-align-div align-{$style}'>".do_shortcode($content)."</div>";
		return $result;
	}
}
add_shortcode('align','align_function');
?>