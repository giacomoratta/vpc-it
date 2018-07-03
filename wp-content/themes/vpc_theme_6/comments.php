
	<section class="wp_comments">
<?php 


function my_vpc_comment_function($comment, $args, $depth)
{
?>
		<div class="comment" id="comment-<?php comment_ID(); ?>"> 
			<div class="info">
            	<!--div class="row"><img class="avatar" src="img/thumb1.jpg" /></div-->
                <div class="row author"><?php echo get_comment_author_link(); ?></div>
                <div class="row date"><?php printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time() ); ?></div>
                <div class="row"><?php edit_comment_link( __( '(Edit)' ), '', '' ); ?></div>
            </div> 
			<div class="text">
				<?php comment_text(); 
                
                /*<p class="reply">
        			<a class="comment-reply-link" href="#">Rispondi</a>
					<a class="comment-reply-link" href="#">Cita</a>
        		</p>*/
        		?>
			</div>
            <div class="clear"></div>
		</div>
<?php
}

		wp_list_comments('type=comment&callback=my_vpc_comment_function');
    
		
?>
    </section><!-- .wp_comments -->
    
    
    