//Parallax
jQuery(function($) {
	$(".site-header").parallax("50%", 0.3);
});

//Open social links in a new tab
jQuery(function($) {
     $( '.social-navigation li a' ).attr( 'target','_blank' );
});

//Toggle sidebar
jQuery(function($) {
	$('.sidebar-toggle').click(function() {
		$('.widget-area').toggleClass('widget-area-visible');
		$('.sidebar-toggle').toggleClass('sidebar-toggled');
		$('.sidebar-toggle').find('i').toggleClass('fa-bars fa-times');
	});
	$('.sidebar-toggle-inside').click(function() {
		$('.widget-area').toggleClass('widget-area-visible');
	});	
});

//Menu
jQuery(function($) {
	$('.main-navigation .menu').slicknav({
		label: '',
		duration: 500,
		prependTo:'.sidebar-nav',
		closedSymbol: '&#43;',
		openedSymbol: '&#45;',
		allowParentLinks: true	
	});
});

jQuery(document).ready(function($) { 

	/**
	* Infinite scroll
	*/
	var $container = $('#ob-grid');
	$container.infinitescroll({
	  navSelector  : '.posts-navigation',    // selector for the paged navigation 
	  nextSelector : '.nav-previous a',  // selector for the NEXT link (to page 2)
	  itemSelector : '.post',     // selector for all items you'll retrieve
	  loading: {
		  finishedMsg: 'No more pages to load.',
		  img: '../images/ajax-loader.gif'
		}
	  },
	  // trigger Masonry as a callback
	  function( newElements ) {
		var $newElems = $( newElements );
		$container.masonry( 'appended', $newElems );
	  }
	);
	/**
	* Detect touch device
	*/
	if( is_touch_device() == false ){
		$('body').addClass( 'not-touch-device' );
	}
	
	
	/**
	* Civil Footnotes Support
	* Slide the window instead of jumping it
	*/
	$('#main').on( 'click', 'a[rel="footnote"], a.backlink', function(e){
		e.preventDefault();
		var target_id = $(this).attr('href');
		var respond_offset = $(target_id).offset();

		$('html, body').animate({
			scrollTop : respond_offset.top - 90
		});
	});	

	/**
	* Toggle expanded UI
	*/
	$('.toggle-button').click(function(e){
		e.preventDefault();

		// Get target ID
		var target_id 		= $(this).attr( 'data-target-id' );
		var sliding_content = $('#'+target_id).find('.sliding-content');
		var direction		= sliding_content.attr( 'data-direction' );

		// Display target ID
		if( $('#'+target_id).is(':visible') ){
			$('#'+target_id).fadeOut();

			if( 'left' == direction ){
				if( $('body').is( '.rtl' ) ){
					sliding_content.animate({ 'right' : '-100%' } );
				} else {
					sliding_content.animate({ 'left' : '-100%' } );
				}
			}
		} else {
			$('#'+target_id).fadeIn();

			if( 'left' == direction ){
				if( $('body').is( '.rtl' ) ){
					sliding_content.animate({ 'right' : '0' } );
				} else {
					sliding_content.animate({ 'left' : '0' } );
				}
			}
		}

		// Mark body
		$('body').toggleClass( target_id + '-expanded' );
	});		
});

/**
* Detect touch device
*/
function is_touch_device() {
	return 'ontouchstart' in window // works on most browsers 
		|| 'onmsgesturechange' in window; // works on ie10
};