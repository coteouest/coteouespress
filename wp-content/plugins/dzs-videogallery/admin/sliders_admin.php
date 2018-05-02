<?php






// -- in action init

//add_action( 'dzsvg_sliders_add_form_fields', 'dzsvg_sliders_admin_add_feature_group_field', 10, 2 );
add_action( 'dzsvg_sliders_edit_form_fields', 'dzsvg_sliders_admin_add_feature_group_field', 10, 10 );

add_filter('dzsvg_sliders_row_actions', 'dzsvg_sliders_admin_duplicate_post_link', 10, 2);
add_action('admin_action_dzsvg_duplicate_slider_term', 'dzsvg_action_dzsvg_duplicate_slider_term', 10, 2);





add_action('admin_init', 'dzsvg_sliders_admin_init',1000);



function dzsvg_sliders_admin_init(){

	global $dzsvg;
	$tax = 'dzsvg_sliders';
	if (  ( isset($_REQUEST['action']) && 'dzsvg_duplicate_slider_term' == $_REQUEST['action'] ) ) {

		if(! ( isset( $_GET['term_id']) || isset( $_POST['term_id']) )){
			wp_die("no term_id set");
		}






		/*
		 * get the original post id
		 */
		$term_id = (isset($_GET['term_id']) ? absint( $_GET['term_id'] ) : absint( $_POST['term_id'] ) );

		$term_meta = get_option("taxonomy_$term_id");

		/*
		$current_user = wp_get_current_user();
		$new_post_author = $current_user->ID;



		 * Nonce verification
		 */

		// -- duplicate
		if ( isset($_GET['duplicate-nonce-for-term-id-'.$term_id]) &&  wp_verify_nonce( $_GET['duplicate-nonce-for-term-id-'.$term_id],'duplicate-nonce-for-term-id-'.$term_id ) ){





			$args = array(
				'post_type' => 'dzsvideo',
				'posts_per_page' => '-1',
				'tax_query' => array(
					array(
						'taxonomy' => 'dzsvg_sliders',
						'field' => 'id',
						'terms' => $term_id
					)
				),
			);
			$query = new WP_Query( $args );



			$reference_term = get_term($term_id,$tax);


			$reference_term_name = $reference_term->name;
			$reference_term_slug = $reference_term->slug;

//			    print_rr($reference_term_name);
//			    print_rr($reference_term_slug);
//			    print_rr($query);


			$new_term_name = $reference_term_name.' '.esc_html("Copy",'dzsvg');
			$new_term_slug = $reference_term_slug.'-copy';
			$original_slug_name = $reference_term_slug.'-copy';


			$ind = 1;
			$breaker = 100;
			while(1){

				$term = term_exists($new_term_name, $tax);
				if ($term !== 0 && $term !== null) {

					$ind++;
					$new_term_name=$reference_term_name.' '.esc_html("Copy",'dzsvg').' '.$ind;;
					$new_term_slug=$original_slug_name.'-'.$ind;
				}else{
					break;
				}

				$breaker--;

				if($breaker<0){
					break;
				}
			}


			$new_term = wp_insert_term(
				$new_term_name, // the term
				$tax, // the taxonomy
				array(

					'slug' => $new_term_slug,
				)
			);



			error_log('query '.print_r($query,true));
			foreach ($query->posts as $po){


			    error_log('duplicate '.$po->ID);
				$dzsvg->duplicate_post($po->ID, array(
					'new_term_slug'=>$new_term_slug,
					'call_from'=>'default',
					'new_tax'=>$tax,
				));



			}

//			$new_term = get_term_by('slug',$new_term_slug,$tax);

//            error_log(print_rr($new_term,array('echo'=>false)));
			$new_term_id = $new_term['term_id'];


			update_option("taxonomy_$new_term_id", $term_meta);
			wp_redirect( admin_url( 'term.php?taxonomy='.$tax.'&tag_ID='.$new_term_id.'&post_type=dzsvideo' ) );

			exit;





//		exit;
		} else {
			$aux = ('invalid nonce for term_id' . $term_id . 'duplicate-nonce-for-term-id-'.$term_id);

			$aux.=print_rr($_SESSION);

			$aux.=' searched nonce - '.$_GET['duplicate-nonce-for-term-id-'.$term_id];
			$aux.=' searched nonce verify - '.wp_verify_nonce( $_GET['duplicate-nonce-for-term-id-'.$term_id],'duplicate-nonce-for-term-id-'.$term_id );


			wp_die($aux);
		}
	}


	// -- export
	if (  ( isset($_REQUEST['action']) && 'dzsvg_export_slider_term' == $_REQUEST['action'] ) ) {


		/*
		 * get the original post id
		 */
		$term_id = (isset($_GET['term_id']) ? absint( $_GET['term_id'] ) : absint( $_POST['term_id'] ) );





		$arr_export = $dzsvg->playlist_export($term_id,array(
		        'download_export'=>true
        ));
		echo json_encode($arr_export);
		die();
		echo json_encode($arr_export);



	exit;

    }







    // -- import

	if(isset($_POST['action']) && $_POST['action']=='dzsvg_import_slider'){

		if(isset($_FILES['dzsvg_import_slider_file'])){

			$file_arr = $_FILES['dzsvg_import_slider_file'];

			$file_cont = file_get_contents($file_arr['tmp_name'],true);

//			print_rr($file_cont);



			$type = 'none';

			$dzsvg->import_slider($file_cont);


		}
	}
}




function dzsvg_action_dzsvg_duplicate_slider_term(  ) {




}
function dzsvg_sliders_admin_duplicate_post_link( $actions, $term ) {

//    error_log(print_rr($term,array('echo'=>false)));
	if (current_user_can('edit_posts')) {



		// Create an nonce, and add it as a query var in a link to perform an action.
		$nonce = wp_create_nonce( 'duplicate-nonce-for-term-id-'.$term->term_id );

		$actions['duplicate'] = '<a href="' . admin_url('edit-tags.php?taxonomy=dzsvg_sliders&post_type=dzsvideo&action=dzsvg_duplicate_slider_term&term_id=' . $term->term_id) . '&duplicate-nonce-for-term-id-' . ($term->term_id) . '='.$nonce.'" title="Duplicate this item" rel="permalink">'.esc_html("Duplicate",'dzsvg').'</a>';
	}


	$actions['export'] = '<a href="' . admin_url('edit-tags.php?taxonomy=dzsvg_sliders&post_type=dzsvideo&action=dzsvg_export_slider_term&term_id=' . $term->term_id) . '" title="'.esc_html("Duplicate this item",'dzsvg').'" rel="permalink">'.esc_html("Export",'dzsvg').'</a>';





	return $actions;
}


