<?php 
if(!function_exists ('button')){
	function button($atts,$content){
		extract(shortcode_atts(array(
			'font_size'				=> 14
			,'color'				=> ''
			,'color_hover'			=> ''
			,'margin'     			=> ''
			,'padding'				=> ''
			,'link'					=> ''
			,'bg_color'				=> ''
			,'bg_color_hover'		=> ''
			,'opacity'				=> 100
			,'border_width'			=> ''
			,'border_color'			=> ''
			,'border_color_hover'	=> ''
			,'custom_class'			=> ''
		),$atts));

		$custom_class = (!empty($custom_class)) ? " {$custom_class}" : '';
		
		$classes = array();
		$classes[] = $custom_class;
		
		$data_styles_hover = '';
		$styles = ' style="';
		
		if( $font_size != '' ){
			$styles .= 'font-size:'. $font_size .(is_numeric($font_size)?'px':'').';';
			$data_styles_hover .= 'font-size:'. $font_size .(is_numeric($font_size)?'px':'').';';
		}
		
		if( $color != '' ){
			$styles .= 'color:'. $color .';';
			$data_styles_hover .= 'color:'. $color_hover .';';
		}
		
		if( $bg_color != '' ){
			$styles .= 'background-color:'. $bg_color .';';
			$data_styles_hover .= 'background-color:'. $bg_color_hover .';';
		}
		
		if ( $margin != '' ) {
			$styles .= 'margin:'. $margin .(is_numeric($margin)?'px':'') .';';
			$data_styles_hover .= 'margin:'. $margin .(is_numeric($margin)?'px':'') .';';
		}
		if( $padding != '' ){
			$styles .= 'padding:'. $padding .(is_numeric($padding)?'px':'').';';
			$data_styles_hover .= 'padding:'. $padding .(is_numeric($padding)?'px':'').';';
		}
		
		if( $border_width != ''	){
			$styles .= 'border-width:'. $border_width .(is_numeric($border_width)?'px':'').';';
			$data_styles_hover .= 'border-width:'. $border_width .(is_numeric($border_width)?'px':'').';';
		}
		
		if( $border_color != ''	){
			$styles .= 'border-color:'. $border_color .';';
			$data_styles_hover .= 'border-color:'. $border_color_hover .';';
		}
		
		if( !is_numeric($opacity) )
			$opacity = 100;
		$opacity = (int)$opacity;
		if( $opacity >= 0 && $opacity <= 100 ){
			$styles .= 'opacity:'. ($opacity/100) .';';
			$styles .= 'filter:alpha(opacity='. $opacity .');';
			$data_styles_hover .= 'opacity:'. ($opacity/100) .';';
			$data_styles_hover .= 'filter:alpha(opacity='. $opacity .');';
		}
		
		$styles .= '"';
		
		$classes = implode( ' ', $classes );
		
		if( strlen(trim($link)) > 0 ){
			return '<a data-hover="'.$data_styles_hover.'" class="wd-shortcode-button ' . esc_attr( $classes ) . '"'. $styles .' href="' . $link . '"' . '>'  . do_shortcode($content) . '</a>';
		}
		return '<button data-hover="'.$data_styles_hover.'" class="wd-shortcode-button '. esc_attr( $classes ) . '"'.$styles.'>'.do_shortcode($content).'</button>';	
	}
}
add_shortcode('wd_button','button');


if(!function_exists ('wd_button_group_function')){
	function wd_button_group_function($atts,$content){
		extract(shortcode_atts(array(
			'vertical' => 0
		),$atts));
		$_vertical = '';
		if( $vertical == 1 )
			$_vertical = " btn-group-vertical";
			
		return "<div class='btn-toolbar'><div class='btn-group{$_vertical}'>".do_shortcode($content)."</div></div>";
	}
}
add_shortcode('wd_button_group','wd_button_group_function');
?>