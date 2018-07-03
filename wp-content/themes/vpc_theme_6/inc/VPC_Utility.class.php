<?php


/**
 * Pacchetto di funzioni di particolare utilita'.
 * 
 */
class VPC_Utility {

	
	/**
	 * Restituisce parte della stringa passata, senza tagliare le parole.
	 * @param string $string - stringa da tagliare
	 * @param int $chars - numero massimo di caratteri
	 * @return string - stringa tagliata
	 */
	static function cutStringByWords($string, $chars)
	{
		$pos=false;
		if(strlen($string)>=$chars) $pos = stripos($string, ' ', $chars);
		if($pos !== false) return substr($string, 0, $pos)."...";
		return $string;
	}
	
	
	
	
	
	/**
	 * Restituisce un elemento a caso dell'array $a
	 * @param array $a
	 * @return mixed
	 */
	static function random_array_element($a)
	{
		$c = count($a);
		if($c==0) return "";
	
		$i = rand(0,$c-1);
	
		return $a[$i];
	}
}



?>