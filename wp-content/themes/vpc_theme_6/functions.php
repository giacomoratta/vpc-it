<?php

include_once("inc/VPC_Utility.class.php");
include_once("inc/VPC_HeadInfo.class.php");
include_once("inc/VPC_Theme.class.php");
include_once("inc/VPC_PostList.class.php");
include_once("inc/VPC_EV.class.php");

include_once("_BusinessData.php");
include_once("inc/VPC_Business.class.php");

include_once("_BannerData.php");
include_once("inc/VPC_Banner.class.php");

include_once("_MainMenuSlider.php");
include_once("_TouristInfo.php");



/**
 * Classe principale di gestione dell'intero tema e sue funzionalita'.
 * Realizzato con lo scopo di evitare l'utilizzo di variabili globali nel tema.
 * Infatti l'oggetto istanziato deve essere l'unico oggetto globale, contenente funzioni,<br/> dati, configurazioni, ecc.
 * Questo oggetto contiene i riferimenti a tutti gli altri oggetti delle classi particolari<br/> del tema: EV, Business, PostList, ecc.
 * 
 */
class VPC
{
	// Flags and Debug
	private $__offline=false;
	private $__is_home=null;
	
	// ID della pagina
	private $__page_id=-1;
	
	
	// OGGETTI
	
	/**
	 * @var VPC_Banner
	 */public $Banner = null;
	
	/**
	 * @var VPC_Business
	 */public $Business = null;
	
	/**
	 * @var VPC_HeadInfo
	 */public $HeadInfo = null;
	
	/**
	 * @var VPC_EV
	 */public $EV = null; /* VPC_EV */
	
	/**
	 * @var VPC_PostList
	 */public $PostList = null;
	
	/**
	 * @var VPC_Theme
	 */public $Theme = null;
	
	
	// Thumbnail
	public $thumb_script_url = "inc/timthumb.php?src=";
	public $thumb_postlist_size = array('wide'=>array(350,80), 'small'=>array(100,100));
	
	// Lista tag effettivi, non usati per attivita' commerciali
	public $blog_tags = array("animali","auto-moto","cerco-affitti","lavoro","pacchetti","sponsored","top","vendesi","video");
	
	// Slug dei tag
	public $TAG_Pack = 'pacchetti';
	public $TAG_Featured = 'featured';
	
	/*

	public $CAT_News = 1;
	public $CAT_Event = 13;
	public $CAT_Promo = 15;
	public $CAT_Hidden = 213;
	public $CAT_Photo = 65;
	
	public $PAGE_About = 8159;
	public $PAGE_BusinessPromo = 8160;
	public $PAGE_Event = 7088;
	public $PAGE_Promo = 7090;
	public $PAGE_News = 774;
	public $PAGE_Featured = 1348;
	public $PAGE_TouristInfo = 5234;
	public $PAGE_Photo = 3887;
	public $PAGE_Links = 1575;
	public $PAGE_Sleeping = 8148;
	public $PAGE_Eating = 8149;
	public $PAGE_SeaBeach = 8150;
	public $PAGE_ShoppingServices = 8158;
	
	*/
	
	public $CAT_News = 1;
	public $CAT_Event = 13;
	public $CAT_Promo = 15;
	public $CAT_Hidden = 213;
	public $CAT_Photo = 67;
	
	public $PAGE_About = 2;
	public $PAGE_BusinessPromo = 8877;
	public $PAGE_Event = 8878;
	public $PAGE_Promo = 8879;
	public $PAGE_News = 774;
	public $PAGE_Featured = 1348;
	public $PAGE_TouristInfo = 5234;
	public $PAGE_Photo = 3887;
	public $PAGE_Links = 1575;
	public $PAGE_Sleeping = 8880;
	public $PAGE_Eating = 8881;
	public $PAGE_SeaBeach = 8882;
	public $PAGE_ShoppingServices = 8883;
	public $PAGE_Privacy = 9180;
	
