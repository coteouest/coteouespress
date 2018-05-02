<?php 

$args_createurs = array ('post_type'=>'acteurs', 'posts_per_page' => 4, 'order'=>'DESC'); 
$createurs_query = new WP_Query($args_createurs);  
?>
<ul class="list-inline">
<?php
if($createurs_query->have_posts() ) :
    while ( $createurs_query->have_posts())  : $createurs_query->the_post();
?>
    <aside>
    <img  src="<?php
                    //Condition pour affichage ou pas des vignettes des programmes
                    if(has_post_thumbnail()){
                        echo the_post_thumbnail_url(); //Affiche l'image du programme
                    }else{
                        echo 'http://localhost/coteouespress/wp-content/uploads/no-pic-actor.jpg'; //Affiche l'image par défaut
                    }
                ?>" alt="<?php the_title(); ?>" class="img-thumbnail" height="70px" width="70px" />
        <h2><?php the_title(); ?></h2>
        <a href="<?php the_permalink(); ?>">Voir+</a>
        <p class="clear"></p>
    </aside>
    
<?php
                        
    endwhile;
endif; wp_reset_query();

?>    