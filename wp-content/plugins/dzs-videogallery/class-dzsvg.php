<?php

class DZSVideoGallery {

    public $thepath;
    public $base_path;
    public $base_url;
    public $slider_index = 0;
    public $sliders_index = 0;
    public $index_players = 0;
    public $cats_index = 0;
    public $the_shortcode = 'videogallery';
    public $capability_user = 'read';
    public $capability_admin = 'manage_options';
    public $dbitemsname = 'zsvg_items';
    public $dbvpconfigsname = 'zsvg_vpconfigs';
    public $dboptionsname = 'zsvg_options';
    public $dbdcname = 'zsvg_options_dc';
    public $dbs = array();
    public $dbdbsname = 'zsvg_dbs';
    public $currDb = '';
    public $currSlider = '';
    public $sliderstructure = '';
    public $itemstructure = '';
    public $mainitems;

    public $vpsettingsdefault = array();
    public $arr_api_errors = array();

    public $mainoptions;
    public $mainoptions_dc;
    public $mainoptions_dc_aurora;
    public $mainvpconfigs;
    public $mainoptions_default;
    public $pluginmode = "plugin";
    public $alwaysembed = "on";
    public $httpprotocol = 'https';
    public $adminpagename = 'dzsvg_menu';
    public $adminpagename_configs = 'dzsvg-vpc';
    public $adminpagename_designercenter = 'dzsvg-dc';
    public $adminpagename_mainoptions = 'dzsvg-mo';
    public $adminpagename_autoupdater = 'dzsvg-autoupdater';
    public $adminpagename_about = 'dzsvg-about';
    public $dbname_dc_aurora = 'dzsvg_options_dc';
    private $usecaching = true;
    private $addons_dzsvp_activated = false;
    private $sw_content_added = false;
    public $redirect_to_intro_page = false;
    public $db_has_read_mainitems = false;
    public $multisharer_on_page = false;


    public $analytics_views = array(); // -- video title, views, date, country
    public $analytics_minutes = array(); // -- video title, seconds, date, country
    public $analytics_users = array(); // -- user id , video title, views, seconds
    public $analytics_ip_country_db = array(); // -- ip , country

    public $plugin_justactivated = false; // -- shows if the plugin has just been activated

    public $video_player_configs = array();
    public $options_array_player = array();
    public $options_array_playlist = array();
    public $notifications = array();

    private $call_index = 50; // -- only allow 50 calls of the main shortcode function to prevent stack overflow

    public $options_item_meta = array();

	public $taxname_sliders = 'dzsvg_sliders';
	public $install_type = 'normal';

	public $allowed_tags = array(
		'p'=>array(
		        'class'=>array()
        ),
		'strong'=>array(),
		'em'=>array(),
		'a'=>array(
			'href' => array(),
			'target' => array(),
		),
	);


	public $options_slider_categories_lng = array();
	public $item_meta_categories_lng = array();

    function __construct() {
        if ($this->pluginmode == 'theme') {
            $this->thepath = THEME_URL . 'plugins/dzs-videogallery/';
        } else {
            $this->thepath = plugins_url('', __FILE__) . '/';
        }


        $this->base_path = dirname(__FILE__) . '/';
        $this->base_url = $this->thepath;

        $currDb = '';
        if (isset($_GET['dbname'])) {
            $this->currDb = $_GET['dbname'];
            $currDb = $_GET['dbname'];
        }


        if (isset($_GET['currslider'])) {
            $this->currSlider = $_GET['currslider'];
        } else {
            $this->currSlider = 0;
        }


        $this->dbs = get_option($this->dbdbsname);
        //$this->dbs = '';
        if ($this->dbs == '') {
            $this->dbs = array('main');
            update_option($this->dbdbsname, $this->dbs);
        }
        if (is_array($this->dbs) && !in_array($currDb, $this->dbs) && $currDb != 'main' && $currDb != '') {
            array_push($this->dbs, $currDb);
            update_option($this->dbdbsname, $this->dbs);
        }
        //echo 'ceva'; print_r($this->dbs);
        if ($currDb != 'main' && $currDb != '') {
            $this->dbitemsname .= '-' . $currDb;
        }



        include("class_parts/options-item-meta.php");
        $this->mainitems = get_option($this->dbitemsname);
        if ($this->mainitems == '') {
            $mainitems_default_ser = file_get_contents(dirname(__FILE__) . '/sampledata/sample_items.txt');
            $this->mainitems = unserialize($mainitems_default_ser);
            update_option($this->dbitemsname, $this->mainitems);
        }

        $this->mainvpconfigs = get_option($this->dbvpconfigsname);
        //cho 'ceva'.is_array($this->mainvpconfigs);
        if ($this->mainvpconfigs == '' || (is_array($this->mainvpconfigs) && count($this->mainvpconfigs) == 0)) {
            //echo 'ceva';
            $this->mainvpconfigs = array();
            $aux = file_get_contents(dirname(__FILE__) . '/sampledata/sample_vpconfigs.txt');
            $this->mainvpconfigs = unserialize($aux);
            //print_r($this->mainvpconfigs);
            //$this->mainitems = array();
            update_option($this->dbvpconfigsname, $this->mainvpconfigs);
        }
        $vpconfigsstr = '';
        foreach ($this->mainvpconfigs as $vpconfig) {
            //print_r($vpconfig);
            $vpconfigsstr .= '<option value="' . $vpconfig['settings']['id'] . '">' . $vpconfig['settings']['id'] . '</option>';
            $aux = array(
                'label'=>$vpconfig['settings']['id'],
                'value'=>$vpconfig['settings']['id'],
            );

            array_push($this->video_player_configs, $aux);
        }


        $this->vpsettingsdefault = array('id' => 'default', 'skin_html5vp' => 'skin_aurora',
            'html5design_controlsopacityon' => '1', 'html5design_controlsopacityout' => '1',
            'defaultvolume' => '',
            'youtube_sdquality' => 'small', 'youtube_hdquality' => 'hd720', 'youtube_defaultquality' => 'hd', 'yt_customskin' => 'on', 'vimeo_byline' => '0', 'vimeo_portrait' => '0', 'vimeo_color' => '',
            'enable_info_button' => 'off',
            'settings_video_overlay' => 'off',
        );


	    $this->mainoptions_default = array('usewordpressuploader' => 'on', 'embed_masonry' => 'on', 'is_safebinding' => 'on', 'disable_api_caching' => 'off', 'disable_fontawesome' => 'off',
	                                       'debug_mode' => 'off',
	                                       'cache_time' => '7200',
	                                       'dzsvp_tabs_breakpoint' => '380',
	                                       'youtube_api_key' => 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4',
	                                       'youtube_playfrom' => '',
	                                       'youtube_hide_non_embeddable' => 'off',
	                                       'vimeo_api_user_id' => '',
	                                       'vimeo_api_client_id' => '',
	                                       'vimeo_api_client_secret' => '',
	                                       'vimeo_api_access_token' => '',
	                                       'vimeo_api_access_token_secret' => '',
	                                       'always_embed' => 'off',
	                                       'extra_css' => '',
	                                       'dzsvg_sliders_rewrite' => 'video-gallery',
	                                       'use_external_uploaddir' => 'off',
	                                       'admin_close_otheritems' => 'on',
	                                       'admin_enable_for_users' => 'off',
	                                       'force_file_get_contents' => 'off',
	                                       'merge_social_into_one' => 'off',
	                                       'social_social_networks' => '<h6  class="social-heading">Social Networks</h6> 
            <a class="social-icon" href="#" onclick=\'window.dzsvg_open_social_link("https://www.facebook.com/sharer.php?u={{replacewithcurrurl}}&amp;title=test"); return false;\'><i class="fa fa-facebook-square"></i><span class="the-tooltip">SHARE ON FACEBOOK</span></a>


                            <a class="social-icon" href="#" onclick=\'window.dzsvg_open_social_link("https://twitter.com/share?url={{replacewithcurrurl}}&amp;text=Check this out!&amp;via=ZoomPortal&amp;related=yarrcat"); return false;\'><i class="fa fa-twitter"></i><span class="the-tooltip">SHARE ON TWITTER</span></a>
                            <a class="social-icon" href="#" onclick="window.dzsvg_open_social_link("https://plus.google.com/share?url={{replacewithcurrurl}}"); return false; "><i class="fa fa-google-plus-square"></i><span class="the-tooltip">SHARE ON GOOGLE PLUS</span></a>


                            <a class="social-icon" href="#" onclick=\'window.dzsvg_open_social_link("https://www.linkedin.com/shareArticle?mini=true&amp;url={{replacewithcurrurl}}&amp;title=Check%20this%20out%20&amp;summary=&amp;source=http://localhost:8888/soundportal/source/index.php?page=page&amp;page_id=20"); return false; \'><i class="fa fa-linkedin"></i><span class="the-tooltip">SHARE ON LINKEDIN</span></a>

                            <a class="social-icon" href="#" onclick=\'window.dzsvg_open_social_link("https://pinterest.com/pin/create/button/?url={{replacewithcurrurl}}&amp;text=Check this out!&amp;via=ZoomPortal&amp;related=yarrcat"); return false;\'><i class="fa fa-pinterest"></i><span class="the-tooltip">SHARE ON PINTEREST</span></a>',
	                                       'social_share_link' => '',
	                                       'social_embed_link' => '<h6 class="social-heading">Embed Code</h6>
<textarea rows="4" class="field-for-view field-for-view-embed-code">{{replacewithembedcode}}</textarea>',
	                                       'vimeo_thumb_quality' => 'medium',
	                                       'include_featured_gallery_meta' => 'off',
	                                       'replace_jwplayer' => 'off',
	                                       'replace_wpvideo' => 'off',
	                                       'enable_video_showcase' => 'on',
	                                       'capabilities_added' => 'off',
	                                       'videoplayer_end_exit_fullscreen' => 'on',
	                                       'enable_developer_options' => 'off',
	                                       'track_views' => 'off',
	                                       'enable_widget' => 'off',
	                                       'loop_playlist' => 'on',
	                                       'advanced_videopage_custom_action_contor_10_secs' => '',
	                                       'enable_cs' => 'off',
	                                       'dzsvp_upload_image_default' => 'https://placeholdit.imgix.net/~text?txtsize=33&txt=placeholder&w=300&h=300',
	                                       'dzsvp_use_default_image' => 'off',
	                                       'dzsvp_try_to_generate_image' => 'off',
	                                       'dzsvp_enable_uncategorized_category' => 'on',
	                                       'videopage_show_views' => 'off',
	                                       'videopage_show_likes' => 'off',
	                                       'videopage_resize_proportional' => 'off',
	                                       'zoombox_autoplay' => 'off',
	                                       'videopage_autoplay' => 'on',
	                                       'videopage_autoplay_next' => 'off',
	                                       'videopage_autoplay_next_direction' => 'normal',
	                                       'enable_auto_backup' => 'on',
	                                       'tinymce_enable_preview_shortcodes' => 'on',
	                                       'settings_trigger_resize' => 'off',
	                                       'settings_limit_notice_dismissed' => 'off',
	                                       'translate_skipad' => __('Skip Ad'),
	                                       'translate_all' => '',
	                                       'translate_share' => '',
	                                       'easing_speed' => '',
	                                       'dzsvg_purchase_code' => '',
	                                       'dzsvg_purchase_code_binded' => 'off',
	                                       'dzsvp_video_config' => 'default',
	                                       'zoombox_video_config' => 'skinauroradefault',
	                                       'dzsvp_enable_likes' => 'on',
	                                       'dzsvp_enable_ratings' => 'off',
	                                       'dzsvp_enable_user_upload_capability' => 'on',
	                                       'dzsvp_upload_user_media_library' => 'on',
	                                       'dzsvp_enable_viewcount' => 'off',
	                                       'dzsvp_enable_likescount' => 'off',
	                                       'dzsvp_enable_ratingscount' => 'off',
	                                       'dzsvp_enable_visitorupload' => 'off',
	                                       'dzsvp_tab_share_content' => '<span class="share-icon-active"><iframe src="//www.facebook.com/plugins/like.php?href={{currurl}}&amp;width&amp;layout=button_count&amp;action=like&amp;show_faces=false&amp;share=false&amp;height=21&amp;appId=569360426428348" scrolling="no" frameborder="0" style="border:none; overflow:hidden; height:21px;" allowTransparency="true"></iframe></span>
<span class="share-icon-active"><div class="g-plusone" data-size="medium"></div></span>
<span class="share-icon-active"><a href="https://twitter.com/share" class="twitter-share-button" data-via="ZoomItFlash">Tweet</a></span><h5>Embed</h5><div class="dzsvp-code">{{embedcode}}</div>
<script type="text/javascript">
  (function() {
    var po = document.createElement("script"); po.type = "text/javascript"; po.async = true;
    po.src = "https://apis.google.com/js/platform.js";
    var s = document.getElementsByTagName("script")[0]; s.parentNode.insertBefore(po, s);
  })();
!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?"http":"https";if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document, "script", "twitter-wjs");</script>',
	                                       'dzsvp_enable_tab_playlist' => 'on',
	                                       'dzsvp_enable_facebooklogin' => 'off',
	                                       'dzsvp_facebook_loginappid' => '',
	                                       'dzsvp_facebook_loginsecret' => '',
	                                       'dzsvp_page_upload' => '',
	                                       'dzsvp_post_name' => __("Video Items",'dzsvg'),
	                                       'dzsvp_post_name_singular' => __("Video Item",'dzsvg'),
	                                       'dzsvp_categories_rewrite' => 'video_categories',
	                                       'dzsvp_tags_rewrite' => 'video_tags',
	                                       'analytics_enable' => 'off',
	                                       'analytics_enable_location' => 'off',
	                                       'analytics_enable_user_track' => 'off',
	                                       'analytics_table_created' => 'off',
	                                       'analytics_galleries' => '',
	                                       'playlists_mode' => 'legacy',
	                                       'facebook_player' => 'custom',
	                                       'facebook_app_id' => '',
	                                       'facebook_app_secret' => '',
	                                       'facebook_access_token' => '',
	    );

        $this->mainoptions = get_option($this->dboptionsname);






//	    $this->mainoptions = '';


        
        // --  default opts / inject into db
        if ($this->mainoptions == '') {
	        $this->mainoptions_default['playlists_mode']='normal';
            $this->mainoptions = $this->mainoptions_default;

            $rand = rand(0,2);

            if($rand==0){
                $this->mainoptions['youtube_api_key']= 'AIzaSyDfDDHWTqJ6iOcASL3wLcpTvPWjmC-NnVk';
            }
            if($rand==1){
                $this->mainoptions['youtube_api_key']= 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4';
            }

            // -- new install
            $this->install_type = 'new';

//            error_log(' we have new options here $this->install_type - '.$this->install_type);



            update_option($this->dboptionsname, $this->mainoptions);

        }

//        print_r($defaultOpts); print_r($this->mainoptions);
        $this->mainoptions = array_merge($this->mainoptions_default, $this->mainoptions);
        //print_r($this->mainoptions);

        
        // -- translation stuff
        load_plugin_textdomain('dzsvg', false, basename(dirname(__FILE__)) . '/languages');


        $def_options_dc = array('background' => '#111111', 'controls_background' => '#333333', 'scrub_background' => '#333333', 'scrub_buffer' => '#555555', 'controls_color' => '#aaaaaa', 'controls_hover_color' => '#dddddd', 'controls_highlight_color' => '#db4343', 'thumbs_bg' => '#333333', 'thumbs_active_bg' => '#777777', 'thumbs_text_color' => '#eeeeee', 'timetext_curr_color' => '#ffffff', 'thumbnail_image_width' => '', 'thumbnail_image_height' => '',);
        $this->mainoptions_dc = get_option($this->dbdcname);

        //==== default opts / inject into db
        if ($this->mainoptions_dc == '') {
            $this->mainoptions_dc = $def_options_dc;
            update_option($this->dbdcname, $this->mainoptions_dc);
        }

        $def_options_dc = array('background' => '#111111', 'controls_background' => '#333333', 'scrub_background' => '#333333', 'scrub_buffer' => '#555555', 'scrub_progress' => '#fdd500', 'controls_color' => '#aaaaaa', 'controls_hover_color' => '#dddddd', 'controls_highlight_color' => '#db4343',);
        $this->mainoptions_dc_aurora = get_option($this->dbname_dc_aurora);

        //==== default opts / inject into db
        if ($this->mainoptions_dc_aurora == '') {
            $this->mainoptions_dc_aurora = array();
        }
        $this->mainoptions_dc_aurora = array_merge($def_options_dc, $this->mainoptions_dc_aurora);


        require_once("class_parts/options_array_player.php");
        require_once("class_parts/options_array_playlist.php");



        if(is_admin()){

            $this->post_options();
        }else{
            if(isset($_GET['dzsvg_enabledebug'])&&$_GET['dzsvg_enabledebug']=='on'){


                $this->mainoptions['debug_mode'] = 'on';
            }
        }


        if (isset($_POST['deleteslider'])) {
            //print_r($this->mainitems);
            if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename) {
                unset($this->mainitems[$_POST['deleteslider']]);
                $this->mainitems = array_values($this->mainitems);
                $this->currSlider = 0;
                //print_r($this->mainitems);
                update_option($this->dbitemsname, $this->mainitems);
            }


            if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_configs) {
                unset($this->mainvpconfigs[$_POST['deleteslider']]);
                $this->mainvpconfigs = array_values($this->mainvpconfigs);
                $this->currSlider = 0;
                //print_r($this->mainitems);
                update_option($this->dbvpconfigsname, $this->mainvpconfigs);
            }
        }

        if (isset($_POST['dzsvg_duplicateslider'])) {
            if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename) {
                $aux = ($this->mainitems[$_POST['dzsvg_duplicateslider']]);
                array_push($this->mainitems, $aux);
                $this->mainitems = array_values($this->mainitems);
                $this->currSlider = count($this->mainitems) - 1;
                update_option($this->dbitemsname, $this->mainitems);
            }
        }

        //echo get_admin_url('', 'options-general.php?page=' . $this->adminpagename) . dzs_curr_url();
        //echo $newurl;

        $uploadbtnstring = '<button class="button-secondary action upload_file">'.__("Upload",'dzsvg').'</button>';
        $uploadbtnstring_video = '<button class="button-secondary action upload_file only-video upload-type-video">'.__("Upload",'dzsvg').'</button>';



        ///==== important: settings must have the class mainsetting
        $this->sliderstructure = '<div class="slider-con" style="display:none;">
        <div class="setting type_all">
            <div class="setting-label">' . __('Select Feed Mode', 'dzsvg') . '</div>
                <div class="main-feed-chooser select-hidden-metastyle">
                <select class="textinput mainsetting" name="0-settings-feedfrom">
                    <option value="normal">' . __('Normal', 'dzsvg') . '</option>
                    <option value="ytuserchannel">' . __('Youtube User Channel', 'dzsvg') . '</option>
                    <option value="ytplaylist">' . __('YouTube Playlist', 'dzsvg') . '</option>
                    <option value="ytkeywords">' . __('YouTube Keywords', 'dzsvg') . '</option>
                    <option value="vmuserchannel">' . __('Vimeo User Channel', 'dzsvg') . '</option>
                    <option value="vmchannel">' . __('Vimeo Channel', 'dzsvg') . '</option>
                    <option value="vmalbum">' . __('Vimeo Album', 'dzsvg') . '</option>
                    <option value="facebook">' . __('Facebook feed', 'dzsvg') . '</option>
                </select>
                <div class="option-con clearfix">
                    <div class="an-option">
                    <div class="an-title">
                    ' . __('Normal', 'dzsvg') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Feed from custom items you set below.', 'dzsvg') . '
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    ' . __('Youtube User Channel', 'dzsvg') . '
                    </div>
                    <div class="an-desc">
                    ' . __(' Feed videos from your YouTube User Channel.', 'dzsvg') . '
                   
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    ' . __('YouTube Playlist', 'dzsvg') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Feed videos from the YouTube Playlist you create on their site. Just input the Playlist ID below.', 'dzsvg') . '
                    
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    ' . __('YouTube Keywords', 'dzsvg') . '
                    </div>
                    <div class="an-desc">
                    ' . sprintf(__('Feed videos by searching for keywords ie. %sfunny cat%s', 'dzsvg'),'<strong>','</strong>') . '
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    ' . __('Vimeo User Channel', 'dzsvg') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Feed videos from your Vimeo User channel.', 'dzsvg') . '
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    ' . __('Vimeo Channel', 'dzsvg') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Feed videos from a Vimeo Channel.', 'dzsvg') . '
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    ' . __('Vimeo Album', 'dzsvg') . '
                    </div>
                    <div class="an-desc">
                    ' . __('Feed videos from a Vimeo Album.', 'dzsvg') . '
                    </div>
                    </div>
                    
                    <div class="an-option">
                    <div class="an-title">
                    ' . __('Facebook feed', 'dzsvg') . '
                    </div>
                    <div class="an-desc">
                    ' . __('input a facebook link', 'dzsvg') . '
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="settings-con">
        <h4>' . __('General Options', 'dzsvg') . '</h4>
        <div class="setting type_all">
            <div class="setting-label">' . __('ID', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting main-id" name="0-settings-id" value="default"/>
            <div class="sidenote">' . __('Choose an unique id. Do not use spaces, do not use special characters.', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Force Height', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-height" value="300"/>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Display Mode', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme has-extra-desc" name="0-settings-displaymode">
                <option>normal</option>
                <option>wall</option>
                <option>rotator</option>
                <option>rotator3d</option>
                <option>slider</option>
                <option>videowall</option>
                <option>alternatemenu</option>
                <option>alternatewall</option>
            </select>
            <div class="sidenote">' . __('<strong>alternatewall</strong> and <strong>alternatemenu</strong> are deprecated.', 'dzsvg') . '</div>
            
            <div class="extra-desc">
            
            
            <div class="bigoption select-option ">
            <div class="option-con">
            <div class="divimage" data-src="https://i.imgur.com/3iRmYlc.jpg"></div>
            <span class="option-label">'.__("Default").'</span>
            </div>
            </div>
            
            <div class="bigoption select-option ">
            <div class="option-con">
            <div class="divimage" data-src="https://i.imgur.com/YhYVMd9.jpg"></div>
            <span class="option-label">'.__("Wall").'</span>
            </div>
            </div>
            
            <div class="bigoption"></div>
            
            <div class="bigoption select-option ">
            <div class="option-con">
            <div class="divimage" data-src="https://i.imgur.com/wQrkSkv.jpg"></div>
            <span class="option-label">'.__("Rotator 3D").'</span>
            </div>
            </div>
            
            
            <div class="bigoption"></div>
            
            <div class="bigoption select-option ">
            <div class="option-con">
            <div class="divimage" data-src="https://i.imgur.com/1jThnc7.jpg"></div>
            <span class="option-label">'.__("Video Wall").'</span>
            </div>
            </div>
            
            
            
            </div>
            
            
            
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Video Gallery Skin', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-skin_html5vg">
                <option value="skin-default">'.__("Default",'dzsvg').' </option>
                <option value="skin-pro">'.__("Skin",'dzsvg').' Pro </option>
                <option value="skin-boxy">'.__("Skin",'dzsvg').' Boxy </option>
                <option value="skin-boxy skin-boxy--rounded">'.__("Skin",'dzsvg').' Boxy Rounded</option>
                <option value="skin-aurora">'.__("Skin",'dzsvg').' Aurora</option>
                <option value="skin-navtransparent">'.__("Skin",'dzsvg').' NavTransparent</option>
                <option value="skin-custom">'.__("Custom Skin",'dzsvg').'</option>
            </select>
            <div class="sidenote">' . __('Skin Custom can be modified via Designer Center.', 'dzsvg') . '</div>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Video Player Configuration', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-vpconfig">
                <option value="default">' . __('default', 'dzsvg') . '</option>
                ' . $vpconfigsstr . '
            </select>
            <div class="sidenote" style="">' . __('setup these inside the <strong>Video Player Configs</strong> admin', 'dzsvg') . ' <a id="quick-edit" class="quick-edit-vp" href="'.admin_url('admin.php?page=' . $this->adminpagename_configs.'&currslider=0&from=shortcodegenerator').'" class="sidenote" style="cursor:pointer;">'.__("Quick Edit ").'</a></div>
        </div>
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Navigation Style', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme dzs-dependency-field" name="0-settings-nav_type">
                <option>thumbs</option>
                <option>thumbsandarrows</option>
                <option>scroller</option>
                <option>outer</option>
                <option>none</option>
            </select>
            <div class="sidenote">' . __('Choose a navigation style for the normal display mode.', 'dzsvg') . '</div>
        </div>';




        $dependency = array(

            array(
                'lab'=>'0-settings-nav_type',
                'val'=>array('outer'),
            ),
        );


        $dependency = json_encode($dependency);
        $this->sliderstructure.='<div class="setting type_all"  data-dependency=\''.$dependency.'\'">
            <div class="setting-label">' . __('Max. Height For Navigation', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-nav_type_outer_max_height" value=""/>
            <div class="sidenote" style="">' . __('input a maximum height for the outer navigation ( only for bottom and top menu positions ) - if the content is larger, then a scrollbar will appear', 'dzsvg') . '</div>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Menu Position', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-menuposition">
                <option>right</option>
                <option>bottom</option>
                <option>left</option>
                <option>top</option>
                <option>none</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Autoplay', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-autoplay">
                <option value="on">' . __('on', 'dzsvg') . '</option>
                <option value="off">' . __('off', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Autoplay Next', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-autoplaynext">
                <option value="on">' . __('on', 'dzsvg') . '</option>
                <option value="off">' . __('off', 'dzsvg') . '</option>
            </select>
            <div class="sidenote">' . __('autoplay next track when selecting in the menu or when the current video has ended', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Cue First Video', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-cueFirstVideo">
                <option value="on">' . __('on', 'dzsvg') . '</option>
                <option value="off">' . __('off', 'dzsvg') . '</option>
            </select>
            <div class="sidenote">' . __('Choose if the video should load at start or it should activate on click ( if a <strong>Cover Image</strong> is set ).', 'dzsvg') . '</div>
            
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Randomize / Shuffle Elements', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-randomize">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Order', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-order">
                <option value="ASC">' . __('ascending', 'dzsvg') . '</option>
                <option value="DESC">' . __('descending', 'dzsvg') . '</option>
            </select>
        </div>
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Transition', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-transition">
                <option value="fade">' . __('Fade', 'dzsvg') . '</option>
                <option value="slidein">' . __('Slide In', 'dzsvg') . '</option>
            </select>
            <div class="sidenote" style="">' . __('set the transition of the gallery  ( when it loads ) ', 'dzsvg') . '</div>
        </div>

        <div class="setting type_all">
            <div class="setting-label">' . __('Enable Underneath Description', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enableunderneathdescription">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
            <div class="sidenote" style="">' . __('add a title and description holder underneath the gallery', 'dzsvg') . '</div>
        </div>

        <div class="setting type_all">
            <div class="setting-label">' . __('Enable Search Field', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme  dzs-dependency-field" name="0-settings-enable_search_field">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
            <div class="sidenote" style="">' . __('enable a search field inside the gallery', 'dzsvg') . '</div>
        </div>';



        $dependency = array(

            array(
                'lab'=>'0-settings-enable_search_field',
                'val'=>array('on'),
            ),
        );


        $dependency = json_encode($dependency);
        $this->sliderstructure.='<div class="setting type_all"  data-dependency=\''.$dependency.'\'">
            <div class="setting-label">' . __('Search Field Location', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-search_field_location">
                <option value="outside">' . __('Outside Gallery', 'dzsvg') . '</option>
                <option value="inside">' . __('Inside Gallery', 'dzsvg') . '</option>
            </select>
            <div class="sidenote" style="">' . __('search bar location', 'dzsvg') . '</div>
        </div>

        <div class="setting type_all">
            <div class="setting-label">' . __('Enable Linking', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_enable_linking">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
            <div class="sidenote" style="">' . __('enable the possibility for the gallery to change the current link depending on the video played, this makes it easy to go to a current video based only on link', 'dzsvg') . '</div>
        </div>


        <div class="setting type_all">
            <div class="setting-label">' . __('Autoplay Ad', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-autoplay_ad">
                <option value="on">' . __('on', 'dzsvg') . '</option>
                <option value="off">' . __('off', 'dzsvg') . '</option>
            </select>
            <div class="sidenote" style="">' . __('autoplay the ad before a video or not - note that if the video autoplay then the ad will autoplay too before', 'dzsvg') . '</div>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Resize Video Proportionally', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-set_responsive_ratio_to_detect">
                <option>off</option>
                <option>on</option>
            </select>
        </div>
        <div class="sidenote">' . __('Settings this to "on" will make an attempt to remove the black bars plus resizing the video proportionally for mobiles.', 'dzsvg') . '</div>


        <hr/>
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Social Options', 'dzsvg') . '</div>
<div class="toggle-content">

        <div class="setting type_all">
            <div class="setting-label">' . __('Share Button', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-sharebutton">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting_label">' . __('Facebook Link', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-facebooklink" value=""/>
            <div class="sidenote" style="">' . __('input here a full link to your facebook page ie. <strong><a href="https://www.facebook.com/digitalzoomstudio">https://www.facebook.com/digitalzoomstudio</a></strong> or input "<strong>{{share}}</strong>" and the button will share the current playing video', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting_label">' . __('Twitter Link', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-twitterlink" value=""/>
        </div>
        <div class="setting type_all">
            <div class="setting_label">' . __('Google Plus Link', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-googlepluslink" value=""/>
        </div>
        <div class="setting type_all">
            <div class="setting_label">' . __('Extra Social HTML', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-social_extracode" value=""/>
            <div class="sidenote" style="">' . __('you can have here some extra social icons', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Embed Button', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-embedbutton">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        
        
        <div class="setting">
            <div class="setting_label">' . __('Logo', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-logo" value=""/>' . $uploadbtnstring . '
        </div>
        <div class="setting">
            <div class="setting_label">' . __('Logo Link', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-logoLink" value=""/>
        </div>
</div>
</div>
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Menu Options', 'dzsvg') . '</div>
<div class="toggle-content">

        <div class="setting type_all">
            <div class="setting-label">' . __('Design Menu Item Width', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5designmiw" value="275"/>
            <div class="sidenote" style="">' . __('these also control the width and height for wall items', 'dzsvg').'</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Design Menu Item Height', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5designmih" value="76"/>
            <div class="sidenote" style="">' . __('these also control the width and height for wall items ( for auto height leave blank here, on wall mode )', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Design Menu Item Space', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5designmis" value="0"/>
        </div>

        <div class="setting type_all">
            <div class="setting-label">' . __('Thumbnail Extra Classes', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-thumb_extraclass" value=""/>
            <div class="sidenote" style="">' . __('add a special class to the thumbnail like <strong>thumb-round</strong> for making the thumbnails rounded', 'dzsvg') . '</div>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Disable Menu Description', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-disable_menu_description">
                <option>off</option>
                <option>on</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Enable Easing on Menu', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-design_navigationuseeasing">
                <option>off</option>
                <option>on</option>
            </select>
                <div class="sidenote" style="">' . __('for navigation type <strong>thumbs</strong> - use a easing on mouse tracking ', 'dzsvg') . '</div>
        </div>
        
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Lock Scroll', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-nav_type_auto_scroll">
                <option>off</option>
                <option>on</option>
            </select>
                <div class="sidenote" style="">' . __('for navigation type <strong>thumbs</strong> - LOCK SCROLL to current item ', 'dzsvg') . '</div>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Menu Item Format', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-menu_description_format" value=""/>
            <div class="sidenote" style="">' . sprintf(__('you can use something like %s{{number}}{{menuimage}}{{menutitle}}{{menudesc}}%s to display - menu item number , menu image, title and description or leave blank for default mode', 'dzsvg'),'<strong>','</strong>').'</div>
        </div>
        

</div>
</div>
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Design Options', 'dzsvg') . '</div>
<div class="toggle-content">


        <div class="setting type_all">
            <div class="setting-label">' . __('Max Width', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-max_width" value=""/>
            <div class="sidenote">' . __('Limit the max width of the gallery ( in pixels ) and center the gallery ', 'dzsvg') . '</div>
        </div>




        <div class="setting">
            <div class="setting_label">' . __('Cover Image', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-coverImage" value=""/>' . $uploadbtnstring . '
                <div class="sidenote">A image that appears while the video is cued / not played</div>
        </div>


        <div class="setting type_all">
            <div class="setting-label">' . __('Navigation Space', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-nav_space" value="0"/>
            <div class="sidenote" style="">' . __('space between navigation and video container', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Disable Menu Title', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-disable_title">
                <option>off</option>
                <option>on</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Disable Video Title', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-disable_video_title">
                <option>off</option>
                <option>on</option>
            </select>
        </div>

        <div class="setting type_all">
            <div class="setting-label">' . __('Laptop Skin', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-laptopskin">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
                <div class="sidenote" style="">' . __('apply a laptop container to the gallery', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Transition', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-html5transition">
                <option>slideup</option>
                <option>fade</option>
            </select>
        </div>

        <div class="setting type_all">
            <div class="setting-label">' . __('Right to Left', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-rtl">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
            <div class="sidenote" style="">' . __('enable RTL', 'dzsvg') . '</div>
        </div>



        <div class="setting">
            <div class="setting-label">' . __('Extra Classes', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-extra_classes" value=""/>
            <div class="sidenote" style="">' . __('some extra css classes that you can use to stylize this gallery', 'dzsvg') . '</div>
        </div>



        <div class="setting">
            <div class="setting-label">' . __('Background', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting with-colorpicker" name="0-settings-bgcolor" value="#444444"/><div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
        </div>

        <div class="setting type_all">
            <div class="setting-label">' . __('Enable Shadow', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-shadow">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
            </select>
        </div>
        
        
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Force Sizes', 'dzsvg') . '</div>
<div class="toggle-content">

        <div class="setting type_all">
            <div class="setting-label">' . __('Force Width', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-width" value="100%"/>
            <div class="sidenote">' . __('Leave "100%" for responsive mode. ', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Force Video Height', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-forcevideoheight" value=""/>
        <div class="sidenote">' . __('Leave this blank if you want the video to autoresize. .', 'dzsvg') . '</div>
        </div>
</div></div>

        <h5>' . __('Mode Wall Settings') . '</h5>

        <div class="setting type_all">
            <div class="setting-label">' . __('Layout for Mode Wall', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-mode_wall_layout">
                <option value="none">' . __('None', 'dzsvg') . '</option>
                <option value="dzs-layout--1-cols">' . __('1 column', 'dzsvg') . '</option>
                <option value="dzs-layout--2-cols">' . __('2 columns', 'dzsvg') . '</option>
                <option value="dzs-layout--3-cols">' . __('3 columns', 'dzsvg') . '</option>
                <option value="dzs-layout--4-cols">' . __('4 columns', 'dzsvg') . '</option>
            </select>
                <div class="sidenote" style="">' . __('the layout for the wall mode. using none will use the Design Menu Item Width and Design Menu Item Height for the item dimensions', 'dzsvg') . '</div>
        </div>


        <br>
</div>
</div>


<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Description Options', 'dzsvg') . '</div>
<div class="toggle-content">
        <div class="sidenote" style="font-size:14px;">' . __('some options regarding YouTube feed mode - playlist / user channel / ', 'dzsvg') . '</div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Max Description Length', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-maxlen_desc" value="250"/>
            <div class="sidenote" style="">' . __('youtube video descriptions will be retrieved through YouTube Data API. You can choose here the number of characters to retrieve from it. ', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Read More Markup ', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-readmore_markup" value="<p><a class=ignore-zoombox href={{postlink}}>' . __('read more') . ' &raquo;</a></p>"/>
            <div class="sidenote" style="">' . '' . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Strip HTML Tags', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-striptags">
                <option value="on">' . __('on', 'dzsvg') . '</option>
                <option value="off">' . __('off', 'dzsvg') . '</option>
                </select>
            <div class="sidenote" style="">' . __('video descriptions will be retrieved as html rich content. you can choose to strip the html tags to leave just simple text ', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Repair HTML Markup', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-try_to_close_unclosed_tags">
                <option value="on">' . __('on', 'dzsvg') . '</option>
                <option value="off">' . __('off', 'dzsvg') . '</option>
                </select>
            <div class="sidenote" style="">' . __('video descriptions will be retrieved as html rich content, some may be broken after shortage. attempt to repair this by setting this to <strong>on</strong>', 'dzsvg') . '</div>
        </div>';


        $lab = '0-settings-desc_different_settings_for_aside';
//                                echo DZSHelpers::generate_input_text($lab,array('id' => $lab, 'val' => 'off','input_type'=>'hidden'));
        $this->sliderstructure .= '<div class="setting">
                                    <h4 class="setting-label">' . __('Aside Navigation has Different Settings?', 'dzsvg') . '</h4>
                                    <div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => '')) . '
                                        <label for="' . $lab . '"></label>
                                    </div>
                                    <div class="sidenote">' . __('allow creating new accounts') . '</div>
                                </div>';


        $this->sliderstructure .= '



<div class="setting type_all appear-only-when-is-on-desc_different_settings_for_aside">
            <div class="setting-label">' . __('Max Description Length', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-desc_aside_maxlen_desc" value="250"/>
            <div class="sidenote" style="">' . __('youtube video descriptions will be retrieved through YouTube Data API. You can choose here the number of characters to retrieve from it. ', 'dzsvg') . '</div>
</div>
<div class="setting type_all appear-only-when-is-on-desc_different_settings_for_aside">
            <div class="setting-label">' . __('Strip HTML Tags', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-desc_aside_striptags">
                <option value="on">' . __('on', 'dzsvg') . '</option>
                <option value="off">' . __('off', 'dzsvg') . '</option>
                </select>
            <div class="sidenote" style="">' . __('video descriptions will be retrieved as html rich content. you can choose to strip the html tags to leave just simple text ', 'dzsvg') . '</div>
</div>
<div class="setting type_all appear-only-when-is-on-desc_different_settings_for_aside">
            <div class="setting-label">' . __('Repair HTML Markup', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-desc_aside_try_to_close_unclosed_tags">
                <option value="on">' . __('on', 'dzsvg') . '</option>
                <option value="off">' . __('off', 'dzsvg') . '</option>
                </select>
            <div class="sidenote" style="">' . __('video descriptions will be retrieved as html rich content, some may be broken after shortage. attempt to repair this by setting this to <strong>on</strong>', 'dzsvg') . '</div>
</div>





</div>
</div>


        

<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Outer Parts', 'dzsvg') . '</div>
<div class="toggle-content">
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Second Con', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_secondcon">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
                </select>
                <div class="sidenote" style="">' . __('enable linking to a slider with titles and descriptions as seen in the demos. to insert the container in your page use this shortcode [dzsvg_secondcon id="theidofthegallery" extraclasses=""]', 'dzsvg') . '</div>
            
        </div>
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Outer Navigation', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_outernav">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
                </select>
                <div class="sidenote" style="">' . __('enable linking to a outside navigation [dzsvg_outernav id="theidofthegallery" skin="oasis" extraclasses="" layout="layout-one-third" thumbs_per_page="9" ]', 'dzsvg') . '</div>
            
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Outer Navigation, Show Video Author', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_outernav_video_author">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
                </select>
                <div class="sidenote" style="">' . __('show the video author for YouTube channels and playlists', 'dzsvg') . '</div>
            
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Outer Navigation, Show Video Date', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-enable_outernav_video_date">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
                </select>
                <div class="sidenote" style="">' . __('published date', 'dzsvg') . '</div>
            
        </div>


</div>
</div>




<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Misc Options', 'dzsvg') . '</div>
<div class="toggle-content">


        <div class="setting type_all">
            <div class="setting-label">' . __('Play Order', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-playorder">
                <option value="ASC">' . __('normal', 'dzsvg') . '</option>
                <option value="DESC">' . __('reverse', 'dzsvg') . '</option>
            </select>
            <div class="sidenote" style="">' . __('set to reverse for example to play the latest episode in a series first ... or for RTL configurations', 'dzsvg') . '</div>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Initialize On', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-init_on">
                <option value="init">' . __('Init', 'dzsvg') . '</option>
                <option value="scroll">' . __('Scroll', 'dzsvg') . '</option>
            </select>
            <div class="sidenote" style="">' . __('init - at start // scroll - when visible in page view', 'dzsvg') . '</div>
        </div>

        <div class="setting type_all">
            <div class="setting-label">' . __('Ids Point to Source', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-ids_point_to_source">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
                </select>
                <div class="sidenote" style="">' . __('the id of the video players can point to the source file used', 'dzsvg') . '</div>

        </div>

        <div class="setting type_all">
            <div class="setting-label">' . __('Autoplay on Mobiles', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-autoplay_on_mobile_too_with_video_muted">
                <option value="off">' . __('off', 'dzsvg') . '</option>
                <option value="on">' . __('on', 'dzsvg') . '</option>
                </select>
                <div class="sidenote" style="">' . __('normally, videos cannot autoplay on mobiles to save bandwidth, but with newest standards videos are allowed to play, but muted - if your video has no sound you can choose this option to autoplay on mobiles', 'dzsvg') . '</div>

        </div>



</div>
</div>

';



        if($this->mainoptions['enable_developer_options']=='on'){

            $this->sliderstructure .= '


        <div class="setting type_all">
            <div class="setting-label">' . __('Javascript on Playlist End', 'dzsvg') . '</div>
            
            '.DZSHelpers::generate_input_textarea('0-settings-action_playlist_end', array(
                    'class'=>'textinput mainsetting',
                )).'
                <div class="sidenote" style="">' . __('write a javascript function that happens on playlist end ', 'dzsvg') . '</div>

        </div>
    
';
        }


        $this->sliderstructure .= '
        
        
        </div><!--end settings con-->
        <div class="modes-con">
        
        <div class="setting mode_ytuserchannel">
            <div class="setting_label">' . __('YouTube User', 'dzsvg') . '</div>
            <input type="text" class="short textinput mainsetting" name="0-settings-youtubefeed_user" value=""/>
        </div>
	<div class="setting mode_ytplaylist">
            <div class="setting_label">' . __('YouTube Playlist', 'dzsvg') . '
                <div class="info-con">
                <div class="info-icon"></div>
                <div class="sidenote">' . __('You need to set the playlist ID there not the playlist Name. For example for this playlist http:' . '/' . '' . '/' . 'www.youtube.com/my_playlists?p=PL08BACDB761A0C52A the id is 08BACDB761A0C52A. Remember that if you have the characters PL at the beggining of the ID they should not be included here.', 'dzsvg') . '</div>
                </div>
</div>
                
                <input type="text" class="short textinput mainsetting" name="0-settings-ytplaylist_source" value=""/>
        </div>
	<div class="setting mode_ytkeywords">
            <div class="setting_label">' . __('YouTube Keywords', 'dzsvg') . '
                <div class="info-con">
                <div class="info-icon"></div>
                <div class="sidenote">' . '' . '</div>
                </div>
                </div>

                <input type="text" class="short textinput mainsetting" name="0-settings-ytkeywords_source" value=""/>
        </div>
        <div class="setting type_all mode_ytuserchannel mode_ytplaylist mode_ytkeywords">
            <div class="setting-label">' . __('YouTube Max Videos', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-youtubefeed_maxvideos" value="50"/>
            <div class="sidenote">' . __('input a limit of videos here ( can be a maximum of 50 ) if you have more then 50 videos in your stream, just input "<strong>all</strong>" in this field ( without quotes ) ', 'dzsvg') . '</div>
        </div>';


        if(ini_get('allow_url_fopen') || function_exists('curl_version')  ){
        }else{

            $this->sliderstructure.='<div class="setting type_all mode_ytuserchannel mode_ytplaylist mode_ytkeywords">
            <div class="setting-label warning">' . __('warning - curl nor allow_furl_open enabled on your server ..  / ask your server to enable any of these', 'dzsvg') . '</div>
        </div>';
        }


        $this->sliderstructure.='<div class="setting type_all mode_vmuserchannel">
            <div class="setting_label">' . __('Vimeo User ID', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeofeed_user" value=""/>
            <div class="sidenote">' . sprintf(__('be sure this to be your user id . For example mine is user5137664 even if my name is digitalzoomstudio  %s  you get that by checking your profile link.', 'dzsvg'),'- https://vimeo.com/user5137664 -') . '</div>
        </div>
        
        <div class="setting type_all mode_vmchannel">
            <div class="setting_label">' . __('Vimeo Channel ID', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeofeed_channel" value=""/>
            <div class="sidenote">' . __('be sure all videos are allowed to be embedded . Channel example for  – https://vimeo.com/channels/636900 - is <strong>636900</strong>.', 'dzsvg') . '</div>
        </div>
        
        <div class="setting type_all mode_vmalbum">
            <div class="setting_label">' . __('Vimeo Album ID', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeofeed_vmalbum" value=""/>
            <div class="sidenote">' . __('be sure all videos are allowed to be embedded . Channel example for  – https://vimeo.com/album/2633720 - is <strong>2633720</strong>.', 'dzsvg') . '</div>
        </div>


        <div class="setting type_all mode_vmuserchannel mode_vmchannel mode_vmalbum">
            <div class="setting-label">' . __('Vimeo Max Videos', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeo_maxvideos" value="25"/>
            <div class="sidenote">' . __('input a limit of videos here - note that if you have not set a Vimeo API oAuth login <a href="admin.php?page=' . $this->adminpagename_mainoptions . '">here</a> /  the limit will be 20 videos with no api setup', 'dzsvg') . '</div>
        </div>
        
        <div class="setting type_all mode_facebook">
            <div class="setting_label">' . __('Facebook url', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-facebook_url" value=""/>
            <div class="sidenote">' . __('input full facebook page url', 'dzsvg') . '</div>
        </div>


        <div class="setting type_all mode_facebook">
            <div class="setting-label">' . __('Facebook Max Videos', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeo_maxvideos" value="25"/>
            <div class="sidenote">' . __('input a limit of videos here - note that if you have not set a Facebook API oAuth login ', 'dzsvg') . '</div>
        </div>


        <div class="setting type_all mode_vmuserchannel mode_vmchannel mode_vmalbum">
            <div class="setting-label">' . __('Vimeo Sort Mode', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-vimeo_sort">
                <option value="default">' . __("Default", 'dzsvg') . '</option>
                <option value="manual">' . __('Manual', 'dzsvg') . '</option>
                <option value="date">' . __('By Date', 'dzsvg') . '</option>
                <option value="alphabetic">' . __('Alphabetic', 'dzsvg') . '</option>
                <option value="plays">' . __('Number plays', 'dzsvg') . '</option>
                </select>
            <div class="sidenote">' . __('Default means as served by vimeo by default / Manual means as sorted in album settings', 'dzsvg') . '</div>
        </div>
        
</div>
        <div class="master-items-con mode_normal">
        <div class="items-con "></div>
        <a href="#" class="add-item"></a>
        </div><!--end master-items-con-->
        <div class="clear"></div>
        </div>';



//        <div class="dzstoggle toggle1" rel="">
//<div class="toggle-title" style="">' . __('Advertising Settings', 'dzsvg') . '</div>
//<div class="toggle-content">
//        <div class="setting type_all">
//            <div class="setting-label">' . __('Ad Source', 'dzsvg') . '</div>
//            <div class="sidenote">' . __('If it is a video ad, input here the mp4 / m4v path ( or upload the video ) <br/>If it is a youtube ad, input here the youtube video id<br/>If it is a image ad, input here the image path ( or upload the image ) <br/>If it is a inline ad, input here the html content ( can load iframes too )
//            format in the same folder', 'dzsvg') . '</div>
//            <input class="textinput upload-prev" name="0-0-adsource" value=""/>' . $uploadbtnstring . '
//        </div>
//        <div class="setting type_all">
//            <div class="setting-label">' . __('Ad Type', 'dzsvg') . '</div>
//            <select class="textinput item-type styleme type_all" name="0-0-adtype">
//            <option>video</option>
//            <option>youtube</option>
//            <option>image</option>
//            <option>inline</option>
//            </select>
//        </div>
//        <div class="setting type_all">
//            <div class="setting-label">' . __('Ad  Link', 'dzsvg') . '</div>
//            <input class="textinput" name="0-0-adlink" value=""/>
//        </div>
//        <div class="setting type_all">
//            <div class="setting-label">' . __('Skip Ad Button Delay', 'dzsvg') . '</div>
//            <input class="textinput" name="0-0-adskip_delay" value=""/>
//            <div class="sidenote">' . __('You can have a skip ad button appear after a set number of seconds. ', 'dzsvg') . '</div>
//        </div>
//        <div class="clear"></div>
//</div>
//</div>

        $this->videoplayerconfig = '<div class="slider-con" style="display:none;">
        
        <div class="settings-con">
        <h4>' . __('General Options', 'dzsvg') . '</h4>
        <div class="setting type_all">
            <div class="setting-label">' . __('Config ID', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting main-id" name="0-settings-id" value="default"/>
            <div class="sidenote">' . __('Choose an unique id.', 'dzsvg') . '</div>
        </div>
        <div class="setting styleme ">
            <div class="setting-label">' . __('Video Player Skin', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme dzs-dependency-field" name="0-settings-skin_html5vp">
                <option>skin_aurora</option>
                <option>skin_default</option>
                <option>skin_white</option>
                <option>skin_pro</option>
                <option>skin_bigplay</option>
                <option value="skin_noskin">'.__("No controls").'</option>
                <option>skin_reborn</option>
                <option>skin_avanti</option>
                <option>skin_custom</option>
                <option>skin_custom_aurora</option>
            </select>
            <div class="sidenote">' . __('Skin Custom can be modified via Designer Center.', 'dzsvg') . '</div>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Use Custom Colors ? ', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-use_custom_colors">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . sprintf(__('custom colors can be modified - %shere%s', 'dzsvg'),'<a href="'.admin_url("admin.php?page=" . $this->adminpagename_designercenter).'" target="_blank">','</a>') . '</div>
        </div>
        <hr/>
        <div class="setting styleme">
            <div class="setting-label">' . __('Video Overlay', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_video_overlay">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('an overlay over the video that you can press for pause / unpause', 'dzsvg') . '</div>
        </div>
        <div class="setting styleme">
            <div class="setting-label">' . __('Big Play Button', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme dzs-dependency-field" name="0-settings-settings_big_play_btn">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('show a big play button centered on video paused', 'dzsvg') . '</div>
        </div>

        
        
        ';



        $dependency = array(

            array(
                'lab'=>'0-settings-settings_big_play_btn',
                'val'=>array('on'),
            ),
        );


        $dependency = json_encode($dependency);
        $dependency = str_replace('"','{{quot}}',$dependency);


        $this->videoplayerconfig .= '<div class="setting styleme"  >
            <div class="setting-label">' . __('Disable Mouse Out Behaviour', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_disable_mouse_out">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('some skins hide the controls on mouse out, you can disable this.', 'dzsvg') . '</div>
        </div>
        <div class="setting styleme"  data-dependency="'.$dependency.'">
            <div class="setting-label">' . __('Hide controls on paused', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-hide_on_paused">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('if big play button is enabled, controls can be hidden on paused too', 'dzsvg') . '</div>
        </div>
        <div class="setting ">
            <div class="setting-label">' . __('Hide controls on mouse out', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-hide_on_mouse_out">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('only for certain skins ( skin_aurora ) / only hides when video is playing', 'dzsvg') . '</div>
        </div>
        
        <div class="setting ">
            <div class="setting-label">' . __('Video Description Style on Video', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-video_description_style">
                <option value="none" >'.__("No show").'</option>
                <option value="show-description" >'.__("Show Description").'</option>
                <option value="gradient">'.__("Gradient Info on Paused").'</option>
            </select>
            <div class="sidenote">' . __('choose how Video Description text shows', 'dzsvg') . '</div>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Delay on Which to Hide Controls', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-settings_mouse_out_delay" value="100"/>
            <div class="sidenote">' . __('number of ms in which to delay the controls hiding', 'dzsvg') . '</div>
        </div>


        <div class="setting styleme">
            <div class="setting-label">' . __('Use the Custom Skin on iOS', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_ios_usecustomskin">
                <option>on</option>
                <option>off</option>
            </select>
            <div class="sidenote">' . __('overwrites the default ios ( ipad and iphone ) skin with the skin you chose in the Video Player Configuration', 'dzsvg') . '</div>
        </div>

        <div class="setting ">
            <div class="setting-label">' . __('Send Google Analytics Event for Play', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-ga_enable_send_play">
                <option>off</option>
                <option>on</option>
            </select>
            <div class="sidenote">' . __('send the play event to google analytics to record gallery plays on your site / you need the google analytics wordpress plugin', 'dzsvg') . '</div>
        </div>

        <div class="setting ">
            <div class="setting-label">' . __('Video End Displays the Last Frame', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-settings_video_end_reset_time">
                <option>on</option>
                <option>off</option>
            </select>
            <div class="sidenote">' . __('available for the self hosted video type', 'dzsvg') . '</div>
        </div>
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Normal Controls Opacity', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5design_controlsopacityon" value="1"/>
            <div class="sidenote">' . __('Choose an opacity from 0 to 1', 'dzsvg') . '</div>
        </div>
        
        ';


        $dependency = array(

            array(
                'lab'=>'0-settings-skin_html5vp',
                'val'=>array('skin_default','skin_aurora','skin_custom_aurora','skin_pro','skin_custom_pro'),
            ),
        );


        $dependency = json_encode($dependency);
        $dependency = str_replace('"','{{quot}}',$dependency);

        $lab = '0-settings-enable_info_button';
        $this->videoplayerconfig.='
        
        <div class="setting type_all" data-dependency="'.$dependency.'">
            <div class="setting-label">' . __('Enable Info Button', 'dzsvg') . '</div>
            <div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => '')) . '
                                        <label for="' . $lab . '"></label>
                                    </div>            
            <div class="sidenote">' . __('enable a extra button for video info', 'dzsvg') . '</div>
        </div>';


        $lab = '0-settings-enable_link_button';
        $this->videoplayerconfig.='
        
        <div class="setting type_all" data-dependency="'.$dependency.'">
            <div class="setting-label">' . __('Enable Link Button', 'dzsvg') . '</div>
            <div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => '')) . '
                                        <label for="' . $lab . '"></label>
                                    </div>            
            <div class="sidenote">' . __('enable a extra button for video info', 'dzsvg') . '</div>
        </div>';


        $lab = '0-settings-enable_cart_button';
        $this->videoplayerconfig.='
        
        <div class="setting type_all" data-dependency="'.$dependency.'">
            <div class="setting-label">' . __('Enable Cart Button', 'dzsvg') . '</div>
            <div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => '')) . '
                                        <label for="' . $lab . '"></label>
                                    </div>            
            <div class="sidenote">' . __('if this is linked to a WooCommerce product a Add to Cart button will appear in the player - you can input the product id in the media id of the player', 'dzsvg') . '</div>
        </div>';


        $lab = '0-settings-enable_quality_changer_button';
        $this->videoplayerconfig.='
        
        <div class="setting type_all" data-dependency="'.$dependency.'">
            <div class="setting-label">' . __('Enable Quality Changer Button', 'dzsvg') . '</div>
            <div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => '')) . '
                                        <label for="' . $lab . '"></label>
                                    </div>            
            <div class="sidenote">' . __('if this is an youtube video, the quality changer button will appear if there are multiple quality options', 'dzsvg') . '</div>
        </div>';


        $lab = '0-settings-enable_multisharer_button';
        $this->videoplayerconfig.='
        
        <div class="setting type_all" data-dependency="'.$dependency.'">
            <div class="setting-label">' . __('Enable Multisharer Button', 'dzsvg') . '</div>
            <div class="dzscheckbox skin-nova">
                                        ' . DZSHelpers::generate_input_checkbox($lab, array('id' => $lab, 'class' => 'mainsetting', 'val' => 'on', 'seekval' => '')) . '
                                        <label for="' . $lab . '"></label>
                                    </div>            
            <div class="sidenote">' . __('clicking this button will open a lightbox full of share options', 'dzsvg') . '</div>
        </div>';



        $this->videoplayerconfig.='<div class="setting type_all">
            <div class="setting-label">' . __('Roll Out Controls Opacity', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-html5design_controlsopacityout" value="1"/>
            <div class="sidenote">' . __('Choose an opacity from 0 to 1 for when the mouse is not on the video player', 'dzsvg') . '</div>
        </div>
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Default Volume', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-defaultvolume" value=""/>
        </div>
        
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('YouTube Options', 'dzsvg') . '</div>
<div class="toggle-content">
        <div class="setting type_all">
            <div class="setting-label">' . __('SD Quality', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-youtube_sdquality">
                <option>small</option>
                <option>medium</option>
                <option>default</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('HD Quality', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-youtube_hdquality">
                <option>hd720</option>
                <option>hd1080</option>
                <option>default</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Default Quality', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-youtube_defaultquality">
                <option value="hd">' . __('HD', 'dzsvg') . '</option>
                <option value="sd">' . __('SD', 'dzsvg') . '</option>
            </select>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Enable Custom Skin', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-yt_customskin">
                <option value="on">' . __('on', 'dzsvg') . '</option>
                <option value="off">' . __('off', 'dzsvg') . '</option>
            </select>
            <div class="sidenote">' . __('Choose if the custom skin you set in the Video Player Skin is how YouTube videos should show ( on )
                 or if the default YouTube skin should show ( off )', 'dzsvg') . '</div>
        </div>
</div>
</div>
        

<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Vimeo Options', 'dzsvg') . '</div>
<div class="toggle-content">
        
                <div class="setting">
                    <div class="label">' . __('Vimeo Player Title', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeo_title" value="1"/>
                    <div class="sidenote">' . __('show the vimeo title in the vimeo default player', 'dzsvg') . '</div>
                </div>
        
                <div class="setting">
                    <div class="label">' . __('Vimeo Player Byline', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeo_byline" value="0"/>
                    <div class="sidenote">' . __('', 'dzsvg') . '</div>
                </div>
                <div class="setting">
                    <div class="label">' . __('Vimeo Player Portrait', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeo_portrait" value="1"/>
                    <div class="sidenote">' . __('show the vimeo author avatar', 'dzsvg') . '</div>
                </div>
                <div class="setting">
                    <div class="label">' . __('Vimeo Player Badge', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeo_badge" value="1"/>
                    <div class="sidenote">' . __('show the vimeo author badge', 'dzsvg') . '</div>
                </div>
                <div class="setting">
                    <div class="label">' . __('Vimeo Player Color', 'dzsvg') . '</div>
            <input type="text" class="textinput mainsetting" name="0-settings-vimeo_color" value=""/>
                    <div class="sidenote">' . __('input the color of controls in this format RRGGBB, ie. <strong>ffffff</strong> for white ', 'dzsvg') . '</div>
                </div>
                <div class="setting">
                    <div class="label">' . __('Vimeo player is chromeless', 'dzsvg') . '</div>
                <select class="textinput mainsetting styleme" name="0-settings-vimeo_is_chromeless">
                    <option value="off">' . __('no', 'dzsvg') . '</option>
                    <option value="on">' . __('yes', 'dzsvg') . '</option>
                </select>
                    <div class="sidenote">' . __('if you have vimeo plus membership you can make vimeo player have your own custom controls', 'dzsvg') . '</div>
                </div>
</div>
</div>
        
        </div><!--end settings con-->
        </div>';
        //print_r($this->mainitems);

//        print_r($_POST);
        $this->check_posts();









	    if(isset($_GET['taxonomy']) && $_GET['taxonomy']==$this->taxname_sliders){
		    include_once('admin/sliders_admin.php');
		    add_action('in_admin_footer','dzsvg_sliders_admin');


	    }

	    add_action( 'edited_'.$this->taxname_sliders, array($this,$this->taxname_sliders.'_save_taxonomy_custom_meta'));




        add_shortcode($this->the_shortcode, array($this, 'show_shortcode'));
        add_shortcode('dzs_' . $this->the_shortcode, array($this, 'show_shortcode'));
        add_shortcode('dzs_videoshowcase', array($this, 'show_shortcode_showcase'));
        add_shortcode('videogallerycategories', array($this, 'show_shortcode_cats'));
        add_shortcode('videogallerylightbox', array($this, 'show_shortcode_lightbox'));
        add_shortcode('videogallerylinks', array($this, 'show_shortcode_links'));
        add_shortcode('dzsvg_secondcon', array($this, 'show_shortcode_secondcon'));
        add_shortcode('dzsvg_outernav', array($this, 'show_shortcode_outernav'));


        add_shortcode('vimeo', array($this, 'vimeo_func'));
        add_shortcode('youtube', array($this, 'youtube_func'));
        add_shortcode('dzs_youtube', array($this, 'youtube_func'));
        add_shortcode('dzs_video', array($this, 'shortcode_player'));

        if ($this->mainoptions['replace_wpvideo'] == 'on') {
            add_shortcode('video', array($this, 'shortcode_player'));
        }
        if ($this->mainoptions['replace_jwplayer'] == 'on') {
            add_shortcode('jwplayer', array($this, 'shortcode_player'));
        }
        if ($this->mainoptions['include_featured_gallery_meta'] == 'on') {
            include_once dirname(__FILE__) . '/class_parts/extras_featured.php';
        }


        add_filter('attachment_fields_to_edit', array($this, 'filter_attachment_fields_to_edit'), 10, 2);

        add_action('init', array($this, 'handle_init'));
        add_action('init', array($this, 'handle_init_end'),9999);
        add_action('wp_ajax_dzsvg_ajax', array($this, 'post_save'));
        add_action('wp_ajax_dzsvg_ajax_json_encode_ad', array($this, 'ajax_insert_ads'));
        add_action('wp_ajax_dzsvg_ajax_json_encode_quality', array($this, 'ajax_insert_quality'));
        add_action('wp_ajax_dzsvg_import_ytplaylist', array($this, 'post_importytplaylist'));
        add_action('wp_ajax_dzsvg_import_ytuser', array($this, 'post_importytuser'));
        add_action('wp_ajax_dzsvg_import_vimeouser', array($this, 'post_importvimeouser'));
        add_action('wp_ajax_dzsvg_get_db_gals', array($this, 'post_get_db_gals'));
        add_action('wp_ajax_get_vimeothumb', array($this, 'ajax_get_vimeothumb'));
        add_action('wp_ajax_dzsvg_import_item_lib', array($this, 'ajax_import_item_lib'));
        add_action('wp_ajax_dzsvg_import_galleries', array($this, 'ajax_import_galleries'));
        add_action('wp_ajax_dzsvg_import_sample_items', array($this, 'ajax_import_sample_items'));
        add_action('wp_ajax_dzsvg_remove_sample_items', array($this, 'ajax_remove_sample_items'));
        add_action('wp_ajax_dzsvp_submit_view', array($this, 'ajax_submit_view'));
        add_action('wp_ajax_nopriv_dzsvp_submit_view', array($this, 'ajax_submit_view'));
        add_action('wp_ajax_dzsvg_delete_notice', array($this, 'ajax_delete_notice'));
        add_action('wp_ajax_dzsvg_activate', array($this, 'ajax_activate_license'));
        add_action('wp_ajax_dzsvg_deactivate', array($this, 'ajax_deactivate_license'));
	    add_action('wp_ajax_dzsvg_send_queue_from_sliders_admin', array($this, 'ajax_send_queue_from_sliders_admin'));
        add_action( 'login_enqueue_scripts', array($this, 'login_enqueue_scripts') );


        add_action('wp_ajax_dzsvg_save_vpc', array($this, 'post_save_vpc'));

        add_action('wp_ajax_dzsvg_ajax_mo', array($this, 'post_save_mo'));
        add_action('wp_ajax_dzsvg_ajax_options_dc', array($this, 'post_save_options_dc'));
        add_action('wp_ajax_dzsvg_ajax_options_dc_aurora', array($this, 'post_save_options_dc_aurora'));


        add_action('admin_menu', array($this, 'handle_admin_menu'));
        add_action('admin_head', array($this, 'handle_admin_head'));


        add_action('wp_head', array($this, 'handle_wp_head'));
        add_action('wp_footer', array($this, 'handle_footer'));


        if ($this->mainoptions['enable_video_showcase'] == 'on') {
            add_filter('the_content', array($this, 'filter_the_content'));
            add_action('save_post', array($this, 'admin_meta_save_dzsvideo'));

        }


        if ($this->mainoptions['analytics_enable'] == 'on') {
            add_action('wp_dashboard_setup', array($this, 'wp_dashboard_setup'));
            include_once("class_parts/analytics.php");
        }


        if ($this->mainoptions['tinymce_enable_preview_shortcodes'] == 'on') {
//            add_filter('mce_external_plugins',array(&$this,'add_tcustom_tinymce_plugin'));
//            add_filter('tiny_mce_before_init',array(&$this,'myformatTinyMCE'));


            add_action('print_media_templates', array($this, 'handle_print_media_templates'));
            add_action('admin_print_footer_scripts', array($this, 'handle_admin_print_footer_scripts'));
        }
	    add_action('admin_footer', array($this, 'handle_admin_footer'));

        if ($this->pluginmode != 'theme') {
            add_action('admin_init', array($this, 'admin_init'));
            add_action('save_post', array($this, 'admin_meta_save'));
        }

    }
	function dzsvg_sliders_save_taxonomy_custom_meta( $term_id ) {


//		error_log('trying to save term meta '.$term_id);
//		error_log(print_rr($_POST,array('echo'=>false)));
		if ( isset( $_POST['term_meta'] ) ) {
			$t_id = $term_id;
			$term_meta = get_option( "taxonomy_$t_id" );
			$cat_keys = array_keys( $_POST['term_meta'] );
			foreach ( $cat_keys as $key ) {
				if ( isset ( $_POST['term_meta'][$key] ) ) {
					$term_meta[$key] = $_POST['term_meta'][$key];
				}
			}
			// Save the option array.
			update_option( "taxonomy_$t_id", $term_meta );
		}
	}


    public function handle_plugin_activate() {
        $this->plugin_justactivated = "on";
//        echo 'ceva';

//        error_log('activation_hook');

        error_log("JUST ACTIVATED DZSVG");

        if(get_option('dzsvg_shown_intro')){

        }else{

        }

    }


    public function redirect_to_intro_page_func(){

    }

    public function handle_plugin_deactivate() {

        flush_rewrite_rules();
        error_log("JUST DEACTIVATED DZSVG");
    }

    function wp_dashboard_setup() {

        wp_add_dashboard_widget('dzsvg_dashboard_analytics', // Widget slug.
            'Video Galery DZS Analytics', // Title.
            'dzsvg_analytics_dashboard_content'

        );
    }


    function check_posts_init() {
        if(isset($_GET['action'])) {
            if ($_GET['action'] == 'ajax_analytics_table_create') {
	            $this->analytics_table_create();
            }
            if ($_GET['action'] == 'ajax_dzsvg_submit_files') {


                /*
                 * DZS Upload
                 * version: 1.0
                 * author: digitalzoomstudio
                 * website: https://digitalzoomstudio.net
                 *
                 * Dual licensed under the MIT and GPL licenses:
                 *   https://www.opensource.org/licenses/mit-license.php
                 *   https://www.gnu.org/licenses/gpl.html
                 */
                @session_start();
//                $nonce = $_REQUEST['dzsvg-upload-bulk-nonce'];

//                $current_user = wp_get_current_user();

//                print_r($current_user);



                $allowed = false;


                $current_user = wp_get_current_user();

                if(isset($_GET['from']) && $_GET['from']=='dzsvp_portal'){

                    if($this->mainoptions['dzsvp_enable_visitorupload']=='on' || $this->mainoptions['dzsvp_enable_user_upload_capability']=='on'){

                        if($current_user->ID){
                            $allowed = true;
                        }
                    }
                }


                if (current_user_can('upload_files')) {
                    $allowed = true;
                }



                if ($allowed) {

                } else {
                    die('no permission');
                }

                $allowed_filetypes = array('.jpg', '.jpeg', '.png', '.gif', '.tiff', '.txt', '.mp4', '.m4v', '.mov', '.ogg', '.ogv', '.webm', '.sql', '.mp3');
                $upload_dir = dirname(__FILE__) . '/upload';


//print_r($_POST); print_r($HTTP_POST_FILES); print_r($_FILES);

                if (isset($_FILES['file_field']['tmp_name'])) {
                    $file_name = $_FILES['file_field']['name'];
                    $file_name = str_replace(" ", "_", $file_name); // strip spaces
                    $path = $upload_dir . "/" . $file_name;


                    $sw = true;


                    foreach ($allowed_filetypes as $dft) {
//            print_r($dft);
                        $pos = strpos(strtolower($file_name), $dft);


//            error_log($pos);
                        if ($pos > strlen($file_name) - 6) {
                            $sw = false;
                        }
                    }

                    if ($sw == true) {
                        $arr = array(
                            'type'=>'upload_notice',
                            'report'=>'error',
                            'text'=>__('extension not allowed'). '( dir - '.$upload_dir.' )',
                        );
                        die(json_encode($arr));
                    }
                    if (!is_writable($upload_dir)) {
                        $arr = array(
                            'type'=>'upload_notice',
                            'report'=>'error',
                            'text'=>__('dir not writable - check permissions'). '( dir - '.$upload_dir.' )',
                        );
                        die(json_encode($arr));
                    }


                    if (copy($_FILES['file_field']['tmp_name'], $path)) {
                        echo '<div class="success">file uploaded</div><script>top.hideFeedbacksCall();</script>';
                    } else {
                        echo '<div class="error">file could not be uploaded</div><script>window.hideFeedbacksCall()</script>';
                    }


                } else {
                    $headers = $_SERVER;
//    print_r($_FILES);
                    if (isset($headers['HTTP_X_FILE_NAME'])) {
                        //print_r($headers);
                        $file_name = $headers['HTTP_X_FILE_NAME'];
                        $file_name = str_replace(" ", "_", $file_name); // strip spaces


                        if (isset($headers['HTTP_X_FILE_UPLOAD_DIR']) && $headers['HTTP_X_FILE_UPLOAD_DIR']) {
                            $upload_dir = $headers['HTTP_X_FILE_UPLOAD_DIR'];

                        }


                        if (isset($_POST['upload_path']) && $_POST['upload_path']) {
                            $upload_dir = $_POST['upload_path'];

                        }



                        $target = $upload_dir . "/" . $file_name;


//        print_r($headers);
                        //==== checking for disallowed file types
                        $sw = true;

                        foreach ($allowed_filetypes as $dft) {
//            print_r($dft);
                            $pos = strpos(strtolower($file_name), $dft);


//            error_log($pos);
                            if ($pos > strlen($file_name) - 6) {
                                $sw = false;
                            }
                        }


                        if ($sw == true) {
                            $arr = array(
                                'type'=>'upload_notice',
                                'report'=>'error',
                                'text'=>__('extension not allowed'). '( dir - '.$upload_dir.' )',
                            );
                            die(json_encode($arr));
                        }

                        if (!is_writable($upload_dir)) {


                            $arr = array(
                                'type'=>'upload_notice',
                                'report'=>'error',
                                'text'=>__('dir not writable - check permissions'). '( dir - '.$upload_dir.' )',
                            );
                            die(json_encode($arr));
                        }

                        $auxindex = 0;
                        $auxname = $file_name;
                        $auxpath = $target;
                        if (file_exists($target)) {

//            die('<div class="error">file already exists</div>');

                            $part1_target = $target;
                            $part2_target = '';


                            $part1_name = $auxname;
                            $part2_name = '';

                            if (strpos($target, '.png') !== false || strpos($target, '.jpg') !== false || strpos($target, '.mp4') !== false || strpos($target, '.m4v') !== false
                                || strpos($target, '.ogg') !== false || strpos($target, '.ogv') !== false || strpos($target, '.gif') !== false || strpos($target, '.mp3') !== false
                                || strpos($target, '.gif') !== false
                            ) {
                                $part1_target = substr($target, 0, -4);
                                $part2_target = substr($target, -4);
                            }


                            if (strpos($auxname, '.png') !== false || strpos($auxname, '.jpg') !== false || strpos($auxname, '.mp4') !== false || strpos($auxname, '.m4v') !== false
                                || strpos($auxname, '.ogg') !== false || strpos($auxname, '.ogv') !== false || strpos($auxname, '.gif') !== false || strpos($auxname, '.mp3') !== false
                                || strpos($auxname, '.gif') !== false
                            ) {
                                $part1_name = substr($auxname, 0, -4);
                                $part2_name = substr($auxname, -4);
                            }

                            if (strpos($target, '.jpeg') !== false) {
                                $part1_target = substr($target, 0, -5);
                                $part2_target = substr($target, -5);
                            }


                            if (strpos($auxname, '.jpeg') !== false) {
                                $part1_name = substr($auxname, 0, -5);
                                $part2_name = substr($auxname, -5);
                            }

                            while (file_exists($auxpath) === true) {
                                $auxindex++;

                                $auxpath = $part1_target . '_' . $auxindex . $part2_target;
                                $auxname = $part1_name . '_' . $auxindex . $part2_name;
                            }
                        }

                        $target = $auxpath;


                        //echo $target;
                        
                        
                        /*
                        $content = file_get_contents("php://input");

                        if (file_put_contents($target, $content)) {
                            echo 'success - file written {{filename-' . $auxname . '}}';
                        } else {
                            die('<div class="error">error at file_put_contents</div>');
                        }
                        
                        
                        
                        */

//                        print_r($_FILES);

//                        echo '$upload_dir.$target - '.$upload_dir.$target.' <--';


                        if(move_uploaded_file($_FILES['myfile']['tmp_name'], $target)){

                            echo 'success - file written {{filename-' . $auxname . '}}';
                        }else{

                            $arr = array(
                                'type'=>'upload_notice',
                                'report'=>'error',
                                'text'=>__("error at ").'move_uploaded_file',
                            );
                            die(json_encode($arr));
                        }
                        
                        
                        

                        
                    } else {
                        die('not for direct access');
                    }
                }

                die();

            }
        }








        if (isset($_GET['dzsvg_action'])) {






            if($_GET['dzsvg_action']=='dzsvp_submit_like'){

                $this->ajax_submit_like();


            }

            if($_GET['dzsvg_action']=='showinzoombox'){

                echo do_shortcode('[videogallery id="'.esc_html($_GET['id']).'"]');

                die();


            }

            if($_GET['dzsvg_action']=='dzsvp_retract_like'){

                $this->ajax_retract_like();
            }
            if($_GET['dzsvg_action']=='savescreenshot'){


//                print_r($_POST);



                if (current_user_can('upload_files')) {
                    $allowed = true;




                    $upload_dir = wp_upload_dir();
//                print_r($upload_dir);

                    $realpath = $upload_dir['path'];
                    $realpath = str_replace('\\','/', $realpath);




                    $name = str_replace('.','',$this->sanitize_for_class($_GET['name']));

                    $target_path = $realpath.'/'.$name.'.png';
                    $target_url = $upload_dir['url'].'/'.$name.'.png';
//                    $target_path = $realpath.'/'.$this->sanitize_for_class($_GET['name']).'.jpg';



                    $data = $_POST['imgData'];



                    $data = str_replace('data:image/png;base64,', '', $data);
                    $data = str_replace(' ', '+', $data);


//                    $uri =  substr($data,strpos($data,",")+1);
                    file_put_contents($target_path, base64_decode($data));

                    echo $target_url;

                }

                die();

            }




            if($_GET['dzsvg_action']=='load_charts_html') {
	            $yesterday = date("d M", time() - 60 * 60 * 24);
	            $days_2 = date("d M", time() - 60 * 60 * 24 * 2);
	            $days_3 = date("d M", time() - 60 * 60 * 24 * 3);

//	            echo 'hmm-'.$yesterday.'-'.$days_2;

//                $yesterday = 'ceva';
//	            $days_2 = 'ceva2';

                // -- chart

	            $trackid = $_POST['postdata'];
	            $arr = array(
		            'labels'=>array(__('Track'),__('Views'),__('Likes')),
		            'lastdays'=>array(
			            array(

				            $days_3,
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'4',
					            'day_end'=>'3',
					            'type'=>'view',
					            'get_count'=>'off',
				            )),
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'4',
					            'day_end'=>'3',
					            'type'=>'like',
					            'get_count'=>'off',
				            )),
			            ),
			            array(

				            $days_2,
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'3',
					            'day_end'=>'2',
					            'type'=>'view',
				            )),
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'3',
					            'day_end'=>'2',
					            'type'=>'like',
				            )),
			            ),

			            array(

				            $yesterday,
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'2',
					            'day_end'=>'1',
					            'type'=>'view',
				            )),
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'2',
					            'day_end'=>'1',
					            'type'=>'like',
				            )),
			            ),
			            array(

				            __("Today"),
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'1',
					            'day_end'=>'0',
					            'type'=>'view',
				            )),
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'1',
					            'day_end'=>'0',
					            'type'=>'like',
				            )),

			            ),
		            ),

	            );

//	            error_log(print_r($arr,true));



	            ?>
                <div class="hidden-data"><?php echo json_encode($arr); ?></div>


                <?php



	            $last_month = date("M", time() - 60 * 60 * 31);
	            $month_2 = date("M", time() - 60 * 60 * 24 * 62);
	            $month_3 = date("M", time() - 60 * 60 * 24 * 93);


//	            echo 'hmm-'.$yesterday.'-'.$days_2;

//                $yesterday = 'ceva';
//	            $days_2 = 'ceva2';

	            $trackid = $_POST['postdata'];
	            $arr = array(
		            'labels'=>array(__('Track'),__('Minutes watched')),
		            'lastdays'=>array(
			            array(

				            $month_3,
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'120',
					            'day_end'=>'90',
					            'type'=>'timewatched',
					            'get_count'=>'off',
					            'id_user'=>'0',
				            )),
			            ),
			            array(

				            $month_2,
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'90',
					            'day_end'=>'60',
					            'type'=>'timewatched',
					            'get_count'=>'off',
					            'id_user'=>'0',
				            )),
			            ),
			            array(

				            $last_month,
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'60',
					            'day_end'=>'30',
					            'type'=>'timewatched',
					            'get_count'=>'off',
					            'id_user'=>'0',
				            )),
			            ),

			            array(

				            "This month",
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'30',
					            'day_end'=>'0',
					            'type'=>'timewatched',
					            'get_count'=>'off',
					            'id_user'=>'0',
				            )),
                        ),
		            ),

	            );

//	            error_log(print_r($arr,true));

                ?>
                <div class="hidden-data-time-watched"><?php echo json_encode($arr); ?></div>
                <?php

	            $last_month = date("M", time() - 60 * 60 * 31);
	            $month_2 = date("M", time() - 60 * 60 * 24 * 62);
	            $month_3 = date("M", time() - 60 * 60 * 24 * 93);


//	            echo 'hmm-'.$yesterday.'-'.$days_2;

//                $yesterday = 'ceva';
//	            $days_2 = 'ceva2';


                // -- time watched
	            $trackid = $_POST['postdata'];
	            $arr = array(
		            'labels'=>array(__('Track'),__('Number of plays')),
		            'lastdays'=>array(
			            array(

				            $month_3,
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'120',
					            'day_end'=>'90',
					            'type'=>'view',
					            'get_count'=>'off',
					            'id_user'=>'0',
				            )),
			            ),
			            array(

				            $month_2,
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'90',
					            'day_end'=>'60',
					            'type'=>'view',
					            'get_count'=>'off',
					            'id_user'=>'0',
				            )),
			            ),
			            array(

				            $last_month,
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'60',
					            'day_end'=>'30',
					            'type'=>'view',
					            'get_count'=>'off',
					            'id_user'=>'0',
				            )),
			            ),

			            array(

				            "This month",
				            $this->mysql_get_track_activity($trackid, array(
					            'get_last'=>'day',
					            'day_start'=>'30',
					            'day_end'=>'0',
					            'type'=>'view',
					            'get_count'=>'off',
					            'call_from'=>'debug',
					            'id_user'=>'0',
				            )),
			            ),
		            ),

	            );
//	            error_log(print_r($arr,true));
                ?>
                <div class="hidden-data-month-viewed"><?php echo json_encode($arr); ?></div>

                <div class="dzs-row">
                    <div class="dzs-col-md-8">
                        <div class="trackchart">

                        </div>
                    </div>
                    <div class="dzs-col-md-4">
                        <div class="dzs-row">

                            <div class="dzs-col-md-6">
                                <h6><?php echo __("Likes Today"); ?></h6>
                                <div><span class="the-number"><?php


							            $aux = $this->mysql_get_track_activity($trackid, array(
								            'get_last'=>'on',
								            'interval'=>'24',
								            'type'=>'like',
							            ));

							            echo $aux;

							            ?></span> <span class="the-label"><?php ?></span> </div>
                            </div>
                            <div class="dzs-col-md-6">
                                <h6><?php echo __("Plays Today"); ?></h6>
                                <div><span class="the-number"><?php


				                        $aux = $this->mysql_get_track_activity($trackid, array(
					                        'get_last'=>'on',
					                        'interval'=>'24',
					                        'type'=>'view',
				                        ));

				                        echo $aux;

				                        ?></span> <span class="the-label"><?php ?></span> </div>
                            </div>
                        </div>

                        <div class="dzs-row">
                            <div class="dzs-col-md-6">


                                <h6><?php echo __("Likes This Week"); ?></h6>
                                <div><span class="the-number"><?php


			                            $aux = $this->mysql_get_track_activity($trackid, array(
				                            'get_last'=>'on',
				                            'interval'=>'144',
				                            'type'=>'like',
			                            ));

			                            echo $aux;

			                            ?></span> <span class="the-label"><?php ?></span> </div>
                            </div>

                            <div class="dzs-col-md-6">
                                <h6><?php echo __("Plays This Week"); ?></h6>
                                <div><span class="the-number"><?php


				                        $aux = $this->mysql_get_track_activity($trackid, array(
					                        'get_last'=>'on',
					                        'interval'=>'144',
					                        'type'=>'view',
				                        ));

				                        echo $aux;

				                        ?></span> <span class="the-label"><?php ?></span> </div>
                            </div>
                        </div>
                        <div class="dzs-row">

                            <div class="dzs-col-md-6">
                                <h6><?php echo __("Likes this month"); ?></h6>
                                <div><span class="the-number"><?php


				                        $aux = $this->mysql_get_track_activity($trackid, array(
					                        'get_last'=>'on',
					                        'interval'=>'720',
					                        'type'=>'like',
				                        ));

				                        echo $aux;

				                        ?></span> <span class="the-label"><?php ?></span> </div>
                            </div>
                            <div class="dzs-col-md-6">
                                <h6><?php echo __("Plays this month"); ?></h6>
                                <div><span class="the-number"><?php


				                        $aux = $this->mysql_get_track_activity($trackid, array(
					                        'get_last'=>'on',
					                        'interval'=>'720',
					                        'type'=>'view',
				                        ));

				                        echo $aux;

				                        ?></span> <span class="the-label"><?php ?></span> </div>
                            </div>
                        </div>

                    </div>
                </div>
                <div class="dzs-row">

                    <div class="dzs-col-md-6">
                        <div class="trackchart-time-watched">

                        </div>
                    </div>

                    <div class="dzs-col-md-6">
                        <div class="trackchart-month-viewed">

                        </div>
                    </div>
                </div>
	            <?php

	            die();

            }

        }




        if (isset($_POST['dzsvg_action'])) {
//            dzsprx_shortcode_builder();


            if ($_POST['dzsvg_action'] == 'submit_video') {






                $allowed = false;

                global $current_user;
//                print_r($current_user);


                if(isset($_GET['from']) && $_GET['from']=='dzsvp_portal'){

                    if($this->mainoptions['dzsvp_enable_visitorupload']=='on' || $this->mainoptions['dzsvp_enable_user_upload_capability']=='on'){

                        if($current_user->data->ID){
                            $allowed=true;
                        }

                    }
                }

                if(current_user_can('upload_files')){
                    $allowed=true;
                }




                if($allowed){
                    global $current_user;

                    $args = array(
                        'post_title' => $_POST['title'],
                        'post_content' => $_POST['description'],
                        'post_status' => 'publish',
                        'post_author' => $current_user->data->ID,
                        'post_type' => 'dzsvideo',
                    );


                    if($_POST['thumbnail']){

                    }else{
                        if($this->mainoptions['dzsvp_use_default_image']=='on'){
                            $_POST['thumbnail'] = $this->mainoptions['dzsvp_upload_image_default'];
                        }
                    }

                    $sample_post_id = wp_insert_post($args);
                    update_post_meta($sample_post_id, 'dzsvg_meta_featured_media', $_POST['source']);
                    update_post_meta($sample_post_id, 'dzsvg_meta_item_type', $_POST['type']);
                    update_post_meta($sample_post_id, 'dzsvg_meta_thumb', $_POST['thumbnail']);



                    $aux = array(
                            'type'=>'upload',
                            'text'=>sprintf(__('video uploaded %shere%s'),'<a href="'.get_permalink($sample_post_id).'">','</a>'),
                    );

                    array_push($this->notifications, $aux);


                    if(isset($_POST['video_category']) && $_POST['video_category']){
//                        echo $_POST['video_category'];
//                        die();
                        wp_set_object_terms( $sample_post_id, array(intval($_POST['video_category'])), 'dzsvideo_category' );
                    }
                    if(isset($_POST['tags']) && $_POST['tags']){
//                        echo $_POST['video_category'];
//                        die();
                        $arr_tags = explode(',',$_POST['tags']);
                        wp_set_object_terms( $sample_post_id, $arr_tags, 'dzsvideo_tags' );
                    }


                    $_POST = array();
                    header("Location: " . get_permalink($sample_post_id));
                }else{
                    die("Not allowed");
                }



            }
        }












	    if (isset($_GET['action'])) {
//            dzsprx_shortcode_builder();


// -- submit view
		    if ($_GET['action'] == 'ajax_dzsvg_submit_view') {
			    $date = date('Y-m-d H:i:s');

//                $date = date("Y-m-d", time() - 60 * 60 * 24);


			    $id = $_POST['video_analytics_id'];
			    $country = '0';

			    if ($this->mainoptions['analytics_enable_location'] == 'on') {

//                    print_r($_SERVER);

				    if ($_SERVER['REMOTE_ADDR']) {

//                        $aux = wp_file


					    $request = wp_remote_get('https://ipinfo.io/' . $_SERVER['REMOTE_ADDR'] . '/json');
					    $response = wp_remote_retrieve_body($request);
					    $aux_arr = json_decode($response);
//                        print_r($aux_arr);

					    if ($aux_arr) {
						    $country = $aux_arr->country;
					    }
				    }
			    }


			    $userid = '';
			    $userid = get_current_user_id();
			    if ($this->mainoptions['analytics_enable_user_track'] == 'on') {

				    if ( $_POST['dzsvg_curr_user'] ) {
					    $userid = $_POST['dzsvg_curr_user'];
				    }
			    }



			    $playerid = $id;

//		        print_rr($_COOKIE);

			    if(isset($_COOKIE["dzsvg_viewsubmitted-" . $playerid]) && $_COOKIE["dzsvg_viewsubmitted-" . $playerid]=='1'){

			    }else{


				    $nr_views = get_post_meta($id, 'dzsvg_nr_views',true);

				    $nr_views = intval($nr_views);
				    update_post_meta($id, 'dzsvg_nr_views',++$nr_views);






//                $date = date("Y-m-d", time() - 60 * 60 * 24);






				    $currip = $this->misc_get_ip();


				    setcookie("dzsvg_viewsubmitted-" . $playerid, 1, time() + 36000, COOKIEPATH);




				    global $wpdb;





				    $table_name = $wpdb->prefix.'dzsvg_activity';





				    if($this->mainoptions['analytics_enable_user_track']=='on'){

				        // -- date precise
					    $date = date('Y-m-d H:i:s');
					    $wpdb->insert(
						    $table_name,
						    array(
							    'ip' => $currip,
							    'country' => $country,
							    'type' => 'view',
							    'val' => 1,
							    'id_user' => $userid,
							    'id_video' => $playerid,
							    'date' => $date,
						    )
					    );
				    }else{


				        // -- date more generic for select matches
					    $date = date('Y-m-d');






					    // -- submit to total plays for today

					    $query = 'SELECT * FROM '.$table_name.' WHERE id_user = \'0\' AND date=\''.$date.'\'  AND type=\''.'view'.'\' AND id_video=\''.($playerid).'\'';
					    if($this->mainoptions['analytics_enable_location']=='on' && $country){
						    $query.=' AND country=\''.$country.'\'';
					    }
					    $results = $wpdb->get_results($query , OBJECT );


					    if(is_array($results) && count($results)>0){


						    $val = intval($results[0]->val);
						    $newval = $val+1;

						    $wpdb->update(
							    $table_name,
							    array(
								    'val' => $val+1,
							    ),
							    array( 'ID' => $results[0]->id ),
							    array(
								    '%s',	// value1
							    ),
							    array( '%d' )
						    );


					    }else{

						    $wpdb->insert(
							    $table_name,
							    array(
								    'ip' => 0,
								    'type' => 'view',
								    'id_user' => 0,
								    'id_video' => $playerid,
								    'date' => $date,
								    'val' => 1,
								    'country' => $country,
							    )
						    );
					    }

                    }




				    echo $nr_views;


















				    $query = 'SELECT * FROM '.$table_name.' WHERE id_user = \'0\' AND date=\''.$date.'\'  AND type=\''.'view'.'\' AND id_video=\''.(0).'\'';
				    if($this->mainoptions['analytics_enable_location']=='on' && $country){
					    $query.=' AND country=\''.$country.'\'';
				    }
				    $results = $wpdb->get_results($query , OBJECT );


				    if(is_array($results) && count($results)>0){


					    $val = intval($results[0]->val);
					    $newval = $val+1;

					    $wpdb->update(
						    $table_name,
						    array(
							    'val' => $val+1,
						    ),
						    array( 'ID' => $results[0]->id ),
						    array(
							    '%s',	// value1
						    ),
						    array( '%d' )
					    );


				    }else{

					    $wpdb->insert(
						    $table_name,
						    array(
							    'ip' => 0,
							    'type' => 'view',
							    'id_user' => 0,
							    'id_video' => 0,
							    'date' => $date,
							    'val' => 1,
							    'country' => $country,
						    )
					    );
				    }



				    die();

//		            echo 'success';
			    }



			    die();


		    }


		    // -- contor
		    if (isset($_GET['action']) && $_GET['action'] == 'ajax_dzsvg_submit_contor_60_secs') {

			    $date = date('Y-m-d');

//                $date = date("Y-m-d", time() - 60 * 60 * 24);


			    $country = '0';
			    $id = $_POST['video_analytics_id'];

			    if ($this->mainoptions['analytics_enable_location'] == 'on') {

//                    print_r($_SERVER);

				    if ($_SERVER['REMOTE_ADDR']) {

//                        $aux = wp_file


					    $request = wp_remote_get('https://ipinfo.io/' . $_SERVER['REMOTE_ADDR'] . '/json');
					    $response = wp_remote_retrieve_body($request);
					    $aux_arr = json_decode($response);
//                        print_r($aux_arr);

					    if ($aux_arr) {
						    $country = $aux_arr->country;
					    }
				    }
			    }


			    $userid = '';
			    $userid = get_current_user_id();
			    if ($this->mainoptions['analytics_enable_user_track'] == 'on') {

				    if ( $_POST['dzsvg_curr_user'] ) {
					    $userid = $_POST['dzsvg_curr_user'];
				    }
			    }



			    $playerid = $id;

			    global $wpdb;
			    $table_name = $wpdb->prefix.'dzsvg_activity';


			    $results = $GLOBALS['wpdb']->get_results( 'SELECT * FROM '.$table_name.' WHERE id_user = \''.$userid.'\' AND date=\''.$date.'\'  AND type=\''.'timewatched'.'\' AND id_video=\''.$playerid.'\'', OBJECT );


//			    print_rr($results);

			    if(is_array($results) && count($results)>0){


				    $val = intval($results[0]->val);
//				    echo '$val  - '.$val;
				    $newval = $val+60;

				    $wpdb->update(
					    $table_name,
					    array(
						    'val' => $val+60,
					    ),
					    array( 'ID' => $results[0]->id ),
					    array(
						    '%s',	// value1
					    ),
					    array( '%d' )
				    );

//				    echo '$newval  - '.$newval;

			    }else{
				    $currip = $this->misc_get_ip();


				    $wpdb->insert(
					    $table_name,
					    array(
						    'ip' => $currip,
						    'type' => 'timewatched',
						    'id_user' => $userid,
						    'id_video' => $playerid,
						    'date' => $date,
						    'val' => 60,
						    'country' => $country,
					    )
				    );
			    }






			    // -- global table

                $query = 'SELECT * FROM '.$table_name.' WHERE id_user = \'0\' AND date=\''.$date.'\'  AND type=\''.'timewatched'.'\' AND id_video=\''.(0).'\'';
			    if($this->mainoptions['analytics_enable_location']=='on' && $country){
			        $query.=' AND country=\''.$country.'\'';
                }
			    $results = $GLOBALS['wpdb']->get_results($query , OBJECT );


			    if(is_array($results) && count($results)>0){


				    $val = intval($results[0]->val);
				    $newval = $val+60;

				    $wpdb->update(
					    $table_name,
					    array(
						    'val' => $val+60,
					    ),
					    array( 'ID' => $results[0]->id ),
					    array(
						    '%s',	// value1
					    ),
					    array( '%d' )
				    );


			    }else{

				    $wpdb->insert(
					    $table_name,
					    array(
						    'ip' => 0,
						    'type' => 'timewatched',
						    'id_user' => 0,
						    'id_video' => 0,
						    'date' => $date,
						    'country' => $country,
						    'val' => 60,
					    )
				    );
			    }



			    die();


		    }


	    }

    }


	function mysql_get_track_activity($track_id, $pargs = array()){



        // -- get last ON for interval training

		$margs = array(
			'get_last'=>'off',
			'call_from'=>'default',
			'interval'=>'24',
			'type'=>'view',
			'table'=>'detect',
			'day_start'=>'3',
			'day_end'=>'2',
			'get_count'=>'off',
		);

		if($pargs){
			$margs = array_merge($margs, $pargs);
		}


		global $wpdb;
		$table_name = $wpdb->prefix.'dzsvg_activity';


		$format_track_id = 'id_video';



		$margs['table']=$table_name;

		$query = "SELECT ";


		if($margs['get_count']=='on'){

			$query.='COUNT(*)';
		}else{

			$query.='*';
		}

		$query.=" FROM `".$margs['table']."` WHERE `".$format_track_id."` = '".$track_id;


		if(strpos($margs['type'], '%')!==false){

			$query.="' AND type LIKE '".$margs['type']."'";
		}else{

			$query.="' AND type='".$margs['type']."'";
		}


		if($margs['get_last']=='on'){
			$query.=' AND date > DATE_SUB(NOW(), INTERVAL '.$margs['interval'].' HOUR)';
		}

		if($margs['get_last']=='day'){
			$query.=' AND date BETWEEN DATE_SUB(NOW(), INTERVAL '.$margs['day_start'].' DAY)
    AND DATE_SUB(NOW(), INTERVAL  '.$margs['day_end'].' DAY)';

//            echo ' query - '.$query;
		}

		// -- interval start / end


//        echo 'query - '.$query."\n"."\n";


        if(isset($margs['id_user'])){
		    $query.=' AND id_user=\''.$margs['id_user'].'\'';
        }






		$results = $GLOBALS['wpdb']->get_results( $query, OBJECT );



		$finalval = 0;
		if(is_array($results) && count($results)>0){


			if($margs['get_count']=='on'){


			    if(isset($results[0])){
				    $results[0] = (array)$results[0];

//				    print_rr($results);
				    return $results[0]['COUNT(*)'];

                }
			}else{

			    if($margs['call_from']=='debug'){

//				    error_log(print_rr($results,true));
                }
			    foreach($results as $lab => $aux2){
				    $results[$lab] = (array)$results[$lab];

				    $finalval+=$results[$lab]['val'];
                }
			}


		}


		return $finalval;


	}


	function check_posts() {

        // --- check posts
        if (isset($_GET['dzsvg_action'])) {


            if($_GET['dzsvg_action']){

                if($_GET['dzsvg_action']=='get_vimeo_thumb'){



                    $hash = unserialize(DZSHelpers::get_contents("https://vimeo.com/api/v2/video/".$_GET['vimeo_id'].".php"));

//                    print_r($hash);
                    $str_featuredimage = $hash[0]['thumbnail_medium'];



                    die($str_featuredimage);

                }


            }
        }
        if (isset($_GET['dzsvg_shortcode_builder']) && $_GET['dzsvg_shortcode_builder'] == 'on') {
//            dzsprx_shortcode_builder();

            include_once(dirname(__FILE__) . '/tinymce/popupiframe.php');
            define('DONOTCACHEPAGE', true);
            define('DONOTMINIFY', true);

        }
        if (isset($_GET['dzsvg_shortcode_showcase_builder']) && $_GET['dzsvg_shortcode_showcase_builder'] == 'on') {
//            dzsprx_shortcode_builder();

            include_once(dirname(__FILE__) . '/tinymce/popupiframe_showcase.php');
            define('DONOTCACHEPAGE', true);
            define('DONOTMINIFY', true);


            wp_enqueue_style('dzstooltip', $this->thepath . 'libs/dzstooltip/dzstooltip.css');
            wp_enqueue_script('dzstooltip', $this->thepath . 'libs/dzstooltip/dzstooltip.js');

        }
        if (isset($_GET['dzsvg_reclam_builder']) && $_GET['dzsvg_reclam_builder'] == 'on') {
//            dzsprx_shortcode_builder();

            include_once(dirname(__FILE__) . '/tinymce/ad_builder.php');
            define('DONOTCACHEPAGE', true);
            define('DONOTMINIFY', true);

        }
        if (isset($_GET['dzsvg_quality_builder']) && $_GET['dzsvg_quality_builder'] == 'on') {
//            dzsprx_shortcode_builder();

            include_once(dirname(__FILE__) . '/tinymce/quality_builder.php');
            define('DONOTCACHEPAGE', true);
            define('DONOTMINIFY', true);

        }







    }


    function misc_get_ip() {

        if (isset($_SERVER['HTTP_CLIENT_IP']) && !empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (isset($_SERVER['HTTP_X_FORWARDED_FOR']) && !empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = (isset($_SERVER['REMOTE_ADDR'])) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0';
        }

        $ip = filter_var($ip, FILTER_VALIDATE_IP);
        $ip = ($ip === false) ? '0.0.0.0' : $ip;


        return $ip;
    }


    function get_views($id, $pargs = array()) {




        $margs = array(


        );

        $margs=  array_merge($margs, $pargs);


        global $wpdb;



        $table_name = $wpdb->prefix . 'dzsvg_activity';

        $count = $wpdb->get_var( "SELECT COUNT(*) FROM $table_name  WHERE id_video='$id'" );


        if($count){

        }else{
            $count = '0';
        }

        return $count;


//        return $ip;
    }

    function analytics_get() {
        $this->analytics_views = get_option('dzsvg_analytics_views');
        $this->analytics_minutes = get_option('dzsvg_analytics_minutes');


        if ($this->mainoptions['analytics_enable_user_track'] == 'on') {
            $this->analytics_users = get_option('dzsvg_analytics_users');


            if ($this->analytics_users == false) {
                $this->analytics_users = array();
            }
        }


    }



	function db_read_mainitems(){




//        echo '$this->db_has_read_mainitems - '.$this->db_has_read_mainitems;
		if($this->db_has_read_mainitems==false){

			$currDb = '';
			if (isset($_GET['dbname'])) {
				$this->currDb = $_GET['dbname'];
				$currDb = $_GET['dbname'];
			}


			if($this->mainoptions['playlists_mode']=='normal'){


				$tax = $this->taxname_sliders;

//                echo ' tax - '.$tax;

				$terms = get_terms( $tax, array(
					'hide_empty' => false,
				) );




				$this->mainitems = array();
				foreach ($terms as $tm){
					$aux = array(
						'label'=>$tm->name,
						'value'=>$tm->slug,
					);

					array_push($this->mainitems, $aux);
				}

//	            print_rr($terms);

			}else{
				if (isset($_GET['currslider'])) {
					$this->currSlider = $_GET['currslider'];
				} else {
					$this->currSlider = 0;
				}




				$this->dbs = get_option($this->dbname_dbs);
				//$this->dbs = '';
				if ($this->dbs == '') {
					$this->dbs = array('main');
					update_option($this->dbname_dbs, $this->dbs);
				}
				if (is_array($this->dbs) && !in_array($currDb, $this->dbs) && $currDb != 'main' && $currDb != '') {
					array_push($this->dbs, $currDb);
					update_option($this->dbname_dbs, $this->dbs);
				}
				//echo 'ceva'; print_r($this->dbs);
				if ($currDb != 'main' && $currDb != '') {
					$this->dbname_mainitems.='-' . $currDb;
				}

				$this->mainitems = get_option($this->dbname_mainitems);


				if (is_array($this->mainitems)==false) {
					$aux = 'a:2:{i:0;a:3:{s:8:"settings";a:17:{s:2:"id";s:20:"playlist_wave_simple";s:5:"width";s:0:"";s:6:"height";s:0:"";s:11:"galleryskin";s:9:"skin-wave";s:12:"menuposition";s:6:"bottom";s:17:"design_menu_state";s:4:"open";s:36:"design_menu_show_player_state_button";s:3:"off";s:18:"design_menu_height";s:7:"default";s:13:"cuefirstmedia";s:2:"on";s:8:"autoplay";s:2:"on";s:12:"autoplaynext";s:2:"on";s:25:"disable_player_navigation";s:3:"off";s:7:"bgcolor";s:11:"transparent";s:8:"vpconfig";s:20:"skinwavewithcomments";s:12:"enable_views";s:3:"off";s:12:"enable_likes";s:3:"off";s:12:"enable_rates";s:3:"off";}i:0;a:17:{s:4:"type";s:5:"audio";s:6:"source";s:78:"http://www.stephaniequinn.com/Music/Allegro%20from%20Duet%20in%20C%20Major.mp3";s:19:"soundcloud_track_id";s:0:"";s:23:"soundcloud_secret_token";s:0:"";s:9:"sourceogg";s:0:"";s:15:"linktomediafile";s:0:"";s:5:"thumb";s:101:"https://lh5.googleusercontent.com/-RhXJ4O5JiEQ/UoKDBeGx5-I/AAAAAAAAAEU/Dkace1QwAKU/s80/smalllogo2.jpg";s:8:"playfrom";s:0:"";s:7:"bgimage";s:0:"";s:21:"play_in_footer_player";s:3:"off";s:10:"extra_html";s:0:"";s:15:"extra_html_left";s:0:"";s:27:"extra_html_in_controls_left";s:0:"";s:28:"extra_html_in_controls_right";s:0:"";s:15:"menu_artistname";s:4:"Tony";s:13:"menu_songname";s:4:"Tail";s:14:"menu_extrahtml";s:0:"";}i:1;a:17:{s:4:"type";s:5:"audio";s:6:"source";s:45:"http://www.stephaniequinn.com/Music/Canon.mp3";s:19:"soundcloud_track_id";s:0:"";s:23:"soundcloud_secret_token";s:0:"";s:9:"sourceogg";s:0:"";s:15:"linktomediafile";s:0:"";s:5:"thumb";s:101:"https://lh5.googleusercontent.com/-RhXJ4O5JiEQ/UoKDBeGx5-I/AAAAAAAAAEU/Dkace1QwAKU/s80/smalllogo2.jpg";s:8:"playfrom";s:0:"";s:7:"bgimage";s:0:"";s:21:"play_in_footer_player";s:3:"off";s:10:"extra_html";s:0:"";s:15:"extra_html_left";s:0:"";s:27:"extra_html_in_controls_left";s:0:"";s:28:"extra_html_in_controls_right";s:0:"";s:15:"menu_artistname";s:4:"Tony";s:13:"menu_songname";s:5:"Cairn";s:14:"menu_extrahtml";s:0:"";}}i:1;a:4:{s:8:"settings";a:17:{s:2:"id";s:21:"gallery_with_comments";s:5:"width";s:0:"";s:6:"height";s:0:"";s:11:"galleryskin";s:9:"skin-aura";s:12:"menuposition";s:6:"bottom";s:17:"design_menu_state";s:4:"open";s:36:"design_menu_show_player_state_button";s:3:"off";s:18:"design_menu_height";s:7:"default";s:13:"cuefirstmedia";s:2:"on";s:8:"autoplay";s:2:"on";s:12:"autoplaynext";s:2:"on";s:25:"disable_player_navigation";s:3:"off";s:7:"bgcolor";s:11:"transparent";s:8:"vpconfig";s:20:"skinwavewithcomments";s:12:"enable_views";s:2:"on";s:12:"enable_likes";s:2:"on";s:12:"enable_rates";s:3:"off";}i:0;a:17:{s:4:"type";s:5:"audio";s:6:"source";s:78:"http://www.stephaniequinn.com/Music/Allegro%20from%20Duet%20in%20C%20Major.mp3";s:19:"soundcloud_track_id";s:0:"";s:23:"soundcloud_secret_token";s:0:"";s:9:"sourceogg";s:0:"";s:15:"linktomediafile";s:1:"1";s:5:"thumb";s:74:"https://placeholdit.imgix.net/~text?txtsize=22&txt=placeholder&w=300&h=300";s:8:"playfrom";s:0:"";s:7:"bgimage";s:0:"";s:21:"play_in_footer_player";s:3:"off";s:10:"extra_html";s:0:"";s:15:"extra_html_left";s:0:"";s:27:"extra_html_in_controls_left";s:0:"";s:28:"extra_html_in_controls_right";s:0:"";s:15:"menu_artistname";s:8:"Artist 1";s:13:"menu_songname";s:7:"Track 1";s:14:"menu_extrahtml";s:0:"";}i:1;a:17:{s:4:"type";s:5:"audio";s:6:"source";s:45:"http://www.stephaniequinn.com/Music/Canon.mp3";s:19:"soundcloud_track_id";s:0:"";s:23:"soundcloud_secret_token";s:0:"";s:9:"sourceogg";s:0:"";s:15:"linktomediafile";s:1:"2";s:5:"thumb";s:74:"https://placeholdit.imgix.net/~text?txtsize=33&txt=placeholder&w=300&h=300";s:8:"playfrom";s:0:"";s:7:"bgimage";s:0:"";s:21:"play_in_footer_player";s:3:"off";s:10:"extra_html";s:0:"";s:15:"extra_html_left";s:0:"";s:27:"extra_html_in_controls_left";s:0:"";s:28:"extra_html_in_controls_right";s:0:"";s:15:"menu_artistname";s:8:"Artist 1";s:13:"menu_songname";s:7:"Track 1";s:14:"menu_extrahtml";s:0:"";}i:2;a:17:{s:4:"type";s:5:"audio";s:6:"source";s:93:"http://www.stephaniequinn.com/Music/Handel%20-%20Entrance%20of%20the%20Queen%20of%20Sheba.mp3";s:19:"soundcloud_track_id";s:0:"";s:23:"soundcloud_secret_token";s:0:"";s:9:"sourceogg";s:0:"";s:15:"linktomediafile";s:4:"1000";s:5:"thumb";s:0:"";s:8:"playfrom";s:0:"";s:7:"bgimage";s:0:"";s:21:"play_in_footer_player";s:3:"off";s:10:"extra_html";s:0:"";s:15:"extra_html_left";s:0:"";s:27:"extra_html_in_controls_left";s:0:"";s:28:"extra_html_in_controls_right";s:0:"";s:15:"menu_artistname";s:8:"Artist 3";s:13:"menu_songname";s:7:"Track 3";s:14:"menu_extrahtml";s:0:"";}}}';
					$this->mainitems = unserialize($aux);
					//$this->mainitems = array();
					update_option($this->dbname_mainitems, $this->mainitems);
				}


			}

			$this->db_has_read_mainitems = true;
		}

	}



	//include the tinymce javascript plugin
    function add_tcustom_tinymce_plugin($plugin_array) {
//        $plugin_array['ve_dzs_video'] = $this->thepath.'tinymce/visualeditor/editor_plugin.js';
        return $plugin_array;
    }

    //include the css file to style the graphic that replaces the shortcode
    function myformatTinyMCE($options) {

        $ext = 'iframe[align|longdesc|name|width|height|frameborder|scrolling|marginheight|marginwidth|src|id|class|title|style],video[source],source[*]';

        if (isset($options['extended_valid_elements'])) $options['extended_valid_elements'] .= ',' . $ext; else
            $options['extended_valid_elements'] = $ext;


        $options['media_strict'] = 'false';

//    print_r($options);

        $options['content_css'] .= "," . $this->thepath . 'tinymce/visualeditor/editor-style.css';

        return $options;
    }

    function handle_wp_head() {
        echo '<script>';
        echo 'window.dzsvg_settings= {dzsvg_site_url: "' . site_url() . '/",version: "' . DZSVG_VERSION . '",ajax_url: "' . admin_url('admin-ajax.php') . '", debug_mode:"'.$this->mainoptions['debug_mode'].'", merge_social_into_one:"'.$this->mainoptions['merge_social_into_one'].'"}; window.dzsvg_site_url="' . site_url() . '";';
        echo 'window.dzsvg_plugin_url="' . $this->thepath . '";';
        if (defined('DZSVP_VERSION')) {
            global $dzsvp;
            echo 'window.dzsvp_plugin_url = "' . $dzsvp->base_url . '";';
            echo 'window.dzsvp_try_to_generate_image = "' . $this->mainoptions['dzsvp_try_to_generate_image'] . '";';
        }
        if (isset($this->mainoptions['translate_skipad']) && $this->mainoptions['translate_skipad'] != 'Skip Ad') {
            echo 'window.dzsvg_translate_skipad = "' . $this->mainoptions['translate_skipad'] . '";';
        }
        if (isset($this->mainoptions['analytics_enable_user_track']) && $this->mainoptions['analytics_enable_user_track'] == 'on') {
            echo 'window.dzsvg_curr_user = "' . get_current_user_id() . '";';
        }
        echo '</script>';

        if ($this->mainoptions['extra_css']) {
            echo '<style>';
            echo $this->mainoptions['extra_css'];




            echo '</style>';
        }

        global $post;


        if($post){
            if($post->post_type=='dzsvideo'){





                $image = '';
                if (get_post_meta($post->ID, 'dzsvp_thumb', true)) {
                    $image = get_post_meta($post->ID, 'dzsvp_thumb', true);
                }else{

	                if (get_post_meta($post->ID, 'dzsvg_meta_thumb', true)) {
		                $image = get_post_meta($post->ID, 'dzsvg_meta_thumb', true);
	                }else{

		                $image = $this->sanitize_id_to_src( get_post_thumbnail_id($post->ID) );
                    }

                }



                    echo '<meta property="og:title" content="' . $post->post_title . '" />';

                    echo '<meta property="og:description" content="' . strip_tags($post->post_excerpt) . '" />';

                    if($image){

                        echo '<meta property="og:image" content="' . $image . '" />';
                    }


            }
        }

        if (isset($_GET['dzsvg_startitem_dzs-video0']) && ($_GET['dzsvg_startitem_dzs-video0'] || $_GET['dzsvg_startitem_dzs-video0'] === '0')) {

            global $post;


//            print_r($post);

            $po_co = $post->post_content;

            $output_array = array();
            preg_match("/\[(?:dzs_){0,1}videogallery.*?id=\"(.*?)\"/sm", $po_co, $output_array);

//            print_r($output_array);

            if (count($output_array) > 0) {

                if (isset($output_array[1])) {
                    $its = $this->show_shortcode(array(
                            'id' => $output_array[1],
                        'return_mode' => 'items',
                        'call_from' => 'check_for_graph',
                        ));

//                    print_r($its);

                    if (isset($its[$_GET['dzsvg_startitem_dzs-video0']])) {
                        $it = $its[$_GET['dzsvg_startitem_dzs-video0']];

//                        print_r($it);


                        if (isset($it['title'])) {
                            echo '<meta property="og:url" content="' . get_permalink($post->ID) . '?dzsvg_startitem_dzs-video0=' . $_GET['dzsvg_startitem_dzs-video0'] . '" />';
                        }

                        if (isset($it['title'])) {

                            echo '<meta property="og:title" content="' . $it['title'] . '" />';
                        }
                        if (isset($it['description'])) {

                            echo '<meta property="og:description" content="' . strip_tags($it['description']) . '" />';
                        }

                        if (isset($it['thethumb'])) {
                            echo '<meta property="og:image" content="' . $it['thethumb'] . '" />';
                            echo '<meta property="twitter:image" content="' . $it['thethumb'] . '" />';
                        }
                    }
                }
            }

        }
    }



    function handle_admin_head() {

        //global $post; print_r($post);
        //echo 'ceva23';
        ///siteurl : "'.site_url().'",
        $aux = admin_url("admin.php?page=" . $this->adminpagename);
        $params = array('currslider' => '_currslider_');

        if (isset($_GET['dbname']) && $_GET['dbname']) {

            $params['dbname'] = $_GET['dbname'];
        }

        if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_configs ) {
            $params['page'] = $this->adminpagename_configs;
        }

        $newurl = (add_query_arg($params, $aux));

        $params = array('deleteslider' => '_currslider_');
        $delurl = (add_query_arg($params, $aux));
        echo '<script>
        

            window.ultibox_options_init = {
                \'settings_deeplinking\' : \'off\'
                ,\'extra_classes\' : \'close-btn-inset\'
            };
        
        var dzsvg_settings = { thepath: "' . $this->thepath . '",the_url: "' . $this->thepath . '",version: "' . DZSVG_VERSION . '", is_safebinding: "' . $this->mainoptions['is_safebinding'] . '", admin_close_otheritems:"' . $this->mainoptions['admin_close_otheritems'] . '",wpurl : "' . site_url() . '"
        ,translate_add_videogallery: "' . __("Add Video Gallery") . '"
        ,translate_add_player: "' . __("Add Video Player") . '"
        ,translate_add_videoshowcase: "' . __("Add Video Showcase") . '" ';

        //echo 'hmm';
        if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename && ((isset($this->mainitems[$this->currSlider]) && $this->mainitems[$this->currSlider] == '') || isset($this->mainitems[$this->currSlider]) == false)) {
            echo ', addslider:"on"';
        }
        if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_configs && (isset($this->mainvpconfigs[$this->currSlider]) == false || $this->mainvpconfigs[$this->currSlider] == '')) {
            echo ', addslider:"on"';
        }
        if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_mainoptions && (((isset($_GET['dzsvg_shortcode_builder'])) && $_GET['dzsvg_shortcode_builder'] == 'on') || ((isset($_GET['dzsvg_shortcode_showcase_builder'])) && $_GET['dzsvg_shortcode_showcase_builder'] == 'on')) && isset($_GET['sel'])) {
            echo ', startSetup:"' . $this->sanitize_for_js($_GET['sel']), '"';
        }
        echo ', urldelslider:"' . $delurl . '", urlcurrslider:"' . $newurl . '", currSlider:"' . $this->currSlider . '", currdb:"' . $this->currDb . '"' . ',settings_limit_notice_dismissed: "' . $this->mainoptions['settings_limit_notice_dismissed'] . '",shortcode_generator_url: "' . admin_url('admin.php?page=' . $this->adminpagename_mainoptions) . '&dzsvg_shortcode_builder=on' . '"
,shortcode_showcase_generator_url: "' . admin_url('admin.php?page=' . $this->adminpagename_mainoptions) . '&dzsvg_shortcode_showcase_builder=on' . '"
,ad_builder_url: "' . admin_url('admin.php?page=' . $this->adminpagename_mainoptions) . '&dzsvg_reclam_builder=on"
,quality_builder_url: "' . admin_url('admin.php?page=' . $this->adminpagename_mainoptions) . '&dzsvg_quality_builder=on"
,shortcode_generator_player_url: "'.admin_url('admin.php?page='.$this->adminpagename_mainoptions).'&dzsvg_shortcode_player_builder=on"
 };';

        if($this->redirect_to_intro_page){
            ?>
            setTimeout(function(){
            window.location.href ="<?php echo admin_url("admin.php?page=".$this->adminpagename_about); ?>";
            },100);
            <?php
        }
        echo '  </script>';


        //backup only on the gallery admin
        if ($this->mainoptions['enable_auto_backup'] == 'on') {
//            $this->do_backup();
            $last_backup = get_option('dzsvg_last_backup');

            if ($last_backup) {

                $timestamp = time();
                if (abs($timestamp - $last_backup) > (3600 * 24)) {

                    $this->do_backup();
                }

            } else {
                $this->do_backup();
            }
        }
        if (isset($_GET['taxonomy']) && $_GET['taxonomy'] == $this->taxname_sliders){

	        ?><style>body.taxonomy-dzsvg_sliders .wrap,.dzsvg-sliders-con{ opacity:0; transition: opacity 0.3s ease-out; }
                body.taxonomy-dzsvg_sliders.sliders-loaded .wrap, body.taxonomy-dzsvg_sliders.sliders-loaded .dzsvg-sliders-con{
                    opacity:1;
                }
            </style>
	        <?php
        }





    }

    function do_backup() {

        $timestamp = time();

//        echo 'time - '.$timestamp;

	    $upload_dir = wp_upload_dir();




	    if(file_exists($upload_dir['basedir'] . '/dzsvg_backups')){

//            echo 'dada';
	    }else{

//            echo 'nunu';
		    mkdir($upload_dir['basedir'] . '/dzsvg_backups', 0755);
	    }

	    if($this->mainoptions['playlists_mode']=='normal') {




		    $terms = get_terms( $this->taxname_sliders, array(
			    'hide_empty' => false,
		    ) );

		    foreach($terms as $term){

			    $data = $this->playlist_export($term->term_id);

			    if ( is_array( $data ) ) {
				    $data = json_encode( $data );
			    }
//                file_put_contents($this->base_path . 'backups/backup_' . $adb . '_' . $timestamp . '.txt', $data);g
			    file_put_contents( $upload_dir['basedir'] . '/dzsvg_backups/backup_' . $term->slug . '_' . $timestamp . '.txt', $data );
		    }
	    }else{
		    $data = get_option($this->dbitemsname);

		    if (is_array($data)) {
			    $data = serialize($data);
		    }

//        echo ' data - '.$data;
//        file_put_contents('backups/backup_'.$timestamp,$data);
//        file_put_contents($this->base_path . 'backups/backup_' . $timestamp . '.txt', $data);






		    file_put_contents($upload_dir['basedir'] . '/dzsvg_backups/backup_' . $timestamp . '.txt', $data);


//        $theurl_forwaveforms = $upload_dir['url'].'/';

//        echo $upload_dir['basedir'] . '/dzsvg_backups/backup_' . $timestamp . '.txt';

//        print_r($upload_dir);

		    update_option('dzsvg_last_backup', $timestamp);


		    if (is_array($this->dbs)) {
			    foreach ($this->dbs as $adb) {
				    $data = get_option($this->dbitemsname . '-' . $adb);

				    if (is_array($data)) {
					    $data = serialize($data);
				    }
//                file_put_contents($this->base_path . 'backups/backup_' . $adb . '_' . $timestamp . '.txt', $data);
				    file_put_contents($upload_dir['basedir'] . '/dzsvg_backups/backup_' . $adb . '_' . $timestamp . '.txt', $data);


			    }
		    }
        }


    }

    function handle_footer() {

        global $post;
        if (!$post) {
            return;
        }






        //echo 'ceva';
        $wallid = get_post_meta($post->ID, 'dzsvg_fullscreen', true);
        if ($wallid != '' && $wallid != 'none') {
            echo '<div class="wall-close">' . __('CLOSE GALLERY', 'dzsvg') . '</div>';
            echo do_shortcode('[videogallery id="' . $wallid . '" fullscreen="on"]');
            ?>
            <script>
                var dzsvg_videofs = true;
                jQuery(document).ready(function ($) {
                    //$('body').css('overflow', 'hidden');
                    jQuery(".wall-close").click(handle_wall_close);
                    function handle_wall_close() {
                        var _t = $(this);
                        if (dzsvg_videofs == true) {
                            _t.html('OPEN GALLERY');
                            jQuery(".gallery-is-fullscreen").fadeOut("slow");
                            dzsvg_videofs = false;
                        } else {
                            _t.html('CLOSE GALLERY');
                            jQuery(".gallery-is-fullscreen").fadeIn("slow");
                            dzsvg_videofs = true;
                        }
                    }
                })
            </script>
            <?php
        }



        $vpsettingsdefault = array();
        $vpsettingsdefault['settings'] = array_merge($this->vpsettingsdefault, array());
        $i = 0;
        $vpconfig_k = 0;
        $vpsettings = array();




        if ($this->mainoptions['zoombox_video_config']) {
            $vpconfig_id = $this->mainoptions['zoombox_video_config'];

            
//            print_r($this->mainvpconfigs);
            for ($i3 = 0; $i3 < count($this->mainvpconfigs); $i3++) {

                if(isset($this->mainvpconfigs[$i3]['settings']['id'])){

//                    echo ' $vpconfig_id - '.$vpconfig_id.' -- '.$this->mainvpconfigs[$i3]['settings']['id'].'|';
                    if ((isset($vpconfig_id)) && ($vpconfig_id == $this->mainvpconfigs[$i3]['settings']['id'])) {
                        $vpconfig_k = $i3;
                    }
                }
            }
            $vpsettings = $this->mainvpconfigs[$vpconfig_k];
        }

//        echo ' $this->mainoptions[\'zoombox_video_config\'] ->  '.$this->mainoptions['zoombox_video_config'];
//        print_r($vpsettings);

        if(is_array($vpsettings)==false){

            $vpsettings = array();
        }

        $vpsettings = array_merge($vpsettingsdefault, $vpsettings);


//        echo ' $this->mainoptions[\'zoombox_video_config\'] -  '.$this->mainoptions['zoombox_video_config'];
//        print_r($vpsettings);


        ?>
        <script class="dzsvg-multisharer-script">"use strict";
            <?php




//                    echo '$this->mainoptions[\'merge_social_into_one\'] - '.$this->mainoptions['merge_social_into_one'].'|';
//                    echo '$this->multisharer_on_page - '.$this->multisharer_on_page;

                if($this->mainoptions['merge_social_into_one']=='on' || $this->multisharer_on_page){

//            $fout.= 'ceva'.$this->mainoptions['social_social_networks'];
                    if($this->mainoptions['social_social_networks']){


                        $aux = stripslashes($this->mainoptions['social_social_networks']);





                        $aux = str_replace(array("\r", "\r\n", "\n"), '', $aux);
                        $aux = str_replace(array("'"), '&quot;', $aux);



                        echo 'window.dzsvg_social_feed_for_social_networks = \''.$aux.'\'; ';


                    }
                    if($this->mainoptions['social_share_link']){


                        $aux = stripslashes($this->mainoptions['social_share_link']);





                        $aux = str_replace(array("\r", "\r\n", "\n"), '', $aux);
                        $aux = str_replace(array("'"), '&quot;', $aux);



                        echo 'window.dzsvg_social_feed_for_share_link = \''.$aux.'\'; ';
                    }
                    if($this->mainoptions['social_embed_link']){


                        if(strpos($this->mainoptions['social_embed_link'], '<textarea')!==false && strpos($this->mainoptions['social_embed_link'], '</textarea>')===false){

                            $this->mainoptions['social_embed_link'] = $this->mainoptions['social_embed_link'].'</textarea>';
                        }






                        $aux = stripslashes($this->mainoptions['social_embed_link']);





                        $aux = str_replace(array("\r", "\r\n", "\n"), '', $aux);
                        $aux = str_replace(array("'"), '&quot;', $aux);



                        echo 'window.dzsvg_social_feed_for_embed_link = \''.$aux.'\'; ';
                    }
                }

        ?>window.init_zoombox_settings = {
                settings_zoom_doNotGoBeyond1X: 'off'
                , design_skin: 'skin-nebula'
                , settings_enableSwipe: 'off'
                , settings_enableSwipeOnDesktop: 'off'
                , settings_galleryMenu: 'dock'
                , settings_useImageTag: 'on'
                , settings_paddingHorizontal: '100'
                , settings_paddingVertical: '100'
                , settings_disablezoom: 'off'
                , settings_transition: 'fade'
                , settings_transition_out: 'fade'
                , settings_transition_gallery: 'slide'
                , settings_disableSocial: 'on'
                , settings_zoom_use_multi_dimension: 'on'
                ,videoplayer_settings:{
                    zoombox_video_autoplay: "<?php echo $this->mainoptions['zoombox_autoplay']; ?>"
                    ,design_skin: "<?php echo $vpsettings['settings']['skin_html5vp']; ?>"
                    ,settings_youtube_usecustomskin: "<?php echo $vpsettings['settings']['yt_customskin']; ?>"
                    ,extra_classes: "<?php

                    if (isset($vpsettings['settings']['hide_on_mouse_out']) && $vpsettings['settings']['hide_on_mouse_out']=='on') {
                        echo ' hide-on-mouse-out';
                    }
                    if (isset($vpsettings['settings']['hide_on_paused']) && $vpsettings['settings']['hide_on_paused']=='on') {
                        echo ' hide-on-paused';
                    }

                    ?>"<?php


                    //addslashes
                    if($this->generate_player_extra_controls(null,$vpsettings)){
                        echo ',extra_controls: \'<div class="extra-controls">'.$this->sanitize_for_js($this->generate_player_extra_controls(null,$vpsettings)).'</div>\'';
                    }

                    ?>
            }
            };
        </script><?php
    }

    function vimeo_func($atts) {
        //[vimeo id="youtubeid"]
        $fout = '';
        $margs = array('id' => '2', 'vimeo_title' => '', 'vimeo_byline' => '0', 'vimeo_portrait' => '0', 'vimeo_color' => '', 'width' => '100%', 'height' => '300', 'config' => '', 'single' => 'on',);

        if ($atts == false) {
            $atts = array();
        }

        $margs = array_merge($margs, $atts);

        $w = 400;
        if (isset($margs['width'])) {
            $w = $margs['width'];
        }
        $h = 300;
        if (isset($margs['height'])) {
            $h = $margs['height'];
        }

        $vpsettingsdefault = array();
        $vpsettingsdefault['settings'] = array_merge($this->vpsettingsdefault, array());
        $i = 0;
        $vpconfig_k = 0;
        $vpsettings = array();


        if ($margs['config'] != '') {
            $vpconfig_id = $margs['config'];

            for ($i = 0; $i < count($this->mainvpconfigs); $i++) {
                if ((isset($vpconfig_id)) && ($vpconfig_id == $vpconfig_id)) {
                    $vpconfig_k = $i;
                }
            }
            $vpsettings = $this->mainvpconfigs[$vpconfig_k];
        }


        $vpsettings = array_merge($vpsettingsdefault, $vpsettings);

        //print_r($vpsettings);

        if (isset($vpsettings['settings']) && isset($vpsettings['settings']['vimeo_byline'])) {
            $margs['vimeo_byline'] = $vpsettings['settings']['vimeo_byline'];
        }
        if (isset($vpsettings['settings']) && isset($vpsettings['settings']['vimeo_title'])) {
            $margs['vimeo_title'] = $vpsettings['settings']['vimeo_title'];
        }
        if (isset($vpsettings['settings']) && isset($vpsettings['settings']['vimeo_color'])) {
            $margs['vimeo_color'] = $vpsettings['settings']['vimeo_color'];
        }
        if (isset($vpsettings['settings']) && isset($vpsettings['settings']['vimeo_portrait'])) {
            $margs['vimeo_portrait'] = $vpsettings['settings']['vimeo_portrait'];
        }

        //print_r($margs);


        $str_title = 'title=' . $margs['vimeo_title'];
        $str_byline = '&amp;byline=' . $margs['vimeo_byline'];
        $str_portrait = '&amp;portrait=' . $margs['vimeo_portrait'];
        $str_color = '';
        if ($margs['vimeo_color'] != '') {
            $str_color = '&amp;color=' . $margs['vimeo_color'];
        }


        $fout .= '<iframe src="https://player.vimeo.com/video/' . $margs['id'] . '?' . $str_title . $str_byline . $str_portrait . $str_color . '" width="' . $w . '" height="' . $h . '" frameborder="0"></iframe>';
        return $fout;
    }

    function youtube_func($atts) {
        //[youtube id="youtubeid"]

        $fout = '';

        $margs = array(
                'width' => '100%',
                'config' => '',
                'height' => '300',
                'source' => '',
                'mediaid' => '',
                'player' => '',
                'mp4' => '',
                'sourceogg' => '',
                'autoplay' => 'off',
                'cuevideo' => 'on',
                'cover' => '',
                'type' => 'youtube',
                'cssid' => '',
                'single' => 'on',
            );

        $margs = array_merge($margs, $atts);

        if (isset($margs['id']) && $margs['id']) {
            $margs['source'] = $margs['id'];
        }

        return $this->shortcode_player($margs);
    }

    function sanitize_url($arg){
        $arg = str_replace('"','',$arg);
        $arg = str_replace('&#8221;','',$arg);
        $arg = str_replace('&#8243;','',$arg);
        return $arg;
    }

	function sanitize_decode_for_html_attr($arg){

		$fout = html_entity_decode($arg);

		$fout = str_replace('{{quot}}','"', $fout);
		$fout = str_replace('{patratstart}','[', $fout);
		$fout = str_replace('{patratend}',']', $fout);




		return $fout;
	}
    function shortcode_player($atts= array(), $content = '') {
        //[dzs_video source="https://localhost/wordpress/wp-content/uploads/2015/03/test.m4v" config="minimalplayer" height="" type="video"]
        //[video source="pathto.mp4"]
        $this->slider_index++;

        $fout = '';


        $this->front_scripts();

        $margs = array(
            'width' => '100%', // -- the width , leave 100% for responsive
            'config' => '',  // -- player configuration name
            'height' => '300', // -- force a height
            'source' => '', // -- the mp4 source / youtube id / vimeo id
            'mediaid' => '', // -- link to a media element
            'sourceogg' => '', // the ogg source
            'autoplay' => 'off', // autoplay video
            'cuevideo' => 'on',  // autoload video
            'cover' => '',  // cover image
            'type' => 'video', // youtube / vimeo / video
            'cssid' => '', // force an id - leave blank preferably
            'single' => 'on', // leave on
            'loop' => 'off', // -- loop the video on ending
            'is_360' => 'off', // -- loop the video on ending
            'responsive_ratio' => 'off',
            'logo' => '', // -- optional logo for the video
            'link' => '', // -- a link where the
            'link_label' => __('Go to Link'),
            'logo_link' => '',
            'qualities' => '',
            'playerid' => '',
            'extra_classes' => '', // leave blank
            'extra_classes_player' => '', // -- enter a extra css class for the player for example, entering "with-bottom-shadow" will create a shadow underneath the player
            'call_from' => 'from_shortcode_player',
            'title' => 'default', // -- title to appear on the left top
            'description' => 'default', // -- description to appear if the info button is enabled in video player configurations
            'autoplay_on_mobile_too_with_video_muted' => 'off', // -- autoplay on mobile too with video muted
        );


        $player_index = $this->index_players + 1;

        $margs = array_merge($margs, $atts);

        $original_margs = array_merge(array(), $margs);

        if ($margs['cssid'] == '') {
            $margs['cssid'] = 'vp' . ($player_index);
        }


        $video_post = null;

        $lab = 'source'; $margs[$lab]=$this->sanitize_url($margs[$lab]);
        $lab = 'config'; $margs[$lab]=$this->sanitize_url($margs[$lab]);
        $lab = 'type'; $margs[$lab]=$this->sanitize_url($margs[$lab]);
        $lab = 'cover'; $margs[$lab]=$this->sanitize_url($margs[$lab]);
        $lab = 'qualities';
        if(isset($margs[$lab])){

	        $margs[$lab]=$this->sanitize_url($margs[$lab]);
        }

        $lab = 'responsive_ratio'; $margs[$lab]=$this->sanitize_url($margs[$lab]);

//        echo 'player $margs  - '; print_rr($margs);


        if($margs['type']=='facebook') {


	        $app_id     = $this->mainoptions['facebook_app_id'];
	        $app_secret = $this->mainoptions['facebook_app_secret'];


	        $the_facebook_video_id = $margs['source'];
	        $the_facebook_video_id_arr = array();
	        if(strpos($margs['source'],'/')){

		        $the_facebook_video_id_arr = explode('/',$the_facebook_video_id);

		        if($the_facebook_video_id_arr[count($the_facebook_video_id_arr)-1]==''){
		            $the_facebook_video_id = $the_facebook_video_id_arr[count($the_facebook_video_id_arr)-2];
                }else{
			        $the_facebook_video_id = $the_facebook_video_id_arr[count($the_facebook_video_id_arr)-1];

                }
            }



	        $posts    = null;
	        $response = null;

	        if ( $app_id && $app_secret ) {

		        require_once 'class_parts/src/Facebook/autoload.php'; // change path as needed

		        $fb = new \Facebook\Facebook( [
			        'app_id'                => $app_id,
			        'app_secret'            => $app_secret,
			        'default_graph_version' => 'v2.10',
			        //'default_access_token' => '{access-token}', // optional
		        ] );



		        $accessToken = $this->mainoptions['facebook_access_token'];

		        $helper = $fb->getRedirectLoginHelper();

		        if($accessToken) {


			        if ( ! isset( $accessToken ) ) {
				        if ( $helper->getError() ) {
					        header( 'HTTP/1.0 401 Unauthorized' );
					        echo "Error: " . $helper->getError() . "\n";
					        echo "Error Code: " . $helper->getErrorCode() . "\n";
					        echo "Error Reason: " . $helper->getErrorReason() . "\n";
					        echo "Error Description: " . $helper->getErrorDescription() . "\n";
				        } else {
//				header('HTTP/1.0 400 Bad Request');
					        echo 'Bad request';
				        }
//			exit;
			        }



//			        echo ' $the_facebook_video_id - '.$the_facebook_video_id;

			        try {
				        // Returns a `Facebook\FacebookResponse` object
				        $response = $fb->get(
					        '/'.$the_facebook_video_id.'?fields=source,embed_html',
					        $accessToken
				        );
			        } catch(Facebook\Exceptions\FacebookResponseException $e) {
				        echo 'Graph returned an error: ' . $e->getMessage();
			        } catch(Facebook\Exceptions\FacebookSDKException $e) {
			        }
			        $graphNode = $response->getGraphNode();


//			        print_rr($graphNode);

			        if($graphNode->getField('source')){
				        $margs['source']=$graphNode->getField('source');
				        $margs['type']='normal';
                    }else{
				        if($graphNode->getField('embed_html')){
					        echo $graphNode->getField('embed_html');
					        return;
				        }
                    }

		        }

// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
//   $helper = $fb->getRedirectLoginHelper();
//   $helper = $fb->getJavaScriptHelper();
//   $helper = $fb->getCanvasHelper();
//   $helper = $fb->getPageTabHelper();


	        }
        }
        if($margs['type']=='video' || $margs['type']=='normal'){

            if(is_numeric($margs['source'])){


                $po = get_post($margs['source']);

//                print_rr($po);

                if($po->post_type=='dzsvideo'){

                    $imgsrc = get_post_meta($margs['source'],'dzsvp_featured_media',true);
                }

//                echo 'imgsrc - '.$imgsrc;
                if($po->post_type=='attachment') {
                    $imgsrc = wp_get_attachment_url($margs['source']);
                }
//                print_r($imgsrc);
                if($margs['mediaid']==''){
                    $margs['mediaid'] = $margs['source'];
                }
                $margs['source'] = $imgsrc;
            }
        }


        if ($margs['mediaid'] != '') {
            $auxpo = get_post($margs['mediaid']);
            if ($auxpo == false) {
                return '<div class="warning">Video does not exist anymore...</div>';
            }else{
                $video_post = $auxpo;
            }

            $post_id = $margs['mediaid'];

//            print_r($auxpo);
            if($auxpo->post_type=='attachment'){

                if($margs['source']==''){

                    $margs['source'] = $auxpo->guid;
                }
            }


            if($auxpo->post_type=='product' || $auxpo->post_type=='dzsvideo'){

                if(get_post_meta($post_id, 'dzsvp_featured_media',true)){

                    if($margs['source']==''){

                        $margs['source']=get_post_meta($post_id, 'dzsvp_featured_media',true);
                    }
                }


                if($video_post->post_content){

                    if($margs['description']=='default'){

                        $margs['description']=$video_post->post_content;
                    }
                }
            }

            //print_r($auxpo);
        }
        if (isset($margs['mp4']) && $margs['mp4'] ) {
            //$auxpo = get_post($margs['mediaid']);
            $margs['source'] = $margs['mp4'];
            //print_r($auxpo);
        }
        if (isset($margs['player']) && $margs['player'] != '') {
            $margs['config'] = $margs['player'];
        }

        if($margs['title']=='default'){
            $margs['title'] = '';
        }
        if($margs['description']=='default'){
            $margs['description'] = '';

            if($content){

                $margs['description'] = $content;
                $margs['striptags'] = 'off';
            }
        }


        $i = 0;
        $vpconfig_k = 0;
        $vpconfig_id = '';

        $vpsettingsdefault = array_merge($this->vpsettingsdefault, array());
        $vpsettings = array();


        if ($margs['config'] != '') {
            $vpconfig_id = $margs['config'];
        }

        if ($vpconfig_id != '') {
            //print_r($this->mainvpconfigs);
            for ($i = 0; $i < count($this->mainvpconfigs); $i++) {
                if ((isset($vpconfig_id)) && ($vpconfig_id == $this->mainvpconfigs[$i]['settings']['id'])) $vpconfig_k = $i;
            }
            $vpsettings = $this->mainvpconfigs[$vpconfig_k];


            if (!isset($vpsettings['settings']) || $vpsettings['settings'] == '') {
                $vpsettings['settings'] = array();
            }
        }

        if (!isset($vpsettings['settings']) || (isset($vpsettings['settings']) && !is_array($vpsettings['settings']))) {
            $vpsettings['settings'] = array();
        }

        $vpsettings['settings'] = array_merge($vpsettingsdefault, $vpsettings['settings']);


        $skin_vp = 'skin_aurora';
        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom') {
            $skin_vp = 'skin_pro';
        } else {
            if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom_aurora') {
                $skin_vp = 'skin_aurora';
            } else {

                $skin_vp = $vpsettings['settings']['skin_html5vp'];
            }
        }

        unset($vpsettings['settings']['id']);


        $str_sourceogg = '';

        $its = array(0 => $margs,);
        $its = array_merge($its, $vpsettings);

        if ($margs['sourceogg'] != '') {

            if (strpos($margs['sourceogg'], '.webm') === false) {
                $str_sourceogg .= ' data-sourceogg="' . $margs['sourceogg'] . '"';
            } else {
                $str_sourceogg .= ' data-sourcewebm="' . $margs['sourceogg'] . '"';
            }

            $its[0]['html5sourceogg'] = $margs['sourceogg'];
        }

        $str_cover = '';

//        print_r($margs);
        if ($margs['cover'] != '') {
//            echo 'lalala';
            $its['settings']['coverImage'] = $margs['cover'];
        }
        if ($margs['title']) {
//            echo 'lalala';
            $its[0]['title'] = $margs['title'];
        }
        if ($margs['loop']) {
//            echo 'lalala';
            $its[0]['loop'] = $margs['loop'];
        }
        if ($margs['logo']) {
//            echo 'lalala';
            $its[0]['logo'] = $margs['logo'];
        }
        if ($margs['logo_link']) {
//            echo 'lalala';
            $its[0]['logo_link'] = $margs['logo_link'];
        }
        if ($margs['description']) {
//            echo 'lalala';
            $its[0]['description'] = $margs['description'];
        }
        if ($margs['link']) {
//            echo 'lalala';
            $its[0]['link'] = $margs['link'];
        }
        if ($margs['link_label']) {
//            echo 'lalala';
            $its[0]['link_label'] = $margs['link_label'];
        }
        if (isset($margs['adarray']) && $margs['adarray']) {
//            echo 'lalala';
            $its[0]['adarray'] = $this->sanitize_decode_for_html_attr($margs['adarray']);
        }
        if ($margs['is_360']) {
//            echo 'lalala';
            $its[0]['is_360'] = $margs['is_360'];
        }

        if($video_post){
            $its[0]['video_post'] = $video_post;
        }

//        print_rr($margs);
//        print_rr($its);

//        print_r($vpsettings);

        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom' || $vpsettings['settings']['skin_html5vp'] == 'skin_custom_aurora' ||  ( isset($vpsettings['settings']['use_custom_colors']) && $vpsettings['settings']['use_custom_colors']=='on' ) ) {

//            echo 'YESSS CUSTOM COLORS';

            $fout.=$this->style_player('.vp' . $player_index,$vpsettings);
        }






	    if(isset($margs['playerid']) && $margs['playerid']){

	    }else{

//	            $fout.=' data-player-id="'.dzs_clean_string($che['source']).'"';
		    $margs['playerid'] = $this->encode_to_number($margs['source']);
	    }


//        print_rr($its);
//        print_rr($margs);

        $margs['call_from']='from_shortcode_player';
        $margs['extra_classes'].=' is-single-video-player';
        $margs['extra_classes_player'].=' is-single-video-player';

//        print_r($margs);
        $fout .= $this->parse_items($its, $margs) . ' 
<script>jQuery(document).ready(function($){ var videoplayersettings'.$player_index.' = {
autoplay : "' . $margs['autoplay'] . '",
cueVideo : "' . $margs['cuevideo'] . '",
ad_show_markers : "on",
controls_out_opacity : "' . $vpsettings['settings']['html5design_controlsopacityon'] . '",
controls_normal_opacity : "' . $vpsettings['settings']['html5design_controlsopacityout'] . '"
,settings_hideControls : "off"
,settings_video_overlay : "' . $vpsettings['settings']['settings_video_overlay'] . '"
,settings_disable_mouse_out : "' . $vpsettings['settings']['settings_disable_mouse_out'] . '"
,settings_ios_usecustomskin : "' . $vpsettings['settings']['settings_ios_usecustomskin'] . '"
,settings_swfPath : "' . $this->thepath . 'preview.swf"
,design_skin: "' . $skin_vp . '"';







        if ( $margs['autoplay_on_mobile_too_with_video_muted'] == 'on') {
            $fout .= ',autoplay_on_mobile_too_with_video_muted:"on"';
        }

        $lab = 'vimeo_byline';
        if (isset($vpsettings['settings'][$lab]) && $vpsettings['settings'][$lab] == '0') {
            $fout .= ','.$lab.':"'.$vpsettings['settings'][$lab].'"';
        }

        $lab = 'vimeo_portrait';
        if (isset($vpsettings['settings'][$lab]) && $vpsettings['settings'][$lab] == '0') {
            $fout .= ','.$lab.':"'.$vpsettings['settings'][$lab].'"';
        }

        $lab = 'vimeo_title';
        if (isset($vpsettings['settings'][$lab]) && $vpsettings['settings'][$lab] == '0') {
            $fout .= ','.$lab.':"'.$vpsettings['settings'][$lab].'"';
        }

        $lab = 'vimeo_badge';
        if (isset($vpsettings['settings'][$lab]) && $vpsettings['settings'][$lab] == '0') {
            $fout .= ','.$lab.':"'.$vpsettings['settings'][$lab].'"';
        }

        $lab = 'vimeo_color';
        if (isset($vpsettings['settings'][$lab]) && $vpsettings['settings'][$lab] && $vpsettings['settings'][$lab] !== 'ffffff') {
            $fout .= ','.$lab.':"'.$vpsettings['settings'][$lab].'"';
        }








	    if (isset($vpsettings['settings']['settings_video_end_reset_time']) && $vpsettings['settings']['settings_video_end_reset_time'] == 'off') {
            $fout .= ',settings_video_end_reset_time:"off"';
        }
        if (isset($vpsettings['settings']['settings_big_play_btn']) && $vpsettings['settings']['settings_big_play_btn'] == 'on') {
            $fout .= ',settings_big_play_btn:"on"';
        }

        if (isset($vpsettings['settings']['video_description_style']) && $vpsettings['settings']['video_description_style']) {
            $fout .= ',video_description_style:"'.$vpsettings['settings']['video_description_style'].'"';
        }

        if (isset($vpsettings['settings']['vimeo_is_chromeless']) && $vpsettings['settings']['vimeo_is_chromeless']=='on') {
            $fout .= ',vimeo_is_chromeless:"'.$vpsettings['settings']['vimeo_is_chromeless'].'"';
        }
        if ($this->mainoptions['videoplayer_end_exit_fullscreen']=='off') {
            $fout .= ',end_exit_fullscreen:"'.$this->mainoptions['videoplayer_end_exit_fullscreen'].'"';
        }

        $fout .= '}; ';

	    if ($this->mainoptions['analytics_enable'] == 'on') {


		    $fout .= 'videoplayersettings' . $player_index . '.action_video_view = window.dzsvg_wp_send_view;';

		    $fout .= 'videoplayersettings' . $player_index . '.action_video_contor_60secs = window.dzsvg_wp_send_contor_60_secs;';

	    }


        if (isset($vpsettings['settings']['enable_quality_changer_button']) && $vpsettings['settings']['enable_quality_changer_button'] == 'on') {
            $fout .= ' videoplayersettings' . $player_index . '.settings_extrahtml_before_right_controls = \'<div class="dzsvg-player-button quality-selector show-only-when-multiple-qualities">{{svg_quality_icon}}<div class="dzsvg-tooltip">{{quality-options}}</div></div>\';';
        }


        if ($this->mainoptions['settings_trigger_resize'] == 'on') {
            $fout .= 'videoplayersettings' . $player_index . '.settings_trigger_resize="1000"; ';
        };







	    if ( isset( $vpsettings['settings']['youtube_sdquality'] ) ) {
		    $fout .= 'videoplayersettings' . $player_index . '.youtube_sdQuality = "' . $vpsettings['settings']['youtube_sdquality'] . '";';
	    }
	    if ( isset( $vpsettings['settings']['settings_mouse_out_delay'] ) ) {
		    $fout .= 'videoplayersettings' . $player_index . '.settings_mouse_out_delay = "' . $vpsettings['settings']['settings_mouse_out_delay'] . '";';
	    }
	    if ( isset( $vpsettings['settings']['youtube_hdquality'] ) ) {
		    $fout .= 'videoplayersettings' . $player_index . '.youtube_hdQuality = "' . $vpsettings['settings']['youtube_hdquality'] . '";';
	    }
	    if ( isset( $vpsettings['settings']['youtube_defaultquality'] ) ) {
		    $fout .= 'videoplayersettings' . $player_index . '.youtube_defaultQuality = "' . $vpsettings['settings']['youtube_defaultquality'] . '";';


		    if($margs['type']=='youtube'){

			    if($vpsettings['settings']['youtube_defaultquality']=='hd'){

				    $fout .= 'videoplayersettings' . $player_index . '.settings_suggestedQuality = "' . $vpsettings['settings']['youtube_hdquality'] . '";';
			    }
            }

	    }

//	    print_rr($vpsettings['settings']);



        // -- for single player
	    if(isset($vpsettings['settings']['enable_multisharer_button']) && $vpsettings['settings']['enable_multisharer_button']=='on'){
		    $auxembed = '<iframe src="' . $this->base_url . 'bridge.php?action=view&video_args=' . urlencode(json_encode($original_margs)) . '" style="width:100%; height:300px; overflow:hidden;" scrolling="no" frameborder="0"></iframe>';
		    $fout .= 'videoplayersettings' . $player_index . '.embed_code=\''.$auxembed.'\'; ';

        }

        $fout.='jQuery(".vp' . ($player_index) . '").vPlayer(videoplayersettings'.$player_index.'); });</script>';







	    if ($this->mainoptions['analytics_enable'] == 'on') {

	        if(current_user_can('manage_options')){

		        $fout .= '<div class="extra-btns-con">';
		        $fout .= '<span class="btn-zoomsounds stats-btn" data-playerid="'.$margs['playerid'].'"><span class="the-icon"><i class="fa fa-tachometer" aria-hidden="true"></i></span><span class="btn-label">'.esc_html__('Stats','dzsvg').'</span></span>';
		        $fout .= '</div>';



		        wp_enqueue_style('dzsvg_showcase', $this->thepath . 'front-dzsvp.css');
		        wp_enqueue_script('dzsvg_showcase', $this->thepath . 'front-dzsvp.js');
            }




	    }


        return $fout;
    }


    function style_player($selector, $vpsettings, $pargs=array()){



        $fout = '';





        $margs = array(

            'gallery'=>''
        );



        $margs = array_merge($margs, $pargs);


        if($selector){

        }else{
            $selector = '.vp' . '0';
        }



        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom' || $vpsettings['settings']['skin_html5vp'] == 'skin_custom_aurora'  ||  ( isset($vpsettings['settings']['use_custom_colors']) && $vpsettings['settings']['use_custom_colors']=='on' )) {


            $fout .= '<style>';
            $fout .=  $selector. ' { background-color:' . $this->mainoptions_dc['background'] . ';} ';
            $fout .=  $selector. ' .cover-image > .the-div-image { background-color:' . $this->mainoptions_dc['background'] . ';} ';
            $fout .= $selector . ' .background { background-color:' . $this->mainoptions_dc['controls_background'] . '!important;} ';
            $fout .= $selector . ' .scrub-bg{ background-color:' . $this->mainoptions_dc['scrub_background'] . '!important;} ';
            $fout .= $selector . ' .scrub-buffer{ background-color:' . $this->mainoptions_dc['scrub_buffer'] . '!important;} ';

            $fout .= $selector . ' .playcontrols .playSimple path,'.$selector . ' .playcontrols .pauseSimple  path{ fill:' . $this->mainoptions_dc['controls_color'] . '!important;}  '.$selector . ' .dzsvg-control,  '.$selector . ' .dzsvg-control a >i{ color: ' . $this->mainoptions_dc['controls_color'] . '!important; }  '.$selector . ' .volumeicon path{ fill: ' . $this->mainoptions_dc['controls_color'] . '!important; }  '.$selector . ' .fscreencontrols rect, '.$selector . ' .fscreencontrols polygon { fill: ' . $this->mainoptions_dc['controls_color'] . '!important; } '.$selector . ' .hdbutton-con .hdbutton-normal{ color: ' . $this->mainoptions_dc['controls_color'] . '!important; }   ';

            $fout.=$selector.' .playSimple{ border-left-color:'.$this->mainoptions_dc['controls_color'].'!important;  } .vplayer.skin_reborn .pauseSimple:before, .vplayer.skin_reborn .pauseSimple:after{ background-color:'.$this->mainoptions_dc['controls_color'].'!important;  } '.$selector.' .skin_reborn .playcontrols{ background-color:' . $this->mainoptions_dc['controls_background'] . '!important; } '.$selector.' .skin_reborn .volume_static .volbar{ background-color:'.$this->mainoptions_dc['controls_background'].'!important; } '.$selector.' .skin_reborn .volume_static .volbar.active{ background-color:'.$this->mainoptions_dc['controls_highlight_color'].'!important; } ';

            $fout .= $selector . ' .playcontrols  .playSimple:hover path{ fill: ' . $this->mainoptions_dc['controls_hover_color'] . '!important; } '.$selector . ' .playcontrols  .pauseSimple:hover path{ fill: ' . $this->mainoptions_dc['controls_hover_color'] . '!important; }  '.$selector . ' .volumeicon:hover path{ fill: ' . $this->mainoptions_dc['controls_hover_color'] . '!important; }  .hdbutton-con:hover .hdbutton-normal{ color: ' . $this->mainoptions_dc['controls_hover_color'] . '!important; }      '.$selector . ' .fscreencontrols:hover rect, '.$selector . ' .fscreencontrols:hover polygon { fill: ' . $this->mainoptions_dc['controls_hover_color'] . '!important; }    '.$selector . ' .dzsvg-control:hover, '.$selector . ' .dzsvg-control:hover a > i{ color: ' . $this->mainoptions_dc['controls_hover_color'] . '!important; }     ';


            $fout .= $selector . ':not(.skin_white) .volume_active{ background-color: ' . $this->mainoptions_dc['controls_highlight_color'] . '!important; } '.$selector . ' .scrub{ background-color: ' . $this->mainoptions_dc['controls_highlight_color'] . '!important; } '.$selector . ' .hdbutton-con .hdbutton-hover{ color: ' . $this->mainoptions_dc['controls_highlight_color'] . '!important; } ';

            $fout.=' .vplayer.skin_reborn .volume_active{ background-color: transparent!important; }';
            $fout .= $selector . ' .curr-timetext{ color: ' . $this->mainoptions_dc['timetext_curr_color'] . '; } ';
            $fout .= '</style>';
        }

        return $fout;
    }

    function log_event($arg) {
        $fil = dirname(__FILE__) . "/log.txt";
        $fh = @fopen($fil, 'a');
        @fwrite($fh, ($arg . "\n"));
        @fclose($fh);
    }

    function show_shortcode_cats($atts, $content = null) {
        $fout = '';
        $margs = array('width' => '100', 'height' => 400,);

        $margs = array_merge($margs, $atts);


        // -- some sanitizing
        $str_tw = $margs['width'];
        $str_th = $margs['height'];


        if (strpos($str_tw, "%") === false) {
            $str_tw = $str_tw . 'px';
        }
        if (strpos($str_th, "%") === false && $str_th != 'auto') {
            $str_th = $str_th . 'px';
        }


//        echo 'ceva'.$content;
        $lb = array("\r\n", "\n", "\r", "<br />");
        $content = str_replace($lb, '', $content);
//        echo $content.'alceva';


        $aux = do_shortcode($content);;

//        $aux = strip_tags($aux, '<p><br/>');

        $fout .= '<div class="categories-videogallery" id="cats' . (++$this->cats_index) . '">';
        $fout .= '<div class="the-categories-con"><span class="label-categories">' . __('categories', 'dzsvg') . '</span></div>';
        $fout .= $aux;
        $fout .= '</div>';
        $fout .= '<script>jQuery(document).ready(function($){ vgcategories("#cats' . $this->cats_index . '"); });</script>';

        return $fout;
    }

    function show_shortcode_lightbox($atts, $content = null) {

        $fout = '';
        //$this->sliders_index++;

        $this->front_scripts();

        wp_enqueue_style('zoombox', $this->thepath . 'assets/zoombox/zoombox.css');
        wp_enqueue_script('zoombox', $this->thepath . 'assets/zoombox/zoombox.js');

        $args = array('id' => 'default', 'db' => '', 'category' => '', 'width' => '', 'height' => '', 'gallerywidth' => '800', 'galleryheight' => '500');
        $args = array_merge($args, $atts);
        $fout .= '<div class="zoombox"';

        if ($args['width'] != '') {
            $fout .= ' data-width="' . $args['width'] . '"';
        }
        if ($args['height'] != '') {
            $fout .= ' data-height="' . $args['height'] . '"';
        }
        if ($args['gallerywidth'] != '') {
            $fout .= ' data-bigwidth="' . $args['gallerywidth'] . '"';
        }
        if ($args['galleryheight'] != '') {
            $fout .= ' data-bigheight="' . $args['galleryheight'] . '"';
        }

        $fout .= 'data-src="' . site_url() . '?dzsvg_action=showinzoombox&id=' . $args['id'] . '" data-type="ajax">' . $content . '</div>';
        $fout .= '<script>
jQuery(document).ready(function($){
$(".zoombox").zoomBox();
});
</script>';

        return $fout;
    }

    function show_shortcode_secondcon($pargs, $content = null) {
        // -- [dzsvg_secondcon id="example-youtube-channel-outer" extraclasses="skin-balne" enable_readmore="on" ]

        $fout = '';

        $margs = array('id' => 'default', 'extraclasses' => '', 'enable_readmore' => 'off',);
        if (is_array($pargs) == false) {
            $pargs = array();
        }
        $margs = array_merge($margs, $pargs);


        wp_enqueue_style('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.css');
        wp_enqueue_script('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.js');




        $id_main = $margs['id'];
        $id = $margs['id'];
        $extra_galleries = array();
        if (strpos($id, ',') !== false) {
            $auxa = explode(",", $id);
            $id = $auxa[0];

            $id_main = $auxa[0];
            unset($auxa[0]);
            $extra_galleries = $auxa;
//            print_r($auxa);
        }



        $gallery_margs = array('id' => $margs['id'], 'return_mode' => 'items',);

        $its = $this->show_shortcode($gallery_margs);




        foreach ($extra_galleries as $extragal) {
            $args = array(
                'id' =>$extragal,
                'return_mode' => 'items',
                'call_from' => 'extra_galleries',

            );

//            print_r($this->show_shortcode($args));


            foreach ($this->show_shortcode($args) as $lab => $it3) {
                if ($lab === 'settings') {
                    continue;
                }
                array_push($its, $it3);
            }
//            $fout.=$this->show_shortcode($args);
//            print_r($its);
        }


        $css_classid = str_replace(' ', '_', $id_main);
        $fout .= '<div class="dzsas-second-con dzsas-second-con-for-' . $css_classid . ' ' . $margs['extraclasses'] . '">';

        if ($margs['enable_readmore'] == 'on') {
            $fout .= '<div class="read-more-con">';
            $fout .= '<div class="read-more-content">';
        }


        $fout .= '<div class="dzsas-second-con--clip">';
        foreach ($its as $lab => $val) {
            if ($lab === 'settings') {
                continue;
            }

            $desc = $val['description'];


            $maxlen = 100;
            if (isset($its['settings']['maxlen_desc']) && $its['settings']['maxlen_desc']) {
                $maxlen = $its['settings']['maxlen_desc'];
            }
//            print_rr($its['settings']);
            if (isset($its['settings']['desc_different_settings_for_aside']) && $its['settings']['desc_different_settings_for_aside']=='on'  ) {

                if (isset($its['settings']['desc_aside_maxlen_desc']) && $its['settings']['desc_aside_maxlen_desc']) {
                    $maxlen = $its['settings']['desc_aside_maxlen_desc'];
                }
            }


//            echo 'maxlen - '.$maxlen;

            $striptags = false;

            if (isset($its['settings']['striptags']) && $its['settings']['striptags'] === 'on') {
                $striptags = true;
            }

            $try_to_close_unclosed_tags = false;


//                $striptags=true;

//            echo 'description - '.$che['description'];

            if (isset($val['description']) && $val['description']) {
                $desc = '' . wp_kses(dzs_get_excerpt(-1, array('content' => $val['description'], 'maxlen' => $maxlen, 'striptags' => $striptags,)), $this->allowed_tags);
//                echo ' final desc -- '. $desc;
            }


            $fout .= '<div class="item">
<h4>' . $val['title'] . '</h4>
<p>' . $desc . '</p>
</div>';

//                print_r($val);

        }


        if ($margs['enable_readmore'] == 'on') {
            $fout .= '</div>';
            $fout .= '</div>';
            $fout .= '<div class="read-more-label"> <i class="fa fa-angle-down"></i> <span>' . __("DETAILS") . '</span></div>';


        } else {

        }
        $fout .= '</div></div>';


        return $fout;


//        print_r($its);
    }



    function sanitize_for_class($arg, $pargs = array()){


        $margs = array(
            'type'=>'image',
        );

        $margs = array_merge($margs,$pargs);



        $arg = str_replace(array(' ','/','\\', ':'),'',$arg);
        return $arg;

    }
    function sanitize_id_to_src($arg, $pargs = array()){




        $margs = array(
            'type'=>'image',
        );

        $margs = array_merge($margs,$pargs);

//        echo ' arg - '.$arg;
        if(is_numeric($arg)){

            if($margs['type']=='image'){

                $imgsrc = wp_get_attachment_image_src($arg, 'full');
                return $imgsrc[0];
            }
            if($margs['type']=='video'){

                $imgsrc = wp_get_attachment_url($arg);
                print_r($imgsrc);
            }



//            print_r($imgsrc);
//            echo ' $imgsrc - '.$imgsrc;
        }else{
            return $arg;
        }


    }

    function sanitize_for_js($arg){

        $lb = array("\r\n", "\n", "\r");
        $arg = str_replace($lb,'',$arg);

        return $arg;
    }

    function sanitize_description($desc, $pargs = array()) {

        $fout = $desc;

        $margs = array('desc_count' => 'default', 'striptags' => 'on', 'try_to_close_unclosed_tags' => 'on', 'desc_readmore_markup' => '',);
        if (is_array($pargs) == false) {
            $pargs = array();
        }
        $margs = array_merge($margs, $pargs);


        $maxlen = 100;
        if ($margs['desc_count']) {
            $maxlen = $margs['desc_count'];
        }


//            echo 'maxlen - '.$maxlen;

        $striptags = false;

        if ($margs['striptags'] == 'on') {
            $striptags = true;
        }

        $try_to_close_unclosed_tags = true;


        if ($striptags) {
            $try_to_close_unclosed_tags = false;
        }
        if ($margs['try_to_close_unclosed_tags'] == 'on') {
            $try_to_close_unclosed_tags = false;
        }
	    $try_to_close_unclosed_tags = false;


//        print_r($margs);
//            echo 'description - '.$che['description'];

        if ($desc) {
            $fout = '' . dzs_get_excerpt(-1, array('content' => $desc, 'maxlen' => $maxlen, 'try_to_close_unclosed_tags' => $try_to_close_unclosed_tags, 'striptags' => $striptags, 'readmore' => 'auto', 'readmore_markup' => $margs['desc_readmore_markup'],));
//                echo ' final desc -- '. $desc;
        }

        return $fout;
    }

    function show_shortcode_outernav($pargs, $content = null) {
        //[dzsvg_outernav id="theidofthegallery" skin="oasis" extraclasses="" thumbs_per_page="12" layout="layout-one-third"]
        $fout = '';

        $margs = array('id' => 'default', 'skin' => 'oasis', 'extraclasses' => '', 'layout' => 'layout-one-fourth', // -- layout-one-fourth   layout-one-third   layout-width-370
            'thumbs_per_page' => '8',);
        if (is_array($pargs) == false) {
            $pargs = array();
        }
        $margs = array_merge($margs, $pargs);


        $id = $margs['id'];

        //---- extra galleries code

        $extra_galleries = array();
        if (strpos($id, ',') !== false) {
            $auxa = explode(",", $id);
            $id = $auxa[0];

            unset($auxa[0]);
            $extra_galleries = $auxa;
//            print_r($auxa);
        }


        $gallery_margs = array('id' => $margs['id'], 'return_mode' => 'items',);





        $its = $this->show_shortcode($gallery_margs);



        foreach ($extra_galleries as $extragal) {
            $args = array(
                'id' =>$extragal,
                'return_mode' => 'items',
                'call_from' => 'extra_galleries',

            );

//            print_r($this->show_shortcode($args));


            foreach ($this->show_shortcode($args) as $lab => $it3) {
                if ($lab === 'settings') {
                    continue;
                }
                array_push($its, $it3);
            }
//            $fout.=$this->show_shortcode($args);
//            print_r($its);
        }


        $css_classid = str_replace(' ', '_', $margs['id']);
        $fout .= '<div class="videogallery--navigation-outer ' . $margs['layout'] . ' videogallery--navigation-outer-for-' . $id . ' videogallery--navigation-outer-for-' . $css_classid . ' skin-' . $margs['skin'] . ' ' . $margs['extraclasses'] . '" data-vgtarget=".id_' . $css_classid . '"><div class="videogallery--navigation-outer--clip"><div class="videogallery--navigation-outer--clipmover">';

        $ix = 0;
        $maxblocksperrow = intval($margs['thumbs_per_page']);
        $nr_pages = 0;

//        print_r($its);

        foreach ($its as $lab => $val) {
            if ($lab === 'settings') {
                continue;
            }

//            print_r($val);

            if ($ix % $maxblocksperrow === 0) {
                $fout .= '<div class="videogallery--navigation-outer--bigblock';
                if ($ix === 0) {
                    $fout .= ' active';
                }

                $fout .= '">';
            }


            $thumb = '';
            if(isset($val['thethumb'])){
                $thumb = $val['thethumb'];
            }
//            echo $thumb;
            if ($thumb == '') {
                if ($val['type'] == 'youtube') {
                    $thumb = "https://img.youtube.com/vi/" . $val['source'] . "/0.jpg";
                }
                if ($val['type'] == 'vimeo') {
                    $id = $val['source'];

                    $target_file = "https://vimeo.com/api/v2/video/$id.php";
                    $cache = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));

                    $apiresp = $cache;
                    $imga = unserialize($apiresp);

                    //        print_r($cache);


//                    print_r($imga[0]);

                    $thumb = $imga[0]['thumbnail_medium'];

                    if($this->mainoptions['vimeo_thumb_quality']=='high'){

                        $thumb = $imga[0]['thumbnail_large'];
                    }
                    if($this->mainoptions['vimeo_thumb_quality']=='low'){

                        $thumb = $imga[0]['thumbnail_small'];
                    }


                }
            }


            $fout .= '<span class="videogallery--navigation-outer--block">';
            if ($margs['skin'] == 'oasis') {
                $fout .= '
<span class="block-thumb" style="background-image: url(' . $thumb . ');"></span>';
            }
            if ($margs['skin'] == 'balne') {
                $fout .= '
<span class="image-con"><span class="hover-rect"></span><img width="100%" height="100%" class="fullwidth" src="' . $thumb . '" data-global-responsive-ratio="0.562"/></span>';
            }
            $fout .= '<span class="block-title">' . $val['title'] . '</span>';

            if ( (isset($its['settings']['enable_outernav_video_author']) &&  $its['settings']['enable_outernav_video_author']=='on') && isset($val['uploader']) && $val['uploader'] != '') {
                $fout .= '<span class="block-extra">' . __('by ', 'dzsvg') . '<strong>' . $val['uploader'] . '</strong>' . '</span>';
            }else{
                if ( (isset($its['settings']['enable_outernav_video_author']) &&  $its['settings']['enable_outernav_video_author']=='on') && isset($val['author_display_name']) && $val['author_display_name'] != '') {
                    $fout .= '<span class="block-extra">' . __('by ', 'dzsvg') . '<strong>' . $val['author_display_name'] . '</strong>' . '</span>';
                }
            }



//            print_r($its);
            if (  (isset($its['settings']['enable_outernav_video_date']) &&  $its['settings']['enable_outernav_video_date']=='on') && isset($val['upload_date']) && $val['upload_date']) {
                $fout .= '<span class="block-extra">' . __('on ', 'dzsvg') . '<strong>' . date("d-m-Y", strtotime($val['upload_date'])) . '</strong>' . '</span>';
            }

            $fout .= '</span>';


            if ($ix % $maxblocksperrow === ($maxblocksperrow - 1)) {
                $fout .= '</div>';
                $nr_pages++;
            }


            $ix++;

//                print_r($val);
        }

        // -- hier
        if ($ix % $maxblocksperrow <= ($maxblocksperrow - 1) && $ix % $maxblocksperrow > 0) {
            $fout .= '</div>';
            $nr_pages++;
        }
        $fout .= '</div></div>';

        if ($nr_pages > 1) {
            $fout .= '<div class="videogallery--navigation-outer--bullets-con">';
            for ($i = 0; $i < $nr_pages; ++$i) {
                $fout .= '<span class="navigation-outer--bullet';
                if ($i == 0) {
                    $fout .= ' active';
                }
                $fout .= '"></span>';
            }
            $fout .= '</div>';
        }


        $fout .= '</div>';

        return $fout;
    }

    function show_shortcode_links($atts, $content = null) {
        //[videogallerylinks ids="2,3" height="300" source="pathtomp4.mp4" type="normal"]
        global $post;
        //print_r($post);
        $fout = '';
        //$this->sliders_index++;

        $this->front_scripts();

        $args = array('ids' => '', 'width' => 400, 'height' => 300, 'source' => '', 'sourceogg' => '', 'type' => 'normal', 'autoplay' => 'on', 'design_skin' => 'skin_aurora', 'gallery_nav_type' => 'thumbs', 'menuitem_width' => '275', 'menuitem_height' => '75', 'menuitem_space' => '1', 'settings_ajax_extradivs' => '',);
        $args = array_merge($args, $atts);
        //print_r($args);
        if ($args['gallery_nav_type'] == 'scroller') {
            wp_enqueue_style('dzs.scroller', $this->thepath . 'assets/dzsscroller/scroller.css');
            wp_enqueue_script('dzs.scroller', $this->thepath . 'assets/dzsscroller/scroller.js');
        }
        $its = array();
        $ind_post = 0;
        $array_ids = explode(',', $args['ids']);
        //print_r($array_ids); print_r($args);
        foreach ($array_ids as $id) {
            $po = get_post($id);
            array_push($its, $po);
        }
        //print_r($its);
        $this->sliders_index++;

        $fout .= '<div class="videogallery-with-links">';
        //==start vg-con
        $fout .= '<div class="videogallery-con currGallery" style="width:' . $args['menuitem_width'] . 'px; height:' . $args['height'] . 'px; float:right; padding-top: 0; padding-bottom: 0;">';
        $fout .= '<div class="preloader"></div>'


        ;
        $fout .= '<div class="vg' . $this->sliders_index . ' videogallery skin_default" >';

        $i = 0;
        foreach ($its as $it) {

            $the_src = wp_get_attachment_image_src(get_post_thumbnail_id($it->ID), 'full');
            $fout .= '<div class="vplayer-tobe" data-videoTitle="' . $it->post_title . '" data-type="link" data-src="' . get_permalink($it->ID) . '">
<div class="menuDescription from-vg-with-links"><img src="' . $the_src[0] . '" class="imgblock"/>
<div class="the-title">' . $it->post_title . '</div><div class="paragraph">' . $it->post_excerpt . '</div></div>
</div>';
            if ($it->ID == $post->ID) {
                $ind_post = $i;
            }
            $i++;
        }

        $fout .= '</div>'; //==end vg
        $fout .= '</div>'; //==end vg-con
        $fout .= '';
        $fout .= '<div class="history-video-element" style="overflow: hidden;">
<div class="vphistory vplayer-tobe" data-videoTitle="" data-img="" data-type="' . $args['type'] . '" data-src="' . $args['source'] . '"';
        if ($args['sourceogg'] != '') {
            if (strpos($args['sourceogg'], '.webm') === false) {
                $fout .= ' data-sourceogg="' . $args['sourceogg'] . '"';
            } else {
                $fout .= ' data-sourcewebm="' . $args['sourceogg'] . '"';
            }
        }
        $fout .= '>
</div>
<div class="nest-script">
<div class="toexecute" style="display:none">
jQuery(document).ready(function($){
    var videoplayersettings = {
        autoplay : "' . $args['autoplay'] . '"
        ,controls_out_opacity : 0.9
        ,controls_normal_opacity : 0.9
        ,settings_hideControls : "off"
        ,design_skin: "skin_aurora"
	,settings_swfPath : "' . $this->thepath . 'preview.swf"
    };
    $(".vphistory").vPlayer(videoplayersettings);
})
</div>
</div>
</div>';

        $fout .= '<script>
jQuery(".toexecute").each(function(){
    var _t = jQuery(this);
    if(_t.hasClass("executed")==false){
        eval(_t.text());
        _t.addClass("executed");
    }
})
jQuery(document).ready(function($){
dzsvg_init(".vg' . $this->sliders_index . '", {
    totalWidth:"' . $args['menuitem_width'] . '"
    ,settings_mode:"normal"
    ,menuSpace:0
    ,randomise:"off"
    ,autoplay :"' . $args['autoplay'] . '"
    ,cueFirstVideo: "off"
    ,autoplayNext : "on"
    ,nav_type: "' . $args['gallery_nav_type'] . '"
    ,menuitem_width:"' . $args['menuitem_width'] . '"
    ,menuitem_height:"' . $args['menuitem_height'] . '"
    ,menuitem_space:"' . $args['menuitem_space'] . '"
    ,menu_position:"right"
    ,transition_type:"fade"
    ,design_skin: "skin_navtransparent"
    ,embedCode:""
    ,shareCode:""
    ,logo: ""
    ,design_shadow:"off"
    ,settings_disableVideo:"on"
    ,startItem: "' . $ind_post . '"
    ,settings_enableHistory: "on"
        ,settings_ajax_extraDivs : "' . $args['settings_ajax_extradivs'] . '"
});
});
</script>';
        $fout .= '</div>';

        return $fout;
    }


    function get_vpsettings($arg) {
        // @arg - the vpsetting gallery name


        $i = 0;
        $vpconfig_k = 0;
        $vpconfig_id = $arg;
        for ($i = 0; $i < count($this->mainvpconfigs); $i++) {
            if ((isset($vpconfig_id)) && ($vpconfig_id == $this->mainvpconfigs[$i]['settings']['id'])) {
                $vpconfig_k = $i;
            }
        }
        $vpsettings = $this->mainvpconfigs[$vpconfig_k];

        if (!isset($vpsettings['settings']) || $vpsettings['settings'] == '') {
            $vpsettings['settings'] = array();
        }

        $vpsettings['settings'] = array_merge($this->vpsettingsdefault, $vpsettings['settings']);

        unset($vpsettings['settings']['id']);
        //print_r($vpsettings);


        return $vpsettings;

    }



	function sanitize_to_gallery_item($che){

		$po_id = $che->ID;



		$che = (array) $che;



		foreach ($this->options_item_meta as $oim){



			if($oim['name']==='post_content' || $oim['name']==='the_post_content' || $oim['name']==='the_post_title'){
				continue;
			}

			$long_name = $oim['name'];

			$short_name = str_replace('dzsvg_meta_','',$oim['name']);



			if(isset($oim['default']) && $oim['default']){

				$che[$oim['name']]=$oim['default'];
				$che[$short_name] = $oim['default'];
//			        error_log(print_rr(get_post_meta($che['ID'], $long_name),true));



				$aux = get_post_meta($che['ID'], $long_name);
				if(get_post_meta($che['ID'], $long_name)){

					if(isset($aux[0])){
						$che[$long_name]=$aux[0];
						$che[$short_name] = $aux[0];
					}
				}
			}else{


				$che[$oim['name']]=get_post_meta($po_id,$oim['name'],true);
				$che[$short_name] = get_post_meta($po_id,$long_name,true);
			}
			

		}



		if(get_post_meta($po_id,'dzsvg_meta_replace_artistname',true)){

			$che['artistname'] = get_post_meta($po_id,'dzsvg_meta_replace_artistname',true);
		}

		if(get_post_meta($po_id,'dzsvg_meta_replace_menu_artistname',true)){

			$che['menu_artistname'] = get_post_meta($po_id,'dzsvg_meta_replace_menu_artistname',true);
		}

		if(get_post_meta($po_id,'dzsvg_meta_replace_menu_songname',true)){

			$che['menu_songname'] = get_post_meta($po_id,'dzsvg_meta_replace_menu_songname',true);
		}



		$che['sourceogg'] = '';



		$che['playfrom'] = '0';

		if(get_post_meta($po_id,'dzsvg_meta_item_thumb',true)){
			$che['thumb'] = get_post_meta($po_id,'dzsvg_meta_item_thumb',true);
        }else{

			$che['thumb'] = get_post_meta($po_id,'dzsvg_meta_thumb',true);
        }

		$che['type'] = get_post_meta($po_id,'dzsvg_meta_type',true);
		$che['playerid'] = $po_id;





		$che['title'] = $che['post_title'];
		$che['description'] = $che['post_content'];
		$che['source'] = get_post_meta($po_id,'dzsvg_meta_featured_media',true);
		$che['type'] = get_post_meta($po_id,'dzsvg_meta_item_type',true);
		$che['loop'] = get_post_meta($po_id,'dzsvg_meta_loop',true);
		$che['is_360'] = get_post_meta($po_id,'dzsvg_meta_is_360',true);
		$che['adarray'] = get_post_meta($po_id,'dzsvg_meta_ad_array',true);
		$che['audioimage'] = get_post_meta($po_id,'dzsvg_meta_audioimage',true);
		$che['menuDescription'] = $che['menu_description'];
		$che['thumbnail'] = $this->get_post_thumb_src($po_id);






		return $che;
	}


	function sanitize_description_anchors_to_html($arg) {

        $fout = '';


        $fout = $arg;
//		echo '$arg - '.$arg;

//		$fout = preg_replace("/g/m", 'b', $arg);
		$fout = preg_replace("/ (http[s]*:\/\/.*?)( |$)/m", ' <a href="$0" target="_blank">$0</a>', $arg);

//		echo '$fout - '.$fout.'|';


        return $fout;
	}
	function show_shortcode($atts) {
        global $post;
        $fout = '';
        $iout = ''; //items parse

//        echo 'show_shortcode()';


        if ($this->mainoptions['debug_mode'] == 'on') {


            $fout .= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('memory usage - ', 'dzsvg') . '</div>
<div class="toggle-content">';
            $fout .= 'memory usage - ' . memory_get_usage() . "\n <br>memory limit - " . ini_get('memory_limit');
            $fout .= '</div></div>';

        }

        $margs = array(
            'id' => 'default',
            'slider' => 'default'
        , 'db' => ''
        , 'call_from' => 'default'
        , 'category' => ''
        , 'fullscreen' => 'off'
        , 'output_container' => 'on'
        , 'call_script' => 'on'
            , 'its' => ''  // -- force $its array
            , 'settings_separation_mode' => 'normal'  // === normal ( no pagination ) or pages or scroll or button
        , 'settings_separation_pages_number' => '5'//=== the number of items per 'page'
        , 'settings_separation_paged' => '0'//=== the page number
        , 'return_mode' => 'normal' // -- "normal" returns the whole gallery, "items" returns the items array, "parsed items" returns the parsed items ( for pagination for example )
        );

        if ($atts == '') {
            $atts = array();
        }


        $margs = array_merge($margs, $atts);


//        echo 'margs - '; print_rr($margs);

        if ($margs['slider'] && $margs['slider'] != 'default') {

            $margs['id'] = $margs['slider'];
        }

        if (isset($_GET['dzsvg_settings_separation_paged'])) {
            $margs['settings_separation_paged'] = $_GET['dzsvg_settings_separation_paged'];
        }

        $extra_galleries = array();

        //===setting up the db
        $currDb = '';
        if (isset($margs['db']) && $margs['db'] != '') {
            $this->currDb = $margs['db'];
            $currDb = $this->currDb;
        }
        $this->dbs = get_option($this->dbdbsname);

        //echo 'ceva'; print_r($this->dbs);
        if ($currDb != 'main' && $currDb != '') {
            $dbitemsname = $this->dbitemsname . '-' . $currDb;
            $this->mainitems = get_option($dbitemsname);
        }
        //===setting up the db END
//        print_r($margs) ; echo $this->dbitemsname; print_r($this->mainitems);



//        echo 'call show_shortcode() - ';
//        print_r($margs);
//        echo "\n\n";

        if ($this->mainitems == '') {
            return;
        }

        $this->front_scripts();


        if ($margs['return_mode'] == 'normal') {
            $this->sliders_index++;
        }


        $i = 0;
        $k = 0;
        $id = 'default';
        if (isset($margs['id'])) {
            $id = $margs['id'];
        }


		$its = array();
        //---- extra galleries code


		$selected_term_id  = '';

		$term_meta ='';
		
		// -- if we have its forced on us we don't need to search them again


        if($margs['its'] && is_array($margs['its'])){
            $its = $margs['its'];
        }else{

	        if($this->mainoptions['playlists_mode']=='legacy') {
		        if ( strpos( $id, ',' ) !== false ) {
			        $auxa = explode( ",", $id );
			        $id   = $auxa[0];

			        unset( $auxa[0] );
			        $extra_galleries = $auxa;
//            print_r($auxa);
		        }

		        //echo 'ceva' . $id;
		        for ( $i = 0; $i < count( $this->mainitems ); $i ++ ) {
			        if ( ( isset( $id ) ) && isset( $this->mainitems[ $i ]['settings'] ) && ( $id == $this->mainitems[ $i ]['settings']['id'] ) ) {
				        $k = $i;
			        }
		        }

		        $its = $this->mainitems[ $k ];
	        }



	        // -- playlists mode normal
	        if($this->mainoptions['playlists_mode']=='normal'){

		        $tax = $this->taxname_sliders;

		        $reference_term = get_term_by( 'slug', $margs['id'], $tax );

//	        print_rr($reference_term);



                if($reference_term){

	                $reference_term_name = $reference_term->name;
	                $reference_term_slug = $reference_term->slug;
	                $selected_term_id    = $reference_term->term_id;




	                $term_meta = get_option("taxonomy_$selected_term_id");






	                if(!isset($term_meta['feed_mode']) || $term_meta['feed_mode']=='' || $term_meta['feed_mode']=='manual') {



		                if ( $selected_term_id ) {

			                $args = array(
				                'post_type'   => 'dzsvideo',
				                'numberposts' => - 1,
				                'posts_per_page' => - 1,
				                //                'meta_key' => 'dzsvg_meta_order_'.$selected_term,

				                'orderby'    => 'meta_value_num',
				                'order'      => 'ASC',
				                'meta_query' => array(
					                'relation' => 'OR',
					                array(
						                'key'     => 'dzsvg_meta_order_' . $selected_term_id,
						                //                        'value' => '',
						                'compare' => 'EXISTS',
					                ),
					                array(
						                'key'     => 'dzsvg_meta_order_' . $selected_term_id,
						                //                        'value' => '',
						                'compare' => 'NOT EXISTS'
					                )
				                ),
				                'tax_query'  => array(
					                array(
						                'taxonomy' => $tax,
						                'field'    => 'id',
						                'terms'    => $selected_term_id // Where term_id of Term 1 is "1".
					                )
				                ),
			                );

			                $my_query = new WP_Query( $args );

//            print_rr($my_query);


//            print_r($my_query->posts);

			                foreach ( $my_query->posts as $po ) {

//                print_r($po);


				                $por = $this->sanitize_to_gallery_item( $po );

				                array_push( $its, $por );

//			        print_rr($po);
			                }
		                }
	                }else{

		                include_once "class_parts/parse_yt_vimeo.php";











		                if($term_meta['feed_mode']=='youtube'){

			                $maxlen = 50;

			                if(isset($term_meta['youtube_maxlen']) && $term_meta['youtube_maxlen']){
				                $maxlen = $term_meta['youtube_maxlen'];
			                }
			                $args = array(
				                'type'                   => 'detect',
				                'max_videos'                   => $maxlen,
				                'enable_outernav_video_author' => 'off',
			                );

			                if ( isset( $its['settings']['enable_outernav_video_date'] ) ) {
				                $args['enable_outernav_video_date'] = $its['settings']['enable_outernav_video_date'];
			                }


//		        print_rr($term_meta);

			                $its2 = dzsvg_parse_yt( $term_meta['youtube_source'], $args, $fout );

			                $its = array_merge( $its, $its2 );
		                }
		                if($term_meta['feed_mode']=='vimeo'){

			                $vimeo_sort = 'default';

			                if(isset($term_meta['vimeo_sort'])){
				                $vimeo_sort = $term_meta['vimeo_sort'];
			                }



			                $maxlen = 50;

			                if(isset($term_meta['vimeo_maxlen']) && $term_meta['vimeo_maxlen']){
				                $maxlen = $term_meta['vimeo_maxlen'];
			                }

			                $args = array(
				                'type'                   => 'detect',
				                'max_videos'                   => $maxlen,
				                'enable_outernav_video_author' => 'off',
				                'vimeo_sort' => $vimeo_sort,
			                );

			                if ( isset( $its['settings']['enable_outernav_video_date'] ) ) {
				                $args['enable_outernav_video_date'] = $its['settings']['enable_outernav_video_date'];
			                }


//		        print_rr($term_meta);

			                $its2 = dzsvg_parse_vimeo( $term_meta['vimeo_source'], $args, $fout );

			                $its = array_merge( $its, $its2 );
//			        print_rr($term_meta);
//			        print_rr($its);
		                }
		                if($term_meta['feed_mode']=='facebook'){




			                $args = array(
				                'type'                   => 'facebook',
				                'max_videos'                   => '50',
				                'enable_outernav_video_author' => 'off',
				                'facebook_source' => $term_meta['facebook_source'],
			                );

			                if ( isset( $its['settings']['enable_outernav_video_date'] ) ) {
				                $args['enable_outernav_video_date'] = $its['settings']['enable_outernav_video_date'];
			                }


//				        echo '$term_meta - '; print_rr($term_meta);

			                $its2 = dzsvg_parse_facebook( $term_meta['facebook_source'], $args, $fout );

			                $its = array_merge( $its, $its2 );


		                }
	                }

                }



	        }else{

		        if (isset($margs['id'])) {
			        $id = $margs['id'];
		        }

		        //echo 'ceva' . $id;
		        for ($i = 0; $i < count($this->mainitems); $i++) {
			        if ((isset($id)) && ($id == $this->mainitems[$i]['settings']['id'])) {
				        $k = $i;
			        }
		        }
		        $its = $this->mainitems[$k];
	        }
        }











		$its_settings_default = array(
			'galleryskin'=>'skin-wave',
			'vpconfig'=>'default',
			'bgcolor'=>'transparent',
			'width'=>'',
			'height'=>'300',
			'autoplay'=>'',
			'autoplaynext'=>'on',
			'autoplay_next'=>'',
			'menuposition'=>'bottom',
			'displaymode'=>'normal',
			'feedfrom'=>'normal',
		);
		if($this->mainoptions['playlists_mode']=='normal'){

			$its_settings_default['id']=$selected_term_id;

		}

		if(isset($its['settings']) && is_array($its['settings'])){

			$its['settings'] = array_merge($its_settings_default, $its['settings']);
        }else{
			$its['settings'] = array_merge($its_settings_default, array());
        }



		if($this->mainoptions['playlists_mode']=='normal'){






//		    print_rr($term_meta);



            if($term_meta && is_array($term_meta)){

	            foreach ($term_meta as $lab => $val){
		            if($lab=='autoplay_next'){

			            $lab = 'autoplaynext';
		            }
		            $its['settings'][$lab]=$val;

	            }
            }




		}

//		print_rr($its);


//        print_r($this->mainitems);


        $vpsettings = array();


        $vpconfig_name = 'default';

        if(isset($its['settings']) && isset($its['settings']['vpconfig'])){
	        $vpconfig_name = $its['settings']['vpconfig'];
        }


        $vpsettings = $this->get_vpsettings($vpconfig_name);


        if (is_array($its['settings']) == false) {
            $its['settings'] = array();
        }


        $its['settings'] = array_merge($its['settings'], $vpsettings['settings']);
        //print_r($its);

//        echo 'sliders_index - '.$this->sliders_index;

        if ($post && $this->sliders_index == 1) {
            if (get_post_meta($post->ID, 'dzsvg_preview', true) == 'on') {

                include_once ("class_parts/preview_page_customizer.php");
            }
        }//----dzsvg preview END


        if (isset($its['settings']['nav_type']) && $its['settings']['nav_type'] == 'scroller') {
            wp_enqueue_style('dzs.scroller', $this->thepath . 'assets/dzsscroller/scroller.css');
            wp_enqueue_script('dzs.scroller', $this->thepath . 'assets/dzsscroller/scroller.js');
        }

        $fullscreenclass = '';
        $theclass = 'videogallery';
        //echo $id;
        //$fout.='<div class="videogallery-con" style="width:'.$w.'; height:'.$h.'; opacity:0;">';
        if ($margs['category']) {
//            $its['settings']['autoplay'] = 'off';
        }

//        print_r($its);

        $user_feed = '';
        $yt_playlist_feed = '';


        $skin_html5vg = 'skin-pro';
        if (isset($its['settings']['skin_html5vg']) == false || $its['settings']['skin_html5vg'] === 'skin-custom') {
            $skin_html5vg = 'skin-pro';
        } else {
            $skin_html5vg = $its['settings']['skin_html5vg'];
        }

        $skin_html5vg = str_replace('_', '-', $skin_html5vg);


        $wmode = 'opaque';
        if (isset($its['settings']['windowmode'])) {
            $wmode = $its['settings']['windowmode'];
        }


        $targetfeed = '';
        $target_file = '';
        if (($its['settings']['feedfrom'] == 'ytuserchannel') && $its['settings']['youtubefeed_user'] != '') {
            $user_feed = $its['settings']['youtubefeed_user'];
            $targetfeed = $its['settings']['youtubefeed_user'];
        }
        if (($its['settings']['feedfrom'] == 'ytplaylist') && $its['settings']['ytplaylist_source'] != '') {
            $yt_playlist_feed = $its['settings']['ytplaylist_source'];
            $targetfeed = $its['settings']['ytplaylist_source'];

            if (substr($yt_playlist_feed, 0, 2) == "PL") {
                $yt_playlist_feed = substr($yt_playlist_feed, 2);
            }
            $user_feed = '';
        }


        $vimeo_maxvideos = 25;

        if (isset($its['settings']['vimeo_maxvideos']) == false || $its['settings']['vimeo_maxvideos'] == '') {
            $its['settings']['vimeo_maxvideos'] = 25;
        }
        $vimeo_maxvideos = $its['settings']['vimeo_maxvideos'];

        if ($its['settings']['vimeo_maxvideos'] == 'all') {
            $vimeo_maxvideos = 500;
        }


//        echo 'feedfrom - '.$its['settings']['feedfrom'];






		if($this->mainoptions['playlists_mode']!='normal') {
			// -----
			// -- ---- ---- YouTube user channel feed ---
			// -----
			if ( ( $its['settings']['feedfrom'] == 'ytuserchannel' ) && $its['settings']['youtubefeed_user'] != '' ) {


				include_once "class_parts/parse_yt_vimeo.php";


				$len = count( $its ) - 1;
				for ( $i = 0; $i < $len; $i ++ ) {
					unset( $its[ $i ] );
				}

				$args = array(
					'type'                         => 'user_channel',
					'subtype'                      => 'user_channel',
					'max_videos'                   => $its['settings']['youtubefeed_maxvideos'],
					'enable_outernav_video_author' => $its['settings']['enable_outernav_video_author'],
				);

				if ( isset( $its['settings']['enable_outernav_video_date'] ) ) {
					$args['enable_outernav_video_date'] = $its['settings']['enable_outernav_video_date'];
				}

				if ( intval( $its['settings']['maxlen_desc'] ) && intval( $its['settings']['maxlen_desc'] ) > 150 ) {

					$args['get_full_description'] = 'on';
				}

				$its2 = dzsvg_parse_yt( $its['settings']['youtubefeed_user'], $args, $fout );

				$its = array_merge( $its, $its2 );

//            print_r($its2);
			}
			// -- END YT USER CHANNEL


			//==============START youtube playlist
			if ( ( $its['settings']['feedfrom'] == 'ytplaylist' ) && $its['settings']['ytplaylist_source'] != '' ) {


				$len = count( $its ) - 1;
				for ( $i = 0; $i < $len; $i ++ ) {
					unset( $its[ $i ] );
				}


				$targetfeed = $its['settings']['ytplaylist_source'];
				$targetfeed = str_replace( 'https://www.youtube.com/playlist?list=', '', $targetfeed );


				$cacher = get_option( 'dzsvg_cache_ytplaylist' );

				$cached          = false;
				$found_for_cache = false;


				if ( $cacher == false || is_array( $cacher ) == false || $this->mainoptions['disable_api_caching'] == 'on' ) {
					$cached = false;
				} else {

//                print_r($cacher);

					if ( $this->mainoptions['debug_mode'] == 'on' ) {
						if ( isset( $_GET['show_cacher'] ) && $_GET['show_cacher'] == 'on' ) {
							print_r( $cacher );
						};
					}


					$ik = - 1;
					$i  = 0;
					for ( $i = 0; $i < count( $cacher ); $i ++ ) {
						if ( $cacher[ $i ]['id'] == $targetfeed ) {
							if ( isset( $cacher[ $i ]['maxlen'] ) && $cacher[ $i ]['maxlen'] == $its['settings']['youtubefeed_maxvideos'] ) {
								if ( $_SERVER['REQUEST_TIME'] - $cacher[ $i ]['time'] < 3600 ) {
									$ik = $i;

//                                echo 'yabebe';
									$cached = true;
									break;
								}
							}

						}
					}


					if ( $cached ) {

						foreach ( $cacher[ $ik ]['items'] as $lab => $item ) {
							if ( $lab === 'settings' ) {
								continue;
							}

							$its[ $lab ] = $item;

//                        print_r($item);
//                        echo 'from cache';
						}

					}
				}


				if ( $this->mainoptions['debug_mode'] == 'on' ) {


					$fout .= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __( 'youtube playlist statistics', 'dzsvg' ) . '</div>
<div class="toggle-content">';
					$fout .= 'memory usage - ' . memory_get_usage() . "\n <br>memory limit - " . ini_get( 'memory_limit' );
					$fout .= '<br>';
					$fout .= 'feed - to be determied';
					$fout .= '</div></div>';
				}


				if ( ! $cached ) {
					if ( isset( $its['settings']['youtubefeed_maxvideos'] ) == false || $its['settings']['youtubefeed_maxvideos'] == '' ) {
						$its['settings']['youtubefeed_maxvideos'] = 50;
					}
					$yf_maxi = $its['settings']['youtubefeed_maxvideos'];

					if ( $its['settings']['youtubefeed_maxvideos'] == 'all' ) {
						$yf_maxi = 50;
					}


					$breaker = 0;

					$i_for_its     = 0;
					$nextPageToken = 'none';

					while ( $breaker < 10 || $nextPageToken !== '' ) {


						$str_nextPageToken = '';

						if ( $nextPageToken && $nextPageToken != 'none' ) {
							$str_nextPageToken = '&pageToken=' . $nextPageToken;
						}

//                echo '$breaker is '.$breaker;

						if ( $this->mainoptions['youtube_api_key'] == '' ) {
							$this->mainoptions['youtube_api_key'] = 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4';
						}


						$target_file = 'https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=' . $targetfeed . '&key=' . $this->mainoptions['youtube_api_key'] . '' . $str_nextPageToken . '&maxResults=' . $yf_maxi;

//                    echo $target_file;


						if ( $this->mainoptions['debug_mode'] == 'on' ) {


							$fout .= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __( 'youtube playlist target file', 'dzsvg' ) . '</div>
<div class="toggle-content">';
							$fout .= 'target file ' . $target_file;
							$fout .= '</div></div>';
						}
//                    echo 'target file - '.$target_file;


						$ida = DZSHelpers::get_contents( $target_file, array( 'force_file_get_contents' => $this->mainoptions['force_file_get_contents'] ) );

//            echo 'ceva'.$ida;

						if ( $ida ) {

							$obj = json_decode( $ida );


							if ( $this->mainoptions['debug_mode'] == 'on' ) {
//                            echo 'mode yt playlist - ida is ' . $ida;
//
//
//                            if ($this->mainoptions['debug_mode'] == 'on') {
//                                if (isset($_GET['show_idar']) && $_GET['show_idar'] == 'on') {
//                                    print_r($obj);
//                                };
//                            }
							}


							if ( $this->mainoptions['debug_mode'] == 'on' ) {


								$fout .= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __( 'youtube playlist target obj', 'dzsvg' ) . '</div>
<div class="toggle-content">';
								$fout .= 'json obj - ' . print_rr( $obj, array(
										'echo'        => false,
										'encode_html' => true
									) );
								$fout .= '</div></div>';
							}

							if ( $obj && is_object( $obj ) ) {
//                            print_r($obj);


								// -- still ytplaylist

//                                        print_r($obj);

								if ( isset( $obj->items[0]->snippet->resourceId->videoId ) ) {


									foreach ( $obj->items as $ytitem ) {
//                                print_r($ytitem);


										if ( $this->mainoptions['debug_mode'] == 'on' ) {
											if ( isset( $_GET['show_item'] ) && $_GET['show_item'] == 'on' ) {
//                                            print_r($ytitem);
											};
										}
										if ( isset( $ytitem->snippet->resourceId->videoId ) == false ) {
											echo 'this does not have id ? ';
											continue;
										}
										$its[ $i_for_its ]['source'] = $ytitem->snippet->resourceId->videoId;

										if ( $ytitem->snippet->thumbnails ) {

											$its[ $i_for_its ]['thethumb'] = $ytitem->snippet->thumbnails->medium->url;
										}
										$its[ $i_for_its ]['type'] = "youtube";

										$aux                        = $ytitem->snippet->title;
										$lb                         = array(
											'"',
											"\r\n",
											"\n",
											"\r",
											"&",
											"-",
											"`",
											'???',
											"'",
											'-'
										);
										$aux                        = str_replace( $lb, ' ', $aux );
										$its[ $i_for_its ]['title'] = $aux;

										$aux = $ytitem->snippet->description;
										$lb  = array( "\r\n", "\n", "\r" );
										$aux = str_replace( $lb, '<br>', $aux );
										$lb  = array( '"' );
										$aux = str_replace( $lb, '&quot;', $aux );
										$lb  = array( "'" );
										$aux = str_replace( $lb, '&#39;', $aux );


										$auxcontent = '<p>' . str_replace( array(
												"\r\n",
												"\n",
												"\r"
											), '</p><p>', $aux ) . '</p>';

										$its[ $i_for_its ]['description']     = $auxcontent;
										$its[ $i_for_its ]['menuDescription'] = $auxcontent;

//                    print_r($its['settings']);
										if ( $its['settings']['enable_outernav_video_author'] == 'on' ) {
//                        echo 'ceva';
											$its[ $i_for_its ]['uploader'] = $ytitem->snippet->channelTitle;
										}

										$i_for_its ++;


									}

									$found_for_cache = true;


								} else {

									array_push( $this->arr_api_errors, '<div class="dzsvg-error">' . __( 'No youtube playlist videos to be found - maybe API key not set ? This is the feed - ' . $target_file ) . '</div>' );

									try {

										if ( isset( $obj->error ) ) {
											if ( $obj->error->errors[0] ) {


												array_push( $this->arr_api_errors, '<div class="dzsvg-error">' . $obj->error->errors[0]->message . '</div>' );
												if ( strpos( $obj->error->errors[0]->message, 'per-IP or per-Referer restriction' ) !== false ) {

													array_push( $this->arr_api_errors, '<div class="dzsvg-error">' . __( "Suggestion - go to Video Gallery > Settings and enter your YouTube API Key" ) . '</div>' );
												} else {

												}
											}
										}

//                                    $arr = json_decode(DZSHelpers::($target_file));
//
//                                    print_r($arr);
									} catch ( Exception $err ) {

									}
								}

							}


							if ( $its['settings']['youtubefeed_maxvideos'] === 'all' ) {

								if ( isset( $obj->nextPageToken ) && $obj->nextPageToken ) {
									$nextPageToken = $obj->nextPageToken;
								} else {

									$nextPageToken = '';
									break;
								}

							} else {
								$nextPageToken = '';
								break;
							}


						}
						$breaker ++;
					}


					if ( $found_for_cache ) {

						$sw34   = false;
						$auxa34 = array(
							'id'     => $targetfeed,
							'items'  => $its,
							'time'   => $_SERVER['REQUEST_TIME'],
							'maxlen' => $its['settings']['youtubefeed_maxvideos']

						);

						if ( ! is_array( $cacher ) ) {
							$cacher = array();
						} else {


							foreach ( $cacher as $lab => $cach ) {
								if ( $cach['id'] == $targetfeed ) {
									$sw34 = true;

									$cacher[ $lab ] = $auxa34;

									update_option( 'dzsvg_cache_ytplaylist', $cacher );

//                                        print_r($cacher);
									break;
								}
							}


						}

						if ( $sw34 == false ) {

							array_push( $cacher, $auxa34 );

//                                            print_r($cacher);

							update_option( 'dzsvg_cache_ytplaylist', $cacher );
						}
					}
				}


			}
			// -- END youtube playlist
			//
			//


			// -- youtube keywords
			if ( ( $its['settings']['feedfrom'] == 'ytkeywords' ) && $its['settings']['ytkeywords_source'] != '' ) {


				include_once "class_parts/parse_yt_vimeo.php";


				$len = count( $its ) - 1;
				for ( $i = 0; $i < $len; $i ++ ) {
					unset( $its[ $i ] );
				}







				$args = array(
					'type'                         => 'user_channel',
					'subtype'                      => 'search',

					'max_videos'                   => $its['settings']['youtubefeed_maxvideos'],
					'enable_outernav_video_author' => $its['settings']['enable_outernav_video_author'],
				);

				if ( isset( $its['settings']['enable_outernav_video_date'] ) ) {
					$args['enable_outernav_video_date'] = $its['settings']['enable_outernav_video_date'];
				}

				if ( intval( $its['settings']['maxlen_desc'] ) && intval( $its['settings']['maxlen_desc'] ) > 150 ) {

					$args['get_full_description'] = 'on';
				}

				$its2 = dzsvg_parse_yt( $its['settings']['ytkeywords_source'], $args, $fout );

				$its = array_merge( $its, $its2 );


			}
			//=======END youtube keywords
			//
			//


			if ( $this->mainoptions['debug_mode'] == 'on' ) {
				wp_enqueue_style( 'dzstoggle', $this->base_url . 'dzstoggle/dzstoggle.css' );
				wp_enqueue_script( 'dzstoggle', $this->base_url . 'dzstoggle/dzstoggle.js' );
			}
			// -- start vimeo user channel //https://vimeo.com/api/v2/blakewhitman/videos.json
			if ( isset( $its['settings']['feedfrom'] ) && ( $its['settings']['feedfrom'] == 'vmuserchannel' ) && $its['settings']['vimeofeed_user'] ) {


				include_once "class_parts/parse_yt_vimeo.php";


				$len = count( $its ) - 1;
				for ( $i = 0; $i < $len; $i ++ ) {
					unset( $its[ $i ] );
				}

				$args = array(
					'type'       => 'user',
					'max_videos' => $its['settings']['vimeo_maxvideos'],
				);

				if ( isset( $its['settings']['vimeo_sort'] ) ) {
					$args['vimeo_sort'] = $its['settings']['vimeo_sort'];
				}
				if ( intval( $its['settings']['maxlen_desc'] ) && intval( $its['settings']['maxlen_desc'] ) > 150 ) {

					$args['get_full_description'] = 'on';
				}

				$its2 = dzsvg_parse_vimeo( $its['settings']['vimeofeed_user'], $args, $fout );

				$its = array_merge( $its, $its2 );


			}

//        print_rr($its);

			// END vmchanneluser


//        print_r($its);


			//------start vmchannel //https://vimeo.com/api/v2/blakewhitman/videos.json
			// -- VIMEO CHANNEL
			if ( ( $its['settings']['feedfrom'] == 'vmchannel' ) && $its['settings']['vimeofeed_channel'] != '' ) {


				include_once "class_parts/parse_yt_vimeo.php";


				$len = count( $its ) - 1;
				for ( $i = 0; $i < $len; $i ++ ) {
					unset( $its[ $i ] );
				}

				$args = array(
					'type'       => 'channel',
					'max_videos' => $its['settings']['vimeo_maxvideos'],
				);

				if ( isset( $its['settings']['vimeo_sort'] ) ) {
					$args['vimeo_sort'] = $its['settings']['vimeo_sort'];
				}
				if ( intval( $its['settings']['maxlen_desc'] ) && intval( $its['settings']['maxlen_desc'] ) > 150 ) {

					$args['get_full_description'] = 'on';
				}

				$its2 = dzsvg_parse_vimeo( $its['settings']['vimeofeed_channel'], $args, $fout );

				$its = array_merge( $its, $its2 );
			}
			// -- end vmchannel


			//------start vmalbum //https://vimeo.com/api/v2/blakewhitman/videos.json
			if ( ( $its['settings']['feedfrom'] == 'vmalbum' ) && $its['settings']['vimeofeed_vmalbum'] != '' ) {


				include_once "class_parts/parse_yt_vimeo.php";


				$len = count( $its ) - 1;
				for ( $i = 0; $i < $len; $i ++ ) {
					unset( $its[ $i ] );
				}

				$args = array(
					'type'       => 'album',
					'max_videos' => $its['settings']['vimeo_maxvideos'],
				);

				if ( isset( $its['settings']['vimeo_sort'] ) ) {
					$args['vimeo_sort'] = $its['settings']['vimeo_sort'];
				}
				if ( intval( $its['settings']['maxlen_desc'] ) && intval( $its['settings']['maxlen_desc'] ) > 150 ) {

					$args['get_full_description'] = 'on';
				}








				$its2 = dzsvg_parse_vimeo( $its['settings']['vimeofeed_vmalbum'], $args, $fout );

				$its = array_merge( $its, $its2 );
			}
			//------start facebook //https://vimeo.com/api/v2/blakewhitman/videos.json
			if ( ( $its['settings']['feedfrom'] == 'facebook' ) && $its['settings']['facebook_url'] != '' ) {


				include_once "class_parts/parse_yt_vimeo.php";


				$len = count( $its ) - 1;
				for ( $i = 0; $i < $len; $i ++ ) {
					unset( $its[ $i ] );
				}

				$args = array(
					'type'       => 'album',
					'max_videos' => $its['settings']['vimeo_maxvideos'],
				);

				if ( isset( $its['settings']['vimeo_sort'] ) ) {
					$args['vimeo_sort'] = $its['settings']['vimeo_sort'];
				}
				if ( intval( $its['settings']['maxlen_desc'] ) && intval( $its['settings']['maxlen_desc'] ) > 150 ) {

					$args['get_full_description'] = 'on';
				}








				$its2 = dzsvg_parse_facebook( $its['settings']['facebook_url'], $args, $fout );

				$its = array_merge( $its, $its2 );
			}
		}

		foreach ($its as $lab => $it){

            // -- lets parse links

//            print_rr($it);
            if(isset($it['description'])){

	            $its[$lab]['description']=$this->sanitize_description_anchors_to_html($it['description']);
            }
			if(isset($it['menuDescription'])) {
				$its[$lab]['menuDescription'] = $this->sanitize_description_anchors_to_html( $it['menuDescription'] );
			}


//			print_rr($it);
//			echo '<br>___';
        }




        if (isset($its['settings']['randomize']) && $its['settings']['randomize'] == 'on' && is_array($its)) {

            $backup_its = $its;
//print_r($its); $rand_keys = array_rand($its, count($its)); print_r($rand_keys);
            shuffle($its);
//print_r($its);print_r($backup_its);

            for ($i = 0; $i < count($its); $i++) {
                if (isset($its[$i]['feedfrom'])) {
                    //print_r($it);

                    unset($its[$i]);
                }
            }
            $its['settings'] = $backup_its['settings'];
            $its = array_reverse($its);
//print_r($its);
        }

        if (isset($its['settings']['order']) && $its['settings']['order'] == 'DESC') {
            $its = array_reverse($its);
        }

        // --- items settled

        if ($margs['return_mode'] == 'items') {
            return $its;
        }


        foreach ($extra_galleries as $extragal) {
            $args = array(
                    'id' =>$extragal,
             'return_mode' => 'items',
             'call_from' => 'extra_galleries',

            );

//            print_r($this->show_shortcode($args));


            foreach ($this->show_shortcode($args) as $lab => $it3) {
                if ($lab === 'settings') {
                    continue;
                }
                array_push($its, $it3);
            }
//            $fout.=$this->show_shortcode($args);
//            print_r($its);
        }


        // --- if display mode is wall, it cannot be shown on a laptop, and height needs to be set to auto
        if ($its['settings']['displaymode'] == 'wall' || $its['settings']['displaymode'] == 'videowall' || ($its['settings']['displaymode'] == 'normal' && (isset($its['settings']['nav_type']) && $its['settings']['nav_type']=='outer' ) ) ) {
            $its['settings']['laptopskin'] = 'off';
            $its['settings']['height'] = 'auto';
        }

        // ------- some sanitizing
        $tw = $its['settings']['width'];
        $th = $its['settings']['height'];

        if($th==''){
            $th = 'auto';
        }

        $etw = $tw;
        $eth = $th;


        if (strpos($tw, "%") === false) {
            $tw = $tw . 'px';
        }
        if (strpos($th, "%") === false && $th != 'auto') {
            $th = $th . 'px';
        }

        if (isset($its['settings']['facebooklink']) && strpos($its['settings']['facebooklink'], "{currurl}") !== false) {
            $its['settings']['facebooklink'] = str_replace('{currurl}', urlencode(dzs_curr_url()), $its['settings']['facebooklink']);
        }


        if ($margs['fullscreen'] == 'on') {
            $tw = '100%';
            $th = '100%';
        }




//        print_r($margs);
//        print_r($its);



        $this->call_index--;

        if($this->call_index<0){
            return $fout;
        }

//        echo 'ceva'; echo $its['settings']['skin_html5vg'];
        if (isset($its['settings']['skin_html5vg'] ) && $its['settings']['skin_html5vg'] == 'skin-custom') {
            $fout .= '<style>';
            $fout .= '.vg' . $this->sliders_index . '.videogallery { background:' . $this->mainoptions_dc['background'] . ';} ';
            $fout .= '.vg' . $this->sliders_index . '.videogallery .navigationThumb{ background: ' . $this->mainoptions_dc['thumbs_bg'] . '; } ';
            $fout .= '.vg' . $this->sliders_index . '.videogallery .navigationThumb.active,.vg' . $this->sliders_index . '.videogallery .navigationThumb:hover{ background-color: ' . $this->mainoptions_dc['thumbs_active_bg'] . '; } ';
            $fout .= '.vg' . $this->sliders_index . '.videogallery .navigationThumb{ color: ' . $this->mainoptions_dc['thumbs_text_color'] . '; } .vg' . $this->sliders_index . '.videogallery .navigationThumb .the-title{ color: ' . $this->mainoptions_dc['thumbs_text_color'] . '; } ';

            if ($this->mainoptions_dc['thumbnail_image_width'] != '') {
                $fout .= '.vg' . $this->sliders_index . '.videogallery .imgblock{ width: ' . $this->mainoptions_dc['thumbnail_image_width'] . 'px; } ';
            }

            if ($this->mainoptions_dc['thumbnail_image_height'] != '') {
                $fout .= '.vg' . $this->sliders_index . '.videogallery .imgblock{ height: ' . $this->mainoptions_dc['thumbnail_image_height'] . 'px; } ';
            }


            $fout .= '</style>';
        }


        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom'  || $vpsettings['settings']['skin_html5vp'] == 'skin_custom_aurora' ||  ( isset($vpsettings['settings']['use_custom_colors']) && $vpsettings['settings']['use_custom_colors']=='on' )) {
            $fout.=$this->style_player('.vg' . $this->sliders_index,$vpsettings);
        }




        if($margs['output_container']=='on'){


	        $fout .= '<div class="gallery-precon gp' . $this->sliders_index . '';
	        if ($margs['fullscreen'] == 'on') {
		        $fout .= ' gallery-is-fullscreen';
	        }


	        $str_h = 'auto';
	        if ($margs['fullscreen'] == 'on') {
		        $str_h = '100%';
	        }
	        if (isset($its['settings']['forcevideoheight']) && $its['settings']['forcevideoheight']) {
//			$str_h = dzs_sanitize_to_css_size($its['settings']['forcevideoheight']);
	        }

	        $fout .= '" style="width:' . $tw . ';height:' . $str_h . ';';





	        if(isset($its['settings']['max_width']) && $its['settings']['max_width']){

		        $fout.=' max-width: '.$its['settings']['max_width'].'px; margin: 0 auto; ';
	        }

	        if ($margs['fullscreen'] == 'on') {
		        $fout .= ' position:' . 'fixed' . '; z-index:50005; top:0; left:0;';
	        }
	        if ($margs['category'] != '') {
//            $fout.=' display:none;"';
		        $fout .= '"';
		        $fout .=  '  data-category="' . $margs['category'] . '';
	        }
	        /*
			 *
			 */
	        $fout .= '"';
	        $fout .= '>';

        }




        $menuitem_w = $its['settings']['html5designmiw'];
        $menuitem_h = $its['settings']['html5designmih'];
        $menuposition = ($its['settings']['menuposition']);
//        echo $menuposition;
        $html5mp = $menuposition;

        $jreadycall = 'jQuery(document).ready(function($)';
        if ($menuposition == 'right' || $menuposition == 'left') {
            //$tw -= $menuitem_w;
        }
        if ($menuposition == 'down' || $menuposition == 'up') {
            //$th -= $menuitem_h;
        }
        if ($menuposition == 'down') {
            $html5mp = 'bottom';
        }
        if ($menuposition == 'up') {
            $html5mp = 'top';
        }


        $skin_vp = 'skin_aurora';
        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom') {
            $skin_vp = 'skin_pro';
        } else {
            if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom_aurora') {
                $skin_vp = 'skin_aurora';
            } else {

                $skin_vp = $vpsettings['settings']['skin_html5vp'];
            }
        }
        //echo $its['settings']['skin_html5vg'];

		if($margs['output_container']=='on') {

			if ( ! isset( $its['settings']['fullscreen'] ) || $margs['fullscreen'] != 'on' ) {
				$fout .= '<div class="videogallery-con';

				if ( isset( $its['settings']['laptopskin'] ) && $its['settings']['laptopskin'] == 'on' ) {
					$fout                           .= ' skin-laptop';
					$its['settings']['totalheight'] = '';
					$th                             = '';
					$its['settings']['bgcolor']     = 'transparent';
				}

//            echo 'hmmdada';


				$str_th = '';


				if ( $margs['fullscreen'] == 'on' ) {
					$str_th = ' height: 100%;';
				}


				//-- con
				$fout .= '" style="width:' . $tw . ';' . $str_th . '">';

				if ( isset( $its['settings']['laptopskin'] ) && $its['settings']['laptopskin'] == 'on' ) {
					$fout .= '<img class="thelaptopbg" width="100%" height="100%" src="' . $this->thepath . 'videogallery/img/mb-body.png"/>';
				}
//				$fout .= '<div class="preloader"></div>';
				$fout .= ' <div class="preloader loader-container ball-pulse"> <div class="loader"> <div class="line-1"></div> <div class="line-2"></div> <div class="line-3"></div> <div class="line-4"></div> <div class="line-5"></div> </div> </div>';
			}
		}

        $css_classid = str_replace(' ', '_', $its['settings']['id']);


        foreach ($this->arr_api_errors as $dzsvg_error) {
            echo $dzsvg_error;
        }


        $show_search_outside = false;

        if (isset($its['settings']['enable_search_field']) && $its['settings']['enable_search_field'] == 'on') {

            if(isset($its['settings']['search_field_location']) && $its['settings']['search_field_location']=='inside' && $its['settings']['displaymode'] == 'normal' && ($its['settings']['nav_type'] == 'thumbs' || $its['settings']['nav_type'] == 'scroller') && ($html5mp == 'left' || $html5mp == 'right')){


            }else{
                $fout .= '<div class="vg' . $this->sliders_index . '-search-field dzsvg-search-field outer"><input type="text" placeholder="' . __('Search') . '..."/><svg class="search-icon" version="1.1" id="Capa_1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="230.042 230.042 15 15" enable-background="new 230.042 230.042 15 15" xml:space="preserve"> <g> <path fill="#898383" d="M244.708,243.077l-3.092-3.092c0.746-1.076,1.118-2.275,1.118-3.597c0-0.859-0.167-1.681-0.501-2.465 c-0.333-0.784-0.783-1.46-1.352-2.028s-1.244-1.019-2.027-1.352c-0.785-0.333-1.607-0.5-2.466-0.5s-1.681,0.167-2.465,0.5 s-1.46,0.784-2.028,1.352s-1.019,1.244-1.352,2.028s-0.5,1.606-0.5,2.465s0.167,1.681,0.5,2.465s0.784,1.46,1.352,2.028 s1.244,1.019,2.028,1.352c0.784,0.334,1.606,0.501,2.465,0.501c1.322,0,2.521-0.373,3.597-1.118l3.092,3.083 c0.217,0.229,0.486,0.343,0.811,0.343c0.312,0,0.584-0.114,0.812-0.343c0.228-0.228,0.342-0.499,0.342-0.812 C245.042,243.569,244.931,243.3,244.708,243.077z M239.241,239.241c-0.79,0.79-1.741,1.186-2.853,1.186s-2.062-0.396-2.853-1.186 c-0.79-0.791-1.186-1.741-1.186-2.853s0.396-2.063,1.186-2.853c0.79-0.791,1.741-1.186,2.853-1.186s2.062,0.396,2.853,1.186 s1.186,1.741,1.186,2.853S240.032,238.45,239.241,239.241z"/> </g> </svg> </div>';
                $show_search_outside = true;
            }


        }



        $transition = 'fade';

        if (isset($its['settings']['transition']) && $its['settings']['transition']) {
            $transition = $its['settings']['transition'];
        }

		if($margs['output_container']=='on') {
			$fout .= '<div class="vg' . $this->sliders_index . ' transition-' . $transition . ' videogallery id_' . $css_classid . ' ' . $skin_html5vg;


			if ( isset( $its['settings']['extra_classes'] ) && $its['settings']['extra_classes'] != '' ) {
				$fout .= ' ' . $its['settings']['extra_classes'] . '';
			}


			$fout .= '" ';
			$fout .= ' id="' . $this->clean( $css_classid ) . '" ';


			$fout .= '';


//2 3
			$fout .= '   style="';


//        $fout.='aaWHATWHAT';


			if ( isset($its['settings']['skin_html5vg']) && $its['settings']['skin_html5vg'] != 'skin-custom' ) {
				$fout .= 'background-color:' . $its['settings']['bgcolor'] . ';';
			}

			$fout .= 'width:' . $tw . '; height:' . $th . '; ">';
		}
//<div class="vplayer-tobe" data-videoTitle="Pages"  data-description="<img src=thumbs/pages1.jpg class='imgblock'/><div class='the-title'>Pages</div>AE Project by Generator" data-sourcemp4="video/pages.mp4" data-sourceogg="video/pages.ogv" ><div class="videoDescription">You can have a description here if you choose to.</div></div>




        $fout .= $this->parse_items($its, $margs);
        $iout .= $this->parse_items($its, $margs);

//        foreach($extra_galleries as $extragal){
//            $args = array(
//                'id' => $extragal,
//                'return_mode' => 'items',
//
//            );
//
//            array_push($its,$this->show_shortcode($args));
////            $fout.=$this->show_shortcode($args);
//        }

        $html5vgautoplay = 'off';
        if ($its['settings']['autoplay'] == 'on') {
            $html5vgautoplay = 'on';
        }

        if (!isset($its['settings']['fullscreen']) || $its['settings']['fullscreen'] != 'on') {
            $fout .= '</div>';
        }

//        print_r($vpsettings);
        








        $fout .= '</div>';


        if($margs['call_script']=='on') {
	        $fout .= '<script>
var videoplayersettings' . $this->sliders_index . ' = {
autoplay : "off"
,ad_show_markers : "on"
,controls_out_opacity : 0.9
,controls_normal_opacity : 0.9
,settings_swfPath : "' . $this->thepath . 'preview.swf"';


	        if (isset($vpsettings['settings']['vimeo_is_chromeless']) && $vpsettings['settings']['vimeo_is_chromeless']=='on') {
		        $fout .= ',vimeo_is_chromeless:"'.$vpsettings['settings']['vimeo_is_chromeless'].'"';
	        }
	        if ( $this->mainoptions['videoplayer_end_exit_fullscreen'] == 'off' ) {
		        $fout .= ',end_exit_fullscreen:"' . $this->mainoptions['videoplayer_end_exit_fullscreen'] . '"';
	        }

	        $fout .= '};
';
	        if ( $its['settings']['displaymode'] == 'wall' ) {
		        $fout .= 'window.zoombox_videoplayersettings = videoplayersettings' . $this->sliders_index . ';';


		        wp_enqueue_style( 'dzsap', $this->thepath . 'assets/audioplayer/audioplayer.css' );
		        wp_enqueue_script( 'dzsap', $this->thepath . 'assets/audioplayer/audioplayer.js' );


		        wp_enqueue_script( 'jquery.masonry', $this->thepath . "assets/masonry/jquery.masonry.min.js" );

		        wp_enqueue_style( 'zoombox', $this->thepath . 'assets/zoombox/zoombox.css' );
		        wp_enqueue_script( 'zoombox', $this->thepath . 'assets/zoombox/zoombox.js' );
	        }
	        if ( isset( $its['settings']['autoplay_on_mobile_too_with_video_muted'] ) && $its['settings']['autoplay_on_mobile_too_with_video_muted'] == 'on' ) {
		        $fout .= 'videoplayersettings' . $this->sliders_index . '.autoplay_on_mobile_too_with_video_muted = "on";';
	        }

	        $fout .= $jreadycall . '{
videoplayersettings' . $this->sliders_index . '.design_skin = "' . $skin_vp . '";
videoplayersettings' . $this->sliders_index . '.settings_youtube_usecustomskin = "' . $its['settings']['yt_customskin'] . '";
videoplayersettings' . $this->sliders_index . '.controls_normal_opacity = "' . $its['settings']['html5design_controlsopacityon'] . '";
videoplayersettings' . $this->sliders_index . '.controls_out_opacity = "' . $its['settings']['html5design_controlsopacityout'] . '";
videoplayersettings' . $this->sliders_index . '.settings_video_overlay = "' . $its['settings']['settings_video_overlay'] . '";';


//        print_r($vpsettings);
	        if ( isset( $vpsettings['settings']['enable_quality_changer_button'] ) && $vpsettings['settings']['enable_quality_changer_button'] == 'on' ) {
		        $fout .= ' videoplayersettings' . $this->sliders_index . '.settings_extrahtml_before_right_controls = \'<div class="dzsvg-player-button quality-selector show-only-when-multiple-qualities">{{svg_quality_icon}}<div class="dzsvg-tooltip">{{quality-options}}</div></div>\';';
	        }
	        if ( isset( $vpsettings['settings']['settings_big_play_btn'] ) && $vpsettings['settings']['settings_big_play_btn'] == 'on' ) {
		        $fout .= ' videoplayersettings' . $this->sliders_index . '.settings_big_play_btn="on";';
	        }
	        if ( isset( $vpsettings['settings']['video_description_style'] ) && $vpsettings['settings']['video_description_style'] ) {
		        $fout .= ' videoplayersettings' . $this->sliders_index . '.video_description_style="' . $vpsettings['settings']['video_description_style'] . '";';
	        }
	        if ( isset( $its['settings']['youtube_sdquality'] ) ) {
		        $fout .= 'videoplayersettings' . $this->sliders_index . '.youtube_sdQuality = "' . $its['settings']['youtube_sdquality'] . '";';
	        }
	        if ( isset( $its['settings']['settings_mouse_out_delay'] ) ) {
		        $fout .= 'videoplayersettings' . $this->sliders_index . '.settings_mouse_out_delay = "' . $its['settings']['settings_mouse_out_delay'] . '";';
	        }
	        if ( isset( $its['settings']['youtube_hdquality'] ) ) {
		        $fout .= 'videoplayersettings' . $this->sliders_index . '.youtube_hdQuality = "' . $its['settings']['youtube_hdquality'] . '";';
	        }
	        if ( isset( $its['settings']['youtube_defaultquality'] ) ) {
		        $fout .= 'videoplayersettings' . $this->sliders_index . '.youtube_defaultQuality = "' . $its['settings']['youtube_defaultquality'] . '";';
	        }


	        if ( isset( $its['settings']['settings_video_end_reset_time'] ) && $its['settings']['settings_video_end_reset_time'] == 'off' ) {
		        $fout .= 'videoplayersettings' . $this->sliders_index . '.settings_video_end_reset_time="off";';
	        }

	        if ( isset( $its['settings']['rtmp_streamserver'] ) ) {
		        $fout .= 'videoplayersettings' . $this->sliders_index . '.rtmp_streamServer = "' . $its['settings']['rtmp_streamserver'] . '";';
	        }

	        if ( isset( $vpsettings['settings']['settings_ios_usecustomskin'] ) ) {
		        $fout .= 'videoplayersettings' . $this->sliders_index . '.settings_ios_usecustomskin = "' . $its['settings']['settings_ios_usecustomskin'] . '";';

	        }
	        if ( isset( $vpsettings['settings']['ga_enable_send_play'] ) ) {
		        $fout .= 'videoplayersettings' . $this->sliders_index . '.ga_enable_send_play = "' . $its['settings']['ga_enable_send_play'] . '";';

	        }
	        if ( isset( $vpsettings['settings']['defaultvolume'] ) ) {
		        $fout .= 'videoplayersettings' . $this->sliders_index . '.defaultvolume = "' . $its['settings']['defaultvolume'] . '";';

	        }


	        if ( isset( $its['settings']['set_responsive_ratio_to_detect'] ) && $its['settings']['set_responsive_ratio_to_detect'] == 'on' ) {




		        $fout .= 'videoplayersettings' . $this->sliders_index . '.responsive_ratio = "detect";';

	        }

	        if ( $this->mainoptions['analytics_enable'] == 'on' ) {



                $fout .= 'videoplayersettings' . $this->sliders_index . '.action_video_view = window.dzsvg_wp_send_view;';
                $fout .= 'videoplayersettings' . $this->sliders_index . '.action_video_contor_60secs = window.dzsvg_wp_send_contor_60_secs;';


	        }


	        if ( $this->mainoptions['settings_trigger_resize'] == 'on' ) {
		        $fout .= 'videoplayersettings' . $this->sliders_index . '.settings_trigger_resize="1000"; ';
	        };


//        print_r($its);


	        if ( $this->mainoptions['youtube_playfrom'] ) {
		        if ( $its['settings']['feedfrom'] == 'ytuserchannel' || $its['settings']['feedfrom'] == 'ytplaylist' || $its['settings']['feedfrom'] == 'ytkeywords' ) {

			        $fout .= 'videoplayersettings' . $this->sliders_index . '.playfrom="' . $this->mainoptions['youtube_playfrom'] . '"; ';
		        }
	        }


//        $fout.='console.info("DZSVG_INIT ", $(".vg'.$this->sliders_index.'"));';

	        $fout .= ' dzsvg_init(".vg' . $this->sliders_index . '",{
menuSpace:0
,randomise:"off"
,settings_menu_overlay:"on"
,totalWidth : "' . $tw . '"';
	        if ( isset( $its['settings']['totalheight'] ) && $its['settings']['totalheight'] != '' ) {
		        $fout .= ',totalHeight : "' . $th . '"';
	        }
	        if ( isset( $margs['start_item'] ) && $margs['start_item'] ) {
		        $fout .= ',startItem : "' . ($margs['start_item']-1) . '"';
	        }

	        if ( isset( $its['settings']['forcevideoheight'] ) && $its['settings']['forcevideoheight'] != '' ) {
		        $fout .= ',forceVideoHeight : "' . $its['settings']['forcevideoheight'] . '"';
	        }


	        if ( isset( $its['settings']['init_on'] ) && $its['settings']['init_on'] && $its['settings']['init_on'] != 'init' ) {
		        $fout .= ',init_on : "' . $its['settings']['init_on'] . '"';
	        }


	        if ( $this->mainoptions['settings_trigger_resize'] == 'on' ) {
		        $fout .= ',settings_trigger_resize:"1000"';
	        };
	        if ( $this->mainoptions['easing_speed'] ) {
		        $fout .= ',easing_speed:"' . $this->mainoptions['easing_speed'] . '"';
	        };


	        $fout .= ',autoplay :"' . $html5vgautoplay . '"
,autoplayNext : "' . $its['settings']['autoplaynext'] . '"';

	        if(isset($its['settings']['nav_type'])){

		        $fout.=',nav_type : "' . $its['settings']['nav_type'] . '"';
            }


	        if ( ( $its['settings']['displaymode'] == 'videowall' || $its['settings']['displaymode'] == 'wall' ) && $its['settings']['mode_wall_layout'] ) {


	        } else {

		        $fout .= ',menuitem_width:"' . $menuitem_w . '"';
	        }


	        if(isset($its['settings']['html5designmis'])){

		        $fout .= ',menuitem_space:"' . $its['settings']['html5designmis'] . '"';
            }
$fout.=',menuitem_height:"' . $menuitem_h . '"
,modewall_bigwidth:"900"
,modewall_bigheight:"500"
';
	        if ( isset( $its['settings']['nav_space'] ) && $its['settings']['nav_space'] ) {
		        $fout .= ',nav_space: "' . $its['settings']['nav_space'] . '"';
	        }
	        if ( $this->mainoptions['loop_playlist'] == 'off' ) {
		        $fout .= ',loop_playlist: "' . $this->mainoptions['loop_playlist'] . '"';
	        }
	        if ( isset( $its['settings']['menu_description_format'] ) && $its['settings']['menu_description_format'] ) {
		        $fout .= ',menu_description_format: "' . $its['settings']['menu_description_format'] . '"';
	        }


	        if ( $margs['settings_separation_mode'] == 'scroll' || $margs['settings_separation_mode'] == 'button' ) {
		        $fout .= ',settings_separation_mode: "' . $margs['settings_separation_mode'] . '"';
		        $fout .= ',settings_separation_pages: [';
		        for ( $i = 1; $i < ( ceil( count( $its ) - 1 ) / intval( $margs['settings_separation_pages_number'] ) ); $i ++ ) {

			        if ( $i > 1 ) {
				        $fout .= ',';
			        }
			        $aux_args = $margs;

			        $fout .= '"' . site_url() . '/index.php?dzsvg_action=load_gallery_items_for_pagination&gallery_id=' . $margs['id'] . '&dzsvg_settings_separation_paged=' . ( $i + 1 ) . '&settings_separation_pages_number=' . $margs['settings_separation_pages_number'] . '"';


//                $fout .= '"' . $this->thepath . 'ajaxreceiver.php?args=' . urlencode(json_encode($aux_args)) . '&dzsvg_settings_separation_paged=' . ($i + 1) . '"';
		        }
		        $fout .= ']';
	        }
	        if ( isset( $its['settings']['cueFirstVideo'] ) ) {
		        $fout .= ',cueFirstVideo:"' . $its['settings']['cueFirstVideo'] . '"';
	        }
	        if ( ( isset( $its['settings']['disable_video_title'] ) && $its['settings']['disable_video_title'] == 'on' ) ) {
		        $fout .= ',disable_videoTitle:"on"';
	        }
	        if ( isset( $its['settings']['displaymode'] ) && ( $its['settings']['displaymode'] == 'wall' || $its['settings']['displaymode'] == 'normal' ) || $its['settings']['displaymode'] == 'rotator' || $its['settings']['displaymode'] == 'rotator3d' || $its['settings']['displaymode'] == 'slider' || $its['settings']['displaymode'] == 'videowall' ) {
		        $fout .= ',settings_mode:"' . $its['settings']['displaymode'] . '"';
	        }

	        if ( isset( $its['settings']['mode_wall_layout'] ) && $its['settings']['mode_wall_layout'] && $its['settings']['mode_wall_layout'] != 'none' ) {

//            echo '$its[\'settings\'][\'mode_wall_layout\'] - '.$its['settings']['mode_wall_layout'] ;
		        if ( $its['settings']['displaymode'] == 'videowall' || $its['settings']['displaymode'] == 'wall' ) {


			        if ( $its['settings']['mode_wall_layout'] == 'layout-2-cols-15-margin' ) {
				        $its['settings']['mode_wall_layout'] = 'dzs-layout--2-cols';
			        }
			        if ( $its['settings']['mode_wall_layout'] == 'layout-3-cols-15-margin' ) {
				        $its['settings']['mode_wall_layout'] = 'dzs-layout--3-cols';
			        }
			        if ( $its['settings']['mode_wall_layout'] == 'layout-4-cols-10-margin' ) {
				        $its['settings']['mode_wall_layout'] = 'dzs-layout--4-cols';
			        }

		        }

		        $fout .= ',extra_class_slider_con:"' . $its['settings']['mode_wall_layout'] . '"';
		        $fout .= ',nav_type_outer_grid:"' . $its['settings']['mode_wall_layout'] . '"';
	        }

	        if ( isset( $its['settings']['nav_type_outer_max_height'] ) && $its['settings']['nav_type_outer_max_height'] ) {


		        if ( isset( $its['settings']['nav_type'] ) && $its['settings']['nav_type'] == 'outer' ) {
			        $fout .= ',nav_type_outer_max_height:"' . $its['settings']['nav_type_outer_max_height'] . '"';
		        }


		        wp_enqueue_style( 'dzs.scroller', $this->thepath . 'assets/dzsscroller/scroller.css' );
		        wp_enqueue_script( 'dzs.scroller', $this->thepath . 'assets/dzsscroller/scroller.js' );
	        }
	        if ( isset( $its['settings']['logoLink'] ) && $its['settings']['logoLink'] != '' ) {
		        $fout .= ',logoLink:"' . $its['settings']['logoLink'] . '"';
	        }
	        $fout .= ',menu_position:"' . $html5mp . '"';

	        if ( isset( $its['settings']['html5transition'] ) ) {
//            $fout.=',transition_type:"' . $its['settings']['html5transition'] . '"';
	        }

	        if ( isset( $its['settings']['transition'] ) ) {
		        $fout .= ',transition_type:"' . $its['settings']['transition'] . '"';
	        }

	        $fout .= ' ,design_skin: "' . $skin_html5vg . '"';

	        if ( isset( $its['settings']['logo'] ) && $its['settings']['logo'] != '' ) {
		        $fout .= ',logo : "' . $its['settings']['logo'] . '" ';
	        }


	        if ( isset( $its['settings']['playorder'] ) ) {
		        $fout .= ',playorder :"' . $its['settings']['playorder'] . '"';
	        }
	        if ( isset( $its['settings']['design_navigationuseeasing'] ) ) {
		        $fout .= ',design_navigationUseEasing :"' . $its['settings']['design_navigationuseeasing'] . '"';
	        }

	        $lab = 'nav_type_auto_scroll';
	        if ( isset( $its['settings'][ $lab ] ) ) {
		        $fout .= ',' . $lab . ' :"' . $its['settings'][ $lab ] . '"';
	        }
	        if ( isset( $its['settings']['enable_search_field'] ) && $its['settings']['enable_search_field'] == 'on' ) {
		        $fout .= ',search_field :"on"';
	        }
//        print_r($its['settings']);
	        if ( isset( $its['settings']['settings_enable_linking'] ) && $its['settings']['settings_enable_linking'] == 'on' ) {
		        $fout .= ',settings_enable_linking :"' . $its['settings']['settings_enable_linking'] . '"';
	        }
	        if ( isset( $its['settings']['autoplay_ad'] ) ) {
		        $fout .= ',autoplay_ad :"' . $its['settings']['autoplay_ad'] . '"';
	        }


	        if ( isset( $its['settings']['enable_search_field'] ) && $its['settings']['enable_search_field'] == 'on' ) {
		        if ( $show_search_outside ) {

			        $fout .= ',search_field_con: $(".vg' . $this->sliders_index . '-search-field > input")';

		        }
	        }

	        if ( isset($its['settings']['enableunderneathdescription']) &&  $its['settings']['enableunderneathdescription'] == 'on' ) {
		        $its['settings']['enable_secondcon'] = 'off';
		        $fout                                .= ',settings_secondCon: "#as' . $this->sliders_index . '-secondcon"';
	        }


	        if ( isset($its['settings']['sharebutton']) && $its['settings']['sharebutton'] == 'on' ) {
		        $auxout = '';
		        if ( $its['settings']['facebooklink'] ) {

			        if ( $its['settings']['facebooklink'] == '{{share}}' ) {

				        $auxout .= '<a class="dzsvg-social-icon"  href="#"  onclick=\'window.dzsvg_open_social_link("https://www.facebook.com/sharer.php?u={{replacewithcurrurl}}"); return false;\'>';
			        } else {
				        $auxout .= '<a class="dzsvg-social-icon" target="_blank" href="' . stripslashes( $its['settings']['facebooklink'] ) . '">';
			        }
			        $auxout .= '<i class="fa fa-facebook"></i></a>';
		        }
		        if ( $its['settings']['twitterlink'] ) {
			        $auxout .= '<a class="dzsvg-social-icon" target="_blank"  href="' . stripslashes( $its['settings']['twitterlink'] ) . '"><i class="fa fa-twitter"></i></a>';
		        }
		        if ( $its['settings']['googlepluslink'] ) {
			        $auxout .= '<a class="dzsvg-social-icon" target="_blank"  href="' . stripslashes( $its['settings']['googlepluslink'] ) . '"><i class="fa fa-google-plus-official" aria-hidden="true"></i></a>';
		        }
		        if ( isset( $its['settings']['social_extracode'] ) && $its['settings']['social_extracode'] != '' ) {
			        $auxout .= $its['settings']['social_extracode'];
		        }

//            $auxout = preg_replace("/'/", "\\'", $auxout);
//            $auxout = str_replace('\'', '{{quot}}', $auxout);


//            echo 'auxout - '.$auxout;


//            $auxout = str_replace("\\\'", "\\'", $auxout);
//            $auxout = mysqli_real_escape_string(null, $auxout);

		        $auxout = str_replace( "'", "\\'", $auxout );
//            $auxout = str_replace("'"," ", $auxout);
		        $fout .= ',shareCode : ' . "'" . ( $auxout ) . "'" . ' ';
	        }

	        if ( isset($its['settings']['enable_secondcon']) && $its['settings']['enable_secondcon'] == 'on' ) {
		        $fout .= ',settings_secondCon:".dzsas-second-con-for-' . $css_classid . '"';
	        }
	        if (   isset($its['settings']['enable_outernav']) && $its['settings']['enable_outernav'] == 'on' ) {
		        $fout .= ',settings_outerNav:$(".videogallery--navigation-outer-for-' . $css_classid . '")';
	        }

	        if ( isset($its['settings']['embedbutton']) &&  $its['settings']['embedbutton'] == 'on' ) {
		        $auxout = '<iframe class="embed-for-gallery-' . $css_classid . '" src="' . $this->thepath . 'bridge.php?action=view&id=' . $its['settings']['id'] . '&db=' . $this->currDb . '" width="' . $its['settings']['width'] . '" height="' . $its['settings']['height'] . '" style="overflow:hidden;" scrolling="no" frameborder="0"></iframe>';
		        $fout   .= ',embedCode : \'' . $auxout . '\' ';
	        }
	        if ( isset( $its['settings']['rtl'] ) && $its['settings']['rtl'] == 'on' ) {
		        $fout .= ',masonry_options : {isRTL: true} ';
	        }

	        $fout .= ',videoplayersettings : videoplayersettings' . $this->sliders_index . '
}); ';


	        //  console.info("hmm",$(\'.id_'.$css_classid.'\'));

	        if ( isset( $its['settings']['action_playlist_end'] ) && $its['settings']['action_playlist_end'] ) {
		        $fout .= '
                        setTimeout(function(){

                            function gotonext_' . $this->sanitize_for_class( $its['settings']['id'] ) . '(arg){
//console.info($(\'.id_' . $css_classid . '\'));

                                ' . stripslashes( $its['settings']['action_playlist_end'] ) . '
                            }
console.info("$(\'.id_' . $css_classid . '\') -> ",$(\'.id_' . $css_classid . '\'));
                            $(\'.id_' . $css_classid . '\').get(0).api_set_action_playlist_end(gotonext_' . $this->sanitize_for_class( $its['settings']['id'] ) . ');
                        },1000);';
	        }


	        $fout .= '
})
</script>';
        }
        if (  isset($its['settings']['shadow']) && $its['settings']['shadow'] == 'on') {
            $fout .= '<div class="all-shadow" style="width:' . $tw . ';"></div>';
        }

        $fout .= '<div class="clear"></div>';

        if ($margs['settings_separation_mode'] == 'pages') {
            $fout .= '<div class="con-dzsvg-pagination">';
            //echo ceil((count($its) - 1) / intval($margs['settings_separation_pages_number']));
            for ($i = 0; $i < (ceil(count($its) - 1) / intval($margs['settings_separation_pages_number'])); $i++) {
                $str_active = '';
                if (($i + 1) == $margs['settings_separation_paged']) {
                    $str_active = ' active';
                };

                $auxurl = add_query_arg(array('dzsvg_settings_separation_paged' => ($i + 1)), dzs_curr_url());

                $fout .= '<a class="pagination-number ' . $str_active . '" href="' . esc_url($auxurl) . '">' . ($i + 1) . '</a>';
            }
            $fout .= '</div>';
        }

        $fout .= '</div>'; //END gallery-precon





//        echo 'hmm - ';print_r($val);



        if (isset($its['settings']['enableunderneathdescription']) && $its['settings']['enableunderneathdescription'] == 'on') {

            $fout .= '<div id="as' . $this->sliders_index . '-secondcon" class="dzsas-second-con"><div class="dzsas-second-con--clip">';
            foreach ($its as $lab => $val) {
                if ($lab === 'settings') {
                    continue;
                }

//                echo 'val - '; print_rr($val);

                $fout .= '<div class="item">';
                if (isset($val['title'])) {
                    $fout .= '<h4>' . stripslashes($val['title']) . '</h4>';
                }


                $fout .= '<div class="menudescriptioncon">' . $val['menuDescription'] . '</div>';


                $fout .= '</div>';

//                print_r($val);

            }
            $fout .= '</div></div>';
        }


        if ($its['settings']['displaymode'] == 'wall') {
            wp_enqueue_script('jquery.masonry', $this->thepath . "assets/masonry/jquery.masonry.min.js");

            wp_enqueue_style('zoombox', $this->thepath . 'assets/zoombox/zoombox.css');
            wp_enqueue_script('zoombox', $this->thepath . 'assets/zoombox/zoombox.js');
        }


        // -- alternatewall
        // ---- mode alternatewall


        if ($its['settings']['displaymode'] == 'alternatewall') {
            $fout = '';
            $iout = '';
            $fout .= '<style>
.dzs-gallery-container .item{ width:23%; margin-right:1%; float:left; position:relative; display:block; margin-bottom:10px; }
.dzs-gallery-container .item-image{ width:100%; }
.dzs-gallery-container h4{  color:#D26; }
.dzs-gallery-container h4:hover{ background: #D26; color:#fff; }
.last { margin-right:0!important; }
.clear { clear:both; }
</style>';
            $fout .= '<div class="dzs-gallery-container">';


            $fout .= $this->parse_items($its, $margs);
            $iout .= $this->parse_items($its, $margs);


            $fout .= '<div class="clear"></div>';
            $fout .= '</div>';


            if ($margs['settings_separation_mode'] == 'pages') {
                $fout .= '<div class="con-dzsvg-pagination">';
                //echo ceil((count($its) - 1) / intval($margs['settings_separation_pages_number']));
                for ($i = 0; $i < (ceil(count($its) - 1) / intval($margs['settings_separation_pages_number'])); $i++) {
                    $str_active = '';
                    if (($i + 1) == $margs['settings_separation_paged']) {
                        $str_active = ' active';
                    }
                    $fout .= '<a class="pagination-number ' . $str_active . '" href="' . esc_url(add_query_arg(array('dzsvg_settings_separation_paged' => ($i + 1)), dzs_curr_url())) . '">' . ($i + 1) . '</a>';
                }
                $fout .= '</div>';
            }

            $fout .= '<div class="clear"></div>';
            $fout .= '<script>jQuery(document).ready(function($){ jQuery(".zoombox").zoomBox(); });</script>';

            wp_enqueue_style('zoombox', $this->thepath . 'assets/zoombox/zoombox.css');
            wp_enqueue_script('zoombox', $this->thepath . 'assets/zoombox/zoombox.js');

            return $fout;
        }


        //=======alternate menu
        /////---mode alternatemenu
        if ($its['settings']['displaymode'] == 'alternatemenu') {
            $i = 0;
            $k = 0;


            $current_urla = explode("?", dzs_curr_url());
            $current_url = $current_urla[0];

            $fout = '';
            $fout .= '
<style type="text/css">
.submenu{
margin:0;
padding:0;
list-style-type:none;
list-style-position:outside;
position:relative;
z-index:32;
}

.submenu a{
display:block;
padding:5px 15px;
background-color: #28211b;
color:#fff;
text-decoration:none;
}

.submenu li ul a{
display:block;
width:200px;
height:auto;
}

.submenu li{
float:left;
position:static;
width: auto;
position:relative;
}

.submenu ul, .submenu ul ul{
position:absolute;
width:200px;
top:auto;
display:none;
list-style-type:none;
list-style-position:outside;
}
.submenu > li > ul{
position:absolute;
top:auto;
left:0;
margin:0;
}

.submenu a:hover{
background-color:#555;
color:#eee;
}

.submenu li:hover ul, .submenu li li:hover ul{
display:block;
}
</style>';

            $fout .= '<ul class="submenu">';
            if (isset($this->mainitems)) {
                for ($k = 0; $k < count($this->mainitems); $k++) {
                    if (count($this->mainitems[$k]) < 2) {
                        continue;
                    }
                    $fout .= '<li><a href="#">' . $this->mainitems[$k]["settings"]["id"] . '</a>';

                    if (isset($this->mainitems[$k]) && count($this->mainitems[$k]) > 1) {

                        $fout .= '<ul>';
                        for ($i = 0; $i < count($this->mainitems[$k]); $i++) {
                            if (isset($this->mainitems[$k][$i]["thethumb"])) $fout .= '<li><a href="' . $current_url . '?the_source=' . $this->mainitems[$k][$i]["source"] . '&the_thumb=' . $this->mainitems[$k][$i]["thethumb"] . '&the_type=' . $this->mainitems[$k][$i]["type"] . '&the_title=' . $this->mainitems[$k][$i]["title"] . '">' . $this->mainitems[$k][$i]["title"] . '</a>';
                        }
                        $fout .= '</ul>';
                    }
                    $fout .= '</li>';
                }
            }

            $k = 0;
            $i = 0;
            $fout .= '</ul>
<div class="clearfix"></div>
<br>';

            if (isset($_REQUEST['the_source'])) {

                $the_source = esc_html($_REQUEST['the_source']);
                $the_type = esc_html($_REQUEST['the_type']);
                $the_thumb = esc_html($_REQUEST['the_thumb']);
                $fout .= '<a class="zoombox" data-type="video" data-videotype="' . $the_type . '" data-src="' . $the_source . '"><img class="item-image" src="';
                if ($its[$i]['thethumb'] != '') $fout .= $the_thumb; else {
                    if ($its[$i]['type'] == "youtube") {
                        $fout .= 'https://img.youtube.com/vi/' . $the_source . '/0.jpg';
                        $its[$i]['thethumb'] = 'https://img.youtube.com/vi/' . $the_source . '/0.jpg';
                    }
                }
                $fout .= '"/></a>';
            }


            $fout .= '<script>jQuery(document).ready(function($){ jQuery(".zoombox").zoomBox(); });</script>';

            wp_enqueue_style('zoombox', $this->thepath . 'assets/zoombox/zoombox.css');
            wp_enqueue_script('zoombox', $this->thepath . 'assets/zoombox/zoombox.js');

            return $fout;
        }

        if ($this->mainoptions['debug_mode'] == 'on') {



            $fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('memory usage - ', 'dzsvg') . '</div>
<div class="toggle-content">';
            $fout.='memory usage - ' . memory_get_usage() . "\n <br>memory limit - " . ini_get('memory_limit');
            $fout.= '</div></div>';

        }




		if ($this->mainoptions['analytics_enable'] == 'on') {

			if(current_user_can('manage_options')){

				$fout .= '<div class="extra-btns-con">';
				$fout .= '<span class="btn-zoomsounds stats-btn" data-playerid="'.''.'"><span class="the-icon"><i class="fa fa-tachometer" aria-hidden="true"></i></span><span class="btn-label">Stats</span></span>';
				$fout .= '</div>';



				wp_enqueue_style('dzsvg_showcase', $this->thepath . 'front-dzsvp.css');
				wp_enqueue_script('dzsvg_showcase', $this->thepath . 'front-dzsvp.js');
			}




		}



        if ($margs['return_mode'] != 'parsed items') {
            $fout = str_replace('https://img.youtube.com', '//img.youtube.com', $fout);

//            echo '                  fout --------- ' . $fout.' <------ end fout --- ';
            return $fout;
        } else {
            $iout = str_replace('https://img.youtube.com', '//img.youtube.com', $iout);
            return $iout;
        }


        //echo $k;
    }


	function import_slider($file_cont){

		$tax = $this->taxname_sliders;
		try{

			$arr = json_decode($file_cont,true);

			$file_cont = str_replace('\\\\"','\\"',$file_cont);

//			error_log( 'content json - '. print_rr($arr,true));

			if($arr && is_array($arr)){

				$type = 'json';
			}else{

			    try{

				    $arr = unserialize($file_cont);


				    error_log( 'content serial - '. print_rr($arr,true). ' - '.print_rr($file_cont,true));
				    $type = 'serial';
                }catch(Exception $e){

				    error_log( 'failed parsing'. print_rr($file_cont,true));
                }
			}

			if(is_array($arr)){
				if($type=='json'){







					$reference_term_name = $arr['original_term_name'];
					$reference_term_slug = $arr['original_term_slug'];

//			    print_rr($reference_term_name);
//			    print_rr($reference_term_slug);
//			    print_rr($query);
					$original_name = $reference_term_name;
					$original_slug = $reference_term_slug;



					$new_term_slug = $reference_term_slug;
					$new_term_name = $reference_term_name;



					$ind = 1;
					$breaker = 100;


					$term = term_exists($new_term_name, $tax);
					if ($term !== 0 && $term !== null) {


						$new_term_name=$original_name.'-'.$ind;
						$new_term_slug=$original_slug.'-'.$ind;
						$ind++;


						while(1){

							$term = term_exists($new_term_name, $tax);
							if ($term !== 0 && $term !== null) {

								$new_term_name=$original_name.'-'.$ind;
								$new_term_slug=$original_slug.'-'.$ind;
							}else{

							    error_log("SEEMS THAT TERM DOES NOT EXIST ".$new_term_name.' '.$new_term_slug);
								break;
							}
							$ind++;

							$breaker--;

							if($breaker<0){
								break;
							}
						}

					}else{

						error_log("SEEMS THAT TERM DOES NOT EXIST ".$new_term_name.' '.$new_term_slug);


					}
//					error_log("SEEMS THAT TERM DOES NOT EXIST ".$new_term_name.' '.$new_term_slug);



					$new_term = wp_insert_term(
						$new_term_name, // the term
						$tax, // the taxonomy
						array(

							'slug' => $new_term_slug,
						)
					);


					$new_term_id = '';
					if(is_array($new_term)){

						$new_term_id = $new_term['term_id'];
					}else{
						error_log(' .. ERROR the name is '.$new_term_name);
						error_log(' .. $tax is '.$tax);
						error_log(print_r($new_term,true));
					}



					$term_meta = array_merge(array(), $arr['term_meta']);

					unset($term_meta['items']);

					update_option("taxonomy_$new_term_id", $term_meta);


					foreach ($arr['items'] as $po){

						$args = array_merge(array(), $po);

						$args['term']=$new_term;
						$args['taxonomy']=$tax;


						// -- we do not need this

						unset($args['post_name']);


						error_log('args import item - '.print_r($args, true));
						$this->import_demo_insert_post_complete($args);



					}

//			$new_term = get_term_by('slug',$new_term_slug,$tax);

//            error_log(print_rr($new_term,array('echo'=>false)));














				}



				// -- legacy
				if($type=='serial'){


					$new_term_id = '';
					$new_term = null;
					$original_slug = '';
					$new_term_slug = '';


					foreach ($arr as $lab=>$val){


						if($lab==='settings'){




							// -- settings


							$reference_term_name = $val['id'];
							$reference_term_slug = $val['id'];

//			    print_rr($reference_term_name);
//			    print_rr($reference_term_slug);
//			    print_rr($query);
							$original_name = $reference_term_name;
							$original_slug = $reference_term_slug;



							$new_term_slug = $reference_term_slug;
							$new_term_name = $reference_term_name;



							$ind = 1;
							$breaker = 100;


							$term = term_exists($new_term_name, $tax);
							if ($term !== 0 && $term !== null) {


								$new_term_name=$original_name.'-'.$ind;
								$new_term_slug=$original_slug.'-'.$ind;
								$ind++;


								while(1){

									$term = term_exists($new_term_name, $tax);
									if ($term !== 0 && $term !== null) {

										$new_term_name=$original_name.'-'.$ind;
										$new_term_slug=$original_slug.'-'.$ind;
									}else{

//										error_log("SEEMS THAT TERM DOES NOT EXIST ".$new_term_name.' '.$new_term_slug);
										break;
									}
									$ind++;

									$breaker--;

									if($breaker<0){
										break;
									}
								}

							}else{

//								error_log("SEEMS THAT TERM DOES NOT EXIST ".$new_term_name.' '.$new_term_slug);


							}



							$new_term = wp_insert_term(
								$new_term_name, // the term
								$tax, // the taxonomy
								array(

									'slug' => $new_term_slug,
								)
							);


							if(is_array($new_term)){

								$new_term_id = $new_term['term_id'];
							}else{
								error_log(' .. the name is '.$new_term_name);
								error_log(print_r($new_term,true));
							}


							$term_meta = array_merge(array(), $val);

							if($val['feedfrom']=='vmuserchannel'||$val['feedfrom']=='vmchannel'||$val['feedfrom']=='vmalbum'){
$term_meta['feed_mode']='vimeo';

								if($val['feedfrom']=='vmuserchannel'){

									$term_meta['vimeo_source']='https://vimeo.com/'.$val['vimeofeed_user'];
                                }
								if($val['feedfrom']=='vmchannel'){

									$term_meta['vimeo_source']='https://vimeo.com/channels/'.$val['vimeofeed_channel'];
                                }
								if($val['feedfrom']=='vmalbum'){

									$term_meta['vimeo_source']='https://vimeo.com/album/'.$val['vimeofeed_vmalbum'];
                                }

                            }

							if($val['feedfrom']=='ytkeywords'||$val['feedfrom']=='ytplaylist'||$val['feedfrom']=='ytuserchannel'){
$term_meta['feed_mode']='youtube';

								if($val['feedfrom']=='ytkeywords'){

									$term_meta['youtube_source']='https://youtube.com/results/?search_query='.$val['ytkeywords_source'];
                                }
								if($val['feedfrom']=='ytplaylist'){

									$term_meta['youtube_source']='https://youtube.com/?list='.$val['ytplaylist_source'];
                                }
								if($val['feedfrom']=='ytuserchannel'){

									$term_meta['youtube_source']='https://youtube.com/c/'.$val['youtubefeed_user'];
                                }

                            }

							unset($term_meta['items']);

							update_option("taxonomy_$new_term_id", $term_meta);
						}else{

							$args = array_merge(array(), $val);

							$args['term']=$new_term;
							$args['taxonomy']=$tax;
							$args['post_name'] =$new_term_slug.'-'.$lab;
							$args['post_title'] =$original_slug.'-'.$lab;

							if(isset($args['title'])){
								$args['post_title'] = $args['title'];
							}

							foreach ($this->options_item_meta as $oim){
								$long_name = $oim['name'];

								$short_name = str_replace('dzsvg_meta_','',$oim['name']);



								if(isset($args[$short_name])){

									$args[$long_name] = $args[$short_name];
								}


							}
							if(isset($args['type'])){
								$args['dzsvg_meta_item_type'] = $args['type'];

							}
							if(isset($args['source'])){
								$args['dzsvg_meta_featured_media'] = $args['source'];

							}
							if(isset($args['thethumb'])){
								$args['dzsvg_meta_thumb'] = $args['thethumb'];

							}
							if(isset($args['description'])){
								$args['dzsvg_meta_description'] = $args['description'];
								$args['dzsvg_meta_menuDescription'] = $args['description'];
							}
							if(isset($args['menudescription'])){
								$args['dzsvg_meta_menu_description'] = $args['menudescription'];
							}
							if(isset($args['menuDescription'])){
								$args['dzsvg_meta_menu_description'] = $args['menuDescription'];
							}


							$args['dzsvg_meta_order_'.$new_term_id] = $lab;


							error_log('args import item - '.print_r($args, true));

							$this->import_demo_insert_post_complete($args);

						}



					}
				}
			}
			return true;
		}catch(Exception $err){
			print_rr($err);
			return false;
		}




	}

    function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = str_replace('?', '-', $string); // Replaces all spaces with hyphens.

        return preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
    }
    function parse_items($its, $pargs) {
        //====returns only the html5 gallery items


        $margs = array(
            'settings_separation_mode' => 'normal',
            'settings_separation_paged' => '0',
            'settings_separation_pages_number' => '5',
            'single' => 'off',
            'video_post' => null,
            'call_from' => 'default',
            'striptags' => 'on',
            'extra_classes_player' => '',
        );

        if (is_array($pargs) == false) {
            $pargs = $margs;
        }


        $margs = array_merge($margs, $pargs);

//        print_r($margs);
        $fout = '';
        $start_nr = 0; // === the i start nr
        $end_nr = count($its); // === the i start nr
        $nr_per_page = 5;
        $nr_items = count($its) - 1;
        $nr_page = intval($margs['settings_separation_paged']);


        if ($nr_page == 0) {
            $nr_page = 1;
        }
//        print_r($its); print_r($margs); echo $margs['settings_separation_mode']; echo $margs['settings_separation_mode']!='normal';
        if ($margs['settings_separation_mode'] != 'normal') {
            $nr_per_page = intval($margs['settings_separation_pages_number']);

            if ($nr_per_page * $nr_page <= $nr_items) {
                $start_nr = $nr_per_page * ($nr_page - 1);
                $end_nr = $start_nr + $nr_per_page;
            } else {
                $start_nr = $nr_items - $nr_per_page - 1;
                $end_nr = $nr_items;
            }
        }
//        echo 'ceva '.$nr_per_page . ' || ' . ($nr_per_page * $nr_page) . ' ||||| ' . $start_nr . ' ' . $end_nr;

        if (isset($its['settings']['displaymode']) && $its['settings']['displaymode'] == 'alternatewall') {
            for ($i = $start_nr; $i < $end_nr; $i++) {
                if (!isset($its[$i]['type'])) {
                    continue;
                }
                $islastonrow = false;
                if ($i % 4 == 3) {
                    $islastonrow = true;
                }
                $itemclass = 'item';
                if ($islastonrow == true) {
                    $itemclass .= ' last';
                }
                $fout .= '<div class="' . $itemclass . '">';
                //$fout.='<a href="' . $this->thepath . 'ajax.php?ajax=true&height=' . $its['settings']['height'] . '&width=' . $its['settings']['width'] . '&type=' . $its[$i]['type'] . '&source=' . $its[$i]['source'] . '" title="' . $its[$i]['type'] . '" rel=""><img class="item-image" src="';
                $fout .= '<a class="zoombox" data-type="video" data-videotype="' . $its[$i]['type'] . '" data-src="' . $its[$i]['source'] . '"><img width="100%" height="100%" class="item-image" src="';
                if ($its[$i]['thethumb'] != '') $fout .= $its[$i]['thethumb']; else {
                    if ($its[$i]['type'] == "youtube") {
                        $fout .= 'https://img.youtube.com/vi/' . $its[$i]['source'] . '/0.jpg';
                        $its[$i]['thethumb'] = 'https://img.youtube.com/vi/' . $its[$i]['source'] . '/0.jpg';
                    }
                }
                $fout .= '"/></a>';
                $fout .= '<h4>' . $its[$i]['title'] . '</h4>';
                $fout .= '</div>';
                if ($islastonrow) {
                    $fout .= '<div class="clear"></div>';
                }
            }
            return $fout;
        }


//        print_r($its); print_r($margs); echo ' start nr : '.$start_nr; echo ' end nr : '. $end_nr;

        for ($i = $start_nr; $i < $end_nr; $i++) {
            if (isset($its[$i]) == false) {
                continue;
            }


            $che = $its[$i];
            $this->index_players++;

            $video_post = null;


//            print_rr($che);
            if (isset($che['video_post']) && $che['video_post']) {

            }else{

                if (isset($che['mediaid']) && $che['mediaid']){




                    $auxpo = get_post($che['mediaid']);
                    if ($auxpo == false) {
                    }else{
                        $video_post = $auxpo;
                    }

                    $post_id = $che['mediaid'];

//            print_r($auxpo);
                    if($auxpo->post_type=='attachment'){

                        if($che['source']=='') {
                            $che['source'] = $auxpo->guid;
                        }
                    }


                    if($auxpo->post_type=='product' || $auxpo->post_type=='dzsvideo'){

                        if(get_post_meta($post_id, 'dzsvp_featured_media',true)){

                            if($che['source']==''){

                                $che['source']=get_post_meta($post_id, 'dzsvp_featured_media',true);
                            }
                        }


                        if($video_post->post_content){

                            if($che['description']=='default'){

                                $che['description']=$video_post->post_content;
                            }
                        }
                    }

                }
            }




            if($video_post){
                $che['video_post'] = $video_post;
            }

//            print_rr($che);

            if ($che['source'] == '' || $che['source'] == ' ') {
                continue;
            }



            $str_id = '';
            $vp_id = 'vp' . $this->index_players;
            if (isset($che['cssid']) && $che['cssid'] != '') {
                $vp_id = $che['cssid'];
            }


            $vp_id = $this->clean($vp_id);
            if (isset($its['settings']['ids_point_to_source']) && $its['settings']['ids_point_to_source'] == 'on') {
                $vp_id = 'vg' . $this->sliders_index . '_' . 'vp' . $this->clean($che['source']);
                $str_id = ' id="'.$vp_id.'"';
            }


            $fout .= '<div  '.$str_id.' class="' . $vp_id . ' vplayer-tobe '.$margs['extra_classes_player'].'';



            
//            print_r($che);
            if (isset($its['settings']['hide_on_mouse_out']) && $its['settings']['hide_on_mouse_out']=='on') {
                $fout .= ' hide-on-mouse-out';
            }
            if (isset($its['settings']['hide_on_paused']) && $its['settings']['hide_on_paused']=='on') {
                $fout .= ' hide-on-paused';
            }

            $fout.='"';


            if(isset($che['playerid']) && $che['playerid']){

	            $fout.=' data-player-id="'.dzs_clean_string($che['playerid']).'"';
            }else{

//	            $fout.=' data-player-id="'.dzs_clean_string($che['source']).'"';

                if(is_numeric($che['source'])){

	                $fout.=' data-player-id="'.(($che['source'])).'"';
                }else{

	                $fout.=' data-player-id="'.intval($this->encode_to_number($che['source'])).'"';
                }
            }



            if (isset($its['settings']['coverImage']) && $its['settings']['coverImage']) {
                $fout .= '  data-img="' . $this->sanitize_id_to_src($its['settings']['coverImage']) . '"';
            }

//            print_r($its);


//            print_r($its['settings']);
            if (!(isset($its['settings']['disable_video_title']) && $its['settings']['disable_video_title'] == 'on') && isset($che['title']) && $che['title']) {
                $che['title'] = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $che['title']);
                $che['title'] = str_replace(array('"'), "&#8221;", $che['title']);
                $fout .= ' data-videoTitle="' . $che['title'] . '"';
            }
            if (isset($che['loop']) && $che['loop']=='on') {
                $fout .= ' data-loop="' . $che['loop'] . '"';

            }

//            print_rr($che);
            if (isset($che['is_360']) && $che['is_360']=='on') {
                $fout .= ' data-is-360="' . $che['is_360'] . '"';
            }
//            print_rr($che);
            if (isset($che['type']) && $che['type'] == 'normal') {
                $che['type'] = 'video';
            }
            if (isset($che['type']) && $che['type'] == 'video') {
                $fout .= ' data-source="' . $che['source'] . '"';
                $fout .= ' data-sourcemp4="' . $che['source'] . '"';


                if (isset($che['html5sourceogg']) && $che['html5sourceogg'] != '') {

                    if (strpos($che['html5sourceogg'], '.webm') === false) {
                        $fout .= ' data-sourceogg="' . $che['html5sourceogg'] . '"';
                    } else {
                        $fout .= ' data-sourcewebm="' . $che['html5sourceogg'] . '"';
                    }
                }
            }

//            print_r($its);


            if(isset($its['settings']['displaymode']) && $its['settings']['displaymode']=='rotator3d'){

                if (isset($che['audioimage'])==false || $che['audioimage']=='') {

                    if (isset($che['thethumb']) && $che['thethumb']) {
                        $che['audioimage'] = $che['thethumb'];
                    }
                }

            }

//            print_rr($che);


            $preview_img = '';


	        if (isset($che['audioimage']) && $che['audioimage']) {

	            $preview_img = $che['audioimage'];
	        }


	        if (isset($its['settings']['displaymode']) && $its['settings']['displaymode'] == 'rotator3d'){

	            $preview_img = $che['thumbnail'];
            }



	        if (isset($its['settings']['displaymode']) && $its['settings']['displaymode'] == 'wall' && isset($che['thethumb']) && $che['thethumb'] != '') {
		        $preview_img = $che['thethumb'];
	        }

	        if($preview_img){

		        $fout .= ' data-previewimg="' . $preview_img . '"';
            }


            if (isset($che['audioimage']) && $che['audioimage']) {
                $fout .= '  data-img="' . $che['audioimage'] . '"';
            } else {

            }
            if (isset($che['type']) && $che['type'] == 'audio') {
                $fout .= ' data-source="' . $che['source'] . '"';
                $fout .= ' data-sourcemp3="' . $che['source'] . '"';
                if (isset($che['html5sourceogg']) && $che['html5sourceogg'] != '') {
                    $fout .= ' data-sourceogg="' . $che['html5sourceogg'] . '"';
                }
                if (isset($che['audioimage']) && $che['audioimage'] != '') {
                    $fout .= ' data-audioimg="' . $che['audioimage'] . '"';
                }
                $fout .= ' data-type="audio"';
            }
            if (isset($che['type']) && $che['type'] == 'youtube') {
                $fout .= ' data-type="youtube"';
                $fout .= ' data-src="' . $che['source'] . '"';
            }
            if (isset($che['type']) && $che['type'] == 'vimeo') {
                $fout .= ' data-type="vimeo"';
                $fout .= ' data-src="' . $che['source'] . '"';
            }
            if (isset($che['type']) && $che['type'] == 'image') {
                $fout .= ' data-type="image"';
                $fout .= ' data-source="' . $che['source'] . '"';
            }
            if (isset($che['type']) && $che['type'] == 'dash') {
                $fout .= ' data-type="dash"';
                $fout .= ' data-source="' . $che['source'] . '"';
            }
            if (isset($che['type']) && $che['type'] == 'facebook') {


                $src = '';

	            $arr = explode('/', $che['source']);
	            
//	            print_rr($arr);

	            $id = '';
	            if($arr[count($arr)-1]==''){
		            $id = $arr[count($arr)-2];
                }else{

		            $id = $arr[count($arr)-1];
                }



                // -- facebook parse
	            $app_id = $this->mainoptions['facebook_app_id'];
	            $app_secret = $this->mainoptions['facebook_app_secret'];
	            $accessToken = $this->mainoptions['facebook_access_token'];


	            if($app_id && $app_secret && $accessToken ){
		            $fout .= ' data-type="video"';
		            require_once 'class_parts/src/Facebook/autoload.php'; // change path as needed

		            $fb = new Facebook\Facebook(array(
			            'app_id' => $app_id,
			            'app_secret' => $app_secret,
			            'default_graph_version' => 'v2.10',
			            //'default_access_token' => '{access-token}', // optional
		            ));





		            try {
			            // Returns a `Facebook\FacebookResponse` object

			            // we don't need thumbnails for now thumbnails,
			            $response = $fb->get(
				            '/'.$id.'/videos?fields=title,picture,description,source',
				            $accessToken
			            );


//			            print_rr($response);

//			            $che['source'] = 'ceva';
		            } catch(Facebook\Exceptions\FacebookResponseException $e) {
			            echo 'Graph line 8225 returned an error: ' . $e->getMessage();
//			exit;
		            } catch(Facebook\Exceptions\FacebookSDKException $e) {
			            echo 'Facebook SDK returned an error: ' . $e->getMessage();
//			exit;
		            }


		            $fout .= ' data-source="' . $che['source'] . '"';


                }else{

	                // -- facebook iframe

//		            $fout .= ' data-type="inline"';

		            $che['type'] = 'inline';

		            $che['source'] = '<iframe src="https://www.facebook.com/plugins/video.php?href=https%3A%2F%2Fwww.facebook.com%2Ffacebook%2Fvideos%2F'.$id.'%2F&show_text=false&appId=998845010190473" width="100%" height="100%" style="border:none;overflow:hidden" scrolling="no" frameborder="0" allowTransparency="true" allowFullScreen="true"></iframe>';
                }







            }
            if (isset($che['type']) && $che['type'] == 'link') {
                $fout .= ' data-type="link"';
                $fout .= ' data-source="' . $che['source'] . '"';

                if (isset($che['type']) && $che['type'] == 'link' && isset($che['link_target'])) {

                    $fout .= ' data-target="' . $che['link_target'] . '"';
                }
            }
            if (isset($che['type']) && $che['type'] == 'inline') {
                $fout .= ' data-type="inline"';
            }



//            print_rr($che);
            $aux = 'adarray';

//            echo '$che[$aux] - '.$che[$aux];
            if (isset($che[$aux]) && $che[$aux]) {


                $che[$aux] = str_replace('{{openbrace}}','[',$che[$aux]);
                $che[$aux] = str_replace('{{closebrace}}',']',$che[$aux]);
                $fout .= ' data-ad-array' . '' . '=\'' . ($che[$aux]) . '\'';

            }

            //stripslashes



            //-- deprecated

            $aux = 'adsource';
            if (isset($che[$aux]) && $che[$aux] != '') {
                if (isset($che['adtype']) && $che['adtype'] != 'inline') {
                    $fout .= ' data-' . $aux . '="' . $che[$aux] . '"';
                }
            }
            $aux = 'adtype';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fout .= ' data-' . $aux . '="' . $che[$aux] . '"';
            }
            $aux = 'adlink';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fout .= ' data-' . $aux . '="' . $che[$aux] . '"';
            }
            $aux = 'adskip_delay';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fout .= ' data-' . $aux . '="' . $che[$aux] . '"';
            }
            //-- deprecated END




            $aux = 'playfrom';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fout .= ' data-' . $aux . '="' . $che[$aux] . '"';
            }

            $aux = 'responsive_ratio';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fout .= ' data-' . $aux . '="' . $che[$aux] . '"';
            }

            // -- if the video player is single shortcode then we can alter width height
            if ($margs['single'] == 'on') {
//                print_r($margs);
                // ===== some sanitizing
                $tw = $margs['width'];
                $th = $margs['height'];
                $str_tw = '';
                $str_th = '';


                if ($tw != '') {
                    if (strpos($tw, "%") === false && $tw != 'auto') {
                        $str_tw = ' width: ' . $tw . 'px;';
                    } else {
                        $str_tw = ' width: ' . $tw . ';';
                    }
                }


                if ($th != '') {
                    if (strpos($th, "%") === false && $th != 'auto') {
                        $str_th = ' height: ' . $th . 'px;';
                    } else {
                        $str_th = ' height: ' . $th . ';';
                    }
                }


                $fout .= ' style="' . $str_tw . $str_th . '"';
            }


            $fout .= '>';

            // -- starting from tag


            $maxlen = 350;
            if (isset($its['settings']['maxlen_desc']) && $its['settings']['maxlen_desc']) {
                $maxlen = $its['settings']['maxlen_desc'];
            }



            $aux = 'qualities';

//            print_rr($che);
            if (isset($che[$aux]) && $che[$aux] ) {
                try{
                    $che[$aux] = str_replace('{{quot}}','"',$che[$aux]);
                    $che[$aux] = str_replace('{{patend}}',']',$che[$aux]);
                    $qual_arr = json_decode($che[$aux]);
                    
//                    echo '$che[$aux] -> '.$che[$aux];
//                    print_rr($qual_arr);
                    if(is_array($qual_arr)){

                        foreach($qual_arr as $it){


                            $att = get_post($it->source);

//                        print_rr($att);

                            $source = '';

                            if(is_numeric($it->source)){

                                if($att->post_type=='attachment'){
                                    $source = wp_get_attachment_url($it->source);
                                }
                            }else{
                                $source = $it->source;
                            }

                            $fout.=' <div class="dzsvg-feed dzsvg-feed-quality" data-label="'.$it->label.'" data-source="'.$source.'"></div>';
                        }
                    }
                }catch(Exception $err){

                }
            }

            $striptags = true;

            $try_to_close_unclosed_tags = true;


            if (isset($its['settings']['striptags'])){
                if($its['settings']['striptags'] === 'on') {
//                $striptags=true;
                    $try_to_close_unclosed_tags = false;
                }


//                print_r($che);
                if($its['settings']['striptags'] === 'off'  ) {
                    $striptags = false;

                }
            }
            if((isset($che['striptags']) && $che['striptags']=='off')){
                $striptags = false;

            }
            if (isset($its['settings']['try_to_close_unclosed_tags']) && $its['settings']['try_to_close_unclosed_tags'] === 'off') {
                $try_to_close_unclosed_tags = false;
            }

//            echo 'description - '.$che['description'];

            $aux24 = '';

            $readmore_markup = '';
            if(isset($its['settings']['readmore_markup'])){
                $readmore_markup = $its['settings']['readmore_markup'];
            }

//            print_r($che);

            $readmore = 'auto';

            if($che['type']=='youtube'){
                $readmore_markup=str_replace('{{postlink}}','https://www.youtube.com/watch?v='.$che['source'], $readmore_markup);
            }else{

                // if no post link
                if(strpos($readmore_markup,'{{postlink}}')!==false){

                    $readmore_markup='';
                    $readmore = 'off';
                }
            }

//            echo '$maxlen -'.$maxlen;


//            print_rr($che);
            $args = array('content' => $che['description'], 'maxlen' => $maxlen, 'try_to_close_unclosed_tags' => $try_to_close_unclosed_tags, 'striptags' => $striptags, 'readmore_markup' => $readmore_markup, 'readmore' => $readmore, 'call_from' => 'simple_description',);

//            print_r($args);

            $che['description'] = wp_kses(dzs_get_excerpt(-1, $args),$this->allowed_tags);

            //stripslashes


	        if (isset($che['menuDescription']) && $che['menuDescription']=='as_description') {
		        $che['menuDescription'] = $che['description'];
	        }


	        if(isset($che['menuDescription'])){


	            // -- TODO: remove striptags for now
	            //, 'striptags' => $striptags

//                echo '$maxlen - '.$maxlen;

                $che['menuDescription'] = wp_kses(dzs_get_excerpt(-1, array('content' => ($che['menuDescription']), 'maxlen' => $maxlen, 'try_to_close_unclosed_tags' => $try_to_close_unclosed_tags, 'striptags' => false, 'readmore_markup' => $readmore_markup, 'readmore' => $readmore, 'call_from' => 'simple_menudescription',)),$this->allowed_tags);
            }


            if (isset($che['description']) && $che['description']) {
                $aux24 = '<div class="videoDescription description-from-parse_items">' . $che['description'] . '</div>';
            }


//if($this->generate_player_extra_controls($che,$its)){

            if ( (isset($its['settings']['enable_info_button']) && $its['settings']['enable_info_button'] == 'on')   ||   (isset($its['settings']['enable_link_button']) && $its['settings']['enable_link_button'] == 'on')   ||   (isset($its['settings']['enable_cart_button']) && $its['settings']['enable_cart_button'] == 'on')    ||   (isset($its['settings']['enable_quality_changer_button']) && $its['settings']['enable_quality_changer_button'] == 'on')    ||   (isset($its['settings']['enable_multisharer_button']) && $its['settings']['enable_multisharer_button'] == 'on')   ) {

                $fout.='<div class="extra-controls">';


                $fout.=$this->generate_player_extra_controls($che,$its);


                $fout.='</div>';
            }






//            echo $aux24.'-'.strrpos($aux24, '</').'-'.strlen($aux24).'- ';
            $aux24 = str_replace('</</div>', '</div>', $aux24);

            $fout .= $aux24;


            if (isset($che['logo']) && $che['logo']) {
                $fout .= '<div class="vplayer-logo">';


                if (isset($che['logo_link']) && $che['logo_link']) {

                    $fout.='<a href="'. $che['logo_link'].'">';
                }
                $fout.='<div class="divimage" style="background-image: url('.$this->sanitize_id_to_src($che['logo']).');"></div>';


                if (isset($che['logo_link']) && $che['logo_link']) {

                    $fout.='</a>';
                }
                $fout .= '</div>';
            }

            $aux = 'subtitle_file';
            if (isset($che[$aux]) && $che[$aux] != '') {
                $fil = DZSHelpers::get_contents($che[$aux]);
                $fout .= '<div class="subtitles-con-input">' . $fil . '</div>';
            }


            if(isset($its['settings']['displaymode']) && $its['settings']['displaymode']=='normal' && isset($its['settings']['menu_description_format']) && $its['settings']['menu_description_format']  ){


                $fout.='
<div class="feed-menu-number">'.($i+1).'</div>
<div class="feed-menu-title">'. stripslashes($che['title']).'</div>';


                if (isset($che['thethumb']) && $che['thethumb']) {
                    $fout.='<div class="feed-menu-image">'. $che['thethumb'].'</div>';
                }else{

                    if ($che['type'] == 'youtube') {
                        $fout .= '<div class="feed-menu-image">{ytthumb}</div>';
                    }
                }

$fout.='<div class="feed-menu-desc">'.$che['menuDescription'].'</div>
<div class="feed-menu-time">'.$che['total_duration'].'</div>';

            }else{
                $fout .= '<div class="menuDescription from-parse-items">';

                // --  imgblock or imgfull
                $thumbclass = 'imgblock';


                if (isset($its['settings']['thumb_extraclass']) && $its['settings']['thumb_extraclass'] != '') {
                    $thumbclass .= ' ' . $its['settings']['thumb_extraclass'];
                }

                if (isset($its['settings']['nav_type']) && $its['settings']['nav_type'] == 'outer') {
                    $thumbclass = 'imgfull';
                }

                if (isset($che['thumbnail']) && $che['thumbnail']) {

                    if (isset($che['thethumb']) && $che['thethumb']) {

                    }else{
                        $che['thethumb'] = $che['thumbnail'];
                    }
                }else{

                }

                if (isset($che['thethumb']) && $che['thethumb']) {
//                echo 'hmmdada'; print_r($che['thethumb']);
//                    $fout .= '<img width="100%" height="100%" src="' . $che['thethumb'] . '" class="' . $thumbclass . '"/>';
                    $fout .= '<div style="background-image: url(' . $che['thethumb'] . '); "   class="divimage ' . $thumbclass . '"></div>';
                } else {
                    if ($che['type'] == 'youtube') {
                        $fout .= '{ytthumb}';
                    }
                }

//                print_rr($che);
//                print_rr($its['settings']);
                if ((isset($its['settings']['disable_title'])==false || $its['settings']['disable_title'] != 'on') && isset($che['title']) && $che['title']) {
                    $fout .= '<div class="the-title">' . stripslashes($che['title']) . '</div>';
                }
//            echo 'hmmtest'.!isset($its['settings']['disable_menu_description']).' '.isset($its['settings']['disable_menu_description']).' '.$its['settings']['disable_menu_description'];


                $aux24 = '<div class="paragraph">';
                if (((isset($its['settings']['disable_menu_description'])) && $its['settings']['disable_menu_description'] != 'on') && isset($che['menuDescription']) && $che['menuDescription']) {



                    $aux24.=$che['menuDescription'];

                }


//            echo $aux24.'-'.strrpos($aux24, '</').'-'.strlen($aux24).'- ';

                if (strrpos($aux24, '</') === strlen($aux24) - 2) {
                    $aux24 = substr($aux24, 0, -2);
                }

                $aux24 .= '</div>';

                $fout .= $aux24;


                $fout .= '</div>'; //---menuDescription END
            }


            if (isset($che['tags']) && $che['tags']) {
                $arr_septag = explode('$$;', $che['tags']);
                foreach ($arr_septag as $septag) {
                    //print_r($septag);
                    if ($septag != '') {
                        $arr_septagprop = explode('$$', $septag);
                        //print_r($arr_septagprop);
                        $fout .= '<div class="dzstag-tobe" data-starttime="' . $arr_septagprop[0] . '" data-endtime="' . $arr_septagprop[1] . '" data-left="' . $arr_septagprop[2] . '" data-top="' . $arr_septagprop[3] . '" data-width="' . $arr_septagprop[4] . '" data-height="' . $arr_septagprop[5] . '" data-link="' . $arr_septagprop[6] . '">' . $arr_septagprop[7] . '</div>';
                    }
                }
                //print_r($arr_septag);
            }

            if (isset($che['type']) && $che['type'] == 'inline') {
                $fout .= stripslashes($che['source']);
            }


            if (isset($che['adtype']) && $che['adtype'] == 'inline') {
                $fout .= '<div class="adSource">' . $che['adsource'] . '</div>';
            }

            $fout .= '</div>';
        }
        return $fout;
    }

    function generate_player_extra_controls($che=null, $its = null){
        $fout = '';
        if (isset($its['settings']['enable_info_button']) && $its['settings']['enable_info_button'] == 'on') {

            if($che && isset($che['description']) && $che['description']){


                $aux = $che['description'];

                $aux = preg_replace("/<a.*?>.*?<\/a>/", "", $aux);

                $fout.='<a class="dzsvg-control dzsvg-info">
                            <i class="fa fa-info-circle"></i>
                            <div class="info-content align-right" style="width: 300px;">
                                '.$aux.'
                            </div>
                        </a>';
            }
        }

        if (isset($its['settings']['enable_link_button']) && $its['settings']['enable_link_button'] == 'on') {

            if($che && isset($che['link']) && $che['link']){

                $fout.='<a class="dzsvg-control dzsvg-link" href="'.$che['link'].'">
                            <i class="fa fa-link"></i>
                            <div class="info-content " style=";">
'.$che['link_label'].'
                            </div>
                        </a>';
            }
        }


        if (isset($its['settings']['enable_cart_button']) && $its['settings']['enable_cart_button'] == 'on') {

            if($che && isset($che['video_post']) && $che['video_post']){
                $video_post = $che['video_post'];

//                        print_r($video_post);

                if($video_post->post_type=='product'){







                    $buy_link = DZSHelpers::add_query_arg(dzs_curr_url(), 'add-to-cart',$video_post->ID);



                    if(function_exists('wc_get_product')){

	                    $product_id = $video_post->ID;
	                    $_product = wc_get_product( $product_id );
	                    if( $_product->is_type( 'simple' ) ) {

	                    } else {
		                    $buy_link = get_permalink($video_post->ID);
	                    }

                    }
                    $fout.='<div class="dzsvg-control dzsvg-add-to-cart">
                            <a href="'.$buy_link.'">
                            <i class="fa fa-shopping-cart"></i>
                            </a>

                            <div class="info-content ">
'.__('Add to Cart').'
                            </div>
                        </div>';
                }
            }
        }


        if (isset($its['settings']['enable_multisharer_button']) && $its['settings']['enable_multisharer_button'] == 'on') {

	        $this->multisharer_on_page = true;


	        $str_share = __('Share');


	        if($this->mainoptions['translate_share']){
		        $str_share = $this->mainoptions['translate_share'];
            }


            $fout.='<div class="dzsvg-control dzsvg-multisharer-but">
                           
                            <i class="the-icon">{{svg_embed_icon}}</i>
                            

                            <div class="info-content ">
'.$str_share.'
                            </div>
                        </div>';

        }

        return $fout;
    }

	function encode_to_number($string) {
		return substr(sprintf("%u", crc32($string)),0,8);
		$ans = array();
		$string = str_split($string);
		#go through every character, changing it to its ASCII value
		for ($i = 0; $i < count($string); $i++) {

			#ord turns a character into its ASCII values
			$ascii = (string) ord($string[$i]);

			#make sure it's 3 characters long
			if (strlen($ascii) < 3)
				$ascii = '0'.$ascii;
			$ans[] = $ascii;
		}

		#turn it into a string
		return implode('', $ans);
	}


	function show_shortcode_showcase($pargs = array()) {


        //[dzsvp_portal count="5" mode="ullist" type="latest"]
        $fout = '';

        $margs = array(
            'count' => '5',
            'type' => 'video_items',
            'mode' => 'scrollmenu',
            'style' => 'list',
            'desc_count' => 'default',
            'desc_readmore_markup' => 'default',
            'max_videos' => '',
            'cat' => '',
            'ids' => '',
            'linking_type' => 'default',
            'return_only_items' => 'off',
            'mode_scrollmenu_height' => '160',
            'mode_zfolio_skin' => 'skin-forwall',
            'mode_zfolio_layout' => '3columns',
            'mode_zfolio_gap' => '30px',
            'mode_zfolio_enable_special_layout' => 'off',
            'mode_zfolio_show_filters' => 'off',
            'mode_zfolio_default_cat' => 'none',
            'mode_zfolio_categories_are_links' => 'off',
            'mode_zfolio_categories_are_links_ajax' => 'off',
            'mode_zfolio_title_links_to' => 'off',
            'mode_list_enable_view_count' => 'off',


            'orderby' => 'none',
            'order' => 'DESC',
            'from_sample' => 'off',

            'mode_gallery_view_gallery_skin' => 'skin_pro',
            'mode_gallery_view_set_responsive_ratio_to_detect' => 'off',
            'mode_gallery_view_width' => '100%',
            'mode_gallery_view_height' => '300',
            'mode_gallery_view_autoplay' => 'off',
            'mode_gallery_view_html5designmiw' => '275',
            'mode_gallery_view_html5designmih' => '100',
            'mode_gallery_view_menuposition' => 'right',
            'mode_gallery_view_analytics_enable' => 'off',
            'mode_gallery_view_autoplaynext' => 'off',
            'mode_gallery_view_nav_type' => 'thumbs',
            'mode_gallery_view_nav_space' => '0',
            'mode_gallery_view_disable_video_title' => 'off',
            'mode_gallery_view_logo' => '',
            'mode_gallery_view_logoLink' => '',
            'mode_gallery_view_playorder' => '',
            'mode_gallery_view_design_navigationuseeasing' => 'off',
            'mode_gallery_view_enable_search_field' => 'off',
            'mode_gallery_view_settings_enable_linking' => 'off',
            'mode_gallery_view_autoplay_ad' => 'off',
            'mode_gallery_view_embedbutton' => 'off',

            'vpconfig' => 'default',
        );

        if (!is_array($pargs)) {
            $pargs = array();
        }

        $margs = array_merge($margs, $pargs);

        if($margs['cat']=='none'){
            $margs['cat']='';
        }

        if(defined('DZSVG_PREVIEW') && DZSVG_PREVIEW=='YES'){
//            print_r($_GET);

            if(isset($_POST['dzsvg_preview_feed']) && $_POST['dzsvg_preview_feed']){



                if(strpos($_POST['dzsvg_preview_feed'], 'youtube')!==false){

                    $margs['type']='youtube';
                    $margs['youtube_link']=$_POST['dzsvg_preview_feed'];
                }

                if(strpos($_POST['dzsvg_preview_feed'], 'vimeo')!==false){

                    $margs['type']='vimeo';
                    $margs['vimeo_link']=$_POST['dzsvg_preview_feed'];
                }


                // ---
            }


        }


        if($margs['from_sample']=='on'){
            $this->ajax_import_sample_items();
        }

//		print_rr($margs);

		if($margs['type']=='vimeo'){
			if(isset($margs['href']) && $margs['href']){
				$margs['vimeo_link'] = $margs['href'];
			}
		}

//        echo 'sliders_index - '.$this->sliders_index;

        if (defined('DZSVG_PREVIEW') && DZSVG_PREVIEW=='YES') {
            global $post;

            if($post){

//            print_r($post); echo 'dzsvg_preview - '.get_post_meta($post->ID, 'dzsvg_preview', true);
                if (get_post_meta($post->ID, 'dzsvg_preview', true) == 'on') {
                    wp_enqueue_script('preseter', $this->thepath . 'assets/preseter/preseter.js');
                    wp_enqueue_style('preseter', $this->thepath . 'assets/preseter/preseter.css');
                    ?>
                    <div class="preseter" style="position: fixed">


                        <div class="the-icon-con">
                            <div class=" btn-show-customizer"><svg class="the-icon" style="enable-background:new 0 0 500 500;" version="1.1" viewBox="0 0 500 500" xml:space="preserve" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink"><g id="pencil-gear"><g><rect height="500" style="" width="500" y="0"/><g id="_x32_9"><g><path d="M407.573,132.556L363.13,88.12c-9.243-9.163-24.097-9.163-33.325,0.021L106.493,311.457      c-4.256,4.255-6.504,9.727-6.834,15.293l-10.378,58.923v0.703c-0.015,3.275,0.71,6.365,1.992,9.17l-9.793,9.785      c-3.062,3.069-3.062,8.043,0,11.111c3.069,3.076,8.064,3.076,11.118,0l9.793-9.785c2.79,1.274,5.889,2,9.141,2h0.85      l59.099-12.869l-0.007-0.029c4.665-0.783,9.162-2.938,12.788-6.533l57.451-57.451c-2.791-0.879-5.361-2.344-7.471-4.453      l-5.061-5.031c-0.036-0.037-0.051-0.088-0.095-0.117l-55.942,55.935c-3.011,3.003-8.094,3.024-11.118,0l-8.877-8.884      l76.494-76.494h-0.403c-3.677,0-7.09-1.106-9.968-2.975l-72.796,72.803l-14.436-14.45l78.771-78.772v-2.175      c0-10.137,8.27-18.398,18.428-18.398h2.139l1.399-1.392l-1.062-1.055c-2.007-2.021-3.391-4.409-4.277-6.929L125.375,341.442      l-7.778-7.772c-3.003-3.024-3.003-8.078,0-11.125l186.661-186.65l13.315,13.352l-61.682,61.678      c2.575,0.908,4.948,2.307,6.926,4.285l1.069,1.062l2.593-2.593c0.762-8.877,7.808-15.923,16.685-16.699l41.074-41.066      l14.458,14.436l-32.944,32.93c2.183,2.534,3.706,5.64,4.263,9.097l35.347-35.361l14.443,14.443l-23.921,23.921      c1.011,0.674,1.978,1.45,2.871,2.329l5.098,5.098c1.172,1.172,2.095,2.513,2.9,3.919l60.82-60.82      C416.743,156.675,416.743,141.799,407.573,132.556z M146.776,385.08l-35.911,7.829c-1.201-0.131-2.3-0.593-3.237-1.303      c-0.183-0.242-0.373-0.506-0.586-0.726c-0.227-0.234-0.483-0.381-0.718-0.586c-0.739-0.96-1.216-2.095-1.325-3.34l6.54-37.112      L146.776,385.08z" style="fill:#FFFFFF;"/></g><g><g><g><path d="M350.957,277.897v-7.156c0-3.977-3.208-7.155-7.163-7.155h-17.065        c-0.747-3.545-1.948-6.899-3.486-10.042l12.627-12.656c2.827-2.805,2.827-7.338,0.015-10.129l-5.054-5.068        c-2.812-2.791-7.324-2.791-10.122,0l-11.543,11.543c-3.091-2.176-6.519-3.882-10.122-5.171v-16.81        c0-3.962-3.208-7.162-7.148-7.17h-7.178c-3.94,0.008-7.148,3.208-7.148,7.17v14.971c-4.204,0.682-8.159,2-11.836,3.816        l-10.866-10.869c-2.789-2.791-7.338-2.791-10.144-0.008l-5.061,5.062c-2.783,2.783-2.783,7.324,0.021,10.137l9.727,9.733        c-2.505,3.626-4.482,7.596-5.786,11.924h-14.385c-3.962,0-7.178,3.201-7.178,7.142v7.155c0,3.963,3.216,7.171,7.178,7.171        h13.477c0.938,4.395,2.564,8.555,4.746,12.363l-10.319,10.319c-2.776,2.806-2.812,7.353-0.029,10.145l5.083,5.061        c2.79,2.791,7.346,2.776,10.145,0l10.125-10.137c3.56,2.154,7.412,3.802,11.514,4.842v15.732c0,3.947,3.208,7.163,7.163,7.163        h7.163c3.955,0,7.148-3.216,7.148-7.163v-15.732c3.765-0.982,7.324-2.417,10.605-4.285l12.114,12.093        c2.783,2.79,7.324,2.798,10.107,0l5.068-5.039c2.798-2.798,2.798-7.339,0-10.122l-11.924-11.939        c1.831-3.018,3.34-6.284,4.424-9.72h17.944C347.749,285.067,350.957,281.853,350.957,277.897z M302.646,272.536        c0,9.888-8.027,17.886-17.915,17.886c-9.888,0-17.886-7.998-17.886-17.886c0-9.91,7.998-17.908,17.886-17.908        C294.619,254.628,302.646,262.626,302.646,272.536z" style="fill:#FFFFFF;"/></g></g><g><g><path d="M420.815,329.87v-4.556c-0.015-2.534-2.065-4.563-4.57-4.563h-10.854        c-0.469-2.256-1.23-4.379-2.227-6.394l8.027-8.042c1.816-1.787,1.816-4.68,0.029-6.453l-3.237-3.223        c-1.773-1.779-4.658-1.779-6.431,0l-7.339,7.347c-1.978-1.384-4.16-2.476-6.445-3.281v-10.708c0-2.52-2.051-4.556-4.556-4.562        h-4.556c-2.505,0.007-4.556,2.043-4.556,4.562v9.529c-2.666,0.432-5.2,1.273-7.515,2.424l-6.929-6.914        c-1.773-1.772-4.673-1.772-6.46,0l-3.223,3.208c-1.758,1.787-1.758,4.673,0,6.46l6.211,6.196        c-1.611,2.308-2.856,4.834-3.677,7.588h-9.155c-2.52,0-4.57,2.036-4.57,4.541v4.548c0,2.535,2.051,4.564,4.57,4.564h8.569        c0.586,2.806,1.626,5.449,3.032,7.866l-6.562,6.57c-1.772,1.794-1.802,4.68-0.029,6.452l3.237,3.223        c1.787,1.78,4.673,1.773,6.431,0l6.46-6.445c2.256,1.362,4.731,2.417,7.324,3.076v10.013c0,2.512,2.051,4.562,4.57,4.562        h4.541c2.52,0,4.57-2.051,4.57-4.562v-10.013c2.388-0.615,4.658-1.538,6.738-2.725l7.705,7.69        c1.773,1.772,4.658,1.787,6.445,0l3.208-3.208c1.787-1.772,1.787-4.658,0-6.439l-7.573-7.595        c1.157-1.919,2.109-3.999,2.798-6.182h11.426C418.75,334.426,420.801,332.375,420.815,329.87z M390.054,326.442        c0,6.299-5.098,11.382-11.396,11.382c-6.284,0-11.397-5.083-11.397-11.382c0-6.299,5.112-11.397,11.397-11.397        C384.956,315.046,390.054,320.143,390.054,326.442z" style="fill:#FFFFFF;"/></g></g></g></g></g></g><g id="Layer_1"/></svg></div>
                            <div class="btn-close-customizer"><svg class="the-icon" version="1.1" xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="100px" viewBox="0 25 100 100" enable-background="new 0 25 100 100" xml:space="preserve"> <g id="Layer_2"> <rect y="25" fill="#3D576B" width="100" height="100"/> </g> <g id="Layer_1"> <g id="pencil-gear"> <g> <g id="_x32_9"> <g> <path fill="#FFFFFF" d="M81.515,51.511l-8.889-8.887c-1.849-1.833-4.819-1.833-6.665,0.004L21.299,87.291 c-0.852,0.852-1.301,1.945-1.367,3.059l-2.075,11.785v0.141c-0.003,0.654,0.142,1.273,0.398,1.834l-1.958,1.957 c-0.612,0.613-0.612,1.608,0,2.223c0.614,0.615,1.613,0.615,2.224,0l1.958-1.957c0.558,0.254,1.178,0.399,1.829,0.399h0.17 l11.82-2.573l-0.001-0.006c0.933-0.157,1.832-0.588,2.558-1.307l11.49-11.49c-0.559-0.176-1.072-0.469-1.494-0.891 l-1.012-1.007c-0.007-0.007-0.01-0.018-0.019-0.023l-11.188,11.188c-0.603,0.601-1.619,0.604-2.224,0l-1.775-1.776 l15.299-15.299h-0.081c-0.735,0-1.418-0.222-1.994-0.596L29.295,97.512l-2.887-2.891l15.754-15.754v-0.435 c0-2.028,1.654-3.68,3.686-3.68h0.428l0.28-0.278l-0.212-0.211c-0.401-0.404-0.678-0.881-0.855-1.386L25.075,93.289 l-1.556-1.555c-0.601-0.605-0.601-1.616,0-2.226l37.332-37.33l2.663,2.67L51.178,67.185c0.516,0.182,0.99,0.461,1.386,0.857 l0.214,0.212l0.519-0.519c0.152-1.775,1.562-3.185,3.337-3.34l8.215-8.213l2.892,2.887l-6.589,6.586 c0.437,0.506,0.741,1.128,0.853,1.819l7.069-7.072l2.889,2.889l-4.784,4.784c0.202,0.135,0.396,0.29,0.574,0.466l1.02,1.02 c0.234,0.234,0.419,0.502,0.58,0.784l12.164-12.164C83.349,56.335,83.349,53.36,81.515,51.511z M29.355,102.016l-7.182,1.566 c-0.24-0.026-0.46-0.119-0.647-0.261c-0.037-0.048-0.074-0.101-0.117-0.146c-0.045-0.047-0.096-0.076-0.144-0.117 c-0.147-0.191-0.243-0.419-0.265-0.668l1.308-7.422L29.355,102.016z"/> </g> <g> <g> <g> <path fill="#FFFFFF" d="M65.771,81.142v-0.912c-0.003-0.506-0.413-0.912-0.914-0.912h-2.171 c-0.094-0.451-0.246-0.876-0.445-1.279l1.605-1.607c0.363-0.357,0.363-0.937,0.006-1.291l-0.647-0.645 c-0.354-0.355-0.932-0.355-1.286,0l-1.468,1.469c-0.396-0.276-0.832-0.495-1.289-0.656v-2.141 c0-0.504-0.41-0.912-0.911-0.913h-0.911c-0.501,0.001-0.911,0.409-0.911,0.913v1.905c-0.533,0.087-1.041,0.255-1.504,0.485 l-1.385-1.383c-0.355-0.355-0.936-0.355-1.293,0l-0.645,0.641c-0.352,0.357-0.352,0.936,0,1.293l1.242,1.238 c-0.322,0.462-0.57,0.967-0.734,1.518h-1.832c-0.504,0-0.914,0.408-0.914,0.908v0.91c0,0.507,0.41,0.912,0.914,0.912h1.715 c0.117,0.562,0.324,1.09,0.605,1.574l-1.312,1.313c-0.354,0.358-0.359,0.937-0.006,1.29l0.648,0.645 c0.357,0.356,0.934,0.355,1.285,0l1.293-1.289c0.451,0.273,0.945,0.484,1.465,0.615v2.003c0,0.503,0.41,0.913,0.914,0.913 h0.908c0.504,0,0.914-0.41,0.914-0.913v-2.003c0.477-0.123,0.932-0.307,1.348-0.545l1.541,1.539 c0.354,0.354,0.932,0.356,1.288,0l0.642-0.643c0.357-0.354,0.357-0.932,0-1.287l-1.514-1.52 c0.23-0.384,0.421-0.8,0.559-1.236h2.285C65.357,82.052,65.768,81.642,65.771,81.142z M59.618,80.456 c0,1.26-1.02,2.275-2.279,2.275c-1.257,0-2.279-1.016-2.279-2.275s1.022-2.28,2.279-2.28 C58.599,78.177,59.618,79.196,59.618,80.456z"/> </g> </g> </g> </g> </g> </g> <g id="Layer_1_1_"> </g> </g> <g id="Layer_3"> <path fill="#FFFFFF" d="M83.991,87.433L77.843,75.35c-0.562-1.106-1.931-1.551-3.038-0.989l-1.009,0.514 c-1.105,0.562-1.552,1.931-0.988,3.037l4.689,9.221l-9.219,4.691c-1.107,0.563-1.553,1.932-0.989,3.037l0.513,1.008 c0.563,1.108,1.932,1.554,3.039,0.989l12.082-6.147c0.595-0.303,0.991-0.838,1.149-1.438 C84.297,88.693,84.294,88.028,83.991,87.433z"/> </g> </svg> </div>
                        </div>
                        <div class="preseter-content-con auto-height overflow-x-visible" style="width: 250px; height: auto;">
                            <div class=" the-content-inner-con">
                                <div class="the-content inner" style=" " data-targetw="-250">
                                    <div class="the-content-inner-inner">
                                        <form method="POST">
                                            <div class="the-bg"></div>
                                            <h3><?php echo __('Customize'); ?></h3>
                                            <div class="setting setting-for-dc-player">
                                                <div class="alabel"><?php echo __('Feed Link'); ?></div>
                                                <input type="text" class="dc-input " name="dzsvg_preview_feed" value="https://www.youtube.com/playlist?list=PLADC18FE37410D250" style="width: 100%;"/><div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
                                            </div>
                                            <button class="preseter-button"><?php echo __("Submit"); ?></button><br><br>
                                            <div class="sidenote" style="font-size: 12px;"><?php echo sprintf(__("Paste here a link to the youtube playlist / youtube channel / youtube keywords / vimeo channel / vimeo album / vimeo user channel. %s %sExamples%s: %s or %s or %s"), '<br><br>','<strong>','</strong>','<br><a href="https://vimeo.com/heidibivens" target="_blank"><strong>https://vimeo.com/heidibivens</strong></a>', '<br><a href="https://www.youtube.com/playlist?list=PLADC18FE37410D250" target="_blank"><strong>https://www.youtube.com/playlist?list=PLADC18FE37410D250</strong></a>', '<br><a href="https://www.youtube.com/results?search_query=cat+funny" target="_blank"><strong>https://www.youtube.com/results?search_query=cat+funny</strong></a>'); ?></div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div><!--end the-content-->
                    </div>
                    <?php
                    if (isset($_GET['opt3'])) {
                        $its['settings']['nav_type'] = 'none';
                        $its['settings']['menuposition'] = $_GET['opt3'];
                        $its['settings']['autoplay'] = $_GET['opt4'];
                        $its['settings']['feedfrom'] = $_GET['feedfrom'];
                        $its['settings']['youtubefeed_user'] = $_GET['opt6'];
                        $its['settings']['ytkeywords_source'] = $_GET['opt6'];
                        $its['settings']['ytplaylist_source'] = $_GET['opt6'];
                        $its['settings']['vimeofeed_user'] = $_GET['opt6'];
                        $its['settings']['vimeofeed_channel'] = $_GET['opt6'];
                    }
                }
            }
        }//----dzsvg preview END


        if ($margs['mode'] == 'zfolio') {
            if ($margs['linking_type'] == 'default') {
                $margs['linking_type'] = 'zoombox';
            }
        }


        if ($margs['linking_type'] == 'default') {
            $margs['linking_type'] = 'direct_link';
        }


        // -- latest


        $its = array();
        $cats = array();








        if ($margs['type'] == 'video_items') {
	        $wpqargs = array(
		        'post_type' => 'dzsvideo',
		        'posts_per_page' => $margs['count'],
		        'orderby' => 'date',
		        'order' => 'DESC',
		        'post_status' => 'publish',
	        );




	        if($margs['orderby']){
	            $wpqargs['orderby'] = $margs['orderby'];
            }

	        if($margs['order']){
	            $wpqargs['order'] = $margs['order'];
            }

            $cats = array();
            if ($margs['cat']) {
//                $wpqargs['cat'] = $margs['cat'];

                $cats = explode(',', $margs['cat']);

//                foreach($cats_aux as $val){
//                    array_push($cats, $val);
//                }

            }else{
                if($margs['mode_zfolio_show_filters']=='on'){

                }
            }

            $cats = array_values($cats);


//            print_rr(' $margs[\'cat\'] - '.$margs['cat']);
//            print_rr($cats);


            foreach($cats as $lab=>$catsingle){
                $cats[$lab] = $this->sanitize_term_slug_to_id($catsingle);

//                echo '$catsingle - '.$catsingle;
//                echo '$catsingle - '.$this->sanitize_term_slug_to_id($catsingle);
            }

//                print_rr($cats);



if($margs['ids']){
    $wpqargs['post__in']= explode(',',$margs['ids']);
}



//print_rr($cats);
            if ($wpqargs['post_type'] == 'dzsvideo' && $margs['cat']) {
                $wpqargs['tax_query'] = array(array('taxonomy' => 'dzsvideo_category', 'field' => 'id', 'terms' => $cats,),);
            }



//            echo '$wpqargs - '; print_rr($wpqargs);


            $query = new WP_Query($wpqargs);


//            print_r($query);
            $its = $this->transform_to_array_for_parse($query->posts, $margs);
        }


        if ($margs['type'] == 'youtube') {
//            echo 'ceva';


            include_once "class_parts/parse_yt_vimeo.php";




            if($margs['youtube_link']){

            }
	        $its = dzsvg_parse_yt($margs['youtube_link'], $margs, $fout);

//            print_r($its);
        }


        if ($margs['type'] == 'vimeo') {
//            echo 'ceva';


            include_once "class_parts/parse_yt_vimeo.php";

            $args = array_merge(array(),$margs);

            $args['type']='detect';
            $its = dzsvg_parse_vimeo($margs['vimeo_link'], $args, $fout);

//            print_r($its);
        }
        if ($margs['type'] == 'facebook') {
//            echo 'ceva';


            include_once "class_parts/parse_yt_vimeo.php";

            $args = array_merge(array(),$margs);

            $args['type']='detect';
            $args['facebook_source']=$margs['facebook_link'];
            $its = dzsvg_parse_facebook($margs['facebook_link'], $args, $fout);

//            print_r($its);
        }

        if ($margs['type'] == 'video_gallery') {
//            echo 'ceva';


            $margs['id'] = $margs['dzsvg_selectid'];
            $margs['return_mode'] = 'items';
            $margs['call_from'] = 'video_gallery_showcase';

            $its = $this->show_shortcode($margs);


            foreach ($its as $lab => $it) {
                $its[$lab]['thumbnail'] = $it['thethumb'];
                $its[$lab]['permalink'] = '#';

//                print_r($margs);

                if($margs['linking_type']=='zoombox'){
                    $its[$lab]['permalink'] = $it['source'];
                }
            }

//            $its = dzsvg_parse_vimeo($margs['vimeo_link'], $margs);

//            print_r($its);
        }

        if($margs['orderby']=='date'){

            if($margs['order']=="ASC"){

                usort($its, "sort_by_date");
            }else{

                usort($its, "sort_by_date_desc");
            }

        }

        if($margs['orderby']=='views'){

            if($margs['order']=="ASC"){

                usort($its, "sort_by_views");
            }else{

                usort($its, "sort_by_views_desc");
            }

        }

//        print_r($margs);
//        print_r($its);

        if ($margs['return_only_items'] == 'on') {
            return $its;
        }


//        print_r($query->posts);


//        echo 'its in showcase - '; print_rr($its,array('encode_html'=>true));

        // -- we need permalink, thumbnail
        $fout .= $this->parse_items_showcase($its, $margs);


        if ($margs['type'] == 'layouter') {

        }


//        print_r($its);

        wp_enqueue_style('dzsvg_showcase', $this->thepath . 'front-dzsvp.css');
        wp_enqueue_style('dzstabsandaccordions', $this->thepath . 'libs/dzstabsandaccordions/dzstabsandaccordions.css');
        wp_enqueue_script('dzstabsandaccordions', $this->thepath . "libs/dzstabsandaccordions/dzstabsandaccordions.js");
        return $fout;


        //echo $k;
    }


    function transform_to_array_for_parse($argits, $pargs = array()) {

        global $post;
        $margs = array(
            'type' => 'video_items',
            'mode' => 'posts',
        );

        if (!is_array($pargs)) {
            $pargs = array();
        }
        $margs = array_merge($margs, $pargs);


        $its = array();


//        print_r($argits);

        foreach ($argits as $it) {


//            print_r($it);


            $aux25 = array();

            $aux25['extra_classes'] = '';


            if ($margs['type'] == 'video_items') {
                $it_id = $it->ID;
                $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($it_id), "full");
//                echo 'ceva'; print_r($imgsrc);


//            print_r($author_data);


                if ($imgsrc) {

                    if (is_array($imgsrc)) {
                        $aux25['thumbnail'] = $imgsrc[0];
                    } else {
                        $aux25['thumbnail'] = $imgsrc;
                    }

                } else {
                    if (get_post_meta($it_id, 'dzsvp_thumb', true)) {
                        $aux25['thumbnail'] = get_post_meta($it_id, 'dzsvp_thumb', true);
                    }else{
	                    if (get_post_meta($it_id, 'dzsvg_meta_thumb', true)) {
		                    $aux25['thumbnail'] = get_post_meta($it_id, 'dzsvg_meta_thumb', true);
	                    }
                    }
                }


                $aux25['type'] = get_post_meta($it_id, 'dzsvp_item_type', true);
                $aux25['date'] = $it->post_date;


//                print_r($margs);

                if(isset($margs['orderby'])){

                    if($margs['orderby']=='views'){

                        $aux25['views'] = $this->get_views($it_id);
                    }
                }


                $aux = get_post_meta($it_id, 'dzsvg_meta_featured_media', true);
                $aux25['source'] = $aux;

                if ($aux25['type'] == 'youtube') {
//                    echo ' aux - '.$aux;
//                    $ceva = DZSHelpers::get_query_arg("https://www.youtube.com/watch?dada=alceva&v=MozX3qFIkp", 'va');
                    if (strpos($aux, 'youtube.com') !== false) {


                        $aux = DZSHelpers::get_query_arg($aux, 'v');


//                        echo ' aux - '.$aux;
                        $aux25['source'] = $aux;

                    }
                }

                $aux25['title'] = $it->post_title;
                $aux25['id'] = $it_id;


                $aux25['permalink'] = get_permalink($it_id);
                $aux25['permalink_to_post'] = get_permalink($it_id);

                if ($margs['linking_type'] == 'zoombox') {
                    $aux25['permalink'] = $aux25['source'];
                }

//                print_r($margs);


                if($margs['type']=='video_items'){
//                    print_r($it);

                    $args = array('orderby' => 'name', 'order' => 'ASC', 'fields' => 'all');

                    $terms = wp_get_post_terms( $it_id, 'dzsvideo_category', $args );

//                    print_r($terms);


                    $str_cats = '';
                    if(count($terms)){
                    }
                    foreach ($terms as $term){
                        if($str_cats){ $str_cats.=','; }
//                        $str_cats.=$term->name;
                        $str_cats.=$term->term_id;
                    }


                    $aux25['cats']=$str_cats;
                }
//                print_r($it);


                $maxlen = $margs['desc_count'];

//            print_r($margs);

                if ($maxlen == 'default') {

                    if ($margs['mode'] == 'scrollmenu') {
                        $maxlen = 50;
                    }
                }
                if ($maxlen == 'default') {
                    $maxlen = 100;
                }


                if ($margs['desc_readmore_markup'] == 'default') {
                    if ($margs['mode'] == 'scrollmenu') {
                        $margs['desc_readmore_markup'] = ' <span style="opacity:0.75;">[...]</span>';
                    }
                }
                if ($margs['desc_readmore_markup'] == 'default') {
                    $margs['desc_readmore_markup'] = '';
                }


                $aux25['description'] = wp_kses($this->sanitize_description($it->post_content, array('desc_count' => intval($maxlen), 'striptags' => 'off', 'try_to_close_unclosed_tags' => 'off', 'desc_readmore_markup' => $margs['desc_readmore_markup'],)),$this->allowed_tags);
//                $aux25['description'] = $this->sanitize_description($it->post_content, array('desc_count' => intval($maxlen), 'striptags' => 'on', 'try_to_close_unclosed_tags' => 'on', 'desc_readmore_markup' => $margs['desc_readmore_markup'],));


                if ($post && $post->ID === $it_id) {
                    $aux25['extra_classes'] .= ' active';
                }

                array_push($its, $aux25);
            }


        }


        return $its;

    }


    function parse_items_showcase($its, $pargs) {
        global $post;
        $fout = '';

        $margs = $pargs;
        $this->sliders_index++;

//        print_r($its);

        $slider_index = $this->sliders_index;


        $skin_vp = 'skin_aurora';
        $vpsettings = array();


//        echo 'its - '; print_r($its);
        if ($margs['vpconfig']) {


            if (isset($its) == false || is_array($its) == false) {
                $its = array();
            }
            if (isset($its['settings']) == false || is_array($its['settings']) == false) {
                $its['settings'] = array();
            }
            if (isset($its['settings']['vpconfig']) == false) {
                $its['settings']['vpconfig'] = 'default';
            }


//            error_log(print_rr($its, array( 'echo'=>false)) );

            $vpsettings = $this->get_vpsettings($its['settings']['vpconfig']);


            if (is_array($its['settings']) == false) {
                $its['settings'] = array();
            }


            if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom') {
                $skin_vp = 'skin_pro';
            } else {
                if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom_aurora') {
                    $skin_vp = 'skin_aurora';
                } else {

                    $skin_vp = $vpsettings['settings']['skin_html5vp'];
                }
            }

        }


//        print_r($margs);

        if ($margs['mode'] == 'ullist') {
            $fout .= '<ul class="dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
        }

        if ($margs['mode'] == 'list') {
            $fout .= '<div class="dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
        }
        if ($margs['mode'] == 'scroller') {

            wp_enqueue_style('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.css');
            wp_enqueue_script('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.js');

            $fout .= '<div id="dzsvpas' . $slider_index . '" class="advancedscroller auto-height item-padding-20 skin-black dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
            $fout .= '<ul class="items">';
        }
        if ($margs['mode'] == 'scrollmenu') {

            wp_enqueue_style('dzs.scroller', $this->thepath . 'assets/dzsscroller/scroller.css');
            wp_enqueue_script('dzs.scroller', $this->thepath . 'assets/dzsscroller/scroller.js');

            $fout .= '<div  class="dzs_slideshow_' . $slider_index . ' scroller-con skin_royale scrollbars-inset  dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '"  style="width: 100%;	height: ' . $margs['mode_scrollmenu_height'] . 'px;" data-options="">';
            $fout .= '<div class="inner" style=""><div class="gallery-items skin-viva">';
        }
        if ($margs['mode'] == 'featured') {

            wp_enqueue_style('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.css');
            wp_enqueue_script('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.js');


            $fout .= '<div class="real-showcase-featured dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
            $fout .= '<div class=" dzspb_lay_con">';
            $fout .= '<div class="dzspb_layb_two_third" style="    float: none;
    display: inline-block;
    vertical-align: middle;">';
            $fout .= '<div id="dzsvpas' . $slider_index . '" class="advancedscroller skin-inset auto-height" >';
            $fout .= '<ul class="items">';
        }
        if ($margs['mode'] == 'layouter') {

            wp_enqueue_style('dzs.layouter', $this->thepath . 'assets/dzslayouter/dzslayouter.css');
            wp_enqueue_script('dzs.layouter', $this->thepath . 'assets/dzslayouter/dzslayouter.js');
            wp_enqueue_script('masonry', $this->thepath . 'assets/dzslayouter/masonry.pkgd.min.js');


            $fout .= '<div class="dzslayouter auto-init skin-loading-grey transition-fade hover-arcana" style="" data-options="{prefferedclass: \'wides\', settings_overwrite_margin: \'0\', settings_lazyload: \'on\'}"><ul class="the-items-feed">';
        }
        $taxonomy= 'dzsvideo_category';
        if ($margs['mode'] == 'zfolio') {


            wp_enqueue_style('zfolio', $this->thepath . 'libs/zfolio/zfolio.css');
            wp_enqueue_script('zfolio', $this->thepath . 'libs/zfolio/zfolio.js');
            wp_enqueue_style('zoombox', $this->thepath . 'assets/zoombox/zoombox.css');
            wp_enqueue_script('zoombox', $this->thepath . 'assets/zoombox/zoombox.js');
            wp_enqueue_script('zfolio.isotope', $this->thepath . 'libs/zfolio/jquery.isotope.min.js');


            $fout .= '<div class="zfolio zfolio' . $slider_index . ' ' . $margs['mode_zfolio_skin'] . '  delay-effects  ';

            if ($margs['mode_zfolio_layout'] == '5columns') {
                $fout .= ' dzs-layout--5-cols';
            }
            if ($margs['mode_zfolio_layout'] == '4columns') {
                $fout .= ' dzs-layout--4-cols';
            }
            if ($margs['mode_zfolio_layout'] == '3columns') {
                $fout .= ' dzs-layout--3-cols';
            }
            if ($margs['mode_zfolio_layout'] == '2columns') {
                $fout .= ' dzs-layout--2-cols';
            }
            if ($margs['mode_zfolio_layout'] == '1column') {
                $fout .= ' dzs-layout--1-cols';
            }

            $fout .= '"';

            if ($margs['mode_zfolio_gap'] == '1px') {
                $fout .= ' data-margin="1"';
            }

            $fout .= ' data-options=\'\'>
 
 ';





//                print_r($margs);
                if($margs['mode_zfolio_show_filters']=='on'){



                    $cas = array();



                    if(isset($margs['cat']) && $margs['cat']){


                        $cas = explode(',',$margs['cat']);


//                        print_rr($cas);

                    }else{
                        if($margs['mode_zfolio_show_filters']=='on'){

                            $cas = get_terms( array(
                                'taxonomy' => $taxonomy,
                                'hide_empty' => true,
                            ) );


//                            echo 'cas - ';print_r($cas);
                        }
                    }

                    
//                    print_rr($cas);
                    foreach($cas as $ca){



	                    $ca = $this->sanitize_term_slug_to_id($ca);
                        
                        $cat = get_term($ca, $taxonomy);





                        if(isset($cat->term_id)){

                            $fout.='
                    <div class="feed-zfolio-zfolio-term" data-termid="'.$cat->term_id.'">'.$cat->name.'</div>
                    ';
                        }
                    }

                }



            $fout.='

                        <div class="items ">

                            ';
        }

//        print_r($margs);


//        print_r($its);

        $ii = 0;

        foreach ($its as $lab => $itval) {


            $it_default = array(

                'thumbnail' => '',
                'author_display_name' => '',
                'type' => 'video',
                'permalink' => '',
                'permalink_selected' => 'default',
                'permalink_to_post' => '',

                'title' => '', 'description' => '', 'extra_classes' => '', 'source' => '', // -- the mp4 link, image source, vimeo id or youtube id ( should already be parsed )
            );

            if ($lab === 'settings') {
                continue;
            }




            $its[$lab] = array_merge($it_default, $itval);

            $it = $its[$lab];

            $str_featuredimage = '';

            if($margs['linking_type']=='zoombox'){

                if ($its[$lab]['permalink']) {

                }else{

                    if ($its[$lab]['source']) {
                        $its[$lab]['permalink'] = $its[$lab]['source'];
                    }
                }
            }

	        if ($its[$lab]['permalink']) {

	        }else{

		        if ($its[$lab]['permalink_to_post']) {
			        $its[$lab]['permalink'] = $its[$lab]['permalink_to_post'];
		        }
	        }

	        if($its[$lab]['type']=='vimeo'){

		        if ($its[$lab]['permalink_to_post']) {
		        }else{

			        $its[$lab]['permalink_to_post'] = $its[$lab]['permalink'];
                }
            }


//	        echo 'it simple - '; print_rr($it);

	        if($its[$lab]['permalink_selected']=='default'){
		        $its[$lab]['permalink_selected'] = $its[$lab]['permalink_to_post'];
            }


	        // -- try to figure out thumbnail START
            if ($its[$lab]['thumbnail']) {
            } else {

                if ($its[$lab]['type'] == 'youtube') {

                    $yt_id = $its[$lab]['source'];;


                    if (strpos($yt_id, 'youtube.com/') !== false) {
                        $yt_id = DZSHelpers::get_query_arg($yt_id, 'v');
//                        print_r($aux_a);
                    }

	                $its[$lab]['thumbnail'] = 'https://img.youtube.com/vi/' . $yt_id . '/0.jpg';




                    if($margs['type']=='video_items'){
//                        print_rr($it);

                        update_post_meta($its[$lab]['id'], 'dzsvp_thumb', 'https://img.youtube.com/vi/' . $yt_id . '/0.jpg');


                    }
                }
                if ($its[$lab]['type'] == 'vimeo') {

                    $yt_id = $its[$lab]['source'];


                    if (strpos($yt_id, 'vimeo.com/') !== false) {
                        $yt_id = DZSHelpers::get_query_arg($yt_id, 'v');
//                        print_r($aux_a);
                    }


                    $hash = unserialize(DZSHelpers::get_contents("https://vimeo.com/api/v2/video/$yt_id.php"));

//                    print_r($hash);
	                $its[$lab]['thumbnail'] = $hash[0]['thumbnail_medium'];

                    if($margs['type']=='video_items'){
//                        print_rr($it);

                        update_post_meta($its[$lab]['id'], 'dzsvp_thumb', $hash[0]['thumbnail_medium']);


                    }
//                    print_rr($margs);
                }
            }

            // -- try to figure out thumbnail END


            if ($margs['desc_readmore_markup'] == 'default') {
                if ($margs['mode'] == 'scrollmenu') {
                    $margs['desc_readmore_markup'] = ' <span style="opacity:0.75;">[...]</span>';
                }
            }

//            print_r($margs);
            if ($margs['desc_readmore_markup'] == 'default') {
                $margs['desc_readmore_markup'] = '';
            }


            $desc = $its[$lab]['description'];


            $maxlen = $margs['desc_count'];

            $desc = DZSHelpers::wp_get_excerpt(-1,array(
                    'content'=>$desc,
                    'maxlen'=>$maxlen,
                    'aftercutcontent_html'=>' [ ... ] ',

            ));

//            echo 'desc - '.$desc;

//            echo $str_featuredimage;

            $extra_attr = ''; // -- extra attr for the blank container elements
            $extra_attr_for_zoombox = ''; // -- extra attr for zoombox ( data-biggallery )
            $extra_classes_for_zoombox = ''; // -- apply zoombox class



            $thumb_url_sanitized = dzs_sanitize_to_url($its[$lab]['thumbnail']);


            if ($margs['linking_type'] == 'zoombox') {
                $extra_classes_for_zoombox .= ' zoombox';
                $extra_attr_for_zoombox .= ' data-type="' . $its[$lab]['type'] . '"  data-biggallery="ullist' . $slider_index . '"  data-biggallerythumbnail="' . $thumb_url_sanitized . '"';


                wp_enqueue_style('dzsvg', $this->thepath . 'videogallery/vplayer.css');
                wp_enqueue_script('dzsvg', $this->thepath . "videogallery/vplayer.js");


            }

            if ($margs['mode'] == 'ullist') {


                if ($margs['linking_type'] == 'zoombox') {
                    $extra_classes_for_zoombox .= ' zoombox';
                    $extra_attr_for_zoombox .= ' data-type="' . $its[$lab]['type'] . '"  data-biggallery="ullist' . $slider_index . '"  data-biggallerythumbnail="' . $thumb_url_sanitized . '"';


                    wp_enqueue_style('dzsvg', $this->thepath . 'videogallery/vplayer.css');
                    wp_enqueue_script('dzsvg', $this->thepath . "videogallery/vplayer.js");


                }

                $fout .= '<li><a class="' . $extra_classes_for_zoombox . '" href="' . $its[$lab]['permalink'] . '"' . $extra_attr_for_zoombox . '>' . $its[$lab]['title'] . '</a></li>';
            }
            // -- ullist END


            if ($margs['mode'] == 'list') {
//                echo 'it - '; print_rr($it);
                $fout .= '<div class="dzsvp-item" data-id="'.$its[$lab]['id'].'">';
                $fout .= '<div class="dzspb_lay_con">';
                if ($its[$lab]['thumbnail']) {

                    $fout .= '<div class="dzspb_layb_one_fourth">';
                    $fout .= '<a class="' . $extra_classes_for_zoombox . '" href="' . $its[$lab]['permalink'] . '"' . $extra_attr_for_zoombox . '>';
                    $fout .= '<img width="100%" src="' . $its[$lab]['thumbnail'] . '" style="width:100%;"/>';
                    $fout .= '</a>';
                    $fout .= '</div>';
                    $fout .= '<div class="dzspb_layb_three_fourth">';
                    $fout .= '<h4 style="margin-top:2px; margin-bottom: 5px;"><a class="' . $extra_classes_for_zoombox . '" href="' . $its[$lab]['permalink'] . '"' . $extra_attr . '>' . $its[$lab]['title'] . '</a></h4>';
                    if ($its[$lab]['author_display_name']) {

                        $fout .= '<p>by <em>' . $its[$lab]['author_display_name'] . '</em></p>';
                    }



                    if($margs['mode_list_enable_view_count']=='on'){

                        wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
                        $fout.='<div class="clear"></div><div class="item-meta-list">';
                        $fout.='<div class="counter-hits">';
                        $fout.='<i class="fa fa-play"></i> <span class="the-label">';
                        $fout.=$this->get_views($its[$lab]['id']). esc_html__(" views",'dzsvg');
                        $fout.='</span>';
                        $fout.='</div>';
                        $fout.='</div>';
                    }

                    $fout .= '<div class="paragraph">' . $desc . '</div>';
                    $fout .= '</div>';
                } else {

                    $fout .= '<div class="dzspb_layb_one_full">';
                    $fout .= '<h4 style="margin-top:2px; margin-bottom: 5px;"><a class="' . $extra_classes_for_zoombox . '" href="' . $it['permalink'] . '"' . $extra_attr_for_zoombox . '>' . $its[$lab]['title'] . '</a></h4>';




                    if ($its[$lab]['author_display_name']) {

                        $fout .= '<p>by <em>' . $its[$lab]['author_display_name'] . '</em></p>';
                    }
	                $fout .= '<div class="paragraph">' . $desc . '</div>';
                    $fout .= '</div>';
                }
                $fout .= '</div>';
                $fout .= '</div>';
            }





            if ($margs['mode'] == 'list-2') {
                $fout .= '<div class="dzsvp-item">';
                $fout .= '<div class="dzspb_lay_con">';

                $fout .= '<div class="dzspb_layb_one_full">';
                $fout .= '<a class="' . $extra_classes_for_zoombox . '" href="' . $it['permalink'] . '"' . $extra_attr_for_zoombox . '>';
                $fout .= '<img width="100%" src="' . $it['thumbnail'] . '" class="fullwidth" style="width:100%;"/>';
                $fout .= '</a>';
                $fout .= '<h4 style="margin-top:2px; margin-bottom: 5px; text-align: center; "><a class="' . $extra_classes_for_zoombox . '" href="' . $it['permalink'] . '"' . $extra_attr . '>' . $it['title'] . '</a></h4>';
                $fout .= '</div>';

                $fout .= '</div>';
                $fout .= '</div>';
            }


            if($margs['linking_type']=='zoombox'){

                wp_enqueue_style('zoombox', $this->thepath . 'assets/zoombox/zoombox.css');
                wp_enqueue_script('zoombox', $this->thepath . 'assets/zoombox/zoombox.js');
            }





            if ($margs['mode'] == 'zfolio') {

                $src = $it['source'];

                if ($it['type'] == 'vimeo') {

                    $src = 'https://vimeo.com/' . $src;
                }


                $zoombox_cls = '';
//                print_r($margs);

                if ($margs['linking_type'] === 'zoombox') {
                    $zoombox_cls = ' zoombox';
                }


                $fout .= '<div class="zfolio-item';


                if ($margs['mode_zfolio_enable_special_layout'] == 'on') {
//                    echo $ii%5;


                    switch ($ii % 5) {
                        case 0:
                            $fout .= ' layout-tall';
                            break;
                        case 1:
                            $fout .= ' layout-big';
                            break;
                        case 2:
                            $fout .= ' layout-wide';
                            break;
                        default:
                            $fout .= ' ';
                            break;
                    }
                }





                if($margs['mode_zfolio_show_filters']=='on'){
                    if(isset($it['cats'])){

                        $cas = explode(',',$it['cats']);


                        foreach($cas as $ca){




                            $cat = get_term($ca, $taxonomy);
//                            print_r($cat);




                            if(isset($cat->term_id)){

                                $fout.=' termid-'.$cat->term_id.'';
                            }
                        }
                    }
                }

//                $fout.=$this->get_post_thumb_src($it['id']);




                $thumb = $it['thumbnail'];


                if($thumb==''){
	                $thumb  = $this->get_post_thumb_src($its[$lab]['id']);
                }


                $permalink = $it['permalink'];

//                print_rr($it);

                if($permalink==''){
                    if($zoombox_cls){
                        $permalink = $it['source'];
                    }
                }

                $fout .= '" data-dzsvgindex="' . $ii . '"   data-overlay_extra_class="" style="" >
                                <div class="zfolio-item--inner">
                                <div class="zfolio-item--inner--inner">
                                <div class="zfolio-item--inner--inner--inner">
                                    <a href="' . $permalink . '" data-type="' . $it['type'] . '" class="the-feature-con ' . $zoombox_cls . '" style="" data-biggallery="zfolio' . $slider_index . '" data-biggallerythumbnail="' . $thumb_url_sanitized . '"><div class="the-feature" style="background-image: url(' . $thumb . ');"></div><div class="the-overlay"></div></a>
                                    <div class="item-meta">';


                $fout.='
                                        <div class="the-title">';


                if($margs['mode_zfolio_title_links_to']=='direct_link' || $margs['mode_zfolio_title_links_to']=='zoombox'){

                    $fout.='<span class="';




                    if($margs['mode_zfolio_title_links_to']=='zoombox'){
                        $fout.=' zoombox';
                    }




                    $fout.='" href="';

                    if($margs['mode_zfolio_title_links_to']=='zoombox'){

                       $fout.=$it['source'];
                    }else{
                        $fout.=get_permalink($it['id']);
                    }


                    $fout.='">';
                }
                $fout.=$it['title'];

                if($margs['mode_zfolio_title_links_to']=='direct_link'){

                    $fout.='</span>';
                }

                $fout.='</div>
                                        <div class="the-desc">' . $it['description'] . '</div>
                                    </div>
                                    <div class="item-meta-secondary">';
                if ($it['author_display_name']) {

                    $fout .= '<div class="s-item-meta"><span class="strong">' . __("Uploader") . ':</span> ' . $it['author_display_name'] . '</div>';
                }
                if (isset($it['upload_date']) && $it['upload_date']) {


                    $d2 = new DateTime($it['upload_date'], new DateTimeZone('Europe/Rome'));
                    $t2 = $d2->getTimestamp();

//                    echo $t2 . ' --- '.current_time('timestamp').' ||| ';

                    $str_date = human_time_diff($t2, current_time('timestamp')) . ' ago';
                    $fout .= '<div class="s-item-meta"><span class="strong">Published:</span> ' . $str_date . '</div>';
                }
                $fout .= '</div><!-- END .item-meta -->
                </div><!-- END .zfolio-item--inner--inner--inner-->
                                </div>
                                </div>



                            </div><!-- END .zfolio-item -->';
            }






            if ($margs['mode'] == 'scrollmenu') {

//                print_r($post);


                $fout .= '<a href="' . $it['permalink'] . '" class="dzsscr-gallery-item';


                $fout .= ' ' . $it['extra_classes'];

                $fout .= '">';


                if ($it['thumbnail']) {
                    $fout .= '<div class="the-thumb" style="background-image:url(' . $it['thumbnail'] . '); "></div>';
                }


                $fout .= '
                        <div class="the-meta">
                            <div class="the-title">' . $it['title'] . '</div>
                            <div class="the-desc">' . $desc . '</div>
                        </div>
                    </a>';

            }






            if ($margs['mode'] == 'scroller') {
                if ($it['thumbnail']) {
                $fout .= '<li class="item-tobe">';

                    $fout .= '<a class="' . $extra_classes_for_zoombox . '" href="' . $its[$lab]['permalink'] . '"' . $extra_attr_for_zoombox . '><img width="100%" class="fullwidth" src="' . $its[$lab]['thumbnail'] . '"/></a>';
                    $fout .= '<h5 class="name"><a href="' . $its[$lab]['permalink'] . '">' . $its[$lab]['title'] . '</a></h5>';


//                    print_rr($val);

                    if ( isset($its[$lab]['author_display_name']) && $its[$lab]['author_display_name']) {
                        $fout .= '<span class="block-extra">' . __('by ', 'dzsvg') . '<strong>' . $it['author_display_name'] . '</strong>' . '</span>';
                    }
                $fout .= '</li>';
                }
            }
            if ($margs['mode'] == 'layouter') {

                $fout .= '<li data-link="' . $it['permalink'] . '" data-src="' . $str_featuredimage . '" ><div class="feed-title">' . $it->post_title . '</div></li>';
            }






            if ($margs['mode'] == 'featured') {
                $fout .= '<li class="item-tobe';
                if ($ii == 0) {
                    $fout .= ' needs-loading';
                }
                $fout .= '">';
                if ($it['thumbnail']) {
//                    print_rr($it);

                    $fout .= '<a class=" featured-thumb-a ' . $extra_classes_for_zoombox . '" href="' . $it['permalink'] . '"><img width="100%" class="fullwidth" src="' . $it['thumbnail'] . '"' . $extra_attr_for_zoombox . '/></a>';
                }
                $fout .= '</li>';
            }



            $ii++;
        }
        // --- END item parse


        if ($margs['mode'] == 'layouter') {
            $fout .= '</ul></div>';
        }

        if ($margs['mode'] == 'ullist') {
            $fout .= '</ul>';
        }
        if ($margs['mode'] == 'list') {
            $fout .= '</div>';
        }
        if ($margs['mode'] == 'scrollmenu') {
            $fout .= '</div>';
            $fout .= '</div>';
            $fout .= '</div>';
            $fout .= '<script>
jQuery(document).ready(function($){
if(window.dzsscr_init){
dzsscr_init(".dzs_slideshow_' . $slider_index . '",{
    settings_skin:\'skin_slider\'
    ,enable_easing:\'on\'
});
}
});</script>';
        }
        if ($margs['mode'] == 'scroller') {
            $fout .= '</ul>';
            $fout .= '</div>';
            $fout .= '<script>
jQuery(document).ready(function($){
dzsas_init("#dzsvpas' . $slider_index . '",{
    settings_swipe: "on"
    ,design_arrowsize: "0"
    ,design_itemwidth: "25%"
});
});</script>';
        }
        if ($margs['mode'] == 'zfolio') {
            $fout .= '</div><div class="zfolio-preloader-circle-con zfolio-preloader-con">
                            <div class="zfolio-preloader-circle"></div>
                        </div>
                    </div>';


            $item_thumb_height = '0.6';
            if ($margs['mode_zfolio_enable_special_layout'] == 'on') {
                $item_thumb_height = '1';
            }

            $fout .= '<script>
jQuery(document).ready(function($){
dzszfl_init(".zfolio' . $slider_index . '",{ design_item_thumb_height:"' . $item_thumb_height . '"
,item_extra_class:""
,selector_con_skin:"selector-con-for-skin-melbourne"
,excerpt_con_transition: "wipe"';






            if($this->mainoptions['translate_all'] && $this->mainoptions['translate_all']!='none'){

                $fout.=',settings_categories_strall:"'.$this->mainoptions['translate_all'].'"';
            }else{


                $fout.=',settings_categories_strall:"'.__("All").'"';

            }
            if($margs['mode_zfolio_default_cat'] && $margs['mode_zfolio_default_cat']!='none'){

                $fout.=',settings_defaultCat:"'.$margs['mode_zfolio_default_cat'].'"';
            }else{



            }



//            print_r($margs);

            if($margs['mode_zfolio_categories_are_links'] && $margs['mode_zfolio_categories_are_links']=='on'){

                $fout.=',settings_useLinksForCategories:"'.$margs['mode_zfolio_categories_are_links'].'"';
            }

            if($margs['mode_zfolio_categories_are_links_ajax'] && $margs['mode_zfolio_categories_are_links_ajax']=='on'){

                $fout.=',settings_useLinksForCategories_enableHistoryApi:"'.$margs['mode_zfolio_categories_are_links_ajax'].'"';
            }



            $fout.='
});
});</script>';
        }



        if ($margs['mode'] == 'featured') {
            $fout .= '</ul>';
            $fout .= '</div>';
            $fout .= '</div>';


            $fout .= '<div id="dzsvpas' . $slider_index . '-secondcon" class="dzspb_layb_one_third dzsas-second-con" style="    float: none;
    display: inline-block;
    vertical-align: middle;">';

             // -- showcase
            $fout .= '<div class="dzsas-second-con--clip">';


            foreach ($its as $it) {
                $fout .= '<div class="item">';
                if(isset($it['title'])){


//                    echo 'it in featured - '; print_rr($it);
	                $fout .= '<h4><a class="featured--link" href="' . $it['permalink_selected'] . '">' . $it['title'] . '</a></h4>';
                }
	            if(isset($it['description'])) {
		            $fout .= '<p>' . $it['description'] . '</p>';
	            }
                $fout .= '</div>';
            }

            $fout .= '</div>';
            $fout .= '</div>';

	        $fout .= '</div>';

            $fout .= '</div>';
            $fout .= '<script>
jQuery(document).ready(function($){
dzsas_init("#dzsvpas' . $slider_index . '",{
            settings_mode: "onlyoneitem"
            ,design_arrowsize: "0"
            ,settings_swipe: "on"
            ,settings_swipeOnDesktopsToo: "on"
            ,settings_slideshow: "on"
            ,settings_slideshowTime: "300"
            ,settings_autoHeight:"on"
            ,settings_transition:"fade"
            ,settings_secondCon: "#dzsvpas' . $slider_index . '-secondcon"
            ,design_bulletspos:"none"
});
});</script>';
        }


        if ($margs['mode'] == 'gallery_view') {


            

            foreach ($margs as $lab=>$val){
                if(strpos($lab,'mode_gallery_view_')===0){
                    $newlab= str_replace('mode_gallery_view_','',$lab);

                    $its['settings'][$newlab]=$val;
                }
            }


            
//            print_rr($margs);
//            print_rr($its);


            return $this->show_shortcode(array(
	            'id' => 'gallery_view'

	            , 'its' => $its  // -- force $its array

            ));








        }


        return $fout;
    }


    function parse_items_view($its, $pargs) {
        global $post;
        $fout = '';

        $margs = $pargs;
        $this->sliders_index++;


        // parse items.. view

        $slider_index = $this->sliders_index;
//        print_r($margs);

        if ($margs['mode'] == 'ullist') {
            $fout .= '<ul class="dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
        }

        if ($margs['mode'] == 'list') {
            $fout .= '<div class="dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
        }
        if ($margs['mode'] == 'scroller') {

            wp_enqueue_style('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.css');
            wp_enqueue_script('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.js');

            $fout .= '<div id="dzsvpas' . $slider_index . '" class="advancedscroller skin-black dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
            $fout .= '<ul class="items">';
        }
        if ($margs['mode'] == 'scrollmenu') {

            wp_enqueue_style('dzs.scroller', $this->thepath . 'assets/dzsscroller/scroller.css');
            wp_enqueue_script('dzs.scroller', $this->thepath . 'assets/dzsscroller/scroller.js');

            $fout .= '<div  class="dzs_slideshow_' . $slider_index . ' scroller-con skin_royale scrollbars-inset  dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '"  style="width: 100%;	height: ' . $margs['mode'] . 'px;" data-options="">';
            $fout .= '<div class="inner" style=""><div class="gallery-items skin-viva">';
        }
        if ($margs['mode'] == 'featured') {

            wp_enqueue_style('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.css');
            wp_enqueue_script('dzs.advancedscroller', $this->thepath . 'assets/advancedscroller/plugin.js');


            $fout .= '<div class=" dzsvp-showcase type-' . $margs['type'] . ' mode-' . $margs['mode'] . '">';
            $fout.='<div class="dzspb_lay_con">';
            $fout .= '<div class="dzspb_layb_two_third">';
            $fout .= '<div id="dzsvpas' . $slider_index . '" class="advancedscroller skin-inset">';
            $fout .= '<ul class="items">';
        }
        if ($margs['mode'] == 'layouter') {

            wp_enqueue_style('dzs.layouter', $this->thepath . 'assets/dzslayouter/dzslayouter.css');
            wp_enqueue_script('dzs.layouter', $this->thepath . 'assets/dzslayouter/dzslayouter.js');
            wp_enqueue_script('masonry', $this->thepath . 'assets/dzslayouter/masonry.pkgd.min.js');


            $fout .= '<div class="dzslayouter auto-init skin-loading-grey transition-fade hover-arcana" style="" data-options="{prefferedclass: \'wides\', settings_overwrite_margin: \'0\', settings_lazyload: \'on\'}"><ul class="the-items-feed">';
        }

//        print_r($its);

        $ii = 0;

        foreach ($its as $it) {


            $it_id = $it->ID;
            $str_featuredimage = '';

            $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($it_id), "full");
//                echo 'ceva'; print_r($imgsrc);

            $author_id = $it->post_author;
            $author_data = get_userdata($author_id);

//            print_r($author_data);


            if ($imgsrc) {

            } else {
                if (get_post_meta($it_id, 'dzsvp_thumb', true)) {
                    $imgsrc = get_post_meta($it_id, 'dzsvp_thumb', true);
                }
            }


            if ($imgsrc) {
                if (is_array($imgsrc)) {
                    $str_featuredimage = $imgsrc[0];
                } else {
                    $str_featuredimage = $imgsrc;
                }

            } else {

                if (get_post_meta($it_id, 'dzsvp_item_type', true) == 'youtube') {

                    $yt_id = get_post_meta($it_id, 'dzsvp_featured_media', true);


                    if (strpos($yt_id, 'youtube.com/') !== false) {
                        $yt_id = DZSHelpers::get_query_arg($yt_id, 'v');
//                        print_r($aux_a);
                    }

                    $str_featuredimage = 'https://img.youtube.com/vi/' . $yt_id . '/0.jpg';
                }
                if (get_post_meta($it_id, 'dzsvp_item_type', true) == 'vimeo') {

                    $yt_id = get_post_meta($it_id, 'dzsvp_featured_media', true);


                    if (strpos($yt_id, 'vimeo.com/') !== false) {
                        $yt_id = DZSHelpers::get_query_arg($yt_id, 'v');
//                        print_r($aux_a);
                    }


                    $hash = unserialize(DZSHelpers::get_contents("https://vimeo.com/api/v2/video/$yt_id.php"));

//                    print_r($hash);
                    $str_featuredimage = $hash[0]['thumbnail_medium'];
                }
            }

            $maxlen = $margs['desc_count'];

//            print_r($margs);

            if ($maxlen == 'default') {

                if ($margs['mode'] == 'scrollmenu') {
                    $maxlen = 50;
                }
            }
            if ($maxlen == 'default') {
                $maxlen = 100;
            }

//            print_r($margs);
            if ($margs['desc_readmore_markup'] == 'default') {
                if ($margs['mode'] == 'scrollmenu') {
                    $margs['desc_readmore_markup'] = ' <span style="opacity:0.75;">[...]</span>';
                }
            }
            if ($margs['desc_readmore_markup'] == 'default') {
                $margs['desc_readmore_markup'] = '';
            }


            $desc = $this->sanitize_description($it->post_content, array('desc_count' => intval($maxlen), 'striptags' => 'on', 'try_to_close_unclosed_tags' => 'on', 'desc_readmore_markup' => $margs['desc_readmore_markup'],));
//            echo $str_featuredimage;

            if ($margs['mode'] == 'ullist') {
                $fout .= '<li><a href="' . get_permalink($it_id) . '">' . $it->post_title . '</a></li>';
            }
            if ($margs['mode'] == 'list') {
                $fout .= '<div class="dzsvp-item">';
                $fout .= '<div class="dzspb_lay_con">';
                if ($str_featuredimage) {

                    $fout .= '<div class="dzspb_layb_one_fourth">';
                    $fout .= '<a href="' . get_permalink($it_id) . '">';
                    $fout .= '<img src="' . $str_featuredimage . '" style="width:100%;"/>';
                    $fout .= '</a>';
                    $fout .= '</div>';
                    $fout .= '<div class="dzspb_layb_three_fourth">';
                    $fout .= '<h4 style="margin-top:2px; margin-bottom: 5px;"><a href="' . get_permalink($it_id) . '">' . $it->post_title . '</a></h4>';
                    $fout .= '<p>by <em>' . $author_data->display_name . '</em></p>';
                    $fout .= '<p>' . $it->post_content . '</p>';
                    $fout .= '</div>';
                } else {

                    $fout .= '<div class="dzspb_layb_one_full">';
                    $fout .= '<h4 style="margin-top:2px; margin-bottom: 5px;"><a href="' . get_permalink($it_id) . '">' . $it->post_title . '</a></h4>';
                    $fout .= '<p>by <em>' . $author_data->display_name . '</em></p>';
                    $fout .= '<p>' . $it->post_content . '</p>';
                    $fout .= '</div>';
                }
                $fout .= '</div>';
                $fout .= '</div>';
            }
            if ($margs['mode'] == 'list-2') {
                $fout .= '<div class="dzsvp-item">';
                $fout .= '<div class="dzspb_lay_con">';

                $fout .= '<div class="dzspb_layb_one_full">';
                $fout .= '<p><a href="' . get_permalink($it_id) . '">';
                $fout .= '<img width="100%" src="' . $str_featuredimage . '" style="width:100%;"/>';
                $fout .= '</a></p>';
                $fout .= '<h4 style="margin-top:2px; margin-bottom: 5px; text-align: center; "><a href="' . get_permalink($it_id) . '">' . $it->post_title . '</a></h4>';
                $fout .= '</div>';

                $fout .= '</div>';
                $fout .= '</div>';
            }


            if ($margs['mode'] == 'scrollmenu') {

//                print_r($post);


                $fout .= '<a href="' . get_permalink($it_id) . '" class="dzsscr-gallery-item';


                if ($post && $post->ID === $it_id) {
                    $fout .= ' active';
                }

                $fout .= '">';


                if ($str_featuredimage) {
                    $fout .= '<div class="the-thumb" style="background-image:url(' . $str_featuredimage . '); "></div>';
                }


                $fout .= '
                        <div class="the-meta">
                            <div class="the-title">' . $it->post_title . '</div>
                            <div class="the-desc">' . $desc . '</div>
                        </div>
                    </a>';

            }
            if ($margs['mode'] == 'scroller') {
                $fout .= '<li class="item-tobe">';
                if ($str_featuredimage) {

                    $fout .= '<a href="' . get_permalink($it_id) . '"><img class="fullwidth" src="' . $str_featuredimage . '"/></a>';
                    $fout .= '<h5 class="name"><a href="' . get_permalink($it_id) . '">' . $it->post_title . '</a></h5>';
                }
                $fout .= '</li>';
            }
            if ($margs['mode'] == 'layouter') {

                $fout .= '<li data-link="' . get_permalink($it_id) . '" data-src="' . $str_featuredimage . '" ><div class="feed-title">' . $it->post_title . '</div></li>';
            }


            if ($margs['mode'] == 'featured') {
                $fout .= '<li class="item-tobe';
                if ($ii == 0) {
                    $fout .= ' needs-loading';
                }
                $fout .= '">';
                if ($str_featuredimage) {

                    $fout .= '<a href="' . get_permalink($it_id) . '"><img class="fullwidth" src="' . $str_featuredimage . '"/></a>';
                }
                $fout .= '</li>';
            }

            $ii++;
        }


        if ($margs['mode'] == 'layouter') {
            $fout .= '</ul></div>';
        }

        if ($margs['mode'] == 'ullist') {
            $fout .= '</ul>';
        }
        if ($margs['mode'] == 'list') {
            $fout .= '</div>';
        }
        if ($margs['mode'] == 'scrollmenu') {
            $fout .= '</div>';
            $fout .= '</div>';
            $fout .= '</div>';
            $fout .= '<script>
jQuery(document).ready(function($){
if(window.dzsscr_init){
dzsscr_init(".dzs_slideshow_' . $slider_index . '",{
    settings_skin:\'skin_slider\',
    settings_replacewheelxwithy:\'on\'
    ,enable_easing:\'on\'
});
}
});</script>';
        }
        if ($margs['mode'] == 'scroller') {
            $fout .= '</ul>';
            $fout .= '</div>';
            $fout .= '<script>
jQuery(document).ready(function($){
dzsas_init("#dzsvpas' . $slider_index . '",{
    settings_swipe: "on"
    ,design_arrowsize: "0"
    ,design_itemwidth: "200"
});
});</script>';
        }
        if ($margs['mode'] == 'featured') {
            $fout .= '</ul>';
            $fout .= '</div>';
            $fout .= '</div>';
            $fout .= '</div>';


            $fout .= '<div id="dzsvpas' . $slider_index . '-secondcon" class="dzspb_layb_one_third dzsas-second-con">';
            $fout .= '<div class="dzsas-second-con--clip">';


            foreach ($its as $it) {
                $fout .= '<div class="item">';
                $fout .= '<h4><a href="' . get_permalink($it->ID) . '">' . $it->post_title . '</a></h4>';
                $fout .= '<p>' . $it->post_content . '</p>';
                $fout .= '</div>';
            }

            $fout .= '</div>';
            $fout .= '</div>';


            $fout .= '</div>';
            $fout .= '<script>
jQuery(document).ready(function($){
dzsas_init("#dzsvpas' . $slider_index . '",{
            settings_mode: "onlyoneitem"
            ,design_arrowsize: "0"
            ,settings_swipe: "on"
            ,settings_swipeOnDesktopsToo: "on"
            ,settings_slideshow: "on"
            ,settings_slideshowTime: "300"
            ,settings_autoHeight:"on"
            ,settings_transition:"fade"
            ,settings_secondCon: "#dzsvpas' . $slider_index . '-secondcon"
            ,design_bulletspos:"none"
});
});</script>';
        }


        return $fout;
    }


    function filter_the_content($content) {
        global $post, $dzsvg, $current_user;
        $po_id = $post->ID;

        $this->sw_content_added = false;

        $fout = '';

        $nr_views = 0;

        if (isset($_POST['dzsvp-upload-video-confirmer']) && $_POST['dzsvp-upload-video-confirmer'] == 'Submit') {
            echo('<script>window.location.href="' . admin_url('edit.php?post_type=dzsvideo') . '";</script>');
        }


        if ($post->post_type == 'dzsvideo' && get_post_meta($po_id, 'dzsvg_meta_featured_media', true) != '') {

            $fout .= $this->parse_videoitem($post, array('call_from' => 'post',));


//            wp_enqueue_style('dzsvg_showcase', $this->thepath . 'front-dzsvp.css');
            wp_enqueue_style('dzsvg_showcase', $this->thepath . 'front-dzsvp.css');
            wp_enqueue_script('dzsvg_showcase', $this->thepath . 'front-dzsvp.js');


        }

        if (!$this->sw_content_added) {

            $fout .= $content;
        }

//            echo 'ceva '.$po_id.' '.$dzsvg->mainoptions['dzsvp_page_upload'];
//            print_r($post);
//            print_r($dzsvg);


        // -- page upload
        if ($post->post_type == 'page' && $dzsvg->mainoptions['dzsvp_page_upload'] != '') {
            if ($po_id == $dzsvg->mainoptions['dzsvp_page_upload']) {
//                echo 'yes';
                wp_enqueue_style('dzsvg_showcase', $this->thepath . 'front-dzsvp.css');
            }
        }

        return $fout;
    }


    function register_links() {

        global $dzsvg;








	    $labels = array(
		    'name'              => esc_html__( 'Video galleries', 'dzsvg' ),
		    'singular_name'     => esc_html__( 'Video gallery', 'dzsvg' ),
		    'search_items'      => esc_html__( 'Search galleries', 'dzsvg' ),
		    'all_items'         => esc_html__( 'All galleries', 'dzsvg' ),
		    'parent_item'       => esc_html__( 'Parent gallery', 'dzsvg' ),
		    'parent_item_colon' => esc_html__( 'Parent gallery', 'dzsvg' ),
		    'edit_item'         => esc_html__( 'Edit gallery', 'dzsvg' ),
		    'update_item'       => esc_html__( 'Update gallery', 'dzsvg' ),
		    'add_new_item'      => esc_html__( 'Add playlist', 'dzsvg' ),
		    'new_item_name'     => esc_html__( 'New gallery name', 'dzsvg' ),
		    'menu_name'         => esc_html__( 'Galleries', 'dzsvg' ),
	    );


	    register_taxonomy($this->taxname_sliders, 'dzsvideo', array(

		    'label' => __('Playlists', 'dzsvg'),
		    'labels' => $labels,
		    'query_var' => true,
		    'show_ui' => true,
		    'hierarchical' => false,
		    'rewrite' => array('slug' => $this->mainoptions['dzsvg_sliders_rewrite']),
		    'show_in_menu'=>true,
	    ));




        register_taxonomy('dzsvideo_category', 'dzsvideo', array('label' => __('Video Categories', 'dzsvp'), 'query_var' => true, 'show_ui' => true, 'hierarchical' => true, 'rewrite' => array('slug' => $dzsvg->mainoptions['dzsvp_categories_rewrite']),));
        register_taxonomy('dzsvideo_tags', 'dzsvideo', array('label' => __('Video Tags', 'dzsvp'), 'query_var' => true, 'show_ui' => true, 'hierarchical' => false, 'rewrite' => array('slug' => $dzsvg->mainoptions['dzsvp_tags_rewrite']),));


        $labels = array('name' => $this->mainoptions['dzsvp_post_name'], 'singular_name' => $this->mainoptions['dzsvp_post_name_singular'],);

        $permalinks = get_option('dzsvp_permalinks');
        //print_r($permalinks);

        $item_slug_permalink = empty($permalinks['item_base']) ? _x('video', 'slug', 'dzsvp') : $permalinks['item_base'];


        $args = array('labels' => $labels,
                      'public' => true,
                      'has_archive' => true,
                      'hierarchical' => false,
//                      'publicly_queryable' => true,
//                      'exclude_from_search' => false,
                      'supports' => array('title', 'editor', 'author', 'thumbnail', 'post-thumbnail', 'comments', 'custom-fields', 'excerpt'),
                      'rewrite' => array('slug' => $item_slug_permalink),
                      'yarpp_support' => true,
                      'capabilities' => array(),
                      //'taxonomies' => array('categoryportfolio'),
        );
        register_post_type('dzsvideo', $args);
    }


    function permalink_settings() {

        echo wpautop(__('These settings control the permalinks used for products. These settings only apply when <strong>not using "default" permalinks above</strong>.', 'dzsvp'));

        $permalinks = get_option('dzsvp_permalinks');
        $dzsvp_permalink = $permalinks['item_base'];
        //echo 'ceva';

        $item_base = _x('video', 'default-slug', 'dzsvp');

        $structures = array(0 => '', 1 => '/' . trailingslashit($item_base));
        ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th><label><input name="dzsvp_permalink" type="radio" value="<?php echo $structures[0]; ?>"
                                  class="dzsvptog" <?php checked($structures[0], $dzsvp_permalink); ?> /> <?php _e('Default'); ?>
                    </label></th>
                <td><code><?php echo home_url(); ?>/?video=sample-item</code></td>
            </tr>
            <tr>
                <th><label><input name="dzsvp_permalink" type="radio" value="<?php echo $structures[1]; ?>"
                                  class="dzsvptog" <?php checked($structures[1], $dzsvp_permalink); ?> /> <?php _e('Product', 'dzsvp'); ?>
                    </label></th>
                <td><code><?php echo home_url(); ?>/<?php echo $item_base; ?>/sample-item/</code></td>
            </tr>
            <tr>
                <th><label><input name="dzsvp_permalink" id="dzsvp_custom_selection" type="radio" value="custom"
                                  class="tog" <?php checked(in_array($dzsvp_permalink, $structures), false); ?> />
                        <?php _e('Custom Base', 'dzsvp'); ?></label></th>
                <td>
                    <input name="dzsvp_permalink_structure" id="dzsvp_permalink_structure" type="text"
                           value="<?php echo esc_attr($dzsvp_permalink); ?>" class="regular-text code"> <span
                        class="description"><?php _e('Enter a custom base to use. A base <strong>must</strong> be set or WordPress will use default instead.', 'dzsvp'); ?></span>
                </td>
            </tr>
            </tbody>
        </table>
        <script type="text/javascript">
            jQuery(function () {
                jQuery('input.dzsvptog').change(function () {
                    jQuery('#dzsvp_permalink_structure').val(jQuery(this).val());
                });

                jQuery('#dzsvp_permalink_structure').focus(function () {
                    jQuery('#dzsvp_custom_selection').click();
                });
            });
        </script>
        <?php
    }

    function permalink_settings_save() {
        if (!is_admin()) {
            return;
        }

        // We need to save the options ourselves; settings api does not trigger save for the permalinks page
        if (isset($_POST['dzsvp_permalink_structure']) || isset($_POST['dzsvp_category_base']) && isset($_POST['dzsvp_product_permalink'])) {
            // Cat and tag bases

//                                $product_category_slug = dzs_clean($_POST['dzsvp_product_category_slug']);
//                                $product_tag_slug = dzs_clean($_POST['dzsvp_product_tag_slug']);
//                                $product_attribute_slug = dzs_clean($_POST['dzsvp_product_attribute_slug']);

            $permalinks = get_option('dzsvp_permalinks');
            if (!$permalinks) $permalinks = array();

//                                $permalinks['category_base'] = untrailingslashit($dzsvp_product_category_slug);
//                                $permalinks['tag_base'] = untrailingslashit($dzsvp_product_tag_slug);
//                                $permalinks['attribute_base'] = untrailingslashit($dzsvp_product_attribute_slug);
            // Product base
            $product_permalink = dzs_clean($_POST['dzsvp_permalink']);

            if ($product_permalink == 'custom') {
                $product_permalink = dzs_clean($_POST['dzsvp_permalink_structure']);
            } elseif (empty($product_permalink)) {
                $product_permalink = false;
            }

            $permalinks['item_base'] = untrailingslashit($product_permalink);

            update_option('dzsvp_permalinks', $permalinks);
        }
    }





    function admin_init() {


	    $this->item_meta_categories_lng = array(
		    'misc'=>esc_html__("Miscellaneous",'dzsvg'),
		    'extra_html'=>esc_html__("Extra HTML",'dzsvg'),
	    );


        add_meta_box('dzsvg_meta_options', __('DZS Video Gallery Settings'), array($this, 'admin_meta_options'), 'post', 'normal');
        add_meta_box('dzsvg_meta_options', __('DZS Video Gallery Settings'), array($this, 'admin_meta_options'), 'page', 'normal');


        // Add a section to the permalinks page

        if ($this->mainoptions['enable_video_showcase'] == 'on') {
            add_meta_box('dzsvp_meta_options', __('Video Player Settings'), array($this, 'dzsvideo_admin_meta_options'), 'dzsvideo', 'normal');
            add_meta_box('dzsvp_meta_options', __('Video Player Settings'), array($this, 'dzsvideo_admin_meta_options'), 'product', 'normal');
            add_settings_section('dzsvp-permalink', __('Video Items Permalink Base', 'dzsvp'), array($this, 'permalink_settings'), 'permalink');
        }



//	    $this->mainoptions['capabilities_added'] = 'off';

//        print_rr($this->mainoptions);
        if($this->mainoptions['capabilities_added']=='off'){

		        $role = get_role( 'administrator' );

		        // This only works, because it accesses the class instance.
		        // would allow the author to edit others' posts for current theme only
		        $role->add_cap( 'video_gallery_edit_others_galleries' );
		        $role->add_cap( 'video_gallery_edit_own_galleries' );
//		        $role->add_cap( 'video_gallery_edit_options' ); // -- not used anymore
		        $role->add_cap( 'video_gallery_edit_player_configs' );



//            $role->remove_cap( 'upload_files' );


	        $this->mainoptions['capabilities_added'] = 'on';
	        update_option($this->dboptionsname, $this->mainoptions);


        }



	    if(isset($_GET['page']) && $_GET['page']=='dzsvg_menu'){
		    if($this->mainoptions['playlists_mode']=='normal'){


		        // TODO: here
//			    wp_redirect(admin_url('edit-tags.php?taxonomy=dzsvg_sliders&post_type=dzsvideo'));
//			    exit;
		    }
	    }
    }

    function dzsvideo_admin_meta_options() {
        global $post, $wp_version;
        $struct_uploader = '<div class="dzsvg-wordpress-uploader">
<a href="#" class="button-secondary">' . __('Upload', 'dzsvp') . '</a>
</div>';


//        echo 'ceva';
        ?>
        <div class="select-hidden-con">
            <input type="hidden" name="dzs_nonce" value="<?php echo wp_create_nonce('dzs_nonce'); ?>"/>


            <?php

            /*
             *
            <div class="dzs-setting">
                <h4><?php echo __('Featured Media', 'dzsvp'); ?></h4>
                <?php echo $this->misc_input_text('dzsvg_meta_featured_media', array('class' => 'upload-type-video main-source', 'def_value' => '', 'seekval' => get_post_meta($post->ID, 'dzsvg_meta_featured_media', true))); ?>
                <?php echo $struct_uploader; ?>
                <div
                    class='sidenote mode_video'><?php echo __('the path to the location of the mp4 / if you have a ogg for firefox too you can place it in the backup field below', 'dzsvp'); ?></div>
                <div
                    class='sidenote mode_youtube mode_vimeo'><?php echo __('input here the id or the link of the video', 'dzsvp'); ?></div>
                <div class='sidenote mode_inline'><?php echo __('input here any html', 'dzsvp'); ?></div>
            </div>
            <div class="dzs-setting mode_video mode_audio">
                <h4><?php echo __('Featured Media OGG backup', 'dzsvp'); ?></h4>
                <?php echo $this->misc_input_text('dzsvp_sourceogg', array('class' => 'input-big-image', 'def_value' => '', 'seekval' => get_post_meta($post->ID, 'dzsvp_sourceogg', true))); ?>
                <?php echo $struct_uploader; ?>
                <div class='sidenote'><?php echo __('a backup ogg file for html5 streaming', 'dzsvp'); ?></div>
            </div>
             */

            ?>









        <?php
        include_once('class_parts/item-meta.php');

        wp_enqueue_style('dzssel', $this->base_url.'libs/dzsselector/dzsselector.css');
        wp_enqueue_script('dzssel', $this->base_url.'libs/dzsselector/dzsselector.js');
        ?>

        </div>

        <?php
    }

    function admin_meta_options() {
        global $post;
        ?>
        <input type="hidden" name="dzs_nonce" value="<?php echo wp_create_nonce('dzs_nonce'); ?>"/>
        <h4><?php _e("Fullscreen Gallery", 'dzsvg'); ?></h4>
        <select class="textinput styleme" name="dzsvg_fullscreen">
            <option>none</option>
            <?php
            foreach ($this->mainitems as $it) {

                if(isset($it['settings'])){

                }else{
                    continue;
                }
                echo '<option ';
                dzs_checked(get_post_meta($post->ID, 'dzsvg_fullscreen', true), $it['settings']['id'], 'selected');
                echo '>' . $it['settings']['id'] . '</option>';
            }
            ?>
        </select>
        <div class="clear"></div>

        <div class="sidenote">
            <?php echo __('Get a fullscreen gallery in your post / page with a close button.', 'dzsvg'); ?><br/>
        </div>
        <?php
    }

    function admin_meta_save($post_id) {
        global $post;
        if (!$post) {
            return;
        }
        if (isset($post->post_type) && !($post->post_type == 'post' || $post->post_type == 'page' || $post->post_type == 'dzsvideo' || $post->post_type == 'product')) {
            return $post_id;
        }
        /* Check autosave */
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        if (isset($_REQUEST['dzs_nonce'])) {
            $nonce = $_REQUEST['dzs_nonce'];
            if (!wp_verify_nonce($nonce, 'dzs_nonce')) wp_die('Security check');
        }
        if (isset($_POST['dzsvg_fullscreen'])) {
            dzs_savemeta($post->ID, 'dzsvg_fullscreen', $_POST['dzsvg_fullscreen']);
        }
        if (isset($_POST['dzsvg_extras_featured'])) {
            dzs_savemeta($post->ID, 'dzsvg_extras_featured', $_POST['dzsvg_extras_featured']);
        }


        if (is_array($_POST)) {
            $auxa = $_POST;
            foreach ($auxa as $label => $value) {

                //print_r($label); print_r($value);
                if (strpos($label, 'dzsvg_') !== false) {
                    dzs_savemeta($post_id, $label, $value);
                }
            }
        }
    }

    function admin_meta_save_dzsvideo($post_id) {

        global $post;
        if (!$post) {
            return;
        }
        if (isset($post->post_type) && !($post->post_type == 'dzsvideo' || $post->post_type == 'product') ) {
            return $post_id;
        }
        /* Check autosave */
        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return $post_id;
        }
        if (isset($_REQUEST['dzsvp_nonce'])) {
            $nonce = $_REQUEST['dzsvp_nonce'];

            if (!wp_verify_nonce($nonce, 'dzsvp_nonce')) {
                wp_die('Security check');

                error_log("DZS NONCE NOT CORRECT");
            }
        }
        if (is_array($_POST)) {
            $auxa = $_POST;
            foreach ($auxa as $label => $value) {

//                error_log(print_r($label,true));
//	            error_log(print_r($value,true));
	            if (strpos($label, 'dzsvp_') !== false) {
                    dzs_savemeta($post_id, $label, $value);
                }
                if (strpos($label, 'dzsvg_') !== false) {
                    dzs_savemeta($post_id, $label, $value);
//	                error_log('save meta - '.print_r($label,true).' - '.print_r($value,true));
                }
            }
        }
    }




    function ajax_submit_like() {
        global $current_user;

//        print_r($current_user->ID);

        $user_id = -1;
        if ($current_user->ID) {
            $user_id = $current_user->ID;
        }

//        echo $user_id;


        $aux_likes = 0;
        $playerid = '';

        if (isset($_POST['playerid'])) {
            $playerid = $_POST['playerid'];
            $playerid = str_replace('ap', '', $playerid);
        }

        if (get_post_meta($playerid, '_dzsvp_likes', true) != '') {
            $aux_likes = intval(get_post_meta($playerid, '_dzsvp_likes', true));
        }

        $aux_likes = $aux_likes + 1;

        update_post_meta($playerid, '_dzsvp_likes', $aux_likes);

        setcookie("dzsvp_likesubmitted-" . $playerid, '1', time() + 36000, COOKIEPATH);


        if ($user_id > 0) {
            $aux_likes_arr = array();
            $aux_likes_arr_test = get_user_meta($user_id, '_dzsvp_likes');
            if (is_array($aux_likes_arr_test)) {
                $aux_likes_arr = $aux_likes_arr_test;
            };

            if (!in_array($playerid, $aux_likes_arr)) {
                array_push($aux_likes_arr, $playerid);

                update_user_meta($user_id, '_dzsvp_likes', $aux_likes_arr);
            }
        };

//        print_r($_POST);
        echo 'success';
        die();
    }

    function ajax_retract_like() {

        //print_r($_COOKIE);


        $aux_likes = 1;
        $playerid = '';

        if (isset($_POST['playerid'])) {
            $playerid = $_POST['playerid'];
            $playerid = str_replace('ap', '', $playerid);
        }


        if (get_post_meta($playerid, '_dzsvp_likes', true) != '') {
            $aux_likes = intval(get_post_meta($_POST['playerid'], '_dzsvp_likes', true));
        }

        $aux_likes = $aux_likes - 1;

        update_post_meta($playerid, '_dzsvp_likes', $aux_likes);

        setcookie("dzsvp_likesubmitted-" . $playerid, '', time() - 36000, COOKIEPATH);

        echo 'success';
        die();
    }


    public function parse_videoitem($po, $pargs = array()){

        // -- for single custom post type dzsvideo

        global $dzsvg, $current_user;
        $po_id = $po->ID;
        $curr_po = $po;

        $fout = '';

//        print_r($po);


        $margs = array('disable_meta' => 'auto',
            'call_from' => 'default',
            );


        $margs = array_merge($margs, $pargs);
//        print_r($margs);


        $this->sliders_index++;
        $dzsvg->front_scripts();

        $target_playlist = '';
        $target_playlist_startnr = 0;

        //---playlist setup

        if (isset($_GET['dzsvp_user']) && isset($_GET['dzsvp_playlist'])) {
            $target_user_id = $_GET['dzsvp_user'];

            $target_playlists = get_user_meta($target_user_id, 'dzsvp_playlists', true);
            if (is_array($target_playlists)) {
                $target_playlists = json_encode($target_playlists);
            }
            $target_playlists = json_decode($target_playlists, true);

//                print_r($target_playlists);

            foreach ($target_playlists as $pl) {
                if ($pl['name'] == $_GET['dzsvp_playlist']) {
                    $target_playlist = $pl;
                    break;
                }
            }
        }

//            print_r($target_playlist);


        if ($margs['disable_meta'] != 'on') {
            if ($dzsvg->mainoptions['dzsvp_tab_share_content'] != 'on' || $dzsvg->mainoptions['dzsvp_enable_tab_playlist'] == 'on') {
            }
        }


        wp_enqueue_style('dzstabsandaccordions', $this->thepath . 'libs/dzstabsandaccordions/dzstabsandaccordions.css');
        wp_enqueue_script('dzstabsandaccordions', $this->thepath . "libs/dzstabsandaccordions/dzstabsandaccordions.js");


        wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');


        $featured_media = get_post_meta($po_id, 'dzsvg_meta_featured_media', true);


        if($featured_media==''){

            // -- deprecated
	        $featured_media = get_post_meta($po_id, 'dzsvg_meta_featured_media', true);
        }



        $type = 'video';

        if (get_post_meta($po_id, 'dzsvg_meta_item_type', true) != '') {
            $type = get_post_meta($po_id, 'dzsvg_meta_item_type', true);
        }

	    if($type==''){

		    // -- deprecated
		    $featured_media = get_post_meta($po_id, 'dzsvp_item_type', true);
	    }

        $i = 0;
        $vpconfig_k = 0;
        $vpconfig_id = '';


        $vpsettingsdefault = array('id' => 'default', 'skin_html5vp' => 'skin_aurora', 'html5design_controlsopacityon' => '1', 'html5design_controlsopacityout' => '1',
            'defaultvolume' => '',
            'youtube_sdquality' => 'small',
            'youtube_hdquality' => 'hd720',
            'youtube_defaultquality' => 'hd',
            'yt_customskin' => 'on',
            'vimeo_byline' => '0',
            'vimeo_portrait' => '0',
            'vimeo_color' => '',
            'settings_video_overlay' => 'off',
            'settings_disable_mouse_out' => 'off',
            );
        $vpsettings = array();


        $vpconfig_id = $dzsvg->mainoptions['dzsvp_video_config'];

        if ($vpconfig_id != '') {
            //print_r($this->mainvpconfigs);
            for ($i = 0; $i < count($dzsvg->mainvpconfigs); $i++) {
                if ((isset($vpconfig_id)) && ($vpconfig_id == $dzsvg->mainvpconfigs[$i]['settings']['id'])) $vpconfig_k = $i;
            }
            $vpsettings = $dzsvg->mainvpconfigs[$vpconfig_k];


            if (!isset($vpsettings['settings']) || $vpsettings['settings'] == '') {
                $vpsettings['settings'] = array();
            }
        }

        if (!isset($vpsettings['settings']) || (isset($vpsettings['settings']) && !is_array($vpsettings['settings']))) {
            $vpsettings['settings'] = array();
        }

        $vpsettings['settings'] = array_merge($vpsettingsdefault, $vpsettings['settings']);


        $skin_vp = 'skin_aurora';
        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom') {
            $skin_vp = 'skin_pro';
        } else {

            if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom_aurora') {
                $skin_vp = 'skin_aurora';

            } else {

                $skin_vp = $vpsettings['settings']['skin_html5vp'];
            }
        }


//        print_r($vpsettings);

        if ($vpsettings['settings']['skin_html5vp'] == 'skin_custom') {
            $fout .= '<style>';


            $selector = '#mainvpfromvp' . $this->sliders_index;
            $fout .= '#mainvpfromvp' . $this->sliders_index . ' { background-color:' . $dzsvg->mainoptions_dc['background'] . ';} ';
            $fout .=  $selector. ' .cover-image > .the-div-image { background-color:' . $this->mainoptions_dc['background'] . ';} ';
            $fout .= '#mainvpfromvp' . $this->sliders_index . ' .background{ background-color:' . $dzsvg->mainoptions_dc['controls_background'] . ';} ';
            $fout .= '#mainvpfromvp' . $this->sliders_index . ' .scrub-bg{ background-color:' . $dzsvg->mainoptions_dc['scrub_background'] . ';} ';
            $fout .= '#mainvpfromvp' . $this->sliders_index . ' .scrub-buffer{ background-color:' . $dzsvg->mainoptions_dc['scrub_buffer'] . ';} ';
            $fout .= '#mainvpfromvp' . $this->sliders_index . ' .playSimple{ border-left-color:' . $dzsvg->mainoptions_dc['controls_color'] . ';} #mainvpfromvp' . $this->sliders_index . ' .stopSimple .pause-part-1{ background-color: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .stopSimple .pause-part-2{ background-color: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .volumeicon{ background: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .volumeicon:before{ border-right-color: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .volume_static{ background: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .hdbutton-con .hdbutton-normal{ color: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .total-timetext{ color: ' . $dzsvg->mainoptions_dc['controls_color'] . '; } ';
            $fout .= '#mainvpfromvp' . $this->sliders_index . ' .playSimple:hover{ border-left-color: ' . $dzsvg->mainoptions_dc['controls_hover_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .stopSimple:hover .pause-part-1{ background-color: ' . $dzsvg->mainoptions_dc['controls_hover_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .stopSimple:hover .pause-part-2{ background-color: ' . $dzsvg->mainoptions_dc['controls_hover_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .volumeicon:hover{ background: ' . $dzsvg->mainoptions_dc['controls_hover_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .volumeicon:hover:before{ border-right-color: ' . $dzsvg->mainoptions_dc['controls_hover_color'] . '; } ';
            $fout .= '#mainvpfromvp' . $this->sliders_index . ' .volume_active{ background-color: ' . $dzsvg->mainoptions_dc['controls_highlight_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .scrub{ background-color: ' . $dzsvg->mainoptions_dc['controls_highlight_color'] . '; } #mainvpfromvp' . $this->sliders_index . ' .hdbutton-con .hdbutton-hover{ color: ' . $dzsvg->mainoptions_dc['controls_highlight_color'] . '; } ';
            $fout .= '#mainvpfromvp' . $this->sliders_index . ' .curr-timetext{ color: ' . $dzsvg->mainoptions_dc['timetext_curr_color'] . '; } ';
            $fout .= '</style>';
        }



        $target_playlist = '';
        $target_playlist_startnr = 0;

        //---playlist setup

        if (isset($_GET['dzsvp_user']) && isset($_GET['dzsvp_playlist'])) {
            $target_user_id = $_GET['dzsvp_user'];

            $target_playlists = get_user_meta($target_user_id, 'dzsvp_playlists', true);
            if (is_array($target_playlists)) {
                $target_playlists = json_encode($target_playlists);
            }
            $target_playlists = json_decode($target_playlists, true);

//                print_r($target_playlists);

            foreach ($target_playlists as $pl) {
                if ($pl['name'] == $_GET['dzsvp_playlist']) {
                    $target_playlist = $pl;
                    break;
                }
            }
        }



        $fout .= '<div class="mainvp-con">';


        if ($target_playlist) {


            wp_enqueue_style('dzs.scroller', $dzsvg->thepath . 'assets/dzsscroller/scroller.css');
            wp_enqueue_script('dzs.scroller', $dzsvg->thepath . 'assets/dzsscroller/scroller.js');

            $fout .= '<div class="videogallery-con currGallery" style="width:275px; height:300px; float:right; padding-top: 0; padding-bottom: 0;">
<div class="preloader"></div>
<div class="vg-playlist videogallery skin_default" style="width:275px; height:300px;">';


            $i5 = 0;

            global $post;
            foreach ($target_playlist['items'] as $it_id) {
                $it = get_post($it_id);

                $auxsrc = get_permalink($it_id);

                $auxsrc = add_query_arg('dzsvp_user', $_GET['dzsvp_user'], $auxsrc);
                $auxsrc = add_query_arg('dzsvp_playlist', $_GET['dzsvp_playlist'], $auxsrc);



//                echo 'whatt';
//                echo 'hier - ' .$post->ID . ' - '.($it_id).' - '.($post->ID  == $it_id);
//                echo ' - ' .dzs_curr_url() . ' - '.($auxsrc).' - '.strpos(dzs_curr_url(), $auxsrc);
                if ($post->ID  == $it_id) {
                    $target_playlist_startnr = $i5;
                }

                $str_featuredimage = '';

                $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($it_id), "full");
//                echo 'ceva'; print_r($imgsrc);


//                print_r($imgsrc);
                if ($imgsrc) {

                    if(is_array($imgsrc)){
                        $imgsrc= $imgsrc[0];
                    }
                } else {
                    if (get_post_meta($po_id, 'dzsvg_meta_thumb', true)) {
                        $imgsrc = get_post_meta($it_id, 'dzsvg_meta_thumb', true);
                    }else{
	                    if (get_post_meta($po_id, 'dzsvp_thumb', true)) {
		                    $imgsrc = get_post_meta($it_id, 'dzsvp_thumb', true);
	                    }
                    }
                }


                if(get_post_meta($it_id, 'dzsvp_featured_media', true)){

                    update_post_meta('dzsvg_meta_featured_media',get_post_meta($it_id, 'dzsvp_featured_media', true));
	                update_post_meta('dzsvp_featured_media','');
                }
                if(get_post_meta($it_id, 'dzsvp_item_type', true)){

                    update_post_meta('dzsvg_meta_item_type',get_post_meta($it_id, 'dzsvp_item_type', true));
	                update_post_meta('dzsvp_item_type','');
                }

//                $str_featuredimage.='yes';
                if ($imgsrc) {
                    $str_featuredimage = '<img src="' . $imgsrc . '" class="imgblock"  alt="' . $it->post_title . '""/>';
                } else {

                    if (get_post_meta($it_id, 'dzsvg_meta_item_type', true) == 'youtube') {
                        $str_featuredimage = '<img src="https://img.youtube.com/vi/' . get_post_meta($it_id, 'dzsvg_meta_featured_media', true) . '/0.jpg" class="imgblock"/>';
                    }
                }


                $fout .= '<div class="vplayer-tobe" data-videoTitle="' . $it->post_title . '" data-type="link" data-source="' . $auxsrc . '" data-postid="' . $it_id . '" data-player-id="' . $it_id . '"';

                if($dzsvg->mainoptions['videopage_resize_proportional']=='on'){
                    $fout.=' data-responsive_ratio="detect"';
                }

                $fout.='>
<div class="menuDescription">' . $str_featuredimage . '
    <div class="the-title">' . $it->post_title . '</div> ' . $it->post_content . '
</div>
</div>';
                $i5++;
            }


            $fout .= '</div></div>';
            $fout .= '<div class="history-video-element" style="overflow:hidden;">';
        }

        $fout .= '<div id="mainvpfromvp' . $this->sliders_index . '"   data-player-id="' . $po->ID . '" data-postid="' . $po->ID . '" class="vplayer-tobe from-parse-videoitem" data-videoTitle="' . $po->post_title . '" data-type="' . $type . '" data-src="' . $featured_media . '"';



        $aux = 'dzsvg_meta_ad_array';
        if (get_post_meta($po->ID, $aux,true)) {
            $fout .= ' data-ad-array' . '' . '=\'' . (get_post_meta($po->ID, $aux,true)) . '\'';

        }

        $aux = 'dzsvg_meta_play_from';
        if (get_post_meta($po->ID, $aux,true)) {
            $fout .= ' data-playfrom' . '' . '=\'' . (get_post_meta($po->ID, $aux,true)) . '\'';

        }



        $fout.='>';







        $aux = 'dzsvg_meta_subtitle';
        if (get_post_meta($po->ID, $aux,true)) {
            $fil = DZSHelpers::get_contents(get_post_meta($po->ID, $aux,true));
            $fout .= '<div class="subtitles-con-input">' . $fil . '</div>';
        }





        $fout.='</div>';


        if ($target_playlist) {

            $fout .= '</div>'; // end .history-video-element
        }






	    if ($this->mainoptions['analytics_enable'] == 'on') {

		    if(current_user_can('manage_options')){

			    $fout .= '<div class="extra-btns-con">';
			    $fout .= '<span class="btn-zoomsounds stats-btn" data-playerid="'.$po_id.'"><span class="the-icon"><i class="fa fa-tachometer" aria-hidden="true"></i></span><span class="btn-label">'.esc_html__('Stats','dzsvg').'</span></span>';
			    $fout .= '</div>';



			    wp_enqueue_style('dzsvg_showcase', $this->thepath . 'front-dzsvp.css');
			    wp_enqueue_script('dzsvg_showcase', $this->thepath . 'front-dzsvp.js');
		    }




	    }


        if ($margs['disable_meta'] != 'on') {
            if ($dzsvg->mainoptions['dzsvp_enable_likes'] == 'on' || $dzsvg->mainoptions['dzsvp_enable_ratings'] == 'on' || $dzsvg->mainoptions['dzsvp_enable_viewcount'] == 'on' || $dzsvg->mainoptions['dzsvp_enable_likescount'] == 'on' || $dzsvg->mainoptions['dzsvp_enable_ratingscount'] == 'on') {

                $nr_views = 0;
                $fout .= '<div class="extra-html extra-html--videoitem">';
                if ($dzsvg->mainoptions['dzsvp_enable_likes'] == 'on') {
//                print_r($_COOKIE);


                    $fout.='<span class=" btn-zoomsounds btn-like';

                    if (isset($_COOKIE['dzsvp_likesubmitted-' . $po_id]) && $_COOKIE['dzsvp_likesubmitted-' . $po_id] == '1') {
                        $fout .= ' active';
                    }

                    $fout.='"><span class="the-icon"><svg xmlns:svg="https://www.w3.org/2000/svg" xmlns="https://www.w3.org/2000/svg" version="1.0" width="15" height="15" viewBox="0 0 645 700" id="svg2"> <defs id="defs4"></defs> <g id="layer1"> <path d="M 297.29747,550.86823 C 283.52243,535.43191 249.1268,505.33855 220.86277,483.99412 C 137.11867,420.75228 125.72108,411.5999 91.719238,380.29088 C 29.03471,322.57071 2.413622,264.58086 2.5048478,185.95124 C 2.5493594,147.56739 5.1656152,132.77929 15.914734,110.15398 C 34.151433,71.768267 61.014996,43.244667 95.360052,25.799457 C 119.68545,13.443675 131.6827,7.9542046 172.30448,7.7296236 C 214.79777,7.4947896 223.74311,12.449347 248.73919,26.181459 C 279.1637,42.895777 310.47909,78.617167 316.95242,103.99205 L 320.95052,119.66445 L 330.81015,98.079942 C 386.52632,-23.892986 564.40851,-22.06811 626.31244,101.11153 C 645.95011,140.18758 648.10608,223.6247 630.69256,270.6244 C 607.97729,331.93377 565.31255,378.67493 466.68622,450.30098 C 402.0054,497.27462 328.80148,568.34684 323.70555,578.32901 C 317.79007,589.91654 323.42339,580.14491 297.29747,550.86823 z" id="path2417" style=""></path> <g transform="translate(129.28571,-64.285714)" id="g2221"></g> </g> </svg> </span><span class="the-label hide-on-active">'.__("Like").'</span><span class="the-label show-on-active">'.__("Liked").'</span></span>';

                }
                if ($dzsvg->mainoptions['dzsvp_enable_ratings'] == 'on') {

                    $w_rate = 0;
                    if (get_post_meta($po_id, '_dzsvp_rate_index', true)) {
                        $w_rate = floatval(get_post_meta($po_id, '_dzsvp_rate_index', true)) * 122 / 5;
                    }
                    $fout .= '<div class="star-rating-con"><div class="star-rating-bg"></div><div class="star-rating-set-clip" style="width: ' . $w_rate . 'px;"><div class="star-rating-prog"></div></div><div class="star-rating-prog-clip"><div class="star-rating-prog"></div></div></div>';
                }
                if ($dzsvg->mainoptions['videopage_show_views'] == 'on') {
                    $nr_views = $this->get_views($po_id);



                    $fout .= '<div class="counter-hits"><i class="fa fa-eye"></i>  <span class="the-label"> <span class="the-number">' . $nr_views . '</span> <span class="the-label-text">'.__("views").'</span></span></div>';

//                update_post_meta()
                }
                if ($dzsvg->mainoptions['dzsvp_enable_likescount'] == 'on') {
                    $nr_likes = '';
                    if (get_post_meta($po_id, '_dzsvp_likes', true) == '') {
                        $nr_likes .= '0';
                    } else {
                        $nr_likes .= get_post_meta($po_id, '_dzsvp_likes', true);
                    }


                    $fout .= '<div class="counter-likes"><i class="fa fa-heart"></i>  <span class="the-label"> <span class="the-number">' . $nr_likes . '</span> <span class="the-label-text">'.__("likes").'</span></span></div>';
                }
                if ($dzsvg->mainoptions['dzsvp_enable_ratingscount'] == 'on') {
                    $fout .= '<div class="counter-rates"><span class="the-number">';

                    $nr_rates = 0;

//                echo 'cevahmm'.get_post_meta($po_id, '_dzsvp_rate_nr', true);
//                print_r($_COOKIE);
                    if (get_post_meta($po_id, '_dzsvp_rate_nr', true)) {
                        $nr_rates = intval(get_post_meta($po_id, '_dzsvp_rate_nr', true));
                    }

                    $fout .= $nr_rates . '</span> ' . __('ratings', 'dzsvp') . '</div>';
                }
                $fout .= '</div>';
                //<span class="the-number">{{get_plays}}</span> plays</div>
            }

        }


        $fout .= '<script>';




        $this->mainoptions['advanced_videopage_custom_action_contor_10_secs'] = str_replace('{{postid}}',$po_id,$this->mainoptions['advanced_videopage_custom_action_contor_10_secs']);
        $this->mainoptions['advanced_videopage_custom_action_contor_10_secs'] = str_replace('{{userid}}',$current_user->data->ID,$this->mainoptions['advanced_videopage_custom_action_contor_10_secs']);


        
        // TODO: for custom action

//        $nonce = rand(0,10000);
//        $_SESSION['user_'.$current_user->data->ID.'_dzspwo_nonce'] = $nonce;

//        print_r($_SESSION);
//        $this->mainoptions['advanced_videopage_custom_action_contor_10_secs'] = str_replace('{{user_nonce}}',$nonce,$this->mainoptions['advanced_videopage_custom_action_contor_10_secs']);


        if($this->mainoptions['advanced_videopage_custom_action_contor_10_secs']){
            $fout.='window.custom_action_contor_10_secs = function(arg1,arg2){
            '.$this->mainoptions['advanced_videopage_custom_action_contor_10_secs'].'
}; ';
        }


        if ($this->mainoptions['videopage_autoplay_next']=='on') {
            $fout.='
window.video_page_action_video_end = function(arg){ console.info("video end - ", arg);';



            $args = array('post_type' => 'dzsvideo', 'posts_per_page' => -1, 'orderby' => 'date', 'order' => 'DESC',);




//            print_rr($args);


            $query = new WP_Query($args);


            $ind = 0;
            foreach ($query->posts as $por){
//                print_r($po);


                if($por->ID == $po_id){
                    $curr_index = $ind;
                }
                $ind++;
            }



            $target_post_id = 0;

            if(isset($query->posts[$curr_index+1])){
                $target_post_id = $query->posts[$curr_index+1];
            }

            if($this->mainoptions['videopage_autoplay_next_direction']=='reverse'){
                if(isset($query->posts[$curr_index-1])){
                    $target_post_id = $query->posts[$curr_index-1];
                }else{
                    $target_post_id = 0;
                }
            }


            if($target_post_id){
                $fout.=' window.location.href = "'.get_permalink( $target_post_id ).'";';
            }
$fout.='};  ';
        }

        if ($margs['disable_meta'] != 'on') {
            if ($dzsvg->mainoptions['dzsvp_enable_ratings'] == 'on') {
                if (isset($_COOKIE['dzsvp_ratesubmitted-' . $po_id])) {
                    $fout .= 'window.starrating_alreadyrated="' . $_COOKIE['dzsvp_ratesubmitted-' . $po_id] . '";';
                }
            };
        }

        $fout .= 'jQuery(document).ready(function($){ var videoplayersettings = {
autoplay : "'.$dzsvg->mainoptions['videopage_autoplay'].'",
cueVideo : "on",
controls_out_opacity : "' . $vpsettings['settings']['html5design_controlsopacityon'] . '",
controls_normal_opacity : "' . $vpsettings['settings']['html5design_controlsopacityout'] . '"
,settings_hideControls : "off"
,settings_video_overlay : "' . $vpsettings['settings']['settings_video_overlay'] . '"
,settings_disable_mouse_out : "' . $vpsettings['settings']['settings_disable_mouse_out'] . '"
,settings_swfPath : "' . $dzsvg->thepath . 'preview.swf"
,design_skin: "' . $skin_vp . '"';


        if($dzsvg->mainoptions['videopage_resize_proportional']=='on'){

            $fout.='
,responsive_ratio: "detect"';
        }
        if($dzsvg->mainoptions['videopage_autoplay_next']=='on'){

            $fout.='
,action_video_end: window.video_page_action_video_end';
        }
        if($this->mainoptions['advanced_videopage_custom_action_contor_10_secs']){

            $fout.='
,action_video_contor_60secs: window.dzsvg_wp_send_contor_60_secs ';
        }





        $fout .= '};';





	    if ($this->mainoptions['analytics_enable'] == 'on') {


		    $player_index = ''; // -- (only one)
		    $fout .= 'videoplayersettings' . $player_index . '.action_video_view = window.dzsvg_wp_send_view;';

		    $fout .= 'videoplayersettings' . $player_index . '.action_video_contor_60secs = window.dzsvg_wp_send_contor_60_secs;';

	    }

        $fout.='dzsvp_init("#mainvpfromvp' . $this->sliders_index . '",videoplayersettings);';


        if ($dzsvg->mainoptions['track_views'] == 'on' || $dzsvg->mainoptions['videopage_show_views']=='on') {
//            print_r($_COOKIE);
            if (!isset($_COOKIE['dzsvp_viewsubmitted-' . $po_id])) {
                $fout .= 'var data = {
    action: "dzsvp_submit_view",
    postdata: "1",
    playerid: "' . $po_id . '"
};
setTimeout(function(){
$.ajax({
    type: "POST",
    url: dzsvg_settings.ajax_url,
    data: data,
    success: function(response) {
    },
    error:function(arg){
    }
});
},1500); ';

//                    update_post_meta($po_id, '_dzsvp_views', $nr_views);
            };
        }




        if ($margs['disable_meta'] != 'on') {
            ;
        }



        if ($target_playlist) {
            $fout .= 'dzsvg_init(".vg-playlist",{
totalWidth:275
,settings_mode:"normal"
,menuSpace:0
,randomise:"off"
,autoplay :"off"
,cueFirstVideo: "off"
,autoplayNext : "on"
,nav_type: "scroller"
,menuitem_width:275
,menuitem_height:75
,menuitem_space:1
,menu_position:"right"
,transition_type:"fade"
,design_skin: "skin_navtransparent"
,embedCode:""
,shareCode:""
,logo: ""
,responsive: "on"
,design_shadow:"off"
,settings_disableVideo:"on"
,startItem: "' . $target_playlist_startnr . '"
,settings_enableHistory: "off"
,settings_ajax_extraDivs: ""
});';
        }


        $fout .= '});</script>'; // end document ready


        $fout.='<div class="clearboth"></div>';
        $fout .= '</div><!-- end .mainvp-con -->'; // end mainvp-con


        if ($margs['disable_meta'] != 'on') {
            if (($dzsvg->mainoptions['dzsvp_tab_share_content'] == 'on' || $dzsvg->mainoptions['dzsvp_enable_tab_playlist'] == 'on' || $dzsvg->mainoptions['dzsvp_enable_tab_playlist'] == 'on') && !is_post_type_archive('dzsvideo')) {
//            return $fout;

                $fout.='<div class="clearboth"></div>';
                $fout .= '<div class=""></div>
                    <div class="dzs-tabs auto-init dzs-tabs-dzsvp-page skin-default" data-options="{ \'design_tabsposition\' : \'top\'
                ,design_transition: \'slide\'
                ,design_tabswidth: \'default\'
                ,toggle_breakpoint : \''.$dzsvg->mainoptions['dzsvp_tabs_breakpoint'].'\'
                 ,toggle_type: \'accordion\'}">';


//                if ($dzsvg->mainoptions['dzsvp_tab_share_content'] != '' || $dzsvg->mainoptions['dzsvp_enable_tab_playlist'] == 'on') {
//                }

                $fout .= '';
                $fout .= '<div class="dzs-tab-tobe">
                <div class="tab-menu"><i class="fa fa-info"></i> ' . __('About', 'dzsvp') . '</div>';
                $fout .= '<div class="tab-content">';


//                print_r($po);
                $fout .= do_shortcode($po->post_content);
                $this->sw_content_added = true;

                $fout .= '</div>';
                $fout .= '</div>'; //close .dzs-tab-tobe



                $fout.='
                        <div class="dzs-tab-tobe tab-disabled"><div class="tab-menu ">&nbsp;&nbsp;</div><div class="tab-content"></div></div>
';




                if ($dzsvg->mainoptions['dzsvp_tab_share_content'] != '' || $dzsvg->mainoptions['dzsvp_enable_tab_playlist'] == 'on') {


                    // -- we are in video item page

                    if ($dzsvg->mainoptions['dzsvp_tab_share_content'] != '') {






                        $aux_cont = $dzsvg->mainoptions['dzsvp_tab_share_content'];
                        $aux_cont = str_replace('{{currurl}}', urlencode(dzs_curr_url()), $aux_cont);


                        $auxembed = '<iframe src="' . $dzsvg->base_url . 'bridge.php?action=view&dzsvideo=' . $po_id . '" style="width:100%; height:300px; overflow:hidden;" scrolling="no" frameborder="0"></iframe>';

                        $aux_cont = str_replace('{{embedcode}}', htmlentities($auxembed), $aux_cont);





                        $fout .= '<div class="dzs-tab-tobe">
                            <div class="tab-menu with-tooltip">
                                <i class="fa fa-share"></i> ' . __('Share', 'dzsvp') . '
                            </div>
                            <div class="tab-content">
                                <br>'.$aux_cont.'

                            </div>
                        </div>';


                    }

                    ob_start();
                    do_action('dzsvg_extra_tabs_videoitem');

                    $fout.=ob_get_contents();

                    /* perform what you need on $str with str_replace */

                    ob_end_clean();

                    $fout .= '</div><!-- end .dzs-tabs -->'; //close .dzs-tabs

                    $fout .= '<script>
jQuery(document).ready(function($){
dzstaa_init(".dzs-tabs-dzsvp-page",{ \'design_tabsposition\' : \'top\'
                ,design_transition: \'slide\'
                ,design_tabswidth: \'default\'
                ,toggle_breakpoint : \''.$dzsvg->mainoptions['dzsvp_tabs_breakpoint'].'\'
                 ,toggle_type: \'accordion\'});
});</script>';


//                    $fout .= '<script>
//jQuery(document).ready(function($){
//$("#tabsclean").dzstabs({
//design_tabsposition:"top"
//});
//});</script>';
                }
            }
        }












        return $fout;
    }


    function handle_init() {




        $uploadbtnstring = '<button class="button-secondary action upload_file">'.__("Upload",'dzsvg').'</button>';
        $uploadbtnstring_video = '<button class="button-secondary action upload_file only-video upload-type-video">'.__("Upload",'dzsvg').'</button>';



        $this->itemstructure = '<div class="item-con">
            <div class="item-delete">x</div>
            <div class="item-duplicate"></div>
        <div class="item-preview" style="">
        </div>
        <div class="item-settings-con">
        <div class="setting type_all">
            <h4 class="non-underline"><span class="underline">' . __('Type', 'dzsvg') . '*</span>&nbsp;&nbsp;&nbsp;<span class="sidenote">select one from below</span></h4> 
            
            <div class="main-feed-chooser select-hidden-metastyle select-hidden-foritemtype">
                <select class="textinput item-type" data-label="type" name="0-0-type">
            <option>youtube</option>
            <option>video</option>
            <option>vimeo</option>
            <option>audio</option>
            <option>image</option>
            <option>link</option>
            <option>rtmp</option>
            <option>dash</option>
            <option>facebook</option>
            <option>inline</option>
                </select>
                <div class="option-con clearfix">
                    <div class="an-option dzstooltip-con">
                    <div class="an-title">
                    ' . __('YouTube', 'dzsvg') . '
                    </div>
                    <div class="an-desc dzstooltip skin-black arrow-bottom align-left">
                    ' . sprintf(__('Input in the %sSource%s field below the youtube video ID. You can find the id contained in the link to 
                    the video - https://www.youtube.com/watch?v=<strong>ZdETx2j6bdQ</strong> ( for example )', 'dzsvg'),'<strong>','</strong>') . '
                    </div>
                    </div>
                    
                    <div class="an-option  dzstooltip-con">
                    <div class="an-title">
                    ' . __('Self-hosted Video', 'dzsvg') . '
                    </div>
                    <div class="an-desc dzstooltip skin-black arrow-bottom align-left">
                    ' . sprintf(__('Stream videos your own hosted videos. You just have to include two formats of the video you are streaming. In the %sSource%s
                    field you need to include the path to your mp4 formatted video. And in the OGG field there should be the ogg / ogv path, this is not mandatory, 
                    but recommended.', 'dzsvg'),'<strong>','</strong>') . ' <a href="' . $this->thepath . 'readme/index.html#handbrake" target="_blank" class="">Documentation here</a>.
                    </div>
                    </div>
                    
                    <div class="an-option  dzstooltip-con">
                    <div class="an-title">
                    ' . __('Vimeo Video', 'dzsvg') . '
                    </div>
                    <div class="an-desc dzstooltip skin-black arrow-bottom align-left">
                    ' . sprintf(__('Insert in the %sSource%s field the ID of the Vimeo video you want to stream. You can identify the ID easy from the link of the video,
                     for example, here see the bolded part', 'dzsvg'),'<strong>','</strong>') . ' - https://vimeo.com/<strong>55698309</strong>
                    </div>
                    </div>
                    
                    <div class="an-option  dzstooltip-con">
                    <div class="an-title">
                    ' . __('Self-hosted Audio File', 'dzsvg') . '
                    </div>
                    <div class="an-desc dzstooltip skin-black arrow-bottom align-left">
                    ' . __('You need a MP3 format of your audio file and an OGG format. You put their paths in the Source and Html5 Ogg Format fields', 'dzsvg') . '
                    </div>
                    </div>
                    
                    <div class="an-option  dzstooltip-con">
                    <div class="an-title">
                    ' . __('Self-hosted Image File', 'dzsvg') . '
                    </div>
                    <div class="an-desc dzstooltip skin-black arrow-bottom align-left">
                    ' . sprintf(__('Just put in the %sSource%s field the path to your image.', 'dzsvg'),'<strong>','</strong>') . '
                    </div>
                    </div>
                    
                    <div class="an-option  dzstooltip-con">
                    <div class="an-title">
                    ' . __('A link', 'dzsvg') . '
                    </div>
                    <div class="an-desc dzstooltip skin-black arrow-bottom align-left">
                    ' . __('Link where the visitor should go when clicking the menu item.', 'dzsvg') . '
                    </div>
                    </div>
                    
                    <div class="an-option  dzstooltip-con">
                    <div class="an-title">
                    ' . __('RTMP File', 'dzsvg') . '
                    </div>
                    <div class="an-desc dzstooltip skin-black arrow-bottom align-left">
                    ' . sprintf(__('For advanced users - if you have a rtmp server - input the server in the %sStream Server%s from the left and input here in the <strong>Source</strong> the location of the file on the server..', 'dzsvg'),'<strong>','</strong>') . '
                    </div>
                    </div>

                    <div class="an-option  dzstooltip-con">
                    <div class="an-title">
                    ' . __('Dash Mpeg Stream', 'dzsvg') . '
                    </div>
                    <div class="an-desc dzstooltip skin-black arrow-bottom align-left">
                    ' . sprintf(__('Input the link to the manifest file in the %sSource%s field. To use dash, ofcourse, you need some kind of streaming server like Wowza Streaming Server ', 'dzsvg'),'<strong>','</strong>') . '
                    </div>
                    </div>

                    <div class="an-option  dzstooltip-con">
                    <div class="an-title">
                    ' . __('Facebook video', 'dzsvg') . '
                    </div>
                    <div class="an-desc dzstooltip skin-black arrow-bottom align-left">
                    ' . sprintf(__('input the id of a facebook video', 'dzsvg'),'<strong>','</strong>') . '
                    </div>
                    </div>
                    
                    <div class="an-option  dzstooltip-con">
                    <div class="an-title">
                    ' . __('Inline Content', 'dzsvg') . '
                    </div>
                    <div class="an-desc dzstooltip skin-black arrow-bottom align-left">
                    ' . sprintf(__('Insert in the %sSource%s field custom content ( ie. embed from a custom site like dailymotion).', 'dzsvg'),'<strong>','</strong>') . '
                    </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Source', 'dzsvg') . '*
                <div class="info-con">
                <div class="info-icon"></div>
                <div class="sidenote">' . sprintf(__('Below you will enter your video address. If it is a video from YouTube or Vimeo you just need to enter 
                the id of the video in the "Video:" field. The ID is the bolded part https://www.youtube.com/watch?v=%sj_w4Bi0sq_w%s. 
                If it is a local video you just need to write its location there or upload it through the Upload button ( .mp4 / .flv format ).', 'dzsvg'),'<strong>','</strong>') . '
                    </div>
                </div>
            </div>
<textarea class="textinput main-source type_all upload-type-video" data-label="source" name="0-0-source" style="width:320px; height:29px;">Hv7Jxi_wMq4</textarea>' . $uploadbtnstring_video . '
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Manage Ads', 'dzsvg') . '</div>
            <input type="text" class="textinput upload-prev upload-type-video big-field" name="0-0-adarray" value=""/><a class=" button-secondary quick-edit-adarray" href="#" style="cursor:pointer;">'.__("Edit Ads").'</a>
            <div class="sidenote">' . __('input here optional ads at custom times', 'dzsvg') . '</div>
        </div>


        <div class="setting type_link">
            <div class="setting-label">' . __('Link Target', 'dzsvg') . '</div>
            <select class="textinput mainsetting styleme" name="0-settings-link_target">
                <option value="_self">' . __('Open Same Window', 'dzsvg') . '</option>
                <option value="_blank">' . __('Open New Window', 'dzsvg') . '</option>
            </select>
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Loop', 'dzsvg') . '</div>
            <select class="textinput styleme type_all" name="0-0-loop">
            <option>off</option>
            <option>on</option>
            </select>
                <div class="sidenote">' . __('play the video again when in reaches the end', 'dzsvg') . '</div>
        </div>
';


        if(defined('DZSVG_360_ITEM_EXTRA1')){
            $this->itemstructure.=DZSVG_360_ITEM_EXTRA1;
        }

//        $this->itemstructure.=;

        $this->itemstructure.='
        
        
        
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Link Settings', 'dzsvg') . '</div>
<div class="toggle-content">
        <div class="setting type_all">
            <div class="setting-label">' . __('Link', 'dzsvg') . '</div>
            <input type="text" class="textinput upload-prev upload-type-video big-field" name="0-0-link" value=""/><div class="sidenote">'  . sprintf(__('If %sEnable Link Button%s is enabled in the Video Player Configurations, then you can place a link here to appear in the video player buttons'),'<strong>','</strong>') . '</div>
       
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Link Label', 'dzsvg') . '</div>
            <input type="text" class="textinput upload-prev upload-type-video big-field" name="0-0-link_label" value=""/><div class="sidenote">'  . sprintf(__('the link text')) . '</div>
       
        </div>
        
        
        <div class="setting type_all">
            <div class="setting-label">' . __('Link to Product', 'dzsvg') . '</div>
            <input type="text" class="textinput upload-prev upload-type-video big-field" name="0-0-mediaid" value=""/><div class="sidenote">'  . sprintf(__('you can input here a woocommerce product id in order for the  ')) . '</div>
       
        </div>
        
        
        <div class="setting type_normal">
            <div class="setting-label">HTML5 OGG ' . __('Format', 'dzsvg') . '</div>
            <input type="text" class="textinput upload-prev upload-type-video big-field" name="0-0-html5sourceogg" value=""/>' . $uploadbtnstring . '
            <div class="sidenote">' . __('Optional ogg / ogv file', 'dzsvg') . ' / ' . __('Only for the Video or Audio type', 'dzsvg') . '</div>
        </div>
        
        </div>
        </div>
        
<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('Appearance Settings', 'dzsvg') . '</div>
<div class="toggle-content">
        <div class="setting type_all  ">
            <div class="setting-label">' . __('Thumbnail', 'dzsvg') . '</div>
            <input type="text" class="textinput main-thumb" name="0-0-thethumb"/>' . $uploadbtnstring . ' 
                <button class="refresh-main-thumb button-secondary">' . __('Refresh Thumbnail', 'dzsvg') . '</button>
                <div class="sidenote">' . __('Refresh the thumbnail if its a vimeo or youtube video', 'dzsvg') . '</div>
        </div>
        
        
        <div class="setting type_normal">
            <div class="setting-label">' . __('Manage Qualities', 'dzsvg') . '</div>
            <input type="text" class="textinput upload-prev upload-type-video big-field" name="0-0-qualities" value=""/><a class=" button-secondary quick-edit-qualityarray" href="#" style="cursor:pointer;">'.__("Edit Qualities").'</a>
            <div class="sidenote">' . __('input here optional qualities', 'dzsvg') . '</div>
        </div>

        
        <div class="dzs-row">
        <div class="dzs-col-md-6">
            <div class="setting type_all ">
                <div class="setting-label">' . __('Menu Title', 'dzsvg') . '</div>
                <input type="text" class="textinput" name="0-0-title"/>
            </div>
            
        <div class="setting type_all">
            <div class="setting-label">' . __('Play From', 'dzsvg') . '</div>
            <input class="textinput upload-prev" name="0-0-playfrom" value=""/>
            <div class="sidenote">' . __('you can input a number ( seconds ) for the initial play status. or just input "last" for the video to come of where it has last been left', 'dzsvg') . '</div>
        </div>
        </div>
        <div class="dzs-col-md-6">
            <div class="setting type_all ">
                <div class="setting-label">' . __('Video Description', 'dzsvg') . ':</div>
                <textarea class="textinput" name="0-0-description"></textarea>
            </div>
            <div class="setting type_all  ">
                <div class="setting-label">' . __('Menu Description', 'dzsvg') . '</div>
                <textarea class="textinput" name="0-0-menuDescription"></textarea>
                    <div class="sidenote">' . __('This description will appear in the menu', 'dzsvg') . '</div>
            </div>
        </div>
        </div>
        <div class="clear"></div>

            <div class="setting type_all ">
                <div class="setting-label">' . __('Total Duration', 'dzsvg') . '</div>
                <input type="text" class="textinput" name="0-0-total_duration"/>
            </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Preview Image', 'dzsvg') . '</div>
            <input class="textinput upload-prev" name="0-0-audioimage" value=""/>' . $uploadbtnstring . '
            <div class="sidenote">' . __('will be used as the background image for audio type too', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Tags', 'dzsvg') . '</div>
            <input class="textinput tageditor-prev" name="0-0-tags" value=""/><button class="button-secondary btn-tageditor">Tag Editor</button>
            <div class="sidenote">' . __('use the tag editor to generate tags at given times of the video', 'dzsvg') . '</div>
        </div>
        

        <div class="setting type_all">
            <div class="setting-label">' . __('Subtitle File', 'dzsvg') . '</div>
            <input class="textinput upload-prev" name="0-0-subtitle_file" value=""/>' . $uploadbtnstring . '
            <div class="sidenote">' . __('you can upload a srt file for optional captioning on the video - it is recommeded  you rename the .srt file to .html format if you want to use the wordpress uploader ( security issues ) ', 'dzsvg') . '</div>
        </div>
        <div class="setting type_all">
            <div class="setting-label">' . __('Responsive Ratio', 'dzsvg') . '</div>
            <input class="textinput upload-prev" name="0-0-responsive_ratio" value=""/>
            <div class="sidenote">' . __('set a responsive ratio height/ratio 0.5 means that the player height will resize to 0.5 of the gallery width / or just set it to "detect" and it will autocalculate the ratios if it is a self hosted mp4', 'dzsvg') . '</div>
        </div>
</div>
</div>
        
</div><!--end item-settings-con-->
</div>';





//        global $post;
        wp_enqueue_script('jquery');
//        print_r($post);
        if (is_admin()) {
            wp_enqueue_style('dzsvg_admin_global', $this->thepath . 'admin/admin_global.css');
            wp_enqueue_script('dzsvg_admin_global', $this->thepath . 'admin/admin_global.js');
            wp_enqueue_style('dzsulb', $this->thepath . 'libs/ultibox/ultibox.css');
            wp_enqueue_script('dzsulb', $this->thepath . 'libs/ultibox/ultibox.js');


            if ($this->mainoptions['analytics_enable'] == 'on') {

                wp_enqueue_script('google.charts', 'https://www.gstatic.com/charts/loader.js');

                if ($this->mainoptions['analytics_enable_location'] == 'on') {

                    wp_enqueue_script('google.maps', 'https://www.google.com/jsapi');
                }
            }

//            echo '(isset($_GET[\'page\']) && ($_GET[\'page\'] == $this->adminpagename || $_GET[\'page\'] == $this->adminpagename_configs)) - '.(isset($_GET['page']) && ($_GET['page'] == $this->adminpagename || $_GET['page'] == $this->adminpagename_configs));
            if (isset($_GET['page']) && ($_GET['page'] == $this->adminpagename || $_GET['page'] == $this->adminpagename_configs)) {
                if ( (current_user_can($this->capability_admin) || $this->mainoptions['admin_enable_for_users']=='on') && function_exists('wp_enqueue_media')) {
                    wp_enqueue_media();
                }

                $this->admin_scripts();


                wp_enqueue_style('dzs.uploader', $this->thepath . 'admin/dzsuploader/upload.css');
                wp_enqueue_script('dzs.uploader', $this->thepath . "admin/dzsuploader/upload.js");
            }
            if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_designercenter) {
                wp_enqueue_style('dzsvg-dc.style', $this->thepath . 'deploy/designer/style/style.css');
                wp_enqueue_script('dzs.farbtastic', $this->thepath . "admin/colorpicker/farbtastic.js");
                wp_enqueue_style('dzs.farbtastic', $this->thepath . 'admin/colorpicker/farbtastic.css');
                wp_enqueue_script('dzsvg-dc.admin', $this->thepath . 'admin/admin-dc.js');
                wp_enqueue_style('dzsvg', $this->thepath . 'videogallery/vplayer.css');
                wp_enqueue_script('dzsvg', $this->thepath . "videogallery/vplayer.js");


            }





	        if(isset($_GET['taxonomy']) && $_GET['taxonomy']==$this->taxname_sliders){
		        wp_enqueue_script('jquery-ui-sortable');
		        $url = 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css';


		        wp_enqueue_style('dzsselector', $this->base_url.'libs/dzsselector/dzsselector.css');
		        wp_enqueue_script('dzsselector', $this->base_url.'libs/dzsselector/dzsselector.js');


		        wp_enqueue_style('fontawesome',$url);
		        wp_enqueue_style('dzs.tooltip', $this->base_url . 'libs/dzstooltip/dzstooltip.css');


//		        echo 'ceva';


		        wp_enqueue_media();
	        }


	        if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_about) {

                wp_enqueue_style('dzsvg', $this->base_url . 'videogallery/vplayer.css');
                wp_enqueue_script('dzsvg', $this->base_url . "videogallery/vplayer.js");
            }
            if (isset($_GET['page']) && $_GET['page'] == $this->adminpagename_mainoptions) {
                wp_enqueue_style('dzsvg_admin', $this->thepath . 'admin/admin.css');
                wp_enqueue_script('dzsvg_admin', $this->thepath . "admin/admin-mo.js");
                wp_enqueue_script('jquery-ui-core');
                wp_enqueue_script('jquery-ui-sortable');


                wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');


                wp_enqueue_style('dzstabsandaccordions', $this->thepath . 'libs/dzstabsandaccordions/dzstabsandaccordions.css');
                wp_enqueue_script('dzstabsandaccordions', $this->thepath . "libs/dzstabsandaccordions/dzstabsandaccordions.js");


                wp_enqueue_style('dzs.dzscheckbox', $this->thepath . 'assets/dzscheckbox/dzscheckbox.css');


                wp_enqueue_style('dzstoggle', $this->thepath . 'dzstoggle/dzstoggle.css');
                wp_enqueue_script('dzstoggle', $this->thepath . 'dzstoggle/dzstoggle.js');



	            wp_enqueue_style('dzsselector', $this->thepath . 'libs/dzsselector/dzsselector.css');
	            wp_enqueue_script('dzsselector', $this->thepath . 'libs/dzsselector/dzsselector.js');


                if(isset($_GET['dzsvg_shortcode_player_builder']) && $_GET['dzsvg_shortcode_player_builder']=='on'){


                    wp_enqueue_style('dzsvg_shortcode_builder_style', $this->base_url . 'tinymce/popup.css');
                    wp_enqueue_style('dzsvg_shortcode_player_builder_style', $this->base_url . 'shortcodegenerator/generator_player.css');
                    wp_enqueue_script('dzsvg_shortcode_player_builder', $this->base_url . 'shortcodegenerator/generator_player.js');

//                    wp_enqueue_style('dzs.tabsandaccordions', $this->base_url . 'libs/dzstabsandaccordions/dzstabsandaccordions.css');
//                    wp_enqueue_script('dzs.tabsandaccordions', $this->base_url . 'libs/dzstabsandaccordions/dzstabsandaccordions.js');
                    wp_enqueue_media();


                    wp_enqueue_style('dzsulb', $this->base_url . 'libs/ultibox/ultibox.css');
                    wp_enqueue_script('dzsulb', $this->base_url . 'libs/ultibox/ultibox.js');


                    include_once(dirname(__FILE__).'/shortcodegenerator/generator_player.php');
                    define('DONOTCACHEPAGE', true);
                    define('DONOTMINIFY', true);
                }


                if (isset($_GET['dzsvg_shortcode_builder']) && $_GET['dzsvg_shortcode_builder'] == 'on') {

                    wp_enqueue_style('dzsvg_shortcode_builder_style', $this->thepath . 'tinymce/popup.css');
                    wp_enqueue_script('dzsvg_shortcode_builder', $this->thepath . 'tinymce/popup.js');


                    wp_enqueue_style('dzsulb', $this->thepath . 'libs/ultibox/ultibox.css');
                    wp_enqueue_script('dzsulb', $this->thepath . 'libs/ultibox/ultibox.js');


                    wp_enqueue_media();
                }


                if (isset($_GET['dzsvg_shortcode_showcase_builder']) && $_GET['dzsvg_shortcode_showcase_builder'] == 'on') {

                    wp_enqueue_style('dzsvg_shortcode_builder_style', $this->thepath . 'tinymce/popup.css');
                    wp_enqueue_script('dzsvg_shortcode_builder', $this->thepath . 'tinymce/popup_showcase.js');


                    wp_enqueue_style('dzsselector', $this->thepath . 'libs/dzsselector/dzsselector.css');
                    wp_enqueue_script('dzsselector', $this->thepath . 'libs/dzsselector/dzsselector.js');


wp_enqueue_style('dzstooltip', $this->thepath . 'libs/dzstooltip/dzstooltip.css');
wp_enqueue_script('dzstooltip', $this->thepath . 'libs/dzstooltip/dzstooltip.js');


                    wp_enqueue_media();
                }

                if (isset($_GET['dzsvg_reclam_builder']) && $_GET['dzsvg_reclam_builder'] == 'on') {

                    wp_enqueue_style('dzsvg_shortcode_builder_style', $this->thepath . 'tinymce/popup.css');
                    wp_enqueue_style('reclambuilder', $this->thepath . 'admin/reclam-builder/reclam-builder.css');
                    wp_enqueue_script('reclambuilder', $this->thepath . 'admin/reclam-builder/reclam-builder.js');


                    wp_enqueue_style('dzsselector', $this->thepath . 'libs/dzsselector/dzsselector.css');
                    wp_enqueue_script('dzsselector', $this->thepath . 'libs/dzsselector/dzsselector.js');


                    wp_enqueue_style('dzstooltip', $this->thepath . 'libs/dzstooltip/dzstooltip.css');
                    wp_enqueue_script('dzstooltip', $this->thepath . 'libs/dzstooltip/dzstooltip.js');
                    wp_enqueue_media();


                }

                if (isset($_GET['dzsvg_quality_builder']) && $_GET['dzsvg_quality_builder'] == 'on') {

                    wp_enqueue_style('dzsvg_shortcode_builder_style', $this->thepath . 'tinymce/popup.css');
                    wp_enqueue_style('qualitybuilder', $this->thepath . 'admin/quality-builder/quality-builder.css');
                    wp_enqueue_script('qualitybuilder', $this->thepath . 'admin/quality-builder/quality-builder.js');


                    wp_enqueue_style('dzsselector', $this->thepath . 'libs/dzsselector/dzsselector.css');
                    wp_enqueue_script('dzsselector', $this->thepath . 'libs/dzsselector/dzsselector.js');


                    wp_enqueue_style('dzstooltip', $this->thepath . 'libs/dzstooltip/dzstooltip.css');
                    wp_enqueue_script('dzstooltip', $this->thepath . 'libs/dzstooltip/dzstooltip.js');
                    wp_enqueue_media();


                }


            }

            if (current_user_can('video_gallery_edit_own_galleries') || current_user_can('manage_options') ) {

                wp_enqueue_script('dzsvg_htmleditor', $this->thepath . 'tinymce/plugin-htmleditor.js');
                wp_enqueue_script('dzsvg_configreceiver', $this->thepath . 'tinymce/receiver.js');
            }
        } else {
            
            // -- facebook
	        if(isset($_GET['state'])) {
		        session_start();
	        }
	        
	        
            if (isset($this->mainoptions['always_embed']) && $this->mainoptions['always_embed'] == 'on') {
                $this->front_scripts();
            }

            if(defined('DZSVP_VERSION')){

                wp_enqueue_style('dzsvg_showcase', $this->thepath . 'front-dzsvp.css');
                wp_enqueue_script('dzsvg_showcase', $this->thepath . 'front-dzsvp.js');
            }

            wp_enqueue_style('dzsvg', $this->thepath . 'videogallery/vplayer.css');
        }



        if ($this->mainoptions['enable_video_showcase'] == 'on') {
            $this->register_links();


            $this->permalink_settings_save();
        }

        $this->check_posts_init();


        if(function_exists('vc_add_shortcode_param')) {
//            add_shortcode_param('dzsvcs_toggle_begin', 'vc_dzsvcs_toggle_begin' );
//            add_shortcode_param('dzsvcs_toggle_end', 'vc_dzsvcs_toggle_end' );
            vc_add_shortcode_param('dzs_add_media_att', 'vc_dzs_add_media_att');
        }
        include_once($this->base_path . 'vc/part-vcintegration.php');


        add_action('wp_ajax_dzs_update_term_order',array($this,'post_dzs_update_term_order'));



        if($this->plugin_justactivated){

            flush_rewrite_rules();
        }




	    if(isset($_GET['page']) && $_GET['page']=='dzsvg_menu' && (isset($_GET['do_not_redirect'])==false || isset($_GET['do_not_redirect']) && $_GET['do_not_redirect']!='on')){
		    if($this->mainoptions['playlists_mode']=='normal'){

			    wp_redirect(admin_url('edit-tags.php?taxonomy=dzsvg_sliders&post_type=dzsvideo'));
			    exit;
		    }
	    }



    }

    function post_dzs_update_term_order() {

        $auxarray = array();
        //parsing post data
        $arr = json_decode(stripslashes($_POST['postdata']),true);


                print_r($_POST);
                print_r($arr);

        foreach ($arr as $po){

            update_post_meta($po['id'],$_POST['meta_key'], $po['order']);
        }
        die();
    }



    function handle_init_end(){

        if(is_admin()){

            include_once('assets/admin/dzs_term_reorder.php');

            $dzs_term_reorder = new Dzs_Term_Reorder(array('dzsvideo'), array('dzsvideo'=>array(
                'dzsvideo_category'
            )),array('dzsvideo_category'), $this->base_url.'assets/admin/');
        }else{





	        if (isset($_GET['dzsvg_action'])) {


		        if ( $_GET['dzsvg_action'] == 'load_gallery_items_for_pagination' ) {



		            echo do_shortcode('[videogallery id="'.$_GET['gallery_id'].'" settings_separation_mode="scroll"  settings_separation_pages_number="'.$_GET['settings_separation_pages_number'].'" return_mode="parsed items" call_script="off" called_from="ajax_pagination" ]');
		            die();
		        }
	        }

        }

	    error_log('get_option(\'dzsvg_sample_data_installed\') - '.get_option('dzsvg_sample_data_installed'));
	    if( !(get_option('dzsvg_sample_data_installed')) ){


	        $tax = $this->taxname_sliders;
		    $reference_term = get_term_by( 'slug', 'example_youtube_videos', $tax );

		    if($reference_term){

            }else{

			    $file_cont = file_get_contents('sampledata/dzsvg_export_example_youtube_videos.txt',true);

			    error_log('trying to import - '.$file_cont);
			    $sw_import = $this->import_slider($file_cont);

			    $file_cont = file_get_contents('sampledata/dzsvg_export_sample_vimeo_channel.txt',true);
			    $sw_import = $this->import_slider($file_cont);

			    $file_cont = file_get_contents('sampledata/dzsvg_export_sample_wall.txt',true);
			    $sw_import = $this->import_slider($file_cont);

			    $file_cont = file_get_contents('sampledata/dzsvg_export_sample_youtube_user_channel.txt',true);
			    $sw_import = $this->import_slider($file_cont);
            }


		    update_option('dzsvg_sample_data_installed','on');

//	        echo ' $file_cont - '.$file_cont;
//	        echo ' $sw_import - '.$sw_import;
	    }





    }

    function handle_print_media_templates() {

//        if ( ! isset( get_current_screen()->id ) || get_current_screen()->base != 'post' )
//            return;
//        echo 'ceva';
        include_once dirname(__FILE__) . '/admin/visualeditor/tmpl-editor-boutique-banner.html';
    }

	function ajax_send_queue_from_sliders_admin() {

//        print_r($_POST);

		$response = array(
			'report'=>'success',
			'items'=>array(),
		);

		$queue_calls = json_decode(stripslashes($_POST['postdata']), true);

//        error_log('$queue_calls - '.print_r($queue_calls,true));

		foreach ($queue_calls as $qc){

			if($qc['type']=='set_meta_order'){
				foreach($qc['items'] as $it){

					update_post_meta($it['id'], 'dzsvg_meta_order_'.$qc['term_id'],$it['order']);
				}
			}
			if($qc['type']=='set_meta'){

				if($qc['lab']=='the_post_title' || $qc['lab']=='the_post_content'){



					$aferent_lab = $qc['lab'];

					if($qc['lab']){
						$aferent_lab = str_replace('the_','',$aferent_lab);
					}

					$my_post = array(
						'ID'           => $qc['item_id'],
						$aferent_lab   => $qc['val'],

					);

// Update the post into the database
					wp_update_post( $my_post );
				}else{

					update_post_meta($qc['item_id'], $qc['lab'], $qc['val']);
				}

			}
			if($qc['type']=='delete_item'){


				$post_id = $qc['id'];


				$term_list = wp_get_post_terms($post_id, $this->taxname_sliders, array("fields" => "all"));


				$response['report_type']='delete_item';
				$response['report_message']=esc_html__("Item deleted",'dzsvg');



				if(is_array($term_list) && count($term_list)==1){

					wp_delete_post($post_id);
				}else{
					wp_remove_object_terms( $post_id, $qc['term_slug'], $this->taxname_sliders );
				}

			}
			if($qc['type']=='create_item'){

//                print_r($qc);



				$taxonomy = 'dzsvg_sliders';



				$current_user = wp_get_current_user();
				$new_post_author_id = $current_user->ID;


				$args = array(
					'post_title' => __("Insert Name",'dzsvg'),
					'post_content' => 'content here',
					'post_status' => 'publish',
					'post_author' => $new_post_author_id,
					'post_type' => 'dzsvideo',
				);
				if(isset($qc['post_title']) && $qc['post_title']){
					$args['post_title']= $qc['post_title'];

				}

				$sample_post_2_id = wp_insert_post($args);


				error_log('item create - '.print_r($qc,true). ' dzs_sanitize_for_post_terms($qc[\'term_slug\']) - '.dzs_sanitize_for_post_terms($qc['term_slug']));
				if(isset($qc['term_slug']) && $qc['term_slug']){
					wp_set_post_terms( $sample_post_2_id, dzs_sanitize_for_post_terms($qc['term_slug']), $taxonomy );

				}





				foreach ($qc as $lab=>$val){
					if(strpos($lab,'dzsvg_meta')===0){
						update_post_meta($sample_post_2_id,$lab,$val);
					}
				}

//        wp_set_post_terms($sample_post_2_id,$arr_cats[0],$taxonomy);

				array_push($response['items'],array(
					'type'=>'create_item',
					'str'=>$this->sliders_admin_generate_item(get_post($sample_post_2_id)),
				));
			}




			if($qc['type']=='duplicate_item'){

//                print_r($qc);





				$reference_po_id = ($qc['id']);

				$sample_post_2_id = $this->duplicate_post($reference_po_id);


//        wp_set_post_terms($sample_post_2_id,$arr_cats[0],$taxonomy);

				array_push($response['items'],array(
					'type'=>'create_item',
					'original_request'=>'duplicate_item',
					'original_post_id'=>$reference_po_id,
					'str'=>$this->sliders_admin_generate_item(get_post($sample_post_2_id)),
				));
			}
		}

		echo json_encode($response);
		die();
	}


	function sliders_admin_generate_item($po){


		$fout = '';
		$thumb = '';
		$thumb_from_meta = '';
		// -- we need real location, not insert-id
		$struct_uploader = '<div class="dzs-wordpress-uploader ">
    <a href="#" class="button-secondary">' . __('Upload', 'dzsvp') . '</a>
</div>';

		if($po && is_int($po->ID)){

			$thumb = $this->get_post_thumb_src($po->ID);

//            echo ' thumb - ';
//            print_r($thumb);


			$thumb_from_meta = get_post_meta($po->ID, 'dzsvg_meta_thumb',true);
		}

		if($thumb_from_meta){

			$thumb = $thumb_from_meta;
		}

		$thumb_url = '';
		if($thumb){
			$thumb_url = $this->sanitize_id_to_src($thumb);

//                    echo ' thumb - '.$this->sanitize_id_to_src($thumb);
		}



		$fout.= '<div class="slider-item dzstooltip-con for-click';

		if($po->ID=='placeholder'){
			$fout.= ' slider-item--placeholder';
		}

		$fout.= '" data-id="'.$po->ID.'">';

		$fout.= '<div class="divimage" style="background-image:url('.$thumb_url.');"></div>';
		$fout.= '<div class="slider-item--title" >'.$po->post_title.'</div>';

		$fout.='
        <div class="delete-btn item-control-btn"><i class="fa fa-times-circle-o"></i></div>
        <div class="clone-item-btn item-control-btn"><i class="fa fa-clone"></i></div>
        <div class="dzstooltip skin-black transition-fade arrow-top align-center">
            <div class="dzstooltip--selector-top"></div>

            <div class="dzstooltip--content">';





		$fout.='<div class="dzs-tabs dzs-tabs-meta-item  skin-default " data-options=\'{ "design_tabsposition" : "top"
,"design_transition": "fade"
,"design_tabswidth": "default"
,"toggle_breakpoint" : "200"
,"settings_appendWholeContent": "true"
,"toggle_type": "accordion"
}
\' style=\'padding: 0;\'>

                <div class="dzs-tab-tobe">
                    <div class="tab-menu ">'.esc_html__("General",'dzsvg').'
    </div>
    <div class="tab-content tab-content-item-meta-cat-main">

'.$this->sliders_admin_generate_item_meta_cat('main', $po).'
    </div>
    </div>
    ';


		foreach ($this->item_meta_categories_lng as $lab=>$val){


			ob_start();
			?>

            <div class="dzs-tab-tobe">
            <div class="tab-menu ">
				<?php
				echo ($val);
				?>
            </div>
            <div class="tab-content tab-content-cat-<?php echo $lab; ?>">



				<?php
				echo $this->sliders_admin_generate_item_meta_cat($lab, $po);
				?>


            </div>
            </div><?php

			$fout.=ob_get_clean();



		}

		$fout.='</div>';// -- end tabs




		$fout.='
                    </div>';
		$fout.='
                    </div>';
		$fout.='
                    </div>';

		return $fout;
	}


	function duplicate_post($reference_po_id, $pargs=array()){


		$margs = array(
			'new_term_slug'=>'',
			'call_from'=>'default',
			'new_tax'=>'dzsvg_sliders',
		);

		$margs = array_merge($margs,$pargs);

		$reference_po = get_post($reference_po_id);




		$current_user = wp_get_current_user();
		$new_post_author_id = $current_user->ID;

		$args = array(
			'post_title' => $reference_po->post_title,
			'post_content' => $reference_po->post_content,
			'post_status' => 'publish',
			'post_author' => $new_post_author_id,
			'post_type' => $reference_po->post_type,
		);


		$sample_post_2_id = wp_insert_post($args);




		/*
		 * get all current post terms ad set them to the new post draft
		 */
		$taxonomies = get_object_taxonomies($reference_po->post_type); // returns array of taxonomy names for post type, ex array("category", "post_tag");
		foreach ($taxonomies as $taxonomy) {
			if($margs['new_term_slug']){
				if($taxonomy=='dzsvg_sliders'){
					continue;
				}
			}
			$post_terms = wp_get_object_terms($reference_po_id, $taxonomy, array('fields' => 'slugs'));
			wp_set_object_terms($sample_post_2_id, $post_terms, $taxonomy, false);
		}


		// -- for duplicate term
		if($margs['new_term_slug']){

			wp_set_object_terms($sample_post_2_id, $margs['new_term_slug'], $margs['new_tax'], false);
		}else{

		}




		/*
		 * duplicate all post meta just in two SQL queries
		 */
		global $wpdb;
		$sql_query_sel = array();
		$post_meta_infos = $wpdb->get_results("SELECT meta_key, meta_value FROM $wpdb->postmeta WHERE post_id=$reference_po_id");
		if (count($post_meta_infos)!=0) {
			$sql_query = "INSERT INTO $wpdb->postmeta (post_id, meta_key, meta_value) ";
			foreach ($post_meta_infos as $meta_info) {
				$meta_key = $meta_info->meta_key;
				if( $meta_key == '_wp_old_slug' ) continue;
				$meta_value = addslashes($meta_info->meta_value);
				$sql_query_sel[]= "SELECT $sample_post_2_id, '$meta_key', '$meta_value'";
			}
			$sql_query.= implode(" UNION ALL ", $sql_query_sel);
			$wpdb->query($sql_query);
		}

		return $sample_post_2_id;
	}


	function sliders_admin_generate_item_meta_cat($cat, $po){

		$fout = '';
		// -- we need real location, not insert-id
		$struct_uploader = '<div class="dzs-wordpress-uploader ">
    <a href="#" class="button-secondary">' . __('Upload', 'dzsvp') . '</a>
</div>';

		foreach ($this->options_item_meta as $lab => $oim){



			$oim = array_merge(array(
				'category'=>'',
				'extraattr'=>'',
				'default'=>'',
			), $oim);

			if($oim['category']==$cat){

			}else{
				if($cat=='main'){
					if($oim['category']==''){


					}else{
						continue;
					}
				}else{
					continue;
				}
			}



			if($oim['name']=='dzsvg_meta_item_type'){

			    if($this->mainoptions['facebook_app_id']){
			        array_push($oim['choices'],            array(
				        'label'=>__("Facebook"),
				        'value'=>'facebook',
			        ));
			        array_push($oim['choices_html'], '<span class="option-con"><img src="'.$this->base_url.'admin/img/type_facebook.png"/><span class="option-label">'.__("Facebook").'</span></span>');


                }
			}

//			error_log(print_rr($oim,true));


			$fout.='
                    <div class="setting ';
			$option_name = $oim['name'];

			if(isset($oim['setting_extra_classes'])){
				$fout.=' '.$oim['setting_extra_classes'];
			}
			if($oim['type']=='attach'){
				$fout.=' setting-upload';
			}

			$fout.='">';
			$fout.='<h5 class="setting-label">'.$oim['title'].'</h5>';


			$fout.='<div class="input-con">';

			if($oim['type']=='attach'){
				$fout.='<span class="uploader-preview"></span>';
			}


			$val = $oim['default'];

			if(is_int($po->ID)){


			    if($oim['default']){

//			        error_log(print_rr(get_post_meta($po->ID, $option_name),true));
			        $aux = get_post_meta($po->ID, $option_name);
				    if(get_post_meta($po->ID, $option_name)){

				        if(isset($aux[0])){
				            $val = $aux[0];
                        }
                    }
                }else{

				    $val = get_post_meta($po->ID, $option_name, true);
                }
			}

			if($option_name=='the_post_title'){
				$val = $po->post_title;
			}
			if($option_name=='the_post_content'){
				$val = $po->post_content;
			}

			$class = 'setting-field medium';

			if($oim['type']=='attach'){
				$class.=' uploader-target';
			}


			if($oim['type']=='attach') {


				if(isset($oim['upload_type']) && $oim['upload_type']){
					$class.=' upload-type-'.$oim['upload_type'];
				}


//				error_log(print_rr($oim,true));
				if(isset($oim['dom_type']) && $oim['dom_type']=='textarea'){

					$fout.= DZSHelpers::generate_input_textarea($option_name, array(
						'class' => $class,
						'seekval' => $val,
						'extraattr' => ' rows="1"',
					));
				}else{

					$fout.= DZSHelpers::generate_input_text($option_name, array(
						'class' => $class,
						'seekval' => $val,
					));
                }
			}
			if($oim['type']=='text') {
				$fout.= DZSHelpers::generate_input_text($option_name, array(
					'class' => $class,
					'seekval' => $val,
				));
			}
			if($oim['type']=='textarea') {
				$fout.= DZSHelpers::generate_input_textarea($option_name, array(
					'class' => $class,
					'seekval' => $val,
					'extraattr' => $oim['extraattr'],
				));
			}
			if($oim['type']=='select') {


				$class = 'dzs-style-me skin-beige setting-field';

				if(isset($oim['select_type']) && $oim['select_type']){
					$class.=' '.$oim['select_type'];
				}

				$fout.= DZSHelpers::generate_select($option_name, array(
					'class' => $class,
					'seekval' => $val,
					'options' => $oim['choices'],
				));

				if(isset($oim['select_type']) && $oim['select_type']=='opener-listbuttons'){

					$fout.= '<ul class="dzs-style-me-feeder">';

					foreach ($oim['choices_html'] as $oim_html){

						$fout.= '<li>';
						$fout.= $oim_html;
						$fout.= '</li>';
					}

					$fout.= '</ul>';
				}


			}

			if($oim['type']=='attach') {
				$fout.= $struct_uploader;
			}


			if(isset($oim['extra_html_after_input']) && $oim['extra_html_after_input']){
				$fout.= $oim['extra_html_after_input'];
			}

			$fout.='</div>'; // -- end input con


			if(isset($oim['sidenote']) && $oim['sidenote']){
				$fout.= '<div class="sidenote">'.$oim['sidenote'].'</div>';
			}

			$fout.='
                    </div>';



		}


		return $fout;
	}












	function handle_admin_footer() {



		if(isset($_GET['taxonomy']) && $_GET['taxonomy']==$this->taxname_sliders){


			echo '<script>';
			echo 'jQuery(document).ready(function($){';

			echo '$("#toplevel_page_dzsvg_menu, #toplevel_page_dzsvg_menu > a").addClass("wp-has-current-submenu");';
			echo '$("#toplevel_page_dzsvg_menu .wp-first-item").addClass("current");';
			echo '$("#menu-posts-dzsvideo, #menu-posts-dzsvideo>a").removeClass("wp-has-current-submenu wp-menu-open");';
			echo '});';
			echo '</script>';

		}
	}
	function handle_admin_print_footer_scripts() {
//        echo 'hmmdada';




		?>
        <script>
            (function ($) {
                var media = wp.media, shortcode_string = 'dzs_videogallery';
                wp.mce = wp.mce || {};
                console.info(wp.mce);
//
                if (media) {
                    wp.mce.dzs_videogallery = {
                        shortcode_data: {},
                        template: media.template('dzsvg-shortcode-preview'),
                        getContent: function () {
                            var options = this.shortcode.attrs.named;
                            options.innercontent = this.shortcode.content;
                            return this.template(options);
                        },
                        View: {

                            template: media.template('dzsvg-shortcode-preview'),
                            postID: $('#post_ID').val(),
                            initialize: function (options) {
                                this.shortcode = options.shortcode;
                                wp.mce.boutique_banner.shortcode_data = this.shortcode;
                            },
                            getHtml: function () {
                                var options = this.shortcode.attrs.named;
                                options.innercontent = this.shortcode.content;
                                return this.template(options);
                            }
                        },
                        createInstance: function (node) {


//                        console.info('update', this, node);

//                        return "alceva";
                        },
                        edit: function (node) {


//                        console.info(this, node);

                            var parsel = '';

                            if (sel != '') {


                                var ed = window.tinyMCE.get('content');
                                var sel = ed.selection.getContent();


                                var ed_sel = ed.dom.select('div[data-wpview-text="' + this.encodedText + '"]')[0];
                                console.info(' the selection - ', ed.dom.select('div[data-wpview-text="' + this.encodedText + '"]')[0]);
                                window.remember_sel = ed_sel;
                                ed.selection.select(ed_sel);

//                            console.info('check sel - ',ed,ed.selection.getContent());

                                parsel += '&sel=' + encodeURIComponent(sel);
                                window.mceeditor_sel = sel;
                            } else {
                                window.mceeditor_sel = '';
                            }
                            //console.log(aux);


                            window.htmleditor_sel = 'notset';


                            window.dzszb_open(dzsvg_settings.shortcode_generator_url + parsel, 'iframe', {
                                bigwidth: 1200,
                                bigheight: 700,
                                forcenodeeplink: 'on',
                                dims_scaling: 'fill'
                            });
//                        var data = window.decodeURIComponent( $( node ).attr('data-wpview-text') );
//                        console.debug(this);
//                        var values = this.shortcode_data.attrs.named;
//                        values['innercontent'] = this.shortcode_data.content;
//                        console.log(values);
//
//                        wp.mce.dzs_videogallery.popupwindow(tinyMCE.activeEditor, values);
                            //$( node ).attr( 'data-wpview-text', window.encodeURIComponent( shortcode ) );
                        },
                        // this is called from our tinymce plugin, also can call from our "edit" function above
                        // wp.mce.dzs_videogallery.popupwindow(tinyMCE.activeEditor, "bird");
                        popupwindow: function (editor, values, onsubmit_callback) {
//                            console.info('popupwindow');
                            if (typeof onsubmit_callback != 'function') {
                                onsubmit_callback = function (e) {
                                    // Insert content when the window form is submitted (this also replaces during edit, handy!)
                                    var s = '[' + shortcode_string;
                                    for (var i in e.data) {
                                        if (e.data.hasOwnProperty(i) && i != 'innercontent') {
                                            s += ' ' + i + '="' + e.data[i] + '"';
                                        }
                                    }
                                    s += ']';
                                    if (typeof e.data.innercontent != 'undefined') {
                                        s += e.data.innercontent;
                                        s += '[/' + shortcode_string + ']';
                                    }
                                    editor.insertContent(s);
                                };
                            }
                            editor.windowManager.open({
                                title: 'Banner',
                                body: [
                                    {
                                        type: 'textbox',
                                        name: 'title',
                                        label: 'Title',
                                        value: values['title']
                                    },
                                    {
                                        type: 'textbox',
                                        name: 'link',
                                        label: 'Button Text',
                                        value: values['link']
                                    },
                                    {
                                        type: 'textbox',
                                        name: 'linkhref',
                                        label: 'Button URL',
                                        value: values['linkhref']
                                    },
                                    {
                                        type: 'textbox',
                                        name: 'innercontent',
                                        label: 'Content',
                                        value: values['innercontent']
                                    }
                                ],
                                onsubmit: onsubmit_callback
                            });
                        }
                    };
                    wp.mce.views.register(shortcode_string, wp.mce.dzs_videogallery);
                }
            }(jQuery));
        </script>

        <?php
    }

    function handle_admin_menu() {


        global $current_user;

        $the_plugins = get_plugins();
        $pluginname = 'DZS Video Portal';

        foreach ($the_plugins as $plugin) {
            if ($plugin['Name'] == $pluginname) {
                if (defined('DZSVP_VERSION')) {
                    $this->addons_dzsvp_activated = true;
                }
            }
        }


        $admin_cap = $this->capability_admin;

//        echo 'ceva'.$this->addons_dzsvp_activated;
        if ($this->mainoptions['admin_enable_for_users'] == 'on') {
            $this->capability_user = 'read';


            //if current user is not an admin then it is a user and should have it's own database




            if (current_user_can($this->capability_admin) == false) {
                //print_r($current_user);

            }
            $admin_cap = $this->capability_user;
        }



	    if ( current_user_can('manage_options')==false || ( current_user_can('video_gallery_edit_own_galleries')  && current_user_can('video_gallery_edit_others_galleries')==false) ) {


		    $currDb = 'user' . $current_user->data->ID;
		    //echo 'ceva'; print_r($this->dbs);
		    if ($currDb != 'main' && $currDb != '') {
			    $this->dbitemsname .= '-' . $currDb;
		    }
		    $this->currDb = $currDb;

		    if (is_array($this->dbs) && !in_array($currDb, $this->dbs) && $currDb != 'main' && $currDb != '') {
			    array_push($this->dbs, $currDb);
			    update_option($this->dbdbsname, $this->dbs);
		    }

		    $this->mainitems = get_option($this->dbitemsname);
		    if ($this->mainitems == '') {

			    $mainitems_default_ser = file_get_contents(dirname(__FILE__) . '/sampledata/sample_items.txt');
			    $this->mainitems = unserialize($mainitems_default_ser);

			    update_option($this->dbitemsname, $this->mainitems);
		    }

	    }



	    $cap = 'video_gallery_edit_own_galleries';

        if(current_user_can('manage_options')){
	        $cap = 'manage_options';
        }

        $dzsvg_page = add_menu_page(__('Video Gallery', 'dzsvg'), __('Video Gallery', 'dzsvg'), $cap, $this->adminpagename, array($this, 'admin_page'), 'div');



	    if($cap!= 'manage_options'){
		    $cap = 'video_gallery_edit_player_configs';
	    }
        $dzsvg_subpage = add_submenu_page($this->adminpagename, __('Video Player Configs', 'dzsvg'), __('Player Configs', 'dzsvg'), $cap, $this->adminpagename_configs, array($this, 'admin_page_vpc'));


        $dzsvg_subpage = add_submenu_page($this->adminpagename, __('Designer Center', 'dzsvg'), __('Designer Center', 'dzsvg'), $this->capability_admin, $this->adminpagename_designercenter, array($this, 'admin_page_dc'));


	    if($cap!= 'manage_options'){
		    $cap = 'video_gallery_edit_own_galleries';
	    }

	    // -- we need this for generator to work on assigned roles
        // -- we will restrict access for admin later

        $dzsvg_subpage = add_submenu_page($this->adminpagename, __('Video Gallery Settings', 'dzsvg'), __('Settings', 'dzsvg'), $cap, $this->adminpagename_mainoptions, array($this, 'admin_page_mainoptions'));




        $dzsvg_subpage = add_submenu_page($this->adminpagename, __('Autoupdater', 'dzsvg'), __('Autoupdater', 'dzsvg'), $this->capability_admin, $this->adminpagename_autoupdater, array($this, 'admin_page_autoupdater'));



	    if($cap!= 'manage_options'){
		    $cap = 'video_gallery_edit_own_galleries';
	    }
        $dzsvg_subpage = add_submenu_page($this->adminpagename, __('About DZS Video Gallery', 'dzsvg'), __('About', 'dzsvg'), $cap, $this->adminpagename_about, array($this, 'admin_page_about'));

    }

    function admin_scripts() {
        wp_enqueue_script('media-upload');
        wp_enqueue_script('tiny_mce');
        wp_enqueue_script('thickbox');
        wp_enqueue_style('thickbox');
        wp_enqueue_script('dzsvg_admin', $this->thepath . "admin/admin.js");
        wp_enqueue_style('dzsvg_admin', $this->thepath . 'admin/admin.css');
        wp_enqueue_style('dzstooltip', $this->thepath . 'libs/dzstooltip/dzstooltip.css');
        wp_enqueue_script('dzstooltip', $this->thepath . 'libs/dzstooltip/dzstooltip.js');
        wp_enqueue_script('dzs.farbtastic', $this->thepath . "admin/colorpicker/farbtastic.js");
        wp_enqueue_style('dzs.farbtastic', $this->thepath . 'admin/colorpicker/farbtastic.css');
        wp_enqueue_style('dzs.scroller', $this->thepath . 'assets/dzsscroller/scroller.css');
        wp_enqueue_script('dzs.scroller', $this->thepath . 'assets/dzsscroller/scroller.js');
        wp_enqueue_style('dzs.dzscheckbox', $this->thepath . 'assets/dzscheckbox/dzscheckbox.css');

        wp_enqueue_style('dzstoggle', $this->thepath . 'dzstoggle/dzstoggle.css');
        wp_enqueue_script('dzstoggle', $this->thepath . 'dzstoggle/dzstoggle.js');
        wp_enqueue_script('jquery-ui-core');
        wp_enqueue_script('jquery-ui-sortable');

        wp_enqueue_style('dzsulb', $this->thepath . 'libs/ultibox/ultibox.css');
        wp_enqueue_script('dzsulb', $this->thepath . 'libs/ultibox/ultibox.js');

//        echo 'enqueue_sortable';
//        print_r($_GET);
        if(isset($_GET['from']) && $_GET['from']=='shortcodegenerator'){

            wp_enqueue_style('dzs.remove_wp_bar', $this->thepath . 'tinymce/remove_wp_bar.css');

        }
    }

    function front_scripts() {
        //print_r($this->mainoptions);
        $videogalleryscripts = array('jquery');
        wp_enqueue_script('dzsvg', $this->thepath . "videogallery/vplayer.js");


//        wp_enqueue_script('dzs.flashhtml5main', $this->thepath . "videogallery/flashhtml5main.js");
        wp_enqueue_style('dzs.vgallery.skin.custom', $this->thepath . 'customs/skin_custom.css');


        if ($this->mainoptions['disable_fontawesome'] != 'on') {

            wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
        }

        //if($this->mainoptions['embed_masonry']=='on'){
        //wp_enqueue_script('jquery.masonry', $this->thepath . "masonry/jquery.masonry.min.js");
        //}
    }

    function add_simple_field($pname, $otherargs = array()) {
        global $data;
        $fout = '';
        $val = '';

        $args = array('val' => '');
        $args = array_merge($args, $otherargs);

        $val = $args['val'];

        //====check if the data from database txt corresponds
        if (isset($data[$pname])) {
            $val = $data[$pname];
        }
        $fout .= '<div class="setting"><input type="text" class="textinput short" name="' . $pname . '" value="' . $val . '"></div>';
        echo $fout;
    }

    function add_cb_field($pname) {
        global $data;
        $fout = '';
        $val = '';
        if (isset($data[$pname])) $val = $data[$pname];
        $checked = '';
        if ($val == 'on') $checked = ' checked';

        $fout .= '<div class="setting"><input type="checkbox" class="textinput" name="' . $pname . '" value="on" ' . $checked . '/> on</div>';
        echo $fout;
    }

    function add_cp_field($pname, $otherargs = array()) {
        global $data;
        $fout = '';
        $val = '';


        $args = array('val' => '', 'class' => '',);

        $args = array_merge($args, $otherargs);


        //print_r($args);
        $val = $args['val'];


        $fout .= '
<div class="setting-input"><input type="text" class="textinput with-colorpicker ' . $args['class'] . '" name="' . $pname . '" value="' . $val . '">
<div class="picker-con"><div class="the-icon"></div><div class="picker"></div></div>
</div>';
        return $fout;
    }

    function admin_page_dc() {
        $dc_config = array('ispreview' => 'off');

        include_once("tinymce/popupiframe_designer_center.php");
        ?>


        <?php
    }

    function misc_input_text($argname, $pargs = array()) {
        $fout = '';

        $margs = array('type' => 'text', 'class' => '', 'seekval' => '', 'extra_attr' => '',);


        $margs = array_merge($margs, $pargs);

        $type = 'text';
        if (isset($margs['type'])) {
            $type = $margs['type'];
        }
        $fout .= '<input type="' . $type . '"';
        if (isset($margs['class'])) {
            $fout .= ' class="' . $margs['class'] . '"';
        }
        $fout .= ' name="' . $argname . '"';
        if (isset($margs['seekval'])) {
            $fout .= ' value="' . $margs['seekval'] . '"';
        }

        $fout .= $margs['extra_attr'];

        $fout .= '/>';
        return $fout;
    }

    function misc_input_textarea($argname, $otherargs = array()) {
        $fout = '';
        $fout .= '<textarea';
        $fout .= ' name="' . $argname . '"';

        $margs = array('class' => '', 'val' => '',// === default value
            'seekval' => '',// ===the value to be seeked
            'type' => '',
            'extraattr' => '',
            );
        $margs = array_merge($margs, $otherargs);


        if ($margs['class'] != '') {
            $fout .= ' class="' . $margs['class'] . '"';
        }
        if ($margs['extraattr']) {
            $fout .= ' ' . $margs['extraattr'] . '';
        }
        $fout .= '>';
        if (isset($margs['seekval']) && $margs['seekval'] != '') {
            $fout .= '' . $margs['seekval'] . '';
        } else {
            $fout .= '' . $margs['val'] . '';
        }
        $fout .= '</textarea>';

        return $fout;
    }

    function misc_input_checkbox($argname, $argopts) {
        $fout = '';
        $auxtype = 'checkbox';

        if (isset($argopts['type'])) {
            if ($argopts['type'] == 'radio') {
                $auxtype = 'radio';
            }
        }
        $fout .= '<input type="' . $auxtype . '"';
        $fout .= ' name="' . $argname . '"';
        if (isset($argopts['class'])) {
            $fout .= ' class="' . $argopts['class'] . '"';
        }
        $theval = 'on';
        if (isset($argopts['val'])) {
            $fout .= ' value="' . $argopts['val'] . '"';
            $theval = $argopts['val'];
        } else {
            $fout .= ' value="on"';
        }
        //print_r($this->mainoptions); print_r($argopts['seekval']);
        if (isset($argopts['seekval'])) {
            $auxsw = false;
            if (is_array($argopts['seekval'])) {
                //echo 'ceva'; print_r($argopts['seekval']);
                foreach ($argopts['seekval'] as $opt) {
                    //echo 'ceva'; echo $opt; echo
                    if ($opt == $argopts['val']) {
                        $auxsw = true;
                    }
                }
            } else {
                //echo $argopts['seekval']; echo $theval;
                if ($argopts['seekval'] == $theval) {
                    //echo $argval;
                    $auxsw = true;
                }
            }
            if ($auxsw == true) {
                $fout .= ' checked="checked"';
            }
        }
        $fout .= '/>';
        return $fout;
    }

    function admin_page_mainoptions() {

        include_once "class_parts/admin-page-mainoptions.php";
    }

    function admin_page_about() {

        include_once('class_parts/admin-page-about.php');


        wp_enqueue_style('dzstabsandaccordions', $this->thepath . 'libs/dzstabsandaccordions/dzstabsandaccordions.css');
        wp_enqueue_script('dzstabsandaccordions', $this->thepath . "libs/dzstabsandaccordions/dzstabsandaccordions.js");
        wp_enqueue_style('fontawesome', 'https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
    }


    function admin_page_autoupdater() {

        ?>
        <div class="wrap">


            <?php

            if (class_exists("ZipArchive")==false) {
                echo '<div class="big-rounded-field setting-text-ok warning warning-bg bg-warning">'.__("Seems that there is no ziparchive support on your server. You can ask your hosting provider to enable it for you to benefit from updates.",'dzsvg').'</div><br>';
            }

            $auxarray = array();


            if (isset($_GET['dzsvg_purchase_remove_binded']) && $_GET['dzsvg_purchase_remove_binded'] == 'on') {

                $this->mainoptions['dzsvg_purchase_code_binded'] = 'off';

                update_option($this->dboptionsname, $this->mainoptions);

            }

            if (isset($_POST['action']) && $_POST['action'] === 'dzsvg_update_request') {


                if (isset($_POST['dzsvg_purchase_code'])) {
                    $auxarray = array('dzsvg_purchase_code' => $_POST['dzsvg_purchase_code']);
                    $auxarray = array_merge($this->mainoptions, $auxarray);


                    $this->mainoptions = $auxarray;


                    update_option($this->dboptionsname, $auxarray);
                }


            }

            $extra_class = '';
            $extra_attr = '';
            $form_method = "POST";
            $form_action = "";
            $disable_button = '';

            $lab = 'dzsvg_purchase_code';

            if ($this->mainoptions['dzsvg_purchase_code_binded'] == 'on') {
                $extra_attr = ' disabled';
                $disable_button = ' <input type="hidden" name="purchase_code" value="' . $this->mainoptions[$lab] . '"/><input type="hidden" name="site_url" value="' . site_url() . '"/><input type="hidden" name="redirect_url" value="' . esc_url(add_query_arg('dzsvg_purchase_remove_binded', 'on', dzs_curr_url())) . '"/><button class="button-secondary" name="action" value="dzsvg_purchase_code_disable">' . __("Disable Key") . '</button>';
                $form_action = ' action="https://zoomthe.me/updater_dzsvg/servezip.php"';
            }


            echo '<form' . $form_action . ' class="mainsettings" method="' . $form_method . '">';

            echo '
                <div class="setting">
                    <div class="label">' . __('Purchase Code', 'dzsvg') . '</div>
                    ' . $this->misc_input_text($lab, array('val' => '', 'seekval' => $this->mainoptions[$lab], 'class' => $extra_class, 'extra_attr' => $extra_attr)) . $disable_button . '
                    <div class="sidenote">' . __('You can <a href="https://lh5.googleusercontent.com/-o4WL83UU4RY/Unpayq3yUvI/AAAAAAAAJ_w/HJmso_FFLNQ/w786-h1179-no/puchase.jpg" target="“_blank”">find it here</a> ', 'dzsvg') . '</div>
                </div>';


            if ($this->mainoptions['dzsvg_purchase_code_binded'] == 'on') {
                echo '</form><form class="mainsettings" method="post">';
            }

            echo '<p><button class="button-primary" name="action" value="dzsvg_update_request">' . __("Update") . '</button></p>';



            ?>
            </form>
        </div>
        <?php





        if (isset($_POST['action']) && $_POST['action'] === 'dzsvg_update_request') {


//            echo 'ceva';


//            die();


            $aux = 'https://zoomthe.me/updater_dzsvg/servezip.php?purchase_code=' . $this->mainoptions['dzsvg_purchase_code'] . '&site_url=' . site_url();
            $res = DZSHelpers::get_contents($aux);

//            echo 'hmm'; echo strpos($res,'<div class="error">'); echo 'dada'; echo $res;
            if ($res === false) {
                echo 'server offline';
            } else {
                if (strpos($res, '<div class="error">') === 0) {
                    echo $res;


                    if (strpos($res, '<div class="error">error: in progress') === 0) {

                        $this->mainoptions['dzsvg_purchase_code_binded'] = 'on';
                        update_option($this->dboptionsname, $this->mainoptions);
                    }
                } else {

                    file_put_contents(dirname(__FILE__) . '/update.zip', $res);
                    if (class_exists('ZipArchive')) {
                        $zip = new ZipArchive;
                        $res = $zip->open(dirname(__FILE__) . '/update.zip');
                        //test
                        if ($res === TRUE) {
//                echo 'ok';
                            $zip->extractTo(dirname(__FILE__));
                            $zip->close();


                            $this->mainoptions['dzsvg_purchase_code_binded'] = 'on';
                            update_option($this->dboptionsname, $this->mainoptions);


                        } else {
                            echo 'failed, code:' . $res;
                        }
                        echo __('Update done.');
                    } else {

                        echo __('ZipArchive class not found.');
                    }

                }
            }
        }

    }


	function get_post_thumb_src($it_id){

	    if(get_post_meta($it_id,'dzsvg_meta_thumb',true)){
		    $imgsrc = get_post_meta($it_id,'dzsvg_meta_thumb',true);

		    return $imgsrc;
        }else{

		    $imgsrc = wp_get_attachment_image_src(get_post_thumbnail_id($it_id), "full");

		    return $imgsrc[0];
        }

	}


	function playlist_export($term_id, $pargs=array()) {



		$margs = array(
			'download_export'=>false
		);

		$margs = array_merge($margs,$pargs);

		$term_meta = get_option( "taxonomy_$term_id" );

//		print_rr($term_meta);

		$tax = $this->taxname_sliders;

		$reference_term = get_term_by( 'id', $term_id, $tax );

//	        print_rr($reference_term);


		$reference_term_name = $reference_term->name;
		$reference_term_slug = $reference_term->slug;
		$selected_term_id    = $reference_term->term_id;


		if ( $selected_term_id ) {

			$args = array(
				'post_type'   => 'dzsvideo',
				'numberposts' => - 1,
				'posts_per_page' => - 1,
				//                'meta_key' => 'dzsap_meta_order_'.$selected_term,

				'orderby'    => 'meta_value_num',
				'order'      => 'ASC',
				'meta_query' => array(
					'relation' => 'OR',
					array(
						'key'     => 'dzsvg_meta_order_' . $selected_term_id,
						//                        'value' => '',
						'compare' => 'EXISTS',
					),
					array(
						'key'     => 'dzsvg_meta_order_' . $selected_term_id,
						//                        'value' => '',
						'compare' => 'NOT EXISTS'
					)
				),
				'tax_query'  => array(
					array(
						'taxonomy' => $tax,
						'field'    => 'id',
						'terms'    => $selected_term_id // Where term_id of Term 1 is "1".
					)
				),
			);

			$my_query = new WP_Query( $args );

//            print_r($my_query);


//            print_r($my_query->posts);


			$arr_export = array(
				'original_term_id'   => $selected_term_id,
				'original_term_slug' => $reference_term_slug,
				'original_term_name' => $reference_term_name,
				'original_site_url'  => site_url(),
				'export_type'        => 'meta_term',
				'term_meta'          => $term_meta,
				'items'              => array(),
			);

			foreach ( $my_query->posts as $po ) {

//                print_r($po);


				$po_sanitized = $this->sanitize_to_gallery_item( $po );


				array_push( $arr_export['items'], $po_sanitized );

//                print_rr($po);
//                print_rr($po_sanitized);
//			        print_rr($po);
			}


			if($margs['download_export']){

				header( 'Content-Type: text/plain' );
				header( 'Content-Disposition: attachment; filename="' . "dzsvg_export_" . $reference_term_slug . ".txt" . '"' );
			}

			return $arr_export;
		}else{
			return array();
		}
	}



	function admin_page() {


        ?>
        <div class="wrap">
            <div class="import-export-db-con">
                <div class="the-toggle"></div>
                <div class="the-content-mask" style="">

                    <div class="the-content">
                        <form class="dzs-container" enctype="multipart/form-data" action="" method="POST">
                            <?php
                            wp_nonce_field( 'dzsvg_importdbupload_nonce', 'dzsvg_importdbupload_nonce' );
                            ?>
                            <div class="one-half">
                                <h3><?php echo __("Import Database"); ?></h3>
                                <input name="dzsvg_importdbupload" type="file" size="10"/><br/>
                            </div>
                            <div class="one-half  alignright">
                                <input class="button-secondary" type="submit" name="dzsvg_importdb" value="Import"/>
                            </div>
                            <div class="clear"></div>
                        </form>


                        <form class="dzs-container" enctype="multipart/form-data" action="" method="POST">
                            <?php
                            wp_nonce_field( 'dzsvg_importslider_nonce', 'dzsvg_importslider_nonce' );
                            ?>
                            <div class="one-half">
                                <h3>Import Slider</h3>
                                <input name="importsliderupload" type="file" size="10"/><br/>
                            </div>
                            <div class="one-half  alignright">
                                <input class="button-secondary" type="submit" name="dzsvg_importslider" value="Import"/>
                            </div>
                            <div class="clear"></div>
                        </form>

                        <div class="dzs-container">
                            <div class="one-half">
                                <h3>Export Database</h3>
                            </div>
                            <div class="one-half  alignright">
                                <form action="" method="POST"><input class="button-secondary" type="submit"
                                                                     name="dzsvg_exportdb" value="Export"/></form>
                            </div>
                        </div>
                        <div class="clear"></div>

                    </div>
                </div>
            </div>
            <h2>DZS <?php _e('Video Gallery Admin', 'dzsvg'); ?>&nbsp; <span class="version-number"
                                                                             style="font-size:13px; font-weight: 100;">version <span
                        class="now-version"><?php echo DZSVG_VERSION; ?></span></span> <img alt=""
                                                                                            style="visibility: visible;"
                                                                                            id="main-ajax-loading"
                                                                                            src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/>
            </h2>
            <noscript><?php _e('You need javascript for this.', 'dzsvg'); ?></noscript>
            <?php
            if (current_user_can($this->capability_admin)) {
                ?>
                <div class="top-buttons">
                <a href="<?php echo $this->thepath; ?>readme/index.html"
                   class="button-secondary action"><?php _e('Documentation', 'dzsvg'); ?></a>
                <a href="<?php echo admin_url('admin.php?page=dzsvg-dc'); ?>" target="_blank"
                   class="button-secondary action"><?php _e('Go to Designer Center', 'dzsvg'); ?></a>
                <div class="super-select db-select dzsvg">
                    <button class="button-secondary btn-show-dbs"><?php echo __("Current Database"); ?> - <span class="strong currdb"><?php
                            if ($this->currDb == '') {
                                echo 'main';
                            } else {
                                echo $this->currDb;
                            }
                            ?></span></button>
                    <select class="main-select hidden"><?php
                        //print_r($this->dbs);

                        if (is_array($this->dbs)) {
                            foreach ($this->dbs as $adb) {
                                $params = array('dbname' => $adb);
                                $newurl = esc_url(add_query_arg($params, dzs_curr_url()));
                                echo '<option' . ' data-newurl="' . $newurl . '"' . '>' . $adb . '</option>';
                            }
                        } else {
                            $params = array('dbname' => 'main');
                            $newurl = esc_url(add_query_arg($params, dzs_curr_url()));
                            echo '<option' . ' data-newurl="' . $newurl . '"' . ' selected="selected"' . '>' . $adb . '</option>';
                        }
                        ?></select>
                    <div class="hidden replaceurlhelper"><?php
                        $params = array('dbname' => 'replaceurlhere');
                        $newurl = esc_url(add_query_arg($params, dzs_curr_url()));
                        echo $newurl;
                        ?></div>
                </div>
                </div><?php
            }
            ?>
            <table cellspacing="0" class="wp-list-table widefat dzs_admin_table main_sliders">
                <thead>
                <tr>
                    <th style="" class="manage-column column-name" id="name"
                        scope="col"><?php echo __('ID', 'dzsvg'); ?></th>
                    <th class="column-edit"><?php echo __('Edit', 'dzsvg'); ?></th>
                    <th class="column-edit"><?php echo __('Embed', 'dzsvg'); ?></th>
                    <th class="column-edit"><?php echo __('Export', 'dzsvg'); ?></th>
                    <th class="column-edit"><?php echo __('Duplicate', 'dzsvg'); ?></th>
                    <th class="column-edit"><?php echo __('Delete', 'dzsvg'); ?></th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <?php
            $url_add = '';
            $items = $this->mainitems;
            //echo count($items);

            $aux = (remove_query_arg('deleteslider', dzs_curr_url()));

            $nextslidernr = count($items);
            if ($nextslidernr < 1) {
                $nextslidernr = 1;
            }
            $params = array('currslider' => $nextslidernr);


            //            echo 'curr link - ' . $aux;
            $url_add = esc_url(add_query_arg($params, $aux));
            ?>
            <a class="button-secondary add-slider"
               href="<?php echo $url_add; ?>"><?php _e('Add Slider', 'dzsvg'); ?></a>
            <form class="master-settings">
            </form>


            <br>

            <div class="dzstoggle">
                <div class="toggle-title"><?php echo __("Bulk upload youtube / vimeo channel"); ?></div>
                <div class="toggle-content">
                    <div class="block">
                        <div class="extra-options">
                            <h3><?php echo __('Import', 'dzsvg'); ?></h3>
                            <!-- demo/ playlist: ADC18FE37410D250, user: digitalzoomstudio, vimeo: 5137664 -->
                            <input type="text" name="import_inputtext" id="import_inputtext" value="digitalzoomstudio"/>
                            <div class="sidenote"><?php _e('Import here feed from a YT Playlist, YT User Channel or Vimeo User Channel - you just have to enter the 
                        id of the playlist / user id in the box below and select the correct type from below', 'dzsvg') . '. Remember to set the <strong>Feed From</strong> field to <strong>Normal</strong> after your videos have been imported.'; ?></div>
                            <a href="#" id="importytplaylist" class="button-secondary">YouTube <?php echo __("Playlist"); ?></a>
                            <a href="#" id="importytuser" class="button-secondary">YouTube <?php echo __("User Channel"); ?></a>
                            <a href="#" id="importvimeouser" class="button-secondary">Vimeo <?php echo __("User Channel"); ?></a>
                            <br/>
                            <span class="import-error" style="display:none;"></span>
                        </div>
                        <div class="sidenote"><?php echo __("This will import your channel for finer controls so you can manually arrage, change titles etc."); ?></div>
                    </div>
                </div>
            </div>


            <div class="dzstoggle">
                <div class="toggle-title"><?php echo __("Bulk upload multiple mp4"); ?></div>
                <div class="toggle-content">
                    <div class="dzs-multi-upload">
                        <h3><?php echo __("Choose file(s)"); ?></h3>
                        <div>
                            <input class="files-upload multi-uploader" name="file_field" type="file" multiple>
                        </div>
                        <div class="droparea">
                            <div class="instructions">drag & drop files here</div>
                        </div>
                        <div class="upload-list-title">The Preupload List</div>
                        <ul class="upload-list">
                            <li class="dummy">add files here from the button or drag them above</li>
                        </ul>
                        <button class="primary-button upload-button">Upload All</button>
                    </div>
                </div>
            </div>

            <div class="notes">
                <div class="curl">
                    Curl: <?php echo function_exists('curl_version') ? 'Enabled' : 'Disabled' . '<br />'; ?>
                </div>
                <div class="fgc">File Get Contents: <?php echo ini_get('allow_url_fopen') ? "Enabled" : "Disabled"; ?>
                </div>
                <div class="sidenote"><?php _e('If neither of these are enabled, only normal feed will work. 
                    Contact your host provider on how to enable these services to use the YouTube User Channel 
                    or YouTube Playlist feed.', 'dzsvg'); ?>
                </div>
            </div>
            <div class="saveconfirmer"><?php _e('Loading...', 'dzsvg'); ?></div>
            <a href="#" class="button-primary master-save"></a> <img alt=""
                                                                     style="position:fixed; bottom:18px; right:125px; visibility: hidden;"
                                                                     id="save-ajax-loading"
                                                                     src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/>

            <a href="#" class="button-primary master-save"><?php echo __('Save All Galleries', 'dzsvg'); ?></a>
            <a href="#" class="button-primary slider-save"><?php echo __('Save Gallery', 'dzsvg'); ?></a>
        </div>
        <?php

//        session_start();
//        print_r($_SESSION);

        ?>
        <script>
            <?php


            //$jsnewline = '\\' + "\n";
            if (isset($this->mainoptions['use_external_uploaddir']) && $this->mainoptions['use_external_uploaddir'] == 'on') {
                echo "window.dzs_upload_path = '" . site_url('wp-content') . "/upload/';
";
                echo "window.dzs_phpfile_path = '" . site_url() . "/index.php?action=ajax_dzsvg_submit_files';
";
            } else {


                $upload_dir = wp_upload_dir();
//                print_r($upload_dir);

                $realpath = $upload_dir['path'];
                $realpath = str_replace('\\','/', $realpath);


                echo "window.dzs_upload_realpath = '" . $realpath . "';
";
                echo "window.dzs_upload_path = '" . $upload_dir['url'] . "/';
";

            $nonce = floor(rand(0,999999));

//                $_SESSION['dzsvg-upload-bulk-nonce'] = $nonce;


                echo "window.dzs_phpfile_path = '" . site_url() . "/index.php?action=ajax_dzsvg_submit_files&dzsvg-upload-bulk-nonce=".$nonce."';";
            }

            //        print_r($items);

            $aux = str_replace(array("\r", "\r\n", "\n"), '', $this->sliderstructure);

            $currslider = 0;


            if (isset($_GET['currslider']) && isset($items[$_GET['currslider']])){
                $currslider = $_GET['currslider'];
            }
            if ($items[$currslider]['settings']) {

                $aux = str_replace(array("theidofthegallery"), $items[$currslider]['settings']['id'], $aux);
            }

//            $aux = str_replace("'",'\'',$aux);
            $aux = str_replace("'",'\'',$aux);
//            $aux = addslashes($aux);


                    $aux = addslashes($aux);
//addslashes
            echo "var ceva = 'alceva'; var sliderstructure = '" . ($aux) . "';
";
            $aux = str_replace(array("\r", "\r\n", "\n"), '', $this->itemstructure);
//            $aux = str_replace(array("'"), '', $aux);
            $aux = addslashes($aux);
            echo "var itemstructure = '" . $aux . "';
";
            $aux = str_replace(array("\r", "\r\n", "\n"), '', $this->videoplayerconfig);
            $aux = addslashes($aux);
            echo "var videoplayerconfig = '" . $aux . "';
";
            ?>
            jQuery(document).ready(function ($) {
                sliders_ready($);
                if ($.fn.multiUploader) {
                    $('.dzs-multi-upload').multiUploader();
                }
                <?php
                $items = $this->mainitems;
                for ($i = 0; $i < count($items); $i++) {
//print_r($items[$i]);
                    $aux = '';
                    if (isset($items[$i]) && isset($items[$i]['settings']) && isset($items[$i]['settings']['id'])) {
                        //echo $items[$i]['settings']['id'];
                        $aux2 = $items[$i]['settings']['id'];
                        $aux2 = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $aux2);
                        $aux2 = str_replace(array('"'), "'", $aux2);
                        $aux = '{ name: "' . $aux2 . '"}';
                    }
                    echo "sliders_addslider(" . $aux . ");";
                }
                if (count($items) > 0) {
                    echo 'sliders_showslider(0);';
                }


                for ($i = 0; $i < count($items); $i++) {
//echo $i . $this->currSlider . 'cevava';
                    if (($this->mainoptions['is_safebinding'] != 'on' || $i == $this->currSlider) && is_array($items[$i])) {

                        //==== jsi is the javascript I, if safebinding is on then the jsi is always 0 ( only one gallery )
                        $jsi = $i;
                        if ($this->mainoptions['is_safebinding'] == 'on') {
                            $jsi = 0;
                        }

                        for ($j = 0; $j < count($items[$i]) - 1; $j++) {
                            echo "sliders_additem(" . $jsi . ");";
                        }

//                        print_r($items);

                        foreach ($items[$i] as $label => $value) {
                            if ($label === 'settings') {
                                if (is_array($items[$i][$label])) {
                                    foreach ($items[$i][$label] as $sublabel => $subvalue) {

                                        $subvalue = $this->sanitize_encode_for_sliders_change($subvalue);
                                        if ($sublabel == 'skin_html5vg') {
                                            $subvalue= str_replace('_','-',$subvalue);
                                        }
                                        if ($sublabel == 'youtubefeed_playlist') {
                                            $sublabel = 'ytplaylist_source';
                                        }
	                                    // -- compatibility with older versions
	                                    if ($sublabel == 'feedfrom') {
		                                    if ($subvalue == 'youtube playlist') {
			                                    $subvalue = 'ytplaylist';
		                                    }
	                                    }


                                        echo 'sliders_change(' . $jsi . ', "settings", "' . $sublabel . '", ' . "'" . $subvalue . "'" . ');';
                                    }
                                }
                            } else {

                                if (is_array($items[$i][$label])) {
                                    foreach ($items[$i][$label] as $sublabel => $subvalue) {
	                                    $subvalue = $this->sanitize_encode_for_sliders_change($subvalue);


	                                    if ($label == '') {
                                            $label = '0';
                                        }
                                        echo 'sliders_change(' . $jsi . ', ' . $label . ', "' . $sublabel . '", ' . "'" . $subvalue . "'" . ');';
                                    }
                                }
                            }
                        }
                        if ($this->mainoptions['is_safebinding'] == 'on') {
                            break;
                        }
                    }
                }
                ?>
                jQuery('#main-ajax-loading').css('visibility', 'hidden');
                if (dzsvg_settings.is_safebinding == "on") {
                    jQuery('.master-save').remove();
                    if (dzsvg_settings.addslider == "on") {
                        sliders_addslider();
                        window.currSlider_nr = -1
                        sliders_showslider(0);
                    }
                }
                check_global_items();
                sliders_allready();
            });
        </script>
        <?php

        if(isset($_GET['donotshowaboutagain']) && $_GET['donotshowaboutagain']=='on'){
            update_option('dzsvg_shown_intro','on');
        }
    }


    function sanitize_encode_for_sliders_change($subvalue){

	    $subvalue = (string)$subvalue;
	    $subvalue = stripslashes($subvalue);
	    $subvalue = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $subvalue);
	    $subvalue = str_replace(array("'"), '"', $subvalue);
	    $subvalue = str_replace(array("</script>"), '{{endscript}}', $subvalue);


	    return $subvalue;
    }

    function admin_page_vpc() {
        ?>
        <div class="wrap">
            <div class="import-export-db-con">
                <div class="the-toggle"></div>
                <div class="the-content-mask" style="">

                    <div class="the-content">
                        <form enctype="multipart/form-data" action="" method="POST">
                            <?php
                            wp_nonce_field( 'dzsvg_importdb_nonce', 'dzsvg_importdb_nonce' );
                            ?>
                            <div class="dzs-container">
                                <div class="one-half">
                                    <h3>Import Database</h3>
                                    <input name="dzsvg_importdbupload" type="file" size="10"/><br/>
                                </div>
                                <div class="one-half  alignright">
                                    <input class="button-secondary" type="submit" name="dzsvg_importdb" value="Import"/>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </form>


                        <form enctype="multipart/form-data" action="" method="POST">
                            <?php
                            wp_nonce_field( 'dzsvg_importslider_nonce', 'dzsvg_importslider_nonce' );
                            ?>
                            <div class="dzs-container">
                                <div class="one-half">
                                    <h3>Import Slider</h3>
                                    <input name="importsliderupload" type="file" size="10"/><br/>
                                </div>
                                <div class="one-half  alignright">
                                    <input class="button-secondary" type="submit" name="dzsvg_importslider"
                                           value="Import"/>
                                </div>
                            </div>
                            <div class="clear"></div>
                        </form>

                        <div class="dzs-container">
                            <div class="one-half">
                                <h3>Export Database</h3>
                            </div>
                            <div class="one-half  alignright">
                                <form action="" method="POST"><input class="button-secondary" type="submit"
                                                                     name="dzsvg_exportdb" value="Export"/></form>
                            </div>
                        </div>
                        <div class="clear"></div>

                    </div>
                </div>
            </div>
            <h2>DZS <?php _e('Video Gallery Admin', 'dzsvg'); ?> <img alt="" style="visibility: visible;"
                                                                      id="main-ajax-loading"
                                                                      src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/>
            </h2>
            <noscript><?php _e('You need javascript for this.', 'dzsvg'); ?></noscript>
            <div class="top-buttons">
                <a href="<?php echo $this->thepath; ?>readme/index.html"
                   class="button-secondary action"><?php _e('Documentation', 'dzsvg'); ?></a>

            </div>
            <table cellspacing="0" class="wp-list-table widefat dzs_admin_table main_sliders">
                <thead>
                <tr>
                    <th style="" class="manage-column column-name" id="name"
                        scope="col"><?php _e('ID', 'dzsvg'); ?></th>
                    <th class="column-edit">Edit</th>
                    <th class="column-edit">Embed</th>
                    <th class="column-edit">Export</th>
                    <th class="column-edit">Duplicate</th>

                    <th class="column-edit">Delete</th>
                </tr>
                </thead>
                <tbody>
                </tbody>
            </table>
            <?php
            $url_add = '';
            $url_add = '';
            $items = $this->mainvpconfigs;
            //echo count($items);
            //print_r($items);

            $aux = remove_query_arg('deleteslider', dzs_curr_url());
            $params = array('currslider' => count($items));
            $url_add = esc_url(add_query_arg($params, $aux));
            ?>
            <a class="button-secondary add-slider"
               href="<?php echo $url_add; ?>"><?php _e('Add Slider', 'dzsvg'); ?></a>
            <form class="master-settings only-settings-con mode_vpconfigs">
            </form>
            <div class="saveconfirmer"><?php _e('Loading...', 'dzsvg'); ?></div>
            <a href="#" class="button-primary master-save-vpc"></a> <img alt=""
                                                                         style="position:fixed; bottom:18px; right:125px; visibility: hidden;"
                                                                         id="save-ajax-loading"
                                                                         src="<?php bloginfo('wpurl'); ?>/wp-admin/images/wpspin_light.gif"/>

            <a href="#" class="button-primary master-save-vpc"><?php _e('Save All Configs', 'dzsvg'); ?></a>
            <a href="#" class="button-secondary slider-save-vpc"><?php _e('Save Config', 'dzsvg'); ?></a>
        </div>
        <script>
            <?php
            //$jsnewline = '\\' + "\n";
            if (isset($this->mainoptions['use_external_uploaddir']) && $this->mainoptions['use_external_uploaddir'] == 'on') {
                echo "window.dzs_upload_path = '" . site_url('wp-content') . "/upload/';
";
                echo "window.dzs_phpfile_path = '" . site_url('wp-content') . "/upload.php';
";
            } else {
                echo "window.dzs_upload_path = '" . $this->thepath . "admin/upload/';
";
                echo "window.dzs_phpfile_path = '" . $this->thepath . "admin/upload.php';
";
            }
            $aux = str_replace(array("\r", "\r\n", "\n"), '', $this->sliderstructure);
            $aux = str_replace("'",'\'',$aux);
            echo "var sliderstructure = '" . addslashes($aux) . "';
";
            $aux = str_replace(array("\r", "\r\n", "\n"), '', $this->itemstructure);
            $aux = addslashes($aux);
            echo "var itemstructure = '" . $aux . "';
";
            $aux = str_replace(array("\r", "\r\n", "\n"), '', $this->videoplayerconfig);
            $aux = addslashes($aux);
            echo "var videoplayerconfig = '" . $aux . "';
";
            ?>
            jQuery(document).ready(function ($) {
                sliders_ready($);
                if (jQuery.fn.multiUploader) {
                    jQuery('.dzs-multi-upload').multiUploader();
                }
                <?php
                $items = $this->mainvpconfigs;
                for ($i = 0; $i < count($items); $i++) {
//print_r($items[$i]);
                    $aux = '';
                    if (isset($items[$i]) && isset($items[$i]['settings']) && isset($items[$i]['settings']['id'])) {
                        //echo $items[$i]['settings']['id'];
                        $aux2 = $items[$i]['settings']['id'];

                        $aux2 = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $aux2);
                        $aux2 = str_replace(array("'"), '"', $aux2);
                        $aux = '{ name: \'' . $aux2 . '\'}';
                    }
                    echo "sliders_addslider(" . $aux . ");";
                }
                if (count($items) > 0) echo 'sliders_showslider(0);';
                for ($i = 0; $i < count($items); $i++) {
//echo $i . $this->currSlider . 'cevava';
                    if (($this->mainoptions['is_safebinding'] != 'on' || $i == $this->currSlider) && is_array($items[$i])) {

                        //==== jsi is the javascript I, if safebinding is on then the jsi is always 0 ( only one gallery )
                        $jsi = $i;
                        if ($this->mainoptions['is_safebinding'] == 'on') {
                            $jsi = 0;
                        }

                        for ($j = 0; $j < count($items[$i]) - 1; $j++) {
                            echo "sliders_additem(" . $jsi . ");";
                        }

                        // -- video player configs

//                        print_r($items);

                        foreach ($items[$i] as $label => $value) {
                            if ($label === 'settings') {
                                if (is_array($items[$i][$label])) {
                                    foreach ($items[$i][$label] as $sublabel => $subvalue) {
                                        $subvalue = (string)$subvalue;
                                        $subvalue = stripslashes($subvalue);
                                        $subvalue = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $subvalue);
                                        $subvalue = str_replace(array("'"), '"', $subvalue);
                                        echo 'sliders_change(' . $jsi . ', "settings", "' . $sublabel . '", ' . "'" . $subvalue . "'" . ');';
                                    }
                                }
                            } else {

                                if (is_array($items[$i][$label])) {
                                    foreach ($items[$i][$label] as $sublabel => $subvalue) {
                                        $subvalue = (string)$subvalue;
                                        $subvalue = stripslashes($subvalue);
                                        $subvalue = str_replace(array("\r", "\r\n", "\n", '\\', "\\"), '', $subvalue);
                                        $subvalue = str_replace(array("'"), '"', $subvalue);
                                        if ($label == '') {
                                            $label = '0';
                                        }
                                        echo 'sliders_change(' . $jsi . ', ' . $label . ', "' . $sublabel . '", ' . "'" . $subvalue . "'" . ');';
                                    }
                                }
                            }
                        }
                        if ($this->mainoptions['is_safebinding'] == 'on') {
                            break;
                        }
                    }
                }
                ?>
                jQuery('#main-ajax-loading').css('visibility', 'hidden');
                if (dzsvg_settings.is_safebinding == "on") {
                    jQuery('.master-save-vpc').remove();
                    if (dzsvg_settings.addslider == "on") {
                        //console.log(dzsvg_settings.addslider)
                        sliders_addslider();
                        window.currSlider_nr = -1
                        sliders_showslider(0);
                    }
                }
                check_global_items();
                sliders_allready();
            });
        </script>
        <?php
    }

    function post_options() {
        //// POST OPTIONS ///

        if (isset($_POST['dzsvg_exportdb'])) {


            //===setting up the db
            $currDb = '';
            if (isset($_POST['currdb']) && $_POST['currdb'] != '') {
                $this->currDb = $_POST['currdb'];
                $currDb = $this->currDb;
            }

            //echo 'ceva'; print_r($this->dbs);
            if ($currDb != 'main' && $currDb != '') {
                $this->dbitemsname .= '-' . $currDb;
                $this->mainitems = get_option($this->dbitemsname);
            }
            //===setting up the db END

            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . "dzsvg_backup.txt" . '"');
            echo serialize($this->mainitems);
            die();
        }
        if (isset($_POST['dzsvg_dismiss_limit_notice']) && $_POST['dzsvg_dismiss_limit_notice'] == 'dismiss') {
            $this->mainoptions['settings_limit_notice_dismissed'] = 'on';

//            print_r($this->mainoptions);

            update_option($this->dboptionsname, $this->mainoptions);
        }

        if (isset($_POST['dzsvg_exportslider'])) {


            //===setting up the db
            $currDb = '';
            if (isset($_POST['currdb']) && $_POST['currdb'] != '') {
                $this->currDb = $_POST['currdb'];
                $currDb = $this->currDb;
            }

            //echo 'ceva'; print_r($this->dbs);
            if ($currDb != 'main' && $currDb != '') {
                $this->dbitemsname .= '-' . $currDb;
                $this->mainitems = get_option($this->dbitemsname);
            }
            //===setting up the db END
            //print_r($currDb);

            header('Content-Type: text/plain');
            header('Content-Disposition: attachment; filename="' . "dzsvg-slider-" . $_POST['slidername'] . ".txt" . '"');
            //print_r($_POST);
            echo serialize($this->mainitems[$_POST['slidernr']]);
            die();
        }
	    if (isset($_POST['dzsvg_exportslider_config'])) {


		    //===setting up the db
		    $currDb = '';




		    //echo 'ceva'; print_r($this->dbs);

		    //===setting up the db END
		    //print_r($currDb);

		    header('Content-Type: text/plain');
		    header('Content-Disposition: attachment; filename="' . "dzsvg-slider-" . $_POST['slidername'] . ".txt" . '"');
		    //print_r($_POST);

		    error_log("EXPORTING SLIDER CONFIG ( currdb - ".$currDb." )". print_rr($this->mainvpconfigs, array('echo'=>false)));
		    echo serialize($this->mainvpconfigs[$_POST['slidernr']]);
		    die();
	    }


        if (isset($_POST['dzsvg_importdb'])) {

            if ( function_exists('wp_verify_nonce') && ( ! wp_verify_nonce( $_REQUEST['dzsvg_importdb_nonce'], 'dzsvg_importdb_nonce' ) ) ) {

                die( 'Security check' );

            }

            $file_data = file_get_contents($_FILES['dzsvg_importdbupload']['tmp_name']);
            $this->mainitems = unserialize($file_data);
            update_option($this->dbitemsname, $this->mainitems);
        }

        if (isset($_POST['dzsvg_importslider'])) {




            if ( function_exists('wp_verify_nonce') &&  (! wp_verify_nonce( $_REQUEST['dzsvg_importdb_nonce'], 'dzsvg_importdb_nonce' ) ) ) {

                die( 'Security check' );

            }

            //print_r( $_FILES);
            $file_data = file_get_contents($_FILES['importsliderupload']['tmp_name']);
            $auxslider = unserialize($file_data);
            //dzs_replace_in_matrix('http://localhost/wpmu/eos/wp-content/themes/eos/', THEME_URL, $this->mainitems);
            //dzs_replace_in_matrix('http://eos.digitalzoomstudio.net/wp-content/themes/eos/', THEME_URL, $this->mainitems);
            //echo 'ceva';
            //print_r($auxslider);
            $this->mainitems = get_option($this->dbitemsname);
            //print_r($this->mainitems);
            $this->mainitems[] = $auxslider;

            update_option($this->dbitemsname, $this->mainitems);
        }




        if (isset($_POST['dzsvg_saveoptions'])) {
            if ($_POST['use_external_uploaddir'] == 'on') {
                copy(dirname(__FILE__) . '/admin/upload.php', dirname(dirname(dirname(__FILE__))) . '/upload.php');
                $mypath = dirname(dirname(dirname(__FILE__))) . '/upload';
                if (is_dir($mypath) === false && file_exists($mypath) === false) {
                    mkdir($mypath, 0755);
                }
            }


            //$this->mainoptions['embed_masonry'] = $_POST['embed_masonry'];
            update_option($this->dboptionsname, $this->mainoptions);
        }
    }

    function post_save_mo() {

        $auxarray_defs = array('disable_api_caching' => 'off', 'disable_fontawesome' => 'off', 'tinymce_enable_preview_shortcodes' => 'off', 'force_file_get_contents' => 'off', 'debug_mode' => 'off', 'settings_trigger_resize' => 'off', 'replace_wpvideo' => 'off',
            'usewordpressuploader' => 'on',
            'dzsvp_enable_visitorupload' => 'off',
            'dzsvp_enable_ratings' => 'off',
            'dzsvp_enable_ratingscount' => 'off',
            );
        $auxarray = array();
        //parsing post data
        parse_str($_POST['postdata'], $auxarray);
//        print_r($auxarray);


//        print_r($_POST);
//        print_r($auxarray);

        $auxarray = array_merge($auxarray_defs, $auxarray);


        if (isset($auxarray['use_external_uploaddir']) && $auxarray['use_external_uploaddir'] == 'on') {

            $path_uploadfile = dirname(dirname(dirname(__FILE__))) . '/upload.php';
            if (file_exists($path_uploadfile) === false) {
                copy(dirname(__FILE__) . '/admin/upload.php', $path_uploadfile);
            }
            $path_uploaddir = dirname(dirname(dirname(__FILE__))) . '/upload';
            if (is_dir($path_uploaddir) === false) {
                mkdir($path_uploaddir, 0777);
            }
        }




        $lab = 'dzsvp_enable_user_upload_capability';

        if(isset($auxarray[$lab]) && $this->mainoptions[$lab]=='on'){

            $role = get_role( 'subscriber' );

            // This only works, because it accesses the class instance.
            // would allow the author to edit others' posts for current theme only
            $role->add_cap( 'upload_files' );
//            $role->remove_cap( 'upload_files' );
        }


//        echo $auxarray['track_views'].'|||| '.
	    if (
	            ( isset($auxarray['track_views']) && $auxarray['track_views'] == 'on' && (isset($this->mainoptions['track_views']) == false || $this->mainoptions['track_views'] == 'off') )
             || ( $auxarray['videopage_show_views']=='on' && $this->mainoptions['videopage_show_views']=='off')
             || (isset($auxarray['analytics_enable']) && $auxarray['analytics_enable'] == 'on')
        ) {
//            echo 'hmmdadadadada';


		    if ( $this->mainoptions['analytics_table_created'] == 'off' ) {

			    $this->analytics_table_create();
		    }

        }


        $auxarray = array_merge($this->mainoptions, $auxarray);
//        print_r($auxarray);;

        update_option($this->dboptionsname, $auxarray);
        die();
    }

    function analytics_table_create(){

		    global $wpdb;
//            $table_name = $wpdb->prefix . 'dzsvg_views';
//            if ($wpdb->get_var("SHOW TABLES LIKE '$table_name'") != $table_name) {
//                //table not in database. Create new table
//                $charset_collate = $wpdb->get_charset_collate();
//
//                $sql = "CREATE TABLE $table_name (
//          id mediumint(9) NOT NULL AUTO_INCREMENT,
//          type varchar(100) NOT NULL,
//          id_user int(10) NOT NULL,
//          ip varchar(255) NOT NULL,
//          id_video int(10) NOT NULL,
//          date datetime NOT NULL,
//          UNIQUE KEY id (id)
//     ) $charset_collate;";
//                require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
//                dbDelta($sql);
//            } else {
//            }
		    $table_name = $wpdb->prefix . 'dzsvg_activity';
		    if ( $wpdb->get_var( "SHOW TABLES LIKE '$table_name'" ) != $table_name ) {
			    //table not in database. Create new table
			    $charset_collate = $wpdb->get_charset_collate();

			    $sql = "CREATE TABLE $table_name (
          id mediumint(9) NOT NULL AUTO_INCREMENT,
          type varchar(100) NOT NULL,
          country varchar(100) NULL,
          id_user int(10) NOT NULL,
          val int(255) NOT NULL,
          ip varchar(255) NOT NULL,
          id_video int(10) NOT NULL,
          date datetime NOT NULL,
          UNIQUE KEY id (id)
     ) $charset_collate;";
			    require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
			    dbDelta( $sql );

			    $this->mainoptions['analytics_table_created'] = 'on';

			    $auxarray['analytics_table_created'] = 'on';;

		    } else {
		    }

	    }

    function post_save_options_dc() {
        $auxarray = array();
        //parsing post data
        parse_str($_POST['postdata'], $auxarray);
        print_r($auxarray);


        update_option($this->dbdcname, $auxarray);
        die();
    }

    function post_save_options_dc_aurora() {
        $auxarray = array();
        //parsing post data
        parse_str($_POST['postdata'], $auxarray);
        print_r($auxarray);


        update_option($this->dbname_dc_aurora, $auxarray);
        die();
    }

    function post_save() {
        //---this is the main save function which saves gallery
        $auxarray = array();
        $mainarray = array();

        //print_r($this->mainitems);
        //parsing post data
        parse_str($_POST['postdata'], $auxarray);


        if (isset($_POST['currdb'])) {
            $this->currDb = $_POST['currdb'];
        }
        //echo 'ceva'; print_r($this->dbs);
        if ($this->currDb != 'main' && $this->currDb != '') {
            $this->dbitemsname .= '-' . $this->currDb;
        }

        //echo $this->dbitemsname;
        if (isset($_POST['sliderid'])) {
            //print_r($auxarray);
            $mainarray = get_option($this->dbitemsname);
            foreach ($auxarray as $label => $value) {
                $aux = explode('-', $label);
                $tempmainarray[$aux[1]][$aux[2]] = $auxarray[$label];
            }
            $mainarray[$_POST['sliderid']] = $tempmainarray;
        } else {
            foreach ($auxarray as $label => $value) {
                //echo $auxarray[$label];
                $aux = explode('-', $label);
                $mainarray[$aux[0]][$aux[1]][$aux[2]] = $auxarray[$label];
            }
        }
//        echo $this->dbitemsname; print_r($_POST); print_r($this->currDb); echo isset($_POST['currdb']);
        update_option($this->dbitemsname, $mainarray);
        echo 'success';
        die();
    }

    function ajax_insert_ads() {
        //---this is the main save function which saves gallery

        $ad_arr_str = '';


        $postdata = array();


        parse_str($_POST['postdata'], $postdata);



        $ad_arr = array();

        foreach ($postdata['source'] as $lab => $val){

//            echo ' lab - '.$lab.' val - '.$val.' ||| ';


            if($postdata['source'][$lab]){

            }else{
                continue;
            }
            $aux_arr = array(
                'source'=>$postdata['source'][$lab],
            );


            $adlab = 'time';

            if(isset($postdata[$adlab][$lab]) && $postdata[$adlab][$lab]){
                $aux_arr[$adlab] = $postdata[$adlab][$lab];
            }


            $adlab = 'type';

            if(isset($postdata[$adlab][$lab]) && $postdata[$adlab][$lab]){
                $aux_arr[$adlab] = $postdata[$adlab][$lab];
            }


            $adlab = 'ad_link';

            if(isset($postdata[$adlab][$lab]) && $postdata[$adlab][$lab]){
                $aux_arr[$adlab] = $postdata[$adlab][$lab];
            }


            $adlab = 'skip_delay';

            if(isset($postdata[$adlab][$lab]) && $postdata[$adlab][$lab]){
                $aux_arr[$adlab] = $postdata[$adlab][$lab];
            }

            array_push($ad_arr, $aux_arr);
        }


        $ad_arr_str = json_encode($ad_arr);
        print_r($ad_arr_str);

        die();
    }

    function ajax_insert_quality() {
        //---this is the main save function which saves gallery

        $ad_arr_str = '';


        $postdata = array();


        parse_str($_POST['postdata'], $postdata);


//        print_rr($postdata);

        $ad_arr = array();

        foreach ($postdata['source'] as $lab => $val){

//            echo ' lab - '.$lab.' val - '.$val.' ||| ';


            if($postdata['source'][$lab]){

            }else{
                continue;
            }
            $aux_arr = array(
                'source'=>$postdata['source'][$lab],
                'label'=>$postdata['label'][$lab],
            );
            


            


            array_push($ad_arr, $aux_arr);
        }


        $ad_arr_str = json_encode($ad_arr);
        echo ($ad_arr_str);

        die();
    }

    function post_get_db_gals() {

        if (isset($_POST['postdata'])) {
            $this->currDb = $_POST['postdata'];
        }


        if ($this->currDb != 'main' && $this->currDb != '') {
            $this->dbitemsname .= '-' . $this->currDb;
        }


        $mainarray = get_option($this->dbitemsname);

        $i = 0;
        foreach ($mainarray as $gal) {
            if ($i > 0) {
                echo ';';
            }

            echo $gal['settings']['id'];

            $i++;
        }


        //echo 'success';
        die();
    }

    function post_save_vpc() {
        //---this is the main save function which saves item
        $auxarray = array();
        $mainarray = array();

        //print_r($this->mainitems);
        //parsing post data
        parse_str($_POST['postdata'], $auxarray);

//        print_r($auxarray);

        if (isset($_POST['currdb'])) {
            $this->currDb = $_POST['currdb'];
        }
        //echo 'ceva'; print_r($this->dbs);
//        if ($this->currDb != 'main' && $this->currDb != '') {
//            $this->dbvpconfigsname .= '-' . $this->currDb;
//        }
        //echo $this->dbitemsname;
        if (isset($_POST['sliderid'])) {
            //print_r($auxarray);
            $mainarray = get_option($this->dbvpconfigsname);
            foreach ($auxarray as $label => $value) {
                $aux = explode('-', $label);
                $tempmainarray[$aux[1]][$aux[2]] = $auxarray[$label];
            }
            $mainarray[$_POST['sliderid']] = $tempmainarray;
        } else {
            foreach ($auxarray as $label => $value) {
                //echo $auxarray[$label];
                $aux = explode('-', $label);
                $mainarray[$aux[0]][$aux[1]][$aux[2]] = $auxarray[$label];
            }
        }
        
//        echo '$this->dbvpconfigsname - '.$this->dbvpconfigsname;
        //echo $this->dbitemsname; print_r($_POST); print_r($this->currDb); echo isset($_POST['currdb']);

        print_r($mainarray);
        update_option($this->dbvpconfigsname, $mainarray);
        echo 'success';
        die();
    }

    function post_importytplaylist() {
        //echo 'ceva';
        $pd = $_POST['postdata'];
        //echo $aux;
        $yf_maxi = 100;
        $i = 0;
        $its = array();

        $str_apikey = '';

        if ($this->mainoptions['youtube_api_key'] != '') {
            $str_apikey = '&key=' . $this->mainoptions['youtube_api_key'];
        }

        $target_file = $this->httpprotocol . "://gdata.youtube.com/feeds/api/playlists/" . $pd . "?alt=json&start-index=1&max-results=40" . $str_apikey;
        $ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));
        $idar = json_decode($ida);
        //print_r($idar);
        if ($idar == false) {
            echo 'error: ' . 'check the id';
        } else {
            foreach ($idar->feed->entry as $ytitem) {
                $cache = $ytitem;
                $aux = array();
                $auxtitle = '';
                $auxcontent = '';
                //print_r($cache);
                //print_r(get_object_vars($cache->title));
                foreach ($cache->title as $hmm) {
                    $auxtitle = $hmm;
                    break;
                }
                foreach ($cache->content as $hmm) {
                    $auxcontent = $hmm;
                    break;
                }
                //print_r($aux2);
                //print_r(parse_str($cache->title));
                parse_str($ytitem->link[0]->href, $aux);
                //print_r($aux);

                $its[$i]['source'] = $aux[$this->httpprotocol . '://www_youtube_com/watch?v'];
                $its[$i]['thethumb'] = "";
                $its[$i]['type'] = "youtube";
                $its[$i]['title'] = $auxtitle;
                $its[$i]['menuDescription'] = $auxcontent;
                $its[$i]['description'] = $auxcontent;

                //print_r($ytitem);
                $aux2 = get_object_vars($ytitem->title);
                $aux = ($aux2['$t']);
                $lb = array("\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                $aux = str_replace($lb, ' ', $aux);

                /*
                  $aux = $ytitem->description;
                  $lb   = array("\r\n", "\n", "\r", "&" ,"-", "`", '???', "'", '-');
                  $aux = str_replace($lb, ' ', $aux);
                  $its['settings']['description'] = $aux;
                 */
                $i++;
                if ($i > $yf_maxi) break;
            }
        }

        if (count($its) == 0) {
            echo 'error: ' . '<a href="' . $target_file . '">this</a> is what the feed returned ' . $ida;
            die();
        }
        for ($i = 0; $i < count($its); $i++) {

        }
        $sits = json_encode($its);
        echo $sits;


        die();
    }

    function post_importytuser() {
        //echo 'ceva';
        $pd = $_POST['postdata'];
        $yf_maxi = 100;
        $i = 0;
        $its = array();
        //echo $aux;
        //echo 'ceva';


        $sw = false;
        //print_r($idar);
        //print_r($idar);
        //print_r(count($idar->data->items));
        $i = 0;
        $yf_maxi = 100;

        //echo $ida;


        $target_file = $this->httpprotocol . "://gdata.youtube.com/feeds/api/users/" . $pd . "/uploads?v=2&alt=jsonc";
        $ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));
        $idar = json_decode($ida);

        if ($ida == 'yt:quotatoo_many_recent_calls') {
            echo 'error: too many recent calls - YouTube rejected the call';
            $sw = true;
        }
        //print_r($idar);

        if ($idar == false) {
            echo 'error: ' . 'check the id ';
            print_r($ida);
            die();
        } else {

            foreach ($idar->data->items as $ytitem) {
                //print_r($ytitem);
                $its[$i]['source'] = $ytitem->id;
                $its[$i]['thethumb'] = "";
                $its[$i]['type'] = "youtube";

                $aux = $ytitem->title;
                $lb = array('"', "\r\n", "\n", "\r", "&", "-", "`", '???', "'", '-');
                $aux = str_replace($lb, ' ', $aux);
                $its[$i]['title'] = $aux;

                $aux = $ytitem->description;
                $lb = array("\r\n", "\n", "\r", "&", '???');
                $aux = str_replace($lb, ' ', $aux);
                $lb = array('"');
                $aux = str_replace($lb, '&quot;', $aux);
                $lb = array("'");
                $aux = str_replace($lb, '&#39;', $aux);
                $its[$i]['description'] = $aux;

                $i++;
                if ($i > $yf_maxi + 1) break;
            }
        }
        if (count($its) == 0) {
            echo 'error: ' . 'this is what the feed returned ' . $ida;
            die();
        }
        $sits = json_encode($its);
        echo $sits;


        die();
    }


    function import_demo_create_term_if_it_does_not_exist($pargs = array()) {


        $margs = array(

            'term_name' => '',
            'slug' => '',
            'taxonomy' => '',
            'description' => '',
            'parent' => '',
        );

        $margs = array_merge($margs, $pargs);

        $term = get_term_by('slug', $margs['slug'], $margs['taxonomy']);


        if ($term) {

        } else {


            $args = array(
                'description' => $margs['description'],
                'slug' => $margs['slug'],


            );

            if ($margs['parent']) {
                $args['parent'] = $margs['parent'];
            }

            $term = wp_insert_term($margs['term_name'], $margs['taxonomy'], $args);

        }
        return $term;

    }



    function import_demo_create_attachment($img_url, $port_id, $img_path){






        $attachment = array(
            'guid'           => $img_url,
            'post_mime_type' => 'image/jpeg',
            'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $img_url ) ),
            'post_content'   => '',
            'post_status'    => 'inherit'
        );

// Insert the attachment.
        $attach_id = wp_insert_attachment( $attachment, $img_url, $port_id );


        require_once( ABSPATH . 'wp-admin/includes/image.php' );

// Generate the metadata for the attachment, and update the database record.
        $attach_data = wp_generate_attachment_metadata( $attach_id, $img_path );
//        die();
        wp_update_attachment_metadata( $attach_id, $attach_data );

        return $attach_id;
    }


    function import_demo_create_portfolio_item($pargs = array()) {






        $margs = array(

            'post_title'=>'',
            'post_content'=>'',
            'post_status'=>'',
            'post_type'=>'dzsvcs_port_items',
        );

        $margs = array_merge($margs, $pargs);



        $args = array(
            'post_type' => $margs['post_type'],
            'post_title' => $margs['post_title'],
            'post_content' => $margs['post_content'],
            'post_status'=>$margs['post_status'],



            /*other default parameters you want to set*/
        );



        $post_id = wp_insert_post($args);

        return $post_id;


    }

    function import_demo_insert_post_complete($pargs = array()) {






        $margs = array(

            'post_title'=>'',

            'post_content'=>'',
            'post_type'=>'dzsvideo',
            'post_status'=>'publish',
            'post_name'=>'',
            'img_url'=>'',
            'img_path'=>'',
            'term'=>'',
            'taxonomy'=>'',
            'attach_id'=>'',
            'dzsvp_thumb'=>'',
            'dzsvp_item_type'=>'detect',
            'dzsvp_featured_media'=>'',
            'dzsvg_meta_featured_media'=>'',
            'q_meta_port_optional_info_2'=>'',
            'q_meta_port_subtitle'=>'',
            'q_meta_port_website'=>'',
            'q_meta_video_cover_image'=>'',
            'q_meta_image_gallery_in_meta'=>'',

        );

        $margs = array_merge($margs, $pargs);



        if($margs['post_name']){


            $the_slug = $margs['post_name'];
            $args = array(
                'name'        => $the_slug,
                'post_type'   => $margs['post_type'],
                'post_status' => 'publish',
                'numberposts' => 1
            );
            $my_posts = get_posts($args);


//            print_rr($my_posts);


            if ($my_posts) {

                if($margs['term']){

	                if(is_object($margs['term']) && isset($margs['term']->term_id)){
		                $term = $margs['term']->term_id;
	                }else{

		                if(is_array($margs['term']) && isset($margs['term']['term_id'])){
			                $term = $margs['term']['term_id'];
		                }
                    }
//	                error_log('term for import - '.print_rr($term,true));
	                wp_set_post_terms($my_posts[0]->ID, $term, $margs['taxonomy']);
                }
                return $my_posts[0];


//                print_r($my_posts);
            }
        }

        $args = array(
            'post_type' => $margs['post_type'],
            'post_title' => $margs['post_title'],

            'post_content' => $margs['post_content'],
            'post_status'=>$margs['post_status'],



            /*other default parameters you want to set*/
        );

        if($margs['post_name']){
//            $args['name']=$margs['post_name'];
            $args['post_name']=$margs['post_name'];
        }


        if($margs['term']){

            $term = $margs['term'];
        }
        $taxonomy = $margs['taxonomy'];

        if($margs['img_url']){

            $img_url = $margs['img_url'];
        }
        $img_path = $margs['img_path'];



//        print_rr($margs);



//	    error_log(' item import - '.print_rr($margs,true).print_rr($args,true));
	    $port_id = $this->import_demo_create_portfolio_item($args);

	    if($margs['term']) {
		    $term = $margs['term'];



		    if(is_object($margs['term']) && isset($margs['term']->term_id)){
			    $term = $margs['term']->term_id;
		    }
		    wp_set_post_terms($port_id, $term, $taxonomy);
	    }



	    foreach ($margs as $lab => $val){
		    if(strpos($lab,'dzsvg_meta')===0){

			    update_post_meta($port_id,$lab,$val);
		    }
	    }






//        update_post_meta($port_id,'q_meta_post_media',$img_url);







        if($margs['attach_id']){

            set_post_thumbnail( $port_id, $margs['attach_id'] );
        }else{

            if($margs['img_url']) {
                $attach_id = $this->import_demo_create_attachment($img_url, $port_id, $img_path);
                set_post_thumbnail($port_id, $attach_id);

                $this->import_demo_last_attach_id = $attach_id;
            }

        }





        return $port_id;



    }



    function ajax_import_item_lib() {


        $cont = '';

        if($_POST['demo']=='sample_vimeo_channel33'){
//            $cont = json_encode(array(
//                'response_type'=>'success',
//                'items'=>array(
//
//                    array(
//                        'type'=>'slider_import',
//                        'src'=>'a:74:{s:8:"feedfrom";s:13:"vmuserchannel";s:2:"id";s:20:"sample_vimeo_channel";s:6:"height";s:3:"300";s:11:"displaymode";s:6:"normal";s:12:"skin_html5vg";s:28:"skin-boxy skin-boxy--rounded";s:8:"vpconfig";s:17:"skinauroradefault";s:8:"nav_type";s:6:"thumbs";s:25:"nav_type_outer_max_height";s:0:"";s:12:"menuposition";s:6:"bottom";s:8:"autoplay";s:2:"on";s:12:"autoplaynext";s:2:"on";s:13:"cueFirstVideo";s:2:"on";s:9:"randomize";s:3:"off";s:5:"order";s:3:"ASC";s:10:"transition";s:4:"fade";s:27:"enableunderneathdescription";s:3:"off";s:19:"enable_search_field";s:3:"off";s:21:"search_field_location";s:7:"outside";s:23:"settings_enable_linking";s:3:"off";s:11:"autoplay_ad";s:2:"on";s:30:"set_responsive_ratio_to_detect";s:2:"on";s:11:"sharebutton";s:3:"off";s:12:"facebooklink";s:0:"";s:11:"twitterlink";s:0:"";s:14:"googlepluslink";s:0:"";s:16:"social_extracode";s:0:"";s:11:"embedbutton";s:3:"off";s:4:"logo";s:0:"";s:8:"logoLink";s:0:"";s:14:"html5designmiw";s:3:"120";s:14:"html5designmih";s:3:"120";s:14:"html5designmis";s:2:"15";s:16:"thumb_extraclass";s:0:"";s:24:"disable_menu_description";s:3:"off";s:26:"design_navigationuseeasing";s:2:"on";s:23:"menu_description_format";s:0:"";s:9:"max_width";s:0:"";s:10:"coverImage";s:0:"";s:9:"nav_space";s:2:"30";s:13:"disable_title";s:3:"off";s:19:"disable_video_title";s:3:"off";s:10:"laptopskin";s:3:"off";s:15:"html5transition";s:7:"slideup";s:3:"rtl";s:3:"off";s:13:"extra_classes";s:0:"";s:7:"bgcolor";s:11:"transparent";s:6:"shadow";s:3:"off";s:5:"width";s:4:"100%";s:16:"forcevideoheight";s:0:"";s:16:"mode_wall_layout";s:4:"none";s:11:"maxlen_desc";s:3:"250";s:15:"readmore_markup";s:65:"<p><a class=ignore-zoombox href={{postlink}}>read more »</a></p>";s:9:"striptags";s:2:"on";s:26:"try_to_close_unclosed_tags";s:2:"on";s:22:"desc_aside_maxlen_desc";s:3:"250";s:20:"desc_aside_striptags";s:2:"on";s:37:"desc_aside_try_to_close_unclosed_tags";s:2:"on";s:17:"rtmp_streamserver";s:0:"";s:16:"enable_secondcon";s:3:"off";s:15:"enable_outernav";s:3:"off";s:28:"enable_outernav_video_author";s:3:"off";s:9:"playorder";s:3:"ASC";s:7:"init_on";s:4:"init";s:19:"ids_point_to_source";s:3:"off";s:39:"autoplay_on_mobile_too_with_video_muted";s:3:"off";s:16:"youtubefeed_user";s:0:"";s:17:"ytplaylist_source";s:0:"";s:17:"ytkeywords_source";s:0:"";s:21:"youtubefeed_maxvideos";s:2:"50";s:14:"vimeofeed_user";s:9:"fancyshot";s:17:"vimeofeed_channel";s:0:"";s:17:"vimeofeed_vmalbum";s:0:"";s:15:"vimeo_maxvideos";s:2:"25";s:10:"vimeo_sort";s:7:"default";}}',
//                    )
//                ),
//                'settings'=>array(
//                    'final_shortcode'=>'[dzs_videogallery id="'.$lab.'" db="main"]'
//                ),
//            ));
        }else{

            $url = 'https://zoomthe.me/updater_dzsvg/getdemo.php?demo='.$_POST['demo'].'&purchase_code='.$this->mainoptions['dzsvg_purchase_code'].'&site_url='.urlencode(site_url());
            $cont = file_get_contents($url);
        }




//        echo $url;




        $resp = json_decode($cont,true);


        if($resp['response_type']=='success'){
            
//            print_r($resp);
            foreach ($resp['items'] as $lab=>$it){
//                error_log('import item lib - '.print_r($it,true));






	            if($it['type']=='vpconfig_import'){

		            $sw_import = true;
		            $slider = unserialize($it['src']);


		            //                    print_r($slider);
		            error_log('$slider[\'settings\'][\'id\'] - '.print_r($slider['settings']['id'],true));
		            error_log('mainitems_configs - '.print_r($this->mainvpconfigs,true));
		            foreach ($this->mainvpconfigs as $mainitem){
			            //                        print_r($mainitem);

			            if($slider['settings']['id']===$mainitem['settings']['id']){

				            //                            echo '$slider[\'settings\'][\'id\'] - '.$slider['settings']['id'].' - $mainitem[\'settings\'][\'id\'] - '.$mainitem['settings']['id'];
				            $sw_import=false;
			            }
		            }

		            //                    print_r($slider);
		            //                    echo '$sw_import - '.$sw_import;


		            if($sw_import){






			            array_push($this->mainvpconfigs, $slider);



			            error_log('mainitems_configs - '.print_r($this->mainvpconfigs,true));
			            update_option($this->dbvpconfigsname, $this->mainvpconfigs);
		            }
	            }





                if($it['type']=='slider_import'){

                    $sw_import = true;
                    $slider = unserialize($it['src']);





                    $file_cont = $it['src'];

	                $sw_import = $this->import_slider($file_cont);


//                    print_r($slider);
//                    foreach ($this->mainitems as $mainitem){
////                        print_r($mainitem);
//
//                        if($slider['settings']['id']===$mainitem['settings']['id']){
//
////                            echo '$slider[\'settings\'][\'id\'] - '.$slider['settings']['id'].' - $mainitem[\'settings\'][\'id\'] - '.$mainitem['settings']['id'];
//                            $sw_import=false;
//                        }
//                    }

//                    print_r($slider);
//                    echo '$sw_import - '.$sw_import;


                    if($sw_import){






                        array_push($this->mainitems, $slider);



                        update_option($this->dbitemsname, $this->mainitems);
                    }
                }



                if($it['type']=='dzsvideo_category'){


                    $args = $it;


                    $args['taxonomy']='dzsvideo_category';
                    $this->import_demo_create_term_if_it_does_not_exist($args);


                }
                if($it['type']=='dzsvideo'){


                    $args = $it;




                    $taxonomy = 'dzsvideo_category';

                    if($args['term_slug']){



                        $term = get_term_by('slug', $args['term_slug'], $taxonomy);


                        if ($term) {



                            $args['term']=$term;


                        }


                        $args['taxonomy']=$taxonomy;

                    }








//	                error_log('import_demo_insert_post_complete pre args - '.print_r($args,true));




                    $this->import_demo_insert_post_complete($args);


                }
            }
        }


        echo json_encode($resp);
        die();
    }
    function ajax_import_galleries() {


        if ($this->mainitems == '') {
            $this->mainitems = array();
        }


        $mainitems_default_ser = file_get_contents(dirname(__FILE__) . '/sampledata/sample_items.txt');
        $aux = unserialize($mainitems_default_ser);

//        print_r($aux);
        foreach ($aux as $lab => $val) {
//            print_r($val);

            $seekid = $val['settings']['id'];


            $sw = false;
            foreach ($this->mainitems as $lab2 => $val2) {

                if ($seekid === $val2['settings']['id']) {

                    $sw = true;
                    break;
                }

            }

            if ($sw) {
                unset($aux[$lab]);
            }
        }
//        print_r($aux);
        $this->mainitems = array_merge($this->mainitems, $aux);
        update_option($this->dbitemsname, $this->mainitems);


        echo 'success - ' . __('galleries imported for sample data use');
        die();
    }


    function ajax_import_sample_items() {

//        echo 'hmmdada2';

//        echo $this->base_path.'class_parts/install_sample_data.php';
        if (get_option('dzsvg_demo_data') == '') {
            include_once($this->base_path . 'class_parts/install_sample_data.php');
        }

        die();
    }


    function ajax_remove_sample_items() {

        $demo_data = get_option('dzsvg_demo_data');

//        print_r($demo_data);

        $taxonomy= 'dzsvideo_category';


        foreach($demo_data['posts'] as $pid) {
            wp_delete_post($pid);
        };

        foreach($demo_data['cats'] as $categ_ID) {
            wp_delete_term($categ_ID,$taxonomy);
        };

        delete_option('dzsvg_demo_data');


        die();
    }

    function login_enqueue_scripts(){
        if(defined('DZSVG_PREVIEW') && DZSVG_PREVIEW=="YES"){?><script>
            (function() {
//                console.info("ceva");
                setTimeout(function(){

                    document.getElementById('user_login').value = 'demouser';
                    document.getElementById('user_pass').value = 'demouser';
                },1000);
            })();
        </script>
        <?php

        }
    }

    function ajax_deactivate_license() {

        $this->mainoptions['dzsvg_purchase_code'] = '';
        $this->mainoptions['dzsvg_purchase_code_binded'] = 'off';
        update_option($this->dboptionsname, $this->mainoptions);
        die();
    }
    function ajax_activate_license() {





        $this->mainoptions['dzsvg_purchase_code'] = $_POST['postdata'];
        $this->mainoptions['dzsvg_purchase_code_binded'] = 'on';
        update_option($this->dboptionsname, $this->mainoptions);
        die();

    }
    function ajax_delete_notice() {





//        print_r($_POST);

        update_option($_POST['postdata'],'seen');
        die();
    }



    function ajax_submit_view() {



//                $date = date("Y-m-d", time() - 60 * 60 * 24);
        $playerid = 1;
        if (isset($_POST['playerid'])) {
            $playerid = $_POST['playerid'];
            $playerid = str_replace('ap', '', $playerid);
        }





        if(isset($_COOKIE["dzsvp_viewsubmitted-" . $playerid]) && $_COOKIE["dzsvp_viewsubmitted-" . $playerid]=='1'){

        }else{

            $currip = $this->misc_get_ip();
            $date = date('Y-m-d H:i:s');

            setcookie("dzsvp_viewsubmitted-" . $playerid, $_POST['postdata'], time() + 36000, COOKIEPATH);

            global $wpdb;
            $table_name = $wpdb->prefix.'dzsvg_activity';

            $user_id = get_current_user_id();


            $wpdb->insert(
                $table_name,
                array(
                    'ip' => $currip,
                    'type' => 'view',
                    'id_user' => $user_id,
                    'id_video' => $playerid,
                    'date' => $date,
                )
            );




            echo 'success';
        }


//        print_r($_COOKIE);




        die();




    }



    function sanitize_vimeo_url_to_id($arg){

        $fout = $arg;

        if(strpos($arg,'/')!==false){

	        $argarr = explode('/',$arg);

	        $fout = $argarr[count($argarr)-1];
        }

        return $fout;
    }

    function ajax_get_vimeothumb() {
        $id = $_POST['postdata'];


	    $id = $this->sanitize_vimeo_url_to_id($id);

//	    echo 'id - '.$id;

        if ( $this->mainoptions['vimeo_api_client_id'] != '' && $this->mainoptions['vimeo_api_client_secret'] != '' && $this->mainoptions['vimeo_api_access_token'] != '') {


            if (!class_exists('VimeoAPIException')) {
                require_once(dirname(__FILE__) . '/vimeoapi/vimeo.php');
            }


            $vimeo_id = $this->mainoptions['vimeo_api_user_id']; // Get from https://vimeo.com/settings, must be in the form of user123456
            $consumer_key = $this->mainoptions['vimeo_api_client_id'];
            $consumer_secret = $this->mainoptions['vimeo_api_client_secret'];
            $token = $this->mainoptions['vimeo_api_access_token'];

            // Do an authentication call
            $vimeo = new Vimeo($consumer_key, $consumer_secret);
            $vimeo->setToken($token); //,$token_secret





            $vimeo_response = $vimeo->request('/videos/' . $id . '/pictures');

            if ($vimeo_response['status'] != 200) {
//                        throw new Exception($channel_videos['body']['message']);
                echo 'error - vimeo error';

                print_r($vimeo_response);
            }

            $ida = '';
            if (isset($vimeo_response['body']['data'])) {
                $ida = $vimeo_response['body']['data'];
            }


//            print_r($ida);

            if($ida && $ida[0]){

                $vimeo_quality_ind = 2;

                if($this->mainoptions['vimeo_thumb_quality']=='medium'){

                    $vimeo_quality_ind = 3;
                }

                if($this->mainoptions['vimeo_thumb_quality']=='high'){

                    $vimeo_quality_ind = 4;
                }

                if(isset($ida[0]['sizes'][$vimeo_quality_ind]['link'])){
                    echo $ida[0]['sizes'][$vimeo_quality_ind]['link'];
                }else{
                    if(isset($ida[0]['sizes'][(--$vimeo_quality_ind)]['link'])){
                        echo $ida[0]['sizes'][$vimeo_quality_ind]['link'];
                    }else{
                        if(isset($ida[0]['sizes'][(--$vimeo_quality_ind)]['link'])){
                            echo $ida[0]['sizes'][$vimeo_quality_ind]['link'];
                        }
                    }
                }


            }

        }else{

//            $hash = unserialize(DZSHelpers::get_contents("https://vimeo.com/api/v2/video/".$id.".php"));

//                    print_r($hash);
//            $str_featuredimage = $hash[0]['thumbnail_medium'];



//            die($str_featuredimage);
        }




        die();
    }



    function sanitize_term_slug_to_id($arg, $taxonomy_name = 'dzsvideo_category'){


        if(is_numeric($arg)){

        }else{

            $term = get_term_by('slug', $arg, $taxonomy_name);

            if($term){
                $arg = $term->term_id;
            }
//                    echo 'new term_id - '; print_r($term_id);
        }


        return $arg;
    }

    function post_importvimeouser() {
        //echo 'ceva';
        $pd = $_POST['postdata'];
        $yf_maxi = 100;
        $i = 0;
        $its = array();
        //echo $aux;
        $target_file = "https://vimeo.com/api/v2/".$pd."/videos.json";
        $ida = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $this->mainoptions['force_file_get_contents']));
        $idar = json_decode($ida);
        $i = 0;
        if ($idar == false) {
            echo 'error: '.'check the id ';
            print_r($ida);
            die();
        } else {
            foreach ($idar as $item) {
                $its[$i]['source'] = $item->id;
                $its[$i]['thethumb'] = $item->thumbnail_small;


                $its[$i]['type'] = "vimeo";

                $aux = $item->title;
                $lb = array('"',"\r\n","\n","\r","&","-","`",'???',"'",'-');
                $aux = str_replace($lb,' ',$aux);
                $its[$i]['title'] = $aux;

                $aux = $item->description;
                $lb = array("\r\n","\n","\r","&",'???');
                $aux = str_replace($lb,' ',$aux);
                $lb = array('"');
                $aux = str_replace($lb,'&quot;',$aux);
                $lb = array("'");
                $aux = str_replace($lb,'&#39;',$aux);
                $its[$i]['description'] = $aux;
                $i++;
            }
        }
        if (count($its) == 0) {
            echo 'error: '.'this is what the feed returned '.$ida;
            die();
        }

        $sits = json_encode($its);
        echo $sits;


        die();
    }

    function filter_attachment_fields_to_edit($form_fields,$post) {


        $vpconfigsstr = '';
        $the_id = $post->ID;
        $post_type = get_post_mime_type($the_id);
        //print_r($this->mainvpconfigs);

        if (strpos($post_type,"video") === false) {
            return $form_fields;
        }


        foreach ($this->mainvpconfigs as $vpconfig) {
            //print_r($vpconfig);
            $vpconfigsstr .='<option value="'.$vpconfig['settings']['id'].'">'.$vpconfig['settings']['id'].'</option>';
        }

        $html_sel = '<select class="styleme" id="attachments-'.$post->ID.'-video-player-config" name="attachments['.$post->ID.'][video-player-config]">';
        $html_sel.=$vpconfigsstr;
        $html_sel .='</select>';
        $form_fields['video-player-config'] = array(
            'label' => 'Video Player Config',
            'input' => 'html',
            'html' => $html_sel,
            'helps' => 'choose a configuration for the player / edit in Video Gallery > Player Configs',
        );

        $form_fields['video-player-height'] = array(
            'label' => 'Force Height',
            'input' => 'html',
            'html' => '<input type="text" id="attachments-'.$post->ID.'-video-player-height" name="attachments['.$post->ID.'][video-player-height]"/>',
            'helps' => 'force a height',
        );





        return $form_fields;
    }

	function show_generator_export_slider_config() {
		?>Please note that this feature uses the last saved data. Unsaved changes will not be exported.
        <form action="<?php echo site_url().'/wp-admin/options-general.php?page=dzsvg_menu'; ?>" method="POST">
            <input type="hidden" class="hidden" name="slidernr" value="<?php echo $_GET['slidernr']; ?>"/>
            <input type="hidden" class="hidden" name="slidername" value="<?php echo $_GET['slidername']; ?>"/>
            <input type="hidden" class="hidden" name="currdb" value="<?php echo $_GET['currdb']; ?>"/>
            <input class="button-secondary" type="submit" name="dzsvg_exportslider_config" value="Export"/>
        </form>
		<?php
	}
    function show_generator_export_slider() {
        ?>Please note that this feature uses the last saved data. Unsaved changes will not be exported.
        <form action="<?php echo site_url().'/wp-admin/options-general.php?page=dzsvg_menu'; ?>" method="POST">
            <input type="hidden" class="hidden" name="slidernr" value="<?php echo $_GET['slidernr']; ?>"/>
            <input type="hidden" class="hidden" name="slidername" value="<?php echo $_GET['slidername']; ?>"/>
            <input type="hidden" class="hidden" name="currdb" value="<?php echo $_GET['currdb']; ?>"/>
            <input class="button-secondary" type="submit" name="dzsvg_exportslider" value="Export"/>
        </form>
        <?php
    }

}

//add_filter( 'script_loader_attrs', 'my_function' );
//
//function my_function( $attrs ) {
//    $attrs = array('async' => 'async', 'charset' => 'utf8'); // whatever attributes you want
//
//   // alternatively, eliminate type='text/javascript' by emptying $attrs:
//   // $attrs = '';
//
//   return $attrs;
//}