	// LANG
	public $LANG_month_names = array('','Gennaio','Febbraio','Marzo','Aprile','Maggio','Giugno','Luglio','Agosto','Settembre','Ottobre','Novembre','Dicembre'); 
	public $LANG_day_names = array('','Luned&igrave;','Marted&igrave;','Mercoled&igrave;','Gioved&igrave;','Venerd&igrave;','Sabato','Domenica');
	
	// Varie
	public $site_url;
	public $template_url;
	public $gmap_url = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyCpy7yZvpWCY0qoRDjyu1NFJ5xsfhUC_LQ';
	public $gmap_opz = array('id'=>'gmap', 'lat'=>40.264558, 'lng'=>17.8294, 'zoom'=>12);
	
	public $seo_title = ", nel Salento. Informazioni turistiche, eventi, offerte, promozioni, pacchetti, last minute, strutture ricettive, attivit&agrave; commerciali, servizi, news a Porto Cesareo, in provincia di Lecce, nel Salento.";
	
	public $seo_description = "Informazioni su Porto Cesareo, in provincia di Lecce, nel Salento. Aggiornamenti su notizie, eventi, musica live, offerte, promozioni, pacchetti, last minute, men&ugrave; turistici, musica live. Strutture ricettive, attivit&agrave; commerciali, servizi. Informazioni turistiche per trascorrere le vacanze a Porto Cesareo e nel Salento.";
	
	public $seo_keywords = "porto cesareo, lecce, salento, eventi, offerte, promozioni, pacchetti, informazioni turistiche, vacanze, spiaggia, mare, fotografie, video, annunci, blog, strutture ricettive, attivit&agrave; commerciali, associazioni, servizi";
	
	
	
