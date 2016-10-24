$(document).ready(function() {
	jQuery.fn.exists = function(){return this.length>0;}

  		 $("#carousel-small").swiperight(function() {
    		  $(this).carousel('prev');
	    		});
		   $("#carousel-small").swipeleft(function() {
		      $(this).carousel('next');
	   });

	 if($('#carousel-small').exists()) {
		$('#carousel-small').on('slide.bs.carousel', function (evt) {
			$(evt.relatedTarget).find('img').each(function() {
				if ($(this).attr('src') == '/common/image/grey.gif') {
					$(this).attr('src', $(this).data('original'));
				}
			});
		})
	}

	if ($("img.lazy").exists()) {
		$("img.lazy").lazyload();
	}
	// Ẩn hiện icon go-top
	$(window).scroll(function(){
		if( $(window).scrollTop() == 0 ) {
			$('#go_top').stop(false,true).fadeOut(600);
		}else{
			$('#go_top').stop(false,true).fadeIn(600);
		}
	});

	// Cuộn trang lên với scrollTop
	$('#go_top').click(function(){
		$('body,html').animate({scrollTop:0},400);
		return false;
	});
});

