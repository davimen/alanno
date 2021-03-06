<?php
/*
  Plugin Name: WD Team
  Plugin URI: http://www.wpdance.com
  Description: Team From WPDance Team
  Version: 1.0.0
  Author: WD Team
  Author URI: http://www.wpdance.com
 */
class WD_Team {

	


	public function __construct(){
		$this->constant();
		
		/****************************/
		// Register Team post type
		//add_action('init', array($this,'wd_slide_register') );
		$this->wd_team_register();
		add_theme_support('post-thumbnails', array('team'));
		
		register_activation_hook(__FILE__, array($this,'wd_team_activate') );
		register_deactivation_hook(__FILE__, array($this,'wd_team_deactivate') );

	
		
		
		add_action('admin_enqueue_scripts',array($this,'init_admin_script'));
		
		add_action('admin_menu', array( $this,'wd_team_create_section' ) );	
		
		add_filter('attribute_escape', array($this,'rename_second_menu_name') , 10, 2);
		
		add_action('save_post', array($this,'wd_team_save_data') , 1, 2);
		
		add_action( 'template_redirect', array($this,'wd_team_template_redirect') );
		
		
		
		$this->init_trigger();
		$this->init_handle();
	}
	
	/******************************** Team POST TYPE INIT START ***********************************/

	public function wd_team_save_data($post_id, $post) {

		if ( ! isset( $_POST['wd_team_box_nonce'] ) )
				return $post_id;
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if (!wp_verify_nonce($_POST['wd_team_box_nonce'],'wd_team_box'))
			return $post->ID;

		if ($post->post_type == 'revision')
			return; //don't store custom data twice

		if (!current_user_can('edit_post', $post->ID))
			return $post->ID;

		// OK, we're authenticated: we need to find and save the data
		// Sanitize the user input.
		if('team' == $_POST['post_type']){
			if(isset($_POST['member_role']))
				update_post_meta($post_id,'wd_member_role',$_POST['member_role']);
			if(isset($_POST['member_email']))
				update_post_meta($post_id,'wd_member_email',$_POST['member_email']);
			if(isset($_POST['member_phone']))
				update_post_meta($post_id,'wd_member_phone',$_POST['member_phone']);
			if(isset($_POST['member_link']))
				update_post_meta($post_id,'wd_member_link',$_POST['member_link']);
			//if(isset($_POST['member_social']))
				//update_post_meta($post_id,'wd_member_social',$_POST['member_social']);
			if(isset($_POST['member_facebook_link']))
				update_post_meta($post_id,'wd_member_facebook_link',$_POST['member_facebook_link']);
			if(isset($_POST['member_twitter_link']))
				update_post_meta($post_id,'wd_member_twitter_link',$_POST['member_twitter_link']);
			if(isset($_POST['member_rss_link']))
				update_post_meta($post_id,'wd_member_rss_link',$_POST['member_rss_link']);
			if(isset($_POST['member_google_link']))
				update_post_meta($post_id,'wd_member_google_link',$_POST['member_google_link']);
			if(isset($_POST['member_linkedlin_link']))
				update_post_meta($post_id,'wd_member_linkedlin_link',$_POST['member_linkedlin_link']);
			if(isset($_POST['member_dribble_link']))
				update_post_meta($post_id,'wd_member_dribble_link',$_POST['member_dribble_link']);		
		}
		
	}	
		
	
	public function wd_team_register() {
		 require_once WDT_TYPES."/team.php";
	}	
	
	
	/******************************** Team POST TYPE INIT END *************************************/
	
	public function wd_team_template_redirect(){
		global $wp_query,$post,$page_datas,$data;
		if( $wp_query->is_page() || $wp_query->is_single() ){
			if ( has_shortcode( $post->post_content, 'team_member' ) ) { 
				add_action('wp_enqueue_scripts',array($this,'init_script'));
			}
		}
		
	}
	
	public function wd_team_create_section() {
		if(post_type_exists('team')) {
			add_meta_box("wp_cp_custom_carousels", "Member Information", array($this,"show_team"), "team", "normal", "high");
		}
	}

	public function show_team(){
		require_once WDT_INCLUDES.'/team.php';
	}
	
	public function wd_team_deactivate() {
		flush_rewrite_rules();
	}

	public function wd_team_activate() {
		$this->wd_team_register();
		flush_rewrite_rules();
	}		
	
	public function rename_second_menu_name($safe_text, $text) {
		if (__('Team Items', 'WD_team_context') !== $text) {
			return $safe_text;
		}

		// We are on the main menu item now. The filter is not needed anymore.
		remove_filter('attribute_escape', array($this,'rename_second_menu_name') );

		return __('WD Team', 'wd_team_context');
	}
		
	protected function init_trigger(){
	
	}
	protected function init_handle(){
		//add_shortcode('wd-team', array( $this,'wd_Slide') );
		add_image_size('wd_team_thumb',400,400,true);  
		require_once WDT_TEMPLATE . "/team_member.php";
	}	
	
	public function init_admin_script() {
		if (function_exists('wp_enqueue_media')) {
			wp_register_script('admin_media_lib_35', WDP_JS . '/admin-media-lib-35.js', 'jquery', false,false);
			wp_enqueue_script('admin_media_lib_35');
		} else {
			wp_enqueue_style('thickbox');
			wp_enqueue_script('media-upload');
			wp_enqueue_script('thickbox');
			wp_register_script('admin_media_lib', WDP_JS . '/admin-media-lib.js', 'jquery', false,false);
			wp_enqueue_script('admin_media_lib');
		}	
	}	
	
	
	public function init_script(){
		wp_enqueue_script('jquery');
		
		wp_register_style( 'bootstrap', WDT_CSS.'/bootstrap.css');
		wp_enqueue_style('bootstrap');
		
		wp_register_style( 'bootstrap-style', WDT_CSS.'/bootstrap-style.css');
		wp_enqueue_style('bootstrap-style');
		
		wp_register_style( 'bootstrap-style', WDT_CSS.'/bootstrap-style.css');
		wp_enqueue_style('bootstrap-style');
		
		wp_register_style( 'bootstrap-ie8-buttonfix', WDT_CSS.'/bootstrap-ie8-buttonfix.css');
		wp_enqueue_style('bootstrap-ie8-buttonfix');
		
		wp_register_script( 'bootstrap', WDT_JS.'/bootstrap.js');
		wp_enqueue_script('bootstrap');
		
		
		wp_register_script( 'jquery.carouFredSel', WDT_JS.'/jquery.carouFredSel-6.2.1.min.js',false,false,true);
		wp_enqueue_script('jquery.carouFredSel');	
		
		//wp_register_script( 'wd.team.js', WDP_JS.'/team.js',false,false,true);			
		
			
		
		wp_register_style( 'wd.team', WDT_CSS.'/team.css');		
		wp_enqueue_style('wd.team');
	}
	
	protected function constant(){
		//define('DS',DIRECTORY_SEPARATOR);	
		define('WDT_BASE'		,  	plugins_url( '', __FILE__ )		);
		define('WDT_JS'			, 	WDT_BASE . '/js'		);
		define('WDT_CSS'		, 	WDT_BASE . '/css'		);
		define('WDT_IMAGE'		, 	WDT_BASE . '/images'	);
		define('WDT_TEMPLATE' 	, 	dirname(__FILE__) . '/templates'	);
		define('WDT_TYPES'	, 	plugin_dir_path( __FILE__ ) . 'post_type'		);
		define('WDT_INCLUDES'	, 	plugin_dir_path( __FILE__ ) . 'includes'		);
	}	
	
}
 
$_wd_Team = new WD_Team; // Start an instance of the plugin class 