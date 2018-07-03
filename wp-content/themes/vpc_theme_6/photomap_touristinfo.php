<?php
/*
 Template Name: PhotoMap_TouristInfo
*/

/* HEADER */
$VPC->Theme->page_class='photomap';
get_header();


// NO POST
if(!have_posts()) :	echo 'Contenuti non disponibili';
else :

the_post();

$phData = $VPC->PostList->photoMap_touristInfoPages(get_the_ID(),$phData_loadmap,$phData_navigation);



/* JAVASCRIPT */

if($phData_loadmap)
$VPC->Theme->set_print_plain_js(function()
{
	global $VPC;
?>
			
	var thematic_gmap_fn = function(){ $("#thematic_gmap").vpc_gmap({
	
		lat: <?php echo $VPC->gmap_opz['lat']; ?>,
		lng: <?php echo $VPC->gmap_opz['lng']; ?>,
		zoom: <?php echo $VPC->gmap_opz['zoom']; ?>,
		info_maxWidth: 250,
		markers:[<?php $VPC->PostList->Print_GmapMarkers_photoMap_touristInfoPages(); ?>],
		});
	}
	
	vpc_responsive_obj.set_large_fn( thematic_gmap_fn );
	vpc_responsive_obj.set_small_fn( thematic_gmap_fn );
	
	
<?php
}, true /*onload*/); // END - function set_print_plain_js()


$VPC->Theme->set_print_plain_js(function()
{
?>

	vpc_responsive_obj.set_large_fn( function(){ $('.boxes').masonry({ itemSelector: 'article' }); } );
	vpc_responsive_obj.set_small_fn( function(){ $('.boxes').masonry({ itemSelector: 'article' }); } );
	vpc_responsive_obj.set_mobile_fn( function(){ $('.boxes').masonry('destroy').css({'height':'auto'}); } );
	
	
<?php
}, true /*onload*/); // END - function set_print_plain_js()

$VPC->Theme->add_js_file("script/masonry.jquery.js");
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
		
		<?php if($phData_loadmap) $VPC->Theme->Print_GMap('thematic_gmap'); ?>
		
		<div id="ads_leaderboard"><?php $VPC->Banner->Article1(); ?></div>
		
<?php 
		if(!$phData) : echo 'Contenuti non disponibili';

		else :
			if(get_the_ID()!=$VPC->PAGE_TouristInfo) :
				
				echo "\n\t\t\t".'<section class="boxes">';
				$VPC->PostList->Print_photoMap();
				echo "\n\t\t\t".'</section><!-- .boxes -->'."\n\n";

			else : $VPC->PostList->Print_touristInfoPages();
			
			endif;

		endif;
?>		
		
	</section>
	
<?php if($phData) $VPC->PostList->Print_Custom_PageNavigation($VPC->PostList->photomapTouristinfoPosts_data['extra']); ?>
	
	</article>
</section>

<?php

endif; /* if(have_posts()) */
get_footer();

?>