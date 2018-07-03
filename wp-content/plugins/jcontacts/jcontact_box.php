<?php
/**
 * @package jContact
 * @version 1.0.0
 */
/*
Plugin Name: jContact
Description: Aggiunge nel testo del post i contatti inseriti nei campi personalizzati
Author: Giacomo Ratta
Version: 1.0.9
*/


$CT_prefix = "xVPC_";

function jct_is_email($string)
{
	return preg_match('/^[_a-z0-9+-]+(\.[_a-z0-9+-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)+$/i',$string);
}

function jcontact_box_ajaxemail_script()
{
	global $CT_prefix;
	$emails = get_post_meta(get_the_id(),$CT_prefix."Email",true);
	if(strlen($emails)<=0) return;
	
	$e = explode(" ",$emails);
	$emails = array();
	foreach($e as $b)
	{
		$b = trim($b);
		if(strlen($b)>0 && jct_is_email($b)) $emails[]=$b;
	}
	
	if(count($emails)<=0) return;
	
	echo "var jae_emails = \"".implode(", ",$emails)."\";\n";
	echo "var jae_emails_text = \"<strong>".implode("</strong> ,&nbsp; <strong>",$emails)."</strong>\";\n";
	echo "var jea_act_link = \"".get_permalink(get_the_id())."\";";
	include("wp-content/plugins/jcontacts/ajax_email/AjaxEmail_script.js");
}


$CT_Settings = array (

	/* Testo scritto nell'etichetta => Testo che deve apparire all'utente */
	
	'Manager' => "",
	'Indirizzo' => "",
	'Tel'=> "Tel",
	'Fax'=> "Fax",
	'Email'=> "eMail",
	'Sitoweb' => "Sito Web",
	'Facebook' => "Facebook",
	'Twitter' => "Twitter",
	'LinkedIn' => "LinkedIn",
	'Skype' => "Skype",
	'MSN' => "MSN",
);


add_filter( 'the_content', 'ct_printer', 10, 10);


/* * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * */



/* Legge/Scrive i contatti */
function ct_printer($content, $admin=false)
{
	global $CT_field, $VPC;

	if($VPC->Theme->page_class!='business_page') return $content;
	
	$a = ct_get_contactfields();
  		
  	$b = ct_print_contactfields($a);
  	if(strlen($b)>0)
  		$b = '<div class="contacts_info"><h2>Contatti &amp; Coordinate</h2>'.$b.'</div><!-- .contacts_info -->';
  	
  	return $content.ct_ajaxemail_form().$b;
}


function ct_ajaxemail_form()
{
	global $CT_prefix;
	$emails = get_post_meta(get_the_id(),$CT_prefix."Email",true);
	if(strlen($emails)<=0) return;
	
	ob_start();
	include("ajax_email/AjaxEmail_form.html");
	$c = ob_get_contents();
	ob_end_clean();

	return $c;
}




function ct_get_contactfields()
{
	global $post_ID, $CT_prefix, $wpdb, $post;
	
	$ct_array = array();
	
	$post_ID = intval($post_ID);
	if($post_ID<=0) $post_ID = intval($post->ID);
	if($post_ID<=0) return null;
	
	$sql_query = "
	
	SELECT $wpdb->postmeta.meta_key, $wpdb->postmeta.meta_value
	FROM $wpdb->postmeta
	WHERE $wpdb->postmeta.post_id=$post_ID && $wpdb->postmeta.meta_key LIKE '$CT_prefix%'
	ORDER BY $wpdb->postmeta.meta_key
	
	";
	
	$ct_array = $wpdb->get_results($sql_query);
	
	if(!is_array($ct_array) || count($ct_array)<=0) return null;
	
	return $ct_array;
	/*
		array(2) {
			[0]=>	object(stdClass)#3288 (2) {
						["meta_key"]=> string(9) "VPC_Email"
						["meta_value"]=> string(17) "klsajsaf@ymail.it"
					}
			[1]=> 	object(stdClass)#3286 (2) { ... }
		}
	*/
}


