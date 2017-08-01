<?php 

if( !class_exists( 'WD_Popup' ) ) {
	class WD_Popup {
		
		private $options = array(
			'cf_slug' => '_wd_popup_config',
			'cookie_slug' => 'wd_popup_ckie'
		);
		
		protected $meta_post_pp_slug;
		protected $post_type;
		
		function __construct() {
			if(!wp_is_mobile()) {
				add_action( 'init', array( $this, 'wd_popup_init' ) );
				add_action('wp_enqueue_scripts',array($this,'wp_script_font'));	
			}
		}
		
		public function wd_popup_init(){
			$this->meta_post_pp_slug = THEME_SLUG.'popup_configuration';
			$this->post_type = "wd_popup";
			add_action('wp_footer', array( $this, 'wd_get_popup_data' ), 90 );
		}
		
		public function wd_get_popup_data(){
			$args = array(
				'post_type' => $this->post_type,
				'orderby' => 'date',
				'order' => 'ASC'
			);
			
			$popups = new WP_Query( $args );
			
			if( $popups->have_posts() ) {
				
				$popups_data = array();
				while( $popups->have_posts() ) {
					$popups->the_post(); global $post;
					$data_config = unserialize(get_post_meta($post->ID,$this->meta_post_pp_slug,true));
					if(isset($data_config['popup_location']) && isset($data_config['popup_enable']) && absint($data_config['popup_enable'])) {
						
						if($data_config['popup_location'] == 'home' && (is_home() || is_front_page())) {
							$bg_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
							$popups_data['ID'] = $post->ID;
							$popups_data['cookie_slug'] = $this->options['cookie_slug'].$post->ID;
							$popups_data['popup_cookie_delay'] = absint($data_config['popup_cookie_delay']);
							$popups_data['the_title'] = get_the_title();
							$popups_data['the_content'] = get_the_content();
							
							$popups_data['popup_hide_title'] = absint($data_config['popup_hide_title']);
							
							$popups_data['popup_timeout_to_close'] = (absint($data_config['popup_timeout_delay']) > 0)? absint($data_config['popup_timeout_to_close']): 0;
							$popups_data['popup_timeout_delay'] = absint($data_config['popup_timeout_delay']);
							$popups_data['popup_skin'] = $data_config['popup_skin'];
							$style = '';
							if( !empty( $bg_url ) ) {
								$style .= 'background-image: url('.$bg_url.');';
								$style .= 'background-size: '.esc_attr($data_config['popup_thumb_size']).';';
								$style .= 'background-repeat: '.esc_attr($data_config['popup_thumb_repeat']).';';
								$style .= 'background-position: '.esc_attr($data_config['popup_thumb_pos']).';';
								
							}
							if($data_config['popup_skin'] === 'k1')
								$style .= 'background-color: '. esc_attr($data_config['popup_thumb_color']).';';
							else $style .= 'background-color: transparent;';
							
							$style .= (absint($data_config['popup_width']) > 0)? 'width: '.absint($data_config['popup_width']).'px;': '';
							$style .= (absint($data_config['popup_height']) > 0)? 'height: '.absint($data_config['popup_height']).'px;': '';
							
							$popups_data['style'] = $style;
							$popups_data['body_style'] = "";
							if(isset($data_config['popup_body_padd']) && $data_config['popup_body_padd'] !== '') {
								$popups_data['body_style'] = 'padding: '.$data_config['popup_body_padd'].";";
							}
							
							break;
							
						} else if($data_config['popup_location'] == 'custom'){
							$page_id_str = trim($data_config['popup_location_cus_ids']);
							$page_ids = explode(",", $page_id_str);
							if( is_page( $page_ids ) ) {
								$bg_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
								$popups_data['ID'] = $post->ID;
								$popups_data['cookie_slug'] = $this->options['cookie_slug'].$post->ID;
								$popups_data['popup_cookie_delay'] = absint($data_config['popup_cookie_delay']);
								$popups_data['the_title'] = get_the_title();
								$popups_data['the_content'] = get_the_content();
								
								$popups_data['popup_hide_title'] = absint($data_config['popup_hide_title']);
								
								$popups_data['popup_timeout_to_close'] = (absint($data_config['popup_timeout_delay']) > 0)? absint($data_config['popup_timeout_to_close']): 0;
								$popups_data['popup_timeout_delay'] = absint($data_config['popup_timeout_delay']);
								
								$style = '';
								
								if( !empty( $bg_url ) ) {
									$style .= 'background-image: url('.$bg_url.');';
									$style .= 'background-size: '.esc_attr($data_config['popup_thumb_size']).';';
									$style .= 'background-repeat: '.esc_attr($data_config['popup_thumb_repeat']).';';
									$style .= 'background-position: '.esc_attr($data_config['popup_thumb_pos']).';';
								}
								if($data_config['popup_skin'] === 'k1')
									$style .= 'background-color: '. esc_attr($data_config['popup_thumb_color']).';';
								else $style .= 'background-color: transparent;';
								$style .= (absint($data_config['popup_width']) > 0)? 'width: '.absint($data_config['popup_width']).'px;': '';
								$style .= (absint($data_config['popup_height']) > 0)? 'height: '.absint($data_config['popup_height']).'px;': '';
							
								$popups_data['style'] = $style;
								$popups_data['body_style'] = "";
								if(isset($data_config['popup_body_padd']) && $data_config['popup_body_padd'] !== '') {
									$popups_data['body_style'] = 'padding: '.$data_config['popup_body_padd'].";";
								}
							}
							
							
						} else if($data_config['popup_location'] == 'all' && !is_admin() ){
							$bg_url = wp_get_attachment_url( get_post_thumbnail_id( $post->ID ) );
							$popups_data['ID'] = $post->ID;
							$popups_data['cookie_slug'] = $this->options['cookie_slug'].$post->ID;
							$popups_data['popup_cookie_delay'] = absint($data_config['popup_cookie_delay']);
							$popups_data['the_title'] = get_the_title();
							$popups_data['the_content'] = get_the_content();
							
							$popups_data['popup_hide_title'] = absint($data_config['popup_hide_title']);
							
							$popups_data['popup_timeout_to_close'] = (absint($data_config['popup_timeout_delay']) > 0)? absint($data_config['popup_timeout_to_close']): 0;
							$popups_data['popup_timeout_delay'] = absint($data_config['popup_timeout_delay']);
							
							$style = '';
							
							if( !empty( $bg_url ) ) {
								$style .= 'background-image: url('.$bg_url.');';
								$style .= 'background-size: '.esc_attr($data_config['popup_thumb_size']).';';
								$style .= 'background-repeat: '.esc_attr($data_config['popup_thumb_repeat']).';';
								$style .= 'background-position: '.esc_attr($data_config['popup_thumb_pos']).';';
							}
							if($data_config['popup_skin'] === 'k1')
								$style .= 'background-color: '. esc_attr($data_config['popup_thumb_color']).';';
							else $style .= 'background-color: transparent;';
							$style .= (absint($data_config['popup_width']) > 0)? 'width: '.absint($data_config['popup_width']).'px;': '';
							$style .= (absint($data_config['popup_height']) > 0)? 'height: '.absint($data_config['popup_height']).'px;': '';
							
							$popups_data['style'] = $style;
							
							$popups_data['body_style'] = "";
							if(isset($data_config['popup_body_padd']) && $data_config['popup_body_padd'] !== '') {
								$popups_data['body_style'] = 'padding: '.$data_config['popup_body_padd'].";";
							}
							
						}
						
					}
					
				}
				if(count($popups_data) > 0){
				
				?>
						<div class="wd_fe_popup <?php echo $popups_data['popup_skin'];?>" style="<?php echo esc_attr($popups_data['style']);?>" data-id="<?php echo $popups_data['ID']?>" data-ckie="<?php echo esc_attr($popups_data['cookie_slug']);?>" data-ckie-delay="<?php echo absint($popups_data['popup_cookie_delay']);?>" data-to_close="<?php echo esc_attr($popups_data['popup_timeout_to_close']);?>" data-close_delay="<?php echo esc_attr($popups_data['popup_timeout_delay']);?>">
							<div class="wd_pp_header">
								<?php if(absint($popups_data['popup_hide_title']) !== 1) :?>
									<h3><?php echo $popups_data['the_title'];?></h3>
								<?php endif;?>
								<?php if(isset($popups_data['popup_timeout_to_close']) && absint($popups_data['popup_timeout_to_close']) == 1):?>
									<span class="wd_pp_timeout">0s</span>
								<?php endif;?>
								
								<a href="#close" class="close bClose" title="close"><i class="fa fa-times"></i></a>
							</div>
							<div class="wd_pp_body" style="<?php echo $popups_data['body_style'];?>">
								<?php echo do_shortcode($popups_data['the_content']);?>
							</div>
						</div>
					<?php 
				}
				
			}
			
		}
		
		
		public function wd_array_atts($pairs, $atts) {
			$atts = (array)$atts;
			$out = array();
			foreach($pairs as $name => $default) {
				if ( array_key_exists($name, $atts) ){
					if( strlen(trim($atts[$name])) > 0 ) $out[$name] = $atts[$name]; 
					else $out[$name] = $default;
				}
				else $out[$name] = $default;	
			}
			return $out;
		}
		
		public function wp_script_font(){
			wp_enqueue_script('jquery');
			
			wp_register_script('wd_bpopup_min', WD_URL_ASSESTS . 'js/jquery.bpopup.min.js', 'jquery', '1.1',false);
			wp_enqueue_script('wd_bpopup_min');
			
			wp_register_script('wd_popup_font', WD_URL_ASSESTS . 'js/wd-popup.js', 'jquery', '1.1',false);
			wp_enqueue_script('wd_popup_font');
			
			wp_register_style( 'wd_fa_css', '//maxcdn.bootstrapcdn.com/font-awesome/4.3.0/css/font-awesome.min.css', false, false );
			wp_enqueue_style( 'wd_fa_css' );
			
			wp_register_style( 'wd_pp_css', WD_URL_ASSESTS . 'css/wd-pp.style.css', false, false );
			wp_enqueue_style( 'wd_pp_css' );
			
		}
		
	}
}
global $WD_POPUP;
$WD_POPUP = new WD_Popup();
?>