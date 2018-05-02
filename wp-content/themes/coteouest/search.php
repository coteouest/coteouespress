<?php
/**
 * The template for displaying search results pages.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>

<main id="programmes">
        <div>
            
        </div>
        <div class="container">
            
            <div class="col-md-12 blanc">
                
                <h1 class="entry-title"><?php _e( 'Search Results for', 'foundationpress' ); ?> "<?php echo get_search_query(); ?>"</h1>
                <?php get_template_part( 'template-parts/search', 'mini' ); ?>
            </div>
            
            <?php if ( have_posts() ) : ?>
            
            <div class="col-md-12 blanc">
                
                <div class="row separe">
                    <?php while ( have_posts() ) : the_post(); ?>
                    
                        <div class="col-md-2 col-sm-6">
                                          <img class='attachment-post-thumbnail size-post-thumbnail wp-post-image' src='<?php
                                                //Condition pour affichage ou pas des vignettes des programmes
                                                if(has_post_thumbnail()){
                                                    echo the_post_thumbnail_url(); //Affiche l'image du programme
                                                }else{
                                                    echo "http://localhost/coteouespress/wp-content/uploads/none.jpg"; //Affiche l'image par défaut
                                                }
                                            ?>' alt='<?php the_title(); ?>' width="165px" height="260px">
                                          <figcaption>
                                                <header>
                                                    <?php 
                                                         echo mb_strimwidth( get_the_title(), 0, 15, ' ...' ); 
                                                    ?>
                                              </header>
                                                <span class="pull-left">
                                                    <span style="text-transform: capitalize; font-weight: bold"><?php echo get_post_type(); ?></span><br/>
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
                    
                <?php endwhile; ?>                    
                    
                </div>
                
            </div>
            
            <?php endif; ?>
            
        </div>
</main>

<?php get_footer();