<?php
/*
Template Name: Acteurs store
*/

get_header(); 
get_template_part( 'template-parts/breadcrumb' );

get_template_part( 'template-parts/featured-image' ); ?>

   <!--container start-->
    <main id="programmes">
        <div>
            
        </div>
        <div class="container">
            
            <div class="row">
                <div class="col-md-3">
                    
                    <?php get_template_part( 'template-parts/programmes', 'menu' ); ?>
                    
                </div>
                
                <div class="col-md-9">
                    
                    <div class="row">
                        <div class="col-md-11 col-md-offset-1 blanc">
                            
                            <!-- -->
                            <h1 class="titlle">
                                ACTEURS
                            </h1>     


                            <div class="row">

                                <?php
                                $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;                 
                                $args = array (
                                    'paged' => $paged,
                                    'page' => $paged,
                                    'post_type'        =>'acteurs',
                                    'posts_per_page'   => 8,
                                    'order'            =>'ASC'
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
                                            ?>' alt='<?php the_title(); ?>' width="165px" height="260px">
                                          <figcaption>
                                                
                                                <span class="pull-left">
                                                <header>
                                                    <?php  
                                                       echo mb_strimwidth( get_the_title(), 0, 15, ' ...' ); 
                                                    ?>
                                                </header>
                                                </span>
                                                <span class="pull-right"><a href="<?php the_permalink(); ?>">Voir+</a></span>
                                                <span style="clear:both"></span>
                                            </figcaption>
                                        </div> 

                                    <?php
                                    endwhile;
                                ?>

                        </div>
                                <?php
                                wp_reset_postdata();
                                    if (function_exists(custom_pagination)) {
                                        custom_pagination($query_programmes->max_num_pages, "", $paged);
                                            }

                                endif; 

                                ?>
                        <!-- -->
                            
                        </div>
                    </div>
                    
                </div>
                
            </div>        
            
        </div>  
        
    </main>
    <!--container end-->


<?php get_footer(); ?>