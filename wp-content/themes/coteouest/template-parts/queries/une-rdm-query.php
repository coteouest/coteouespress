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
            'value' => 'Reste du monde',
            'compare' => '=',
        ),
    ),
); 

$alaune = new WP_Query($args);  


if($pharmanews->have_posts() ) :
    while ( $pharmanews->have_posts())  : $pharmanews->the_post();
?>
            
    <a href="<?php the_field('document_pdf'); ?>" target="_blank">Télécharger</a>

<?php
    endwhile;
endif; wp_reset_query();
?>