function ct_print_contactfields($ct_array)
{
	global $CT_Settings, $post_ID;
	
	$final_string = "";
	
	if(count($ct_array)<=0) return;
	
	$order_array = $CT_Settings;
	
	// Inizializza l'array
	$order_keys = array_keys($order_array);
	for($i=0;$i<count($order_keys);$i++) $order_array[$order_keys[$i]]=array();
	
	foreach($ct_array as $ct)
	{
		// Lettura etichetta
		$label = explode("_",$ct->meta_key);
		$label = $label[1];
		
		// Modifiche sul meta_value
		$ct->meta_value = trim($ct->meta_value);
		
		// Inserimento ordinato + Sostituzione etichetta con $CT_Settings
		$order_array[$label] = array((in_array($label,$order_keys)?$CT_Settings[$label]:$label),$ct->meta_value);
	}
	
	// GPS
	$gps_latlng="";
	$latlng = get_post_meta($post_ID,"GMap_LatLng",true);
	if(strlen($latlng)>0) $gps_latlng = Coordinate_DD2DMS($latlng);
	if(strlen($gps_latlng)>0)
	{
		$order_array['GPS'] = array('GPS',$gps_latlng);
	}
	
	if(count($order_array)<=0) return;
	
	foreach ($order_array as $ct)
	{
		if(count($ct)<=0) continue;
		
		// Contenuto di LINK?
		$pos = strpos($ct[1],"http://");
		if($pos===false) $pos = strpos($ct[1],"https://");
		if(!($pos===false) && $pos==0)
		{
			$ct[1]=__ct_set_links($ct[1],$ct[0]);
		}
		
		// Contenuto TESTUALE
		else
		{
			// Etichetta
			if(strlen($ct[0])>0) $ct[0]='<strong>'.$ct[0].'</strong>: ';
			
			if(is_single() || is_page())
			{
				//$ct[1]=str_ireplace(array("<br/>","<br />"),"\n",$ct[1]);
				$ct[1]=preg_replace('/(\\n)+/', "\n", $ct[1]);
				$ct[1]='<p>'.$ct[0].$ct[1].'</p>';
			}
			else $ct[1]='<p>'.$ct[0].$ct[1].'</p>';
		}
		
		$final_string .= "\n\n".$ct[1];
	}
	
	$final_string .= "\n\n";
	return $final_string;
}


function __ct_set_links($c,$label)
{
	$links = explode("\n",$c);
	
	$counter = 1;
	$link_label = "";
	$return = "";
	
	if(count($links)<=0) return "";
	
	foreach($links as $l)
	{
		// Elimina spazi multipli
		$l = trim(preg_replace('/\s\s+/', ' ', $l));
		
		// Etichetta standard con contatore
		$link_label = $label;
		if(count($links)>1) $link_label .= ' '.($counter++);
		
		// Scompatto i dati (link+etichetta)
		if($l[strlen($l)-1]==']')
		{
			// Trovo lo spazio
			$space_pos = stripos($l," ");
			if($space_pos===false) return "";
			
			// Taglio la stringa
			$l_exp = array("","");
			$l_exp[0] = substr($l,0,$space_pos);
			$l_exp[1] = substr($l,$space_pos+1);
			
			// Pulisco e imposto etichetta+link
			$l = trim($l_exp[0]);
			$link_label = trim(substr($l_exp[1],1,-1));
		}
		
		$return .=	'<p class="visible_link"><strong><a href="'.$l.'" target="_blank" rel="nofollow">'.$link_label.'</a></strong></p>'.
					'<p class="ghost_link"><strong>'.$link_label.'</strong> &raquo; <a href="'.$l.'" target="_blank" rel="nofollow">'.$l.'</a></p>';
	}
	
	return $return;
	
}



/* Da coordinate GMap a coordinate GPS */
function Coordinate_DD2DMS($latlng)
{
	list($lat,$lng) = explode(",",$latlng);
	if(strlen($lat)<=0 || strlen($lng)<=0) return "";

	$LT = ($lat>0?"N":"S");
	$LN = ($lng>0?"E":"W");

	$lat = abs($lat);
	$lng = abs($lng);

	$lat_D = intval($lat);
	$lng_D = intval($lng);

	$lat_M = intval(60*($lat-$lat_D));
	$lng_M = intval(60*($lng-$lng_D));

	$lat_S = intval(60*(60*($lat-$lat_D)-$lat_M));
	$lng_S = intval(60*(60*($lng-$lng_D)-$lng_M));

	if($lat_S==60) { $lat_M++; $lat_S=0; }
	if($lng_S==60) { $lng_M++; $lng_S=0; }

	if($lat_M==60) { $lat_D++; $lat_M=0; }
	if($lng_M==60) { $lng_D++; $lng_M=0; }

	return "$lat_D&deg; $lat_M' $lat_S'' $LT ,  $lng_D&deg; $lng_M' $lng_S'' $LN";
}



?>