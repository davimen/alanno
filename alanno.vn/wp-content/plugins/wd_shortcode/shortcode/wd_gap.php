<?php
if(!function_exists('wd_gap_shortcode')) {
    function wd_gap_shortcode($atts, $content = null) {
		extract( shortcode_atts( array(
		'height' => 10,
		), $atts ) );
		
		$output = '<div class="gap" style="line-height: ' . absint($height) . 'px; height: ' . absint($height) . 'px;"></div>';
		
		return $output;
    }
}
add_shortcode('wd_gap', 'wd_gap_shortcode');
?>