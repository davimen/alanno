<?php
/*
  Plugin Name: WD Slide
  Plugin URI: http://www.wpdance.com
  Description: Slide From WPDance Team
  Version: 1.0.0
  Author: WD Team
  Author URI: http://www.wpdance.com
 */
class WD_Slide {

	public function __construct(){
		$this->constant();
		
		/****************************/
		// Register Slide post type
		//add_action('init', array($this,'wd_slide_register') );
		$this->wd_slide_register();
		add_theme_support('post-thumbnails', array('slide'));
		
		register_activation_hook(__FILE__, array($this,'wd_slide_activate') );
		register_deactivation_hook(__FILE__, array($this,'wd_slide_deactivate') );

		add_action('admin_enqueue_scripts',array($this,'init_admin_script'));
		
		add_action('admin_menu', array( $this,'wd_slide_create_section' ) );	
		
		add_filter('attribute_escape', array($this,'rename_second_menu_name') , 10, 2);
		
		add_action('save_post', array($this,'wd_slide_save_data') , 1, 2);
		
		add_action( 'template_redirect', array($this,'wd_slide_template_redirect') );
		
		
		
		$this->init_trigger();
		$this->init_handle();
	}
	
	/******************************** Slide POST TYPE INIT START ***********************************/

	public function wd_slide_save_data($post_id, $post) {

		if ( ! isset( $_POST['wd_slide_box_nonce'] ) )
				return $post_id;
		// verify this came from the our screen and with proper authorization,
		// because save_post can be triggered at other times
		if (!wp_verify_nonce($_POST['wd_slide_box_nonce'],'wd_slide_box'))
			return $post->ID;

		if ($post->post_type == 'revision')
			return; //don't store custom data twice

		if (!current_user_can('edit_post', $post->ID))
			return $post->ID;

		// OK, we're authenticated: we need to find and save the data
		// Sanitize the user input.
		if( isset($_POST['_sliders_slider']) && $_POST['_sliders_slider'] == 1 ){
			$ret_str = '';
			$element_count = count($_POST['element_id']);
			$ret_arr = array();
			for( $i = 0 ; $i < $element_count ; $i++ ){	
				$temp_arr = array(
					'id' 				=> $_POST['element_id'][$i]
					//,'image_url' 		=> $_POST['element_image_url'][$i]
					//,'thumb_url' 		=> $_POST['thumb_url'][$i]
					,'thumb_id' 		=> $_POST['thumb_id'][$i]
					,'url' 				=> $_POST['element_url'][$i]
					,'alt' 				=> $_POST['element_alt'][$i]
					,'title' 			=> $_POST['element_title'][$i]
					,'slide_title' 		=> $_POST['slide_title'][$i]
					,'slide_content' 	=> $_POST['slide_content'][$i]					
				
				);
				array_push( $ret_arr, $temp_arr );
			}
			
			$ret_str = serialize($ret_arr);
			update_post_meta($post_id,'wd_portfolio_slider',$ret_str);	
			
			
			$ret_arr = array(
				'portfolio_slider_config_size' 			=> $_POST['portfolio_slider_config_size']
				,'portfolio_slider_config_autoslide' 	=> $_POST['portfolio_slider_config_autoslide']
				// ,'portfolio_slider_config_width' 		=> $_POST['portfolio_slider_config_width']
				// ,'portfolio_slider_config_height' 		=> $_POST['portfolio_slider_config_height']
			);
			$ret_str = serialize($ret_arr);
			update_post_meta($post_id,'wd_slide_config',$ret_str);
			if (!$ret_arr)
				delete_post_meta($post_id, 'wd_slide_config'); //delete if blank
		}
	}	
	
	public function wd_slide_register() {
		 require_once WDS_TYPES."/slide.php";
	}	
	
	
	/******************************** Slide POST TYPE INIT END *************************************/
	
	public function wd_slide_template_redirect(){
		global $wp_query,$post,$page_datas,$data;
		add_action('wp_enqueue_scripts',array($this,'init_script'));		
	}
	
	public function wd_slide_create_section() {
		if(post_type_exists('slide')) {
			add_meta_box("wp_cp_custom_carousels", "Insert Slider", array($this,"showcarousel"), "slide", "normal", "high");
		}
	}

	public function showcarousel(){
		require_once WDS_INCLUDES.'/carousel.php';
	}
	
	public function wd_Slide_deactivate() {
		flush_rewrite_rules();
	}

	public function wd_Slide_activate() {
		$this->wd_slide_register();
		flush_rewrite_rules();
	}		
	
	public function rename_second_menu_name($safe_text, $text) {
		if (__('Slide Items', 'WD_slide_context') !== $text) {
			return $safe_text;
		}

		// We are on the main menu item now. The filter is not needed anymore.
		remove_filter('attribute_escape', array($this,'rename_second_menu_name') );

		return __('WD Slide', 'wd_slide_context');
	}
		
	protected function init_trigger(){
	
	}
	protected function init_handle(){
		//add_shortcode('wd-slide', array( $this,'wd_Slide') );
		require_once WDS_TEMPLATE . "/slide.php";
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
		/// Start Fancy Box
		wp_register_style( 'fancybox_css', WDS_CSS.'/jquery.fancybox.css');
		wp_enqueue_style('fancybox_css');
		
		wp_register_script( 'fancybox_js', WDS_JS.'/jquery.fancybox.pack.js',false,false,true);
		wp_enqueue_script('fancybox_js');	
	}	
	
	
	public function init_script(){
		wp_enqueue_script('jquery');
		
		wp_register_style( 'bootstrap', WDS_CSS.'/bootstrap.css');
		wp_enqueue_style('bootstrap');
		
		wp_register_style( 'bootstrap-style', WDS_CSS.'/bootstrap-style.css');
		wp_enqueue_style('bootstrap-style');
		
		wp_register_style( 'bootstrap-style', WDS_CSS.'/bootstrap-style.css');
		wp_enqueue_style('bootstrap-style');
		
		wp_register_style( 'bootstrap-ie8-buttonfix', WDS_CSS.'/bootstrap-ie8-buttonfix.css');
		wp_enqueue_style('bootstrap-ie8-buttonfix');
		
		wp_register_script( 'bootstrap', WDS_JS.'/bootstrap.js');
		wp_enqueue_script('bootstrap');
		
		
		wp_register_script( 'jquery.carouFredSel', WDS_JS.'/jquery.carouFredSel-6.2.1.min.js',false,false,true);
		wp_enqueue_script('jquery.carouFredSel');	
		
		//wp_register_script( 'wd.slide.js', WDP_JS.'/slide.js',false,false,true);			
		
		wp_register_style( 'wd.slide', WDS_CSS.'/wd_slide.css');		
		wp_enqueue_style('wd.slide');
	}
	
	protected function constant(){
		//define('DS',DIRECTORY_SEPARATOR);	
		define('WDS_BASE'	,  	plugins_url( '', __FILE__ ));
		define('WDS_JS'			, 	WDS_BASE . '/js'		);
		define('WDS_CSS'		, 	WDS_BASE . '/css'		);
		define('WDS_IMAGE'		, 	WDS_BASE . '/images'	);
		define('WDS_TEMPLATE' 	, 	dirname(__FILE__) . '/templates'	);
		define('WDS_TYPES'	, 	plugin_dir_path( __FILE__ ) . 'post_type'		);
		define('WDS_INCLUDES'	, 	plugin_dir_path( __FILE__ ) . 'includes'		);
	}	
	
}
 
$_wd_Slide = new WD_Slide; // Start an instance of the plugin class 
?>