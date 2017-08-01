<?php 

	
?>
<div class="wrap metabox-holder">
	<h2>WD Ajax Search</h2>
	<div id="wd-sample-image-wrapper" class="postbox">
		<div class="handlediv" title="Click to toggle"></div>
		<h3 class="hndle"><span>Change Option</span></h3>
		<div class="inside">
			
			<div class="ajax-search-option-wrap">
				<form method="post" id="wd_search_form">
					<table class="form-table">
						<tbody>
							<tr>
								<th scope="row">Type timeout</th>
								<td>
									<select name="wd_ops_type_timeout" class="wd_perand" id="wd_ops_type_timeout">
										<option <?php echo ( isset($this->options->type_timeout) && absint($this->options->type_timeout) == 100) ? "selected=\"selected\" " : ""; ?>value="100">0.1 Sec</option>
										<option <?php echo ( isset($this->options->type_timeout) && absint($this->options->type_timeout) == 200) ? "selected=\"selected\" " : ""; ?>value="200">0.2 Sec</option>
										<option <?php echo ( isset($this->options->type_timeout) && absint($this->options->type_timeout) == 300) ? "selected=\"selected\" " : ""; ?>value="300">0.3 SEC</option>
										<option <?php echo ( isset($this->options->type_timeout) && absint($this->options->type_timeout) == 500) ? "selected=\"selected\" " : ""; ?>value="500">0.5 Sec</option>
										<option <?php echo ( isset($this->options->type_timeout) && absint($this->options->type_timeout) == 750) ? "selected=\"selected\" " : ""; ?>value="750">0.75 Sec</option>
										<option <?php echo ( isset($this->options->type_timeout) && absint($this->options->type_timeout) == 1000) ? "selected=\"selected\" " : ""; ?>value="1000">1 Sec</option>
									</select>
								</td>
							</tr>
							
							<tr>
								<th scope="row">Default search with</th>
								<td>
									<select name="wd_ops_def_search" class="wd_perand" id="wd_ops_def_search">
										<option <?php echo ( isset($this->options->def_search) && $this->options->def_search == 'post') ? "selected=\"selected\" " : ""; ?>value="post">Post</option>
										<?php if(class_exists('WooCommerce')): ?>
										<option <?php echo ( isset($this->options->def_search) && $this->options->def_search == 'product') ? "selected=\"selected\" " : ""; ?>value="product">Porduct</option>
										<?php endif;?>
									</select>
								</td>
							</tr>
							
							
							<tr>
								<th scope="row">Search with</th>
								<td>
									<select name="wd_ops_search_with" class="wd_perand" id="wd_ops_search_with">
										<option <?php echo ( isset($this->options->search_with) && $this->options->search_with == 'post') ? "selected=\"selected\" " : ""; ?>value="post">Post</option>
										<?php if(class_exists('WooCommerce')): ?>
										<option <?php echo ( isset($this->options->search_with) && $this->options->search_with == 'product') ? "selected=\"selected\" " : ""; ?>value="product">Porduct</option>
										<option <?php echo ( isset($this->options->search_with) && $this->options->search_with == 'all') ? "selected=\"selected\" " : ""; ?>value="all">Post & Product</option>
										<?php endif;?>
									</select>
								</td>
							</tr>
							<?php if(class_exists('WooCommerce')): ?>
							<tr>
								<th scope="row">Result number of product</th>
								<td>
									<select name="wd_ops_product_res_num" class="wd_perand" id="wd_ops_product_res_num">
										<option <?php echo ( isset($this->options->product_res_num) && absint($this->options->product_res_num) == 2) ? "selected=\"selected\" " : ""; ?>value="2">2 product</option>
										<option <?php echo ( isset($this->options->product_res_num) && absint($this->options->product_res_num) == 3) ? "selected=\"selected\" " : ""; ?>value="3">3 product</option>
										<option <?php echo ( isset($this->options->product_res_num) && absint($this->options->product_res_num) == 4) ? "selected=\"selected\" " : ""; ?>value="4">4 product</option>
										<option <?php echo ( isset($this->options->product_res_num) && absint($this->options->product_res_num) == 5) ? "selected=\"selected\" " : ""; ?>value="5">5 product</option>
										<option <?php echo ( isset($this->options->product_res_num) && absint($this->options->product_res_num) == 6) ? "selected=\"selected\" " : ""; ?>value="6">6 product</option>
										<option <?php echo ( isset($this->options->product_res_num) && absint($this->options->product_res_num) == 7) ? "selected=\"selected\" " : ""; ?>value="7">7 product</option>
									</select>
								</td>
							</tr>
							<?php endif;?>
							<tr>
								<th scope="row">Result number of Post</th>
								<td>
									<select name="wd_ops_post_res_num" class="wd_perand" id="wd_ops_post_res_num">
										<option <?php echo ( isset($this->options->post_res_num) && absint($this->options->post_res_num) == 3) ? "selected=\"selected\" " : ""; ?>value="3">3 post</option>
										<option <?php echo ( isset($this->options->post_res_num) && absint($this->options->post_res_num) == 4) ? "selected=\"selected\" " : ""; ?>value="4">4 post</option>
										<option <?php echo ( isset($this->options->post_res_num) && absint($this->options->post_res_num) == 5) ? "selected=\"selected\" " : ""; ?>value="5">5 post</option>
										<option <?php echo ( isset($this->options->post_res_num) && absint($this->options->post_res_num) == 6) ? "selected=\"selected\" " : ""; ?>value="6">6 post</option>
										<option <?php echo ( isset($this->options->post_res_num) && absint($this->options->post_res_num) == 7) ? "selected=\"selected\" " : ""; ?>value="7">7 post</option>
									</select>
								</td>
							</tr>
							
							<tr>
								<td colspan="2">
									<input type="submit" id="wd-ajs-submit-button" name="wdsi-submit-button" class="button button-primary" value="Apply setting" />
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