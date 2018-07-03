<?php

class _BusinessData
{
	public $array;
	public $thematic;
	public $thematic_config;
	
	function __construct()
	{
		// configurazioni colori marker
		// sottotitolo di ogni categoria tematica
		
		$this->thematic_config = array(
		
			'marker_colors' => array(
				'default' => 'red',
				'cats' => array(
						'Spiagge e Scogliere'=>'yellow',
						),
			),
			
			'subtitles' => array(
				/*'Dove Dormire' => */	 "Hotel, B&B, appartamenti, agenzie",
				/*'Dove Mangiare' => */	 "Ristoranti, locali, bar, vendita prodotti",
				/*'Mare e Spiagge' => */ "Lidi attrezzati, spiagge, escursioni",
				/*'Servizi e altre attivita ' =>*/ "Shopping, divertimento, noleggi, servizi",
			),
		);
		
		
		$this->thematic = array(
		
			/*
				'Dove Dormire' => array( slug della pagina wp, array di categorie )
				Le categorie non dovrebbero appartenere a piu' di una pagina tematica!!!
			*/
	
			/*'Dove Dormire' => */ array('dove-dormire',array(
				'Agenzie e Servizi turistici',
				'Agriturismi / Masserie',
				'Appartamenti e B&B',
				'Hotel / Resort',
				//i villaggi devono andare in appartamenti o resort
			)),
			
			/*'Dove Mangiare' => */ array('dove-mangiare',array(
				'Agriturismi / Masserie',
				'Gelaterie, Pasticcerie, Bar',
				'Paninoteche, Rosticcerie , Creperie',
				'Pescherie',
				'Ristoranti, Pizzerie, Pub',
				'Supermercati e Prodotti Alimentari'
			)),
			
			/*'Mare e Spiagge' => */ array('mare-spiagge',array(
				'Escursioni, Diving Center, Snorkeling',
				'Spiagge e Scogliere',
				'Stabilimenti Balneari - Villaggi turistici',
			)),
			
			/*'Shopping, Servizi e altre attivita' ' => */ array('shopping-servizi',array(
				'Artisti',// e Band musicali',
				'Associazioni e Soggetti politici',
				'Centri Fitness, Palestre, Sport',
				'Edicole e Internet Point',
				'Fiori e Piante',
				'Fotografia e Video',
				'Lavanderie',
				'Ludoteche',
				'Luoghi di interesse pubblico',
				'Noleggi',
				'Parrucchieri, Estetisti',
				'Sale Giochi',
				'Servizi Bancari e Postali',
				'Servizi specializzati - Assistenza tecnica',
				'Shopping e Abbigliamento',
			)),
		);
		
		
		
		$this->array = array(
		
			'Agenzie e Servizi turistici'	=> array(1552,2234,2550,2633,2701,4619,7426,7621),
			
			'Agriturismi / Masserie'	=> array(964,1007,1312,1231,1672,2439,2692,2735),
			
			'Appartamenti e B&B'	=> array(387,1563,1745,1801,1918,2225,2274,2330,2861,4080,4616,4628,4631,4634,4637,4640,4642,4645,5695,5701,6088,6873,7436,1062,176,1061,587,1007,1624,1674,1882,1984,2064,2213,2237,2517,2561,2642,2716,2748,3533,3677,5041,5492,7735,8242,9141,9134),
			
			'Artisti'/* e Band musicali',*/	=> array(640,634,644,751,2827,2835),
			
			'Associazioni e Soggetti politici'	=> array(497,978,1321,1557,460,497,843,1774,528,1850),
			
			'Centri Fitness, Palestre, Sport'	=> array(434,2654,8889),
			
			'Edicole e Internet Point' 	=> array(4769,5480),
			
			'Escursioni, Diving Center, Snorkeling'	=> array(1131,5347),
			
			'Farmacie'	=> array(4868),
			
			'Fiori e Piante'	=> array(1385),
			
			'Fotografia e Video'	=> array(3105),
			
			'Gelaterie, Pasticcerie, Bar' 	=> array(468,331,406,493,2107,2658,4760,4764,5480,5689,7500,7903,7928,5480),
			
			'Hotel / Resort'	=> array(114,611,716,679,690,825,924,949,1001,1182,1408,1435,1839,2036,2095,2123,2270,2610,2708,2756,3525,5024,5772),
			
			'Lavanderie'	=> array(4923),
			
			'Ludoteche' 	=> array(4093,5744),
			
			'Luoghi di interesse pubblico' 	=> array(574,1773,1354,1552,2468,2667,3008,3066,3067,8898,9372,9373),
			
			'Noleggi'	=> array(1546,2550,9213),
			
			'Paninoteche, Rosticcerie , Creperie'	=> array(7773,7779,5480),
			
			'Parrucchieri, Estetisti'	=> array(4886,7939),
			
			'Pescherie'	=> array(7749),
			
			'Ristoranti, Pizzerie, Pub'	=> array(63,76,431,310,322,326,690,703,727,825,924,964,1007,1014,1121,1269,1312,1231,1467,1493,1432,1460,2123,2408,2440,2441,2588,2692,2705,4760,5014,983,1949,2703,4919,7694,7903,9129),
			
			'Sale Giochi'	=> array(76,314,1546),
			
			'Servizi Bancari e Postali'	=> array(2409),
			
			'Servizi specializzati - Assistenza tecnica'	=> array(1726,2411,2586,2631,3370,6154,7742),
			
			'Shopping e Abbigliamento'	=> array(895,1833,2032,2410,2412,2414,2415,2438,2584,2637,4687,4950),
			
			'Spiagge e Scogliere' => array(),
			
			'Stabilimenti Balneari - Villaggi turistici'	=> array(431,41,63,405,245,322,299,76,314,246,825,1745,1839,2123, 2199,2270,2844),
			
			'Supermercati e Prodotti Alimentari'	=> array(1053,2407,2639,2656,5242),			
		);
	}
}


?>