function dzsvg_sliders_admin(){

    if(isset($_GET['taxonomy']) && $_GET['taxonomy']=='dzsvg_sliders' ){


        //&& isset($_GET['tag_ID'])
        global $dzsvg;














	    $tax = 'dzsvg_sliders';










//        echo 'here <strong>sliders_admin.php</strong> ';

        wp_enqueue_script('sliders_admin',$dzsvg->base_url.'admin/sliders_admin.js');
        wp_enqueue_script('dzstaa',$dzsvg->base_url.'libs/dzstabsandaccordions/dzstabsandaccordions.js');
        wp_enqueue_style('dzstaa',$dzsvg->base_url.'libs/dzstabsandaccordions/dzstabsandaccordions.css');
	    wp_enqueue_script('dzs.farbtastic', $dzsvg->base_url . "libs/farbtastic/farbtastic.js");
	    wp_enqueue_style('dzs.farbtastic', $dzsvg->base_url . 'libs/farbtastic/farbtastic.css');


        $terms = get_terms( $tax, array(
            'hide_empty' => false,
        ) );

//        print_r($terms);




        $i23=0;

//	    $dzsvg->db_read_mainitems();

//        print_r($dzsvg);

//	    print_rr($dzsvg->options_slider);



	    $selected_term = null;
	    $curr_term = null;
	    $selected_term_id = '';
	    $selected_term_name = '';
	    $selected_term_slug = '';
        if(isset($_GET['tag_ID'])){

	        $curr_term = get_term($_GET['tag_ID'], $tax);


	        if(isset($curr_term)){

		        $selected_term_id = $curr_term->term_id;
		        $selected_term_name = $curr_term->name;
		        $selected_term_slug = $curr_term->slug;
	        }




	        if(isset($_GET['tag_ID'])){
		        $selected_term = $_GET['tag_ID'];

//		        $term = get_term($_GET['tag_ID'], $tax);
//		        $selected_term_name = $term->name;

//            print_r($term);
	        }
        }



	    $term_meta = get_option("taxonomy_$selected_term");

//        echo $selected_term;


        ?>



        <div class="dzsvg-sliders-con" data-term_id="<?php echo $selected_term_id; ?>" data-term-slug="<?php echo $selected_term_slug; ?>">



        <h3 class="slider-label" style="font-weight: normal">
            <span><?php echo __("Editing "); ?></span><span style="font-weight: bold;"><?php echo $selected_term_name; ?></span> <span class="slider-status empty ">
                <div class="slider-status--inner loading"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i> <span class="text-label"><?php echo __("Saving"); ?></span></div>
            </span>
        </h3>


        <div style="">
            <?php

            $val = 'manual';



            $lab = 'feed_mode';



            if(isset($term_meta[$lab]) && $term_meta[$lab]){
	            $val = $term_meta[$lab];
            }

            $nam = $lab;
            echo DZSHelpers::generate_select(' ',array(

	            'input_type'=>'hidden',
	            'class'=>'dzs-style-me  opener-listbuttons option-display-block skin-btn-secondary ',
	            'extraattr'=>' data-aux-name="'.$lab.'"',
	            'seekval'=>$val,
                'options'=>array(
                        'manual',
                        'youtube',
                        'vimeo',
                        'facebook',
                ),
            ));



            ?>
            <ul class="dzs-style-me-feeder">

                <li class="bigoption fig-position selector-btn-secondary"><span class="the-text">Manual <?php echo esc_html__("feed",'dzsvg'); ?></span></li>
                <li class="bigoption fig-position selector-btn-secondary"><span class="the-text">YouTube <?php echo esc_html__("feed",'dzsvg'); ?> &nbsp;<span class="dzstooltip-con"><i style="color: #999999;" class="fa fa-info-circle"></i><span style="width: 190px; padding: 10px; right: -10px; margin-top: 25px;" class="dzstooltip skin-white arrow-top align-right"><?php echo esc_html__("input the youtube link to your channel / playlist / search query in the field below",'dzsvg'); ?></span></span></li>
                <li class="bigoption fig-position selector-btn-secondary"><span class="the-text">Vimeo <?php echo esc_html__("feed",'dzsvg'); ?> &nbsp;<span class="dzstooltip-con"><i style="color: #999999;" class="fa fa-info-circle"></i><span style="width: 190px; padding: 10px; right: -10px; margin-top: 25px;" class="dzstooltip skin-white arrow-top align-right"><?php echo esc_html__("input the vimeo link to your channel / user channel / album in the field below",'dzsvg'); ?></span></span></li>
                <li class="bigoption fig-position selector-btn-secondary"><span class="the-text">Facebook <?php echo esc_html__("feed",'dzsvg'); ?></span></li>

            </ul>

        </div>



        <div class="feed-con for-feed_mode-youtube">
            <h4><?php echo esc_html__("Youtube URL",'dzsvg'); ?></h4>
            <?php
            $val = '';
            $lab = 'youtube_source';
            if(isset($term_meta[$lab])){
                $val = $term_meta[$lab];
            }
            ?>
            <input type="text" class="big-rounded-field" data-aux-name="<?php echo $lab; ?>" value="<?php echo $val; ?>"/>

            <div class="sidenote"><?php echo esc_html__("just paste the link to your channel / playlist / search query",'dzsvg'); ?><br>
	            <?php echo esc_html__("examples.",'dzsvg'); ?>.
            <p><strong><?php echo esc_html__("search query",'dzsvg'); ?></strong>: https://www.youtube.com/results?search_query=cat+and+dog</p>
            <strong><?php echo esc_html__("user channel",'dzsvg'); ?></strong>: https://www.youtube.com/user/digitalzoomstudio
            <p><strong><?php echo esc_html__("playlist",'dzsvg'); ?></strong>: https://www.youtube.com/watch?list=PLBsCKuJJu1paAkH0V0pHcrFvZxRFIPIaG</p>
            </div>
        </div>




        <div class="setting for-feed_mode-youtube">
            <h4><?php echo esc_html__("YouTube Maximum Videos",'dzsvg'); ?></h4>
		    <?php
		    $val = '';
		    $lab = 'youtube_maxlen';
		    if(isset($term_meta[$lab])){
			    $val = $term_meta[$lab];
		    }
		    ?>
            <input type="text" class="big-rounded-field" data-aux-name="<?php echo $lab; ?>" value="<?php echo $val; ?>"/>
            <div class="sidenote"><?php echo sprintf(esc_html__("input the maximum youtube videos to show ( max 50 ) or input %s to show all videos",'dzsvg'),'<strong>all</strong>'); ?></div>
        </div>





        <div class="feed-con for-feed_mode-vimeo">
	        <?php
	        $val = '';
	        $lab = 'vimeo_source';
	        if(isset($term_meta[$lab])){
		        $val = $term_meta[$lab];
	        }
	        ?>
            <h4><?php echo esc_html__("Vimeo URL",'dzsvg'); ?></h4>
            <input type="text" class="big-rounded-field" data-aux-name="<?php echo $lab; ?>" value="<?php echo $val; ?>"/>
            <div class="sidenote"><?php echo esc_html__("input the vimeo link to your channel / user channel / album in the field below",'dzsvg'); ?></div>
        </div>

        <div class="setting for-feed_mode-facebook">
            <h4><?php echo esc_html__("Vimeo Maximum Videos",'dzsvg'); ?></h4>
		    <?php
		    $val = '';
		    $lab = 'vimeo_maxlen';
		    if(isset($term_meta[$lab])){
			    $val = $term_meta[$lab];
		    }
		    ?>
            <input type="text" class="big-rounded-field" data-aux-name="<?php echo $lab; ?>" value="<?php echo $val; ?>"/>
            <div class="sidenote"><?php echo esc_html__("input the maximum vimeo videos to show ( max 50 ) ",'dzsvg'); ?></div>
        </div>




	    <?php



        echo '<div class="feed-con for-feed_mode-vimeo">';
        ?>
        <h4><?php echo esc_html__("Vimeo Sort Mode",'dzsvg'); ?></h4>
            <?php


        $lab = 'vimeo_sort';

        $val = 'default';
	        if(isset($term_meta[$lab])){
		        $val = $term_meta[$lab];
	        }
	    echo DZSHelpers::generate_select($lab,array(

		    'input_type'=>'hidden',
		    'class'=>'dzs-style-me  skin-beige ',
		    'extraattr'=>' data-aux-name="'.$lab.'"',
		    'seekval'=>$val,
		    'options'=>array(
			    array(
			      'label'=>esc_html__("Default",'dzsvg'),
			      'value'=>'default',
			    ),
			    array(
				    'label'=>esc_html__("Manual",'dzsvg'),
				    'value'=>'manual',
			    ),
			    array(
				    'label'=>esc_html__("By date",'dzsvg'),
				    'value'=>'date',
			    ),
			    array(
				    'label'=>esc_html__("Alphabetic",'dzsvg'),
				    'value'=>'alphabetic',
			    ),
			    array(
				    'label'=>esc_html__("Number plays",'dzsvg'),
				    'value'=>'plays',
			    ),
		    ),
	    ));

	    ?><div class="sidenote"><?php echo esc_html__("Default means as served by vimeo by default / Manual means as sorted in album settings",'dzsvg'); ?></div>

	    <?php
	    echo '</div>';


        ?>


        <div class="setting for-feed_mode-vimeo">
            <h4><?php echo esc_html__("Vimeo Maximum Videos",'dzsvg'); ?></h4>
		    <?php
		    $val = '';
		    $lab = 'vimeo_maxlen';
		    if(isset($term_meta[$lab])){
			    $val = $term_meta[$lab];
		    }
		    ?>
            <input type="text" class="big-rounded-field" data-aux-name="<?php echo $lab; ?>" value="<?php echo $val; ?>"/>
            <div class="sidenote"><?php echo esc_html__("input the maximum vimeo videos to show ( max 50 ) ",'dzsvg'); ?></div>
        </div>


        <div class="feed-con for-feed_mode-facebook">
            <h4><?php echo esc_html__("Facebook URL",'dzsvg'); ?></h4>
	        <?php
	        $val = '';
	        $lab = 'facebook_source';
	        if(isset($term_meta[$lab])){
		        $val = $term_meta[$lab];
	        }
	        ?>
            <input type="text" class="big-rounded-field" data-aux-name="<?php echo $lab; ?>" value="<?php echo $val; ?>"/>
            <div class="sidenote"><?php echo esc_html__("input the fadebook page link",'dzsvg'); ?></div>
        </div>
        <div class="setting for-feed_mode-facebook">
            <h4><?php echo esc_html__("Facebook Maximum Videos",'dzsvg'); ?></h4>
	        <?php
	        $val = '';
	        $lab = 'facebook_maxlen';
	        if(isset($term_meta[$lab])){
		        $val = $term_meta[$lab];
	        }
	        ?>
            <input type="text" class="big-rounded-field" data-aux-name="<?php echo $lab; ?>" value="<?php echo $val; ?>"/>
            <div class="sidenote"><?php echo esc_html__("input the maximum facebook videos to show ( max 100 ) ",'dzsvg'); ?></div>
        </div>

        <div class="dzsvg-slider-items for-feed_mode-manual">

        <?php

        if($selected_term){

            $args = array(
                'post_type'     => 'dzsvideo',
                'numberposts' => -1,
                'posts_per_page' => -1,
//                'meta_key' => 'dzsvg_meta_order_'.$selected_term,

                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_query' => array(
                    'relation' => 'OR',
                    array(
                        'key'=>'dzsvg_meta_order_'.$selected_term,
//                        'value' => '',
                        'compare' => 'EXISTS',
                    ),
                    array(
                        'key'=>'dzsvg_meta_order_'.$selected_term,
//                        'value' => '',
                        'compare' => 'NOT EXISTS'
                    )
                ),
                'tax_query' => array(
                    array(
                        'taxonomy' => $tax,
                        'field' => 'id',
                        'terms' => $selected_term // Where term_id of Term 1 is "1".
                    )
                ),
            );

            $my_query = new WP_Query( $args );

//            error_log('admin slider query ( selected term - '.$selected_term.' ) - '.print_r($my_query,true));


//            print_r($my_query->posts);

            foreach ($my_query->posts as $po){

//                print_r($po);
                 echo $dzsvg->sliders_admin_generate_item($po);


            }
            $object = (object) [
                'ID' => 'placeholder',
                'post_title' => 'placeholder',
            ];
//           echo  $dzsvg->sliders_admin_generate_item($object)
            ?>

            </div>

            <div class="add-btn for-feed_mode-manual">
                <i class="fa fa-plus-circle add-btn--icon"></i>
                <div class="add-btn-new button-secondary"><?php echo __("Create New Item"); ?></div>
                <div class="add-btn-existing add-btn-existing-media upload-type-video button-secondary"><?php echo __("Add From Library"); ?></div>
            </div>

            <br>
            <br>




            <div class="tag-options-con">
                <?php

                $val = 'manual';
                $lab = 'feed_mode';
                if(isset($term_meta[$lab]) && $term_meta[$lab]){
                    $val = $term_meta[$lab];
                }
                $nam = 'term_meta['.$lab.']';
                echo DZSHelpers::generate_input_text($nam,array(

                        'input_type'=>'hidden',
                        'seekval'=>$val,
                ));






                $val = '';
                $lab = 'youtube_source';
                if(isset($term_meta[$lab]) && $term_meta[$lab]){
	                $val = $term_meta[$lab];
                }
                $nam = 'term_meta['.$lab.']';
                echo DZSHelpers::generate_input_text($nam,array(

	                'input_type'=>'hidden',
	                'seekval'=>$val,
                ));
                $val = '';
                $lab = 'youtube_maxlen';
                if(isset($term_meta[$lab]) && $term_meta[$lab]){
	                $val = $term_meta[$lab];
                }
                $nam = 'term_meta['.$lab.']';
                echo DZSHelpers::generate_input_text($nam,array(

	                'input_type'=>'hidden',
	                'seekval'=>$val,
                ));










                $val = '';
                $lab = 'vimeo_source';
                if(isset($term_meta[$lab]) && $term_meta[$lab]){
	                $val = $term_meta[$lab];
                }
                $nam = 'term_meta['.$lab.']';
                echo DZSHelpers::generate_input_text($nam,array(

	                'input_type'=>'hidden',
	                'seekval'=>$val,
                ));


                $val = '';
                $lab = 'vimeo_sort';
                if(isset($term_meta[$lab]) && $term_meta[$lab]){
	                $val = $term_meta[$lab];
                }
                $nam = 'term_meta['.$lab.']';
                echo DZSHelpers::generate_input_text($nam,array(

	                'input_type'=>'hidden',
	                'seekval'=>$val,
                ));



                $val = '';
                $lab = 'vimeo_maxlen';
                if(isset($term_meta[$lab]) && $term_meta[$lab]){
	                $val = $term_meta[$lab];
                }
                $nam = 'term_meta['.$lab.']';
                echo DZSHelpers::generate_input_text($nam,array(

	                'input_type'=>'hidden',
	                'seekval'=>$val,
                ));



                $val = '';
                $lab = 'facebook_source';
                if(isset($term_meta[$lab]) && $term_meta[$lab]){
	                $val = $term_meta[$lab];
                }
                $nam = 'term_meta['.$lab.']';
                echo DZSHelpers::generate_input_text($nam,array(

	                'input_type'=>'hidden',
	                'seekval'=>$val,
                ));
                $val = '';
                $lab = 'facebook_maxlen';
                if(isset($term_meta[$lab]) && $term_meta[$lab]){
	                $val = $term_meta[$lab];
                }
                $nam = 'term_meta['.$lab.']';
                echo DZSHelpers::generate_input_text($nam,array(

	                'input_type'=>'hidden',
	                'seekval'=>$val,
                ));





                ?>
            <div id="tabs-box" class="dzs-tabs  skin-qcre " data-options='{ "design_tabsposition" : "top"
,"design_transition": "fade"
,"design_tabswidth": "default"
,"toggle_breakpoint" : "400"
,"settings_appendWholeContent": "true"
,"toggle_type": "accordion"
}
'>

                <div class="dzs-tab-tobe">
                    <div class="tab-menu ">
                        <?php
                        echo esc_html__("Main Settings",'dzsvg');
                        ?>
                    </div>
                    <div class="tab-content tab-content-cat-main">


                    </div>
                </div>



                <?php
                foreach ($dzsvg->options_slider_categories_lng as $lab=>$val){



                    ?>

                    <div class="dzs-tab-tobe">
                        <div class="tab-menu ">
			                <?php
			                echo ($val);
			                ?>
                        </div>
                        <div class="tab-content tab-content-cat-<?php echo $lab; ?>">


                            <table class="form-table custom-form-table sa-category-<?php echo $lab; ?>">
                                <tbody>
                                <?php
                                dzsvg_sliders_admin_parse_options($curr_term,$lab);
                                ?>
                                </tbody>

                            </table>

                        </div>
                    </div><?php

                }
                ?>




            </div>
            </div>




            <div class="dzsvg-sliders">
        <table class="wp-list-table widefat fixed striped tags">
            <thead>
            <tr>




                <th scope="col" id="name" class="manage-column column-name column-primary sortable desc"><a href="http://localhost/wordpress/wp-admin/edit-tags.php?taxonomy=dzsvg_sliders&amp;post_type=dzsvideo&amp;orderby=name&amp;order=asc"><span>Name</span><span class="sorting-indicator"></span></a></th>




                <th scope="col" id="slug" class="manage-column column-slug sortable desc"><a href="http://localhost/wordpress/wp-admin/edit-tags.php?taxonomy=dzsvg_sliders&amp;post_type=dzsvideo&amp;orderby=slug&amp;order=asc"><span><?php echo __("Edit"); ?></span><span class="sorting-indicator"></span></a></th>

                <th scope="col" id="posts" class="manage-column column-posts num sortable desc"><a href="http://localhost/wordpress/wp-admin/edit-tags.php?taxonomy=dzsvg_sliders&amp;post_type=dzsvideo&amp;orderby=count&amp;order=asc"><span>Count</span><span class="sorting-indicator"></span></a></th>	</tr>
            </thead>

            <tbody id="the-list" data-wp-lists="list:tag">


            <?php


            foreach ($terms as $tm){

                ?>


                <tr id="tag-<?php echo $tm->term_id; ?>">

                    <td class="name column-name has-row-actions column-primary" data-colname="Name"><strong>
                            <a class="row-title" href="<?php echo site_url(); ?>/wp-admin/term.php?taxonomy=dzsvg_sliders&amp;tag_ID=<?php echo $tm->term_id; ?>&amp;post_type=dzsvideo&amp;wp_http_referer=%2Fwordpress%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Ddzsvg_sliders%26post_type%3Ddzsvideo" aria-label="“<?php echo $tm->name; ?>” (Edit)"><?php echo $tm->name; ?></a></strong>
                        <br>
                        <div class="hidden" id="inline_<?php echo $tm->term_id; ?>">

                            <div class="name"><?php echo $tm->name; ?></div><div class="slug"><?php echo $tm->slug; ?></div><div class="parent">0</div></div><div class="row-actions">

                            <span class="edit"><a href="<?php echo site_url(); ?>/wp-admin/term.php?taxonomy=dzsvg_sliders&amp;tag_ID=<?php echo $tm->term_id; ?>&amp;post_type=dzsvideo&amp;wp_http_referer=%2Fwordpress%2Fwp-admin%2Fedit-tags.php%3Ftaxonomy%3Ddzsvg_sliders%26post_type%3Ddzsvideo" aria-label="Edit “Test 1”">Edit</a> | </span>

                            <span class="delete"><a href="edit-tags.php?action=delete&amp;taxonomy=dzsvg_sliders&amp;tag_ID=<?php echo $tm->term_id; ?>&amp;_wpnonce=<?php echo wp_create_nonce('delete-tag_' . $tm->term_id); ?>" class="delete-tag aria-button-if-js" aria-label="Delete “<?php echo $tm->name; ?>”" role="button">Delete</a> | </span><span class="view"><a href="<?php echo site_url(); ?>/audio-sliders/test-1/" aria-label="View “Test 1” archive">View</a></span></div><button type="button" class="toggle-row"><span class="screen-reader-text">Show more details</span></button></td>

                    <td class="description column-description" data-colname="Description">Edit</td>

                    <td class="slug column-slug" data-colname="Slug"><?php echo $tm->count; ?></td>
                </tr>
                <?php
            }
            ?>



            </tbody>



        </table>

        </div>




</div>




            <?php
        }else{
            echo '</div></div>';
            ?>


            <form class="import-slider-form" style="display: none;" enctype="multipart/form-data" action="" method="POST">
                <h3><?php echo esc_html__("Import slider",'dzsvg'); ?></h3>
                <p><input name="dzsvg_import_slider_file" type="file" size="10"/></p>
                <button class="button-secondary" type="submit" name="action" value="dzsvg_import_slider"  ><?php echo esc_html__("Import",'dzsvg'); ?></button>
                <div class="clear"></div>
            <?php





            ?>
            </form><div class="feedbacker"><?php echo esc_html__("Loading..."); ?></div><?php
        }




    }
}







