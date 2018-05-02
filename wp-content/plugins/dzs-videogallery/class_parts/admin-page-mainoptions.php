<?php

//print_r($this->mainoptions);


if (isset($_GET['dzsvp_shortcode_builder']) && $_GET['dzsvp_shortcode_builder'] == 'on') {

do_action('dzsvg_mainoptions_before_wrap');
} elseif (isset($_GET['dzsvg_shortcode_builder']) && $_GET['dzsvg_shortcode_builder'] == 'on') {
dzsvg_shortcode_builder();
} elseif (isset($_GET['dzsvg_reclam_builder']) && $_GET['dzsvg_reclam_builder'] == 'on') {
dzsvg_ad_builder();
} elseif (isset($_GET['dzsvg_quality_builder']) && $_GET['dzsvg_quality_builder'] == 'on') {
dzsvg_quality_builder();
} elseif (isset($_GET['dzsvg_shortcode_showcase_builder']) && $_GET['dzsvg_shortcode_showcase_builder'] == 'on') {
dzsvg_shortcode_showcase_builder();
} elseif (isset($_GET['dzsvg_shortcode_player_builder']) && $_GET['dzsvg_shortcode_player_builder'] == 'on') {
    dzsvg_shortcode_player_builder();
} else {



    if(current_user_can('video_gallery_edit_options') || current_user_can('manage_options')){

    }else{
        die(__("You are not allowed to edit video gallery options"));
    }

if (isset($_POST['dzsvg_delete_cache']) && $_POST['dzsvg_delete_cache'] == 'on') {
delete_option('dzsvg_cache_ytuserchannel');
delete_option('dzsvg_cache_ytplaylist');
delete_option('dzsvg_cache_ytkeywords');
delete_option('cache_dzsvg_vmuser');
delete_option('cache_dzsvg_vmchannel');
delete_option('cache_dzsvg_vmalbum');
delete_option('dzsvg_cache_vmalbum');
delete_option('dzsvg_cache_vmchannel');
delete_option('dzsvg_cache_vmuserchannel');
delete_option('dzsvg_cache_vmuser');
}

//print_rr($_POST);

if (isset($_POST['dzsvg_delete_all_options']) && $_POST['dzsvg_delete_all_options'] == 'on') {



//    print_rr($_POST);

    if ( ! wp_verify_nonce( $_REQUEST['dzsvg_delete_all_options_nonce'], 'dzsvg_delete_all_options_nonce' ) ) {

        die( 'Security check' );

    }


delete_option('dzsvg_cache_ytuserchannel');
delete_option('dzsvg_cache_ytplaylist');
delete_option('dzsvg_cache_ytkeywords');
delete_option('cache_dzsvg_vmuser');
delete_option('cache_dzsvg_vmchannel');
delete_option('cache_dzsvg_vmalbum');
delete_option('dzsvg_cache_vmalbum');
delete_option('dzsvg_cache_vmchannel');
delete_option('dzsvg_cache_vmuser');
delete_option($this->dbitemsname);
delete_option($this->dbvpconfigsname);
delete_option($this->dboptionsname);
delete_option($this->dbdcname);
delete_option($this->dbdbsname);



    global $wpdb;
    $table_name = $wpdb->prefix.'posts';

    $user_id = get_current_user_id();


    $wpdb->delete( $table_name, array( 'post_type' => 'dzsvideo' ) );;



}




    $arr_vpconfigs = array();
    $i = 0;
    $arr_vpconfigs[$i] = array('lab' => __('Default Configuration', 'dzsvp'), 'val' => 'default');
    $i++;
    foreach ($this->mainvpconfigs as $vpconfig) {
        //print_r($vpconfig);
//            $vpconfigsstr .='<option value="' . $vpconfig['settings']['id'] . '">' . $vpconfig['settings']['id'] . '</option>';
        $arr_vpconfigs[$i] = array('lab' => $vpconfig['settings']['id'], 'val' => $vpconfig['settings']['id']);
        $i++;
    };
//        print_r($this->mainoptions);
?>

<div class="wrap <?php

if (isset($_GET['dzsvg_shortcode_builder']) && $_GET['dzsvg_shortcode_builder'] == 'on') {
    echo ' wrap-shortcode-builder';
}
?>">
    <h2><?php echo __('Video Gallery Main Settings', 'dzsvg'); ?></h2>
    <br/>

    <form class="mainsettings">

        <a class="zoombox button-secondary" href="<?php echo $this->thepath; ?>readme/index.html"
           data-bigwidth="1100" data-scaling="fill"
           data-bigheight="700"><?php echo __("Documentation"); ?></a>

        <a href="<?php echo admin_url('admin.php?page=dzsvg-mo&dzsvg_shortcode_showcase_builder=on'); ?>" target="_blank"
           class="button-secondary action"><?php _e('Showcase Shortcode Generator', 'dzsvg'); ?></a>

        <a href="<?php echo admin_url('admin.php?page=dzsvg-mo&dzsvg_shortcode_builder=on'); ?>" target="_blank"
           class="button-secondary action"><?php _e(' Gallery Shortcode Generator', 'dzsvg'); ?></a>

        <a href="<?php echo admin_url('admin.php?page=dzsvg-mo&dzsvg_shortcode_player_builder=on'); ?>" target="_blank"
           class="button-secondary action"><?php _e(' Player Generator', 'dzsvg'); ?></a>


        <?php
        do_action('dzsvg_mainoptions_before_tabs');
        ?>

        <h3><?php echo __("Admin Options"); ?></h3>

        <div class="dzs-tabs auto-init" data-options="{ 'design_tabsposition' : 'top'
,design_transition: 'fade'
,design_tabswidth: 'default'
,toggle_breakpoint : '400'
,toggle_type: 'accordion'
,toggle_type: 'accordion'
,settings_enable_linking : 'on'
,settings_appendWholeContent: true
,refresh_tab_height: '1000'
}">

            <div class="dzs-tab-tobe">
                <div class="tab-menu with-tooltip">
                    <i class="fa fa-tachometer"></i> <?php echo __("Settings"); ?>
                </div>
                <div class="tab-content">
                    <br>


                    <div class="setting">

                        <?php
                        $lab = 'playlists_mode';
                        ?>
                        <h4 class="setting-label"><?php echo __('Playlists mode', 'dzsvg'); ?></h4><?php
                        echo DZSHelpers::generate_select($lab, array('id' => $lab,
                                                                     'class' => 'dzs-style-me skin-beige',
                                                                     'options' => array(
	                                                                     array(
		                                                                     'label'=>__("Legacy"),
		                                                                     'value'=>'legacy',
	                                                                     ),
	                                                                     array(
		                                                                     'label'=>__("Normal"),
		                                                                     'value'=>'normal',
	                                                                     ),
                                                                     ),
                                                                     'seekval' => $this->mainoptions[$lab]));
                        ?>
                        <div
                            class="sidenote"><?php echo __('by default scripts and styles from this gallery are included only when needed for optimizations reasons, but you can choose to always use them ( useful for when you are using a ajax theme that does not reload the whole page on url change )', 'dzsvg'); ?></div>
                    </div>


                    <div class="setting">

                        <?php
                        $lab = 'always_embed';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Always Embed Scripts?', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div
                            class="sidenote"><?php echo __('by default scripts and styles from this gallery are included only when needed for optimizations reasons, but you can choose to always use them ( useful for when you are using a ajax theme that does not reload the whole page on url change )', 'dzsvg'); ?></div>
                    </div>


                    <div class="setting">

                        <?php
                        $lab = 'disable_fontawesome';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Disable FontAwesome', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div
                            class="sidenote"><?php echo __('do not include the fontawesome library', 'dzsvg'); ?></div>
                    </div>


                    <div class="setting">

                        <?php
                        $lab = 'settings_trigger_resize';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Force Refresh Size Every 1000ms', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div
                            class="sidenote"><?php echo __('sometimes sizes need to be recalculated ( for example if you use the gallery in tabs )', 'dzsvg'); ?></div>
                    </div>


                    <div class="setting">

                        <?php
                        $lab = 'replace_wpvideo';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Replace [video] Shortcode for Simple Videos', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div
                            class="sidenote"><?php echo __('render simple wp videos with DZS Video Gallery', 'dzsvg'); ?></div>
                    </div>

                    <div class="setting">

                        <?php
                        $lab = 'enable_widget';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Enable Widget', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div
                            class="sidenote"><?php echo __('enable widget for including in sidebar', 'dzsvg'); ?></div>
                    </div>

                    <div class="setting">

                        <?php
                        $lab = 'loop_playlist';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Loop Playlist', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div
                            class="sidenote"><?php echo __('loop the playlist after the last video has finished', 'dzsvg'); ?></div>
                    </div>

                    <div class="setting">

                        <?php
                        $lab = 'enable_cs';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Enable CornerStone Support', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div
                            class="sidenote"><?php echo __('enable CornerStone support', 'dzsvg'); ?></div>
                    </div>


                    <div class="setting">

                        <?php
                        $lab = 'enable_auto_backup';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Enable Autobackup', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div
                            class="sidenote"><?php echo __('enable once per day autobackup of the main database', 'dzsvg'); ?></div>
                    </div>


                    <div class="setting">

                        <?php
                        $lab = 'enable_video_showcase';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Enable Video Showcase', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div
                            class="sidenote"><?php echo __('enable Video Items and special Showcase options', 'dzsvg'); ?></div>
                    </div>


                    <div class="setting">

                        <?php
                        $lab = 'track_views';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Track Views', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div class="sidenote"><?php echo __('Track views on video posts', 'dzsvg'); ?></div>
                    </div>


                    <div class="setting">

                        <?php
                        $lab = 'videoplayer_end_exit_fullscreen';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Exit Fullscreen on Video End', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div class="sidenote"><?php echo __('exit fullscreen mode when video has ended or remain fullscreen ?', 'dzsvg'); ?></div>
                    </div>


                    <?php

                    /*
                    <div class="setting">

                        <?php
                        $lab = 'multishare';
                        ?>
                        <h4 class="setting-label"><?php echo __('Show Share Options', 'dzsvg'); ?></h4>
                        <?php
                        echo DZSHelpers::generate_select($lab, array('id' => $lab,
                                                                     'class' => 'dzs-style-me skin-beige',
                                                                     'options' => array(
                                                                         array(
                                                                             'label'=>__("Auto"),
                                                                             'value'=>'auto',
                                                                         ),
                                                                         array(
                                                                             'label'=>__("On"),
                                                                             'value'=>'on',
                                                                         ),
                                                                     ),
                                                                     'seekval' => $this->mainoptions[$lab]));
                        ?>

                        <div class="sidenote"><?php echo __('autoplay the next video item post', 'dzsvg'); ?></div>
                    </div>
                    */

                ?>

                    <div class="setting">

                        <?php
                        $lab = 'enable_developer_options';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Enable Developer Options', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div class="sidenote"><?php echo __('warning - only for developers', 'dzsvg'); ?></div>
                    </div>




                    <!-- end general settings -->


                </div>
            </div>

            <div class="dzs-tab-tobe tab-disabled">
                <div class="tab-menu ">
                    &nbsp;&nbsp;
                </div>
                <div class="tab-content">

                </div>
            </div>

            <div class="dzs-tab-tobe">
                <div class="tab-menu with-tooltip">
                    <i class="fa fa-paint-brush"></i> <?php echo __("Appearance") ?>
                </div>
                <div class="tab-content">
                    <br>


                    <?php
                    $lab = 'translate_skipad';
                    echo '
                                   <div class="setting">
                                       <div class="setting-label">' . __('Translate Skip Ad', 'dzsvg') . '</div>
                                       ' . $this->misc_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                   </div>';
                    ?>



                    <?php
                    $lab = 'translate_all';
                    echo '
                                   <div class="setting">
                                       <div class="setting-label">' . sprintf(__('Translate %sAll%s', 'dzsvg'),'<em>','</em>') . '</div>
                                       ' . $this->misc_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                       <div class="sidenote">'.__('leave blank here and you can translate in multiple languages via WPML or poedit', 'dzsvg').'</div>
                                   </div>';
                    ?>




                    <?php
                    $lab = 'translate_share';
                    echo '
                                   <div class="setting">
                                       <div class="setting-label">' . sprintf(__('Translate %sShare%s', 'dzsvg'),'<em>','</em>') . '</div>
                                       ' . $this->misc_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                       <div class="sidenote">'.__('leave blank here and you can translate in multiple languages via WPML or poedit', 'dzsvg').'</div>
                                   </div>';
                    ?>





	                <?php
	                $lab = 'easing_speed';
	                echo '
                                   <div class="setting">
                                       <div class="setting-label">' . __("Easing duration for the menu to scroll") . '</div>
                                       ' . $this->misc_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                       <div class="sidenote">'.__('set a custom duration for the menu to scroll in ms - for example: ', 'dzsvg').' 30 '.__("or").' 40 '.__("for slower scrolling").'</div>
                                   </div>';
	                ?>





                    <div class="setting">
                        <div class="setting-label"><?php echo __('Extra CSS', 'dzsvg'); ?></div>
                        <?php echo $this->misc_input_textarea('extra_css', array('val' => '', 'seekval' => $this->mainoptions['extra_css'])); ?>
                        <div class="sidenote"><?php echo __('', 'dzsvg'); ?></div>
                    </div>

                </div>
            </div>



            <div class="dzs-tab-tobe tab-disabled">
                <div class="tab-menu ">
                    &nbsp;&nbsp;
                </div>
                <div class="tab-content">

                </div>
            </div>

            <div class="dzs-tab-tobe">
                <div class="tab-menu with-tooltip">
                    <i class="fa fa-external-link"></i> <?php echo __("Video Page") ?>
                </div>
                <div class="tab-content">
                    <br>





                    <h3><?php echo __('Video Page', 'dzsvg'); ?></h3>




                    <div class="setting">
                        <h4 class="setting-label"><?php echo __('Post Name', 'dzsvp'); ?></h4>
                        <?php
                        $lab = 'dzsvp_post_name';
                        $val = $this->mainoptions[$lab];
                        echo DZSHelpers::generate_input_text($lab, array( 'class' => '', 'seekval' => $val));
                        ?>

                    </div>



                    <div class="setting">
                        <h4 class="setting-label"><?php echo __('Post Name Singular', 'dzsvp'); ?></h4>
                        <?php
                        $lab = 'dzsvp_post_name_singular';
                        $val = $this->mainoptions[$lab];
                        echo DZSHelpers::generate_input_text($lab, array( 'class' => '', 'seekval' => $val));
                        ?>

                    </div>


                    <div class="setting">
                        <h4 class="setting-label"><?php echo __('Video Player Configuration', 'dzsvp'); ?></h4>
                        <?php
                        $lab = 'dzsvp_video_config';
                        $val = $this->mainoptions[$lab];
                        echo DZSHelpers::generate_select($lab, array('options' => $arr_vpconfigs, 'class' => 'dzs-style-me skin-beige', 'seekval' => $val));
                        ?>

                    </div>


                    <div class="setting">

                        <?php
                        $lab = 'videopage_show_views';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Show Play Count ', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div class="sidenote"><?php echo __('Yes / No', 'dzsvg'); ?></div>
                    </div>
                    <div class="setting">

                        <?php
                        $lab = 'videopage_autoplay';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Autoplay', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div class="sidenote"><?php echo __('autoplay videos on video page', 'dzsvg'); ?></div>
                    </div>
                    <div class="setting">

                        <?php
                        $lab = 'videopage_autoplay_next';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab,
                            'val' => 'off',
                            'class' => 'fake-input',
                            'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Autoplay Next Video', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab,
                                'val' => 'on',
                                'class' => 'dzs-dependency-field',
                                'seekval' => $this->mainoptions[$lab]));
                            ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div class="sidenote"><?php echo __('autoplay the next video item post', 'dzsvg'); ?></div>
                    </div>


                    <?php



                    $dependency = array(

                        array(
                            'label'=>'videopage_autoplay_next',
                            'value'=>array('on'),
                        ),
                    );


                    $dependency = json_encode($dependency);

                    ?>


                    <div class="setting" data-dependency='<?php echo ($dependency); ?>'>

                        <?php
                        $lab = 'videopage_autoplay_next_direction';
                        ?>
                        <h4 class="setting-label"><?php echo __('Autoplay Next Video', 'dzsvg'); ?></h4>
                        <?php
                        echo DZSHelpers::generate_select($lab, array('id' => $lab,
                            'class' => 'dzs-style-me skin-beige',
                            'options' => array(
                                array(
                                    'label'=>__("Normal"),
                                    'value'=>'normal',
                                ),
                                array(
                                    'label'=>__("Reverse"),
                                    'value'=>'reverse',
                                ),
                            ),
                            'seekval' => $this->mainoptions[$lab]));
                        ?>

                        <div class="sidenote"><?php echo __('autoplay the next video item post', 'dzsvg'); ?></div>
                    </div>
                    <div class="setting">

                        <?php
                        $lab = 'videopage_resize_proportional';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Resize proportional ?', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div class="sidenote"><?php echo __('resize proportionally to try and hide black bars', 'dzsvg'); ?></div>
                    </div>

                    <h3><?php echo __('Lightbox Settings', 'dzsvg'); ?></h3>
                    <div class="setting">

                        <?php
                        $lab = 'zoombox_autoplay';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Autoplay Video in Zoombox', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div class="sidenote"><?php echo __('Yes / No', 'dzsvg'); ?></div>
                    </div>





                    <div class="setting">
                        <h4 class="setting-label"><?php echo __('Video Player Configuration', 'dzsvp'); ?></h4>
                        <?php
                        $lab = 'zoombox_video_config';
                        $val = $this->mainoptions[$lab];
                        echo DZSHelpers::generate_select($lab, array('options' => $arr_vpconfigs, 'class' => 'dzs-style-me skin-beige', 'seekval' => $val));
                        ?>

                    </div>



                </div>
            </div>


            <div class="dzs-tab-tobe tab-disabled">
                <div class="tab-menu ">
                    &nbsp;&nbsp;
                </div>
                <div class="tab-content">
                    <br>


                </div>
            </div>

            <div class="dzs-tab-tobe">
                <div class="tab-menu with-tooltip">
                    <i class="fa fa-bar-chart"></i> <?php echo __("Analytics") ?>
                </div>
                <div class="tab-content">
                    <br>


                    <div class="dzs-container">
                        <div class="full">
                            <div class="setting">

                                <?php
                                $lab = 'analytics_enable';
                                echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                                ?>
                                <h4 class="setting-label"><?php echo __('Enable Analytics', 'dzsvg'); ?></h4>
                                <div class="dzscheckbox skin-nova">
                                    <?php
                                    echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                    <label for="<?php echo $lab; ?>"></label>
                                </div>
                                <div
                                    class="sidenote"><?php echo __('activate analytics for the galleries', 'dzsvg'); ?></div>
                            </div>


                            <div class="setting">

                                <?php
                                $lab = 'analytics_enable_location';
                                echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                                ?>
                                <h4 class="setting-label"><?php echo __('Track Users Country?', 'dzsvg'); ?></h4>
                                <div class="dzscheckbox skin-nova">
                                    <?php
                                    echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                    <label for="<?php echo $lab; ?>"></label>
                                </div>
                                <div
                                    class="sidenote"><?php echo __('use geolocation to track users country', 'dzsvg'); ?></div>
                            </div>

                            <div class="setting">

                                <?php
                                $lab = 'analytics_enable_user_track';
                                echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                                ?>
                                <h4 class="setting-label"><?php echo __('Track Statistic by User?', 'dzsvg'); ?></h4>
                                <div class="dzscheckbox skin-nova">
                                    <?php
                                    echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                    <label for="<?php echo $lab; ?>"></label>
                                </div>
                                <div
                                    class="sidenote"><?php echo __('track views and minutes watched of each user', 'dzsvg'); ?></div>
                            </div>


                        </div>




                    </div>


                </div>
            </div>

            <div class="dzs-tab-tobe tab-disabled">
                <div class="tab-menu ">
                    &nbsp;&nbsp;
                </div>
                <div class="tab-content">

                </div>
            </div>

            <div class="dzs-tab-tobe">
                <div class="tab-menu with-tooltip">
                    <i class="fa fa-youtube"></i> <?php echo __("YouTube") ?>
                </div>
                <div class="tab-content">
                    <br>


                    <?php


                    echo '
                <div class="setting">
                    <div class="label">' . __('YouTube API Key', 'dzsvg') . '</div>
                    ' . $this->misc_input_text('youtube_api_key', array('val' => '', 'seekval' => $this->mainoptions['youtube_api_key'])) . '
                    <div class="sidenote">' . sprintf(__('get a api key %shere%s, create a new project, access API > %sAPIs%s and enabled YouTube Data API, then create your Public API Access from API > Credentials', 'dzsvg'),'<a href="https://console.developers.google.com">','</a>','<strong>','</strong>') . '</div>
                    <div class="sidenote">' . sprintf(__('remember, do not enter anything in referers field, unless you know what you are doing, leave it clear like so - %shere%s', 'dzsvg'),'<a href="https://lh3.googleusercontent.com/5eps7rIYzxwpO5ftxy4D6GiMdimShMRWM7XE0-pQ5lI=w1221-h950-no">','</a>') . '</div>
                </div>';



                    $lab = 'youtube_playfrom';
                    echo '
                <div class="setting">
                    <div class="label">' . __('YouTube Play From', 'dzsvg') . '</div>
                    ' . $this->misc_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                    <div class="sidenote">' . sprintf(__('Set a play from for youtube channel and playlist feeds. For example you can input here %slast%s and the youtube video will play from the last position.', 'dzsvg'),'<strong>','</strong>') . '</div>
                    
                </div>';


