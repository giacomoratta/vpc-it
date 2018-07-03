/**
 * Insieme di regole e funzioni per gestire le differenti dimensioni del browser.
 * Dipende da jquery.
 * 
 */




/**
 * Costruttore dell'oggetto vpc_responsive.
 */
function vpc_responsive(max_size, min_size)
{
	this.old_size = 0;
	this.new_size = 0;
	
	this.max_size = max_size;
	this.min_size = min_size;
	
	this.large_fn = new Array();
	this.small_fn = new Array();
	this.mobile_fn = new Array();
}




/**
 * Imposta background-image con la url indicata nell'attributo 'data-href'
 */
vpc_responsive.prototype.set_imgback = function()
{
	$(this).css({'background-image':'url('+$(this).attr("data-href")+')'});
}


/**
 * Imposta src di img con la url indicata nell'attributo 'data-href'
 */
vpc_responsive.prototype.set_imgsrc = function()
{
	$(this).attr("src",$(this).attr("data-href"));
}


/**
 * Imposta src di iframe, script, ecc.
 */
vpc_responsive.prototype.set_scriptsrc = function()
{
	$(this).attr("src",$(this).attr("data-href"));
} 



/**
 * Azioni con lo schermo grande
 */
vpc_responsive.prototype.browser_width_large = function()
{
	this.old_size = this.new_size;
	this.new_size = 1;
	if(this.old_size == this.new_size) return;
	//console.log("screen size = large");
	
	$("#MainMenuSlider_vert .lazy-imgback").each(this.set_imgback);
	$("#MainMenuSlider_orizz .lazy-imgback").each(this.set_imgback);
	$("#iframe_facebook_page_widget").each(this.set_scriptsrc);
	//$("#iframe_meteo_sidebar_widget").each(this.set_scriptsrc);
	$(".home_featured article.video iframe").each(this.set_scriptsrc);
	
	for (i = 0; i < this.large_fn.length; i++) { this.large_fn[i](); }
}

vpc_responsive.prototype.set_large_fn = function(fn) { this.large_fn.push(fn); }





/**
 * Azioni con lo schermo piccolo (tablet)
 */
vpc_responsive.prototype.browser_width_small = function()
{
	this.old_size = this.new_size;
	this.new_size = 2;
	if(this.old_size == this.new_size) return;
	//console.log("screen size = small");
	
	$("#MainMenuSlider_orizz .lazy-imgback").each(this.set_imgback);
	
	for (i = 0; i < this.small_fn.length; i++) { this.small_fn[i](); }
}

vpc_responsive.prototype.set_small_fn = function(fn) { this.small_fn.push(fn); }





/**
 * Azioni con schermi mobile piccoli (smartphone)
 */
vpc_responsive.prototype.browser_width_mobile = function()
{
	this.old_size = this.new_size;
	this.new_size = 3;
	if(this.old_size == this.new_size) return;
	//console.log("screen size = mobile");
	
	// non serve... le immagini vengono caricate comunque dalla galleria
	//$(".vpcgallery_slider_replace .lazyimg").each(set_imgsrc);
	
	for (i = 0; i < this.mobile_fn.length; i++) { this.mobile_fn[i](); }
}

vpc_responsive.prototype.set_mobile_fn = function(fn) { this.mobile_fn.push(fn); }







/**
 * Funzione da richiamare al caricamento del documento e ad ogni resize del browser.
 * 
 * 		var vpc_responsive_obj = new vpc_responsive(1093,743); //costruttore
 * 
 * 		vpc_responsive_obj.vpc_responsive_go();
 * 		$(window).resize(function(){ vpc_responsive_obj.vpc_responsive_go(); });
 */
vpc_responsive.prototype.go = function(max_size, min_size)
{
	var width = $(window).width();
	
	if(width>=this.max_size) this.browser_width_large();
	else if(width<this.max_size && width>=this.min_size) this.browser_width_small();
	else if(width<this.min_size) this.browser_width_mobile();
}