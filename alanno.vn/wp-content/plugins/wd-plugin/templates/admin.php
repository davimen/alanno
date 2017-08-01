<?php 
	$wd_plugines = array( WD_AS_SLUG =>'WD Ajax Search', WDNTF_SLUG => 'WD Order Notification', WD_PP_SLUG =>'WD Popup' );
?>

<div class="wrap metabox-holder">
	<h2>WD Plugin</h2>
	<div id="wd-sample-image-wrapper" class="postbox">
		<div class="handlediv" title="Click to toggle"></div>
		<h3 class="hndle"><span>Change Option</span></h3>
		<div class="inside">
			
			<div class="wp-plugin-option-wrap">
				<form method="post" id="wd_plugin_form">
					<table class="form-table">
						<tbody>
							<?php 
							foreach($wd_plugines as $wd_pl_key => $wd_pl_data){
							?>
							<tr>
								<th scope="row"><?php echo esc_html($wd_pl_data);?></th>
								<td>
									<?php if( WDNTF_SLUG == $wd_pl_key ){ ?>
									<?php if( class_exists( 'woocommerce' ) ): ?>
									<select name="wd_ops_<?php echo esc_attr($wd_pl_key);?>" class="wd_perand" id="wd_ops_<?php echo esc_attr($wd_pl_key);?>">
										<option <?php echo ( isset($this->options->{$wd_pl_key}) && absint($this->options->{$wd_pl_key}) == 1) ? "selected=\"selected\" " : ""; ?>value="1">Enable</option>
										<option <?php echo ( isset($this->options->{$wd_pl_key}) && absint($this->options->{$wd_pl_key}) == 0) ? "selected=\"selected\" " : ""; ?>value="0">Disable</option>
									</select>
										<?php else: ?>
											<i>no Woocommerce plugin found!</i>
										<?php endif;?>
									<?php } else {?>
									<select name="wd_ops_<?php echo esc_attr($wd_pl_key);?>" class="wd_perand" id="wd_ops_<?php echo esc_attr($wd_pl_key);?>">
										<option <?php echo ( isset($this->options->{$wd_pl_key}) && absint($this->options->{$wd_pl_key}) == 1) ? "selected=\"selected\" " : ""; ?>value="1">Enable</option>
										<option <?php echo ( isset($this->options->{$wd_pl_key}) && absint($this->options->{$wd_pl_key}) == 0) ? "selected=\"selected\" " : ""; ?>value="0">Disable</option>
									</select>
									<?php }?>
								</td>
							</tr>
							<?php
							}
							?>
							
							<tr>
								<td></td>
								<td>
									<button type="submit" id="wd-plugin-submit-button" name="wd-plugin-submit-button" class="button button-primary"><i class="fa fa-floppy-o" style="margin-right:5px"></i> <?php _e('Apply setting', 'wpdance');?></button>
									
								</td>
							</tr>
							
							<tr>
								<td colspan="2">
									<div class="wd_as_nitification"></div>
								</td>
							</tr>
							
						</tbody>
					</table>
					
				</form>
			</div>
			
		</div>
	</div>
</div>