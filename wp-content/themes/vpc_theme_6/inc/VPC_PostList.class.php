<?php

//   http://codex.wordpress.org/Template_Tags/get_posts
//   http://codex.wordpress.org/Class_Reference/WP_Query



class VPC_PostList
{
	/**
	 * Riferimento all'oggetto della classe principale VPC.
	 * @var VPC
	 */private $vpc;
	
	
	/**
	 * ID dei post che man mano vengono aggiunti per evitare che altre funzioni<br/> di questa classe possano recuperare gli stessi post.
	 * @var array
	 */private $exclude=array();
	
	
	/**
	 * @var array
	 */private $lastPosts_data=array();
	
	
	/**
	 * @var array
	 */private $topPosts_data=array();
	
	
	/**
	 * @var array
	 */private $releatedPosts_data=array();
	
	
	/**
	 * @var array
	 */private $featuredPosts_data=array();
	
	
	/**
	 * @var array
	 */public $photomapTouristinfoPosts_data=array();
	
	
	
	/**
	 * Formato da usare nelle funzioni per le date, in get_the-date(), ecc.
	 * @var string
	 */public $date_basic_format = 'Y-m-d H:i:s';
	
	
	
	
	/**
	 * @var _TouristInfo
	 */private $TouristInfo_Data = null;
	
	
	
	
	
	/**
	 * Costruttore dell'oggetto PostList.
	 * @param object $VPC_Object riferimento all'oggetto della classe principale VPC
	 */
	function __construct($VPC_Object)
	{
		$this->vpc = $VPC_Object;
	}
	
	
	
	
	
	/**
	 * Resetta l'array degli id da escludere dalle query.
	 */
	function reset()
	{
		$this->exclude=array();
	}
	
	
	
	
	
	/**
	 * Formatta la data di un post nel formato semplice 'Y-m-d', per i tag html5 'time'.
	 * @param object $date - data dell'oggetto post
	 * @return string - data formattata come 'Y-m-d'
	 */
	function get_ISO8601_date($date)
	{
		$date = DateTime::createFromFormat($this->date_basic_format, $date);
		return date('c',$date->getTimestamp());
	}
	
	
	
	
	
	/**
	 * Formatta una data in formato leggibile umano 'd m Y'.
	 * @param object $date - data dell'oggetto post
	 * @return string - data formattata come 'd m Y'
	 */
	function get_text_date($date)
	{
		$date = DateTime::createFromFormat($this->date_basic_format, $date);
		$day = date('d',$date->getTimestamp());
		$month = $this->vpc->LANG_month_names[intval(date('m',$date->getTimestamp()))];
		$year = date('Y',$date->getTimestamp());
		return $day." ".$month." ".$year;
	}
	
	
	
	/**
	 * Taglia un excerpt troppo lungo.
	 * @para string $excerpt - excerpt da tagliare
	 * @return string - nuovo excerpt
	 */
	function cut_excerpt($excerpt, $maxlen=300)
	{
		if(strlen($excerpt)<$maxlen) return $excerpt;
		
		$newlen = stripos($excerpt, " ", $maxlen);
		
		return substr($excerpt, 0, $newlen)." [...]";
	}
	
	
	
	
	
	/**
	 * Ottiene gli ultimi post del blog, ordinati per data.
	 * @param int $num - numero di post da ottenere
	 * @return int - numero di post effettivamente ottenuti
	 * @uses array $this->exclude - aggiunge gli ID dei post ottenuti
	 * @uses array $this->topPosts_data
	 */
	function lastPosts($num=10)
	{
		$args = array(
			'posts_per_page'   => $num,
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'suppress_filters' => true,
			'exclude'          => implode(",",$this->exclude),
		);
		$this->lastPosts_data = get_posts( $args );
		for($i=0; $i<count($this->lastPosts_data); $i++) $this->exclude[]=$this->lastPosts_data[$i]->ID;
		return count($this->lastPosts_data);
	}
	
	
	
	
	
