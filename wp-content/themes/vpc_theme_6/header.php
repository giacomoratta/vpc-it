<?php

global $VPC;

/* Compatibilita' di HTML5 con IE < 9 */
$VPC->Theme->add_js_file("script/html5shiv.js","","lt IE 9");
$VPC->Theme->add_js_file("script/html5shiv-printshiv.js","","lt IE 9");

/* jQuery e vpc_contentslider servono in tutte le pagine */
$VPC->Theme->add_js_file("script/jquery-1.11.1.min.js");
$VPC->Theme->add_js_file("script/vpc_contentslider_v2.jquery.js");
$VPC->Theme->add_js_file("script/modernizr.textshadow.js");
$VPC->Theme->add_js_file("script/jquery.textshadow.js");


$VPC->Theme->add_js_file("script/vpc_responsive.js");
$VPC->Theme->set_print_plain_js(function(){
?>
	var vpc_responsive_obj = new vpc_responsive(1093,683);

<?php
}, true /*onload*/); // END - function set_print_plain_js()



?><!DOCTYPE html>
<!--[if lte IE 7 ]> <html class="ie7" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 8 ]>     <html class="ie8" <?php language_attributes(); ?>> <![endif]-->
<!--[if IE 9 ]>     <html class="ie9" <?php language_attributes(); ?>> <![endif]-->
<!--[if gt IE 9 ]>  <html class="ie" <?php language_attributes(); ?>> <![endif]-->
<!--[if !(IE) ]><!--> <html class="not-ie" <?php language_attributes(); ?>> <!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>">
<meta name="HandheldFriendly" content="True"/>
<meta name="viewport" content="width=device-width,initial-scale=1,user-scalable=yes" />
<link rel="profile" href="http://gmpg.org/xfn/11">
<link rel="icon" type="image/png" href="<?php echo $VPC->site_url ?>vpcicon.png" />
<link rel="image_src" href="<?php $VPC->HeadInfo->img(); ?>" />
<meta name="robots" content="nofollow" />

<title><?php echo $VPC->site_title(); ?></title>

<meta name="description" content="<?php echo $VPC->page_description(); ?>" />

<meta name="keywords" content="<?php echo $VPC->seo_keywords; ?>" />

<meta name="author" content="<?php echo $VPC->HeadInfo->site_name; ?>" />
<meta name="google-site-verification" content="cct9hmNh5I52aewez0C1LbNsxMt-_ZpFICx9cmhhFjI" />

<meta property="og:title" content="<?php $VPC->HeadInfo->title(); ?>" />
<meta property="og:type" content="website" />
<meta property="og:image" content="<?php $VPC->HeadInfo->img(); ?>" />
<meta property="og:url" content="http://<?php echo $_SERVER["HTTP_HOST"].$_SERVER["REQUEST_URI"]; ?>" />
<meta property="og:description" content="<?php $VPC->HeadInfo->description(); ?>" />
<meta property="og:locale" content="it_IT" />
<meta property="og:site_name" content="<?php echo $VPC->HeadInfo->site_name; ?>" />
<meta property="fb:admins" content="100001389143007" />

<link rel="mobile-icon" href="<?php echo $VPC->template_url ?>img/favicon_57.png"/>
<link rel="icon" type="image/png" href="<?php echo $VPC->template_url ?>img/vpcicon.png" />
<link rel="shortcut icon" href="<?php echo $VPC->template_url ?>img/vpcicon.png" type="image/png"/>
<link href="<?php echo $VPC->template_url ?>img/favicon_57.png" rel="apple-touch-icon" />
<link href="<?php echo $VPC->template_url ?>img/favicon_72.png" rel="apple-touch-icon" sizes="72x72" />
<link href="<?php echo $VPC->template_url ?>img/favicon_114.png" rel="apple-touch-icon" sizes="114x114" />

