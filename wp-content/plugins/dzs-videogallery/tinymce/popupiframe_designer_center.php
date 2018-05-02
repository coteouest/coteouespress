<div class="wrap">
    <?php
//    print_r($dc_config);

        ?>

    <?php


    $vp_skin = 'skin_pro';


    if(isset($_GET['skin'])){
        $vp_skin = $_GET['skin'];
    }
    ?>
        <h1><?php echo __('Video Gallery Designer Center', 'dzsvg'); ?></h1>
        <?php if (defined("DZSVG_PREVIEW") && DZSVG_PREVIEW == 'YES') { ?>
            <div class="comment"><?php echo __('Hello and welcome to DZS Video / YouTube / Vimeo Gallery Designer Center. As this is only a preview, it will not save the changes in the primary database, but it will create temp files so you can preview the full power of this 
                    tool ( click <strong>Preview</strong> from the right ). You may notice that you would not find here all the options that you may need for fully customising the gallery. That is because here are only the options that are stricly related to the controls
                 of the gallery. The others like menu position, video list etc. are found in the main xml file ( gallery.xml ) you can find a full list of those options at the bottom.', 'dzsvg'); ?>
            </div>
        <?php } ?>
        <hr>
        <form class="settings-html5vg">
            <div class="settings_block">
                <h2 style="font-weight:normal;"><?php echo __('Modify <strong>Colors</strong> of the Player', 'dzsvg'); ?></h2>




                <div class="setting">
                    <h5 class="setting-label"><?php echo __("Background"); ?></h5>
                    <?php
                    $sname = 'background';
                    $val = $this->mainoptions_dc[$sname];
                    echo $this->add_cp_field($sname, array('class' => 'dc-input', 'val' => $val));
                    ?>
                </div>
                <div class="setting">
                    <div class="setting-label"><?php echo __("Controls"); ?> <?php echo __("Background"); ?></div>
                    <?php
                    $sname = 'controls_background';
                    $val = $this->mainoptions_dc[$sname];
                    echo $this->add_cp_field($sname, array('class' => 'dc-input', 'val' => $val));
                    ?>
                </div>
                <div class="setting">
                    <div class="setting-label">Scrubbar Background</div>
                    <?php
                    $sname = 'scrub_background';
                    $val = $this->mainoptions_dc[$sname];
                    echo $this->add_cp_field($sname, array('class' => 'dc-input', 'val' => $val));
                    ?>
                </div>
                <div class="setting">
                    <div class="setting-label">Scrubbar Buffer</div>
                    <?php
                    $sname = 'scrub_buffer';
                    $val = $this->mainoptions_dc[$sname];
                    echo $this->add_cp_field($sname, array('class' => 'dc-input', 'val' => $val));
                    ?>
                </div>
                <div class="setting">
                    <div class="setting-label"><?php echo __("Controls Main Color"); ?></div>
                    <?php
                    $sname = 'controls_color';
                    $val = $this->mainoptions_dc[$sname];
                    echo $this->add_cp_field($sname, array('class' => 'dc-input', 'val' => $val));
                    ?>
                </div>
                <div class="setting">
                    <div class="setting-label"><?php echo __("Controls Hover Color"); ?></div>
                    <?php
                    $sname = 'controls_hover_color';
                    $val = $this->mainoptions_dc[$sname];
                    echo $this->add_cp_field($sname, array('class' => 'dc-input', 'val' => $val));
                    ?>
                </div>
                <div class="setting">
                    <div class="setting-label"><?php echo __("Controls Highlight Color");?></div>
                    <?php
                    $sname = 'controls_highlight_color';
                    $val = $this->mainoptions_dc[$sname];
                    echo $this->add_cp_field($sname, array('class' => 'dc-input', 'val' => $val));
                    ?>
                </div>
                <div class="setting">
                    <div class="setting-label"><?php echo __("Current Time Color"); ?></div>
                    <?php
                    $sname = 'timetext_curr_color';
                    $val = $this->mainoptions_dc[$sname];
                    echo $this->add_cp_field($sname, array('class' => 'dc-input', 'val' => $val));
                    ?>
                </div>
                <br/>
                <div class="toggle">
                    <div class="toggle-title"><h3><?php echo __('Gallery Thumbs Design', 'dzsvg'); ?></h3>
                        <div class="arrow-down"></div>
                    </div>

                    <div class="toggle-content" style="display:none">

                        <div class="setting">
                            <div class="setting-label"><?php echo __("Background Color"); ?></div>
                            <?php
                            $sname = 'thumbs_bg';
                            $val = $this->mainoptions_dc[$sname];
                            echo $this->add_cp_field($sname, array('class' => 'dc-input', 'val' => $val));
                            ?>
                        </div>
                        <div class="setting">
                            <div class="setting-label"><?php echo __("Active Background Color"); ?></div>
                            <?php
                            $sname = 'thumbs_active_bg';
                            $val = $this->mainoptions_dc[$sname];
                            echo $this->add_cp_field($sname, array('class' => 'dc-input', 'val' => $val));
                            ?>
                        </div>
                        <div class="setting">
                            <div class="setting-label"><?php echo __("Text Color"); ?></div>
                            <?php
                            $sname = 'thumbs_text_color';
                            $val = $this->mainoptions_dc[$sname];
                            echo $this->add_cp_field($sname, array('class' => 'dc-input', 'val' => $val));
                            ?>
                        </div>
                        <div class="setting">
                            <div class="setting-label">Thumbnail Image Width</div>
                            <?php
                            $sname = 'thumbnail_image_width';
                            $val = '';
                            if (isset($this->mainoptions_dc[$sname])) {
                                $val = $this->mainoptions_dc[$sname];
                            }
                            echo DZSHelpers::generate_input_text($sname, array('class' => 'dc-input', 'seekval' => $val));
                            ?>
                        </div>
                        <div class="setting">
                            <div class="setting-label">Thumbnail Image Height</div>
                            <?php
                            $sname = 'thumbnail_image_height';
                            $val = '';
                            if (isset($this->mainoptions_dc[$sname])) {
                                $val = $this->mainoptions_dc[$sname];
                            }
                            echo DZSHelpers::generate_input_text($sname, array('class' => 'dc-input', 'seekval' => $val));
                            ?>
                        </div>

                    </div>
                </div>


            </div>
            <div class="preview_block">
                <div>
                    <h2><?php echo __('Preview', 'dzsvg'); ?></h2>
                    <style id="html5vg-preview-style"></style>



                    <div class="setting styleme ">
                        <h5 class="setting-label"><?php echo __('Video Player Skin', 'dzsvg'); ?></h5>

                        <?php
                        echo DZSHelpers::generate_select(vp_skin,array(
                                'options'=>array(
                                        'skin_pro',
                                        'skin_aurora',
                                        'skin_default',
                                        'skin_white',
                                        'skin_bigplay',
                                        'skin_reborn',
                                        'skin_avanti',
                                ),
                            'class'=>'textinput mainsetting dzs-style-me skin-beige ',
                            'seekval'=>$vp_skin,
                        ))
                        ?>
                        <div class="sidenote"><?php echo __('choose a skin to preview changes', 'dzsvg'); ?></div>
                    </div>
                    <br>

                    <div id="html5vg-preview" class="videogallery skin_pro no-mouse-out"
                         style="width:100%; height:300px;">

                        <div class="vplayer-tobe" data-videoTitle="YouTube Video" data-type="video"
                             data-src="http://techslides.com/demos/sample-videos/small.mp4">
                            <div class="menuDescription">
                                <div class="the-title">This is an Self hosted video</div>
                                The thumbnail cannot autogenerate...
                            </div>
                        </div>
                        <div class="vplayer-tobe" data-videoTitle="YouTube Video" data-type="video"
                             data-src="http://techslides.com/demos/sample-videos/small.mp4">
                            <div class="menuDescription"><div class="divimage imgblock" style="background-image: url(http://i.imgur.com/N5izAeb.jpg)"></div>
                                <div class="the-title">This is an Self hosted video</div>
                                The thumbnail cannot autogenerate...
                            </div>
                        </div>
                        <div class="vplayer-tobe" data-videoTitle="YouTube Video" data-type="video"
                             data-src="http://techslides.com/demos/sample-videos/small.mp4">
                            <div class="menuDescription"><div class="divimage imgblock" style="background-image: url(http://i.imgur.com/N5izAeb.jpg)"></div>
                                <div class="the-title">This is an Self hosted video</div>
                                The thumbnail cannot autogenerate...
                            </div>
                        </div>
                        <div class="vplayer-tobe" data-videoTitle="YouTube Video" data-type="video"
                             data-src="http://techslides.com/demos/sample-videos/small.mp4">
                            <div class="menuDescription"><div class="divimage imgblock" style="background-image: url(http://i.imgur.com/N5izAeb.jpg)"></div>
                                <div class="the-title">This is an Self hosted video</div>
                                The thumbnail cannot autogenerate...
                            </div>
                        </div>
                        <div class="vplayer-tobe" data-videoTitle="YouTube Video" data-type="video"
                             data-src="http://techslides.com/demos/sample-videos/small.mp4">
                            <div class="menuDescription"><div class="divimage imgblock" style="background-image: url(http://i.imgur.com/N5izAeb.jpg)"></div>
                                <div class="the-title">This is an Self hosted video</div>
                                The thumbnail cannot autogenerate...
                            </div>
                        </div>
                        <div class="vplayer-tobe" data-videoTitle="YouTube Video" data-type="youtube"
                             data-src="AaD0o9q5HXs">
                            <div class="menuDescription">{ytthumb}
                                <div class="the-title">This is an YouTube video</div>
                                The thumbnail can autogenerate...
                            </div>
                        </div>
                        <div class="vplayer-tobe" data-videoTitle="YouTube Video" data-type="youtube"
                             data-src="O2-hiHUh4UQ">
                            <div class="menuDescription">{ytthumb}
                                <div class="the-title">This is an YouTube video</div>
                                The thumbnail can autogenerate...
                            </div>
                        </div>
                        <div class="vplayer-tobe" data-videoTitle="YouTube Video" data-type="youtube"
                             data-src="J-F2q77fhkM">
                            <div class="menuDescription">{ytthumb}
                                <div class="the-title">This is an YouTube video</div>
                                The thumbnail can autogenerate...
                            </div>
                        </div>
                    </div>
                    <!--END VIDEO GALLERY-->
                    <script>

                        var videoplayersettings = {
                            autoplay: "off",
                            constrols_out_opacity: 0.9,
                            constrols_normal_opacity: 0.9
                            , settings_video_overlay: 'on'
                            , responsive_ratio: 'detect'
                            , settings_hideControls: "off"
                            , design_skin: "sameasgallery"
                            , youtube_defaultQuality: 'hd'
                            <?php
                            $sname = 'controls_color';
                            $val = $this->mainoptions_dc[$sname];
                            if ($val != '') {
                                echo ',controls_fscanvas_bg:"' . $val . '"';
                            }
                            $sname = 'controls_hover_color';
                            $val = $this->mainoptions_dc[$sname];
                            if ($val != '') {
                                echo ',controls_fscanvas_hover_bg:"' . $val . '"';
                            }
                            ?>
                        };
                        jQuery(document).ready(function ($) {

                            <?php

                                ?>
                            videoplayersettings.design_skin = "<?php echo $vp_skin; ?>";
                            videoplayersettings.settings_youtube_usecustomskin = "on";
                            dzsvg_init("#html5vg-preview", {
                                totalWidth: '100%',
                                settings_mode: "normal",
                                menuSpace: 0,
                                randomise: "off",
                                autoplay: "on",
                                cueFirstVideo: "off",
                                autoplayNext: "off",
                                menuitem_width: 275,
                                menuitem_height: 75,
                                menuitem_space: 1,
                                nav_space: '0',
                                menu_position: "right",
                                transition_type: "slideup",
                                design_skin: "skin_navtransparent",
                                videoplayersettings: videoplayersettings
                                , design_shadow: "on"
                                , settings_menu_overlay: 'on'
                                , settings_disableOutBehaviour: 'on'
                            });
                        });
                    </script>
                </div>


            </div>
        </form>

        <div class="clear"></div><p>&nbsp;</p>
        <div class="sidenote">
            <?php echo __('Other design options can be found in the main admin under Html5 Gallery Options.', 'dzsvg'); ?>
            <br/>
            <img src="<?php echo $this->thepath; ?>admin/img/design_main.png"/>
        </div>
        <p><?php echo esc_html__('Remember that in order to use the colors set up here, you must go the video player configuration you are using, and enable Custom Colors.', 'dzsvg'); ?></p>


        <?php

    ?>
    <div class="clear"></div>

    <br/>
    <?php

    if (defined("DZSVG_PREVIEW") && DZSVG_PREVIEW == 'YES') {
        echo '<div>Because preview mode is enabled, saving is disabled. You can still preview your configuration from the Preview button in the right half.</div>';
    }
    ?>
    <a class="<?php
    if (!(defined("DZSVG_PREVIEW") && DZSVG_PREVIEW == 'YES')) {
        echo 'save-button ';
    }
    ?> button-primary" href="#"><?php echo __('Save colors','dzsvg'); ?></a><div id="save-ajax-loading" class="preloader"></div>
    <div class="clear"></div><br/>
</div>