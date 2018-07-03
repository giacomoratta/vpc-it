<?php

global $VPC;

?>
<!-- * * * * * * * * * * * * * * * * * * * * SIDEBAR * * * * * * * * * * * * * * * * * * * * -->
<aside>
<div class="business_promo"><?php $VPC->print_page_link($VPC->PAGE_BusinessPromo,'Promuovi la tua attivit&agrave; qui!'); ?></div>

<section class="aside_item main_search">
	<form id="main_search" action="<?php echo $VPC->site_url; ?>" method="get">
		<input class="text" type="text" name="s" id="s" value="Cerchi qualcosa?" 
		onFocus="if(this.value=='Cerchi qualcosa?'){this.value='';}" 
		onBlur="if(this.value==''){this.value='Cerchi qualcosa?';}"/>
		<input class="button" type="submit" value="" />
		<div class="clear"></div>
	</form>
</section>

<div class="aside_item"><?php $VPC->Banner->Sidebar1(); ?></div>

<?php 

if(!$VPC->isHomepage())
{
	$VPC->Theme->MainMenuSlider('MainMenuSlider_vert','aside_item');
	
	/* PLAIN JAVASCRIPT */
	$VPC->Theme->set_print_plain_js(function()
	{
?>

	$.plugin('MMS_vert_VPC_ContentSlider', VPC_ContentSlider_Object);
	$("#MainMenuSlider_vert").vpc_contentslider({
		interval:3,
		textshadow_ie8:"1px 3px 1px #444444",
		textshadow_ie9:"2px 3px 2px #000000"
	});
	
<?php
	}, true /*onload*/); // END - function set_print_plain_js()
}

?>

<section id="main_menu" class="aside_item  main_menu_section">
<?php

	/* definito in header.php */
	$VPC->Theme->main_menu();
 
?>
</section>

<!-- div class="aside_item"><?php //$VPC->meteo_sidebar_widget(); ?></div -->

</aside>