	/**
	 * Recupera i post con il tag slug 'top', ordinati per data.
	 * @param int $num - numero di post da ottenere
	 * @return int - numero di post effettivamente ottenuti
	 * @uses array $this->exclude - aggiunge gli ID dei post ottenuti
	 * @uses array $this->lastPosts_data
	 */
	function topPosts($num=10)
	{
		$args = array(
			'posts_per_page'   => $num,
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'suppress_filters' => true ,
			'tag_slug__in'	   => array( 'top' /*,'sponsored'*/ ),
			'exclude'          => implode(",",$this->exclude),
		);
		$postslist = get_posts( $args );
		$this->topPosts_data = array();
		
		// Controllo scadenza
		for($i=0; $i<count($postslist); $i++)
		{
			$duration = intval(get_post_meta($postslist[$i]->ID, 'Top_Duration', true))*24*3600;			
			$date = DateTime::createFromFormat($this->date_basic_format, $postslist[$i]->post_date);
			$diff = time()-$date->getTimestamp();
			if((time()-$date->getTimestamp())<=$duration) $this->topPosts_data[]=$postslist[$i];
		}
		
		for($i=0; $i<count($this->topPosts_data); $i++) $this->exclude[]=$this->topPosts_data[$i]->ID;
		return count($this->topPosts_data);
	}
	
	
	
	
	
	// COMMENTARE!!!
	function businessPageReleatedPosts($id, $num=100, $exclude_ids=array())
	{
		$page = get_post($id);
		if($page==null) return -1;
		
		$tag = trim($page->post_name);
		if(strlen($tag)<=0) return -1;
		
		$args = array(
				'posts_per_page'   => $num,
				'orderby'          => 'post_date',
				'order'            => 'DESC',
				'post_type'        => 'post',
				'post_status'      => 'publish',
				'suppress_filters' => true ,
				'tag_slug__in'	   => $tag,
				'exclude'          => implode(",",$this->exclude).",".implode(",",$exclude_ids),
		);
		$this->releatedPosts_data = get_posts( $args );
		for($i=0; $i<count($this->releatedPosts_data); $i++) $this->exclude[]=$this->releatedPosts_data[$i]->ID;
		return count($this->releatedPosts_data);
	}
	
	
	
	
	
	/**
	 * Recupera i post con il tag slug 'featured', ordinati per data.
	 * @param int $num - numero di post da ottenere
	 * @return int - numero di post effettivamente ottenuti
	 * @uses array $this->exclude - aggiunge gli ID dei post ottenuti
	 * @uses array $this->featuredPosts_data
	 */
	function featuredPosts($num=10)
	{
		$args = array(
			'posts_per_page'   => $num,
			'orderby'          => 'post_date',
			'order'            => 'DESC',
			'post_type'        => 'post',
			'post_status'      => 'publish',
			'suppress_filters' => true ,
			'tag_slug__in' => array( 'featured' ),
			'exclude'          => implode(",",$this->exclude),
		);
		$this->featuredPosts_data = get_posts( $args );
		for($i=0; $i<count($this->featuredPosts_data); $i++) $this->exclude[]=$this->featuredPosts_data[$i]->ID;
		return count($this->featuredPosts_data);
	}





