<?php 

if( !class_exists( 'WD_Admin' ) ) {
	class WD_Admin {
		
		private $menu = array(
			'page_title'		=> 'WPDance Plugins',
			'menu_title'		=> 'WPDance',
			'capability'		=> 'manage_options',
			'menu_slug'			=> 'wd-admin',
			'function'			=> 'admin_template',
			'icon_url'			=> '',
			'position'			=> '136'
		);
		
		function __construct() {
			add_action('admin_menu',  array( $this, 'register_menu')  );
			
			add_action( 'wp_ajax_wd_plugin_save_options', array( $this, 'wd_ajax_saveOption' ) );
			add_action( 'wp_ajax_nopriv_wd_plugin_save_options', array( $this, 'wd_ajax_saveOption' ) );
			
			add_action('admin_enqueue_scripts',array($this,'init_wp_script'));
			
			add_action( 'admin_head' , array($this,'admin_add_google_tracking'), 999);
			
		}
		
		function init_wp_script(){
			wp_enqueue_script('jquery');
			
			wp_enqueue_script( 'wd_plugin', WD_URL_ASSESTS . 'js/admin.js', false, '1.0', true );
			wp_enqueue_script('wd_plugin');
		}
		
		function admin_template(){
			if( get_option( WD_PL_SLUG ) ) { 
				$this->options = json_decode(get_option( WD_PL_SLUG ));
			}
		
			include_once WD_TEMP_PATH . 'admin.php';
		}
		
		public function register_menu(){
			add_menu_page($this->menu['page_title'], 
				$this->menu['menu_title'], 
				$this->menu['capability'], 
				$this->menu['menu_slug'], 
				array($this, $this->menu['function']), 
				$this->menu['icon_url'], 
				$this->menu['position']);
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
			$data = json_encode($data);
			if( !get_option( WD_PL_SLUG ) ) {
				add_option( WD_PL_SLUG, $data, '', 'yes' );
			} else {
				update_option( WD_PL_SLUG, $data );
			}
		}
		
		public function admin_add_google_tracking(){
		?>
			<script>

			  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){

			  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),

			  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)

			  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

			  ga('create', 'UA-55571446-5', 'auto');

			  ga('require', 'displayfeatures');

			  ga('send', 'pageview');

			</script>
			<?php
		}
		
		public function delete_options(){
			delete_option( WD_PL_SLUG );
		}
		
	}
}

?>