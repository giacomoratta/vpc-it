<?php


/**
 * Pacchetto di funzioni di particolare utilita'.
 * 
 */
class VPC_HeadInfo
{

	/**
	 * Riferimento alla classe principale.
	 * @var VPC
	 */private $vpc;
	
	
	/**
	 * Nome del sito scritto in un modo carino
	 * @var string
	 */ public $site_name = "VisitPortoCesareo.it";
	
	
	/**
	 * Link all'immagine delle pagina
	 * @var string
	 */	private $thumb="";
	
	
	
	/**
	 * Costruttore dell'oggetto HeadInfo.
	 * @param object $VPC_Object riferimento all'oggetto della classe principale VPC
	 */
	function __construct($VPC_Object)
	{
		$this->vpc = $VPC_Object;
	}
	
	
	
	/**
	 * Imposta e manipola il titolo della pagina.
	 */
	function title()
	{
		$wp_title = trim(wp_title( '', false, 'right' ));
		//if(strlen($wp_title)<=0) $wp_title=$this->site_name;
		if(strlen($wp_title)<=0) $wp_title = htmlentities($this->site_name, ENT_QUOTES, 'UTF-8');
		
		echo $wp_title;
	}
	
	
	/**
	 * Imposta e manipola la descrizione della pagina..
	 */
	function description()
	{
		$excerpt="";
		$limit=170; /* adatto per facebook */
		
		if($this->vpc->get_the_ID()>0)
		{
			if(is_page()) $excerpt = trim(get_post_meta($this->vpc->get_the_ID(),'vpc_page_excerpt',true));
			
			if( strlen($excerpt)<=0 ) $excerpt = trim(get_the_excerpt());
		}
		
		if(strlen($excerpt)>0)
		{
			$excerpt = str_ireplace(array("\n","\r","\t"), " ", $excerpt);
			$excerpt = VPC_Utility::cutStringByWords($excerpt, 170);
			if($excerpt[strlen($excerpt)-1]!='.') $excerpt.=".";
		}
		else $excerpt=$this->vpc->seo_description;
		
		$excerpt = htmlentities($excerpt, ENT_QUOTES, 'UTF-8');
		echo $excerpt;
	}
	
	/**
	 * Imposta e stampa il link dell'immagine delle pagina
	 */
	function img()
	{
		if(strlen($this->thumb)>5 /* http: 5 caratteri */) { echo $this->thumb; return; } 
		
		$id = $this->vpc->get_the_ID();
		if($id>0) $img = $this->vpc->getPostThumbURL($id);
		
		if($id>0 && count($img)>0)
		{
			$this->thumb=$this->vpc->ThumbScriptURL($img['thumb'][0],200,200,90,$img['align'],1);
		}
		else
		{
			$this->thumb=$this->vpc->ThumbScriptURL($this->vpc->template_url.'img/logo420.png',200,200,90,"",2);
		}
		echo $this->thumb;
	}
}



?>