jQuery(document).ready(function(){
	
	jQuery('#wd_plugin_form').on('submit', function(){
		var form = jQuery('#wd_plugin_form').serializeArray();
		form.push({name: 'action', value: "wd_plugin_save_options"});
		jQuery.ajax({
			url: ajaxurl,
			data: form,
			type: "POST",
			beforeSend: function(){
				jQuery('#wd_plugin_form [type="submit"]').attr('disabled', 'disabled')
						.find('i.fa').attr('class','fa fa-spinner fa-spin');
			},
			success: function(data) {
				window.location.reload(true);
			}
		});
		return false;
	});
});