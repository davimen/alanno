function generate_horizontal_slide(temp_visible,row,item_width,show_nav,show_icon_nav,autoplay,object_selector,baseE, responsive){
	if(typeof responsive === 'undefined') {
		responsive = [];
	}
	var res_len = responsive.length;
	var res_val = [];
	if(res_len == 5) {
		res_val = responsive;
	} else {
		res_val[0] = 1;
		res_val[1] = Math.round(temp_visible * 480 /1200);
		res_val[2] = Math.round(temp_visible * 768 /1200);
		res_val[3] = Math.round(temp_visible * 992 /1200);
		res_val[4] = temp_visible;
	}
	
	var _slider_datas =	{
		items 			: temp_visible
		,loop			: true
		,nav			: show_nav
		,navText		: [ '<', '>' ]
		,dots			: show_icon_nav
		,lazyload		:true
		,autoplay		:autoplay
		,autoplayTimeout	:8000
		,responsive		:{
			0:{
				items: res_val[0]
			},
			480:{
				items: res_val[1]
			},
			748:{
				items: res_val[2]//temp_visible -1
			},
			992:{
				items: res_val[3]//temp_visible -1
			},
			1200:{
				items: res_val[4]
			}
		}
		,onInitialized: function(){
			jQuery(object_selector).parents('.wd-loading').addClass('wd-loaded').removeClass('wd-loading');	
			if(autoplay) {
				setTimeout(function(){
					jQuery(object_selector).trigger('next.owl.carousel');
				}, 5000);
			}
		}
	}
	
	if( typeof baseE !== 'undefined' && baseE == true) {
		_slider_datas.responsiveBaseElement = jQuery(object_selector);
	}
	
	_slider_datas.pagination = true;
	var owl = jQuery(object_selector);
		
	owl.owlCarousel(_slider_datas);
	
	
	jQuery(object_selector).parents('.wd-slider-sc').on('click', '.owl-next', function(e){
		e.preventDefault();
		owl.trigger('next.owl.carousel');
	});
	
	jQuery(object_selector).parents('.wd-slider-sc').on('click', '.owl-prev', function(e){
		e.preventDefault();
		owl.trigger('prev.owl.carousel');
	});
	
}

function generate_blog_slide(temp_visible,row,item_width,show_nav,show_icon_nav,object_selector){
	var _slider_datas =	{
		item 			: temp_visible
		,loop			: true
		,nav			: show_nav
		,navText		: [ '<', '>' ]
		,dots			: show_icon_nav
		,lazyload		:true
		//,itemElement	:'section'
		,responsive		:{
			0:{
				items:1
			},
			480:{
				items:Math.max(1, parseInt(temp_visible * 480 /1200))
			},
			768:{
				items: Math.max(1, parseInt(temp_visible * 768 /1200))//temp_visible -1
			},
			992:{
				items: Math.max(1, parseInt(temp_visible * 992 /1200))//temp_visible -1
			},
			1200:{
				items:temp_visible
			}
		}
	}
	if(row >= 1)
	{
		_slider_datas.pagination = true;
		var owl = jQuery(object_selector);
		
		if(row > 1 && false){
			owl.on('resize.owl.carousel', function(e) {
				jQuery(object_selector).find('.item').css('height','auto');
			});
			owl.on('initialized.owl.carousel resized.owl.carousel', function(e) {
				setTimeout(function(){
					var max_height = 0;
					jQuery(object_selector).find('.item').each(function(index,value){
						if(jQuery(value).outerHeight() > max_height){
							max_height = jQuery(value).outerHeight();
						}
					});
					jQuery(object_selector).find('.item').outerHeight(max_height);
				}, 0);
			});
			
		}
		owl.owlCarousel(_slider_datas);	

	}
}

jQuery(document).ready(function(){
	var _wd_shortcode_button_data;
	jQuery(".wd-shortcode-button").hover(
		function(){
			_wd_shortcode_button_data = jQuery(this).attr('style');
			jQuery(this).attr('style',jQuery(this).attr('data-hover'));
		},
		function(){
			jQuery(this).attr('style',_wd_shortcode_button_data);
		}
	);
});