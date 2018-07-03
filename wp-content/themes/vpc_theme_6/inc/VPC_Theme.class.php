<?php


/**
 * Gestione del tema.
 *
 */
class VPC_Theme
{
	/**
	 * @var VPC
	 */private $vpc;
	
	private $__breadcrumb_max_length = 35;
	
	private $Print_SingleBreadcrumbs_Data = null;
	
	private $Print_PageBreadcrumbs_Data = null;
	
	private $Print_BusinessPageBreadcrumbs_Data = null;
	
	
	/**
	 * @var _MainMenuSlider
	 */private $MainMenuSlider_Data = null;
	
	
	/**
	 * Funzione che si occupa di stampare il menu' principale.
	 * @var function pointer
	 */private $main_menu_function = null;
	
	
	/**
	 * Funzione che si occupa di stampare le icone social.
	 * @var function pointer
	 */private $social_buttons_function = null;
	
	
	/**
	 * Funzione che si occupa di stampare il javascript plain text.
	 * @var function pointer
	 */private $plain_javascript_function = null;
	
	
	/**
	 * Funzione che si occupa di stampare l'area dopo contenuto e sidebar, e prima del footer.
	 * @var function pointer
	 */private $pre_footer_function = null;
	
	
	/**
	 * Array dei file javascript in coda per essere inclusi nell'HTML.
	 * @var array
	 */private $js_files = array();
	
	
	/**
	 * Array dei file css in coda per essere inclusi nell'HTML.
	 * @var array
	 */private $css_files = array();
	
	
	
	
	
	/**
	 * Classe principale della pagina.
	 * Viene stampata in header.php, nel primo tag 'section'.
	 * @var string
	 */public $page_class = "";
	
	
	/**
	 * Indica che e' stato stampato il codice di almeno una galleria.
	 * @var bool
	 */public $Shortcode_VpcGallery = false;
	
	
	
	
	
	/**
	 * Costruttore dell'oggetto Theme.
	 * @param object $VPC_Object riferimento all'oggetto della classe principale VPC
	 */
	function __construct($VPC_Object)
	{
		$this->vpc = $VPC_Object;
	}
	
	
	
	
	
	/**
	 * Imposta o stampa il menu' principale
	 * @param function pointer $fn - funzione per stampa menu'
	 * @param mixed $data - dati da passare alla funzione
	 */
	function main_menu($fn=null, $data=null)
	{
		if($fn==null || !is_callable($fn))
		{
			if($this->main_menu_function==null || !is_array($this->main_menu_function))
			{
				$this->main_menu_function=null;
			}
			else
			{
				$fn = $this->main_menu_function[0];
				$fn($this->main_menu_function[1]);
			}
			return;
		}
		$this->main_menu_function=array($fn,$data);
	}
	
	
	
	
	
	/**
	 * Imposta o stampa i pulsanti social
	 * @param function pointer $fn - funzione per stampa
	 * @param mixed $data - dati da passare alla funzione
	 */
	function social_buttons($fn=null, $data=null)
	{
		if($fn==null || !is_callable($fn))
		{
			if($this->social_buttons_function==null || !is_array($this->social_buttons_function))
			{
				$this->social_buttons_function=null;
			}
			else
			{
				$fn = $this->social_buttons_function[0];
				$fn($this->social_buttons_function[1]);
			}
			return;
		}
		$this->social_buttons_function=array($fn,$data);
	}
	
	
	
	
	
	/**
	 * Stampa i link per la condivisione sui social
	 * @param string $title - titolo pagina
	 * @param string $link - link pagina
	 */
	function share_on_socials($title,$link)
	{
?>
		<section class="social">
			<div class="label">Condividi!</div>
			<div class="button facebook"><a href="http://www.facebook.com/sharer.php?u=<?php echo $link; ?>" target="_blank">Facebook</a></div>
			<div class="button twitter"><a href="http://twitter.com/home/?status=<?php echo $title." ".$link; ?>" target="_blank">Twitter</a></div>
			<div class="clear"></div>
		</section>  
<?php
	}
	
	
	
	
	
