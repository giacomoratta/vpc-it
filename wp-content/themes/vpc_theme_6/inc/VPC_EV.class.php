<?php


/**
 * Gestione dei post in calendario e operazioni correlate.
 * @uses $ec3, $wpdb - oggetti globali di wordpress e plugin vari
 * 
 */
class VPC_EV
{
	/**
	 * Riferimento all'oggetto della classe principale VPC.
	 * @var VPC
	 */private $vpc;
	
	
	
	
	
	/**
	 * @var array
	 */	public $GetData_data = array();
	
	
	
	
	
	/** 
	 * Costruttore dell'oggetto EV.
	 * @param object $VPC_Object riferimento all'oggetto della classe principale VPC
	 */
	function __construct($VPC_Object)
	{
		$this->vpc = $VPC_Object;
	}
	
	
	
	
	
	/**
	 * Formatta la data dell'evento.
	 * @param object $ev - oggetto di un singolo evento
	 * @param bool $noH - true per stampare anche l'ora
	 * @return string - data formattata
	 * 
	 * @uses 'EV_List_CustomDate' custom meta field di wordpress
	 */
	private function __formatDate($ev, $noH) 
	{ 
		$return = "";
			
		$customDate = get_post_meta($ev->post_id,'EV_List_CustomDate',true); 
		if(strlen($customDate)>0) return $customDate;
		
		
		$start_mktime = mktime($ev->s_hour,$ev->s_minute,0,$ev->s_month,$ev->s_day,$ev->s_year); 
		$end_mktime = mktime($ev->e_hour,$ev->e_minute,0,$ev->e_month,$ev->e_day,$ev->e_year); 
		$start_dayname = $this->vpc->LANG_day_names[intval(date('N',$start_mktime))]; 
		$end_dayname = $this->vpc->LANG_day_names[intval(date('N',$end_mktime))]; 
  
  		$start_day = $ev->s_day.($ev->s_month!=$ev->e_month?" ".$this->vpc->LANG_month_names[$ev->s_month]:''); 
		$end_day = $ev->e_day." ".$this->vpc->LANG_month_names[$ev->e_month];
			
		$start_time = date('H.i',$start_mktime);
		$end_time = date('H.i',$end_mktime);

		// PARTE 1
		if($start_day!=$end_day) $return .= 'da '; 
		$return .= $start_dayname." ".$start_day; 
			
		if(!$noH) 
		{
			if($start_day!=$end_day  && $start_time!=$end_time)
				$return .= ' ore '.$start_time;
			elseif($start_day==$end_day && $start_time!=$end_time)
				$return .= ', dalle ore '.$start_time;
			elseif($start_day==$end_day && $start_time==$end_time)
				$return .= ', ore '.$start_time; 
		}
		
		// PARTE 2 
		if($start_day!=$end_day)
			$return .= ' a '.$end_dayname." ".$end_day; 
			
		if(!$noH && $start_time!=$end_time) 
		{
			if($start_day!=$end_day)
				$return .= ' ore '.$end_time;
			elseif($start_day==$end_day)
				$return .= ' alle ore '.$end_time;
		} 
			
		// PARTE 3
		if(!$noH && $start_day!=$end_day && $start_time==$end_time)
			$return .= ', ore '.$end_time;
 
		return $return;
	}
	
	
	
	

	/**
	 * Controlla che il post passato per ID abbia almeno un tag il cui slug corrisponda<br/> a uno degli slug di pagina passati in $page_slugs.
	 * [ Serve alla funzione GetData per selezionare gli EV di determinate pagine. ] 
	 * @param array $page_slugs - slugs SOLO di pagine attivita'
	 * @param int $post_id - id del post di cui controllare la corrispondenza 'tag-pagina'
	 * @return boolean - true se il post e' associato a una pagina attivita'.
	 */
	private function __checkTagPage($page_slugs, $post_id)
	{
		$tags = wp_get_post_tags($post_id);
		if(!is_array($tags) || count($tags)<=0) return false;
		
		foreach($tags as $t)
		{
			/*	Si presume che i page slugs siano di pagine attivita'.
				Quindi non serve fare un check con gli array dei tag "non attivita'".
			*/
			if(in_array($t->slug,$page_slugs)) return true;
		}
		return false;
	}


	
	

