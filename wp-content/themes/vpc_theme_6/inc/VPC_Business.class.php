<?php


/**
 * Gestione delle pagine attivita' e operazioni correlate.
 * 
 */
class VPC_Business
{
	/**
	 * Oggetto della classe _BusinessData, con le pagine attivita' divise in categorie,
	 * e altri dati, opzioni, configurazioni, ecc.
	 * @var _BusinessData 	 
	 */private $B;
	
	
	/**
	 * Riferimento all'oggetto della classe principale VPC.
	 * @var object
	 */private $vpc;
	
	
	
	
	
	/**	
	 *  @var string	 
	 */public $getPostBusinessPosition_data = "";
	
	
	/**
	 * @var array
	 */public $getPostBusiness_data = array();
	
	
	/**
	 * @var array
	 */public $getBusinessCategories_data = array();
	
	
	/**
	 * @var array
	 */public $getThematicListData_data = array();
	
	
	
	
	
	/** 
	 * Costruttore dell'oggetto Business.
	 * @param object $VPC_Object riferimento all'oggetto della classe principale VPC
	 */
	function __construct($VPC_Object)
	{
		$this->vpc = $VPC_Object;
		$this->B = new _BusinessData();
	}
	
	
	
	
	
	/** Controlla che l'id passato sia presente nell'array delle pagine attivita'.
	 *  @param int $page_id - id di un post di tipo 'page'
	 *  @return bool - true se viene trovato, altrimenti false
	 */
	function isBusinessPage($page_id)
	{
		$keys = array_keys($this->B->array);
		foreach($keys as $k)
			if(in_array($page_id,$this->B->array[$k])) return true;
		return false;
	}
	
	
	
	
	
	/** Cerca la posizione della pagina attivita' associata al post passato.
	 *  Utile per il plugin GMap_Box.
	 *  @param int $post_id - id di un post di tipo 'post'
	 *  @uses string $this->getBusinessPosition_data
	 *  @return bool - true se vengono trovate delle coordinate
	 */
	function getPostBusinessPosition($post_id)
	{
		$this->getPostBusinessPosition_data = "";
		$tags_array = wp_get_post_tags($post_id);
		/*
			Ottengo tutti i tag del post.
			Controllo per ognuno se esiste una pagina con lo stesso slug.
			Eventualmente controllo che sia effettivamente una pagina attivita'.
			Appena trova delle coordinate valide la funzione ritorna.
		*/
		foreach($tags_array as $t)
		{
			$page = get_page_by_path($t->slug);
			if($page==null) continue;
			
			// Controlla che sia effettivamente una pagina attivita'
			//if(!$this->isBusinessPage($page->ID)) continue;
			
			$this->getPostBusinessPosition_data = trim(get_post_meta( $page->ID, 'GMap_LatLng', true ));
			if(strlen($this->getPostBusinessPosition_data)<=0) continue;
			
			// Appena trova le coordinate, la ricerca si ferma
			break;
		}
		if(strlen($this->getPostBusinessPosition_data)<=0) return false;
		return true;
	}
	
	
	
	
	
	/** Cerca tutte le pagine attivita' associate all'articolo.
	 *  @param int $post_id - id di un post di tipo 'post'
	 *  @uses array $this->getPostBusiness_data (id di pagina)
	 *  @return bool - true se ci sono dei dati
	 */
	function getPostBusiness($post_id)
	{
		$this->getPostBusiness_data = array();
		$tags_array = wp_get_post_tags($post_id);
		/*
			Ottengo tutti i tag del post.
			Controllo per ognuno se esiste una pagina con lo stesso slug.
			Eventualmente controllo che sia effettivamente una pagina attivita'.
			Man mano raccoglie gli id di pagina e ritorna l'array.
		*/
		foreach($tags_array as $t)
		{
			$page = get_page_by_path($t->slug);
			if($page==null) continue;
			
			// Controlla che sia effettivamente una pagina attivita'
			if(!$this->isBusinessPage($page->ID)) continue;
			
			$this->getPostBusiness_data[]=$page->ID;
		}
		if(count( $this->getPostBusiness_data )<=0) return false;
		return true;
	}
	
	
	
	
	
	/** Trasforma il nome di una categoria in una stringa pulita da mettere nella url.
	 *  @param int $k - nome categoria (chiave) da trasformare
	 *  @return string - nome pulito.
	 */
	function key2anchor($k)
	{
		$s = strtolower($k);
		$s = preg_replace("/[^a-z\s ]/i","",$s);
		$s = preg_replace("/[\s ]+/i","-",$s);
		return $s;
	}
	
	
	
	
	
