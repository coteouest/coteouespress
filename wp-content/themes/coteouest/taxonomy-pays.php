<?php
/**
 * The template for displaying pages
 *
 * This is the template that displays all pages by default.
 * Please note that this is the WordPress construct of pages and that
 * other "pages" on your WordPress site will use a different template.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
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
                    
                    <div class="row">
                        <div class="col-md-12 blanc">
                            <?php if ( !function_exists( 'dynamic_sidebar' ) || !dynamic_sidebar('Widget Pays') ) ?>
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
                            
                            <!-- -->
                            <h1 class="titlle">
                                <?php
                                    $term = get_term_by( 'slug', get_query_var( 'term' ), get_query_var( 'taxonomy' ) ); 
                                    echo $term->name.'<br />';
                                ?>
                            </h1>  

                            <div class="row">

                                <?php
                                $paged = ( get_query_var('paged') ) ? get_query_var('paged') : 1;
                                $args = array (
                                    'post_type'        =>'programmes',
                                    'posts_per_page'   => 8,
                                    'order'            =>'DESC',
                                    'paged' => $paged,
                                    'page' => $paged,
                                    'tax_query' => array(
                                        array(
                                            'taxonomy' => 'pays',
                                            'field'    => 'slug',
                                            'terms'    => $term->slug,
                                        ),
                                    ),

                                );
                                $query_tax_genres = new WP_Query($args);  

                                if($query_tax_genres->have_posts() ) :
                                    while ( $query_tax_genres->have_posts())  : $query_tax_genres->the_post();
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
                                endif; wp_reset_query();
                                wp_reset_postdata();
                                    if (function_exists(custom_pagination)) {
                                        custom_pagination($query_tax_genres->max_num_pages, "", $paged);
                                            }
                                ?>


                            </div>
                            <!-- -->
                    
                            
                        </div>
                    </div>
                    
                </div>
                
            </div>        
            
        </div>  
        
    </main>
    <!--container end-->


<?php get_footer(); ?>