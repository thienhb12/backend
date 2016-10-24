/*
Copyright (c) 2003-2011, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.plugins.add( 'Media',
{
	requires : [ 'dialog' ],

	init : function( editor )
	{
		editor.config.media_path = editor.config.media_path || ( this.path + 'images/' );
		editor.addCommand( 'Media', new CKEDITOR.dialogCommand( 'Media' ) );
		editor.ui.addButton( 'Media',
			{
				label : 'Chèn Media',
				command : 'Media',
				icon : this.path + 'logo.gif'
			});
		CKEDITOR.dialog.add( 'Media', this.path + 'dialogs/media.js' );
	}
} );