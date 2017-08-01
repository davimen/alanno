<?php
global $post,$wd_custom_size;
wp_nonce_field( 'wd_slide_box', 'wd_slide_box_nonce' );
//$portfolio_slider = get_post_meta($post->ID,'wd_portfolio_slider',true);
$portfolio_slider =get_metadata('post', $post->ID, 'wd_portfolio_slider', true);
$portfolio_slider = unserialize($portfolio_slider);

//$portfolio_slider_config = get_post_meta($post->ID,'wd_slide_config',true);
$portfolio_slider_config =get_metadata('post', $post->ID, 'wd_slide_config', true);
$portfolio_slider_config = unserialize($portfolio_slider_config);
//$portfolio_slider_config = wd_array_atts(array(
//												"portfolio_slider_config_autoslide" => 1
//												,"portfolio_slider_config_size" => 'slider'
//											),unserialize($portfolio_slider_config));
//echo '<pre>'; print_r($portfolio_slider_config); echo '</pre>';
if(!isset($portfolio_slider_config['portfolio_slider_config_autoslide']) || $portfolio_slider_config['portfolio_slider_config_autoslide'] == '') {
	$portfolio_slider_config['portfolio_slider_config_autoslide'] = 1;
}
if(!isset($portfolio_slider_config['portfolio_slider_config_size']) || $portfolio_slider_config['portfolio_slider_config_size'] == '') {
	$portfolio_slider_config['portfolio_slider_config_size'] ='slider';
}
?>
<div class="show-shortcode">
	<input type="hidden" name="slide-id" id="slide-id" value="<?php echo $post->ID;?>">
	<p>
		<span id="carousel-shortcode" name="carousel-shortcode">
			Slider Shortcode : <?php echo "[slider id=\"{$post->ID}\"]";?><br>
		</span>
	</p>
</div>
<br>
<div class="shortcode-config">
	<input type="hidden" name="slide-id" id="slide-id" value="<?php echo $post->ID;?>">

	
	<label for="carousel-shortcode">Auto Slide</label>
	<span id="carousel-shortcode" name="carousel-shortcode">
		<select name="portfolio_slider_config_autoslide" id="portfolio_slider_config_autoslide">
			<option value="0" <?php if( (int)$portfolio_slider_config['portfolio_slider_config_autoslide'] == 0 ) echo "selected";?>>No</option>	
			<option value="1" <?php if( (int)$portfolio_slider_config['portfolio_slider_config_autoslide'] == 1 ) echo "selected";?>>Yes</option>
		</select>
	</span>	
	<br>
	
	
	<label for="carousel-size">Slide Size</label>
	<span id="carousel-shortcode" name="carousel-shortcode">
		<select name="portfolio_slider_config_size">
			<option value="slideshow" <?php if( strcmp(esc_html($portfolio_slider_config['portfolio_slider_config_size']),'slideshow') == 0 ) echo "selected";?>>Slideshow ( 960x350 )</option>
			<option value="slider" <?php if( strcmp(esc_html($portfolio_slider_config['portfolio_slider_config_size']),'slider') == 0 ) echo "selected";?>>Slider 1( 140x110 )</option>
			<option value="blog_thumb" <?php if( strcmp(esc_html($portfolio_slider_config['portfolio_slider_config_size']),'blog_thumb') == 0 ) echo "selected";?>>Slider 2( 280x246 )</option>
			<option value="prod_midium_thumb_1" <?php if( strcmp(esc_html($portfolio_slider_config['portfolio_slider_config_size']),'prod_midium_thumb_1') == 0 ) echo "selected";?>>Slider 3( 510x652 )</option>
			<option value="prod_midium_thumb_2" <?php if( strcmp(esc_html($portfolio_slider_config['portfolio_slider_config_size']),'prod_midium_thumb_2') == 0 ) echo "selected";?>>Slider 4( 366x360 )</option>
			<option value="prod_small_thumb" <?php if( strcmp(esc_html($portfolio_slider_config['portfolio_slider_config_size']),'prod_small_thumb') == 0 ) echo "selected";?>>Slider 5( 141x141 )</option>
			<option value="related_thumb" <?php if( strcmp(esc_html($portfolio_slider_config['portfolio_slider_config_size']),'related_thumb') == 0 ) echo "selected";?>>Slider 6( 190x122 )</option>			
			<option value="custom_size_1" <?php if( strcmp(esc_html($portfolio_slider_config['portfolio_slider_config_size']),'custom_size_1') == 0 ) echo "selected";?>>Custom Size 1( <?php echo $wd_custom_size[0][0],"x",$wd_custom_size[0][1];?> )</option>			
			<option value="custom_size_2" <?php if( strcmp(esc_html($portfolio_slider_config['portfolio_slider_config_size']),'custom_size_2') == 0 ) echo "selected";?>>Custom Size 2( <?php echo $wd_custom_size[1][0],"x",$wd_custom_size[1][1];?> )</option>			
			<option value="custom_size_3" <?php if( strcmp(esc_html($portfolio_slider_config['portfolio_slider_config_size']),'custom_size_3') == 0 ) echo "selected";?>>Custom Size 3( <?php echo $wd_custom_size[2][0],"x",$wd_custom_size[2][1];?> )</option>			
		</select>
	</span>	
	<br>	

