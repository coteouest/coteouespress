<?php

$args = array (
    'post_type'=>'programmes',
    'order'=>'DESC',
    'meta_query' => array(
        'relation' => 'AND',
        array(
            'key' => 'a_la_une',
            'value' => 'oui',
            'compare' => '=',
        ),
        array(
            'key' => 'region',
            'value' => 'Afrique',
            'compare' => '=',
        ),
    ),
); 

$une-afrique-query = new WP_Query($args);  


if($une-afrique-query->have_posts() ) :
    while ( $une-afrique-query->have_posts())  : $une-afrique-query->the_post();
?>
            
    <a href="<?php the_field('document_pdf'); ?>" target="_blank">Télécharger</a>

<?php
    endwhile;
endif; wp_reset_query();
?>