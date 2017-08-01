<?php
	$selector_sc = 'feature';
	$shortcodes = get_shortcode_options();
	$shortcodes =  apply_filters('arr_shortcode_options', $shortcodes);
	$wd_content_shortcode = "[".$selector_sc;
?>
<div class="sc_container">
	<div class="sc_selector_area">
		<select name="sc_selector" class="wd_all_sc sc_selector">
			<option value=""><?php _e('Choose One','wpdance'); ?></option>
		<?php foreach($shortcodes as $sc){?>
			<option value="<?php echo esc_html($sc['value']); ?>"><?php echo esc_html($sc['name']); ?></option>
		<?php }?>
		</select>
	</div><!-- .sc_selector_area -->
	<div class="sc_options">
		<?php 
			foreach($shortcodes as $sc){
				if($sc['value'] == $selector_sc){
					if(count($sc['options']) > 0){
					?>
						<ul class="option_list" id="<?php echo $selector_sc;?>">
							<?php foreach($sc['options'] as $option){?>
							<li>
							   <span class="sc_option_label"><?php echo $label = sprintf( __( '%s','wpdance' ), esc_html($option['label']) ); ?></span>
							   <p>
								   <?php if($option['type'] == 'text'){?>
								   <input id="<?php echo esc_html($option['id']); ?>" class="wpdane_input<?php if( isset($option['class']) ) echo ' '.$option['class'];?>" name="<?php echo esc_html($option['name']); ?>"  type="text" value="<?php echo isset($option['default']) ? $option['default'] : ''; ?>"/>
								 
									<?php }elseif( esc_html($option['type']) == 'insert_slide' ){?>
										<div class="uploader">
											<input type="hidden" name="_sliders_slider" value="0">
											<a href="javascript:void(0)" class="button stag-metabox-table" name="_unique_name_button" id="_unique_name_button">Insert</a>
											<a href="javascript:void(0)" class="button clear-all-slides" name="clear-all-slides" id="clear-all-slides" style="display: none;">Clear</a>
										</div>					 
								   <?php }elseif($option['type'] == 'checkbox') {?>
								   <input id="<?php echo esc_html($option['id']); ?>" class="wpdane_input" name="<?php echo esc_html($option['name']);?>" type="checkbox" <?php echo (isset($option['default']) && $option['default']== '1') ? 'checked': ''; ?>/>
								   <?php }elseif($option['type'] == 'textarea'){?>
								   <textarea id="<?php echo $option['id']; ?>" class="wpdane_input" name="<?php echo esc_textarea($option['name']);?>"></textarea>
								   <?php }elseif($option['type'] == 'select'){?>
								   <select name="<?php echo esc_html($option['name']);?>" class="wpdane_input" id="<?php echo esc_html($option['id']);?>">
										<?php foreach($option['values'] as $value){?>
										<option value="<?php echo $value['value'];?>" <?php if(isset($option['default']) && $option['default'] == $value['value']):?>selected<?php endif;?>><?php echo ($value['label']);?></option>	
										<?php }?>
								   </select>
								   <?php }elseif($option['type'] == 'radio'){?>
										<?php foreach($option['values'] as $value){?>
										<label><input name="<?php echo $option['name']?>" class="wpdane_input<?php if($option['class']) echo ' '.$option['class'];?>" type="radio"  value="<?php echo $value['value'];?>" <?php if(isset($option['default']) && $option['default'] == $value['value']):?>checked="checked"<?php endif;?>><?php echo ($value['label']);?></label>
										<?php }?>
								   <?php }?>					   
								   <?php if( isset($option['after_text']) ){?>
								   <span class="after_text"><?php echo $option['after_text'];?></span>
								   <?php }?>
								   <!--
								   <?php //if(isset($option['illustrations'])){?>
										<?php //foreach($option['illustrations'] as $image){?>
											<p><img src="<?php //echo THEME_ADMIN_IMAGES.'/shortcodes_generator/'.$image['src'] ?>" class="<?php //echo $image['class'] ?>"/></p>
										<?php //}?>
								   <?php //}?>
								   -->
							   </p>
							</li>
							<?php 
								$temp_value = str_replace($selector_sc.'_','',$option['id']);
								$wd_content_shortcode .= ' '.$temp_value.'="{{'.$temp_value.'}}"';
							?>
							<?php }?>
						</ul>	
					<?php
					}
				}
			}
		?>
	</div><!-- .sc_options -->
	<?php //include_once('icon-list.php');?>
	<?php $wd_content_shortcode .= '][/'.$selector_sc.']'?>
	<div id="_wpdance_shortcode" class="hidden"><?php echo $wd_content_shortcode;?></div>
	<input type="button" href="#" id="sc_send_editor" value="Send"/>