?>

                    <div class="setting">
                        <div class="setting-label"><?php echo __('Hide non-embeddable movies', 'dzsvp'); ?></div>
		                <?php
		                $lab = 'youtube_hide_non_embeddable';
		                $arr_opts = array(
			                array(
				                'lab' => __('Off'),
				                'val' => 'off',
			                ),
			                array(
				                'lab' => __('On'),
				                'val' => 'on',
			                ),

		                );

		                $val = $this->mainoptions[$lab];
		                echo DZSHelpers::generate_select($lab, array('options' => $arr_opts, 'class' => 'styleme', 'seekval' => $val));


		                echo '<div class="sidenote">' . (__('do not retrieve videos that cannot be embedded outside of youtube', 'dzsvg')) . '</div>';
		                ?>
                    </div>
                    <?php




                    ?>


                </div>
            </div>



            <div class="dzs-tab-tobe tab-disabled">
                <div class="tab-menu ">
                    &nbsp;&nbsp;
                </div>
                <div class="tab-content">

                </div>
            </div>

            <div class="dzs-tab-tobe">
                <div class="tab-menu with-tooltip">
                    <i class="fa fa-facebook"></i> <?php echo __("Facebook") ?>
                </div>
                <div class="tab-content">
                    <br>


                    <div class="setting">
                        <div class="label"><?php echo __('Facebook play in', 'dzsvp'); ?></div>
                        <?php
                        $lab = 'facebook_player';
                        $arr_opts = array(
                                array(
                                        'lab' => __('Custom player'),
                                        'val' => 'custom',
                                    ),
                                array(
                                        'lab' => __('iFrame'),
                                        'val' => 'iframe',
                                    ),

                            );

                        $val = $this->mainoptions[$lab];
                        echo DZSHelpers::generate_select($lab, array('options' => $arr_opts, 'class' => 'styleme', 'seekval' => $val));
                        ?>
                    </div>


                    <?php
                    $lab = 'facebook_app_id';
                    echo '
                                   <div class="setting">
                                       <div class="label">' . __('Facebook application ID', 'dzsvg') . '</div>
                                       ' . DZSHelpers::generate_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                       <div class="sidenote">' . esc_html__("Tutorial",'dzsvg').' <a href="http://zoomthe.me/themeadmin-dzsvg/knowledge-base/knowledge-base/facebook-create-application-for-getting-application-id-and-application-secret/" target="_blank">'.esc_html__("here",'dzsvg').'</a>' . '</div>
                                   </div>';

                    $lab = 'facebook_app_secret';
                    echo '
                                   <div class="setting">
                                       <div class="label">' . __('Facebook application secret', 'dzsvg') . '</div>
                                       ' . DZSHelpers::generate_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                       
                                   </div>';


                    $lab = 'facebook_access_token';


                    $extra_attr = '';

                    if($this->mainoptions['facebook_app_id']){



                    }else{
	                    $extra_attr = ' disabled';

	                    echo '<br><br><div class="sidenote warning warning-bg" style="color: #222; font-weight:bold;">' . esc_html__("Input application ID and application secret, then click Save Options, refresh, then click LOG IN WITH FACEBOOK bellow ",'dzsvg').'</div>';
                    }
                    echo '
                                   <div class="setting">
                                       <div class="label">' . __('Access Token', 'dzsvg') . '</div>
                                       ' . DZSHelpers::generate_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab],'extraattr'=>$extra_attr)) . '
                                      
                                   </div>';


                    ;



                    $app_id = $this->mainoptions['facebook_app_id'];
                    $app_secret = $this->mainoptions['facebook_app_secret'];


                    if($app_id && $app_secret){



	                    if(function_exists('session_status')){

		                    if (session_status() == PHP_SESSION_NONE) {
			                    session_start();
		                    }
	                    }


	                    require_once 'src/Facebook/autoload.php'; // change path as needed





	                    $fb = new Facebook\Facebook(array(
		                    'app_id' => $app_id,
		                    'app_secret' => $app_secret,
		                    'default_graph_version' => 'v2.10',
		                    //'default_access_token' => '{access-token}', // optional
	                    ));








//	                    if(isset($_GET['state'])){
//
//
//
//		                    foreach ($_COOKIE as $k=>$v) {
//			                    if(strpos($k, "FBRLH_")!==FALSE) {
//				                    $_SESSION[$k]=$v;
//			                    }
//		                    }
//
////		$_SESSION['FBRLH_state']=$_GET['state'];
//	                    }




	                    $accessToken = '';

	                    $helper = $fb->getRedirectLoginHelper();



	                    $redir_url = admin_url('admin.php?page=dzsvg-about');



		                    $permissions = array('email'); // Optional permissions
		                    $loginUrl = $helper->getLoginUrl($redir_url, $permissions);


		                    foreach ($_SESSION as $k=>$v) {
			                    if(strpos($k, "FBRLH_")!==FALSE) {
				                    if(!setcookie($k, $v)) {
					                    //what??
				                    } else {
					                    $_COOKIE[$k]=$v;
				                    }
			                    }
		                    }

//		                    echo ' redir_url - '.admin_url('admin.php?page=dzsvg-mo&tab=10').'<br>';
		                    echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';






	                    ?>


<?php
                    }