	/**
	 * Stampa il video in evidenza
	 */
	function featured_video()
	{
		if($this->__offline) return;
		
		/*
		 * ATTENZIONE!!!
		 * Impostare src="" e data-href="..." per resposive.js
		 * Larghezza massima = 315px
		 *  
		 */
?>
<iframe width="315" height="250" src="" data-href="//www.youtube-nocookie.com/embed/_aDJVbLL3Hg" frameborder="0" allowfullscreen></iframe>
<?php
	}


	
	/**
	 *  Widget facebook vicino al logo
	 */
	function facebook_page_widget($large=true)
	{		
		$this->Theme->set_print_plain_js(function($large_widget)
		{
?>
			vpc_responsive_obj.set_large_fn( function(){ 

				$('#facebook_page_widget').html('<?php 

echo '<div class="fb-like-box"'.
' data-href="https://www.facebook.com/VisitPortoCesareo"'.
' data-colorscheme="light" data-show-faces="true" data-header="false" data-stream="false"'.
' data-width="'.($large_widget?'290':'250').'" data-height="'.($large_widget?'190':'60').'"'. 
' data-show-border="false"></div>';

				?>');
				
				if(window.FB!=undefined && window.FB!=null && typeof window.FB == 'object')
				window.FB.XFBML.parse(document.getElementById('facebook_page_widget'));
			});
<?php
		}, true /*onload*/, $large); // END - function set_print_plain_js()
	}
	
	
	
	/**
	 * Widget meteo nella sidebar
	 */ 
	function meteo_sidebar_widget()
	{
		if($this->__offline) return;
		echo '<!-- Inizio codice ilMeteo.it --><iframe width="300" height="298" scrolling="no" '.
		' frameborder="no" noresize="noresize" id="iframe_meteo_sidebar_widget" src="" data-href="http://www.ilmeteo.it/box/previsioni.php?'.
		'citta=5453&type=mps1&width=300&ico=3&lang=ita&days=2&font=Tahoma&fontsize=14&bg=FFFFFF&'.
		'fg=444444&bgtitle=FFFFFF&fgtitle=333333&bgtab=FFEF9C&fglink=904000"></iframe>'.
		'<!-- Fine codice ilMeteo.it -->';
	}
	
	
	
	/**
	 * Stampa il codice di monitoraggio di Google Analytics.
	 */
	function google_analytics()
	{
		if(is_user_logged_in() || $this->__offline) return;
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-8636780-4', 'auto');
  ga('send', 'pageview');

</script>
<?php
	}
	
	
	
	/**
	 * Stampa il codice del Facebook SDK
	 */
	function facebook_sdk()
	{
		if($this->__offline) return;
?>
<div id="fb-root"></div>
<script>
  window.fbAsyncInit = function() {
    FB.init({
      appId      : '1535708239974060',
      xfbml      : true,
      version    : 'v2.1'
    });
  };

  (function(d, s, id){
     var js, fjs = d.getElementsByTagName(s)[0];
     if (d.getElementById(id)) {return;}
     js = d.createElement(s); js.id = id;
     js.src = "//connect.facebook.net/it_IT/sdk.js";
     fjs.parentNode.insertBefore(js, fjs);
   }(document, 'script', 'facebook-jssdk'));
</script>
<?php
	}
	
	
	/**
	 * Restituisce un id valido della pagina.
	 * Il problema era che in pagine particolari come index e archive l'id non e' corretto!
	 * @return int - 0 se id non valido, altrimenti un id>0
	 */
	function get_the_ID()
	{
		if($this->__page_id>=0) return $this->__page_id;
	
		if(	$this->isHomepage() || is_home() || is_front_page() || is_archive() || is_search())
		{
			//echo '<!-- NO ID -->';
			return 0;
		}
		return max(0,get_the_ID());
	}
	
	
	
	/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */ 
 
 
 	// Costruttore
	function __construct() 
	{ 
		$this->site_url =  get_bloginfo('url')."/";
		$this->template_url = get_bloginfo('template_directory')."/";
		$this->thumb_script_url = $this->template_url.$this->thumb_script_url;
		
		$this->EV = new VPC_EV($this);
		$this->PostList = new VPC_PostList($this);
		$this->Theme = new VPC_Theme($this);
		$this->Banner = new VPC_Banner($this, ABSPATH.'vpc_banners/', $this->site_url.'vpc_banners/', !$this->__offline);
		$this->HeadInfo = new VPC_HeadInfo($this);
		
		add_filter('pre_get_posts',array($this, 'exclude_categories_filter'));
		
		$this->Shortcode_Clean();
		
		if($this->__offline) $this->gmap_url="";
		
		$this->get_the_ID(); /* precarica l'id della pagina */
	}
	
	function Shortcode_Clean()
	{
		add_shortcode( 'vpc-gallery', array($this, '__shortcode_cleaner') );
	}
	
	function Shortcode_Add()
	{
		add_shortcode( 'vpc-gallery', array($this->Theme, 'Shortcode_VpcGallery') );
	}
	
	function __shortcode_cleaner($atts) { return ""; }
	
	
	
	/**
	 * Imposta e manipola il titolo del sito.
	 * @return string
	 */
	function site_title()
	{
		$title = wp_title( '&raquo;', false, 'right' ).
			get_bloginfo('name').
			htmlentities($this->seo_title, ENT_QUOTES, 'UTF-8');
		
		return $title;
	}
	
	
	
	/**
	 * Imposta e manipola la descrizione della pagina.
	 * Usa il campo meta 'vpc_page_excerpt'.
	 * @return string
	 */
	function page_description()
	{
		$excerpt="";
		$limit=200;
		
		if($this->get_the_ID()>0)
		{
			if(is_page()) $excerpt = trim(get_post_meta($this->get_the_ID(),'vpc_page_excerpt',true));
			
			if( strlen($excerpt)<=0 ) $excerpt = trim(get_the_excerpt());
		}
		
		if(strlen($excerpt)>0)
		{
			$excerpt = str_ireplace(array("\n","\r","\t"), " ", $excerpt);
			$excerpt = VPC_Utility::cutStringByWords($excerpt, $limit);
			if($excerpt[strlen($excerpt)-1]!='.') $excerpt.=".";
			$excerpt.=" ";
		}
		
		$excerpt .= $this->seo_description;
		
		$excerpt = htmlentities($excerpt, ENT_QUOTES, 'UTF-8');
		
		return $excerpt;
	}
	
	
	
	
	// Crea oggetto delle attivita', quando effettivamente richiesto
	function new_VPC_Business()
	{
		if($this->Business!=null) return;
		$this->Business = new VPC_Business($this);
	}
	
	
	
	// Controlla che sia l'homepage
	function isHomepage()
	{
		if($this->__is_home!=null) return $this->__is_home;
		$this->__is_home=false;
		if(!is_home()) return $this->__is_home;
		
		$p1=pathinfo($_SERVER['SCRIPT_NAME']);
		if(strcasecmp(trim($p1['basename']),'index.php')!=0) return $this->__is_home;
		$p1['dirname'] = trim(preg_replace('/[\/\\\]+/i', "", $p1['dirname']));
		
		$p2=pathinfo($_SERVER['REQUEST_URI']);
		$p2['basename'] = trim($p2['basename']);
		
		if(strcasecmp($p1['dirname'],$p2['basename'])!=0) return $this->__is_home;
		
		return $this->__is_home=true;
		return $this->__is_home;
	}



 	/**
 	 * Imposta la URL della thumbnail
 	 * @param string $src - url originale dell'immagine
 	 * @param string $width - larghezza
 	 * @param string $height - altezza
 	 * @param string $quality - qualita' dell'immagine (da 1 a 100)
 	 * @param string $align - allineamento (c,t,l,r,tr,tl,b,br,bl)
 	 * @param string $zoomcrop - zoom e crop (0, 1, 2, 3)
 	 * @return string
 	 */
	function ThumbScriptURL($src,$width="",$height="",$quality="",$align="",$zoomcrop="")
	{
		/*
			HELP [TimThumb] http://www.binarymoon.co.uk/projects/timthumb/
		    ----------------------------------------------------------------
			Quality ... from 1 to 100
			
			Align
			- c : position in the center (this is the default)
			- t : align top
			- tr : align top right
			- tl : align top left
			- b : align bottom
			- br : align bottom right
			- bl : align bottom left
			- l : align left
			- r : align right
			
			ZoomCrop
			- 0	: Resize to Fit specified dimensions (no cropping)	
			- 1	: Crop and resize to best fit the dimensions (default)
			- 2	: Resize proportionally to fit entire image into specified dimensions, and add borders if required
			- 3	: Resize proportionally adjusting size of scaled image so there are no borders gaps
		*/

		/* Risolve il problema 'c' */
		$align = strtolower(trim($align));
		if( !in_array($align, array('t','l','r','tr','tl','b','br','bl'))) $align='';
		
		/* Risolve il problema del background nero con le GIF */
		$ext = trim(pathinfo($src, PATHINFO_EXTENSION));
		if(strtolower($ext)=='gif') return $src;
		
		return  $this->thumb_script_url.$src
				.(strlen($width)>0?"&amp;w=$width":"")
				.(strlen($height)>0?"&amp;h=$height":"")
				.(strlen($quality)>0?"&amp;q=$quality":"")
				.(strlen($align)>0?"&amp;a=$align":"")
				.(strlen($zoomcrop)>0?"&amp;zc=$zoomcrop":"");
	}
	
	
	
	
	
	/**
	 * Restituisce le informazioni sulla thumb associata al post indicato.
	 * L'array restituito ha i seguenti indici:
	 * - thumb ( [0]=url, [1]=larghezza, [2]=altezza, [3]=true se ridimensionata, false se originale)
	 * - align ( c, t, tr, tl, b, br, bl, l, r )
	 * - size  ( small, wide o stringa vuota)
	 * @param int $post_id - id del post di cui recuperare la thumb
	 * @return array (pieno o vuoto)
	 */
	function getPostThumbURL($post_id)
	{
		$thumb_id = get_post_meta($post_id, 'Thumbnail_ID', true);
		if(strlen($thumb_id)<=0) return array();
		
		$thumb_id_info = explode(" ",preg_replace('/[\s ]{2,}/i',' ',$thumb_id));
		if(strlen($thumb_id_info[0])<=0) return array();
		
		// Creo il campo per la dimensione, se non esiste
		// Serve per avere il campo settato per il codice che segue...
		if(!isset($thumb_id_info[2])) $thumb_id_info[2]="";
		
		// Check campo per allineamento
		if(!isset($thumb_id_info[1])) $thumb_id_info[1]="";
		else
		{
			$thumb_id_info[1] = strtolower(trim($thumb_id_info[1]));
			
			/*
			 * Se il campo che indica l'allineamento non ha i valori previsti
			 * si mette il contenuto nel campo che indica la dimensione,
			 * il cui valore verra' controllato dopo.
			 */
			if( !in_array($thumb_id_info[1], array('t','b','l','r','tr','tl','br','bl')) )
			{
				$thumb_id_info[2]=$thumb_id_info[1];
				$thumb_id_info[1]="";
			}
		}
		
		// Check campo per dimensione
		if(strcasecmp($thumb_id_info[2],'small')!=0) $thumb_id_info[2]="";
		
		// Ottengo i dati dell'immagine wordpress
		$thumb = wp_get_attachment_image_src(trim($thumb_id_info[0]), array(1000,1000));
		if($thumb===false) return array();

		return array('thumb'=>$thumb, 'align'=>$thumb_id_info[1], 'size'=>$thumb_id_info[2]);
	}
	
	
	
	
	
	/**
	 * Restituisce un array con le url degli id indicati nel campo 'Code4Gallery'.
	 * @param int $post_id - id del post
	 * @return array (vuoto o pieno)
	 */
	function getCode4GalleryURLs($post_id)
	{
		$urls = array();
		
		$thumb_id = get_post_meta($post_id, 'Code4Gallery', true);
		if(strlen($thumb_id)<=0) return $urls;
		
		$ids = explode(',',$thumb_id);
		if(count($ids)<=0) return $urls;
		
		foreach($ids as $id)
		{
			$thumb = wp_get_attachment_image_src(intval($id), array(1000,1000));
			if($thumb===false) continue;
			$urls[] = $thumb[0];
		}
		return $urls;
	}
	
	
	
	
	
	/** Esclude le categorie specificate, se non siamo in admin.
	 * @param object $query - oggetto wordpress della query attuale
	 * @return object $query modificato con le categorie escluse
	 */
	function exclude_categories_filter($query)
	{
		if($query->is_admin) return $query;
	
		// ESEMPIO ... $query->set('cat','-225,-213');
		$query->set('cat','-'.$this->CAT_Hidden);
	
		return $query;
	}
	
	
	
	
	
	/**
	 * Restituisce latitudine e longitudine
	 * @param int $id - id del post/page di cui ottenere le coordinate
	 * @return array - 2 elementi lat,lng (oppure vuoto se coordinate non trovate)
	 */
	function get_latlng($id)
	{
		$latlng = trim(get_post_meta( $id, 'GMap_LatLng', true ));
		if(strlen($latlng)<=0) return array();
		return explode(",", $latlng);
	}
	
	
	
	
	
	/**
	 * Restituisce lo slug di categoria da usare con "category_name" in WP_Query
	 * @param int $id - id della categoria
	 * @return string
	 */
	function get_cat_slug($id)
	{
		$cat = get_category( $id );
		if($cat==null) return "";
		return $cat->slug;
	}
	
	
	
	
	
	/**
	 * Stampa il link ad una pagina.
	 * @param int $id - id del post/page di cui ottenere il link
	 * @param string $label - etichetta personalizzata del link
	 * @param string $style - stile da applicare al tag 'a'
	 */
	function print_page_link($id, $label="", $style="")
	{
		if(strlen($style)>0) $style='  style="'.$style.'"';
		
		if(strlen($label)<=0):
			$p = get_post($id);
			if($p==null) return;
			echo '<a href="'.get_permalink($p->ID).'"'.$style.'>'.$p->post_title.'</a>';
			
		else:
			echo '<a href="'.get_permalink($id).'"'.$style.'>'.$label.'</a>';
		
		endif;
	}
	
	
	
} 
$VPC = new VPC(); 



add_filter('show_admin_bar', '__return_false');


?>