<?php

// **********************************************************************// 
// ! Register New Element: Pricing Table
// **********************************************************************//
if (!function_exists('wd_ptable_shortcode')) {
	function wd_ptable_shortcode($atts, $content = null) {
        $args = array(
            "title"         => "",
            "price"         => "0",
            "currency"      => "$",
            "price_period"  => "/ month",
            "link"          => "",
            "target"        => "",
            "button_text"   => "Buy Now",
            "active"        => ""
        );
	        
		extract(shortcode_atts($args, $atts));
	        
	    $html = ""; 
	        
        if($target == ""){
                $target = "_self";
        }
        $html .= "<div class='wd_price_table'>";
        
        if($active == "yes"){
            $html .= "<div class='price_table_inner acitve_price'>";
        } else {
            $html .= "<div class='price_table_inner'>";
        }

        $html .= "<ul>";
		$html .= "<li class='cell table_title'><h1>".$title."</h1></li>";
        $html .= "<li class='prices'>";
        $html .= "<span class='price_in_table'>";
        $html .= "<sup class='value'>".$currency."</sup>";
        $html .= "<span class='pricing'>".$price."</span>";
        $html .= "<sub class='mark'>".$price_period."</sub>";
        $html .= "</span>";
        $html .= "</li>"; //close price li wrapper
	    
	    $html .= "<li><p>". $content. "</p></li>"; //append pricing table content 
	    
	    $html .="<li class='price_button'>";
	    $html .= "<a class='button normal' href='$link' target='$target'>".$button_text."</a>";
	    $html .= "</li>"; //close button li wrapper
	    
	    $html .= "</ul>";
	    $html .= "</div>"; //close price_table_inner
	    $html .="</div>"; //close price_table
	    
	    return $html;
	}
}
add_shortcode('wd_ptable', 'wd_ptable_shortcode');

?>