	/**
	 * Prepara i dati per le breadcrumb dell'articolo singolo, e infine stampa.
	 * @param int $id - id del post/page di cui stampare le breadcumb
	 * @uses $this->Print_SingleBreadcrumbs_Data
	 * @uses $this->__print_breadcrumbs()
	 */
	function Print_SingleBreadcrumbs($id)
	{
		if($this->Print_SingleBreadcrumbs_Data!=null)
		{
			$this->__print_breadcrumbs($this->Print_SingleBreadcrumbs_Data);
			return;
		}
		
		$labels=array();
		$pages=array();
		
		// Categorie
		$array = wp_get_post_categories($id);
		foreach($array as $c)
		{
			$cat = get_category($c);
			if($cat==null) continue;
			$labels[] = $cat->slug;
		}
		
		// Tags
		$array = wp_get_post_tags($id);
		foreach($array as $t)
		{
			if($t==null) continue;
			$labels[] = $t->slug;
		}
		
		// Link pagine corrispondenti
		foreach($labels as $slug)
		{
			$x = get_page_by_path( $slug );
			if($x==null) continue;
			if($x->post_status!='publish') continue;
			$y = get_permalink($x->ID);
			if($y==false) continue;
			
			if(strlen($x->post_title)>$this->__breadcrumb_max_length)
				$x->post_title=substr($x->post_title,0,$this->__breadcrumb_max_length)."...";
			
			$pages[] = array($x->post_title,$y,$x->ID);
		}
		
		$this->Print_SingleBreadcrumbs_Data = $pages;
		$this->__print_breadcrumbs($this->Print_SingleBreadcrumbs_Data);
	}
	
	
	
	
	
	/**
	 * Prepara i dati per le breadcrumb della pagina semplice, e infine stampa.
	 * @param int $id - id del post/page di cui stampare le breadcumb
	 * @uses $this->Print_PageBreadcrumbs_Data
	 * @uses $this->__print_breadcrumbs()
	 */
	function Print_PageBreadcrumbs($id)
	{
		if($this->Print_PageBreadcrumbs_Data!=null)
		{
			$this->__print_breadcrumbs($this->Print_PageBreadcrumbs_Data);
			return;
		}
		
		$parent_ids = get_post_ancestors( $id );
		if(count($parent_ids)<=0) return;
		
		$parents_data = get_pages('include='.implode(",",$parent_ids));
		if(count($parent_ids)<=0) return;
		
		// Etichette delle pagine genitori
		foreach($parents_data as $p)
		{
			$labels[] = $p->post_name;
		}
	
		// Link pagine corrispondenti
		foreach($labels as $slug)
		{
			$x = get_page_by_path( $slug );
			if($x==null) continue;
			if($x->post_status!='publish') continue;
			$y = get_permalink($x->ID);
			if($y==false) continue;
				
			if(strlen($x->post_title)>$this->__breadcrumb_max_length)
				$x->post_title=substr($x->post_title,0,$this->__breadcrumb_max_length)."...";
				
			$pages[] = array($x->post_title,$y,$x->ID);
		}
	
		$this->Print_PageBreadcrumbs_Data = $pages;
		$this->__print_breadcrumbs($this->Print_PageBreadcrumbs_Data);
	}
	
	
	
	
	
	/**
	 * Prepara i dati per le breadcrumb della pagina semplice, e infine stampa.
	 * @param int $id - id del post/page di cui stampare le breadcumb
	 * @uses $this->Print_PageBreadcrumbs_Data
	 * @uses $this->__print_breadcrumbs()
	 */
	function Print_BusinessPageBreadcrumbs($id)
	{
		if($this->Print_BusinessPageBreadcrumbs_Data!=null)
		{
			$this->__print_breadcrumbs($this->Print_BusinessPageBreadcrumbs_Data);
			return;
		}
		
		$this->vpc->new_VPC_Business();
		if(!$this->vpc->Business->getBusinessCategories($id)) return;
		
		$keys = array_keys($this->vpc->Business->getBusinessCategories_data);
		if(count($keys)<=0) return;
		
		$links1 = array();
		$links2 = array();
		$anchors = array();
		
		foreach($keys as $k)
		{
			$x = get_page_by_path( $k );
			if($x==null) continue;
			$y = get_permalink($x->ID);
			if($y==false) continue;
			
			$links1[] = array($x->post_title,$y);
			$kArray = $this->vpc->Business->getBusinessCategories_data[$k];
			
			foreach($kArray as $ka)
			{
				if(in_array($ka[1],$anchors)) continue;
				$anchors[] = $ka[1];
				$links2[] = array($ka[0],$y."#".$ka[1]);
			}
		}
		$links = array_merge($links2,$links1);
		
		
		// Link pagine corrispondenti
		foreach($links as $lk)
		{
			if(strlen($lk[0])>$this->__breadcrumb_max_length)
				$lk[0]=substr($lk[0],0,$this->__breadcrumb_max_length)."...";
	
			$pages[] = array($lk[0],$lk[1],0);
		}
	
		$this->Print_BusinessPageBreadcrumbs_Data = $pages;
		$this->__print_breadcrumbs($this->Print_BusinessPageBreadcrumbs_Data);
	}
	
	
	
	
	
