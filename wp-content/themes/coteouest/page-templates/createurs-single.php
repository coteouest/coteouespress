<?php
/*
Template Name: Createurs
Template Post Type: realisateurs, createurs
*/


    get_header(); 

    get_template_part( 'template-parts/breadcrumb' );
?>

   <main id="programmes">
       <?php while ( have_posts() ) : the_post(); ?>
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
                            <h1 class="titlle">
                                <?php the_title(); ?>
                            </h1>
                            
                            <p>                                
                                <?php the_content(); ?>
                            </p>
                            
                        </div>                        
                    </div>
                    
                    <div class="row separe">
                        <div class="col-md-11 col-md-offset-1 blanc">
                            
                            <h3 class="capitalize">
                                <span style="color: #006699;">Voir plus de programmes de <?php the_title(); ?></span>
                            </h3>
                            <br />
                            <div class="row">
                                <?php
                                    $programmes = get_posts(array(
                                    'post_type' => 'programmes',
                                    'meta_query' => array(
                                        array(
                                            'key' => 'crearea', // name of custom field
                                            'value' => '"' . get_the_ID() . '"',
                                            'compare' => 'LIKE'
                                            )
                                        )
                                    ));
                                
                                //$get_programmes = get_field('$programmes');
                            
                                if($programmes)
                                {
                                    foreach($programmes as $showprogrammes):
                                    
                            ?>
                                    <div class="col-md-3 col-sm-6">
                                            <img src="<?php
                                            //Condition pour affichage ou pas des vignettes des programmes
                                            if(has_post_thumbnail($showprogrammes->ID)){
                                                echo get_the_post_thumbnail_url($showprogrammes->ID); //Affiche l'image du programme
                                            }else{
                                                echo 'http://localhost/coteouespress/wp-content/uploads/none.jpg'; //Affiche l'image par défaut
                                            }
                                        ?>" alt="<?php get_the_title( $showprogrammes->ID ); ?>" />
                                            <br />
                                            <a href="<?php echo get_permalink( $showprogrammes->ID ); ?>">
                                                <?php echo mb_strimwidth( get_the_title($showprogrammes->ID), 0, 20, ' ...' ); ?>
                                            </a>
                                        
                                    </div>
                            <?php
                                    endforeach;
                                }
                                
                            ?>                                        
                            </div>
                            
                        </div>                        
                    </div>
                
                </div>        
         </div>    
        </div>  
        <?php endwhile; // end of the loop. ?>
    </main>

<?php get_footer(); ?>