<link rel="alternate" type="application/atom+xml" title="Atom 0.3" href="<?php bloginfo('atom_url'); ?>" />
<link rel="alternate" type="application/rss+xml" title="<?php bloginfo('name'); ?> RSS Feed" href="<?php bloginfo('rss2_url'); ?>" />
<link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />

<?php

/* CSS per ogni pagina */
$VPC->Theme->add_css_file("reset.css","","all");
$VPC->Theme->add_css_file("style.css","","all");
$VPC->Theme->print_css_files();


$VPC->Theme->social_buttons(function(){
?>
	<!--a href="http://m.visitportocesareo.it/" title="VisitPortoCesareo.it per smartphone e piccoli schermi" class="mobile"></a-->
	<a href="https://www.youtube.com/user/VisitPortoCesareo" title="YouTube" class="youtube" target="_blank"></a>
	<a href="https://twitter.com/portocesareo" title="Twitter" class="twitter" target="_blank"></a>
	<!--a href="http://www.facebook.com/pages/Visit-Porto-Cesareo/171772776168423?v=wall" title="Facebook" target="_blank"></a-->
	<!--a href="http://feeds.feedburner.com/PortoCesareo" title="FeedRSS" target="_blank"></a-->
	<a href="https://www.facebook.com/VisitPortoCesareo" class="facebook" title="Facebook" target="_blank"></a>
	<a href="http://feeds.feedburner.com/PortoCesareo" title="FeedRSS" class="feedrss" target="_blank"></a>
	<a href="mailto:visitportocesareo@gmail.com" title="Email" class="email"></a>
<?php
});

wp_head();


?>

</head>
<body>
<?php $VPC->facebook_sdk(); ?>
<div id="L1"><div id="L1_padding">

<section class="main_content <?php echo $VPC->Theme->page_class; ?>">
	
<header class="main_header">

	<section id="header_desktop">
	    <div class="logo">
	    	<a href="<?php echo $VPC->site_url; ?>" class="img" style="background-image:url('<?php echo $VPC->template_url?>img/logo420.png');"></a>
	        <!-- div class="msg"><a href="http://m.visitportocesareo.it/">Stai navigando con uno smartphone? Clicca qui</a></div -->
	    </div>
	    
		<div class="social">
	    	<div class="likebox" id="facebook_page_widget"><?php $VPC->facebook_page_widget(); ?></div>
	        <div class="buttons">
	        	<div id="social_buttons">
	        		<?php $VPC->Theme->social_buttons(); ?>
					<div class="clear"></div>
				</div>
	        </div>
	    </div>
    </section>
	
    
	<section id="header_mobile">
    	
    	<div class="logo">
    		<a href="<?php echo $VPC->site_url; ?>" class="img"><img src="<?php echo $VPC->template_url?>img/logo420.png" /></a>
    		<a href="#main_menu_mobile" class="sitemenu_icon onlymobile menu_btn" title="Men&ugrave; del sito"></a>
    		<a href="#main_search_mobile" class="sitemenu_icon onlymobile search_btn" title="Cerca nel sito..."></a>
    		<div class="business_promo"><?php $VPC->print_page_link($VPC->PAGE_BusinessPromo,'Promuovi la tua attivit&agrave; qui!'); ?></div>
    	</div>
    	
    	<div class="banner">
    	<?php $VPC->Banner->Small_Sidebar1(); ?>
    	</div>
    	
    	<div class="social">
    		<section class="main_search">
			</section>
    		<a href="#main_menu_mobile" class="sitemenu_icontext menu_btn">
    			<div class="ico"></div>
    			<div class="txt">Men&ugrave; del sito</div>
    			<div class="clear"></div>
    		</a>
    		<div class="buttons">
	        </div>
	        <!-- div class="likebox"><?php //$VPC->facebook_page_widget(false); ?></div-->
    		
    	</div>
    </section>
</header>

<?php

