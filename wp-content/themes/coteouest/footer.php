<!--container end-->

<!--footer start-->
    <footer class="footer">
      <div class="container">
        <div class="row">
          
          <div class="col-lg-3 col-sm-3">
            <div class="text-footer wow fadeInUp" data-wow-duration="2s" data-wow-delay=".7s">
                
                <p>
                    <img src="<?php echo get_stylesheet_directory_uri(); ?>/src/imgs/logBlanc.png" />
                </p>
                <p style="padding-top:40px;">
                    This is a text widget.Lorem ipsum dolor sit amet. This is a text widget.Lorem ipsum dolor sit amet
                </p>
              
            </div>
          </div>
            <div class="col-lg-3 col-sm-3 address wow fadeInUp" data-wow-duration="2s" data-wow-delay=".1s">
            <h1>
              contact info
            </h1>
            <address>
              <p><i class="fa fa-home pr-10"></i>Address: No.XXXXXX street</p>
              <p><i class="fa fa-globe pr-10"></i>Mars city, Country </p>
              <p><i class="fa fa-mobile pr-10"></i>Mobile : (123) 456-7890 </p>
              <p><i class="fa fa-phone pr-10"></i>Phone : (123) 456-7890 </p>
              <p><i class="fa fa-envelope pr-10"></i>Email :   <a href="javascript:;">support@example.com</a></p>
            </address>
          </div>
          <div class="col-lg-3 col-sm-3 wow fadeInUp" data-wow-duration="2s" data-wow-delay=".3s">
            <h1>latest tweet</h1>
              <div class="tweet-box">
                <i class="fa fa-twitter"></i>
                <em>
                  Please follow
                  <a href="javascript:;">@example</a>
                  for all future updates of us!
                  <a href="javascript:;">twitter.com/acme</a>
                </em>
              </div>
              <div class="tweet-box">
                <i class="fa fa-twitter"></i>
                <em>
                  Please follow
                  <a href="javascript:;">@example</a>
                  for all future updates of us!
                  <a href="javascript:;">twitter.com/acme</a>
                </em>
              </div>
              
          </div>
          <div class="col-lg-3 col-sm-3">
            <div class="page-footer wow fadeInUp" data-wow-duration="2s" data-wow-delay=".5s">
              <h1>
                Our Company
              </h1>
              <ul class="page-footer-list">
                <li>
                  <i class="fa fa-angle-right"></i>
                  <a href="about.html">About Us</a>
                </li>
                <li>
                  <i class="fa fa-angle-right"></i>
                  <a href="faq.html">Support</a>
                </li>
                <li>
                  <i class="fa fa-angle-right"></i>
                  <a href="service.html">Service</a>
                </li>
                <li>
                  <i class="fa fa-angle-right"></i>
                  <a href="privacy-policy.html">Privacy Policy</a>
                </li>
                <li>
                  <i class="fa fa-angle-right"></i>
                  <a href="career.html">We are Hiring</a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </footer>
   <!--footer end -->
    <!--small footer start -->
    <footer class="footer-small">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-sm-4 pull-right">
                    <ul class="social-link-footer list-unstyled">
                        <li class="wow flipInX" data-wow-duration="2s" data-wow-delay=".1s"><a href="#"><i class="fa fa-facebook"></i></a></li>
                        <li class="wow flipInX" data-wow-duration="2s" data-wow-delay=".5s"><a href="#"><i class="fa fa-twitter"></i></a></li>
                        <li class="wow flipInX" data-wow-duration="2s" data-wow-delay=".8s"><a href="#"><i class="fa fa-youtube"></i></a></li>
                    </ul>
                </div>
                <div class="col-md-6">
                  <div class="copyright">
                    <p>&copy;Copyright - Tous droits réservés à Côte Ouest Audovisuel</p>
                  </div>
                </div>
            </div>
        </div>
    </footer>
    <!--small footer end-->
    <script src="<?php echo get_stylesheet_directory_uri(); ?>/src/js/jquery-1.8.3.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/src/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/src/js/hover-dropdown.js"></script>
    <!-- <script defer src="<?php //echo get_stylesheet_directory_uri(); ?>/src/js/jquery.flexslider.js"></script> -->
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/src/assets/bxslider/jquery.bxslider.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/src/js/wow.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/src/js/jquery.easing.min.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/src/js/link-hover.js"></script>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/src/js/superfish.js"></script>
<?php
    if(is_front_page())
    {
?>
    <script type="text/javascript" src="<?php echo get_stylesheet_directory_uri(); ?>/src/rs-plugin/js/jquery.themepunch.revolution.min.js"></script> 
    <script type="text/javascript">

				var tpj=jQuery;
				
				tpj(document).ready(function() {

				if (tpj.fn.cssOriginal!=undefined)
					tpj.fn.css = tpj.fn.cssOriginal;

					var api = tpj('.fullwidthbanner').revolution(
						{
							delay:9000,
							startwidth:960,
							startheight:500,

							onHoverStop:"on",						// Stop Banner Timet at Hover on Slide on/off

							thumbWidth:100,							// Thumb With and Height and Amount (only if navigation Tyope set to thumb !)
							thumbHeight:50,
							thumbAmount:3,

							hideThumbs:0,
							navigationType:"bullet",				// bullet, thumb, none
							navigationArrows:"solo",				// nexttobullets, solo (old name verticalcentered), none

							navigationStyle:"round",				// round,square,navbar,round-old,square-old,navbar-old, or any from the list in the docu (choose between 50+ different item), custom


							navigationHAlign:"center",				// Vertical Align top,center,bottom
							navigationVAlign:"bottom",				// Horizontal Align left,center,right
							navigationHOffset:30,	
							navigationVOffset:10,					// Bottom round		

							soloArrowLeftHalign:"left",
							soloArrowLeftValign:"center",
							soloArrowLeftHOffset:20,
							soloArrowLeftVOffset:0,

							soloArrowRightHalign:"right",
							soloArrowRightValign:"center",
							soloArrowRightHOffset:20,
							soloArrowRightVOffset:0,

							touchenabled:"on",						// Enable Swipe Function : on/off


							stopAtSlide:-1,							// Stop Timer if Slide "x" has been Reached. If stopAfterLoops set to 0, then it stops already in the first Loop at slide X which defined. -1 means do not stop at any slide. stopAfterLoops has no sinn in this case.
							stopAfterLoops:-1,						// Stop Timer if All slides has been played "x" times. IT will stop at THe slide which is defined via stopAtSlide:x, if set to -1 slide never stop automatic

							hideCaptionAtLimit:0,					// It Defines if a caption should be shown under a Screen Resolution ( Basod on The Width of Browser)
							hideAllCaptionAtLilmit:0,				// Hide all The Captions if Width of Browser is less then this value
							hideSliderAtLimit:0,					// Hide the whole slider, and stop also functions if Width of Browser is less than this value


							fullWidth:"on",

							shadow:1								//0 = no Shadow, 1,2,3 = 3 Different Art of Shadows -  (No Shadow in Fullwidth Version !)

						});


						// TO HIDE THE ARROWS SEPERATLY FROM THE BULLETS, SOME TRICK HERE:
						// YOU CAN REMOVE IT FROM HERE TILL THE END OF THIS SECTION IF YOU DONT NEED THIS !
							api.bind("revolution.slide.onloaded",function (e) {


								jQuery('.tparrows').each(function() {
									var arrows=jQuery(this);

									var timer = setInterval(function() {

										if (arrows.css('opacity') == 1 && !jQuery('.tp-simpleresponsive').hasClass("mouseisover"))
										  arrows.fadeOut(300);
									},3000);
								})

								jQuery('.tp-simpleresponsive, .tparrows').hover(function() {
									jQuery('.tp-simpleresponsive').addClass("mouseisover");
									jQuery('body').find('.tparrows').each(function() {
										jQuery(this).fadeIn(300);
									});
								}, function() {
									if (!jQuery(this).hasClass("tparrows"))
										jQuery('.tp-simpleresponsive').removeClass("mouseisover");
								})
							});
						// END OF THE SECTION, HIDE MY ARROWS SEPERATLY FROM THE BULLETS

			});
    </script>
    
    <script type="text/javascript">
      jQuery(document).ready(function() {

        $('.bxslider1').bxSlider({
          minSlides: 4,
          maxSlides: 4,
          slideWidth: 165,
          slideMargin: 30,
          moveSlides: 2,
          responsive: true,
          nextSelector: '#slider-next1',
          prevSelector: '#slider-prev1',
          nextText: '<img src="<?php echo get_stylesheet_directory_uri(); ?>/src/imgs/icones/next.jpg" />',
          prevText: '<img src="<?php echo get_stylesheet_directory_uri(); ?>/src/imgs/icones/prev.jpg" />'
        });
          
        $('.bxslider2').bxSlider({
          minSlides: 4,
          maxSlides: 5,
          slideWidth: 165,
          slideMargin: 30,
          moveSlides: 2,
          responsive: true,
          nextSelector: '#slider-next2',
          prevSelector: '#slider-prev2',
          nextText: '<img src="<?php echo get_stylesheet_directory_uri(); ?>/src/imgs/icones/next.jpg" />',
          prevText: '<img src="<?php echo get_stylesheet_directory_uri(); ?>/src/imgs/icones/prev.jpg" />'
        });
          
        $('.bxslider3').bxSlider({
          minSlides: 4,
          maxSlides: 5,
          slideWidth: 165,
          slideMargin: 30,
          moveSlides: 2,
          responsive: true,
          nextSelector: '#slider-next3',
          prevSelector: '#slider-prev3',
          nextText: '<img src="<?php echo get_stylesheet_directory_uri(); ?>/src/imgs/icones/next.jpg" />',
          prevText: '<img src="<?php echo get_stylesheet_directory_uri(); ?>/src/imgs/icones/prev.jpg" />'
        });

      });


    </script>
<?php
    }
?>

<script>    
    // For Demo purposes only (show hover effect on mobile devices)
    [].slice.call( document.querySelectorAll('a[href="#"') ).forEach( function(el) {
    el.addEventListener( 'click', function(ev) { ev.preventDefault(); } );
    } );
</script>
</body>
</html>