	/**
	 * Stampa l'html delle breadcrump.
	 * Viene chiamata dalle funzioni Print_XxxBreadcrumbs().
	 * @param array $pages - ogni elemento e' ancora un array: [0]=titolo, [1]=link.
	 */
	private function __print_breadcrumbs($pages)
	{
		if(!is_array($pages) || count($pages)<=0) return;
?>
		<section class="pagetags">
			<div class="label">Torna a...</div>
<?php 			foreach($pages as $p) : ?>
			<a href="<?php echo $p[1];?>" class="pagetag"><?php echo $p[0];?></a>
<?php 			endforeach; ?>
			<div class="clear"></div>
		</section>
<?php
	}
	
	
	
	
	/**
	 * Stampa i riquadri delle attivita' associate al post.
	 * 
	 */
	function Print_BusinessPages_SinglePost()
	{
		if($this->Print_SingleBreadcrumbs_Data==null) return;
		$this->vpc->new_VPC_Business();
		
		foreach($this->Print_SingleBreadcrumbs_Data as $p)
		{
			if(!$this->vpc->Business->isBusinessPage($p[2])) continue;
			
			$address = get_post_meta( $p[2], 'xVPC_Indirizzo', true );
			$tel = get_post_meta( $p[2], 'xVPC_Tel', true );
			$email = get_post_meta( $p[2], 'xVPC_Email', true );
			
			$thumb = $this->vpc->getPostThumbURL( $p[2] );
			$thumb['thumb'][0] = trim($thumb['thumb'][0]);
			
			$this_width = $this_height = $basic = 120; // vedi css
			
			$this_width = min($this_width,$thumb['thumb'][1]);
			$this_height = min($this_height,$thumb['thumb'][2]);
			
			$url = $this->vpc->ThumbScriptURL(
					$thumb['thumb'][0],
					$this_width, $this_height, // vedi css
					"90",
					strtolower($thumb['align']),
					"1");

			$background_position="";
			if($basic>$this_width && $basic>$this_height ) $background_position="background-position:left top;";
			elseif($basic>$this_width )  $background_position="background-position:left center;";
			elseif($basic>$this_height )  $background_position="background-position:center top;";

?>
	<section class="company">
	
		<?php if(strlen($thumb['thumb'][0])>0) :
		?><a href="<?php echo $p[1];?>" class="logo" style=" <?php echo $background_position;
		?>  background-image:url(<?php echo $url;?>); background-color:#FFFFFF;"></a><?php
		
		else : ?><a href="<?php echo $p[1];?>" class="logo" style="height:50px;"></a><?php endif; ?>
		
		<header class="name"><a href="<?php echo $p[1];?>"><?php echo $p[0];?></a></header>
		<div class="info">
			<?php if(strlen($address)>0) : ?><p><?php echo $address;?></p><?php endif; ?>
			<?php if(strlen($tel)>0) : ?><p><strong>Tel</strong>. <?php echo $tel;?></p><?php endif; ?>
			<?php if(strlen($email)>0) : ?><p><strong>Email</strong>: <?php echo $email;?></p><?php endif; ?>
		</div>
		<div class="clear"></div>
	</section>
<?php
		}
	}
	
	
	