?>

                </div>


            </div>


            <div class="dzs-tab-tobe tab-disabled">
                <div class="tab-menu ">
                    &nbsp;&nbsp;
                </div>
                <div class="tab-content">
                    <br>


                </div>
            </div>

            <div class="dzs-tab-tobe">
                <div class="tab-menu with-tooltip">
                    <i class="fa fa-vimeo"></i> <?php echo __("Vimeo") ?>
                </div>
                <div class="tab-content">
                    <br>


                    <div class="setting">
                        <div class="label"><?php echo __('Vimeo Thumbnail Quality', 'dzsvp'); ?></div>
                        <?php
                        $arr_opts = array(array('lab' => __('Low Quality'), 'val' => 'low',), array('lab' => __('Medium Quality'), 'val' => 'medium',), array('lab' => __('High Quality'), 'val' => 'high',),);

                        $lab = 'vimeo_thumb_quality';
                        $val = $this->mainoptions[$lab];
                        echo DZSHelpers::generate_select($lab, array('options' => $arr_opts, 'class' => 'styleme', 'seekval' => $val));
                        ?>
                    </div>


                    <?php
                    $lab = 'vimeo_api_user_id';
                    echo '
                                   <div class="setting">
                                       <div class="label">' . __('Your User ID', 'dzsvg') . '</div>
                                       ' . DZSHelpers::generate_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                       <div class="sidenote">' . __('get it from https://vimeo.com/settings, must be in the form of user123456 ', 'dzsvg') . '</div>
                                   </div>';

                    $lab = 'vimeo_api_client_id';
                    echo '
                                   <div class="setting">
                                       <div class="label">' . __('Client ID', 'dzsvg') . '</div>
                                       ' . DZSHelpers::generate_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                       <div class="sidenote">' . sprintf(__('you can get an api key from %shere%s - section %soAuth2%s from the app ', 'dzsvg'),'<a href="https://developer.vimeo.com/apps">','</a>','<strong>','</strong>') . ' / '. sprintf( __(' additional tutorial %s here %s'), '<a target="_blank" href="http://digitalzoomstudio.net/docs/wpvideogallery/#faq-vimeoapi">', '</a>'). '</div>
                                   </div>';


                    $lab = 'vimeo_api_client_secret';
                    echo '
                                   <div class="setting">
                                       <div class="label">' . __('Client Secret', 'dzsvg') . '</div>
                                       ' . DZSHelpers::generate_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                   </div>';


                    $lab = 'vimeo_api_access_token';
                    echo '
                                   <div class="setting">
                                       <div class="label">' . __('Access Token', 'dzsvg') . '</div>
                                       ' . DZSHelpers::generate_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab])) . '
                                       <div class="sidenote">
                                       '.sprintf(__(' make sure API key is correct - see %s here %s - make sure it DOES NOT look like this -  %s'),'<a target="_blank" href="http://digitalzoomstudio.net/docs/wpvideogallery/#faq-vimeoapi">','</a>','https://api.vimeo.com/oauth/access_token').'
                                       </div>
                                   </div>';


                    ;
                    ?>


                </div>


            </div>


            <div class="dzs-tab-tobe tab-disabled">
                <div class="tab-menu ">
                    &nbsp;&nbsp;
                </div>
                <div class="tab-content">
                    <br>


                </div>
            </div>




            <div class="dzs-tab-tobe">
                <div class="tab-menu with-tooltip">
                    <i class="fa fa-share-alt"></i> <?php echo __("Social") ?>
                </div>
                <div class="tab-content">
                    <br>




                    <div class="setting">

                        <?php
                        $lab = 'merge_social_into_one';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <h4 class="setting-label"><?php echo __('Merge social options into one lightbox', 'dzsvg'); ?></h4>
                        <div class="dzscheckbox skin-nova">
                            <?php
                            echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                            <label for="<?php echo $lab; ?>"></label>
                        </div>
                        <div
                                class="sidenote"><?php echo __('enable a single lightbox for share and embed links', 'dzsvg'); ?></div>
                    </div>



                    <?php


                    $lab = 'social_social_networks';
                    ?>



                    <div class="setting">
                        <div class="setting-label"><?php echo __('Social Networks HTML', 'dzsvg'); ?></div>
                        <?php
                        echo DZSHelpers::generate_input_textarea($lab, array(
                            'val' => '',
                            'extraattr' => ' rows="4" style="width: 100%;"',
                            'seekval' => (stripslashes($this->mainoptions[$lab])),
                        ));
                        ?>
                        <div class="sidenote"><?php echo __('', 'dzsvg'); ?></div>
                    </div>


                    <?php


                    $lab = 'social_share_link';
                    ?>



                    <div class="setting">
                        <div class="setting-label"><?php echo __('Social Networks Share Link HTML', 'dzsvg'); ?></div>
                        <?php
                        echo DZSHelpers::generate_input_textarea($lab, array(
                            'val' => '',
                            'extraattr' => ' rows="4" style="width: 100%;"',
                            'seekval' => $this->mainoptions[$lab],
                        ));
                        ?>
                        <div class="sidenote"><?php echo __('', 'dzsvg'); ?></div>
                    </div>


                    <?php


                    $lab = 'social_embed_link';
                    ?>



                    <div class="setting">
                        <div class="setting-label"><?php echo __('Social Networks Embed Code HTML', 'dzsvg'); ?></div>
                        <?php
                        echo $this->misc_input_textarea($lab, array(
                                'val' => '',
                                'extraattr' => ' rows="4" style="width: 100%;"',
                                'seekval' => htmlentities($this->mainoptions[$lab]),
                            ));
                        ?>
                        <div class="sidenote"><?php echo __('', 'dzsvg'); ?></div>
                    </div>


                    <?php


                    $lab = 'dzsvp_tab_share_content';
                    ?>



                    <div class="setting">
                        <div class="setting-label"><?php echo __('Video Page -> Share tab content', 'dzsvg'); ?></div>
                        <?php
                        echo $this->misc_input_textarea($lab, array(
                                'val' => '',
                                'extraattr' => ' rows="4" style="width: 100%;"',
                                'seekval' => $this->mainoptions[$lab],
                            ));
                        ?>
                        <div class="sidenote"><?php echo __('', 'dzsvg'); ?></div>
                    </div>







                </div>


            </div>


            <div class="dzs-tab-tobe tab-disabled">
                <div class="tab-menu ">
                    &nbsp;&nbsp;
                </div>
                <div class="tab-content">
                    <br>


                </div>
            </div>






            <!-- system check -->
            <div class="dzs-tab-tobe tab-disabled">
                <div class="tab-menu ">
                    &nbsp;&nbsp;
                </div>
                <div class="tab-content">

                </div>
            </div>

            <div class="dzs-tab-tobe">
                <div class="tab-menu with-tooltip">
                    <i class="fa fa-gear"></i> <?php echo __("System Check"); ?>
                </div>
                <div class="tab-content">
                    <br>



                    <div class="setting">

                        <h4 class="setting-label">GetText <?php echo __("Support"); ?></h4>


                        <?php
                        if (function_exists("gettext")) {
                            echo '<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> '.''.__("supported").'</div>';
                        } else {

                            echo '<div class="setting-text-notok">'.''.__("not supported").'</div>';
                        }
                        ?>

                        <div class="sidenote"><?php echo __('translation support'); ?></div>
                    </div>


                    <div class="setting">

                        <h4 class="setting-label">ZipArchive <?php echo __("Support"); ?></h4>


                        <?php
                        if (class_exists("ZipArchive")) {
                            echo '<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> '.''.__("supported").'</div>';
                        } else {

                            echo '<div class="setting-text-notok">'.''.__("not supported").'</div>';
                        }
                        ?>

                        <div class="sidenote"><?php echo __('zip making for album download support'); ?></div>
                    </div>
                    <div class="setting">

                        <h4 class="setting-label">Curl <?php echo __("Support"); ?></h4>


                        <?php
                        if (function_exists('curl_version')) {
                            echo '<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> '.''.__("supported").'</div>';
                        } else {

                            echo '<div class="setting-text-notok">'.''.__("not supported").'</div>';
                        }
                        ?>

                        <div class="sidenote"><?php echo __('for making youtube / vimeo api calls'); ?></div>
                    </div>
                    <div class="setting">

                        <h4 class="setting-label">allow_url_fopen <?php echo __("Support"); ?></h4>


                        <?php
                        if (ini_get('allow_url_fopen')) {
                            echo '<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> '.''.__("supported").'</div>';
                        } else {

                            echo '<div class="setting-text-notok">'.''.__("not supported").'</div>';
                        }
                        ?>

                        <div class="sidenote"><?php echo __('for making youtube / vimeo api calls'); ?></div>
                    </div>



                    <div class="setting">

                        <h4 class="setting-label"><?php echo __("PHP Version"); ?></h4>

                        <div class="setting-text-ok">
                            <?php
                            echo phpversion();
                            ?>
                        </div>

                        <div class="sidenote"><?php echo __('the install php version, 5.4 or greater required for facebook api'); ?></div>
                    </div>


                    <div class="setting">

                        <h4 class="setting-label"><?php echo __("Wordpress Version"); ?></h4>

                        <div class="setting-text-ok">
                            <?php
                            echo get_bloginfo( 'version' );
                            ?>
                        </div>

                        <div class="sidenote"><?php echo __('the install php version, 5.4 or greater required for facebook api'); ?></div>
                    </div>



                    <div class="setting">

                        <h4 class="setting-label"><?php echo __("Analytics table status"); ?></h4>
                        <?php
                        global $wpdb;

                        $table_name = $wpdb->prefix . 'dzsvg_activity';

                        $var = $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" );