	/**
	 * Recupera le informazioni turistiche o altri tipi di post, ordinate per titolo.
	 * @param int $page_id - id della pagina attuale
	 * @param bool $loadmap - (byRef) true indica che i dati devono essere visualizzati anche nella mappa
	 * @return int - numero di post effettivamente ottenuti
	 */	
	function photoMap_touristInfoPages($page_id, &$loadmap=false)
	{
		$this->photomapTouristinfoPosts_data = array('posts'=>array(), 'extra'=>array());
		$post_count = 0;
		$loadmap = false;
		$posts_per_page = -1;
		
		if($page_id==$this->vpc->PAGE_TouristInfo):
			$loadmap = true;
			$labels = array("Pagine pi&ugrave; recenti","Pagine pi&ugrave; vecchie");
			$args = array(
				'post_type' => 'page',
				'post_parent' => $this->vpc->PAGE_TouristInfo,
				'posts_per_page' => $posts_per_page,
				'orderby' => 'title',
				'order' => 'asc'
			);
		
		elseif($page_id==$this->vpc->PAGE_Photo):
			$labels = array("Foto e video pi&ugrave; recenti","Foto e video pi&ugrave; vecchi");
			$posts_per_page = 20;
			$args = array(
				'category_name' => $this->vpc->get_cat_slug($this->vpc->CAT_Photo),
				'post_type' => 'post',
				'posts_per_page' => $posts_per_page,
				'paged' => get_query_var('paged'),
				'orderby' => 'date',
				'order' => 'desc'
			);
		
		else:
			wp_reset_postdata(); 
			return $post_count;
		
		endif;
		
		wp_reset_postdata();
		$the_query = new WP_Query( $args );		
		
		if ( !$the_query->have_posts() ) { wp_reset_postdata(); return $post_count; }
		
		$this->photomapTouristinfoPosts_data['extra']['total_posts'] = intval($the_query->found_posts);
		$this->photomapTouristinfoPosts_data['extra']['posts_per_page'] = $posts_per_page;
		$this->photomapTouristinfoPosts_data['extra']['posts_displayed'] = intval($the_query->post_count);
		$this->photomapTouristinfoPosts_data['extra']['page_number'] = max(1,intval(get_query_var('paged')));
		$this->photomapTouristinfoPosts_data['extra']['nav_labels'] = $labels;
		
		while( $the_query->have_posts() )
		{
			$the_query->the_post();
			
			$latlng = array();
			if($loadmap) $latlng = $this->vpc->get_latlng( get_the_ID());
			if(count($latlng)<=0 && $loadmap) continue;
			
			/*
			 * PhotoGallery si distingue da TouristInfo dall'uso di Code4Gallery.
			 * Se ci sono id validi in Code4Gallery e' sicuramente un post destinato alla galleria delle foto.
			 * Altrimenti non e' detto!!!
			 * 
			 * */

			$thumblinks_forMap = array();
			$thumb_urls = $this->vpc->getCode4GalleryURLs(get_the_ID());
			if(count($thumb_urls)<=0)
			{
				$thumb_urls[] = $this->getPhotoPostThumbnail(get_the_ID(),360,200);
				if($loadmap) $thumblinks_forMap[] = $this->getPhotoPostThumbnail(get_the_ID(),250,120);
			}
			else
			{
				for($i=0;$i<count($thumb_urls);$i++)
				{
					$thumb_urls[$i] = $this->vpc->ThumbScriptURL($thumb_urls[$i],360,200,90,"",1);
					if($loadmap) $thumblinks_forMap[$i] = $this->vpc->ThumbScriptURL($thumb_urls[$i],250,120,90,"",1);
				}
			}
			if(count($thumb_urls)<=0) continue;
			
			
			for($i=0;$i<count($thumb_urls);$i++)
			{
				$this->photomapTouristinfoPosts_data['posts'][] = array(
						'id' => get_the_ID(),
						'title' => get_the_title(),
						'link' => get_permalink(get_the_ID()),
						'thumblink' => $thumb_urls[$i],
						'thumblink_forMap' => ($loadmap?$thumblinks_forMap[$i]:''),
						'latlng' => $latlng,
				);
			}
			
			$post_count++;
		}
		
		/* Restore original Post Data */
		wp_reset_postdata();
		
		return $post_count;
	}	
	

	
	
	
	
	/**
	 * Restituisce la thumbnail specificando se 'small' o 'wide'.
	 * @param int $post_id - ID del post di cui ottenere la thumbnail
	 * @return array - array('small',$url)/array('wide',$url) se c'e' la thumb, altrimenti array()
	 */
	function getThumbnail($post_id)
	{
		$info = $this->vpc->getPostThumbURL($post_id);
		if(count($info)<=0) return array();
		
		// Small
		if( $info['thumb'][1] < $this->vpc->thumb_postlist_size['wide'][0]  ||  strcasecmp($info['size'],'small')==0 )
		{
			$url = $this->vpc->ThumbScriptURL(
				$info['thumb'][0],
				$this->vpc->thumb_postlist_size['small'][0],
				$this->vpc->thumb_postlist_size['small'][1],
				"90",
				strtolower($info['align']),
				"1");
			return array('small',$url);				
		}
		
		// Wide
		else
		{
			$url = $this->vpc->ThumbScriptURL(
				$info['thumb'][0],
				$this->vpc->thumb_postlist_size['wide'][0],
				$this->vpc->thumb_postlist_size['wide'][1],
				"90",
				strtolower($info['align']),
				"1");
			return array('wide',$url);			
		}
		return array();
	}
	
	
	
	
	