/* * * * * * * * * * HEADER RESPONSIVE HANDLERS * * * * * * * * * */
$VPC->Theme->set_print_plain_js(function()
{
?>

	var header_large_screen = function()
	{
		$( "#header_desktop .social .buttons" ).append( $( "#social_buttons" ) );
		$( "aside .main_search" ).append( $( "#main_search" ) );

		$( "#VPC_Banner_SidebarMini1_Container" ).append( $( "#VPC_Banner_sidebarmini1" ) );
		$( "#VPC_Banner_SidebarMini2_Container" ).append( $( "#VPC_Banner_sidebarmini2" ) );
		$( "#VPC_Banner_SidebarMini3_Container" ).append( $( "#VPC_Banner_sidebarmini3" ) );
	}

	var header_small_screen = function()
	{
		$( "#header_mobile .social .buttons" ).append( $( "#social_buttons" ) );
		$( "#header_mobile .social .main_search" ).append( $( "#main_search" ) );
		
		$( "#VPC_Banner_Footer_Container" ).append( $( "#VPC_Banner_sidebarmini1" ) );
		$( "#VPC_Banner_Footer_Container" ).append( $( "#VPC_Banner_sidebarmini2" ) );
		$( "#VPC_Banner_Footer_Container" ).append( $( "#VPC_Banner_sidebarmini3" ) );
	}
	
	var header_mobile_screen = function()
	{
		$( "footer.main_footer .links" ).append( $( "#social_buttons" ) );
		$( "#main_search_mobile" ).append( $( "#main_search" ) );
		
		$( "#VPC_Banner_Footer_Container" ).append( $( "#VPC_Banner_sidebarmini1" ) );
		$( "#VPC_Banner_Footer_Container" ).append( $( "#VPC_Banner_sidebarmini2" ) );
		$( "#VPC_Banner_Footer_Container" ).append( $( "#VPC_Banner_sidebarmini3" ) );
	}

	vpc_responsive_obj.set_large_fn( header_large_screen );
	vpc_responsive_obj.set_small_fn( header_small_screen );
	vpc_responsive_obj.set_mobile_fn( header_mobile_screen );
	
	
<?php
}, true /*onload*/); // END - function set_print_plain_js()





/* * * * * * * * * * MENU' PRINCIPALE * * * * * * * * * */
$VPC->Theme->main_menu(function($vpcobj){
?>

	<section>
    <p><?php $vpcobj->print_page_link($vpcobj->PAGE_TouristInfo,
    	'<span class="bigger">Informazioni Turistiche</span><br />'.
    	'<span class="subtitle">Spiagge, Mare, Natura, Territorio, Storia</span>'
	); ?></p>
    </section>
    
	<section class="bigger">
    <p><?php $vpcobj->print_page_link($vpcobj->PAGE_Event); ?></p>
    <p><?php $vpcobj->print_page_link($vpcobj->PAGE_Promo); ?></p>
    </section>
    
    <section>
    <p><?php $vpcobj->print_page_link($vpcobj->PAGE_Sleeping); ?></p>
    <p><?php $vpcobj->print_page_link($vpcobj->PAGE_Eating); ?></p>
    <p><?php $vpcobj->print_page_link($vpcobj->PAGE_SeaBeach); ?></p>
    <p class="smaller"><?php $vpcobj->print_page_link($vpcobj->PAGE_ShoppingServices); ?></p>
    <!-- p class="smaller"><?php //$vpcobj->print_page_link($vpcobj->PAGE_Links); ?></p -->
    </section>
   
    <section>
	<p class="bigger"><?php $vpcobj->print_page_link($vpcobj->PAGE_News); ?></p>
    <p><?php $vpcobj->print_page_link($vpcobj->PAGE_Photo); ?></p>
	</section>
    
    <section>
	<p><?php $vpcobj->print_page_link($vpcobj->PAGE_About); ?></p>
	<p class="bigger"><?php $vpcobj->print_page_link($vpcobj->PAGE_BusinessPromo); ?></p>
    </section>


<?php
}, $VPC);




?>

