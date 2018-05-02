<?php
/**
 * The template for displaying archive pages
 *
 * Used to display archive-type pages if nothing more specific matches a query.
 * For example, puts together date-based pages if no date.php file exists.
 *
 * If you'd like to further customize these archive views, you may create a
 * new template file for each one. For example, tag.php (Tag archives),
 * category.php (Category archives), author.php (Author archives), etc.
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); 
get_template_part( 'template-parts/breadcrumb' );

while ( have_posts() ) : the_post(); ?>

    <!--container start-->
    <main id="programmes" class="mar-b-50">
        <div>
            
        </div>
        <div class="container">
            
            <div class="row">
                <div class="col-md-3">
                    
                    <?php get_sidebar(); ?>
                    
                </div>
                
                <div class="col-md-9">
                    
                    <!-- -->
                    <h1 class="titlle">
                        <?php the_title(); ?>
                    </h1>  
                    
                    <div class="row">
                            
                        <?php
                        $paged = (get_query_var('page')) ? get_query_var('page') : 1;
                        $args = array (
                            'post_type'        =>'programmes',
                            'posts_per_page'   => 16,
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
                                        <header><?php echo ucfirst(the_title('<header>', '</header>', false)); ?></header>
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
                        if (function_exists(custom_pagination)) {
                                custom_pagination($query_programmes->max_num_pages, "", $paged);
                            } 
                        endif; wp_reset_query();
                        
                        ?>
                            
                            
                </div>
                    <!-- -->
                    
                </div>
                
            </div>        
            
        </div>  
        
    </main>
    <!--container end-->

<?php endwhile;?>

<?php get_footer(); ?>