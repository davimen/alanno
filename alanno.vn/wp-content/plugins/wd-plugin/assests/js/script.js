jQuery(document).ready(function(){
	jQuery('#wd_as_form select, #wd_as_form input').on('change', function(){
		jQuery('#wd_as_form').trigger('wd_ad_ops_change');
	});
	
	jQuery('#wd_as_form select.wd_perand').on('change', function(){
		var id = jQuery(this).attr('id');
		jQuery( '.wd_perand_appl' ).each(function(index, event){
			if( jQuery( this ).attr( 'data-element' ) == id ) {
				var val = jQuery( this ).attr('data-value');
				if( jQuery('#'+id).val() !== val ) jQuery(this).hide();
				else jQuery(this).show();
			}
		});
		
	});
	
	jQuery('#wd_as_form').on('wd_ad_ops_change', function(){
		var form = jQuery('#wd_as_form').serializeArray();
		form.push({name: 'action', value: "wd_as_save_options"});
		jQuery.ajax({
			url: ajaxurl,
			data: form,
			type: "POST",
			beforeSend: function(){
				jQuery('.wd_as_nitification').text("Saving...").show(100);
			},
			success: function(data) {
				jQuery('.wd_as_nitification').text("Save success!");
				jQuery('.wd_as_nitification').delay(2100).hide(100);
			}
		});
	});
});