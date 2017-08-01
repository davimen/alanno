<?php 
// **********************************************************************// 
// ! Register New Element: Feedburner Subscription shortcode
// **********************************************************************//
if (!function_exists('wd_feedburner_shortcode')) {
	function wd_feedburner_shortcode($atts, $content = null) {
		
		$args = array(
            "feedburner"                => "wpdance",
            "intro"                     => "",
            "custom_color"              => "",
			"bg_color"          		=> "",
			"border_color"          	=> "#ffffff",
			"button_text"				=> "Send",
			'style'						=> 'style-1',
			'align'						=> 'text-left'
        );
        
        extract(shortcode_atts($args, $atts));
		
		$html = '';
		if(strlen(trim($intro)) > 0){
			$intro = '<div class="newsletter">'.$intro.'</div>';
		}
		if(strlen(trim($bg_color)) > 0 || strlen(trim($border_color)) > 0) {
			$form_style = 'style="';
			if(strlen(trim($bg_color)) > 0) $form_style .= 'background-color: '. $bg_color . ' !important;';
			if(strlen(trim($border_color)) > 0) $form_style .= 'border-color: '. $border_color . ' !important;';
			$form_style .= '"';
		}
		
		$html .='<div class="subscribe_widget ' .  esc_attr($style) . ' ' . esc_attr($align) . '">'.$intro.
				'<form '.$form_style.' action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onsubmit="window.open(\'http://feedburner.google.com/fb/a/mailverify?uri='.$feedburner.'\', \'popupwindow\', \'scrollbars=yes,width=550,height=520\');return true">
					<p class="subscribe-email"><span>'.__('newletter','wpdance').'<span>'.__('sign-up','wpdance').'</span></span><input type="text" name="email" class="subscribe_email" placeholder="'.__('enter your email address','wpdance').'" value=""/></p>
					<input type="hidden" value="'.$feedburner.'" name="uri"/>
					<input type="hidden" value="'.get_bloginfo( 'name' ).'" name="title"/>
					<input type="hidden" name="loc" value="en_US"/>';
				
		$button_out = '';
		if(strlen(trim($custom_color)) > 0){
			$button_out = 'style="';
			
			$button_out .= 'color:'.$custom_color.';';

			$button_out .= '"';

		}
		$html .= '<button class="btn" '.$button_out.' type="submit" title="submit">'.$button_text.'</button>';		
		$html .='</form></div>';
		
		return $html;
	}
}
add_shortcode('wd_feedburner', 'wd_feedburner_shortcode');
?>