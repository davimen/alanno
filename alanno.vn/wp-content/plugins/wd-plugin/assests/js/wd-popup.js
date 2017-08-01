
function setCookie(cname, cvalue, exMs) {
    var d = new Date();
    d.setTime(d.getTime() + (exMs* 60*1000));
    var expires = "expires="+d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function getCookie(cname) {
    var name = cname + "=";
    var ca = document.cookie.split(';');
    for(var i=0; i<ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1);
        if (c.indexOf(name) == 0) return c.substring(name.length,c.length);
    }
    return false;
}
	
jQuery(document).ready(function(){
	
	var wdPP_ck_name = jQuery('.wd_fe_popup').attr('data-ckie');
	var wdPP_ck_delay = jQuery('.wd_fe_popup').attr('data-ckie-delay');
	var wdPP_close = jQuery('.wd_fe_popup').data('to_close');
	var wdPP_close_delay = jQuery('.wd_fe_popup').data('close_delay');
	
	if(getCookie(wdPP_ck_name) == false) {
		
		if(wdPP_close == '1') {
			var wd_pp_timeout;
			jQuery('.wd_fe_popup').bPopup({
				modalClose: false,
				opacity: 0.7,
				positionStyle: 'fixed',
				autoClose: wdPP_close_delay * 1000,
				onOpen: function(){
					var i = wdPP_close_delay;
					jQuery('.wd_fe_popup .wd_pp_header .wd_pp_timeout').text(i+"s");
					wd_pp_timeout = setInterval(function(){
						i--;
						jQuery('.wd_fe_popup .wd_pp_header .wd_pp_timeout').text(i+"s");
						if(i == 0) clearInterval(wd_pp_timeout);
					}, 1000);
				},
				onClose: function(){
					clearInterval(wd_pp_timeout);
					if(wdPP_ck_delay !== 0)
						setCookie(wdPP_ck_name, 1, wdPP_ck_delay);
				}
			});
		} else {
			jQuery('.wd_fe_popup').bPopup({
				modalClose: false,
				opacity: 0.7,
				positionStyle: 'fixed',
				onClose: function(){
					if(wdPP_ck_delay !== 0)
						setCookie(wdPP_ck_name, 1, wdPP_ck_delay);
				}
			});
		}
		
		
	}
	
	
	jQuery('.bClose').click(function(e){
		e.preventDefault();
	});
	
});