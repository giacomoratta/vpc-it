<?php
/**
 * The homepage template file.
 *
 * @package WordPress
 * @subpackage VisitPortoCesareo.it Theme v.6
 * 
 */


$VPC->Theme->page_class='homepage';

get_header();


/* PAGE CONTENT */

$VPC->Theme->MainMenuSlider('MainMenuSlider_orizz');

?>


<div id="ads_leaderboard"><?php $VPC->Banner->Article1(); ?></div>


<?php 

$post_count = $VPC->PostList->topPosts(100);
$VPC->PostList->lastPosts(5-$post_count);

$VPC->EV->GetData(0 /* tutti */, 90);

?>


<section class="main_column2">
<?php $VPC->EV->Print_Calendar(); ?>
<?php $VPC->EV->Print_All_Boards(); ?>
<?php $VPC->Theme->Print_feedNewsletter(); ?>
</section>


<section class="main_column1">
<?php $VPC->PostList->Print_Homepage_LastPosts(); ?>
</section>




<?php

/* FEATURED POSTS */
$VPC->Theme->set_print_pre_footer(function(){
?>
	<div class="clear"></div>
<?php

	global $VPC;
	$VPC->PostList->featuredPosts(3);
	echo $VPC->PostList->Print_Homepage_FeaturedPosts();

});



/* PLAIN JAVASCRIPT */
$VPC->Theme->set_print_plain_js(function()
{
?>

	$.plugin('MMS_orizz_VPC_ContentSlider', VPC_ContentSlider_Object);
	$("#MainMenuSlider_orizz").vpc_contentslider({
		interval:3,
		textshadow_ie8:"1px 3px 1px #444444",
		textshadow_ie9:"2px 3px 2px #000000"
	});

<?php
}, true /*onload*/); // END - function set_print_plain_js()


get_footer();

?>