<?php
/*
Template Name: Programmes store
*/

    get_header(); 

    get_template_part( 'template-parts/breadcrumb' );
?>

    <!--container start-->
    <main id="programmes">
        <div>
            
        </div>
        <div class="container">
            
            <div class="row">
                <div class="col-md-3">                    
                    <div class="row">
                        <div class="col-md-12 blanc">
                            <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Wigdet categories') ) ?>
                        </div>
                    </div>
                    
                    <div class="row separe">
                        <div class="col-md-12 blanc">
                            <h1 class="">
                            CONTACT
                            </h1>
                            <p>Vous êtes diffuseur, et souhaitez obtenir des informations sur nos programmes Cliquez ici</p>
                        </div>
                    </div>
                </div>
                
                <div class="col-md-9">
                    
                    <div class="row">
                        <div class="col-md-11 col-md-offset-1 blanc">
                        
                            <h1 class="titlle">
                                NOS <strong>PROGRAMMES</strong>
                            </h1>     

                            <div id="filters__searc">
                                <p>
                                    CÔTE OUEST distribue annuellement un catalogue de plus de 22 000 heures, allant des séries et films africains, Télénovelas, Animation, aux productions à succès provenant des grands studios tels que MGM, Warner, GLOBO, VIACOM International. 
                                </p>

                                <?php get_template_part( 'template-parts/search', 'mini' ); ?>

                            </div>
                            
                            <div class="row">
                            
                                <?php

                                $args = array (
                                    'post_type'        =>'programmes',
                                    'posts_per_page'   => 16,
                                    'order_by'         => 'post_date',
                                    'order'            =>'DESC'
                                );
                                $query_programmes = new WP_Query($args);  
                                if($query_programmes->have_posts() ) :
                                    while ( $query_programmes->have_posts())  : $query_programmes->the_post();
                                    ?>

                                       <div class="col-md-3 col-sm-6">
                                          <img class='attachment-post-thumbnail size-post-thumbnail wp-post-image' src='<?php
                                                //Condition pour affichage ou pas des vignettes des programmes
                                                if(has_post_thumbnail()){
                                                    echo the_post_thumbnail_url(); //Affiche l'image du programme
                                                }else{
                                                    echo "http://localhost/coteouespress/wp-content/uploads/none.jpg"; //Affiche l'image par défaut
                                                }
                                            ?>' alt='<?php the_title(); ?>'>
                                          <figcaption>
                                                <header>
                                                    <?php 
                                                         echo mb_strimwidth( get_the_title(), 0, 15, ' ...' ); 
                                                    ?>
                                              </header>
                                                <span class="pull-left">
                                                    <strong><?php the_field('format'); ?></strong><br/>
                                                    <?php 
                                                        foreach((get_the_category()) as $cat) { 
                                                            echo $cat->cat_name; 
                                                            }
                                                    ?><br/>
                                                </span>
                                                <span class="pull-right"><a href="<?php the_permalink(); ?>">Voir+</a></span>
                                                <span style="clear:both"></span>
                                            </figcaption>
                                        </div> 

                                    <?php
                                    endwhile;

                                endif;

                                ?>                            

                        </div>
                            
                        </div>
                    </div>
                    
                </div>
                
            </div>        
            
        </div>  
        
    </main>
    <!--container end-->



<?php get_footer(); ?>