</div>

<br>
<div class="uploader">
	<input type="hidden" name="_sliders_slider" value="1"/>
	<a href="javascript:void(0)" class="button stag-metabox-table" name="_unique_name_button" id="_unique_name_button"/>Insert</a>
	<a href="javascript:void(0)" class="button clear-all-slides" name="clear-all-slides" id="clear-all-slides"/>Clear</a>
	<div class="sortable-wrapper">
		<ul id="sortable">
			<?php
			if( is_array($portfolio_slider) && count($portfolio_slider) > 0 ):
				foreach( $portfolio_slider as $single_slider ):
					$_image_url = wp_get_attachment_image_src( $single_slider['thumb_id'], 'full', false );
					$_image_url = $_image_url[0];
					$_thumb_url = wp_get_attachment_image_src( $single_slider['thumb_id'], 'thumbnail', false );
					$_thumb_url = $_thumb_url[0];
			?>
				<li>
					<div id="image-value<?php echo $single_slider['id'];?>" class="hidden lightbox-image">
						<img  class="lightbox-preview-img" src="<?php echo $_image_url;?>" alt="<?php echo $single_slider['alt'];?>" title="<?php echo $single_slider['title'];?>">
						<input type="hidden" value="<?php echo $single_slider['id'];?>" name="element_id[]" class="inline-element element_id">
						<input type="hidden" value="<?php echo $_image_url;?>" name="element_image_url[]" id="element_image_url" class="inline-element insert_url">
						<input type="hidden" value="<?php echo $single_slider['thumb_id'];?>" name="thumb_id[]" id="thumb_id" class="inline-element element_thumb_id">
						<input type="hidden" value="<?php echo $_thumb_url;?>" name="thumb_url[]" id="thumb_url" class="inline-element element_thumb">
						<p><span class="label">Slide Url</span><input type="text" value="<?php echo esc_url($single_slider['url']);?>" name="element_url[]" class="inline-element link_url"></p>
						<p><span class="label">Image Title</span><input type="text" value="<?php echo esc_html($single_slider['title']);?>" name="element_title[]" class="inline-element image_title "></p>
						<p><span class="label">Image Alt</span><input type="text" value="<?php echo esc_html($single_slider['alt']);?>" name="element_alt[]" class="inline-element image_alt"></p>
						<p><span class="label">Slide Title</span><input type="text" value="<?php echo esc_html($single_slider['slide_title']);?>" name="slide_title[]" class="inline-element slide_title"></p>
						<p><span class="label">Slide Contents</span><textarea name="slide_content[]" class="inline-element slide_content"><?php echo esc_textarea($single_slider['slide_content']);?></textarea></p>						
						<div class="btn fancy-button-wrapper">
							<a href="javascript:void(0)" class="button save-slide" name="save-slide"/>Save</a>
							<a href="javascript:void(0)" class="button save-slide" name="close-slide"/>Close</a>
						</div>
					</div>
					
					
					<p class="image-wrappper">
						<img  class="preview-img" src="<?php echo $_thumb_url;?>" alt="<?php echo $single_slider['alt'];?>" title="<?php echo esc_html($single_slider['title']);?>" width="120" height="120">
						<a href="#image-value<?php echo $single_slider['id'];?>" class="preview-img-edit">Edit</a>
						<a href="javascript:void(0)" class="preview-img-remove">Del</a>
					</p>
				</li>						
			<?php	
				endforeach;		
			endif;	
			?>

		</ul> 
	</div>
  
