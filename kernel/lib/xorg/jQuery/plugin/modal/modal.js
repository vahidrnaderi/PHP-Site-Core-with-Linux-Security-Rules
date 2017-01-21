(function($) {
	
	$(window).scroll(function () { 
		$('#modalWindow').faraModal('modal', 'modalWindow');	
	});
	
	$('#modalMask').click(function () { 
		$('#modalWindow').faraModal('closeModal', 'modalWindow');
	});
	
	$(window).resize(function(){
		$('#modalWindow').faraModal('modal', 'modalWindow');
	});
	

    $.fn.extend({
        faraModal: function(options,arg){
            if (options && typeof(options) == 'object') {
                options = $.extend({}, $.fModal.defaults, options);
            }

            this.each(function() {
                new $.fModal(this, options, arg);
            });
            return;
        }
    });

    $.fModal = function(elem, options, arg) {

    	var target = '#' + elem.id;
        if (options && typeof(options) == 'string') {
        	if (options == 'modal') {
                modal(arg);
            }
        	if (options == 'closeModal') {
        		closeModal(arg);
        	}
        	if (options == 'showModal') {
        		showModal(arg);
        	}
           return;
        }
        
        function modal(id){
        	var windowWidth = $(window).width();
        	var windowHeight = $(window).height();
        	var documentHeight = $(document).height();
        	
          	var modalWidth = $('#' + id).outerWidth();
    		var modalHeight = $('#' + id).outerHeight();

    		var xScroll, yScroll;
    		
    		$('#modalMask').css({'width': windowWidth, 'height': documentHeight});
    		
    	    if (self.pageYOffset) {
    	      yScroll = self.pageYOffset;
    	      xScroll = self.pageXOffset;
    	    } else if (document.documentElement && document.documentElement.scrollTop) {	 // Explorer 6 Strict
    	      yScroll = document.documentElement.scrollTop;
    	      xScroll = document.documentElement.scrollLeft;
    	    } else if (document.body) {// all other Explorers
    	      yScroll = document.body.scrollTop;
    	      xScroll = document.body.scrollLeft;
    	    }
    	    
    	    $('#' + id).css({'top': yScroll + (documentHeight/14), 'left': windowWidth/2-150});
        }
        
        function closeModal(id) {
        	$('#modalMask').fadeOut(500);
        	$('#' + id).fadeOut(500);
        }
        
        function showModal(id) {
        	$('#modalMask').css({'display': 'block', opacity: 0});
        	$('#modalMask').fadeTo(500, 0.8);
        	$('#' + id).fadeIn(500);
        }

    };

//    $.fModal.defaults = {
//       
//    };

})(jQuery);