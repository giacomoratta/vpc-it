<?php
/**
 * Template Name: List Page
 * 
 * Standard page loaded by page.php.
 * 
 * This template has the standar loop implemented by WP_Query.
 * 
 */


/* HEADER */
$VPC->Theme->page_class='list_page';
get_header();

// NO POST
if(!have_posts()) :	echo 'Contenuto non disponibile';

else : the_post();




$VPC->Theme->set_print_plain_js(function()
{
	?>

	vpc_responsive_obj.set_large_fn( function(){ $('.posts_list').masonry({ itemSelector: 'article.basicpost' }); } );
	vpc_responsive_obj.set_small_fn( function(){ $('.posts_list').masonry({ itemSelector: 'article.basicpost' }); } );
	vpc_responsive_obj.set_mobile_fn( function(){ $('.posts_list').masonry('destroy'); } );
	
	
<?php
}, true /*onload*/); // END - function set_print_plain_js()

$VPC->Theme->add_js_file("script/masonry.jquery.js");

?>

<section class="main_article">
	<article id="post-<?php the_ID(); ?>">
	
	<header class="ahf">
            
		<h1 class="with_tagline"><a href="<?php the_permalink(); ?>" rel="bookmark"><span class="title"><?php the_title(); ?></span></a></h1>
            
		<time pubdate="pubdate" datetime="<?php echo get_the_date("c"); ?>"><?php 
		
			edit_post_link( '<small style="font-size:0.85em;">(modifica)</small>', '', '&nbsp;&nbsp;&nbsp;', get_the_ID() );

		?></time>
            
<?php 	$VPC->Theme->share_on_socials(get_the_title(), get_permalink()); ?>
            
		<div class="clear"></div>
	</header>
	
	<div id="ads_leaderboard"><?php $VPC->Banner->Article1(); ?></div>

	<div class="posts_list">
<?php 

		$post_count = 0;
		$posts_per_page = 20;
		$id = get_the_ID();
		
		if($VPC->PAGE_News == $id)
		$args = array(
			'cat' => $VPC->CAT_News,
			'post_type' => 'post',
			'posts_per_page' => $posts_per_page,
			'paged' => get_query_var('paged'),
			'orderby' => 'date',
			'order' => 'desc'
		);

		elseif($VPC->PAGE_Featured == $id)
		$args = array(
				'tag' => $VPC->TAG_Featured,
				'post_type' => 'post',
				'posts_per_page' => $posts_per_page,
				'paged' => get_query_var('paged'),
				'orderby' => 'date',
				'order' => 'desc'
		);

		
		$the_query = new WP_Query( $args );
		if ( !$the_query->have_posts() ) : 
		
			echo 'Non ci sono articoli.';
		
		
		else:
		
		while( $the_query->have_posts() )
		{
			$the_query->the_post();
			
			$VPC->PostList->Print_BasicPost(
				get_the_ID(), 
				get_the_title(), 
				get_the_excerpt(),
				get_the_date('c'),
				get_the_date()
				//$VPC->PostList->get_ISO8601_date(get_the_date($VPC->PostList->date_basic_format)),
				//$VPC->PostList->get_text_date(get_the_date($VPC->PostList->date_basic_format))
			);
			
			$post_count++;
		}
		
		$nav_info = array();
		
		$nav_info['total_posts'] = intval($the_query->found_posts);
		$nav_info['posts_per_page'] = $posts_per_page;
		$nav_info['posts_displayed'] = intval($the_query->post_count);
		$nav_info['page_number'] = max(1,intval(get_query_var('paged')));
		$nav_info['nav_labels'] = array("Articoli pi&ugrave; recenti","Articoli pi&ugrave; vecchi");
		
		endif;

		wp_reset_postdata();
?>
	</div><!-- class="posts_list" -->
	
<?php if($post_count>0) $VPC->PostList->Print_Custom_PageNavigation($nav_info); ?>
	
	
	</article>
</section>

<?php 

endif; /* if(have_posts()) */

get_footer(); ?>