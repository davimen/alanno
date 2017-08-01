<?php 

if( !class_exists( 'WD_Admin_Popup' ) ) {
	class WD_Admin_Popup {
		
		private $options = array(
			'cf_slug' => '_wd_popup_config',
			'cookie_slug' => 'wd_popup_ckie'
		);
		
		protected $meta_post_pp_slug;
		protected $post_type;
		
		function __construct() {
			
			add_action( 'init', array( $this, 'wd_popup_init' ) );
			add_action('admin_enqueue_scripts',array($this,'init_wp_script'));
			
			add_action("admin_init", array($this,"register_meta_box"));
			add_action('save_post', array($this,'saveCustomField'));
			
			
		}
		
		public function wd_popup_init(){
			$this->meta_post_pp_slug = THEME_SLUG.'popup_configuration';
			$this->post_type = "wd_popup";
			$labels = array(
				'name'               => _x( 'WD Popups', 'WPDance Popups', 'wpdance' ),
				'singular_name'      => _x( 'Popup', 'Popup', 'wpdance' ),
				'menu_name'          => _x( 'WD Popups', 'WPDance Popups', 'wpdance' ),
				'name_admin_bar'     => _x( 'WD Popups', 'WD Popups', 'wpdance' ),
				'add_new'            => _x( 'Add New', 'Add New', 'wpdance' ),
				'add_new_item'       => __( 'Add New Popup', 'wpdance' ),
				'new_item'           => __( 'New Popup', 'wpdance' ),
				'edit_item'          => __( 'Edit Popup', 'wpdance' ),
				'view_item'          => __( 'View Popup', 'wpdance' ),
				'all_items'          => __( 'All Popups', 'wpdance' ),
				'search_items'       => __( 'Search Popups', 'wpdance' ),
				'parent_item_colon'  => __( 'Parent Popups:', 'wpdance' ),
				'not_found'          => __( 'No Popups found.', 'wpdance' ),
				'not_found_in_trash' => __( 'No Popups found in Trash.', 'wpdance' )
			);
			
			$args = array(
				'labels'             => $labels,
				'public'             => true,
				'publicly_queryable' => true,
				'show_ui'            => true,
				'show_in_menu'       => true,
				'query_var'          => true,
				'rewrite'            => array( 'slug' => $this->post_type ),
				'capability_type'    => 'post',
				'has_archive'        => true,
				'hierarchical'       => false,
				'menu_position'      => 5,
				'supports'           => array( 'title', 'editor', 'author', 'thumbnail' )
			);
			register_post_type( $this->post_type, $args );
			
			add_filter('post_updated_messages', array($this,'prefix_post_updated_messages') );
			add_action('admin_notices', array( $this, 'wd_admin_notice' ));
			
			add_filter('manage_'.$this->post_type.'_posts_columns', array( $this, 'wd_popup_table_head' ) );
			add_action( 'manage_'.$this->post_type.'_posts_custom_column', array( $this,'wd_popup_table_content' ), 10, 2 );
			
			add_filter('post_row_actions', array( $this, 'wd_popup_remove_action_row' ), 10, 2);
			
			add_action( 'wp_ajax_wd_popup_manage_enab_action', array( $this, 'wd_popup_manage_enab_action' ) );
			
			
		}
		
		public function prefix_post_updated_messages($messages) {
			global $post;
			$post_type			= $post->post_type;
			$post_type_object 	= get_post_type_object($post_type);
			$messages['wd_popup'] = array(
				0 => '',
				1 => __('WD-Popup updated.', 'wpdance'),
				2 => __('Custom field updated.', 'wpdance'),
				3 => __('Custom field deleted.', 'wpdance'),
				4 => __('Popup updated.', 'wpdance'),
				5  => isset($_GET['revision']) ? sprintf(__('Popup restored to revision from %s', 'wpdance'), wp_post_revision_title( (int) $_GET['revision'], false)) : false,
				6  => __('Popup published.', 'wpdance'),
				7  => __('Popup saved.', 'wpdance'),
				8  => __('Popup submitted.', 'wpdance'),
				9  => sprintf(__('Popup scheduled for: <strong>%1$s</strong>.', 'wpdance'), date_i18n(__('M j, Y @ G:i', 'wpdance'), strtotime($post->post_date))),
				10 => __('Popup draft updated.', 'wpdance'),
			);
			
			return $messages;
		}
		
		
		public function wd_popup_manage_enab_action(){
			$id = isset($_POST['id'])? $_POST['id']: '';
			$ena_val = isset($_POST['ena_val'])? $_POST['ena_val']: '';
			$wd_data = unserialize(get_post_meta($id,$this->meta_post_pp_slug,true));
			$wd_data['popup_enable'] = $ena_val;
			
			$ret_str = serialize($wd_data);			
			update_post_meta( $id, $this->meta_post_pp_slug, $ret_str );
			die($ena_val);
		}
		
		
		public function wd_popup_remove_action_row( $actions, $post ){
			if( $post->post_type === $this->post_type ) {
				unset( $actions['view'] );
				$wd_data = unserialize(get_post_meta($post->ID,$this->meta_post_pp_slug,true));
				if(isset($wd_data['popup_enable'])){
					$txt = absint($wd_data['popup_enable']) == 1? __('Disable', 'wpdance'): __('Enable', 'wpdance');
					$val = absint($wd_data['popup_enable']) == 0? 1: 0;
					$actions['wd_enable_act'] = '<a href="#" class="wd_popup_manage_enab_btn" data-id="'.$post->ID.'" data-action="wd_popup_manage_enab_action" data-ena_val="'.absint($val).'">'.$txt.'</a>';
				}
				
			}
			return $actions;
		}
		
		public function wd_popup_table_head( $columns) {
			unset($columns['date'], $columns['author'], $columns['title']);
			$columns['wd_thumb']    = __('Background', 'wpdance');
			$columns['title'] 		= __('Title', 'wpdance');
			
			$columns['wd_status']   = __('Status', 'wpdance');
			$columns['wd_close']   = __('Auto close', 'wpdance');
			$columns['wd_where']    = __('Show on', 'wpdance');
			$columns['wd_cookie']   = __('Cookie', 'wpdance');
			
			/*$columns['author'] 		= __('Author', 'wpdance');*/
			$columns['date'] 		= __('Date', 'wpdance');
			return $columns;
		}
		
		public function wd_popup_table_content( $column_name, $post_id) {
			if ($column_name == 'wd_status') {
				$wd_data = unserialize(get_post_meta($post_id,$this->meta_post_pp_slug,true));
				if( isset($wd_data['popup_enable']) && absint($wd_data['popup_enable']) == 1) {
					echo "<label class=\"wd_label enable label\">Enable</label>";
				} else {
					echo "<label class=\"wd_label label\">Disable</label>";
				}
			} else if ($column_name == 'wd_where'){
				$wd_data = unserialize(get_post_meta($post_id,$this->meta_post_pp_slug,true));
				if( isset($wd_data['popup_location'])) {
					switch( $wd_data['popup_location'] ) {
						case 'home': 
							echo __("Home page", "wpdance");
							break;
						case 'all':
							echo __("All pages", "wpdance");
							break;
						case 'custom':
							echo __("Custom pages", "wpdance");
							break;
					}
				}
			} else if ($column_name == 'wd_cookie'){
				$wd_data = unserialize(get_post_meta($post_id,$this->meta_post_pp_slug,true));
				if( isset($wd_data['popup_cookie_delay']) && absint($wd_data['popup_cookie_delay']) > 0 ) {
					if( absint($wd_data['popup_cookie_delay']) <= 30 ) {
						echo "<label class=\"wd_label noti\">".absint($wd_data['popup_cookie_delay'])."m</label>";
					} else if( absint($wd_data['popup_cookie_delay']) <= 720 ) {
						echo "<label class=\"wd_label noti\">". (absint($wd_data['popup_cookie_delay'])/60) ."h</label>";
					} else {
						echo "<label class=\"wd_label noti\">1d</label>";
					}
				} else {
					echo "<label class=\"wd_label\">No cookie</label>";
				}
			} else if ($column_name == 'wd_thumb'){
				$wd_data = unserialize(get_post_meta($post_id,$this->meta_post_pp_slug,true));
				$bg_color = '#ffffff';
				if(isset($wd_data['popup_thumb_color'])) {
					$bg_color = esc_attr($wd_data['popup_thumb_color']);
				}
				echo "<div class=\"wd_box_t1\" style=\"width: 70px; height: 35px; background-color: ".$bg_color."; border: 1px solid #cccccc;\">";
				$wd_backg = wp_get_attachment_image_src( get_post_thumbnail_id($post_id), 'thumbnail' );
				if(!empty($wd_backg) && trim($wd_backg[0])!=='')
					echo "<img src=\"".esc_url($wd_backg[0])."\" alt=\"popup background\" width=\"35\" height=\"35\" />";
				echo "</div>";
				
			} else if ($column_name == 'wd_close'){
				$wd_data = unserialize(get_post_meta($post_id,$this->meta_post_pp_slug,true));
				if( isset($wd_data['popup_timeout_to_close']) && isset($wd_data['popup_timeout_delay']) && absint($wd_data['popup_timeout_to_close']) == 1 && absint($wd_data['popup_timeout_delay']) > 0 ) {
					if ($wd_data['popup_timeout_delay'] < 60) {
						echo "<label class=\"wd_label noti\">".absint($wd_data['popup_timeout_delay'])."s</label>";
					} else {
						$m = absint( absint($wd_data['popup_timeout_delay'])/60 );
						$s = absint($wd_data['popup_timeout_delay'])%60;
						$s = ( $s > 0 )? $s."s": "";
						echo "<label class=\"wd_label noti\">".$m."m".$s."</label>";
					}
				} else {
					echo "<label class=\"wd_label\">Disable</label>";
				}
			}
			
			
		}
		
		public function register_meta_box(){
			require_once WD_INC_PATH . 'class.featured_image_metabox_customizer.php';
			
			new Featured_Image_Metabox_Customizer(array(
				'post_type'     => $this->post_type,
				'metabox_title' => __( 'Popup background', 'feat-img-custom' ),
				'set_text'      => __( 'Set popup background', 'feat-img-custom' ),
				'remove_text'   => __( 'Remove popup background', 'feat-img-custom' )
			));
			
			add_meta_box("wd_popup_config", "Popup Configuration", array($this,"popup_configuration_temp"), $this->post_type, "normal", "high");
		}
		
		
		public function saveCustomField($post_id){
			if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE )
				return $post_id;
			if(isset($_POST['_inline_edit'])) 
				return $post_id;
			if( isset($_REQUEST['mode']) && $_REQUEST['mode'] == 'list' )
				return $post_id;	
			if( isset($_REQUEST['action']) &&  $_REQUEST['action'] == 'trash' )
				return $post_id;
			
			
			if ( isset($_POST['_wd_popup_config']) && (int)$_POST['_wd_popup_config'] == 1 && wp_verify_nonce($_POST['nonce_wd_popup_config'],'_update_'.$this->post_type.'_config') ){
				$_post_params = array(
					"popup_location" 			=> $_POST['popup_location'],
					"popup_enable" 				=> isset($_POST['popup_enable'])? $_POST['popup_enable']:0,
					"popup_hide_title"			=> isset($_POST['popup_hide_title'])? $_POST['popup_hide_title']:0,
					"popup_cookie_delay"		=> $_POST['popup_cookie_delay'],
					"popup_skin"				=> $_POST['popup_skin'],
					"popup_body_padd"			=> isset($_POST['popup_hide_title']) && $_POST['popup_hide_title']!==''? $_POST['popup_body_padd'] : '25px',
					"popup_width"				=> (absint($_POST['popup_width']) >  300 )? absint($_POST['popup_width']): 720,
					"popup_height"				=> ($_POST['popup_height'] !== '')? $_POST['popup_height']: 370,
					"popup_timeout_to_close"	=> isset($_POST['popup_timeout_to_close'])? absint($_POST['popup_timeout_to_close']): 0,
					"popup_timeout_delay"		=> ($_POST['popup_timeout_delay'] !== '')? absint($_POST['popup_timeout_delay']): 5,
					"popup_thumb_color"			=> isset($_POST['popup_thumb_color'])? esc_attr($_POST['popup_thumb_color']): '',
					"popup_thumb_repeat"		=> isset($_POST['popup_thumb_repeat']) && ($_POST['popup_thumb_repeat'] !== '')? esc_attr($_POST['popup_thumb_repeat']): 'no-repeat',
					"popup_thumb_size"			=> isset($_POST['popup_thumb_size']) && ($_POST['popup_thumb_size'] !== '')? esc_attr($_POST['popup_thumb_size']): 'cover',
					"popup_thumb_pos"		=> isset($_POST['popup_thumb_pos']) && ($_POST['popup_thumb_pos'] !== '')? esc_attr($_POST['popup_thumb_pos']): 'center center',
					"popup_location_cus_ids"	=> isset($_POST['popup_location_cus_ids']) && ($_POST['popup_location_cus_ids'] !== '')? esc_attr($_POST['popup_location_cus_ids']): '',
				);
				//die(print_r($_post_params));						
				$_post_params = $this->wd_array_atts(array(
					"popup_location" 			=> 'home',
					"popup_enable"				=> '0',
					"popup_hide_title"			=> '0',
					"popup_cookie_delay"		=> '0',
					"popup_skin"				=> 'k1',
					"popup_body_padd"			=> '25px',
					"popup_width"				=> '720',
					"popup_height"				=> '370',
					"popup_timeout_to_close"	=> '0',
					"popup_timeout_delay"		=> '5',
					"popup_thumb_color"			=> '',
					"popup_thumb_repeat"		=> 'no-repeat',
					"popup_thumb_size"			=> 'cover',
					"popup_thumb_pos"		=> 'center',
					"popup_location_cus_ids"	=> '',
					"popup_mess_type"			=> '',
					"popup_mess_content"		=> '',
				),$_post_params	);
				
				$page_id_str = trim($_post_params['popup_location_cus_ids']);
				$page_ids = explode(",", $page_id_str);
				$mess_str = '';
				foreach( $page_ids as $k=>$p ) {
					if(is_numeric($p)) {
						if(!get_post($p)) {
							unset($page_ids[$k]);
							$mess_str .= $p;$mess_str .= ',';
						}
					} else {
						if(!get_page_by_title($p)) {
							unset($page_ids[$k]);
							$mess_str .= $p;$mess_str .= ',';
						}
					}
				}
				$_post_params['popup_mess_content'] = trim($mess_str) !== '' ? substr(trim($mess_str), 0, strlen(trim($mess_str))-1): '';
				$_post_params['popup_mess_type'] = trim($mess_str) !== '' ? 'error': '';
				
				$_post_params['popup_location_cus_ids'] = implode(',', $page_ids);
				$ret_str = serialize($_post_params);			
				
				update_post_meta( $post_id, $this->meta_post_pp_slug, $ret_str );
				
			}
		}
		
		public function wd_admin_notice(){
			global $pagenow;
			if( $pagenow == 'post.php' || $pagenow == 'post-new.php' ){
				global $post;
				if($post->post_type == $this->post_type) {
					$data_config = unserialize(get_post_meta($post->ID,$this->meta_post_pp_slug,true));
					if( isset($data_config['popup_mess_type']) && $data_config['popup_mess_type'] !== '' ){
					?>
						<div class="<?php echo esc_attr($data_config['popup_mess_type'])?>">
							<p><strong>Warning!</strong> "<?php echo esc_html($data_config['popup_mess_content']);?>" was not found, it's were removed from your page ids input that you custom.</p>
						</div>
					<?php 
					}
					$data_config['popup_mess_type'] = '';
					$data_config['popup_mess_content'] = '';
					$ret_str = serialize($data_config);
					update_post_meta( $post->ID, $this->meta_post_pp_slug, $ret_str );
				}
			}
		}
		
		public function wd_array_atts($pairs, $atts) {
			$atts = (array)$atts;
			$out = array();
			foreach($pairs as $name => $default) {
				if ( array_key_exists($name, $atts) ){
					if( strlen(trim($atts[$name])) > 0 ){
						$out[$name] = $atts[$name];
					}else{
						$out[$name] = $default;
					}
				}
				else{
					$out[$name] = $default;
				}	
			}
			return $out;
		}
		
		public function popup_configuration_temp(){
			require_once WD_TEMP_PATH.'/popup_configuration.php';
		}
		
		public function init_wp_script(){
			wp_enqueue_style( 'wp-color-picker' );
			
			wp_enqueue_script('jquery');
			
			wp_register_style( 'wd_pp_style', WD_URL_ASSESTS . 'css/admin-pp.style.css', false, false );
			wp_enqueue_style( 'wd_pp_style' );
			
			wp_register_script('wd_popup_admin', WD_URL_ASSESTS . 'js/wd-admin-popup.js', array( 'wp-color-picker' ), false, true);
			wp_enqueue_script('wd_popup_admin');
			
		}
		
		public function delete_options(){
			delete_option( WD_AS_SLUG );
		}
		
	}
}
global $WD_AD_POPUP;
$WD_AD_POPUP = new WD_Admin_Popup();
?>