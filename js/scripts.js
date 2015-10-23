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
		$container.masonry( 'appended', $newElems );
		//下面这段是延时加载(lazyload)的代码。我不会优化只能全粘上来。不使用unveil-ui.min.js的完全应该注释掉!!
		(function(e){e.fn.unveil=function(t,n){function f(){var t=u.filter(function(){var t=e(this),n=r.scrollTop(),s=n+r.height(),o=t.offset().top,u=o+t.height();return u>=n-i&&o<=s+i});a=t.trigger("unveil");u=u.not(a)}var r=e(window),i=t||0,s=window.devicePixelRatio>1,o=s?"data-src-retina":"data-src",u=this,a;this.one("unveil",function(){var e=this.getAttribute(o);e=e||this.getAttribute("data-src");if(e){this.setAttribute("src",e);if(typeof n==="function")n.call(this)}});r.scroll(f);r.resize(f);f();return this}})(window.jQuery||window.Zepto);jQuery(document).ready(function(e){if(typeof t==="undefined"){var t=0}e('img[data-unveil="true"]').unveil(t,function(){e(this).load(function(){this.style.opacity=1})})})
	  }
	);
});
	
jQuery(document).ready(function($) { 

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
	
