/*
Copyright (c) 2003-2010, CKSource - Frederico Knabben. All rights reserved.
For licensing, see LICENSE.html or http://ckeditor.com/license
*/

CKEDITOR.editorConfig = function( config )
{
	// Define changes to default configuration here. For example:
	config.language = 'fa';
	// config.uiColor = '#AADC6E';
	config.toolbar = 'Full';

//	config.toolbar_Full =
//		[
//		    ['Source','-','Save','NewPage','Preview','-','Templates'],
//		    ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Print', 'SpellChecker', 'Scayt'],
//		    ['Undo','Redo','-','Find','Replace','-','SelectAll','RemoveFormat'],
//		    ['Form', 'Checkbox', 'Radio', 'TextField', 'Textarea', 'Select', 'Button', 'ImageButton', 'HiddenField'],
//		    ['BidiLtr', 'BidiRtl'],
//		    '/',
//		    ['Bold','Italic','Underline','Strike','-','Subscript','Superscript'],
//		    ['NumberedList','BulletedList','-','Outdent','Indent','Blockquote','CreateDiv'],
//		    ['JustifyLeft','JustifyCenter','JustifyRight','JustifyBlock'],
//		    ['Link','Unlink','Anchor'],
//		    ['Image','Flash','Table','HorizontalRule','Smiley','SpecialChar','PageBreak'],
//		    '/',
//		    ['Styles','Format','Font','FontSize'],
//		    ['TextColor','BGColor'],
//		    ['Maximize', 'ShowBlocks','-','About']
//		];

	
	config.toolbar_Full =
		[
		 	['BidiRtl','BidiLtr'],
		    ['Bold','Italic','Underline'],
		    ['NumberedList','BulletedList','-','Indent','Outdent'],
		    ['JustifyRight','JustifyCenter','JustifyLeft','JustifyBlock'],
		    ['Link','Unlink','Image','Flash','Table'],
		    '/',
		    ['Styles','Format','Font','FontSize'],
		    ['TextColor','BGColor'],
		    ['Source','Preview']
		];

	config.toolbarStartupExpanded = false;
};
CKFinder.setupCKEditor( null, '/kernel/lib/xorg/finder/' );

