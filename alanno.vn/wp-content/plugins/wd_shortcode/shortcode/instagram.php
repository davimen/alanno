<?php

if(!function_exists('wd_instagram_photo_shortcode')){
	function wd_instagram_photo_shortcode($atts,$content){
		extract(shortcode_atts(array(
			'title'				=> "" 
			,'limit' 			=> 6
			,'columns'			=> 6 
			,'instag_user'		=> "#wpdance" 
		),$atts));
		
		delete_transient( 'wd_instagram_transient' );
		
		if( strlen( $instag_user ) > 0 ) {
			$instagram = new Instagram(array(
				'apiKey'      => '5a9055959fc847e79466bda92f3ec685',
				'apiSecret'   => '42c523441bca4cac89be952e1a519f1a',
				'apiCallback' => ''
			));
			
			if( strcmp( substr( $instag_user, 0, 1 ), '#' ) == 0 ) {
				$media = $instagram->getTagMedia( substr( $instag_user, 1 ), $limit );
				
			} else {
				$user = $instagram->searchUser( $instag_user );
				
				if( empty( $user ) ) return "Instagram username not avairable!";
				$u_id = '';
				foreach( $user->data as $us ){
					if(strcmp( $us->username, $instag_user ) == 0) $u_id = esc_attr($us->id);
				}
				if( strlen($u_id) == 0 ) return "Instagram username not avairable!";
				$media = $instagram->getUserMedia( $u_id, $limit );
			}
			
		} else {
			return;
		}
		
		$instag_transient = get_transient( 'wd_instagram_transient' ); 
		
		if( empty( $instag_transient ) || strlen( trim( $instag_transient ) ) == 0 ) {
			$rand_id = "wd_instagram_". rand(0,1000);
			ob_start();
			?>
			<div class="wd_instagram_photos_slider middle_center wd-loading">
				<div id="<?php echo esc_attr( $rand_id );?>">
					<?php 
					foreach( $media->data as $photo ){
					?>
					<a target="_blank" class="effect_color" href="<?php echo esc_url( $photo->link );?>">
						<img src="<?php echo esc_url( $photo->images->low_resolution->url );?>" alt="instag image" />
					</a>
					<?php }?>
				</div>
			</div>
			
			<script type='text/javascript'>
				//<![CDATA[
					jQuery(document).ready(function() {
						"use strict";
						var temp_visible = <?php echo absint($columns);?>;
						
						var row = 1;
						var item_width = 70;
						
						var show_nav = true;
						var prev,next,pagination;

						var show_icon_nav = false;
						
						var object_selector = "#<?php echo $rand_id?>";
						var autoplay = false;
						generate_horizontal_slide(temp_visible,row,item_width,show_nav,show_icon_nav,autoplay,object_selector, false, [2,3,4,5,6]);
					});
				//]]>	
				</script>
			
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			if( count($media->data) > 0 ) set_transient( 'wd_instagram_transient', $output, 60*60*12 );
		} else {
			$output = $instag_transient;
			
		}
		
		return $output;
	}
}
add_shortcode('wd_instagram','wd_instagram_photo_shortcode');
?>