<?php
/**
 * @package GMap Box
 * @version 1.0.0
 */
/*
Plugin Name: GMap Box
Description: Aggiunge un pannello con una Google Map in cui specificare il luogo associato al post
Author: Giacomo Ratta
Version: 1.0.9
*/

$GMap_Box_field = "GMap_LatLng";

$GMap_Box_google_url = 'https://maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&key=AIzaSyCpy7yZvpWCY0qoRDjyu1NFJ5xsfhUC_LQ';

$GMap_Box_local_theme_script = '/script/vpc_gmap.jquery.js';



if(is_admin())
{
	global $GMap_Box_local_theme_script;
	wp_enqueue_script('jquery');
	wp_enqueue_script("GoogleMaps_API", 'http://maps.google.com/maps/api/js?sensor=false');
	wp_enqueue_script("GMapBox_Script", get_bloginfo('template_directory').$GMap_Box_local_theme_script);
}

/* Define the custom box */
add_action('add_meta_boxes', 'gmap_box_add');

/* Do something with the data entered */
add_action('save_post', 'gmap_box_save');



/* Adds a box to the main column on the Post and Page edit screens */
function gmap_box_add()
{
    add_meta_box( 'gmap_box_id', "Google Map Box", 'gmap_box_print', 'post' );
    add_meta_box( 'gmap_box_id', "Google Map Box", 'gmap_box_print', 'page' );
}



/* Prints the box content */
function gmap_box_print()
{
  global $post_ID,$GMap_Box_field;

  // Use nonce for verification
  wp_nonce_field( plugin_basename(__FILE__), 'gmap_box_noncename' );
  
  //if(post_type=="post")... -> page -> gmap_latlng
  
  $GMap_Box_latlng = get_post_meta($post_ID,$GMap_Box_field,true);
  if(strlen($GMap_Box_latlng)>0) list($GMap_Box_lat,$GMap_Box_lng) = explode(",",$GMap_Box_latlng);

  // The actual fields for data entry
  include("GMapBox.html");
}



/* When the post is saved, saves our custom data */
function gmap_box_save( $post_id )
{
  global $GMap_Box_field, $VPC;
  
  // verify this came from the our screen and with proper authorization,
  // because save_post can be triggered at other times

  if ( !wp_verify_nonce( $_POST['gmap_box_noncename'], plugin_basename(__FILE__) )) {
    return $post_id;
  }

  // verify if this is an auto save routine. If it is our form has not been submitted, so we dont want to do anything
  if ( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ) 
    return $post_id;

  
  // Check permissions
  if ( 'page' == $_POST['post_type'] ) {
    if ( !current_user_can( 'edit_page', $post_id ) )
      return $post_id;
  } else {
    if ( !current_user_can( 'edit_post', $post_id ) )
      return $post_id;
  }

  // OK, we're authenticated: we need to find and save the data
  
  $latlng=null;
  $latlng_postmeta=trim(get_post_meta($post_id, $GMap_Box_field,true));  
  
  
  // Recupera le coordinate dalla pagina associata
  if(strlen($latlng_postmeta)<=0 && strlen($_POST['GMap_BOX_LatLng'])<=0)
  {
  	$VPC->new_VPC_Business();
  	if($VPC->Business->getPostBusinessPosition($post_id))
  	{
  		list($_POST['GMap_BOX_Lat'],$_POST['GMap_BOX_Lng']) = explode(",",$VPC->Business->getPostBusinessPosition_data);
  	}
  	
  	// check pagine turistiche ?
  }
  
  // Salva le coordinate [MARKER]
  if(strlen($_POST['GMap_BOX_Lat'])>0 && strlen($_POST['GMap_BOX_Lng'])>0)
  {
  	update_post_meta($post_id, $GMap_Box_field, $_POST['GMap_BOX_Lat'].",".$_POST['GMap_BOX_Lng']);
  }
  
  // Salva le coordinate [CUSTOM FIELD]
  elseif(strlen($latlng_postmeta)>0)
  {
	update_post_meta($post_id, $GMap_Box_field, $latlng_postmeta);
  }
  
  // Delete Custom Field
  else delete_post_meta($post_id, $GMap_Box_field);
  
  
  
  
}
?>