	/**
	 * Da un array di ID di pagina restituisce un array con i loro slug.
	 * [ Serve alla funzione GetData per selezionare gli EV di determinate pagine. ] 
	 * @param array $page_ids - id delle pagine
	 * @return array - slug delle pagine
	 */
	private function __createPageSlugsArray($page_ids)
	{
		$page_slugs = array();
		$pages = get_pages(array('numberposts'=> -1, 'orderby'=> 'post_name', 'order'=>'ASC', 'include'=>$page_ids));
		if(!is_array($pages) || count($pages)<=0) return $page_slugs;
		
		foreach($pages as $p)
		{
			$page_slugs[]=$p->post_name;
		}
		return $page_slugs;
	}




	
	/**
	 * Ottiene dal database tutti gli EV di un certo tipo, entro un certo numero di giorni.
	 * Passare gli id di pagine serve alle pagine tematiche: dopo aver ottenuto le pagine<br/> tematiche si prendono i loro id e si ottengono gli eventi associati.
	 * @param int $type - 0: tutti, 1:event, 2:promo/pack
	 * @param int $days - a partire da oggi, il numero di giorni in cui cercare EV
	 * @param bool $map_coord - true per farsi restituire le coordinate dei post
	 * @param array $page_ids - id delle pagine di cui recuperare gli EV; se NULL non c'e' selezione
	 * @param array $allids (byref) (opzionale) - id dei post convalidati e restituiti
	 * @return bool - true se sono stati trovati degli EV
	 * @uses array $this->GetData_data 
	 */
	public function GetData($type, $days=10, $map_coord=false, $page_ids=null, &$allids=array()) 
	{
		global $ec3,$wpdb;
		$this->GetData_data = array();
		$allids = array();
		$return_flag = false;
		
		// Check $type (all=0, ev=1, promo=2)
		$type = intval($type);
		$EV_page_ids = array($this->vpc->CAT_Event,$this->vpc->CAT_Promo);
		if($type<0 || $type>2) $type=0;
		if($type==0) $cat_id=implode("|%' OR $ec3->schedule.category_id LIKE '%|",$EV_page_ids);
		else $cat_id=$EV_page_ids[$type-1];
		
		// Check $number
		$days = intval($days); if($days<=0) $days=10;
		$end = time() +60*60*24*$days +60;

		
		// SQL
		$sql_query = " 
		 
		SELECT DISTINCT 
		   $wpdb->posts.ID AS post_id, 
		   $wpdb->posts.post_title AS post_title, 
		   $ec3->schedule.category_id AS category_id,
		   
		   $ec3->schedule.start AS start, 
		   $ec3->schedule.end AS end, 
		   $ec3->schedule.allday AS noH, 
		   
		   DAYOFMONTH($ec3->schedule.start) as s_day, MONTH($ec3->schedule.start) as s_month, YEAR($ec3->schedule.start) as s_year, 
		   HOUR($ec3->schedule.start) as s_hour, MINUTE($ec3->schedule.start) as s_minute, 
		   
		   DAYOFMONTH($ec3->schedule.end) as e_day, MONTH($ec3->schedule.end) as e_month, YEAR($ec3->schedule.end) as e_year, 
		   HOUR($ec3->schedule.end) as e_hour, MINUTE($ec3->schedule.end) as e_minute 
		   
		FROM $ec3->schedule 
		LEFT JOIN $wpdb->posts ON $ec3->schedule.post_id=$wpdb->posts.ID 
		WHERE	$wpdb->posts.post_status = 'publish'
				AND $ec3->schedule.end >= '$ec3->today'
				AND ( $ec3->schedule.category_id LIKE '%|$cat_id|%' )
		ORDER BY $ec3->schedule.start ASC, $wpdb->posts.ID DESC
		LIMIT 0,100 
		"; 
		
		$calendar_entries = $wpdb->get_results($sql_query);
		if(!is_array($calendar_entries) || count($calendar_entries)<=0) return false;
		
		$this->GetData_data = array('calendar'=>array(), 'event'=>array(), 'pack'=>array(), 'promo'=>array());
		$PAGE_SLUGS = array();
		$month = -1;
		$day = -1;
		
		
		
		if($page_ids!=null && is_array($page_ids) && count($page_ids)>0)
		{
			/*
			 * 1) ottenere gli slug di tutte le pagine
			 * 2) per ogni post ottenere gli slug di tutti i suoi tag
			 * 3) controllare se ALMENO UNO dei tag del post e' nell'insieme degli slug delle pagine
			 */			
			$PAGE_SLUGS = $this->__createPageSlugsArray($page_ids);
		}
		else $page_ids=null; // necessaria per i successivi check

		
		foreach($calendar_entries as $ev)
		{
			$key="";
			$new_entry = array();
			$new_entry_flag = 0; // 0=invalid; 1=calendar; 2=event; 3=pack; 4=promo
			
			$permalink = get_permalink($ev->post_id);
			$start_mktime = mktime($ev->s_hour,$ev->s_minute,0,$ev->s_month,$ev->s_day,$ev->s_year); 
			$end_mktime = mktime($ev->e_hour,$ev->e_minute,0,$ev->e_month,$ev->e_day,$ev->e_year);
			
			
			/*
			 * Controlla che il post sia associato ad una pagina attivita',
			 * se sono stati indicati degli id di pagina. Se non lo e' passa avanti.
			 */
			if($page_ids!=null && !$this->__checkTagPage($PAGE_SLUGS, $ev->post_id)) continue;
			
				
			// Evento giornaliero ed entro il limite?
			if(($end_mktime-$start_mktime) <= (24*60*60))
			{
				// calendar
				if($start_mktime<=$end)
				{
					$key="calendar";
					$new_entry_flag=1;
						
					if(!$ev->noH) $time=date('H:i',mktime($ev->s_hour,$ev->s_minute,0,$ev->s_month,$ev->s_day,$ev->s_year));
					else $time="";

					// Imposto l'array del giorno di calendario
					$month=intval($ev->s_month);
					$day=intval($ev->s_day);
					$key_day=$this->vpc->LANG_day_names[intval(date('N',$start_mktime))]." $day ".$this->vpc->LANG_month_names[$month];
					if(!isset($this->GetData_data[$key][$key_day])) $this->GetData_data[$key][$key_day]=array();
					
					// Campi specifici di $new_entry
					$new_entry['time']=$time;
				}
			}
			else
			{
				$cids = explode("|",$ev->category_id);
				
				if(in_array($this->vpc->CAT_Event,$cids))
				{
					// event
					$key = "event";
					$new_entry_flag=2;
					$new_entry['date']=$this->__formatDate($ev,$ev->noH);
				}
				else if(in_array($this->vpc->CAT_Promo,$cids))
				{
					$tags = wp_get_post_tags($ev->post_id,array('fields'=>'slugs'));
					if(in_array($this->vpc->TAG_Pack,$tags))
					{
						// pack
						$key = "pack";
						$new_entry_flag=3;
						$new_entry['date']=$this->__formatDate($ev,$ev->noH);
					}
					else
					{
						// promo
						$key = "promo";
						$new_entry_flag=4;
						$new_entry['date']=$this->__formatDate($ev,$ev->noH);
					}
				}
			}			
			
			
			// NUOVA ENTRY
			if($new_entry_flag>0)
			{
				if($map_coord) $new_entry['map']=$this->vpc->get_latlng($ev->post_id);
			
				$new_entry['id'] = $allids[] = $ev->post_id;
				$new_entry['title']=$ev->post_title;
				
				if(in_array($this->vpc->CAT_Hidden,explode("|",$ev->category_id))) $new_entry['link']="";
				else $new_entry['link']=$permalink;
				
				if($new_entry_flag==1) // calendario
				{
					$this->GetData_data[$key][$key_day][] = $new_entry;
				}
				else
				{
					$this->GetData_data[$key][] = $new_entry;
				}
				$return_flag=true;
			}

			
		}/* end_foreach $calendar_entries */
		
		return $return_flag;
	}
	
	
	
	
	
