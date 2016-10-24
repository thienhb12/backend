jQuery(document).ready(function($) {
	// khởi tạo lazy load
	  $(".lazy").lazyload({
            effect : "fadeIn"
        });
	
	// khởi tạo owl caroulsel sản phẩm mới
$(".owl-carousel").owlCarousel({
	    responsiveClass:true,
        lazyLoad : true,
        dots : false,
        loop : true,
        nav  : true,
        margin :0,
        autoplayTimeout:3000,
        autoplay :true,
        navText : ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
        responsive:{
            0:{
                items:1,
            },
            568:{
                items:2,
            },
            600:{
                items:2,
            },
            1024:{
                items:4,
            },
            1200:{
                items:5,
            }
        }
	});
    
	// khởi tạo owl caroulsel tintuc
	$(".news-carousel").owlCarousel({
	    responsiveClass:true,
        lazyLoad : true,
        dots : false,
        loop : true,
        nav  : true,
        margin : 10,
        autoplayTimeout:3000,
        autoplay :true,
        navText : ['<i class="fa fa-angle-left"></i>','<i class="fa fa-angle-right"></i>'],
        responsive:{
            0:{
                items:1,
            },
            568:{
                items:2,
            },
            600:{
                items:2,
            },
            1024:{
                items:3,
            },
            1200:{
                items:3,
            }
        }
	});

	// lazy load slider
    $('#carousel-id').on('slide.bs.carousel', function (evt) {
        var img = $(evt.relatedTarget).find('img');
        if (img.attr('src') == 'assets/images/grey.gif') {
            img.attr('src', img.data('original'));
        }
    })

    // scroll top
    $(document).on('click','.scroll_top',function(){
        $('body,html').animate({scrollTop:0},400);
        return false;
    })

    $(window).scroll(function(){
    	if( $(window).scrollTop() == 0 ) {
            $('.scroll_top').stop(false,true).fadeOut(600);
        }else{
            $('.scroll_top').stop(false,true).fadeIn(600);
        }
    });

    $('input[name=payment]').click(function(event) {
        if($("input[name=payment]:checked").val() == 0){
            $(".visa").hide();
            $(".direct").show();
        }else{  
            $(".direct").hide();
            $(".visa").show();
        }
    });

    $(window).scroll(function(){
        if($(window).scrollTop() > 540){ 
            $(".navbar-default").addClass("navbar-fixed-top");
            $('.navbar-nav').css('display','none');
        }
        else{ 
            $(".navbar-default").removeClass("navbar-fixed-top");
            $('.navbar-nav').css('display','block');
        }
    });
    
    $(".navbar-header,.navbar-nav").on( "mouseover", function() {
        $('.navbar-fixed-top .navbar-nav').css('display','block');
    })
    .on( "mouseout", function() {
        $('.navbar-fixed-top .navbar-nav').css('display','none');
    });

   

    $('#grid').isotope({
      // options    
      itemSelector: '.grid-item',
      percentPosition: true,
    });
});