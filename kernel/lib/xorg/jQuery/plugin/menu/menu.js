$("ul.subnav").parent().append("<span></span>");
$("ul.topnav li span").hover(function() {
	$(this).parent().find("ul.subnav").slideDown('fast').show();
	$(this).parent().hover(function() {
	}, function() {
		$(this).parent().find("ul.subnav").slideUp('slow');
	});
}).hover(function() {
	$(this).addClass("subhover");
}, function() {
	$(this).removeClass("subhover");
});