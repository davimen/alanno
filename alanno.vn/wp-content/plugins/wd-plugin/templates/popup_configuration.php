<?php 
	global $post;
	$datas = unserialize(get_post_meta($post->ID, $this->meta_post_pp_slug,true));
	$datas = $this->wd_array_atts(array(
		"popup_location" 			=> 'home',
		"popup_enable" 				=> '0',
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
		"popup_thumb_size"			=> 'auto',
		"popup_thumb_pos"		=> 'center center',
		"popup_location_cus_ids"	=> ''
	),$datas);							
?>
<div class="popup_config_wrapper">
	<div class="popup_config_wrapper_inner">
		<input type="hidden" value="1" name="<?php echo $this->options['cf_slug']; ?>">
		<?php wp_nonce_field( "_update_wd_popup_config", "nonce_wd_popup_config" ); ?>
		
		<div class="wd_popup_conf_group">
			<div class="wd_popup_header"><h3>Includ this Popup</h3></div>
			<div class="wd_popup_body">
				<div class="first input-group">
					<label for="wd_popup_enable"><?php _e('Includ this Popup','wpdance');?> : </label>
					<div class="input">
						<input class="wd_checkbox" type="checkbox" name="popup_enable" <?php if( strcmp($datas['popup_enable'],'1') == 0 ) echo "checked";?> id="wd_popup_enable" value="1" /> 
					</div>
					<span class="wd_message_txt">Enabld/Disable this popup</span>
				</div>
				
				<div class="last input-group">
					<label for="wd_popup_hide_title"><?php _e('Hide Title','wpdance');?> : </label>
					<div class="input">
						<input class="wd_checkbox" type="checkbox" name="popup_hide_title" <?php if( strcmp($datas['popup_hide_title'],'1') == 0 ) echo "checked";?> id="wd_popup_hide_title" value="1" /> 
					</div>
					<span class="wd_message_txt">Show/Hide this popup title</span>
				</div>
				
			</div>
		</div>
		
		<div class="wd_popup_conf_group">
			<div class="wd_popup_header"><h3>Timeout to close</h3></div>
			<div class="wd_popup_body">
				<div class="first input-group">
					<label for="wd_popup_timeout_to_close"><?php _e('Enable timeout to close','wpdance');?> : </label>
					<div class="input">
						<input class="wd_checkbox" type="checkbox" name="popup_timeout_to_close" <?php if( strcmp($datas['popup_timeout_to_close'],'1') == 0 ) echo "checked";?> id="wd_popup_timeout_to_close" value="1" /> 
					</div>
					<span class="wd_message_txt">Enabld/Disable timeout to close</span>
				</div>
				
				<div class="input-group">
					<label for="wd_popup_timeout_delay"><?php _e('Delay time','wpdance');?> : </label>
					<div class="input">
						<input class="wd_checkbox" min="0" type="number" name="popup_timeout_delay" id="wd_popup_timeout_delay" value="<?php echo absint($datas['popup_timeout_delay'])?>" />
					</div>
					<span class="wd_message_txt">(s)</span>
				</div>
				
			</div>
		</div>
		
		<div class="wd_popup_conf_group">
			<div class="wd_popup_header"><h3>Where to show</h3></div>
			<div class="wd_popup_body">
				<div class="first input-group">
					<label for="wd_popup_location_home"><?php _e('Enable on Home Page','wpdance');?> : </label>
					<div class="input">
						<input type="radio" name="popup_location" <?php if( strcmp($datas['popup_location'],'home') == 0 ) echo "checked";?> id="wd_popup_location_home" value="home" />
					</div>
					<span class="wd_message_txt">Show popup on home page</span>
				</div>
				
				<div class="input-group">
					<label for="wd_popup_location_all"><?php _e('Enable on All Pages','wpdance');?> : </label>
					<div class="input">
						<input type="radio" name="popup_location" <?php if( strcmp($datas['popup_location'],'all') == 0 ) echo "checked";?> id="wd_popup_location_all" value="all" />
					</div>
					<span class="wd_message_txt">Show Popup on entire site</span>
				</div>
				
				<div class="input-group">
					<label for="wd_popup_location_cus"><?php _e('Enable on Custom Pages','wpdance');?> : </label>
					<div class="input">
						<input type="radio" class="wd_enable_to_show" data-class_show="wd_pages_id" name="popup_location" <?php if( strcmp($datas['popup_location'],'custom') == 0 ) echo "checked";?> id="wd_popup_location_cus" value="custom" />
					</div>
					<span class="wd_message_txt">Show Popup on selected Pages/Posts</span>
					
				</div>
				
				<div class="input-group <?php if( strcmp($datas['popup_location'],'custom') !== 0 ) echo "wd_hide";?> wd_pages_id">
					<label for="wd_popup_location_cus_ids"><?php _e('Page IDs','wpdance');?> : </label>
					<div class="input">
						<input type="text" name="popup_location_cus_ids" id="wd_popup_location_cus_ids" value="<?php echo esc_attr($datas['popup_location_cus_ids']);?>" />
					</div>
					<span class="wd_message_txt">Ex: <i>{id}, {page_slug}</i></span>
					
				</div>
				
			</div>
		</div>
		
		<div class="wd_popup_conf_group">
			<div class="wd_popup_header"><h3>Popup skin</h3></div>
			<div class="wd_popup_body">
				<div class="input-group">
					<label for="wd_popup_skin"><?php _e('Skin','wpdance');?> : </label>
					<div class="input">
						<select name="popup_skin" id="wd_popup_skin">
							<option value="k1" <?php if( strcmp(trim($datas['popup_skin']), 'k1' ) == 0 ) echo "selected";?>>Skin 1</option>
							<option value="k2" <?php if( strcmp(trim($datas['popup_skin']), 'k2' ) == 0 ) echo "selected";?>>Skin 2</option>
						</select>
					</div>
					<span class="wd_message_txt">choonse skin for popup</span>
				</div>
				<div class="input-group">
					<label for="wd_popup_body_padd"><?php _e('Body padding','wpdance');?> : </label>
					<div class="input">
						<input type="text" name="popup_body_padd" id="wd_popup_body_padd" value="<?php echo $datas['popup_body_padd']?>" />
					</div>
					<span class="wd_message_txt">[top right bottom left]</span>
				</div>
			</div>
		</div>
		
		<div class="wd_popup_conf_group">
			<div class="wd_popup_header"><h3>Popup size</h3></div>
			<div class="wd_popup_body">
				<div class="first input-group">
					<label for="wd_popup_size_width"><?php _e('Popup Width','wpdance');?> : </label>
					<div class="input">
						<input type="number" name="popup_width" id="wd_popup_size_width" value="<?php echo absint($datas['popup_width'])?>" />
					</div>
					<span class="wd_message_txt">px</span>
				</div>
				
				<div class="last input-group">
					<label for="wd_popup_size_height"><?php _e('Popup Hieght','wpdance');?> : </label>
					<div class="input">
						<input type="number" name="popup_height" id="wd_popup_size_height" value="<?php echo absint($datas['popup_height'])?>" />
					</div>
					<span class="wd_message_txt">px</span>
				</div>
				
			</div>
		</div>
		
		<div class="wd_popup_conf_group">
			<div class="wd_popup_header"><h3>Background options</h3></div>
			<div class="wd_popup_body">
				<div class="first input-group">
					<label for="wd_popup_thumb_color"><?php _e('Background color','wpdance');?> : </label>
					<div class="input">
						<input name="popup_thumb_color" type="text" data-default-color="#ffffff" class="wd_color_picker" id="wd_popup_thumb_color" value="<?php echo esc_attr($datas['popup_thumb_color'])?>" />
					</div>
					<span class="wd_message_txt">set background color for popup</span>
				</div>
				
				<div class="input-group">
					<label for="wd_popup_thumb_repeat"><?php _e('Background color','wpdance');?> : </label>
					<div class="input">
						<select name="popup_thumb_repeat" id="wd_popup_thumb_repeat">
							<option value="no-repeat" <?php if( strcmp(trim($datas['popup_thumb_repeat']), 'no-repeat' ) == 0 ) echo "selected";?>>No repeat</option>
							<option value="repeat" <?php if( strcmp(trim($datas['popup_thumb_repeat']), 'repeat' ) == 0 ) echo "selected";?>>Repeat</option>
							<option value="repeat-y" <?php if( strcmp(trim($datas['popup_thumb_repeat']), 'repeat-y' ) == 0 ) echo "selected";?>>Repeat y</option>
							<option value="repeat-x" <?php if( strcmp(trim($datas['popup_thumb_repeat']), 'repeat-x' ) == 0 ) echo "selected";?>>Repeat x</option>
						</select>
					</div>
					<span class="wd_message_txt">set background repeat for popup</span>
				</div>
				
				<div class="input-group">
					<label for="wd_popup_thumb_size"><?php _e('Background size','wpdance');?> : </label>
					<div class="input">
						<select name="popup_thumb_size" id="wd_popup_thumb_size">
							<option value="auto" <?php if( strcmp(trim($datas['popup_thumb_size']), 'auto' ) == 0 ) echo "selected";?>>Auto</option>
							<option value="cover" <?php if( strcmp(trim($datas['popup_thumb_size']), 'cover' ) == 0 ) echo "selected";?>>Cover</option>
							<option value="contain" <?php if( strcmp(trim($datas['popup_thumb_size']), 'contain' ) == 0 ) echo "selected";?>>Contain</option>
						</select>
					</div>
					<span class="wd_message_txt">set background size for popup</span>
				</div>
				
				<div class="input-group">
					<label for="wd_popup_thumb_pos"><?php _e('Background position','wpdance');?> : </label>
					<div class="input">
						<select name="popup_thumb_pos" id="wd_popup_thumb_pos">
							<option value="top left" <?php if( strcmp(trim($datas['popup_thumb_pos']), 'top left' ) == 0 ) echo "selected";?>>Top left</option>
							<option value="top center" <?php if( strcmp(trim($datas['popup_thumb_pos']), 'top center' ) == 0 ) echo "selected";?>>Top center</option>
							<option value="top right" <?php if( strcmp(trim($datas['popup_thumb_pos']), 'top right' ) == 0 ) echo "selected";?>>Top right</option>
							<option value="center left" <?php if( strcmp(trim($datas['popup_thumb_pos']), 'center left' ) == 0 ) echo "selected";?>>Center left</option>
							<option value="center center" <?php if( strcmp(trim($datas['popup_thumb_pos']), 'center center' ) == 0 ) echo "selected";?>>Center center</option>
							<option value="center right" <?php if( strcmp(trim($datas['popup_thumb_pos']), 'center right' ) == 0 ) echo "selected";?>>Center right</option>
							<option value="bottom left" <?php if( strcmp(trim($datas['popup_thumb_pos']), 'bottom left' ) == 0 ) echo "selected";?>>Bottom left</option>
							<option value="bottom center" <?php if( strcmp(trim($datas['popup_thumb_pos']), 'bottom center' ) == 0 ) echo "selected";?>>Bottom center</option>
							<option value="bottom right" <?php if( strcmp(trim($datas['popup_thumb_pos']), 'bottom right' ) == 0 ) echo "selected";?>>Bottom right</option>
						</select>
					</div>
					<span class="wd_message_txt">set background position for popup</span>
				</div>
				
			</div>
		</div>
		
		<div class="wd_popup_conf_group">
			<div class="wd_popup_header"><h3>Cookie</h3></div>
			<div class="wd_popup_body">
				<div class="input-group">
					<label for="wd_popup_cookie_delay"><?php _e('Time delay','wpdance');?> : </label>
					<div class="input">
						<select name="popup_cookie_delay" id="wd_popup_cookie_delay">
							<option <?php if( absint($datas['popup_cookie_delay'] ) == 0 ) echo "selected";?> value="0">No cookie</option>
							<option <?php if( absint($datas['popup_cookie_delay'] ) == 5 ) echo "selected";?> value="5">5m</option>
							<option <?php if( absint($datas['popup_cookie_delay'] ) == 15 ) echo "selected";?> value="15">15m</option>
							<option <?php if( absint($datas['popup_cookie_delay'] ) == 30 ) echo "selected";?> value="30">30m</option>
							<option <?php if( absint($datas['popup_cookie_delay'] ) == 60 ) echo "selected";?> value="60">1h</option>
							<option <?php if( absint($datas['popup_cookie_delay'] ) == 180 ) echo "selected";?> value="180">3h</option>
							<option <?php if( absint($datas['popup_cookie_delay'] ) == 720 ) echo "selected";?> value="720">12h</option>
							<option <?php if( absint($datas['popup_cookie_delay'] ) == 1440 ) echo "selected";?> value="1440">1d</option>
						</select>
					</div>
					
					<span class="wd_message_txt">Popup hidden in a period of time</span>
				</div>
			</div>
		</div>
		
		
	</div>
</div>
