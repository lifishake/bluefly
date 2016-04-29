

jQuery(function($) {
	
	//Open social links in a new tab
	$( '.social-navigation li a' ).attr( 'target','_blank' );
	
    //home
    $('.floathome').click(function() {
        var my_host = "http://"+window.location.host+"/";
        window.location.replace(my_host);
    });
	//Toggle sidebar
	$('.sidebar-toggle').click(function() {
		$('.widget-area').toggleClass('widget-area-visible');
        $comm = jQuery('.floatcomment');
        if ( $('.widget-area').hasClass('widget-area-visible') ) {
            jQuery('.floattop').css('display', 'none');
            jQuery('.floathome').css('display', 'none');
            if ( $comm )
                $comm.css('display', 'none');
        }
        else {
            jQuery('.floattop').css('display', '');
            jQuery('.floathome').css('display', '');
            if ( $comm )
                $comm.css('display', '');
        }
		$('.sidebar-toggle').toggleClass('sidebar-toggled');
		$('.sidebar-toggle').find('i').toggleClass('fa-bars fa-times');
	});
	$('.sidebar-toggle-inside').click(function() {
		$('.widget-area').toggleClass('widget-area-visible');
	});	

	//Menu
	$('.main-navigation .menu').slicknav({
		label: '',
		duration: 500,
		prependTo:'.sidebar-nav',
		closedSymbol: '&#43;',
		openedSymbol: '&#45;',
		allowParentLinks: true	
	});
	
	/**
	* 无限滚动
	*/
	var $js_path = get_js_URL() ;
	var $img_load = $js_path + '/../images/loading.gif' ;
	var $container = $('#ob-grid');
	$container.infinitescroll({
	  navSelector  : '.posts-navigation',    
	  nextSelector : '.nav-previous a',  
	  itemSelector : '.post',     
	  loading: {
		  finishedMsg: '<em>到底了。</em>',
		  img: $img_load,
		  msgText:'<em>拼命加载中…</em>'
		}
	  },
	  //Masonry的回调函数
	  function( newElements ) {
		var $newElems = $( newElements );
		$('div.lazy').lazyload({
		  effect : "fadeIn"
		});
		$container.masonry( 'appended', $newElems );
	  }
	);
	
	//backtotop
	function g($) {
        return ($.which > 0 || "mousedown" === $.type || "mousewheel" === $.type) && f.stop().off("scroll mousedown DOMMouseScroll mousewheel keyup", g);
    }
    //Stop the viewport animation if user interaction is detected
    var f = $("html, body");
	var resobj = document.getElementById("respond"); 
    var pos = 0;
	if (resobj){ 
		pos = $("#respond").offset().top; 
		}
	$(".floattop").on("click touchstart touchend", function ($) {
        f.on("scroll mousedown DOMMouseScroll mousewheel keyup", g);
        f.animate({
            scrollTop: 0
        }, 1e3, function () {
            f.stop().off("scroll mousedown DOMMouseScroll mousewheel keyup", g);
        });
        $.preventDefault();
    });
    $(".floatcomment").on("click touchstart touchend", function ($) {
        f.on("scroll mousedown DOMMouseScroll mousewheel keyup", g);
        f.animate({
            scrollTop: pos
        }, 1e3, function () {
            f.stop().off("scroll mousedown DOMMouseScroll mousewheel keyup", g);
        });
        $.preventDefault();
    });

});

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
	
jQuery(document).ready(function($) { 

	$('div.lazy').lazyload({
      effect : "fadeIn"
	});
	$('#ob-grid').masonry();
	$("img.lazy").lazyload({
      effect : "fadeIn"
	});
	/**
	* 判断是否是触屏设备
	*/
	if( is_touch_device() == false ){
		$('body').addClass( 'not-touch-device' );
	}
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
/*
skip-link-focus-fix
*/
( function() {
	var is_webkit = navigator.userAgent.toLowerCase().indexOf( 'webkit' ) > -1,
	    is_opera  = navigator.userAgent.toLowerCase().indexOf( 'opera' )  > -1,
	    is_ie     = navigator.userAgent.toLowerCase().indexOf( 'msie' )   > -1;

	if ( ( is_webkit || is_opera || is_ie ) && document.getElementById && window.addEventListener ) {
		window.addEventListener( 'hashchange', function() {
			var id = location.hash.substring( 1 ),
				element;

			if ( ! ( /^[A-z0-9_-]+$/.test( id ) ) ) {
				return;
			}

			element = document.getElementById( id );

			if ( element ) {
				if ( ! ( /^(?:a|select|input|button|textarea)$/i.test( element.tagName ) ) ) {
					element.tabIndex = -1;
				}

				element.focus();
			}
		}, false );
	}
})();