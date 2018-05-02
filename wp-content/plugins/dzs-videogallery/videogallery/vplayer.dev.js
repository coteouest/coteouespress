/*
 * Author: Digital Zoom Studio
 * Website: http://digitalzoomstudio.net/
 * Portfolio: http://codecanyon.net/user/ZoomIt/portfolio?ref=ZoomIt
 * This is not free software.
 * Video Gallery
 * Version: 10.20
 */

"use strict";

var vgsettings = {
    protocol: 'https'
    , vimeoprotocol: 'https:'
};

var youtubeid_array = []; // -- an array to hold inited youtube videos
var dzsvp_players_arr = []; // -- an array to hold video players
var dzsvp_yt_iframe_settoload = false;
var _global_youtubeIframeAPIReady  = false;
var _global_vimeoIframeAPIReady  = false;
var _global_vimeoIframeAPILoading  = false;
var _global_vimeoIframeAPILoading_inter  = 0;


var svg_quality_icon = '<svg class="the-icon" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="896.025px" height="896.025px" viewBox="0 0 896.025 896.025" style="enable-background:new 0 0 896.025 896.025;" xml:space="preserve"> <g> <path id="settings_1_" d="M863.24,382.771l-88.759-14.807c-6.451-26.374-15.857-51.585-28.107-75.099l56.821-70.452 c12.085-14.889,11.536-36.312-1.205-50.682l-35.301-39.729c-12.796-14.355-34.016-17.391-50.202-7.165l-75.906,47.716 c-33.386-23.326-71.204-40.551-112-50.546l-14.85-89.235c-3.116-18.895-19.467-32.759-38.661-32.759h-53.198 c-19.155,0-35.561,13.864-38.608,32.759l-14.931,89.263c-33.729,8.258-65.353,21.588-94.213,39.144l-72.188-51.518 c-15.558-11.115-36.927-9.377-50.504,4.171l-37.583,37.61c-13.548,13.577-15.286,34.946-4.142,50.504l51.638,72.326 c-17.391,28.642-30.584,60.086-38.841,93.515l-89.743,14.985C13.891,385.888,0,402.24,0,421.435v53.156 c0,19.193,13.891,35.547,32.757,38.663l89.743,14.985c6.781,27.508,16.625,53.784,29.709,78.147L95.647,676.44 c-12.044,14.875-11.538,36.312,1.203,50.669l35.274,39.73c12.797,14.382,34.028,17.363,50.216,7.163l77-48.37 c32.581,22.285,69.44,38.664,108.993,48.37l14.931,89.25c3.048,18.896,19.453,32.76,38.608,32.76h53.198 c19.194,0,35.545-13.863,38.661-32.759l14.875-89.25c33.308-8.147,64.531-21.245,93.134-38.5l75.196,53.705 c15.53,11.155,36.915,9.405,50.478-4.186l37.598-37.597c13.532-13.536,15.365-34.893,4.127-50.479l-53.536-75.059 c17.441-28.738,30.704-60.238,38.909-93.816l88.758-14.82c18.921-3.116,32.756-19.469,32.756-38.663v-53.156 C895.998,402.24,882.163,385.888,863.24,382.771z M449.42,616.013c-92.764,0-168-75.25-168-168c0-92.764,75.236-168,168-168 c92.748,0,167.998,75.236,167.998,168C617.418,540.763,542.168,616.013,449.42,616.013z"/> </g> </svg>';




var svg_embed = '<svg width="32.00199890136719" height="32" viewBox="0 0 32.00199890136719 32" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g><path d="M 23.586,9.444c 0.88,0.666, 1.972,1.064, 3.16,1.064C 29.648,10.508, 32,8.156, 32,5.254 C 32,2.352, 29.648,0, 26.746,0c-2.9,0-5.254,2.352-5.254,5.254c0,0.002,0,0.004,0,0.004L 8.524,11.528 C 7.626,10.812, 6.49,10.38, 5.254,10.38C 2.352,10.38,0,12.734,0,15.634s 2.352,5.254, 5.254,5.254c 1.048,0, 2.024-0.312, 2.844-0.84 l 13.396,6.476c0,0.002,0,0.004,0,0.004c0,2.902, 2.352,5.254, 5.254,5.254c 2.902,0, 5.254-2.352, 5.254-5.254 c0-2.902-2.352-5.254-5.254-5.254c-1.188,0-2.28,0.398-3.16,1.064L 10.488,16.006c 0.006-0.080, 0.010-0.158, 0.012-0.238L 23.586,9.444z"></path></g></svg>';

window.backup_onYouTubePlayerReady = null;


window.dzsvg_self_options = {};
window.dzsvp_self_options = {};

//VIDEO GALLERY
(function($) {



    Math.easeIn = function(t, b, c, d) {

        return -c *(t/=d)*(t-2) + b;

    };
    Math.easeOut = function (t, b, c, d) {
        t /= d;
        return -c * t*(t-2) + b;
    };




    $.fn.prependOnce = function(arg, argfind) {
        var _t = $(this) // It's your element


//        console.info(argfind);
        if(typeof(argfind) =='undefined'){
            var regex = new RegExp('class="(.*?)"');
            var auxarr = regex.exec(arg);


            if(typeof auxarr[1] !='undefined'){
                argfind = '.'+auxarr[1];
            }
        }



        // we compromise chaining for returning the success
        if(_t.children(argfind).length<1){
            _t.prepend(arg);
            return true;
        }else{
            return false;
        }
    };
    $.fn.appendOnce = function(arg, argfind) {
        var _t = $(this) // It's your element


        if(typeof(argfind) =='undefined'){
            var regex = new RegExp('class="(.*?)"');
            var auxarr = regex.exec(arg);


            if(typeof auxarr[1] !='undefined'){
                argfind = '.'+auxarr[1];
            }
        }
//        console.info(_t, _t.children(argfind).length, argfind);
        if(_t.children(argfind).length<1){
            _t.append(arg);
            return true;
        }else{
            return false;
        }
    };
    $.fn.vGallery = function(o) {

        var defaults = {
            totalWidth: ""
            ,totalHeight: ""
            ,init_on: "init"
            ,totalHeightDifferenceOnParent: ""
            ,forceVideoWidth: ''
            ,forceVideoHeight: ''
            ,menuSpace: 0 //deprecated replaced by nav_space
            ,randomise: "off"
            ,autoplay: "off"
            ,autoplayNext: "on" // -- play the next video when one finishes
            ,loop_playlist: "on" // -- loop the playlist from the beggining when the end has been reached
            ,autoplay_ad: "on"
            ,playorder: "normal" // -- normal or reverse
            ,menu_position: 'right'
            ,menuitem_width: "200"
            ,menuitem_height: "71"
            ,easing_speed: ""
            ,menuitem_space: "0"
            ,nav_type: "thumbs"//thumbs or thumbsandarrows or scroller
            ,nav_space: '0'
            ,transition_type: "slideup"
            ,design_skin: 'skin-default'
            ,videoplayersettings: {}
            ,embedCode: ''
            ,shareCode: ''
            ,php_media_data_retriever: '' // -- this can help get the video meta data for youtube and vimeo
            ,cueFirstVideo: 'on'
            ,design_navigationUseEasing: 'off'
            ,logo: ''
            ,logoLink: ''
            ,settings_enable_linking : 'on' // -- enable deeplinking on video gallery items
            ,settings_mode: 'normal' /// -- normal / wall / rotator / rotator3d / slider
            ,settings_disableVideo: 'off' //disable the video area
            ,settings_enableHistory : 'off' // html5 history api for link type items
            ,settings_enableHistory_fornormalitems : 'off' // html5 history api for normal items
            ,settings_ajax_extraDivs : '' // extra divs to pull in the ajax request
            ,startItem:'default'
            ,settings_separation_mode: 'normal' // === normal ( no pagination ) or pages or scroll or button
            ,settings_separation_pages: []
            ,settings_separation_pages_number: '5' //=== the number of items per 'page'
            ,nav_type_outer_grid: 'dzs-layout--4-cols' // -- four-per-row
            ,nav_type_outer_max_height: '' // -- enable a scroller if menu height bigger then max_height
            ,settings_menu_overlay: 'off' // -- an overlay to appear over the menu
            ,search_field: 'off' // an overlay to appear over the menu
            ,search_field_con: null // an overlay to appear over the menu
            ,disable_videoTitle: "off" // -- do not auto set the video title
            ,nav_type_auto_scroll: "off" // -- auto scroll nav
            ,settings_trigger_resize: '0' // -- a force trigger resize every x ms
            ,settings_go_to_next_after_inactivity: '0' // -- go to next track if no action
            ,modewall_bigwidth: '800' // -- the mode wall video ( when opened ) dimensions
            ,modewall_bigheight: '500'
            ,settings_secondCon: null
            ,settings_outerNav: null
            ,extra_class_slider_con: ''
            ,menu_description_format: '' // -- use something like "{{number}}{{menuimage}}{{title}}{{desc}}"
            ,masonry_options:{}
        };



        if(typeof o =='undefined'){
            if(typeof $(this).attr('data-options')!='undefined' && $(this).attr('data-options')!=''){
                var aux = $(this).attr('data-options');
                aux = 'window.dzsvg_self_options = ' + aux;
                eval(aux);
                o = $.extend({},window.dzsvg_self_options);
                window.dzsvg_self_options = $.extend({},{});
            }
        }

        o = $.extend(defaults, o);

        this.each(function() {

            var cgallery = $(this)
                ,cid = ''
            ;
            var nrChildren = 0;
            var _sliderMain
                , _sliderCon
                , _navMain
                , _navCon
                , _adSpace
                , _mainNavigation
                , _searchField
            ;
            //gallery dimensions
            var videoWidth
                , videoHeight
                , totalWidth
                , totalHeight
                , last_totalWidth = 0 // -- so we can compare to the last values
                , last_totalHeight = 0
                , navWidth = 0 // the _navCon width
                , navHeight = 0
                , ww
                , wh
                ,last_height_for_videoheight = 0 // -- last responsive_ratio height known
            ;

            var inter_start_the_transition = null
                ,inter_mouse_move = null
                ,inter_relayout_masonry = null
                ,inter_try_to_init_scroller = null
            ;

            var nav_main_totalsize = 0 // the total size of the thumbs
                , nav_main_consize = 0 // the total size of the container
                , nav_page_size = 0 // the total size of a page of thumbs
                , nav_max_pages = 0 // max number of pages
                , nav_pages_visible = 0 // max number of pages
                , nav_excess_thumbs = 0 // the total size of the last page of thumbs
                , nav_arrow_size = 40
            ;
            var thumbs_thumb_var = 0
                , thumbs_thumb_var_sec = 0
                , thumbs_total_var = 0
                , thumbs_total_var_sec = 0
                , thumbs_css_main = "top"
                ,thumbs_per_page = 0
                ,ultra_responsive_last_layout = 'normal'
            ;

            var nav_thumbs_dir_ver = false
                ,nav_thumbs_dir_hor = false
                ,merge_social_into_one = false
                ,menu_move_locked = false
            ;


            var backgroundY;
            var used = [];
            var content = [];
            var currNr = -1
                , currNr_curr = -1 // current transitioning
                , nextNr = -1
                , prevNr = -1
                , currPage = 0
                , last_arg = 0
            ;
            var currVideo;
            var arr_inlinecontents = [];

            var _rparent
                , _con
                , ccon
                , currScale = 1
                ,initial_h = -1
            ;
            var conw = 0;
            var conh = 0;

            var wpos = 0
                , hpos = 0
                , nav_max_pages = 0
                ,navMain_mousex = 0
                ,navMain_mousey = 0
            ;
            var lastIndex = 99;

            var busy_transition = false
                ,sw_transition_started = false
                ,busy_ajax = false
                ,loaded = false//===gallery loaded sw, when dimensions are set, will take a while if wall
            ;
            var firsttime = true; // firsttime changed item
            var embed_opened = false
                , share_opened = false
                , ad_playing = false
                , search_added = false
                , first_played = false
                , mouse_is_over = false
                , first_transition = false // -- first transition made
            ;


            var i = 0;

            var aux = 0
                , aux1 = 0
            ;



            var viewIndex = 0
                , viewMaxH
                , viewMaxV
                , offsetBuffer = 70;
            ;

            var down_x = 0
                , up_x = 0;


            var menuitem_width = 0
                ,menuitem_height = 0
                ,menuitem_space = 0;

            var menu_position = 'right';

            var settings_separation_nr_pages = 0;
            var ind_ajaxPage = 0;




            var duration_viy = 20
                ,target_viy = 0
                ,target_vix = 0
                ,begin_viy = 0
                ,begin_vix = 0
                ,finish_viy = 0
                ,finish_vix = 0
                ,change_viy = 0
                ,change_vix = 0
            ;

            var init_settings = {};


            var action_playlist_end = null;


            init_settings = $.extend({}, o);
            if(o.nav_type=='outer'){
                if(o.forceVideoHeight==''){
                    o.forceVideoHeight = '300';
                }
            }




            if(o.settings_mode=='slider'){

                o.menu_position = 'none';
                o.nav_type = 'none';
                // o.videoplayersettings.responsive_ratio = 'default';

            }

//            console.info(o.menuitem_width);
            if(isNaN(parseInt(o.menuitem_width, 10))==false && String(o.menuitem_width).indexOf('%')==-1){
                o.menuitem_width = parseInt(o.menuitem_width, 10);
            }
//            console.info(o.menuitem_width);

            o.menuitem_height = parseInt(o.menuitem_height, 10);
            o.settings_go_to_next_after_inactivity = parseInt(o.settings_go_to_next_after_inactivity, 10);
            //o.menuitem_space = ;

            if(isNaN(Number(o.menuitem_space))==false){
                menuitem_space = Number(o.menuitem_space);
                //console.log('ceva');
            }

            if(o.startItem!='default'){
                o.startItem = parseInt(o.startItem, 10);
            }

            // console.info('o.startItem - ',o.startItem);

            o.settings_separation_pages_number = parseInt(o.settings_separation_pages_number, 10);
            o.settings_trigger_resize = parseInt(o.settings_trigger_resize, 10);

            // console.info('o.easing_speed - ',o.easing_speed);
            if(o.easing_speed && !isNaN(parseInt(o.easing_speed,10))){
                duration_viy = parseInt(o.easing_speed,10);
            }

            menu_position = o.menu_position;

            if(!isNaN(parseInt(o.forceVideoWidth,10))){
                o.forceVideoWidth = parseInt(o.forceVideoWidth,10);
            }
            if(!isNaN(parseInt(o.forceVideoHeight,10))){
                o.forceVideoHeight = parseInt(o.forceVideoHeight,10);
            }

            nrChildren = cgallery.children('.vplayer,.vplayer-tobe').length;


            var masonry_options_default = {
                columnWidth: 1
                ,containerStyle: {position: 'relative'}
                ,isFitWidth: false
                ,isAnimated:true
            };

            //console.info(o.masonry_options);

            o.masonry_options = $.extend(masonry_options_default, o.masonry_options);


            if(can_history_api()==false){
                o.settings_enable_linking = 'off';
            }



            if(o.init_on=='init'){

                init();
            }
            if(o.init_on=='scroll'){


                $(window).on('scroll',handle_scroll);
                handle_scroll();
            }

            var _gbuttons = cgallery.children('.gallery-buttons');

            function init(){


                // console.info('init() -');




                ccon = cgallery.parent();
                _rparent = cgallery.parent();




                //console.log($.fn.urlParam(window.location.href, 'dzsvgpage'));


                // ==== separation - PAGES
                var elimi = 0;
                //console.info(o.settings_separation_mode)
                if(o.settings_separation_mode=='pages'){
                    //var dzsvg_page = $.fn.urlParam(window.location.href, 'dzsvgpage');
                    var dzsvg_page = get_query_arg(window.location.href, 'dzsvgpage');
                    //console.info(dzsvg_page, o.settings_separation_pages_number, nrChildren);

                    if(typeof dzsvg_page== "undefined"){
                        dzsvg_page=1;
                    }
                    dzsvg_page = parseInt(dzsvg_page,10);


                    if(dzsvg_page == 0 || isNaN(dzsvg_page)){
                        dzsvg_page = 1;
                    }

                    if(dzsvg_page > 0 && o.settings_separation_pages_number < nrChildren){
                        //console.log(cgallery.children());
                        var aux;
                        if(o.settings_separation_pages_number * dzsvg_page <= nrChildren){
                            for(elimi = o.settings_separation_pages_number * dzsvg_page - 1; elimi >= o.settings_separation_pages_number * (dzsvg_page-1);elimi--){
                                cgallery.children().eq(elimi).addClass('from-pagination-do-not-eliminate');
                            }
                        }else{
                            for(elimi = nrChildren - 1; elimi >= nrChildren - o.settings_separation_pages_number;elimi--){
                                cgallery.children().eq(elimi).addClass('from-pagination-do-not-eliminate');
                            }
                        }

                        cgallery.children().each(function(){
                            var _t = $(this);
                            if(!_t.hasClass('from-pagination-do-not-eliminate')){
                                _t.remove();
                            }
                        })

                        var str_pagination = '<div class="con-dzsvg-pagination">';
                        settings_separation_nr_pages = Math.ceil(nrChildren / o.settings_separation_pages_number);
                        //console.info(settings_separation_nr_pages)
                        nrChildren = cgallery.children().length;

                        for(i=0;i<settings_separation_nr_pages;i++){
                            var str_active = '';
                            if((i+1)==dzsvg_page){
                                str_active = ' active';
                            }
                            str_pagination+='<a class="pagination-number '+str_active+'" href="'+add_query_arg(window.location.href, 'dzsvgpage', (i+1))+'">'+(i+1)+'</a>'
                        }

                        str_pagination+='</div>';
                        cgallery.after(str_pagination);

                    }
                }



                if(is_touch_device()){
                    cgallery.addClass('is-touch');
                }

                cgallery.children().each(function(){
                    var _t = $(this);

                    //console.info(_t);

                    if (_t.attr('data-type')=='youtube' && _t.attr('data-thumb')==undefined){
                        _t.attr('data-thumb', '//img.youtube.com/vi/' + _t.attr('data-src') + '/0.jpg');
                    }

                    if (_t.attr('data-previewimg') == undefined) {
                        if (_t.attr('data-thumb') != undefined){
                            _t.attr('data-previewimg', _t.attr('data-thumb'));
                        }

                        if (_t.attr('data-img') != undefined){
                            _t.attr('data-previewimg', _t.attr('data-img'));
                        }
                    }
                    if (_t.attr('data-src') == undefined) {

                        if (_t.attr('data-source') != undefined){
                            _t.attr('data-src', _t.attr('data-source'));
                        }
                    }
                    if(o.settings_mode=='wall'){

                        if (_t.find('.videoDescription').length == 0) {
                            if (_t.find('.menuDescription').length > 0) {
                                _t.append('<div class="videoDescription">'+_t.find('.menuDescription').html()+'</div>')
                            }
                        }
                    }
                });

                if(o.settings_mode=='wall' || o.settings_mode=='videowall'){
                    o.design_shadow = 'off';
                    o.logo = '';

                }



                //==some sanitizing of the videoWidth and videoHeight parameters





                if(_rparent.hasClass("skin-laptop")) {
                    o.totalWidth = '62%';
                }



                if (o.totalWidth=='' || o.totalWidth == 0) {
                    totalWidth = cgallery.width();
                } else {
                    totalWidth = o.totalWidth;
                    cgallery.css('width', totalWidth);
                }

                if (o.totalHeight=='' || o.totalHeight == 0) {
                    totalHeight = cgallery.height();
                } else {
                    totalHeight = o.totalHeight;
                }


                //console.info(totalWidth);

                //=== some sanitizing
                if(isNaN(totalWidth)){
                    totalWidth = 800;
                }

                if(isNaN(totalHeight)){
                    totalHeight = 400;
                }


                cid = cgallery.attr('id');
                if(typeof cid=='undefined' || cid==''){
                    var auxnr = 0;
                    var temps = 'vgallery'+auxnr;

                    while($('#'+temps).length>0){
                        auxnr++;
                        temps = 'vgallery'+auxnr;
                    }

                    cid = temps;
                    cgallery.attr('id', cid);
                }

                //console.info(totalWidth);


                cgallery.get(0).var_scale = 1;

                backgroundY = o.backgroundY;

                if(is_touch_device()){

                    if(o.nav_type=='scroller'){

                        o.nav_type = 'thumbs';
                    }
                }

                cgallery.addClass('mode-' + o.settings_mode);
                cgallery.addClass('nav-' + o.nav_type);

                var mainClass = '';

                if (typeof(cgallery.attr('class')) == 'string') {
                    mainClass = cgallery.attr('class');
                } else {
                    mainClass = cgallery.get(0).className;
                }
                if (mainClass.indexOf('skin-') == -1) {
                    cgallery.addClass(o.design_skin);
                }

                if (o.videoplayersettings.design_skin == 'sameasgallery') {
                    o.videoplayersettings.design_skin = o.design_skin;
                }







                for (i = 0; i < nrChildren; i++) {

                    // console.info()
                    content[i] = cgallery.children().eq(i);
                    //_sliderCon.append(content[i]);
                    if (o.randomise == 'on'){
                        randomise(0, nrChildren);
                    }else{
                        used[i] = i;
                    }

                }


                if(o.search_field_con){
                    _searchField = o.search_field_con;
                    search_added=true;
                }

                var aux ='<div class="main-navigation"><div class="navMain"><div class="videogallery--navigation-container">';

                if(!search_added && o.search_field=='on'){

                    aux+='<div class="dzsvg-search-field"><input type="text" placeholder="search..."/><svg class="search-icon" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="15px" height="15px" viewBox="230.042 230.042 15 15" enable-background="new 230.042 230.042 15 15" xml:space="preserve"> <g> <path fill="#898383" d="M244.708,243.077l-3.092-3.092c0.746-1.076,1.118-2.275,1.118-3.597c0-0.859-0.167-1.681-0.501-2.465 c-0.333-0.784-0.783-1.46-1.352-2.028s-1.244-1.019-2.027-1.352c-0.785-0.333-1.607-0.5-2.466-0.5s-1.681,0.167-2.465,0.5 s-1.46,0.784-2.028,1.352s-1.019,1.244-1.352,2.028s-0.5,1.606-0.5,2.465s0.167,1.681,0.5,2.465s0.784,1.46,1.352,2.028 s1.244,1.019,2.028,1.352c0.784,0.334,1.606,0.501,2.465,0.501c1.322,0,2.521-0.373,3.597-1.118l3.092,3.083 c0.217,0.229,0.486,0.343,0.811,0.343c0.312,0,0.584-0.114,0.812-0.343c0.228-0.228,0.342-0.499,0.342-0.812 C245.042,243.569,244.931,243.3,244.708,243.077z M239.241,239.241c-0.79,0.79-1.741,1.186-2.853,1.186s-2.062-0.396-2.853-1.186 c-0.79-0.791-1.186-1.741-1.186-2.853s0.396-2.063,1.186-2.853c0.79-0.791,1.741-1.186,2.853-1.186s2.062,0.396,2.853,1.186 s1.186,1.741,1.186,2.853S240.032,238.45,239.241,239.241z"/> </g> </svg> </div>';


                }else{
                }
                aux+='</div></div></div>';
                if( menu_position!='bottom' ){



                    cgallery.append(aux);
                    cgallery.append('<div class="sliderMain"><div class="sliderCon"></div></div>');


                }else{
                    ////=== normal positioning -> video + navigation



                    cgallery.append('<div class="sliderMain"><div class="sliderCon"></div></div>');
                    cgallery.append(aux);

                }
                if(!search_added && o.search_field=='on') {
                    _searchField = cgallery.find('.dzsvg-search-field > input');
                }

                //console.info(cgallery);
                cgallery.append('<div class="gallery-buttons"></div>');
                cgallery.append('<div class="videogallery--adSpace" style="display:none;"></div>');
                if (o.design_shadow == 'on') {
                    cgallery.prepend('<div class="shadow"></div>');
                }

                _sliderMain = cgallery.find('.sliderMain');
                _sliderCon = cgallery.find('.sliderCon');
                _adSpace = cgallery.find('.videogallery--adSpace').eq(0);
                _mainNavigation = cgallery.find('.main-navigation');

                _sliderCon.addClass(o.extra_class_slider_con);

                if(o.settings_mode=='slider'){
                    _sliderMain.after(_mainNavigation);
                }

                //console.info(cgallery, _sliderCon, o.extra_class_slider_con);

                if(o.settings_disableVideo=='on'){
                    _sliderMain.remove();
                    //cgallery.children('.shadow').remove();

                }

                if (is_ie8()) {
                    $('html').addClass('ie8-or-lower');
                    cgallery.addClass('ie8-or-lower');
                    _sliderCon.addClass('sliderCon-ie8');
                }
                if (can_translate()) {
                    $('html').addClass('supports-translate');
                }

                _navMain = cgallery.find('.navMain');
                _navCon = cgallery.find('.videogallery--navigation-container').eq(0);


                if(o.design_navigationUseEasing=='on'){
//                _navCon.addClass('use-easing');
                }


                //console.info(nrChildren, cgallery.children().length);
                if (o.settings_mode == 'normal') {
                    reinit();
                }
                if (o.settings_mode == 'slider') {

                    _navMain.append('<div class="rotator-btn-gotoNext"><svg enable-background="new 0 0 32 32" height="32px" id="Layer_1" version="1.1" viewBox="0 0 32 32" width="32px" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><path d="M24.291,14.276L14.705,4.69c-0.878-0.878-2.317-0.878-3.195,0l-0.8,0.8c-0.878,0.877-0.878,2.316,0,3.194  L18.024,16l-7.315,7.315c-0.878,0.878-0.878,2.317,0,3.194l0.8,0.8c0.878,0.879,2.317,0.879,3.195,0l9.586-9.587  c0.472-0.471,0.682-1.103,0.647-1.723C24.973,15.38,24.763,14.748,24.291,14.276z" fill="#515151"/></svg></div><div class="rotator-btn-gotoPrev"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"                width="32px" height="32px" viewBox="0 0 32 32" enable-background="new 0 0 32 32" xml:space="preserve"><path fill="#515151" d="M7.927,17.729l9.619,9.619c0.881,0.881,2.325,0.881,3.206,0l0.803-0.804c0.881-0.88,0.881-2.323,0-3.204l-7.339-7.342l7.34-7.34c0.881-0.882,0.881-2.325,0-3.205l-0.803-0.803c-0.881-0.882-2.325-0.882-3.206,0l-9.619,9.619                C7.454,14.744,7.243,15.378,7.278,16C7.243,16.621,7.452,17.256,7.927,17.729z"/></svg>                </div>');
                }
                if (o.settings_mode == 'rotator') {
                    _navMain.append('<div class="rotator-btn-gotoNext"></div><div class="rotator-btn-gotoPrev"></div>');
                    _navMain.append('<div class="descriptionsCon"></div>');
                }



                for (i = 0; i < nrChildren; i++) {

                    // console.info('content[used[i]] - ',content[used[i]]);
                    _sliderCon.append(content[used[i]]);
                }


                for (i = 0; i < nrChildren; i++) {
                    var autoplaysw = 'off';
                    if (i == 0 && o.autoplay == 'on'){
                        autoplaysw = 'on';
                    }
                }

//            console.info(menu_position);


                _mainNavigation.addClass('menu-' + menu_position);

                if (o.nav_type == 'thumbsandarrows') {
                    _mainNavigation.append('<div class="thumbs-arrow-left inactive"></div>');
                    _mainNavigation.append('<div class="thumbs-arrow-right"></div>');


                    //_navCon.addClass('static');
                    _mainNavigation.find('.thumbs-arrow-left').bind('click', gotoPrevPage);
                    _mainNavigation.find('.thumbs-arrow-right').bind('click', gotoNextPage);
                }


                if(o.search_field=='on'){
                    //console.info(_searchField);
                    _searchField.bind('keyup', change_search_field);
                }


                //(o.menuitem_width + o.menuitem_space) * nrChildren
                //console.info('ceva', is_ios());
                if (is_ios() || is_android()){
                    // _navMain.css('overflow', 'auto');
                };

                var hpos = 0;


                //console.info(totalWidth, videoWidth);

                if (o.settings_mode == 'rotator3d') {
                    menu_position = 'none';
                    o.menu_position = 'none';

                    _sliderCon.children().each(function() {
                        var _t = $(this);
                        _t.addClass('rotator3d-item');
                        _t.css({'width': videoWidth, 'height': videoHeight})
                        _t.append('<div class="previewImg" style="background-image:url(' + _t.attr('data-previewimg') + ');"></div>');
                        _t.children('.previewImg').bind('click', mod_rotator3d_clickPreviewImg);

                    })
                }
                if (o.settings_mode == 'wall') {

                    //jQuery('body').zoomBox();

                    if (cgallery.parent().hasClass('videogallery-con')) {
                        cgallery.parent().css({'width': 'auto', 'height': 'auto'})
                    }
                    cgallery.css({'width': 'auto', 'height': 'auto'});
                    //return;
                    _sliderCon.children().each(function() {
                        //====each item
                        var _t = $(this);

                        _t.addClass('vgwall-item').addClass('clearfix');


                        var cssargs = {
                            'height': 'auto'
                            , 'position': 'relative'
                            , 'top': 'auto'
                            , 'left': 'auto'
                        };

                        if(o.menuitem_width!='200'){
                            cssargs.width = o.menuitem_width;
                        }


                        _t.css(cssargs);
                        //console.log(totalWidth, totalHeight);
                        _t.attr('data-bigwidth', o.modewall_bigwidth);
                        _t.attr('data-bigheight', o.modewall_bigheight);
                        _t.attr('data-biggallery', cgallery.attr('id'));


                        _t.addClass('zoombox');


                        var desc = _t.find('.menuDescription').html();

                        var thumb = _t.attr('data-thumb');

                        if(desc){

                            if (desc.indexOf('{ytthumb}') > -1) {
                                desc = desc.split("{ytthumb}").join('<div style="background-image:url(//img.youtube.com/vi/' + _t.attr('data-src') + '/0.jpg)" class="imgblock divimage"></div>');
                            }
                            if (desc.indexOf('{ytthumbimg}') > -1) {
                                desc = desc.split("{ytthumbimg}").join('//img.youtube.com/vi/' + _t.attr('data-src') + '/0.jpg');
                            }
                            _t.find('.menuDescription').html(desc);
                        }


                        if(thumb){

                        }else{
                            thumb = _t.find('.imgblock').attr('src');
                            if(thumb){

                            }else{
                                thumb = _t.find('.imgblock').css('background-image');
                            }
                        }

                        // console.info('thumb - ', thumb);

                        if(thumb){

                            thumb = thumb.replace('url(','');
                            thumb = thumb.replace(')','');
                            thumb = thumb.replace(/"/g,'');
                            _t.attr('data-biggallerythumbnail', thumb);
                        }
                        // console.info('thumb - ', thumb);

                        _t.find('.menuDescription .imgblock').after(_t.children('.videoTitle').clone());


                        var str_menu_width = '';

                        if(String(o.menuitem_width).indexOf('%')>-1 || String(o.menuitem_width).indexOf('auto')>-1){
                            str_menu_width = ' width: '+ o.menuitem_width+';';
                        }else{
                            str_menu_width = ' width: '+ o.menuitem_width+'px;';
                        }

                        if (_t.attr('data-videoTitle') != undefined && _t.attr('data-videoTitle') != '') {
                            _t.prepend('<div class="videoTitle">' + _t.attr('data-videoTitle') + '</div>');
                        }
                        if (_t.attr('data-previewimg') != undefined) {
                            var aux2 = _t.attr('data-previewimg');

                            if (aux2 != undefined && aux2.indexOf('{ytthumbimg}') > -1) {
                                //console.log(_t.attr('data-src'));
                                aux2 = aux2.split("{ytthumbimg}").join('//img.youtube.com/vi/' + _t.attr('data-src') + '/0.jpg');
                            }


                            var aux7 = '';


                            if(String(o.menuitem_height)!=''){
                                aux7+='<div class="previewImg divimg" style="background-image:url(' + aux2 + '); width: 100%; ';
                                if(String(o.menuitem_height).indexOf('%')>-1 || String(o.menuitem_width).indexOf('auto')>-1){
                                    aux7+=' height:'+o.menuitem_height+';';
                                }else{
                                    aux7+=' height:'+o.menuitem_height+'px;';
                                }
                                aux7+='"></div>';
                            }else{
                                aux7+='<img class="previewImg" src="' + aux2 + '"';
                                aux7+= '/>';
                            }


                            _t.prepend(aux7);

                        }

                        //console.log(jQuery.fn.masonry);


                        var args = {};
                        if( window.init_zoombox_settings){
                            args = window.init_zoombox_settings;
                        }


                        if($.fn.zoomBox){

                            _t.zoomBox(args);
                        }else{

                            console.warn('zoombox not defined ? ');
                        }
                    });



                    setTimeout(function() {

                        setTimeout(handleResize, 1000);
                        loaded = true;
                    }, 1500);
                }


                if (o.settings_mode == 'videowall') {

                    //jQuery('body').zoomBox();

                    if (cgallery.parent().hasClass('videogallery-con')) {
                        cgallery.parent().css({'width': 'auto', 'height': 'auto'})
                    }
                    cgallery.css({'width': 'auto', 'height': 'auto'});
                    //return;

                }


                if(o.settings_mode=='wall' || o.settings_mode=='videowall'){
                    reinit({
                        'call_from':'init'
                    });
                }

                // --- go to video 0 <<<< the start of the gallery
                cgallery.get(0).videoEnd = handleVideoEnd;
                cgallery.get(0).init_settings = init_settings;
                cgallery.get(0).turnFullscreen = turnFullscreen;
                cgallery.get(0).api_play_currVideo = play_currVideo;
                cgallery.get(0).external_handle_stopCurrVideo = handle_stopCurrVideo;
                cgallery.get(0).api_gotoNext = gotoNext;
                cgallery.get(0).api_gotoPrev = gotoPrev;
                cgallery.get(0).api_gotoItem = gotoItem;
                cgallery.get(0).api_responsive_ratio_resize_h = responsive_ratio_resize_h;
                cgallery.get(0).api_ad_block_navigation = ad_block_navigation;
                cgallery.get(0).api_ad_unblock_navigation = ad_unblock_navigation;


                if (o.logo) {
                    cgallery.append('<img class="the-logo" src="' + o.logo + '"/>');
                    if (o.logoLink != undefined && o.logoLink != '') {
                        cgallery.children('.the-logo').css('cursor', 'pointer');
                        cgallery.children('.the-logo').click(function() {
                            window.open(o.logoLink);
                        });
                    }
                }

                _gbuttons = cgallery.children('.gallery-buttons');




                if(window.dzsvg_settings){
                    // console.info("window.dzsvg_settings -> ",window.dzsvg_settings);
                    if(window.dzsvg_settings.merge_social_into_one=='on'){
                        // console.info("HMM");

                        merge_social_into_one = true;
                    }
                }




                // console.info('merge_social_into_one -> ',merge_social_into_one, o.embedCode, o.shareCode);
                if(merge_social_into_one){


                    if (o.embedCode != '' || o.shareCode != '') {



                        dzsvg_check_multisharer();



                        if (o.settings_mode == 'wall') {

                            if(cgallery.children('.gallery-buttons').length==0){

                                cgallery.prepend('<div class="gallery-buttons"></div>');

                                _gbuttons = cgallery.children('.gallery-buttons');

                            }
                            setTimeout(function(){
                                _sliderMain.before(_gbuttons);

                                // console.info(_sliderMain,'move before')
                            },500);
                        }


                        _gbuttons.append('<div class="embed-button open-in-embed-ultibox"><div class="handle">'+svg_embed+'</div><div class="feed-dzsvg feed-dzsvg--embedcode">' + o.embedCode + '</div></div>');
                        _gbuttons.find('.embed-button .handle').click(dzsvg_click_open_embed_ultibox)



                    }


                }else{

                    if (o.embedCode != '') {
                        _gbuttons.append('<div class="embed-button"><div class="handle">'+svg_embed+'</div><div class="contentbox" style="display:none;"><textarea class="thetext">' + o.embedCode + '</textarea></div></div>');
                        _gbuttons.find('.embed-button .handle').click(click_embedhandle)
                        _gbuttons.find('.embed-button .contentbox').css({
                            'right': 50
                        })
                    }
                    if (o.shareCode != '') {
                        _gbuttons.append('<div class="share-button"><div class="handle"><svg width="32" height="33.762001037597656" viewBox="0 0 32 33.762001037597656" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" fill="#000000"><g><path d="M 22,6c0-3.212-2.788-6-6-6S 10,2.788, 10,6c0,3.212, 2.788,6, 6,6S 22,9.212, 22,6zM 16,14c-5.256,0-10,5.67-10,12.716s 20,7.046, 20,0S 21.256,14, 16,14z"></path></g></svg></div><div class="contentbox" style="display:none;"><div class="thetext">' + o.shareCode + '</div></div></div>');
                        _gbuttons.find('.share-button .handle').click(click_sharehandle)
                        _gbuttons.find('.share-button .contentbox').css({
                            'right': 50
                        })
                    }
                }




                if(o.nav_type=='outer'){
                    _navCon.addClass(o.nav_type_outer_grid);


                    _navCon.children().addClass('dzs-layout-item');


                    if(o.menuitem_width){
                        o.menuitem_width ='';
                    }



                    if(o.nav_type_outer_max_height){
                        var nto_mh = Number(o.nav_type_outer_max_height);


                        _navMain.css('max-height', nto_mh+'px');
                        _navMain.addClass('scroller-con skin_apple inner-relative');
                        _navCon.addClass('inner');

                        _navMain.css({
                            'height' : 'auto'
                        })

                        try_to_init_scroller();
                    }
                }

                calculateDims({



                    'call_from':'init'
                });



                if (o.nav_type == 'scroller') {
                    _navMain.addClass('scroller-con skin_apple');
                    _navCon.addClass('inner');

                    if ((menu_position == 'right' || menu_position == 'left') && nrChildren > 1) {
                        //console.log((o.menuitem_height + o.menuitem_space) * nrChildren);
                        _navCon.css({
                            // 'width' : menuitem_width
                        })
                    }
                    if ((menu_position == 'bottom' || menu_position == 'top') && nrChildren > 1) {
                        _navCon.css({
                            'height' : (menuitem_height)
                        })
                    }

                    _navMain.css({
                        'height' : '100%'
                    })




                    _navMain.scroller({
                        'enable_easing':'on'
                    });

                    setTimeout(function(){

                        // console.warn('$(\'html\').eq(0) - ',$('html').eq(0));
                        if($('html').eq(0).attr('dir')=='rtl'){
                            _navMain.get(0).fn_scrollx_to(1);
                        }
                    },100);
                }


                //====== NO FUNCTION HIER



                //console.log('hier');

                cgallery.on('click','.rotator-btn-gotoNext,.rotator-btn-gotoPrev', handle_mouse);
                $(document).on('keyup.dzsvgg',handleKeyPress);



                window.addEventListener("orientationchange",handle_orientationchange);
                $(window).on('resize', handleResize);
                handleResize();

                setTimeout(function(){
                    calculateDims({

                        'call_from':'first_timeout'
                    })
                }, 3000);
                setTimeout(init_start,100);


                if(o.settings_trigger_resize>0){
                    setInterval(function(){

                        // console.info("HMM");
                        calculateDims({
                            'call_from':'recheck_sizes'
                        });
                    },o.settings_trigger_resize);
                };



                if(o.startItem=='default'){
                    o.startItem=0;
                    if(o.playorder=='reverse'){
                        o.startItem = nrChildren-1;
                    }
                }

                // --- gotoItem
                if (o.settings_mode != 'wall' && o.settings_mode != 'videowall') {

                    loaded = true;

                    //console.info(o.startItem, get_query_arg(window.location.href,'dzsvg_startitem_'+cid));


                    // console.warn('o.startItem - ',o.startItem);

                    if( get_query_arg(window.location.href,'dzsvg_startitem_'+cid )) {
                        o.startItem = Number(get_query_arg(window.location.href, 'dzsvg_startitem_'+cid));
                    }
                    if(get_query_arg(window.location.href,'the-video') && $('.videogallery').length==1){
                        o.startItem = Number(get_query_arg(window.location.href, 'the-video'));
                    }
                    if(get_query_arg(window.location.href,'the-video-'+cid)){
                        o.startItem = Number(get_query_arg(window.location.href, 'the-video-'+cid));


                        if(cgallery.parent().parent().parent().hasClass('categories-videogallery')){


                            var _cach = cgallery.parent().parent().parent();

                            //console.error('_cach - ',_cach);
                            var ind = _cach.find('.videogallery').index(cgallery);


                            //console.error('_cach - ',_cach, ind);

                            if(ind){
                                setTimeout(function(){
                                    _cach.get(0).api_goto_category(ind,{
                                        'call_from':'deeplink'
                                    });
                                },100);
                            }
                        }
                    }

                    if(isNaN(o.startItem)){
                        o.startItem = 0;
                    }
                    // console.info('o.startItem - ',o.startItem);
                    //console.log(_navMain, _sliderCon.children().eq(o.startItem).attr('data-type'));
                    // console.info('startItem - ',o.startItem);
                    if(_sliderCon.children().eq(o.startItem).attr('data-type')=='link'){
                        gotoItem(o.startItem, {donotopenlink: "on", 'call_from': 'init'});

                    }else{
                        gotoItem(o.startItem, {'call_from': 'init'});

                        // console.info('o.startItem - ',o.startItem);


                    }
                    if(o.nav_type=='scroller'){
                        animate_to_curr_thumb();
                    }


                    if(o.settings_go_to_next_after_inactivity){
                        setInterval(function(){
                            if(first_played==false){

                                gotoNext();
                            }
                        },o.settings_go_to_next_after_inactivity*1000);
                    }
                }

                if(o.settings_separation_mode=='scroll'){
                    $(window).bind('scroll', handle_scroll);
                }
                if(o.settings_separation_mode=='button'){
                    cgallery.append('<div class="btn_ajax_loadmore">Load More</div>');
                    cgallery.children('.btn_ajax_loadmore').bind('click', click_btn_ajax_loadmore);
                }


                // console.warn(cgallery);
                cgallery.get(0).api_handleResize = handleResize;
                cgallery.get(0).api_gotoItem = gotoItem;
                cgallery.get(0).api_handleResize_currVideo = handleResize_currVideo;
                cgallery.get(0).api_play_currVideo = play_currVideo;
                cgallery.get(0).api_pause_currVideo = pause_currVideo;
                cgallery.get(0).api_currVideo_refresh_fsbutton = api_currVideo_refresh_fsbutton;
                cgallery.get(0).api_video_ready = the_transition;
                cgallery.get(0).api_set_action_playlist_end = function(arg){
                    action_playlist_end = arg;
                };

                cgallery.get(0).api_setup_ad = setup_ad;
                cgallery.get(0).api_played_video = function(){
                    first_played = true;
                    //console.info('first_played - ',first_played);
                };




                cgallery.on('mouseleave',handleMouseout);
                cgallery.on('mouseover', handleMouseover);




                if(o.nav_type_auto_scroll=='on'){
                    if(o.nav_type=='thumbs'){


                        if(menu_position=='right' || menu_position=='left'){

                            nav_thumbs_dir_ver = true;
                            nav_thumbs_dir_hor = false;
                        }


                        if(menu_position=='top' || menu_position=='bottom'){

                            nav_thumbs_dir_ver = false;
                            nav_thumbs_dir_hor = true;
                        }



                        setTimeout(function() {



                            animate_to_curr_thumb();

                        },20);
                    }
                }


            }



            function mouse_is_over_func(){

                mouse_is_over = true;
                // cgallery.removeClass('mouse-is-out');
                // cgallery.addClass('mouse-is-over');
            }
            function handleMouseover(e) {

                // console.info('mouseover', e.currentTarget);

                mouse_is_over_func();


            }


            function animate_to_curr_thumb(pargs){




                var margs = {
                    caller: null
                    ,'call_from':'default'
                }

                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                if(is_touch_device()){
                    // return false;
                }


                // return false;
                // console.info('animate_to_curr_thumb', margs);

                if(o.nav_type=='thumbs'){

                    var rat = ( _navCon.find('.navigationThumb.active').offset().top - _navCon.offset().top ) /   (_navCon.outerHeight() - _navMain.parent().outerHeight());



                    // console.info('_navCon.find(\'.navigationThumb.active\').offset().top - ',_navCon.find('.navigationThumb.active').offset().top);
                    // console.info('_navCon.offset().top - ',_navCon.offset().top);
                    // console.info('_navCon.outerHeight() - ',_navCon.outerHeight(), _navCon);
                    // console.info('_navMain.outerHeight() - ',_navMain.outerHeight(), _navMain);

                    // console.info('thumbs, rat -> ',rat);
                    // console.info('(_navCon.outerHeight() - _navMain.parent().outerHeight()) -> ',(_navCon.outerHeight() - _navMain.parent().outerHeight()));

                    if(cgallery.hasClass('ultra-responsive')==true || ( menu_position=='left'||menu_position=='right' )){

                        animate_menu_y(rat * (_navCon.outerHeight() - _navMain.parent().outerHeight()) ,{
                            'call_from':'animate_to_curr_thumb'
                        });
                    }else{


                        rat = ( _navCon.find('.navigationThumb.active').offset().left - _navCon.offset().left ) /  (_navCon.outerWidth() - _navMain.outerWidth());




                        // console.info('thumbs, rat -> ',rat);
                        // console.info('_navCon.find(\'.navigationThumb.active\').offset().left -> ',_navCon.find('.navigationThumb.active').offset().left);
                        // console.info('_navCon.offset().left -> ',_navCon.offset().left);
                        // console.info('_navCon.outerWidth() -> ',_navCon.outerWidth());
                        // console.info('( _navCon.find(\'.navigationThumb.active\').offset().left - _navCon.offset().left ) -> ',( _navCon.find('.navigationThumb.active').offset().left - _navCon.offset().left ));
                        // console.info('rat -> ',rat);
                        pre_animate_menu_x(rat * _navMain.outerWidth() );
                    }
                }
                if(o.nav_type=='scroller'){

                    var aux = 0;

                    if(_navCon.find('.navigationThumb.active').length){

                        aux = _navCon.find('.navigationThumb.active').offset().top - _navCon.eq(0).offset().top;

                        // console.info('_navCon.children().eq(o.startItem) - ',_navCon.children().eq(o.startItem));

                        // console.info('scroller - ', 'aux - ',aux);

                        setTimeout(function(){

                            if(typeof _navMain.get(0).api_scrolly_to != 'undefined'){
                                // console.info('focescroll to', aux);
                                _navMain.get(0).api_scrolly_to(aux);
                            }
                        },300);
                    }

                }
            }

            function handleMouseout(e) {

                mouse_is_over = false;

                if(o.nav_type_auto_scroll=='on'){
                    // console.info("o.nav_type - ",o.nav_type);

                    if(o.nav_type=='thumbs' || o.nav_type=='scroller'){
                        setTimeout(function(){
                            if(mouse_is_over==false){


                                animate_to_curr_thumb({
                                    'call_from':'from_mouseout'
                                });

                            }else{
                                handleMouseout();
                            }
                        },2000);
                    }
                }

            }

            function handleKeyPress(e){
                var _t = $(this);


                // console.info('keypress - ',e.type, e.keyCode,e);
                if(e.type=='keyup'){
                    if(e.keyCode==27){
                        cgallery.find('.is_fullscreen').removeClass('is_fullscreen is-fullscreen');
                        setTimeout(function(){
                            // console.info("ESC KEY");
                            calculateDims();
                        },100);
                    }
                }


            }

            function try_to_init_scroller(){


                // console.warn("EE")
                if(window.dzsscr_init){

                    // console.warn("EE");


                    window.dzsscr_init(_navMain, {

                        'enable_easing':'on'
                        ,'settings_skin':'skin_apple'
                    });



                }else{




                    var baseUrl = '';
                    var baseUrl_arr = get_base_url_arr();
                    for(var i24=0;i24<baseUrl_arr.length-2; i24++){
                        baseUrl+=baseUrl_arr[i24]+'/';
                    }
                    //var src = scripts[scripts.length-1].src;


                    console.warn(baseUrl);


                    var url = baseUrl+'dzsscroller/scroller.js';
                    //console.warn(scripts[i23], baseUrl, url);

                    $('head').append('<link rel="stylesheet" type="text/css" href="'+baseUrl+'dzsscroller/scroller.css">');
                    $.ajax({
                        url: url,
                        dataType: "script",
                        success: function(arg){


                            try_to_init_scroller();



                        }
                    });
                }
            }

            function init_scroller(){

            }


            function ad_block_navigation(){

                // console.info('blocked');
                cgallery.addClass('ad-blocked-navigation');

            }
            function ad_unblock_navigation(){

                cgallery.removeClass('ad-blocked-navigation');
            }

            function init_start(){

                // console.info('init_start() - ');

                if(o.settings_mode=='wall'){

                    setTimeout(init_showit,1500);

                }else{

                    init_showit();
                }

                if(o.nav_type=='thumbs' && o.design_navigationUseEasing=='on'){
                    handle_frame();
                }


                if(o.settings_secondCon){
                    var xpos = 0;
                    $(o.settings_secondCon).find('.item').each(function(){
                        var _t = $(this);
                        _t.css('left', xpos+'%');
                        xpos+=100;
                    })
                }

                //console.info(o.settings_outerNav);
                if(o.settings_outerNav){

                    var xpos = 0;
                    o.settings_outerNav.find('.videogallery--navigation-outer--bigblock').each(function(){
                        var _t = $(this);
                        _t.css('left', xpos+'%');
                        xpos+=100;
                    })
                }

                setInterval(tick, 1000);

                handleResize();

                cgallery.addClass('inited');

                //setTimeout(handleResize,1000);
            }

            function handle_mouse(e){

                var _t = $(this);
                if(_t.hasClass('rotator-btn-gotoNext')){

                    gotoNext();
                }
                if(_t.hasClass('rotator-btn-gotoPrev')){

                    gotoPrev();
                }
            }

            function init_showit(){

                // console.error('init_showit()');



// console.info('check - ',cgallery.parent().parent().parent());



                var _vc_tta_panel = null;


                if(cgallery.parent().parent().parent().hasClass("vc_tta-panel-body")){
                    _vc_tta_panel = cgallery.parent().parent().parent();
                }
                if(cgallery.parent().parent().parent().parent().parent().hasClass("vc_tta-panel-body")){
                    _vc_tta_panel = cgallery.parent().parent().parent().parent().parent();
                }


                if(_vc_tta_panel){
                    // console.info("yes, it's tabs",_vc_tta_panel.parent().parent().parent().parent());


                    var _c = _vc_tta_panel.parent().parent().parent().parent();
                    _c.find('.vc_tta-tab').data('parent-tabs',_c);
                    _c.find('.vc_tta-tab').on('click',function(){
                        var _t2 = $(this);

                        // console.info();

                        if(_t2.data('parent-tabs')){
                            var _con2 = _t2.data('parent-tabs');

                            _con2.find('.videogallery').each(function(){
                                var _t3 = $(this);

                                if(_t3.get(0) && _t3.get(0).api_handleResize_currVideo){
                                    setTimeout(function(arg){
                                        arg.get(0).api_handleResize();
                                        arg.get(0).api_handleResize_currVideo();
                                    },10,_t3);
                                    setTimeout(function(arg){
                                        arg.get(0).api_pause_currVideo();
                                    },100,_t3);
                                }
                            })
                        }
                    })
                }

                cgallery.parent().children('.preloader').fadeOut('fast');
                cgallery.parent().children('.css-preloader').fadeOut('fast');


                if(o.init_on=='scroll'&& cgallery.hasClass('transition-slidein')){
                    setTimeout(function(){

                        cgallery.addClass('dzsvg-loaded');
                    },300);
                }else{

                    cgallery.addClass('dzsvg-loaded');
                }
            }
            function reinit(pargs){





                var margs = {
                    caller: null
                    ,'call_from':'default'
                }

                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                // console.log('reinit()', cgallery.children('.vplayer-tobe').length);

                var len = cgallery.children('.vplayer-tobe').length;


                if (o.settings_mode == 'normal') {





                    for (i = 0; i < nrChildren; i++) {
                        var _c = cgallery.children('.vplayer-tobe').eq(used[i]);
                        var desc = ' no description';

                        if(_c.find('.menuDescription').length){
                            desc = _c.find('.menuDescription').html();
                        }

                        //console.info(desc, used[i], cgallery.children().eq(used[i]));
                        if( !(_c.attr('data-videoTitle')) ){
                            if(_c.find('.menuDescription').find('.the-title').html()){

                                if(o.disable_videoTitle!='on'){

                                    _c.attr('data-videoTitle',_c.find('.menuDescription').find('.the-title').html());
                                }

                            }
                        }
                        _c.find('.menuDescription').remove();
                        if (desc == null) {



                            if(o.menu_description_format){
                                //console.info(o.menu_description_format, _c.find('.feed-menu-number').html());

                                var aux = o.menu_description_format;


                                aux = aux.replace('{{number}}', '<div class="menu-number"><span class="the-number">'+_c.find('.feed-menu-number').html()+'</span></div>');
                                aux = aux.replace('{{menuimage}}', '<div class="divimage imgblock" style="background-image: url('+_c.find('.feed-menu-image').html()+')"></div>');
                                aux = aux.replace('{{menutitle}}', '<div class="menu-title" style="">'+_c.find('.feed-menu-title').html()+'</div>');
                                aux = aux.replace('{{menudesc}}', '<div class="menu-desc" style="">'+_c.find('.feed-menu-desc').html()+'</div>');
                                aux = aux.replace('{{menutime}}', '<div class="menu-time" style="">'+_c.find('.feed-menu-time').html()+'</div>');
                                desc = aux;
                            }else{

                                continue;
                            }

                        }

                        var _c2 = _c;


                        //console.info(_c2, o.videoplayersettings.responsive_ratio);
                        if(_c2.attr('data-src')){
                            if(String(_c2.attr('data-src')).indexOf('youtube.com/watch')){

                                var dataSrc = _c2.attr('data-src');
                                var auxa = String(dataSrc).split('youtube.com/watch?v=');
//                            console.info(auxa);
                                if(auxa[1]){

                                    dataSrc = auxa[1];
                                    if(auxa[1].indexOf('&')>-1){
                                        var auxb = String(auxa[1]).split('&');
                                        dataSrc = auxb[0];
                                    }

                                    _c2.attr('data-src', dataSrc);
                                }
                            }

                            // console.info("HIER");
                            _c2.attr('data-src', String(_c2.attr('data-src')).replace('https://youtu.be/',''));
                        }
                        if (desc.indexOf('{ytthumb}') > -1) {
                            desc = desc.split("{ytthumb}").join('<div style="background-image:url(//img.youtube.com/vi/' + _c2.attr('data-src') + '/0.jpg)" class="imgblock divimage"></div>');
                        }
                        if (desc.indexOf('{ytthumbimg}') > -1) {
                            desc = desc.split("{ytthumbimg}").join('//img.youtube.com/vi/' + _c2.attr('data-src') + '/0.jpg');
                        }



                        var aux = '';

                        //console.info(cgallery.children('.vplayer-tobe').eq(i));


                        var _ci = cgallery.children('.vplayer-tobe').eq(i);


                        //console.info(_ci.attr('data-type'));


                        // -- this is inside video gallery
                        if( (_ci.attr('data-type')=='youtube' || _ci.attr('data-type')=='vimeo'|| _ci.attr('data-type')=='inline') && o.videoplayersettings.responsive_ratio=='detect' && !(_ci.attr('data-responsive_ratio'))){


                            _ci.attr('data-responsive_ratio', '0.5625');


                            _ci.attr('data-responsive_ratio-not-known-for-sure', 'on');  // -- we set this until we know the responsive ratio for sure , 0.562 is just 16/9 ratio so should fit to most videos


                            if(o.php_media_data_retriever){

                                //console.info(o.php_media_data_retriever);
                                setTimeout(function(arg){

                                    var _cach = _sliderCon.children().eq(arg);
                                    //console.info(arg, _cach);

                                    var src = _cach.attr('data-src');

                                    $.get( o.php_media_data_retriever+"?action=dzsvg_action_get_responsive_ratio&type="+_cach.attr('data-type')+"&source="+src, function( data ){

                                        //console.info(data);

                                        try{
                                            var json = JSON.parse(data);

                                            //console.info(json);


                                            var rr = 0;

                                            rr = json.height / json.width;

                                            //console.info(rr,rr.toFixed(3));

                                            if(rr.toFixed(3)!='0.563'){
                                                _cach.attr('data-responsive_ratio',rr.toFixed(3));
                                            }
                                            _cach.attr('data-responsive_ratio-not-known-for-sure', 'off');


                                            if(_cach.get(0) && _cach.get(0).api_get_responsive_ratio){
                                                _cach.get(0).api_get_responsive_ratio({
                                                    'reset_responsive_ratio':true
                                                    ,'call_from':'php_media_data_retriever'
                                                })

                                                setTimeout(function(){
                                                    handleResize_currVideo();
                                                },100);
                                            }
                                        }catch(err){
                                            console.info('json parse error - ',data);
                                        }





                                        //_cach.attr('data-responsive_ratio')
                                    });
                                },100,i);
                            }

                        }

                        if(_ci.attr('data-type')=='link'){


                            aux+='<a class="navigationThumb"';

                            if(cgallery.children('.vplayer-tobe').eq(i).attr('data-source')){
                                aux+=' href="'+_ci.attr('data-source')+'"';
                            }
                            if(cgallery.children('.vplayer-tobe').eq(i).attr('data-target')){
                                aux+=' target="'+_ci.attr('data-target')+'"';
                            }

                            aux+='>';
                        }else{
                            aux+='<div class="navigationThumb">';
                        }


                        aux+='<div class="navigationThumb-content">';
                        if(o.settings_menu_overlay=='on'){
                            aux+='<div class="menuitem-overlay"></div>';
                        }

                        // console.warn(desc);

                        if(cgallery.hasClass('skin-boxy')){
                            desc = desc.replace(/\<img src=\"(.+?)".*?\/{0,1}>/g, '<div class="big-thumb" style=\'background-image:url("$1");\'></div>');
                        }
                        // console.info(desc);
                        aux+=desc + '</div>';




                        if(_ci.attr('data-type')=='link'){

                            aux+='</a>';
                        }else{

                            aux+='</div>';
                        }
                        _navCon.append(aux);
                        _navCon.children().last().find('.imgblock.divimage').addClass('big-thumb');
                    }


                    if(o.nav_type=='none'){
                        _mainNavigation.hide();
                    }



                    /*
                     var initnavConlen = _navCon.children().length;
                     wpos = 0;
                     hpos =0 ;
                     for (i = 0; i < len; i++) {
                     var _c = cgallery.children('.vplayer-tobe').eq(i);



                     var desc = _c.find('.menuDescription').html();


                     _c.find('.menuDescription').remove();
                     if (desc == null) {


                     if(o.menu_description_format){
                     console.info('ceva');
                     }else{

                     continue;
                     }


                     }else{

                     }
                     if (desc.indexOf('{ytthumb}') > -1) {
                     desc = desc.split("{ytthumb}").join('<img src="//img.youtube.com/vi/' + cgallery.children().eq(used[i]).attr('data-src') + '/0.jpg" class="imgblock"/>');
                     }
                     if (desc.indexOf('{ytthumbimg}') > -1) {
                     desc = desc.split("{ytthumbimg}").join('//img.youtube.com/vi/' + cgallery.children().eq(used[i]).attr('data-src') + '/0.jpg');
                     }


                     _navCon.append('<div><div class="navigationThumb-content">' + desc + '</div></div>')
                     _navCon.children().eq(initnavConlen + i).addClass("navigationThumb");
                     _navCon.children().eq(initnavConlen + i).css({
                     'width': o.menuitem_width,
                     'height': o.menuitem_height
                     });

                     _navCon.children('.navigationThumb').eq(initnavConlen + i).click(handleButton);




                     if (o.nav_type == 'scroller') {

                     }

                     hpos += o.menuitem_height + menuitem_space;
                     wpos += o.menuitem_width + menuitem_space;
                     }

                     */

                }

                for (i = 0; i < len; i++) {
                    var _t = cgallery.children('.vplayer-tobe').eq(0);
                    //console.log(_t)
                    _sliderCon.append(_t);
                }




                if (o.settings_mode == 'videowall') {

                    _sliderCon.children().each(function() {
                        //====each item
                        var _t = $(this);

                        _t.wrap('<div class="dzs-layout-item"></div>');

                        // console.info(o.videoplayersettings);

                        o.videoplayersettings.responsive_ratio = 'detect';
                        o.videoplayersettings.autoplay = 'off';
                        o.videoplayersettings.preload_method = 'metadata';

                        _t.vPlayer(o.videoplayersettings);



                        // _t.zoomBox();
                    });
                }
                if (o.settings_mode == 'wall') {

                    _sliderCon.children().each(function() {
                        //====each item
                        var _t = $(this);



                        if (_t.find('.videoDescription').length == 0) {
                            if (_t.find('.menuDescription').length > 0) {
                                _t.append('<div class="videoDescription">'+_t.find('.menuDescription').html()+'</div>')
                            }
                        }


                        _t.addClass('vgwall-item').addClass('clearfix dzs-layout-item');
                        _t.css({

                        });
                        //console.log(totalWidth, totalHeight);
                        _t.attr('data-bigwidth', o.modewall_bigwidth);
                        _t.attr('data-bigheight', o.modewall_bigheight);

                        if (_t.attr('data-videoTitle') != undefined && _t.attr('data-videoTitle') != '') {
                            _t.prependOnce('<div class="videoTitle">' + _t.attr('data-videoTitle') + '</div>', '.videoTitle');
                        }
                        if (_t.attr('data-previewimg') != undefined) {
                            var aux2 = _t.attr('data-previewimg');

                            if (aux2 != undefined && aux2.indexOf('{ytthumbimg}') > -1) {
                                //console.log(_t.attr('data-src'));
                                aux2 = aux2.split("{ytthumbimg}").join('//img.youtube.com/vi/' + _t.attr('data-src') + '/0.jpg');
                            }

                            _t.prependOnce('<img class="previewImg" style="" src="' + aux2 + '"/>', '.previewImg');

                        }



                        _t.zoomBox();
                    });
                }



                //console.info('o.nav_type - ',o.nav_type);
                if(o.nav_type=='outer'){
                    _navCon.children().addClass('dzs-layout-item');
                }


                nrChildren = _sliderCon.children().length;

                if(nrChildren==1){
                    //console.info("one child");
                }
            }

            function gotoNextPage() {
                var tempPage = currPage;

                tempPage++;
                gotoPage(tempPage);

            }

            function gotoPrevPage() {
                if (currPage == 0)
                    return;

                currPage--;
                gotoPage(currPage);

            }

            function change_search_field(){
                var _t = $(this);

                // console.info(_t.val());

                if(o.settings_mode=='wall'){
                    _sliderCon.children().each(function() {
                        var _t2 = $(this);


                        if(_t.val() == '' || String(String(_t2.find('.menuDescription').eq(0).html()).toLowerCase()).indexOf(_t.val().toLowerCase())>-1){

                            _t2.show();
                        }else{

                            _t2.hide();
                        }


                    });
                }


                if(o.nav_type=='scroller'){


                    // console.info(_navMain.get(0), _navMain.get(0).api_scrolly_to)
                    if(typeof _navMain.get(0).api_scrolly_to != 'undefined'){
                        // console.info('focescroll to', aux);
                        _navMain.get(0).api_scrolly_to(0);
                    }

                    setTimeout(function(){

                        // console.info(_navCon);
                        _navCon.css('top','0')
                    },100)
                }
                _navCon.children().each(function(){
                    var _t2 = $(this);

                    // console.warn(_t2);

                    //console.info(_t2.find('.navigationThumb-content').eq(0).html(),_t.val());
                    if(_t.val() == '' || String(String(_t2.find('.navigationThumb-content').eq(0).html()).toLowerCase()).indexOf(_t.val().toLowerCase())>-1){

                        _t2.show();
                    }else{

                        if(_t2.hasClass('dzsvg-search-field')==false){

                            _t2.hide();
                        }
                    }
                });

                handleResize();
            }

            function responsive_ratio_resize_h(arg, pargs){

                // -- gallery
//                return false;
//                videoHeight = arg;


                var margs = {
                    caller: null
                    ,'call_from':'default'
                }

                if(pargs){
                    margs = $.extend(margs,pargs);
                }

                console.info('responsive_ratio_resize_h - ', arg, cgallery,  margs,cgallery.parent().hasClass('skin-laptop'));
                if(margs.caller==null || cgallery.parent().hasClass('skin-laptop')){
                    return false;
                }


                if(initial_h===-1){
                    initial_h=_sliderMain.height();
                }else{
//                    console.info(initial_h);
                }



                // return false;

//                console.info(menu_position);

                //console.info(arg);
                // console.info('menu_position - ',menu_position)
                if(cgallery.hasClass('ultra-responsive')==false && (menu_position=='left'||menu_position=='right'||menu_position=='none')){
                    totalHeight = arg;
                    cgallery.height(arg);

                    if(o.settings_mode!='slider'){

                        _mainNavigation.height(arg);
                    }


                    if(menu_position=='none' || menu_position=='right' || menu_position=='left'){
                        currVideo.height(arg);
                    }

                    // console.info("LETS ASSIGN VIDEOHEIGHT",arg);
                    videoHeight = arg;
//                    console.info(arg, cgallery.height());
                }else{
                    cgallery.css('height', 'auto');
//                    console.info(arg, cgallery.height());

                    // totalHeight = arg;
                    videoHeight = arg;
                    currVideo.height(arg);

                }


                if(margs.caller){
                    margs.caller.data('height_for_videoheight',arg);
                    calculateDims({
                        call_from: 'height_for_videoheight'
                    });
                }

                if(o.nav_type=='scroller'){
                    setTimeout(function(){


                        if(_navMain.get(0) && _navMain.get(0).api_toggle_resize){
                            _navMain.get(0).api_toggle_resize();
                        }
                    },100)
                }


                //console.info(initial_h);

                // console.info('adjusting _sliderMain height',arg, videoHeight);
                // _sliderMain.height(arg);
//                console.info(arg, cgallery.height());
            }
            function gotoPage(arg) {
                if (arg > nav_max_pages || o.nav_type != 'thumbsandarrows'){
                    return;
                }


                console.info("gotoPage ..",arg, ' ... nav type ', o.nav_type, '... nav_max_pages', nav_max_pages);


                var thumbsSlider = _navCon;

                _mainNavigation.find('.thumbs-arrow-left').removeClass('inactive');
                _mainNavigation.find('.thumbs-arrow-right').removeClass('inactive');
                if (arg == 0) {
                    _mainNavigation.find('.thumbs-arrow-left').addClass('inactive');
                }
                if (arg == nav_max_pages ) {
                    _mainNavigation.find('.thumbs-arrow-right').addClass('inactive');
                }

                if (arg == nav_max_pages ) {

                    if (menu_position == "right" || menu_position == "left"){


                        thumbsSlider.animate({
                            'top': thumbs_thumb_var * -arg
                            ,'left': 0
                        }, {
                            duration: 400,
                            queue: false
                        });
                    }

                    if (menu_position == "bottom" || menu_position == "top"){
                        thumbsSlider.animate({
                            'left': thumbs_thumb_var * -arg
                            ,'top': 0
                        }, {
                            duration: 400,
                            queue: false
                        });
                    }

                } else {


                    if (menu_position == "right" || menu_position == "left"){
                        thumbsSlider.animate({
                            'top': thumbs_thumb_var * -arg
                            ,'left': 0
                        }, {
                            duration: 400,
                            queue: false
                        });

                        console.info('nav_page_size * -arg', nav_page_size * -arg, menuitem_height);
                    }

                    if (menu_position == "bottom" || menu_position == "top"){
                        thumbsSlider.animate({
                            'left': thumbs_thumb_var * -arg
                            ,'top': 0
                        }, {
                            duration: 400,
                            queue: false
                        });
                    }

                }

                currPage = arg;
            }

            function tick(){


            }
            function calculateDims(pargs){

                // -- gallery



                var margs = {
                    'call_from':'default'
                };
                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                // console.info(margs);


                totalWidth = cgallery.outerWidth();
                totalHeight = cgallery.height();

                if(cgallery.height()==0){
                    if(o.forceVideoHeight){

                        if(menu_position=='top' || menu_position=='bottom'){

                            totalHeight = o.forceVideoHeight + o.design_menuitem_height;
                        }else{

                            totalHeight = o.forceVideoHeight;
                        }
                    }
                }

                // console.log('vgallery calculateDims()', margs, totalWidth, totalHeight);

                // console.info(margs);
                if(margs.call_from=='recheck_sizes'){

                    // console.info(Math.abs(last_totalWidth-totalWidth), Math.abs(last_totalHeight-totalHeight));
                    if(Math.abs(last_totalWidth-totalWidth)<4 && Math.abs(last_totalHeight-totalHeight)<4){


                        // console.info("SAME SIZES");
                        return false;
                    }

                }

                // console.info('passed test');

                last_totalWidth = totalWidth;
                last_totalHeight = totalHeight;


                if(totalWidth<721){
                    cgallery.addClass('under-720');



                }else{
                    cgallery.removeClass('under-720');
                }


                if(totalWidth<601){
                    cgallery.addClass('under-600');
                }else{
                    cgallery.removeClass('under-600');
                }


                if( String(cgallery.get(0).style.height).indexOf('%')>-1){

                    totalHeight = cgallery.height();
                }else{

                    if(cgallery.data('init-height')){

                        totalHeight = cgallery.data('init-height');
                    }else{

                        totalHeight = cgallery.height();
                        //cgallery.data('init-height',totalHeight);

                        setTimeout(function(){
                            //console.log(cgallery.height(), cgallery.outerHeight());
                        })

                        //console.info(cgallery, totalHeight);
                    }
                }

                // console.log( 'checking total height',cgallery.outerHeight(), totalHeight, cgallery);

                if(o.totalHeightDifferenceOnParent!=''){
                    //console.info('ceva');
                    var aux = parseFloat(o.totalHeightDifferenceOnParent);
                    //console.log(aux);
                    var aux2 = 1 + aux;
                    //console.log(aux2);

                    totalHeight = aux2 * _rparent.outerHeight(false);
                    //console.info(totalHeight);
                }

//
//                if(cgallery.attr('id')=='vg1'){
//                }


                //return;

                // console.warn('totalHeight is', totalHeight);
//                console.info(cgallery.height(), totalHeight);

                videoWidth = totalWidth;
                videoHeight = totalHeight;


                //console.info('videoHeight -- ',videoHeight);

//                console.info(videoHeight);

                menuitem_width = o.menuitem_width;
                menuitem_height = o.menuitem_height;

                if(isNaN(menuitem_height)){ menuitem_height = 0; }

//                console.info(videoHeight);


                // -- ultra-responsive
                if (o.settings_disableVideo!='on'&& (o.menu_position == 'right' || o.menu_position == 'left') && nrChildren > 1) {
//                    console.info(menuitem_width, menuitem_space, totalWidth/2);
                    if(menuitem_width + menuitem_space > totalWidth/2){
                        ultra_responsive_last_layout = 'movedup';
                        //menu_position='top'

                        cgallery.addClass('ultra-responsive');

                        //console.warn('videoHeight - ',videoHeight, _sliderMain);



                    }else{
                        ultra_responsive_last_layout = 'normal';
                        //cgallery.removeClass('force-ultra-responsive-top');

                        cgallery.removeClass('ultra-responsive');
                    }

                }



                if(o.settings_mode=='normal'){


                    if(o.nav_type=='thumbs'){

                        if(menu_position == 'right'){

                            if(menuitem_space){



                                _navCon.children('.navigationThumb').css({
                                    'margin-bottom':menuitem_space
                                    ,'margin-top':''
                                });


                                if(cgallery.hasClass('skin-boxy--rounded')){

                                    _navCon.css({
                                        'padding-top':menuitem_space
                                        ,'padding-bottom':menuitem_space
                                    });

                                    cgallery.appendOnce(' <svg class="svg_rounded" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="1px" height="1px" viewBox="0 0 1 1" enable-background="new 0 0 1 1" xml:space="preserve"> <g id="Layer_1"> </g> <g id="Layer_2"> <g> <defs> <path id="SVGID_1_" d="M1,0.99C1,0.996,0.996,1,0.99,1H0.01C0.004,1,0,0.996,0,0.99V0.01C0,0.004,0.004,0,0.01,0h0.98 C0.996,0,1,0.004,1,0.01V0.99z"/> </defs> <clipPath id="SVGID_2_"  clipPathUnits="objectBoundingBox"> <use xlink:href="#SVGID_1_" overflow="visible"/> </clipPath> <path clip-path="url(#SVGID_2_)" fill="#2A2F3F" d="M3,1.967C3,1.985,2.984,2,2.965,2h-3.93C-0.984,2-1,1.985-1,1.967v-2.934 C-1-0.985-0.984-1-0.965-1h3.93C2.984-1,3-0.985,3-0.967V1.967z"/> </g> </g> </svg>  ')
                                }

                            }else{


                                _navCon.children('.navigationThumb').css({
                                    'margin-bottom':''
                                });
                            }
                        }

                        if(menu_position == 'bottom'){

                            if(menuitem_space){



                                _navCon.children('.navigationThumb').css({
                                    'margin-right':menuitem_space
                                });


                                if(cgallery.hasClass('skin-boxy--rounded')){

                                    _navCon.css({
                                        // 'padding-top':menuitem_space
                                        // ,'padding-bottom':menuitem_space
                                    });

                                    cgallery.appendOnce(' <svg class="svg_rounded" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="1px" height="1px" viewBox="0 0 1 1" enable-background="new 0 0 1 1" xml:space="preserve"> <g id="Layer_1"> </g> <g id="Layer_2"> <g> <defs> <path id="SVGID_1_" d="M1,0.99C1,0.996,0.996,1,0.99,1H0.01C0.004,1,0,0.996,0,0.99V0.01C0,0.004,0.004,0,0.01,0h0.98 C0.996,0,1,0.004,1,0.01V0.99z"/> </defs> <clipPath id="SVGID_2_"  clipPathUnits="objectBoundingBox"> <use xlink:href="#SVGID_1_" overflow="visible"/> </clipPath> <path clip-path="url(#SVGID_2_)" fill="#2A2F3F" d="M3,1.967C3,1.985,2.984,2,2.965,2h-3.93C-0.984,2-1,1.985-1,1.967v-2.934 C-1-0.985-0.984-1-0.965-1h3.93C2.984-1,3-0.985,3-0.967V1.967z"/> </g> </g> </svg>  ')
                                }

                            }else{


                            }
                        }
                    }
                }




                _mainNavigation.removeClass('menu-top menu-bottom menu-right menu-left');
                _mainNavigation.addClass('menu-'+menu_position);

                cgallery.removeClass('menu-top menu-bottom menu-right menu-left');
                cgallery.addClass('menu-'+menu_position);

                // console.info(menu_position, nrChildren);

                //console.info('menuitem_space - ',menuitem_space);

                if ((menu_position == 'right' || menu_position == 'left') && nrChildren > 1) {
                    videoWidth -= (menuitem_width + menuitem_space);


                }


                if(_rparent.hasClass("skin-laptop")){
                    o.totalWidth = '62%';
                    // cgallery.height(_rparent.height() * 0.686)
                    cgallery.height(_rparent.height() * 0.716)
                    cgallery.attr('data-laptop-screen-height',_rparent.height() * 0.716)
                    o.totalHeightDifferenceOnParent = '-0.30';
                }

                // console.info("TEMP VIDEO HEIGHT", videoHeight);
                if (o.nav_type!='outer' && (menu_position == 'bottom' || menu_position == 'top') && nrChildren > 1 && cgallery.get(0).style &&  cgallery.get(0).style.height!='auto') {
                    //console.info(videoHeight);
                    videoHeight -= (menuitem_height + menuitem_space );

                }


                // console.groupCollapsed('currVideo data height');

                //console.log('currVideo - ',currVideo, o.videoplayersettings.responsive_ratio);

                if(currVideo && currVideo.data('height_for_videoheight')){

                    // console.info("GET videoHeight from currVideo data", currVideo.data('height_for_videoheight'));
                    videoHeight = currVideo.data('height_for_videoheight');

                    last_height_for_videoheight = videoHeight;
                }else{
                    // -- lets try to get the last value known for responsive ratio if the height of the current video is now currently known
                    if(o.videoplayersettings.responsive_ratio && o.videoplayersettings.responsive_ratio == 'detect'){

                        if(last_height_for_videoheight){

                            videoHeight = last_height_for_videoheight;
                        }

                    }else{
                        if(menu_position=='left' || menu_position =='right'){

                            videoHeight = 300;
                        }
                        //console.info("HMM menu_position - ",menu_position);
                    }
                }



                // console.groupEnd();

                //console.info('o.forceVideoHeight - ',o.forceVideoHeight);
                if(o.forceVideoHeight!='' && (!o.videoplayersettings || o.videoplayersettings.responsive_ratio!='detect') ){
                    videoHeight = o.forceVideoHeight;
                }

                if (o.settings_mode == 'rotator3d') {
                    videoWidth = totalWidth / 2;
                    videoHeight = totalHeight * 0.8;
                    menuitem_width = 0;
                    menuitem_height = 0;
                    menuitem_space = 0;
                    o.transition_type = 'rotator3d';
                }

                //console.warn('videoHeight - ',videoHeight);

                cgallery.addClass('transition-'+ o.transition_type)



                // === if there is only one video we hide the nav
                if (nrChildren == 1) {
                    //totalWidth = videoWidth;
                    _mainNavigation.hide();
                }



                if(typeof(currVideo)!='undefined'){
                    /// === why ?
                    /*
                     currVideo.css({
                     'width': videoWidth
                     ,'height': videoHeight
                     })
                     */
                };

                hpos = 0;
                for (i = 0; i < nrChildren; i++) {
                    //if(is_ios())	break;

                    _sliderCon.children().eq(i).css({
                    })
                    hpos += totalHeight;
                }

                //console.warn('videoHeight - ',videoHeight);
                // console.warn('menu_position - ',menu_position);
                if (o.settings_mode != 'wall' && o.settings_mode != 'videowall') {
                    // console.info(videoHeight);


                    // console.info('adjusting slidermain dims for !wall', videoHeight)
                    _sliderMain.css({
                        'width': videoWidth
                    })


                    if( (menu_position=='left' || menu_position == 'right')  && nrChildren>1){
                        _sliderMain.css('width','auto');

                        if(cgallery.hasClass('ultra-responsive')){

                            _sliderMain.css('height',videoHeight+'px');
                            //_navMain.css('height',(o.design_item_height * nrChildren)+'px');
                        }else{

                            _sliderMain.css({
                                'height': ''
                            })
                        }
                    }else{


                        // console.info("SLIDERMAIN videoHeight", videoHeight);
                        _sliderMain.css({
                            'height': videoHeight
                        })
                    }

                }

                if (o.settings_mode == 'rotator3d') {
                    _sliderMain.css({
                        'width': totalWidth,
                        'height': totalHeight
                    })
                    _sliderCon.children().css({
                        'width': videoWidth,
                        'height': videoHeight
                    })
                }
                if (menu_position == 'right') {

                    //console.info('o.nav_space - ',o.nav_space);
                    if(cgallery.hasClass('ultra-responsive')) {

                        _mainNavigation.css({

                            'margin-left': ''
                            , 'margin-right': ''
                            , 'height': '' // height is auto in ultra-responsive
                        });
                    }else{
                        _mainNavigation.css({
                            'width': menuitem_width
                            ,'height': totalHeight
                            ,'position':'relative'
                            ,'margin-left' : o.nav_space + 'px'
                            ,'margin-right' : 0
                            ,'margin-top' : 0
                            ,'margin-bottom' : 0
                        })
                    }

                }
                if (menu_position == 'left') {

                    //console.log(totalHeight);
                    if(cgallery.hasClass('ultra-responsive')) {

                        _mainNavigation.css({

                            'margin-left': ''
                            , 'margin-right': ''
                        });
                    }else {
                        _mainNavigation.css({
                            'width': menuitem_width,
                            'height': totalHeight,
                            'left': 0
                            , 'position': 'relative'
                            , 'margin-left': 0
                            , 'margin-right': o.nav_space+'px'
                            , 'margin-top': 0
                            , 'margin-bottom': 0
                        })
                        _sliderMain.css({
//                        'left': menuitem_width + o.nav_space
                        })
                    }
                }
                if (menu_position == 'bottom') {
                    _mainNavigation.css({
                        'width': totalWidth,
                        'height': menuitem_height
                        ,'margin-left' : 0
                        ,'margin-right' : 0
                        ,'margin-top' : o.nav_space + 'px'
                        ,'margin-bottom' : 0
                    })
                }
                if (menu_position == 'top') {
                    _mainNavigation.css({
                        'width': totalWidth
                        ,'height': ''
                        ,'margin-left' : 0
                        ,'margin-right' : 0
                        ,'margin-top' : 0
                        ,'margin-bottom' : o.nav_space + 'px'
                    })
                    _sliderMain.css({

                    })
                }





                //===calculate dims for navigation / mode-normal
                if (o.nav_type == 'thumbsandarrows') {

                    navWidth = (totalWidth - nav_arrow_size * 2);
                    navHeight = (totalHeight - nav_arrow_size * 2);




                    if (menu_position == 'bottom' || menu_position == 'top') {
                        thumbs_thumb_var = menuitem_width;
                        thumbs_thumb_var_sec = menuitem_height;
                        thumbs_total_var = totalWidth;
                        thumbs_total_var_sec = totalHeight;
                        thumbs_css_main = 'left';
                        _navMain.css({
                            'left': nav_arrow_size
                            ,'top': ''
                            , 'width': navWidth
                            , 'height': '100%'
                        });

                        var auxs = 0;
                        _navCon.children().each(function(){
                            var _t = $(this);
                            auxs+=_t.outerWidth();
                        })
                        _navCon.css('width', auxs);

                        _mainNavigation.children('.thumbs-arrow-left').css({
                            'left': nav_arrow_size / 2
                            ,'top': ''
                        });
                        _mainNavigation.children('.thumbs-arrow-right').css({
                            'left': navWidth + nav_arrow_size + nav_arrow_size / 2
                            ,'top': ''
                        });
                        nav_main_consize = navWidth;
                    }
                    if (menu_position == 'left' || menu_position == 'right') {
                        thumbs_thumb_var = menuitem_height;
                        thumbs_thumb_var_sec = menuitem_width;
                        thumbs_total_var = totalHeight;
                        thumbs_total_var_sec = totalWidth;
                        thumbs_css_main = 'top';
//                        console.info(navHeight);
                        _navMain.css({'top': nav_arrow_size
                            , 'height': navHeight, 'width': '100%'
                            , 'left': ''
                        });
                        _mainNavigation.children('.thumbs-arrow-left').css({
                            'top': nav_arrow_size / 2
                            ,'left': ''
                        });
                        _mainNavigation.children('.thumbs-arrow-right').css({
                            'top': 'auto'
                            , 'bottom': nav_arrow_size / 2 - 10
                            ,'left': ''
                        });
                        nav_main_consize = navHeight;
                    }


                    nav_main_totalsize = nrChildren * thumbs_thumb_var + (nrChildren - 1) * menuitem_space;
                    aux1 = (((((thumbs_total_var - nav_arrow_size * 2) / (thumbs_thumb_var + menuitem_space)) * (thumbs_thumb_var + menuitem_space)))) - ((((parseInt((thumbs_total_var - nav_arrow_size * 2) / (thumbs_thumb_var + menuitem_space))) * (thumbs_thumb_var + menuitem_space))));

                    nav_page_size = menuitem_width+menuitem_space ;
                    nav_max_pages = nav_main_totalsize / thumbs_thumb_var;

                    nav_pages_visible = Math.floor(nav_main_consize / thumbs_thumb_var) ;

                    thumbs_per_page = Math.floor(nav_page_size / (thumbs_thumb_var + menuitem_space));
                    nav_max_pages = (Math.ceil(nav_max_pages));
                    nav_max_pages = nav_max_pages - nav_pages_visible;
                    nav_excess_thumbs = (nav_main_totalsize - (nav_max_pages - 1) * nav_page_size);


                    // console.info(cgallery, 'nav_main_totalsize --- ',nav_main_totalsize, thumbs_thumb_var, 'nav_pages_visible - ',nav_pages_visible);
                    // console.info('nav_page_size vars - ', thumbs_total_var, nav_arrow_size, aux1);
                    // console.info('nav_max_pages - ',nav_max_pages);

                    if (nav_main_totalsize < nav_main_consize) {
                        _mainNavigation.children('.thumbs-arrow-left').hide();
                        _mainNavigation.children('.thumbs-arrow-right').hide();



                        _navCon.css({'left' : ''})
                    }else{

                        _mainNavigation.children('.thumbs-arrow-left').show();
                        _mainNavigation.children('.thumbs-arrow-right').show();
                    }



                }


                if(o.nav_type=='thumbs'){
                    if (menu_position == 'bottom' || menu_position == 'top') {
                        //console.log(_navCon.width())
                        navWidth = 0;
                        _navCon.children().each(function(){
                            var _t = $(this);
                            navWidth+=_t.outerWidth(true);
                        });



                        if(navWidth > totalWidth){
                            _navMain.unbind('mousemove', handleMouse);
                            _navMain.bind('mousemove', handleMouse);

                        }else{

                            cgallery.addClass('navWidth-bigger-then-totalWidth')
                            _navCon.css({'left' : ''})
                            _navMain.unbind('mousemove', handleMouse);

                        }
                    }
                    if (menu_position == 'left' || menu_position == 'right') {




                        //console.log(_navCon.width())
                        navHeight = 0;
                        navHeight = _navCon.outerHeight();

                        //console.info('navHeight - ',navHeight);
//                        console.info(navHeight);
                        if(navHeight > totalHeight){
                            _navMain.unbind('mousemove', handleMouse);
                            _navMain.bind('mousemove', handleMouse);
                        }else{
                            _navCon.css({'top' : ''})
                            _navMain.unbind('mousemove', handleMouse);
                        }
                    }

                }
                //===END calculate dims for navigation / mode-normal


                if(o.nav_type=='outer'){
                    _mainNavigation.css({
                        'top':0,
                        'left':0,
                        'height':'auto'
                    });
                    _navMain.css({
                        //'height':'auto'
                    })
                    _sliderMain.css({
                        'top':0,
                        'left':0
                    })
                    cgallery.css({
                        'height':'auto'
                    })
                    _navCon.children().css({
                        'top' : 0,
                        'left' : 0,
                        'width' : '',
                        'height' : ''
                    });

                    if(menu_position=='right'){
                        _sliderMain.css({
                            'overflow':'hidden'
                        })
                    }
                    if(menu_position=='left'){
                        _sliderMain.css({
                            'overflow':'hidden'
                        })
                    }
                }




                if (o.settings_mode == 'normal') {
                    hpos = 0;
                    wpos =0 ;

                    _navCon.children('.navigationThumb').unbind('click', click_navCon_item);
                    _navCon.children('.navigationThumb').bind('click', click_navCon_item);


                    _navCon.children('.navigationThumb').css({
                        'width': menuitem_width,
                        'height': menuitem_height
                    });

                    //console.info('menuitem_height - ',menuitem_height);

                    if(menuitem_height==0){
                        _navCon.children('.navigationThumb').css({
                            'height':''
                        });
                    }

                    if(isNaN(menuitem_height) == false && menuitem_height>0){
                        if(cgallery.hasClass('skin-aurora')){
                            _navCon.find('.navigationThumb .menu-desc').css({
                                'max-height': menuitem_height
                            })
                        }
                    }
                }

                if(o.nav_type=='scroller'){

                    if(menu_position=='top' || menu_position=='bottom'){
                        navWidth = 0;
                        _navCon.children().each(function(){
                            var _t = $(this);
                            navWidth+=_t.outerWidth(true);
                        });
                        _navCon.width(navWidth);
                    }
                }


                if(o.settings_mode=='normal'){
                    if(menu_position=='right' || menu_position=='bottom' || menu_position=='left'){
                        cgallery.find('.gallery-buttons').css({
                            'top':0
                        });
                        cgallery.find('.the-logo').css({
                            'top':10
                        });
                    }
                    if(menu_position=='top' || menu_position=='bottom' || menu_position=='left'){
                        cgallery.find('.gallery-buttons').css({
                            'right':0
                        });
                        cgallery.find('.the-logo').css({
                            'right':50
                        });
                    }

                    //console.info(menu_position,  (Number(o.menuitem_width) + Number(o.nav_space)));

                    if (menu_position == 'right') {

                        _gbuttons.css({
                            'right': (Number(o.menuitem_width) + Number(o.nav_space)) + 'px'
                        });
                        if (cgallery.find('.the-logo').length > 0) {
                            cgallery.find('.the-logo').css({
                                'right': (Number(o.menuitem_width) + Number(o.nav_space) + 60) + 'px'
                            });
                        }
                    }
                    if (menu_position == 'top') {
                        _gbuttons.css({
                            'top': (Number(o.menuitem_height) + Number(o.nav_space)) + 'px'
                        });
                        if (cgallery.find('.the-logo').length > 0) {
                            cgallery.find('.the-logo').css({
                                'top': (Number(o.menuitem_height) + Number(o.nav_space) + 10) + 'px'
                                , 'right': (60) + 'px'
                            });
                        }
                    }
                }





                calculate_dims_second_con(currNr_curr);



                //====== calculateDims() END
            }

            function handle_orientationchange(){
                setTimeout(function(){
                    handleResize();
                },1000);
            }

            function handleResize(e,pargs) {
                ww = $(window).width();
                wh = $(window).height();

                conw = _rparent.width();






                if(cgallery.hasClass('try-breakout')){
                    cgallery.css('width',ww+'px');

                    cgallery.css('margin-left','0');

                    //console.info(cgallery, cgallery.get(0).offsetLeft, cgallery.offset().left, _theTarget.offset().left)

                    if(cgallery.offset().left>0){
                        cgallery.css('margin-left','-'+cgallery.offset().left+'px');
                    }
                }



                if(cgallery.hasClass('try-height-as-window-minus-offset')){

                    var aux = wh - cgallery.offset().top;

                    if(aux<300){

                        cgallery.css('height','90vh')
                    }else{
                        cgallery.css('height',aux+'px');
                    }
                    //console.info('ceva', aux);

                }



                if(cgallery.hasClass('try-height-as-window')){

                    var aux = wh;

                    if(aux<300){

                        cgallery.css('height',aux+'px');
                    }else{
                        cgallery.css('height','100vh')
                    }
                    //console.info('ceva', aux);

                }

                // console.info('handleResize() gallery - ', cgallery);

                //console.log('ceva', ww, wh, conw, conh, totalWidth, totalHeight, (conw/totalWidth));
                //console.log(o.responsive_mode, totalWidth, totalHeight);
                calculateDims();


                if (o.settings_mode == 'wall') {


                }

                //alert(currVideo);
                if(currVideo){
                    handleResize_currVideo();
                }

            }

            function handleResize_currVideo(e, pargs){




                var margs = {
                    'force_resize_gallery' : true
                    ,'call_from' : 'default'
                };

                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                margs.call_from+='_handleResize_currVideo';

                // console.info(currVideo);
                if((currVideo) && currVideo.get(0) && (currVideo.get(0).api_handleResize)){


                    // console.info('call resize from vgallery');
                    currVideo.get(0).api_handleResize(null,margs);
                }
            }

            function pause_currVideo(e, pargs){




                var margs = {
                    'force_resize_gallery' : true
                    ,'call_from' : 'default'
                };

                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                margs.call_from+='_pause_currVideo';

                if((currVideo) && (currVideo.get(0).api_pauseMovie)){


                    // console.info('call resize from vgallery');
                    currVideo.get(0).api_pauseMovie(margs);
                }
            }


            function api_currVideo_refresh_fsbutton(argcol){
                if(typeof(currVideo)!="undefined" && typeof(currVideo.get(0))!="undefined" && typeof(currVideo.get(0).api_currVideo_refresh_fsbutton)!="undefined"){
                    currVideo.get(0).api_currVideo_refresh_fsbutton(argcol);
                }
            }


            function randomise(arg, max) {
                arg = parseInt(Math.random() * max);
                var sw = 0;
                for (var j = 0; j < used.length; j++) {
                    if (arg == used[j])
                        sw = 1;
                }
                if (sw == 1) {
                    randomise(0, max);
                    return;
                } else
                    used.push(arg);
                return arg;
            }


            function animate_menu_x(viewIndex){



                //console.info(finish_viy);

                if(is_ios()==false && is_android()==false){
                    if(o.design_navigationUseEasing!='on'){

                        if ($('html').hasClass('supports-translate')) {


                            _navCon.css({
                                '-webkit-transform': 'translate3d('+finish_vix+'px, '+0+'px, 0)'
                                ,'transform': 'translate3d('+finish_vix+'px, '+0+'px, 0)'
                            });
                        }else{
                            _navCon.css({
                                'left': finish_vix
                            });
                        }
                    }


                }
            }


            function animate_menu_y(viewIndex,pargs){




                // -- positive number viewIndexX
                var margs = {

                    call_from : "default"
                }

                if(pargs){
                    margs = $.extend(margs,pargs);
                }





                // console.info('animate_menu_y()', viewIndex);

                if(is_touch_device()==false){

                    // console.info('finish_viy - ',finish_viy);

                    if(o.design_navigationUseEasing!='on'){

                        _navCon.css({
                            'transform': 'translate3d(0, '+ (finish_viy)+'px, 0)'
                        });
                    }else{
                        if((-finish_viy)<_navCon.outerHeight() - _mainNavigation.outerHeight()){

                            finish_viy = -(_navCon.outerHeight() - _mainNavigation.outerHeight());

                        }
                        finish_viy = -viewIndex;
                    }


                }else{
                    if(margs.call_from=='animate_to_curr_thumb'){


                        setTimeout(function(){

                            _mainNavigation.animate({'scrollTop':viewIndex});
                            _mainNavigation.scrollTop(viewIndex);
                        },1500);
                    }
                }
            }

            function handle_frame(){


                if(isNaN(target_viy)){
                    target_viy=0;
                }

                if(duration_viy===0){
                    requestAnimFrame(handle_frame);
                    return false;
                }

                if(nav_thumbs_dir_ver){
                    begin_viy = target_viy;
                    change_viy = finish_viy - begin_viy;



                    target_viy = Number(Math.easeIn(1, begin_viy, change_viy, duration_viy).toFixed(4));;


                    //console.info(finish_viy);

                    if(is_ios()==false && is_android()==false){
                        _navCon.css({
                            'transform': 'translate3d(0,'+target_viy+'px,0)'
                        });
                    }

                }

                // console.info(nav_thumbs_dir_hor);
                if(nav_thumbs_dir_hor){
                    begin_vix = target_vix;
                    change_vix = finish_vix - begin_vix;

                    // console.info('duration_viy - ',duration_viy);

                    target_vix= Number(Math.easeIn(1, begin_vix, change_vix, duration_viy).toFixed(4));;


                    // console.info(finish_vix);

                    if(is_ios()==false && is_android()==false){
                        _navCon.css({
                            'transform': 'translate3d('+target_vix+'px,0,0)'
                        });
                    }

                }

                //console.info(_blackOverlay,target_bo);;

                requestAnimFrame(handle_frame);
            }



            function pre_animate_menu_x(navMain_mousex){
                viewMaxH = (navWidth) - totalWidth;
                finish_vix = (navMain_mousex / totalWidth) * -(viewMaxH + offsetBuffer * 2) + offsetBuffer;
                //finish_viy = parseInt(viewIndex, 10);


                // console.info('finish_viy -> ',finish_viy);


                //console.info(finish_viy);
                if (finish_vix > 0)
                    finish_vix = 0;
                if (finish_vix < -viewMaxH)
                    finish_vix = -viewMaxH;


                if(o.design_navigationUseEasing=='on'){

                }else{



                    animate_menu_x(viewIndex);
                }
            }

            function pre_animate_menu_y(navMain_mousey,pargs){



                var margs = {

                    call_from : "default"
                }

                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                viewMaxH = (navHeight) - totalHeight;
                finish_viy = (navMain_mousey / totalHeight) * -(viewMaxH + offsetBuffer * 2) + offsetBuffer;
                //finish_viy = parseInt(viewIndex, 10);



                // console.info('pre_animate_menu_y - ',navMain_mousey, margs);
                // console.info('finish_viy - ',finish_viy);
                // console.info('viewMaxH - ',viewMaxH);
                // console.info('navHeight - ',navHeight);
                // console.info('totalHeight - ',totalHeight);
                // console.info('finish_viy -> ',finish_viy);


                //console.info(finish_viy);
                if (finish_viy > 0)
                    finish_viy = 0;
                if (finish_viy < -viewMaxH)
                    finish_viy = -viewMaxH;


                if(is_touch_device()){
                    o.design_navigationUseEasing = 'off';
                }
                // console.log('o.design_navigationUseEasing -> ',o.design_navigationUseEasing);
                if(o.design_navigationUseEasing=='on'){

                }else{



                    animate_menu_y(viewIndex);
                }
            }

            function handleMouse(e) {
                //handle mouse for the _navCon element
                var menuAnimationSw = true;
                navMain_mousey = (e.pageY - _navMain.offset().top)
                navMain_mousex = (e.pageX - _navMain.offset().left)

//                console.info(mouseX,mouseY, is_android())
                if (is_ios() == false && is_android() == false) {


                    if(menu_move_locked){
                        return false;
                    }

                    if (menu_position == 'right' || menu_position == 'left') {
                        nav_thumbs_dir_ver = true;
                        nav_thumbs_dir_hor = false;




                        pre_animate_menu_y(navMain_mousey,{

                            call_from : "handleMouse"
                        });




                    }
                    if (menu_position == 'bottom' || menu_position == 'top') {

                        // console.info('navWidth - ',navWidth);
                        // console.info('totalWidth - ',totalWidth);
                        viewMaxH = navWidth - totalWidth;
                        finish_vix = ((navMain_mousex / totalWidth) * -(viewMaxH + offsetBuffer * 2) + offsetBuffer) / currScale;
                        finish_vix = parseInt(finish_vix, 10);


                        // console.info('finish_vix - ',finish_vix);


                        if (finish_vix > 0)
                            finish_vix = 0;
                        if (finish_vix < -viewMaxH)
                            finish_vix = -viewMaxH;

                        // console.info('finish_vix - ',finish_vix);




                        nav_thumbs_dir_ver = false;
                        nav_thumbs_dir_hor = true;

                        if(o.design_navigationUseEasing=='on'){

                        }else{



                            animate_menu_x(viewIndex);
                        }

                        //_navCon.animate({'left' : -((e.pageX-_navMain.offset().left)/totalWidth * (((o.menuitem_width + o.menuitem_space)*nrChildren) - totalWidth))	}, {queue:false, duration:100});
                    }

                }else{
//                    console.info('ceva');
                    return false;
                }

            }

            function click_navCon_item(e) {
                //console.info('click_navCon_item')
                var _t = $(this);

                var cclass= '';

                if(_t.hasClass('navigationThumb')){
                    cclass='.navigationThumb';
                }

                if(_t.get(0) && _t.get(0).nodeName!="A"){
                    gotoItem(_navCon.children(cclass).index(_t));


                    if(o.nav_type_auto_scroll=='on') {
                        if (o.nav_type == 'thumbs' || o.nav_type == 'scroller') {




                            menu_move_locked=true;

                            setTimeout(function () {


                                animate_to_curr_thumb({
                                    'call_from':'animate_to_curr_thumb'
                                });

                            }, 100);
                            setTimeout(function () {


                                menu_move_locked = false;

                            }, 2000);
                        }
                    }

                }else{
                    if(currVideo && currVideo.get(0) && typeof(currVideo.get(0).api_pauseMovie)!="undefined"){
                        currVideo.get(0).api_pauseMovie({
                            'call_from':'click_navCon_item()'
                        });
                    }

                }

            }

            function handle_scroll(){
                //console.log(loaded);
                if(loaded==false){




                    var st = $(window).scrollTop();
                    var cthis_ot = cgallery.offset().top;

                    var wh = window.innerHeight;


                    // console.info(cthis_ot, st+wh);


                    if(cthis_ot<st+wh+50){
                        init();
                    }


                    return;
                }else{

                    var _t = $(this);//==window
                    wh = $(window).height();
                    //console.log(_t.scrollTop(), wh, cgallery.offset().top, cgallery.height(), ind_ajaxPage, o.settings_separation_pages, _t.scrollTop() + wh, (cgallery.offset().top + cgallery.height() - 10), (_t.scrollTop() + wh) > (cgallery.offset().top + cgallery.height() - 10), ind_ajaxPage, o.settings_separation_pages.length ) ;

                    if(busy_ajax==true || ind_ajaxPage >= o.settings_separation_pages.length){
                        return;
                    }



                    if( (_t.scrollTop() + wh) > (cgallery.offset().top + cgallery.height() - 10) ){
                        //console.info('ALCEVA');
                        ajax_load_nextpage();
                    }
                }

            }
            function click_btn_ajax_loadmore(e){

                if(busy_ajax==true || ind_ajaxPage >= o.settings_separation_pages.length){
                    return;
                }
                ajax_load_nextpage();
            }

            function ajax_load_nextpage(){

                //console.log('ajax_load_nextpage');
                cgallery.parent().children('.preloader').fadeIn('slow');

                $.ajax({
                    url : o.settings_separation_pages[ind_ajaxPage],
                    success: function(response) {
                        if(window.console !=undefined ){ console.log('Got this from the server: ' + response); }
                        setTimeout(function(){

                            cgallery.append(response);
                            //setTimeout(reinit, 1000);
                            reinit();
                            setTimeout(function(){
                                busy_ajax = false ;
                                cgallery.parent().children('.preloader').fadeOut('slow');
                                ind_ajaxPage++;





                                if(ind_ajaxPage >= o.settings_separation_pages.length){
                                    cgallery.children('.btn_ajax_loadmore').fadeOut('slow');
                                }




                            }, 1000);
                        }, 1000);
                    },
                    error:function (xhr, ajaxOptions, thrownError){
                        if(window.console !=undefined ){ console.error('not found ' + ajaxOptions); }
                        ind_ajaxPage++;
                        cgallery.parent().children('.preloader').fadeOut('slow');

                    }
                });

                busy_ajax = true ;
            }

            function gotoItem(arg, pargs) {
//                console.log(_sliderCon.children().eq(arg), currNr, arg, busy_transition);
//                 console.log('gotoItem() - ', currNr, arg, busy_transition);





                var margs = {

                    'ignore_arg_currNr_check' : false
                    ,'ignore_linking' : false // -- does not change the link if set to true
                    ,donotopenlink : "off"
                    ,call_from : "default"
                }

                if(pargs){
                    margs = $.extend(margs,pargs);
                }



                if (currNr == arg || busy_transition == true || ad_playing){
                    return false;
                }
                var transformed = false; //if the video is already transformed there is no need to wait





                var _c = _sliderCon.children().eq(arg);
                currVideo = _c;
                var index = _c.parent().children().index(_c);


                // console.info(index, _c);




                if(_c.attr('data-type')=='link' && (margs.donotopenlink!='on')){


                    // --- history API ajax cool stuff
                    if(o.settings_enableHistory=='on' && can_history_api()){
                        var stateObj = { foo: "bar" };
                        history.pushState(stateObj, "Gallery Video", _c.attr('data-src'));

                        //jQuery('.history-video-element').load(_c.attr('data-src') + ' .history-video-element');

                        $.ajax({
                            url : _c.attr('data-src'),
                            success: function(response) {
                                if(window.console !=undefined ){ console.info('Got this from the server: ' + response); }
                                setTimeout(function(){
                                    //console.log(jQuery(response).find('.history-video-element').eq(0).get(0).innerHTML);

                                    $('.history-video-element').eq(0).html($(response).find('.history-video-element').eq(0).html());


                                    $('.toexecute').each(function(){
                                        var _t = $(this);
                                        if(_t.hasClass('executed')==false){
                                            eval(_t.text());
                                            _t.addClass('executed');
                                        }
                                    });


                                    if(o.settings_ajax_extraDivs!=''){
                                        var extradivs = String(o.settings_ajax_extraDivs).split(',');

                                        for(i=0;i<extradivs.length;i++){
                                            //console.log(extradivs[i], jQuery(response).find(extradivs[i]));
                                            $(extradivs[i]).eq(0).html(jQuery(response).find(extradivs[i]).eq(0).html());
                                        }
                                    }

                                    //console.log(jQuery(response)); console.log(jQuery('.toexecute'));
                                    //jQuery('.history-video-element').eq(0).get(0).innerHTML = jQuery(response).find('.history-video-element').eq(0).get(0).innerHTML;
                                    //eval(jQuery('.toexecute').text());
                                }, 100);
                            },
                            error:function (xhr, ajaxOptions, thrownError){
                                if(window.console !=undefined ){ console.error('not found ' + ajaxOptions); }
                                ind_ajaxPage++;
                                cgallery.children('.preloader').fadeOut('slow');

                            }
                        });
                        /*
                         */

                        return false;
                    }else{
                        //window.location = _c.attr('data-src');
                    }
//                    return;


                }


                // console.warn('margs - ',margs);
                if(_c.attr('data-type')!='link'){
                    if(margs.ignore_linking==false && o.settings_enable_linking=='on' && margs.call_from!='init'){
                        var stateObj = { foo: "bar" };



                        //console.info('$(\'.videogallery\').length -  ', $('.videogallery').length);

                        //console.warn('first_played - ',first_played);
                        if($('.videogallery').length==1){

                            history.pushState(stateObj, null, add_query_arg(window.location.href, 'the-video', (arg)));
                        }else{

                            history.pushState(stateObj, null, add_query_arg(window.location.href, 'the-video-'+cid, (arg)));
                        }
                    }
                }

                currNr_curr = arg;

                if (currNr > -1) {
                    var _c2 = _sliderCon.children().eq(currNr);
                    //console.log(_c2);

                    // --- if on iPad or iPhone, we disable the video as it had runed in a iframe and it wont pause otherwise

                    //console.log(_c2.attr('data-type'))
                    _c2.addClass('transitioning-out');

                    if (_c2.attr('data-type') == 'inline' || (_c2.attr('data-type') == 'youtube' && o.videoplayersettings['settings_youtube_usecustomskin']!='on')){

                    }
                    //console.log(_c2, arr_inlinecontents);

                    //console.log(o.videoplayersettings);


                    //console.info(o.videoplayersettings['settings_youtube_usecustomskin']);
                    if (o.settings_mode=='normal' && ( is_ios() || _c2.data('isflash') == 'on' || _c2.attr('data-type') == 'inline' || (_c2.attr('data-type') == 'youtube' && o.videoplayersettings['settings_youtube_usecustomskin']!='on')  || (_c2.attr('data-type') == 'youtube' && is_android() ) ) ) {
                        setTimeout(function() {
                            _c2.find('.video-description').remove();
                            arr_inlinecontents[currNr] = _c2.html();
                            _c2.html('');
                            _c2.removeClass('vplayer');
                            _c2.addClass('vplayer-tobe');

                        }, 1000);
                    }
                    ;
                }


                // console.info('o.autoplay_ad - ',o.autoplay_ad)
                if(o.autoplay_ad=='on'){

                    setup_ad(_c);

                    _c.data('adplayed','on');
                }else{

                    _c.data('adplayed','off');
                }



                //console.error('currNr - ',currNr);
                if (_c.hasClass('vplayer')) {
                    transformed = true;
                }


                // console.info('_c - ',_c);
                _c.addClass('transitioning-in');
                if (_c.hasClass('vplayer-tobe')) {

                    //console.log(_c);
                    _c.addClass('in-vgallery');
                    o.videoplayersettings['videoWidth'] = videoWidth;
                    o.videoplayersettings['videoHeight'] = '';

                    if(o.logo){
                        _c.children('.vplayer-logo').remove();
                    }

                    //console.log(videoWidth, videoHeight);
                    //console.log(currNr, o.cueFirstVideo, o.autoplay);
                    if (currNr == -1 && o.cueFirstVideo == 'off') {
                        o.videoplayersettings.cueVideo = 'off';
                    } else {
                        o.videoplayersettings.cueVideo = 'on';
                    }
                    //var sw_autoplay = 'off';
                    // console.warn('currNr - ',currNr);
                    // console.warn('o.autoplay ( cgallery )  -  ',o.autoplay);
                    if (currNr == -1) {
                        if (o.autoplay == 'on') {
                            //console.warn('hmm from dzs');
                            //console.warn(cgallery);

                            if(cgallery.parent().parent().parent().hasClass('categories-videogallery')){
                                if(cgallery.parent().parent().hasClass('gallery-precon')){

                                    if(cgallery.parent().parent().hasClass('curr-gallery')){

                                        o.videoplayersettings['autoplay'] = 'on';
                                    }else{

                                        o.videoplayersettings['autoplay'] = 'off';
                                    }
                                }else{

                                    o.videoplayersettings['autoplay'] = 'on';
                                }
                            }else{

                                o.videoplayersettings['autoplay'] = 'on';
                            }



                        } else {
                            o.videoplayersettings['autoplay'] = 'off';
                        }

                    }

                    // console.info("o.videoplayersettings['autoplay'] - ",o.videoplayersettings['autoplay']);
                    //-- if it's not the first video
                    if (currNr > -1) {
                        if (o.autoplayNext == 'on') {
                            o.videoplayersettings['autoplay'] = 'on';
                            o.videoplayersettings['cueVideo'] = 'on';
                        } else {
                            o.videoplayersettings['autoplay'] = 'off';
                        }
                    }
                    if (ad_playing == true) {
                        o.videoplayersettings['autoplay'] = 'off';
                    }


                    o.videoplayersettings['settings_disableControls'] = 'off';


                    if (typeof(arr_inlinecontents[arg]) != 'undefined' && arr_inlinecontents[arg] != '') {
                        //console.log(arr_inlinecontents, arr_inlinecontents[arg]);
                        o.videoplayersettings.htmlContent = arr_inlinecontents[arg];
                    } else {
                        o.videoplayersettings.htmlContent = '';
                    }

                    o.videoplayersettings.gallery_object = cgallery;

                    //console.log(o.videoplayersettings);


                    if(o.videoplayersettings.end_exit_fullscreen=='off'){


                        // console.info('is fullscreen -',fs_status());

                        if(fs_status()=='1'){

                            if(o.videoplayersettings.extra_classes){

                                o.videoplayersettings.extra_classes+=' is_fullscreen is-fullscreen';
                            }else{

                                o.videoplayersettings.extra_classes=' is_fullscreen is-fullscreen';
                            }
                        }
                    }

                    // console.info("CONVERT IT");
                    if(o.settings_disableVideo=='on'){
                    }else{
                        //console.log(o.videoplayersettings);

                        //console.info(_c);

                        var args = $.extend({}, o.videoplayersettings);

                        args.gallery_last_curr_nr = currNr;
                        _c.vPlayer(args);

                    }


                }else{

                    // -- if already setuped

                    if (o.autoplayNext == 'on') {
                        if(typeof _c.get(0)!='undefined' && typeof _c.get(0).externalPlayMovie!='undefined'){
                            _c.get(0).externalPlayMovie({
                                'call_from':'autoplayNext'
                            });
                        }

                    }



                    if(o.videoplayersettings.end_exit_fullscreen=='off'){


                        // console.info('is fullscreen -',fs_status());

                        if(fs_status()=='1'){

                            _c.addClass('is_fullscreen is-fullscreen');
                        }
                    }

                    // -- we force a resize on the player just in case it has an responsive ratio


                    setTimeout(function(){
                        if(typeof _c.get(0)!='undefined' && _c.get(0).api_handleResize){

                            _c.get(0).api_handleResize(null,{
                                force_resize_gallery : true
                            })
                        }
                    },250)

                }








                prevNr = arg - 1;
                if (prevNr < 0) {
                    prevNr = _sliderCon.children().length - 1;
                }
                nextNr = arg + 1;
                if (nextNr > _sliderCon.children().length - 1) {
                    nextNr = 0;
                }


                if (o.nav_type == 'thumbsandarrows') {

                }
                if (o.settings_mode == 'normal') {
                    _c.css('display','');
                }
                if (o.settings_mode == 'rotator3d') {
                    _sliderCon.children().removeClass('nextItem currItem hide-preview-img').removeClass('prevItem');
                    _sliderCon.children().eq(nextNr).addClass('nextItem');
                    _sliderCon.children().eq(prevNr).addClass('prevItem');
                }
                if (o.settings_mode == 'rotator') {

                    if (currNr > -1) {

                    }
                    var _descCon = _navMain.children('.descriptionsCon');
                    _descCon.children('.currDesc').removeClass('currDesc').addClass('pastDesc');
                    _descCon.append('<div class="desc">' + _c.find('.menuDescription').html() + '</div>');
                    setTimeout(function() {
                        _descCon.children('.desc').addClass('currDesc');
                    }, 20)

                    //console.log(_c);
                }




//                console.info(currNr, transformed);

                last_arg = arg;
                if (currNr == -1 || transformed) {
                    the_transition();
                    if(o.settings_mode == 'rotator3d') {
                        _sliderCon.children().eq(arg).addClass('hide-preview-img');
                    }
                } else {
                    cgallery.parent().children('.preloader').fadeIn('fast');
//                    the_transition();

                    var delay = 500;

                    console.info("DO IT NOW");
                    if(o.settings_mode == 'rotator3d'){
                        delay = 10;
                        _sliderCon.children().eq(arg).addClass('currItem');
                        setTimeout(function(){

                            _sliderCon.children().eq(arg).addClass('hide-preview-img');
                        },300);
                    }

                    inter_start_the_transition = setTimeout(the_transition, delay, arg);

                }



                // console.info('arg for calculate_dims_second_con - ',arg);
                calculate_dims_second_con(arg);
                if(o.settings_outerNav){

                    var _c_outerNav = $(o.settings_outerNav);
                    _c_outerNav.find('.videogallery--navigation-outer--block ').removeClass('active');
                    _c_outerNav.find('.videogallery--navigation-outer--block ').eq(arg).addClass('active');

                    _c_outerNav.find('*[data-global-responsive-ratio]').each(function(){
                        var _t4 = $(this);

                        var rat = Number(_t4.attr('data-global-responsive-ratio'));

                        _t4.height( rat * _t4.width());
                    })
                }

                if(cgallery.hasClass('responsive-ratio-smooth')){
                    if(!_c.attr('data-responsive_ratio')){
                        responsive_ratio_resize_h(initial_h);
                    }else{
                        $(window).trigger('resize');
                    }
//                    responsive_ratio_resize_h(initial_h);
                }


                /*
                 if(is_ios()){
                 //	console.log(currNr, arg);

                 }else{
                 if(currNr>-1) {




                 }
                 */

                cgallery.removeClass('hide-players-not-visible-on-screen');
                setTimeout(function(){

                    cgallery.addClass('hide-players-not-visible-on-screen');
                    _sliderCon.find('.transitioning-in').removeClass('transitioning-in');
                    _sliderCon.find('.transitioning-out').removeClass('transitioning-out');


                    // console.info('cgallery.parent().parent().next() - ',cgallery.parent().parent().next());

                    var _extraBtns = null;


                    if(cgallery.parent().parent().next().hasClass('extra-btns-con')){
                        _extraBtns = cgallery.parent().parent().next();
                    }
                    if(cgallery.parent().parent().next().next().hasClass('extra-btns-con')){
                        _extraBtns = cgallery.parent().parent().next().next();
                    }
                    if(_extraBtns){
                        // console.info(cgallery.parent().parent().next(), currNr, currVideo);
                        _extraBtns.find('.stats-btn').attr('data-playerid', currVideo.attr('data-player-id'));

                    }
                },400);
                firsttime = false;
                busy_transition = true;




                return true;
            }



            function the_transition() {
                if(sw_transition_started){
                    return;
                }

                var arg = last_arg;


                var _c = _sliderCon.children().eq(arg);

                sw_transition_started = true;
                clearTimeout(inter_start_the_transition);
                cgallery.parent().children('.preloader').fadeOut('fast');


                _sliderCon.children().removeClass('currItem');


                // console.info('the_transition - ', _c, currNr);
                if(currNr==-1){
                    _c.addClass('currItem');
                    _c.addClass('no-transition');
                    setTimeout(function(){
                        _sliderCon.children().eq(currNr).removeClass('no-transition')
                    })
                }else{

                    if(currNr!=arg){

                        _sliderCon.children().eq(currNr).addClass('transition-slideup-gotoTop')
                    }else{

                        _sliderCon.children().eq(currNr).addClass('currItem');
                    }



                }

                setTimeout(setCurrVideoClass, 100);
                _navCon.children().removeClass('active');
                _navCon.children().eq(arg).addClass('active');

                if(o.nav_type=='thumbs' || o.nav_type=='scroller' || o.nav_type=='thumbsandarrows'){

                    _navCon.children('.navigationThumb').removeClass('active');
                    _navCon.children('.navigationThumb').eq(arg).addClass('active');
                }


//                console.info(arg, _navCon.children().eq(arg));

                setTimeout(function(){
                    $('window').trigger('resize');
                    _sliderCon.children().removeClass('transition-slideup-gotoTop');
                },1000);

                if (is_ios() && currNr > -1) {
                    if (_sliderCon.children().eq(currNr).children().eq(0).length > 0 && _sliderCon.children().eq(currNr).children().eq(0)[0] != undefined) {
                        if (_sliderCon.children().eq(currNr).children().eq(0)[0].tagName == 'VIDEO') {
                            _sliderCon.children().eq(currNr).children().eq(0).get(0).pause();
                        }
                    }
                }

                if(first_transition){

                    handle_stopCurrVideo({
                        'call_from':'the_transition'
                    });
                }

                if(currNr > -1){


                    first_transition = true;

                    //console.error('currNr - - ',currNr);

                }
                currNr = arg;

                setTimeout(function(){

                    busy_transition = false;
                    sw_transition_started = false;
                    hide_all_videos_but_curr();
                }, 400);
            } // end the_transition()



            function calculate_dims_second_con(arg){


                if(o.settings_secondCon){

                    var _c = $(o.settings_secondCon);

                    // console.info(_c);

                    // console.warn('(arg*100) -> ',(arg*100));

//                    console.info($(o.settings_secondCon).find('.item').eq(arg).outerHeight(false));
                    _c.find('.item').removeClass('active');
                    _c.find('.item').eq(arg).addClass('active');
                    _c.find('.dzsas-second-con--clip').css( {
                            'height': _c.find('.item').eq(arg).outerHeight(false)
                            ,'left' : -(arg*100)+'%'
                        }
                    );


                }
            }

            function hide_all_videos_but_curr(){
                if(o.settings_mode=='normal'){

                    _sliderCon.children().each(function(){
                        var _t = $(this);

                        if(_t.hasClass('currItem')==false){
                            _t.hide();
                        }
                    })
                }
            }



            function setup_ad(arg){
                // -- challery

                // console.info("SETUP_AD()");

                var _c = arg;

                // console.info('adding ad - ',_c.attr('data-adsource'), _c.find('.adSource').length>0,(_c.attr('data-adsource') ||  _c.find('.adSource').length>0 ), ( is_ios() && o.videoplayersettings.settings_ios_usecustomskin=='on' ) );


                //!is_ios() ||

                if ( (_c.attr('data-adsource') ||  _c.find('.adSource').length>0 ) && !( is_ios() && o.videoplayersettings.settings_ios_usecustomskin!='on' )) {
                    // console.info('adding ad');
                    //advertisment
                    var aux = '<div class="vplayer-tobe"';

                    //data-source="video/test.m4v"
                    if (_c.attr('data-adsource') != undefined) {
                        aux += ' data-src="' + _c.attr('data-adsource') + '"';
                    }
                    if (_c.attr('data-adsource_ogg') != undefined) {
                        aux += ' data-sourceogg="' + _c.attr('data-adsource_ogg') + '"';
                    }
                    if (_c.attr('data-adtype') != undefined) {
                        aux += ' data-type="' + _c.attr('data-adtype') + '"';
                    }
                    if (_c.attr('data-adlink') != undefined) {
                        aux += ' data-adlink="' + _c.attr('data-adlink') + '"';
                    }
                    if (_c.attr('data-adtitle') != undefined) {
                        aux += ' data-videoTitle="' + _c.attr('data-adtitle') + '"';
                    }
                    if (_c.attr('data-adskip_delay') != undefined) {
                        aux += ' data-adskip_delay="' + _c.attr('data-adskip_delay') + '"';
                    }


                    aux += '>';


                    if (_c.attr('data-adtype') == 'inline') {

                        if(_c.find('.adSource').length>0){
                            aux+= _c.find('.adSource').eq(0).html();
                        }else{
                            aux+= _c.attr('data-adsource');
                        }
                    }

                    aux+='</div>';
                    _adSpace.show();
                    _adSpace.append(aux);
//                    console.info(aux);


                    var auxoptions = $.extend(true,{},o.videoplayersettings);;
                    auxoptions['videoWidth'] = totalWidth;
                    auxoptions['videoHeight'] = totalHeight;
                    auxoptions['is_ad'] = 'on';


                    auxoptions.autoplay= 'on';
                    auxoptions.settings_disableControls = 'on';
                    auxoptions.gallery_object = cgallery;



                    // console.info('setup ad - ',auxoptions);

                    ad_playing = true;
//                    console.info(auxoptions);

                    if(is_android()){
                        auxoptions['autoplay']='off';
                    }

                    // console.info('setup ad - ',auxoptions);

                    _adSpace.children('.vplayer-tobe').addClass('is-ad');
                    _adSpace.children('.vplayer-tobe').vPlayer(auxoptions);

//                    console.info(_adSpace);
//                    return;
                }
            }



            function setCurrVideoClass(){
                currVideo.addClass('currItem');
            }


            function play_currVideo(){

                if (_sliderCon.children().eq(currNr).get(0) && _sliderCon.children().eq(currNr).get(0).externalPauseMovie){
                    _sliderCon.children().eq(currNr).get(0).api_playMovie({
                        'call_from':'api_playMovie'
                    });
                }
            }
            function handle_stopCurrVideo(pargs) {

                var margs = {
                    'call_from':'default'
                }

                if(pargs){
                    margs = $.extend(margs,pargs);
                }

                if (!is_ios() && !is_ie8() && currNr > -1) {
                    if (_sliderCon.children().eq(currNr).get(0) && _sliderCon.children().eq(currNr).get(0).externalPauseMovie){
                        _sliderCon.children().eq(currNr).get(0).externalPauseMovie({
                            'call_from':'external_handle_stopCurrVideo() - '+margs.call_from
                        });
                    }
                }
            }




            function click_embedhandle() {
                if (embed_opened == false) {
                    _gbuttons.find('.embed-button .contentbox').animate({
                        'right': 60
                    }, {queue: false, duration: 300});

                    _gbuttons.find('.embed-button .contentbox').fadeIn('fast');
                    embed_opened = true;
                } else {
                    _gbuttons.find('.embed-button .contentbox').animate({
                        'right': 50
                    }, {queue: false, duration: 300});

                    _gbuttons.find('.embed-button .contentbox').fadeOut('fast');
                    embed_opened = false;
                }
            }
            function click_sharehandle() {
                if (share_opened == false) {
                    _gbuttons.find('.share-button .contentbox').animate({
                        'right': 60
                    }, {queue: false, duration: 300});

                    _gbuttons.find('.share-button .contentbox').fadeIn('fast');
                    share_opened = true;
                } else {
                    _gbuttons.find('.share-button .contentbox').animate({
                        'right': 50
                    }, {queue: false, duration: 300});

                    _gbuttons.find('.share-button .contentbox').fadeOut('fast');
                    share_opened = false;
                }
            }
            function gotoPrev() {
                //console.log(cgallery);

                if(o.playorder=='reverse'){
                    gotoNext();
                    return;
                }

                var tempNr = currNr - 1;
                if (tempNr < 0) {
                    tempNr = _sliderCon.children().length - 1;
                }
                gotoItem(tempNr);


                if (o.nav_type == 'thumbsandarrows') {
                    if (Math.floor((tempNr) / thumbs_per_page) != currPage) {
                        gotoPage(Math.floor((tempNr) / thumbs_per_page))
                    }

                }

            }
            function gotoNext() {
                //console.log(cgallery);

                if(o.playorder=='reverse'){
                    gotoPrev();
                    return;
                }

                var goforwardwithnext = true;
                var tempNr = currNr + 1;
                if (tempNr >= _sliderCon.children().length) {
                    tempNr = 0;


                    if(o.loop_playlist!='on'){
                        goforwardwithnext = false;
                    }

                    if(action_playlist_end){
                        action_playlist_end(cgallery);
                    }
                }


                if(goforwardwithnext){

                    gotoItem(tempNr);
                }


                if (o.nav_type == 'thumbsandarrows') {
                    if (Math.floor((tempNr) / thumbs_per_page) != currPage) {
                        gotoPage(Math.floor((tempNr) / thumbs_per_page))
                    }


                }




                if(o.nav_type_auto_scroll=='on') {
                    if (o.nav_type == 'thumbs' || o.nav_type == 'scroller') {


                        setTimeout(function () {


                            animate_to_curr_thumb();

                        }, 20);
                    }
                }
            }
            function handleVideoEnd() {
                // -- cgallery
                //console.info(ad_playing);

                //console.info('video end - ', ad_playing);
                if (ad_playing == true) {
                    _adSpace.children().animate({opacity: 0}, 300);
                    setTimeout(function() {
                        _adSpace.html('');
                        _adSpace.hide();
                    }, 400)
                    ad_playing = false;

//                    console.info(currVideo);
                    if(currVideo && typeof currVideo.get(0).externalPlayMovie!='undefined' && o.autoplayNext=='on' && is_ios()==false && is_android()==false){
                        currVideo.get(0).externalPlayMovie({
                            'call_from':'gallery - video end()'
                        });
                    }
                } else {

                    if(o.autoplayNext=='on'){

                        gotoNext();
                    }
                }


            }

            function turnFullscreen() {
                var _t = jQuery(this);
                //console.log(_t);
                return;
                _t.css({
                    'position': 'static'
                })
                _sliderMain.css({
                    'position': 'static'
                })
            }

            function mod_rotator3d_clickPreviewImg() {
                var _t = $(this);
                var ind = _t.parent().parent().children().index(_t.parent());
                //console.log(_t, ind);
                gotoItem(ind);
            }
            $.fn.turnNormalscreen = function() {
                $(this).css({
                    'position': 'relative'
                })
                _sliderMain.css({
                    'position': 'relative'
                })
                for (i = 0; i < nrChildren; i++) {
                    _sliderCon.children().eq(i).css({
                        'position': 'absolute'
                    })
                }
            }
            $.fn.vGallery.gotoItem = function(arg) {
//                gotoItem(arg);
            }
            return this;

        }); // end each
    }
    window.dzsvg_init = function(selector, settings) {

        if(typeof(settings)!="undefined" && typeof(settings.init_each)!="undefined" && settings.init_each==true ){
            var element_count = 0;
            for (var e in settings) { element_count++; }
            if(element_count==1){
                settings = undefined;
            }

            $(selector).each(function(){
                var _t = $(this);
                _t.vGallery(settings)
            });
        }else{
            $(selector).vGallery(settings);
        }
    };
    //==== deprecated
    window.zsvg_init = function(selector, settings) {
        $(selector).vGallery(settings);
    };

})(jQuery);



function vgcategories(arg){
    var ccat = jQuery(arg);
    var currCatNr = -1;
    //console.log(ccat);
    ccat.find('.gallery-precon').each(function(){
        var _t = jQuery(this);
        //console.log(_t);
        _t.css({'display' : 'none'});
        ccat.find('.the-categories-con').append('<span class="a-category">'+_t.attr('data-category')+'</span>')
    });

    ccat.find('.the-categories-con').find('.a-category').eq(0).addClass('active');
    ccat.find('.the-categories-con').find('.a-category').bind('click', click_category);
    function click_category(){
        var _t = jQuery(this);
        var ind = _t.parent().children('.a-category').index(_t);

        //console.warn('ind - ',ind);
        gotoCategory(ind);
        setTimeout(function(){
            jQuery(window).trigger('resize');
        },100);
    }

    //console.info(ccat);

    var i2 = 0;

    ccat.find('.gallery-precon').each(function(){
        var _t = jQuery(this);

        //console.info(_t);

        _t.find('.pagination-number').each(function(){
            var _t2 = jQuery(this);
            //console.log(_t2);

            var auxurl = _t2.attr('href');

            //console.log(auxurl);

            auxurl = add_query_arg(auxurl, ccat.attr('id')+'_cat', NaN);

            //console.log(auxurl);

            auxurl = add_query_arg(auxurl, ccat.attr('id')+'_cat', i2);

            _t2.attr('href', auxurl);
        })

        i2++;
    })

    var tempCat = 0;

    //console.info(window.location.href, ccat.attr('id')+'_cat', get_query_arg(window.location.href, ccat.attr('id')+'_cat'))

    if(get_query_arg(window.location.href, ccat.attr('id')+'_cat')){
        tempCat = Number(get_query_arg(window.location.href, ccat.attr('id')+'_cat'));
    }


    ccat.get(0).api_goto_category = gotoCategory;

    gotoCategory(tempCat,{
        'call_from':'init'
    });
    function gotoCategory(arg,pargs){



        var margs = {
            'call_from':'default'
        };


        if(pargs){
            margs = jQuery.extend(margs,pargs);
        }

        //console.error('gotoCategory() -', arg, margs);

        if(currCatNr>-1 && ccat.find('.gallery-precon').eq(currCatNr).find('.videogallery').eq(0).get(0)!=undefined && ccat.find('.gallery-precon').eq(currCatNr).find('.videogallery').eq(0).get(0).external_handle_stopCurrVideo!=undefined ){


            var ind = 0;
            ccat.find('.gallery-precon').each(function(){


                //console.info(jQuery(this));
                if(ind!=arg){

                    jQuery(this).find('.videogallery').eq(0).get(0).external_handle_stopCurrVideo();
                }
                ind++;
            })


        }
        ccat.find('.gallery-precon').removeClass('curr-gallery');
        ccat.find('.the-categories-con').find('.a-category').removeClass('active');
        ccat.find('.the-categories-con').find('.a-category').eq(arg).addClass('active');
        ccat.find('.gallery-precon').addClass('disabled');
        ccat.find('.gallery-precon').eq(arg).css('display','').removeClass('disabled');

        var _cach = ccat.find('.gallery-precon').eq(arg);
        var _cachg =_cach.find('.videogallery').eq(0);

        //console.info('_cach - ',_cach);
        //console.info('_cachg - ',_cachg);

        if(_cachg.get(0) && _cachg.get(0).init_settings){

            //console.info('_cachg.get(0).init_settings - ',_cachg.get(0).init_settings.autoplay);

            if(_cachg.get(0).init_settings.autoplay=='on'){
                setTimeout(function(){

                    _cachg.get(0).api_play_currVideo();
                },10);

                if(margs.call_from=='deeplink' || margs.call_from=='init'){

                    setTimeout(function(){

                        //_cachg.get(0).api_play_currVideo();
                    },1000);
                    setTimeout(function(){

                        _cachg.get(0).api_play_currVideo();
                    },1500);
                }
            }
        }

        setTimeout(function(){
            ccat.children('.dzsas-second-con').hide();
            ccat.children('.dzsas-second-con').eq(arg).show();


            //console.info(ccat.find('.gallery-precon').eq(arg));

            ccat.find('.gallery-precon').eq(arg).addClass('curr-gallery');




            currCatNr = arg;

            //console.log(ccat.find('.gallery-precon').eq(arg).find('.videogallery').eq(0));
            if(typeof ccat.find('.gallery-precon').eq(arg).find('.videogallery').eq(0).get(0) != 'undefined' && typeof ccat.find('.gallery-precon').eq(arg).find('.videogallery').eq(0).get(0).api_handleResize != 'undefined'){
                ccat.find('.gallery-precon').eq(arg).find('.videogallery').eq(0).get(0).api_handleResize();
                ccat.find('.gallery-precon').eq(arg).find('.videogallery').eq(0).get(0).api_handleResize_currVideo();
            }
            setTimeout(function(){

                jQuery(window).trigger('resize');
            },1500);


        },50);





    }
}



function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}

//-------VIDEO PLAYER
var ytplayer;
(function($) {
    $.fn.vPlayer = function(o) {

        var defaults = {
            type: 'normal'
            ,autoplay: "off"
            ,init_on: "init"
            ,autoplay_on_mobile_too_with_video_muted: "off" // -- will attempt to autoplay on mobile too, but with no sound
            ,videoWidth: 0
            ,videoHeight: 0
            ,design_scrubbarWidth: 'default'
            ,gallery_object: null // -- the gallery which invoked this
            ,parent_player: null // -- the player which invoked this
            ,design_skin: 'skin_default'
            , design_background_offsetw: 0
            , defaultvolume: 'last'
            , settings_youtube_usecustomskin: 'on'
            , settings_ios_usecustomskin: 'on'
            , cueVideo: 'on'
            , settings_disableControls: 'off'
            , is_ad: 'off'
            , settings_hideControls: 'off'
            , ad_link: ''
            , vimeo_color: 'ffffff'
            , vimeo_title: '1'
            , vimeo_avatar: '1'
            , vimeo_badge: '1'
            , vimeo_byline: '1'
            , vimeo_is_chromeless: 'off'
            , ad_show_markers: 'off' // -- show markers on the scrubbar
            , settings_suggestedQuality: 'hd720'
            , settings_currQuality: 'HD'
            , design_enableProgScrubBox: 'default'
            , settings_enableTags: 'on'
            , settings_disableVideoArray: 'off'
            , settings_makeFunctional: false
            ,settings_video_overlay: "off" //-- an overlay over the video that you can press for pause / unpause
            ,settings_big_play_btn: "off" // -- show a big play button centered on video paused
            ,video_description_style: "none" // -- choose how Video Description text shows
            , htmlContent: ''
            , extra_controls: '' // a div full of extra html
            , settings_swfPath: 'preview.swf'
            ,settings_disable_mouse_out:'off'  // -- disable the normal mouse-is-out behaviour when mouse leaves the player
            ,settings_disable_mouse_out_for_fullscreen:'off'  // -- disable the normal mouse-is-out behaviour when mouse is in the player / fullscreen
            , controls_fscanvas_bg: '#aaa'
            , controls_fscanvas_hover_bg: '#ddd'
            , touch_play_inline: 'on'
            , google_analytics_send_play_event: 'off'
            , settings_video_end_reset_time: 'on' // -- when the video has ended, reset the time to 0
            ,settings_trigger_resize: '0' // -- a force trigger resize every x ms
            ,settings_mouse_out_delay: 100
            ,settings_mouse_out_delay_for_fullscreen: 1100

            ,playfrom : 'default' // play from a index , default means that this will be decided by the data-playfrom
            ,settings_subtitle_file : '' // -- set a subtitle file
            ,responsive_ratio : 'default' // -- set a responsive ratio height/ratio 0.5 means that the player height will resize to 0.5 of the gallery width
            ,action_video_play: null
            ,action_video_view: null // -- an external function that you can set to record a view of the video - will be only cast once on play
            ,action_video_end: null // -- an external function that you can set to record a view of the video - will be only cast once on play
            ,action_video_contor_5secs: null // -- apply a function which fires once per second
            ,action_video_contor_10secs: null
            ,action_video_contor_60secs: null
            ,try_to_pause_zoomsounds_players: 'off'
            ,end_exit_fullscreen : 'on'
            ,extra_classes: ''
            ,embed_code: '' // -- the embed code for multisharer
            ,default_playbackrate: '1' // -- only for self hosted
        }


        // console.info('typeof o -' ,typeof o,o, this);
        if(typeof o =='undefined'){
            // console.info('$(this).attr(\'data-options\') -' ,$(this).attr('data-options'));
            if(typeof $(this).attr('data-options')!='undefined' && $(this).attr('data-options')!=''){
                var aux = $(this).attr('data-options');
                aux = 'window.dzsvp_self_options = ' + aux;
                eval(aux);
                o = $.extend({},window.dzsvp_self_options);

                // console.error(o);
                window.dzsvp_self_options = $.extend({},{});
            }
        }


        o = $.extend(defaults, o);

        /*
         * the way the plugin works is.
         * first the markup is analyzed
         * then the init function
         * then the init_readyVideo function
         *
         */
        this.each(function() {

            var cthis
                ,cid = ''
                ,_cmedia
            ;
            var the_player_id = ''; // -- this is set only if the player actually has an id

            var _controlsDiv;
            var videoWidth
                ,videoHeight
                ,natural_videow = 0
                ,natural_videoh = 0
                ,last_videoWidth = 0
                ,last_videoHeight = 0
                , totalWidth
                , totalHeight;
            var video;
            var aux = 0;
            var aux2 = 0;
            var is_fullscreen = 0;
            var inter_videoReadyState // interval to check for time
                ,inter_checkytadend // interval to check on when the youtube video ad has ended
                ,inter_removeFsControls // interval to remove fullscreen controls when no action is detected
                ,inter_mousedownscrubbing = 0 // interval to apply mouse down scrubbing on the video
            ;
            var lastVolume;
            var defaultVolume;
            var infoPosX;
            var infoPosY;
            var wasPlaying = false;
            var autoplay = "off";
            var fScreenControls
                , playcontrols
                , _volumeControls
                , _volumeControls_real
                , info
                , infotext
                , scrubbar
                , _scrubBg
                , _timetext
                , _vpInner = null
                , _btnhd
                , _adSkipCon = null
                , _controlsBackground = null
                , _muteControls = null
                , _adContainer = null

            ;
            var paused = true
                ,played = false
                ,ad_playing = false
                ,mouseover = false // -- the mouse is over the vplayer
                ,initial_played = false
                ,google_analytics_sent_play_event = false
                ,volume_mouse_down = false
                ,scrub_mouse_down = false
                ,controls_are_hovered = false
                ,view_sent = false
                ,fullscreen_just_pressed = false
                ,play_commited = false // -- this will apply play later on
                ,has_ad_to_play = false
                ,loop = 'off'
                ,suspend_state_2_for_loop = false
                ,inited = false
                ,vimeo_is_ready = false
            ;

            var scrubbar_moving_x = 0
                ,scrubbar_moving = false // -- touch
            ;

            var ie8paused = true;
            var totalDuration = 0;
            var time_curr = 0;
            var dataType = '';
            var queue_change_quality = '';
            var queue_goto_perc = 0; // -- seek to
            var dataSrc = '';
            var dataVideoDesc = '';

            var video_title = '';

            var dash_player = null
                ,dash_context = null
                ,dash_inter_check_sizes = 0
            ;
            //responsive vars
            var conw
                , conh
                , newconh
                , _rparent
                , _vgparent
                , currScale = 1
            ;
            var ww
                , wh
            ;
            var yt_qualArray = []
                , yt_qualCurr
                , hasHD = false
            ;

            var is360 = false;

            var arrTags = [];

            var ad_array = [];

            var bufferedLength = -1
                , bufferedWidthOffset = 0
                , volumeLength = 0
                , volumeWidthOffset = 0
                ,scrubbg_width = 0
            ;
            var time_counter_skipad = 0
                ,ad_status = 'undefined'
                ,inter_time_counter_skipad = 0
                ,inter_check_yt_iframe_ready = 0
                ,inter_clear_playpause_mistake = 0
            ;

            var action_video_end = null
                ,action_video_play = null
                ,action_video_pause = null
                ,action_video_view = null
            ;

            var busy_playpause_mistake = false
            ;

            var _controls_fs_canvas;

            var vimeo_data, vimeo_url;


            var dzsvg_translate_youcanskipto = 'you can skip to video in ';
            var translate_skipad = 'Skip Ad';

            var inter_10_secs_contor = 0
                ,inter_5_secs_contor = 0
                ,inter_60_secs_contor = 0
            ;

            var video_pattern_w_arr = []
                ,video_pattern_h_arr = []
            ;

            // -- 360 objects

            var videoTexture = null
                ,scene = null
                ,cube = null
                ,sphereMat = null
                ,cubeGeometry = null
                ,renderer = null
                ,camera = null
                ,controls = null
            ;


            var svg_aurora_play_btn = '<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100%" height="100%" viewBox="0 0 13.75 12.982" enable-background="new 0 0 13.75 12.982" xml:space="preserve"> <path d="M11.889,5.71L3.491,0.108C3.389,0.041,3.284,0,3.163,0C2.834,0,2.565,0.304,2.565,0.676H2.562v11.63h0.003 c0,0.372,0.269,0.676,0.597,0.676c0.124,0,0.227-0.047,0.338-0.115l8.389-5.595c0.199-0.186,0.326-0.467,0.326-0.781 S12.088,5.899,11.889,5.71z"/> </svg>';



            var original_scrubwidth = 0;


            if(window.dzsvg_translate_youcanskipto!=undefined){ dzsvg_translate_youcanskipto = window.dzsvg_translate_youcanskipto;  }
            if(window.dzsvg_translate_skipad){ translate_skipad = window.dzsvg_translate_skipad; }

            cthis = $(this);

            // console.info('o - ', o, cthis);

            if(cthis.attr('id')){
                the_player_id = cthis.attr('id');
            }else{

                if(cthis.attr('data-player-id')){
                    the_player_id = cthis.attr('data-player-id');
                }

            }

            if(typeof cthis.attr('id')!='undefined' && cthis.attr('id')){
                cid = cthis.attr('id');
            }else{
                cid = 'dzsvp'+parseInt(Math.random()*10000,10)
            }




            if (cthis.parent().parent().parent().hasClass('videogallery')) {
                _vgparent = cthis.parent().parent().parent();
            }


            //console.log(cthis, cthis.css('width'));

            autoplay = o.autoplay;

            //console.log(o);

            //==some sanitizing of the videoWidth and videoHeight parameters





            if (o.videoWidth == 0) {
                videoWidth = cthis.width();
            } else {
                videoWidth = o.videoWidth;
            }

            if (o.videoHeight == 0) {
                videoHeight = cthis.height();
            } else {
                videoHeight = o.videoHeight;
            }

            if(o.autoplay=='on'){
                autoplay = 'on';
            }
            // console.info('autoplay - ',autoplay);
            // console.info('o.autoplay_on_mobile_too_with_video_muted - ',o.autoplay_on_mobile_too_with_video_muted);


// console.info('is_mobile() -> ',is_mobile(), 'o.type -> ',o.type);
            if(is_mobile()){

                cthis.addClass('is-mobile');

                if(cthis.attr('data-img')){

                }else{

                    cthis.removeClass('hide-on-paused');
                }


                if(o.autoplay_on_mobile_too_with_video_muted=='on'){

                }else{

                    autoplay = 'off';
                }
                // o.autoplay='off';


            }

            // console.info('autoplay - ',autoplay);


            //console.info(cthis, o.responsive_ratio);

//            console.info(Number(o.playfrom));
            if(o.playfrom=='default'){
                if(typeof cthis.attr('data-playfrom')!='undefined' && cthis.attr('data-playfrom')!=''){
                    o.playfrom = cthis.attr('data-playfrom');
                }
            }
            if(isNaN(Number(o.playfrom))==false){
                o.playfrom = Number(o.playfrom);
            }

            if(o.is_ad=='on'){
//                console.info(cthis);

                if(o.type=='youtube'){

                }
            }

            cthis.data('embed_code',o.embed_code);


            if(o.init_on=='init'){

                init();
            }
            if(o.init_on=='scroll'){


                $(window).on('scroll.'+cid,handle_scroll);
                handle_scroll();
            }

            function handle_scroll(){
                //console.log(loaded);
                if(inited==false){




                    var st = $(window).scrollTop();
                    var cthis_ot = cthis.offset().top;

                    var wh = window.innerHeight;


                    // console.info('lets try to init video player - ', cthis_ot, st+wh);


                    if(cthis_ot<st+wh+150){
                        init();
                    }


                    return;
                }else{


                }

            }



            function init() {
                //console.info('init()', cthis);

                if (cthis.hasClass('vplayer-tobe')) {

                    //alert('ceva');
                    var _c = cthis;
                    cthis.removeClass('vplayer-tobe');
                    cthis.addClass('vplayer');

                    //console.log(autoplay, cthis);


                    if(o.settings_disableVideoArray!='on'){
                        dzsvp_players_arr.push(cthis);
                    }


                    inited = true;
                    var mainClass = '';
                    if (typeof(cthis.attr('class')) == 'string') {
                        mainClass = cthis.attr('class');
                    } else {
                        mainClass = cthis.get(0).className;
                    }
                    $(window).off('scroll.'+cid);

                    if (mainClass.indexOf('skin_') == -1) {
                        cthis.addClass(o.design_skin);
                        mainClass += ' ' + o.design_skin;
                    }


                    cthis.addClass(o.extra_classes);
                    //console.info('scrubbar width - ',cthis, o.design_scrubbarWidth);
                    //-setting skin specific vars
                    if (mainClass.indexOf('skin_aurora') > -1) {
                        o.design_skin = 'skin_aurora';
                        bufferedWidthOffset = -2;
                        volumeWidthOffset = -2;
                        if (o.design_enableProgScrubBox == 'default') {
                            o.design_enableProgScrubBox = 'on';
                        }
                        if (o.design_scrubbarWidth == 'default') {
                            o.design_scrubbarWidth = -113;
                        }
                    }
                    if (mainClass.indexOf('skin_pro') > -1) {
                        o.design_skin = 'skin_pro';
                        bufferedWidthOffset = 0;
                        volumeWidthOffset = -2;
                        if (o.design_enableProgScrubBox == 'default') {
                            o.design_enableProgScrubBox = 'off';
                        }
                        if (o.design_scrubbarWidth == 'default') {
                            o.design_scrubbarWidth = 0;
                        }
                    }
                    if (mainClass.indexOf('skin_bigplay') > -1) {
                        o.design_skin = 'skin_bigplay';
                    }
                    if (mainClass.indexOf('skin_nocontrols') > -1) {
                        o.design_skin = 'skin_nocontrols';
                    }
                    if (mainClass.indexOf('skin_bigplay_pro') > -1) {
                        o.design_skin = 'skin_bigplay_pro';
                    }
                    if (mainClass.indexOf('skin_bluescrubtop') > -1) {
                        o.design_skin = 'skin_bluescrubtop';

                        if (o.design_scrubbarWidth == 'default') {
                            o.design_scrubbarWidth = 0;
                        }
                    }
                    if (mainClass.indexOf('skin_avanti') > -1) {
                        o.design_skin = 'skin_avanti';

                        if (o.design_scrubbarWidth == 'default') {
                            o.design_scrubbarWidth = -125;
                        }
                    }
                    if (mainClass.indexOf('skin_noskin') > -1) {
                        o.design_skin = 'skin_noskin';
                    }



                    if(cthis.hasClass('skin_white')){
                        o.design_skin='skin_white';
                    }
                    if(cthis.hasClass('skin_reborn')){
                        o.design_skin='skin_reborn';
                        if (o.design_scrubbarWidth == 'default') {
                            o.design_scrubbarWidth = -312;

                        }
                    }

//                    console.info(o.design_scrubbarWidth);
                    if (o.design_scrubbarWidth == 'default') {
                        o.design_scrubbarWidth = -201;
                    }

                    if(is_mobile()){
                        cthis.addClass('disable-volume');

                        if(o.design_skin=='skin_reborn'){
                            o.design_scrubbarWidth+=65;
                        }
                    }



                    if(o.action_video_end){
                        action_video_end = o.action_video_end;
                    }
                    if(o.action_video_view){
                        action_video_view = o.action_video_view;
                    }

                    if(o.gallery_object){
                        if(o.gallery_object.get(0)){

                            cthis.get(0).gallery_object = o.gallery_object.get(0);

                        }
                    }


                    if(o.extra_controls){
                        cthis.append(o.extra_controls);
                    }

                    original_scrubwidth = o.design_scrubbarWidth;




                    if (typeof cthis.attr('data-src') == 'undefined' && typeof cthis.attr('data-source') != 'undefined' && cthis.attr('data-source') != '') {
                        cthis.attr('data-src', cthis.attr('data-source'));
                    }

                    var aux = '';
                    if(cthis.attr('data-ad-array')){
                        aux = cthis.attr('data-ad-array')
                        try{


                            // temp - try to remove slashes manually
                            aux = aux.replace(/{\\"/g,'{"');
                            aux = aux.replace(/\\":/g,'":');
                            aux = aux.replace(/:\\"/g,':"');
                            aux = aux.replace(/\\",/g,'",');
                            aux = aux.replace(/\\"}/g,'"}');
                            aux = aux.replace(/,\\"/g,',"');
                            ad_array = JSON.parse(aux);
                        }catch(err){
                            console.log('ad array parse error', aux);
                        }
                    }

                    //console.info('ad_array - ', aux);



                    if(typeof cthis.attr('data-type')=='undefined' || cthis.attr('data-type')=='' || cthis.attr('data-type')=='detect'){
                        if(typeof cthis.attr('data-src')){
                            if(String(cthis.attr('data-src')).indexOf('youtube.com/watch?')>-1){
                                cthis.attr('data-type','youtube');



                            }

                            if(String(cthis.attr('data-src')).indexOf('youtube.com/embed')>-1){
                                cthis.attr('data-type','youtube');



                            }
                            if(String(cthis.attr('data-src')).indexOf('vimeo.com/')>-1){
                                cthis.attr('data-type','vimeo');



                            }


                            if(String(cthis.attr('data-src')).indexOf('.mp4')>-1){
                                cthis.attr('data-type','normal');

                            }
                            if(String(cthis.attr('data-src')).indexOf('.mpd')>String(cthis.attr('data-src')).length-5){
                                cthis.attr('data-type','dash');

                            }

                        }
                    }



                    //console.info('type is ',cthis.attr('data-type'));

                    if (cthis.attr('data-type') == 'youtube') {
                        o.type = 'youtube';
                    }
                    if (cthis.attr('data-is-360') == 'on') {
                        is360 = true;
                    }

                    // console.info('is360 - ',is360);
                    if (cthis.attr('data-type') == 'video') {
                        o.type = 'normal';
                    }
                    if (cthis.attr('data-type') == 'dash') {
                        o.type = 'dash';
                    }
                    if (cthis.attr('data-type') == 'vimeo') {
                        o.type = 'vimeo';
                    }
                    if (cthis.attr('data-type') == 'image') {
                        o.type = 'image';
                    }
                    if (cthis.attr('data-type') == 'audio') {
                        o.type = 'audio';


                        if(is_mobile()){
                            autoplay = 'off';
                            o.autoplay = 'off';
                        }

                    }
                    if (cthis.attr('data-type') == 'inline') {
                        o.type = 'inline';
                    }
                    // console.log('ad_link1 - ' ,o.ad_link);

                    cthis.addClass('type-'+o.type);

                    if(o.type=='vimeo'){
                        if(o.vimeo_is_chromeless=='on'){
                            cthis.addClass('vimeo-chromeless');
                        }
                    }

// console.info('o.type -> ',o.type);
                    if(o.type!='normal' && o.type!='video'){
                        is360 = false;
                    }
                    // console.info('is360 -> ',is360);
                    if (cthis.attr('data-adlink')) {



                        o.ad_link = cthis.attr('data-adlink');
                    }
                    _rparent = cthis.parent();

                    // console.log('ad_link2 - ' ,o.ad_link);


                    if(cthis.attr('data-type')=='youtube'){

                        if(_global_youtubeIframeAPIReady==false && dzsvp_yt_iframe_settoload==false){
                            var head= document.getElementsByTagName('head')[0];
                            var script= document.createElement('script');
                            script.type= 'text/javascript';
                            script.src= 'https://www.youtube.com/iframe_api';
                            head.appendChild(script);
                            dzsvp_yt_iframe_settoload=true;
                        }
                    }

//                    console.info(cthis, o, o.settings_disableControls);

                    if (o.type == 'inline') {

                        //console.info(o);
                        if (o.htmlContent != '') {
                            cthis.html(o.htmlContent);
                        }

                        if (cthis.children().length > 0) {
                            var _cach = cthis.children().eq(0);
                            if (_cach.get(0) != undefined) {
                                if (_cach.get(0).nodeName == 'IFRAME') {
                                    _cach.attr('width', '100%');
                                    _cach.attr('height', '100%');
                                }
                            }
                        }
                    }


                    if(o.is_ad=='on'){
//                console.info(cthis);

                        if(o.type=='youtube' && is_touch_device() && $(window).width()<700){

                            cthis.addClass('is-touch-device type-youtube');

                        }
                        o.settings_video_overlay= 'on';

                        if(is_mobile()){

                        }
                    }





                    if(cthis.children('.vp-inner').length){

                    }else{

                        //&& o.type != 'vimeo'
                        if (o.type != 'inline' ) {

                            if(o.type=='vimeo'){

                                if(o.settings_big_play_btn=='on'){

                                    cthis.append('<div class="vp-inner '+o.design_skin+'"></div>');
                                }else{

                                    cthis.prepend('<div class="vp-inner '+o.design_skin+'"></div>');
                                }
                            }else{


                                cthis.append('<div class="vp-inner '+o.design_skin+'"></div>');
                            }

                            _vpInner = cthis.children('.vp-inner').eq(0);
                        }
                    }
                    if (cthis.attr('data-type') != undefined) {
                        dataType = cthis.attr('data-type');
                    }
                    if (cthis.attr('data-src')) {
                        dataSrc = cthis.attr('data-src');
                    } else {

                        if(o.type=='normal'){
                            if (cthis.attr('data-sourcemp4')) {
                                dataSrc = cthis.attr('data-sourcemp4');
                            }else{
                                if (cthis.attr('data-sourcem')) {
                                    dataSrc = cthis.attr('data-source');
                                }
                            }
                        }


                    }

                    // console.info('dataSrc - ',dataSrc);







                    var str_scrubbar = '<div class="scrubbar">';





                    str_scrubbar += '<div class="scrub-bg"></div><div class="scrub-buffer"></div><div class="scrub">';


                    if(o.design_skin=='skin_aurora'){

                        ;
                    }


                    str_scrubbar+='</div><div class="scrubBox"></div><div class="scrubBox-prog"></div>';

                    str_scrubbar+='</div>';



                    if(o.design_skin=='skin_pro'){

                        if( !(o.type=='vimeo' && o.vimeo_is_chromeless!='on') ){

                            // console.info("LETS ADD TIHS");
                            _vpInner.append(str_scrubbar);
                        }

                    }

                    var str_controls= '<div class="controls"></div>';

                    if(cthis.find('.cover-image').length>0){
                        //console.info('ceva',str_controls);
                        cthis.find('.cover-image').eq(0).before(str_controls);
                    }else{

                        if(_vpInner){

                            _vpInner.append(str_controls);
                            // console.info("ADDED CONTROLS");
                        }
                    }


                    setTimeout(function(){
                        cthis.addClass('cover-image-loaded');
                    },600);

                    _controlsDiv = cthis.find('.controls');




                    if(o.design_skin == 'skin_aurora'){
                        //console.info('ceva');


                        if(is_touch_device()){

                            //cthis.append('<div class="touch-play-btn"></div>');
                        }

                    }

                    //console.log('ceva');



                    //console.log(videoWidth);
                    totalWidth = videoWidth;
                    totalHeight = videoHeight;

                    //console.log(cthis, videoWidth);
                    cthis.css({
                        //'width': videoWidth,
                        //'height': videoHeight
                    })

                    if(videoWidth=='100%'){

                    }


                    if(video_title==''){

                        //console.warn(cthis.children());
                    }

                    //console.warn(video_title);

                    //if (cthis.css('position') != 'absolute' && cthis.css('position') != 'fixed') {
                    //    cthis.css('position', 'relative')
                    //}

                    //console.log(o.type);






                    if(o.design_skin == 'skin_pro' || o.design_skin == 'skin_aurora'){

                        _controlsDiv.append('<div class="controls-right"></div>');
                    }

                    var _controlsRight = _controlsDiv.find('.controls-right');

                    // console.info("o -3 ",o, o.vimeo_is_chromeless, o.type);
                    if ( (o.type != 'vimeo' || o.vimeo_is_chromeless=='on') && o.type != 'image' && o.type != 'inline') {

                        // console.info("YES");
                        var aux34 = '<div class=""></div>';


                        var struct_bg = '<div class="background"></div>';
                        var struct_playcontrols = '<div class="playcontrols"></div>';





                        var struct_timetext = '<div class="timetext"><span class="curr-timetext"></span><span class="total-timetext"></span></div>';


                        var struct_volume = '<div class="volumecontrols"></div>';
                        var struct_fscreen = '<div class="fscreencontrols"></div>';

                        aux34+='';
                        _controlsDiv.append(struct_bg);
                        _controlsDiv.append(struct_playcontrols);
                        if(o.design_skin!='skin_pro'){
                            _controlsDiv.append(str_scrubbar);
                        }
                        _controlsDiv.append(struct_timetext);

                        if(_controlsRight.length){
                            _controlsRight.append(struct_volume);
                            _controlsRight.append(struct_fscreen);
                        }else{

                            _controlsDiv.append(struct_volume);
                            _controlsDiv.append(struct_fscreen);
                        }




                        if(o.design_skin=='skin_avanti'){
                            _controlsDiv.append('<div class="mutecontrols-con"><div class="btn-mute"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="72.786px" height="72.786px" viewBox="0 0 72.786 72.786" enable-background="new 0 0 72.786 72.786" xml:space="preserve"> <g id="Capa_1"> <g> <g id="Volume_Off"> <g> <path d="M38.479,4.216c-1.273-0.661-2.819-0.594-4.026,0.188L13.858,17.718h-2.084C5.28,17.718,0,22.84,0,29.135v14.592 c0,6.296,5.28,11.418,11.774,11.418h2.088L34.46,68.39c0.654,0.421,1.41,0.632,2.17,0.632c0.636,0,1.274-0.148,1.854-0.449 c1.274-0.662,2.067-1.949,2.067-3.355V7.572C40.551,6.172,39.758,4.878,38.479,4.216z"/> </g> </g> </g> </g> <g id="only-if-mute"> <path d="M67.17,35.735l4.469-4.334c1.529-1.48,1.529-3.896-0.004-5.377c-1.529-1.489-4.018-1.489-5.553,0l-4.461,4.328 l-4.045-3.923c-1.535-1.489-4.021-1.489-5.552,0c-1.534,1.489-1.534,3.896,0,5.378l4.048,3.926l-3.63,3.521 c-1.53,1.488-1.53,3.896,0,5.386c0.767,0.737,1.771,1.112,2.774,1.112c1.005,0,2.009-0.375,2.775-1.112l3.629-3.521l4.043,3.92 c0.769,0.744,1.771,1.121,2.775,1.121c1.004,0,2.008-0.377,2.773-1.121c1.533-1.48,1.533-3.89,0-5.377L67.17,35.735z"/> </g> </svg></div></div>');

                            _muteControls = _controlsDiv.find('.mutecontrols-con').eq(0);
                        }
                    }


                    if(_controlsRight.length){

                        _controlsDiv.append(_controlsRight);
                    }



                    _timetext = cthis.find('.timetext').eq(0);


                    if(o.settings_extrahtml_before_right_controls){
                        // console.warn('aux -> ',aux);

                        // console.info("o.settings_extrahtml_before_right_controls -> > ",o.settings_extrahtml_before_right_controls,_controlsRight);
                        o.settings_extrahtml_before_right_controls = String(o.settings_extrahtml_before_right_controls).replace('{{svg_quality_icon}}',svg_quality_icon);

                        if(_controlsRight && _controlsRight.length){

                            _controlsRight.prepend(o.settings_extrahtml_before_right_controls);
                        }else{

                            if(_timetext){

                                _timetext.after(o.settings_extrahtml_before_right_controls);
                            }
                        }
                    }

                    _controlsBackground = _controlsDiv.find('.background').eq(0);

                    if (o.type == 'image') {
                        cthis.attr('data-img', cthis.attr('data-src'));


                    }

                    if(cthis.children('.vplayer-logo')){
                        cthis.append(cthis.children('.vplayer-logo'));
                    }



                    if(cthis.children('.extra-controls')){
                        if(o.design_skin=='skin_aurora'){
                            cthis.children('.extra-controls').children().each(function(){
                                var _t = $(this);

                                if(_t.html().indexOf('{{')){
                                    _t.html(String(_t.html()).replace('{{svg_embed_icon}}',svg_embed));
                                }
                                if(_t.get(0).outerHTML.indexOf('dzsvg-multisharer-but')>-1){
                                    dzsvg_check_multisharer();
                                }

                                cthis.find('.timetext').eq(0).after(_t);
                            });

                        }
                    }


                    if (typeof cthis.attr('data-img') != 'undefined' && cthis.attr('data-img')!='') {
                        _vpInner.prepend('<div class="cover-image from-type-'+o.type+'"><div class="the-div-image" style="background-image:url(' + cthis.attr('data-img') + ');"/></div>');
                    }

                    if(cthis.attr('data-loop')=='on'){
                        loop = 'on';
                    }


                    if (o.type == 'image') {

                        cthis.addClass('dzsvp-loaded');


                        if (o.ad_link) {

                            var _c = cthis.children().eq(0);
                            _c.css({'cursor': 'pointer'})
                            _c.bind('click', function() {
                                if(cthis.find('.controls').eq(0).css('pointer-events')!='none'){

                                    window.open(o.ad_link);
                                }
                            })
                        }
                        setup_skipad();
                        return;
                    }



                    // console.info(o.type);
                    if (o.type == 'inline') {

                        // if (o.settings_disableControls == 'on') {
                        //     if (o.ad_link != '') {
                        //
                        //         var _c = cthis.children().eq(0);
                        //         _c.css({'cursor': 'pointer'})
                        //         _c.bind('click', function() {
                        //             window.open(o.ad_link);
                        //         })
                        //     }
                        //
                        // }
                        cthis.find('.cover-image').bind('click',function(){


                            $(this).fadeOut('slow');
                        });
                        setup_skipad();

                        var aux_preloader = '<div class="dzsvg-preloader preloader-fountain" > <div id="fountainG_1" class="fountainG"></div> <div id="fountainG_2" class="fountainG"></div> <div id="fountainG_3" class="fountainG"></div> <div id="fountainG_4" class="fountainG"></div> </div>';

                        if(cthis.find('.cover-image').length){
                            cthis.find('.cover-image').before(aux_preloader);
                        }else{
                            cthis.append(aux_preloader);
                        }

                        cthis.addClass('dzsvp-loaded');

                        setTimeout(function(){

                            cthis.addClass('dzsvp-really-loaded');


                        },2000);


                        get_responsive_ratio({
                            'call_from' : 'init .. inline'
                        });
                        handleResize();
                        setTimeout(function(){

                            handleResize();
                        },1000);
                        $(window).on('resize', handleResize);

                        return;
                    }



                    if (o.type == 'youtube') {

                        get_responsive_ratio({
                            'call_from' : 'init .. youtube'
                        });
                    }
                    if (o.type == 'video') {

                        if (o.settings_disableControls == 'on') {
                            // -- for youtube ads we force enable the custom skin because we need to know when the video ended
                            o.cueVideo = 'on';
                            o.settings_youtube_usecustomskin='on';

                            if(is_mobile()){

                                autoplay='off';
                            }
                        }


                        // && isNaN(Number(cthis.attr('data-adskip_delay')))==false  && Number(cthis.attr('data-adskip_delay'))>0
                        if(o.is_ad=='on' && is_mobile() && cthis.attr('data-adskip_delay')){

                            // console.info(cthis);


                            // -- TBC - skip ad notice on mobile
                            cthis.appendOnce('<div class="skipad-con"></div>', '.skinad-con');
                            _adSkipCon = cthis.find('.skipad-con').eq(0);
                            _adSkipCon.html("Play the ad for the skip ad counter to appear");

                            ad_status = 'waiting_for_play';
                        }else{

                            setup_skipad();
                        }
                    }

                    if (o.type == 'vimeo') {

                        // console.info('_controlsDiv - ',_controlsDiv,_controlsDiv.children());
                        setup_skipad();
                    }
                    if (o.type == 'youtube') {
                        if (o.settings_disableControls == 'on') {
                            // -- for youtube ads we force enable the custom skin because we need to know when the video ended
                            o.cueVideo = 'on';
                            o.settings_youtube_usecustomskin='on';
                            if(is_mobile()){

                                autoplay='off';
                            }
                        }


                        if (o.ad_link != '') {

//                             if(cthis.parent().hasClass('videogallery--adSpace')){
//                                 cthis.parent().css({'cursor': 'pointer'});
//                                 cthis.parent().unbind('click');
// //                                console.info(cthis.parent());
//                                 cthis.parent().bind('click', function(e) {
//                                     if(cthis.find('.controls').eq(0).css('pointer-events')!='none') {
//                                         if($(e.target).hasClass('skipad')){
//
//                                             return ;
//
//                                         }
//
//                                         if($(e.target).parent().hasClass('volumecontrols')){
//                                             return;
//                                         }
//
//                                         //console.log(e.target);
//                                         window.open(o.ad_link);
//                                     }
//                                 })
//                             }

                        }


                        if(o.is_ad=='on' && is_mobile()  && (cthis.attr('data-adskip_delay') || videoplayersettings.settings_youtube_usecustomskin!='on') ){

                            // console.info(cthis);

                            cthis.appendOnce('<div class="skipad-con"></div>', '.skipad-con');
                            _adSkipCon = cthis.find('.skipad-con').eq(0);
                            _adSkipCon.html("Play the ad for the skip ad counter to appear");
                            ad_status = 'waiting_for_play';
                        }else{

                            setup_skipad();
                        }
                        // console.info("SETTING UP SKIP AD");
                    }
                    info = cthis.find('.info');
                    infotext = cthis.find('.infoText');

                    ////info



                    var aux = '';


                    playcontrols = cthis.find('.playcontrols');



                    aux = '<div class="playSimple">';



                    if(o.design_skin=='skin_bigplay_pro'){

                        aux+='<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="120px" height="120px" viewBox="0 0 120 120" enable-background="new 0 0 120 120" xml:space="preserve"> <path fill-rule="evenodd" clip-rule="evenodd" fill="#D0ECF3" d="M79.295,56.914c2.45,1.705,2.45,4.468,0,6.172l-24.58,17.103 c-2.45,1.704-4.436,0.667-4.436-2.317V42.129c0-2.984,1.986-4.022,4.436-2.318L79.295,56.914z M0.199,54.604 c-0.265,2.971-0.265,7.821,0,10.792c2.57,28.854,25.551,51.835,54.405,54.405c2.971,0.265,7.821,0.265,10.792,0 c28.854-2.57,51.835-25.551,54.405-54.405c0.265-2.971,0.265-7.821,0-10.792C117.231,25.75,94.25,2.769,65.396,0.198 c-2.971-0.265-7.821-0.265-10.792,0C25.75,2.769,2.769,25.75,0.199,54.604z M8.816,65.394c-0.309-2.967-0.309-7.82,0-10.787 c2.512-24.115,21.675-43.279,45.79-45.791c2.967-0.309,7.821-0.309,10.788,0c24.115,2.512,43.278,21.675,45.79,45.79 c0.309,2.967,0.309,7.821,0,10.788c-2.512,24.115-21.675,43.279-45.79,45.791c-2.967,0.309-7.821,0.309-10.788,0 C30.491,108.672,11.328,89.508,8.816,65.394z"/> </svg>';
                    }
                    if(o.design_skin=='skin_aurora' || o.design_skin=='skin_bigplay' || o.design_skin=='skin_avanti' || o.design_skin=='skin_default' || o.design_skin=='skin_pro'|| o.design_skin=='skin_white'){

                        aux+=svg_aurora_play_btn;
                    }




                    aux+='</div><div class="pauseSimple">';


                    if(o.design_skin=='skin_aurora' || o.design_skin=='skin_pro' || o.design_skin=='skin_bigplay'|| o.design_skin=='skin_avanti'|| o.design_skin=='skin_default'|| o.design_skin=='skin_white'){

                        aux+='<svg version="1.1" id="Layer_2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="13.75px" height="12.982px" viewBox="0 0 13.75 12.982" enable-background="new 0 0 13.75 12.982" xml:space="preserve"> <g> <path d="M5.208,11.982c0,0.55-0.45,1-1,1H3c-0.55,0-1-0.45-1-1V1c0-0.55,0.45-1,1-1h1.208c0.55,0,1,0.45,1,1V11.982z"/> </g> <g> <path d="M12.208,11.982c0,0.55-0.45,1-1,1H10c-0.55,0-1-0.45-1-1V1c0-0.55,0.45-1,1-1h1.208c0.55,0,1,0.45,1,1V11.982z"/> </g> </svg> ';
                    }


                    aux+='</div>';

                    playcontrols.append(aux);





                    scrubbar = cthis.find('.scrubbar');
                    // scrubbar.append(aux_scrub);

                    _scrubBg = scrubbar.children('.scrub-bg');
//                    console.info(scrubbar, _scrubBg);





                    _volumeControls = cthis.find('.volumecontrols');
                    _volumeControls_real = cthis.find('.volumecontrols');

                    aux = '<div class="volumeicon">';



                    if(o.design_skin=='skin_aurora' || o.design_skin=='skin_default' || o.design_skin=='skin_white'){
                        aux+='<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="10px" height="12px" viewBox="0 0 10 12" enable-background="new 0 0 10 12" xml:space="preserve"> <path fill-rule="evenodd" clip-rule="evenodd" fill="#200C34" d="M8.475,0H7.876L5.323,1.959c0,0-0.399,0.667-1.157,0.667H1.454 c0,0-1.237,0.083-1.237,1.334v3.962c0,0-0.159,1.334,1.277,1.334h2.553c0,0,0.877,0.167,1.316,0.667l2.513,1.959l0.638,0.083 c0,0,0.678,0,0.678-0.667V0.667C9.193,0.667,9.153,0,8.475,0z"/> </svg>';
                    }


                    aux+='</div><div class="volume_static">';


                    if(o.design_skin=='skin_default'){
                        aux+='<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="14px" viewBox="0 5 24 14" enable-background="new 0 5 24 14" xml:space="preserve"> <path d="M0,19h24V5L0,19z M22,17L5,17.625l12-6.227l5-2.917V17z"/> </svg>';
                    }
                    if(o.design_skin=='skin_reborn' || o.design_skin=='skin_white'){
                        for(var i2=0;i2<10;i2++){
                            aux+='<div class="volbar"></div>';
                        }
                    }


                    aux+='</div><div class="volume_active">';

                    if(o.design_skin=='skin_default'){
                        aux+='<svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="24px" height="14px" viewBox="0 5 24 14" enable-background="new 0 5 24 14" xml:space="preserve"> <path d="M0,19h24V5L0,19z M22,17L22,17V8.875V8.481V17z"/> </svg>';
                    }


                    if(o.design_skin=='skin_aurora'){
                        ;
                    }


                    aux+='</div><div class="volume_cut"></div>';


                    if(o.design_skin=='skin_reborn') {
                        aux+='<div class="volume-tooltip">VOLUME: 100</div>';
                    }

                    _volumeControls.append(aux);

                    fScreenControls = cthis.find('.fscreencontrols');

                    aux = '<div class="full">';



                    if(o.design_skin=='skin_rebornTRYDIFFERENT') {
                        aux += '<svg class="for-fullscreen-inactive" version="1.1" baseProfile="tiny" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="14px" viewBox="0 0 14 14" overflow="auto" xml:space="preserve"> <path fill-rule="evenodd" fill="none" d="M14,14V9h-1v4H9v1H14z M0,14h5v-1H1V9H0V14z M14,0H9v1h4v4h1V0z M0,0v5h1V1h4V0H0z"/> </svg>';

                        aux+='<svg class="for-fullscreen-active" version="1.1" baseProfile="tiny" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="14px" height="14px" viewBox="0 0 14 14" xml:space="preserve"> <path fill-rule="evenodd" fill="none" d="M5,5V0H4v4H0v1H5z M9,5h5V4h-4V0H9V5z M5,9H0v1h4v4h1V9z M9,9v5h1v-4h4V9H9z"/> </svg>';
                    }

                    if(o.design_skin=='skin_aurora' || o.design_skin=='skin_default' || o.design_skin=='skin_white') {
                        aux += '<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="16px" height="16px" viewBox="0 0 16 16" enable-background="new 0 0 16 16" xml:space="preserve"> <g id="Layer_3"> <polygon fill="#FFFFFF" points="2.404,2.404 0.057,4.809 0.057,0 4.751,0 "/> <polygon fill="#FFFFFF" points="13.435,2.404 11.03,0.057 15.839,0.057 15.839,4.751 "/> <polygon fill="#FFFFFF" points="2.404,13.446 4.809,15.794 0,15.794 0,11.1 "/> <polygon fill="#FFFFFF" points="13.435,13.446 15.781,11.042 15.781,15.851 11.087,15.851 "/> </g> <g id="Layer_2"> <rect x="4.255" y="4.274" fill="#FFFFFF" width="7.366" height="7.442"/> </g> </svg>';

                    }


                    aux+='</div><div class="fullHover"></div>';



                    if(o.design_skin=='skin_reborn') {
                        aux+='<div class="full-tooltip">FULLSCREEN</div>';
                    }


                    fScreenControls.append(aux);


                    if (o.design_skin == 'skin_pro' || o.design_skin == 'skin_bigplay') {
                        playcontrols.find('.pauseSimple').eq(0).append('<div class="pause-part-1"></div><div class="pause-part-2"></div>');
                        fScreenControls.find('.full').eq(0).append('<canvas width="15" height="15" class="fullscreen-button"></canvas>');


                        //console.log(fScreenControls.find('.full').eq(0));

                        _controls_fs_canvas=fScreenControls.find('.full').eq(0).find('canvas.fullscreen-button').eq(0)[0];
                        if( (!is_ie() || (is_ie() && version_ie()>8)) && _controls_fs_canvas!=undefined){
//                            console.info(o.controls_fscanvas_bg);
                            draw_fs_canvas(o.controls_fscanvas_bg);
                            $(_controls_fs_canvas).bind('mouseover', handleMouseover);
                            $(_controls_fs_canvas).bind('mouseout', handleMouseout);
                        }
                    }




                    if (_c.find('.videoDescription').length > 0) {
                        dataVideoDesc = _c.find('.videoDescription').html();
                        _c.find('.videoDescription').remove();
                    }



                    if (cthis.attr('data-videoTitle')) {
                        if(_vpInner) {
                            _vpInner.append('<div class="video-description"></div>')

                        }


                        cthis.find('.video-description').eq(0).append('<div class="video-title">' + cthis.attr('data-videoTitle') + '</div>');
                        if (o.video_description_style=='show-description' && dataVideoDesc) {
                            cthis.find('.video-description').eq(0).append('<div class="video-subdescription">' + dataVideoDesc + '</div>');
                        }
                        // cthis.find('.video-subdescription').eq(0).css('width', (0.7 * videoWidth));
                        video_title = cthis.attr('data-videoTitle');
                    }

                    // console.info('dataVideoDesc -> ',dataVideoDesc);



                    if(o.type=='vimeo'){


                        if(_global_vimeoIframeAPILoading){

                        }else{




                            try{



                                var head= document.getElementsByTagName('head')[0];
                                var script= document.createElement('script');
                                script.type= 'text/javascript';
                                script.src= 'https://player.vimeo.com/api/player.js';
                                head.appendChild(script);

                                // console.info("ADDED");


                                _global_vimeoIframeAPILoading = true;

                                _global_vimeoIframeAPILoading_inter = setInterval(function(){

                                    if(window.Vimeo){
                                        clearInterval(_global_vimeoIframeAPILoading_inter);
                                        _global_vimeoIframeAPIReady=true;


                                        if(_cmedia){
                                            // console.info(_cmedia);




                                            var player = new Vimeo.Player(_cmedia);


                                            player.on('play', function() {
                                                playMovie_visual()
                                            });

                                            player.on('pause', function() {
                                                pauseMovie_visual();
                                            });

                                        }

                                    }
                                    // console.info(_global_vimeoIframeAPIReady);
                                },500);

                            }catch(err){
                                console.error(err);
                            }
                        }
                    }


                    if(cthis.get(0)){
                        //cthis.get(0).fn_change_mainColor = fn_change_mainColor; cthis.get(0).fn_change_mainColor('#aaa');
                        cthis.get(0).fn_change_color_highlight = fn_change_color_highlight; //cthis.get(0).fn_change_mainColor('#aaa');

                        cthis.get(0).api_handleResize = handleResize;
                        cthis.get(0).api_seek_to_perc = seek_to_perc;

                        cthis.get(0).api_currVideo_refresh_fsbutton = draw_fs_canvas;
                        cthis.get(0).api_reinit_cover_image = reinit_cover_image;
                        cthis.get(0).api_restart_video = restart_video;



                        cthis.get(0).api_ad_end = ad_end;
                        cthis.get(0).api_action_set_video_end = function(arg){
                            action_video_end = arg;
                        };
                        cthis.get(0).api_action_set_video_view = function(arg){
                            action_video_view = arg;
                        };
                        cthis.get(0).api_action_set_video_play = function(arg){
                            action_video_play = arg;
                        };
                        cthis.get(0).api_action_set_video_pause = function(arg){
                            action_video_pause = arg;
                        };

                        //console.log('ceva');
                    }








                    if(o.settings_big_play_btn=='on'){
                        var auxbpb = '<div class="big-play-btn">';
                        auxbpb+=svg_aurora_play_btn;
                        auxbpb+='</div>';

                        if(cthis.find('.controls').length){
                            cthis.find('.controls').before(auxbpb);
                        }else{
                            cthis.append(auxbpb);
                        }


                        // console.info("cthis.find('.big-play-btn') - ",cthis.find('.big-play-btn'))
                        // cthis.on('click','.big-play-btn', function(){
                        //     console.info("CEVA");
                        // });
                        // cthis.on('click','.big-play-btn', click_videoOverlay);
                        cthis.find('.big-play-btn').on('click', click_videoOverlay);

                        cthis.addClass('has-big-play-btn');
                    }else{

                        cthis.addClass('not-has-big-play-btn');
                    }


                    //===setup player colors





                    //console.log(cthis, o.cueVideo, o.type);

                    //===if cueVideo is not on then, init_readyControls on click


                    //console.log("FIREFOX YOU BASTARD");




                    if(o.cueVideo=='on' || ( ( !is_ios() || o.settings_ios_usecustomskin=='on') && (o.type=='normal'||o.type=='youtube'||o.type=='vimeo') )){

                        if(o.type=='youtube'){
                            inter_check_yt_iframe_ready = setInterval(check_if_yt_iframe_ready, 100);
                        }else{
                            init_readyControls();
                        }
                    }else{

                        resizePlayer(videoWidth, videoHeight);
                        cthis.bind('click', init_readyControls);

                        cthis.addClass('dzsvp-loaded');

                    }

                    if (o.cueVideo != 'on') {


                        //--------------normal
                        if (!is_ie8() && ( !is_ios() || o.settings_ios_usecustomskin=='on')) {
                        }


                    } else {
                        //console.log(o.type);

                    }



                    if (o.settings_enableTags == 'on') {
                    }

                    setInterval(check_one_sec, 1000);
                    check_one_sec();


                    handleResize();
                }

                // console.info('o.action_video_contor_10secs - ',o.action_video_contor_10secs);
                if(inter_10_secs_contor==0 && o.action_video_contor_10secs){
                    inter_10_secs_contor = setInterval(count_10secs, 10000);
                }
                if(inter_60_secs_contor==0 && o.action_video_contor_60secs){
                    inter_60_secs_contor = setInterval(count_60secs, 30000);
                }

                // console.info(o,inter_5_secs_contor,o.action_video_contor_5secs);
                if(inter_5_secs_contor==0 && o.action_video_contor_5secs){
                    inter_5_secs_contor = setInterval(count_5secs, 3000);
                    setTimeout(function(){

                        count_5secs();
                    },500)
                }



                //console.info(cthis, autoplay, o.autoplay);
            }


            function check_one_sec(){

                // console.info('check_on_sec',ad_playing,paused,ad_array, ad_array.length, typeof ad_array);

                if(ad_playing==false && (paused==false||is_mobile()) ){
                    // console.info('ad_array - ',ad_array);

                    if(typeof ad_array =='object' && ad_array.length>0){

                        for(var i2 in ad_array){
                            // console.info('ad vars - ',time_curr, ad_array[i2].time, totalDuration);
                            if(totalDuration && time_curr >= ad_array[i2].time * totalDuration){

                                ad_setup(i2);

                            }
                        }
                    }

                }

                if (o.settings_enableTags == 'on') {
                    check_tags();
                }

            }

            function ad_setup(arg){

                var source = ad_array[arg].source;
                var type = ad_array[arg].type;
                var skip_delay = ad_array[arg].skip_delay;
                var ad_link = ad_array[arg].ad_link;
                var ad_time = ad_array[arg].time;


                ad_array.splice(arg, 1);

                cthis.appendOnce('<div class="ad-container"></div>');

                _adContainer = cthis.find('.ad-container').eq(0);


                // console.info('adding ad');
                //advertisment
                var aux = '<div class="vplayer-tobe"';

                //data-source="video/test.m4v"
                if(type!='inline'){

                    aux += ' data-src="' + source + '"';
                }

                if (type) {
                    aux += ' data-type="' + type + '"';
                }

                if (ad_link) {
                    aux += ' data-adlink="' + ad_link + '"';
                }

                if (skip_delay) {
                    aux += ' data-adskip_delay="' + skip_delay + '"';
                }
                aux += '>';


                if(type=='inline'){
                    // aux+='<div class="adSource">' + source + '</div>';
                    aux+=source;
                }



                aux+='</div>';
                _adContainer.show();
                _adContainer.append(aux);
//                    console.info(aux);


                var auxoptions = {};


                auxoptions.design_skin= o.design_skin;
                auxoptions.autoplay= 'on';
                auxoptions.settings_disableControls = 'on';
                auxoptions.is_ad = 'on';
                auxoptions.parent_player = cthis;
                // auxoptions.gallery_object = o.gallery_object;



                ad_playing = true;



                if(ad_time<0.1 && is_mobile()){
                    // this is invisible

                    _adContainer.children('.vplayer-tobe').addClass('mobile-pretime-ad');
                    cthis.addClass('pretime-ad-setuped');
                    if(o.gallery_object){
                        o.gallery_object.addClass('pretime-ad-setuped');
                    }
                }



                console.info('setup ad - ',auxoptions);

                _adContainer.children('.vplayer-tobe').addClass('is-ad');
                _adContainer.children('.vplayer-tobe').vPlayer(auxoptions);


                if(o.gallery_object){
                    if(o.gallery_object.get(0) && o.gallery_object.get(0).api_ad_block_navigation){

                        o.gallery_object.get(0).api_ad_block_navigation();

                    }
                }





//                    console.info(_adSpace);
//                    return;


                setTimeout(function(){

                    cthis.addClass('ad-playing');
                },100);
                ad_playing=true;
                pauseMovie();
            }

            function ad_end(){

                if(_adContainer.children().get(0) && _adContainer.children().get(0).api_destroy_listeners){

                    _adContainer.children().get(0).api_destroy_listeners();
                }

                if(is_mobile()==false){

                }
                playMovie();
                cthis.addClass('ad-transitioning-out');



                if(o.gallery_object){
                    if(o.gallery_object.get(0) && o.gallery_object.get(0).api_ad_unblock_navigation){

                        o.gallery_object.get(0).api_ad_unblock_navigation();

                    }
                }

                setTimeout(function(){

                    cthis.removeClass('ad-playing');
                    cthis.removeClass('ad-transitioning-out');
                    _adContainer.children().remove();
                    ad_playing=false;
                },300)
            }

            function count_10secs(){
                if(o.action_video_contor_10secs && cthis.hasClass('is-playing')){
                    o.action_video_contor_10secs(cthis,video_title);
                }
            }
            function count_60secs(){
                if(o.action_video_contor_60secs && cthis.hasClass('is-playing')){
                    o.action_video_contor_60secs(cthis,video_title);
                }
            }
            function count_5secs(){
                if(o.action_video_contor_5secs){
                    o.action_video_contor_5secs(cthis,video_title);
                }
            }

            function restart_video(){


                //console.info(o.type);
                if(o.type=='video'){
                    seek_to_perc(0);
                }
                if(o.type=='vimeo'){

                    seek_to_perc(0);
                }
                if(o.type=='youtube'){



                    if (video && video.pauseVideo) {
                        //console.info(dataSrc);
                    }

                }

                reinit_cover_image();
            }

            function check_if_yt_iframe_ready(){

//                console.info(o.type);
                if( (window.YT && window.YT.Player ) || _global_youtubeIframeAPIReady){
                    init_readyControls();
                    clearInterval(inter_check_yt_iframe_ready);
                }
            }

            function setup_skipad(pargs){


                var margs = {
                    'call_from': 'default'
                }

                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                if(ad_status=='first_played'){
                    return false;
                }
                //console.info('setup_skipad()');
                if (o.settings_disableControls == 'on') {
                    var skipad_timer = 0;

                    if(o.type=='image' || o.type=='inline'){
                        skipad_timer=0;
                    }
                    if(o.type=='normal' || o.type=='youtube'){
                        skipad_timer=1001;
                    }

                    cthis.appendOnce('<div class="skipad-con"></div>', '.skipad-con');
                    _adSkipCon = cthis.find('.skipad-con').eq(0);

                    if(typeof cthis.attr('data-adskip_delay')!='undefined'){
                        skipad_timer = Number(cthis.attr('data-adskip_delay')) ;
                    }
                    //console.info(skipad_timer);

//                    console.info(cthis, cthis.attr('data-adskip_delay'), skipad_timer);
                    time_counter_skipad = skipad_timer;
//                    console.info(cthis, time_counter_skipad);
                    if(skipad_timer!=1001){
                        setTimeout(function(){
//                            console.info(cthis.find('.skipad-con').eq(0))
                            time_counter_skipad=0;
                            _adSkipCon.html('<div class="skipad">'+translate_skipad+'</div>');
                            _adSkipCon.children('.skipad').bind('click', function() {
                                handleVideoEnd();
                            })
                        }, skipad_timer*1000);

                        if(skipad_timer>0){
                            inter_time_counter_skipad = setInterval(tick_counter_skipad, 1000);
                        }
                    }


                }

                ad_status = 'first_played';
            }
            function tick_counter_skipad(){

//                console.info(cthis, time_counter_skipad);
                if(time_counter_skipad>0){
                    time_counter_skipad= time_counter_skipad-1;
                    if(_adSkipCon){
                        _adSkipCon.html(dzsvg_translate_youcanskipto + time_counter_skipad);
                    }

                }else{
                    clearInterval(inter_time_counter_skipad);
                }
            }
            function draw_fs_canvas(argcol){

//                console.info(o.design_skin, argcol);
                if(o.design_skin!='skin_pro'){
                    return;
                }
                var ctx=_controls_fs_canvas.getContext("2d");
                var ctx_w = _controls_fs_canvas.width;
                var ctx_h = _controls_fs_canvas.height;
                var ctx_pw = ctx_w/100;
                var ctx_ph = ctx_w/100;
                //console.log(ctx_pw, c.width);
                ctx.fillStyle= argcol;
                var borderw = 30;
                ctx.fillRect(25*ctx_pw,25*ctx_ph,50*ctx_pw,50*ctx_ph);
                ctx.beginPath();
                ctx.moveTo(0*ctx_pw,0*ctx_ph);
                ctx.lineTo(0*ctx_pw,borderw*ctx_ph);
                ctx.lineTo(borderw*ctx_pw,0*ctx_ph);
                ctx.fill();
                ctx.moveTo(0*ctx_pw,100*ctx_ph);
                ctx.lineTo(0*ctx_pw,(100-borderw)*ctx_ph);
                ctx.lineTo(borderw*ctx_pw,100*ctx_ph);
                ctx.fill();
                ctx.moveTo((100)*ctx_pw,(100)*ctx_ph);
                ctx.lineTo((100-borderw)*ctx_pw,(100)*ctx_ph);
                ctx.lineTo((100)*ctx_pw,(100-borderw)*ctx_ph);
                ctx.fill();
                ctx.moveTo((100)*ctx_pw,(0)*ctx_ph);
                ctx.lineTo((100-borderw)*ctx_pw,(0)*ctx_ph);
                ctx.lineTo((100)*ctx_pw,(borderw)*ctx_ph);
                ctx.fill();
            }
            function fn_change_color_highlight(arg){
                cthis.find('.scrub').eq(0).css({
                    'background' : arg
                })
                cthis.find('.volume_active').eq(0).css({
                    'background' : arg
                })
                cthis.find('.hdbutton-hover').eq(0).css({
                    'color' : arg
                })
            }
            function check_tags() {
                var roundTime = Number(time_curr);


                //console.log(arrTags.length);
                if (arrTags.length == 0) {
                    return;
                }

                arrTags.removeClass('active');
                arrTags.each(function() {
                    var _t = $(this);
                    //console.log(_t);
                    if (Number(_t.attr('data-starttime')) <= roundTime && Number(_t.attr('data-endtime')) >= roundTime) {
                        _t.addClass('active');
                    }
                })
                //jQuery('.dzstag[data-starttime=' + roundTime + ']').addClass('active');
            }


            function init_readyControls() {
                // console.warn('init_readyControls() - ', cthis);



                var _c = cthis;
                _c.unbind();

                if(_c.attr('data-type')=='youtube'){

                    dataSrc =  String(dataSrc).replace('https://youtu.be/','');
                }
                if(_c.attr('data-type')=='youtube' && String(dataSrc).indexOf('youtube.com/watch?') > -1){
                    var auxa = String(dataSrc).split('youtube.com/watch?v=');
                    //console.info(auxa);
                    dataSrc = auxa[1];
                    if(auxa[1].indexOf('&')>-1){
                        var auxb = String(auxa[1]).split('&');
                        dataSrc = auxb[0];
                    }
                }
                if(_c.attr('data-type')=='youtube' && String(dataSrc).indexOf('youtube.com/embed') > -1){
                    var auxa = String(dataSrc).split('youtube.com/embed/');
                    //console.info(auxa);
                    dataSrc = auxa[1];
                }


                if(_c.attr('data-type')=='vimeo' && String(dataSrc).indexOf('vimeo.com/') > -1){
                    var auxa = String(dataSrc).split('vimeo.com/');
                    //console.info(auxa);
                    dataSrc = auxa[1];
                }



                //console.log(cthis.find('.preview'))









                //console.info('type is ', o.type, dataSrc);



                // -- ios video setup

                //console.info(o.settings_ios_usecustomskin!='on',is_ios() )
                if (o.settings_ios_usecustomskin!='on' && is_ios()) {
                    var str_poster = '';


                    console.warn("WE ARE HERE IN DEFAULT SKIN IOS");

                    if (cthis.attr('data-img')) {
                        str_poster = ' poster="'+cthis.attr('data-img')+'"';
                    }

                    if (o.type == 'normal') {

                        console.warn("HERE we will setup ios not custom skin");
                        // _c.prepend('<video class="the-video" width="100%" height="100%" controls preload="metadata" '+str_poster+'></video>');
                        _vpInner.prepend('<video class="the-video" width="100%" height="100%" crossorigin="anonymous" controls preload="metadata" '+str_poster+'></video>');
                        //_c.children().eq(0).attr('width', videoWidth);
                        //_c.children().eq(0).attr('height', videoHeight);
                        if (dataSrc) {
                            _c.find('video.the-video').eq(0).append('<source src="' + dataSrc + '"/>');
                        }
                    }
                    if (o.type == 'audio') {
                        _c.prepend('<audio controls preload '+str_poster+'></audio>');
                        _c.children().eq(0).attr('width', videoWidth);
                        _c.children().eq(0).attr('height', videoHeight);
                        if (_c.attr('data-sourcemp3') != undefined) {
                            _c.children().eq(0).append('<source src="' + _c.attr('data-src') + '" type="audio/mp3" style="width:100%; height:100%;"/>');
                        }
                    }
                    if (o.type == 'youtube') {
                        o.type = 'youtube';
                        _c.children().remove();
                        _c.append('<iframe src="//www.youtube.com/embed/' + dataSrc + '?rel=0&showinfo=0" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowfullscreen style="width:100%; height:100%;"></iframe>');
                        //_c.attr('data-ytid', aux);
                    }
                    if (o.type == 'vimeo') {
                        _c.children().remove();
                        var src = dataSrc;
                        _c.append('<iframe width="100%" height="100%" src="//player.vimeo.com/video/' + src + '" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen style=""></iframe>');

                    }



                    _vpInner.find('.controls').remove();

                    cthis.find('.cover-image').remove();
                    //console.log(cthis, cthis.find('.cover-image'));
                    //cthis.find('.cover-image').fadeOut('slow');
                    /*
                     if(typeof cthis.find('.cover-image').get(0)!='undefined'){
                     cthis.find('.cover-image').get(0).addEventListener('touchstart', function(){
                     console.log('ceva');
                     }, false);
                     }
                     */

                    // console.info("iOS so we dont medley", o.responsive_ratio);


                    if(o.responsive_ratio=='default'){
                        // console.info("YES");

                        get_responsive_ratio({
                            'call_from' : 'init_readyControls .. ios'
                        });;
                        o.responsive_ratio=0.5625;
                    }

                    cthis.addClass('dzsvp-loaded');

                    handleResize();
                    //return;//our job on the iphone / ipad has been done, we exit the function.
                }



                // -- normal
                if (!is_ie8() && ( !is_ios() || o.settings_ios_usecustomskin=='on')) {

                    //--normal video on modern browsers
                    if (o.settings_enableTags == 'on') {
                        cthis.find('.dzstag-tobe').each(function() {
                            var _t = $(this);
                            var auxhtml = _t.html();
                            var w = 100;
                            var h = 100;
                            var acomlink = '';
                            if (_t.attr('data-width') != undefined) {
                                w = _t.attr('data-width');
                            }
                            if (_t.attr('data-height') != undefined) {
                                h = _t.attr('data-height');
                            }
                            if (_t.attr('data-link') != undefined) {
                                acomlink = '<a href="' + _t.attr('data-link') + '"></a>';
                            }

                            _t.html('');
                            _t.css({'left': (_t.attr('data-left') + 'px'), 'top': (_t.attr('data-top') + 'px')});
                            //console.log(_t);
                            _t.append('<div class="tag-box" style="width:' + w + 'px; height:' + h + 'px;">' + acomlink + '</div>');
                            _t.append('<span class="tag-content">' + auxhtml + '</span>');
                            _t.removeClass('dzstag-tobe').addClass('dzstag');
                            //_t.remove();
                        })
                        arrTags = cthis.find('.dzstag');
                    }
                    aux = '';
                    if (o.type == 'audio') {
                        if (_c.attr('data-audioimg') != undefined) {
                            aux = '<div style="background-image:url(' + _c.attr('data-audioimg') + ')" class="div-full-image from-type-audio"/>';
                            _vpInner.prepend(aux);
                        }
                    }
                    //console.log(_c);
                    if (o.type == 'normal') {

                        //console.info(o.cueVideo);
                        //setup_local_video();



                        // console.info(o);
                        //console.info("SETUP VIDEO")
                        if(o.cueVideo!='on'){
                            // o.autoplay = 'off';
                            autoplay = 'off';


                            setup_local_video({
                                'preload':'metadata'
                            })
                        }else{

                            setup_local_video();
                        }
                    }






                    // console.info("NORMAL , type - ",o.type)
                    // ---type audio
                    if (o.type == 'audio') {
                        var aux = '<audio class="the-video" controls preload="metadata"';

                        aux += '>';



                        if (cthis.attr('data-src')) {
                            //console.log(_c.attr('data-sourcemp4'));
                            aux+='<source src="' + _c.attr('data-src') + '" type="audio/mp3"/>'

                        }


                        aux+='</audio>';
                        _vpInner.prepend(aux);
                        video = _vpInner.find('.the-video').get(0);
                        _cmedia = _vpInner.find('.the-video').get(0);


                        if(is_ios()){
                            video.src = _c.attr('data-src');
                        }



                        // console.info("cthis.attr('data-src') -> ",cthis.attr('data-src'), $(video), ' aux - ',aux);
                        if (cthis.attr('data-src')) {
                            //console.log(_c.attr('data-sourcemp4'));
                            // $(video).eq(0).append('<source src="' + _c.attr('data-src') + '" type="audio/mp3"/>');

                        }
                        // return false;
                        if (_c.attr('data-sourceogg') != undefined) {
                            $(video).eq(0).append('<source src="' + _c.attr('data-sourceogg') + '" type="audio/ogg"/>');
                        }
                        if (_c.attr('data-sourcewav') != undefined) {
                            $(video).eq(0).append('<source src="' + _c.attr('data-sourcewav') + '" type="audio/wav"/>');
                        }


                        // console.info(_cmedia);
                    }
                    //console.info(o.type,o.settings_youtube_usecustomskin)



                    // --- type youtube
                    if (o.type == 'youtube') {
                        o.type = 'youtube';
                        //console.log(o.settings_youtube_usecustomskin)

                        //console.info(is_android(), o.settings_youtube_usecustomskin)



                        // console.info(" settings_youtube_usecustomskin - ", o.settings_youtube_usecustomskin);

                        // ---- default skin youtube
                        if (o.settings_youtube_usecustomskin != 'on' || ( o.settings_ios_usecustomskin!='on' && is_ios() )) {




                            // console.warn("ENTER DEFAULT SKIN");

                            _vpInner.children(':not(.cover-image)').remove();
                            var aux = 'ytplayer' + dataSrc;
                            var param_autoplay = 0;
                            //console.log(o);
                            if(o.autoplay=='on'){
                                param_autoplay = 1
                            }

                            //_c.append('<iframe type="text/html" style="position:relative; top:0; left:0; width:100%; height:100%;" src="//www.youtube.com/embed/' + dataSrc + '?modestbranding=1&rel=0&showinfo=0'+param_autoplay+'" frameborder="0" allowfullscreen></iframe>');
                            _c.attr('data-ytid', aux);


                            _vpInner.prepend('<span class="cmedia-con"><span id="the-media-'+cid+'"></span></span>');

                            //console.info(param_autoplay, o.autoplay, o);
                            if(o.cueVideo=='off'){
                                // o.autoplay='off';
                                autoplay = 'off';
                            }



                            // -- youtube default skin
                            // default yt skin
                            video = new YT.Player('the-media-'+cid, {
                                height: '100%',
                                width: '100%',
                                playerVars: { 'autoplay': param_autoplay, controls: 1, 'showinfo': 0, 'playsinline' : 1, rel:0, autohide: 0, wmode: 'transparent', iv_load_policy: '3', enablejsapi : 1},//, 'playsinline' : 0, enablejsapi : 1
                                videoId: dataSrc,

                                suggestedQuality: o.settings_suggestedQuality,
                                events: {
                                    'onReady': yt_onPlayerReady,
                                    'onStateChange': yt_onPlayerStateChange
                                }
                            });
                            _cmedia = video;

                        } else {

                            //ytplayer= document.getElementById("flashcontent");
                            //ytplayer.loadVideoById('L7ANahx7aF0')

                            _vpInner.prepend('<span class="cmedia-con"><span id="the-media-'+(cid)+'"></span></span>');


                            if(o.cueVideo=='off'){
                                // o.autoplay='off';
                                autoplay = 'off';
                            }
                            //console.info(o.settings_suggestedQuality);



                            var playfrom = '';


                            if(o.playfrom!='default') {

//                    console.info(the_player_id, o.playfrom);

                                if (o.playfrom == 'last' && the_player_id != '') {
                                    if (typeof Storage != 'undefined') {

                                        if (typeof localStorage['dzsvp_' + the_player_id + '_lastpos'] != 'undefined') {

                                            playfrom = (Number(localStorage['dzsvp_' + the_player_id + '_lastpos']))

                                            // console.error("GOT HERE");
                                        }
                                    }
                                }

                                if (isNaN(Number(o.playfrom)) == false) {

                                    playfrom = Number(o.playfrom);
                                }
                            }

                            // console.info('playfrom - ',playfrom);
                            // console.info('o.playfrom - ',o.playfrom);
                            // console.info('the_player_id - ',the_player_id, 'dzsvp_' + the_player_id + '_lastpos');




                            // console.info('quality - ',o.settings_suggestedQuality);
                            video = new YT.Player('the-media-'+cid, {
                                height: '100%',
                                width: '100%',
                                playerVars: { 'autoplay': 0, controls: 0, 'showinfo': 0, 'playsinline' : 1, rel:0, autohide: 1, start: playfrom,wmode: 'transparent', iv_load_policy: '3', modestbranding: 1},//, 'playsinline' : 0, enablejsapi : 1
                                videoId: dataSrc,
                                suggestedQuality: o.settings_suggestedQuality,
                                events: {
                                    'onReady': yt_onPlayerReady
                                    ,'onStateChange': yt_onPlayerStateChange
                                    ,'onPlaybackQualityChange': onPlayerPlaybackQualityChange
                                }
                            });

                            setTimeout(function(){
                                // console.info('captions - ', video.captions);
                            },5000);

                            _cmedia = video;


                            // console.info(cthis.find('#the-media-'+cid));
                            cthis.find('#the-media-'+cid).bind('mousemove', handle_mousemove);
                        }
                    }
                    if (o.type == 'vimeo') {
                        //_c.children().remove();
                        var src = dataSrc;
                        var str_autoplay = '';



                        // console.info('o.settings_mode - ',o.settings_mode);
                        if(autoplay=='on' && o.settings_mode!='videowall'){
                            str_autoplay = '&autoplay=1';
                            cthis.find('.cover-image').fadeOut('fast');
                        }


                        if(_vpInner && o.vimeo_is_chromeless!='on') {
                            _vpInner.children('.controls').remove();
                        }
                        //console.info(autoplay, o.autoplay);

                        //&title=0&byline=0&portrait=0&badge=0

                        _vpInner.prepend('<iframe class="vimeo-iframe from-simple" scrolling="no" src="'+vgsettings.vimeoprotocol+'//player.vimeo.com/video/' + src + '?api=1&color='+o.vimeo_color+'&title='+o.vimeo_title+'&byline='+o.vimeo_byline+'&portrait='+o.vimeo_portrait+'&badge='+o.vimeo_badge+'&player_id=vimeoplayer' + src +str_autoplay+ '" width="100%" height="100%" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen style=""></iframe>');



                        // console.info('_controlsDiv - ',_controlsDiv);
                        _cmedia = _vpInner.find('.vimeo-iframe').get(0);





                        //ytplayer= document.getElementById("flashcontent");
                        //ytplayer.loadVideoById('L7ANahx7aF0')
                    }




                    if (o.type == 'dash') {


                        console.warn("Reading from source ", dataSrc);






                        setup_local_video();
                        video = cthis.find('.the-video').eq(0).get(0);



                        if(window.Webm){
                            dash_context = new Webm.di.WebmContext();
                            dash_player = new MediaPlayer(dash_context);
                            dash_player.startup();
                            dash_player.attachView(video);

                            if(autoplay=='on'){
                                dash_player.setAutoPlay(true);
                            }else{

                                dash_player.setAutoPlay(false);
                            }

                            dash_player.attachSource(dataSrc);


                            //console.info(video.naturalWidth);
                        }else{


                            var baseUrl = '';
                            var baseUrl_arr = get_base_url_arr();
                            for(var i24=0;i24<baseUrl_arr.length-1; i24++){
                                baseUrl+=baseUrl_arr[i24]+'/';
                            }
                            //var src = scripts[scripts.length-1].src;


                            var url = baseUrl+'dash.js';
                            //console.warn(scripts[i23], baseUrl, url);
                            $.ajax({
                                url: url,
                                dataType: "script",
                                success: function(arg){
                                    //console.info(arg);

                                    dash_context = new Webm.di.WebmContext();
                                    dash_player = new MediaPlayer(dash_context);
                                    dash_player.startup();
                                    dash_player.attachView(video);
                                    if(autoplay=='on'){
                                        dash_player.setAutoPlay(true);
                                    }else{

                                        dash_player.setAutoPlay(false);
                                    }
                                    dash_player.attachSource(dataSrc);


                                }
                            });
                        }

                    }



                }


                if (o.type == 'normal') {
                    video = cthis.find('.the-video').eq(0)[0];

                    // console.info(video);
                    if (video != undefined) {
                        video.controls = false;
                    }
                }
                if (o.type == 'audio') {
                    video = cthis.find('.the-video').eq(0)[0];
                    if(video!=undefined){
                        video.controls = false;
                    }
                }
                if (o.type == 'youtube') {
//                    video = cthis.children('object')[0];

//                    console.info(video);
                }

                if (o.type == 'vimeo') {
                    video = cthis.find('iframe')[0];
                    //console.log(video);
                    //

                    if (window.addEventListener) {
                        window.addEventListener('message', vimeo_windowMessage, false);
                    }

                }

                if (o.type == 'normal') {
                    $(video).css({
                        'position': 'absolute',
                        'background-color': '#000000'
                    })
                }

                if (autoplay == 'on') {
                    wasPlaying = true;
                }else{

                }


                if(cthis.attr('data-adsource') &&  cthis.data('adplayed')!='on'){
                    has_ad_to_play = true;
                }

                // console.info('has_ad_to_play - ',has_ad_to_play, cthis.attr('data-adsource'), cthis.data('adplayed'));



                get_responsive_ratio({
                    'call_from' : 'init .. readyControls'
                });;

                // cthis.find('.cover-image').bind('click',click_coverImage);

                cthis.on('click','.cover-image:not(.from-type-image), .div-full-image.from-type-audio', click_coverImage);

                cthis.addClass('dzsvp-loaded');

                // console.info("type -", o.type);


                inter_videoReadyState = setInterval(check_videoReadyState, 50);
                cthis.get(0).externalPauseMovie = pauseMovie;
                cthis.get(0).externalPlayMovie = playMovie;
                cthis.get(0).api_pauseMovie = pauseMovie;
                cthis.get(0).api_playMovie = playMovie;
                cthis.get(0).api_get_responsive_ratio = get_responsive_ratio;


                //console.info(cthis, o.type, o.responsive_ratio, cthis.attr('data-responsive_ratio'));

                if(is360){

                    // console.info("360!!!");


                    $(video).on('click',function(e){
                        if(initial_played==false){
                            playMovie();

                            mouse_is_over();
                        }
                    })

                    cthis.on('touchstart',function(e){
                        console.warn("touch start", e);


                        if(e.originalEvent && e.originalEvent.target && $(e.originalEvent.target).hasClass('video-overlay')){
                            cthis.addClass('mouse-is-out');
                            // console.warn("daada");
                        }

                        if(controls){
                            controls.enabled=true;
                        }
                    })
                    $(document).on('touchend',function(e){
                        // console.warn("touch end", e);

                        cthis.removeClass('mouse-is-out');
                        if(controls){

                            controls.enabled=false;
                        }
                    })
                }

                $(window).on('resize', handleResize);




                var _scrubbar = cthis.find('.scrubbar').eq(0);

                _scrubbar.on('touchstart', function(e) {
                    scrubbar_moving = true;
                })

                if(o.ad_show_markers=='on'){
                    for(var i2 = 0;i2<ad_array.length;i2++){
                        // console.info(_scrubBg, (ad_array[i2].time*100));
                        //  _scrubBg.append('<span class="ad-marker" style="left: '+(ad_array[i2].time*100)+'%"></span>');
                        _scrubbar.find('.scrub').eq(0).before('<span class="reclam-marker" style="left: '+(ad_array[i2].time*100)+'%"></span>');
                        // _scrubbar.find('.scrub-buffer').append('<span class="ad-marker" style="left: '+(Number(ad_array[i2].time)*_scrubBg)+'%"></span>');
                    }
                }

                $(document).on('touchmove', function(e) {
                    if (scrubbar_moving) {
                        scrubbar_moving_x = e.originalEvent.touches[0].pageX;


                        var aux3 = scrubbar_moving_x - _scrubbar.offset().left;

                        if (aux3 < 0) {
                            aux3 = 0;
                        }
                        if (aux3 > _scrubbar.width()) {
                            aux3 = _scrubbar.width();
                        }

                        seek_to_perc(aux3 / _scrubbar.width());


                        //console.info(aux3);


                    }
                });

                $(document).on('touchend', function(e) {
                    scrubbar_moving = false;
                })





                o.settings_trigger_resize = parseInt(o.settings_trigger_resize, 10);
                if(o.settings_trigger_resize>0){
                    setInterval(function(){

                        // console.info("HMM");
                        handleResize(null, {
                            'call_from':'recheck_sizes'
                        });
                    },o.settings_trigger_resize);
                };

                if(is_touch_device()){
                    cthis.addClass('is-touch');
                }

            }




            function get_responsive_ratio(pargs){


                var margs = {
                    'reset_responsive_ratio' : false
                    ,'call_from' : 'default'
                };
                // console.warn('get_responsive_ratio()',pargs, o.responsive_ratio);

                if(pargs){
                    margs = $.extend(margs,pargs);
                }

                if(margs.reset_responsive_ratio){
                    o.responsive_ratio='default';
                }

                if(o.responsive_ratio=='default' || (o.type=='youtube' && o.responsive_ratio=='detect')){

                    if(cthis.attr('data-responsive_ratio')){
                        o.responsive_ratio= cthis.attr('data-responsive_ratio');
                    }
                }

                if(o.responsive_ratio=='detect'){

                    // console.info('lets calculate responsive ratio', o.type, video, video.videoWidth,video.videoHeight);
                    if(o.type=='normal'||o.type=='video'||o.type=='dash'){
                        if(video && video.videoHeight){

                            o.responsive_ratio = video.videoHeight / video.videoWidth ;
                        }else{
                            o.responsive_ratio = 0.5625;
                        }

                        //console.info(o.responsive_ratio);
                        if(video.addEventListener){
                            video.addEventListener('loadedmetadata',function(){
                                o.responsive_ratio = video.videoHeight / video.videoWidth ;
//                                console.info(o.responsive_ratio);
                                handleResize();
                            })
                        }

                        if(o.type=='dash'){
                            dash_inter_check_sizes = setInterval(function(){

                                //console.info(o.type, video, video.videoWidth,video.videoHeight);

                                if(video && video.videoHeight) {
                                    if (video.videoWidth > 0) {

                                        o.responsive_ratio = video.videoHeight / video.videoWidth;
                                        handleResize();

                                        clearInterval(dash_inter_check_sizes);
                                    }
                                }
                            },1000);
                        }

                    }
                    if(o.type=='audio'){

                        if(cthis.find('.div-full-image').length){

                            var _cach = cthis.find('.div-full-image').eq(0);

                            var aux = _cach.css('background-image');

                            aux = aux.replace(/"/g, '');
                            aux = aux.replace("url(", '');
                            aux = aux.replace(")", '');

                            var img = new Image();

                            img.onload = function(){
                                //console.info(this, this.naturalWidth, this.naturalHeight);

                                o.responsive_ratio = this.naturalHeight / this.naturalWidth ;
//                                console.info(o.responsive_ratio);
                                handleResize();

                            };


                            img.src = aux;

                            //console.info(aux);
                            //
                            //console.info(cthis.find('.div-full-image').eq(0))
                        }



                    }
                    if(o.type=='youtube'){

                        o.responsive_ratio=0.5625;
                    }
                    if(o.type=='vimeo'){

                        o.responsive_ratio=0.5625;
                    }
                    if(o.type=='inline'){

                        o.responsive_ratio=0.5625;
                    }

                }
                o.responsive_ratio = Number(o.responsive_ratio);
            }

            function setup_local_video(pargs){

                //console.info('setup_local_video()', pargs);

                var margs = {
                    'preload' : 'auto'
                    ,'is_dash' : false
                };

                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                if(o.is_ad=='on' && is_mobile()){
                    margs.preload = 'metadata';
                }

                aux = '<video class="the-video" preload="'+margs.preload+'" ';


                // console.info('is_mobile - '+is_mobile(),'autoplay -> ',autoplay);
                if((is_mobile() && autoplay=='on' && o.autoplay_on_mobile_too_with_video_muted=='on')||o.defaultvolume===0){
                    aux+= ' muted';
                }


                if(o.touch_play_inline=='on'){
                    aux+=' webkit-playsinline playsinline';
                }else{

                    aux+=' controls="false"';
                }

                if (videoWidth != 0) {
                    aux += ' width="100%"';//aux += ' width="' + videoWidth + '"';
                    aux += ' height="100%"';//aux += ' height="' + videoHeight + '"';
                }
                aux += '></video>';














                if (!is_ie9()) {
                    // cthis.prepend(aux);
                    _vpInner.prepend(aux);
                }
                if(margs.is_dash==true){

                    return false;
                }
                //var obj = document.createElement('video');
                //obj.src='ceva';
                //console.log('ceva', cthis, cthis.attr('data-src'), cthis.attr('data-source'));


                if ( (cthis.attr('data-src') || cthis.attr('data-source')) && margs.is_dash==false) {
                    if (cthis.attr('data-src') && (cthis.attr('data-src').indexOf('.ogg') > -1 || cthis.attr('data-src').indexOf('.ogv') > -1) ) {
                        cthis.attr('data-sourceogg', cthis.attr('data-src'));
                    }
                    if (cthis.attr('data-src') && ( cthis.attr('data-src').indexOf('.m4v') > -1 || cthis.attr('data-src').indexOf('.mp4') > -1 ) ) {
                        cthis.attr('data-sourcemp4', cthis.attr('data-src'));
                    }



                    //console.warn((cthis.attr('data-source'))==true);
                    if (cthis.attr('data-source') && ( cthis.attr('data-source').indexOf('.m4v') > -1 || cthis.attr('data-source').indexOf('.mp4') > -1 ) ) {
                        cthis.attr('data-sourcemp4', cthis.attr('data-source'));
                    }
                }


                ///console.log(cthis.attr('data-sourcemp4'));
                if ( ( cthis.attr('data-src') || cthis.attr('data-sourcemp4') )  && margs.is_dash==false) {


                    if(cthis.attr('data-src')){
                        cthis.attr('data-sourcemp4',cthis.attr('data-src'));
                    }

                    cthis.find('.the-video').eq(0).append('<source src="' + cthis.attr('data-sourcemp4') + '"/>');
                    if (is_ie9()) {
                        var auxdiv = cthis.find('.controls');
                        cthis.prepend('<video controls preload><source src="' + cthis.attr('data-sourcemp4') + '" type="video/mp4"/></video>');
                        //cthis.append('<div class="controls"></div>');
                        //cthis.children('.controls') = auxdiv;
                    }
                }



                if (cthis.attr('data-sourceogg') != undefined) {
                    cthis.find('.the-video').eq(0).append('<source src="' + cthis.attr('data-sourceogg') + '" type="video/ogg"/>');
                }
                if (cthis.attr('data-sourcewebm') != undefined) {
                    cthis.find('.the-video').eq(0).append('<source src="' + cthis.attr('data-sourcewebm') + '" type="video/webm"/>');
                }
                //console.log(cthis.attr('data-sourceflash'), cthis.attr('data-sourcewebm'), cthis.attr('data-sourceogg'), $.browser.mozilla, (cthis.attr('data-sourceflash')!=undefined && cthis.attr('data-sourcewebm')==undefined && cthis.attr('data-sourceogg')==undefined && $.browser.mozilla))

                //- --- setup the type
                var str_type = '';

//                        console.info(o, cthis.attr('data-sourceflash'));

                video      = cthis.find('.the-video').get(0);



                if(is360){

                    // console.warn("LOADING 360 ASSETS");
                    cthis.addClass('is-360');
                    get_responsive_ratio({
                        'call_from' : '360'
                    });
                    if(totalHeight == 0 && o.responsive_ratio){
                        totalHeight = Number(o.responsive_ratio) * totalWidth;
                    }




                    var url = 'https://cdnjs.cloudflare.com/ajax/libs/three.js/r73/three.min.js';
                    //console.warn(scripts[i23], baseUrl, url);
                    $.ajax({
                        url: url,
                        dataType: "script",
                        success: function(arg){
                            //console.info(arg);

                            url = 'https://s3-us-west-2.amazonaws.com/s.cdpn.io/211120/orbitControls.js';
                            //console.warn(scripts[i23], baseUrl, url);
                            $.ajax({
                                url: url,
                                dataType: "script",
                                success: function(arg){
                                    //console.info(arg);

                                    // console.info('totalWidth - ',totalWidth, 'totalHeight - ',totalHeight, 'responsive_ratio - ',o.responsive_ratio);



                                    renderer = new THREE.WebGLRenderer({ antialias: true });
                                    renderer.setSize(totalWidth, totalHeight);
                                    $(video).after(renderer.domElement)



                                    scene = new THREE.Scene();


                                    video.setAttribute('crossorigin', 'anonymous');
                                    videoTexture = new THREE.Texture(video);
                                    videoTexture.minFilter = THREE.LinearFilter;
                                    videoTexture.magFilter = THREE.LinearFilter;
                                    videoTexture.format = THREE.RGBFormat;



                                    cubeGeometry = new THREE.SphereGeometry(500, 60, 40);
                                    sphereMat = new THREE.MeshBasicMaterial({map: videoTexture});
                                    sphereMat.side = THREE.BackSide;
                                    cube = new THREE.Mesh(cubeGeometry, sphereMat);
                                    scene.add(cube);



                                    camera = new THREE.PerspectiveCamera(45, totalWidth / totalHeight, 0.1, 10000);
                                    camera.position.y = 0;
                                    camera.position.z = 500;

                                    scene.add(camera);

                                    controls = new THREE.OrbitControls( camera);

                                    controls.enableDamping = false;
                                    controls.enableRotate = false;
                                    controls.dampingFactor = 0.25;

                                    if(is_mobile()){

                                    }

                                    controls.enableZoom = true;
                                    controls.maxDistance = 500;
                                    controls.minDistance = 500;
                                    controls.minDistance = 300;
                                    controls.maxDistance = 1000;

                                    controls.enabled=false;


                                    render();

                                    // console.warn("FINISHED LOADING");

                                }
                            });


                        }
                    });


                }




                function render() {
                    if( video.readyState === video.HAVE_ENOUGH_DATA ){
                        videoTexture.needsUpdate = true;
                    }
                    controls.update();
                    renderer.render(scene, camera);
                    requestAnimationFrame(render);
                }



            }


            function check_videoReadyState() {
                if (!video ) {
                    return;
                }
                // console.log('check_videoReadyState', video, video.readyState);
                if (o.type == 'youtube' && video.getPlayerState) {

                    init_readyVideo();
                }

                //console.info(video.readyState);


                if (is_firefox() && o.cueVideo!='on' && (o.type == 'normal' || o.type == 'audio') && Number(video.readyState) >= 2) {
                    init_readyVideo({
                        'call_from' : 'check_videoReadyState'
                    });
                    return false;
                }



                if(o.type=='vimeo' && o.vimeo_is_chromeless=='on'){

                    if(vimeo_is_ready){
                        init_readyVideo();
                        return false;
                    }
                }
                if(o.type=='audio'){
                    if(is_mobile()){
                        if(Number(video.readyState) >= 1){

                            init_readyVideo();
                            return false;
                        }
                    }
                    if(is_opera()){
                        if(Number(video.readyState) == 2){

                            init_readyVideo();
                            return false;
                        }
                    }

                    if(Number(video.readyState) >= 3) {
                        clearInterval(inter_videoReadyState);
                        init_readyVideo({
                            'call_from' : 'check_videoReadyState'
                        });
                        return false;
                    }
                }
                if(o.type=='normal'){

                    if(is_ios()){
                        if(Number(video.readyState) >= 1){

                            init_readyVideo();
                            return false;
                        }
                    }

                    if(is_android()){
                        if(Number(video.readyState) >= 2){

                            init_readyVideo();
                            return false;
                        }
                    }


                    if(Number(video.readyState) >= 3) {
                        clearInterval(inter_videoReadyState);
                        init_readyVideo({
                            'call_from' : 'check_videoReadyState'
                        });
                        return false;
                    }
                }


                // --- WORKAROUND __ for some reason ios default browser would not go over video ready state 1

                if(o.type=='dash'){
                    //console.warn(video.readyState)
                    clearInterval(inter_videoReadyState)
                    init_readyVideo({
                        'call_from' : 'check_videoReadyState'
                    });
                }
//                console.log(video.readyState);
            }

            function yt_onPlayerReady(e){




                // console.info('yt_onPlayerReady',e);



                //yt_qualCurr = video.getPlaybackQuality();
                //
                //console.log(yt_qualCurr);


                if(video && video.getPlaybackQuality){
                    // console.info('getPlaybackQuality - ', video.getPlaybackQuality(), ' | state - ','player ready', ' | quality levels - ',video.getAvailableQualityLevels() );


                    video.setPlaybackQuality(o.settings_suggestedQuality);
                }


                // console.warn('o.playfrom - ',o.playfrom);
                // console.warn('autoplay - ',autoplay);
                if(o.playfrom=='last'){
                    // TODO: dunno why we need this

                    if(autoplay=='off'){

                        setTimeout(function(){

                            pauseMovie();
                        },1000)
                    }
                }



                return false;
                //console.info(video.getOptions('cc'));
                video.unloadModule('cc'); // For AS3.
                video.unloadModule('captions'); // For HTML5.
                video.unloadModule("captions");  //Works for AS3 ignored by html5
                video.setOption("captions", "track", {"languageCode": "en"});

                var yt_playerReady = true;

                init_readyall();
            }


            function onPlayerPlaybackQualityChange(e){

                // console.info('onPlayerPlaybackQualityChange', cthis, e.data, e);

            }
            function yt_onPlayerStateChange(e){

                /*

                 -1 – unstarted
                 0 – ended
                 1 – playing
                 2 – paused
                 3 – buffering
                 5 – video cued

                 */

                console.info('yt_onPlayerStateChange', cthis, e.data, e);

                if(e.data==1){
                    // -play
                    //console.log(paused);
                    //if(paused){ playMovie(); }



                    // console.info("PLAYED..");
                    // console.info(has_ad_to_play);


                    if(queue_goto_perc){
                        seek_to_perc(queue_goto_perc);
                        queue_goto_perc = '';
                    }


                    if(ad_status=='waiting_for_play'){
                        setup_skipad();
                    }

                    check_if_ad_must_be_played();
                    check_if_hd_available();

                    if(queue_change_quality){

                        video.setPlaybackQuality(o.settings_suggestedQuality);
                        queue_change_quality='';
                    }

                    playMovie_visual();
                    paused = false;
                    wasPlaying = true;

                    initial_played=true;


                    if(is_mobile()){
                        cthis.find('.controls').eq(0).css('pointer-events', 'auto');

                    }

                }
                if(e.data==2){
                    // pause

                    if(suspend_state_2_for_loop==false){

                        pauseMovie({
                            'call_from':'state_2_for_youtube'
                        });
                    }
                    paused = true;
                    wasPlaying = false;
                }

                if(video && video.getPlaybackQuality){
                    // console.info('getPlaybackQuality - ', video.getPlaybackQuality(), ' | state - ',e.data, ' | quality levels - ',video.getAvailableQualityLevels() );
                }


                if(e.data==3){


                }
                if(e.data==5){



                }
                if(e.data==0){

                    // -- handlevideo end
                    handleVideoEnd();
                }
            }

            function init_readyVideo(pargs) {
                //console.log(video.getAvailableQualityLevels());

                // console.info("READY VIDEO");


                var margs = {

                    'call_from' : 'default'
                };

                if(pargs){
                    margs = $.extend(margs, pargs);
                }


                clearInterval(inter_videoReadyState);
                // if(video && video.getPlaybackQuality){
                //     console.info('getPlaybackQuality - ', video.getPlaybackQuality(), ' | state - ','init_readyVideo', ' | quality levels - ',video.getAvailableQualityLevels() );
                // }


                // console.log('init_readyVideo() - ', cthis, margs);




                // console.info('cthis.find(\'.dzsvg-feed-quality\') -> ',cthis.find('.dzsvg-feed-quality'));
                var _cas = cthis.find('.dzsvg-feed-quality');
                if(_cas.length){
                    cthis.addClass('has-multiple-quality-levels');


                    var _cach = cthis.find('.quality-selector');
                    var aux = _cach.find('.dzsvg-tooltip').html();

                    var aux_opts = '';

                    var added = false;


                    var curr_qual_added = false;
                    _cas.each(function(){
                        var _t2 = $(this);


                        aux_opts += '<div class="quality-option';


                        if(_t2.attr('data-source')==cthis.attr('data-src')){
                            aux_opts+= ' active';
                            added = true;
                        }



                        aux_opts += '" data-val="'+_t2.attr('data-label')+'" data-source="'+_t2.attr('data-source')+'">'+_t2.attr('data-label')+'</div>';
                    })

                    if(added==false){

                        aux_opts += '<div class="quality-option active ';




                        aux_opts += '" data-val="'+o.settings_currQuality+'" data-source="'+cthis.attr('data-src')+'">'+o.settings_currQuality+'</div>';

                    }


                    if(aux){

                        // aux = aux.replace('{{svg_quality_icon}}',svg_quality_icon);
                        aux = aux.replace('{{quality-options}}',aux_opts);


                        _cach.find('.dzsvg-tooltip').html(aux);
                    }else{
                        console.warn('no aux ? ',aux,_cach);
                    }


                }





                if(o.defaultvolume==''){
                    o.defaultvolume = 'last';
                }

                if(isNaN(Number(o.defaultvolume))){


                    if(o.defaultvolume=='last'){
                        if (localStorage != null) {
                            if (localStorage.getItem('volumeIndex') === null){

                                defaultVolume = 1;
                            }else{

                                defaultVolume = localStorage.getItem('volumeIndex');
                            }
                        }
                    }
                }else{
                    o.defaultvolume = Number(o.defaultvolume);

                    defaultVolume = o.defaultvolume;
                }

                defaultVolume = Number(defaultVolume);


                if(defaultVolume>-0.1 || isNaN(Number(defaultVolume))==false){

                    // -- all well
                }else{
                    defaultVolume = 1;
                }




                if (videoWidth == 0) {
                    //videoWidth = jQuery(video).width();
                    //videoHeight = jQuery(video).height();
                    videoWidth = cthis.width();
                    videoHeight = cthis.height();
                }

                cthis.addClass('dszvp-loaded');
                if (o.gallery_object != null) {
                    if(typeof(o.gallery_object.get(0))!='undefined'){
                        o.gallery_object.get(0).api_video_ready();
                    }
                }

                if (o.type == 'youtube') {
                    yt_qualCurr = video.getPlaybackQuality();

                }


                // console.info(o.type);
                if(o.type=='video' || o.type=='normal'){
                    if(o.default_playbackrate && o.default_playbackrate!='1'){

                        // console.info('_cmedia - ',_cmedia);
                        setTimeout(function(){

                            // console.info('_cmedia - ',_cmedia);
                            // console.info('video - ',video);
                        },1000);
                        if(video && video.playbackRate){

                            video.playbackRate = Number(o.default_playbackrate);
                        }
                    }
                }


                videoWidth = cthis.outerWidth();
                videoHeight = cthis.outerHeight();


//                console.log(cthis.width(), videoWidth, videoHeight);
                resizePlayer(videoWidth, videoHeight)

                if(is_touch_device()==false){

                    setupVolume(defaultVolume, {'call_from':'init, defaultVolume'});
                }


                // console.log(cthis, o.settings_disableControls);
                // console.info(' is ad ', o.is_ad, o.type, is_ios() && video && o.is_ad == 'on');

                var checkInter = setInterval(tick, 100);



                if(is_mobile() && o.is_ad=='on'){
                    // autoplay = 'on';
                }

                // console.info('o.type-',o.type);
                // console.info('autoplay-',autoplay);
                // console.info('o.autoplay-',o.autoplay);
                if (autoplay == 'on') {

                    if(o.type!='vimeo'){

                        playMovie({
                            'call_from':'autoplay - on'
                        });
                    }
                }

                if(o.playfrom!='default'){

//                    console.info(the_player_id, o.playfrom);

                    if(o.playfrom=='last' && the_player_id!=''){
                        if(typeof Storage!='undefined'){

                            if(typeof localStorage['dzsvp_'+the_player_id+'_lastpos']!='undefined'){
//                                console.info(localStorage['dzsvp_'+the_player_id+'_lastpos'], o.type, Number(localStorage['dzsvp_'+the_player_id+'_lastpos']));
                                if (o.type == 'normal' || o.type == 'audio') {
                                    video.currentTime = Number(localStorage['dzsvp_'+the_player_id+'_lastpos']);
                                }
                                if (o.type == 'youtube') {
                                    video.seekTo(Number(localStorage['dzsvp_'+the_player_id+'_lastpos']));
                                    if(wasPlaying==false){
                                        pauseMovie({
                                            'call_from':'_init_readyVideo()'
                                        });
                                    }
                                }
                            }
                        }
                    }

                    if(isNaN(Number(o.playfrom))==false){
                        if (o.type == 'normal' || o.type == 'audio') {
                            video.currentTime = o.playfrom;
                        }
                        if (o.type == 'youtube') {
                            //video.seekTo(o.playfrom, true);
                            //
                            //
                            //
                            ////console.info(wasPlaying);
                            //if(wasPlaying==false){
                            //
                            //
                            //
                            //    setTimeout(function(){
                            //        if (video && video.pauseVideo) {
                            //
                            //            try {
                            //                video.pauseVideo();
                            //            }catch (err) {
                            //                if (window.console) {
                            //                    console.log(err);
                            //                }
                            //            }
                            //        }
                            //    },300);
                            //
                            //
                            //    setTimeout(function(){
                            //
                            //        pauseMovie();
                            //    },1100);
                            //}
                        }
                    }


                }






                tick({
                    skin_play_check : true
                });


                // console.info('o.settings_disableControls - ',o.settings_disableControls,cthis);



                // -- we include this for both ads and normal videos
                cthis.on('mouseleave',handleMouseout);
                cthis.on('mouseover', handleMouseover);
                if (o.settings_disableControls != 'on') {
                    // -- only for normal videos

                    if(cthis.hasClass('debug-target')){ console.info(cthis, playcontrols); }
                    cthis.find('.controls').eq(0).bind('mouseover', handle_mouse);
                    cthis.find('.controls').eq(0).bind('mouseout', handle_mouse);
                    cthis.bind('mousemove', handle_mousemove);
                    $(document).on('keyup.dzsvgp',handleKeyPress);



                    // console.warn("HMM IOS");
                    cthis.on('click','.quality-option',handle_mouse);

                    fScreenControls.off('click',onFullScreen);
                    // fScreenControls.on('mousedown touchstart',onFullScreen)
                    fScreenControls.on('click',onFullScreen)
                    scrubbar.bind('click', handleScrub);
                    scrubbar.bind('mousedown', handle_mouse);
                    scrubbar.bind('mousemove', handleScrubMouse);
                    scrubbar.bind('mouseout', handleScrubMouse);
                    cthis.bind('mouseleave', handleScrubMouse);
                    // playcontrols.click(onPlayPause);

                    //console.info('cthis in addEventListener - ',cthis);
                    cthis.on('click','> .controls .playcontrols, > .vp-inner > .controls .playcontrols',onPlayPause);
                    cthis.find('.touch-play-btn').on('click touchstart', onPlayPause);
                    cthis.find('.mutecontrols-con').bind('click', click_mutecontrols);

                    // console.info("HMM");
                    // cthis.bind('keypress',handle_key);

                    if(o.type=='normal'){

                        video.addEventListener('fullscreenchange', check_fullscreen, false);
                        video.addEventListener('mozfullscreenchange', check_fullscreen, false);
                        video.addEventListener('webkitfullscreenchange', check_fullscreen, false);
                    }else{
                    }

                    document.addEventListener('fullscreenchange', check_fullscreen, false);
                    document.addEventListener('mozfullscreenchange', check_fullscreen, false);
                    document.addEventListener('webkitfullscreenchange', check_fullscreen, false);



                    //console.info('ios - ',is_ios(),'android - ',is_android());
                    if(is_mobile()){
                        if(o.type=='youtube'){
                            if(o.settings_video_overlay=='on'){

                                cthis.find('.controls').eq(0).css('pointer-events', 'none');
                                cthis.find('.controls .playcontrols').eq(0).css('pointer-events', 'auto');
                            }
                        }
                        //$(video).bind('ended', event_video);
                    }else{

                        if(is360){
                            o.settings_video_overlay='off';
                        }
                    }


                } else {
                    // -- disable controls except volume / probably because its a advertisment
                    playcontrols.css({'opacity': 0.5});
                    fScreenControls.css({'opacity': 0.5});
                    scrubbar.css({'opacity': 0.5});
                    _timetext.css({'opacity': 0.5});


                    if(o.is_ad=='on' && autoplay=='off'){

                    }

                    if(is_ios() || is_android()){

                        playcontrols.css({'opacity': 0.9});
                        playcontrols.click(onPlayPause);

                        o.settings_hideControls = 'off';

                        cthis.removeClass('hide-on-paused');
                        cthis.removeClass('hide-on-mouse-out');



                        // console.info('o.is_ad - ',o.is_ad);

                        if(o.is_ad=='on'){

                            autoplay='on';
                            o.autoplay='on';
                            o.cue='on';



                            cthis.find('.video-overlay').append('<div class="warning-mobile-ad">'+'You need to click here for the ad for to start'+'</div>')

                        }

                    }

                    //volumecontrols.css({'opacity' : 0.5});
//                     if (o.ad_link != '') {
//                         //console.log(cthis, cthis.children().eq(0), o.ad_link
//                         var _c = cthis.children().eq(0);
//                         _c.css({'cursor': 'pointer'})
// //                        console.info(_c, cthis, o.ad_link, _c.parent(), _c.parent().find('.video-overlay'));
//                         _c.unbind('click');
//                         _c.bind('click', function() {
//                             //console.info(played, 'ceva', cthis.find('.controls').eq(0).css('pointer-events'));
//                             if(played) {
//                                 window.open(o.ad_link);
//                             }
//                         })
//                     }
                }

                $(video).bind('play', event_video);


                // console.info('o.settings_disableControls -',o.settings_disableControls, 'o.settings_video_overlay - ',  o.settings_video_overlay, cthis);
                if(o.settings_video_overlay=='on'){

                    // console.info("HMM", cthis.find('.the-video').eq(0));

                    var str_video_overlay = '<div class="video-overlay"></div>';
                    cthis.find('.the-video').eq(0).after(str_video_overlay);

                    if(o.type=='youtube' || o.type=='vimeo'){

                        $('.cmedia-con').after(str_video_overlay);
                    }

                    cthis.on('click','.video-overlay', click_videoOverlay);
                    cthis.on('dblclick','.video-overlay', onFullScreen);
                }

                if(o.video_description_style=='gradient'){

                    var aux3 = '<div class="video-description video-description-style-'+o.video_description_style+'"><div>';

                    aux3+=dataVideoDesc;
                    aux3+='</div></div>';

                    if(cthis.find('.big-play-btn').length){

                        cthis.find('.big-play-btn').eq(0).before(aux3);
                    }else{

                        if(cthis.find('.video-overlay').length){

                            cthis.find('.video-overlay').eq(0).after(aux3);
                        }else{

                            cthis.find('.controls').before(aux3);
                        }
                    }
                }

                //console.info('dataVideoDesc - ',dataVideoDesc);


                //_volumeControls.click(handleVolume);

                window.dzsvg_handle_mouse = handle_mouse;


                _volumeControls_real.bind('mousedown', handle_mouse);

                $(document).on('mouseup.dzsvg', window.dzsvg_handle_mouse);
                _volumeControls_real.bind('click', handleVolume);



                if (o.settings_hideControls == 'on') {
                    _controlsDiv.hide();
                }


                if (o.type == 'normal' || o.type == 'audio') {

                    video.addEventListener('ended', handleVideoEnd, false);
                    if(is_ios()){

                        video.addEventListener('webkitendfullscreen',onFullScreen);
                    }

                    if(is_ios() && video && o.is_ad == 'on'){

                        // console.info("ADDED END FULLSCREEN EVENT");
                        video.addEventListener('webkitendfullscreen', function(){
                            if(video.currentTime>video.duration*0.75){
                                handleVideoEnd();
                            }
                        }, false);
                    }
                }


                if(cthis.children('.subtitles-con-input').length || o.settings_subtitle_file){
                    setup_subtitle();
                }



                setTimeout(handleResize, 500);



                //--if video were inside a gallery, the gallery would handle resize
                if(o.gallery_object==null){
                }






                cthis.get(0).api_destroy_listeners = destroy_listeners;






            }

            function destroy_listeners(){
                cthis.unbind('mouseout',handleMouseout);
                cthis.unbind('mouseover',handleMouseover);
                cthis.find('.controls').eq(0).unbind('mouseover', handle_mouse);
                cthis.find('.controls').eq(0).unbind('mouseout', handle_mouse);
                cthis.unbind('mousemove', handle_mousemove);
                cthis.unbind('keydown',handleKeyPress);
                fScreenControls.off('click',onFullScreen)
                scrubbar.unbind('click', handleScrub);
                scrubbar.unbind('mousedown', handle_mouse);
                scrubbar.unbind('mousemove', handleScrubMouse);
                scrubbar.unbind('mouseout', handleScrubMouse);
                cthis.unbind('mouseleave', handleScrubMouse);
                cthis.off('click');
                cthis.find('.mutecontrols-con').unbind('click', click_mutecontrols);
                document.removeEventListener('fullscreenchange', check_fullscreen, false);
                document.removeEventListener('mozfullscreenchange', check_fullscreen, false);
                document.removeEventListener('webkitfullscreenchange', check_fullscreen, false);


                if(o.gallery_object==null) {
                    $(window).off('resize', handleResize);
                }

                if (o.type == 'normal' || o.type == 'audio') {

                    video.removeEventListener('ended', handleVideoEnd, false);
                }
            }

            function check_if_hd_available(){


                if(yt_qualArray.length>0){
                    return false;
                }



                yt_qualCurr = video.getPlaybackQuality();

                yt_qualArray = video.getAvailableQualityLevels();

                console.info('yt_qualCurr - ',yt_qualCurr, yt_qualArray);

                if ($.inArray('hd720', yt_qualArray) > -1) {
                    hasHD = true;
                }

                if(yt_qualArray.length>1){
                    cthis.addClass('has-multiple-quality-levels');
                }

                var _cach = cthis.find('.quality-selector');

                if(_cach.length==0){

                    if (hasHD == true) {

                        if(_controlsDiv.children('.hdbutton-con').length==0){




                            if(o.settings_suggestedQuality!='default'){
                                if(yt_qualCurr!=o.settings_suggestedQuality){
                                    video.setPlaybackQuality(o.settings_suggestedQuality);
                                    //console.info(yt_qualCurr, o.settings_suggestedQuality)
                                }
                            }





                            if(o.design_skin!='skin_pro' && o.design_skin!='skin_reborn'){
                                o.design_scrubbarWidth -= 23;
                                original_scrubwidth -= 23;

                            }

                            //console.log(o.design_skin);
                            if(o.design_skin=='skin_pro'){
                                _controlsDiv.find('.timetext').after('<div class="hdbutton-con"><div class="hdbutton-normal">HD</div></div>');

                            }else{

                                _controlsDiv.append('<div class="hdbutton-con"><div class="hdbutton-normal">HD</div></div>');
                            }

                            _btnhd = _controlsDiv.children('.hdbutton-con');
                            if (yt_qualCurr == 'hd720' || yt_qualCurr == 'hd1080') {
                                _btnhd.addClass('active');
                            }
                            _btnhd.bind('click', click_hd);



                            resizePlayer(videoWidth, videoHeight);
                        }

                    }
                }else{

                    // no-quality selector

                    var aux = _cach.find('.dzsvg-tooltip').html();

                    var aux_opts = '';


                    console.info('yt_qualArray -> ',yt_qualArray);
                    for(var i2 in yt_qualArray){



                        aux_opts += '<div class="quality-option';


                        if(yt_qualCurr==yt_qualArray[i2]){
                            aux_opts+=' active';
                        }

                        aux_opts += '" data-val="'+yt_qualArray[i2]+'">'+yt_qualArray[i2]+'</div>';
                    }


                    aux = aux.replace('{{quality-options}}',aux_opts);


                    _cach.find('.dzsvg-tooltip').html(aux);

                }

            }

            function handle_mouse(e){
//                console.info(e.pageX, e.pageY);
                var _t = $(this);



                if(e.type=='mouseover'){

                    if(_t.hasClass('controls')){

                        controls_are_hovered = true;
                    }
                }
                if(e.type=='mouseout'){

                    if(_t.hasClass('controls')){

                        controls_are_hovered = false;
                    }
                }

                if(e.type=='mousedown'){

                    if(_t.hasClass('volumecontrols')){

                        volume_mouse_down = true;
                    }
                    if(_t.hasClass('scrubbar')){



                        clearTimeout(inter_mousedownscrubbing);
                        inter_mousedownscrubbing = setTimeout(enable_mousedown_scrubbing,100);

                    }
                }
                if(e.type=='click'){


                    if(_t.hasClass('quality-option')){


                        if(_t.hasClass('active')){
                            return false;
                        }


                        queue_goto_perc = time_curr / totalDuration;
                        if(o.type=='youtube'){

                            // console.info("_t.attr('data-val') - ",_t.attr('data-val'));
                            video.setPlaybackQuality(_t.attr('data-val'));




                            // console.warn('queue_goto_perc - ',queue_goto_perc);

                            video.stopVideo();
                            video.setPlaybackQuality(_t.attr('data-val'));
                            video.playVideo();


                            // queue_change_quality = _t.attr('data-val');
                            //
                            // pauseMovie();
                            // playMovie();

                            setTimeout(function(){

                                yt_qualCurr = video.getPlaybackQuality();

                                console.info('yt_qualCurr - ',yt_qualCurr)


                            },2000)
                        }




                        // console.info('o.type - ',o.type);
                        if(o.type=='normal'){


                            var newsource = _t.attr('data-source');

                            // console.info(video);

                            var _c = $(video).eq(0);

                            cthis.find('.the-video').addClass('transitioning-out');

                            _c.after(_c.clone());

                            var _c2 = _c.next();

                            _c2.removeClass('transitioning-out transitioning-in');
                            _c2.addClass('preparing-transitioning-in js-transitioning-in');
                            _c2.html('<source src="'+newsource+'">')


                            var aux_wasPlaying = wasPlaying;
                            _c2.on('loadeddata',function(){
                                console.warn("LOADED DATA");

                                _c2.off('loadeddata');
                                video = _c2.get(0);

                                if(queue_goto_perc){
                                    seek_to_perc(queue_goto_perc);
                                    queue_goto_perc = '';
                                }


                                if(is360){


                                    video.setAttribute('crossorigin', 'anonymous');
                                    videoTexture = new THREE.Texture(video);
                                    videoTexture.minFilter = THREE.LinearFilter;
                                    videoTexture.magFilter = THREE.LinearFilter;
                                    videoTexture.format = THREE.RGBFormat;


                                    scene.remove(cube);

                                    cubeGeometry = new THREE.SphereGeometry(500, 60, 40);
                                    sphereMat = new THREE.MeshBasicMaterial({map: videoTexture});
                                    sphereMat.side = THREE.BackSide;
                                    cube = new THREE.Mesh(cubeGeometry, sphereMat);
                                    scene.add(cube);
                                }


                                setTimeout(function(){

                                    pauseMovie();
                                    if(cthis.find('.transitioning-out').get(0).pause){
                                        cthis.find('.transitioning-out').get(0).pause();
                                    }
                                    cthis.find('.transitioning-out').remove();

                                    cthis.find('.the-video.js-transitioning-in').addClass('transitioning-in');


                                    if(aux_wasPlaying){
                                        playMovie();
                                    }
                                },500)

                            })

                            setTimeout(function(){

                            },100);
                        }


                        _t.parent().children().removeClass('active');
                        _t.addClass('active');
                    }
                }
                if(e.type=='mouseup'){

                    clearTimeout(inter_mousedownscrubbing);

                    //console.info('window mouseup');
                    volume_mouse_down = false;
                    scrub_mouse_down = false;
                }
            }
            function enable_mousedown_scrubbing(){

                scrub_mouse_down = true;
            }

            function handle_mousemove(e){
                //console.info(e.pageX, e.pageY);
                // console.info('mousemove', is_fullscreen, o.settings_disable_mouse_out, o.settings_disable_mouse_out_for_fullscreen, o.settings_mouse_out_delay_for_fullscreen);
                cthis.removeClass('mouse-is-out');
                mouseover = true;

                if(volume_mouse_down){
                    handleVolume(e);
                }
                if(scrub_mouse_down){


                    var argperc = (e.pageX - (scrubbar.offset().left)) / (scrubbar.children().eq(0).width());
                    seek_to_perc(argperc);
                }

                if(is_fullscreen){


                    if(o.settings_disable_mouse_out!='on' && o.settings_disable_mouse_out_for_fullscreen!='on') {
                        clearTimeout(inter_removeFsControls);
                        inter_removeFsControls = setTimeout(controls_mouse_is_out, o.settings_mouse_out_delay_for_fullscreen);
                    }

                    if(e.pageX>ww-10 ){
                        controls_are_hovered = false;
                    }
                }
            }

            function controls_mouse_is_out(){

                // console.info('controls_mouse_is_out', paused, controls_are_hovered, cthis);
                if(paused==false && (controls_are_hovered==false|| is_android() )){

                    cthis.removeClass('mouse-is-over');
                    cthis.addClass('mouse-is-out');
                }
                mouseover = false;
            }

            function event_video(e){

                // console.info('event_video', e, e.type);

                if(e.type=='play'){

                    played = true;

                    if(is_ios() || is_android()){
                        cthis.find('.controls').eq(0).css('pointer-events', 'auto');
                    }
                    setup_skipad();
                }
            }

            function click_mutecontrols(e){
                var _t = $(this);
                _t.toggleClass('active');

                if(_t.hasClass('active')){
                    lastVolume = getVolume();
                    setupVolume(0, {'call_from':'mute'});
                }else{

                    setupVolume(lastVolume, {'call_from':'unmute'});
                }
            }

            function handle_key(e){
                console.info(e);


            }

            function setup_subtitle(){
                var subtitle_input = '';
                if(cthis.children('.subtitles-con-input').length>0){
                    subtitle_input = cthis.children('.subtitles-con-input').eq(0).html();
//                    console.info(subtitle_input);
                    parse_subtitle(subtitle_input);
                }else{
                    if(o.settings_subtitle_file!=''){
                        $.ajax({
                            url: o.settings_subtitle_file
                            , success: function(response){
//                                console.info(response);
                                subtitle_input = response;
                                parse_subtitle(subtitle_input);
                            }
                        });
                    }
                }





            }
            function parse_subtitle(arg){
                var regex_subtitle = /([0-9]+(?:\.[0-9]*)?)[\s\S]*?((.*)--[>|\&gt;](.*))[\n|\r]([\s\S]*?)[\n|\r]/g;
                var arr_subtitle = [];
                cthis.append('<div class="subtitles-con"></div>')
                while(arr_subtitle=regex_subtitle.exec(arg)){
//                    console.info(arr_subtitle);

                    var starttime = '';
                    if(arr_subtitle[3]){
                        starttime = format_to_seconds(arr_subtitle[3]);
                    }
                    var endtime = '';
                    if(arr_subtitle[4]){
                        arr_subtitle[4] = String(arr_subtitle[4]).replace('gt;', '');
                        endtime = format_to_seconds(arr_subtitle[4]);
                    }

                    var cnt = '';
                    if(arr_subtitle[5]){
                        cnt = arr_subtitle[5];
                    }

                    cthis.children('.subtitles-con').append('<div class="dzstag subtitle-tag" data-starttime="'+starttime+'" data-endtime="'+endtime+'">'+cnt+'</div>');
                }
                arrTags = cthis.find('.dzstag');

            }

            function format_to_seconds(arg){
//                console.info(arg);
                var argsplit = String(arg).split(':');
                argsplit.reverse();
                var secs = 0;
//                console.info(argsplit);
                if(argsplit[0]){
                    argsplit[0] = String(argsplit[0]).replace(',','.');
                    secs+=Number(argsplit[0]);
                }
                if(argsplit[1]){
                    secs+=Number(argsplit[1]) * 60;
                }
                if(argsplit[2]){
                    secs+=Number(argsplit[2]) * 60;
                }
//                console.info(secs);

                return secs;
            }


            function click_coverImage(e){
                //console.log(cthis.find('.cover-image'));


                // console.info('click_coverImage()', o.type, wasPlaying);

                if(o.type!='image'){

                    if(wasPlaying==false){

                        playMovie({
                            'call_from':'click coverImage'
                        });
                    }else{
                        pauseMovie();
                    }
                }
            }
            function click_videoOverlay(e){

                // console.info('click_videoOverlay', cthis, 'wasPlaying',wasPlaying);



                if(controls){
                    controls.enabled = true;
                    if(is_mobile()){
                        setTimeout(function(){

                            // controls.enabled = false;
                        },3000);
                    }
                }

                if(o.is_ad=='on'){


                    // console.info('initial_played - ',initial_played);

                    if(initial_played==false){

                        onPlayPause();

                        if(cthis.hasClass('mobile-pretime-ad') && cthis.hasClass('first-played')==false){
                            return false;
                        }
                    }


                    if(o.ad_link){
                        window.open(o.ad_link);
                        return false;
                    }else{

                        return false;
                    }
                }else{

                    if(wasPlaying===false){
                        playMovie({
                            'call_from':'_click_videoOverlay()'
                        });
                    }else{
                        pauseMovie({
                            'call_from':'_click_videoOverlay()'
                        });
                    }

                }



            }

            function click_hd() {
                var _t = $(this);
                //console.log(_t);
                if (_t.hasClass('active')) {
                    _t.removeClass('active');
                    if ($.inArray('large', yt_qualArray) > -1) {
                        video.setPlaybackQuality('large');
                    } else {
                        if ($.inArray('medium', yt_qualArray) > -1) {
                            video.setPlaybackQuality('medium');
                        } else {
                            if ($.inArray('small', yt_qualArray) > -1) {
                                video.setPlaybackQuality('small');
                            }
                        }
                    }

                } else {
                    _t.addClass('active');
                    if ($.inArray('hd1080', yt_qualArray) > -1) {
                        video.setPlaybackQuality('hd1080');
                    }else{

                        if ($.inArray('hd720', yt_qualArray) > -1) {
                            video.setPlaybackQuality('hd720');
                        }
                    }
                }
            }

            function check_fullscreen(e) {
                console.log('check_fullscreen()', e.keyCode=='27',is_fullscreen, document.fullscreen, document.mozFullScreen);
                var identifiers_fs = [document.fullscreen, document.mozFullScreen, document.webkitIsFullScreen];
                for (var i = 0; i < identifiers_fs.length; i++) {
                    if (identifiers_fs[i] != undefined) {
                        //console.log(identifiers_fs[i]);
                        if (identifiers_fs[i] == true) {
                            is_fullscreen = 1;
                            cthis.addClass('is_fullscreen');
                        }
                        if (identifiers_fs[i] === false && is_fullscreen == 1) {
                            onFullScreen(null,{
                                'call_from':'check_fullscreen'
                            });
                            //is_fullscreen=0;
                            //console.log(identifiers_fs[i], is_fullscreen);

                        }
                    }
                }

                if(o.touch_play_inline=='on'){

                    if(is_ios()){
                        pauseMovie({
                            'call_from':'_touch_play_inline_ios()'
                        });
                    }
                }
            }

            function mouse_is_over(){

                if(controls){
                    controls.enabled = true;
                    if(is_mobile()){
                        setTimeout(function(){

                            // controls.enabled = false;
                        },1000);
                    }
                }
                clearTimeout(inter_removeFsControls);
                cthis.removeClass('mouse-is-out');
                cthis.addClass('mouse-is-over');
            }
            function handleMouseover(e) {

                // console.info('mouseover', e.currentTarget);

                if($(e.currentTarget).hasClass('vplayer')){

                    if(o.settings_disable_mouse_out!='on'){

                        if(fullscreen_just_pressed==false){
                            mouse_is_over();
                        }

                    }
                }
                if($(e.currentTarget).hasClass('fullscreen-button')){
                    draw_fs_canvas(o.controls_fscanvas_hover_bg);
                }


            }
            function handleMouseout(e) {

                if(controls){
                    controls.enabled = false;
                }
                // console.info(' - mouseout - ');

                if(o.type=='youtube' && is_fullscreen){
                    fullscreen_just_pressed = true;

                    setTimeout(function(){
                        fullscreen_just_pressed = false;
                    }, 500)
                }
                if($(e.currentTarget).hasClass('vplayer')){


                    // console.info('settings_disable_mouse_out - ',o.settings_disable_mouse_out);
                    if(o.settings_disable_mouse_out!='on'){



                        clearTimeout(inter_removeFsControls);

                        // console.info('delay - ' ,o.settings_mouse_out_delay_for_fullscreen);
                        inter_removeFsControls = setTimeout(controls_mouse_is_out, o.settings_mouse_out_delay);
                    }
                }
                if($(e.currentTarget).hasClass('fullscreen-button')){
                    draw_fs_canvas(o.controls_fscanvas_bg);
                }

            }
            function handleScrubMouse(e) {
                //console.log(e.type, e);
                var _t = scrubbar;

                if (e.type == 'mousemove') {
                    //console.log(e, e.pageX, jQuery(this).offset().left)
                    var mouseX = (e.pageX - $(this).offset().left) / currScale;
                    //console.log(_t,_t.children('.scrubBox'));
                    var aux = (mouseX / scrubbg_width) * totalDuration;
                    _t.children('.scrubBox').html(formatTime(aux));
                    _t.children('.scrubBox').css({'visibility': 'visible', 'left': (mouseX - 16)});


                }
                if (e.type == 'mouseout') {
                    _t.children('.scrubBox').css({'visibility': 'hidden'});

                }
                if (e.type == 'mouseleave') {
                    _t.children('.scrubBox').css({'visibility': 'hidden'});
                }
                //console.log(mouseX);
            }


            function handleScrub(e) {
                /*
                 if (wasPlaying == false){
                 pauseMovie();
                 }else{
                 //console.log(o.type);
                 playMovie();
                 }
                 */
                //console.log(o.type);
                //return;

                var argperc = (e.pageX - (scrubbar.offset().left)) / (scrubbar.children().eq(0).width());
                seek_to_perc(argperc);
            }

            function seek_to_perc(argperc){
                //console.info('seek_to_perc()',argperc)

                // console.info(argperc);
                if(ad_array.length){
                    for(var i2 = 0; i2<ad_array.length; i2++){
                        if(ad_array[i2].time < argperc){
                            argperc = Number(ad_array[i2].time);
                        }
                    }
                }

                // console.info(argperc);

                if (o.type == 'normal' || o.type == 'audio' || o.type == 'dash') {
                    totalDuration = video.duration;




                    if(isNaN(totalDuration)){
                        return false;
                    }
                    // console.info(totalDuration, argperc)
                    video.currentTime = (argperc) * totalDuration;
                }
                if (o.type == 'youtube') {
                    //console.log(video.getDuration());


                    if(video && video.getDuration){

                        totalDuration = video.getDuration();
                    }else{
                        console.info('vplayer warning, youtube type - youtube api not ready .. ? ');
                        totalDuration = 0;
                    }
                    //console.info(time_curr, totalDuration);

                    // -- no need for seek to perct if video has not started.
                    if(isNaN(totalDuration) || (time_curr==0 && argperc==0) ){
                        return false;
                    }

                    video.seekTo(argperc * totalDuration);


                    console.info('wasPlaying - ',wasPlaying);
                    if(wasPlaying==false){
                        pauseMovie({
                            'call_from':'_seek_to_perc()'
                        });
                    }
                }



                if (o.type == 'vimeo') {
                    //if (/Opera/.test(navigator.userAgent)) {
                    //    return;
                    //}

                    //console.info(initial_played);
                    if(argperc==0 && initial_played){

                        vimeo_data = {
                            "method": "seekTo"
                            ,"value": "0"
                        };

                        if(vimeo_url) {
                            try {
                                video.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);

                                wasPlaying = false;
                                paused=true;
                            } catch (err) {
                                if (window.console) {
                                    console.log(err);
                                }
                            }
                        }
                    }else{

                        if(o.vimeo_is_chromeless=='on'){

                            vimeo_data = {
                                "method": "seekTo"
                                ,"value": (argperc) * totalDuration
                            };

                            if(vimeo_url) {
                                try {
                                    video.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);

                                } catch (err) {
                                    if (window.console) {
                                        console.log(err);
                                    }
                                }
                            }
                        }

                    }
                }

            }

            function tick(pargs){
                // -- enterFrame function

                var margs = {
                    skin_play_check : false
                }

                if(pargs){
                    margs = $.extend(margs,pargs);
                }
                // console.warn('tick');

                if (o.type == 'normal' || o.type == 'audio' || o.type == 'dash' ) {
                    totalDuration = video.duration;
                    time_curr = video.currentTime;

                    //console.log(cthis, video.buffered.end(0), bufferedWidthOffset);

                    if(video && video.buffered && video.readyState>1){
                        bufferedLength=0;
                        try{

                            bufferedLength = (video.buffered.end(0) / video.duration) * (scrubbar.children().eq(0).width() + bufferedWidthOffset);
                        }catch(err){
                            console.log(err);
                        }
                    }


                }
                if (o.type == 'youtube') {
//                    console.log(video.getVideoLoadedFraction())
                    if (video.getVideoLoadedFraction == undefined || video.getVideoLoadedFraction==0) {
                        return false;
                    }
                    if (video.getDuration != undefined) {
                        totalDuration = video.getDuration();
                        time_curr = video.getCurrentTime();
                    }
                    bufferedLength = (video.getVideoLoadedFraction()) * (_scrubBg.width() + bufferedWidthOffset);

//                    console.info(video.getVideoLoadedFraction(), scrubbar, _scrubBg,  (_scrubBg.width() + bufferedWidthOffset), bufferedLength);
                    aux = 0;
                    scrubbar.children('.scrub-buffer').css('left', aux);


                }
                aux = ((time_curr / totalDuration) * (scrubbg_width));

                // console.info(time_curr, totalDuration, scrubbg_width);
                if(aux>scrubbg_width){
                    aux = scrubbg_width;
                }
                aux = parseInt(aux,10);
                // scrubbar.children('.scrub').width(aux);



                var anim_duration = 100;

                if(o.vimeo_is_chromeless=='on'){
                    anim_duration = 300;


                    scrubbar.children('.scrub').css({
                        'width' : aux
                    },{

                    });
                }else{

                    scrubbar.children('.scrub').animate({
                        'width' : aux
                    },{
                        queue:false
                        ,duration: anim_duration
                        ,easing: "linear"

                    });
                }


                if (bufferedLength > -1) {

                    //console.info(bufferedLength, scrubbar.children('.scrub-bg').width(), _scrubBg.width(), bufferedWidthOffset);

                    if(bufferedLength > scrubbg_width+bufferedWidthOffset){
                        bufferedLength = scrubbg_width+bufferedWidthOffset;
                    }
                    scrubbar.children('.scrub-buffer').width(bufferedLength)
                }
                if (_timetext.css('display') != 'none' && (wasPlaying==true || margs.skin_play_check==true) || (o.type=='vimeo' && o.vimeo_is_chromeless=='on')) {

                    var aux35 = formatTime(totalDuration);


                    if(o.design_skin!='skin_reborn'){

                        aux35 = ' / '+aux35;
                    }

                    //console.info(o.design_skin);



                    _timetext.children(".curr-timetext").html(formatTime(time_curr));
                    _timetext.children(".total-timetext").html(aux35);
                }
                if (o.design_enableProgScrubBox == 'on') {
                    scrubbar.children('.scrubBox-prog').html(formatTime(time_curr));
                    // scrubbar.children('.scrubBox-prog').css('left', aux - 16);

                    scrubbar.children('.scrubBox-prog').animate({
                        'left' : aux - 16
                    },{
                        queue:false
                        ,duration: 100
                        ,easing: "linear"

                    })
                }


                // console.info('o.playfrom - ',o.playfrom);

                if(o.playfrom=='last'){
                    if(typeof Storage!='undefined'){
                        localStorage['dzsvp_'+the_player_id+'_lastpos'] = time_curr;
                        // console.warn('dzsvp_'+the_player_id+'_lastpos');
                    }
                }

            }



            function handleVolume(e) {




                _volumeControls = cthis.find('.volumecontrols').children();
                if ((e.pageX - (_volumeControls.eq(1).offset().left)) >= 0) {

                    // console.info("CLICKED INISDE VOLUME");

                    aux = (e.pageX - (_volumeControls.eq(1).offset().left)) / currScale;

                    //_volumeControls.eq(2).height(24)
                    _volumeControls.eq(2).css('visibility', 'visible')
                    _volumeControls.eq(3).css('visibility', 'hidden')

                    setupVolume(aux / _volumeControls.eq(1).width(), {'call_from':'handleVolume'});
                } else {
                    if (_volumeControls.eq(3).css('visibility') == 'hidden') {
                        if (o.type == 'normal' || o.type == 'audio') {
                            lastVolume = video.volume;
                            video.volume = 0;
                        }
                        if (o.type == 'youtube') {
                            lastVolume = video.getVolume();
                            video.setVolume(0);
                        }



                        if (o.type == 'vimeo') {
                            vimeo_data = {
                                "method": "setVolume"
                                , "value": "0"
                            };

                            if (vimeo_url) {
                                try {
                                    video.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);

                                } catch (err) {
                                    if (window.console) {
                                        console.log(err);
                                    }
                                }
                            }
                        }
                        _volumeControls.eq(3).css('visibility', 'visible')
                        _volumeControls.eq(2).css('visibility', 'hidden')
                    } else {
                        //console.log(lastVolume);

                        // console.info('o.type');
                        if (o.type == 'normal' || o.type == 'audio') {
                            video.volume = lastVolume;
                        }
                        if (o.type == 'youtube') {
                            video.setVolume(lastVolume);
                        }


                        console.error('lastVolume - ',lastVolume);

                        if (o.type == 'vimeo') {
                            vimeo_data = {
                                "method": "setVolume"
                                , "value": lastVolume
                            };

                            if (vimeo_url) {
                                try {
                                    video.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);

                                } catch (err) {
                                    if (window.console) {
                                        console.log(err);
                                    }
                                }
                            }
                        }
                        _volumeControls.eq(3).css('visibility', 'hidden')
                        _volumeControls.eq(2).css('visibility', 'visible')
                    }
                }

            }

            function getVolume(){


                if (o.type == 'normal') {
                    return video.volume;
                }
                if (o.type == 'youtube') {
                    return (Number(video.getVolume()) / 100);
                }

                return 0;
            }

            function setupVolume(arg, pargs) {

                // -- @arg is ratio 0 - 1


                var margs = {
                    'call_from':'default'
                }

                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                // console.info('setupVolume() - ', arg, margs);
                var volumeControl = cthis.find('.volumecontrols').children();

                if(arg>1){
                    arg = 1;
                }

                var aux = 0;

                if (arg >= 0) {

                    // console.info(o.type);
                    if (o.type == 'normal' || o.type == 'audio'){
                        video.volume = arg;
                    }
                    if (o.type == 'youtube') {
                        aux = arg * 100;
                        video.setVolume(aux);

                    }


                    // console.error('aux - ',arg);

                    if (o.type == 'vimeo') {
                        vimeo_data = {
                            "method": "setVolume"
                            , "value": arg
                        };

                        if (vimeo_url) {
                            try {
                                video.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);

                            } catch (err) {
                                if (window.console) {
                                    console.log(err);
                                }
                            }
                        }
                    }

                }


                if(o.design_skin=='skin_reborn'){
                    arg*=10;
                    arg = Math.round(arg);
                    arg/=10;
                }

                if(arg>1){
                    arg = 1;
                }

                // console.info(volumeControl, arg, volumeControl.eq(1).width(), volumeWidthOffset)


                aux = arg * (volumeControl.eq(1).width() + volumeWidthOffset) ;

                // console.warn(aux);

                if(o.design_skin=='skin_reborn' || o.design_skin=='skin_white'){

                    //console.info(arg, aux);
                    var aux2 = arg*=10;
                    _volumeControls_real.children('.volume_static').children().removeClass('active');
                    //console.info(aux2, _volumeControls_real, _volumeControls_real.children('.volume_static').children())
                    for(var i=0;i<aux2;i++){
                        //console.info(_volumeControls_real, _volumeControls_real.children('.volume_static').children())
                        _volumeControls_real.children('.volume_static').children().eq(i).addClass('active');
                    }


                    _volumeControls_real.children('.volume-tooltip').css({
                        'right': (100- ( aux2*10))+'%'
                    })
                    _volumeControls_real.children('.volume-tooltip').html('VOLUME: '+( aux2*10));

                }else{

                    volumeControl.eq(2).width(aux);
                }


                if (localStorage != null){
                    localStorage.setItem('volumeIndex', arg);
                }
            }

            function formatTime(arg) {
                //formats the time
                var s = Math.round(arg);
                var m = 0;
                if (s > 0) {
                    while (s > 59) {
                        m++;
                        s -= 60;
                    }
                    return String((m < 10 ? "0" : "") + m + ":" + (s < 10 ? "0" : "") + s);
                } else {
                    return "00:00";
                }
            }
            function handleVideoEnd() {
                //console.info('handleVideoEnd() - ' ,cthis, is_fullscreen, o.type, window.fullscreen);

                // -- function on video end

                if(o.type=='vimeo'){


                    if(o.end_exit_fullscreen=='on'){

                        if(document.exitFullscreen) {
                            document.exitFullscreen();
                        } else if(document.mozCancelFullScreen) {
                            document.mozCancelFullScreen();
                        } else if(document.webkitExitFullscreen) {
                            document.webkitExitFullscreen();
                        }
                    }
                }


                if (is_fullscreen == 1 ) {

                    if(o.end_exit_fullscreen=='on'){

                        onFullScreen(); // we exit fullscreen if video has ended on fullscreen
                    }
                    setTimeout(function(){
                        handleResize();
                    },100);
                }


                // console.info('o.type - ',o.type);
                if (o.type == 'video' || o.type == 'normal' || o.type == 'audio'|| o.type == 'dash') {

                    // console.info("loop - ", loop, video);
                    if(loop=='on'){

                        // console.info("YES");
                        seek_to_perc(0);
                        playMovie({
                            'call_from':'play_from_loop'
                        });
                        return false;
                    }
                    if (video) {


                        if(o.settings_video_end_reset_time=='on'){

                            video.currentTime = 0;

                            if(loop!='on'){

                                pauseMovie({
                                    'call_from':'end_video()'
                                });
                                cthis.find('.cover-image').fadeIn('slow');
                            }else{
                                return false;
                            }


                        }
                    }
                }
                if (o.type == 'youtube') {
                    if(loop=='on'){

                        // console.info("YES");
                        seek_to_perc(0);
                        setTimeout(function(){

                            playMovie({
                                'call_from':'play_from_loop'
                            });
                        },1000);

                        suspend_state_2_for_loop = true;
                        setTimeout(function(){
                            suspend_state_2_for_loop = false;
                        },1500);
                        return false;
                    }
                    //console.log(video.getDuration())
                    if (video) {
                        if (video && video.pauseVideo) {
                            wasPlaying = false;
                            if(o.settings_video_end_reset_time=='on'){

                                // -- youtube already shows cover photo and replay button - maybe no need

                                //seek_to_perc(0);
                                //
                                //video.pauseVideo();
                            }
                        }
                    }
                }


                console.log("PASS EVENT TO GALLERY", cthis, o.gallery_object);
                if (o.gallery_object ) {
                    if(typeof(o.gallery_object.get(0))!='undefined'){
                        o.gallery_object.get(0).videoEnd();
                    }

                }
                if (o.parent_player) {
                    if(o.parent_player.get(0)){

                        console.info(o.parent_player.get(0));
                        o.parent_player.get(0).api_ad_end();
                    }

                }

                if(action_video_end){
                    action_video_end(cthis);
                }

            }
            function handleResize(e, pargs) {
                // console.log('vplayer triggered resize',e,pargs);
                //return;


                var margs = {
                    'force_resize_gallery' : false
                    ,'call_from' : 'default'
                };

                if(pargs){
                    margs = $.extend(margs,pargs);
                }





                videoWidth = cthis.width();
                videoHeight = cthis.height();




                if(margs.call_from=='recheck_sizes'){

                    // console.info('recheck_sizes player', Math.abs(last_videoHeight-videoHeight), Math.abs(last_videoWidth-videoWidth));
                    if(Math.abs(last_videoHeight-videoHeight)<4 && Math.abs(last_videoWidth-videoWidth)<4){


                        return false;
                    }

                }

                last_videoWidth = videoWidth;
                last_videoHeight = videoHeight;


                //console.info(cthis, o.responsive_ratio, isNaN(o.responsive_ratio), videoWidth, videoHeight);

                //console.info(cthis, o.is_ad);
                // console.info('responsive_ratio', o.responsive_ratio, auxh);

                // console.info('o.responsive_ratio', o.responsive_ratio);
                if(isNaN(o.responsive_ratio)===true){
                    o.responsive_ratio = 0.5675;
                }
                if(isNaN(o.responsive_ratio)===false && o.responsive_ratio>0){

                    var auxh = o.responsive_ratio * videoWidth;


                    // console.warn('o.gallery_object -' , o.gallery_object, (cthis.hasClass('currItem') && o.is_ad!='on'), margs.force_resize_gallery)
                    if('o.gallery_object -',o.gallery_object && ( (cthis.hasClass('currItem') && o.is_ad!='on') || margs.force_resize_gallery)  ){
                        //console.warn("RESIZE IT!",cthis, auxh , o.responsive_ratio, videoWidth, videoHeight);
                        if(o.gallery_object.get(0) && o.gallery_object.get(0).api_responsive_ratio_resize_h){
                            o.gallery_object.addClass('responsive-ratio-smooth');
                            o.gallery_object.get(0).api_responsive_ratio_resize_h(auxh, {
                                caller: cthis
                            });
                        }
                    }else{
                        //console.info('single player', o.responsive_ratio);

                        //console.warn(o);

                        if(o.is_ad!='on'){

                            cthis.height(o.responsive_ratio * cthis.width());
                        }
                    }
                }
                if (is_ios()) {
                    //ios has a nasty bug wbhen the parent is scaled - iframes scale too
                    if (undefined != _vgparent) {
                        var aux = (_vgparent.get(0).var_scale);
                        //console.log(cthis);
                        //cthis.children('iframe').width((1/aux) * videoWidth); cthis.children('iframe').height((1/aux) * videoHeight);

                    }
                }




                if(videoWidth<600){
                    cthis.addClass('under-600');

                    if(o.design_skin=='skin_aurora'){
                        o.design_scrubbarWidth = original_scrubwidth - 10;
                    }



                    if(videoWidth<421){
                        cthis.addClass('under-420');

                        if(o.design_skin=='skin_aurora'){
                            o.design_scrubbarWidth = original_scrubwidth - 10;
                        }
                    }else{
                        cthis.removeClass('under-420');
                        if(o.design_skin=='skin_aurora'){
                            o.design_scrubbarWidth = original_scrubwidth ;
                        }
                    }
                }else{
                    cthis.removeClass('under-600');
                    if(o.design_skin=='skin_aurora'){
                        o.design_scrubbarWidth = original_scrubwidth ;
                    }
                }



                if (is_fullscreen === 1) {
                    ww = $(window).width();
                    wh = window.innerHeight;
                    resizePlayer(ww, wh);


                    cthis.css('transform', '');
                    currScale = 1;
                } else {

                    //console.info(cthis, videoWidth,videoHeight);
                    resizePlayer(videoWidth, videoHeight);
                }

            }
            function handleKeyPress(e) {
                //-check if space is pressed for pause

                // console.info('keypress player - ',e, e.which, e.charCode,e.keyCode);

                if (mouseover ){
                    if(e.charCode==27 || e.keyCode==27){
                        setTimeout(function(){
                            // console.info("ESC KEY from player");
                            handleResize(null, {
                                'call_from':'esc_key'
                            });
                        },300);
                    }
                    if(e.charCode == 32 || e.keyCode==32) {
                        onPlayPause();

                        e.stopPropagation();
                        e.preventDefault();
                    }
                }
            }

            function vimeo_windowMessage(e) {
                //--- we receive iframe messages from vimeo here
                var data, method;
                //console.log(e);

                if (e.origin != 'https://player.vimeo.com' && e.origin != 'http://player.vimeo.com') {
                    return;
                }

                // console.info('received event from vimeo - ',e);
                vimeo_url = '';
                vimeo_url = $(video).attr('src').split('?')[0];

                vimeo_is_ready = true;

                if(String(vimeo_url).indexOf('http')!=0){
                    vimeo_url = 'https:'+vimeo_url;
                }

                //console.info('vimeo_url',vimeo_url);
                try {
                    data = JSON.parse(e.data);
                    method = data.event || data.method;

                    // console.warn(data);

                    if(data.data.duration){
                        time_curr = data.data.seconds;
                        totalDuration = data.data.duration;
                    }
                }
                catch (e) {
                    //fail silently... like a ninja!
                }


                //if(cthis.attr)
                if (data.player_id && dataSrc != data.player_id.substr(11)) {
                    return;
                }

                if (data != undefined) {
                    if (data.event == 'pause') {
                        pauseMovie_visual();
                    }
                    if (data.event == 'ready') {
                        //console.log(cthis);
                        if (autoplay == 'on') {
                            // -- we don't force play Movie because we already set autoplay to 1 on the iframe
                            //playMovie();
                        }
                        vimeo_data = {
                            "method": "addEventListener",
                            "value": "finish"
                        };

                        //console.info(vimeo_url);
                        if(video && video.contentWindow&&vimeo_url){

                            video.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);
                        }
                        vimeo_data = {
                            "method": "addEventListener",
                            "value": "pause"
                        };

                        //console.info(vimeo_url);
                        if(video && video.contentWindow&&vimeo_url){

                            video.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);
                        }
                        vimeo_data = {
                            "method": "addEventListener",
                            "value": "playProgress"
                        };

                        //console.info(vimeo_url);
                        if(video && video.contentWindow&&vimeo_url){

                            video.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);
                        }

                        //if(video){
                        //
                        //    video.addEvent('pause', function(){
                        //        console.info('paUSE!!!');
                        //    });
                        //}


                        cthis.addClass('dzsvp-loaded');
                        if (o.gallery_object != null) {
                            if (typeof(o.gallery_object.get(0)) != 'undefined') {
                                o.gallery_object.get(0).api_video_ready();
                            }
                        }


                    }
                    if (data.event == 'playProgress') {
                        initial_played = true;


                        if(paused==true){

                            playMovie_visual();
                        }

                    }
                    if (data.event == 'finish') {
                        handleVideoEnd();
                    }
                }
            }
            function onPlayPause() {

                // console.log(busy_playpause_mistake, 'onPlayPause, paused - ', paused,' wasPlaying - ',wasPlaying);
                if(busy_playpause_mistake){
                    return false;
                }


                busy_playpause_mistake=true;

                if(inter_clear_playpause_mistake){
                    clearTimeout(inter_clear_playpause_mistake);
                }
                inter_clear_playpause_mistake = setTimeout(function(){
                    busy_playpause_mistake = false;
                }, 300);
                //return;

                if (o.type == 'youtube' && video.getPlayerState && (video.getPlayerState() == 2||video.getPlayerState() == -1)) {
                    paused = true;
                }

                // console.info('playpause, paused - ', paused);
                if (paused) {
                    playMovie({
                        'call_from':'onPlayPause'
                    });
                } else {
                    pauseMovie({
                        'call_from':'onPlayPause'
                    });
                }


            }


            function onFullScreen(e, pargs) {
                // -- is_fullscreenscreen trigger event



                var margs = {
                    'call_from' : 'event'
                };

                if(pargs){
                    margs = $.extend(margs,pargs);
                }



                console.info('click fullscreen',e, margs);



                var aux = cthis.get(0);
                var _t = $(this);



                // console.info(e);

                if(cthis.hasClass('is-ad')){
                    if(e && e.currentTarget){
                        if(e.currentTarget.className.indexOf('video-overlay')>-1){
                            return false;
                        }
                    }
                }

                //totalWidth= $(window).width()
                //totalHeight= $(window).height()

                videoWidth = cthis.outerWidth();
                videoHeight = cthis.outerHeight();

                // console.info(e, is_fullscreen);
                //console.log(_t, _t.parent().parent().parent().parent().parent())

                // console.info(is_fullscreen, document.fullscreenEnabled, document.msFullscreenEnabled);
                // if (document.fullscreenEnabled) {
                //     is_fullscreen = 1;
                // } else if (document.mozFullscreenEnabled) {
                //     is_fullscreen = 1;
                // } else if (document.webkitFullscreenEnabled) {
                //     is_fullscreen = 1;
                // }else if (document.msFullscreenEnabled) {
                //     is_fullscreen = 1;
                // }

                // console.info(is_fullscreen);

                if(is_ios() && o.touch_play_inline=='off'){
                    playMovie({
                        'call_from':'onfullscreen ios'
                    });
                    return false;
                }

                if (is_fullscreen == 0) {


                    // (is_ios() && o.settings_ios_usecustomskin=='on') == false
                    if( 1 ){

                        is_fullscreen = 1;

                        cthis.addClass('is_fullscreen');
                        cthis.addClass('is-fullscreen');


                        if(is360 && is_ios()){


                            setTimeout(function(){

                                handleResize(null, {
                                    'call_from':'fullscreen 360'
                                });
                            },300)

                        }else{

                            if(is_ios() && video.webkitEnterFullscreen){

                                // console.info('hmm ios fullscreen')
                                video.webkitEnterFullscreen();
                                return false;
                            }

                            var elem = aux;
                            console.info('elem - ',elem, elem.requestFullScreen, elem.msRequestFullscreen, elem.webkitRequestFullScreen);


                            if (elem.requestFullScreen) {
                                elem.requestFullScreen();
                            } else if (elem.mozRequestFullScreen) {
                                elem.mozRequestFullScreen();
                            } else if (elem.webkitRequestFullScreen) {
                                elem.webkitRequestFullScreen();
                            }else if (elem.msRequestFullscreen) {
                                elem.msRequestFullscreen();
                            } else {
                                if(o.gallery_object){
                                    o.gallery_object.find('.the-logo').hide();
                                    o.gallery_object.find('.gallery-buttons').hide();
                                }

                            }

                            //jQuery('body').css('overflow', 'hidden');
                            totalWidth= window.screen.width;
                            totalHeight= window.screen.height;
                            //console.log(totalWidth, totalHeight);

                            resizePlayer(totalWidth,totalHeight);
                            /*
                             cthis.css({
                             'position' : 'fixed',
                             'z-index' : 9999,
                             'left' : '0px',
                             'top' : '0px'
                             //,'width': totalWidth
                             //,'height': totalHeight
                             })
                             if(cthis.find('.audioImg').length>0){
                             cthis.find('.audioImg').css({
                             'width' : totalWidth
                             ,'height' : totalHeight
                             })
                             }
                             */

                            if(is_ie()){
                                setTimeout(handleResize, 300);
                            }



                            if(o.design_skin=='skin_reborn') {
                                cthis.find('.full-tooltip').eq(0).html('EXIT FULLSCREEN');
                            }




                            fullscreen_just_pressed = true;

                            setTimeout(function(){
                                fullscreen_just_pressed = false
                            },700)

                            console.info(o.settings_disable_mouse_out, o.settings_disable_mouse_out_for_fullscreen, o.settings_mouse_out_delay_for_fullscreen, cthis)
                            if(o.settings_disable_mouse_out!='on' && o.settings_disable_mouse_out_for_fullscreen!='on') {

                                clearTimeout(inter_removeFsControls);
                                inter_removeFsControls = setTimeout(controls_mouse_is_out, o.settings_mouse_out_delay_for_fullscreen);
                            }

                            if (o.gallery_object) {
                                //dispatchEvent('goFullscreen');
                                //_t.parent().parent().parent().parent().parent().turnFullscreen();

                                if (o.gallery_object != null) {
                                    //o.videoGalleryCon.turnFullscreen();
                                }
                            }
                        }

                    }

                } else {

                    //console.info('ceva');
                    is_fullscreen = 0;
                    cthis.addClass('remove_fullscreen');
                    cthis.removeClass('is_fullscreen');
                    cthis.find('.vplayer.is_fullscreen').removeClass('is_fullscreen');
                    cthis.removeClass('is-fullscreen');
                    var elem = document;
                    if (elem.cancelFullScreen) {
                        elem.cancelFullScreen();
                    } else if (elem.exitFullscreen) {
                        elem.exitFullscreen();
                    }else if (elem.mozCancelFullScreen) {
                        elem.mozCancelFullScreen();
                    } else if (elem.webkitCancelFullScreen) {
                        elem.webkitCancelFullScreen();
                    } else if (elem.msExitFullscreen) {
                        elem.msExitFullscreen();
                    }

                    if(o.design_skin=='skin_reborn') {
                        cthis.find('.full-tooltip').eq(0).html('FULLSCREEN');
                    }



                    //console.info(cthis, videoWidth,videoHeight);
                    handleResize();


                    if(is_ie() || is_firefox()){
                        setTimeout(handleResize, 300);
                    }
                    if(is_ios() ){
                        setTimeout(handleResize, 1000);
                    }


                }
            }

            function resizePlayer(warg, harg) {
                //console.log(cthis);


                calculateDims(warg, harg);

                //console.log(warg);

//                console.log(_scrubBg, warg, o.design_scrubbarWidth, (warg + o.design_scrubbarWidth));

                if(the_player_id=='debug-video'){

                    //console.info(cthis, the_player_id, warg, o.design_scrubbarWidth);
                }

                //console.info(cthis, warg, o.design_scrubbarWidth);

                if(_scrubBg) {

                    _scrubBg.css({
                        'width' : (warg + o.design_scrubbarWidth)
                    });

                    if(o.design_skin=='skin_aurora' || o.design_skin=='skin_avanti' || o.design_skin=='skin_default' || o.design_skin=='skin_white'){
                        _scrubBg.css({
                            'width': '100%'
                        });
                    }

                    scrubbg_width = _scrubBg.width();

                }
                if(is360){

                    if(renderer){


                        renderer.setSize(warg, harg);
                        camera.aspect = warg/harg;
                    }
                }


                infoPosX = parseInt(_controlsDiv.find('.infoText').css('left'));
                infoPosY = parseInt(_controlsDiv.find('.infoText').css('top'));
            }


            function calculateDims(warg, harg){

                // console.info('vplayer calculateDims()', warg, harg, o.design_skin);
                if(o.design_skin!='skin_bigplay'){
                    /*
                     _controlsDiv.find('.background').css({
                     'width': warg + parseInt(o.design_background_offsetw)
                     })
                     */
                }






                if(o.type=='normal'){

                    if(video){
                        if(video.videoWidth){

                            natural_videow =video.videoWidth;
                        }
                        if(video.videoHeight){

                            natural_videoh =video.videoHeight;
                        }
                    }else{
                        console.info('video not found ? problem');
                    }

                }
                if(cthis.hasClass('pattern-video')){
                    // console.warn('o.type - ',o.type);
                    if(o.type=='normal'){
                        // console.warn("HMM", natural_videow, totalWidth, natural_videoh, videoHeight);


                        if(natural_videow){

                            var nr_w = Math.ceil(totalWidth/natural_videow);
                            var nr_h = Math.ceil(videoHeight/natural_videoh);

                            // console.warn('nr_w - ',nr_w);
                            // console.warn('nr_h - ',nr_h);


                            for(var i=0;i<nr_w;i++){
                                for(var j=0;j<nr_h;j++){


                                    // console.error(i,j);


                                    if( (i==0 && j==0) || (cthis.find('video[data-dzsvgindex="'+i+''+j+'"]').length)){
                                        continue;
                                    }
                                    $(video).after($(video).clone());
                                    $(video).next().attr('data-dzsvgindex',String(i)+String(j));
                                    $(video).next().get(0).play();
                                    $(video).next().css({
                                        'left':(i * natural_videow)
                                        ,'top':(j * natural_videoh)
                                    })

                                }
                            }





                            if(nr_w){
                                for(var i = 0 ;i<nr_w;i++){

                                }
                            }

                        }
                    }
                }

                /*
                 _controlsDiv.css({
                 'width': warg
                 });
                 */
            }

            function check_if_ad_must_be_played(){

                // console.info('check_if_ad_must_be_played()', o.gallery_object, cthis.attr('data-adsource'), cthis.data('adplayed'));
                if(cthis.attr('data-adsource') && cthis.data('adplayed')!='on'){



                    if(is_ios()){
                        setTimeout(function(){
                            // console.info("PAUSE VIDEO FOR AD")
                            pauseMovie({
                                'call_from':'check_if_ad_must_be_played'
                            });

                            if(o.type=='youtube'){
                                video.stopVideo();
                            }

                            seek_to_perc(0);
                            // if(video && video.webkitExitFullScreen){
                            //
                            //     video.webkitExitFullScreen();
                            //     document.webkitExitFullscreen();
                            // }
                        },1000);
                    }


                    if(o.gallery_object && o.gallery_object.get(0) && o.gallery_object.get(0).api_setup_ad){

                        o.gallery_object.get(0).api_setup_ad(cthis);

                        cthis.data('adplayed','on');

                        has_ad_to_play = false;

                        return false;
                    }

                }

            }

            function playMovie(pargs) {
                // console.info('playMovie() - ',pargs, cthis, o.type, 'paused - ',paused);
                //console.info(o.type);


                var margs = {
                    'call_from': 'default'
                    // ,'setPlaybackQuality':''
                };

                if(pargs){
                    margs = $.extend(margs,pargs);
                }

                play_commited = true;
                if(cthis.hasClass('dszvp-loaded')==false && o.type!='vimeo'){
                    setTimeout(function(){
                        // -- check if play still commited

                        // console.info('check if play still commited - ',play_commited, cthis);
                        if(play_commited){

                            margs.call_from = margs.call_from + ' recommit';
                            playMovie(margs);
                        }
                    },500);
                    return false;
                }

                if(margs.call_from=='play_only_on_desktop'){
                    if(is_mobile()){
                        return false;
                    }
                }



                cthis.find('.cover-image').fadeOut('fast');



                // console.warn('playMovie()', cthis, o.type,paused, initial_played, margs);

                if(o.settings_disableVideoArray!='on'){
                    for(var i=0;i< dzsvp_players_arr.length;i++){
//                        console.info(dzsvp_players_arr);
                        if(dzsvp_players_arr[i].get(0).externalPauseMovie!=undefined){
                            dzsvp_players_arr[i].get(0).externalPauseMovie({
                                'call_from':'external_pauseMovie()'
                            });
                        }
                    }
                }

                // check_if_ad_must_be_played();




                if (o.type == 'vimeo') {
                    vimeo_data = {
                        "method": "play"
                    };
                    // console.info('ceva',vimeo_url);

                    if(video&&video.contentWindow&&vimeo_url){

                        video.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);
                    }


                }
                //return;



                if(o.try_to_pause_zoomsounds_players=='on'){
                    //console.info(dzsap_list);
                    for (i = 0; i < dzsap_list.length; i++) {

                        //                    console.info(!is_ie8(), dzsap_list[i].get(0), typeof dzsap_list[i].get(0)!="undefined", typeof dzsap_list[i].get(0).api_pause_media!="undefined")
                        if (!is_ie8() && typeof dzsap_list[i].get(0) != "undefined" && typeof dzsap_list[i].get(0).api_pause_media != "undefined" && dzsap_list[i].get(0) != cthis.get(0)) {

                            //console.info('try to pause', dzsap_list[i].get(0),dzsap_list[i].data('type_audio_stop_buffer_on_unfocus'))
                            if (dzsap_list[i].data('type_audio_stop_buffer_on_unfocus') && dzsap_list[i].data('type_audio_stop_buffer_on_unfocus') == 'on') {
                                dzsap_list[i].get(0).api_destroy_for_rebuffer();
                            } else {

                                dzsap_list[i].get(0).api_pause_media({
                                    'audioapi_setlasttime': false
                                });
                            }
                            window.dzsap_player_interrupted_by_dzsvg = dzsap_list[i].get(0);
                        }
                    }
                }

//                console.info(cthis, 'playmovie', video, o.type);;

                if (o.type == 'normal' || o.type == 'audio'|| o.type == 'dash'){
                    video.play();

                    if(cthis.hasClass('pattern-video')){
                        cthis.find('.the-video').each(function(){
                            var _t = $(this);

                            // console.warn('_t - ',_t, margs);
                            if(margs.call_from=='play_from_loop'){

                                _t.get(0).currentTime=0;
                            }
                            _t.get(0).play();
                        })
                    }
                }
                if (o.type == 'youtube'){



                    //yt_qualCurr = video.getPlaybackQuality();
                    //
                    //console.info(yt_qualCurr);

                    // console.info('paused - ',paused);

                    if(paused==false){
                        return false;
                    }

                    if(video.playVideo!=undefined && video.getPlayerState && video.getPlayerState!=1){
                        video.playVideo();
                    }
                }



                wasPlaying = true;
                paused=false;
                initial_played=true;
                //console.log(wasPlaying);

                cthis.trigger('videoPlay');


                playMovie_visual();
                check_one_sec();


                //console.info(o.action_video_view);
                if(action_video_view){
                    if(view_sent == false){
                        action_video_view(cthis,video_title);
                        view_sent = true;
                    }
                }



            }

            function playMovie_visual(){

                //console.warn("playMovie_visual()");


                // playcontrols.children().eq(0).fadeOut('fast');
                // playcontrols.children().eq(2).fadeIn('fast');
                //
                // if(cthis.hasClass('skin_aurora')==false && cthis.hasClass('skin_bigplay')==false && cthis.hasClass('skin_pro')==false){
                //
                //     playcontrols.children().eq(1).fadeOut('fast');
                //     playcontrols.children().eq(3).fadeIn('fast');
                // }


                cthis.addClass('first-played');

                if(o.is_ad=='on'){

                    o.parent_player.removeClass('pretime-ad-setuped');


                    console.info('ad, gallery_object - ',o.gallery_object);

                    if(o.parent_player.get(0) && o.parent_player.get(0).gallery_object){
                        $(o.parent_player.get(0).gallery_object).removeClass('pretime-ad-setuped')
                    }

                }



                if (o.settings_disableControls != 'on') {
                }

                if(o.google_analytics_send_play_event=='on' && window._gaq && google_analytics_sent_play_event==false){
                    //if(window.console){ console.info( 'sent event'); }
                    window._gaq.push(['_trackEvent', 'Video Gallery Play', 'Play', 'video gallery play - '+cthis.attr('data-source')]);
                    google_analytics_sent_play_event = true;
                }


                if(o.settings_disable_mouse_out!='on'){



                    if(is_mobile()){
                        clearTimeout(inter_removeFsControls);
                        inter_removeFsControls = setTimeout(controls_mouse_is_out, o.settings_mouse_out_delay_for_fullscreen);
                    }

                }


                cthis.addClass('is-playing');
                if(o.gallery_object && o.gallery_object.get(0) && o.gallery_object.get(0).api_played_video){
                    o.gallery_object.get(0).api_played_video();
                }

                paused=false;
                wasPlaying = true;
                initial_played=true;


                if(action_video_play){
                    action_video_play(cthis,video_title);
                }
            }

            function pauseMovie_visual(){

                //
                // if(cthis.hasClass('skin_aurora')==false && cthis.hasClass('skin_bigplay')==false && cthis.hasClass('skin_pro')==false){
                //
                //     playcontrols.children().eq(1).fadeIn('fast');
                //     playcontrols.children().eq(3).fadeOut('fast');
                // }
                //
                // playcontrols.children().eq(2).fadeOut('fast');
                // playcontrols.children().eq(0).fadeIn('fast');



                wasPlaying = false;
                paused=true;


                cthis.removeClass('is-playing');

                if(action_video_pause){
                    action_video_pause(cthis,video_title);
                }

            }

            function pauseMovie(pargs) {

                //console.info(o.type, paused);
                //if(o.type!='vimeo' && paused==true){
                //    return;
                //}

                //console.info('initial_played', initial_played);

                var margs = {
                    'call_from': 'default'
                };

                if(pargs){
                    margs = $.extend(margs,pargs);
                }




                play_commited = false;
                if(o.try_to_pause_zoomsounds_players=='on'){
                    if(window.dzsap_player_interrupted_by_dzsvg){

                        window.dzsap_player_interrupted_by_dzsvg.api_play_media({
                            'audioapi_setlasttime': false
                        });
                        window.dzsap_player_interrupted_by_dzsvg = null;
                    }
                }

                // console.warn('pauseMovie() - ', cthis, o.type,paused, initial_played, margs);

                if(initial_played==false){
                    return false;
                }
                suspend_state_2_for_loop = true;
                setTimeout(function(){
                    suspend_state_2_for_loop = false;
                },1500);

                //console.info(initial_played);

                pauseMovie_visual();

                if (o.type == 'normal' || o.type == 'audio' || o.type=='dash') {
                    if(video!=undefined){
                        video.pause();
                    }else{
                        if(window.console != undefined){ console.info('warning: video undefined') };
                    }
                }
                if (o.type == 'youtube') {
                    //return false;

                    //console.info('pause YOUTUBE',video ,video.pauseVideo);

                    if (video && video.pauseVideo) {

                        try {
                            video.pauseVideo();
                        } catch (err) {
                            if (window.console) {
                                console.log(err);
                            }
                        }
//                        console.info('pauseMovie',video, video.pauseVideo, paused);
                    }
                }

                if (o.type == 'vimeo') {
                    //if (/Opera/.test(navigator.userAgent)) {
                    //    return;
                    //}
                    vimeo_data = {
                        "method": "pause"
                    };

                    if(vimeo_url) {
                        try {
                            video.contentWindow.postMessage(JSON.stringify(vimeo_data), vimeo_url);

                            //console.info(vimeo_data, vimeo_url);
                            wasPlaying = false;
                            paused=true;
                        } catch (err) {
                            if (window.console) {
                                console.log(err);
                            }
                        }
                    }
                    return;
                }


                wasPlaying = false;
                paused=true;

                mouse_is_over();

                cthis.removeClass('is-playing');
            }

            function reinit_cover_image(){

                cthis.find('.cover-image').fadeIn('fast');
            }

            //console.log(cthis);
            try {
                cthis.get(0).checkYoutubeState = function() {
                    if (o.type == 'youtube' && video.getPlayerState != undefined) {
                        //console.log("ceva", cthis, video.getPlayerState());
                        if (video.getPlayerState && video.getPlayerState() == 0) {
                            handleVideoEnd();
                        }
                    }
                }

            } catch (err) {
                if (window.console)
                    console.log(err);
            }
            /*
             window.checkYoutubeState=function(){
             // - we check if video youtube has ended so we can go to the next one

             }
             */

        }); // end each

    }


    window.dzsvp_init = function(selector, settings) {
        //console.info($(selector), settings);


        if(typeof(settings)!="undefined" && typeof(settings.init_each)!="undefined" && settings.init_each==true ){
            var element_count = 0;
            for (var e in settings) { element_count++; }
            if(element_count==1){
                settings = undefined;
            }

            $(selector).each(function(){
                var _t = $(this);
                _t.vPlayer(settings)
            });
        }else{
            $(selector).vPlayer(settings);
        }


    };


})(jQuery);




if(typeof window.onYouTubePlayerReady=='function'){
    window.backup_onYouTubePlayerReady = window.onYouTubePlayerReady;
}
window.onYouTubePlayerReady = function onYouTubePlayerReady(playerId) {
    //alert('ytready')
    //alert(playerId)




//    console.info('ytready', playerId);

    var aux_objectid = playerId;
    var aux_videoid = playerId.substr(8);

//        console.info(youtubeid_array);

//        console.info(auxerid, document.getElementById(aux_objectid), aux_videoid);
    for(var i=0;i<youtubeid_array.length;i++){
        if(youtubeid_array[i].id == aux_videoid){
            if(youtubeid_array[i].nrtimes>0){
                aux_objectid+='_clone'+youtubeid_array[i].nrtimes;
            }
        }
    }

//        console.info(aux_objectid, document.getElementById(aux_objectid))


    ytplayer = document.getElementById(aux_objectid);


    var _t = jQuery(ytplayer);
    if(_t.hasClass('treated')){
        return;
    }
    _t.addClass('treated');

    //console.log(ytplayer);
    ytplayer.addEventListener("onStateChange", "onytplayerStateChange");
    var aux2 = _t.attr('data-suggestedquality');
    //console.log(aux2);
    ytplayer.loadVideoById(aux_videoid, 0, aux2);
    ytplayer.pauseVideo();




};


window.requestAnimFrame = (function(){
    return  window.requestAnimationFrame       ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame    ||
        function( callback ){
            window.setTimeout(callback, 1000 / 60);
        };
})();


jQuery(document).ready(function($){
//    --- mega conflict with mediaelement.js, well workaround by treating untreated flash items

    var inter_check_treat = 0;

    clearTimeout(inter_check_treat);
    inter_check_treat = setTimeout(workaround_treatuntretreadItems, 2000);

    function workaround_treatuntretreadItems(){


        jQuery('.js-api-player:not(.treated)').each(function(){
            var _t = jQuery(this);
            var __t = _t.get(0);
//            console.info(_t, __t);

            var playerId = _t.attr('id');

            var aux = playerId.substr(8);
            var aux2 = _t.attr('data-suggestedquality');
            //console.log(aux2);

            if(typeof __t.loadVideoById !='undefined'){
                __t.loadVideoById(aux, 0, aux2);
                __t.pauseVideo();
            }else{

                inter_check_treat = setTimeout(workaround_treatuntretreadItems, 2000);
            }


        })

    }

    if(typeof window.onYouTubePlayerReady=='function' && typeof backup_onYouTubePlayerReady == 'undefined'){
        window.backup_onYouTubePlayerReady = window.onYouTubePlayerReady;
    }

    window.onYouTubePlayerReady = function onYouTubePlayerReady(playerId) {
        //alert('ytready')
        //alert(playerId)


        //console.info('ytready', playerId);

        var aux_objectid = playerId;
        var aux_videoid = playerId.substr(8);

//        console.info(youtubeid_array);

//        console.info(auxerid, document.getElementById(aux_objectid), aux_videoid);
        for(var i=0;i<youtubeid_array.length;i++){
            if(youtubeid_array[i].id == aux_videoid){
                if(youtubeid_array[i].nrtimes>0){
                    aux_objectid+='_clone'+youtubeid_array[i].nrtimes;
                }
            }
        }

//        console.info(aux_objectid, document.getElementById(aux_objectid))


        ytplayer = document.getElementById(aux_objectid);


        var _t = jQuery(ytplayer);

        if(_t.hasClass('treated')){
            return;
        }
        _t.addClass('treated');

        //console.log(ytplayer);
        ytplayer.addEventListener("onStateChange", "onytplayerStateChange");
        var aux2 = _t.attr('data-suggestedquality');
//        console.log(aux2, ytplayer);
        ytplayer.loadVideoById(aux_videoid, 0, aux2);
        ytplayer.pauseVideo();

        if(typeof backup_onYouTubePlayerReady=='function'){
            backup_onYouTubePlayerReady(playerId);
        }

    };


    $('.videogallery--navigation-outer').each(function(){
        var _t = $(this);
        var _tar = $(_t.attr('data-vgtarget')).eq(0);
        var _clip =_t.find('.videogallery--navigation-outer--clip').eq(0);
        var _clipmover =_t.find('.videogallery--navigation-outer--clipmover').eq(0);

        var currPage = 0;
        var _block_active = _t.find('.videogallery--navigation-outer--bigblock.active').eq(0);
//        console.info(_tar);

        var _navOuterBullets = _t.find('.navigation-outer--bullet');
        var _navOuterBlocks = _t.find('.videogallery--navigation-outer--block');

        setTimeout(function(){
            _t.addClass('active');
            _block_active = _t.find('.videogallery--navigation-outer--bigblock.active').eq(0);
            _clip.height(_block_active.height());
        },500)

        _navOuterBlocks.bind('click', function(){
            var _t2 = $(this);
            var ind = _navOuterBlocks.index(_t2);


//            console.info(ind);

            if(_tar.get(0) && _tar.get(0).api_gotoItem){
                if(_tar.get(0).api_gotoItem(ind)){
                }


                $('html, body').animate({
                    scrollTop: _tar.offset().top
                }, 300);
            }
        });

        _navOuterBullets.bind('click',function(){
            var _t2 = $(this);
            var ind = _navOuterBullets.index(_t2);

            gotoPage(ind);

        })

        function gotoPage(arg){
            var auxl = -(Number(arg)*100) + '%';

            _navOuterBullets.removeClass('active');
            _navOuterBullets.eq(arg).addClass('active');

            _t.find('.videogallery--navigation-outer--bigblock.active').removeClass('active');
            _t.find('.videogallery--navigation-outer--bigblock').eq(arg).addClass('active');


            _clip.height(_t.find('.videogallery--navigation-outer--bigblock').eq(arg).height());

            _clipmover.css('left',auxl);

        }


    })
});




jQuery(document).ready(function($){
    //console.info($('.zoomvideogallery.auto-init'));
    dzsvp_init('.vplayer-tobe.auto-init', {init_each: true});
    dzsvg_init('.videogallery.auto-init', {init_each: true});

    //if (typeof window.onYouTubeIframeAPIReady!='undefined' && typeof backup_yt_iframe_ready=='undefined'){
    //    backup_yt_iframe_ready = window.onYouTubeIframeAPIReady;
    //}
    //
    //window.onYouTubeIframeAPIReady = function() {
    //
    //    dzszvp_yt_iframe_ready();
    //    if(backup_yt_iframe_ready){
    //        backup_yt_iframe_ready();
    //    }
    //}




    $(document).undelegate('.dzsas-second-con .read-more-label', 'click');
    $(document).delegate('.dzsas-second-con .read-more-label', 'click',function(e){
        // console.log(e);

        var _t = $(this);
        var _con = _t.parent();

        var _content = _con.children('.read-more-content').eq(0);

        if(_con.hasClass('active')){

            _content.animate({
                'height' : 0
            },{
                queue:false
                ,duration:300
                ,complete: function(e){
                    //console.info(this);

                }
            })

            _con.removeClass('active');
        }else{
            _content.css('height', 'auto');

            var auxh = (_content.outerHeight());

            _content.css('height', 0);
            _content.animate({
                'height' : auxh
            },{
                queue:false
                ,duration:300
                ,complete: function(e){
                    //console.info(this);

                    $(this).css('height', 'auto');
                }
            })

            _con.addClass('active');

        }


        return false;
    })
});




function onytplayerStateChange(newState) {
//    console.log(jQuery(ytplayer).parent().get(0), "Player's new state: " + newState, ytplayer.getAvailableQualityLevels());
    if(typeof(jQuery(ytplayer).parent().get(0))!='undefined'){
        try {
            jQuery(ytplayer).parent().get(0).checkYoutubeState();
        } catch (err) {
            if (window.console){
                console.log(err);
            }
        }
    }

    //console.log(newState);
    //window.checkYoutubeState();
    //- we send the on end event to the gallery if it has one
    newState = parseInt(newState, 10);
    if (newState == 0) {
        //console.log(jQuery(ytplayer))
        //jQuery(ytplayer).parent().get(0).handleVideoEnd();
    }

    window.onYouTubeIframeAPIReady = function() {
        dzsvp_yt_iframe_ready();
    }
}

function dzsvp_yt_iframe_ready(){

    _global_youtubeIframeAPIReady = true;
}

window.onYouTubeIframeAPIReady = function() {
    dzsvp_yt_iframe_ready();
}

function onYouTubeIframeAPIReady() {
}


function can_translate() {
    if (is_chrome() || is_safari()) {
        return true;
    }
    if (is_firefox() && version_firefox() > 10) {
        return true;
    }
    return false;
}

function can_history_api() {
    return !!(window.history && history.pushState);
}
function is_ios() {
    // return true;
    return ((navigator.platform.indexOf("iPhone") != -1) || (navigator.platform.indexOf("iPod") != -1) || (navigator.platform.indexOf("iPad") != -1)
    );
}

function is_android() {
    //return true;


    var ua = navigator.userAgent.toLowerCase();
    // console.info(ua);
    return (ua.indexOf("android") > -1);
}

function is_ie() {
    if (navigator.appVersion.indexOf("MSIE") != -1) {
        return true;    }; return false;
}
;
function is_firefox() {
    if (navigator.userAgent.indexOf("Firefox") != -1) {        return true;    };
    return false;
}
;
function is_opera() {
    if (navigator.userAgent.indexOf("Opera") != -1) {        return true;    };
    return false;
}
;
function is_chrome() {
    return navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
}
;
function is_safari() {
    return navigator.userAgent.toLowerCase().indexOf('safari') > -1;
}
;
function version_ie() {
    return parseFloat(navigator.appVersion.split("MSIE")[1]);
}
;
function version_firefox() {
    if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
        var aversion = new Number(RegExp.$1); return(aversion);
    }
    ;
}
;
function version_opera() {
    if (/Opera[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
        var aversion = new Number(RegExp.$1); return(aversion);
    }
    ;
}
;
function is_ie8() {
    if (is_ie() && version_ie() < 9) {  return true;  };
    return false;
}
function is_ie9() {
    if (is_ie() && version_ie() == 9) {
        return true;
    }
    return false;
}

function get_query_arg(purl, key){
    if(purl.indexOf(key+'=')>-1){
        //faconsole.log('testtt');
        var regexS = "[?&]"+key + "=.+";
        var regex = new RegExp(regexS);
        var regtest = regex.exec(purl);
        //console.info(regtest);

        if(regtest != null){
            var splitterS = regtest[0];
            if(splitterS.indexOf('&')>-1){
                var aux = splitterS.split('&');
                splitterS = aux[1];
            }
            //console.log(splitterS);
            var splitter = splitterS.split('=');
            //console.log(splitter[1]);
            //var tempNr = ;

            return splitter[1];

        }
        //$('.zoombox').eq
    }
}

function add_query_arg(purl, key,value){
    key = encodeURIComponent(key); value = encodeURIComponent(value);

    var s = purl;
    var pair = key+"="+value;

    var r = new RegExp("(&|\\?)"+key+"=[^\&]*");

    s = s.replace(r,"$1"+pair);
    //console.log(s, pair);
    if(s.indexOf(key + '=')>-1){


    }else{
        if(s.indexOf('?')>-1){
            s+='&'+pair;
        }else{
            s+='?'+pair;
        }
    }
    //if(!RegExp.$1) {s += (s.length>0 ? '&' : '?') + kvp;};


    //if value NaN we remove this field from the url
    if(value=='NaN'){
        var regex_attr = new RegExp('[\?|\&]'+key+'='+value);
        s=s.replace(regex_attr, '');
    }

    return s;
}

function can_play_mp3(){
    var a = document.createElement('audio');
    return !!(a.canPlayType && a.canPlayType('audio/mpeg;').replace(/no/, ''));
}
function can_play_mp4(){
    var a = document.createElement('video');
    return !!(a.canPlayType && a.canPlayType('video/mp4;').replace(/no/, ''));
}


//time, begin, change( f-b ), duration
function global_ease_in(t, b, c, d) {

    return -c *(t/=d)*(t-2) + b;

};
function is_mobile() {
    // return true;
    return is_ios() || is_android();
}

function is_touch_device() {
    //return true;
    return !!('ontouchstart' in window);
}


jQuery.fn.urlParam = function (arg, name) {
    var results = new RegExp('[\\?&]' + name + '=([^&#]*)').exec(arg);
    return (results !== null) ? results[1] : 0;
}


window.dzsvg_wp_send_view = function(argcthis, argtitle){
    //console.info(argcthis, argtitle);



    var data = {
        video_title: argtitle
        ,video_analytics_id: argcthis.attr('data-player-id')
    };

    if(window.dzsvg_curr_user){
        data.dzsvg_curr_user = window.dzsvg_curr_user;
    }

    var theajaxurl = 'index.php?action=ajax_dzsvg_submit_view';

    if(window.dzsvg_site_url){

        theajaxurl = dzsvg_settings.dzsvg_site_url + theajaxurl;
    }


    jQuery.ajax({
        type: "POST",
        url: theajaxurl,
        data: data,
        success: function(response) {
            if(typeof window.console != "undefined" ){ console.log('Ajax - submit view - ' + response); }



        },
        error:function(arg){
            if(typeof window.console != "undefined" ){ console.warn('Got this from the server: ' + arg); };
        }
    });


}
window.dzsvg_wp_send_contor_60_secs = function(argcthis, argtitle){

    var data = {
        video_title: argtitle
        // ,video_analytics_id: argcthis.attr('data-analytics-id')
        ,video_analytics_id: argcthis.attr('data-player-id')
        ,dzsvg_curr_user: window.dzsvg_curr_user
    };
    var theajaxurl = 'index.php?action=ajax_dzsvg_submit_contor_60_secs';

    if(window.dzsvg_site_url){

        theajaxurl = dzsvg_settings.dzsvg_site_url + theajaxurl;
    }



    jQuery.ajax({
        type: "POST",
        url: theajaxurl,
        data: data,
        success: function(response) {
            if(typeof window.console != "undefined" ){ console.log('Ajax - submit view - ' + response); }



        },
        error:function(arg){
            if(typeof window.console != "undefined" ){ console.warn('Got this from the server: ' + arg); };
        }
    });
}

function fs_status() {
    if(document.fullscreenElement)
    {
        return 1;
    }
    else if(document.webkitFullscreenElement ){

        return 1;
    }
    else if (document.mozFullScreenElement){
        return 1;
    }
    else return -1;
}

window.dzsvg_open_social_link = function(arg){
    var leftPosition, topPosition;
    var w = 500, h= 500;

    arg = arg.replace("{{replacewithcurrurl}}",encodeURIComponent(window.location.href));
    //Allow for borders.
    leftPosition = (window.screen.width / 2) - ((w / 2) + 10);
    //Allow for title and status bars.
    topPosition = (window.screen.height / 2) - ((h / 2) + 50);
    var windowFeatures = "status=no,height=" + h + ",width=" + w + ",resizable=yes,left=" + leftPosition + ",top=" + topPosition + ",screenX=" + leftPosition + ",screenY=" + topPosition + ",toolbar=no,menubar=no,scrollbars=no,location=no,directories=no";
    window.open(arg,"sharer", windowFeatures);
}


function get_base_url_arr(){



    var scripts = document.getElementsByTagName("script");


    for(var i23 in scripts){
        if(scripts[i23] && scripts[i23].src && String(scripts[i23].src).indexOf('vplayer.js')>-1){

            break;
        }
    }
    var baseUrl_arr = String(scripts[i23].src).split('/');

    return baseUrl_arr;
}

function dzsvg_check_multisharer(){
    // console.info("LOAD MULTISHARER");



    setTimeout(function(){


        jQuery('body').append('<div class="dzsvg-main-con skin-default gallery-skin-default transition-slideup "> <div class="overlay-background"></div> <div class="box-mains-con"> <div class="box-main" style=""> <div class="box-main-media-con transition-target"> <div class="close-btn-con"> <svg enable-background="new 0 0 40 40" id="" version="1.1" viewBox="0 0 40 40" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M28.1,26.8c0.4,0.4,0.4,1,0,1.4c-0.2,0.2-0.5,0.3-0.7,0.3s-0.5-0.1-0.7-0.3l-6.8-6.8l-6.8,6.8c-0.2,0.2-0.5,0.3-0.7,0.3 s-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l6.8-6.8l-6.8-6.8c-0.4-0.4-0.4-1,0-1.4c0.4-0.4,1-0.4,1.4,0l6.8,6.8l6.8-6.8 c0.4-0.4,1-0.4,1.4,0c0.4,0.4,0.4,1,0,1.4L21.3,20L28.1,26.8z"></path></g><g><path d="M19.9,40c-11,0-20-9-20-20s9-20,20-20c4.5,0,8.7,1.5,12.3,4.2c0.4,0.3,0.5,1,0.2,1.4c-0.3,0.4-1,0.5-1.4,0.2 c-3.2-2.5-7-3.8-11-3.8c-9.9,0-18,8.1-18,18s8.1,18,18,18s18-8.1,18-18c0-3.2-0.9-6.4-2.5-9.2c-0.3-0.5-0.1-1.1,0.3-1.4 c0.5-0.3,1.1-0.1,1.4,0.3c1.8,3.1,2.8,6.6,2.8,10.2C39.9,31,30.9,40,19.9,40z"></path></g></svg></div> <div class="box-main-media type-inlinecontent" style="width: 530px; height: 280px;"><div class=" real-media" style=""><div class="hidden-content share-content" > <div class="social-networks-con"></div> <div class="share-link-con"></div> <div class="embed-link-con"></div> </div> </div> </div> <div class="box-main-under"></div> </div> </div> </div><!-- end .box-mains-con--> </div>');

        // jQuery('body').append('<div class="dzsvg-main-con skin-default gallery-skin-default transition-slideup "> <div class="overlay-background"></div> <div class="box-mains-con"> <div class="box-main" style=""> <div class="box-main-media-con transition-target"> <div class="close-btn-con"> <svg enable-background="new 0 0 40 40" id="" version="1.1" viewBox="0 0 40 40" xml:space="preserve" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"><g><path d="M28.1,26.8c0.4,0.4,0.4,1,0,1.4c-0.2,0.2-0.5,0.3-0.7,0.3s-0.5-0.1-0.7-0.3l-6.8-6.8l-6.8,6.8c-0.2,0.2-0.5,0.3-0.7,0.3 s-0.5-0.1-0.7-0.3c-0.4-0.4-0.4-1,0-1.4l6.8-6.8l-6.8-6.8c-0.4-0.4-0.4-1,0-1.4c0.4-0.4,1-0.4,1.4,0l6.8,6.8l6.8-6.8 c0.4-0.4,1-0.4,1.4,0c0.4,0.4,0.4,1,0,1.4L21.3,20L28.1,26.8z"></path></g><g><path d="M19.9,40c-11,0-20-9-20-20s9-20,20-20c4.5,0,8.7,1.5,12.3,4.2c0.4,0.3,0.5,1,0.2,1.4c-0.3,0.4-1,0.5-1.4,0.2 c-3.2-2.5-7-3.8-11-3.8c-9.9,0-18,8.1-18,18s8.1,18,18,18s18-8.1,18-18c0-3.2-0.9-6.4-2.5-9.2c-0.3-0.5-0.1-1.1,0.3-1.4 c0.5-0.3,1.1-0.1,1.4,0.3c1.8,3.1,2.8,6.6,2.8,10.2C39.9,31,30.9,40,19.9,40z"></path></g></svg></div> <div class="box-main-media type-inlinecontent" style="width: 530px; height: 280px;"><div class=" real-media" style=""><div class="hidden-content share-content" > <div class="social-networks-con"></div> <div class="share-link-con"></div> <div class="embed-link-con"></div> </div> </div> </div> <div class="box-main-under"></div> </div> </div> </div><!-- end .box-mains-con--> </div>');

    },1000);


    jQuery(document).on('click.dzsvg','.dzsvg-main-con .close-btn-con,.dzsvg-main-con .overlay-background', function(){

        var _c = jQuery('.dzsvg-main-con').eq(0);

        _c.removeClass('loading-item loaded-item');
    })

    jQuery(document).on('click.dzsvg','.dzsvg-multisharer-but', dzsvg_click_open_embed_ultibox);
    jQuery(document).on('click.dzsvg','.dzsvg-main-con .close-btn-con,.dzsvg-main-con .overlay-background', function(){

        var _c = jQuery('.dzsvg-main-con').eq(0);

        _c.removeClass('loading-item loaded-item');
    })


}

window.dzsvg_curr_embed_code = '';
function dzsvg_click_open_embed_ultibox() {


    // console.info('ceva');

    var _c = jQuery('.dzsvg-main-con').eq(0);

    // console.info('_c -> ',_c, cgallery.find('.feed-dzsvg--for-social-networks').eq(0).html(), _c.find('.social-networks-con'));


    var _t = jQuery(this);

    console.info(_t);
    console.info(_t.parent().parent().parent());



    var aux = '';

    if(window.dzsvg_social_feed_for_social_networks){
        aux = window.dzsvg_social_feed_for_social_networks;
    }

    aux = aux.replace(/&quot;/g, '\'');
    aux = aux.replace('onclick=""', '');

    _c.find('.social-networks-con').html(aux);



    aux = '';


    if(window.dzsvg_social_feed_for_share_link){
        aux = window.dzsvg_social_feed_for_share_link;
    }


    if(aux){

        aux=aux.replace('{{replacewithcurrurl}}',window.location.href);
        _c.find('.share-link-con').html(aux);
    }

    aux = '';
    if(window.dzsvg_social_feed_for_embed_link){
        aux = window.dzsvg_social_feed_for_embed_link;
    }

    var _vp = null;

    if(_t.parent().parent().parent().hasClass('vplayer')){
        _vp = _t.parent().parent().parent();
    }

    console.info('dzsvg_social_feed_for_embed_link - ',aux);
    if(_vp && aux) {

        if(_vp.data('embed_code')){
            jQuery('.embed-link-con').show();
            aux = aux.replace('{{replacewithembedcode}}', htmlEntities(_vp.data('embed_code')));

            console.info('_c.find(\'.embed-link-con\') - ',_c.find('.embed-link-con'));
            _c.find('.embed-link-con').html(aux);
        }else{

            jQuery('.embed-link-con').hide();
        }
    }


    jQuery(document).on('click.dzsvg','.field-for-view', function(){

        // console.info("HMM ", this);
        // selectText(this);

        jQuery(this).select();
    });
    setTimeout(function(){

        _c.addClass('loading-item');
    },100);

    setTimeout(function(){
        _c.addClass('loaded-item');
    },200);
}
