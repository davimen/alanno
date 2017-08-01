
<div class="wrap metabox-holder">
	<h2>WD Order Notification</h2>
	<div id="wd-sample-image-wrapper" class="postbox">
		<div class="handlediv" title="Click to toggle"></div>
		<h3 class="hndle"><span>Change Option</span></h3>
		<div class="inside">
			<button class="button button-hero notify-checking button-primary"><i style="margin-right:5px" class="fa fa-spinner fa-spin"></i> Checking...</button>
			
			<div class="notify-option-wrap" style="display:none; overflow: hidden;">
				<form method="post" id="wd_notify_form">
					<div style=" overflow: hidden;">
					<table class="form-table" style="width: 50%; min-width: 350px; display: inline-block; float:left;">
						<tbody>
							<tr>
								<th scope="row">Loop delay</th>
								<td>
									<select name="wd_ops_loop_delay" class="wd_perand" id="wd_ops_loop_delay">
										<option <?php echo ( isset($this->options->loop_delay) && absint($this->options->loop_delay) == 1) ? "selected=\"selected\" " : ""; ?>value="1">1 min</option>
										<option <?php echo ( isset($this->options->loop_delay) && absint($this->options->loop_delay) == 3) ? "selected=\"selected\" " : ""; ?>value="3">3 min</option>
										<option <?php echo ( isset($this->options->loop_delay) && absint($this->options->loop_delay) == 5) ? "selected=\"selected\" " : ""; ?>value="5">5 min</option>
										<option <?php echo ( isset($this->options->loop_delay) && absint($this->options->loop_delay) == 10) ? "selected=\"selected\" " : ""; ?>value="10">10 min</option>
										<option <?php echo ( isset($this->options->loop_delay) && absint($this->options->loop_delay) == 15) ? "selected=\"selected\" " : ""; ?>value="15">15 min</option>
										<option <?php echo ( isset($this->options->loop_delay) && absint($this->options->loop_delay) == 20) ? "selected=\"selected\" " : ""; ?>value="20">20 min</option>
										<option <?php echo ( isset($this->options->loop_delay) && absint($this->options->loop_delay) == 30) ? "selected=\"selected\" " : ""; ?>value="30">30 min</option>
										<option <?php echo ( isset($this->options->loop_delay) && absint($this->options->loop_delay) == 45) ? "selected=\"selected\" " : ""; ?>value="45">45 min</option>
										<option <?php echo ( isset($this->options->loop_delay) && absint($this->options->loop_delay) == 60) ? "selected=\"selected\" " : ""; ?>value="60">60 min</option>
									</select>
								</td>
							</tr>
							
							<tr>
								<th scope="row">Notify delay</th>
								<td>
									<select name="wd_ops_notify_delay" class="wd_perand" id="wd_ops_notify_delay">
										<option <?php echo ( isset($this->options->notify_delay) && absint($this->options->notify_delay) == 10) ? "selected=\"selected\" " : ""; ?>value="10">10 sec</option>
										<option <?php echo ( isset($this->options->notify_delay) && absint($this->options->notify_delay) == 15) ? "selected=\"selected\" " : ""; ?>value="15">15 sec</option>
										<option <?php echo ( isset($this->options->notify_delay) && absint($this->options->notify_delay) == 20) ? "selected=\"selected\" " : ""; ?>value="20">20 sec</option>
										<option <?php echo ( isset($this->options->notify_delay) && absint($this->options->notify_delay) == 25) ? "selected=\"selected\" " : ""; ?>value="25">25 sec</option>
										<option <?php echo ( isset($this->options->notify_delay) && absint($this->options->notify_delay) == 30) ? "selected=\"selected\" " : ""; ?>value="30">30 sec</option>
									</select>
								</td>
							</tr>
							
							<tr>
								<td></td>
								<td>
									<button type="submit" id="wdntf-submit-button" name="wdsi-submit-button" class="button button-primary" ><i class="fa fa-floppy-o" style="margin-right:5px"></i> <?php _e('Apply setting', 'wpdance');?></button>
								</td>
							</tr>
							
							<tr>
								<td colspan="2">
									<div class="wd_as_nitification"></div>
								</td>
							</tr>
							
						</tbody>
					</table>
					
					<div id="wd_notify_log" style="width: 40%; padding-bottom: 5px; min-width: 350px; display: inline-block; float:right; background: #ededed;">
						<h4 style="padding: 10px; background-color:#cccccc; border-bottom: 1px solid #cccccc; margin: 0px; font-size: 1em;" class="wd_ntf_progress" style="margin: 0;"><i class="fa fa-refresh"></i> <i>Waiting loop</i></h4>
						<ul class="message" style="height: 230px; overflow-y: scroll; margin:0; padding: 5px 0px;"></ul>
					</div>
					
					
					</div>
					<!--p class="label">Looking for woo order, please don't close this window!</p-->
					<input type="hidden" id="notify_data" data-loop_delay="<?php echo isset($this->options->loop_delay)? absint($this->options->loop_delay) * 60000 : 20000;?>" data-notify_delay="<?php echo isset($this->options->notify_delay)? absint($this->options->notify_delay) * 1000 : 10000;?>" data-ref_url="<?php echo get_admin_url('', 'edit.php?post_type=shop_order')?>" data-permission="default" data-icon="<?php echo esc_url(WD_URL_ASSESTS. 'img/order-icon.png');?>">
				</form>
			</div>
			
		</div>
	</div>
</div>