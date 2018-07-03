<?php

class _MainMenuSlider
{

	public $cards = array();
	
	public $baseurl;
	
	/**
	 * @var VPC
	 */	public $vpc = null;
	


	/**
	 * Costruttore dello slider.
	 * @param VPC $vpc
	 */
	function __construct($vpc)
	{
		$this->cards = array();
		$this->vpc = $vpc;
		$this->baseurl = $this->vpc->template_url.'mmslider/';
		

		
		/* EVENTI */
		$p = get_post($vpc->PAGE_Event); if($p!=null) 
		$this->cards[] = array(
			'title' => $p->post_title,
			'summary' => get_post_meta($p->ID, 'vpc_page_excerpt', true ),
			'link' => get_permalink($p->ID),
			'photo' => array(
					array(
							'url'=>$this->baseurl.'eventi/'.'8349885953_1c2a8aef4f_k.jpg', // porto cesareo night
							'credit' => 'https://www.flickr.com/photos/parasitepix/8349885953'
					),
					array(
							'url'=>$this->baseurl.'eventi/'.'torreluminariesantacesarea.jpg',
							'credit' => ''
					),
					array(
							'url'=>$this->baseurl.'eventi/'.'223339881_649f1959af_o.jpg', // fuochi 
							'credit' => 'https://www.flickr.com/photos/florixc/223339881/'
					),
					array(
							'url'=>$this->baseurl.'eventi/'.'5382130814_73842e148c_b.jpg', // focara
							'credit' => 'https://www.flickr.com/photos/31598370@N08/5382130814/'
					),
					array(
							'url'=>$this->baseurl.'eventi/'.'bahia-porto-cesareo-4.jpg', // mani in alto
							'credit' => ''
					),
			),
		);
		
		
		/* INFORMAZIONI TURISTICHE */
		$p = get_post($vpc->PAGE_TouristInfo); if($p!=null)
		$this->cards[] = array(
			'title' => $p->post_title,
			'summary' =>  get_post_meta($p->ID, 'vpc_page_excerpt', true ),
			'link' => get_permalink($p->ID),
			'photo' => array(
					array(
							'url'=>$this->baseurl.'infotur/'.'218086092_27a8769d71_b.jpg', // torre lapillo
							'credit' => 'https://www.flickr.com/photos/niki75ciao/218086092'
					),
					array(
							'url'=>$this->baseurl.'infotur/'.'isola_alto.jpg',
							'credit' => ''
					),
					array(
							'url'=>$this->baseurl.'infotur/'.'10782969614_74546edf36_b.jpg', // tramonto isola
							'credit' => 'https://www.flickr.com/photos/24408298@N08/10782969614/'
					),
					array(
							'url'=>$this->baseurl.'infotur/'.'lachiesetta.jpg',
							'credit' => ''
					),
			),
		);
		
		
		/* DOVE DORMIRE */
		$p = get_post($vpc->PAGE_Sleeping); if($p!=null)
		$this->cards[] = array(
			'title' => $p->post_title,
			'summary' =>  get_post_meta($p->ID, 'vpc_page_excerpt', true ),
			'link' => get_permalink($p->ID),
			'img' => $this->vpc->template_url.'img/4.jpg',
			'photo' => array(
					array(
							'url'=>$this->baseurl.'dormire/'.'365gns201355.jpg', // camera dune
							'credit' => ''
					),
					array(
							'url'=>$this->baseurl.'dormire/'.'3620757_12_z.jpg', // club azzurro
							'credit' => ''
					),
					array(
							'url'=>$this->baseurl.'dormire/'.'8155465913_2dc9b6c472_k.jpg', // piscina dune
							'credit' => ''
					),
					array(
							'url'=>$this->baseurl.'dormire/'.'Masseria_Corda_di_Lana_Hotel__Porto_Cesareo_1.jpg',
							'credit' => ''
					),
			),
		);
		
		
		/* DOVE MANGIARE */
		$p = get_post($vpc->PAGE_Eating); if($p!=null)
		$this->cards[] = array(
			'title' => $p->post_title,
			'summary' =>  get_post_meta($p->ID, 'vpc_page_excerpt', true ),
			'link' => get_permalink($p->ID),
			'photo' => array(
					array(
							'url'=>$this->baseurl.'mangiare/'.'1_1400748945_.jpg',
							'credit' => ''
					),
					array(
							'url'=>$this->baseurl.'mangiare/'.'pasticciotti.jpg',
							'credit' => ''
					),
					array(
							'url'=>$this->baseurl.'mangiare/'.'scoglio6.jpg',
							'credit' => ''
					),
					array(
							'url'=>$this->baseurl.'mangiare/'.'15-ristorante-porto-cesareo-camping-linguine-scoglio.jpg',
							'credit' => ''
					),
			),
		);
		
		
		/* MARE SPIAGGE */
		$p = get_post($vpc->PAGE_SeaBeach); if($p!=null)
		$this->cards[] = array(
			'title' => $p->post_title,
			'summary' =>  get_post_meta($p->ID, 'vpc_page_excerpt', true ),
			'link' => get_permalink($p->ID),
			'photo' => array(
					array(
							'url'=>$this->baseurl.'marespiagge/'.'2880226264_d8b282384a_b.jpg',
							'credit' => 'https://www.flickr.com/photos/photoago/2880226264/'
					),
					array(
							'url'=>$this->baseurl.'marespiagge/'.'705_lido_samana1.jpg',
							'credit' => ''
					),
					array(
							'url'=>$this->baseurl.'marespiagge/'.'8388352790_150bd8230e_k.jpg',
							'credit' => 'https://www.flickr.com/photos/florixc/8388352790/'
					),
					array(
							'url'=>$this->baseurl.'marespiagge/'.'1201246615_416ebc0402_o.jpg',
							'credit' => 'https://www.flickr.com/photos/remuz78/1201246615/'
					),
			),
		);
				
	}
}


?>