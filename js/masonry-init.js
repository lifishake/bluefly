
//Masonry init
jQuery(function($) {
	var $container = $('.grid-layout');
	$container.imagesLoaded( function() {
		$container.masonry({
			itemSelector: '.hentry',
			isOriginLeft: true,
			isFitWidth: true,
	        isAnimated: false,
			percentPosition: true,
			animationOptions: {
				duration: 300,
				easing: 'linear',
			}
	    });
	});
});