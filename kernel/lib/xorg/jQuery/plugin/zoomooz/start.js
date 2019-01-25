function zoomoozStart() {
	$(".zoomTarget").click(function(evt) {
		evt.stopPropagation();
		$(this).zoomTo({
			targetsize : 0.9,
			debug : false
		});
	});
	$(document).click(function(evt) {
		evt.stopPropagation();
		$("body").zoomTo({
			targetsize : 1.0,
			debug : false
		});
	});
	$("body").zoomTo({
		targetsize : 1.0,
		debug : false
	});
}