<?php

global $VPC;

?>
</section><!-- .main_content -->



<?php

get_sidebar();

?>

<div id="VPC_Banner_Footer_Container">

<?php

$VPC->Banner->Sidebar2(); 

//$VPC->Banner->SidebarMini();
//$VPC->Banner->SidebarMini();
//$VPC->Banner->SidebarMini();

?>

</div>
	
	
<?php

$VPC->Theme->call_print_pre_footer();





/* Questo codice js DEVE stare alla fine di tutti i file/codici js!!! */
$VPC->Theme->set_print_plain_js(function()
{
?>
	vpc_responsive_obj.go();
	$(window).resize(function(){ vpc_responsive_obj.go(); });

<?php
}, true /*onload*/); // END - function set_print_plain_js()
?>


<section id="main_menu_mobile" >
	<header>Men&ugrave; del sito</header>
	<section class="main_menu_section">  
<?php

	/* definito in header.php */
	$VPC->Theme->main_menu();
 
?>
	</section>
</section><!-- #main_menu_mobile -->



<section id="main_search_mobile" class="main_search">
	<header>Cerca...</header>
</section>



<div class="clear"></div>

<footer class="main_footer">
<p class="links">
	<a href="<?php echo $VPC->site_url; ?>">Homepage</a>
   	<?php $VPC->print_page_link($VPC->PAGE_About); ?>
    <?php $VPC->print_page_link($VPC->PAGE_BusinessPromo,"","font-weight:bold;font-size:1.05em;"); ?>
    <?php //$VPC->print_page_link($VPC->PAGE_Privacy); ?>
    <a href="//www.iubenda.com/privacy-policy/728085" class="iubenda-white iubenda-embed" title="Privacy Policy">Privacy Policy</a>
</p>
<p>Powered by <a href="http://wordpress.org/" target="_blank">Wordpress</a></p>
<p>&copy; VisitPortoCesareo.it</p>
</footer>

</div> <!-- #L1_padding -->
</div> <!-- #L1 -->


<?php $VPC->Theme->print_js_files(); ?>



<script type="text/javascript">
<?php $VPC->Theme->call_print_plain_js(); ?>
</script>

<?php $VPC->google_analytics(); ?>

<?php wp_footer(); ?>
</body>
</html>