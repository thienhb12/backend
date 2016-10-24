/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add( 'Media', function( editor )
{
	var calendar = new Array();
	var getSDate = function() {
		var sDate = new Date();
		sDate.setDate(sDate.getDate() - 14)

		var sday = "" + sDate.getDate();
		if (sday.length == 1)
		{
			sday = "0" + sday;
		}
		var smonth = "" + (sDate.getMonth() + 1);
		if (smonth.length == 1)
		{
			smonth = "0" + smonth;
		}
		var syear = sDate.getFullYear();
		return sday + '/' + smonth + '/' + syear;
	};

	var getEDate = function() {
		var eDate = new Date();
		eDate.setDate(eDate.getDate()+1);
		var eday = "" + eDate.getDate();
		if (eday.length == 1)
		{
			eday = "0" + eday;
		}
		var emonth = "" + (eDate.getMonth() + 1);
		if (emonth.length == 1)
		{
			emonth = "0" + emonth;
		}
		var eyear = eDate.getFullYear();

		return eday + '/' + emonth + '/' + eyear ;
	};

	var showCalendar = function(input) {
		if (!calendar[input])
			calendar[input] = new calendar1(CKEDITOR.dialog.getCurrent().getContentElement('divMedia', input).getInputElement().$);
		var cal = calendar[input];
		cal.year_scroll = true;
		cal.time_comp = false;
		cal.popup();

	};

	CKEDITOR.dialog.insertMedia = function (id, filename) {
		var media;
		filename = filename.toLowerCase();
		if (filename.indexOf('.flv') > -1 || filename.indexOf('.mp4') > -1) {
			media = 'video';
		} else if (filename.indexOf('.mp3') > -1 || filename.indexOf('.mid') > -1) {
			media = 'audio';
		} else if (filename.indexOf('.jpg') > -1 || filename.indexOf('.gif') > -1 || filename.indexOf('.png') > -1) {
			media = 'image';
		} else {
			alert('Kiểu file không hợp lệ');
			return;
		}

		var content = '[@media(@id)]';
		content = content
			.replace(/@media/g, media)
			.replace(/@id/g, id);

		var editor = CKEDITOR.dialog.getCurrent().getParentEditor();
		var oContainerDiv = editor.document.createElement('div');
		oContainerDiv.setAttribute( 'class', media) ;
		oContainerDiv.setAttribute('style', 'text-align: center;') ;
		oContainerDiv.$.innerHTML = content;
		editor.insertElement( oContainerDiv );
		CKEDITOR.dialog.getCurrent().hide();
	};

	CKEDITOR.dialog.setIfExists = function (obj,src) {
		var img = new Image();
		img.onload = function () {
			obj.src = src;
		};
		img.src = src;
	};

	CKEDITOR.dialog.get_media_list = function(page) {
		var holder = document.getElementById("media_list");
		holder.innerHTML = '<table style="width: 100%; text-align: center;"><tr><td style="text-align: center;"><img src="/core/ckeditor/loading.gif" style="vertical-align: middle" /> Đang tải dữ liệu</td></tr></table>';

		//var scope =  CKEDITOR.dialog.getCurrent().getContentElement('divMedia', 'scope').getValue();
		var mediaType = CKEDITOR.dialog.getCurrent().getContentElement('divMedia', 'mediaType').getValue();
		var keword = CKEDITOR.dialog.getCurrent().getContentElement('divMedia', 'keyword').getValue();
		//var from_date = CKEDITOR.dialog.getCurrent().getContentElement('divMedia', 'from_date').getValue();
		//var to_date = CKEDITOR.dialog.getCurrent().getContentElement('divMedia', 'to_date').getValue();

		var url = '/index.php?mod=admin&amod=tintuc&atask=media&task=getlist&ajax&page=' + page + '&filter='+encodeURIComponent(keword) + '&type='+mediaType ;
		//&from_date='+encodeURIComponent(from_date)+'&to_date='+encodeURIComponent(to_date);

		$.getJSON( url, function( data ) {
			var holder = document.getElementById("media_list");
			var content = "";
			var media_list = data.items;
			for (i = 0; i < media_list.length; i++) {
				item = media_list[i];
				if ((i%3)==0) {
					content += '<tr>';
				}

				console.log(item)

				content += '<td style="min-width: 125px; width: 30%; text-align: center; vertical-align: middle;">'
					+ '<a href="javascript:CKEDITOR.dialog.insertMedia(' + item.id + ', \'' + item.url + '\');">'
					+ '<img style="width: 150px;border: none; max-width: 90%;cursor: pointer;" src="' + item.avatar + '" onerror ="CKEDITOR.dialog.setIfExists(this,\''+editor.config.media_path+'/video.jpg\')"/>'
					+ '<div style="text-align: center; white-space:normal; width: 100%; overflow: hidden;">'
					+ item.name
					+ '</div>'
                    + '</a>'
					+ '</td>';

				if ((i%3) == 2) {
					content += '</tr>';
				}
			}

            var pagination_area = '';
			if (content == "") {
				content = "<tr><td style='text-align: center; font-weight: bold;'>Không có kết quả</td></tr>";
			} else {
				var next = data.next;

                if (page != "") {
                   if ( parseInt(page) > 1)
                   {
                       pagination_area += '<td style="text-align:left; border:0;" nowrap="nowrap"><a style="cursor: pointer;color: blue" href="javascript: CKEDITOR.dialog.get_media_list(' + (parseInt(page) - 1) + ')"> Trang trước </a></td>';
                   }
                   else
                   {
                       pagination_area += '<td style="border:0;" nowrap="nowrap"></td>';
                   }
                } else {
                	pagination_area += '<td style="border:0;" nowrap="nowrap"></td>';
                }
                pagination_area += '<td style="border:0;"></td>';
                if (next != "")
                {
                    pagination_area += '<td style="text-align:right; border:0;" nowrap="nowrap"><a style="cursor: pointer;color: blue" href="javascript: CKEDITOR.dialog.get_media_list(' + (parseInt(page) + 1 ) + ')"> Trang sau </a></td>';
                }
                else
                {
                   pagination_area += '<td style="border:0;" nowrap="nowrap"></td>';
                }
                content = '<tr>' + pagination_area + '</tr>' + content + '<tr>' + pagination_area + '</tr>';
            }
			holder.innerHTML = '<table style="width: 100%" align="center" cellpadding="5" cellspacing="1">' + content + '</table>';
		});
	};

	return {
		title : 'Chèn Media',
		width : 500,
		minHeight : 350,
		onLoad : function()
		{
			CKEDITOR.dialog.get_media_list(1);
		},
		contents : [
			{
				id : 'divMedia',
				label : '',
				title : '',
				elements : [
						{
							type: 'hbox',
							widths : ['20px', '100px', '70px'],
							children:
								[
									 {
										 id: 'mediaType',
										 type: 'select',
										 label: 'Loại Media',
										 'default' : '',
										 onChange: function()
										 {
											CKEDITOR.dialog.get_media_list(1);
										 },
										 items:
											 [
												[ 'All' , ''],
												[ 'Audio' , 'audio'],
												[ 'Video' , 'video']
											 ]
									 },
									{
										id : 'keyword',
										type : 'text',
										width: '200',
										label : 'Từ khoá',
										'default' : ''
									},
									{
										type : 'button',
										label : 'Tìm kiếm',
										style : 'display:inline-block;margin-top:13px;',
										align : 'center',
										onClick: function() {
											CKEDITOR.dialog.get_media_list(1);
										}
									}
								]
						},
						{
							type : 'vbox',
							height : 350,
							children :
							[
								{
									type : 'html',
									id : 'MediaListContainer',
									style : 'width:95%;',
									height: 350,
									html: '<input type="hidden" name="media_list_page" id="media_list_page" value="0" />'+
										'<div id="media_list" style="text-align: center;overflow: auto;height: 260px;"><table width="100%"><tr><td style="text-align: center; font-weight: bold;">Nhấn nút tìm kiểm để tải dữ liệu</td></tr></table></div>'
								}
							]
						}
					]
			}
		],
		buttons : [ CKEDITOR.dialog.cancelButton ]
	};
} );