	/**
	 * Stampa il menu' delle categorie in una pagina tematica.
	 *  
	 */
	function Print_ThematicPage_Categories($class="")
	{
		$thdata = $this->vpc->Business->getThematicListData_data;
		if(!is_array($thdata) || count($thdata)<=0) return;
		if(!is_array($thdata['cat']) || count($thdata['cat'])<=0) return;

?>
		<section class="<?php echo $class; ?>">        
			<h3 class="catnav_title">Categorie <small>(clicca per posizionarti nella sezione desiderata)</small></h3>
				<div class="catnav">
<?php 	
		$cnames = array_keys($thdata['cat']);

		foreach($cnames as $cname) : ?>
					<p><a href="<?php echo '#'.$thdata['cat'][$cname]['a']; ?>"><?php echo $cname; ?></a></p>
<?php 	endforeach; ?>
					<div class="clear"></div>
                </div>
        </section>
<?php
	}
	
	
	
	
	function Print_ThematicPage_Catbox()
	{
		$thdata = $this->vpc->Business->getThematicListData_data;
		if(!is_array($thdata) || count($thdata)<=0) return;
		if(!is_array($thdata['cat']) || count($thdata['cat'])<=0) return;
		if(!is_array($thdata['info']) || count($thdata['info'])<=0) return;
		
		$cnames = array_keys($thdata['cat']);

		foreach($cnames as $cname) : 
?>
		<div class="catbox" id="<?php echo $thdata['cat'][$cname]['a']; ?>">
			<h2><?php echo $cname; ?></h2>                
			<div class="catbox_wrapper">
<?php 			
			foreach($thdata['cat'][$cname]['data'] as $id) : 
			
				$this_act = $thdata['info'][$id];
?>
                <div class="actbox">
                	<h4><a href="<?php echo $this_act['link']; ?>"><?php echo $this_act['title']; ?></a></h4>
                    <p><small><?php echo $this_act['excerpt']; ?></small></p>
                    <p><?php echo $this_act['addr']; ?></p>
                    <?php if(strlen($this_act['tel'])>0) : ?><p>Tel. <?php echo $this_act['tel']; ?></p><?php endif; ?>
                </div>
<?php
			endforeach; 
?>
				<div class="clear"></div>
			</div>
		</div>

<?php	endforeach;
	}
	
	
	
	


	/**
	 * Stampa la lista di marker in formato json.
	 * @uses array $this->getThematicListData_data
	 */
	function Print_GmapMarkers_ThematicData()
	{
		$thdata = $this->vpc->Business->getThematicListData_data;
		if(!is_array($thdata) || count($thdata)<=0) return;
		if(!is_array($thdata['cat']) || count($thdata['cat'])<=0) return;
		if(!is_array($thdata['info']) || count($thdata['info'])<=0) return;
		
		if(!is_array($thdata) || count($thdata)<=0) return;
		$kids = array_keys($thdata['info']);
		foreach($kids as $kid)
		{
			if(count($thdata['info'][$kid]['map'])<=0) continue;
			if(!isset($thdata['info'][$kid]['cats'][0])) continue;
				
			$latlng = $thdata['info'][$kid]['map'];
			$color = $thdata['info'][$kid]['cats'][0]['color'];
	
			if(strlen($thdata['info'][$kid]['title'])>0)
				$txt = '<p class="title"><a href="'.$thdata['info'][$kid]['link'].
				'" target="_blank">'.$thdata['info'][$kid]['title'].'</a></p>';
	
			if(strlen($thdata['info'][$kid]['addr'])>0)
				$txt .= '<p class="addr">'.$thdata['info'][$kid]['addr'].'</p>';
	
			if(strlen($thdata['info'][$kid]['tel'])>0)
				$txt .= '<p class="tel">Tel. '.$thdata['info'][$kid]['tel'].'</p>';
	
			if(strlen($thdata['info'][$kid]['excerpt'])>0)
				$txt .= '<p class="excerpt">'.$thdata['info'][$kid]['excerpt'].'</p>';
	
			$txt = "'<div class=\"gmapinfowindow\">".str_ireplace("'","\'", $txt)."</div>'";
	
			?>
					{lat:<?php echo $latlng[0]; ?>, lng:<?php echo $latlng[1]; 
					?>, txt:<?php echo $txt; 
					?>, icon:<?php echo "'".$this->vpc->template_url."img/small_markers/mm_20_".$color.".png"."'";
					/*?>', shadow:'<?php echo $this->vpc->template_url."img/small_markers/mm_20_shadow.png"; 
					*/?>},
						
	<?php	
		}
	}	
	
	
	
	
	
