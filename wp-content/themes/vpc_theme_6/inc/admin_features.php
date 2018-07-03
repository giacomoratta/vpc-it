<?php

/**
 * Template Name: Admin Features
 *
 */

function admin_ACT_page_tags()
{
	$TAGS = array();
	$PAGES = array();
	$TAG_ids = array();
	$PAGE_ids = array();
	
	$PAGE_TAGS = array();
	$BLOG_PAGES = array();
	$BLOG_TAGS = array();
	$EMPTY_PAGES_ids = array();
	
	
	// TAG
	$args = array(
    	'number'                    => 0, //all
    	'orderby'                   => 'slug', 
    	'order'                     => 'ASC'
	);
	$gets = get_tags($args); $html="";
	foreach ( $gets as $g )
	{
		$TAGS[] = trim($g->slug);
		$TAG_ids[] = $g->term_id;
	}

	
	// PAGE
	$args = array(
    	'numberposts'               => -1, //all
    	'orderby'                   => 'post_name', 
    	'order'                     => 'ASC'
	);
	$gets = get_pages($args); $html="";
	if(!isset($_GET['minlen'])) $_GET['minlen']=200;
	foreach ( $gets as $g )
	{
		$PAGES[] = trim($g->post_name);
		$PAGE_ids[] = $g->ID;
		
		// Poco contenuto
		if(strlen($g->post_content)<$_GET['minlen']) $EMPTY_PAGES_ids[] = $g->ID;
	}
	

	// Ricerca
	for($i=0; $i<count($TAGS); $i++)
	{
		$id = array_search($TAGS[$i],$PAGES);
		if(!($id===false)) $PAGE_TAGS[]=array($TAGS[$i],$TAG_ids[$i],$PAGE_ids[$id]);
		else $BLOG_TAGS[]=array($TAGS[$i],$TAG_ids[$i]);
	}
	for($i=0; $i<count($PAGES); $i++)
	{
		$id = array_search($PAGES[$i],$TAGS);
		if($id===false) $BLOG_PAGES[]=array($PAGES[$i],$PAGE_ids[$i]);
	}
	
	// Stampa
	echo "<h2>Controllo Tag e Pagine attivit&agrave;</h2>\n";
	echo '<p>Per controllare la lunghezza del contenuto delle pagine usare il parametro GET &quot;minlen&quot;.</p>';
	echo "<h3>TAG delle pagine</h3>\n";
	
	$edit_page_link = home_url()."/wp-admin/post.php?action=edit&post=";
	$edit_tag_link = home_url()."/wp-admin/edit-tags.php?action=edit&taxonomy=post_tag&tag_ID=";
	
	for($i=0; $i<count($PAGE_TAGS); $i++)
	{
		$empty_page="";
		if(in_array($PAGE_TAGS[$i][2], $EMPTY_PAGES_ids))
			$empty_page='<span style="color:#FF6600;">Pagina vuota!</span>';
		
		echo 	"<div>".
				'<small><a href="'.$edit_tag_link.$PAGE_TAGS[$i][1].'">Tag='.$PAGE_TAGS[$i][1].'</a></small>&nbsp;&nbsp;'.
				'<small><a href="'.$edit_page_link.$PAGE_TAGS[$i][2].'">Page='.$PAGE_TAGS[$i][2].'</a></small>&nbsp;&nbsp;'.
				'... <strong>'.$PAGE_TAGS[$i][0].'</strong> '.$empty_page.
				"</div>\n\n";
	}
	
	echo "<h3>TAG generici del sito</h3>\n";
	for($i=0; $i<count($BLOG_TAGS); $i++)
	{
		echo 	"<div>".
				'<small><a href="'.$edit_tag_link.$BLOG_TAGS[$i][1].'">Tag='.$BLOG_TAGS[$i][1].'</a></small>&nbsp;&nbsp;'.
				'... <strong>'.$BLOG_TAGS[$i][0].'</strong> '.
				"</div>\n\n";
	}
	
	echo "<h3>PAGINE generiche del sito</h3>\n";
	for($i=0; $i<count($BLOG_PAGES); $i++)
	{
		$empty_page="";
		if(in_array($BLOG_PAGES[$i][1], $EMPTY_PAGES_ids))
			$empty_page='<span style="color:#FF6600;">Pagina vuota!</span>';
		
		echo 	"<div>".
				'<small><a href="'.$edit_page_link.$BLOG_PAGES[$i][1].'">Page='.$BLOG_PAGES[$i][1].'</a></small>&nbsp;&nbsp;'.
				'... <strong>'.$BLOG_PAGES[$i][0].'</strong> '.$empty_page.
				"</div>\n\n";
	}
}


function admin_GalleryMetaFields()
{
	echo "<h2>Controllo campo 'Gallery'</h2>\n";
	
	$args = array(
		'post_type' => array('post','page'),
		'meta_key' => 'Gallery',
		'numberposts' => -1,
		'orderby' => 'id',
		'order' => 'asc'
	);
	$postslist = get_posts( $args );
	foreach ($postslist as $p)
	{
		echo '<p>'.$p->ID.' <a href="'.get_permalink($p->ID).'">'.$p->post_title.'</a></p>';
	}
}



