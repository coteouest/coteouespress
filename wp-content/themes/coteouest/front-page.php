<?php
/**
 * The main template file
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * e.g., it puts together the home page when no home.php file exists.
 *
 * Learn more: {@link https://codex.wordpress.org/Template_Hierarchy}
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */

get_header(); ?>

<div class="container">
      <div class="row">
        <div class="col-md-12">
     <!-- service -->       
     <div id="home-services">

      <div class="container mar-b-50">
        <div class="row">
          <div class="col-md-12">
            <h2>
              Espace privé
            </h2>
          </div>

          <div class="col-md-6">
            <div class="h-service">
              <div class="icon-wrap ico-bg round-fifty wow fadeInDown">
                <i class="fa fa-lock">
                </i>
              </div>
              <div class="h-service-content wow fadeInUp">
                <h3>
                  SE CONNECTER
                </h3>
                <p>
                  Connectez-vous à votre espace.
                  <br /><br />
                  <a href="#">
                    Cliquez ici si vous avez déjà un compte
                  </a>
                </p>
              </div>
            </div>
          </div>
          
          <div class="col-md-6">
            <div class="h-service">
              <div class="icon-wrap ico-bg round-fifty wow fadeInDown">
                <i class="fa fa-user">
                </i>
              </div>
              <div class="h-service-content wow fadeInUp">
                <h3>
                  S'ENREGISTRER
                </h3>
                <p>
                  Si vous n'avez pas de compte, veuillez en créer un ici.
                  <br /><br />
                  <a href="#">
                    Cliquez ici pour créer un compte
                  </a>
                </p>
              </div>
            </div>
          </div>
        </div>
        <!-- /row -->

      </div>
      <!-- /container -->

    </div>
    <!-- service end -->    
            
          
          <!--feature end-->
        </div>
      </div>
    
</div>


<div id="une" class="mar-b-50">

    <div class="container">
      <div class="col-md-12">
          <div class="feature-head wow fadeInDown">
            <div class="row">
                <div class="pull-left">
                    <h1 class="">
                        A LA <strong>UNE</strong>
                    </h1>
                </div>
                <div class="direction__new1 pull-right">
                    <ul class="nav nav-pills">
                          <li class="active"><a href="#tab1" data-toggle="tab">AFRIQUE</a></li>
                          <li><a href="#tab2" data-toggle="tab">RESTE DU MONDE</a></li>
                    </ul>
                </div>
            </div>

            <div class="row">
                <div class="tab-content">
                    
                    
                    <?php get_template_part( 'template-parts/front/une', 'view' ); ?>
                    
                </div>
                
            </div>
              
          </div>

          <!--feature end-->
    </div>
</div>  
    
</div>

  
      
<div class="container mar-b-50">
    <div class="row">
    
        <div class="col-md-8 mar-b-50">
            
            <div class="row">
            
                <div class="col-md-12">
                    <div class="feature-head wow fadeInDown">
                        <div class="row">
                            <div class="col-md-6">
                                <h1 class="">
                                  Telenovelas
                                </h1>
                            </div>
                            <div class="col-md-6 direction__new">
                                <span id="slider-prev1"></span>
                                <span id="slider-next1"></span>
                            </div>
                        </div>     
                        
                        <div class="row">
                            <div class="col-md-12 wow fadeInUp">
                                
                                <?php get_template_part( 'template-parts/front/telenovelas', 'view' ); ?>
                                
                            </div> 
                        </div>
                      </div>
                        
                    </div>
                </div>
            
                <div class="row">
            
                <div class="col-md-12">
                    <div class="feature-head wow fadeInDown">
                        <div class="row">
                            <div class="col-md-6">
                                <h1 class="">
                                  Séries
                                </h1>
                            </div>
                            <div class="col-md-6 direction__new">
                                <span id="slider-prev3"></span>
                                <span id="slider-next3"></span>
                            </div>
                        </div>     
                        
                        <div class="row">
                            <div class="col-md-12 wow fadeInUp">
                                <?php get_template_part( 'template-parts/front/series', 'view' ); ?>
                            </div> 
                        </div>
                      </div>
                        
                    </div>
                </div>
            
                <div class="row">
            
                <div class="col-md-12">
                    <div class="feature-head wow fadeInDown">
                        <div class="row">
                            <div class="col-md-6">
                                <h1 class="">
                                  Films
                                </h1>
                            </div>
                            <div class="col-md-6 direction__new">
                                <span id="slider-prev2"></span>
                                <span id="slider-next2"></span>
                            </div>
                        </div>     
                        
                        <div class="row">
                            <div class="col-md-12 wow fadeInUp">                    
                                <?php get_template_part( 'template-parts/front/films', 'view' ); ?>
                            </div> 
                        </div>
                      </div>
                        
                    </div>
                </div>
            
                
            
            </div>
        
            <div class="col-md-4">
                
                <div class="row">
                    
                    <div class="col-md-12">
                    <div class="feature-head wow fadeInDown">
                        <h1 class="">
                          Restez à la une
                        </h1>
                        
                        <h4>Dernière bande annonce</h4>
                        
                        <div class="video-conteneur">
                               
                            <?php 

                                $args_vimeo = array ('post_type'=>'bande_annonce','showposts'=>1,'order'=>'DESC'); 
                                $query_vimeo = new WP_Query($args_vimeo);  

                                if($query_vimeo->have_posts() ) :
                                    while ( $query_vimeo->have_posts())  : $query_vimeo->the_post();
                             ?>
                                  <iframe src="https://player.vimeo.com/video/<?php echo the_field('code_url_vimeo'); ?>" frameborder="0" webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>  
                             <?php
                                    endwhile;
                                endif; wp_reset_query();

                            ?>
                        </div>
                          

                      </div>
                    </div>
                
                </div>
                
                <div class="row" id="biographie">
                    
                    <div class="col-md-12">
                    <div class="feature-head wow fadeInDown">
                        <h1 class="">
                            Biographie d'acteurs
                        </h1>
                        <?php get_template_part( 'template-parts/front/acteurs', 'view' ); ?>
                      </div>
                    </div>
                
                </div>
                
                <div class="row" id="letters">
                    
                    <div class="col-md-12">
                    <div class="feature-head wow fadeInDown">
                        <h1 class="">
                          Newsletter
                        </h1>

                        <div id="mc_embed_signup">
                        <form action="https://coteouest.us7.list-manage.com/subscribe/post?u=3494494e82585da60a2af6049&amp;id=912594e678" method="post" id="mc-embedded-subscribe-form" name="mc-embedded-subscribe-form" class="validate" target="_blank" novalidate>
                            <div id="mc_embed_signup_scroll">
                                <label for="mce-EMAIL">Email</label>
                                <input type="email" value="" name="EMAIL" class="email" id="mce-EMAIL" placeholder="Entrez votre mail ici" required>
                                <!-- real people should not fill this in and expect good things - do not remove this or risk form bot signups-->
                                <div style="position: absolute; left: -5000px;" aria-hidden="true"><input type="text" name="b_3494494e82585da60a2af6049_912594e678" tabindex="-1" value=""></div>
                                <div class="clear">
                                <button type="submit" class="btn btn-default" name="subscribe" class="button" id="mc-embedded-subscribe">Envoyer</button>
                               
                                </div>
                            </div>
                        </form>
                        </div>

<!--End mc_embed_signup-->

                      </div>
                    </div>
                
                </div>
    
            </div>
        </div>
        
    
</div>


<?php get_footer(); ?>