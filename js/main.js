$(document).ready(function(){
	/* all_menu depth */
    $('.all_menu li, .depth_menu').mouseover(function(){
        var depthCount = $(this).children('.depth_menu').find('.dep').length,
            depthWidth = 228 * depthCount + 2;
        
        $(this).children('.depth_menu').css({
            display:'block',
            width:depthWidth+'px'
        });
    }).mouseout(function(){
        $('.depth_menu').hide();
    });
    
    /* all menu */
    $('.all_btn').click(function(){
		if($(this).parent().hasClass('sub')){ // ?釉??댁??? all_btn? ?대┃?? ?
		   if($(this).hasClass('open')){
				$('.all_area').hide();
				$(this).removeClass('open');
			} else {
				$('.all_area').show();
				$(this).addClass('open');
			}
		} else { // index ???? all_area ?대??ㅽ??댁? ??
		   return false;
		}
        
    });
	
	// quick close btn
	$('.quick_btn').click(function(){
		if($('.quick_wrap').hasClass('close')){
			$('.quick_wrap').removeClass('close');
			$.cookie('show_quick', '', { expires: 1, path: '/', domain: document.domain, secure: false });
		} else {
			$('.quick_wrap').addClass('close');
			$.cookie('show_quick', 'hidden', { expires: 1, path: '/', domain: document.domain, secure: false });
		}
	});
	
	if($.cookie("show_quick") == "hidden"){
		$('.quick_wrap').addClass('close'); 
	}else{
		$('.quick_wrap').removeClass('close'); 
	}
});