/* SimpleTreeMenu */

(function($) {
	
	var methods = {
			
		init: function() {
			
	    	return this.each(function() {
	    		var $this = $(this);
				if ($this.hasClass("simpleTreeMenu") === false) {
					$this.hide();
					$(this).addClass("simpleTreeMenu");
					$this.children("li").each(function() {
						methods.buildNode($(this));
					});	
					$(this).show();	
				}
	    	});		
		},
		
		buildNode: function($li) {
			if ($li.children("ul").length > 0) {
				$li.children("ul").hide();
				$li.addClass("Node").click(function(event) {
					var $t = $(this);
					if ($t.hasClass("expanded")) {
						$t.removeClass("expanded");
						$t.children("ul").hide();
					} else {
						$t.addClass("expanded");
						$t.children("ul").show();
					}
					event.stopPropagation();
				});  
                if ($li.children("ul").children("li").length == 0) {
                    $li.addClass("EmptyNode");
                }
				$li.children("ul").children("li").each(function() {
					methods.buildNode($(this));
				});
			} else {
				$li.addClass("Leaf").click(function(event) {
					event.stopPropagation();
				});
				return;
			}		
		},
		
		expandToNode: function($li) {
			if ($li.parent().hasClass("simpleTreeMenu")) {
				if (!$li.hasClass("expanded")) {
					$li.addClass("expanded");
					$li.children("ul").show();
				}
			}
			$li.parents("li", "ul.simpleTreeMenu").each(function() {
				var $t = $(this);
				if (!$t.hasClass("expanded")) {
					$t.addClass("expanded");
					$t.children("ul").show();
				}
			});
		},
		
		expandAll: function() {
			$(this).find("li.Node").each(function() {
				$t = $(this);
				if (!$t.hasClass("expanded")) {
					$t.addClass("expanded");
					$t.children("ul").show();
				}
			});	
		},
		
		closeAll: function() {
			$("ul", $(this)).hide();
			var $li = $("li.Node");
			if ($li.hasClass("expanded")) {
				$li.removeClass("expanded");
			}
		}		
		
	};
//alert ('method');
	
	$.fn.simpleTreeMenu = function(method) {
	    if (methods[method]) {
			return methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
	    } else if (typeof method === 'object' || !method) {
			return methods.init.apply(this, arguments);
	    } else {
			$.error('Method ' +  method + ' does not exist on jQuery.simpleTreeMenu');
	    }    	
	};
	
})(jQuery);


			
			
	