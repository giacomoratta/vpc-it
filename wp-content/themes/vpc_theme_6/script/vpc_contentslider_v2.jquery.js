/*

Content Slider v2 (plugin for jquery)

"Using Inheritance Patterns to Organize Large jQuery Applications"
https://alexsexton.com/blog/2010/02/using-inheritance-patterns-to-organize-large-jquery-applications/

*/



var VPC_ContentSlider_Object = {
		
	init: function(options, elem)
	{
		// Mix in the passed in options with the default options
		this.options = $.extend({},this.options,options);

		// Save the element reference, both as a jQuery reference and a normal reference
		this.elem  = elem;
		this.$this = $(elem);
		this.$wrapper = this.$this.contents().filter('.wrapper');
		this.$nav_prev = this.$this.contents().filter('.nav-prev');
		this.$nav_next = this.$this.contents().filter('.nav-next');
		
		// Build the dom initial structure
		this._build();

		// return this so we can chain/use the bridge with less code.
		return this;
	},
	
	
	
	options: {
		'interval': 3,
		'fade_duration': 'slow',
		'textshadow_ie8':'1px 1px 0px #000000',
		'textshadow_ie9':'1px 1px 0px #000000',
			
		'_content_mouseover': 0,
		'_nav_pressed': 0,
		'_nav_opacity_time': 400
	},
	
	
	
	_build: function()
	{
		// IE compatibility
		var m = window.Modernizr;
		if(!m.textshadow)
		{
			if($('html').hasClass('ie9')) this.$this.find('.text').textshadow(this.options.textshadow_ie9);
			else this.$this.find('.text').textshadow(this.options.textshadow_ie8);
		}
		
		
		// opacity di partenza
		this.$nav_prev.css({opacity:"0"});
		this.$nav_next.css({opacity:"0"});
		
		
		// Mouse sullo slider: si deve bloccare tutto
		this.$this
			.mouseenter({ $this: this },function(e){ e.data.$this.options._content_mouseover=5; })
			.mouseleave({ $this: this },function(e){ e.data.$this.options._content_mouseover=0; });


		// Nasconde tutti gli elementi di indice 0 della classe .card
		this.$wrapper.contents().filter('.card:gt(0)').hide();
		//this.$this.find('.text').textshadow('0px 0px 8px #000000');

		
		// Associa le funzioni ai pulsanti (e le animazioni)
		this.bind_click(this);
		this.$nav_prev
			.mouseenter({ $this: this },function(e){ e.data.$this.$nav_prev.animate({opacity:"0.5"}, e.data.$this.options._nav_opacity_time); })
			.mouseleave({ $this: this },function(e){ e.data.$this.$nav_prev.animate({opacity:"0"}, e.data.$this.options._nav_opacity_time); });
		this.$nav_next
			.mouseenter({ $this: this },function(e){ e.data.$this.$nav_next.animate({opacity:"0.5"}, e.data.$this.options._nav_opacity_time); })
			.mouseleave({ $this: this },function(e){ e.data.$this.$nav_next.animate({opacity:"0"}, e.data.$this.options._nav_opacity_time); });
		
		
		// Rotazione schede
		if(parseInt(this.$wrapper.contents().filter('.card').length)>1)
		{
			setInterval(function($this)
			{
				if($this.options._content_mouseover>0 || $this.options._nav_pressed==1)
				{
					/*config._content_mouseover--;*/
					$this.options._nav_pressed=0;
					return;
				}
				$this.next();	
			},
			this.options.interval*1000,this);
		}
	},
	
	
	next: function()
	{
		this.unbind_click(this);
		$first = this.$wrapper.contents().filter('.card:first');
		$next = $first.next('.card');
		
		$first.appendTo(this.$wrapper);
  		$first.fadeOut();
  		var $my_this = this;
  		$next.fadeIn(this.options.fade_duration,function(){ $my_this.bind_click($my_this); });
	},
	
	
	prev: function()
	{
		this.unbind_click(this);
		$first = this.$wrapper.contents().filter('.card:first');
		$last = $first.nextAll('.card:last');
		
		$last.prependTo(this.$wrapper);
  		$first.fadeOut();
  		var $my_this = this;
  		$last.fadeIn(this.options.fade_duration,function(){ $my_this.bind_click($my_this); });
	},
	
	
	
	bind_click: function($tthis)
	{
		$tthis.$nav_prev.bind("click",{ $this: $tthis },function(e){ e.data.$this.options._nav_pressed=1; e.data.$this.prev(); });
		$tthis.$nav_next.bind("click",{ $this: $tthis },function(e){ e.data.$this.options._nav_pressed=1; e.data.$this.next(); });
	},
	
	unbind_click: function($tthis)
	{
		$tthis.$nav_next.unbind("click");
		$tthis.$nav_prev.unbind("click");
	},
	

};







// Funzione per creare istanze indipendenti del plugin.
$.plugin = function(name, object)
{
	$.fn[name] = function(options) {
		// optionally, you could test if options was a string
		// and use it to call a method name on the plugin instance.
		return this.each(function() {
			if ( ! $.data(this, name) ) {
				$.data(this, name, Object.create(object).init(options, this));
			}
		});
	};
};



//Make sure Object.create is available in the browser (for our prototypal inheritance)
//Courtesy of Papa Crockford
//Note this is not entirely equal to native Object.create, but compatible with our use-case
if (typeof Object.create !== 'function')
{
	Object.create = function (o) {
		function F() {} // optionally move this outside the declaration and into a closure if you need more speed.
		F.prototype = o;
		return new F();
	};
}


// Codice base del plugin jquery.
// La logica del plugin e' racchiusa nell'oggetto VPC_ContentSlider_Object
(function($){
	
	// Start a plugin
	$.fn.vpc_contentslider = function(options) {
		
		// Don't act on absent elements -via Paul Irish's advice
		if ( this.length ) {
			return this.each(function(){
	
				// Create a new speaker object via the Prototypal Object.create
				var vpcCS = Object.create(VPC_ContentSlider_Object);
	
				// Run the initialization function of the speaker
				vpcCS.init(options, this); // `this` refers to the element
	
				// Save the instance of the speaker object in the element's data store
				$.data(this, 'vpcCS', vpcCS);
			});
		}
	};
})(jQuery);





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


5) Codice javascript/jquery

	var opt1 = { interval:2, textshadow_ie8:"1px 3px 1px #444444", textshadow_ie9:"2px 3px 2px #000000"	};
	
	var opt2 = { interval:1, textshadow_ie8:"1px 3px 1px #444444", textshadow_ie9:"2px 3px 2px #000000" };
	
	$( document ).ready(function() {
	
		$.plugin('samplePlugin1', VPC_ContentSlider_Object);
		$("#MainMenuSlider_orizz").vpc_contentslider(opt1);
	
		$.plugin('samplePlugin2',  VPC_ContentSlider_Object);
		$("#MainMenuSlider_vert").vpc_contentslider(opt2);

	});

*/