	/**
	 * Restituisce la thumbnail di un post fotografico.
	 * @param int $post_id - ID del post di cui ottenere la thumbnail
	 * @return string - url della thumbnail
	 */
	function getPhotoPostThumbnail($post_id, $width, $height)
	{
		$info = $this->vpc->getPostThumbURL($post_id);
		if(count($info)<=0) return "";
		
		if( $info['thumb'][1] >= $width  || $info['thumb'][2] >= $height || strcasecmp($info['size'],'small')!=0 )
		{
			return $this->vpc->ThumbScriptURL(
					$info['thumb'][0],
					$width,
					$height,
					90,
					strtolower($info['align']),
					1);
		}
		return "";
	}
	
	
	
	
	
	/**
	 * Stampa un TopPost.
	 * @param int $id - id del post
	 * @param string $title - titolo del post
	 * @param string $excerpt - sintesi del post
	 */
	function Print_TopPost($id,$title,$excerpt)
	{
		$permalink = get_permalink($id);
		$thumbinfo = $this->getThumbnail($id);
		?>
			<article class="toppost">
				<div class="label"></div>
				<div class="wrapper">
<?php
				if(count($thumbinfo)>0) { if($thumbinfo[0]!="small") { ?>
				<a href="<?php echo $permalink; ?>"><img class="<?php echo $thumbinfo[0]; 
						?>" src="<?php echo $this->vpc->template_url; 
						?>img/ghost.gif" style="background-image:url('<?php echo $thumbinfo[1]; ?>');" /></a>
<?php			} } 
?>
				<h2><a href="<?php echo $permalink; ?>"><?php echo $title; ?></a></h2>
				<p><?php
				if(count($thumbinfo)>0) { if($thumbinfo[0]=="small") { ?>
				<a href="<?php echo $permalink; ?>"><img class="<?php echo $thumbinfo[0]; 
						?>" src="<?php echo $this->vpc->template_url; 
						?>img/ghost.gif" style="background-image:url('<?php echo $thumbinfo[1]; ?>');" /></a>
<?php			} }
				echo $this->cut_excerpt($excerpt).'<div class="clear"></div>'; ?></p>
				<div class="readmore"><a href="<?php echo $permalink; ?>">Leggi l'articolo completo...</a></div>
				</div>
			</article>
<?php
	}
	
	
	
	
	
