
<div class="wd_as_swapper">
	<div class="wd_as_heading"><h3>[WD] Ajax Search Options</h3></div>
	<div class="wd_as_container">
		<form method="post" action="#" id="wd_as_form">
			<table>
				<tr>
					<th width="150"><label for="wd_ops_search">Search</label></th>
					<td>
						<select name="wd_ops_search" class="wd_perand" id="wd_ops_search">
							<option <?php echo ( isset($this->options->search) && $this->options->search == 'post') ? "selected=\"selected\" " : ""; ?>value="post">Post</option>
							<?php if( class_exists( 'woocommerce' ) ):?>
							<option <?php echo ( isset($this->options->search) && $this->options->search == 'product') ? "selected=\"selected\" " : ""; ?>value="product">Product</option>
							<?php endif;?>
							<option <?php echo ( isset($this->options->search) && $this->options->search == 'all') ? "selected=\"selected\" " : ""; ?>value="all">All</option>
						</select>
					</td>
				</tr>
				<tr class="wd_perand_appl" data-element="wd_ops_search" data-value="all">
					<th><label for="wd_ops_search_def">Search default</label></th>
					<td>
						<select name="wd_ops_search_def">
							<option <?php echo ( isset($this->options->search_def) && $this->options->search_def == 'post') ? "selected=\"selected\" " : ""; ?>value="post">Post</option>
							<?php if( class_exists( 'woocommerce' ) ):?>
							<option <?php echo ( isset($this->options->search_def) && $this->options->search_def == 'product') ? "selected=\"selected\" " : ""; ?>value="product">Product</option>
							<?php endif;?>
						</select>
					</td>
				</tr>
				
				<tr>
					<th valign="top"><label for="">Search with</label></th>
					<td>
						<p><input <?php echo ( isset($this->options->search_with) && $this->options->search_with == 'product_cat') ? "checked=\"checked\" " : ""; ?>name="wd_ops_search_with" id="wd_ops_search_with_cat" type="radio" value="product_cat" />
						<label for="wd_ops_search_with_cat">Product category</label></p>
						<p><input <?php echo ( isset($this->options->search_with) && $this->options->search_with == 'product_meta') ? "checked=\"checked\" " : ""; ?>name="wd_ops_search_with" id="wd_ops_search_with_meta" type="radio" value="product_meta" />
						<label for="wd_ops_search_with_meta">Product meta</label></p>
					</td>
				</tr>
				
			</table>
		</form>
		<div class="wd_as_nitification" style="display:none;"></div>
		
	</div>
</div>