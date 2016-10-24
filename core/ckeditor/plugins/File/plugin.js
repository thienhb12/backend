/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'File',
{
	requires : [ 'dialog' ],

	init : function( editor )
	{
		editor.config.file_path = editor.config.file_path || ( this.path + 'images/' );
		editor.addCommand( 'File', new CKEDITOR.dialogCommand( 'File' ) );
		editor.ui.addButton( 'File',
			{
				label : 'Chèn tập tin',
				command : 'File',
				icon : this.path + 'logo.gif'
			});
		CKEDITOR.dialog.add( 'File', this.path + 'dialogs/file.js' );
	}
} );