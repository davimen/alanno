jQuery(document).ready(function() {
	
	var delay = (function () {
            var timer = 0;
            return function (callback, ms) {
                clearTimeout(timer);
                timer = setTimeout(callback, ms);
            };
	})();
	
	jQuery.fn.appendResult = function(o, url, site_url, s, img, s_view_l, type){
		
		if(o.length > 0){
			for(var i=0; i<o.length; i++) {
				var category ='';
				if(o[i]['cat'].length > 0) {
					category ='<p class="category">';
					for(var cc = 0; cc < o[i]['cat'].length; cc++) {
						if(cc > 0) category += ', <a href="'+o[i]['cat'][cc]['url']+'" rel="tag">'+o[i]['cat'][cc]['name']+'</a>';
						else category += ' <a href="'+o[i]['cat'][cc]['url']+'" rel="tag">'+o[i]['cat'][cc]['name']+'</a>';
					}
					category +='</a>';
				}
				if(img) img = '<a class="thumbnail" href="'+o[i]['url']+'" title="'+o[i]['post_title']+'">'+o[i]['src']+'</a>';
				else img ='';
				var title = o[i]['post_title'];
				title = title.toUpperCase();
				s_up = s.toUpperCase();
				var s_pos = title.indexOf(s_up);
				if(s_pos >=0) {
					title = title.insertAt(s_pos, '<span class="line-height">');
					s_pos = title.indexOf(s_up) + s_up.length;
					title = title.insertAt(s_pos, '</span>');
				}
				
				jQuery(this).find('ul').append('\
					<li>\
						'+img+'\
						<div class="content"><a href="'+o[i]['url']+'" title="'+o[i]['post_title']+'">\
							'+title+'\
						</a>\
						'+category+'</div>\
					</li>\
				');
			}
			//_this.parent().find('.list_result ul')
			if( s_view_l && o.length !== 1 ){
				jQuery(this).find('ul').append('\
					<li class="search_all"><a class="button none-bg primary button-white" href="'+site_url+'?s='+s+'&post_type='+type+'">Show All</a></li>\
				');
			}
			
		}
	}
	
	String.prototype.insertAt=function(index, string) { 
		return this.substr(0, index) + string + this.substr(index);
	}
	
	var wd_timeout = jQuery('.wd_woo_search_box #wd_searchform input[name=s]').data('timeout');
	
	jQuery('.wd_woo_search_box #wd_searchform input[name=s]').keyup(function(){
		var _this = jQuery(this).parents('#wd_searchform');
		var s = jQuery(this).val();
		var $this = jQuery(this);
		var search_result = $this.parents('form#wd_searchform').nextAll('.list_result');
		delay(function () {
			if(s==''){
				_this.parent().find('.list_result').html('');
			} else {
				var $data = $this.data();
				$data.action = 'wd_seach_result';
				$data.s = s;
				
				var taxonomy = _this.find('input[name=taxonomy]').val();
				var term = _this.find('select[name=term]').val();
				if(typeof taxonomy !== 'undefined') {
					$data.taxonomy = taxonomy;
					$data.type = 'product';
				}
				if(typeof term !== 'undefined') {
					$data.term = term;
				}
				
				jQuery.ajax({
					type	: "POST",
					url		: $data.ajax_url,
					data	: $data,
					dataType: 'json',
					beforeSend: function(o){
						_this.find('[type=submit] .fa').removeClass('fa-search').addClass('fa-spinner fa-spin');
					},
					success: function(data){
						
						if((typeof data['pro_res']!= 'undefined' && data['pro_res'].length > 0) || (typeof data['blog_res']!= 'undefined' && data['blog_res'].length > 0)) {
							search_result.html('<ul class="product_list_widget"></ul>');
							if ($data.type == 'all') {
								search_result.appendResult(data['pro_res'], $data.ajax_url, $data.site_url, s, true, true, 'product');
								search_result.appendResult(data['blog_res'], $data.ajax_url, $data.site_url, s, false, true, 'post');
							} else if($data.type == 'post') {
								search_result.appendResult(data['blog_res'], $data.ajax_url, $data.site_url, s, false, false, 'post');
							} else {
								search_result.appendResult(data['pro_res'], $data.ajax_url, $data.site_url, s, true, false, 'product');
							}
						} else {
							search_result.html('<ul class="product_list_widget"><li>Not found!</li></ul>');
						}
						
						_this.find('[type=submit] .fa').removeClass('fa-spinner fa-spin').addClass('fa-search');
					}
				});
			}
			
        }, wd_timeout);
		
		
	});
	
	
});