	/**
	 * Stampa un BasicPost.
	 * @param int $id - id del post
	 * @param string $title - titolo del post
	 * @param string $excerpt - sintesi del post
	 * @param string $iso8601_date - data formattata iso8601 '2013-09-29T12:50:53+00:00'
	 * @param string $txt_date - data testuale da visualizzare
	 */
	function Print_BasicPost($id,$title,$excerpt,$iso8601_date,$txt_date)
	{
		$permalink = get_permalink($id);
		$thumbinfo = $this->getThumbnail($id);
?>
			<article class="basicpost">
				<time pubdate="pubdate" datetime="<?php echo $iso8601_date; ?>"><?php echo $txt_date; ?></time>
<?php
				if(count($thumbinfo)>0) { if($thumbinfo[0]!="small") { ?>
				<a href="<?php echo $permalink; ?>"><img class="<?php echo $thumbinfo[0]; 
					?>" src="<?php echo $this->vpc->template_url; 
					?>img/ghost.gif" style="background-image:url('<?php echo $thumbinfo[1]; ?>');" /></a>
<?php			} }
?>
				<h2><a href="<?php echo $permalink; ?>"><?php echo $title; ?></a></h2>
				<p><?php
				if(count($thumbinfo)>0) { if($thumbinfo[0]=="small") { ?>
				<a href="<?php echo $permalink; ?>"><img class="<?php echo $thumbinfo[0]; 
						?>" src="<?php echo $this->vpc->template_url; 
						?>img/ghost.gif" style="background-image:url('<?php echo $thumbinfo[1]; ?>');" /></a>
<?php			} }
				echo $this->cut_excerpt($excerpt).'<div class="clear"></div>'; ?></p>
				<div class="readmore"><a href="<?php echo $permalink; ?>">Leggi l'articolo completo...</a></div>
			</article>
<?php
	}
	
	
	
	
	/**
	 * Stampa un MinimalPost.
	 * @param int $id - id del post
	 * @param string $title - titolo del post
	 * @param string $excerpt - sintesi del post
	 * @param string $iso8601_date - data formattata iso8601 '2013-09-29T12:50:53+00:00'
	 * @param string $txt_date - data testuale da visualizzare
	 */
	function Print_MinimalPost($id,$title,$iso8601_date,$txt_date)
	{
		$permalink = get_permalink($id);
?>
		<article class="minimalpost">
			<h3><time pubdate="pubdate" datetime="<?php echo $iso8601_date; ?>"><?php echo $txt_date; 
			?></time> - <a href="<?php echo $permalink; ?>"><?php echo $title; ?></a></h3>
		</article>
<?php
	}
	
	
	
	
	/**
	 * Stampa un singolo articolo con foto grande.
	 * @param array $p - post singolo (chiavi attese: id,title,link,thumblink,thumblink_forMap,latlng)
	 */
	function Print_singlePhotoArticle($p)
	{
?>

				<article id="post-<?php echo $p['id'];  ?>"><a href="<?php echo $p['link']; 
					?>" style="background-image: url('<?php echo $p['thumblink']; ?>');">
					<div class="caption"><?php echo $p['title'];  ?></div>
				</a></article>
<?php
	}
	
	
	
	
	/**
	 * Stampa tutte le foto.
	 */
	function Print_photoMap()
	{
		$phdata = $this->vpc->PostList->photomapTouristinfoPosts_data['posts'];
		if(!is_array($phdata) || count($phdata)<=0) return;
		
		foreach($phdata as $p)
			$this->Print_singlePhotoArticle($p);
	}
	
	
	
	
	/**
	 * Stampa tutte le informazioni turistiche.
	 */
	function Print_touristInfoPages()
	{
		$phdata = $this->vpc->PostList->photomapTouristinfoPosts_data['posts'];
		if(!is_array($phdata) || count($phdata)<=0) return;
		
		if($this->TouristInfo_Data==null)
			$this->TouristInfo_Data = new _TouristInfo();
		
		/* Se non ci sono sezioni stampa una photomap */
		$keys = array_keys($this->TouristInfo_Data->array);
		if(!is_array($keys) || count($keys)<0)
		{
			$this->Print_photoMap();
			return;
		}

		
		/* Stampa le sezioni e le foto */
		$ids = array();
		foreach($keys as $k) :

			if(count($this->TouristInfo_Data->array[$k])<=0) continue;
			
			echo "\n\t\t\t".'<h2 class="boxes_title">'.$k.'</h2>';
			echo "\n\t\t\t".'<section class="boxes">';
			foreach($phdata as $p) :
				
				if(!in_array(intval($p['id']), $this->TouristInfo_Data->array[$k])) continue;
				$ids[] = intval($p['id']);
				$this->Print_singlePhotoArticle($p);
				
			endforeach;
			echo "\n\t\t\t".'</section><!-- .boxes -->'."\n\n";
		endforeach;

		
		/* Stampa tutte le info turistiche non presenti in alcuna sezione. */
		$other_p = true;
		foreach($phdata as $p)
		{
			if(!in_array(intval($p['id']),$ids))
			{
				if($other_p)
				{
					echo "\n\t\t\t".'<h2 class="boxes_title">Altri</h2>';
					echo "\n\t\t\t".'<section class="boxes">';
					$other_p = false;
				}
				$this->Print_singlePhotoArticle($p);
			}
		}
		if(!$other_p) echo "\n\t\t\t".'</section><!-- .boxes -->'."\n\n";

	}
		
	
	
	
	
	
	/**
	 * Stampa i marker di tutte le informazioni turistiche o altro.
	 */
	function Print_GmapMarkers_photoMap_touristInfoPages()
	{
		$phdata = $this->vpc->PostList->photomapTouristinfoPosts_data['posts'];
		if(!is_array($phdata) || count($phdata)<=0) return;
	
		foreach($phdata as $p)
		{
			$txt = '<p class="title"><a href="'.$p['link'].'">'.$p['title'].'</a></p>';
			$txt .= '<p class="thumb"><a href="'.$p['link'].'"><img src="'.$p['thumblink_forMap'].'" /></a></p>';
	
			$txt = "'<div class=\"gmapinfowindow\">".str_ireplace("'","\'", $txt)."</div>'";
	
?>
					{lat:<?php echo $p['latlng'][0]; ?>, lng:<?php echo $p['latlng'][1]; 
					?>, txt:<?php echo $txt; 
					?>, icon:<?php echo "'".$this->vpc->template_url."img/small_markers/mm_20_red.png"."'";
					/*?>', shadow:'<?php echo $this->vpc->template_url."img/small_markers/mm_20_shadow.png"; 
					*/?>},
								
<?php	
			}
		}
	
	
	

	
	
	
	
