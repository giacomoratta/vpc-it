<?php

class _BannerData
{
	private $bannerBaseID = "VPC_Banner_";
	
	public $article1 = null;
	public $adsense_article1 = null;
	public $adsense_small_article1 = null;
	
	public $sidebar1 = null;
	public $adsense_sidebar1 = null;
	public $adsense_small_sidebar1 = null;
	
	public $sidebar2 = null;
	public $adsense_sidebar2 = null;
	public $adsense_small_sidebar2 = null;
	
	public $sidebar_mini = null;
	// /* non serve - i mini banner sono solo di vpc.it */ public $adsense_sidebar_mini = null;
	
	//public $sidebar_mini2 = array();
	//public $adsense_sidebar_mini2 = '<div style="background-color:#EEE; width:300px; height:80px;">Mini banner link VPC 2</div>';
	
	

	function __construct()
	{
		/*
		 * ISTRUZIONI
		 * array(data_partenza, numero_giorni, array( array('img.jpg','id_large'), array('img.jpg','id_small')) , link, alt_title),
		 * esempio: array(	'1/1/2012',	15,
		 * 					array( array('img.jpg','id_large'), array('img.jpg','id_small')),
		 * 					'#','alt title alt title'
		 * 				 );
		 */
		
		
		
		$this->article1 = array(
			
			/*array(	'10/09/2014',	55,
					array(
							array('6.jpg',$this->bannerBaseID.'article1'),
							array('3.jpg',$this->bannerBaseID.'small_article1'),
					),
					'#',
					'alt title alt title',
				),*/
		);
		$this->adsense_article1 = 
		'<div id="'.$this->bannerBaseID.'article1" '.
		'style="width:728px; height:90px;">
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- vpc_730_1 -->
		<ins class="adsbygoogle"
		     style="display:inline-block;width:728px;height:90px"
		     data-ad-client="ca-pub-3785472025435497"
		     data-ad-slot="8717526772"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script></div>';
		
		$this->adsense_small_article1 =
		'<div id="'.$this->bannerBaseID.'small_article1" '.
		'style="width:400px; height:100px;">
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- vpc_mobi_320 -->
		<ins class="adsbygoogle"
		     style="display:inline-block;width:320px;height:100px"
		     data-ad-client="ca-pub-3785472025435497"
		     data-ad-slot="4147726373"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>
		</div>';
		
		
		
		

		$this->sidebar1 = array(

			/*array(	'10/09/2014',	55,
					array(
							array('4.jpg',$this->bannerBaseID.'sidebar1'),
							array('5.jpg',$this->bannerBaseID.'small_sidebar1'),
					),
					'#',
					'alt title alt title',
				),*/
		);
		$this->adsense_sidebar1 =
		'<div id="'.$this->bannerBaseID.'sidebar1" '.
		'style="width:300px; height:250px;">
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- vpc_300_1 -->
		<ins class="adsbygoogle"
		     style="display:inline-block;width:300px;height:250px"
		     data-ad-client="ca-pub-3785472025435497"
		     data-ad-slot="3245888452"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script></div>';
		
		$this->adsense_small_sidebar1 =
		'<div id="'.$this->bannerBaseID.'small_sidebar1" '.
		'style="width:200px; height:150px;">
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- vpc_200_1 -->
		<ins class="adsbygoogle"
		     style="display:inline-block;width:200px;height:150px"
		     data-ad-client="ca-pub-3785472025435497"
		     data-ad-slot="2670993179"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script>
		</div>';


		
		
		$this->sidebar2 = array();
		$this->adsense_sidebar2 =
		'<div id="'.$this->bannerBaseID.'sidebar2" '.
		'style="width:300px; height:250px;">
		<script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
		<!-- vpc_300_2 -->
		<ins class="adsbygoogle"
		     style="display:inline-block;width:300px;height:250px"
		     data-ad-client="ca-pub-3785472025435497"
		     data-ad-slot="8752532483"></ins>
		<script>
		(adsbygoogle = window.adsbygoogle || []).push({});
		</script></div>';
		
		
		
		
		
		$this->sidebar_mini = array(
			
			/*array(	'10/09/2014',	55,
					array(
							array('2a.jpg',$this->bannerBaseID.'sidebarmini1'),
					),
					'#',
					'alt title alt title',
			),
				
			array(	'10/09/2014',	55,
					array(
							array('2b.jpg',$this->bannerBaseID.'sidebarmini2'),
					),
					'#',
					'alt title alt title',
			),*/
		);
				
	}
}


?>