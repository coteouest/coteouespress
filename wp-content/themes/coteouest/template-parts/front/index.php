<?php get_header(); ?

    if ( have_posts() ) : 

        while ( have_posts() ) : the_post();
            get_template_part( 'template-parts/content', get_post_format() );
		endwhile;

    else :   
	   get_template_part( 'template-parts/content', 'none' );

	endif;


get_footer();