	/** Restituisce la lista di pagine tematiche con all'interno le categorie alle quali appartiene la pagina dell'id passato.
	 *  @param int $page_id - id di un post di tipo 'page'
	 *  @uses array $this->getBusinessCategories_data
	 *  @return bool - true se ci sono dei dati 
	 */
	function getBusinessCategories($page_id)
	{
		$this->getBusinessCategories_data = array();
		
		/* key pagine tematiche */ $keys_th = array_keys($this->B->thematic);
		foreach($keys_th as $kth)
		{
			/* key pagina per l'array da ritornare */
			$kth4array = $this->B->thematic[$kth][0];
			
			foreach($this->B->thematic[$kth][1] as $k)
			{
				/* cerca */ if(!in_array($page_id,$this->B->array[$k])) continue;
				
				/* conversione chiave per array */
				$k2a = $this->key2anchor($k);
				
				if(!isset($this->getBusinessCategories_data[$kth]))
					$this->getBusinessCategories_data[$kth4array]=array();
				
				$this->getBusinessCategories_data[$kth4array][] = array($k,$k2a);
			}			
		}
		if(count( $this->getBusinessCategories_data )<=0) return false;
		return true;
	}
	
	

	
	/**
	 * Restituisce i dati per la pagina tematica. 
	 * @param int $key - indice intero (0...n) dell'array $thematic di _BusinessData.php
	 * @uses array $this->getThematicListData_data
	 * @return bool - true se ci sono dei dati 
	 */
	function getThematicListData($key)
	{
		$this->getThematicListData_data=array();
		
		if(!isset($this->B->thematic[$key])) return false;
		
		$array = array('cat'=>array(), 'info'=>array(), 'markers'=>array());
		
		/* Scorro le categorie nell'array tematico */
		foreach($this->B->thematic[$key][1] as $cat)
		{
			if(count($this->B->array[$cat])<=0) continue;
			
			$array['cat'][$cat] = array( 'a'=>$this->key2anchor($cat), 'data'=>array() );
			
			/* Colori marker */
			$marker_color = $this->B->thematic_config['marker_colors']['default'];
			if(isset($this->B->thematic_config['marker_colors']['cats'][$cat]))
				$marker_color = $this->B->thematic_config['marker_colors']['cats'][$cat];
			
			/* Scorro tutti gli id della categoria */
			foreach($this->B->array[$cat] as $id)
			{
				$array['cat'][$cat]['data'][] = $id;
				$array['info']["$id"]['cats'][] = array(
					'data'=>array($cat, $this->key2anchor($cat)),
					'color'=>$marker_color);
				
				if(!in_array($marker_color,$array['markers'])) $array['markers'][]=$marker_color;
			}
			
			
			/* Recupero info pagine */
			$args = array(
					'sort_order' => 'ASC',
					'sort_column' => 'post_title', // ID, post_title, post_date, post_modified
					'hierarchical' => 0,
					'include' => implode(",",$this->B->array[$cat]),
					'number' => '',
					'post_type' => 'page',
					'post_status' => 'publish'
			);
			$pages = get_pages($args);
			if(!is_array($pages) || count($pages)<=0) return false;
			
			
			/* Imposto le informazioni */
			$special_chars = array('/[\x0A\x0D]+/','/[\x00-\x09\x0B\x0C\x0E-\x1F]+/');
			$replace_special_chars = array('; ','');
			$array['cat'][$cat]['data'] = array();
			foreach($pages as $p)
			{
				$array['info']["$p->ID"]['title'] = $p->post_title;
				$array['info']["$p->ID"]['link'] = get_permalink($p->ID);
				
				$array['info']["$p->ID"]['excerpt'] = trim(get_post_meta($p->ID, 'vpc_page_excerpt',true));
				$array['info']["$p->ID"]['excerpt'] = strip_tags($array['info']["$p->ID"]['excerpt']);
				$array['info']["$p->ID"]['excerpt'] = VPC_Utility::cutStringByWords($array['info']["$p->ID"]['excerpt'],120);
				$array['info']["$p->ID"]['excerpt'] = preg_replace($special_chars, $replace_special_chars, $array['info']["$p->ID"]['excerpt']);
				
				$array['info']["$p->ID"]['map'] = $this->vpc->get_latlng($p->ID);
				
				$array['info']["$p->ID"]['addr'] = trim(get_post_meta($p->ID, 'xVPC_Indirizzo', true ));
				$array['info']["$p->ID"]['addr'] = strip_tags($array['info']["$p->ID"]['addr']);
				$array['info']["$p->ID"]['addr'] = preg_replace($special_chars, $replace_special_chars, $array['info']["$p->ID"]['addr']);
				
				$array['info']["$p->ID"]['tel'] = trim(get_post_meta($p->ID, 'xVPC_Tel', true ));
				$array['info']["$p->ID"]['tel'] = strip_tags($array['info']["$p->ID"]['tel']);
				$array['info']["$p->ID"]['tel'] = preg_replace($special_chars, $replace_special_chars, $array['info']["$p->ID"]['tel']);
				
				$array['cat'][$cat]['data'][] = $p->ID;
			}
		}
		$this->getThematicListData_data = $array;
		if(count($array)<=0) return false;
		return true;
	}
	
	
	
	
	
	/**
	 * Ottiene la lista delle email di tutte le attivita'.
	 * @return array di tutte le email
	 */
	function getMailingList()
	{
		$mailingList = array();
		
		foreach($this->B->array as $ak)
		{
			foreach($ak as $id)
			{
				$email = trim(get_post_meta($id,'xVPC_Email',true));
				if(strlen($email)<=0) continue;
				$emails = explode(",", $email);
				
				for($i=0; $i<count($emails); $i++)
				{
					$x = explode(" ",trim($emails[$i]));
					if(strlen($x[0])<=0) continue;
					$x[0]= trim($x[0]);
					
					if(!in_array($x[0],$mailingList)) $mailingList[] = $x[0];
				}
				
				//$mailingList[] = 
			}
		}
		return $mailingList;
	}
	
}







?>