	/**
	 * Stampa un FeaturedPost.
	 * @param int $id - id del post
	 * @param string $title - titolo del post
	 * @param string $excerpt - sintesi del post
	 */
	function Print_FeaturedPost($id,$title,$excerpt)
	{
		$permalink = get_permalink($id);
		$thumbinfo = $this->getThumbnail($id);
?>
			<article class="basicpost">
<?php
				if(count($thumbinfo)>0) { if($thumbinfo[0]!="small") { ?>
				<a href="<?php echo $permalink; ?>"><img class="<?php echo $thumbinfo[0]; 
						?>" src="<?php echo $this->vpc->template_url; 
						?>img/ghost.gif" style="background-image:url('<?php echo $thumbinfo[1]; ?>');" /></a>
<?php			} }
?>
				<h2><a href="<?php echo $permalink; ?>"><?php echo $title; ?></a></h2>
				<p><?php
				if(count($thumbinfo)>0) { if($thumbinfo[0]=="small") { ?>
				<a href="<?php echo $permalink; ?>"><img class="<?php echo $thumbinfo[0]; 
						?>" src="<?php echo $this->vpc->template_url; 
						?>img/ghost.gif" style="background-image:url('<?php echo $thumbinfo[1]; ?>');" /></a>
<?php			} }
				echo $this->cut_excerpt($excerpt).'<div class="clear"></div>'; ?></p>
				<div class="readmore"><a href="<?php echo $permalink; ?>">Leggi l'articolo completo...</a></div>
			</article>
<?php
	}
		
		
		
		
		
	/**
	 * Stampa gli ultimi post.
	 * @uses $this->lastPosts_data
	 */
	function Print_LastPosts()
	{
		if(count($this->lastPosts_data)<=0) return;
?>
		<section class="posts_list lasts">
    
			<header>Ultimi articoli</header>
<?php		
			foreach($this->lastPosts_data as $p)
			{
				$this->Print_BasicPost($p->ID,$p->post_title,$p->post_excerpt,$p->post_date);
			}
?>
			<footer><a href="<?php echo get_permalink($this->vpc->PAGE_News); ?>">&laquo; Articoli e News meno recenti</a></footer>
		</section>
<?php
	}
	
	
	
	
	
	/**
	 * Stampa i post correlati.
	 * @uses $this->releatedPosts_data
	 */
	function Print_businessPageReleatedPosts($id)
	{
		if(count($this->releatedPosts_data)<=0) return;
		
		$page = get_post($id);
		if($page==null) return;
		$tag = trim($page->post_name);
		if(strlen($tag)<=0) return;
		
		$tagret = get_term_by('slug', $tag, 'post_tag');
		if($tagret === false) return;
		
		$taglink = get_tag_link($tagret->term_id);
		if(strlen($taglink)<=0) return;
?>
			<section class="posts_list releated">
	    
				<header>Articoli correlati</header>
<?php		
				foreach($this->releatedPosts_data as $p)
				{
					$this->Print_MinimalPost(
						$p->ID,$p->post_title,
						$this->get_ISO8601_date($p->post_date),
						$this->get_text_date($p->post_date)
					);
				}
	?>
				<footer><a href="<?php echo $taglink; ?>">&laquo; Tutti gli articoli correlati</a></footer>
			</section>
<?php
		}
	


	
	
