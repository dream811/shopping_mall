$(document).ready(function(){
	function scrollOff(){ // body scroll fixed
		$('body').addClass('scrollOff').on('scroll touchmove mousewheel', function(e){
		  e.preventDefault();
	   });
	}
	function scrollOn(){
		$('body').removeClass('scrollOff').off('scroll touchmove mousewheel');
	}
	
	// snb script
	$('.snb_open').click(function(){
		scrollOff();
		var height = $(document).height(); // gnb_wrap height
		$('.shadow_bg').fadeIn();
        $('.side_wrap').css('left',0);
        $('.scroll_menu').hide();
	});
	$('.snb_close').click(function(){
		scrollOn();
		$('.shadow_bg').hide();
        $('.side_wrap').css('left',-100+'%');
        $('.scroll_menu').show();
	});
    
	// snb depth menu
	$('.snb_area span').click(function(){
		if($(this).parent('li').hasClass('open')){
			$(this).parent('li').removeClass('open');
		} else {
			$('.snb_area > li').removeClass('open');
			$(this).parent('li').addClass('open');
		}
	});
	
	 $('.sch_open').click(function(){
		 var currentScroll = $(document).scrollTop(); // 검색창 열기 전에 현재 스크롤값 저장
		 
		 scrollOff();

         $('.sch_wrap').show();
         $('.scroll_menu').hide();
		 
		 $('.sch_close, .popular_cls').click(function(){
			 scrollOn();
			 
			 $(document).scrollTop(currentScroll); // 검색창 닫을 때 저장된 스크롤 위치로

			 $('.sch_wrap').hide();
			 $('.scroll_menu').show();
		});
    });
    
    // scrollTop script 
	$('.go_top').click(function(){
		$('html,body').animate({ scrollTop:0}, 300);
	});

    // scroll menu
    $(window).scroll(function(){
        var currentTop = $(window).scrollTop();
        
        if(currentTop > 0){
            $('.scroll_menu').css('marginBottom',0);  
        } else if(currentTop == 0) {
            $('.scroll_menu').css('marginBottom',-20+'rem');  
            if($('.scroll_btn').hasClass('on')){
                $('.sc_btn').trigger('click');
            }
        }
    });
    
     $('.sc_btn').click(function(){
       if($('.scroll_btn').hasClass('on')){
           $('.shadow_bg').hide();
           $(this).parent('.scroll_btn').removeClass('on');
       } else {
           $('.shadow_bg').fadeIn();
           $(this).parent('.scroll_btn').addClass('on');
       }
    });
});