</div>
<script type="text/javascript">
//<![CDATA[
	function sort_list_images(){
		jQuery( "#sortable" ).sortable();
	}
    jQuery(document).ready(function($){

		clear_button = $('#clear-all-slides');
		
		if( $('#sortable > li').length > 0 )
			clear_button.show();
		else
			clear_button.hide();
		
		clear_button.click(function(event){
			$('#sortable').html('');
			clear_button.hide();
		});
		
		count_id = '<?php echo rand(0,1000),time()?>';
		count_id = parseInt(count_id); 
	var ready_lightbox = false;
	fancy = $(".preview-img-edit").fancybox({
		'minWidth' : 450
		,'minHeight' : 450
		,beforeLoad : function(){
			if(	ready_lightbox ){
			}			
		}
		,beforeClose  : function(){
			ready_lightbox = false;
		}
	});

	$(".save-slide").live('click',function(){
		$('.fancybox-close').trigger('click');
	});	

	

	$( "#sortable" ).disableSelection();	
		sort_list_images();
		var _custom_media = true,_orig_send_attachment = wp.media.editor.send.attachment;
		$('.stag-metabox-table').click(function(e) {
			var send_attachment_bkp = wp.media.editor.send.attachment;
			var button = $(this);
			_custom_media = true;
			wp.media.editor.send.attachment = function(props, attachment){
				console.log(attachment);
				//console.log(props);
				if( attachment.type == 'image' ){
					var thumb_id  = attachment.id;
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
					build_html = '';
					if ( _custom_media ) {
						count_id = count_id + 1;
						build_html += '<div id="image-value' + count_id + '" class="hidden lightbox-image">';
						build_html += '<img  class="lightbox-preview-img" src="' + insert_url + '" alt="' + image_alt + '" title="' + image_title + '">';
						build_html += '<input type="hidden" value="' + count_id + '" name="element_id[]" class="inline-element element_id">';
						build_html += '<input type="hidden" value="' + thumb_url + '" name="thumb_url[]" id="thumb_url" class="inline-element element_thumb">';
						build_html += '<input type="hidden" value="' + thumb_id + '" name="thumb_id[]" id="thumb_id" class="inline-element element_thumb_id">';
						build_html += '<input type="hidden" value="' + insert_url + '" id="element_image_url" name="element_image_url[]" class="inline-element insert_url">';
						build_html += '<p><span class="label">Slide Url</span><input type="text" value="' + link_url + '" name="element_url[]" class="inline-element link_url"></p>';
						build_html += '<p><span class="label">Image Title</span><input type="text" value="' + image_title + '" name="element_title[]" class="inline-element image_title "></p>';
						build_html += '<p><span class="label">Image Alt</span><input type="text" value="' + image_alt + '" name="element_alt[]" class="inline-element image_alt"></p>';
						build_html += '<p><span class="label">Slide Title</span><input type="text" value="' + image_title + '" name="slide_title[]" class="inline-element slide_title"></p>';
						build_html += '<p><span class="label">Slide Contents</span><textarea name="slide_content[]" class="inline-element slide_content">'+slide_description+'</textarea></p>';
						build_html += '<div class="btn fancy-button-wrapper"><a href="javascript:void(0)" class="button save-slide" name="save-slide"/>Save</a>';
						build_html += '<a href="javascript:void(0)" class="button save-slide" name="close-slide"/>Close</a></div>';
						build_html += '</div>';
						
						
						build_html += '<p class="image-wrappper">';
						build_html += '<img  class="preview-img" src="' + thumb_url + '" alt="' + image_alt + '" title="' + image_title + '" width="120" height="120">';
						build_html += '<a href="#image-value' + count_id + '" class="preview-img-edit">Edit</a>';
						build_html += '<a href="javascript:void(0)" class="preview-img-remove">Del</a>';
						build_html += '</p>';
						
						jQuery('<li class="ui-state-default"></li>').html(build_html).appendTo('#sortable');
						clear_button.show();

					} else {
						return _orig_send_attachment.apply( this, [props, attachment] );
					};
				}
			}
			wp.media.editor.open(button);
			sort_list_images();
			
			return false;
		});
		
		//bind editor upload image
		$('.add_media').on('click', function(){
			_custom_media = false;
		});
		
		//remove thumb function
		$('.image-wrappper > .preview-img-remove').live('click',function(){
			$(this).parent().parent().remove();
			if( $('#sortable > li').length > 0 )
				clear_button.show();
			else
				clear_button.hide();			
			sort_list_images();
		});
    });
//]]>	
</script>