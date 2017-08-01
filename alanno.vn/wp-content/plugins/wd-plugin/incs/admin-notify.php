<?php 

if( !class_exists( 'WD_Admin_Notify' ) ) {
	class WD_Admin_Notify {
		
		private $options = array();
		
		private $admin_page = array(
			'parent_slug'	=> 'wd-admin',
			'page_title'	=> 'Nitification',
			'menu_title'	=> 'Nitification',
			'capability'	=> 'manage_options',
			'menu_slug'		=> 'wd-woo-notify'
		);
		
		function __construct() {
			add_action('admin_menu',  array( $this, 'register_admin_menu')  );
			
			add_action( 'wp_ajax_wdntf_save_options', array( $this, 'wd_ajax_saveOption' ) );
			add_action( 'wp_ajax_nopriv_wdntf_save_options', array( $this, 'wd_ajax_saveOption' ) );
			
			add_action( 'wp_ajax_wd_notify_order', array($this, 'ajax_get_new_order'));
			add_action( 'wp_ajax_nopriv_wd_notify_order', array($this, 'ajax_get_new_order'));
			
			add_action('admin_enqueue_scripts',array($this,'init_wp_script'));
		}
		
		function register_admin_menu(){
			add_submenu_page(	$this->admin_page['parent_slug'],
								$this->admin_page['page_title'],
								$this->admin_page['menu_title'],
								$this->admin_page['capability'],
								$this->admin_page['menu_slug'],
								array( $this, 'wd_notify_admin_page_view' ) );
		}
		
		function wd_notify_admin_page_view(){
			if( get_option( WDNTF_SLUG ) ) { 
				$this->options = json_decode(get_option( WDNTF_SLUG ));
			}
		
			include_once WD_TEMP_PATH . 'notify-template.php';
		}
		
		function init_wp_script(){
			wp_enqueue_script('jquery');
			
			wp_register_script('wdntf_admin_js', WD_URL_ASSESTS . 'js/admin-notify.js', 'jquery', false,false);
			wp_enqueue_script('wdntf_admin_js');
		}
		
		public function ajax_get_new_order(){
			$start_time = $this->get_notify_date();
			$args = array(
				'post_type' 		=> 'shop_order',
				'post_status' 		=> 'wc-on-hold',
				'orderby'     		=> 'date', 
				'order'       		=> 'desc',
			);
			if( strcmp( trim( $start_time ), 'none' ) !== 0 ) {
				$args['date_query'] = array(
					array(
						'after'	=> $start_time,
					),
				);
			}
			$result = new WP_Query($args);
			die(json_encode($result->posts));
		}
		
		public function get_notify_date() {
			$datetime = 'none';
			if(get_option('wd_notify_option')) {
				$option = json_decode(get_option('wd_notify_option'));
				if(isset($option->start_time)) $datetime = $option->start_time;
				$option->start_time = current_time('mysql');
				$option = json_encode($option);
				update_option('wd_notify_option', $option);
			} else {
				$option['start_time'] = current_time('mysql');
				$option = json_encode($option);
				add_option('wd_notify_option', $option, '', 'yes');
			}
			return $datetime;
		}
		
		public function wd_ajax_saveOption(){
			$data 	= $_POST;
			if(isset($data['action'])) unset($data['action']);
			foreach($data as $k=>$v) {
				if( strpos( $k, "wd_ops" ) > -1 ) {
					$k = substr( $k, 7 );
					$this->options[$k] = $v;
				}
			}
			$this->save_option($this->options);
			die();
		}
		
		public function save_option($data){
			
			if( !get_option( WDNTF_SLUG ) ) {
				$data = json_encode($data);
				add_option( WDNTF_SLUG, $data, '', 'yes' );
			} else {
				$this->options = json_decode(get_option( WDNTF_SLUG ));
				$old_value = isset($this->options->start_time)? $this->options->start_time :'';
				if($old_data !== '') $data['start_time'] = $old_value;
				$data = json_encode($data);
				update_option( WDNTF_SLUG, $data );
			}
		}
		
		public function delete_options(){
			delete_option( WDNTF_SLUG );
		}
		
	}
}
global $WD_AD_NTF;
$WD_AD_NTF = new WD_Admin_Notify();
?>