	/**
	 * Stampa un calendario di EV. 
	 * @uses array $this->GetData_data
	 */
	function Print_Calendar()
	{
		if(count($this->GetData_data)<=0) return;
		$keys = array_keys($this->GetData_data['calendar']);
		if(count($this->GetData_data['calendar'])<=0) return;
?>
		<section class="notice_board calendar">
			<header>Eventi in calendario</header>
<?php		foreach($keys as $k) : 
?>
			<article>
				<h2><?php echo $k ?></h2>
<?php			foreach($this->GetData_data['calendar'][$k] as $e) : 
?>
				<p><?php
				
				if(strlen($e['time'])>0) echo '<span class="time">'.$e['time'].'</span> - ';
				
				if(strlen($e['link'])>0) echo '<a href="'.$e['link'].'">'.$e['title'].'</a>';
				else echo $e['title'];
				
                ?></p>
<?php			
			endforeach ?>
			</article>
<?php		endforeach ?>
		</section>
<?php
	}
	
	
	
	
	
	/**
	 * Stampa una bacheca di EV.
	 * @param string $key - chiave dell'array ('event','pack','promo')
	 * @param string $title - titolo della bacheca.
	 * @uses array $this->GetData_data
	 */
	function Print_Single_Board($key, $title)
	{
		if(count($this->GetData_data)<=0) return;
		if(!array_key_exists($key, $this->GetData_data)) return;
		if(count($this->GetData_data[$key])<=0) return;
?>
		<section class="notice_board board">
			<header><?php echo $title; ?></header>
<?php		foreach($this->GetData_data[$key] as $e) : 
?>
			<article>
				<div class="date_time"><?php echo $e['date']; ?></div>
				<h3><?php
				
				if(strlen($e['link'])>0) echo '<a href="'.$e['link'].'">'.$e['title'].'</a>';
				else echo $e['title'];
				
                ?></h3>
			</article>
<?php		endforeach ?>
		</section>
<?php
	}
	
	
	
	
	
