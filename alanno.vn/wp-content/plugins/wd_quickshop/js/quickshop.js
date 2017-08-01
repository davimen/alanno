/**
 * WD QuickShop
 *
 * @license commercial software
 * @copyright (c) 2013 Codespot Software JSC - WPDance.com. (http://www.wpdance.com)
 */



(function($) {
	
	
	
	// disable QuickShop:
	if(jQuery('body').innerWidth() < 768)
		EM_QUICKSHOP_DISABLED = true;
	
	jQuery.noConflict();
	qs = null;
	jQuery(function ($) {
			//insert quickshop popup
			 $('.wd_quickshop_handler').prettyPhoto({
				deeplinking: false
				,opacity: 0.9
				,social_tools: false
				/*,default_width: jQuery('body').innerWidth()/8*5
				,default_height: "innerHeight" in window ? ( window.innerHeight - 150 ) : (document.documentElement.offsetHeight - 150)*/
				//,default_height: window.innerHeight - 150
				,default_width: 900
				,default_height: 500
				,theme: 'pp_woocommerce'
				,changepicturecallback : function(){
				
					//$("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").addClass('buttons_added').append('<input type="button" value="+" id="add1" class="plus" />').prepend('<input type="button" value="-" id="minus1" class="minus" />');
					$("div.quantity:not(.buttons_added), td.quantity:not(.buttons_added)").addClass('buttons_added');
					$('.pp_inline').find('form.variations_form').wc_variation_form();
					$('.pp_inline').find('form.variations_form .variations select').change();	
					
					var _li_count = jQuery('.qs-thumbnails > li').length;
					if( _li_count > 0 ){
						_li_count = Math.min(_li_count,4);
					}else{
						_li_count = 4;
					}

					jQuery('.qs-thumbnails').carouFredSel({		
						responsive: true
						,width	: _li_count*25 + '%'
						,height	: 88
						,scroll	: 1
						,swipe	: { onMouse: false, onTouch: true }	
						,items	: {
							width		: 85
							,height		: 85
							,visible	: {
								min		: 1
								,max	: 4
							}
						}
						,auto	: false
						,prev	: '#qs_thumbnails_prev'
						,next	: '#qs_thumbnails_next'								
					});
				}
			});
		
		// quickshop init
		function _qsJnit() {		
			//var qsHandlerImg = $('#em_quickshop_handler img');
			var qsHandler = $('.wd_quickshop_handler');
			
			/*qsHandler.live('click', function (event) {		
				_qs_window(this);
				event.preventDefault();
			});*/
			
			$('.wd_quickshop.product').live('mouseover',function(){
				if( !$(this).hasClass('active') ){
					$(this).addClass('active');
					$('#qs-zoom,.wd-qs-cloud-zoom,.cloud-zoom, .cloud-zoom-gallery').CloudZoom({});							
				}
			});
			
			//fix bug group 0 qty, and out of stock
			$('.wd_quickshop.product form button.single_add_to_cart_button').live('click',function(){
				
				if($('.wd_quickshop.product form table.group_table').length > 0){
					$('.wd_quickshop.product').prev('ul.woocommerce-error').remove();
					temp = 0;
					
					$('.wd_quickshop.product form table.group_table').find('input.qty').each(function(i,value){
						var td_cur = $(value).closest( "td" );
						
						if($(value).val() > temp && !td_cur.next().hasClass('wd_product_out-of-stock'))
							temp = $(value).val();
					});
					if(temp == 0) {
						$('.wd_quickshop.product').before( $( "<ul class=\'woocommerce-error\'><li>Please choose the quantity of items you wish to add to your cart…</li></ul>" ) );
						return false;
					}	
				}
			});
			
		}

		if (typeof EM_QUICKSHOP_DISABLED == 'undefined' || !EM_QUICKSHOP_DISABLED)
		
			/*************** Disable QS in Main Menu *****************/
			jQuery('ul.menu').find('div.products').addClass('no_quickshop');
			jQuery('.wd_product_category_list_shortcode').find('div.products').addClass('no_quickshop');
			jQuery('div.custom_category_shortcode_style2 .bottom_content').find('div.products').addClass('no_quickshop');
			/*************** Disable QS in Main Menu *****************/		
		
			_qsJnit({
				itemClass		: 'div.products div.product.type-product.status-publish .product_thumbnail_wrapper' //selector for each items in catalog product list,use to insert quickshop image
				,inputClass		: 'input.hidden_product_id' //selector for each a tag in product items,give us href for one product
			});
			qs = _qsJnit;
	});
})(jQuery);