//                        print_rr($var);
                        if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {

	                        echo '<div class="setting-text-notok bg-error">'.''.__("table not installed").'</div>';
                        } else {
	                        echo '<div class="setting-text-ok"><i class="fa fa-thumbs-up"></i> '.''.__("table ok").'</div>';




	                        echo '<p class=""><a class="button-secondary repair-table" href="'.admin_url('admin.php?page=dzsvg-mo&tab=17&analytics_table_repair=on').'">'.__("repair table").'</a></p>';



	                        echo '<p class=""><a class="button-secondary" href="'.admin_url('admin.php?page=dzsvg-mo&tab=17&show_analytics_table_last_10_rows=on').'">'.__("check last 10 rows").'</a></p>';



	                        if(isset($_GET['show_analytics_table_last_10_rows']) && $_GET['show_analytics_table_last_10_rows']=='on'){

		                        $query = 'SELECT * FROM '.$table_name.' ORDER BY id DESC LIMIT 10';
		                        $results = $GLOBALS['wpdb']->get_results($query , OBJECT );

		                        print_rr($results);
                            }
	                        if(isset($_GET['analytics_table_repair']) && $_GET['analytics_table_repair']=='on'){



		                        $query = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS
           WHERE TABLE_SCHEMA=\''.DB_NAME.'\' AND TABLE_NAME=\''.$table_name.'\' AND column_name=\'country\'';


		                        $val = $wpdb->query($query);


//echo $query; print_r($val);

		                        $sw = false;
		                        if($val !== FALSE){
			                        //DO SOMETHING! IT EXISTS!

			                        if($val->num_rows>0){


			                        }else{

				                        $query = 'ALTER TABLE `'.$table_name.'` ADD `country` mediumtext NULL ;';


				                        $val = $wpdb->query($query);


				                        $sw = true;


			                        }

		                        }

		                        $query = 'SELECT * FROM INFORMATION_SCHEMA.COLUMNS
           WHERE TABLE_SCHEMA=\''.DB_NAME.'\' AND TABLE_NAME=\''.$table_name.'\' AND column_name=\'val\'';


		                        $val = $wpdb->query($query);


//echo $query; print_r($val);

		                        if($val !== FALSE){
			                        //DO SOMETHING! IT EXISTS!

			                        if($val->num_rows>0){


			                        }else{

				                        $query = 'ALTER TABLE `'.$table_name.'` ADD `val` int(255) NULL ;';


				                        $val = $wpdb->query($query);


				                        $sw = true;


			                        }

		                        }

		                        if($sw){

			                        echo 'table repaired!';
                                }else{

			                        echo 'table was already okay';

			                        //
		                        }




	                        }

                        }
                        ?>

	                        <?php
	                        if (ini_get('allow_url_fopen')) {
	                        } else {

	                        }
	                        ?>

                        <div class="sidenote"><?php echo __('check if the analytics table exists'); ?></div>
                    </div>







                </div>
            </div>
            <!-- system check END -->


            <?php
            if($this->mainoptions['enable_developer_options']=='on') {

                ?>




                <div class="dzs-tab-tobe tab-disabled">
                    <div class="tab-menu ">
                        &nbsp;&nbsp;
                    </div>
                    <div class="tab-content">

                    </div>
                </div>

                <div class="dzs-tab-tobe">
                    <div class="tab-menu with-tooltip">
                        <i class="fa fa-gears"></i> <?php echo __("Developer"); ?>
                    </div>
                    <div class="tab-content">
                        <br>


                        <?php
                        $lab = 'is_safebinding';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <div class="setting">
                            <h4 class="setting-label"><?php echo __('Safe binding?', 'dzsvg'); ?></h4>
                            <div class="dzscheckbox skin-nova">
                                <?php

                                echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                <label for="<?php echo $lab; ?>"></label>
                            </div>
                            <div
                                    class="sidenote"><?php echo __('the galleries admin can use a complex ajax backend to ensure fast editing, but this can cause limitation issues on php servers. Turn this to on if you want a faster editing experience ( and if you have less then 20 videos accross galleries ) '); ?></div>
                        </div>


                        <?php
                        $lab = 'disable_api_caching';
                        echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                        ?>
                        <div class="setting">
                            <h4 class="setting-label"><?php echo __('Do Not Use Caching', 'dzsvg'); ?></h4>
                            <div class="dzscheckbox skin-nova">
                                <?php

                                echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                <label for="<?php echo $lab; ?>"></label>
                            </div>
                            <div
                                    class="sidenote"><?php echo __('use caching for vimeo / youtube api ( recommended - on )'); ?></div>
                        </div>


                        <div class="setting">

                            <?php
                            $lab = 'admin_enable_for_users';
                            echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                            ?>
                            <h4 class="setting-label"><?php echo __('Enable Visitors Gallery Access', 'dzsvg'); ?></h4>
                            <div class="dzscheckbox skin-nova">
                                <?php
                                echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                <label for="<?php echo $lab; ?>"></label>
                            </div>
                            <div
                                    class="sidenote"><?php echo __('your logged in users will be able to have their own galleries', 'dzsvg'); ?></div>
                        </div>

                        <?php

                        if (ini_get('allow_url_fopen')) {
                            $lab = 'force_file_get_contents';
                            ?>

                            <div class="setting">

                                <?php

                                echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                                ?>
                                <h4 class="setting-label"><?php echo __('Force File Get Contents', 'dzsvg'); ?></h4>
                                <div class="dzscheckbox skin-nova">
                                    <?php
                                    echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                    <label for="<?php echo $lab; ?>"></label>
                                </div>
                                <div
                                        class="sidenote"><?php echo __('sometimes curl will not work for retrieving youtube user name / playlist - try enabling this option if so...', 'dzsvg'); ?></div>
                            </div>

                            <?php

                        } else {
                            echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));

                        }
                        ?>


                        <div class="setting">

                            <?php
                            $lab = 'replace_jwplayer';
                            echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                            ?>
                            <h4 class="setting-label"><?php echo __('Replace JWPlayer', 'dzsvg'); ?></h4>
                            <div class="dzscheckbox skin-nova">
                                <?php
                                echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                <label for="<?php echo $lab; ?>"></label>
                            </div>
                            <div
                                    class="sidenote"><?php echo __('render jw player shortcodes with DZS Video Gallery', 'dzsvg'); ?></div>
                        </div>


                        <div class="setting">

                            <?php
                            $lab = 'include_featured_gallery_meta';
                            echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                            ?>
                            <h4 class="setting-label"><?php echo __('Include Featured Gallery Option', 'dzsvg'); ?></h4>
                            <div class="dzscheckbox skin-nova">
                                <?php
                                echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                <label for="<?php echo $lab; ?>"></label>
                            </div>
                            <div
                                    class="sidenote"><?php echo __('only works on certain themes', 'dzsvg'); ?></div>
                        </div>


                        <div class="setting">

                            <?php
                            $lab = 'tinymce_enable_preview_shortcodes';
                            echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                            ?>
                            <h4 class="setting-label"><?php echo __('Enable Preview Shortcodes in TinyMce Editor', 'dzsvg'); ?></h4>
                            <div class="dzscheckbox skin-nova">
                                <?php
                                echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                <label for="<?php echo $lab; ?>"></label>
                            </div>
                            <div
                                    class="sidenote"><?php echo __('add a box with the shortcode in the tinymce Visual Editor', 'dzsvg'); ?></div>
                        </div>


                        <div class="setting">

                            <?php
                            $lab = 'use_external_uploaddir';
                            echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                            ?>
                            <h4 class="setting-label"><?php echo __('External Upload Dir', 'dzsvg'); ?></h4>
                            <div class="dzscheckbox skin-nova">
                                <?php
                                echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                <label for="<?php echo $lab; ?>"></label>
                            </div>
                            <div
                                    class="sidenote"><?php echo __('use external uploaddir', 'dzsvg'); ?></div>
                        </div>


                        <div class="setting">

                            <?php
                            $lab = 'debug_mode';
                            echo DZSHelpers::generate_input_text($lab, array('id' => $lab, 'val' => 'off', 'input_type' => 'hidden'));
                            ?>
                            <h4 class="setting-label"><?php echo __('Debug Mode', 'dzsvg'); ?></h4>
                            <div class="dzscheckbox skin-nova">
                                <?php
                                echo DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'val' => 'on', 'seekval' => $this->mainoptions[$lab])); ?>
                                <label for="<?php echo $lab; ?>"></label>
                            </div>
                            <div
                                    class="sidenote"><?php echo __('activate debug mode ( advanced mode )', 'dzsvg'); ?></div>
                        </div>


                        <?php
                        $lab = 'cache_time';
                        ?>
                        <div class="setting">

                            <h4 class="setting-label"><?php echo __("Cache Time in settings"); ?></h4>


                            <?php echo $this->misc_input_text($lab, array('val' => '', 'extraattr' => ' style="width: 100%; "', 'seekval' => $this->mainoptions[$lab])); ?>

                            <div class="sidenote"><?php echo __('a value between 7200 and 166400 is recommended'); ?></div>
                        </div>



                        <?php
                        $lab = 'dzsvp_tabs_breakpoint';
                        ?>
                        <div class="setting">

                            <h4 class="setting-label"><?php echo __("Upload Form Breakpoint"); ?></h4>


                            <?php echo $this->misc_input_text($lab, array('val' => '', 'extraattr' => ' style="width: 100%; "', 'seekval' => $this->mainoptions[$lab])); ?>

                            <div class="sidenote"><?php echo __('the breakpoint of the front end video submit  form - in which the tabs transform to accordions'); ?></div>
                        </div>


                        <?php
                        $lab = 'advanced_videopage_custom_action_contor_10_secs';
                        ?>
                        <div class="setting">

                            <h4 class="setting-label"><?php echo __("Video Page 10 sec. Javascript Function"); ?></h4>


                            <?php echo $this->misc_input_textarea($lab, array('val' => '', 'extraattr' => ' style="width: 100%; "', 'seekval' => $this->mainoptions[$lab])); ?>

                            <div class="sidenote"><?php echo __('warning - developers only'); ?></div>
                        </div>


                        <!-- end developer settings -->


                    </div>
                </div>

                <?php
            }
                ?>



            <!-- system check END --><?php

            do_action('dzsvg_mainoptions_extra_in_tab');

            ?>

        </div><!-- end .dzs-tabs -->


        <?php

        wp_enqueue_style('dzstoggle', $this->thepath . 'dzstoggle/dzstoggle.css');
        wp_enqueue_script('dzstoggle', $this->thepath . 'dzstoggle/dzstoggle.js');

        do_action('dzsvg_mainoptions_extra');
        ?>
        <br/>
        <a href='#'
           class="button-primary dzsvg-mo-save-mainoptions"><?php echo __('Save Options', 'dzsvg'); ?></a>
    </form>
    <br/><br/>
    <div class="dzstoggle toggle1" rel="">
        <div class="toggle-title" style=""><?php echo __('Delete Settings', 'dzsvg'); ?></div>
        <div class="toggle-content">
            <form class="mainsettings" method="POST" style="display: inline-block; margin-right: 5px;">
                <button name="dzsvg_delete_cache" value="on"
                        class="button-secondary"><?php echo __('Delete All Caches', 'dzsvg'); ?></button>
            </form>

            <form class="delete-all-settings" method="POST" style="display: inline-block; margin-right: 5px;">
                <button name="dzsvg_delete_all_options" value="on"
                        class="button-secondary"><?php echo __('Delete All Content', 'dzsvg'); ?></button>


                <?php
                wp_nonce_field( 'dzsvg_delete_all_options_nonce', 'dzsvg_delete_all_options_nonce' );
                ?>
            </form>
        </div>
    </div>

    <div class="sidenote"><?php echo __("Delete all YouTube and Vimeo channel feeds caches", 'dzsvg'); ?></div>
    <br/>

    <div class="saveconfirmer" style=""><img alt="" style="" id="save-ajax-loading2"
                                             src="<?php echo site_url(); ?>/wp-admin/images/wpspin_light.gif"/>
    </div>
</div>
<div class="clear"></div><br/>
<?php
}
