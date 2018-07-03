<?php
/**
 * The main template file.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage VisitPortoCesareo.it Theme v.6
 * 
 */

if(!isset($VPC)) die('<!-- VisitPortoCesareo.it > Main object VPC not defined! -->');

if($VPC->isHomepage())
{
	include('homepage.php');
	die();
}

/* HEADER */
$VPC->Theme->page_class='list_page';
get_header();


 ?>

<section class="main_article">
	<article>
		<header class="ahf">
<?php if(!have_posts()) : ?>
			<h1 class="with_tagline"><span class="title">Non ho trovato niente!</span></h1>
<?php elseif(is_search()) : ?>			
			<h1 class="with_tagline"><span class="title">Ricerca &quot;<?php echo $_GET['s']; ?>&quot; <small>(pag. <?php echo ($paged==0?1:$paged); ?>)</small></span></h1>
<?php elseif(is_home()||is_front_page()) : ?>
			<h1 class="with_tagline"><span class="title">Archivio del blog <small>(pag. <?php echo ($paged==0?1:$paged); ?>)</small></span></h1>
<?php endif; ?>
            
            <div class="clear"></div>
        </header>
		
		<div id="ads_leaderboard"><?php $VPC->Banner->Article1(); ?></div>

		<div class="posts_list">
			
<?php

		/* The loop */
		$count=0;
		
		if ( have_posts() ) : 
		
			$have_posts = true;

			while ( have_posts() ) :

				the_post();

				$VPC->PostList->Print_BasicPost(
					get_the_ID(), 
					get_the_title(), 
					get_the_excerpt(),
					get_the_date('c'),
					get_the_date()
				);

				$count++;

				if($count%2==0) :
?>
			<div class="clear"></div>
<?php
				endif;
				

			endwhile; ?>	
	
<?php else : $have_posts = false; ?>

			<div class="sorry">
				<p>Mi dispiace ma in questo indirizzo non c'&eacute; nessuna pagina.</p>
				<p>Per continuare dai uno sguardo alla barra laterale: potrebbe esserci qualcosa di interessante!</p>
			</div>

<?php endif; ?>
		
        	<div class="clear"></div>
        </div><!-- .posts_list -->

<?php  if(have_posts()) $VPC->PostList->Print_PageNavigation("Articoli pi&ugrave; recenti","Articoli pi&ugrave; vecchi"); ?>
        
	</article>

</section> <!-- .main_article -->


<?php get_footer(); ?>