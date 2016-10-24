/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.dialog.add( 'File', function( editor )
{
	String.prototype.startWith = function(str) {
		return (this.match("^"+str)==str);
	};

	String.prototype.trim = function() {
		return this.replace(/^\s+|\s+$/g,"");
	};

	CKEDITOR.dialog.insertFile = function (link, desc) {

	    var content;
		var label = CKEDITOR.dialog.getCurrent().getContentElement('divFile', 'txtContent').getValue();

		if (!label || label == '' && desc)
			label = desc;
		else if (!label || label == '')
			label = link.substring(link.lastIndexOf('/')+1,link.length);

		if(link.startWith("/")) {
			content = '<a href="'+link+'">'+label+'</a>';
		} else {
			content = '<a href="/'+link+'">'+label+'</a>';
		}
		editor.insertHtml(content);

		CKEDITOR.dialog.getCurrent().hide();
	};


	CKEDITOR.dialog.deleteFile = function (id) {
		url = "/index.php?mod=admin&amod=tintuc&atask=file&task=delete&ajax&id="+ id;
		$.getJSON( url, function( data ) {
			if (data.delete == 1) {
				CKEDITOR.dialog.get_file_list(1);
			}else {
				alert('Lỗi trong quá trình xóa!')
			}
		});
	};

	CKEDITOR.dialog.fileUploadOk = function() {
		CKEDITOR.dialog.get_file_list(1);
	},

	OnUploadCompleted = function ( errorNumber, fileUrl, fileName )
	{
		switch ( errorNumber )
		{
			case 1 :	// No errors
				alert( 'Tải file lên thành công' ) ;
				break ;
			case 179:
				alert('Kích thước ảnh vượt quá quy định');
				break;
			case 201 :
				alert( 'Đã tồn tại file có tên trùng với file của bạn. File của bạn được đổi tên thành "' + fileName + '"' ) ;
				break ;
			case 202 :
				alert( 'Kiểu file không hợp lệ' ) ;
				break;
			case 259:
				alert('Kích thước ảnh vượt quá quy định. Đề nghị nhập ảnh khác.');
				break;
			case 203 :
				alert( "Lỗi bảo mật. Có thể bạn không được quyền tải file lên. Hãy kiểm tra lại máy chủ." ) ;
				break;
			case 999:
				alert("Lỗi chèn ảnh vào DB.");
				break;
			default :
				alert( 'Lỗi tải file. Mã lỗi: ' + errorNumber ) ;
		}
		try {
			CKEDITOR.dialog.get_file_list(1);
		}
		catch (e) {console.log(e)}

		CKEDITOR.dialog.getCurrent().selectPage('divFile');

	};

	CKEDITOR.dialog.get_file_list = function(page) {
		var holder = document.getElementById("file_list");
		holder.innerHTML = '<table style="width: 100%; text-align: center;"><tr><td style="text-align: center;"><img src="/core/ckeditor/loading.gif" style="vertical-align: middle" /> Đang tải dữ liệu</td></tr></table>';
		var keword = CKEDITOR.dialog.getCurrent().getContentElement('divFile', 'keyword').getValue();
		var url = '/index.php?mod=admin&amod=tintuc&atask=file&task=getlist&ajax&page=' + page + '&filter='+encodeURIComponent(keword);

		$.getJSON( url, function( data ) {
			var holder = document.getElementById("file_list");
			var content = "";
			console.log(data);

			var items = data.items;

			for (i = 0; i < items.length; i++) {
				var fileId = items[i].id;
				var fileName = items[i].url;
				var fileType = items[i].type;
				var fileDesc = items[i].description;

				content += '<tr>';
				content += '<td style="padding-top: 2px; padding-bottom: 2px; font-size: 12px; border: 1px solid; border-collapse: separate"><div style="width: 200px; overflow: hidden; word-wrap: break-word;">'+fileName+'</div></td>';
				content += '<td style="width: 250px; padding-top: 2px; padding-bottom: 2px; font-size: 12px; border: 1px solid; border-collapse: separate;white-space: pre-wrap;white-space: -moz-pre-wrap;white-space: -pre-wrap;white-space: -o-pre-wrap;word-wrap: break-word;">'+fileDesc+'</td>';
				content += '<td style="text-align: center; padding-top: 2px; padding-bottom: 2px; font-size: 12px; border: 1px solid; border-collapse: separate">'+"<a style='cursor: pointer;color: blue' href='javascript:CKEDITOR.dialog.insertFile(\""+fileName+"\",\""+fileDesc+"\")'>Ch&#232;n v&#224;o b&#224;i</a> "
					+ " | <a style='cursor: pointer;color: blue' href='javascript:CKEDITOR.dialog.deleteFile("+fileId+")'>Delete</a> "
					+ '</td>';
				content += '</tr>';
			}

            var pagination_area = '';
			if (content == "") {
				content = "<tr><td>Không có kết quả</td></tr>";
			} else {
                var next = data.next;

                if (page != "") {
                   if ( parseInt(page) > 1)
                   {
                       pagination_area += '<td style="text-align:left; border:0;" nowrap="nowrap"><a style="cursor: pointer;color: blue" href="javascript: CKEDITOR.dialog.get_file_list(' + (parseInt(page) - 1) + ')"> Trang trước </a></td>';
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
                    pagination_area += '<td style="text-align:right; border:0;" nowrap="nowrap"><a style="cursor: pointer;color: blue" href="javascript: CKEDITOR.dialog.get_file_list(' + (parseInt(page) + 1 ) + ')"> Trang sau </a></td>';
                }
                else
                {
                   pagination_area += '<td style="border:0;" nowrap="nowrap"></td>';
                }
                content = '<tr>' + pagination_area + '</tr>' + content + '<tr>' + pagination_area + '</tr>';
            }
			holder.innerHTML = '<table style="width: 98%; border-collapse: separate; border-spacing: 3px" border="1" cellspacing="1" cellpadding="3" width="98%" id="file_items">' + content + '</table>';
		});
	};
	return {
		title : 'Chèn tập tin',
		width : 600,
		minHeight : 350,
		onShow : function()
		{
			CKEDITOR.dialog.get_file_list(1);
			$('.cke_dialog_tabs a').removeClass('cke_dialog_tab_disabled');
		},
		contents : [
			{
				id : 'divFile',
				label : 'Tìm tập tin',
				title : 'Tìm tập tin',
				elements : [
						{
							type: 'hbox',
							widths : [ '100px', '300px', '200px', '50px'],
							children:
								[
									 {
										 type: 'html',
										 html: '<div />'
									 }
									 ,
									 {
										id : 'keyword',
										type : 'text',
										label : 'Từ khoá',
										'default' : ''
									 }
									 ,
									 {
										type : 'button',
										label : 'Tìm kiếm',
										style : 'display:inline-block;margin-top:13px;',
										align : 'center',
										onClick: function() {
											CKEDITOR.dialog.get_file_list(1);
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
									id : 'FileListContainer',
									height: 350,
									style : 'width:95%;',
									html: '<div id="file_list" style="text-align: center;overflow: auto;height: 260px;"><table width="100%"><tr><td style="text-align: center; font-weight: bold;">Nhấn nút tìm kiểm để tải dữ liệu</td></tr></table></div>'
								}
							]
						}
						,
						{
							type: 'hbox',
							widths : ['', '50px', '150px'],
							children:
								[
									{
										type: 'html',
										html: '<div />'
									}
									,
									{
										type : 'html',
										html: '<span>Text</span>'
										//style : 'display:inline-block;margin-top:13px;'
									}
									,
									{
										id : 'txtContent',
										type : 'text',
										'default' : ''
									}
								]
						}
					]
			}
			,
			{
				id : 'Upload',
				label : editor.lang.image.upload,
				elements :
				[
					{
						type : 'html',
						style: 'overflow: none',
						html: '<form id="frmUpload" name="frmUpload" method="post" target="UploadWindow" enctype="multipart/form-data" action="/index.php?mod=admin&amod=tintuc&atask=file&task=upload&ajax">' +
								'<table cellspacing="0" border="0" align="left" style="width:100%;" role="presentation">' +
								'<tr><td class="cke_dialog_ui_vbox_child">' +
								'<div class="cke_dialog_ui_text">'+
								'<label class="cke_dialog_ui_labeled_label">Tải ảnh lên mấy chủ</label><br />'+
								'</div>' +
								'<div class="cke_dialog_ui_text">'+
								'<input class="cke_dialog_ui_input_file" id="txtUploadFile" style="height: 20px; width: 100%" type="file" size="40" name="Filedata" />' +
								'</div>' +
								'</td></tr>'+
								'<div class="cke_dialog_ui_text">'+
								'<tr><td class="cke_dialog_ui_vbox_child">' +
								'<label class="cke_dialog_ui_labeled_label">Ghi chú</label><br />'+
								'</div>' +
								'<div class="cke_dialog_ui_text">'+
								'<div role="presentation" class="cke_dialog_ui_input_text">'+
								'<input class="cke_dialog_ui_input_text" type="text" size="40" name="description" id="desc" />'+
								'</div>' +
								'</div>' +
								'</td></tr>'+
								'<tr><td class="cke_dialog_ui_vbox_child">' +
								'<div class="cke_dialog_ui_text">'+
								'<a aria-labelledby="cke_167_label" role="button" class="cke_dialog_ui_fileButton cke_dialog_ui_button" hidefocus="true" title="Tải lên máy chủ" '+
								'href="javascript:document.frmUpload.submit();" style="-moz-user-select: none;"><span class="cke_dialog_ui_button" id="cke_167_label">Tải lên máy chủ</span></a>'+
								'</div>' +
								'</td></tr>'+
								'</table>' +
								'</form><iframe name="UploadWindow" style="display: none" src="/core/ckeditor/blank.html"></iframe>'
					}
				]
			}
		],
		buttons : [ CKEDITOR.dialog.cancelButton ]
	};
} );