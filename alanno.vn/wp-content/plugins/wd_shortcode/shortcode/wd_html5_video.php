<?php 
	if(!function_exists('wd_video_function')){
		function wd_video_function($atts,$content){
			extract(shortcode_atts(array(
				'use_cover'			=> '1'
				,'cover_url'		=>	''
				,'mp4'				=>	''
				,'webm'				=> ''
				,'ogv'				=>	''
				,'width'			=> '100%'
				,'height'			=> '480px'
				,'bg_color'			=> ""
				,'bg_opacity'		=> "0.15"
				,'auto_play'		=> "0"
				,'loop'				=> "1"
				,'backg_video'		=> "1"
				,'v_margin_top'		=> '0px'
				,'v_bg_color'		=> "none"
				,'bg_opacity'		=> "0.15"
				,'not_support_txt'	=>	'Your browser does not support the video tag.'
			),$atts));
			
			
			
			if ($cover_url!==''){
				$cover_url = wp_get_attachment_url($cover_url);
			}
			
			if ($mp4!==''){
				$mp4_arg = explode('|', $mp4);
				$mp4 = urldecode(substr($mp4_arg[0], 4));
			}
			if ($webm!==''){
				$webm_arg = explode('|', $webm);
				$webm = urldecode(substr($webm_arg[0], 4));
			}
			if ($ogv!==''){
				$ogv_arg = explode('|', $ogv);
				$ogv = urldecode(substr($ogv_arg[0], 4));
			}
			
			$bg_opacity = is_numeric($bg_opacity)? $bg_opacity: 0.15;
			
			$margin_top = (!isset($margin_top) || $margin_top == '')? 0 : $margin_top;
			$v_margin_top = (!isset($v_margin_top) || $v_margin_top == '')? "" : "margin-top:" . $v_margin_top;
			//handle features
			$id_ = "wd_video_" . rand();
			
			ob_start();
			if(absint($backg_video)) $position = "relative"; else $position = "relative";
			
			if($v_bg_color !== 'none'){
				$v_bg_color = ($v_bg_color == 'black')? "rgba(0,0,0, ".$bg_opacity.")" : "rgba(255,255,255, ".$bg_opacity.")";
			} else {
				$v_bg_color = "rgba(0,0,0,0)";
			}
			
			if(wp_is_mobile()) $auto_play = 0;
			
			if(absint($auto_play) == 1) {
				$bg_image = " background-image: url(".get_template_directory_uri()."/images/media/video_play_backg.png); background-color: " . $v_bg_color . "; background-repeat: repeat;";
				$content_display = " display: table;";
				$btn_play = "fa-pause";
			} elseif(absint($use_cover)) {
				$bg_color = ($bg_color !== '')? $bg_color : "rgba(0,0,0,0)";
				$bg_image = ($cover_url!=='')? " background-image: url(".$cover_url."); background-color: " . $bg_color . "; background-repeat: no-repeat;": '';
				$content_display = " display: table;";
				$btn_play = "fa-play";
			}
			
			
			?>
			<div class="<?php echo $id_;?><?php echo (absint($auto_play) == 1)? ' video_appear':'' ;?> pause" data-id="<?php echo $id_;?>" style="width: <?php echo $width;?>; height: <?php echo $height;?>; overflow: hidden; position: <?php echo $position;?>;">
				
				<div id="<?php echo $id_;?>_cover" style="position: absolute; width: 100%; padding: 25px 15px;min-height: 100px; height: 100%; z-index:13;<?php echo $bg_image;?> background-position: center center;">
					<div class="display-table" style="<?php echo esc_attr($content_display);?>"><p><?php echo do_shortcode($content);?></p></div>
					
				</div>
				<?php if( $mp4 !== '' || $ogv !== '' || $webm !== '' ):?>
				<video id="<?php echo $id_;?>_v" style="height: auto; min-width: 100%; min-height:100%;" <?php echo (absint($loop) == 1)? ' loop':'' ;?>>
					<?php if($mp4 !== ''):?><source src="<?php echo esc_url($mp4);?>" type="video/mp4"><?php endif;?>
					<?php if($ogv !== ''):?><source src="<?php echo esc_url($ogv);?>" type="video/ogg"><?php endif;?>
					<?php if($webm !== ''):?><source src="<?php echo esc_url($webm);?>" type="video/webm" /><?php endif;?>
					<?php echo esc_attr($not_support_txt);?>
				</video>
				
				<a href="javascript:void(0);" class="videoplay-btn-<?php echo $id_;?>" style="<?php echo esc_attr($v_margin_top);?>"><em class="fa fa-2x <?php echo esc_attr($btn_play);?>"></em></a>
				<?php endif;?>
			</div>
			
			<script type="text/javascript">
				
				jQuery(document).ready(function() {
					"use strict";
					
					var <?php echo $id_;?> = document.getElementById('<?php echo $id_;?>_v');
					jQuery('.<?php echo $id_?> video').prop("volume", 1);
					
					var v_bg = '<?php echo $v_bg_color;?>';
					var v_bg_img = '<?php echo get_template_directory_uri()."/images/media/video_play_backg.png";?>';
					
					var video_height = jQuery('.<?php echo $id_?> video').height();
					var v_box_height = jQuery('.<?php echo $id_?>').height();
					var video_margin = v_box_height - video_height;
					jQuery('.<?php echo $id_?> video').css('margin-top', video_margin );
					
					jQuery('.videoplay-btn-<?php echo $id_;?>').click(function(){
						
						if(<?php echo $id_;?>.paused) {
							jQuery('.<?php echo $id_;?>').removeClass('video_appear').addClass('video_appear');
							jQuery('.<?php echo $id_;?>').removeClass('pause').removeClass('play').addClass('play');
							jQuery('#<?php echo $id_;?>_cover').css({"background-color": v_bg, "background-image": "url("+v_bg_img+")", "background-repeat": "repeat"});
							/*jQuery(this).find('> *').hide();*/
							jQuery(this).find('em.fa').removeClass('fa-play').addClass('fa-pause');
							document.getElementById('<?php echo $id_;?>_v').play();
						} else {
							jQuery('.<?php echo $id_;?>').removeClass('video_appear');
							jQuery('.<?php echo $id_;?>').removeClass('pause').removeClass('play');
							<?php if(absint($use_cover) && $cover_url!==''):?>
							jQuery('#<?php echo $id_;?>_cover').css({"background-color": '<?php echo $bg_color; ?>', "background-image": "<?php echo "url(".$cover_url.")";?>", "background-repeat": "no-repeat"});
							<?php endif;?>
							jQuery(this).find('em.fa').removeClass('fa-pause').addClass('fa-play');
							document.getElementById('<?php echo $id_;?>_v').pause();
						}
					});
					
				});
			</script>
			<?php
			$output = ob_get_contents();
			ob_end_clean();
			rewind_posts();
			wp_reset_query();
			return $output;
		}
	}
	add_shortcode('wd_video','wd_video_function');
	
	
	function wd_soundcloud_shortocde( $atts, $content ){
		extract(shortcode_atts(array(
			'params'		=> "color=ff5500&auto_play=false&hide_related=false&show_comments=true&show_user=true&show_reposts=false"
			,'url'			=> ''
			,'width'		=> '100%'
			,'height'		=> '166'
			,'iframe'		=> 'true'
		),$atts));
		
		if( strcmp( trim( $iframe ), 'true' ) == 0 )
			return wd_soundcloud_iframe_widget( $atts );
		else 
			return wd_soundcloud_flash_widget( $atts );
	}
	
	
	function wd_soundcloud_iframe_widget($options) {
		$url = "https://w.soundcloud.com/player/?url=".$options["url"]."&".$options['params'];
		
		return sprintf('<iframe width="%s" height="%s" scrolling="no" frameborder="no" src="%s"></iframe>', $options['width'], $options['height'], esc_url( $url ) );
	}
	
	function wd_soundcloud_flash_widget( $options ){
		$url = "https://player.soundcloud.com/player.swf?url=".$options["url"]."&".$options['params'];
		
		return preg_replace('/\s\s+/', "", sprintf('<object width="%s" height="%s">
                                <param name="movie" value="%s"></param>
                                <param name="allowscriptaccess" value="always"></param>
                                <embed width="%s" height="%s" src="%s" allowscriptaccess="always" type="application/x-shockwave-flash"></embed>
                              </object>', $options['width'], $options['height'], esc_url( $url ), $options['width'], $options['height'], esc_url( $url )));
	}
	
	add_shortcode('soundcloud','wd_soundcloud_shortocde');
?>