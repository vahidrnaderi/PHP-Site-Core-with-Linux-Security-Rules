function startShare() {
	$('.twitter').sharrre({
		share: {
			twitter: true
		},
		enableHover: false,
		enableTracking: true,
		buttons: { twitter: {via: '_JulienH'}},
		click: function(api, options){
		    api.simulateClick();
		    api.openPopup('twitter');
		}
	});
	$('.facebook').sharrre({
		share: {
			facebook: true
		},
		enableHover: false,
		enableTracking: true,
		urlCurl : 'kernel/lib/xorg/jQuery/plugin/sharrre/sharrre.php',
		click: function(api, options){
		    api.simulateClick();
		    api.openPopup('facebook');
		}
	});
	$('.googleplus').sharrre({
		share: {
			googlePlus: true
		},
		enableHover: false,
		enableTracking: true,
		urlCurl : 'kernel/lib/xorg/jQuery/plugin/sharrre/sharrre.php',
		click: function(api, options){
		    api.simulateClick();
		    api.openPopup('googlePlus');
		}
	});
	$('.stumbleupon').sharrre({
		share: {
			stumbleupon: true
		},
		enableHover: false,
		enableTracking: true,
		urlCurl : 'kernel/lib/xorg/jQuery/plugin/sharrre/sharrre.php',
		click: function(api, options){
		    api.simulateClick();
		    api.openPopup('stumbleupon');
		}
	});
	$('.delicious').sharrre({
		share: {
			delicious: true
		},
		enableHover: false,
		enableTracking: true,
		urlCurl : 'kernel/lib/xorg/jQuery/plugin/sharrre/sharrre.php',
		click: function(api, options){
		    api.simulateClick();
		    api.openPopup('delicious');
		}
	});
	$('.linkedin').sharrre({
		share: {
			linkedin: true
		},
		enableHover: false,
		enableTracking: true,
		urlCurl : 'kernel/lib/xorg/jQuery/plugin/sharrre/sharrre.php',
		click: function(api, options){
		    api.simulateClick();
		    api.openPopup('linkedin');
		}
	});
	$('.pinterest').sharrre({
		share: {
			pinterest: true
		},
		enableHover: false,
		enableTracking: true,
		urlCurl : 'kernel/lib/xorg/jQuery/plugin/sharrre/sharrre.php',
		click: function(api, options){
		    api.simulateClick();
		    api.openPopup('pinterest');
		}
	});
}