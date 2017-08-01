// handle countdown
function handleCountdown(){
  jQuery('.count_holder_big').each(function() {        
    var tip = jQuery(this).find('.count_holder_small');
    
    jQuery(this).hover(
      function() { tip.addClass('hover').appendTo('body'); },
      function() { tip.removeClass('hover').appendTo(this); }
    ).mousemove(function(e) {
      var x = e.pageX + 60,
          y = e.pageY - 50,
          w = tip.width(),
          h = tip.height(),
          dx = jQuery(window).width() - (x + w),
          dy = jQuery(window).height() - (y + h);
      
      if ( dx < 50 ) x = e.pageX - w - 60;
      //if ( dy < 50 ) y = e.pageY - h - 130;
      
      tip.css({ left: x, top: y });
    });         
  });
}

jQuery(document).ready(function($){
	handleCountdown();
});