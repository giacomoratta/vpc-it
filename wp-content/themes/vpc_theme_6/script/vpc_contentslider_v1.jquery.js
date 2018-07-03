(function($) {
    $.fn.vpc_contentslider = function(options) {

		// Elemento della slideshow
		$this = $(this);
		$this_w = $this.contents().filter('.wrapper');
		
		// Pulsanti
		$nav_prev = $this.contents().filter('.nav-prev');
		$nav_next = $this.contents().filter('.nav-next');
		
		var config = {
        	'interval': 3,
			'fade_duration': 'slow',
			'textshadow_ie8':'1px 1px 0px #000000',
			'textshadow_ie9':'1px 1px 0px #000000',
			
			'_content_mouseover': 0,
			'_nav_pressed': 0,
			'_nav_opacity_time': 400
        };
		if (options) $.extend(config, options);		
		
		/* IE compatibility */
		var m = window.Modernizr;
		if(!m.textshadow)
		{
			if($('html').hasClass('ie9')) $this.find('.text').textshadow(config.textshadow_ie9);
			else $this.find('.text').textshadow(config.textshadow_ie8);
		}
		
		// parte con opacity=1 nonostante nei CSS sia 0
		$nav_prev.css({opacity:"0"}); $nav_next.css({opacity:"0"});
		
		
		
		
		
		// Mouse sullo slider: si deve bloccare tutto
		$this
			.mouseenter(function(){ config._content_mouseover=5; })
			.mouseleave(function(){ config._content_mouseover=0; });


		// Nasconde tutti gli elementi di indice 0 della classe .card
		$this_w.contents().filter('.card:gt(0)').hide();
		//$this.find('.text').textshadow('0px 0px 8px #000000');

		
		// Associa le funzioni ai pulsanti (e le animazioni)
		bind_click();
		$nav_prev
			.mouseenter(function(){ $nav_prev.animate({opacity:"0.5"}, config._nav_opacity_time); })
			.mouseleave(function(){ $nav_prev.animate({opacity:"0"}, config._nav_opacity_time); });
		$nav_next
			.mouseenter(function(){ $nav_next.animate({opacity:"0.5"}, config._nav_opacity_time); })
			.mouseleave(function(){ $nav_next.animate({opacity:"0"}, config._nav_opacity_time); });
		
		
		function bind_click()
		{
			$nav_prev.bind("click",function(){ config._nav_pressed=1; prev(); });
			$nav_next.bind("click",function(){ config._nav_pressed=1; next(); });
		}
		function unbind_click() { $nav_next.unbind("click"); $nav_prev.unbind("click"); }
		
		function next()
		{
			
			unbind_click();
			$first = $this_w.contents().filter('.card:first');
			$next = $first.next('.card');
			
			$first.appendTo($this_w);
      		$first.fadeOut();
			$next.fadeIn(config.fade_duration,bind_click);
		}
		
		function prev()
		{
			unbind_click();
			$first = $this_w.contents().filter('.card:first');
			$last = $first.nextAll('.card:last');
			
			$last.prependTo($this_w);
      		$first.fadeOut();
			$last.fadeIn(config.fade_duration,bind_click);
		}

		
		// Rotazione schede
		if(parseInt($this_w.contents().filter('.card').length)>1)
		{
			setInterval(function()
			{
				if(config._content_mouseover>0 || config._nav_pressed==1)
				{ /*config._content_mouseover--;*/ config._nav_pressed=0; return; }
				next();	
			},config.interval*1000);
		}
	
		
}})(jQuery);




/*

   USARE IL PLUGIN
--------------------------------------------------------------------------------


1) STRUTTURA HTML

<div id="MainMenuSlider">
	<div class="wrapper">
		<div class="card" style="background-image:url('img/6.jpg');">
        	<div class="text">
            	<div class="title">...</div>
                <div class="summary">...</div>
            </div>
        </div>
		<div class="card" style="background-image:url('img/5.jpg');">
        	<div class="text">
            	<div class="title">...</div>
                <div class="summary">...</div>
            </div>
        </div>
    </div><!-- end .wrapper -->
    <div class="nav-prev"></div>
    <div class="nav-next"></div>
</div>


2) Includere MODERNIZR per rilevazione "textshadow"


3) Includere i file .css e .js di https://github.com/heygrady/textshadow
   (libreria polyfill per text-shadow in IE9 e precedenti).


4) Sostituire il tag <html> con:
<!--[if lte IE 7 ]> <html class="ie7"> <![endif]-->
<!--[if IE 8 ]>    <html class="ie8"> <![endif]-->
<!--[if IE 9 ]>    <html class="ie9"> <![endif]-->
<!--[if gt IE 9 ]><!--> <html class="ie"> <!--<![endif]-->
<!--[ !(IE) ]><!--> <html class="not-ie"> <!--<![endif]-->

*/


