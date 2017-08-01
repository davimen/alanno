<?php 
if(!function_exists ('wd_social')){
	function wd_social($atts,$content){
		extract(shortcode_atts(array(
			'style'		=>	'def',
			'params'	=> ''
		),$atts));
		
		if( strlen( $params ) == 0 ) return "";
		$pr_args = explode( ',', $params );
		
		ob_start();
		?>
		<div class="social-icons">
			<ul>
				
			<?php 
			foreach( $pr_args as $prs ){
				$pr = explode( '|', $prs );
				if( isset( $pr[0] ) ) {
					$id = isset( $pr[1] ) && strlen( $pr[1] ) > 0 ? $pr[1] : '#';
					switch( $pr[0] ) {
						case 'facebook':
							$link = "http://www.facebook.com/".$id;
							$icon = "facebook";
							break;
						case 'twitter':
							$link = "http://twitter.com/".$id;
							$icon = "twitter";
							break;
						case 'google':
							$link = "https://plus.google.com/u/0/".$id;
							$icon = "google-plus";
							break;
						case 'pinterest':
							$link = "http://www.pinterest.com/".$id;
							$icon = "pinterest";
							break;
						case 'instagram':
							$link = "http://instagram.com/".$id;
							$icon = "instagram";
							break;
						case 'youtube':
							$link = "http://www.youtube.com/".$id;
							$icon = "youtube";
							break;
						case 'linkedin':
							$link = "https://www.linkedin.com/pub/".$id;
							$icon = "linkedin";
							break;
					}
				?>
				<li class="icon-<?php echo esc_attr($icon);?>"><a href="<?php echo esc_url($link);?>" target="_blank" title="<?php _e('Become our fan', 'wpdance'); ?>" ><i class="fa fa-<?php echo esc_attr($icon);?>"></i></a></li>	
				<?php 
				}
			}
			?>
				
			</ul>
		</div>
		<?php 
		$result = ob_get_clean();
		return $result;
	}
}
add_shortcode('wd_social','wd_social');
?>