	/**
	 * Stampa lo slider delle pagine principali.
	 * @param string $id - id del tag section (es. MainMenuSlider_orizz, MainMenuSlider_vert)
	 * @param string $class - classe del tag section
	 * @uses class _MainMenuSlider
	 */
	function MainMenuSlider($id, $class="")
	{
		// $id = MainMenuSlider_orizz | MainMenuSlider_vert
		
		if($this->MainMenuSlider_Data==null)
			$this->MainMenuSlider_Data = new _MainMenuSlider($this->vpc);
?>
<section id="<?php echo $id; ?>"<?php if(strlen($class)>0) echo ' class="'.$class.'"'; ?>>
	<div class="wrapper">

<?php 	foreach($this->MainMenuSlider_Data->cards as $card) :

		/* <div class="card" style="background-image:url('<?php echo $card['img']; ?>');"> */

		$img = VPC_Utility::random_array_element($card['photo']);
?>
		<div class="card lazy-imgback" data-href="<?php echo $img['url']; ?>">
        	<a class="text" href="<?php echo $card['link']; ?>">
            	<div class="title"><?php echo $card['title']; ?></div>
                <div class="summary"><?php echo $card['summary']; ?></div>
            </a>
<?php 		
			if(strlen($img['credit'])>0 ) : 
?>			<div class="photocredit"><a href="<?php echo $img['credit']; ?>" target="_blank" rel="nofollow">Photo Credit</a></div><?php 
			endif;
?>
        </div>
<?php  endforeach;		?>

    </div>
    <div class="nav-prev"></div>
    <div class="nav-next"></div>
</section>
<?php
	}
	
	
	
	function Shortcode_VpcGallery($atts)
	{
		//echo '<!--Shortcode_VpcGallery-->';
		$gal_flag = false;
		$meta_values = get_post_meta( get_the_ID(), 'Gallery', false );
		
		foreach($meta_values as $gallery)
		{
			$gallery = explode("\n", $gallery);
			if(trim($gallery[0])==trim($atts['id'])) { $gal_flag=true; break; }
		}
		if(!$gal_flag) return;
		if(stripos($gallery[1],"http://")>0 || stripos($gallery[1],"https://")>0) return;
		
		$size = explode(",",$gallery[1]);
		$size[0] = intval($size[0]);
		$size[1] = intval($size[1]);
		if($size[0]<=0 || $size[1]<=0) return;
		
		/* align */
		$atts['align'] = trim($atts['align']);
		if($atts['align']=='right') $atts['align']='float:right; margin-left:2em;';
		else if($atts['align']=='left') $atts['align']='float:left; margin-right:2em;';
		else $atts['align']='margin-left:auto; margin-right:auto;';

		ob_start();
		
		/* 
		 * nav-prev e nav-next ... width = intval($size[0]/6) 
		 * 
		 * */
?>
<section id="vpcgallery_slider_<?php echo trim($gallery[0]); ?>" class="vpcgallery_slider" style="<?php echo $atts['align']; ?> width:<?php echo $size[0]; ?>px; height:<?php echo $size[1]; ?>px;">
	<div class="wrapper" style="width:<?php echo $size[0]; ?>px; height:<?php echo $size[1]; ?>px;">

<?php	for($i=2; $i<count($gallery); $i++) : ?>
		<div class="card" style=" width:<?php echo $size[0]; ?>px; height:<?php echo $size[1]; ?>px; background-image:url('<?php echo trim($gallery[$i]); ?>');"></div>
<?php	endfor;	?>

    </div>
    <div class="nav-prev" style="height:<?php echo $size[1]; ?>px;"></div>
    <div class="nav-next" style="left:<?php echo intval($size[0]-50); ?>px; height:<?php echo $size[1]; ?>px;"></div>
</section>

<section class="vpcgallery_slider_replace">
<?php	for($i=2; $i<count($gallery); $i++) : ?>
	<img class="aligncenter size-full" width="<?php echo $size[0]; ?>" height="<?php echo $size[1]; ?>" src="<?php echo trim($gallery[$i]); ?>" />
<?php	endfor;	?>
</section>

<?php
//var_dump($meta_values);
		$this->Shortcode_VpcGallery = true;
		
		if(true)
			//echo '<!--VpcGallery_'.$data.'-->';
		$this->set_print_plain_js(function($data)
		{
?>
			$.plugin('VpcGallery_<?php echo $data; ?>', VPC_ContentSlider_Object);
			$("#vpcgallery_slider_<?php echo $data; ?>").vpc_contentslider({
				interval:3,
				textshadow_ie8:"1px 3px 1px #444444",
				textshadow_ie9:"2px 3px 2px #000000"
			});		
<?php
		}, true /*onload*/, trim($gallery[0]));
		
		return ob_get_clean();
	}
	
	
	
	
	
