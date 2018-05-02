<?php
/*
Template Name: Programmes
Template Post Type: programmes
*/

    get_header(); 

    get_template_part( 'template-parts/breadcrumb' );
?>

    <main class="mar-b-50" id="fiche">
        <?php while ( have_posts() ) : the_post(); ?>
        <div class="container" id="fiche__details">
            
            <h1 class="title__main">
                <?php the_title(); ?>
            </h1>
            <h4 class="subtitle">
                <?php the_field('titre_original'); ?>
            </h4>
            
            <div class="row" id="showroom">
                <div class="col-md-12">
                    123
                </div>
            </div>
            
            <div class="row">
                <div class="col-md-3">
                    <h3>Détails techniques</h3>
                    <br />                   
                    <p>
                        <strong>Version(s) disponible(s):</strong><br />
                        <?php
                            $get_version = get_field('langue');
                            if($get_version)
                            {
                                foreach($get_version as $version):
                                
                                    echo $version .' / ';
                                
                                endforeach;
                            }
                            else
                            {
                                echo "Non défini";
                            }
                        ?>
                    </p>
                    
                    <p>
                        <strong>Date:</strong> <?php the_field('annee'); ?>
                    </p>
                    
                    <p>
                        <strong>Origine:</strong> <?php the_terms( $post->ID, 'pays' ,  ' ' ); ?>
                    </p>
                    
                    <p>
                        <strong>Format:</strong> <?php the_field('format'); ?>
                    </p>
                    
                    <p>
                        <strong>Genre:</strong> 
                        <?php
                            the_terms( $post->ID, 'Genres' ,  ' ' );
                        ?>
                    </p>
                    
                    <h3>Réalisateur(s)<br />Créateur(s)</h3>
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
                                            <?php echo get_the_title( $crearea->ID ); ?>
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
                    
                    <p>
                        <a class="btn btn-default" href="#" role="button">Plus d'informations</a>
                    </p>
                    
                </div>
                <div class="col-md-9" id="synopsis">

                    <div class="row">
                        <div class="col-md-12">
                            <h3>Synopsis</h3>
                            <br />

                            <p>
                                <?php the_content(); ?>
                            </p>
                        </div>
                    </div>
                    
                    <div class="row" id="casting">
                        
                        <div class="col-md-12">
                        
                            <h3>Casting</h3>
                        <br />
                        <div class="row">
                            <?php
                                $get_casting = get_field('acteurs');
                            
                                if($get_casting)
                                {
                                    foreach($get_casting as $casting):
                            ?>
                                <div class="col-md-3" style="text-align:center">
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
										<?php echo get_the_title( $casting->ID ); ?>
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
                    
                    
                    
                </div>
            </div>
            
            <div id="prixnomitations">
                
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
                                  echo "Aucune information n'est disponible pour ce programme.";
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
                                  echo "Aucune information n'est disponible pour ce programme.";
                              }
                              
                            ?>
                        </p>
                        </div>
                      </div>
                    </div>
                  </section>
                  <!--tab end-->
                
                
            </div>
            <?php               
                foreach((get_the_category()) as $cat) { 
                    $categorie = $cat->cat_name; 
                }
            ?>
            <div class="row">
                <div class="col-md-12" style="margin-top:50px;">
                    <h3 class="capitalize">
                       <span style="color: #a3860e"><?php echo $categorie; ?> à voir absolument</span>
                    </h3>
                </div>
            </div>
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
                            <header><?php the_title(); ?></header>
                            <span class="pull-left">
                                <strong><?php the_field('format'); ?></strong><br/>
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
        <?php endwhile; // end of the loop. ?>
    </main>

<?php get_footer(); ?>