function admin_RemoveVpcContatti()
{
	echo "<h2>Remove 'vpc-contatti'</h2>\n";
	echo "<p>Inserisci il parametro 'ok' nella url</p>\n";
	
	if(!isset($_GET['ok'])) return;
	
	$posts_array = get_posts( array( 
			'posts_per_page'=>-1 ,
			'post_type'=>'page', /*post,any*/
			'orderby' => 'title',
			'order' => 'ASC',
	 ) );
	
	$start_text = "<!--vpc-contatti-->";
	$end_text = "<!--END-vpc-contatti-->";
	$end_text_len = strlen($end_text);
	$emailbox_text = "<!--vpc-contatti-email-->";

	
	foreach($posts_array as $p)
	{
		$content = $p->post_content;
		$flag = false;
		
		$start_pos = stripos($content, $start_text);
		if(!($start_pos===false))
		{
			echo '<p><a target="_blank" href="'.get_bloginfo('url').'/wp-admin/post.php?post='.
					$p->ID.'&action=edit">'.$p->post_title."</a></p>";
			
			$end_pos = stripos($content, $end_text);
			if($end_pos===false)
			{
				/* elimino solo il tag <!--vpc-contatti--> */
				$content = str_ireplace($start_text, "", $content);
				echo 'No '.htmlentities($end_text)."<br />";
			}
			else
			{
				/* elimino tutto ciò che è incluso tra <!--vpc-contatti--> e <!--END-vpc-contatti--> */
				$content = substr_replace($content, "", $start_pos, $end_pos-$start_pos+$end_text_len);
			}
			$flag = true;
		}
		
		$emailbox_pos = stripos($content, $emailbox_text);
		if(!($emailbox_pos===false))
		{
			$content = str_ireplace($emailbox_text, "", $content);
			echo 'Replaced '.htmlentities($emailbox_text)."<br />";
			$flag = true;
		}
		
		if($flag)
			if(wp_update_post( array('ID'=>$p->ID,'post_content'=>$content))>0)
				echo 'Aggiornamento riuscito'."$p->ID<br />";
			else
				echo 'Errore durante l\'aggiornamento'."<br />";
		
		//if($flag) return;
	}
}


function admin_CleanPosts()
{
	echo "<h2>Clean Posts</h2>\n";
	echo "<p>Inserisci il parametro 'ok' nella url</p>\n";
	
	if(!isset($_GET['ok'])) return;

	/*$posts_array = get_posts( array(
			'posts_per_page'=>1 ,
			'post_type'=>'page',
			'include' => 493, //387 493,
	) );*/
	
	$posts_array = get_posts( array(
			'posts_per_page'=>-1 ,
			'post_type'=> array( 'post', 'page' ),
			'orderby' => 'title',
			'order' => 'ASC',
	) );
	
	foreach($posts_array as $p)
	{
		$content = $p->post_content;
		$flag = false;
		
		/* Sostituisce <!--gallery-nome--> con [vpc-gallery id="nome"] */
		while(1)
		{
			$start_pos = stripos($content, "<!--gallery-");
			if($start_pos===false) break;
			
			$end_pos = stripos($content, "-->",$start_pos+1);
			$start_pos = $start_pos+12;
			$nome = substr($content, $start_pos, $end_pos-$start_pos);
			
			$content = str_ireplace("<!--gallery-$nome-->", "\n[vpc-gallery id=\"$nome\"]\n", $content);
			$flag = true;
		}
		
		
		/* Sostituisce <!--...--> , <!--actlink--> , <!--gmap--> , <!--more--> , <!--adsense(*)--> */
		while(1)
		{
			$start_pos = stripos($content, "<!--");
			if($start_pos===false) break;
			$end_pos = stripos($content, "-->",$start_pos+1);
			
			$content = substr($content,0,$start_pos).substr($content,$end_pos+3);
			$flag = true;
		}
		
		if($flag)	echo '<p><a target="_blank" href="'.get_bloginfo('url').'/wp-admin/post.php?post='.
					$p->ID.'&action=edit">'.$p->post_title."</a></p>";

		
		/* Elimina \n multipli */
		$content = str_ireplace("\r", "", $content);
		$content = preg_replace('/[\n]{3,}/i',"\r\n\r\n",$content);
		
		
		if($flag)
			if(wp_update_post( array('ID'=>$p->ID,'post_content'=>$content))>0)
				echo 'Aggiornamento riuscito'."$p->ID<br />";
			else
				echo 'Errore durante l\'aggiornamento'."<br />";
		
		//if($flag) return;
	}
}





function admin_MailingList()
{
	echo "<h2>ACT Mailing List</h2>\n";
	
	global $VPC;
	
	$VPC->new_VPC_Business();
	
	$list = $VPC->Business->getMailingList();
	
	$verticalList = '<p>'.implode("<br/>", $list).'</p>';
	
	$textarea = '<p><textarea rows="20" cols="100" style="width:90%;">'.implode(", ", $list).'</textarea></p>';
	
	echo $verticalList;
	echo $textarea;
}





?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>Admin Features</title>
	<!--link rel="stylesheet" href="<?php echo bloginfo('template_url'); ?>/script/reset.css"-->
</head>
<body>
<?php

$links = array(

	'ACT Page Tag',
	'Gallery Meta Fields',
	'Remove vpc-contatti',
	'Clean posts',
	'Mailing List',
);


$ID = get_the_ID(); 
$this_link = get_permalink($ID);
for($i=0; $i<count($links); $i++) { echo '<p><a href="'.$this_link.'?pag='.($i+1).'">'.($i+1).') '.$links[$i].'</a></p>'."\n"; }

echo '<hr />';

if(!isset($_GET['pag']) || intval($_GET['pag'])<0 || intval($_GET['pag'])>(count($links)))
{
	die();
}
else
{
	if(intval($_GET['pag'])==1) admin_ACT_page_tags();
	elseif(intval($_GET['pag'])==2) admin_GalleryMetaFields();
	elseif(intval($_GET['pag'])==3) admin_RemoveVpcContatti();
	elseif(intval($_GET['pag'])==4) admin_CleanPosts();
	elseif(intval($_GET['pag'])==5) admin_MailingList();
}






?>
</body>
</html>