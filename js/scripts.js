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

/*jQuery(function($) {
	var $container = $('.grid-layout');
	$container.imagesLoaded( function() {
		$container.masonry({
			itemSelector: '.hentry',
			isOriginLeft: true,
			isFitWidth: true,
	        isAnimated: false,
			columnWidth: '.grid-sizer',
			percentPosition: true
			animationOptions: {
				duration: 300,
				easing: 'linear',
			}
	    });
	});
});*/

jQuery(function($) {
	/**
	* Infinite scroll
	*/
	var $js_path = get_js_URL() ;
	var $img_load = $js_path + '/../images/loading.gif' ;
	var $container = $('#ob-grid');
	$container.infinitescroll({
	  navSelector  : '.posts-navigation',    // selector for the paged navigation 
	  nextSelector : '.nav-previous a',  // selector for the NEXT link (to page 2)
	  itemSelector : '.post',     // selector for all items you'll retrieve
	  loading: {
		  finishedMsg: 'No more pages to load.',
		  img: $img_load
		}
	  },
	  //Masonry的回调函数
	  function( newElements ) {
		var $newElems = $( newElements );
		$container.masonry( 'appended', $newElems );
		//下面这段是延时加载(lazyload)的代码。我不会优化只能全粘上来。不使用unveil-ui.min.js的完全应该注释掉!!
		(function(e){e.fn.unveil=function(t,n){function f(){var t=u.filter(function(){var t=e(this),n=r.scrollTop(),s=n+r.height(),o=t.offset().top,u=o+t.height();return u>=n-i&&o<=s+i});a=t.trigger("unveil");u=u.not(a)}var r=e(window),i=t||0,s=window.devicePixelRatio>1,o=s?"data-src-retina":"data-src",u=this,a;this.one("unveil",function(){var e=this.getAttribute(o);e=e||this.getAttribute("data-src");if(e){this.setAttribute("src",e);if(typeof n==="function")n.call(this)}});r.scroll(f);r.resize(f);f();return this}})(window.jQuery||window.Zepto);jQuery(document).ready(function(e){if(typeof t==="undefined"){var t=0}e('img[data-unveil="true"]').unveil(t,function(){e(this).load(function(){this.style.opacity=1})})})
	  }
	);
});
	
jQuery(document).ready(function($) { 

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

function get_js_URL() {
	var result = "",m;
	try{
		a.b.c();
	}catch(e){
		if(e.fileName){//firefox
			result = e.fileName;
		}else if(e.sourceURL){//safari
			result = e.sourceURL;
		}else if(e.stacktrace){//opera9
			m = e.stacktrace.match(/\(\) in\s+(.*?\:\/\/\S+)/m);
			if (m && m[1])
				result =  m[1]
		}else if(e.stack){//chrome 4+
			m= e.stack.match(/\(([^)]+)\)/)
			if (m && m[1])
				result = m[1]
		}
	}
	if(!result){//IE与chrome4- opera10+
		var scripts = document.getElementsByTagName("script");
		var reg = /dom([.-]\d)*\.js(\W|$)/i,src
		for(var i=0, el; el = scripts[i++];){
			src = !!document.querySelector ? el.src:
			el.getAttribute("src",4);
			if(src && reg.test(src)){
				result = src
				break;
			}
		}
	}
	return result.substr( 0, result.lastIndexOf('/'));
	};
