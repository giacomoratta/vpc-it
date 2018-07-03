<?php

class _TouristInfo
{
	
	/** 
	 * Array delle sezioni e id associati.
	 * @var array
	 */public $array; 
	
	/**
	 * Costruttore dello slider.
	 * @param VPC $vpc
	 */
	function __construct()
	{
		
		$this->array = array(
			
			'Mare, Spiagge, Scogliere' => array(5705,5672,5680),
			
			'Natura' => array(5672),
			
			'Festivit&agrave;' => array(),
	
			'Storia e Territorio' => array(3008),
			
			'Punti di interesse' => array(),
			
		);
				
	}
}


?>