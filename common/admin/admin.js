$('.back').on('click', function() {
	history.back();
})


function selectStep(title, id) {
	if($('#related_link').is('ul'))
	{
		if (!$('#link_' + id).is('li'))
		{
			$('#related_link').append('<li id="link_' + id + '" class="list-group-item"><span class="badge" onclick="deleteRelatedLink(this)">Xóa</span>' + title + '<input type="hidden" name="related_id[]" class="clear"  value="' + id + '" /></li>');
		}

		$( "#related_link" ).sortable({
		  revert: true
		});
	}
};

$('#list-step li .badge').on('click', function() {
	var li = $(this).parent();
	selectStep(li.text().replace('↓', ''), li.attr('data-id'));
});

function deleteRelatedLink (obj) {
	$(obj).parent().remove();
}

$('#fieldType').on('change', function() {
	switch ($(this).val()) {
		case 'radio':
		case 'select':
			$('#inputSource').attr('required', 'required')
		break;
		default:
			$('#inputSource').removeAttr('required')
	}
})

function getURLParams(name) {
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

function showListAtrribute(config, callbackFn ) {
	self = this;
	url = '/index.php?mod=admin&amod=tintuc&atask=tintuc&task=pager&ajax'
	this.config = config;
	if (config.url) {
		url = config.url;
	}
	if (config.page) {
		url += '&page=' + config.page;
	}
	if (config.filter) {
		url += '&filter=' + config.filter;
	}
	if (config.atask) {
		url += '&atask=' + config.atask;
	}
	$('#list-step').attr('data-atask', config.atask);
	$('#list-step').attr('data-url', config.url);
	$('#list-step').html('<li class="text-center"><span class="glyphicon glyphicon-refresh glyphicon-refresh-animate"></span> đang tải...</li>');
        
	$.getJSON( url, function( data ) {
            console.log(111);
		$('#list-step').html("");
		if (data.items.length > 0) {
			for (var i = 0; i < data.items.length; i++) {
				$item = data.items[i];
				$('#list-step').append('<li class="list-group-item" data-id="' + $item.id + '">		<span class="badge btn btn-default ">↓</span>' + $item.title + '</li>');
			}
		}else {
			$('#list-step').append('<li style="text-align: center;">Không có dữ liệu</li>');
		}
                
		$('#list-step li .badge').on('click', function() {
			var li = $(this).parent();
			selectStep(li.text().replace('↓', ''), li.attr('data-id'));
		});

		if (typeof (callbackFn) == 'function') {
			callbackFn(self.config.page, data.total_pages);
		}
	});
}

$('.btn.btn-default.attribute').click(function() {
    
	showListAtrribute({page: 1, atask: 'tintuc'}, function(page, total_pages) {
		if (total_pages > 1) {
			$('#pager').bootpag({
			   total: total_pages,
			   page: 1,
			   maxVisible: 6,
			   href: "#pro-page-{{number}}",
			   leaps: false,
			   next: 'sau',
			   prev: 'trước'
			}).on('page', function(event, num){
				showListAtrribute({page: num, filter: $('.search').val()});
			});
		}
		$('#related').modal('show');
	});
})

$('.btn.btn-default.btn-search').click(function() {
	var config = {page: 1, filter: $('.search').val(), atask: $('#list-step').data('atask')}
	if ($('#list-step').data('url')) {
		config.url = $('#list-step').data('url')
	}
	showListAtrribute(config, function(total_pages) {
		if (total_pages > 1) {
			$('#pager').bootpag({
			   total: total_pages,
			   page: 1,
			   maxVisible: 6,
			   href: "#pro-page-{{number}}",
			   leaps: true ,
			   next: 'sau',
			   prev: 'trước'
			}).on('page', function(event, num){
				showListAtrribute({page: num, filter: $('.search').val()});
			});
		}
		$('#related').modal('show');
	});
	return false;
});

$('#btn-next-attribute').on('click', function() {
	if ($('#product_type_id').val() != '-1') {
		location.href = location.href + '&product_type_id=' +$('#product_type_id').val();
	}else {
		$('#product_type_id').focus();
	}
})


//DOCUMENT READY
$(function() {

	if($('.colorpicker').is('input'))
		$('.colorpicker').colorpicker();

	$( "#related_link" ).sortable({
	  revert: true
	});

	$('.datetimepicker').datetimepicker();

	$('.list-attribute-file li .badge').each(function() {
		$(this).click(function() {
			$(this).parent().remove();
		})
	})

	$.fn.hasAttr = function(name) {
	   return this.attr(name) !== undefined;
	};
});


if ($('#article').is('textarea')) {
	$('#article').ckeditor();
	$('#create_datetimepicker').datetimepicker();

	$('.avatar-item .close').click(function() {
		$(this).parent('.avatar-item').remove();
	})
}

$('.form-horizontal').validator();

if($('.add_answer').is('button')) {
	$('.add_answer').on('click', function(){
		var item_html = '<div class="form-group">' +
					'<label for="title" class="col-sm-2 control-label">' +
						$(this).data('label') + ' ' + ($('#list_answer .form-group').length + 1) +
					'</label>'+
					 '<div class="col-sm-5 input-group" style="padding-left: 15px;">' +
						'<input type="text" class="form-control" name="answer[]" value="" required />' +
						'<span class="input-group-btn">' +
							'<button class="btn btn-default delete" type="button">' + $(this).data('delete') + '</button>' +
						'</span>' +
					'</div>' +
				'</div>';
		var item = $(item_html);
		item.find('.input-group .btn.btn-default.delete').on('click', function() {
			$(this).parents('.form-group').remove();
		});
		$('#list_answer').append(item);
	});
}

$('#list_answer .input-group .btn.btn-default.delete').on('click', function() {
	$(this).parents('.form-group').remove();
});

makeImage = function(img_url, image_id, position) {
	var item = $('.thumb-' + position);
	item.find('img').attr('src', img_url);
	//item.append('<input type="hidden" name="avatar_position" value="' + position + '" />');
	//item.append('<input type="hidden" name="avatar" value="' + img_url + '" />');
	//item.append('<button type="button" class="close"><span aria-hidden="true">×</span></button>');

	/*item.find('.close').click(function() {
		Holder.run({images:$(this).parent('img')});
		$(this).parent().find('input').remove();
		$(this).parent().find('button').remove();
	})*/
        item.find('input').attr('value', img_url);
}


$('#avatar-thumbnail .close').click(function() {
    $(this).parent().find('img').attr("src", "");
    $(this).parent().find('input').attr('value', '');
    Holder.run();
})

$('#avatar-thumbnail .thumbnail img').click(function() {
	var url = 'index.php?mod=admin&amod=images&atask=images&task=manage&ajax';
	url += '&position=' +  $(this).data('position');
	window.open(url, "_blank", "toolbar=yes, scrollbars=yes, resizable=yes, top=100, left=50%, width=600, height=600");
})