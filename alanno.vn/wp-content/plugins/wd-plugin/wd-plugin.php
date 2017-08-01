<?php 
/**
 * Plugin Name: WpDance plugin
 * Plugin URI: http://wpdance.com
 * Description: WpDance plugin use only on WpDance Theme.
 * Version: 1.1
 * Author: WpDance Team
 * Author URI: http://wpdance.com/
 * License: GPLv2
 */

// Exit if accessed directly
if( !defined( 'ABSPATH' ) ) {
    die;
} 

define('WD_INC', 'incs/');
define('WD_CLASSES', 'incs/classes');
define('WD_JS', 'assests/js/');
define('WD_CSS', 'assests/css/');
define('WD_TEMP_P', 'wd-plugin/templates/');
define('WD_TEMP_PATH',  plugin_dir_path( __FILE__ ) . 'templates/');
define('WD_INC_PATH', plugin_dir_path( __FILE__ ) . 'incs/');
define('WD_CSS_PATH', plugin_dir_path( __FILE__ ) . 'assests/css/');
define('WD_URL_ASSESTS', plugins_url('assests/', __FILE__) );
define('WD_AS_SLUG', 'wd_as_options');
define('WDNTF_SLUG', 'wd_notify_option');
define('WD_PL_SLUG', 'wd_plugin_option');
define('WD_PP_SLUG', 'wd_pp_option');

if( get_option( WD_PL_SLUG ) ) {
	$wp_pl_option = json_decode(get_option( WD_PL_SLUG ));
} else {
	$wp_pl_option = (object) Array();
	$wp_pl_option->{WD_AS_SLUG} = 1;
	$wp_pl_option->{WDNTF_SLUG} = 1;
	$wp_pl_option->{WD_PP_SLUG} = 1;
}

if( is_admin() ):
	global $WD_AD;
	include_once plugin_basename( 'incs/admin.php' );
	$WD_AD = new WD_Admin();
	
	if ( ! function_exists( 'is_plugin_active' ) ) {
		require_once ABSPATH . 'wp-admin/includes/plugin.php';
	}
	
	$woo_active = is_plugin_active("woocommerce/woocommerce.php");
	
	if( !empty($wp_pl_option->{WDNTF_SLUG}) && absint( $wp_pl_option->{WDNTF_SLUG} ) && $woo_active )
		include_once plugin_basename( 'incs/admin-notify.php' );
	
	if( !empty($wp_pl_option->{WD_AS_SLUG}) && absint( $wp_pl_option->{WD_AS_SLUG} ) )
		include_once plugin_basename( 'incs/admin-search.php' );
		
	if( !empty($wp_pl_option->{WD_PP_SLUG}) && absint( $wp_pl_option->{WD_PP_SLUG} ) ) {
		
		include_once plugin_basename( 'incs/admin-popup.php' );
	}
	
	/*register_deactivation_hook( __FILE__, 'deactive_wd_as' );*/
	/*register_uninstall_hook( __FILE__, 'deactive_wd_plugin' );*/
	
	function deactive_wd_plugin(){
		if( get_option( WDNTF_SLUG ) ) delete_option( WDNTF_SLUG );
		if( get_option( WD_AS_SLUG ) ) delete_option( WD_AS_SLUG );
		if( get_option( WD_PL_SLUG ) ) delete_option( WD_PL_SLUG );
	}
	
else:
	if( !empty($wp_pl_option->{WD_AS_SLUG}) && absint( $wp_pl_option->{WD_AS_SLUG} ) ) include_once plugin_basename( 'incs/admin-search.php' );

	if( !empty($wp_pl_option->{WD_PP_SLUG}) && absint( $wp_pl_option->{WD_PP_SLUG} ) ) include_once plugin_basename( 'incs/wd-popup.php' );
	
endif;




?>