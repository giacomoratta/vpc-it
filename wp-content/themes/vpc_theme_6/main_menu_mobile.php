<?php

global $VPC;


/*

* * * * * * * * * *  MAIN MENU MOBILE  * * * * * * * * * *

#L1 #main_menu_mobile { font-size:1.2em; background-color:#DCECFF; margin-bottom:2em; }
#L1 #main_menu_mobile a { padding:0.3em 0.8em;  text-decoration:none; display:block; }
#L1 #main_menu_mobile p { margin-bottom:0.3em; }
#L1 #main_menu_mobile p span.subtitle { font-size:0.75em; }
#L1 #main_menu_mobile .bigger { font-weight:600; font-size:1.05em; }
#L1 #main_menu_mobile .smaller { font-size:0.9em; }
#L1 #main_menu_mobile_open p, #L1 #main_menu_mobile_close p { margin-bottom:0; }
#L1 #main_menu_mobile .panel { padding-top:0.7em; }

#L1 #main_menu_mobile a.with_icon { padding:0.4em 0.5em; font-size:1.1em;}
#L1 #main_menu_mobile a.with_icon .menu_icon_left { background:url('img/buttons/menu_icon_b9d9ff.gif') center center no-repeat; width:40px; height:31px; display:block; float:left; }
#L1 #main_menu_mobile a.with_icon .menu_text { padding:0.25em 0.7em; display:block; float:left; }
#L1 #main_menu_mobile a.with_icon .menu_icon_right { background:url('img/buttons/arrow_down_b9d9ff.gif') center center no-repeat; width:45px; height:31px;  display:block; float:right; }
#L1 #main_menu_mobile a.with_icon .clear { display:block; }

#L1 #main_menu_mobile_close a.with_icon .menu_text { float:right; }
#L1 #main_menu_mobile_close a.with_icon .menu_icon_right { background-image:url('img/buttons/arrow_up_b9d9ff.gif'); }

*/

?>

<section id="main_menu_mobile">

	<section id="main_menu_mobile_open" class="bigger">
    <p><a href="#" class="with_icon">
    	<span class="menu_icon_left"></span>
    	<span class="menu_text">Scegli una pagina...</span>
    	<span class="menu_icon_right"></span>
    	<span class="clear"></span>
    </a></p>
    </section>
    
    <div class="panel" style="display:none;">
<?php

	/* definito in header.php */
	$VPC->Theme->main_menu();
 
?>
	</div>
</section>


<?php 

/* PLAIN JAVASCRIPT */
$VPC->Theme->set_print_plain_js(function()
{
?>

	$('#main_menu_mobile_open a').bind( "click", function(e)
	{
		e.preventDefault();
		
		var box = $('#main_menu_mobile .panel');
		
		$('#main_menu_mobile_open').css({'display':'none'});
		box.animate({ height: "toggle"}, 2000);
	});
	
	
	$('#main_menu_mobile_close a').bind( "click", function(e)
	{
		e.preventDefault();
		
		var box = $('#main_menu_mobile .panel');
		
		box.animate({ height: "toggle"}, 2000, function(){ $('#main_menu_mobile_open').css({'display':'block'}); });
	});
	
	function main_menu_mobile_hide()
	{
		$('#main_menu_mobile').css({'display':'none'});
		$('#main_menu_mobile .panel').css({'display':'none'});
		$('#main_menu_mobile_open').css({'display':'block'});
	}
	
	function main_menu_mobile_show()
	{
		$('#main_menu_mobile').css({'display':'block'});
		$('#main_menu_mobile .panel').css({'display':'none'});
		$('#main_menu_mobile_open').css({'display':'block'});
	}

	vpc_responsive_obj.set_large_fn( main_menu_mobile_hide );
	vpc_responsive_obj.set_small_fn( main_menu_mobile_show );
	vpc_responsive_obj.set_mobile_fn( main_menu_mobile_show );

<?php
}, true /*onload*/); // END - function set_print_plain_js()

?>