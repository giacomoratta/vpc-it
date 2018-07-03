<?php
/**
 * Standard page loaded by single.php and page.php.
 * 
 */

/* HEADER */
get_header();

// NO POST
if(!have_posts()) : echo 'Contenuto non disponibile';
else : the_post();



/* PLAIN JAVASCRIPT */
$GMap_LatLng = false;
$latlng = $VPC->get_latlng(get_the_ID());
if(count($latlng)>=2) :
$GMap_LatLng = true;
$VPC->Theme->set_print_plain_js(function($data)
{
?>

	var single_gmap_fn = function(){
		
		$("#single_gmap").vpc_gmap({
			lat:<?php echo $data[0]; ?>,
			lng:<?php echo $data[1]; ?>,
			zoom:16,
			markers:[{lat:<?php echo $data[0]; ?>,lng:<?php echo $data[1]; ?>}],	
		});
		$("#single_gmap .errormsg").css({"display":"block"});
	}
	
	<?php 
	
	$single_gmap_fn_url = 'http://maps.googleapis.com/maps/api/staticmap?center='.$data[0].','.$data[1].
		'&zoom=15&size=700x400&markers=color:red%7C'.$data[0].','.$data[1].'&sensor=false';
	
	$single_gmap_fn_link = 'http://maps.google.it/maps?q='.$data[0].','.$data[1].'&t=m&z=15';
	
	?>
	
	vpc_responsive_obj.set_large_fn( single_gmap_fn );
	vpc_responsive_obj.set_small_fn( single_gmap_fn );
	vpc_responsive_obj.set_mobile_fn( function()
	{
		$("#single_gmap").css({"background":"url('<?php echo $single_gmap_fn_url; ?>') center center no-repeat"});
		$("#single_gmap .errormsg").css({"display":"none"});
		$("#single_gmap_link a").attr('href','<?php echo $single_gmap_fn_link; ?>');
	});

<?php
}, true /*onload*/, $latlng); // END - function set_print_plain_js()
$VPC->Theme->add_js_file("script/vpc_gmap.jquery.js");
$VPC->Theme->add_js_file($VPC->gmap_url);
endif;

?>


<section class="main_article">
	<article id="post-<?php the_ID(); ?>">

		<header class="ahf">
		
<?php	
		/* * * * * * * * * * BREADCRUMBS * * * * * * * * * */ 		

		if($VPC->Theme->page_class=='simple_post')
			$VPC->Theme->Print_SingleBreadcrumbs(get_the_ID());
		
		else if($VPC->Theme->page_class=='simple_page')
			$VPC->Theme->Print_PageBreadcrumbs(get_the_ID());
		
		else if($VPC->Theme->page_class=='business_page')
		{
			
			$VPC->Theme->Print_BusinessPageBreadcrumbs(get_the_ID());
			
			function business_page_releated_posts($content)
			{
				global $VPC;
				$exclude_ids = array();
				
				$EV_GetData = $VPC->EV->GetData(0,1000,false,array(get_the_ID()),$exclude_ids);
				
				
				ob_start();
				if($EV_GetData) :
?>
				<section id="small_ev">
					<?php $VPC->EV->Print_Calendar(); ?>
					<?php $VPC->EV->Print_All_Boards(); ?>
				</section>
<?php
				endif;
				$releatedEVposts = ob_get_contents();
				ob_end_clean();
				
				
				ob_start();
				$VPC->PostList->businessPageReleatedPosts(get_the_ID(),10,$exclude_ids);
				$VPC->PostList->Print_businessPageReleatedPosts(get_the_ID());
				$businessPageReleatedPosts = ob_get_contents();
				ob_end_clean();
				
				return $releatedEVposts."\n\n\n".$content."\n\n\n".$businessPageReleatedPosts;
			}
			
			add_filter( 'the_content', 'business_page_releated_posts', 5, 10);
		}

?>
            
		<h1><a href="<?php the_permalink(); ?>" rel="bookmark"><?php 
		
			$title = trim(get_post_meta(get_the_ID(),'PTH1_CustomTitle',true));
			if(strlen($title)>0) echo $title; 
			else the_title();
			
		?></a></h1>
            
		<time pubdate="pubdate" datetime="<?php echo get_the_date("c"); ?>"><?php 
		
			edit_post_link( '<small style="font-size:0.85em;">(modifica)</small>', '', '&nbsp;&nbsp;&nbsp;', get_the_ID() );

			if($VPC->Theme->page_class=='simple_post' || $VPC->Theme->page_class=='simple_page')
				echo get_the_date("d F Y");

		?></time>
            
<?php 		$VPC->Theme->share_on_socials(get_the_title(), get_permalink()); ?>
            
		<div class="clear"></div>
	</header>
	
        
	<section class="text">
	
		<div id="ads_leaderboard"><?php $VPC->Banner->Article1(); ?></div>

<?php 
		$VPC->Shortcode_Add();	
		the_content(); 
?>
	</section>
	
<?php 
			
	if($VPC->Theme->page_class=='business_page' && function_exists('jcontact_box_ajaxemail_script'))
		$VPC->Theme->set_print_plain_js(jcontact_box_ajaxemail_script);
?>
        
<?php if($GMap_LatLng): ?>
	<?php $VPC->Theme->Print_GMap('single_gmap'); ?>
	<section id="single_gmap_link" style="text-align:center;padding-bottom:1em;"><a href="" target="_blank">( Clicca per INGRANDIRE su maps.google.it )</a></section>
<?php endif; ?>
        
<?php 	$VPC->Theme->Print_BusinessPages_SinglePost(); ?>       
    	
    	
        
	<footer class="ahf">
 
<?php 		$VPC->Theme->share_on_socials(get_the_title(), get_permalink()); ?>
            
			<div class="clear"></div>
            
<?php	
		/* * * * * * * * * * BREADCRUMBS * * * * * * * * * */ 		

		if($VPC->Theme->page_class=='simple_post')
			$VPC->Theme->Print_SingleBreadcrumbs(get_the_ID());
		
		else if($VPC->Theme->page_class=='simple_page')
			$VPC->Theme->Print_PageBreadcrumbs(get_the_ID());
		
		else if($VPC->Theme->page_class=='business_page')
			$VPC->Theme->Print_BusinessPageBreadcrumbs(get_the_ID());

?>

	</footer>
	</article>
    
    
    

<?php 

	/* * * * * * * * * * STRINGHE COMMENT FORM * * * * * * * * * */

	if($VPC->Theme->page_class=='business_page')
		$strings = array(
			'__block_title' => 'Lascia una recensione!',
			'title' => 'Scrivi qui la tua recensione',
			'label' => 'Pubblica la recensione',
			'msg' => 'Questo form serve per solo esprimere un\'opinione su questa attivit&agrave;.<br/>'.
					 'Per inviare un\'email all\'attivit&agrave; utilizzare il form precedente.',
		);
	
	else 
		$strings = array(
			'__block_title' => 'Lascia un commento!',
			'title' => 'Scrivi qui il tuo commento',
			'label' => 'Pubblica il commento',
			'msg' => '',
		);
	
?>



<?php

	if(comments_open())
	{
?>
		<h2 class="comments_title"><?php echo $strings['__block_title']; ?></h2>

		<section class="fb-comments" data-href="<?php the_permalink(); ?>" data-width="100%" data-numposts="10" data-colorscheme="light"></section>
<?php
		comments_template();
	
		$VPC->Theme->Print_commentForm($strings);

	}
?>
    
    

</section> <!-- .main_article -->


 
<?php 

endif; /* if(have_posts()) */

get_footer(); ?>