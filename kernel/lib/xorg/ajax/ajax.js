(function($) {
	
	var baseTitle = $(document).prop("title");
	var href;
	var target;
	var title;
	var rel;
	var fileContent;
	var prompt = 0;
	var init = true;
	var state = window.history.pushState !== undefined;
	
	$(document).on("click", "a", function(event){
//alert("a href lik click before preventDefault");
		event.preventDefault();
//alert("a href lik click after preventDefault");

		href = $(this).prop("href") ? $(this).prop("href") : "";
		target = $(this).prop("target") ? "#" + $(this).prop("target") : "#content";
//alert (target + " test1");
//alert ($(this).offsetParent());
		title = $(this).prop("title") ? $(this).prop("title") : "";
		rel = $(this).prop("rel") ? $(this).prop("rel") : "";
		
		if (target!="#nomove"){
//alert ("oh yes");
			if($(target).css("display") == "none"){
				$('#accessories').slideToggle('slow'); 
				/*$(target).fadeIn("slow");*/
//alert ("slidetoggle");
			}
		}else{
			target="#temp";
//target= $(this).offsetParent();
		}
		
		$(document).prop("title", baseTitle + " " +title);
		
		var str="http://" +$(location).prop("host");
		var href=href.replace(str, "");
//alert (str1);
//		if(href.replace(/blue/g, "red"));
		
		if(href.match("http://") || href.match("https://") || href.match("ymsgr:") || href.match("skype:") || href.match("mailto:")){
//alert("1-->"+href);
			window.location = href;
		}else{
//alert("2");
			if(href != "" && !href.match("javascript:void") && rel != "download"){
//alert("21");
//alert("href: http://" + $(location).prop("host") + href + "\nlocation: " + $(location).prop("href"));
				if($(location).prop("href") == "http://" + $(location).prop("host") + href){
//alert("211");
					$.address.value(href + "/");
				}else{
//alert("212");
					$.address.value(href);
				}
			}
		}
	});

	$.address.state('').init(function(event) {
    }).change(function(event) {
		$.address.state().replace(/^\/$/, "") + event.value;
		$(target).farajax("loader", event.value, "crawl=" + event.value);
//alert(target);
	        if (target == "#content" || target == "#add"){
			    $.scrollTo("#menuContainer", {duration:3000});
	        }
	        else{
	            $.scrollTo(target, {duration:3000});
	        }
	});
	
    $.fn.extend({
        "farajax": function(options,arg, data){
//alert("0.25-options->"+options+" * arg->"+arg+" * data->"+data);
            if (options && typeof(options) == "object") {
                options = $.extend({}, $.fJax.defaults, options);
//alert("0.5");
            }

            this.each(function() {
//alert("1-this->"+this+" * options->"+options+" * arg->"+arg+" * data->"+data);             
                new $.fJax(this, options, arg, data);
            });
//            return "test";
            return
        }
    });

    $.fJax = function(elem, options, arg, data) {

    	var target = "#" + elem.id;
    	
//alert("2-target->"+target+" * elem->"+elem+" * options->"+options+" * arg->"+arg+" * data->"+data);
        if (options && typeof(options) == "string") {
           if (options == "loader") {
               loader(arg, data);               
           }
           return
        }

        function loader(href, data){
        	var LoadMsg = "Please Wait ...";
//alert("3-href->"+href+" * data->"+data);       	
    			$.ajax({
    				type: "POST",
    				url: href,
    				data: data,
    				dataType: "html",
    				timeout: 30000,
    				cache: false,
    				tryCount: 0,
    				retryLimit: 3,
    				
    				beforeSend:function(){
    				    $(target).showLoading();
    				},
    				
    				success: function(d,s){
//alert("4-d->"+d+" * s->"+s);
    					fileContent = d;
						if (d.match("ENO: #") || d.match("SNO: #") || d.match("WNO: #") || d.match("INO: #") || d.match("UNO: #") || d.match("POPUP: #")) {
							prompt = 1;
							$(this).remove();
							$("#modalWindow").faraModal("showModal", "modalWindow");
							$("#modalWindow").faraModal("modal", "modalWindow");
							document.getElementById("modalWindow").innerHTML = d;
		/*** digiSeo code
							$("#modalMask").fadeIn("slow", function (){
								$("#modalWindow").fadeIn("slow");
								$("#modalContent").html(d);
								disable_scroll();
							});
		*/
						}else{
							$(this).remove();
							$(target).html(d);
						}
    				},
    				
    				complete: function(){
//alert("5");
    					var commands = fileContent.match(/commands value="(.+?)"/);
						if(commands){
	    					if (typeof commands !== "undefined") {
								setTimeout(commands[1], 100);
							}
						}
//						alert(prompt);
						if (editor && prompt == 0) { removeEditor(); }
    					$(target).hideLoading();
    				},
    						
    				error: function(o,s,e){
//alert("6-o->"+o.id+"*s->"+s+"*e->"+e);
    					if (s == "timeout") {
    			            this.tryCount++;
    			            if (this.tryCount <= this.retryLimit) {
    			                //try again
    			            	$(target).html("Your internet speed is very low<br>Retry number:" + this.tryCount); 
    			                $.ajax(this);
    			                return
    			            }            
    			            return
    			        }
    			        if (o.status == 500) {
    			        	$(target).html("Error number " + o.status + ". Internal server error, please contact to system administrator.");
    			        } else if(o.status == 404){	
//alert("7");    			        	
    			        	$(target).html("Error number " + o.status + ". Cant find, please contact to system administrator.");
    			        }else{
    			        	$(target).html("Error number " + o.status + ", please contact to system administrator.");
    			        }
    				}
    			});
        }
    };

})(jQuery);