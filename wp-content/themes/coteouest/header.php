<?php
/**
 * The template for displaying the header
 *
 * Displays all of the head element and everything up until the "container" div.
 *
 * @package FoundationPress
 * @since FoundationPress 1.0.0
 */
?>
<html class="no-js" <?php language_attributes(); ?> >
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <?php wp_head(); ?>
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Open+Sans%3A400%2C300&#038;ver=4.2.13' type='text/css' media='all' />
    <link rel='stylesheet' href='http://fonts.googleapis.com/css?family=Source+Sans+Pro:400,300,600,700' type='text/css' media='all' />
    <link href='http://fonts.googleapis.com/css?family=Lato' rel='stylesheet' type='text/css' media='all'>
    <?php
    if(is_front_page())
    {
    ?>
        <link href="//cdn-images.mailchimp.com/embedcode/horizontal-slim-10_7.css" rel="stylesheet" type="text/css">
    <?php
    }
    ?>
</head>
<body>
    
<aside id="top-header">
    <div class="container">
        <div class="pull-left">123</div>
        <div class="pull-right">123</div>
    </div>
</aside>

<!--header start-->
<header class="head-section">
    <div class="navbar navbar-default navbar-static-top container">
        <div class="navbar-header">
              <button class="navbar-toggle" data-target=".navbar-collapse" data-toggle="collapse" type="button">
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
              </button>
              <a class="navbar-brand" href="<?php bloginfo('url'); ?>"><img src="<?php echo get_stylesheet_directory_uri(); ?>/src/imgs/logS.png" /></a>
          </div>
        <div class="navbar-collapse collapse">
              <ul class="nav navbar-nav">
                  <li>
                      <a href="<?php bloginfo('url'); ?>">News</a>
                  </li>
                  <li>
                      <a href="contact.html">A propos de nous</a>
                  </li>
                  
                  <li class="dropdown">
                      <a class="dropdown-toggle" data-close-others="false" data-delay="0" data-hover=
                      "dropdown" data-toggle="dropdown" href="#">Programmes <i class="fa fa-angle-down"></i>
                      </a>
                      <ul class="dropdown-menu">
                          <li>
                              <a href="#">Telenovelas</a>
                          </li>
                          <li>
                              <a href="#">Séries</a>
                          </li>
                          <li>
                              <a href="#">Animations</a>
                          </li>
                          <li>
                              <a href="#">Documentaires</a>
                          </li>
                          <li>
                              <a href="#">Films</a>
                          </li>
                          
                      </ul>
                  </li>
                  <li>
                      <a href="#">Contact</a>
                  </li>
                  <li>
                      <form role="search" method="get" class="search-form" action="<?php echo home_url( '/' ); ?>">
                        <input name="s" class="form-control search" placeholder=" Recherche ..." type="text" value="<?php echo get_search_query() ?>" />
                    </form>
                  </li>
              </ul>
          </div>
    </div>
</header>
<!--header end-->
    
<?php
    if(is_front_page())
    {
?>
<!-- Sequence Modern Slider --> 
<div class="row">
        <div class="fullwidthbanner-container">
            <div class="fullwidthbanner">
                <ul>
                    <?php get_template_part( 'template-parts/front/slide', 'view' ); ?>
                </ul>
            <div class="tp-bannertimer tp-bottom"></div>
            </div>
          </div>
</div>
<?php 
    } 
?>