	/**
	 * Stampa tutte le bacheche.
	 */
	function Print_All_Boards()
	{
		$this->Print_Single_Board('event','Eventi in bacheca');
		$this->Print_Single_Board('promo','Offerte e Promozioni');
		$this->Print_Single_Board('pack','Pacchetti Turistici');
	}
	
	
	
	
	
	/**
	 * Stampa i dati dei marker in formato oggetti json.
	 * Stampa solo '{...},' , quindi occorre provvedere a circondare questo output<br/> con var markers = [ ... ]; in modo che sia un array di oggetti json.
	 * @uses array $this->GetData_data
	 */
	function Print_GmapMarkersData()
	{
		// var markers = [ {...} , {...}, ];
		// Stampa prima il calendario e poi le bacheche, dato che gli array sono diversi e non si possono scorrere nello stesso modo.
		
		if(count($this->GetData_data)<=0) return;
		
		$keys = array_keys($this->GetData_data['calendar']);

		foreach($keys as $k) : 
			foreach($this->GetData_data['calendar'][$k] as $e) : 
			
				if(count($e['map'])<=0) continue;
			
				$txt =  '<div class="EV_infowindow">'.
							'<div class="title">'.(strlen($e['link'])>0?'<a href="'.$e['link'].'" target="_blank">'.$e['title'].'</a>':$e['title']).'</div>'.
							'<div class="date">'.$k.(strlen($e['time'])>0?', ore '.$e['time']:'').'</div>'.
						'</div>';
				$txt = "'".str_ireplace("'","\'", $txt)."'";
?>
				{lat:<?php echo $e['map'][0]; ?>, lng:<?php echo $e['map'][1]; ?>, txt:<?php echo $txt; 
				?>, icon:<?php echo "'".$this->vpc->template_url."img/small_markers/mm_20_red.png"."'"; ?>},
<?php	
			endforeach;
		endforeach;
	
	
		$keys = array_keys($this->GetData_data);
		foreach($keys as $k) : 
			if($k=='calendar') continue;
			foreach($this->GetData_data[$k] as $e) : 
			
				if(count($e['map'])<=0) continue;
			
				$txt =  '<div class="EV_infowindow">'.
							'<div class="title">'.(strlen($e['link'])>0?'<a href="'.$e['link'].'" target="_blank">'.$e['title'].'</a>':$e['title']).'</div>'.
							'<div class="date">'.$e['date'].'</div>'.
						'</div>';
				$txt = "'".str_ireplace("'","\'", $txt)."'";
?>
				{lat:<?php echo $e['map'][0]; ?>, lng:<?php echo $e['map'][1]; ?>, txt:<?php echo $txt; 
				?>, icon:<?php echo "'".$this->vpc->template_url."img/small_markers/mm_20_red.png"."'"; ?>},
<?php
			endforeach;
		endforeach;
	}

		
}




?>