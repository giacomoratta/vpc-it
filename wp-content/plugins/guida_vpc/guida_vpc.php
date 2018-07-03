<?php
/*
Plugin Name: Guida alla scrittura dei post
Description: Aggiunge un pannello con le direttive back-end da seguire per la scrittura dei post
Author: Giacomo Ratta
Version: 2.0.9
*/


/* Define the custom box */
add_action('add_meta_boxes', 'vpc_reference_add');


/* Adds a box to the main column on the Post and Page edit screens */
function vpc_reference_add()
{
    add_meta_box( 'vpc_reference_id', "Guida alla scrittura dei post", 'vpc_reference_print', 'post' );
    add_meta_box( 'vpc_reference_id', "Guida alla scrittura dei post", 'vpc_reference_print', 'page' );
}

/* Prints the box content */
function vpc_reference_print()
{
	// Use nonce for verification
	wp_nonce_field( plugin_basename(__FILE__), 'vpc_reference_noncename' );

	
?>

<style type="text/css">
#vpc_reference_id .vpc_reference_id_content { font-size:12px; }
#vpc_reference_id .vpc_reference_id_content h2,
#vpc_reference_id .vpc_reference_id_content h3,
#vpc_reference_id .vpc_reference_id_content h4,
#vpc_reference_id .vpc_reference_id_content h5 { font-size:13px; margin:0 0 0.6em 0; }
#vpc_reference_id .vpc_reference_id_content ul { list-style:square; margin:0 0 2em 3em; }
#vpc_reference_id .vpc_reference_id_content li { margin:0 0 0.4em; 0; }
</style>
<div class="vpc_reference_id_content">
<?php 

$id = 8885;

$p = get_post($id);
echo $p->post_content;

echo edit_post_link( "Modifica la guida", '<p style="text-align:right;">', '</p>', $id );


?>
</div>

<?php
}

?>