	/**
	 * Imposta la funzione da chiamare per stampare l'area pre-footer.
	 * @param function pointer $fn
	 * @example set_print_pre_footer( function(){ myprinter(); } )
	 */
	function set_print_pre_footer($fn)
	{
		$this->pre_footer_function = null;
		if($fn==null || !is_callable($fn)) return;
		$this->pre_footer_function = $fn;
	}
	
	
	/**
	 * Chiama la funzione per stampare l'area pre-footer.
	 */
	function call_print_pre_footer()
	{
		if($this->pre_footer_function==null) return;
		$fn = $this->pre_footer_function;
		$fn();
	}
	
	
	
	
	
	/**
	 * Imposta la funzione da chiamare per stampare il plain javascript.
	 * @param function pointer $fn
	 * @example set_print_plain_js( function(){ myprinter(); } )
	 */
	function set_print_plain_js($fn, $onload=false, $data=null)
	{
		if($fn==null || !is_callable($fn)) return;
		if(!is_array($this->plain_javascript_function)) $this->plain_javascript_function = array();
		$this->plain_javascript_function[] = array();
		$i = count($this->plain_javascript_function)-1;
		$this->plain_javascript_function[$i][0] = $fn;
		$this->plain_javascript_function[$i][1] = $onload;
		$this->plain_javascript_function[$i][2] = $data;
	}
	
	
	/**
	 * Chiama la funzione per stampare il plain javascript.
	 */
	function call_print_plain_js()
	{
		if(!is_array($this->plain_javascript_function)) return;
		
		$count_onload=0;
		
		// Stampa il javascript statico
		// Prima questo perche' potrebbe essere richiamato dal codice 'onload'.
		foreach($this->plain_javascript_function as $e)
		{
			if($e[1]) { $count_onload++; continue; }
			$fn = $e[0];
			if($e[2]!=null)	$fn($e[2]);
			else $fn();
		}
		
		// Stampa il javascript 'onload'
		if($count_onload>0)
		{
			
			//$( document ).ready(function() {
?>

$(window).load(function () {

	console.log("VisitPortoCesareo.it - Window.Load OK");
	
<?php
			foreach($this->plain_javascript_function as $e)
			{
				if(!$e[1]) { continue; }
				$fn = $e[0];
				if($e[2]!=null)	$fn($e[2]);
				else $fn();
			}
?>
});

<?php
		}
	}
	
	
	
	
	
