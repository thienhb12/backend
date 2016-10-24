$(document).ready(function() {
	//Khởi tạo Slide Box Tin nổi bật
	if ($('#player-hotnews').is('div')) {
		var hotnews = carousel({
			slides: 'div.item',
			fx: 'tileBlind',
			'tile-count': 15,
			paused: false,
			placeholder: '#player-hotnews'
		});

		hotnews
		.hover(function() {
			$(this).find('.cycle-control').show();
		}, function() {
			$(this).find('.cycle-control').hide()
		})
		.on('cycle-before', function(event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag) {
			if ($(incomingSlideEl).find('img').attr('src') == '') {
				$(incomingSlideEl).find('img').attr('src', $(incomingSlideEl).find('img').attr('data-src'));
			}
		});
	}

	if ($('#player-clip').is('div')) {
		var cliphot = carousel({
			slides: 'div.block',
			next: '.clip_next',
			prev: '.clip_prev',
			placeholder: '#player-clip'
		});

		cliphot
		.on('cycle-before', function(event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag) {
			if ($(incomingSlideEl).find('img').attr('src') == '') {
				$(incomingSlideEl).find('img').attr('src', $(incomingSlideEl).find('img').attr('data-src'));
			}
		});
	}
	if ($('#player-photo').is('div')) {
		var photo = carousel({
			slides: 'div.item',
			next: '.photo-next',
			prev: '.photo-prev',
			placeholder: '#player-photo'
		});

		photo
		.on('cycle-before', function(event, optionHash, outgoingSlideEl, incomingSlideEl, forwardFlag) {
			if ($(incomingSlideEl).find('img').attr('src') == '') {
				$(incomingSlideEl).find('img').attr('src', $(incomingSlideEl).find('img').attr('data-src'));
				$(incomingSlideEl).removeClass('hide');
			}
		});
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