<?php
/*
 Template Name: EV Calendar
*/


/* HEADER */
$VPC->Theme->page_class='thematic ev_page';
get_header();

$EV_GetData = false;
$ev_calendar_index = intval(get_post_meta(get_the_ID(),'EV_Calendar_Index',true));
if($ev_calendar_index>0) $EV_GetData /* serve dopo */ = $VPC->EV->GetData($ev_calendar_index,90,true);

// NO POST
if(!have_posts()) :	echo 'Contenuti non disponibili';
else :

the_post();


/* JAVASCRIPT */
if($EV_GetData) :
$VPC->Theme->set_print_plain_js(function()
{
	global $VPC;
	?>
	
	var thematic_gmap_fn = function(){ $("#thematic_gmap").vpc_gmap({
    
		lat: <?php echo $VPC->gmap_opz['lat']; ?>,
		lng: <?php echo $VPC->gmap_opz['lng']; ?>,
		zoom: <?php echo $VPC->gmap_opz['zoom']; ?>,
		info_maxWidth: 300,
		markers:[<?php $VPC->EV->Print_GmapMarkersData(); ?>],
	}); }
	
	vpc_responsive_obj.set_large_fn( thematic_gmap_fn );
	vpc_responsive_obj.set_small_fn( thematic_gmap_fn );
	
<?php
}, true /*onload*/); // END - function set_print_plain_js()
endif;

$VPC->Theme->add_js_file("script/vpc_gmap.jquery.js");
$VPC->Theme->add_js_file($VPC->gmap_url);


?>


<section class="main_article">
	<article id="post-<?php the_ID(); ?>">
	
	<header class="ahf">
            
		<h1 class="with_tagline"><a href="<?php the_permalink(); ?>" rel="bookmark"><span class="title"><?php the_title(); 
		?></span><small>a Porto Cesareo, Torre Lapillo, Punta Prosciutto e dintorni...</small></a></h1>
            
		<time pubdate="pubdate" datetime="<?php echo get_the_date("c"); ?>"><?php 
			edit_post_link( '<small style="font-size:0.85em;">(modifica)</small>', '', '&nbsp;&nbsp;&nbsp;', get_the_ID() );
		?></time>
            
<?php 	$VPC->Theme->share_on_socials(get_the_title(), get_permalink()); ?>
            
		<div class="clear"></div>
	</header>
	
	<section class="text">
		
<?php 	//the_content(); ?>
		
		<!--  h4 style="text-align:center;">&raquo; <a href="#view_map">Visualizza la mappa</a> &laquo;</h4  -->
		
		<?php if($EV_GetData) $VPC->Theme->Print_GMap('thematic_gmap'); ?>
		
		<div id="ads_leaderboard"><?php $VPC->Banner->Article1(); ?></div>

<?php if($EV_GetData) : ?>
		<section class="ev">
<?php 		$VPC->EV->Print_Calendar(); ?>
<?php 		$VPC->EV->Print_All_Boards(); ?>
		</section>
<?php else: echo 'Nessun evento in programma.'; ?>
<?php endif; ?>

		
	</section>
	
	
	</article>
</section>



<?php 

endif; /* if(have_posts()) */
get_footer();

?>