	/**
	 * Aggiunge un file all'array contenitore.
	 * @param array $array privato dei file (es. $this->js_files).
	 * @param array $data con i dati del file da aggiungere.
	 * @param string $ie_cond - particolare dei commenti condizionali IE (es. "lt IE 9").
	 */
	private function add_css_js_file(&$array, $data, $ie_cond="")
	{
		if(strlen($ie_cond)<=0) $ie_cond="ALL";
		$ie_cond = preg_replace("/^[a-zA-Z0-9 \s]$/i", "", trim($ie_cond));
		
		$keys = array_keys($array);
		foreach($keys as $k)
		{
			for($i=0; $i<count($array[$k]); $i++)
			{
				if(strcasecmp($array[$k][$i][0],$data[0])==0)
					array_splice($array[$k], $i, 1);
			}
			if(count($array[$k])<=0) unset($array[$k]);
		}
		
		if(!isset($array[$ie_cond])) $array[$ie_cond]=array();
		$array[$ie_cond][]=$data;
	}
	
	
	/**
	 * Stampa i file dell'array contenitore.
	 * @param array $array privato dei file (es. $this->js_files).
	 * @param function $print_fn - funzione che gestisce il singolo dato passato in $data nella add_css_js_file.
	 */
	private function print_css_js_files($array, $print_fn)
	{
		$keys = array_keys($array);
		
		foreach($keys as $k)
		{
			if($k!="ALL") {
?>
<!--[if <?php echo $k; ?>]>
<?php				
			}
			
			foreach($array[$k] as $data)
			{
				$pos = stripos($data[0],'http');
				if($pos===false || $pos>0) $data[0]=$this->vpc->template_url.$data[0];
				$print_fn($data);
			}
				
			if($k!="ALL"){
?>
<![endif]-->
<?php				
			}
			echo "\n";
		}
	}
	
	
	/**
	 * Aggiunge un file javascript.
	 * Se esiste gia', viene eliminato quello vecchio e viene inserito il nuovo.
	 * @param string $file_name - nome del file javascript.
	 * @param string- $id - id del tag script per scopi responsive; aggiunge attributo 'data-href'.
	 * @param string $ie_cond - particolare dei commenti condizionali IE (es. "lte IE8").
	 */
	function add_js_file($file_name, $id="", $ie_cond="")
	{
		if(strlen($file_name)<=0) return;
		$this->add_css_js_file($this->js_files,array($file_name,$id),$ie_cond);
	}
	
	
	/**
	 * Aggiunge un file CSS.
	 * Se esiste gia', viene eliminato quello vecchio e viene inserito il nuovo.
	 * @param string $file_name - nome del file css.
	 * @param string $id - id del tag link per scopi responsive; aggiunge attributo 'data-href'.
	 * @param string $media - media per il quale e' stato fatto il file (es. 'print').
	 * @param string $ie_cond - particolare dei commenti condizionali IE (es. 'lte IE8').
	 */
	function add_css_file($file_name, $id="", $media="", $ie_cond="")
	{
		$this->add_css_js_file($this->css_files,array($file_name,$id,trim($media)),$ie_cond);
	}
	
	
	/**
	 * Stampa tutti i file javascript in coda.
	 */
	function print_js_files()
	{
		$this->print_css_js_files($this->js_files, function($data){

		$id=trim($data[1]);
		if(strlen($id)>0) $id = strtolower(preg_replace("/[^A-Za-z]*/i", "", trim($id)));
		
?>
<script <?php echo ((strlen($id)>0)?'id="'.$id.'" src="" data-href':'src'); ?>="<?php echo $data[0]; ?>"></script>
<?php
		});
	}
	
	
	/**
	 * Stampa tutti i file CSS in coda.
	 */
	function print_css_files()
	{
		$this->print_css_js_files($this->css_files, function($data){

			$id=trim($data[1]);
			if(strlen($id)>0) $id = strtolower(preg_replace("/[^A-Za-z]*/i", "", trim($id)));

			$file = TEMPLATEPATH."/".basename($data[0]);
			$lastmod = filemtime($file);
?>
<link <?php echo ((strlen($id)>0)?'id="'.$id.'" src="" data-href':'href'); ?>="<?php echo $data[0]."?".$lastmod;?>"<?php 

			if(strlen($data[2])) echo ' media="'.$data[2].'"';

?> rel="stylesheet" type="text/css">
<?php
		});
	}
	
	
	
	
	/**
	 * Stampa il blocco 'Abbonati alla Newsletter'!
	 * 
	 */
	function Print_feedNewsletter()
	{
?>
	<section id="feedNewsletter">
    
    	<header>Abbonati alla Newsletter<em>!</em></header>
        
        <article>
        	<p style="font-weight:500;font-size:1.15em;padding-bottom:0.4em;">Abbonati per ricevere giornalmente gli articoli del sito e conoscere sempre subito gli eventi, le offerte e i pacchetti turistici.</p>
        	<p>
        		<form action="http://feedburner.google.com/fb/a/mailverify" method="post" target="popupwindow" onSubmit="window.open('http://feedburner.google.com/fb/a/mailverify?uri=PortoCesareo', 'popupwindow', 'scrollbars=yes,width=550,height=520'); return true">
				<input type="text" style="width:60%;height:18px;vertical-align:middle;" name="email"/> 
				<input type="hidden" value="PortoCesareo" name="uri"/> 
				<input type="hidden" name="loc" value="en_US"/> 
				<input type="submit" value="Iscrivimi!" style="width:80px; height:26px; padding-bottom:3px; top:1px; vertical-align:middle;" />
                </form>
			</p>
            <p>Scrivi il tuo indirizzo e-mail e premi &quot;iscrivimi!&quot;.</p>
			<p>Si aprir&agrave; una finestra: segui tutte le istruzioni oppure leggi <a href="http://www.visitportocesareo.it/ricevere-articoli-post-via-email.html">questo articolo</a> che ti guider&agrave; passo passo alla procedura di abbonamento gratuito.</p>
			<p>In seguito potrai sempre cancellare la tua iscrizione, per terminare la ricezione di email.</p>
            <p style="font-size:0.85em;">Powered by <a href="http://feedburner.google.com" target="_blank">FeedBurner</a></p>
        </article>
    	
    </section>

<?php
	}
	
	
	
