function flexStarter(fileName) {
	var fp = new FlexPaperViewer('kernel/lib/xorg/jQuery/plugin/flexPaper/FlexPaperViewer', 'viewerPlaceHolder', {
		config : {
			SwfFile : escape(fileName),
			Scale : 0.6,
			ZoomTransition : 'easeOut',
			ZoomTime : 0.5,
			ZoomInterval : 0.2,
			FitPageOnLoad : true,
			FitWidthOnLoad : false,
			FullScreenAsMaxWindow : false,
			ProgressiveLoading : false,
			MinZoomSize : 0.2,
			MaxZoomSize : 5,
			SearchMatchAll : false,
			InitViewMode : 'Portrait',
			PrintPaperAsBitmap : false,

			ViewModeToolsVisible : true,
			ZoomToolsVisible : true,
			NavToolsVisible : true,
			CursorToolsVisible : true,
			SearchToolsVisible : true,

			localeChain : 'en_US'
		}
	});
}