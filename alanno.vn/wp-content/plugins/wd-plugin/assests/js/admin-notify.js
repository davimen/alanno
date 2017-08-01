function notify_get_permission() {
		if (!("Notification" in window)) {
			alert("This browser does not support desktop notification");
		} else if (Notification.permission === "granted") {
			jQuery('body').trigger('notify_check');
		} else if (Notification.permission !== 'denied') {
			Notification.requestPermission(function (permission) {
				window.location.reload(true);
			});
		}
		
	}

function set_notify(title, option){
	if( typeof option !== 'undefined') {
		var notification = new Notification(title, option);
	} else {
		var notification = new Notification(title);
	}
	var delay = jQuery('#notify_data').data('notify_delay');
	var ref_url = jQuery('#notify_data').data('ref_url');
	notification.onclick = function(){
		var win = window.open(ref_url, '_blank');
		win.focus();
	}
	
	setTimeout(function(){ notification.close();}, delay);
}
	
jQuery(document).ready(function(){
	"use strict";
	if( typeof jQuery('#notify_data').data('loop_delay') !== 'undefined' ){
		jQuery('body').on('notify_check', function(){
			if (!("Notification" in window)) {
				alert("This browser does not support desktop notification");
			} else if (Notification.permission === "default") {
				jQuery('.notify-checking').removeClass('granted').removeClass('denied').addClass('get-perm').html("<i class=\"fa fa-bookmark-o\" style=\"margin-right:5px\"></i> Set Permission");
				jQuery('#notify_data').data('permission', 'default');
			} else if (Notification.permission === "granted") {
				jQuery('.notify-checking').hide();
				jQuery('.notify-option-wrap').show();
				jQuery('#notify_data').data('permission', 'granted');
			} else {
				var perm = Notification.permission;
				jQuery('.notify-checking').removeClass('granted').removeClass('denied').removeClass('get-perm').addClass(perm).html("<i class=\"fa fa-ban\" style=\"margin-right:5px\"></i> "+perm).attr('disabled','disabled');
				jQuery('#notify_data').data('permission', perm);
			}
		});
		jQuery('body').trigger('notify_check');
		
		jQuery('body').on('click', '.notify-checking.get-perm', function(){
			notify_get_permission();
		});
		
		
		jQuery('#wd_notify_form').submit(function(){
			jQuery('#wd_notify_form').trigger('wdntf_ops_change');
			
		});
		
		jQuery('#wd_notify_form').on('submit', function(){
			var form = jQuery('#wd_notify_form').serializeArray();
			form.push({name: 'action', value: "wdntf_save_options"});
			jQuery.ajax({
				url: ajaxurl,
				data: form,
				type: "POST",
				beforeSend: function(){
					jQuery('#wd_notify_form [type="submit"]').attr('disabled', 'disabled')
							.find('i.fa').attr('class','fa fa-spinner fa-spin');
				},
				success: function(data) {
					window.location.reload(true);
				}
			});
			return false;
		});
		
		
		jQuery('body').on('get_new_order', function(){
			var perm = jQuery('#notify_data').data('permission');
			var icon = jQuery('#notify_data').data('icon');
			var message = "<i>Not found!</i>";
			var res_icon = "<i style=\"float:right\" class=\"fa fa-square\"></i>";
			if(perm === 'granted') {
				jQuery.ajax({
					type: "GET",
					dataType: 'json',
					url: ajaxurl + '?action=wd_notify_order',
					beforeSend: function(o){
						jQuery('#wd_notify_log .wd_ntf_progress').html("<i class=\"fa fa-spinner fa-spin\"></i> <i>Checking...</i>");
					}, success: function(o){
						var ref_url = jQuery('#notify_data').data('ref_url');
						var has_order = "";
						if(o.length > 0) {
							set_notify("New Order", {body: "You are having "+o.length+ " order(s)", icon: icon});
							message = "<a title=\"View orders page\" target=\"_blank\" href='"+ref_url+"'><i>You are having "+o.length+ " order(s)</i></a>";
							res_icon = "<i style=\"float:right\" class=\"fa fa-check-square\"></i>";
							has_order = "background-color: #dedede;";
						}
						
						var currentdate = new Date();
						jQuery('#wd_notify_log .message').prepend('<li style="padding: 5px 10px; margin:0;'+has_order+'">'+res_icon+' <b>'+ currentdate.toLocaleString() +'</b><i class="fa fa-long-arrow-right" style="margin: 0 20px";></i>' + message +'</li>');
						jQuery('#wd_notify_log .wd_ntf_progress').html("<i class=\"fa fa-refresh\"></i> <i>Waiting loop</i>");
						jQuery('#wd_notify_log .message').animate({'scrollTop': 0},300);
					}
				});
			}
			
		});
		
		var wd_notify_delay = jQuery('#notify_data').data('loop_delay');
		jQuery('body').trigger('get_new_order');
		setInterval(function(){
			jQuery('body').trigger('get_new_order');
		}, wd_notify_delay);
		
	
	}
});