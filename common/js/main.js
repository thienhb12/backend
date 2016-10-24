function formatMoney(num) {
    var p = num.toFixed(2).split(".");
    return  p[0].split("").reverse().reduce(function(acc, num, i, orig) {
        return  num + (i && !(i % 3) ? "," : "") + acc;
    }, "");
}

function setCookie(cookieName,cookieValue,nDays) {
	var today = new Date();
	var expire = new Date();
	if (nDays == null || nDays == 0) nDays = 1;
	expire.setTime(today.getTime() + 3600000*24*nDays);
	document.cookie = cookieName + "=" + escape(cookieValue) + ";expires=" + expire.toGMTString() + ";path=/";
}

function getCookie( name ) {
	var start = document.cookie.indexOf( name + "=" );
	var len = start + name.length + 1;
	if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) ) {
		return null;
	}
	if ( start == -1 ) return null;
	var end = document.cookie.indexOf( ";", len );
	if ( end == -1 ) end = document.cookie.length;
	return unescape( document.cookie.substring( len, end ) );
}

function getParamUrl(name) {
	name = name.replace(/[\[]/,"\\\[").replace(/[\]]/,"\\\]");
	var regexS = "[\\?&]"+name+"=([^&#]*)";
	var regex = new RegExp( regexS );
	var results = regex.exec(window.location.href);

	if( results == null ) {
		return "";
	}
	else {
		return decodeURI(results[1].split('+').join(' '));
	}
}

var menu_time_out;

$(document).ready(function() {
	jQuery.fn.exists = function(){return this.length>0;}

	//Sự kiện hover menu begin
	$('#main-menu .menu-item').hover(function() {
		clearTimeout(menu_time_out);
		$('.submenu-content').hide();
		if ($('.submenu-' + $(this).data('id')).is('div')) {
			$('#menu-over')
				.height($(document).height() - 170)
				.show();
			$('.submenu-' + $(this).data('id')).show();
		}else {
			hidenMenuActive();
		}
	}, function() {
		clearTimeout(menu_time_out);

		menu_time_out = setTimeout(function() {
			hidenMenuActive();
		}, 500);
	});

	function hidenMenuActive() {
		clearTimeout(menu_time_out);
		$('.submenu-content').hide();
		$('.menu-item').removeClass('active');
		$('#menu-over').hide();
	}

	$('.submenu-content').hover(function() {
		clearTimeout(menu_time_out)
		$('.menu-' + $(this).data('id')).addClass('active');
		$(this).show();
	}, function() {
		hidenMenuActive();
	});
	//Sự kiện hover menu END

	/** Load anh sau voi carousel*/
	if($('#carousel-menu-generic').is('div')) {
		$('#carousel-menu-generic').on('slide.bs.carousel', function (evt) {
			$(evt.relatedTarget).find('img').each(function() {
				if ($(this).attr('src') == '/common/image/grey.gif') {
					$(this).attr('src', $(this).data('original'));
				}
			});
		})
	}
	if($('#carousel-small').is('div')) {
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

	if ($("#sl2").exists()) {
		var url = "/common/bootstrap/slider/js/bootstrap-slider.js";
		$.getScript( url, function( data, textStatus, jqxhr ) {
			$('#sl2').slider()
			  .on('slide', function(ev){
				$('#minPrice').val(ev.value[0]);
				$('#maxPrice').val(ev.value[1]);
			  });
		});
	}

	$(".btn-filter-price").on('click', function() {
		var url = location.pathname;
		if (location.search != '') {
			url += location.search;
		}else {
			url += '?f';
		}
		old_price = '&fprice=' + getParamUrl('fprice') + '&tprice=' + getParamUrl('tprice');
		url = url.replace(old_price, '');
		url += '&fprice=' + $('#minPrice').val() + '&tprice=' + $('#maxPrice').val();
		location.href =  url;
	})

	$(".box-attribute .attribute").on('click', function() {
		var url = location.pathname;
		if (location.search != '') {
			url += location.search;
		}else {
			url += '?f';
		}

		var param = 'a_' + $(this).data('attr-id');
		var val = $.trim($(this).data('attr-val'));
		var attr = getParamUrl(param);

		if (attr == '') {
			url = url.replace(param + '=', '');
			url += '&' + param + '=' + val;
		}else {
			url = url.replace(encodeURI(attr), encodeURI(processUrl(val, attr)));
		}

		location.href =  url;
	})

	function processUrl(val, attr) {
		arr_attr = attr.split(',');
		new_arr_attr = [];
		var has_val = false;
		for (var i = 0; i < arr_attr.length; i++) {
			console.log(arr_attr[i],val, arr_attr[i] == val)
			if (arr_attr[i] == val) {
				has_val = true;
			}else {
				new_arr_attr.push(arr_attr[i])
			}
		}

		if (!has_val) {
			new_arr_attr.push(val)
		}

		return new_arr_attr.valueOf();
	}


	$('.toggle-popover').popover({
		content: $(this).find('.popover-content').html(),
		placement: 'bottom',
		trigger: 'click',
		html: true
	}).show();

	$(body).click(function() {
		$('.toggle-popover').popover('hide')
	})

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

	$('#content img').each(function() {
		if ($(this).attr('src').indexOf('/upload/fckeditor/') == 0 || $(this).attr('src').indexOf('/lib/ckfinder') == 0) {
			$(this).attr('src', 'http://dangquangwatch.vn' + $(this).attr('src'));
		}
	})
});
