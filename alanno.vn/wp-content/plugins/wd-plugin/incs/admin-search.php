<?php 

if( !class_exists( 'WD_Admin_Search' ) ) {
	class WD_Admin_Search {
		
		private $options = array();
		
		private $admin_page = array(
			'parent_slug'	=> 'wd-admin',
			'page_title'	=> 'Ajax Search',
			'menu_title'	=> 'Ajax Search',
			'capability'	=> 'manage_options',
			'menu_slug'		=> 'wd-ajax-search'
		);
		
		function __construct() {
			add_action('admin_menu',  array( $this, 'register_admin_menu')  );
			
			add_action( 'wp_ajax_wdas_save_options', array( $this, 'wd_ajax_saveOption' ) );
			add_action( 'wp_ajax_nopriv_wdas_save_options', array( $this, 'wd_ajax_saveOption' ) );
			
			add_action( 'wp_ajax_wd_seach_result', array($this, 'ajax_seach_result'));
			add_action( 'wp_ajax_nopriv_wd_seach_result', array($this, 'ajax_seach_result'));
			
			add_shortcode('wd_woo_search',array($this, 'wd_woo_search_shortcode'));
			
			add_action('wp_enqueue_scripts',array($this,'wp_script_font'));
			add_action('admin_enqueue_scripts',array($this,'init_wp_script'));
			
		}
		
		function register_admin_menu(){
			add_submenu_page(	$this->admin_page['parent_slug'],
								$this->admin_page['page_title'],
								$this->admin_page['menu_title'],
								$this->admin_page['capability'],
								$this->admin_page['menu_slug'],
								array( $this, 'wd_ajax_search_admin_page_view' ) );
		}
		
		function wd_ajax_search_admin_page_view(){
			if( get_option( WD_AS_SLUG ) ) { 
				$this->options = json_decode(get_option( WD_AS_SLUG ));
			}
		
			include_once WD_TEMP_PATH . 'search-tempale.php';
		}
		
		function init_wp_script(){
			wp_enqueue_script('jquery');
			
			wp_enqueue_script( 'wd_ajax_search', WD_URL_ASSESTS . 'js/wd-admin-search.js', false, '1.0', true );
			wp_enqueue_script('wd_ajax_search');
		}
		
		public function wp_script_font(){
			wp_enqueue_script('jquery');
		
			wp_register_script('wd_woo_search_font', WD_URL_ASSESTS . 'js/wd_search_font.js', 'jquery', '1.1',false);
			wp_enqueue_script('wd_woo_search_font');
		}
		
		public $post_where_filter_args;
		
		public function wd_query_title_filter_string( $where, &$wp_query ) {
			global $wpdb;
			
			if( $search_term = $wp_query->get( 'wd_search_title' ) ) {
				$w_str = '1=0';
				$like_str = esc_sql( $wpdb->esc_like( $search_term ) );
				if( absint($this->post_where_filter_args['title']) ) 
					$w_str .= ' OR ('.$wpdb->posts.'.post_title LIKE \'%' . $like_str . '%\')';
				if( absint($this->post_where_filter_args['except']) ) 
					$w_str .= ' OR (post_excerpt LIKE \'%' . $like_str . '%\')';
				if( absint($this->post_where_filter_args['content']) ) 
					$w_str .= ' OR ('.$wpdb->posts.'.post_content LIKE \'%' . $like_str . '%\')';
				
				$where .= ' AND (('.$w_str.')) ';
			}
			
			return $where;
		}
		
		public function wd_query_title_filter($args, $title=1, $except=1, $content=1){
			$this->post_where_filter_args = array(
				'title'		=> $title,
				'except'	=> $except,
				'content'	=> $content
			);
			
			add_filter( 'posts_where', array( $this, 'wd_query_title_filter_string'), 10, 2 );
			$result = new WP_Query($args);
			remove_filter( 'posts_where', array( $this, 'wd_query_title_filter_string'), 10, 2 );
			return $result;
		}
		
		public function ajax_seach_result(){
		
			$s = $_POST['s'];
			$prod_limit = isset($_POST['prod_limit'])? $_POST['prod_limit']: 2;
			$blog_limit = isset($_POST['blog_limit'])? $_POST['blog_limit']: 3;
			$type 		= (isset($_POST['type']))? $_POST['type'] : "all";
			$taxonomy 	= isset( $_POST['taxonomy'] )? $_POST['taxonomy']: '';
			$term 		= isset( $_POST['term'] )? $_POST['term']: '';
			
			$args_product = array(
				'post_type' 		=> 'product',
				'posts_per_page'	=> $prod_limit,
				'post_status' 		=> 'publish',
				'orderby'     		=> 'title', 
				'order'       		=> 'ASC',
				'wd_search_title'	=> $s,
				'meta_query' 		=> array(
					array(
						'key'		=> '_visibility',
						'value' 	=> array( 'search', 'visible' ),
						'compare'	=> 'IN'
					),
					array(
						'key' => '_stock_status',
						'value' => 'outofstock',
						'compare' => '!='
					)
				)
			);
			
			if( strlen(trim($taxonomy)) > 0 && strlen(trim($term)) > 0 ) {
				$args_product['tax_query'] 			= array(
					array(
						'taxonomy' 		=> $taxonomy,
						'terms' 		=> array( esc_attr($term) ),
						'field' 		=> 'slug',
						'operator' 		=> 'IN'
					)
				);
			}
			
			$args_blog = array(
				'post_type' 		=> 'post',
				'posts_per_page'	=> $blog_limit,
				'post_status' 		=> 'publish',
				'orderby'     		=> 'title', 
				'order'       		=> 'ASC',
				'wd_search_title'	=> $s,
			);
			
			if($type == 'product') {
				$prod_result = $this->wd_get_result($args_product, $prod_limit);
			}
			if($type == 'post') {
				$blog_result = $this->wd_get_result($args_blog, $blog_limit);
			}
			if($type == 'all') {
				$prod_result = $this->wd_get_result($args_product, $prod_limit);
				$blog_result = $this->wd_get_result($args_blog, $blog_limit);
			}
			
			if(isset($prod_result) && isset($prod_result['res'])) {
				$arr['pro_res'] = $this->objectToArray($prod_result['res']->posts);
				if(isset($prod_result['res_more'])) {
					foreach($this->objectToArray($prod_result['res_more']->posts) as $v) {
						array_push($arr['pro_res'], $v);
					}
				}
				foreach($arr['pro_res'] as $key => $res) {
					$arr['pro_res'][$key]['src'] =  wp_get_attachment_image(get_post_thumbnail_id($res['ID']), 'shop_thumbnail');
					$arr['pro_res'][$key]['url'] = get_permalink($res['ID']);
					$catObject = wp_get_post_terms( $res['ID'], 'product_cat' );
					$arr['pro_res'][$key]['cat'] = $this->objectToArray($catObject);
					foreach($arr['pro_res'][$key]['cat'] as $k => $v) {
						$arr['pro_res'][$key]['cat'][$k]['url'] = get_term_link($v['term_id'], $v['taxonomy']);
					}
				}
			}
			if(isset($blog_result) && isset($blog_result['res'])) {
				$arr['blog_res'] = $this->objectToArray($blog_result['res']->posts);
				if(isset($blog_result['res_more'])) {
					foreach($this->objectToArray($blog_result['res_more']->posts) as $v) {
						array_push($arr['blog_res'], $v);
					}
				}
				foreach($arr['blog_res'] as $key => $res) {
					$arr['blog_res'][$key]['src'] =  wp_get_attachment_image(get_post_thumbnail_id($res['ID']), 'shop_thumbnail');
					$arr['blog_res'][$key]['url'] = get_permalink($res['ID']);
					$catObject = wp_get_post_terms( $res['ID'], 'category' );
					$arr['blog_res'][$key]['cat'] = $this->objectToArray($catObject);
					foreach($arr['blog_res'][$key]['cat'] as $k => $v) {
						$arr['blog_res'][$key]['cat'][$k]['url'] = get_term_link($v['term_id'], $v['taxonomy']);
					}
				}
			}

			die(json_encode($arr));
		}
		
		public function wd_get_result( $args = array(), $limit = 3 ){
			$result = array();
			$result['res'] = $this->wd_query_title_filter($args,1,0,0);
			if(absint($result['res']->found_posts) < absint($limit)){
				$args['posts_per_page'] = absint($limit) - absint($result['res']->found_posts);
				$result['res_more'] = $this->wd_query_title_filter($args,0,1,1);
			}
			return $result;
		}
		
		public function objectToArray($d) {if (is_object($d)) {$d = get_object_vars($d);}if (is_array($d)) {return array_map( array($this, 'objectToArray'), $d);}else {return $d;}}
		
		public function wd_woo_search_shortcode($atts,$content){
			extract(shortcode_atts(array(
				"limit" 	=> '3',
				"b_limit" 	=> '5',
				"type"		=> 'all',
				"timeout"	=> '200',
				"style"		=> 'light',
				"use_header_v4"	=> '0'
			), $atts));
			ob_start();
			
			if( get_option( WD_AS_SLUG ) ) {
				$data = json_decode(get_option( WD_AS_SLUG ));
				$def_search = $data->def_search;
				$limit = $data->product_res_num;
				$b_limit = $data->post_res_num;
				$type = $data->search_with;
				$timeout = $data->type_timeout;
				/*$use_header_v4 = $data->use_header_v4;*/
			}
			
			?>
			<div class="wd_woo_search_box">
				<?php if(absint($use_header_v4) == 0):?>
				<h1 class="wd_search_heading text-center"><?php _e('Search', 'wpdance');?></h1>
				<div class="line line-30 line-margin text-center"></div>
				<form role="search" method="get" autocomplete="off" id="wd_searchform" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
					<input type="text" name="s" id="s" <?php if(isset($_GET['s'])) echo "value=\"".esc_attr($_GET['s']) . "\""; ?> data-type="<?php echo esc_attr($type);?>" data-timeout="<?php echo absint($timeout);?>" data-prod_limit="<?php echo esc_attr($limit);?>" data-blog_limit="<?php echo esc_attr($b_limit);?>" data-site_url="<?php echo get_site_url();?>/" data-ajax_url="<?php echo admin_url( 'admin-ajax.php' )?>" />
					<div class="button_search"><button type="submit" title="<?php echo esc_attr__( 'Search', 'wpdance' ); ?>"><i class="fa fa-search"></i></button></div>
					<?php include_once( ABSPATH . 'wp-admin/includes/plugin.php' ); ?>
					<input type="hidden" name="post_type" value="<?php echo is_plugin_active('woocommerce/woocommerce.php')? $def_search : 'post'?>">
				</form>
				
				<?php else:?>
					<?php $this->wd_product_search_form($data);?>
				<?php endif;?>
				<nav class="list_result <?php echo esc_attr($style);?> woocommerce"></nav>
			</div>
			<?php
			$out_ = ob_get_contents();
			ob_end_clean();
			return $out_;
		}
		
		public function wd_product_search_form($data){
			$def_search = $data->def_search;
			$limit = $data->product_res_num;
			$b_limit = $data->post_res_num;
			$type = 'product';
			$timeout = $data->type_timeout;
			$use_header_v4 = $data->use_header_v4;
				
			$args = array(
				'number'     => '',
				'orderby'    => 'name',
				'order'      => 'ASC',
				'hide_empty' => true,
				'include'    => array()
			);
			$product_categories = get_terms( 'product_cat', $args );
			$categories_show = '<option value="">'.__('All Categories','wpdance').'</option>';
			$check = '';
			if(is_search()){
				if(isset($_GET['term']) && $_GET['term']!=''){
					$check = $_GET['term'];	
				}
			}
			$checked = '';
			foreach($product_categories as $category){
				if(isset($category->slug)){
					if(trim($category->slug) == trim($check)){
						$checked = 'selected="selected"';
					}
					$categories_show  .= '<option '.$checked.' value="'.$category->slug.'">'.$category->name.'</option>';
					$checked = '';
				}
			}
			?>
			<form role="search" method="get" autocomplete="off" id="wd_searchform" action="<?php echo esc_url( home_url( '/'  ) ); ?>">
				<select class="wd_search_product" name="term"><?php echo $categories_show; ?></select>
				<div class="wd_search_form">
					<label class="screen-reader-text" for="s"><?php _e( 'Search for:', 'wpdance' ); ?></label>
					<input type="text" name="s" id="s" <?php if(isset($_GET['s'])) echo "value=\"".esc_attr($_GET['s']) . "\""; ?> data-type="<?php echo esc_attr($type);?>" data-timeout="<?php echo absint($timeout);?>" data-prod_limit="<?php echo esc_attr($limit);?>" data-blog_limit="<?php echo esc_attr($b_limit);?>" data-site_url="<?php echo get_site_url();?>/" data-ajax_url="<?php echo admin_url( 'admin-ajax.php' )?>" placeholder="<?php _e('Search here...', 'wpdance');?>" />
					<div class="button_search"><button type="submit" title="<?php echo esc_attr__( 'Search', 'wpdance' ); ?>"><i class="fa fa-search"></i></button></div>
					<input type="hidden" name="post_type" value="product" />
					<input type="hidden" name="taxonomy" value="product_cat" />
				</div>
			</form>
			<?php 
			
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
			if( !get_option( WD_AS_SLUG ) ) {
				add_option( WD_AS_SLUG, $data, '', 'yes' );
			} else {
				update_option( WD_AS_SLUG, $data );
			}
		}
		
		public function delete_options(){
			delete_option( WD_AS_SLUG );
		}
		
	}
}
global $WD_AD_AS;
$WD_AD_AS = new WD_Admin_Search();
?>