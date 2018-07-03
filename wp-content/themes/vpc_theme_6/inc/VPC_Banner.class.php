<?php


/**
 * Classe di gestione dei banner del sito.
 * 
 */
class VPC_Banner
{
	/**
	 * Oggetto della classe _BannerData, con banner personalizzati e adsense.
	 * @var _BannerData
	 */private $B;
	
	
	/**
	 * Riferimento all'oggetto della classe principale VPC.
	 * @var VPC
	 */private $vpc;
	
	
	/**
	 * Percorso assoluto della directory dei banner.
	 * @var string
	 */private $directory_path;
	
	
	/**
	 * URL della directory dei banner.
	 * @var string
	 */private $directory_url;
	
	
	/**
	 * Indica se i banner sono attivi.
	 * @var bool
	 */private $is_active;
	
	
	/**
	 * Indica l'indice del prossimo mini banner da stampare nella sidebar.
	 * @var int
	 */private $sidebar_mini_banner_counter=0;
	
	
	/* Array validi da passare a __get_b1 */
	private $article1_valids = array();
	private $sidebar1_valids = array();
	private $sidebar2_valids = array();
	
	
	
	
	
	/**
	 * Costruttore della classe che gestisce i banner.
	 * @param object $VPC_Object - riferimento all'oggetto della classe principale VPC
	 * @param string $dir - percorso assoluto della directory dei banner
	 * @param string $url - url della directory dei banner
	 * @param bool $off - true per spegnere i banner
	 */
	function __construct($VPC_Object, $dir, $url, $off=false)
	{
		$this->vpc = $VPC_Object;
		$this->B = new _BannerData();

		$this->directory_path=$dir;
		$this->directory_url=$url;
		$this->is_active = $off;
		
		$this->article1_valids = $this->__get_all_banners($this->B->article1);
		$this->sidebar1_valids = $this->__get_all_banners($this->B->sidebar1);
		$this->sidebar2_valids = $this->__get_all_banners($this->B->sidebar2);
	}
	
	
	
	
	
	/* * * * * * * * * * FUNZIONI PUBBLICHE DI STAMPA DEI BANNER * * * * * * * * * */
	
	
	
	function Article1() 
	{
		if(!$this->is_active) return;
		echo $this->__get_b1(array(),$this->B->adsense_article1,$this->article1_valids);
		
		$this->Small_Article1();
	}
	
	function Small_Article1()
	{
		if(!$this->is_active) return;
		if(count($this->article1_valids)>0) return;
		echo $this->B->adsense_small_article1;
	}

	
	
	function Sidebar1()
	{
		if(!$this->is_active) return;
		echo $this->__get_b1(array(),$this->B->adsense_sidebar1,$this->sidebar1_valids);
	}

	function Small_Sidebar1()
	{
		if(!$this->is_active) return;
		if(count($this->sidebar1_valids)>0) return;
		echo $this->B->adsense_small_sidebar1;
	}

	
	
	function Sidebar2()
	{
		if(!$this->is_active) return;
		echo $this->__get_b1(array(),$this->B->adsense_sidebar2,$this->sidebar2_valids);
	}
	
	function Small_Sidebar2()
	{
		if(!$this->is_active) return;
		if(count($this->sidebar2_valids)>0) return;
		echo $this->B->adsense_small_sidebar2;
	}
	
	
	
	function SidebarMini()
	{
		if(!$this->is_active) return;
		$valids = $this->__get_all_banners($this->B->sidebar_mini);
		if(count($valids)>0 && isset($valids[$this->sidebar_mini_banner_counter]))
		{
			$output = $valids[$this->sidebar_mini_banner_counter];
			/* counter update */ $this->sidebar_mini_banner_counter++;
			echo $this->__print_custom_banner($output,"");
		}
	}
	
	
	
	
	
	/**
	 * Seleziona un banner da quelli di $array.
	 * Se non ci sono banner da stampare viene restituito $replace.
	 * @param array $array - insieme dei banner personali
	 * @param string $replace - banner di default
	 * @param array $valids - (opzionale) banner gia' validi
	 * @return string - banner da stampare oppure stringa vuota
	 */
	private function __get_b1($array, $replace, $valids=array())
	{
		if(count($valids)<=0)
		{
			$valids = $this->__get_all_banners($array);
			if(count($valids)<=0) return $replace;
		}
		
		/* Prende un banner a caso tra quelli validi */
		$output = VPC_Utility::random_array_element($valids);
		if(count($output)<=0) return $replace;
		
		return $this->__print_custom_banner($output, $replace);
	}
	
	
	
	
	
	/**
	 * Restituisce il banner personale oppure quello sostitutivo.
	 * @param array $banner - info del banner personale
	 * @param string $replace - banner sostitutivo di default
	 * @return string - banner finale da stampare
	 */
	private function __print_custom_banner($banner, $replace)
	{
		$output = "";
		
		foreach($banner[0] as $b)
		{
			/* Controllo esistenza file immagine */
			if(!file_exists($this->directory_path.$b[0])) continue;
			
			/* Stampa banner personale */
			$size = getimagesize($this->directory_url.$b[0]);
			$output .= "\n\t".'<a id="'.$b[1].'" class="VPC_Banner" style="width:'.$size[0].'px;height:'.$size[1].'px;" '.
					'href="'.$banner[1].'" title="'.$banner[2].'" target="_blank">'.
					'<img src="'.$this->directory_url.$b[0].'" alt="'.$banner[2].'" title="'.$banner[2].'" width="'.
					$size[0].'" height="'.$size[1].'" /></a> ';
		}
		
		if(strlen($output)<=0) return $replace;
		return $output;
	}
	
	
	
	
	
	/**
	 * Convalida i banner passati in $array.
	 * Se non ci sono banner da stampare viene restituito un array vuoto.
	 * @param array $array - insieme dei banner personali da validare
	 * @return array - lista banner validi oppure array vuoto
	 */
	private function __get_all_banners($array)
	{
		if(!is_array($array)) return array();
		if(count($array)<=0) return array();
		$valids = array();

		foreach($array as $b)
		{
			/* Controllo che ogni elemento abbia i 5 campi */
			if(count($b)<5) continue;

			/* Recupero data e controllo */
			$start = explode("/",$b[0]);
			if(!checkdate($start[1],$start[0],$start[2])) continue;
			
			/* Calcolo giorni e controllo */
			$st_time = mktime(0,0,0,$start[1],$start[0],$start[2]);
			$today = mktime(0,0,0);
			$days = ($today-$st_time)/(24*60*60);
			if($days<0 || $days>($b[1]-1)) continue;

			/* Aggiunta banner a quelli da visualizzare */
			$valids[]=array($b[2],$b[3],$b[4]);
		}

		/* Lista banner visualizzabili (non scaduti) */
		return $valids; // potrebbe essere comunque vuoto
	}
}




?>