</div><!-- .sc_container -->
<script type="text/javascript">
//<![CDATA[
function toggle_icons_block(){
	if(jQuery('select.sc_selector option:selected').val() == 'icon'){
		jQuery('#icons-block').show();
	}else{
		jQuery('#icons-block').hide();
	}
}


jQuery(document).ready(function($){
	function icon_to_editor(icon_string){
		
		window.send_to_editor(icon_string);
		//send_to_editor(icon_string);
	}
	function wd_shortcode_load(){
		var shortcode = $('#_wpdance_shortcode').text(),
		uShortcode = shortcode;
		var sc_id = $('.option_list').attr('id');
		// fill in the gaps eg {{param}}
		$('.wpdane_input').each(function() {
			var input = $(this),
			id = input.attr('id'),
			id = id.replace(sc_id+"_", ''),		// gets rid of the fusion_ prefix
			re = new RegExp("{{"+id+"}}","g");
			var value = input.val();
			if(value == null) {
			  value = '';
			}
			uShortcode = uShortcode.replace(re, value);
		});

		// adds the filled-in shortcode as hidden input
		$('#_wpdance_ushortcode').remove();
		$('.sc_options').prepend('<div id="_wpdance_ushortcode" class="hidden">' + uShortcode + '</div>');
	}
		wd_shortcode_load();
		// update on children value change
		$('.wpdane_input').live('change', function() {
			wd_shortcode_load();
		});
		
		// change shortcode when a user selects a shortcode from choose a dropdown field
		$('.wd_all_sc').change(function() {
			var name = $(this).val();
			var label = $(this).text();
			tb_show("WpDance Shortcodes", ajaxurl + "?action=wpdance_shortcodes");
			$('#TB_window').hide();
			/*if(name != 'select') {
				tinyMCE.activeEditor.execCommand("fusionPopup", false, {
					title: label,
					identifier: name
				});

				$('#TB_window').hide();
			}*/
		});
			
		$('#sc_send_editor').click(function(){
			var value = $('#_wpdance_ushortcode').html();
			window.send_to_editor(value);
		});
		
		var _custom_media = true,_orig_send_attachment = wp.media.editor.send.attachment;
		$('.stag-metabox-table').click(function(e) {
			var send_attachment_bkp = wp.media.editor.send.attachment;
			var button = $(this);
			_custom_media = true;
			wp.media.editor.send.attachment = function(props, attachment){
				//console.log(attachment);
				//console.log(props);
				var thumb_url = '';
				if( typeof(attachment.sizes.thumbnail) !== 'undefined' ){
					thumb_url = attachment.sizes.thumbnail.url;
				}else{
					thumb_url = attachment.sizes[props.size].url;
				}
				//var insert_url = attachment.sizes[props.size].url;
				var insert_url = attachment.sizes['full'].url;
				var link_url = props.linkUrl;
				if( props.link == 'file' ){
					link_url = attachment.url;
				}
				if( props.link == 'post' ){
					link_url = attachment.link;
				}	
				if( props.link == 'none' ){
					link_url = '#';
				}					
				var image_title = attachment.title;
				var slide_description = attachment.description; 
				var image_alt = attachment.alt;		
				var current_html = jQuery('#slideshow_content').val();
				build_html = current_html;
				if( current_html.length > 0 ){
					current_html += "\n";
				}				
				if ( _custom_media ) {
					
					build_html = '[slide';
					if( image_title.length > 0 ){
						build_html += ' image_title="'+ image_title +'"'
					}
					if( image_alt.length > 0 ){
						build_html += ' image_alt="'+ image_alt +'"'
					}
					if( insert_url.length > 0 ){
						build_html += ' image="'+ insert_url +'"'
					}
					if( link_url.length > 0 ){
						build_html += ' slide_link="'+ link_url +'"'
					}
					if( image_title.length > 0 ){
						build_html += ' title="'+ image_title +'"'
					}else{
						build_html += ' title="Slide Title Goes Here"'
					}
					if( slide_description.length > 0 ){
						build_html += ' description="'+ slide_description +'"'
					}else{
						build_html += ' description="Slide Description Goes Here"'
					}
					build_html += ' ]';	
					
					jQuery('#slideshow_content').val(current_html+build_html);
				} else {
					return _orig_send_attachment.apply( this, [props, attachment] );
				};
			}
			wp.media.editor.open(button);
			
			return false;
		});
		
		//bind editor upload image
		$('.add_media').on('click', function(){
			_custom_media = false;
		});
		toggle_icons_block();
		jQuery('select.sc_selector').change(function(){
			toggle_icons_block();
		});


	jQuery('.colorpicker_control').each(function(index,element){
		jQuery(element).colorpicker({
			// onChange: function (hsb, hex, rgb) {
				// jQuery(element).val('#' + hex);
			// }
		});
	});
	
	jQuery('.colorpicker_control_rgba').each(function(index,element){
		jQuery(element).colorpicker({ 'format':"rgba" });
	});	
	
	
});
//]]>
</script>