function dzsvg_sliders_admin_add_feature_group_field($term) {

//    echo 'cevadada';





    global $dzsvg;





//    error_log('slider in footer');






	$arr_off_on  =array(
		array(
			'label'=>esc_html__("Off",'dzsvg'),
			'value'=>'off',
		),
		array(
			'label'=>esc_html__("On",'dzsvg'),
			'value'=>'on',
		),
	);





	$dzsvg->options_slider = array(






		array(
			'name'=>'displaymode',
			'type'=>'select',
			'category'=>'main',
			'select_type'=>'opener-listbuttons ',
			'title'=>esc_html__('Display mode','dzsvg'),
			'extra_classes'=>' opener-listbuttons-2-cols ',
			'sidenote'=>__("select the type of media"),
			'choices'=>array(
				array(
					'label'=>esc_html__("Normal",'dzsvg'),
					'value'=>'normal',
				),
				array(
					'label'=>esc_html__("Pro",'dzsvg'),
					'value'=>'wall',
				),
				array(
					'label'=>esc_html__("Boxy",'dzsvg'),
					'value'=>'rotator3d',
				),
				array(
					'label'=>esc_html__("Boxy rounded",'dzsvg'),
					'value'=>'videowall',
				),
			),
						'choices_html'=>array(
							'<span class="option-con"><img src="https://i.imgur.com/3iRmYlc.jpg"/><span class="option-label">'.esc_html__("Gallery",'dzsvg').'</span></span>',
							'<span class="option-con"><img src="https://i.imgur.com/YhYVMd9.jpg"/><span class="option-label">'.esc_html__("Wall",'dzsvg').'</span></span>',
							'<span class="option-con"><img src="https://i.imgur.com/wQrkSkv.jpg"/><span class="option-label">'.esc_html__("Rotator 3d",'dzsvg').'</span></span>',
							'<span class="option-con"><img src="https://i.imgur.com/1jThnc7.jpg"/><span class="option-label">'.esc_html__("Video wall",'dzsvg').'</span></span>',
						),


		),

		array(
			'name'=>'skin_html5vg',
			'type'=>'select',
			'category'=>'main',
			'select_type'=>'',
			'title'=>esc_html__('Gallery skin','dzsvg'),
			'extra_classes'=>' ',
			'sidenote'=>__("select the type of media"),
			'choices'=>array(
				array(
					'label'=>esc_html__("Default",'dzsvg'),
					'value'=>'skin-default',
				),
				array(
					'label'=>esc_html__("Pro",'dzsvg'),
					'value'=>'skin-pro',
				),
				array(
					'label'=>esc_html__("Boxy",'dzsvg'),
					'value'=>'skin-boxy',
				),
				array(
					'label'=>esc_html__("Boxy rounded",'dzsvg'),
					'value'=>'skin-boxy skin-boxy--rounded',
				),
				array(
					'label'=>esc_html__("Aurora",'dzsvg'),
					'value'=>'skin-aurora',
				),
				array(
					'label'=>esc_html__("Navigation transparent",'dzsvg'),
					'value'=>'skin-navtransparent',
				),
				array(
					'label'=>esc_html__("Custom",'dzsvg'),
					'value'=>'skin-custom',
				),
			),
			'dependency'=>array(
				array(
					'element'=>'term_meta[displaymode]',
					'value'=>array('normal'),
				),

			),
//			'choices_html'=>array(
//				'<span class="option-con"><img src="'.$dzsvg->base_url.'img/galleryskin-wave.jpg"/><span class="option-label">'.esc_html__("Wave",'dzsvg').'</span></span>',
//				'<span class="option-con"><img src="'.$dzsvg->base_url.'img/galleryskin-default.jpg"/><span class="option-label">'.esc_html__("Default",'dzsvg').'</span></span>',
//				'<span class="option-con"><img src="'.$dzsvg->base_url.'img/galleryskin-aura.jpg"/><span class="option-label">'.esc_html__("Aura",'dzsvg').'</span></span>',
//			),


		),



		array(
			'name'=>'vpconfig',
			'title'=>esc_html__('Player configuration','dzsvg'),
			'description'=>esc_html__('choose the gallery skin','dzsvg'),
			'type'=>'select',
			'category'=>'main',
			'options'=>array(
			),
		),
		array(
			'name'=>'nav_type',
			'title'=>esc_html__('Navigation style','dzsvg'),
			'description'=>esc_html__('Choose a navigation style for the normal display mode.','dzsvg'),
			'type'=>'select',
			'category'=>'main',
			'options'=>array(
				array(
					'label'=>esc_html__("Thumbnails",'dzsvg'),
					'value'=>'thumbs',
				),
				array(
					'label'=>esc_html__("Thumbs and arrows",'dzsvg'),
					'value'=>'thumbsandarrows',
				),
				array(
					'label'=>esc_html__("Scrollbar",'dzsvg'),
					'value'=>'scroller',
				),
				array(
					'label'=>esc_html__("Outer menu",'dzsvg'),
					'value'=>'outer',
				),
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'none',
				),
			),
		),
		array(
			'name'=>'menuposition',
			'title'=>esc_html__('Menu position','dzsvg'),
			'description'=>esc_html__('Choose a navigation style for the normal display mode.','dzsvg'),
			'type'=>'select',
			'category'=>'main',
			'options'=>array(
				array(
					'label'=>esc_html__("Right",'dzsvg'),
					'value'=>'right',
				),
				array(
					'label'=>esc_html__("Bottom",'dzsvg'),
					'value'=>'bottom',
				),
				array(
					'label'=>esc_html__("Left",'dzsvg'),
					'value'=>'left',
				),
				array(
					'label'=>esc_html__("Top",'dzsvg'),
					'value'=>'top',
				),
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'none',
				),
			),
		),
		array(
			'name'=>'settings_mode_showall_show_number',
			'title'=>esc_html__('Mode Showall Number','dzsvg'),
			'description'=>esc_html__('display the number','dzsvg'),
			'type'=>'select',
			'category'=>'main',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
			'dependency'=>array(
				array(
					'element'=>'term_meta[mode]',
					'value'=>array('mode-showall'),
				),

			),
		),


		array(
			'name'=>'bgcolor',
			'title'=>esc_html__('Background Color','dzsvg'),
			'category'=>'main',
			'description'=>esc_html__('for tag color ','dzsvg'),
			'type'=>'color',
		),




		array(
			'name'=>'design_menu_state',
			'title'=>esc_html__('Menu State','dzsvg'),
			'description'=>esc_html__('If you set this to closed, you should enable the <strong>Menu State Button</strong> below. ','dzsvg'),

			'type'=>'select',
			'category'=>'menu',
			'options'=>array(
				array(
					'label'=>esc_html__("Open",'dzsvg'),
					'value'=>'open',
				),
				array(
					'label'=>esc_html__("Closed",'dzsvg'),
					'value'=>'closed',
				),

			),
		),
		array(
			'name'=>'design_menu_state',
			'title'=>esc_html__('Menu State Button','dzsvg'),
			'description'=>esc_html__('If you set this to closed, you should enable the <strong>Menu State Button</strong> below. ','dzsvg'),

			'type'=>'select',
			'category'=>'menu',
			'options'=>$arr_off_on,
		),
		array(
			'name'=>'menu_facebook_share',
			'title'=>esc_html__('Facebook Share','dzsvg'),
			'description'=>esc_html__('enable a facebook share button in the menu ','dzsvg'),

			'type'=>'select',
			'category'=>'menu',
			'options'=>array(
				array(
					'label'=>esc_html__("Auto",'dzsvg'),
					'value'=>'auto',
				),
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),
		array(
			'name'=>'menu_like_button',
			'title'=>esc_html__('Facebook Share','dzsvg'),
			'description'=>esc_html__('enable a like button in the menu ','dzsvg'),

			'type'=>'select',
			'category'=>'menu',
			'options'=>array(
				array(
					'label'=>esc_html__("Auto",'dzsvg'),
					'value'=>'auto',
				),
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),
		array(
			'name'=>'randomize',
			'title'=>esc_html__('Randomize / suffle elements','dzsvg'),
			'description'=>esc_html__('enable a like button in the menu ','dzsvg'),

			'type'=>'select',
			'category'=>'misc',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),
		array(
			'name'=>'order',
			'title'=>esc_html__('Randomize / suffle elements','dzsvg'),
			'description'=>esc_html__('enable a like button in the menu ','dzsvg'),

			'type'=>'select',
			'category'=>'misc',
			'options'=>array(
				array(
					'label'=>esc_html__("Ascending",'dzsvg'),
					'value'=>'ascending',
				),
				array(
					'label'=>esc_html__("Descending",'dzsvg'),
					'value'=>'descending',
				),
			),
		),


		array(
			'name'=>'order',
			'title'=>esc_html__('Play order','dzsvg'),
			'description'=>esc_html__('set to reverse for example to play the latest episode in a series first ... or for RTL configurations','dzsvg'),

			'type'=>'select',
			'category'=>'misc',
			'options'=>array(
				array(
					'label'=>esc_html__("Normal",'dzsvg'),
					'value'=>'normal',
				),
				array(
					'label'=>esc_html__("Reverse",'dzsvg'),
					'value'=>'reverse',
				),
			),
		),


		array(
			'name'=>'init_on',
			'title'=>esc_html__('Initialize on','dzsvg'),
			'description'=>esc_html__('set to reverse for example to play the latest episode in a series first ... or for RTL configurations','dzsvg'),

			'type'=>'select',
			'category'=>'misc',
			'options'=>array(
				array(
					'label'=>esc_html__("Init",'dzsvg'),
					'value'=>'init',
				),
				array(
					'label'=>esc_html__("Scroll",'dzsvg'),
					'value'=>'scroll',
				),
			),
		),




		array(
			'name'=>'ids_point_to_source',
			'title'=>esc_html__('Ids point to source','dzsvg'),
			'description'=>esc_html__('the id of the video players can point to the source file used ( for lightbox ) ','dzsvg'),

			'type'=>'select',
			'category'=>'misc',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),

		array(
			'name'=>'autoplay_on_mobile_too_with_video_muted',
			'title'=>esc_html__('Autoplay on Mobiles ( muted )','dzsvg'),
			'description'=>esc_html__('normally, videos cannot autoplay on mobiles to save bandwidth, but with newest standards videos are allowed to play, but muted - if your video has no sound you can choose this option to autoplay on mobiles','dzsvg'),

			'type'=>'select',
			'category'=>'autoplay',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),


		array(
			'name'=>'transition',
			'title'=>esc_html__('Transition','dzsvg'),
			'description'=>esc_html__('set the transition of the gallery between menu items','dzsvg'),

			'type'=>'select',
			'category'=>'misc',
			'options'=>array(
				array(
					'label'=>esc_html__("Fade",'dzsvg'),
					'value'=>'fade',
				),
				array(
					'label'=>esc_html__("Slide in",'dzsvg'),
					'value'=>'slidein',
				),
			),
		),



		array(
			'name'=>'enableunderneathdescription',
			'title'=>esc_html__('Enable Underneath Description','dzsvg'),
			'description'=>esc_html__('add a title and description holder underneath the gallery','dzsvg'),

			'type'=>'select',
			'category'=>'misc',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),



		array(
			'name'=>'sharebutton',
			'title'=>esc_html__('Social share button','dzsvg'),

			'type'=>'select',
			'category'=>'social',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),


		array(
			'name'=>'facebooklink',
			'title'=>esc_html__('Facebook link','dzsvg'),


			'type'=>'text',
			'category'=>'social',

		),

		array(
			'name'=>'twitterlink',
			'title'=>esc_html__('Twitter link','dzsvg'),


			'type'=>'text',
			'category'=>'social',

		),

		array(
			'name'=>'googlepluslink',
			'title'=>esc_html__('Google plus link','dzsvg'),


			'type'=>'text',
			'category'=>'social',

		),

		array(
			'name'=>'social_extracode',
			'title'=>esc_html__('Extra Social HTML','dzsvg'),
			'description'=>esc_html__('you can have here some extra social icons','dzsvg'),


			'type'=>'text',
			'category'=>'social',

		),


		array(
			'name'=>'logo',
			'title'=>__('Logo'),
			'category'=>'social',
			'type'=>'media-upload',
		),


		array(
			'name'=>'logoLink',
			'title'=>__('Logo link'),
			'category'=>'social',
			'type'=>'text',
		),


		array(
			'name'=>'html5designmiw',
			'title'=>__('Design menu item width'),
			'category'=>'menu',
			'type'=>'text',
			'default'=>'275',
		),

		array(
			'name'=>'html5designmih',
			'title'=>__('Design menu item height'),
			'category'=>'menu',
			'type'=>'text',
			'default'=>'75',
		),
		array(
			'name'=>'html5designmis',
			'title'=>__('Design Menu Item Space'),
			'category'=>'menu',
			'type'=>'text',
			'default'=>'0',
		),
		array(
			'name'=>'thumb_extraclass',
			'title'=>__('Thumbnail Extra Classes'),
			'category'=>'menu',
			'type'=>'text',
			'default'=>'',
		),


		array(
			'name'=>'disable_menu_description',
			'title'=>esc_html__('menu description','dzsvg'),

			'type'=>'select',
			'category'=>'menu',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),
		array(
			'name'=>'design_navigationuseeasing',
			'title'=>esc_html__('easing on menu','dzsvg'),

			'type'=>'select',
			'category'=>'menu',
			'default'=>'on',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),
		array(
			'name'=>'nav_type_auto_scroll',
			'title'=>esc_html__('Lock scroll','dzsvg'),
			'description'=>esc_html__('for navigation type thumbs or scrollbar - LOCK SCROLL to current item','dzsvg'),

			'type'=>'select',
			'category'=>'menu',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),
		array(
			'name'=>'menu_description_format',
			'title'=>__('Menu item format'),
			'description'=>esc_html__('you can use something like {{number}}{{menuimage}}{{menutitle}}{{menudesc}} to display - menu item number , menu image, title and description or leave blank for default mode','dzsvg'),
			'category'=>'menu',
			'type'=>'text',
			'default'=>'',
		),


		array(
			'name'=>'embedbutton',
			'title'=>esc_html__('Embed Button','dzsvg'),

			'type'=>'select',
			'category'=>'social',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),





		array(
			'name'=>'max_width',
			'title'=>__('Max width'),
			'category'=>'dimensions',
			'type'=>'text',
			'default'=>'',
		),


		array(
			'name'=>'nav_space',
			'title'=>__('Navigation Space'),
			'category'=>'dimensions',
			'type'=>'text',
			'default'=>'',
		),


		array(
			'name'=>'width',
			'title'=>__('Force width'),
			'description'=>esc_html__('recommended - leave default','dzsvg'),
			'category'=>'dimensions',
			'type'=>'text',
			'default'=>'100%',
		),
		array(
			'name'=>'height',
			'title'=>__('Force height'),
			'description'=>esc_html__('the gallery height','dzsvg'),
			'category'=>'dimensions',
			'type'=>'text',
			'default'=>'300',
		),
		array(
			'name'=>'forcevideoheight',
			'title'=>__('Force video height'),
			'description'=>esc_html__('you can change the height of the video player here - will only have effect if you disable RESIZE VIDEOS PROPORTIONALLY in general settings','dzsvg'),
			'category'=>'dimensions',
			'type'=>'text',
			'default'=>'',
		),


		array(
			'name'=>'mode_wall_layout',
			'title'=>__('Wall item dimensions'),
			'description'=>esc_html__('the layout for the wall mode. using none will use the Design Menu Item Width and Design Menu Item Height for the item dimensions','dzsvg'),
			'category'=>'dimensions',
			'type'=>'select',
			'default'=>'',
			'options'=>array(
				array(
					'label'=>esc_html__("Default",'dzsvg'),
					'value'=>'none',
				),
				array(
					'label'=>sprintf(esc_html__("%s Column",'dzsvg'),'1'),
					'value'=>'dzs-layout--1-cols',
				),
				array(
					'label'=>sprintf(esc_html__("%s Columns",'dzsvg'),'2'),
					'value'=>'dzs-layout--2-cols',
				),
				array(
					'label'=>sprintf(esc_html__("%s Columns",'dzsvg'),'3'),
					'value'=>'dzs-layout--3-cols',
				),
				array(
					'label'=>sprintf(esc_html__("%s Columns",'dzsvg'),'4'),
					'value'=>'dzs-layout--4-cols',
				),
			),
		),

		array(
			'name'=>'disable_title',
			'title'=>esc_html__('menu title','dzsvg'),

			'type'=>'select',
			'category'=>'appearance',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),



		array(
			'name'=>'disable_video_title',
			'title'=>esc_html__('video title','dzsvg'),

			'type'=>'select',
			'category'=>'appearance',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),



		array(
			'name'=>'laptopskin',
			'title'=>esc_html__('Laptop skin','dzsvg'),

			'type'=>'select',
			'category'=>'appearance',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),



		array(
			'name'=>'rtl',
			'title'=>esc_html__('Laptop skin','dzsvg'),

			'type'=>'select',
			'category'=>'appearance',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),



		array(
			'name'=>'coverImage',
			'title'=>__('Cover Image'),
			'category'=>'appearance',
			'type'=>'media-upload',
		),



		array(
			'name'=>'shadow',
			'title'=>esc_html__('Shadow','dzsvg'),

			'type'=>'select',
			'category'=>'appearance',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),

		array(
			'name'=>'extra_classes',
			'title'=>__('Extra classes'),
			'category'=>'misc',
			'type'=>'text',
			'default'=>'',
		),


		array(
			'name'=>'maxlen_desc',
			'title'=>__('Max Description length'),
			'description'=>esc_html__('youtube video descriptions will be retrieved through YouTube Data API. You can choose here the number of characters to retrieve from it. ','dzsvg'),
			'category'=>'description',
			'type'=>'text',
			'default'=>'150',
		),



		array(
			'name'=>'readmore_markup',
			'title'=>__('Read more markup'),
			'category'=>'description',
			'type'=>'text',
			'default'=>' <a href="{{postlink}}">read more</a>',
		),


		array(
			'name'=>'enable_search_field',
			'title'=>esc_html__('Search Field','dzsvg'),
			'description'=>esc_html__('enable a search field inside the gallery','dzsvg'),

			'type'=>'select',
			'category'=>'',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),




		array(
			'name'=>'striptags',
			'title'=>esc_html__('Strip HTML Tags','dzsvg'),
			'description'=>esc_html__('video descriptions will be retrieved as html rich content. you can choose to strip the html tags to leave just simple text','dzsvg'),

			'type'=>'select',
			'category'=>'description',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),









		array(
			'name'=>'desc_different_settings_for_aside',
			'title'=>esc_html__('Aside Navigation has Different Settings?','dzsvg'),
			'description'=>esc_html__('different settings for aside navigation','dzsvg'),

			'type'=>'select',
			'category'=>'description',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),


		array(
			'name'=>'desc_aside_maxlen_desc',
			'title'=>__('Max Description length'),
			'description'=>esc_html__('youtube video descriptions will be retrieved through YouTube Data API. You can choose here the number of characters to retrieve from it. ','dzsvg'),
			'category'=>'description',
			'type'=>'text',
			'default'=>'150',
			'dependency'=>array(
				array(
					'element'=>'term_meta[desc_different_settings_for_aside]',
					'value'=>array('on'),
				),

			),
		),




		array(
			'name'=>'desc_aside_striptags',
			'title'=>esc_html__('Strip HTML Tags','dzsvg'),
			'description'=>esc_html__('video descriptions will be retrieved as html rich content. you can choose to strip the html tags to leave just simple text','dzsvg'),

			'type'=>'select',
			'category'=>'description',
			'options'=>array(
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
			),
			'dependency'=>array(
				array(
					'element'=>'term_meta[desc_different_settings_for_aside]',
					'value'=>array('on'),
				),

			),
		),




		array(
			'name'=>'enable_secondcon',
			'title'=>esc_html__('Second con','dzsvg'),
			'description'=>esc_html__('enable linking to a slider with titles and descriptions as seen in the demos. to insert the container in your page use this shortcode 
','dzsvg').' [dzsvg_secondcon id="{{currgalleryslug}}" extraclasses=""]',

			'type'=>'select',
			'category'=>'outer',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),


		array(
			'name'=>'enable_outernav',
			'title'=>esc_html__('Second navigation','dzsvg'),
			'description'=>esc_html__('enable linking to a outside navigation [dzsvg_outernav id="{{currgalleryslug}}" skin="oasis" extraclasses="" layout="layout-one-third" thumbs_per_page="9" ]','dzsvg'),

			'type'=>'select',
			'category'=>'outer',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),


		array(
			'name'=>'enable_outernav_video_author',
			'title'=>esc_html__('Outer Navigation, Show Video Author','dzsvg'),
			'description'=>esc_html__('show the video author for YouTube channels and playlists','dzsvg'),

			'type'=>'select',
			'category'=>'outer',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),


		array(
			'name'=>'enable_outernav_video_date',
			'title'=>esc_html__('Outer Navigation, Show Video Date','dzsvg'),
			'description'=>esc_html__('published date','dzsvg'),

			'type'=>'select',
			'category'=>'outer',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),





		array(
			'name'=>'settings_enable_linking',
			'title'=>esc_html__('Linking','dzsvg'),
			'description'=>esc_html__('enable the possibility for the gallery to change the current link depending on the video played, this makes it easy to go to a current video based only on link
','dzsvg'),

			'type'=>'select',
			'category'=>'main',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),

		array(
			'name'=>'autoplay_ad',
			'title'=>esc_html__('Autoplay Ad','dzsvg'),
			'description'=>esc_html__('autoplay the ad before a video or not - note that if the video autoplay then the ad will autoplay too before','dzsvg'),

			'type'=>'select',
			'category'=>'autoplay',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),



		array(
			'name'=>'set_responsive_ratio_to_detect',
			'title'=>esc_html__('Resize Video Proportionally','dzsvg'),
			'description'=>esc_html__('Settings this to "on" will make an attempt to remove the black bars plus resizing the video proportionally for mobiles.','dzsvg'),

			'type'=>'select',
			'category'=>'main',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),



		array(
			'name'=>'cueFirstVideo',
			'title'=>esc_html__('Cue First media','dzsvg'),


			'type'=>'select',
			'category'=>'autoplay',
			'options'=>array(
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
			),
		),
		array(
			'name'=>'autoplay',
			'title'=>esc_html__('Autoplay','dzsvg'),


			'type'=>'select',
			'category'=>'autoplay',
			'options'=>array(
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
			),
		),
		array(
			'name'=>'autoplay_next',
			'title'=>esc_html__('Autoplay next','dzsvg'),


			'type'=>'select',
			'category'=>'autoplay',
			'options'=>array(
				array(
					'label'=>esc_html__("Enable",'dzsvg'),
					'value'=>'on',
				),
				array(
					'label'=>esc_html__("Disable",'dzsvg'),
					'value'=>'off',
				),
			),
		),



	);



	$dzsvg->options_slider_categories_lng = array(
		'menu'=>esc_html__("Menu",'dzsvg'),
		'outer'=>esc_html__("Outer",'dzsvg'),


		'autoplay'=>esc_html__("Play Options",'dzsvg'),
		'dimensions'=>esc_html__("Dimensions",'dzsvg'),
		'description'=>esc_html__("Description",'dzsvg'),
		'social'=>esc_html__("Social",'dzsvg'),
		'appearance'=>esc_html__("Appearance",'dzsvg'),
		'misc'=>esc_html__("Miscellaneous",'dzsvg'),
	);



























	$i23 = 0;
	foreach ($dzsvg->mainvpconfigs as $vpconfig) {
		//print_r($vpconfig);


		$aux = array(
			'label'=>$vpconfig['settings']['id'],
			'value'=>$vpconfig['settings']['id'],
		);

//            print_rr($aux);


		foreach($dzsvg->options_slider as $lab => $so){

			if($so['name']=='vpconfig'){

//	                print_rr($aux);


				array_push($dzsvg->options_slider[$lab]['options'],$aux);

				break;
			}
		}


		$i23++;
	}



	dzsvg_sliders_admin_parse_options($term,'main');


}

function dzsvg_sliders_admin_parse_options($term,$cat='main'){

    global $dzsvg;
	$indtem = 0;



	$t_id = $term->term_id;

	// retrieve the existing value(s) for this meta field. This returns an array
	$term_meta = get_option("taxonomy_$t_id");

//	echo '$term_meta - '; print_rr($term_meta);


    // -- we need real location, not insert-id

	$struct_uploader = '<div class="dzs-wordpress-uploader ">
<a href="#" class="button-secondary">' . __('Upload', 'dzsvp') . '</a>
</div>';

	foreach ($dzsvg->options_slider as $tem) {


//	    echo '$indtem - '.$indtem;
//	    echo '$indtem%2 - '.($indtem%2);


        if($cat=='main'){

	        if(isset($tem['category'])==false || (isset($tem['category']) && $tem['category']=='main') ){

	        }else{
		        continue;
	        }
        }else{

	        if((isset($tem['category']) && $tem['category']==$cat) ){

	        }else{
		        continue;
	        }
        }
		if($indtem%2===0){
//			echo '<tr class="clear"></tr>';

//	        echo 'yes';
		}

		if(isset($tem['choices'])){
		    $tem['options']=$tem['choices'];
        }

        if(isset($tem['sidenote'])){
		    $tem['description']=$tem['sidenote'];
        }
 ?>
        <tr class="form-field" <?php


		if(isset($tem['dependency'])){

		    echo ' data-dependency=\''.json_encode($tem['dependency']).'\'';
		}

        ?>>
            <th scope="row" valign="top"><label
                        for="term_meta[<?php echo $tem['name']; ?>]"><?php echo $tem['title']; ?></label></th>
            <td class="<?php
			if($tem['type']=='media-upload'){
				echo 'setting-upload';
			}
			?>">





				<?php


				if($tem['type']=='media-upload' || $tem['type']=='color'){
					echo '<div class="uploader-three-floats">';
				}

				if($tem['type']=='media-upload'){
					echo '<span class="uploader-preview"></span>';
				}
				?>



				<?php
				$lab = 'term_meta['.$tem['name'].']';


				$class = 'setting-field medium';


				if($tem['type']=='media-upload') {
					$class.=' uploader-target';
				}

				if($tem['type']=='color') {
					$class .= ' wp-color-picker-init';
				}




				$val = '';


				if(isset($tem['default']) && $tem['default']){
					$val = $tem['default'];
				}
				if(isset($term_meta[$tem['name']])){


					// -- why ?
//					$val = esc_attr($term_meta[$tem['name']]) ? esc_attr($term_meta[$tem['name']]) : '';

                    $val = $term_meta[$tem['name']];
				}
				if($tem['type']=='media-upload' || $tem['type']=='text' || $tem['type']=='input' || $tem['type']=='color') {



				    if($tem['type']=='color'){
				        $class.=' with_colorpicker';
                    }

					echo DZSHelpers::generate_input_text($lab, array(
						'class' => $class,
						'seekval' => stripslashes(esc_attr($val)),
						'id' => $lab,
					));

				}


				if($tem['type']=='select') {

//				    print_rr($tem);


					$class.=' dzs-style-me skin-beige';

					if(isset($tem['select_type'])){
					    $class.=' '.$tem['select_type'];
                    }
					if(isset($tem['extra_classes'])) {
						$class .= ' ' . $tem['extra_classes'];
					}
					$class.=' dzs-dependency-field';
					echo DZSHelpers::generate_select($lab, array(
						'class' => $class,
						'options' => $tem['options'],
						'seekval' => $val,
						'id' => $lab,
					));




//					print_rr($tem);
					if(isset($tem['select_type']) && strpos($tem['select_type'],'opener-listbuttons')!==false ){

						echo  '<ul class="dzs-style-me-feeder">';

						foreach ($tem['choices_html'] as $oim_html){

							echo '<li>';
							echo $oim_html;
							echo '</li>';
						}

						echo '</ul>';


					}
				}

				if($tem['type']=='color') {
//                DZSHelpers::generate_input_text($lab, array('val' => '', 'class' => 'wp-color-picker-init ', 'seekval' => $val));


                    echo '<div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>';
				}

				if($tem['type']=='media-upload') {
					echo $struct_uploader;
				}
				?>
				<?php


				if($tem['type']=='media-upload' || $tem['type']=='color'){
					echo '</div><!-- end uploader three floats -->';
				}

				$description = '';
                if(isset($tem['description'])){
                    $description = $tem['description'];
                }

				if($description){

//                    print_rr($term);
                    $description = str_replace('{{currgalleryslug}}',$term->slug,$description);
					?>
                    <p class="description"><?php echo $description; ?></p>
					<?php


				}
				?>
            </td>
        </tr>
		<?php

		$indtem++;
	}

}