<?php
	global $post;
	
	$_wd_countdown_use_yes = '';
	$_wd_countdown_use_no = '';
	
	$_countdown_config = get_post_meta( $post->ID, 'wd_countdown', true );
	
	if( strlen($_countdown_config) > 0 ){
		$_countdown_config = unserialize($_countdown_config);
		
		$wd_countdown_use = $_countdown_config['countdown_use'];
		$wd_countdown_text = $_countdown_config['countdown_text'];
	}
	
	if (isset($wd_countdown_use) && $wd_countdown_use == 1)
		$_wd_countdown_use_yes = 'selected="selected"';
	else
		$_wd_countdown_use_no = 'selected="selected"';
?>

<div class="select-layout postbox">
	<div class="panel woocommerce_options_panel">
		<div class="options_group">
			<p class="form-field _sku_field ">
				<label for="wd_countdown_use">Use countdown?</label>
				<select name="wd_countdown_use" id="wd_countdown_use">
					<option value="1"<?php echo $_wd_countdown_use_yes; ?>>Yes</option>
					<option value="0"<?php echo $_wd_countdown_use_no; ?>>No</option>
				</select>
				<img class="help_tip" src="<?php echo WD_COUNTDOWN_IMG.'/help.png'; ?>" tooltip-content="Choose if the countdown should be on or off on this page" alt="" height="16" width="16" />
			</p>
		</div>
	</div>
	
	<div class="panel woocommerce_options_panel">
		<div class="options_group">
			<p class="form-field _sku_field ">
				<label for="wd_countdown_text">Info Text</label>
				<input type="text" name="wd_countdown_text" id="wd_countdown_text" value="<?php if(isset($wd_countdown_text)) echo $wd_countdown_text; ?>" />
				<img class="help_tip" src="<?php echo WD_COUNTDOWN_IMG.'/help.png'; ?>" tooltip-content="Info Text (to appear before time)" alt="" height="16" width="16" />
			</p>
		</div>
	</div>
</div>

<script type="text/javascript">
	jQuery(document).ready(function($){
		$(".help_tip").tipTip({ attribute: 'tooltip-content' });
	});
</script>