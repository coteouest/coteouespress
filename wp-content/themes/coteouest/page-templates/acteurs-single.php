<?php
/*
Template Name: Acteurs
Template Post Type: acteurs
*/

   get_header(); 

    get_template_part( 'template-parts/breadcrumb' );
?>

   <main id="programmes" class="mar-b-50">
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
                    
                    <div class="row">
                        <div class="col-md-11 col-md-offset-1 blanc">
                            
                            <h3 class="capitalize">
                                <span style="color: #006699; font-weight: bold;"> <?php the_title(); ?></span> a joué un rôle dans :
                            </h3>
                            <br />
                            <div class="row" id="tab1">
                                <?php
                                    $programmes = get_posts(array(
                                    'post_type' => 'programmes',
                                    'meta_query' => array(
                                        array(
                                            'key' => 'acteurs', // name of custom field
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
                                        <a href="<?php echo get_permalink( $showprogrammes->ID ); ?>">
                                            <img src="<?php
                                            //Condition pour affichage ou pas des vignettes des programmes
                                            if(has_post_thumbnail($showprogrammes->ID)){
                                                echo get_the_post_thumbnail_url($showprogrammes->ID); //Affiche l'image du programme
                                            }else{
                                                echo 'http://localhost/coteouespress/wp-content/uploads/none.jpg'; //Affiche l'image par défaut
                                            }
                                        ?>" alt="<?php get_the_title( $showprogrammes->ID ); ?>" />
                                            <br />
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