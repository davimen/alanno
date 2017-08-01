
	
jQuery(document).ready(function(){
		
	jQuery('#wd_search_form').on('submit', function(){
		var form = jQuery('#wd_search_form').serializeArray();
		form.push({name: 'action', value: "wdas_save_options"});
		jQuery.ajax({
			url: ajaxurl,
			data: form,
			type: "POST",
			beforeSend: function(){
				jQuery('#wd_search_form [type="submit"]').attr('disabled', 'disabled');
				jQuery('.wd_as_nitification').text("Saving...").show(100);
			},
			success: function(data) {
				jQuery('.wd_as_nitification').text("Save success!");
				jQuery('#wd_search_form [type="submit"]').removeAttr('disabled');
				jQuery('.wd_as_nitification').delay(2100).hide(100);
			}
		});
		return false;
	});
		
	/*jQuery('body').on('get_new_order', function(){
		var perm = jQuery('#notify_data').attr('data-permission');
		var icon = jQuery('#notify_data').attr('data-icon');
		console.log(perm);
		if(perm === 'granted') {
			jQuery.ajax({
				type: "GET",
				dataType: 'json',
				url: ajaxurl + '?action=wd_notify_order',
				success: function(o){
					if(o.length > 0) {
						set_notify("New Order", {body: "You are having "+o.length+ " order(s)", icon: icon});
					}
					
				}
			});
		}
	});*/
		
	
});