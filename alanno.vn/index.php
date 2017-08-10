<?php
/**
 * Front to the WordPress application. This file doesn't do anything, but loads
 * wp-blog-header.php which does and tells WordPress to load the theme.
 *
 * @package WordPress
 */

/**
 * Tells WordPress to load the WordPress theme and output it.
 *
 * @var bool
 */

define("prefixTable","");
define("HOST","http://".$_SERVER["HTTP_HOST"]."/");
date_default_timezone_set('UTC');
/*
*******************************************************************************************************/
$URL_GOC = explode(".html",$_SERVER["REQUEST_URI"]);
if(count($URL_GOC)>0)
{
   $URL=explode("/",$URL_GOC[0]);
}else
{
  $URL=explode("/",$_SERVER["REQUEST_URI"]);
}
array_shift($URL);
$page_url=strtolower($URL[0]);

switch ($page_url) {
  case "signout":	
  case "checkout":            
  case "checkout-complete":
  case "login":
  case "register":
  case "account":
  case "change-password": 
  case "forgot-password":          
  case "custormer-checkout":
  case "custormer-checkout-step2":        
		include("index2.php");
		break;		
  default :
		define('WP_USE_THEMES', true);
		/** Loads the WordPress Environment and Template */
		require( dirname( __FILE__ ) . '/wp-blog-header.php' );
		break;
}