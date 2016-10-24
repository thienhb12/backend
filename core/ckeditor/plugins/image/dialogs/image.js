/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

(function()
{
	var imageDialog = function( editor, dialogType )
	{
		var standardWidth = new Array('120', '120', '400', '200', '400', '450');
		var standardHeight = new Array('120', '120', '400', '200', '400', '450');
		
		var calendar = new Array();
		// Load image preview.
		var IMAGE = 1,
			LINK = 2,
			PREVIEW = 4,
			CLEANUP = 8,
			regexGetSize = /^\s*(\d+)((px)|\%)?\s*$/i,
			regexGetSizeOrEmpty = /(^\s*(\d+)((px)|\%)?\s*$)|^$/i,
			pxLengthRegex = /^\d+px$/;
		
			use = function (object, imageUrl) {
			var src = "";
			if (!imageUrl){
				src = object.src;
			}
			else {
				src = imageUrl;
			}

			src = src;
			CKEDITOR.dialog.getCurrent().getContentElement('info', 'txtUrl').setValue(src);                                                     
			CKEDITOR.dialog.getCurrent().getContentElement('info','txtAlt').setValue(object.title);
			CKEDITOR.dialog.getCurrent().selectPage('info');
		};
		
		setOriginalInfo = function (original_url, original_width, original_height, desc )
		{
			//GetE('original_url').value = original_url;
			//GetE('original_width').value = original_width;
			//GetE('original_height').value = original_height;
		};
		
		OnUploadCompleted = function ( errorNumber, fileUrl, thumbUrl, fileName )
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
					return ;
				case 259:
					alert('Kích thước ảnh vượt quá quy định. Đề nghị nhập ảnh khác.');
					return;
				case 203 :
					alert( "Lỗi bảo mật. Có thể bạn không được quyền tải file lên. Hãy kiểm tra lại máy chủ." ) ;
					return ;
				case 999:
					alert("Lỗi chèn ảnh vào DB.");
					return;
				default :
					alert( 'Lỗi tải file. Mã lỗi: ' + errorNumber ) ;
					return ;
			}

			try {
				get_image_list(1);
			}
			catch (e) {}

			//sActualBrowser = ''
			CKEDITOR.dialog.getCurrent().getContentElement('info', 'txtUrl').setValue(fileUrl);
			CKEDITOR.dialog.getCurrent().getContentElement('info','txtAlt').setValue(document.getElementById('desc').value);
			CKEDITOR.dialog.getCurrent().selectPage('info');
			
		};
		
		get_image_list = function(page) {
			var holder = document.getElementById("image_list");
			holder.innerHTML = '<table style="width: 100%; text-align: center;"><tr><td style="text-align: center;"><img src="/core/ckeditor/loading.gif" style="vertical-align: middle" /> Đang tải dữ liệu</td></tr></table>';
			
			var url = '/index.php?mod=admin&amod=images&atask=images&task=list&ajax&page='+page;
			
			//var scope =  CKEDITOR.dialog.getCurrent().getContentElement('divSearchImage', 'scope').getValue();
			//url += '&scope='+encodeURIComponent(scope);

			//var from_date = CKEDITOR.dialog.getCurrent().getContentElement('divSearchImage', 'from_date').getValue();
			//url += '&from_date='+encodeURIComponent(from_date);

			//var to_date = CKEDITOR.dialog.getCurrent().getContentElement('divSearchImage', 'to_date').getValue();
			//url += '&to_date='+encodeURIComponent(to_date);
		        
			var keword = CKEDITOR.dialog.getCurrent().getContentElement('divSearchImage', 'keyword').getValue();
			url += '&filter='+encodeURIComponent(keword);
			
			$.getJSON( url, function( data ) {
				var holder = document.getElementById("image_list");
				var content = "";
				var image_list = data.items;
				for (i = 0; i < image_list.length; i++) {
					var item = image_list[i];
					if (!(i%3)) {
						content += '<tr>';
					}
					content += '<td style="min-width: 125px; width: 30%; text-align: center; vertical-align: middle;">' 
						 + '<img style="width: 110px;border: none; cursor: pointer;" src="' + item.url + '" onclick="use(this,\'' + item.url + '\');" title="' + item.description + '" />'
						 + '</td>';

					if ((i%3) == 2) {
						content += '</tr>';
					}
				}

				if (content == "") {
					content = "<tr><td>Không có kết quả</td></tr>"
				}

				holder.innerHTML = '<table style="width: 100%" cellpadding="5" cellspacing="1">' + content + '</table>';				
				
				var page = data.page;
				
				var next = data.next;

				var pagination_area = '';
				pagination_area = '<table style="border: none; min-width: 375px; width: 100%;" border="0"><tr>';
				if (page != '')
				{   
				   if ( parseInt(page) > 1)  {
					   pagination_area += '<td style="border: none; text-align: left; color: blue;"><a style="color: blue;cursor: pointer;" href="javascript: get_image_list('
								+(parseInt(page) - 1)
								+')"> Trang trước </a></td>';
				   }
				   else  {
					   pagination_area += '<td style="border: none; align: left;">&nbsp;</td>';
				   }
				}

				if (next != ''){
					pagination_area += '<td style="border: none; text-align: right; color: blue;"><a style="color: blue;cursor: pointer;" href="javascript: get_image_list('+(parseInt(page)+1)+')"> Trang sau </a></td>';
				}
				else{
				   pagination_area += '<td style="border: none; align: right;">&nbsp;</td>';
				}
				pagination_area += '</tr></table>';
				holder.innerHTML = pagination_area +  holder.innerHTML + pagination_area;
			});
		};
		
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
				calendar[input] = new calendar1(CKEDITOR.dialog.getCurrent().getContentElement('divSearchImage', input).getInputElement().$);
			var cal = calendar[input];
			cal.year_scroll = true;
			cal.time_comp = false;
			cal.popup();
			
		};
		
		function SetStandardSize(dialog)
		{
			var val = dialog.getValueOf( 'info', 'cmbImageType'); 
			if ( val == -1)
			{
				return;
			}
			var oImageOriginal = dialog.originalElement;
			if ( oImageOriginal ) 
			{
				dialog.setValueOf( 'info', 'txtWidth', standardWidth[val] );
				dialog.getContentElement('info', 'txtWidth').onKeyUp();
			}
			else 
			{
				dialog.setValueOf( 'info', 'txtWidth', standardWidth[val] );
				dialog.getContentElement('info', 'txtWidth').onKeyUp();
			}
		}
		
		var onSizeChange = function()
		{
			var value = this.getValue(),	// This = input element.
				dialog = this.getDialog(),
				aMatch  =  value.match( regexGetSize );	// Check value
			if ( aMatch )
			{
				if ( aMatch[2] == '%' )			// % is allowed - > unlock ratio.
					switchLockRatio( dialog, false );	// Unlock.
				value = aMatch[1];
			}

			// Only if ratio is locked
			if ( dialog.lockRatio )
			{
				var oImageOriginal = dialog.originalElement;
				if ( oImageOriginal.getCustomData( 'isReady' ) == 'true' )
				{
					if ( this.id == 'txtHeight' )
					{
						if ( value && value != '0' )
							value = Math.round( oImageOriginal.$.width * ( value  / oImageOriginal.$.height ) );
						if ( !isNaN( value ) )
							dialog.setValueOf( 'info', 'txtWidth', value );
					}
					else		//this.id = txtWidth.
					{
						if ( value && value != '0' )
							value = Math.round( oImageOriginal.$.height * ( value  / oImageOriginal.$.width ) );
						if ( !isNaN( value ) )
							dialog.setValueOf( 'info', 'txtHeight', value );
					}
				}
			}
			updatePreview( dialog );
		};

		var updatePreview = function( dialog )
		{
			//Don't load before onShow.
			if ( !dialog.originalElement || !dialog.preview )
				return 1;

			// Read attributes and update imagePreview;
			dialog.commitContent( PREVIEW, dialog.preview );
			return 0;
		};

		// Custom commit dialog logic, where we're intended to give inline style
		// field (txtdlgGenStyle) higher priority to avoid overwriting styles contribute
		// by other fields.
		function commitContent()
		{
			var args = arguments;
			var inlineStyleField = this.getContentElement( 'advanced', 'txtdlgGenStyle' );
			inlineStyleField && inlineStyleField.commit.apply( inlineStyleField, args );

			this.foreach( function( widget )
			{
				if ( widget.commit &&  widget.id != 'txtdlgGenStyle' )
					widget.commit.apply( widget, args );
			});
		}

		// Avoid recursions.
		var incommit;

		// Synchronous field values to other impacted fields is required, e.g. border
		// size change should alter inline-style text as well.
		function commitInternally( targetFields )
		{
			if ( incommit )
				return;

			incommit = 1;

			var dialog = this.getDialog(),
				element = dialog.imageElement;
			if ( element )
			{
				// Commit this field and broadcast to target fields.
				this.commit( IMAGE, element );

				targetFields = [].concat( targetFields );
				var length = targetFields.length,
					field;
				for ( var i = 0; i < length; i++ )
				{
					field = dialog.getContentElement.apply( dialog, targetFields[ i ].split( ':' ) );
					// May cause recursion.
					field && field.setup( IMAGE, element );
				}
			}

			incommit = 0;
		};

		var switchLockRatio = function( dialog, value )
		{
			if ( !dialog.getContentElement( 'info', 'ratioLock' ) )
				return null;

			var oImageOriginal = dialog.originalElement;

			// Dialog may already closed. (#5505)
			if( !oImageOriginal )
				return null;

			// Check image ratio and original image ratio, but respecting user's preference.
			if ( value == 'check' )
			{
				if ( !dialog.userlockRatio && oImageOriginal.getCustomData( 'isReady' ) == 'true'  )
				{
					var width = dialog.getValueOf( 'info', 'txtWidth' ),
						height = dialog.getValueOf( 'info', 'txtHeight' ),
						originalRatio = oImageOriginal.$.width * 1000 / oImageOriginal.$.height,
						thisRatio = width * 1000 / height;
					dialog.lockRatio  = false;		// Default: unlock ratio

					if ( !width && !height )
						dialog.lockRatio = true;
					else if ( !isNaN( originalRatio ) && !isNaN( thisRatio ) )
					{
						if ( Math.round( originalRatio ) == Math.round( thisRatio ) )
							dialog.lockRatio = true;
					}
				}
			}
			else if ( value != undefined )
				dialog.lockRatio = value;
			else
			{
				dialog.userlockRatio = 1;
				dialog.lockRatio = !dialog.lockRatio;
			}

			var ratioButton = CKEDITOR.document.getById( btnLockSizesId );
			if ( dialog.lockRatio )
				ratioButton.removeClass( 'cke_btn_unlocked' );
			else
				ratioButton.addClass( 'cke_btn_unlocked' );

			ratioButton.setAttribute( 'aria-checked', dialog.lockRatio );

			// Ratio button hc presentation - WHITE SQUARE / BLACK SQUARE
			if ( CKEDITOR.env.hc )
			{
				var icon = ratioButton.getChild( 0 );
				icon.setHtml(  dialog.lockRatio ? CKEDITOR.env.ie ? '\u25A0': '\u25A3' : CKEDITOR.env.ie ? '\u25A1' : '\u25A2' );
			}

			return dialog.lockRatio;
		};

		var resetSize = function( dialog )
		{
			var oImageOriginal = dialog.originalElement;
			if ( oImageOriginal.getCustomData( 'isReady' ) == 'true' )
			{
				var widthField = dialog.getContentElement( 'info', 'txtWidth' ),
					heightField = dialog.getContentElement( 'info', 'txtHeight' );
				widthField && widthField.setValue( oImageOriginal.$.width );
				heightField && heightField.setValue( oImageOriginal.$.height );
			}
			updatePreview( dialog );
		};

		var setupDimension = function( type, element )
		{
			if ( type != IMAGE )
				return;

			function checkDimension( size, defaultValue )
			{
				var aMatch  =  size.match( regexGetSize );
				if ( aMatch )
				{
					if ( aMatch[2] == '%' )				// % is allowed.
					{
						aMatch[1] += '%';
						switchLockRatio( dialog, false );	// Unlock ratio
					}
					return aMatch[1];
				}
				return defaultValue;
			}

			var dialog = this.getDialog(),
				value = '',
				dimension = this.id == 'txtWidth' ? 'width' : 'height',
				size = element.getAttribute( dimension );

			if ( size )
				value = checkDimension( size, value );
			value = checkDimension( element.getStyle( dimension ), value );

			this.setValue( value );
		};

		var previewPreloader;

		var onImgLoadEvent = function()
		{
			// Image is ready.
			var original = this.originalElement;
			original.setCustomData( 'isReady', 'true' );
			original.removeListener( 'load', onImgLoadEvent );
			original.removeListener( 'error', onImgLoadErrorEvent );
			original.removeListener( 'abort', onImgLoadErrorEvent );

			// Hide loader
			CKEDITOR.document.getById( imagePreviewLoaderId ).setStyle( 'display', 'none' );

			// New image -> new domensions
			if ( !this.dontResetSize )
				resetSize( this );

			if ( this.firstLoad )
				CKEDITOR.tools.setTimeout( function(){ switchLockRatio( this, 'check' ); }, 0, this );

			this.firstLoad = false;
			this.dontResetSize = false;
		};

		var onImgLoadErrorEvent = function()
		{
			// Error. Image is not loaded.
			var original = this.originalElement;
			original.removeListener( 'load', onImgLoadEvent );
			original.removeListener( 'error', onImgLoadErrorEvent );
			original.removeListener( 'abort', onImgLoadErrorEvent );

			// Set Error image.
			var noimage = CKEDITOR.getUrl( editor.skinPath + 'images/noimage.png' );

			if ( this.preview )
				this.preview.setAttribute( 'src', noimage );

			// Hide loader
			CKEDITOR.document.getById( imagePreviewLoaderId ).setStyle( 'display', 'none' );
			switchLockRatio( this, false );	// Unlock.
		};

		var makeElement = function( name )
		{
			return new CKEDITOR.dom.element( name, editor.document );
		};

		var numbering = function( id )
			{
				return CKEDITOR.tools.getNextId() + '_' + id;
			},
			btnLockSizesId = numbering( 'btnLockSizes' ),
			btnResetSizeId = numbering( 'btnResetSize' ),
			imagePreviewLoaderId = numbering( 'ImagePreviewLoader' ),
			imagePreviewBoxId = numbering( 'ImagePreviewBox' ),
			previewLinkId = numbering( 'previewLink' ),
			previewImageId = numbering( 'previewImage' );

		return {
			title : editor.lang.image[ dialogType == 'image' ? 'title' : 'titleButton' ],
			minWidth : 420,
			minHeight : 360,
			onShow : function()
			{
				this.imageElement = false;
				this.linkElement = false;

				// Default: create a new element.
				this.imageEditMode = false;
				this.linkEditMode = false;

				this.lockRatio = true;
				this.userlockRatio = 0;
				this.dontResetSize = false;
				this.firstLoad = true;
				this.addLink = false;

				var editor = this.getParentEditor(),
					sel = this.getParentEditor().getSelection(),
					element = sel.getSelectedElement(),
					link = element && element.getAscendant( 'a' );
					oTable = element && element.getAscendant( 'table' ) && element.getAscendant( 'table' ).$;
					
				if (oTable) {
					var rowLen = oTable.rows.length;
					if (rowLen > 1 && oTable.rows[rowLen - 1] && oTable.rows[rowLen - 1].cells[0])
					{
						oDesc = oTable.rows[rowLen - 1].cells[0].firstChild && oTable.rows[rowLen - 1].cells[0].firstChild.data;
						if (oDesc)
							element.setAttribute('alt', oDesc);
					}
				}
				
				//Hide loader.
				CKEDITOR.document.getById( imagePreviewLoaderId ).setStyle( 'display', 'none' );
				// Create the preview before setup the dialog contents.
				previewPreloader = new CKEDITOR.dom.element( 'img', editor.document );
				this.preview = CKEDITOR.document.getById( previewImageId );

				// Copy of the image
				this.originalElement = editor.document.createElement( 'img' );
				this.originalElement.setAttribute( 'alt', '' );
				this.originalElement.setCustomData( 'isReady', 'false' );

				if ( link )
				{
					this.linkElement = link;
					this.linkEditMode = true;

					// Look for Image element.
					var linkChildren = link.getChildren();
					if ( linkChildren.count() == 1 )			// 1 child.
					{
						var childTagName = linkChildren.getItem( 0 ).getName();
						if ( childTagName == 'img' || childTagName == 'input' )
						{
							this.imageElement = linkChildren.getItem( 0 );
							if ( this.imageElement.getName() == 'img' )
								this.imageEditMode = 'img';
							else if ( this.imageElement.getName() == 'input' )
								this.imageEditMode = 'input';
						}
					}
					// Fill out all fields.
					if ( dialogType == 'image' )
						this.setupContent( LINK, link );
				}
				
				if ( element && element.getName() == 'img' && !element.data( 'cke-realelement' )
					|| element && element.getName() == 'input' && element.getAttribute( 'type' ) == 'image' )
				{
					this.imageEditMode = element.getName();
					this.imageElement = element;
				}

				if ( this.imageEditMode )
				{
					// Use the original element as a buffer from  since we don't want
					// temporary changes to be committed, e.g. if the dialog is canceled.
					this.cleanImageElement = this.imageElement;
					this.imageElement = this.cleanImageElement.clone( true, true );

					// Fill out all fields.
					this.setupContent( IMAGE, this.imageElement );
				}
				else
				{
					this.imageElement =  editor.document.createElement( 'img' );
					CKEDITOR.dialog.getCurrent().selectPage('Upload');
					document.getElementById('txtUploadFile').value = '';
					document.getElementById('desc').value = '';
				}

				// Refresh LockRatio button
				switchLockRatio ( this, true );

				// Dont show preview if no URL given.
				if ( !CKEDITOR.tools.trim( this.getValueOf( 'info', 'txtUrl' ) ) )
				{
					this.preview.removeAttribute( 'src' );
					this.preview.setStyle( 'display', 'none' );
				}

				$('.cke_dialog_tabs a').removeClass('cke_dialog_tab_disabled');
			},
			onOk : function()
			{
				// Edit existing Image.
				if ( this.imageEditMode )
				{
					var imgTagName = this.imageEditMode;

					// Image dialog and Input element.
					if ( dialogType == 'image' && imgTagName == 'input' && confirm( editor.lang.image.button2Img ) )
					{
						// Replace INPUT-> IMG
						imgTagName = 'img';
						this.imageElement = editor.document.createElement( 'img' );
						this.imageElement.setAttribute( 'alt', '' );
						editor.insertElement( this.imageElement );
					}
					// ImageButton dialog and Image element.
					else if ( dialogType != 'image' && imgTagName == 'img' && confirm( editor.lang.image.img2Button ))
					{
						// Replace IMG -> INPUT
						imgTagName = 'input';
						this.imageElement = editor.document.createElement( 'input' );
						this.imageElement.setAttributes(
							{
								type : 'image',
								alt : ''
							}
						);
						editor.insertElement( this.imageElement );
					}
					else
					{
						// Restore the original element before all commits.
						this.imageElement = this.cleanImageElement;
						delete this.cleanImageElement;
					}					
				}
				else	// Create a new image.
				{
					// Image dialog -> create IMG element.
					if ( dialogType == 'image' )
						this.imageElement = editor.document.createElement( 'img' );
					else
					{
						this.imageElement = editor.document.createElement( 'input' );
						this.imageElement.setAttribute ( 'type' ,'image' );
					}
					this.imageElement.setAttribute( 'alt', '' );
				}

				// Create a new link.
				if ( !this.linkEditMode )
					this.linkElement = editor.document.createElement( 'a' );

				// Set attributes.
				this.commitContent( IMAGE, this.imageElement );
				this.commitContent( LINK, this.linkElement );

				// Remove empty style attribute.
				if ( !this.imageElement.getAttribute( 'style' ) )
					this.imageElement.removeAttribute( 'style' );

				// Insert a new Image.
				if ( !this.imageEditMode )
				{
					var table = makeElement( 'table' );	
					var tbody = table.append( makeElement( 'tbody' ));
					
					var imgRow = tbody.append( makeElement( 'tr' ) );
					var imgCell = imgRow.append( makeElement( 'td' ) );
					
					if ( this.addLink )
					{
						//Insert a new Link.
						if ( !this.linkEditMode )
						{
							//editor.insertElement( this.linkElement );
							imgCell.append( this.linkElement );
							this.linkElement.append( this.imageElement, false );
						}
						else	 //Link already exists, image not.
							imgCell.append( this.imageElement );
							//editor.insertElement( this.imageElement );
					}
					else
						imgCell.append( this.imageElement );
						//editor.insertElement( this.imageElement );
					
					var descRow = tbody.append( makeElement( 'tr' ) );
					var descCell = descRow.append( makeElement( 'td' ) );
						descCell.setAttribute('class', 'image_desc');
						
					if ( !CKEDITOR.env.ie )
						descCell.append( makeElement( 'br' ) );
					
					editor.insertElement( table );
					
					element = this.imageElement;
					oTable = table.$;
				}
				else		// Image already exists.
				{
					//Add a new link element.
					if ( !this.linkEditMode && this.addLink )
					{
						editor.insertElement( this.linkElement );
						this.imageElement.appendTo( this.linkElement );
					}
					//Remove Link, Image exists.
					else if ( this.linkEditMode && !this.addLink )
					{
						editor.getSelection().selectElement( this.linkElement );
						editor.insertElement( this.imageElement );
					}					
					var sel = this.getParentEditor().getSelection(),
					element = sel.getSelectedElement(),
					oTable = element && element.getAscendant( 'table' ) && element.getAscendant( 'table' ).$;
				}

				if (oTable) {
					var dialog = CKEDITOR.dialog.getCurrent(),
						cmdAlignElement = dialog.getContentElement( 'info', 'cmbAlign');
					oTable.setAttribute('width',  dialog.getContentElement( 'info', 'txtWidth' ).getValue());
					oTable.setAttribute( "align" ,  cmdAlignElement.getValue()) ;
					
					if (cmdAlignElement.getValue() == 'right')
					{
						oTable.setAttribute( 'class' , 'image rightside' ) ;
					}
					else if (cmdAlignElement.getValue() == 'left')
					{
						oTable.setAttribute( 'class' , 'image leftside' ) ;
					}
					else
					{
						oTable.setAttribute( 'class' , 'image center' ) ;
					}
					var rowLen = oTable.rows.length;
					if (rowLen > 1 && oTable.rows[rowLen - 1] && oTable.rows[rowLen - 1].cells[0])
					{
						oDesc = oTable.rows[rowLen - 1].cells[0];
						if (oDesc.getAttribute('class')=='image_desc')
						{
							if (!element.getAttribute('alt') || '' == element.getAttribute('alt')) {
								oDesc.style.display = 'none';
								oDesc.innerHTML = element.getAttribute('alt');
							} else {
								oDesc.style.display = '';
								oDesc.innerHTML = element.getAttribute('alt');
							}
						}
					}
					
					var table = element && element.getAscendant( 'table' );
					
					var pre = table.$.previousSibling;
					var next = table.$.nextSibling;
										
					if (pre == null || pre.nodeName =='TABLE') {
						console.warn ('insert p before');
						var p = makeElement('p');
						p.setHtml( '&nbsp;' );
						p.insertBefore( table);
					}
					if (next == null || next.nodeName =='TABLE') {
						console.warn ('insert p after');
						var p = makeElement('p');
						p.setHtml( '&nbsp;' );
						p.insertAfter( table);
					}
				}
				$('.cke_dialog_tabs a').removeClass('cke_dialog_tab_disabled');
			},
			onLoad : function()
			{
				//if ( dialogType != 'image' )
					//this.hidePage( 'Link' );		//Hide Link tab.
				var doc = this._.element.getDocument();

				if ( this.getContentElement( 'info', 'ratioLock' ) )
				{
					this.addFocusable( doc.getById( btnResetSizeId ), 5 );
					this.addFocusable( doc.getById( btnLockSizesId ), 5 );
				}

				this.commitContent = commitContent;
				
				get_image_list(1);
				$('.cke_dialog_tabs a').removeClass('cke_dialog_tab_disabled');
			},
			onHide : function()
			{
				if ( this.preview )
					this.commitContent( CLEANUP, this.preview );

				if ( this.originalElement )
				{
					this.originalElement.removeListener( 'load', onImgLoadEvent );
					this.originalElement.removeListener( 'error', onImgLoadErrorEvent );
					this.originalElement.removeListener( 'abort', onImgLoadErrorEvent );
					this.originalElement.remove();
					this.originalElement = false;		// Dialog is closed.
				}

				delete this.imageElement;
			},
			contents : [
				{
					id : 'info',
					label : editor.lang.image.infoTab,
					accessKey : 'I',
					elements :
					[
						{
							type : 'vbox',
							padding : 0,
							children :
							[
								{
									type : 'hbox',
									widths : [ '280px', '110px' ],
									align : 'right',
									children :
									[
										{
											id : 'txtUrl',
											type : 'text',
											label : editor.lang.common.url,
											required: true,
											onChange : function()
											{
												var dialog = this.getDialog(),
													newUrl = this.getValue();

												//Update original image
												if ( newUrl.length > 0 )	//Prevent from load before onShow
												{
													dialog = this.getDialog();
													var original = dialog.originalElement;

													dialog.preview.removeStyle( 'display' );

													original.setCustomData( 'isReady', 'false' );
													// Show loader
													var loader = CKEDITOR.document.getById( imagePreviewLoaderId );
													if ( loader )
														loader.setStyle( 'display', '' );

													original.on( 'load', onImgLoadEvent, dialog );
													original.on( 'error', onImgLoadErrorEvent, dialog );
													original.on( 'abort', onImgLoadErrorEvent, dialog );
													original.setAttribute( 'src', newUrl );

													// Query the preloader to figure out the url impacted by based href.
													previewPreloader.setAttribute( 'src', newUrl );
													dialog.preview.setAttribute( 'src', previewPreloader.$.src );
													updatePreview( dialog );
												}
												// Dont show preview if no URL given.
												else if ( dialog.preview )
												{
													dialog.preview.removeAttribute( 'src' );
													dialog.preview.setStyle( 'display', 'none' );
												}
											},
											setup : function( type, element )
											{
												if ( type == IMAGE )
												{
													var url = element.data( 'cke-saved-src' ) || element.getAttribute( 'src' );
													var field = this;

													this.getDialog().dontResetSize = true;

													field.setValue( url );		// And call this.onChange()
													// Manually set the initial value.(#4191)
													field.setInitValue();
												}
											},
											commit : function( type, element )
											{
												if ( type == IMAGE && ( this.getValue() || this.isChanged() ) )
												{
													element.data( 'cke-saved-src', this.getValue() );
													element.setAttribute( 'src', this.getValue() );
												}
												else if ( type == CLEANUP )
												{
													element.setAttribute( 'src', '' );	// If removeAttribute doesn't work.
													element.removeAttribute( 'src' );
												}
											},
											validate : CKEDITOR.dialog.validate.notEmpty( editor.lang.image.urlMissing )
										},
										{
											type : 'button',
											id : 'browse',
											// v-align with the 'txtUrl' field.
											// TODO: We need something better than a fixed size here.
											style : 'display:inline-block;margin-top:13px;',
											align : 'center',
											label : 'Tìm ảnh',
											onClick: function() {
												CKEDITOR.dialog.getCurrent().selectPage('divSearchImage');
											}
										}
									]
								}
							]
						},
						{
							id : 'txtAlt',
							type : 'text',
							label : editor.lang.image.alt,
							accessKey : 'T',
							'default' : '',
							onChange : function()
							{
								updatePreview( this.getDialog() );
							},
							setup : function( type, element )
							{
								if ( type == IMAGE )
									this.setValue( element.getAttribute( 'alt' ) );
							},
							commit : function( type, element )
							{
								if ( type == IMAGE )
								{
									if ( this.getValue() || this.isChanged() )
										element.setAttribute( 'alt', this.getValue() );
								}
								else if ( type == PREVIEW )
								{
									element.setAttribute( 'alt', this.getValue() );
								}
								else if ( type == CLEANUP )
								{
									element.removeAttribute( 'alt' );
								}
							}
						},
						{
							type : 'hbox',
							children :
							[
								{
									id : 'basic',
									type : 'vbox',
									children :
									[
										{
											type : 'hbox',
											widths : [ '50%', '50%' ],
											children :
											[
												{
													type : 'vbox',
													padding : 1,
													children :
													[
														{
															type : 'text',
															width: '40px',
															id : 'txtWidth',
															label : editor.lang.common.width,
															onKeyUp : onSizeChange,
															onChange : function()
															{
																commitInternally.call( this, 'advanced:txtdlgGenStyle' );
															},
															validate : function()
															{
																var aMatch  =  this.getValue().match( regexGetSizeOrEmpty ),
																	isValid = !!( aMatch && parseInt( aMatch[1], 10 ) !== 0 );
																if ( !isValid )
																	alert( editor.lang.common.invalidWidth );
																return isValid;
															},
															setup : setupDimension,
															commit : function( type, element, internalCommit )
															{
																var value = this.getValue();
																if ( type == IMAGE )
																{
																	if ( value )
																		element.setStyle( 'width', CKEDITOR.tools.cssLength( value ) );
																	else
																		element.removeStyle( 'width' );

																	!internalCommit && element.removeAttribute( 'width' );
																}
																else if ( type == PREVIEW )
																{
																	var aMatch = value.match( regexGetSize );
																	if ( !aMatch )
																	{
																		var oImageOriginal = this.getDialog().originalElement;
																		if ( oImageOriginal.getCustomData( 'isReady' ) == 'true' )
																			element.setStyle( 'width',  oImageOriginal.$.width + 'px');
																	}
																	else
																		element.setStyle( 'width', CKEDITOR.tools.cssLength( value ) );
																}
																else if ( type == CLEANUP )
																{
																	element.removeAttribute( 'width' );
																	element.removeStyle( 'width' );
																}
															}
														},
														{
															type : 'text',
															id : 'txtHeight',
															width: '40px',
															label : editor.lang.common.height,
															onKeyUp : onSizeChange,
															onChange : function()
															{
																commitInternally.call( this, 'advanced:txtdlgGenStyle' );
															},
															validate : function()
															{
																var aMatch = this.getValue().match( regexGetSizeOrEmpty ),
																	isValid = !!( aMatch && parseInt( aMatch[1], 10 ) !== 0 );
																if ( !isValid )
																	alert( editor.lang.common.invalidHeight );
																return isValid;
															},
															setup : setupDimension,
															commit : function( type, element, internalCommit )
															{
																var value = this.getValue();
																if ( type == IMAGE )
																{
																	if ( value )
																		element.setStyle( 'height', CKEDITOR.tools.cssLength( value ) );
																	else
																		element.removeStyle( 'height' );

																	!internalCommit && element.removeAttribute( 'height' );
																}
																else if ( type == PREVIEW )
																{
																	var aMatch = value.match( regexGetSize );
																	if ( !aMatch )
																	{
																		var oImageOriginal = this.getDialog().originalElement;
																		if ( oImageOriginal.getCustomData( 'isReady' ) == 'true' )
																			element.setStyle( 'height', oImageOriginal.$.height + 'px' );
																	}
																	else
																		element.setStyle( 'height',  CKEDITOR.tools.cssLength( value ) );
																}
																else if ( type == CLEANUP )
																{
																	element.removeAttribute( 'height' );
																	element.removeStyle( 'height' );
																}
															}
														}
													]
												},
												{
													id : 'ratioLock',
													type : 'html',
													style : 'margin-top:30px;width:40px;height:40px;',
													onLoad : function()
													{
														// Activate Reset button
														var	resetButton = CKEDITOR.document.getById( btnResetSizeId ),
															ratioButton = CKEDITOR.document.getById( btnLockSizesId );
														if ( resetButton )
														{
															resetButton.on( 'click', function( evt )
																{
																	resetSize( this );
																	evt.data && evt.data.preventDefault();
																}, this.getDialog() );
															resetButton.on( 'mouseover', function()
																{
																	this.addClass( 'cke_btn_over' );
																}, resetButton );
															resetButton.on( 'mouseout', function()
																{
																	this.removeClass( 'cke_btn_over' );
																}, resetButton );
														}
														// Activate (Un)LockRatio button
														if ( ratioButton )
														{
															ratioButton.on( 'click', function(evt)
																{
																	var locked = switchLockRatio( this ),
																		oImageOriginal = this.originalElement,
																		width = this.getValueOf( 'info', 'txtWidth' );

																	if ( oImageOriginal.getCustomData( 'isReady' ) == 'true' && width )
																	{
																		var height = oImageOriginal.$.height / oImageOriginal.$.width * width;
																		if ( !isNaN( height ) )
																		{
																			this.setValueOf( 'info', 'txtHeight', Math.round( height ) );
																			updatePreview( this );
																		}
																	}
																	evt.data && evt.data.preventDefault();
																}, this.getDialog() );
															ratioButton.on( 'mouseover', function()
																{
																	this.addClass( 'cke_btn_over' );
																}, ratioButton );
															ratioButton.on( 'mouseout', function()
																{
																	this.removeClass( 'cke_btn_over' );
																}, ratioButton );
														}
													},
													html : '<div>'+
														'<a href="javascript:void(0)" tabindex="-1" title="' + editor.lang.image.lockRatio +
														'" class="cke_btn_locked" id="' + btnLockSizesId + '" role="checkbox"><span class="cke_icon"></span><span class="cke_label">' + editor.lang.image.lockRatio + '</span></a>' +
														'<a href="javascript:void(0)" tabindex="-1" title="' + editor.lang.image.resetSize +
														'" class="cke_btn_reset" id="' + btnResetSizeId + '" role="button"><span class="cke_label">' + editor.lang.image.resetSize + '</span></a>'+
														'</div>'
												}
											]
										},
										{
											id : 'cmbImageType',
											type : 'select',
											widths : [ '35%','65%' ],
											style : 'width:90px',
											label : editor.lang.common.align,
											'default' : '',
											items :
											[
												[ 'Chọn kiểu' , '-1'],
												[ 'Tin bài' , '0'],
												[ 'Tin bài (Trung bình)' , '3'],
												[ 'Tin bài (To)' , '4'],
												[ 'Tin bài (Rất to)' , '5'],
												[ 'Thế giới anh (ảnh chủ)' , '2'],
												[ 'Thế giới anh (ảnh con)' , '1']
											],
											onChange : function()
											{
												SetStandardSize( this.getDialog() );
											}
										},
										{
											type : 'vbox',
											padding : 1,
											children :
											[
												{
													type : 'text',
													id : 'txtBorder',
													width: '60px',
													label : editor.lang.image.border,
													'default' : '',
													onKeyUp : function()
													{
														updatePreview( this.getDialog() );
													},
													onChange : function()
													{
														commitInternally.call( this, 'advanced:txtdlgGenStyle' );
													},
													validate : CKEDITOR.dialog.validate.integer( editor.lang.image.validateBorder ),
													setup : function( type, element )
													{
														if ( type == IMAGE )
														{
															var value,
																borderStyle = element.getStyle( 'border-width' );
															borderStyle = borderStyle && borderStyle.match( /^(\d+px)(?: \1 \1 \1)?$/ );
															value = borderStyle && parseInt( borderStyle[ 1 ], 10 );
															isNaN ( parseInt( value, 10 ) ) && ( value = element.getAttribute( 'border' ) );
															this.setValue( value );
														}
													},
													commit : function( type, element, internalCommit )
													{
														var value = parseInt( this.getValue(), 10 );
														if ( type == IMAGE || type == PREVIEW )
														{
															if ( !isNaN( value ) )
															{
																element.setStyle( 'border-width', CKEDITOR.tools.cssLength( value ) );
																element.setStyle( 'border-style', 'solid' );
															}
															else if ( !value && this.isChanged() )
															{
																element.removeStyle( 'border-width' );
																element.removeStyle( 'border-style' );
																element.removeStyle( 'border-color' );
															}

															if ( !internalCommit && type == IMAGE )
																element.removeAttribute( 'border' );
														}
														else if ( type == CLEANUP )
														{
															element.removeAttribute( 'border' );
															element.removeStyle( 'border-width' );
															element.removeStyle( 'border-style' );
															element.removeStyle( 'border-color' );
														}
													}
												},
												{
													type : 'text',
													id : 'txtHSpace',
													width: '60px',
													label : editor.lang.image.hSpace,
													'default' : '',
													onKeyUp : function()
													{
														updatePreview( this.getDialog() );
													},
													onChange : function()
													{
														commitInternally.call( this, 'advanced:txtdlgGenStyle' );
													},
													validate : CKEDITOR.dialog.validate.integer( editor.lang.image.validateHSpace ),
													setup : function( type, element )
													{
														if ( type == IMAGE )
														{
															var value,
																marginLeftPx,
																marginRightPx,
																marginLeftStyle = element.getStyle( 'margin-left' ),
																marginRightStyle = element.getStyle( 'margin-right' );

															marginLeftStyle = marginLeftStyle && marginLeftStyle.match( pxLengthRegex );
															marginRightStyle = marginRightStyle && marginRightStyle.match( pxLengthRegex );
															marginLeftPx = parseInt( marginLeftStyle, 10 );
															marginRightPx = parseInt( marginRightStyle, 10 );

															value = ( marginLeftPx == marginRightPx ) && marginLeftPx;
															isNaN( parseInt( value, 10 ) ) && ( value = element.getAttribute( 'hspace' ) );

															this.setValue( value );
														}
													},
													commit : function( type, element, internalCommit )
													{
														var value = parseInt( this.getValue(), 10 );
														if ( type == IMAGE || type == PREVIEW )
														{
															if ( !isNaN( value ) )
															{
																element.setStyle( 'margin-left', CKEDITOR.tools.cssLength( value ) );
																element.setStyle( 'margin-right', CKEDITOR.tools.cssLength( value ) );
															}
															else if ( !value && this.isChanged( ) )
															{
																element.removeStyle( 'margin-left' );
																element.removeStyle( 'margin-right' );
															}

															if ( !internalCommit && type == IMAGE )
																element.removeAttribute( 'hspace' );
														}
														else if ( type == CLEANUP )
														{
															element.removeAttribute( 'hspace' );
															element.removeStyle( 'margin-left' );
															element.removeStyle( 'margin-right' );
														}
													}
												},
												{
													type : 'text',
													id : 'txtVSpace',
													width : '60px',
													label : editor.lang.image.vSpace,
													'default' : '',
													onKeyUp : function()
													{
														updatePreview( this.getDialog() );
													},
													onChange : function()
													{
														commitInternally.call( this, 'advanced:txtdlgGenStyle' );
													},
													validate : CKEDITOR.dialog.validate.integer( editor.lang.image.validateVSpace ),
													setup : function( type, element )
													{
														if ( type == IMAGE )
														{
															var value,
																marginTopPx,
																marginBottomPx,
																marginTopStyle = element.getStyle( 'margin-top' ),
																marginBottomStyle = element.getStyle( 'margin-bottom' );

															marginTopStyle = marginTopStyle && marginTopStyle.match( pxLengthRegex );
															marginBottomStyle = marginBottomStyle && marginBottomStyle.match( pxLengthRegex );
															marginTopPx = parseInt( marginTopStyle, 10 );
															marginBottomPx = parseInt( marginBottomStyle, 10 );

															value = ( marginTopPx == marginBottomPx ) && marginTopPx;
															isNaN ( parseInt( value, 10 ) ) && ( value = element.getAttribute( 'vspace' ) );
															this.setValue( value );
														}
													},
													commit : function( type, element, internalCommit )
													{
														var value = parseInt( this.getValue(), 10 );
														if ( type == IMAGE || type == PREVIEW )
														{
															if ( !isNaN( value ) )
															{
																element.setStyle( 'margin-top', CKEDITOR.tools.cssLength( value ) );
																element.setStyle( 'margin-bottom', CKEDITOR.tools.cssLength( value ) );
															}
															else if ( !value && this.isChanged( ) )
															{
																element.removeStyle( 'margin-top' );
																element.removeStyle( 'margin-bottom' );
															}

															if ( !internalCommit && type == IMAGE )
																element.removeAttribute( 'vspace' );
														}
														else if ( type == CLEANUP )
														{
															element.removeAttribute( 'vspace' );
															element.removeStyle( 'margin-top' );
															element.removeStyle( 'margin-bottom' );
														}
													}
												},
												{
													id : 'cmbAlign',
													type : 'select',
													widths : [ '35%','65%' ],
													style : 'width:90px',
													label : editor.lang.common.align,
													'default' : 'center',
													items :
													[
														//[ editor.lang.common.notSet , ''],
														[ 'Giữa' , 'center'],
														[ editor.lang.common.alignLeft , 'left'],
														[ editor.lang.common.alignRight , 'right']
														// Backward compatible with v2 on setup when specified as attribute value,
														// while these values are no more available as select options.
														//	[ editor.lang.image.alignAbsBottom , 'absBottom'],
														//	[ editor.lang.image.alignAbsMiddle , 'absMiddle'],
														//  [ editor.lang.image.alignBaseline , 'baseline'],
														//  [ editor.lang.image.alignTextTop , 'text-top'],
														//  [ editor.lang.image.alignBottom , 'bottom'],
														//  [ editor.lang.image.alignMiddle , 'middle'],
														//  [ editor.lang.image.alignTop , 'top']
													],
													onChange : function()
													{
														updatePreview( this.getDialog() );
														commitInternally.call( this, 'advanced:txtdlgGenStyle' );
													},
													setup : function( type, element )
													{
														if ( type == IMAGE )
														{
															var value = element.getStyle( 'float' );
															switch( value )
															{
																// Ignore those unrelated values.
																case 'inherit':
																case 'none':
																	value = '';
															}

															!value && ( value = ( element.getAttribute( 'align' ) || '' ).toLowerCase() );
															this.setValue( value );
														}
													},
													commit : function( type, element, internalCommit )
													{
														var value = this.getValue();
														if ( type == IMAGE || type == PREVIEW )
														{
															if ( value )
																element.setStyle( 'float', value );
															else
																element.removeStyle( 'float' );

															if ( !internalCommit && type == IMAGE )
															{
																value = ( element.getAttribute( 'align' ) || '' ).toLowerCase();
																switch( value )
																{
																	// we should remove it only if it matches "left" or "right",
																	// otherwise leave it intact.
																	case 'left':
																	case 'right':
																		element.removeAttribute( 'align' );
																}
															}
														}
														else if ( type == CLEANUP )
															element.removeStyle( 'float' );

													}
												}
											]
										}
									]
								},
								{
									type : 'vbox',
									height : '350px',
									children :
									[
										{
											type : 'html',
											id : 'htmlPreview',
											style : 'width:95%;',
											html : '<div>' + CKEDITOR.tools.htmlEncode( editor.lang.common.preview ) +'<br>'+
											'<div id="' + imagePreviewLoaderId + '" class="ImagePreviewLoader" style="display:none"><div class="loading">&nbsp;</div></div>'+
											'<div id="' + imagePreviewBoxId + '" class="ImagePreviewBox"><table><tr><td>'+
											'<a href="javascript:void(0)" target="_blank" onclick="return false;" id="' + previewLinkId + '">'+
											'<img id="' + previewImageId + '" alt="" /></a>' +
											'</td></tr></table></div></div>'
										}
									]
								}
							]
						}
					]
				},
				{
					id : 'divSearchImage',
					label : 'Tìm ảnh',
					elements :
					[
						/*{
							type : 'hbox',
							widths : [ '70px', '50%', '20px', '50%', '20px'],
							children :
							[
								{
									id : 'scope',
									type : 'select',
									label : 'Phạm vi',
									'default' : 0,
									items :
									[
										[ 'Của tôi', 0],
										[ 'Tất cả', 1 ],
									],
									onChange : function()
									{
										get_image_list(1);
									}
								},
								{
									id : 'from_date',
									'class': 'calendar',
									type : 'text',
									label : 'Từ ngày',
									'default' : getSDate()
								}
								,
								{
									type: 'html',
									onClick: function() {
										showCalendar('from_date');
									},
									style : 'display:inline-block;margin-top:13px;',
									html: '<img style="display:inline-block;margin-top:14px;cursor: pointer; border: none;" height="17px" src="/core/ckeditor/image/cal.gif">'
								}
								,
								{
									id : 'to_date',
									type : 'text',
									label : 'Đến Ngày',
									'default' : getEDate()
								}
								,
								{
									type: 'html',
									style : 'display:inline-block;margin-top:13px;',
									onClick: function() {
										showCalendar('to_date');
									},
									html: '<img style="display:inline-block;margin-top:14px;cursor: pointer;border: none;" height="17px" src="/core/ckeditor/image/cal.gif">'
								}
							]
						},*/
						{
							type : 'hbox',
							widths : [ '150px', '30%'],
							children :
							[
								
								{
									id : 'keyword',
									type : 'text',
									label : 'Từ khoá',
									'default' : ''
								},
								{
									type : 'button',
									label : 'Tìm kiếm',
									style : 'display:inline-block;margin-top:13px;',
									align : 'center',
									onClick: function() {
										get_image_list(1);
									}
								}
							]
						},
						{
							type : 'vbox',
							height : '250px',
							children :
							[
								{
									type : 'html',
									id : 'ImageListContainer',
									style : 'width:95%;',
									html: '<input type="hidden" name="image_list_page" id="image_list_page" value="0" />'+
									'<div id="image_list" style="text-align: center;overflow: auto;height: 260px;"><table width="100%"><tr><td style="text-align: center; font-weight: bold;">Nhấn nút tìm kiểm để tải dữ liệu</td></tr></table></div>'
								}
							]
						}
					]
				},
				{
					id : 'Upload',
					label : editor.lang.image.upload,
					elements :
					[
						{
							type : 'html',
							html: '<form id="frmUpload" name="frmUpload" method="post" target="UploadWindow" enctype="multipart/form-data" action="/index.php?mod=admin&amod=images&atask=images&task=upload&ajax=fck">' +
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
								'</form>' +
								'<iframe name="UploadWindow" style="display: none" src="/core/ckeditor/blank.html"></iframe>'
						}
					]
				}
			]
		};
	};
	
	CKEDITOR.dialog.add( 'image', function( editor )
		{
			return imageDialog( editor, 'image' );
		});

	CKEDITOR.dialog.add( 'imagebutton', function( editor )
		{
			return imageDialog( editor, 'imagebutton' );
		});
})();