	/**
	 * Stampa gli ultimi post e i post 'top', come previsto in homepage.
	 * @uses $this->lastPosts_data
	 * @uses $this->topPosts_data
	 */
	function Print_Homepage_LastPosts()
	{
		if(count($this->lastPosts_data)<=0) return;

		// Non si puo' uscire per questo. Non sempre ci sono 'top' post!
		// if(count($this->topPosts_data)<=0) return;
?>
		<section class="posts_list">
    
			<header>Ultimi articoli</header>
            
<?php		// TOP POSTS
			foreach($this->topPosts_data as $p)
			{
				$this->Print_TopPost($p->ID,$p->post_title,$p->post_excerpt);
			}
			
			// LAST POSTS
			foreach($this->lastPosts_data as $p)
			{
				$this->Print_BasicPost($p->ID,$p->post_title,$p->post_excerpt,
										$this->get_ISO8601_date($p->post_date),
										$this->get_text_date($p->post_date));
			} 
?>
			<footer><?php $this->vpc->print_page_link($this->vpc->PAGE_News,'&laquo; Articoli e News meno recenti'); ?></footer>
		</section>
<?php
	}
	
	
	
	
	
	/**
	 * Stampa i post 'featured', come previsto in homepage.
	 * @uses $this->featuredPosts_data
	 * @uses $this->vpc->featured_video
	 */
	function Print_Homepage_FeaturedPosts()
	{
		if(count($this->featuredPosts_data)<=0) return;
?>
		<section class="posts_list home_featured">
			<header>
				Da non perdere<em>!</em>
				<div class="all"><?php $this->vpc->print_page_link($this->vpc->PAGE_Featured,'Tutti gli articoli da non perdere &raquo;'); ?></div>
				<div class="clear"></div>
			</header>
    
			<div class="border">
				<article class="basicpost video"><?php $this->vpc->featured_video(); ?></article>
<?php
				$i=0; foreach($this->featuredPosts_data as $p)
				{
					$this->Print_FeaturedPost($p->ID,$p->post_title,$p->post_excerpt);
					if($i%2==0) echo '<div class="clear sep"></div>';
					$i++;
				}
?>
				<div class="clear"></div>
			</div><!-- .border -->
		</section>
<?php
	}
	
	
	
	
	
	
	
	
	
	function Print_PageNavigation($prev="Pagina precedente",$next="Pagina successiva")
	{
?>
		<nav class="pages">
       		<div class="L"><?php previous_posts_link('&laquo;&nbsp; '.$prev); ?></div>
        	<div class="R"><?php echo next_posts_link($next.' &nbsp;&raquo;'); ?></div>
        	<div class="clear"></div>
		</nav>
<?php
	}
	
	
	/**
	 * Stampa i link di navigazione.
	 * Ci si aspetta che l'array $info abbia i seguenti indici:
	 * - 'page_number': numero della pagina attuale
	 * - 'posts_per_page': numero di post per ogni pagina
	 * - 'posts_displayed': numero di post attualmente visualizzati
	 * - 'total_posts': numero di post totali corrispondenti alla query attuale
	 * - 'nav_labels': etichette dei link ([0]=>prev, [1]=>next) 
	 * @param array $info
	 */
	function Print_Custom_PageNavigation($info)
	{		
		$printL = false;
		$printR = false;
		
		if($info['page_number']>1) $printL=true;
		if(((($info['page_number']-1)*$info['posts_per_page'])+$info['posts_displayed'])<$info['total_posts']) $printR=true;
		
		$link = get_permalink(get_the_ID()).'/page/';
?>
		<nav class="pages">
       		<?php if($printL) : ?><div class="L"><a href="<?php echo $link.($info['page_number']-1); ?>"><?php echo $info['nav_labels'][0]; ?></a></div><?php endif; ?>
        	<?php if($printR) : ?><div class="R"><a href="<?php echo $link.($info['page_number']+1); ?>"><?php echo $info['nav_labels'][1]; ?></a></div><?php endif; ?>
        	<div class="clear"></div>
		</nav>
<?php
	}
	
	
	

}


?>