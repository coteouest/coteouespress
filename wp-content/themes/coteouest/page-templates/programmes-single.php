<?php
/*
Template Name: Programmes
Template Post Type: programmes
*/

    get_header(); 

    get_template_part( 'template-parts/breadcrumb' );
?>

    <main id="fiche">
        <?php while ( have_posts() ) : the_post(); ?>
        <div class="container" id="fiche__details">
        
            <h1 class="title__main">
                <?php the_field('titre_original'); ?> (<span class="othersTitle"><?php the_title(); ?></span>)
            </h1>
            <br />
            
            <div class="row separe">
                
                <div class="col-md-3">
                    
                    <div class="row">
                        <div class="col-md-12 blanc">
                            <h3>Détails techniques</h3>
                            <br />  
                            <p>
                                <strong>Version(s) disponible(s):</strong> <?php echo get_field('versions_disponibles'); ?>
                            </p>
                            
                            <p>
                            <strong>Date(s):</strong> <?php the_field('annee'); ?>
                            </p>

                            <p>
                                <strong>Origine(s):</strong> <?php the_terms( $post->ID, 'pays' ,  ' ' ); ?>
                            </p>

                            <p>
                                <strong>Format(s):</strong> <?php the_field('format'); ?>
                            </p>

                            <p>
                                <strong>Catégorie:</strong> 
                                <?php
                                    echo get_the_term_list($post->ID, 'category', '', ', ', '');
                                ?>
                            </p>

                            <p>
                                <strong>Genre(s):</strong> 
                                <span style="text-transform: capitalize;">
                                <?php
                                    the_terms( $post->ID, 'Genres' ,  ' ' );
                                ?>
                                </span>
                            </p>
                            
                            <p style="padding-bottom:0; margin-bottom:0; display: inline-block">
                                <strong>Langue(s):</strong></p>
                                <ul class="list-unstyled" id="langu_age">
                                <?php
                                    $get_version = get_field('langue');
                                    if($get_version){
                                        foreach($get_version as $version):

                                            echo "<li>".$version."</li>";

                                        endforeach;
                                    }
                                    else
                                    {
                                        echo "Non défini";
                                    }
                                ?>
                                </ul>
                        </div>
                    </div>
                    
                    <div class="row separe">
                        <div class="col-md-12 blanc">                 
                            <h3>
                                <?php 
                                foreach((get_the_category()) as $category) { 
                                    $cat_slug= $category->cat_name; 
                                    if($cat_slug == "Télénovelas" OR $cat_slug =="Séries"){
                                        echo "Créateurs";                                        
                                        }else{
                                            echo "Réalisateurs";                                   
                                    }
                                }                                    
                                ?>
                            </h3>
                            <br /> 

                            <div class="row">

                                <?php
                                        $get_crearea = get_field('crearea');

                                        if($get_crearea)
                                        {
                                            foreach($get_crearea as $crearea):
                                    ?>
                                            <div class="col-md-6" style="text-align:center">
                                                <a href="<?php echo get_permalink( $crearea->ID ); ?>">
                                                    <img src="<?php
                                                    //Condition pour affichage ou pas des vignettes des programmes
                                                    if(has_post_thumbnail($crearea->ID)){
                                                        echo get_the_post_thumbnail_url($crearea->ID); //Affiche l'image du programme
                                                    }else{
                                                        echo 'http://localhost/coteouespress/wp-content/uploads/no-pic-actor.jpg'; //Affiche l'image par défaut
                                                    }
                                                ?>" alt="<?php get_the_title( $crearea->ID ); ?>" height="116px" />
                                                    <br />
                                                    <?php echo mb_strimwidth( get_the_title( $crearea->ID ), 0, 11, ' ...' ); ?>
                                                </a>
                                            </div>
                                    <?php
                                            endforeach;
                                        }
                                        else{
                                            echo "<center>Non défini</center>";
                                        }
                                    ?>

                            </div>
                        </div>
                    </div>
                    
                    <div class="row separe">
                        <div class="col-md-12 blanc">
                            <h3>Casting</h3>
                            <br />
                            <div class="row">
                                <?php
                                    $get_casting = get_field('acteurs');

                                    if($get_casting)
                                    {
                                        foreach($get_casting as $casting):
                                ?>
                                    <div class="col-md-6" style="text-align:center">
                                        <a href="<?php echo get_permalink($casting->ID); ?>">
                                            <img src="<?php
                                            //Condition pour affichage ou pas des vignettes des programmes
                                            if(has_post_thumbnail($casting->ID)){
                                                echo get_the_post_thumbnail_url($casting->ID); //Affiche l'image du programme
                                            }else{
                                                echo 'http://localhost/coteouespress/wp-content/uploads/no-pic-actor.jpg'; //Affiche l'image par défaut
                                            }
                                        ?>" alt="<?php get_the_title( $casting->ID ); ?>" class="img-thumbnail" />
                                            <br />
                                            <?php echo mb_strimwidth( get_the_title( $casting->ID ), 0, 11, ' ...' ); ?>
                                        </a>
                                    </div>
                                <?php
                                        endforeach;
                                    }
                                    else{
                                        echo "<center>Aucun acteur connu pour ce programme.</center>";
                                    }
                                ?>
                            </div>
                        </div>
                    </div>
                                        
                    
                    <p class="separe">
                        <a class="btn btn-default" href="#" role="button">Plus d'informations</a>
                    </p>
                    
                </div>
                
                <div class="col-md-9">
                    
                    <div class="row">
                        
                        <div class="col-md-11 col-md-offset-1">
                            <div class="noir" id="trailer">
                               <?php 
                                    if(!is_user_logged_in()){ 
                                        do_action('showvideos', $liens = get_field('trailers'));
                                    }
                                    if(is_user_logged_in())
                                    { 
                                        the_content();  
                                    } 
                                ?>
                            </div>                            
                        </div>
                        
                    </div>
                    
                    <div class="row separe">
                        <div class="col-md-11 col-md-offset-1 blanc">
                            <h3>Synopsis</h3>
                            <br />   
                            <?php echo get_field('synopsis'); ?>
                        </div>
                    </div>
                                        
                    
                    <div class="row separe">
                        <div class="col-md-11 col-md-offset-1 blanc">
                            <h3>Galérie photos</h3>
                            <br />

                                <?php
                                    $get_galleries = get_field('photos');

                                    if($get_galleries)
                                    {
                                        foreach($get_galleries as $gallerie):
                                ?>
                                <div class="row">
                                    <div class="col-md-12" style="text-align:center">
                                        <h3>
                                            <?php 
                                                echo  get_the_title($gallerie->ID); 
                                            ?>
                                        </h3>
                                        <?php 
                                            //Affichage du contenu du THE CONTENT
                                            $post   = get_post( $gallerie->ID );
                                            $output =  apply_filters( 'the_content', $post->post_content );
                                            echo $output;
                                            //Affichage des photos
                                          $images = acf_photo_gallery('photos', $gallerie->ID);
                                            //Check if return array has anything in it
                                            if( count($images) ):
                                                //Cool, we got some data so now let's loop over it
                                                foreach($images as $image):
                                                    $id = $image['id']; // The attachment id of the media
                                                    $title = $image['title']; //The title
                                                    $caption= $image['caption']; //The caption
                                                    $full_image_url= $image['full_image_url']; //Full size image url
                                                    $full_image_url = acf_photo_gallery_resize_image($full_image_url, 262, 160); //Resized size to 262px width by 160px height image url
                                                    $thumbnail_image_url= $image['thumbnail_image_url']; //Get the thumbnail size image url 150px by 150px
                                                    $url= $image['url']; //Goto any link when clicked
                                                    $target= $image['target']; //Open normal or new tab
                                                    $alt = get_field('photo_gallery_alt', $id); //Get the alt which is a extra field (See below how to add extra fields)
                                                    $class = get_field('photo_gallery_class', $id); //Get the class which is a extra field (See below how to add extra fields)
  
                                        ?>
                                        <div class="col-xs-6 col-md-3">
                                            <div class="thumbnail">
                                                <a class="fancybox image" href="<?php echo $full_image_url; ?>" target="_blank">
                                                    <img src="<?php echo $full_image_url; ?>" alt="<?php echo $title; ?>" title="<?php echo $title; ?>">
                                                </a>
                                            </div>
                                        </div>
                                        <?php endforeach; endif; ?>
                                    </div>
                                </div>
                                <?php
                                        endforeach;
                                    }
                                    else{
                                        echo "<center>Aucune gallerie n'est associée à ce programme.</center>";
                                    }
                                ?>
                            

                        </div>
                    </div>
                    <!--
                    <div class="row separe" id="videos">
                        <div class="col-md-11 col-md-offset-1 blanc">
                            <h3>Plus de vidéos</h3>
                            <br />
                            <?php
                                /*if(is_user_logged_in()){
                                    
                                }else{
                                    echo "Veuillez vous connecter pour voir plus de vidéos. Inscrivez-vous ici si vous n'avez pas de compte.";
                                }*/
                            ?>
                        </div>
                    </div>
                    -->
                    <div class="row separe" id="videos">
                        <div class="col-md-11 col-md-offset-1 blanc">
                            <!--tab start-->
                              <section class="tab wow fadeInLeft">
                                <header class="panel-heading tab-bg-dark-navy-blue">
                                  <ul class="nav nav-tabs nav-justified ">

                                    <li class="active">
                                      <a data-toggle="tab" href="#news">
                                        Prix et nomination(s)
                                      </a>
                                    </li>
                                    <li class="">
                                      <a data-toggle="tab" href="#notice-board">
                                        Récompense(s)
                                      </a>
                                    </li>
                                  </ul>
                                </header>
                                <div class="panel-body">
                                  <div class="tab-content tasi-tab">
                                    <div id="news" class="tab-pane fade in active">
                                      <p>
                                        <?php

                                          if(get_field('nominations')){
                                              the_field('nominations');
                                          }
                                          else{
                                              echo "Aucun prix et/ou nomination n'est disponible pour ce programme.";
                                          }

                                        ?>
                                      </p>
                                    </div>

                                    <div id="notice-board" class="tab-pane fade">
                                    <p>
                                      <?php

                                          if(get_field('recompense')){
                                              the_field('recompense');
                                          }
                                          else{
                                              echo "Aucune récompense n'est disponible pour ce programme.";
                                          }

                                        ?>
                                    </p>
                                    </div>
                                  </div>
                                </div>
                              </section>
                              <!--tab end-->
                        </div>
                    </div>
                    
                </div>
                
            </div>
            
            <div class="row separe">
                    <div class="col-md-12 blanc">
                        
                        <h3 class="capitalize">
                           <span style="color: #a3860e"><?php echo $cat_slug; ?> à voir absolument</span>
                        </h3>
                    
                        <br />
                        <div class="row" id="tab1">
                           <?php 
                            $args_voir_aussi = array (
                                'post_type'        =>'programmes',
                                'posts_per_page'   => 6,
                                'category_name'    => $categorie,
                                'order'            =>'DESC',
                                'orderby'        => 'rand',
                                'post__not_in' => array(get_the_ID())
                            );

                            $get_voir_aussi = new WP_Query($args_voir_aussi);

                            if($get_voir_aussi->have_posts())
                            {
                                while( $get_voir_aussi->have_posts() ) : $get_voir_aussi->the_post();
                            ?>
                                <div class="col-md-2 col-sm-6">
                                    <img class='attachment-post-thumbnail size-post-thumbnail wp-post-image' src='<?php
                                        //Condition pour affichage ou pas des vignettes des programmes
                                        if(has_post_thumbnail()){
                                            echo the_post_thumbnail_url(); //Affiche l'image du programme
                                        }else{
                                            echo "http://localhost/coteouespress/wp-content/uploads/none.jpg"; //Affiche l'image par défaut
                                        }
                                    ?>' alt='<?php the_title(); ?>'>
                                    <figcaption>
                                        <header><?php echo mb_strimwidth( get_the_title(), 0, 15, ' ...' ); ?></header>
                                        <span class="pull-left">
                                            <strong><?php echo mb_strimwidth( the_field('format'), 0, 10, ' ...' ); ?></strong><br/>
                                            <?php 
                                                foreach((get_the_category()) as $category) { 
                                                    echo $category->cat_name; 
                                                }
                                            ?><br/>
                                        </span>
                                        <span class="pull-right"><a href="<?php the_permalink(); ?>">Voir+</a></span>
                                        <span style="clear:both"></span>
                                    </figcaption>
                                </div>
                            <?php
                                    endwhile;
                            }

                            else{
                                echo "Aucun programme ne correspond à cette catégorie.";
                            }
                            ?>


                        </div>
                    </div>
            </div>
            
        </div>    
        <?php endwhile; // end of the loop. ?>
    </main>

<?php get_footer(); ?>