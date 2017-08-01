jQuery(document).ready(function(){
	 jQuery('.wd_color_picker').wpColorPicker();
	  
	 jQuery('.wd_enable_to_show').each(function(i,e){
		var name = jQuery('.wd_enable_to_show').attr('name');
		var val = jQuery('.wd_enable_to_show').val();
		var _this = jQuery(this);
		jQuery('input[name='+name+']').on('change', function(){
			var class_show = _this.data('class_show');
			if(jQuery(this).val() == val) {
				jQuery("."+class_show).removeClass('wd_hide');
			} else {
				jQuery("."+class_show).addClass('wd_hide');
			}
		});
	 });
	 
	 jQuery('.wd_popup_manage_enab_btn').on('click', function(e){
		
		var action = jQuery(this).data('action');
		var id = jQuery(this).data('id');
		var ena_val = jQuery(this).data('ena_val');
		var _this = jQuery(this);
		jQuery.ajax({
			type: 'post',
			url: ajaxurl,
			data: {
				action: action,
				id: id,
				ena_val: ena_val
			},
			beforeSend: function(){
				_this.parents('tr').find('.column-wd_status .wd_label').text('Loding...');
			},
			success: function(o){
				if(o=='1') {
					_this.parents('tr').find('.column-wd_status .wd_label').text('Enable');
					_this.parents('tr').find('.column-wd_status .wd_label').addClass('enable');
					_this.text('Disable').data('ena_val', '0');
				} else {
					_this.parents('tr').find('.column-wd_status .wd_label').text('Disable');
					_this.parents('tr').find('.column-wd_status .wd_label').removeClass('enable');
					_this.text('Enable').data('ena_val', '1');
				}
			}
		});
		
		e.preventDefault();
	 });
	 
});