	/**
	 * Stampa il form wordpress dei commenti.
	 * @param array $strings - array delle stringhe usate nel form. Indici attesi:
	 * 			'title' - titolo del form (es. 'Scrivi qui il tuo commento'),
	 * 			'label' - testo del pulsante submit (es. 'Pubblica il commento')
	 * 			'msg' - messaggio inserito all'inizio del form
	 * @uses comment_form()
	 */
	function Print_commentForm($strings)
	{
		global $req;
?>
		<h2 class="comments_title"><?php echo $strings['title']; ?></h2>
	    
	    <section class="wp_comments_form">
<?php

		$commenter = wp_get_current_commenter();
		$req = get_option( 'require_name_email' );
		$aria_req = ( $req ? " aria-required='true'" : '' );
		
		if(strlen($strings['msg'])>0)
			$strings['msg']='<p style="font-weight:bold;">'.$strings['msg'].'</p>';
		
		$fields =  array(
		
		'author' =>
			"\n\t".'<div class="row">
				<label for="author">Nome * <small>(richiesto)</small></label>
				<input type="text" name="author" id="author" value="' .
				 esc_attr( $commenter['comment_author'] ) .
				'" size="22" tabindex="171" class="text" ' . $aria_req . ' />
			</div>',
		
		'email' =>
			"\n\t".'<div class="row">
				<label for="email">E-Mail * <small>(non verr&agrave; pubblicata, richiesta)</small></label>
				<input type="text" name="email" id="email" value="' . 
				esc_attr(  $commenter['comment_author_email'] ) .
				'" size="22" tabindex="172" class="text" ' . $aria_req . '/>
			</div>',
		
		'url' =>
			"\n\t".'<div class="row">
				<label for="url">Sito web <small>(opzionale)</small></label>
				<input type="text" name="url" id="url" value="' . 
				esc_attr( $commenter['comment_author_url'] ) .
				'" size="22" tabindex="173" class="text" />
			</div>',
		);
		
		//apply_filters( 'comment_form_default_fields', $fields );
		
		add_filter( 'comment_form_after', function(){ echo '<div class="clear"></div>'; } );
		add_filter( 'comment_form_before_fields', function(){ echo '<div class="rightrow">'; } );
		add_filter( 'comment_form_after_fields', function(){ echo '</div>'; } );
		
		comment_form(array(
		
		'title_reply' => '',
		
		'label_submit' => $strings['label'],
		
		'comment_notes_before' =>
				'<div class="msg">'.$strings['msg'].
				'<p>L\'indirizzo email non verr&agrave; pubblicato. I campi obbligatori sono contrassegnati (&#42;).</p>'.
				'</div>'."\n",
		
		'comment_notes_after' => '', /* default: elenco dei tag ammessi */
		
		'fields' => $fields,
		
		'comment_field' => 
				"\n\t".'<div class="row leftrow">
		            <label for="comment">Testo <small>(richiesto)</small></label>
		            <textarea name="comment" id="comment" cols="10" rows="10" tabindex="170" class="text"></textarea>
		        </div>',
		
		));
?>
	    </section>

<?php
	}
	
	
	
	
	/**
	 * Stampa il tag section contenente la mappa, con la classe 'map'.
	 * @param string $id - id da assegnare al tag section
	 * @param string $msg - messaggio di fallback
	 */
	function Print_GMap($id,$msg="")
	{
		if(strlen($msg)<=0)
			$msg =	"<p class=\"errormsg\">Il caricamento della mappa di Google non &egrave; andato a buon fine.</p>".
					"<p class=\"errormsg\">Fai un ultimo tentativo! Premi F5 per ricaricare completamente la pagina.</p>";
		
		echo '<section class="map" id="'.$id.'">'.$msg.'</section>';
	}	
	
}


?>