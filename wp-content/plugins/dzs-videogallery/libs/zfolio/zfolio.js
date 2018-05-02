

/*
 * Author: Digital Zoom Studio
 * Website: http://digitalzoomstudio.net/
 * Portfolio: http://codecanyon.net/user/ZoomIt/portfolio
 *
 * Version: 0.9901
 */

"use strict";


window.dzszfl_self_options = {};

Math.easeIn = function(t, b, c, d) {

    return -c *(t/=d)*(t-2) + b;

};

function sort_by_sort(a, b){
    var nr1=0;
    var nr2 = 0;
    //console.log(a);

    if(a && a.attr){

    }
    nr1 = Number(jQuery(a).attr('data-sort'));
    nr2 = Number(jQuery(b).attr('data-sort'));


    return ((nr1 < nr2) ? -1 : ((nr1 > nr2) ? 1 : 0));
}


(function($) {
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
    $.fn.zfolio = function(o) {
        var defaults = {
            design_item_thumb_just_use_img: "off" // -- just use images tags for auto width and height
            , settings_autoHeight: 'on'
            , settings_skin: 'skin-default'
            , settings_mode: 'isotope'// -- "isotope" or "simple" or "scroller"
            , settings_disableCats: 'off'
            , settings_clickaction: 'none'
            , title: ''
            ,design_total_height_full:'off'
            ,pagination_method:'normal' // pagination or scroll
            , design_item_width: '0'
            , design_item_height: '0'
            , design_item_height_same_as_width: 'off' // ==deprecated, use thumbh 1/1
            , design_sizecontain_forfeatureimage: 'off' // -- use size contain for feature image
            , design_thumbw: ''
            , init_on: 'init'
            , design_item_thumb_height: ''// -- default thumbh, values like "2/3" ( of width )  are accepted or "proportional" to just calculate for each item individually
            , design_categories_pos: 'top' // top or bottom
            , design_categories_align: 'auto' //auto, alignleft, aligncenter or alignright
            ,design_specialgrid_chooser_align: 'auto' //auto, alignleft, aligncenter or alignright
            ,design_pageContent_pos: 'top'
            , design_categories_style: 'normal' // normal or dropdown
            ,design_waitalittleforallloaded: 'off' //wait for the items to arrange first before making the portfolio visible
            , use_scroll_lazyloading_for_images: 'off' // -- set images to lazy load on scroll
            , settings_ajax_method: 'off' // -- "off" / "curritems" for the current items in the queue / "on" for the pages array
            , settings_ajax_method_curritems_per_page: '5' // -- number of items to be loaded at a time
            , settings_ajax_method_curritems_per_page_initial: '' // -- number of items to be loaded at a time
            , settings_ajax_pagination_method: 'scroll'// -- choose between scroll and button mode NEW pages
            , settings_ajax_pages: []
            , settings_lightboxlibrary: 'zoombox'
            , item_inner_addid: ''
            , settings_preloadall: 'off'
            , settings_add_loaded_on_images: 'off' // -- add a loaded class on the image items when laoded
            , settings_useLinksForCategories: 'off'
            , settings_useLinksForCategories_enableHistoryApi: 'off'
            ,item_link_thumb_con_to: "link"
            , disable_itemmeta: "off"
            , filters_enable: "on"
            , disable_cats: "off" // -- disable the categories display
            ,wall_settings: {}
            ,settings_enableHistory : 'off' // history api for link type items
            ,audioplayer_swflocation: 'ap.swf'
            ,videoplayer_swflocation: 'preview.swf'
            ,settings_makeFunctional: true
            ,settings_defaultCat: '' // == default a category at start
            ,settings_forceCats: [] // == force categories in this order
            ,settings_categories_strall: 'All' // == the name of the all category select
            ,settings_categories_strselectcategory: 'Select Category'
            ,settings_set_forced_width: "off" // -- set a javascript calculated width on the item
            ,settings_isotope_settings: {
                getSortData: {
                    // sorter: function ($elem) {
                    //     return parseInt($($elem).attr('data-sort'), 10);
                    // }
                    sorter: function( itemElem ) { // function
                        var weight = $( itemElem ).attr('data-sort');

                        if(weight){

                            return parseInt( weight.replace( /[\(\)]/g, '') );
                        }else{
                            return 0;
                        }
                    }
                }

                , itemSelector: '.isotope-item'
                , sortBy: 'sorter'
                ,percentPosition: true
                ,columnWidth: '.grid-sizer'
                ,gutter: '.gutter-sizer'
                ,layoutMode: 'packery'
                // ,layoutMode: 'masonry'
                //,percentPosition: true
                ,masonry: {
                    // use outer width of grid-sizer for columnWidth
                    // columnWidth: '.grid-sizer'
                    columnWidth: '.grid-sizer'
                    ,percentPosition: true
                }
                // -- packery does not sort whel percent Position
                ,packery: {
                    // use outer width of grid-sizer for columnWidth
                    columnWidth: '.grid-sizer'
                    ,percentPosition: true
                    // columnWidth: 1
                }
            }
            ,scroller_settings: {}
            ,zoombox_settings: {}

            ,item_extra_class:''
            ,responsive_fallback_tablet:''
            ,responsive_fallback_mobile:''
            ,excerpt_con_transition:'zoom' // -- wipe or zoom
            ,excerpt_con_resize_videos:'off' // -- resize videos in the excerpt con based on a responsive ratio.
            ,excerpt_con_responsive_ratio:'810' // -- the responsive width on which the height is based ( height should already be set on the element )
            ,selector_con_skin:'default' // -- select a selector con so the categories would be outside the zfolio
            ,selector_con_generate_categories:"auto" // -- select a selector con so the categories would be outside the zfolio jQuery("#selector-con-for-zfolio2")
            ,outer_con_selector_con:null // -- select a selector con so the categories would be outside the zfolio jQuery("#selector-con-for-zfolio2")
            ,outer_con_search_con:null // -- select a selector con so the categories would be outside the zfolio
            ,pagination_selector:null // -- select a pagination con so the zfolio can be paginated


        };


        var scroller_default_settings = {
            "settings_direction": "horizontal"
            , "settings_onlyone": "off"
            , "settings_autoHeight": "off"
            , "per_row": "default"
            , "design_bulletspos": "none"
        };


        if(typeof o =='undefined'){
            if(typeof $(this).attr('data-options')!='undefined'  && $(this).attr('data-options')!=''){
                var aux = $(this).attr('data-options');

                try{
                    o = $.extend({},JSON.parse(aux) );


                }catch(err){

                    o = defaults;
                }

            }
        }
        // console.info('o - ',o);


        o = $.extend(defaults, o);
        // console.info('o2 - ',o);

        // console.info('o.scroller_settings - ',o.scroller_settings)
        o.scroller_settings = $.extend(scroller_default_settings, o.scroller_settings);

        // console.info('o.scroller_settings2 - ',o.scroller_settings)
        this.each(function() {
            var cthis = $(this);
            var cclass = '';
            var cid = '';
            var cchildren = cthis.children()
                ,images
            ;
            var nr_children = cthis.find('.items').eq(0).children().length
            ;
            var currNr = -1;
            var i = 0;
            var ww
                , wh
                , tw
                , th
            ;
            var _pageCont
                ,_items = null
                , _theitems
                , _selectorCon = null
                , _paginationCon = null
                , _contentScroller = null
                , _contentScrollerItems = null
            ;
            var arr_cats = [] // =categories
                ,arr_itemhproportionals = [] // === proportional item heights for each item
                ,arr_thumbhproportionals = [] // === proportional thumb heights for each item
            ;
            var busy = false
                ,busy_ajax = false
                ,destroyed = false
                ,zfolio_is_faded = false
                ,can_load_next_images_from_scroll = false
                ,can_load_next_images_from_prev_loaded = false
                ,can_load_next_images_first_time = true // first time we allow
            ;
            var sw = false;
            var the_skin = 'skin-default';
            var isotope_settings = o.settings_isotope_settings;
            var inter_calculate_dims = 0
                ,inter_reset_light = 0
                ,inter_relayout = 0

            ;
            var action_after_portfolio_expanded = null
            ;

            var ind_ajaxPage = 0
                ,mode_cols_nr_of_cols = 0
                ,nr_per_page = 5
                ,class_all_cols = ' dzs-layout--1-cols dzs-layout--2-cols dzs-layout--3-cols dzs-layout--4-cols dzs-layout--5-cols dzs-layout--6-cols'
                ,class_all_temp_cols = ' temp-dzs-layout--1-cols temp-dzs-layout--2-cols temp-dzs-layout--3-cols temp-dzs-layout--4-cols temp-dzs-layout--5-cols temp-dzs-layout--6-cols'
            ;


            var sw_mode_cols_nr_of_cols = 0
                ,i_dzscol_ind = 0;
            ;


            var lastmargs = null; // -- for debug

            var animation_duration=300;

            //===thumbsize
            var st_tw = 0
                , st_th = 0
                ,design_item_thumb_height = 200
                ,grid_unit_px = 200
                ,design_item_padding = 30
                ,design_item_thumb_height_dynamic = false
                , thumbh_dependent_on_w = false
                , itemh_dependent_on_w = false
                ,layout_margin = 0
                ,inter_relayout_allow = false
                ,initial_cols_before_fallback = ''
            ;

            var inter_set_transition_duration=0;


            var _excerptContentCon = null // - the excerpt con
                ,_excerptContent = null
                ,_excerptContent_initialPortItem = null // -- the initial port item that has been clicked to trigger the excerpt
                ,_tcon_content = null
            ;

            var dzsp_translate_close = "Close"
            ;
            var arr_cats = [] // -- categories
                ,arr_cats_type = 'datacategory' // -- categories
                ,cat_curr = '*' // -- categories
            ;

            if(typeof window.dzsp_translate_close!='undefined'){
                dzsp_translate_close = window.dzsp_translate_close;
            }

//            console.info(window.dzsp_translate_close2);



            var is_already_inited="off"
                ,is_hard_defined_inittop = 'notsetyet'
            ;

            var categories_parent;


            //==loading vars
            var nrLoaded = 0
                ,started = false
                ,widthArray = []
                ,heightArray = []
                ,loadedArray = []
                ,images_tobeloaded = []
                ,startitems_html = ''
                ,$itemsArray = null
            ;

            //console.info(nr_children);

            cid = cthis.attr('id');


            if(typeof cid=='undefined' || cid==''){
                var auxnr = 0;
                var temps = 'zoomfolio'+auxnr;

                while($('#'+temps).length>0){
                    auxnr++;
                    temps = 'zoomfolio'+auxnr;
                }

                cid = temps;
                cthis.attr('id', cid);
            }




            function handle_scroll(e, pargs) {
                //console.info('handle_scroll', cthis, e, $(window).scrollTop());
                var st = $(window).scrollTop();
                var cthis_ot = cthis.offset().top;

                var wh = $(window).height();


                // console.info(cthis_ot, st+wh);


                if(cthis_ot<st+wh+50){
                    init();
                }
            }

            if(o.init_on=='init'){

                init();
            }
            if(o.init_on=='scroll'){


                $(window).on('scroll.dzszfl_init_'+cid,handle_scroll);
                handle_scroll();
            }


            function init(){
                //console.info(cthis, 'zoomfolio - init()', cthis.descendantOf($('body')), o);

                // console.info('cid - ',cid);
                $(window).off('scroll.dzszfl_init_'+cid);


                if(cthis.hasClass('dzszfl-inited')){
                    return false;
                }

                if(cthis.hasClass('skin-qucreative')){
                    o.design_skin = 'skin-qucreative';
                }
                if(cthis.hasClass('skin-material')){
                    o.design_skin = 'skin-material';
                }
                if(cthis.hasClass('skin-forwall')){
                    o.design_skin = 'skin-forwall';
                    if(o.selector_con_skin=='default'){
                        o.selector_con_skin = 'selector-con-for-skin-forwall';
                    }
                }
                if(cthis.hasClass('skin-melbourne')){
                    o.design_skin = 'skin-melbourne';
                    if(o.selector_con_skin=='default'){
                        o.selector_con_skin = 'selector-con-for-skin-melbourne';
                    }
                }
                if(cthis.hasClass('skin-silver')){
                    o.design_skin = 'skin-silver';
                    if(o.selector_con_skin=='default'){
                        o.selector_con_skin = 'selector-con-for-skin-silver';
                    }
                }
                if(cthis.hasClass('skin-gazelia')){
                    o.design_skin = 'skin-gazelia';
                }
                if(cthis.hasClass('skin-lazarus')){
                    o.design_skin = 'skin-lazarus';
                }
                if(cthis.hasClass('skin-alba')){
                    o.design_skin = 'skin-alba';
                }
                if(cthis.hasClass('skin-woo')){
                    o.design_skin = 'skin-woo';
                }
                if(cthis.hasClass('skin-noskin')){
                    o.design_skin = 'skin-noskin';
                }


                if(o.selector_con_skin=='default'){
                    o.selector_con_skin = 'selector-con-for-skin-melbourne';
                }

                _items = cthis.find('.items').eq(0);

                cthis.addClass('mode-'+o.settings_mode);




                //console.log(Number(o.design_item_thumb_height));
                if(isNaN(Number(o.design_item_thumb_height))==false){
                    design_item_thumb_height = Number(o.design_item_thumb_height);


                    design_item_thumb_height_dynamic = design_item_thumb_height <= 3 && design_item_thumb_height > 0;
                }else{

                }
                if(isNaN(Number(cthis.attr('data-margin')))==false){
                    layout_margin = Number(cthis.attr('data-margin'));
                }

                if(o.design_item_thumb_height==''){
                    o.design_item_thumb_height = 'proportional';
                }

                if(o.design_item_thumb_height=='proportional'){
                    cthis.addClass('wait-until-item-loaded-then-visible');
                    cthis.addClass('items-depend-on-thumb-height');
                }

                if(o.settings_ajax_method_curritems_per_page=='auto'){
                    if(cthis.hasClass('dzs-layout--6-cols')){
                        o.settings_ajax_method_curritems_per_page = '6';
                    }
                    if(cthis.hasClass('dzs-layout--5-cols')){
                        o.settings_ajax_method_curritems_per_page = '5';
                    }
                    if(cthis.hasClass('dzs-layout--4-cols')){
                        o.settings_ajax_method_curritems_per_page = '4';
                    }
                    if(cthis.hasClass('dzs-layout--3-cols')){
                        o.settings_ajax_method_curritems_per_page = '3';
                    }
                    if(cthis.hasClass('dzs-layout--2-cols')){
                        o.settings_ajax_method_curritems_per_page = '2';
                    }
                }
                if(o.settings_ajax_method_curritems_per_page=='auto'){

                    o.settings_ajax_method_curritems_per_page = '4';
                }


                if(o.outer_con_selector_con){

                    if(o.outer_con_selector_con.off){

                    }else{
                        (o.outer_con_selector_con) = $(o.outer_con_selector_con)
                    }

                    _selectorCon=(o.outer_con_selector_con);
                }



                nr_per_page = Number(o.settings_ajax_method_curritems_per_page);

                // console.log('nr_per_page -> ',nr_per_page, o.settings_ajax_method_curritems_per_page);

                if(o.settings_ajax_method_curritems_per_page_initial){
                    nr_per_page = Number(o.settings_ajax_method_curritems_per_page_initial);
                }


                if(o.settings_mode=='scroller'){
                    cthis.append('<div  class="contentscroller auto-init2 bullets-right animate-height " data-margin="30"> <div class="arrowsCon arrow-skin-bare" style="text-align: right;"> <div class="arrow-left"> <div class="arrow-con"> <i class="the-icon fa fa-chevron-left"></i> </div> </div> <div class="arrow-right"> <div class="arrow-con"> <i class="the-icon fa fa-chevron-right"></i> </div> </div> </div><div class="items"></div></div>');

                    _contentScroller = cthis.find('.contentscroller');
                    _contentScrollerItems = _contentScroller.find('.items');


                    console.info('_contentScroller - ',_contentScroller);
                }



                cthis.addClass('dzszfl-inited');

                reinit({
                    'call_from':'init'
                });

                init_ready();


                var _the_content_con = null;


                if(cthis.parent().parent().parent().parent().parent().parent().parent().parent().hasClass('the-content-con')){
                    _the_content_con = cthis.parent().parent().parent().parent().parent().parent().parent().parent();
                }
                if(cthis.parent().parent().parent().parent().parent().parent().parent().parent().parent().parent().hasClass('the-content-con')){
                    _the_content_con = cthis.parent().parent().parent().parent().parent().parent().parent().parent().parent().parent();
                }

                // console.info('cthis.parent().parent().parent().parent().parent().parent().parent().parent() - ',cthis.parent().parent().parent().parent().parent().parent().parent().parent());

                if(_the_content_con){
                    // console.info('found', _the_content_con);

                    if(_the_content_con.hasClass('fullit')){
                        var gap = parseInt(_the_content_con.find('.the-content').eq(0).attr('data-portfolio-gap'),10);


                        if(cthis.hasClass('skin-melbourne') || cthis.hasClass('skin-gazelia skin-gazelia--transparent')){

                            if(gap<10){
                                gap=10;
                            }
                        }
                        if(isNaN(gap)==false){

                            // console.info(_the_content_con.find('.translucent-layer'));
                            _the_content_con.find('.translucent-layer').css({
                                'padding':gap+'px'
                            })
                            _the_content_con.find('.categories').eq(0).css({
                                // 'padding-left':gap+'px'
                                // ,'padding-right':gap+'px'
                            })
                        }
                    }

                    _the_content_con.attr('data-filters-position',cthis.attr('data-filters-position'));
                }

                // console.info('.the-content -> ', cthis.parent().parent().parent().parent().parent().parent().parent().parent());


                $(window).on('scroll.dzszfl_'+cid,handle_scroll_inner);



                // cthis.find('.content-opener').unbind('click');
                // cthis.find('.content-opener').bind('click', click_contentOpener);
                cthis.on('click','.content-opener', click_contentOpener);
            }

            function handle_scroll_inner(e, pargs) {
                // console.info('handle_scroll', cthis, e, $(window).scrollTop());
                var st = $(window).scrollTop();
                var cthis_ot = cthis.offset().top;



                th = cthis.height();



                // console.info(cthis_ot, st+wh);



                if(o.pagination_method=='scroll'){
                    if(st + wh>cthis_ot+th-20){
                        can_load_next_images_from_scroll = true;

                        load_batch_images({
                            call_scroll: false
                            ,'call_from':'handle_scroll_inner'
                        });
                    }else{

                        can_load_next_images_from_scroll = false;
                    }
                }


                // console.info('can_load_next_images_from_scroll - ',can_load_next_images_from_scroll , ' th ( ',th, ' ) ', ' st ( ',st, ' ) ');


            }

            function reinit(pargs){


                var margs = {
                    'call_from': 'default'
                };

                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                // console.info('reinit', margs);


                if(margs.call_from=='ajax_append_new_items'){
                    can_load_next_images_from_prev_loaded = true;
                    can_load_next_images_from_scroll = true;

                }

                var i=1;
                i_dzscol_ind = 0;
                sw_mode_cols_nr_of_cols = 0;
                arr_cats = [];


                _items.children('.zfolio-item:not(.inited)').each(function(){

                    var _t = $(this);

                    // console.warn(_t);

                    _t.addClass('dzs-layout-item');
                    if(_t.children().eq(0).hasClass('zfolio-item--inner')){


                    }else{


                        if(_t.attr('data-link') && o.design_skin=='skin-melbourne'){

                            var aux4 = '<a href="'+_t.attr('data-link')+'" class="zfolio-item--inner ';


                            if(_t.attr('data-overlay_extra_class') && _t.attr('data-overlay_extra_class').indexOf('content-opener')>-1){

                                aux4+=' for-content-opener';
                            }


                            aux4+='custom-a "></a>';


                            _t.wrapInner(aux4);
                        }else{


                            console.info('_t - ',_t);
                            _t.wrapInner('<div class="zfolio-item--inner"><div class="zfolio-item--inner--inner"><div class="zfolio-item--inner--inner--inner"></div></div></div>');
                        }

                    }

                    if(_t.attr('data-thumb')){
                        if(_t.attr('data-thumbnail')){
                        }else{

                            _t.attr('data-thumbnail', _t.attr('data-thumb'));
                        }
                    }

                    var _ti = _t.children('.zfolio-item--inner');
                    var _tii = _t.find('.zfolio-item--inner--inner');
                    var _tiii = _t.find('.zfolio-item--inner--inner--inner');

                    //console.log("CEVA", o.item_inner_addid);
                    if(o.item_inner_addid){
                        var aux = '';

                        if(cthis.attr('id')){
                            aux+=cthis.attr('id')+'-';
                        }

                        aux+=o.item_inner_addid+String(i);

                        if(aux){
                            _ti.attr('id',aux);
                        }
                    }

                    //console.log(_t);

                    var aux_the_feature_con = '';

                    var inside_anchor = false;
                    var inside_anchor_source = 'the-feature-con';


                    // console.info('o.item_link_thumb_con_to - ', o.item_link_thumb_con_to);

                    // console.info(_t);




                    var item_link_thumb_con_to = o.item_link_thumb_con_to;
                    if(_ti.get(0) && _ti.get(0).nodeName=='A'){
                        item_link_thumb_con_to = '';
                    }


                    if (item_link_thumb_con_to == 'link') {
                        aux_the_feature_con += ' <a class="the-feature-con  custom-a';
                        inside_anchor = true;
                        inside_anchor_source = 'the-feature-con';
                    }else{
                        aux_the_feature_con += ' <div class="the-feature-con';
                    }


                    if (item_link_thumb_con_to == 'ultibox') {

                        if(_t.attr('data-bigimage')) {
                            aux += ' ultibox-item';
                        }
                    }

                    aux_the_feature_con+='"';


                    if (item_link_thumb_con_to == 'ultibox') {


                        if(_t.attr('data-bigimage')){

                            aux_the_feature_con += ' data-source="' + _t.attr('data-bigimage') + '"';
                        }
                    }
                    if (item_link_thumb_con_to == 'link') {

                        if(_t.attr('data-bigimage')){

                            aux_the_feature_con += ' href="' + _t.attr('data-bigimage') + '"';
                        }
                    }
                    aux_the_feature_con+='>';


                    if (item_link_thumb_con_to == 'link') {
                        aux_the_feature_con += ' </a>';
                    }else{
                        aux_the_feature_con += '</div>';
                    }

                    // console.info('_t - ', _t);


                    if(_ti.find('.the-feature-con').length){

                    }else{

                        _tiii.prepend(aux_the_feature_con);
                    }

                    var _thumbCon = _ti.find('.the-feature-con').eq(0);

                    if (item_link_thumb_con_to == 'ultibox') {
                        if(_ti.children('.feed-rst').length){

                            _thumbCon.append('<div class="feed-ultibox feed-ultibox-desc"><div class="rst-big-desc">' + _ti.children('.feed-rst').eq(0).html() + '</div></div>');
                        }
                    }




                    if (item_link_thumb_con_to == 'ultibox') {

                    }

                    if(o.design_skin=='skin-alba'){
                        _tii.append('<figure class="line1"></figure><figure class="line1"></figure><figure class="line1"></figure><figure class="line1"></figure>')
                    }



                    if(o.design_item_thumb_just_use_img=='on'){





                        var aux = '<img class="the-feature';

                        if(o.use_scroll_lazyloading_for_images=='on'){

                            aux+=' lazyloading-transition-fade set-height-auto-after-load" data-src="'+_t.attr('data-thumbnail')+'"';
                            aux+='" style="height:300px; display:block;"';
                        }else{

                            aux+='" src="'+_t.attr('data-thumbnail')+'"';
                        }

                        aux+='/>';

                        _thumbCon.prependOnce(aux);

                        _thumbCon.addClass('auto-height');

                    }else{

                        _thumbCon.prependOnce('<div class="the-feature the-feature-div" style="background-image: url('+_t.attr('data-thumbnail')+');"></div>');
                    }


                    if(_ti.find('.feed-zfolio-append-feature-con').length){
                        _thumbCon.append(_ti.find('.feed-zfolio-append-feature-con').eq(0));
                    }
                    _ti.find('.the-feature-con')


                    var overlay_extra_class='';

                    //console.log(_t.attr('data-overlay_extra_class'));
                    if(_t.attr('data-overlay_extra_class')){
                        overlay_extra_class+= ' '+_t.attr('data-overlay_extra_class');
                    }

                    var izlink=false;
                    var aux = '<div class="the-overlay'+overlay_extra_class+'" ';




                    if(_t.attr('data-link')){

                        if(o.design_skin=='skin-silver' || o.design_skin=='skin-qucreative'){


                            if(inside_anchor){

                                aux='<div href="'+_t.attr('data-link')+'" class="the-overlay '+overlay_extra_class+'" ';
                            }else{

                                aux='<a href="'+_t.attr('data-link')+'" class="the-overlay '+overlay_extra_class+'" ';
                            }


                            izlink=true;
                        }


                        if(o.design_skin=='skin-gazelia'){

                            izlink=false;
                            aux = '<div class="the-overlay" ';
                            aux+='>';
                            //console.info(_t);
                            if(inside_anchor) {
                                aux += '<div href="' + _t.attr('data-link') + '" class="the-overlay-anchor ' + overlay_extra_class + '"';

                            }else{

                                aux += '<a href="' + _t.attr('data-link') + '" class="the-overlay-anchor ' + overlay_extra_class + '"';
                            }

                            if(_t.attr('data-overlay_anchor_extra_attr')){
                                aux+=_t.attr('data-overlay_anchor_extra_attr');
                            }


                            aux+='>';


                            if(_t.children('.overlay-anchor-extra-html').length>0){
                                aux+=_t.children('.overlay-anchor-extra-html').eq(0).html();
                            }

                            if(inside_anchor) {

                                aux+='</div';
                            }else{

                                aux+='</a';
                            }
                        }


                        if(o.design_skin=='skin-lazarus'){

                            izlink=false;
                            aux = '<div class="the-overlay" ';
                            aux+='>';
                            //console.info(_t);



                            if(inside_anchor) {

                                aux+='<div href="'+_t.attr('data-link')+'" class="the-overlay-anchor '+overlay_extra_class+'"';

                            }else{

                                aux+='<a href="'+_t.attr('data-link')+'" class="the-overlay-anchor '+overlay_extra_class+'"';

                            }

                            if(_t.attr('data-overlay_anchor_extra_attr')){
                                aux+=_t.attr('data-overlay_anchor_extra_attr');
                            }


                            aux+='>';


                            if(_t.children('.overlay-anchor-extra-html').length>0){
                                aux+=_t.children('.overlay-anchor-extra-html').eq(0).html();
                            }


                            if(inside_anchor) {

                                aux+='</div';
                            }else{

                                aux+='</a';
                            }

                        }
                    }

                    if(_t.attr('data-overlay_extra_attr')){
                        aux+=_t.attr('data-overlay_extra_attr');
                    }

                    aux+='>';



                    if(izlink){

                        aux+='</a>';
                    }else{

                        aux+='</div>';
                    }




                    // console.info('aux - ',aux);
                    _ti.find('.the-feature-con').eq(0).appendOnce(aux);



                    if(o.design_skin=='skin-gazelia'){

                        //console.info('ceva',_t, cthis.children('.the-overlay-anchor').length)
                        if(_t.children('.the-overlay-anchor').length>0){
                            //console.info(ti.find('.the-overlay').eq(0));
                            _t.find('.the-overlay').eq(0).append(_t.children('.the-overlay-anchor').eq(0))
                        }
                    }

                    if(o.design_skin=='skin-lazarus'){

                        //console.info('ceva',_t, cthis.children('.the-overlay-anchor').length)
                        if(_t.children('.the-overlay-anchor').length>0){
                            //console.info(ti.find('.the-overlay').eq(0));
                            _t.find('.the-overlay').eq(0).append(_t.children('.the-overlay-anchor').eq(0))
                        }
                    }

                    if(o.design_skin=='skin-material'){

                        var _itemMeta = _t.find('.item-meta').eq(0);
                        var _theFeatureCon = _t.find('.the-feature-con').eq(0);
                        // console.info("ceva", _itemMeta);;

                        if(_itemMeta.length){
                            _theFeatureCon.before(_itemMeta.find('.the-title'));
                        }
                    }

                    if(design_item_thumb_height_dynamic==false && design_item_thumb_height>0){

                        // console.warn('design_item_thumb_height - ',design_item_thumb_height);
                        // _ti.find('.the-feature-con').height(design_item_thumb_height);





                    }


                    // console.info('design_item_thumb_height_dynamic - ',design_item_thumb_height_dynamic);

                    _t.addClass(o.item_extra_class);

                    _t.addClass('inited');

                    //console.info(i);
                    // _t.attr('data-sort',i*10);
                    _t.attr('data-sort',_items.children('.zfolio-item').index(_t)*10);
                    // _t.attr('data-sort',_items.children('.zfolio-item').index(_t)*(Math.random()*100));



                    // -- deprecated

                    var the_cats = _t.attr('data-category');
                    if (the_cats) {

                        the_cats = the_cats.split(';');
                        //console.log(the_cats);
                        for (var j = 0; j < the_cats.length; j++){
                            var the_cat = the_cats[j];
                            var the_cat_unsanatized = the_cats[j];
                            if (the_cat != undefined) {
                                the_cat = the_cat.replace(/ /gi, '-');

                                _t.addClass('cat-' + the_cat);

                            }
                            sw = false;

                            //console.log(the_cats, arr_cats, the_cat_unsanatized)
                            for (var k = 0; k < arr_cats.length; k++) {
                                if (arr_cats[k] == the_cat_unsanatized) {
                                    sw = true;
                                }
                            }
                            if (sw == false) {

                                // console.info(the_cat_unsanatized);
                                arr_cats.push(the_cat_unsanatized);
                            }
                        }
                    }
                    // -- deprecated END




                    var the_cats_regex = /termid-(\d+)/ig;


                    var aux = null;
                    // console.info(_t.attr('class'));

                    while(aux = the_cats_regex.exec(_t.attr('class'))){

                        var the_cat = aux[1];

                        // console.info(aux);

                        sw = false;

                        //console.log(the_cats, arr_cats, the_cat_unsanatized)
                        for (var k = 0; k < arr_cats.length; k++) {
                            if (arr_cats[k] == the_cat) {
                                sw = true;
                            }
                        }
                        if (sw == false) {

                            // console.info(the_cat_unsanatized);
                            arr_cats.push(the_cat);
                        }

                        arr_cats_type = 'dataterm';
                    }

                    // console.info(aux);





                    i++;



                    // console.info('o.design_item_thumb_height - ',o.design_item_thumb_height);


                    if(o.design_item_thumb_height=='proportional'){
                        var aux = {
                            'image':_t.attr('data-thumbnail')
                            ,'item':_t
                            ,'loaded':"off"
                        }

                        images_tobeloaded.push(aux);
                    }
                    if(o.design_item_thumb_height==1){
                        var aux = {
                            'image':_t.attr('data-thumbnail')
                            ,'item':_t
                            ,'loaded':"off"
                        }

                        images_tobeloaded.push(aux);
                    }


                    if(o.settings_mode=='scroller'){
                        // console.info('_t - ',_t);

                        _t.addClass('csc-item');
                        _contentScrollerItems.append(_t);
                    }
                });




                if(o.settings_mode=='scroller') {
                    if (window.dzscsc_init) {

                        if(o.scroller_settings.per_row=='default'){

                            if(cthis.hasClass('dzs-layout--5-cols')){

                                o.scroller_settings.per_row = '5';
                            }

                        }

                        if(o.scroller_settings.per_row=='default'){


                            o.scroller_settings.per_row = '4';
                        }

                        dzscsc_init(_contentScroller, o.scroller_settings)
                    }
                }

                // -- still reinit()

                // console.log('arr_cats', arr_cats);



                if(o.outer_con_selector_con){

                    if(o.outer_con_selector_con.off){

                    }else{
                        (o.outer_con_selector_con) = $(o.outer_con_selector_con)
                    }

                    _selectorCon=(o.outer_con_selector_con);
                }

                // console.info('selector_con_skin - ',o.selector_con_skin);
                if(arr_cats.length > 1 && cthis.find('.selector-con').length==0){

                    var aux = '<div class="selector-con '+o.selector_con_skin+'"><div class="categories">';
                    aux+='</div></div>';


                    if(o.outer_con_selector_con){

                        _selectorCon=(o.outer_con_selector_con);
                    }else{

                        _items.before(aux);
                        _selectorCon = cthis.find('.selector-con').eq(0);
                    }




                }else{
                    if(o.outer_con_selector_con){

                        _selectorCon=$(o.outer_con_selector_con);
                        _selectorCon.addClass('empty-categories');

                        // console.info(_selectorCon);


                    }
                }


                if(_selectorCon && _selectorCon.children){
                    categories_parent = _selectorCon.children('.categories');


                    if(categories_parent.find('.a-category').length){

                        console.warn(categories_parent.find('.a-category'),o.selector_con_generate_categories);
                        if(o.selector_con_generate_categories=='auto'){
                            o.selector_con_generate_categories = 'off';
                        }
                    }

                    if(o.selector_con_generate_categories=='auto'){
                        o.selector_con_generate_categories = 'on';
                    }



                    if(o.filters_enable=='off'){
                        _selectorCon.hide();
                    }



                    // console.info('arr_cats - ',arr_cats);






                    if(cthis.find('.feed-zfolio-zfolio-term').length){
                        arr_cats = [];

                        cthis.find('.feed-zfolio-zfolio-term').each(function(){
                            var _t = $(this);

                            arr_cats.push(_t.attr('data-termid'));
                            cthis.addClass('has-filters');
                        })
                        ;
                    }



                    if(o.selector_con_generate_categories=='off'){





                    }else{
                        if(categories_parent.length){
                            categories_parent.html('');
                        }
                        if(o.settings_useLinksForCategories=='on'){
                            categories_parent.append('<a class="a-category allspark active" href="'+add_query_arg(window.location.href, 'dzsp_defCategory_'+cid, 0)+'">'+ o.settings_categories_strall+'</a>');

                        }else{
                            categories_parent.append('<div class="a-category allspark active">'+ o.settings_categories_strall+'</div>');
                        }


                        for (i = 0; i < arr_cats.length; i++) {
                            //categories_parent.append('');

                            var label = cthis.find('.feed-zfolio-zfolio-term[data-termid="' + arr_cats[i] + '"]').eq(0).html();
                            // console.info('hmm arr_cats[i] - ',label);
                            if(o.settings_useLinksForCategories=='on'){
                                // categories_parent.append('<a class="a-category">'+arr_cats[i]+'</a>');



                                if(arr_cats_type=='dataterm'){


                                    if(label){

                                        categories_parent.append('<a class="a-category"  href="'+add_query_arg(window.location.href, 'dzsp_defCategory_'+cid, (i+1))+'" data-termid="' + arr_cats[i] + '">' + label + '</a>');
                                    }
                                }else{

                                    categories_parent.append('<a class="a-category"  href="'+add_query_arg(window.location.href, 'dzsp_defCategory_'+cid, (i+1))+'" >' + arr_cats[i] + '</a>');
                                }

                            }else{

                                if(arr_cats_type=='dataterm'){



                                    if(label){

                                        categories_parent.append('<div class="a-category" data-termid="' + arr_cats[i] + '">' + label + '</div>');
                                    }
                                }else{

                                    categories_parent.append('<div class="a-category" >' + arr_cats[i] + '</div>');
                                }
                            }
                        }


                        _selectorCon.removeClass('empty-categories');
                    }


                }


                images = _items.children();


                // console.warn("REINIT",'o.design_item_thumb_height - ',o.design_item_thumb_height);


                if(o.design_item_thumb_height=='proportional'){
                    load_batch_images({
                        call_scroll: false
                        ,'call_from':'reinit'
                    });
                }

                if(o.settings_add_loaded_on_images=='on'){

                    nrLoaded=0;
                    loadImage();


                    setTimeout(function(){
                        checkIfLoaded({
                            'force_all_loaded':true
                        });


                    },6700)
                }

            }

            function load_isotope(){

                var args = {};
                args = $.extend(args, o.settings_isotope_settings);


                args.transitionDuration = '0s';
                //console.info(args);

                // -- init isotope here

                // console.info('init isotope args',args);


                // console.info('isotope args - ',args);

                if(cthis.hasClass('dzs-layout--3-cols')){
                    args.percent_amount = 33.3333;
                }
                if(cthis.hasClass('dzs-layout--4-cols')){
                    args.percent_amount = 25;
                }
                if(cthis.hasClass('dzs-layout--6-cols')){
                    args.percent_amount = 16.6666;
                }


                var url = qucreative_options.theme_url+'libs/zfolio/jquery.isotope.min.js';
                //console.warn(scripts[i23], baseUrl, url);
                $.ajax({
                    url: url,
                    dataType: "script",
                    success: function(arg){
                        //console.info(arg);


                        _items.isotope(args);

                        _items.addClass('isotoped');

                    }
                });
            }

            function init_ready(){


                // console.info('init_ready()');

                if(o.settings_mode=='isotope'){

                    if(o.design_item_thumb_height!='proportional'){

                        _items.children('*:not(.grid-sizer):not(.gutter-sizer)').addClass('isotope-item');
                    }

                    //<div class="gutter-sizer"></div>
                    _items.prepend('<div class="grid-sizer"></div>');
                    if(cthis.hasClass('dzs-layout--5-cols')){
                        //o.settings_isotope_settings.columnWidth = '.grid-sizer';

                        //o.settings_isotope_settings.columnWidth=(cthis.width()/5)
                        //o.settings_isotope_settings.isFitWidth=true;
                    }

                    //console.info(o.settings_isotope_settings);
                    if(1){

                        var args = {};
                        args = $.extend(args, o.settings_isotope_settings);


                        args.transitionDuration = '0s';

                        if(cthis.hasClass('dzs-layout--3-cols')){
                            args.percent_amount = 33.3333;
                        }
                        if(cthis.hasClass('dzs-layout--4-cols')){
                            args.percent_amount = 25;
                        }
                        if(cthis.hasClass('dzs-layout--6-cols')){
                            args.percent_amount = 16.6666;
                        }


                        if($.fn.isotope){

                            _items.isotope(args);
                            _items.addClass('isotoped');
                        }else{
                            load_isotope();
                        }
                        _items.children('.isotope-item').addClass('isotoped-item');

                        inter_set_transition_duration = setTimeout(function(){
                            args.transitionDuration = '0.3s';
                            args.transitionDuration = '0.4s';
                            //console.info(args);
                            _items.isotope(args);

                            cthis.addClass('dzszfl-ready-for-transitions');
                        },4000);
                    }
                }




                if(o.pagination_selector){
                    o.pagination_selector = $(o.pagination_selector);
                    _paginationCon = o.pagination_selector;
                }

                // console.info(_selectorCon);



                if(_selectorCon){

                    _selectorCon.off('click', '.a-category');
                    _selectorCon.on( 'click', '.a-category',handle_mouse);
                    //_selectorCon.delegate('.a-category.active', 'click', handle_mouse);
                    //_selectorCon.find('.a-category').bind('click', handle_mouse);
                }

                if(o.outer_con_search_con){
                    var _c = o.outer_con_search_con;

                    _c.on('keyup',handle_key);
                }

                if(_paginationCon){

                    // console.info('_paginationCon->',_paginationCon);
                    _paginationCon.find('a').addClass('dzszfl-pagination-a custom-a');
                    _paginationCon.on('click','a',handle_mouse);
                }





                cthis.get(0).api_destroy = destroy;
                cthis.get(0).api_handle_resize = handle_resize;
                cthis.get(0).api_destroy_listeners = destroy_listeners;
                cthis.get(0).api_relayout_isotope = function(){

                    if(inter_relayout_allow){

                        clearTimeout(inter_relayout);
                        inter_relayout = setTimeout(calculate_dims_only_relayout, 500);
                    }

                };


                setTimeout(function(){
                    inter_relayout_allow=true;
                },2500)


                handle_resize();
                $(window).on('resize.dzszfl_'+cid,handle_resize);



                if(o.settings_defaultCat==''){
                    if(get_query_arg(window.location.href,'dzsp_defCategory_'+cid)){
                        // o.settings_defaultCat = _selectorCon.find('.a-category').eq(Number(get_query_arg(window.location.href, 'dzsp_defCategory_'+cid))).html();


                        var ind = get_query_arg(window.location.href,'dzsp_defCategory_'+cid);


                        if(categories_parent) {
                            categories_parent.children().eq(ind).trigger('click');

                        }


                    }
                }else{
                    goto_category(o.settings_defaultCat, {
                        'class_name':'termid'
                    })
                }


                setTimeout(function(){
                    var args={

                        'parse_items':false
                        ,'relayout_isotope':true
                        ,'disable_easing_on_isotope_transiton':false
                    };
                    //console.info('recalculate relayout isotope on init ... ');
                    calculate_dims(args);
                },2500);

                //console.info(o.settings_add_loaded_on_images);


                if(o.design_item_height==0){

                    setTimeout(function(){

                        init_allready();
                    },1200);

                }else{


                    setTimeout(function(){
                        init_allready();
                    },1200);
                }

                // console.info("ZFOLIO READY");
                if(o.settings_mode=='scroller'){

                    setTimeout(function(){
                        cthis.addClass('inited-scroller-ready');
                    },1000);
                }

            }

            function init_allready(){

                if(_items.css('opacity')=='0'){

                    _items.animate({
                        opacity:1
                    },{
                        duration: 500
                        ,queue:false
                    })
                }


                cthis.removeClass('set-height-when-final');
                cthis.addClass('dzszfl-ready');


                _items.on( 'removeComplete',
                    function( event, removedItems ) {
                        console.log( 'Removed ' + removedItems.length + ' items' );
                    }
                );
            }

            function image_onload(e){

                var _t = this;


                if(this && this.removeEventListener){
                    this.removeEventListener('load',handleLoadedImage);
                }

                // console.log('image_onload() - ', _t,_t.item,e);

                // console.info(e);

                if(e.type=='error'){

                    images_tobeloaded[this.indexinarr].loaded = "error";
                }else{

                    images_tobeloaded[this.indexinarr].loaded = "on";
                }



                var _c = this.item;

                var nw = this.naturalWidth;
                var nh = this.naturalHeight;

                var perc = Number(nw/nh).toFixed(3);

                _c.find('.the-feature').attr('naturalwidth',nw);
                _c.find('.the-feature').attr('naturalheight',nh);
                _c.find('.the-feature').attr('n_perc',perc);
                _c.find('.the-feature').css('padding-top',(1/perc*100)+ '%');





                if(lastmargs.call_from=='handle_scroll_inner'){
                    // return false;
                }
                check_if_items_can_show({
                    'call_from':'image_onload'
                    ,'call_scroll':false
                });

            }

            function load_batch_images(pargs){


                // -- lets see first if it needs loading :D

                var margs = {

                    'call_scroll':true
                    ,'call_from':'default'
                };


                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                // console.info('load_batch_images', margs);
                // console.info('images_tobeloaded -> ',images_tobeloaded, 'can_load_next_images_first_time - ',can_load_next_images_first_time, 'can_load_next_images_from_prev_loaded - ', can_load_next_images_from_prev_loaded,'can_load_next_images_from_scroll - ',can_load_next_images_from_scroll );


                // console.info('can_load_next_images_from_prev_loaded - ',can_load_next_images_from_prev_loaded);
                // console.info('can_load_next_images_from_scroll - ',can_load_next_images_from_scroll);
                if(can_load_next_images_first_time == false && (can_load_next_images_from_prev_loaded==false || can_load_next_images_from_scroll==false) ){
                    return;
                }




                lastmargs = margs;

                if(images_tobeloaded && images_tobeloaded.length){



                    var j = 0;

                    console.info('images_tobeloaded - ',images_tobeloaded);
                    for(var i in images_tobeloaded){

                        if(images_tobeloaded[i].loaded=="on" || images_tobeloaded[i].loaded=="set" || images_tobeloaded[i].loaded=="error"){
                            continue;
                        }

                        var auxImage = new Image();
                        auxImage.src=images_tobeloaded[i].image;
                        auxImage.item=images_tobeloaded[i].item;
                        auxImage.indexinarr=i;


                        if(margs.call_from!='handle_scroll_inner'){
                        }
                        auxImage.onload=image_onload;

                        auxImage.onerror=image_onload;

                        images_tobeloaded[i].loaded = "loading";


                        // console.info('loading .. ',images_tobeloaded[i].image);

                        j++;
                        if(j>=nr_per_page && cthis.hasClass('pagination-method-off')==false){
                            break;
                        }
                    }

                    // console.info('lastindex - ',i, 'nr_per_page - ',nr_per_page);

                    can_load_next_images_from_prev_loaded = false;
                    can_load_next_images_from_scroll = false;



                    if(can_load_next_images_first_time){
                        nr_per_page = Number(o.settings_ajax_method_curritems_per_page);
                    }
                    can_load_next_images_first_time = false;

                    setTimeout(function(){

                        // -- it s not from here

                        // console.info("CALLING SCROLL? ",margs);
                        if(margs.call_scroll){

                            handle_scroll_inner(null, {

                            })
                        }
                    },100);
                }

            }

            function check_if_items_can_show(pargs){
                // -- call from image_onload





                var margs = {

                    'call_scroll':true
                    ,'call_from':'default'
                };


                if(pargs){
                    margs = $.extend(margs,pargs);
                }

                console.warn('check_if_items_can_show', margs,images_tobeloaded);



                // console.info(images_tobeloaded);


                // -- deprecated


                var swnext = true;
                for(var i in images_tobeloaded){

                    if(images_tobeloaded[i].loaded == "loading"){
                        swnext = false;
                    }
                }

                // console.info('swnext - ',swnext);

                if(swnext){


                    _items.children().removeClass('new-item');
                    for(var i in images_tobeloaded){

                        if(images_tobeloaded[i].loaded == "on" || cthis.hasClass('pagination-method-off') ){
                            var _c = images_tobeloaded[i].item;

                            _c.addClass('isotope-item');

                            if(_c.hasClass('isotoped-item')==false){
                                _c.addClass('new-item');
                            }


                            images_tobeloaded[i].loaded = 'set';


                            // _items.isotope('insert', _c);
                            setTimeout(function(arg){
                                // console.warn('arg - ',arg);
                            },300,_c);
                            setTimeout(function(arg){
                                // console.warn('arg - ',arg);
                                arg.addClass('loaded');
                            },1000,_c);




                        }
                    }

                    var _newitems = _items.children('.new-item');

                    // console.info('newitems - ',_newitems);
                    if(lastmargs.call_from=='handle_scroll_inner'){
                        // return false;
                    }
                    _items.isotope('appended', _newitems);
                    // _items.isotope('insert', _newitems);
                    // _items.isotope('reloadItems');

                    setTimeout(function(){

                        // _items.isotope('layout');
                        _items.children().removeClass('new-item');
                    },1000);




                    calculate_dims_the_feature_div();



                    can_load_next_images_from_prev_loaded = true;

                    // console.info("DID IT");
                    load_batch_images({
                        'call_from':'check_if_items_can_show'
                    });


                    setTimeout(function(){
                        calculate_dims_only_relayout();
                    },500);
                }
            }


            function loadImage(){
                // console.info('loadImage');

                if(images){
                    var _t = images.eq(nrLoaded);

                    if(1==1){
                        //console.log(_t.attr('data-thumbnail'))
                        var auxImage = new Image();
                        auxImage.src=_t.attr('data-thumbnail');
                        auxImage.onload=handleLoadedImage;
                    }else{
                        handleLoadedNonImage();
                    }

                }



            }



            function imgLoadedEvent(e){

            }

            function handleLoadedImage(e, pargs){
                var _tar = (e.target);
                // console.log(_tar, $(_tar).css('display'),nrLoaded, images.eq(nrLoaded));


                var margs = {

                };


                if(pargs){
                    margs = $.extend(margs,pargs);
                }

                //console.info(this);

                if(this && this.removeEventListener){
                    this.removeEventListener('load',handleLoadedImage);
                }

                images.eq(nrLoaded).addClass('image-loaded');
                loadedArray[nrLoaded]=true;
                widthArray[nrLoaded] = parseInt(_tar.naturalWidth,10);
                heightArray[nrLoaded] = parseInt(_tar.naturalHeight,10);
                //if(o.design_thumbh == 'proportional'){
                //    arr_thumbhproportionals[nrLoaded] = heightArray[nrLoaded] / widthArray[nrLoaded];
                //    thumbh_dependent_on_w = true;
                //}


                nrLoaded++;
                //console.log("==== CALL FROM IMAGE LOADED / works in chrome yes");
                checkIfLoaded();
            }
            function handleLoadedNonImage(e){
                loadedArray[nrLoaded]=true;
                //console.log(e);
                nrLoaded++;
                //console.log("==== CALL FROM NONIMAGE");
                checkIfLoaded();
            }
            function checkIfLoaded(pargs){
                //nrLoaded++;
                // console.info('checkIfLoaded - ',nrLoaded,o.settings_preloadall,nr_children);


                var margs = {
                    'force_all_loaded' : false
                };



                if(pargs){
                    margs = $.extend(margs,pargs);
                }

                if(margs.force_all_loaded){
                    nrLoaded = nr_children;
                    images.addClass('image-loaded');
                }

                if(o.settings_preloadall=='on'){
                    if(nrLoaded>=nr_children) {
                        setTimeout(init_allready, 1000);
                    }
                }
                if(o.settings_add_loaded_on_images=='on'){
                    if(nrLoaded>=nr_children) {
                        cthis.addClass('all-images-loaded');
                    }
                }
                if(nrLoaded<nr_children){
                    loadImage();
                }
            }

            function fadeout_and_destroy_items(){

                zfolio_is_faded = true;


                // -- new page


                // console.error("CALL SCROLL");

                // console.info('cthis.offset().top (for scroll) -> ',cthis.offset().top - 100)

                var aux_scroll_pos = cthis.offset().top - 100;
                if($('.scroller-con.type-scrollTop').get(0) && $('.scroller-con.type-scrollTop').get(0).api_scrolly_to){

                    $('.scroller-con.type-scrollTop').get(0).api_scrolly_to(aux_scroll_pos);
                }else{

                    $('html, body').animate({
                        scrollTop: aux_scroll_pos
                    }, 300);
                }

                _items.animate({
                    'opacity':0
                },{
                    queue:false
                    ,duration: 300
                    ,complete: function(){
                        var _t = $(this);

                        // console.info(_t);

                        goto_category("*",{
                            'call_from':'fadeout_and_destroy_items'
                        });
                        destroy_items();

                        zfolio_is_faded = false;

                    }
                })

                if(_selectorCon){




                    // TODO: lets leave this one for now
                    // _selectorCon.animate({
                    //     'opacity':0
                    // }, {
                    //     queue: false
                    //     , duration: 300
                    // });
                }
                if(_paginationCon){
                    // _paginationCon.animate({
                    //     'opacity':0
                    // }, {
                    //     queue: false
                    //     , duration: 300
                    // });
                }
            }

            function destroy_items(){



                if(o.settings_mode=='isotope') {

                    // cthis.find('.')
                    // _items.isotope('destroy');



                }


                // _items.children().remove();





                // _items.children().remove();
            }

            function ajax_append_new_items(arg, pargs){

                // console.warn('ajax_append_new_items', zfolio_is_faded, arg);
                console.warn('ajax_append_new_items', zfolio_is_faded);




                var margs = {
                    'call_from' : "default"
                };



                // return;


                if(pargs){
                    margs = $.extend(margs,pargs);
                }


                console.info('ajax_append_new_items', margs, zfolio_is_faded);

                if(zfolio_is_faded){
                    setTimeout(function(){
                        ajax_append_new_items(arg);
                    },500);


                    return false;
                }







                _items.children('.zfolio-item').addClass('old-item');



                if(o.settings_mode=='isotope') {

                    _items.children('.zfolio-item.old-item').each(function(){
                        var _t3 = $(this);
                        // console.warn('lets destroy _t3 - ',_t3.get(0));
                        // _items.isotope('remove',this);
                        _items.isotope('remove',_t3);
                    })


                    // var $allAtoms = _items.data('isotope').$allAtoms;
                    // _items.isotope( 'remove', $allAtoms );


                    // _items.isotope('destroy');
                    // _items.children('.zfolio-item').remove();
                    setTimeout(function(){

                        // _items.children('.zfolio-item.old-item').remove();
                    },10);
                    setTimeout(function(){

                        // _items.isotope('layout');
                    },300);
                }

                setTimeout(function(){
                    _items.append(arg);



                    // console.info('o.settings_mode -4', o.settings_mode);

                    if(o.settings_mode=='isotope') {

                        var args = {};
                        args = $.extend(args, o.settings_isotope_settings);


                        //console.info(args);

                        // _items.children('.zfolio-item:not(".old-item")').each(function(){
                        //     var _t23 = $(this);
                        //
                        //     console.info("APPENDING ITEM", _t23);
                        //
                        //     _items.isotope('appended', _t23);
                        //     // _items.isotope('insert', _t23);
                        // });


                        // console.info("TRYING TO ADD ITEMS", _items.children('.zfolio-item:not(".old-item")'));
                        // _items.isotope('appended', _items.children('.zfolio-item'), function(){console.log('Appended',this);});
                        // _items.isotope('appended', _items.children('.zfolio-item:not(".old-item")'), function(){console.log('Appended',this);});
                        // _items.isotope('insert', _items.children('.zfolio-item:not(".old-item")'), function(){console.log('Appended',this);});


                        // console.info('isotope args - ',_items.children('.isotope-item'), args);

                        _items.children('*:not(.grid-sizer):not(.gutter-sizer)').addClass('isotope-item');
                        // _items.isotope(args);
                        // _items.isotope('appended', _items.children());


                        var _newitems = _items.children('*:not(.grid-sizer):not(.gutter-sizer):not(.old-item)');

                        if(cthis.hasClass('thumbnail-height-mode-normal')){
                            _items.isotope('appended', _newitems);
                        }
                        // _items.isotope('insert', _newitems);

                        // _items.isotope('addItems', _newitems);


                        // console.info("NEW ITEMS - ",_newitems);

                        // _items.isotope('addItems', _newitems);
                        // _items.isotope('reloadItems');
                        // _items.isotope('layout');


                        setTimeout(function(){
                            // console.info("PACKERY getElements");
                            // var elems = _items.isotope('getItemElements')
                            //




                            // console.info("LETS RELOAD isotope");

                            // console.info(_items.isotope('getItemElements'))
                            // console.info(elems);
                            // _items.isotope('reloadItems');

                            // console.info(' initing CALCULATE_DIMS -4', o.settings_mode);

                            calculate_dims({

                                'parse_items':true
                                ,'relayout_isotope':true
                                ,'disable_easing_on_isotope_transiton':false
                            });


                            _items.isotope('layout');
                        },500)

                        setTimeout(function(){
                            // console.info("PACKERY getElements");
                            // var elems = _items.isotope('getItemElements')
                            //




                            // console.info("LETS RELOAD isotope");

                            // console.info(_items.isotope('getItemElements'))
                            // console.info(elems);
                            // _items.isotope('reloadItems');
                            // _items.isotope('layout');
                        },1500)

                    }
                    reinit({
                        'call_from':'ajax_append_new_items'
                    });

                    setTimeout(function(){

                        if(cthis.hasClass('thumbnail-height-mode-normal')){
                        }
                        calculate_dims();
                        // calculate_dims();
                    })


                    setTimeout(function(){
                        _items.animate({
                            'opacity':1
                        },{
                            queue:false
                            ,duration: 300
                            ,complete: function(){

                            }
                        })

                        if(_selectorCon){

                            _selectorCon.animate({
                                'opacity':1
                            },{
                                queue:false
                                ,duration: 300
                            })
                        }
                        if(_paginationCon){

                            _paginationCon.animate({
                                'opacity':1
                            },{
                                queue:false
                                ,duration: 300
                            })
                        }
                    },1000);
                },300);



            }

            function destroy_listeners(){


                // console.info(cthis, 'settings_mode - ',o.settings_mode);
                if (o.settings_mode=='isotope' && $.fn.isotope) {


                    try{

                        // console.info('_items -3',_items);
                        _items.isotope('destroy');
                    }catch(err){
                        // console.warn(_items);
                        // console.warn(err);
                    }
                }


                cthis.off('click');

                $(window).off('resize.dzszfl_'+cid);
                $(window).off('scroll.dzszfl_'+cid);
                destroyed=true;

            }
            function destroy(){

                if (o.settings_mode=='isotope' && $.fn.isotope) {


                    try{

                        // console.info('_items -3',_items);
                        // _items.isotope('destroy');
                    }catch(err){
                        // console.warn(_items);
                        // console.warn(err);
                    }
                }
                destroyed=true;

            }

            function handle_key(e) {
                var _t = $(this);


                if(e.type=='keyup'){
                    console.info("_t - ",_t);





//                console.log(key, value);
                    if(o.settings_mode=='isotope'){
                        _items.isotope({ filter: function() {
                                var name = $(this).find('.item-meta').eq(0).text();
                                var ok = false;

                                var regex = new RegExp('.*'+_t.val()+'.*');

                                if(name.match( regex)){
                                    ok = true;
                                }


                                return ok;
                            } });
                    }
                    if(o.settings_mode=='simple'){
                        _items.children().fadeOut('fast');
                        _items.children(value).fadeIn('fast');
                    }
                }
            }


            function filter_cat(_t){

                var sw_return_false = false;



                if(cthis.hasClass('dzszfl-ready-for-transitions')==false){

                    var args = {};
                    args = $.extend(args, o.settings_isotope_settings);


                    args.transitionDuration = '0s';

                    if(cthis.hasClass('dzs-layout--3-cols')){
                        args.percent_amount = 33.3333;
                    }
                    if(cthis.hasClass('dzs-layout--4-cols')){
                        args.percent_amount = 25;
                    }
                    if(cthis.hasClass('dzs-layout--6-cols')){
                        args.percent_amount = 16.6666;
                    }


                    args.transitionDuration = '0.3s';
                    args.transitionDuration = '0.4s';
                    //console.info(args);
                    _items.isotope(args);

                    cthis.addClass('dzszfl-ready-for-transitions');

                    clearTimeout(inter_set_transition_duration);
                }

                // console.info("CLICKED A CATEGORY");

                if(_t.hasClass('active')){


                    _selectorCon.toggleClass('is-opened');

                    sw_return_false = true;
                }



                var ind = _t.parent().children().index(_t);

                var cat = _t.html();
                if(_t.attr('data-termid')){

                    cat = (_t.attr('data-termid'));
                    goto_category(cat,{
                        'class_name' : 'termid'
                    });
                    // _selectorCon.removeClass('is-opened');
                }else{




//                console.info(o.settings_useLinksForCategories, o.settings_useLinksForCategories_enableHistoryApi)


                    if(o.settings_useLinksForCategories!='on' || o.settings_useLinksForCategories_enableHistoryApi =='on'){
                        goto_category(cat);
                        // return false;
                    }
                }

                _selectorCon.removeClass('is-opened');

                if(o.settings_useLinksForCategories=='on' && o.settings_useLinksForCategories_enableHistoryApi=='on' ){


                    // console.info("REACHED HER");
                    var stateObj = { foo: "bar" };
                    history.pushState(stateObj, "ZoomFolio Category "+ind, add_query_arg(window.location.href, 'dzsp_defCategory_'+cid, (ind)));


                }

                if(o.settings_useLinksForCategories_enableHistoryApi=='on' ){


                    sw_return_false = true;
                }

                return sw_return_false;

            }
            function handle_mouse(e){
                var _t = $(this);

                if(e.type=='click'){
                    if(_t.hasClass('a-category')){

                        var sw_return_false = filter_cat(_t);


                        if(sw_return_false){
                            return false;
                        }

                    }
                    if(_t.hasClass('dzszfl-pagination-a')){


                        if(_t.hasClass('active') || _t.parent().hasClass('active') || _t.attr('href')=='#'){

                            return false;
                        }


                        fadeout_and_destroy_items();


                        $.ajax({
                            url: _t.attr('href'),
                            context: document.body
                        }).done(function (response) {

                            // console.info('got this from server ' + response);
                            ajax_append_new_items(response, {
                                'call_from':'ajax-pagination'
                            });


                        });


                        _t.parent().parent().find('.active').removeClass('active');

                        _t.parent().addClass('active');



                        // console.info(_t);

                        return false;
                    }
                }
            }
            function handle_resize(e,pargs){


                var margs={
                    calculate_dims_init: true
                    ,calculate_excerpt_con: true
                    ,excerpt_con_noanimation: true

                };


                if(pargs){
                    margs = $.extend(margs,pargs);
                }

                ww = window.innerWidth;
                wh = window.innerHeight;
                tw = cthis.width();


                // console.info('zfolio', tw);
                if(ww<=520){
                    cthis.addClass('under-520');
                }else{

                    cthis.removeClass('under-520');
                }
                if(ww<=720){
                    cthis.addClass('under-720');

                    if(o.outer_con_selector_con){
                        o.outer_con_selector_con.addClass('under-720');
                    }
                }else{

                    cthis.removeClass('under-720');
                    if(o.outer_con_selector_con){

                        o.outer_con_selector_con.removeClass('under-720');
                    }
                }
                if(ww<=1000){
                    cthis.addClass('under-1000');

                    if(o.outer_con_selector_con){
                        o.outer_con_selector_con.addClass('under-1000');
                    }
                }else{

                    cthis.removeClass('under-1000');
                    if(o.outer_con_selector_con){

                        o.outer_con_selector_con.removeClass('under-1000');
                    }
                }

                if(margs.calculate_excerpt_con){


                    if(_excerptContent_initialPortItem){
                        if(o.excerpt_con_resize_videos=='on'){

                            excerpt_content_resize_vplayer();
                        }
                    }



                    setTimeout(function(){
                        if(_excerptContent_initialPortItem){
                            _tcon_content.css({
                                'padding': _excerptContent.css('padding-top')
                                ,'width': cthis.outerWidth()
                            })




                            var auxh = 0;
                            if(o.excerpt_con_transition=='zoom'){
                                _excerptContentCon.css({
                                    //'height': _tcon_content.outerHeight()
                                });

                                auxh = _tcon_content.outerHeight();



                                if(_excerptContent.find('.advancedscroller').length>0){
                                    //delaytime2 = 1500;
                                    auxh-=1;
                                }
                                _excerptContent.css({
                                    'height': auxh
                                });
                            }else{

                                //console.info(_tcon_content, _tcon_content.outerHeight());
                                auxh = _tcon_content.outerHeight();

                                if(margs.excerpt_con_noanimation){

                                    auxh = _excerptContent.children('.dzs-colcontainer').outerHeight();

                                    if(_excerptContent.find('.advancedscroller').length>0){
                                        //delaytime2 = 1500;
                                        auxh-=1;
                                    }

                                    _excerptContent.css({
                                        'height': auxh
                                    });



                                    _excerptContentCon.css({
                                        'height': ''
                                    });
                                }






                            }

                            //console.info(_excerptContentCon);
                        }

                    },500)

                }


                if(margs.calculate_dims_init){

                    if(inter_calculate_dims){
                        clearTimeout(inter_calculate_dims);
                    }
                    inter_calculate_dims = setTimeout(calculate_dims, 300);
                }
            }

            function calculate_dims_the_feature_div(){

                // console.info(_items);
                // _items.children('.isotope-item').each(function(){
                //     var _t = $(this);
                //
                //
                //     var _c = _t.find('.the-feature-div');
                //     // console.warn(_c);
                //
                //     if(_c.length){
                //
                //
                //         if(o.design_item_thumb_height=='proportional'){
                //
                //             // console.warn(_c);
                //             var perc = _c.attr('n_perc');
                //
                //             if(perc){
                //                 perc = Number(perc);
                //
                //                 _c.height(1/perc * _c.width());
                //             }
                //         }
                //
                //     }
                // })
            }

            function excerpt_content_resize_vplayer(){

                if(_excerptContent && _excerptContent.find('.vplayer').length>0){

                    var _c = _excerptContent.find('.vplayer').eq(0);


                    var auxr = Number(o.excerpt_con_responsive_ratio);
                    var excerpt_width = _excerptContent.width();

                    if(_c.parent().hasClass('dzs-col-8')){
                        auxr*=2/3;

                        if(o.excerpt_con_responsive_ratio==810){
                            auxr=580;
                            excerpt_width = 580;


                            //console.info('window width is ',ww);
                            if(ww<=1000){
                                excerpt_width = _excerptContent.width();
                            }

                        }

                    }

                    var auxih=0;




                    if(_c.data('initial-height-for-excerpt-content')){
                        auxih = Number(_c.data('initial-height-for-excerpt-content'));
                    }else{

                        auxih = 0.5625 * _excerptContent.width();
                        if(_c.parent().hasClass('dzs-col-8')){

                            auxih = 0.5625 * _c.parent().width();
                        }
                        _c.data('initial-height-for-excerpt-content',auxih);
                    }




                    var aux_ratio = auxih/auxr;


                    //console.log(auxr);
                    // console.log('auxih-',auxih,'aux_ratio-',aux_ratio, excerpt_width, 'excerpt_width-',excerpt_width * aux_ratio, _excerptContent, _excerptContent.width());

                    _c.height(auxih);


                }


            }

            function calculate_dims(pargs){

                var margs={

                    'parse_items':true
                    ,'relayout_isotope':true
                    ,'disable_easing_on_isotope_transiton':false
                };


                var registered_heights = [];
                var registered_end_pos = [];
                var sw_recheck_at_end = false; // -- recheck all item so that they align with the others nicely

                if(pargs){
                    margs = $.extend(margs,pargs);
                }

                // console.info('calculate_dims -4',margs,destroyed);
                if(destroyed){
                    return;
                }


                th = cthis.height();
                wh = window.innerHeight;
                var breaker = 20;





                //console.info('ceva');


                if(margs.parse_items){

                    i_dzscol_ind = 0;

                    sw_mode_cols_nr_of_cols = 0;

                    $itemsArray = _items.children('.zfolio-item');


                    if(o.settings_mode=='scroller'){
                        $itemsArray = _contentScroller.find('.thumbsCon').children('.zfolio-item');
                    }


                    $itemsArray.each(function(){


                        var _t = $(this);
                        //console.info(_t);

                        var aux_iw = -1;

                        var aux_tw = cthis.width();

                        if(_items.css('margin-left')=='0px'){
                            // aux_tw+=2;
                            // _items.css('width', 'calc(100% + 10px)')

                            design_item_padding = 0;
                        }
                        if(_items.css('margin-left')=='-1px'){
                            // aux_tw+=2;
                            // _items.css('width', 'calc(100% + 6px)')
                            design_item_padding = 2;
                        }
                        if(_items.css('margin-left')=='-10px'){
                            // aux_tw+=20;
                            // _items.css('width', 'calc(100% + 26px)')
                            design_item_padding = 20;
                        }
                        if(_items.css('margin-left')=='-15px'){
                            // aux_tw+=30;
                            // _items.css('width', 'calc(100% + 30px)')
                            design_item_padding = 30;
                        }

                        // console.warn('design_item_padding - ',design_item_padding);






                        // console.info('cthis class -> ',cthis.attr('class'));


                        var temp_layout = '';



                        cthis.removeClass(class_all_temp_cols);
                        var aux_grid_response = {};

                        aux_grid_response = generate_grid_response(cthis.attr('class'));
                        aux_iw = aux_grid_response.aux_iw;
                        grid_unit_px = aux_grid_response.grid_unit_px;
                        initial_cols_before_fallback = aux_grid_response.nr_cols;

                        // console.info('aux_grid_response-> ',aux_grid_response);

                        cthis.removeClass('temp-dzs-layout--2-cols');

                        if(cthis.hasClass('dzs-layout--6-cols')){


                            cthis.attr('data-nr-cols','6');

                            //console.info(cthis.width(), _items.css('margin-left'), aux_tw);
                            // aux_iw = parseInt((aux_tw) / 5,10);

                            if(cthis.hasClass('under-1000')){

                                // aux_iw = parseInt((aux_tw) / 3,10);
                                // aux_iw = Number(100/3).toFixed(3);
                            }

                            if(cthis.hasClass('under-720')){

                                // aux_iw = parseInt((aux_tw) / 2,10);
                                aux_iw = Number(100/2).toFixed(3);
                                grid_unit_px = tw/2;
                                // cthis.addClass();
                                temp_layout = 'temp-dzs-layout--2-cols';

                                cthis.attr('data-nr-cols','2');
                            }else{

                            }
                            if(cthis.hasClass('under-520')){

                                // aux_iw = parseInt((aux_tw),10);
                                aux_iw = Number(100/1).toFixed(3);
                                grid_unit_px = tw;
                            }

                        }

                        if(cthis.hasClass('dzs-layout--5-cols')){

                            cthis.attr('data-nr-cols','5');


                            //console.info(cthis.width(), _items.css('margin-left'), aux_tw);
                            // aux_iw = parseInt((aux_tw) / 5,10);


                            if(cthis.hasClass('under-1000')){

                                // aux_iw = parseInt((aux_tw) / 3,10);
                                // aux_iw = Number(100/3).toFixed(3);
                            }

                            if(cthis.hasClass('under-720')){

                                // aux_iw = parseInt((aux_tw) / 2,10);
                                aux_iw = Number(100/2).toFixed(3);
                                grid_unit_px = tw/2;
                                temp_layout = 'temp-dzs-layout--2-cols';

                                cthis.attr('data-nr-cols','2');
                            }else{

                            }
                            if(cthis.hasClass('under-520')){

                                // aux_iw = parseInt((aux_tw),10);
                                aux_iw = Number(100/1).toFixed(3);
                                grid_unit_px = tw;
                                cthis.attr('data-nr-cols','1');
                            }

                        }


                        if(cthis.hasClass('dzs-layout--4-cols')){


                            cthis.attr('data-nr-cols','4');


                            //console.info(cthis.width(), _items.css('margin-left'), aux_tw);
                            // aux_iw = parseInt((aux_tw) / 4,10);


                            if(ww<720){

                                // aux_iw = parseInt((aux_tw) / 2,10);
                                aux_iw = Number(100/2).toFixed(3);
                                grid_unit_px = tw/2;


                                temp_layout = 'temp-dzs-layout--2-cols';

                                cthis.attr('data-nr-cols','2');
                            }else{

                            }
                            if(ww<520){

                                // aux_iw = parseInt((aux_tw),10);
                                aux_iw = Number(100/1).toFixed(3);
                                grid_unit_px = tw/1;
                                temp_layout = 'temp-dzs-layout--1-cols';

                                cthis.attr('data-nr-cols','1');
                            }
                        }


                        if(cthis.hasClass('dzs-layout--3-cols')){



                            cthis.attr('data-nr-cols','3');

                            //console.info(cthis.width(), _items.css('margin-left'), aux_tw);
                            // aux_iw = parseInt((aux_tw) / 4,10);
                            if(cthis.hasClass('under-720')){

                                // aux_iw = parseInt((aux_tw) / 2,10);
                                aux_iw = Number(100/2).toFixed(3);
                                grid_unit_px = tw/2;

                                temp_layout = 'temp-dzs-layout--2-cols';

                                cthis.attr('data-nr-cols','2');
                            }else{

                            }
                            if(cthis.hasClass('under-520')){

                                aux_iw = parseInt((aux_tw),10);
                                aux_iw = Number(100/1).toFixed(3);
                                grid_unit_px = tw/1;
                                temp_layout = 'temp-dzs-layout--1-cols';

                                cthis.attr('data-nr-cols','1');
                            }
                        }


                        if(cthis.hasClass('dzs-layout--2-cols')){



                            //console.info(cthis.width(), _items.css('margin-left'), aux_tw);
                            // aux_iw = parseInt((aux_tw) / 4,10);



                            if(cthis.hasClass('under-720')){

                            }
                            if(cthis.hasClass('under-520')){

                                aux_iw = parseInt((aux_tw),10);
                                aux_iw = Number(100/1).toFixed(3);
                                grid_unit_px = tw;
                                temp_layout = 'temp-dzs-layout--1-cols';

                                cthis.attr('data-nr-cols','1');
                            }
                        }





                        if(ww<=920){
                            if(o.responsive_fallback_tablet){

                                aux_grid_response = generate_grid_response(' '+o.responsive_fallback_tablet);
                                aux_iw = aux_grid_response.aux_iw;
                                grid_unit_px = aux_grid_response.grid_unit_px;

                                temp_layout = 'temp-'+(o.responsive_fallback_tablet);


                                var aux = /dzs-layout--(\d)-cols/g.exec(o.responsive_fallback_tablet);


                                if(aux && aux[1]){
                                    cthis.attr('data-nr-cols',aux[1]);
                                }
                            }


                            if(ww<=620){
                                if(o.responsive_fallback_mobile){

                                    aux_grid_response = generate_grid_response(' '+o.responsive_fallback_mobile);
                                    aux_iw = aux_grid_response.aux_iw;
                                    grid_unit_px = aux_grid_response.grid_unit_px;

                                    temp_layout = 'temp-'+(o.responsive_fallback_mobile);


                                    var aux = /dzs-layout--(\d)-cols/g.exec(o.responsive_fallback_mobile);


                                    if(aux && aux[1]){
                                        cthis.attr('data-nr-cols',aux[1]);
                                    }

                                }
                            }



                        }else{

                            if(o.responsive_fallback_tablet || o.responsive_fallback_mobile){


                                // cthis.addClass('dzs-layout--'+initial_cols_before_fallback+'-cols');

                                aux_grid_response = generate_grid_response(cthis.attr('class'));
                                aux_iw = aux_grid_response.aux_iw;
                                grid_unit_px = aux_grid_response.grid_unit_px;
                            }
                        }


                        // console.info('temp_layout -> ',temp_layout);

                        cthis.addClass(temp_layout);









                        grid_unit_px = parseInt(grid_unit_px, 10);




                        // console.info('aux_iw - ',aux_iw);
                        if(o.settings_mode!='scroller') {
                            _t.css('width', '');
                        }

                        if(o.settings_set_forced_width=='on'){

                            if(o.settings_mode!='scroller'){

                                _t.outerWidth(aux_iw);
                            }

                        }


                        // -- dynamic SCALING

                        // console.info('design_item_thumb_height_dynamic -4 ',design_item_thumb_height_dynamic)
                        if(design_item_thumb_height_dynamic){

                            sw_recheck_at_end = true;
                            //console.log(cthis, cthis.width());
                            if(design_item_thumb_height<=2){


                                //console.info(aux_iw, layout_margin);



                                var wexpand = 1;
                                var hexpand = 1;





                                if(_t.attr('data-hexpand')){

                                    hexpand = Number(_t.attr('data-hexpand'));

                                }



                                if(_t.attr('data-wexpand')){

                                    wexpand = Number(_t.attr('data-wexpand'));

                                }

                                var orig_hexpand = hexpand;

                                hexpand = hexpand / wexpand;


                                // if(aux_iw%2 == 1 ) { aux_iw++; };
                                // console.info('aux_iw hier - ',aux_iw);

                                var finalw = wexpand * aux_iw;

                                if(Number(finalw)>99){
                                    finalw = 100;
                                }
                                if(aux_iw>0){
                                    // _t.outerWidth(aux_iw);

                                    // console.info('aux_iw hier - ',aux_iw);
                                    if(o.settings_mode!='scroller') {
                                        _t.css('width', finalw + '%');
                                    }
                                }







                                if(_t.width()<300){
                                    _t.addClass('under-300');
                                }else{

                                    _t.removeClass('under-300');
                                }

                                //console.info(aux_iw);

                                // console.info(_t.width(),_t.outerWidth(),_t.outerWidth(false));



                                var auxh = 0;



                                // console.info('_t.width() - ',_t.width());





                                // console.info('hexpand - ',hexpand);


                                // auxh = Math.floor(hexpand * design_item_thumb_height* Math.floor(_t.width())) + ( (hexpand - 1) * design_item_padding);
                                auxh = (hexpand * design_item_thumb_height* (_t.width())) + ( (hexpand - 1) * design_item_padding);



                                for(var i2 in registered_heights){
                                    // console.info(registered_heights[i2]);

                                    var ncach = registered_heights[i2];




                                    // console.info('ncach - ',ncach);


                                    if(auxh==ncach-1 || auxh ==ncach+1){
                                        auxh = ncach;
                                    }
                                }

                                if($.inArray(auxh,registered_heights)>-1){

                                }else{
                                    registered_heights.push(auxh);
                                }







                                // console.log(registered_heights);
                                // console.info('auxh - ',auxh);


                                // TODO: we dont need this anymore

                                var sw_custom_layout = false;

                                if(cthis.hasClass('custom-layout')){
                                    sw_custom_layout = true;
                                }

                                if(cthis.hasClass('skin-silver')){

                                    // TODO: why ?
                                    // sw_custom_layout = false;
                                }

                                // console.warn('_t - ',_t);

                                if(sw_custom_layout){


                                    _t.find('.zfolio-item--inner').css({
                                        'padding-top': (hexpand*100) + '%'
                                    })
                                }else{


                                    // -- this is it
                                    // _t.find('.the-feature-con').eq(0).height(auxh);
                                    _t.find('.the-feature-con').eq(0).css({
                                        'padding-top': (100) + '%'
                                    })


                                    if(o.design_skin=='skin-silver'){

                                        // _t.eq(0).outerHeight(auxh);
                                        // _t.eq(0).outerHeight(orig_hexpand * grid_unit_px);
                                    }
                                }





                                // just thumb relative


                                // console.info(_t.find('.the-feature-con'), _t.width());
                                // console.info(_t.outerWidth(),_t.outerHeight(), design_item_thumb_height)



                            }
                        }else{
                            //console.info(design_item_thumb_height);
                        }
                    });


                    if(sw_recheck_at_end){



                        // recheck_items_end_pos();
                    }

                    if(sw_mode_cols_nr_of_cols){
                        mode_cols_nr_of_cols = sw_mode_cols_nr_of_cols;
                    }
                }

                ;


                if(margs.relayout_isotope){

                    if(margs.disable_easing_on_isotope_transiton){

                        var args = {};
                        args = $.extend(args, o.settings_isotope_settings);

                        args.transitionDuration = '0s';
                        //console.info(args);

                        _items.isotope(args);

                        _items.children('.isotope-item').addClass('isotoped-item');

                        setTimeout(function(){
                            //args.transitionDuration = '0.3s';
                            ////console.info(args);
                            //_items.isotope(args);

                        },500);
                    }else{

                        if(o.settings_mode=='isotope'){
                            //console.info('fromhere',_items);
                            if(_items && !destroyed){


                                _items.isotope('layout');
                            }
                        }

                    }

                    setTimeout(function(){
                        if(o.settings_mode=='isotope'){
                            if(_items && !destroyed) {
                                _items.isotope('layout');
                            }
                        }
                    },500);
                }

                if(window.dzs_check_lazyloading_images){
                    window.dzs_check_lazyloading_images();
                }
            }

            function generate_grid_response(arg){


                var regex = / dzs-layout--(.*?)-cols/g;



                var oout = {};


                var aux = regex.exec(arg);

                // console.info(aux);

                if(aux){
                    var nr_cols = parseInt(aux[1],10);

                    // console.info('nr_cols -> ',nr_cols);


                    var aux_iw = Number(100/nr_cols).toFixed(4);

                    var grid_unit_px = tw/nr_cols;



                    oout.nr_cols = nr_cols;
                    oout.aux_iw = aux_iw;
                    oout.grid_unit_px = grid_unit_px;

                }

                return oout;
            }

            function calculate_dims_only_relayout(){

                var args = {

                    'parse_items':false
                    ,'relayout_isotope':true
                    ,'disable_easing_on_isotope_transiton':false
                };

                calculate_dims(args);
            }



            function goto_category(arg, args){


                var margs = {
                    'call_from' : 'default'
                    ,'class_name' : 'cat'
                }


                if(args){
                    margs = $.extend(margs,args);
                }

                var options = {};
                var key = "filter";
                //console.log(arg);
                var value = '.'+margs.class_name+'-' + arg;
                if (!arg || arg=="*" || arg == o.settings_categories_strall) {
                    value = "*";
                }

                if(cat_curr==value){
                    return false;
                }

                console.info('goto_category() - ',arg,args, 'cat_curr - ',cat_curr)

                cat_curr = value;
                if(categories_parent){
                    categories_parent.children().removeClass('active');



                    categories_parent.children().each(function(){
                        var _t = $(this);
                        //console.info(_t);
                        if(_t.text()==arg){
                            _t.addClass('active');
                        }


                        // console.info(margs.class_name=='termid', _t.attr('data-termid'), arg);
                        if(margs.class_name=='termid'){
                            if(_t.attr('data-termid')==arg){
                                _t.addClass('active');

                            }
                        }
                    })
                }

                value = value === "false" ? false : value;

                value = value.replace(/ /gi, '-');

//                console.log(key, value);

                // console.info('key - ',key);
                // console.info('value - ',value);
                if(o.settings_mode=='scroller'){
                    console.info('key -', key,'value - ',value);

                    if($itemsArray){

                        $itemsArray.each(function(){
                            var _t = $(this);

                            if(value=='*'){
                                _t.removeClass('filtered-out');


                            }else{

                                // console.info('_t.hasClass(value) -' ,_t.hasClass(value),value, _t.attr('class'));
                                if(_t.hasClass(value.replace('.',''))){

                                    _t.removeClass('filtered-out');
                                }else{
                                    _t.addClass('filtered-out');

                                }
                            }
                        })
                        console.info('$itemsArray -', $itemsArray);
                    }
                }
                if(o.settings_mode=='isotope'){
                    o.settings_isotope_settings[ key ] = value;
                    _items.isotope(o.settings_isotope_settings);

                    setTimeout(function(){

                        _items.isotope(o.settings_isotope_settings);
                    },500);
                }
                if(o.settings_mode=='simple'){
                    _items.children().fadeOut('fast');
                    _items.children(value).fadeIn('fast');
                }

                if($('.main-container').get(0) && $('.main-container').get(0).api_get_view_index_y) {
                    //console.log($('.main-container').get(0).api_get_view_index_y())
                }


            }

            function click_contentOpener(e){
                var _t = $(this);
                var ind = -1;
                _excerptContent_initialPortItem = null;

                //--trial and error
                if(_t.parent().hasClass('zfolio-item')){
                    _excerptContent_initialPortItem = _t.parent();
                }else{
                    if(_t.parent().parent().hasClass('zfolio-item')){
                        _excerptContent_initialPortItem = _t.parent().parent();
                    }else{
                        if(_t.parent().parent().parent().hasClass('zfolio-item')){
                            _excerptContent_initialPortItem = _t.parent().parent().parent();
                        }else{
                            if(_t.parent().parent().parent().parent().hasClass('zfolio-item')){
                                _excerptContent_initialPortItem = _t.parent().parent().parent().parent();
                            }else{
                                if(_t.parent().parent().parent().parent().parent().hasClass('zfolio-item')){
                                    _excerptContent_initialPortItem = _t.parent().parent().parent().parent().parent();
                                }
                            }
                        }
                    }
                }

                // console.info('click_contentOpener -> ',_excerptContent_initialPortItem, _t);

                //console.info(_excerptContent_initialPortItem);
                //--no point in continuing if tcon is not found


                if(_excerptContent_initialPortItem==null ){
                    return false;
                }
                if(_excerptContent_initialPortItem.hasClass('active')){
                    contentOpener_close();
                    return false;
                }else{
                    if(_excerptContentCon){
                        contentOpener_close();
                        _excerptContent_initialPortItem.parent().children().removeClass('active');
                        setTimeout(function(){
                            _t.click();
                        },750)
                        return false;
                    }
                }


                var tcon_y = _excerptContent_initialPortItem.offset().top;

                var sw=false;
                var _tcon_next = null;

                while(sw==false){
                    if(_tcon_next){
                        _tcon_next = _tcon_next.next();;
                    }else{
                        _tcon_next = _excerptContent_initialPortItem.next();
                    }

//                    console.info(_tcon_next);

                    if(_tcon_next.hasClass('zfolio-item')){

                        if(_tcon_next.offset().top!=tcon_y){
                            sw=true;
                            ind = _tcon_next.parent().children('.isotope-item').index(_tcon_next);
                        }

                    }else{
                        sw=true;
                    }
                }

                //console.info();

                var excerptContent_extraClasses = '';
                var portclass = _excerptContent_initialPortItem.attr('class');
                portclass+=' ';

                //console.log(portclass);

                var aux_regex = /cat-\w+/gi;
                var aux_regex_a;
                while(aux_regex_a = aux_regex.exec(portclass)){
                    if(aux_regex_a){
                        excerptContent_extraClasses+=' '+aux_regex_a[0];
                    }

                }



                _tcon_content = _excerptContent_initialPortItem.find('.the-content');

                var aux_excerpt_content_con = '<div class="isotope-item excerpt-content-con'+excerptContent_extraClasses+' transition-'+ o.excerpt_con_transition+' "';





                if(_excerptContent_initialPortItem){

                    // console.info(_excerptContent_initialPortItem);

                    var ind3 = Number(_excerptContent_initialPortItem.attr('data-sort'));

                    // console.info(ind3, ind3+1);
                    // _excerptContentCon.attr('data-sort', (ind)+1)

                    aux_excerpt_content_con+=' data-sort="'+(ind3+1)+'"';



                }



                aux_excerpt_content_con+='><div class="'+_tcon_content.attr('class')+'" style="">'+_tcon_content.html()+'<div class="close-btn">x</div></div></div>';



                // console.warn('aux_excerpt_content_con->',aux_excerpt_content_con);
                if(_tcon_next.length>0){
                    //--- even if the-content div is display: none, the height can still be calculated
//                    console.info(_tcon, _tcon_content.outerHeight());

                    // console.info('excerpt-content-con placed before',_tcon_next);

                    _tcon_next.before(aux_excerpt_content_con);

                }else{
                    // console.info('excerpt-content-con placed appended',_items);
                    _items.append(aux_excerpt_content_con);
                }



                _excerptContentCon = cthis.find('.excerpt-content-con').eq(0);
                _excerptContent = _excerptContentCon.find('.excerpt-content').eq(0);
                setTimeout(function(){

                    if(_excerptContent.removeClass){

                        _excerptContent.removeClass('transitioning-in')
                    }
                },800)
                // _items.isotope('updateSortData').isotope();

                // console.info('_excerptContentCon 3-> ',_excerptContentCon);
                // console.info('_excerptContent 3-> ',_excerptContent);
                //console.info(_excerptContent.find('.advancedscroller').length);


//console.info(_excerptContentCon,_excerptContent);

//                console.info(_tcon_content, _excerptContent.css('padding'), _excerptContent, _excerptContent_initialPortItem.offset().left, cthis.offset().left, _excerptContent_initialPortItem.outerWidth()/2);


                window.dzszfl_execute_target = _excerptContent;



                //console.info(_excerptContent,_excerptContent.find('.toexecute'));
                _excerptContent.find('.toexecute').each(function(){
                    var _t2 = $(this);
                    if(_t2.hasClass('executed')==false){
                        var aux = (_t2.html());


                        try{


                            var arr = JSON.parse(aux);

                            if(arr.type=='transform_slider_con'){
                                window.dzsas_init(window.dzszfl_execute_target.find(".slider-con .advancedscroller"),arr);
                            }

                            if(arr.type=='item_excerpt_setup'){
                                window.dzsas_init(window.dzszfl_execute_target.find(".advancedscroller"),arr);
                            }

                        }catch(err){
                            console.info("ERROR PARSING",err,aux);
                        }


                        _t2.addClass('executed');
                    }
                });



                if(_excerptContent.find('.advancedscroller').length==0){
                    actually_open_it();
                }else{

                    //console.info(_excerptContent.find('.advancedscroller').eq(0).hasClass('loaded'));

                    var inter_aux = setInterval(function(){

                        //console.info(_excerptContent.find('.advancedscroller').eq(0).hasClass('loaded'));

                        if(_excerptContent){

                            if(_excerptContent && _excerptContent.find('.advancedscroller').eq(0).hasClass('loaded')){
                                actually_open_it();
                                clearInterval(inter_aux);
                            }else{

                                // console.info('advancedscroller not loaded');
                            }
                        }else{
                            // console.info('_excerptContent not found');
                        }
                    },100)
                }


                function actually_open_it(){
                    var args={
                        calculate_dims_init: false
                        ,calculate_excerpt_con: true
                        ,excerpt_con_noanimation: false

                    };

                    // console.error('actually_open_it');
                    //handle_resize(null,args);


                    _excerptContent.css({
                        'height': 0
                    });


                    var delaytime = 100;
                    if(o.excerpt_con_transition=='wipe') {
                        delaytime=100;
                    }

                    handle_resize(null,{
                        calculate_dims_init: true
                        ,calculate_excerpt_con: true
                        ,excerpt_con_noanimation: true

                    });
                    setTimeout(function(){
//                    return;

                        if(_excerptContent.find('.advancedscroller').length>0){

                            //console.info('CALL RESIZE');
                            _excerptContent.find('.advancedscroller').each(function(){
                                var _t = $(this);
                                //console.info(_t);

                                if(_t.get(0) && _t.get(0).api_force_resize){
                                    var args ={

                                    };

                                    if(_t.attr('data-defaultheight')){

                                        args.calculate_auto_height_default_h = Number(_t.attr('data-defaultheight'));
                                    }
                                    _t.get(0).api_force_resize(null, args);
                                }
                            })
                        }

                        //console.info(_excerptContent.children('.dzs-colcontainer').outerHeight());

                        var auxh = _excerptContent.children('.dzs-colcontainer').outerHeight();

                        // console.info('auxh - ',auxh);

                        var delaytime2 = 500;


                        if(_excerptContent.find('.advancedscroller').length>0){
                            //delaytime2 = 1500;
                            auxh-=2;
                        }


                        // console.info('auxh - ',auxh);


                        if(_tcon_content.hasClass('skin-qucreative')){


                            _excerptContent.css({
                                'height': auxh
                            });
                            _excerptContentCon.css({
                                'height': auxh
                            });

                            if(o.excerpt_con_transition=='zoom'){
                                _excerptContentCon.css({
                                    'height': auxh
                                });


                                _excerptContent.css({
                                    'height': auxh
                                });
                            }else{


                                _excerptContentCon.css({
                                    'height': auxh
                                });


                                _excerptContent.css({
                                    'height': auxh
                                });

                                setTimeout(function(){

                                    //_excerptContent.animate({
                                    //    'height': auxh
                                    //},{
                                    //    duration: 500
                                    //    ,queue:false
                                    //});


                                    // console.error("CALL SCROLLL");
                                    var aux = _excerptContent.offset().top-100;
                                    if($('.scroller-con.type-scrollTop').get(0) && $('.scroller-con.type-scrollTop').get(0).api_scrolly_to){

                                        $('.scroller-con.type-scrollTop').get(0).api_scrolly_to(aux);
                                    }else{

                                        $('html, body').animate({
                                            scrollTop: aux
                                        }, 300);
                                    }


                                },700)

                            }
                            excerpt_content_resize_vplayer();



                        }else{

                            _excerptContent.css({
                                'height':  _excerptContent.children('.dzs-colcontainer').outerHeight() + 40 * 2
                            });
                            _excerptContentCon.css({
                                'height':  _excerptContent.children('.dzs-colcontainer').outerHeight() + 40 * 2
                            });
                        }


                        setTimeout(function(){
                            //console.log('placed');

                            _excerptContentCon.addClass('placed');


                            //_excerptContentCon.css({
                            //    'height':  ''
                            //});
                        },delaytime2);



                    },delaytime);

                    setTimeout(function(){

                        var args={
                            calculate_dims_init: true
                            ,calculate_excerpt_con: true

                        };

                        handle_resize(null,args);
                    },1000);
//                console.info(_excerptContent.outerHeight());

                    _excerptContent.prepend('<style>#'+cid+'.dzsportfolio .excerpt-content:before{ left:'+(_excerptContent_initialPortItem.offset().left - cthis.offset().left + _excerptContent_initialPortItem.outerWidth()/2 -8)+'px; }</style>')

                    _excerptContent_initialPortItem.addClass('active');


                    _excerptContent.find('.vplayer-tobe.auto-init-from-q').each(function(){
                        var _t2 = $(this);

                        if(window.dzsvp_init){

                            var args = {
                                settings_youtube_usecustomskin:"off"
                                ,init_each:true
                                ,controls_out_opacity: "1"
                                ,controls_normal_opacity: "1"
                                ,cueVideo: "off"
                            };


                            if(window.qucreative_options.video_player_settings){
                                args= $.extend(args, window.qucreative_options.video_player_settings);
                            }

                            //console.info(window.qucreative_options,args);
                            //console.info(args);

                            window.dzsvp_init(_t2,args)
                        }
                    })
                    //return false;

                    _excerptContent.find('.close-btn').bind('click', contentOpener_close);






                    if (o.settings_mode=='isotope' && $.fn.isotope != undefined) {
                        //isotope_settings.sortBy = 'name';
//                        ===== we let a little time for the items to settle their widths
//                    console.log(_items);
                        //_items.isotope(o.isotope_settings );
                        //console.info('isotope relayout');
                        //_theitems.isotope('layout');

                        delaytime = 300;
                        if(o.excerpt_con_transition=='wipe') {
                            delaytime=101;
                        }
                        //console.info(ind);
                        setTimeout(function(){
                            //_items.isotope(o.settings_isotope_settings).isotope('layout');
                            //_items.isotope('reloadItems').isotope('layout');
                            //_items.layoutItems(_excerptContentCon);
                            // if(ind>0){
                            //     //console.info(ind);
                            //     _excerptContentCon.attr('data-sort', (Number(ind)*10)+1)
                            // }else{
                            //
                            //     _excerptContentCon.attr('data-sort', 10000)
                            // }

                            _excerptContentCon.addClass('isotoped');
                            _items.isotope('insert',_excerptContentCon);



                            //_items.children().eq(ind).before(_excerptContentCon);
                            _items.isotope('layout');
                            //_theitems.isotope('layo ut');

                        }, delaytime);


                    }
//                console.info(_tcon, _tcon_next);

                    delaytime = 700;
                    if(o.excerpt_con_transition=='wipe') {
                        delaytime=100;
                    }

                    setTimeout(function(){
                        _excerptContent.addClass('placed');
                    }, delaytime);

                    setTimeout(function(){

                        // -- scroller resize
                        if($('.main-container').eq(0).get(0) && $('.main-container').eq(0).get(0).api_toggle_resize){
                            $('.main-container').eq(0).get(0).api_toggle_resize();
                        }
                    },300);
                }



                return false;
            }
            function contentOpener_close(){

//                console.info(_excerptContentCon);
                if(_excerptContentCon || _excerptContent){
                    _excerptContent.removeClass('placed');
                    _excerptContent_initialPortItem.removeClass('active');

                    if(o.excerpt_con_transition=='wipe'){

                        setTimeout(function(){

                            _excerptContentCon.css({
                                'height':0
                                ,'margin-bottom': 0
                            });

                            _items.isotope('layout');
                        },100)


                        _excerptContent.animate({
                            'height': 0
                        },{
                            duration: 400
                            ,queue:false
                        });

                    }

                    var delaytime = 700;

                    if(o.excerpt_con_transition=='wipe'){
                        delaytime=400;
                    }

                    setTimeout(function(){

                        if (o.settings_mode=='isotope' && $.fn.isotope != undefined) {

                            _items.isotope('remove',_excerptContentCon);
                        }

                        if(_excerptContentCon){

                            _excerptContentCon.remove();
                            _excerptContentCon = null;
                        }
                        _excerptContent_initialPortItem = null
                        _excerptContent = null;

                        handle_resize();
                    }, delaytime);
                }


                setTimeout(function(){

                    if($('.main-container').eq(0).get(0) && $('.main-container').eq(0).get(0).api_toggle_resize){
                        $('.main-container').eq(0).get(0).api_toggle_resize();
                    }
                },300);
            }




            return this;
        })
    }

    window.dzszfl_init = function(selector, settings) {
        if(typeof(settings)!="undefined" && typeof(settings.init_each)!="undefined" && settings.init_each==true ){
            var element_count = 0;
            for (var e in settings) { element_count++; }
            if(element_count==1){
                settings = undefined;
            }

            $(selector).each(function(){
                var _t = $(this);
                _t.zfolio(settings)
            });
        }else{
            $(selector).zfolio(settings);
        }
    };
})(jQuery);



jQuery(document).ready(function($){

    dzszfl_init('.zfolio.auto-init', {init_each: true});

});






function get_query_arg(purl, key){
    if(purl.indexOf(key+'=')>-1){
        //faconsole.log('testtt');
        var regexS = "[?&]"+key + "=.+";
        var regex = new RegExp(regexS);
        var regtest = regex.exec(purl);


        if(regtest != null){
            var splitterS = regtest[0];
            if(splitterS.indexOf('&')>-1){
                var aux = splitterS.split('&');
                splitterS = aux[1];
            }
            var splitter = splitterS.split('=');

            return splitter[1];

        }
        //$('.zoombox').eq
    }
}


function add_query_arg(purl, key,value){
    key = encodeURIComponent(key); value = encodeURIComponent(value);

    //if(window.console) { console.info(key, value); };

    var s = purl;
    var pair = key+"="+value;

    var r = new RegExp("(&|\\?)"+key+"=[^\&]*");


    //console.info(pair);

    s = s.replace(r,"$1"+pair);
    //console.log(s, pair);
    var addition = '';
    if(s.indexOf(key + '=')>-1){


    }else{
        if(s.indexOf('?')>-1){
            addition = '&'+pair;
        }else{
            addition='?'+pair;
        }
        s+=addition;
    }

    //if value NaN we remove this field from the url
    if(value=='NaN'){
        var regex_attr = new RegExp('[\?|\&]'+key+'='+value);
        s=s.replace(regex_attr, '');
    }


    //if(!RegExp.$1) {s += (s.length>0 ? '&' : '?') + kvp;};

    return s;
}

function is_touch_device() {
    return !!('ontouchstart' in window);
}



window.requestAnimFrame = (function() {
    //console.log(callback);
    return window.requestAnimationFrame ||
        window.webkitRequestAnimationFrame ||
        window.mozRequestAnimationFrame ||
        window.oRequestAnimationFrame ||
        window.msRequestAnimationFrame ||
        function(/* function */callback, /* DOMElement */element) {
            window.setTimeout(callback, 1000 / 60);
        };
})();


jQuery.fn.textWidth = function(){
    var _t = jQuery(this);
    var html_org = _t.html();
    if(_t[0].nodeName=='INPUT'){
        html_org = _t.val();
    }
    var html_calcS = '<span>' + html_org + '</span>';
    jQuery('body').append(html_calcS);
    var _lastspan = jQuery('span').last();
    //console.log(_lastspan, html_calc);
    _lastspan.css({
        'font-size' : _t.css('font-size')
        ,'font-family' : _t.css('font-family')
    })
    var width =_lastspan.width() ;
    //_t.html(html_org);
    _lastspan.remove();
    return width;
};




function can_history_api() {
    return !!(window.history && history.pushState);
}

function is_ios() {
    return ((navigator.platform.indexOf("iPhone") != -1) || (navigator.platform.indexOf("iPod") != -1) || (navigator.platform.indexOf("iPad") != -1)
    );
}

function is_android() {
    //return true;
    var ua = navigator.userAgent.toLowerCase();
    return (ua.indexOf("android") > -1);
}

function is_ie() {
    if (navigator.appVersion.indexOf("MSIE") != -1) {
        return true;
    }
    ;
    return false;
}
;
function is_firefox() {
    if (navigator.userAgent.indexOf("Firefox") != -1) {
        return true;
    }
    ;
    return false;
}
;
function is_opera() {
    if (navigator.userAgent.indexOf("Opera") != -1) {
        return true;
    }
    ;
    return false;
}
;
function is_chrome() {
    return navigator.userAgent.toLowerCase().indexOf('chrome') > -1;
}
;

function is_safari() {
    return Object.prototype.toString.call(window.HTMLElement).indexOf('Constructor') > 0;
}

function version_ie() {
    return parseFloat(navigator.appVersion.split("MSIE")[1]);
}
;
function version_firefox() {
    if (/Firefox[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
        var aversion = new Number(RegExp.$1);
        return(aversion);
    }
    ;
}
;
function version_opera() {
    if (/Opera[\/\s](\d+\.\d+)/.test(navigator.userAgent)) {
        var aversion = new Number(RegExp.$1);
        return(aversion);
    }
    ;
}
;
function is_ie8() {
    if (is_ie() && version_ie() < 9) {
        return true;
    }
    return false;
}
function is_ie9() {
    if (is_ie() && version_ie() == 9) {
        return true;
    }
    return false;
}

























/*!
 * Isotope PACKAGED v3.0.3
 *
 * Licensed GPLv3 for open source use
 * or Isotope Commercial License for commercial use
 *
 * http://isotope.metafizzy.co
 * Copyright 2017 Metafizzy
 */

/**
 * Bridget makes jQuery widgets
 * v2.0.1
 * MIT license
 */

/* jshint browser: true, strict: true, undef: true, unused: true */

( function( window, factory ) {
    // universal module definition
    /*jshint strict: false */ /* globals define, module, require */
    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( 'jquery-bridget/jquery-bridget',[ 'jquery' ], function( jQuery ) {
            return factory( window, jQuery );
        });
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory(
            window,
            require('jquery')
        );
    } else {
        // browser global
        window.jQueryBridget = factory(
            window,
            window.jQuery
        );
    }

}( window, function factory( window, jQuery ) {
    'use strict';

// ----- utils ----- //

    var arraySlice = Array.prototype.slice;

// helper function for logging errors
// $.error breaks jQuery chaining
    var console = window.console;
    var logError = typeof console == 'undefined' ? function() {} :
        function( message ) {
            console.error( message );
        };

// ----- jQueryBridget ----- //

    function jQueryBridget( namespace, PluginClass, $ ) {
        $ = $ || jQuery || window.jQuery;
        if ( !$ ) {
            return;
        }

        // add option method -> $().plugin('option', {...})
        if ( !PluginClass.prototype.option ) {
            // option setter
            PluginClass.prototype.option = function( opts ) {
                // bail out if not an object
                if ( !$.isPlainObject( opts ) ){
                    return;
                }
                this.options = $.extend( true, this.options, opts );
            };
        }

        // make jQuery plugin
        $.fn[ namespace ] = function( arg0 /*, arg1 */ ) {
            if ( typeof arg0 == 'string' ) {
                // method call $().plugin( 'methodName', { options } )
                // shift arguments by 1
                var args = arraySlice.call( arguments, 1 );
                return methodCall( this, arg0, args );
            }
            // just $().plugin({ options })
            plainCall( this, arg0 );
            return this;
        };

        // $().plugin('methodName')
        function methodCall( $elems, methodName, args ) {
            var returnValue;
            var pluginMethodStr = '$().' + namespace + '("' + methodName + '")';

            $elems.each( function( i, elem ) {
                // get instance
                var instance = $.data( elem, namespace );
                if ( !instance ) {
                    logError( namespace + ' not initialized. Cannot call methods, i.e. ' +
                        pluginMethodStr );
                    return;
                }

                var method = instance[ methodName ];
                if ( !method || methodName.charAt(0) == '_' ) {
                    logError( pluginMethodStr + ' is not a valid method' );
                    return;
                }

                // apply method, get return value
                var value = method.apply( instance, args );
                // set return value if value is returned, use only first value
                returnValue = returnValue === undefined ? value : returnValue;
            });

            return returnValue !== undefined ? returnValue : $elems;
        }

        function plainCall( $elems, options ) {
            $elems.each( function( i, elem ) {
                var instance = $.data( elem, namespace );
                if ( instance ) {
                    // set options & init
                    instance.option( options );
                    instance._init();
                } else {
                    // initialize new instance
                    instance = new PluginClass( elem, options );
                    $.data( elem, namespace, instance );
                }
            });
        }

        updateJQuery( $ );

    }

// ----- updateJQuery ----- //

// set $.bridget for v1 backwards compatibility
    function updateJQuery( $ ) {
        if ( !$ || ( $ && $.bridget ) ) {
            return;
        }
        $.bridget = jQueryBridget;
    }

    updateJQuery( jQuery || window.jQuery );

// -----  ----- //

    return jQueryBridget;

}));

/**
 * EvEmitter v1.0.3
 * Lil' event emitter
 * MIT License
 */

/* jshint unused: true, undef: true, strict: true */

( function( global, factory ) {
    // universal module definition
    /* jshint strict: false */ /* globals define, module, window */
    if ( typeof define == 'function' && define.amd ) {
        // AMD - RequireJS
        define( 'ev-emitter/ev-emitter',factory );
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS - Browserify, Webpack
        module.exports = factory();
    } else {
        // Browser globals
        global.EvEmitter = factory();
    }

}( typeof window != 'undefined' ? window : this, function() {



    function EvEmitter() {}

    var proto = EvEmitter.prototype;

    proto.on = function( eventName, listener ) {
        if ( !eventName || !listener ) {
            return;
        }
        // set events hash
        var events = this._events = this._events || {};
        // set listeners array
        var listeners = events[ eventName ] = events[ eventName ] || [];
        // only add once
        if ( listeners.indexOf( listener ) == -1 ) {
            listeners.push( listener );
        }

        return this;
    };

    proto.once = function( eventName, listener ) {
        if ( !eventName || !listener ) {
            return;
        }
        // add event
        this.on( eventName, listener );
        // set once flag
        // set onceEvents hash
        var onceEvents = this._onceEvents = this._onceEvents || {};
        // set onceListeners object
        var onceListeners = onceEvents[ eventName ] = onceEvents[ eventName ] || {};
        // set flag
        onceListeners[ listener ] = true;

        return this;
    };

    proto.off = function( eventName, listener ) {
        var listeners = this._events && this._events[ eventName ];
        if ( !listeners || !listeners.length ) {
            return;
        }
        var index = listeners.indexOf( listener );
        if ( index != -1 ) {
            listeners.splice( index, 1 );
        }

        return this;
    };

    proto.emitEvent = function( eventName, args ) {
        var listeners = this._events && this._events[ eventName ];
        if ( !listeners || !listeners.length ) {
            return;
        }
        var i = 0;
        var listener = listeners[i];
        args = args || [];
        // once stuff
        var onceListeners = this._onceEvents && this._onceEvents[ eventName ];

        while ( listener ) {
            var isOnce = onceListeners && onceListeners[ listener ];
            if ( isOnce ) {
                // remove listener
                // remove before trigger to prevent recursion
                this.off( eventName, listener );
                // unset once flag
                delete onceListeners[ listener ];
            }
            // trigger listener
            listener.apply( this, args );
            // get next listener
            i += isOnce ? 0 : 1;
            listener = listeners[i];
        }

        return this;
    };

    return EvEmitter;

}));

/*!
 * getSize v2.0.2
 * measure size of elements
 * MIT license
 */

/*jshint browser: true, strict: true, undef: true, unused: true */
/*global define: false, module: false, console: false */

( function( window, factory ) {
    'use strict';

    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( 'get-size/get-size',[],function() {
            return factory();
        });
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory();
    } else {
        // browser global
        window.getSize = factory();
    }

})( window, function factory() {
    'use strict';

// -------------------------- helpers -------------------------- //

// get a number from a string, not a percentage
    function getStyleSize( value ) {
        var num = parseFloat( value );
        // not a percent like '100%', and a number
        var isValid = value.indexOf('%') == -1 && !isNaN( num );
        return isValid && num;
    }

    function noop() {}

    var logError = typeof console == 'undefined' ? noop :
        function( message ) {
            console.error( message );
        };

// -------------------------- measurements -------------------------- //

    var measurements = [
        'paddingLeft',
        'paddingRight',
        'paddingTop',
        'paddingBottom',
        'marginLeft',
        'marginRight',
        'marginTop',
        'marginBottom',
        'borderLeftWidth',
        'borderRightWidth',
        'borderTopWidth',
        'borderBottomWidth'
    ];

    var measurementsLength = measurements.length;

    function getZeroSize() {
        var size = {
            width: 0,
            height: 0,
            innerWidth: 0,
            innerHeight: 0,
            outerWidth: 0,
            outerHeight: 0
        };
        for ( var i=0; i < measurementsLength; i++ ) {
            var measurement = measurements[i];
            size[ measurement ] = 0;
        }
        return size;
    }

// -------------------------- getStyle -------------------------- //

    /**
     * getStyle, get style of element, check for Firefox bug
     * https://bugzilla.mozilla.org/show_bug.cgi?id=548397
     */
    function getStyle( elem ) {
        var style = getComputedStyle( elem );
        if ( !style ) {
            logError( 'Style returned ' + style +
                '. Are you running this code in a hidden iframe on Firefox? ' +
                'See http://bit.ly/getsizebug1' );
        }
        return style;
    }

// -------------------------- setup -------------------------- //

    var isSetup = false;

    var isBoxSizeOuter;

    /**
     * setup
     * check isBoxSizerOuter
     * do on first getSize() rather than on page load for Firefox bug
     */
    function setup() {
        // setup once
        if ( isSetup ) {
            return;
        }
        isSetup = true;

        // -------------------------- box sizing -------------------------- //

        /**
         * WebKit measures the outer-width on style.width on border-box elems
         * IE & Firefox<29 measures the inner-width
         */
        var div = document.createElement('div');
        div.style.width = '200px';
        div.style.padding = '1px 2px 3px 4px';
        div.style.borderStyle = 'solid';
        div.style.borderWidth = '1px 2px 3px 4px';
        div.style.boxSizing = 'border-box';

        var body = document.body || document.documentElement;
        body.appendChild( div );
        var style = getStyle( div );

        getSize.isBoxSizeOuter = isBoxSizeOuter = getStyleSize( style.width ) == 200;
        body.removeChild( div );

    }

// -------------------------- getSize -------------------------- //

    function getSize( elem ) {
        setup();

        // use querySeletor if elem is string
        if ( typeof elem == 'string' ) {
            elem = document.querySelector( elem );
        }

        // do not proceed on non-objects
        if ( !elem || typeof elem != 'object' || !elem.nodeType ) {
            return;
        }

        var style = getStyle( elem );

        // if hidden, everything is 0
        if ( style.display == 'none' ) {
            return getZeroSize();
        }

        var size = {};
        size.width = elem.offsetWidth;
        size.height = elem.offsetHeight;

        var isBorderBox = size.isBorderBox = style.boxSizing == 'border-box';

        // get all measurements
        for ( var i=0; i < measurementsLength; i++ ) {
            var measurement = measurements[i];
            var value = style[ measurement ];
            var num = parseFloat( value );
            // any 'auto', 'medium' value will be 0
            size[ measurement ] = !isNaN( num ) ? num : 0;
        }

        var paddingWidth = size.paddingLeft + size.paddingRight;
        var paddingHeight = size.paddingTop + size.paddingBottom;
        var marginWidth = size.marginLeft + size.marginRight;
        var marginHeight = size.marginTop + size.marginBottom;
        var borderWidth = size.borderLeftWidth + size.borderRightWidth;
        var borderHeight = size.borderTopWidth + size.borderBottomWidth;

        var isBorderBoxSizeOuter = isBorderBox && isBoxSizeOuter;

        // overwrite width and height if we can get it from style
        var styleWidth = getStyleSize( style.width );
        if ( styleWidth !== false ) {
            size.width = styleWidth +
                // add padding and border unless it's already including it
                ( isBorderBoxSizeOuter ? 0 : paddingWidth + borderWidth );
        }

        var styleHeight = getStyleSize( style.height );
        if ( styleHeight !== false ) {
            size.height = styleHeight +
                // add padding and border unless it's already including it
                ( isBorderBoxSizeOuter ? 0 : paddingHeight + borderHeight );
        }

        size.innerWidth = size.width - ( paddingWidth + borderWidth );
        size.innerHeight = size.height - ( paddingHeight + borderHeight );

        size.outerWidth = size.width + marginWidth;
        size.outerHeight = size.height + marginHeight;

        return size;
    }

    return getSize;

});

/**
 * matchesSelector v2.0.2
 * matchesSelector( element, '.selector' )
 * MIT license
 */

/*jshint browser: true, strict: true, undef: true, unused: true */

( function( window, factory ) {
    /*global define: false, module: false */
    'use strict';
    // universal module definition
    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( 'desandro-matches-selector/matches-selector',factory );
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory();
    } else {
        // browser global
        window.matchesSelector = factory();
    }

}( window, function factory() {
    'use strict';

    var matchesMethod = ( function() {
        var ElemProto = window.Element.prototype;
        // check for the standard method name first
        if ( ElemProto.matches ) {
            return 'matches';
        }
        // check un-prefixed
        if ( ElemProto.matchesSelector ) {
            return 'matchesSelector';
        }
        // check vendor prefixes
        var prefixes = [ 'webkit', 'moz', 'ms', 'o' ];

        for ( var i=0; i < prefixes.length; i++ ) {
            var prefix = prefixes[i];
            var method = prefix + 'MatchesSelector';
            if ( ElemProto[ method ] ) {
                return method;
            }
        }
    })();

    return function matchesSelector( elem, selector ) {
        return elem[ matchesMethod ]( selector );
    };

}));

/**
 * Fizzy UI utils v2.0.4
 * MIT license
 */

/*jshint browser: true, undef: true, unused: true, strict: true */

( function( window, factory ) {
    // universal module definition
    /*jshint strict: false */ /*globals define, module, require */

    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( 'fizzy-ui-utils/utils',[
            'desandro-matches-selector/matches-selector'
        ], function( matchesSelector ) {
            return factory( window, matchesSelector );
        });
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory(
            window,
            require('desandro-matches-selector')
        );
    } else {
        // browser global
        window.fizzyUIUtils = factory(
            window,
            window.matchesSelector
        );
    }

}( window, function factory( window, matchesSelector ) {



    var utils = {};

// ----- extend ----- //

// extends objects
    utils.extend = function( a, b ) {
        for ( var prop in b ) {
            a[ prop ] = b[ prop ];
        }
        return a;
    };

// ----- modulo ----- //

    utils.modulo = function( num, div ) {
        return ( ( num % div ) + div ) % div;
    };

// ----- makeArray ----- //

// turn element or nodeList into an array
    utils.makeArray = function( obj ) {
        var ary = [];
        if ( Array.isArray( obj ) ) {
            // use object if already an array
            ary = obj;
        } else if ( obj && typeof obj == 'object' &&
            typeof obj.length == 'number' ) {
            // convert nodeList to array
            for ( var i=0; i < obj.length; i++ ) {
                ary.push( obj[i] );
            }
        } else {
            // array of single index
            ary.push( obj );
        }
        return ary;
    };

// ----- removeFrom ----- //

    utils.removeFrom = function( ary, obj ) {
        var index = ary.indexOf( obj );
        if ( index != -1 ) {
            ary.splice( index, 1 );
        }
    };

// ----- getParent ----- //

    utils.getParent = function( elem, selector ) {
        while ( elem != document.body ) {
            elem = elem.parentNode;
            if ( matchesSelector( elem, selector ) ) {
                return elem;
            }
        }
    };

// ----- getQueryElement ----- //

// use element as selector string
    utils.getQueryElement = function( elem ) {
        if ( typeof elem == 'string' ) {
            return document.querySelector( elem );
        }
        return elem;
    };

// ----- handleEvent ----- //

// enable .ontype to trigger from .addEventListener( elem, 'type' )
    utils.handleEvent = function( event ) {
        var method = 'on' + event.type;
        if ( this[ method ] ) {
            this[ method ]( event );
        }
    };

// ----- filterFindElements ----- //

    utils.filterFindElements = function( elems, selector ) {
        // make array of elems
        elems = utils.makeArray( elems );
        var ffElems = [];

        elems.forEach( function( elem ) {
            // check that elem is an actual element
            if ( !( elem instanceof HTMLElement ) ) {
                return;
            }
            // add elem if no selector
            if ( !selector ) {
                ffElems.push( elem );
                return;
            }
            // filter & find items if we have a selector
            // filter
            if ( matchesSelector( elem, selector ) ) {
                ffElems.push( elem );
            }
            // find children
            var childElems = elem.querySelectorAll( selector );
            // concat childElems to filterFound array
            for ( var i=0; i < childElems.length; i++ ) {
                ffElems.push( childElems[i] );
            }
        });

        return ffElems;
    };

// ----- debounceMethod ----- //

    utils.debounceMethod = function( _class, methodName, threshold ) {
        // original method
        var method = _class.prototype[ methodName ];
        var timeoutName = methodName + 'Timeout';

        _class.prototype[ methodName ] = function() {
            var timeout = this[ timeoutName ];
            if ( timeout ) {
                clearTimeout( timeout );
            }
            var args = arguments;

            var _this = this;
            this[ timeoutName ] = setTimeout( function() {
                method.apply( _this, args );
                delete _this[ timeoutName ];
            }, threshold || 100 );
        };
    };

// ----- docReady ----- //

    utils.docReady = function( callback ) {
        var readyState = document.readyState;
        if ( readyState == 'complete' || readyState == 'interactive' ) {
            // do async to allow for other scripts to run. metafizzy/flickity#441
            setTimeout( callback );
        } else {
            document.addEventListener( 'DOMContentLoaded', callback );
        }
    };

// ----- htmlInit ----- //

// http://jamesroberts.name/blog/2010/02/22/string-functions-for-javascript-trim-to-camel-case-to-dashed-and-to-underscore/
    utils.toDashed = function( str ) {
        return str.replace( /(.)([A-Z])/g, function( match, $1, $2 ) {
            return $1 + '-' + $2;
        }).toLowerCase();
    };

    var console = window.console;
    /**
     * allow user to initialize classes via [data-namespace] or .js-namespace class
     * htmlInit( Widget, 'widgetName' )
     * options are parsed from data-namespace-options
     */
    utils.htmlInit = function( WidgetClass, namespace ) {
        utils.docReady( function() {
            var dashedNamespace = utils.toDashed( namespace );
            var dataAttr = 'data-' + dashedNamespace;
            var dataAttrElems = document.querySelectorAll( '[' + dataAttr + ']' );
            var jsDashElems = document.querySelectorAll( '.js-' + dashedNamespace );
            var elems = utils.makeArray( dataAttrElems )
                .concat( utils.makeArray( jsDashElems ) );
            var dataOptionsAttr = dataAttr + '-options';
            var jQuery = window.jQuery;

            elems.forEach( function( elem ) {
                var attr = elem.getAttribute( dataAttr ) ||
                    elem.getAttribute( dataOptionsAttr );
                var options;
                try {
                    options = attr && JSON.parse( attr );
                } catch ( error ) {
                    // log error, do not initialize
                    if ( console ) {
                        console.error( 'Error parsing ' + dataAttr + ' on ' + elem.className +
                            ': ' + error );
                    }
                    return;
                }
                // initialize
                var instance = new WidgetClass( elem, options );
                // make available via $().data('namespace')
                if ( jQuery ) {
                    jQuery.data( elem, namespace, instance );
                }
            });

        });
    };

// -----  ----- //

    return utils;

}));

/**
 * Outlayer Item
 */

( function( window, factory ) {
    // universal module definition
    /* jshint strict: false */ /* globals define, module, require */
    if ( typeof define == 'function' && define.amd ) {
        // AMD - RequireJS
        define( 'outlayer/item',[
                'ev-emitter/ev-emitter',
                'get-size/get-size'
            ],
            factory
        );
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS - Browserify, Webpack
        module.exports = factory(
            require('ev-emitter'),
            require('get-size')
        );
    } else {
        // browser global
        window.Outlayer = {};
        window.Outlayer.Item = factory(
            window.EvEmitter,
            window.getSize
        );
    }

}( window, function factory( EvEmitter, getSize ) {
    'use strict';

// ----- helpers ----- //

    function isEmptyObj( obj ) {
        for ( var prop in obj ) {
            return false;
        }
        prop = null;
        return true;
    }

// -------------------------- CSS3 support -------------------------- //


    var docElemStyle = document.documentElement.style;

    var transitionProperty = typeof docElemStyle.transition == 'string' ?
        'transition' : 'WebkitTransition';
    var transformProperty = typeof docElemStyle.transform == 'string' ?
        'transform' : 'WebkitTransform';

    var transitionEndEvent = {
        WebkitTransition: 'webkitTransitionEnd',
        transition: 'transitionend'
    }[ transitionProperty ];

// cache all vendor properties that could have vendor prefix
    var vendorProperties = {
        transform: transformProperty,
        transition: transitionProperty,
        transitionDuration: transitionProperty + 'Duration',
        transitionProperty: transitionProperty + 'Property',
        transitionDelay: transitionProperty + 'Delay'
    };

// -------------------------- Item -------------------------- //

    function Item( element, layout ) {
        if ( !element ) {
            return;
        }

        this.element = element;
        // parent layout class, i.e. Masonry, Isotope, or Packery
        this.layout = layout;
        this.position = {
            x: 0,
            y: 0
        };

        this._create();
    }

// inherit EvEmitter
    var proto = Item.prototype = Object.create( EvEmitter.prototype );
    proto.constructor = Item;

    proto._create = function() {
        // transition objects
        this._transn = {
            ingProperties: {},
            clean: {},
            onEnd: {}
        };

        this.css({
            position: 'absolute'
        });
    };

// trigger specified handler for event type
    proto.handleEvent = function( event ) {
        var method = 'on' + event.type;
        if ( this[ method ] ) {
            this[ method ]( event );
        }
    };

    proto.getSize = function() {
        this.size = getSize( this.element );
    };

    /**
     * apply CSS styles to element
     * @param {Object} style
     */
    proto.css = function( style ) {
        var elemStyle = this.element.style;

        for ( var prop in style ) {
            // use vendor property if available
            var supportedProp = vendorProperties[ prop ] || prop;
            elemStyle[ supportedProp ] = style[ prop ];
        }
    };

    // measure position, and sets it
    proto.getPosition = function() {
        var style = getComputedStyle( this.element );
        var isOriginLeft = this.layout._getOption('originLeft');
        var isOriginTop = this.layout._getOption('originTop');
        var xValue = style[ isOriginLeft ? 'left' : 'right' ];
        var yValue = style[ isOriginTop ? 'top' : 'bottom' ];
        // convert percent to pixels
        var layoutSize = this.layout.size;
        var x = xValue.indexOf('%') != -1 ?
            ( parseFloat( xValue ) / 100 ) * layoutSize.width : parseInt( xValue, 10 );
        var y = yValue.indexOf('%') != -1 ?
            ( parseFloat( yValue ) / 100 ) * layoutSize.height : parseFloat( yValue );

        // clean up 'auto' or other non-integer values
        x = isNaN( x ) ? 0 : x;
        y = isNaN( y ) ? 0 : y;
        // remove padding from measurement
        x -= isOriginLeft ? layoutSize.paddingLeft : layoutSize.paddingRight;
        y -= isOriginTop ? layoutSize.paddingTop : layoutSize.paddingBottom;

        this.position.x = x;
        this.position.y = y;
    };

// set settled position, apply padding
    proto.layoutPosition = function() {
        var layoutSize = this.layout.size;
        var style = {};
        var isOriginLeft = this.layout._getOption('originLeft');
        var isOriginTop = this.layout._getOption('originTop');

        // x
        var xPadding = isOriginLeft ? 'paddingLeft' : 'paddingRight';
        var xProperty = isOriginLeft ? 'left' : 'right';
        var xResetProperty = isOriginLeft ? 'right' : 'left';

        var x = this.position.x + layoutSize[ xPadding ];
        // set in percentage or pixels


        var xvalue = this.getXValue( x );



        if(xvalue.indexOf('%')>-1){
            xvalue = parseFloat(this.getXValue( x ));

            // console.info('xvalue - ',xvalue%1);

            // console.info('percent_amount - ',this.layout.options.percent_amount);



            // console.info()

            // -- isotope



            var perc_amount = 0;

            if(this.layout.options.percent_amount){
                perc_amount = this.layout.options.percent_amount;
            }


            var _c = jQuery(this.layout.element).parent();

            if(_c.hasClass('zfolio')){
                if(_c.attr('data-nr-cols')=='5'){
                    perc_amount = 20;
                }
                if(_c.attr('data-nr-cols')=='45'){
                    perc_amount = 25;
                }
                if(_c.attr('data-nr-cols')=='3'){
                    perc_amount = 33.333;
                }
                if(_c.attr('data-nr-cols')=='2'){
                    perc_amount = 50;
                }
                if(_c.attr('data-nr-cols')=='1'){
                    perc_amount = 100;
                }
            }


            // console.warn('_c->',_c, this);
            // console.warn('perc_amount->',perc_amount);

            if(perc_amount){

                if(xvalue%perc_amount>0.1){
                    xvalue = Math.ceil(xvalue/perc_amount) * perc_amount;
                }


            }else{

                if(xvalue%1>0.85){
                    xvalue = Math.ceil(xvalue);
                }
            }
            xvalue = String(xvalue)+'%';

            // console.info(this);
        }

        // console.info('this.getXValue( x ) - ',xvalue);

        style[ xProperty ] = xvalue;
        // reset other property
        style[ xResetProperty ] = '';

        // y
        var yPadding = isOriginTop ? 'paddingTop' : 'paddingBottom';
        var yProperty = isOriginTop ? 'top' : 'bottom';
        var yResetProperty = isOriginTop ? 'bottom' : 'top';

        var y = this.position.y + layoutSize[ yPadding ];
        // set in percentage or pixels
        style[ yProperty ] = this.getYValue( y );

        // console.info('this.position.y ',this.position.y);
        // console.info('layoutSize[ yPadding ] ',layoutSize[ yPadding ]);
        // console.info('this.getYValue( y ) -> ',this.getYValue( y ));

        // reset other property
        style[ yResetProperty ] = '';

        this.css( style );
        this.emitEvent( 'layout', [ this ] );
    };

    proto.getXValue = function( x ) {
        var isHorizontal = this.layout._getOption('horizontal');
        return this.layout.options.percentPosition && !isHorizontal ?
            ( ( x / this.layout.size.width ) * 100 ) + '%' : x + 'px';
    };

    proto.getYValue = function( y ) {
        var isHorizontal = this.layout._getOption('horizontal');
        return this.layout.options.percentPosition && isHorizontal ?
            ( ( y / this.layout.size.height ) * 100 ) + '%' : y + 'px';
    };

    proto._transitionTo = function( x, y ) {
        this.getPosition();
        // get current x & y from top/left
        var curX = this.position.x;
        var curY = this.position.y;

        var compareX = parseInt( x, 10 );
        var compareY = parseInt( y, 10 );
        var didNotMove = compareX === this.position.x && compareY === this.position.y;

        // save end position
        this.setPosition( x, y );

        // if did not move and not transitioning, just go to layout
        if ( didNotMove && !this.isTransitioning ) {
            this.layoutPosition();
            return;
        }

        var transX = x - curX;
        var transY = y - curY;
        var transitionStyle = {};
        transitionStyle.transform = this.getTranslate( transX, transY );

        this.transition({
            to: transitionStyle,
            onTransitionEnd: {
                transform: this.layoutPosition
            },
            isCleaning: true
        });
    };

    proto.getTranslate = function( x, y ) {
        // flip cooridinates if origin on right or bottom
        var isOriginLeft = this.layout._getOption('originLeft');
        var isOriginTop = this.layout._getOption('originTop');
        x = isOriginLeft ? x : -x;
        y = isOriginTop ? y : -y;
        x = Math.round(x);
        y = Math.round(y)
        if(Math.abs(x)<=1){
            x=0;
            // str_x=-1;
        }
        if(Math.abs(y)==1){
            y=0;
        }
        // x-=1;
        var str_x = x+'px';
        return 'translate3d(' + (x) + 'px, ' + y + 'px, 0)';

        if(str_x!='0px' && y!=0){

            // return 'translate(' + (str_x) + ', ' + y + 'px)';
        }

        return 'translate(' + (str_x) + ', ' + y + 'px)';
    };

// non transition + transform support
    proto.goTo = function( x, y ) {
        this.setPosition( x, y );
        this.layoutPosition();
    };

    proto.moveTo = proto._transitionTo;

    proto.setPosition = function( x, y ) {
        this.position.x = parseInt( x, 10 );
        this.position.y = parseFloat( y );
    };

// ----- transition ----- //

    /**
     * @param {Object} style - CSS
     * @param {Function} onTransitionEnd
     */

// non transition, just trigger callback
    proto._nonTransition = function( args ) {
        this.css( args.to );
        if ( args.isCleaning ) {
            this._removeStyles( args.to );
        }
        for ( var prop in args.onTransitionEnd ) {
            args.onTransitionEnd[ prop ].call( this );
        }
    };

    /**
     * proper transition
     * @param {Object} args - arguments
     *   @param {Object} to - style to transition to
     *   @param {Object} from - style to start transition from
     *   @param {Boolean} isCleaning - removes transition styles after transition
     *   @param {Function} onTransitionEnd - callback
     */
    proto.transition = function( args ) {
        // redirect to nonTransition if no transition duration
        if ( !parseFloat( this.layout.options.transitionDuration ) ) {
            this._nonTransition( args );
            return;
        }

        var _transition = this._transn;
        // keep track of onTransitionEnd callback by css property
        for ( var prop in args.onTransitionEnd ) {
            _transition.onEnd[ prop ] = args.onTransitionEnd[ prop ];
        }
        // keep track of properties that are transitioning
        for ( prop in args.to ) {
            _transition.ingProperties[ prop ] = true;
            // keep track of properties to clean up when transition is done
            if ( args.isCleaning ) {
                _transition.clean[ prop ] = true;
            }
        }

        // set from styles
        if ( args.from ) {
            this.css( args.from );
            // force redraw. http://blog.alexmaccaw.com/css-transitions
            var h = this.element.offsetHeight;
            // hack for JSHint to hush about unused var
            h = null;
        }
        // enable transition
        this.enableTransition( args.to );
        // set styles that are transitioning
        this.css( args.to );

        this.isTransitioning = true;

    };

// dash before all cap letters, including first for
// WebkitTransform => -webkit-transform
    function toDashedAll( str ) {
        return str.replace( /([A-Z])/g, function( $1 ) {
            return '-' + $1.toLowerCase();
        });
    }

    var transitionProps = 'opacity,' + toDashedAll( transformProperty );

    proto.enableTransition = function(/* style */) {
        // HACK changing transitionProperty during a transition
        // will cause transition to jump
        if ( this.isTransitioning ) {
            return;
        }

        // make `transition: foo, bar, baz` from style object
        // HACK un-comment this when enableTransition can work
        // while a transition is happening
        // var transitionValues = [];
        // for ( var prop in style ) {
        //   // dash-ify camelCased properties like WebkitTransition
        //   prop = vendorProperties[ prop ] || prop;
        //   transitionValues.push( toDashedAll( prop ) );
        // }
        // munge number to millisecond, to match stagger
        var duration = this.layout.options.transitionDuration;
        duration = typeof duration == 'number' ? duration + 'ms' : duration;
        // enable transition styles
        this.css({
            transitionProperty: transitionProps,
            transitionDuration: duration,
            transitionDelay: this.staggerDelay || 0
        });
        // listen for transition end event
        this.element.addEventListener( transitionEndEvent, this, false );
    };

// ----- events ----- //

    proto.onwebkitTransitionEnd = function( event ) {
        this.ontransitionend( event );
    };

    proto.onotransitionend = function( event ) {
        this.ontransitionend( event );
    };

// properties that I munge to make my life easier
    var dashedVendorProperties = {
        '-webkit-transform': 'transform'
    };

    proto.ontransitionend = function( event ) {
        // disregard bubbled events from children
        if ( event.target !== this.element ) {
            return;
        }
        var _transition = this._transn;
        // get property name of transitioned property, convert to prefix-free
        var propertyName = dashedVendorProperties[ event.propertyName ] || event.propertyName;

        // remove property that has completed transitioning
        delete _transition.ingProperties[ propertyName ];
        // check if any properties are still transitioning
        if ( isEmptyObj( _transition.ingProperties ) ) {
            // all properties have completed transitioning
            this.disableTransition();
        }
        // clean style
        if ( propertyName in _transition.clean ) {
            // clean up style
            this.element.style[ event.propertyName ] = '';
            delete _transition.clean[ propertyName ];
        }
        // trigger onTransitionEnd callback
        if ( propertyName in _transition.onEnd ) {
            var onTransitionEnd = _transition.onEnd[ propertyName ];
            onTransitionEnd.call( this );
            delete _transition.onEnd[ propertyName ];
        }

        this.emitEvent( 'transitionEnd', [ this ] );
    };

    proto.disableTransition = function() {
        this.removeTransitionStyles();
        this.element.removeEventListener( transitionEndEvent, this, false );
        this.isTransitioning = false;
    };

    /**
     * removes style property from element
     * @param {Object} style
     **/
    proto._removeStyles = function( style ) {
        // clean up transition styles
        var cleanStyle = {};
        for ( var prop in style ) {
            cleanStyle[ prop ] = '';
        }
        this.css( cleanStyle );
    };

    var cleanTransitionStyle = {
        transitionProperty: '',
        transitionDuration: '',
        transitionDelay: ''
    };

    proto.removeTransitionStyles = function() {
        // remove transition
        this.css( cleanTransitionStyle );
    };

// ----- stagger ----- //

    proto.stagger = function( delay ) {
        delay = isNaN( delay ) ? 0 : delay;
        this.staggerDelay = delay + 'ms';
    };

// ----- show/hide/remove ----- //

// remove element from DOM
    proto.removeElem = function() {
        this.element.parentNode.removeChild( this.element );
        // remove display: none
        this.css({ display: '' });
        this.emitEvent( 'remove', [ this ] );
    };

    proto.remove = function() {
        // just remove element if no transition support or no transition
        if ( !transitionProperty || !parseFloat( this.layout.options.transitionDuration ) ) {
            this.removeElem();
            return;
        }

        // start transition
        this.once( 'transitionEnd', function() {
            this.removeElem();
        });
        this.hide();
    };

    proto.reveal = function() {
        delete this.isHidden;
        // remove display: none
        this.css({ display: '' });

        var options = this.layout.options;

        var onTransitionEnd = {};
        var transitionEndProperty = this.getHideRevealTransitionEndProperty('visibleStyle');
        onTransitionEnd[ transitionEndProperty ] = this.onRevealTransitionEnd;

        this.transition({
            from: options.hiddenStyle,
            to: options.visibleStyle,
            isCleaning: true,
            onTransitionEnd: onTransitionEnd
        });
    };

    proto.onRevealTransitionEnd = function() {
        // check if still visible
        // during transition, item may have been hidden
        if ( !this.isHidden ) {
            this.emitEvent('reveal');
        }
    };

    /**
     * get style property use for hide/reveal transition end
     * @param {String} styleProperty - hiddenStyle/visibleStyle
     * @returns {String}
     */
    proto.getHideRevealTransitionEndProperty = function( styleProperty ) {
        var optionStyle = this.layout.options[ styleProperty ];
        // use opacity
        if ( optionStyle.opacity ) {
            return 'opacity';
        }
        // get first property
        for ( var prop in optionStyle ) {
            return prop;
        }
    };

    proto.hide = function() {
        // set flag
        this.isHidden = true;
        // remove display: none
        this.css({ display: '' });

        var options = this.layout.options;

        var onTransitionEnd = {};
        var transitionEndProperty = this.getHideRevealTransitionEndProperty('hiddenStyle');
        onTransitionEnd[ transitionEndProperty ] = this.onHideTransitionEnd;

        this.transition({
            from: options.visibleStyle,
            to: options.hiddenStyle,
            // keep hidden stuff hidden
            isCleaning: true,
            onTransitionEnd: onTransitionEnd
        });
    };

    proto.onHideTransitionEnd = function() {
        // check if still hidden
        // during transition, item may have been un-hidden
        if ( this.isHidden ) {
            this.css({ display: 'none' });
            this.emitEvent('hide');
        }
    };

    proto.destroy = function() {
        // this.css({
        //     position: '',
        //     left: '',
        //     right: '',
        //     top: '',
        //     bottom: '',
        //     transition: '',
        //     transform: ''
        // });
    };

    return Item;

}));

/*!
 * Outlayer v2.1.0
 * the brains and guts of a layout library
 * MIT license
 */

( function( window, factory ) {
    'use strict';
    // universal module definition
    /* jshint strict: false */ /* globals define, module, require */
    if ( typeof define == 'function' && define.amd ) {
        // AMD - RequireJS
        define( 'outlayer/outlayer',[
                'ev-emitter/ev-emitter',
                'get-size/get-size',
                'fizzy-ui-utils/utils',
                './item'
            ],
            function( EvEmitter, getSize, utils, Item ) {
                return factory( window, EvEmitter, getSize, utils, Item);
            }
        );
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS - Browserify, Webpack
        module.exports = factory(
            window,
            require('ev-emitter'),
            require('get-size'),
            require('fizzy-ui-utils'),
            require('./item')
        );
    } else {
        // browser global
        window.Outlayer = factory(
            window,
            window.EvEmitter,
            window.getSize,
            window.fizzyUIUtils,
            window.Outlayer.Item
        );
    }

}( window, function factory( window, EvEmitter, getSize, utils, Item ) {
    'use strict';

// ----- vars ----- //

    var console = window.console;
    var jQuery = window.jQuery;
    var noop = function() {};

// -------------------------- Outlayer -------------------------- //

// globally unique identifiers
    var GUID = 0;
// internal store of all Outlayer intances
    var instances = {};


    /**
     * @param {Element, String} element
     * @param {Object} options
     * @constructor
     */
    function Outlayer( element, options ) {
        var queryElement = utils.getQueryElement( element );
        if ( !queryElement ) {
            if ( console ) {
                console.error( 'Bad element for ' + this.constructor.namespace +
                    ': ' + ( queryElement || element ) );
            }
            return;
        }
        this.element = queryElement;
        // add jQuery
        if ( jQuery ) {
            this.$element = jQuery( this.element );
        }

        // options
        this.options = utils.extend( {}, this.constructor.defaults );
        this.option( options );

        // add id for Outlayer.getFromElement
        var id = ++GUID;
        this.element.outlayerGUID = id; // expando
        instances[ id ] = this; // associate via id

        // kick it off
        this._create();

        var isInitLayout = this._getOption('initLayout');
        if ( isInitLayout ) {
            this.layout();
        }
    }

// settings are for internal use only
    Outlayer.namespace = 'outlayer';
    Outlayer.Item = Item;

// default options
    Outlayer.defaults = {
        containerStyle: {
            position: 'relative'
        },
        initLayout: true,
        originLeft: true,
        originTop: true,
        resize: true,
        resizeContainer: true,
        // item options
        transitionDuration: '0.4s',
        hiddenStyle: {
            opacity: 0,
            transform: 'scale(0.001)'
        },
        visibleStyle: {
            opacity: 1,
            transform: 'scale(1)'
        }
    };

    var proto = Outlayer.prototype;
// inherit EvEmitter
    utils.extend( proto, EvEmitter.prototype );

    /**
     * set options
     * @param {Object} opts
     */
    proto.option = function( opts ) {
        utils.extend( this.options, opts );
    };

    /**
     * get backwards compatible option value, check old name
     */
    proto._getOption = function( option ) {
        var oldOption = this.constructor.compatOptions[ option ];
        return oldOption && this.options[ oldOption ] !== undefined ?
            this.options[ oldOption ] : this.options[ option ];
    };

    Outlayer.compatOptions = {
        // currentName: oldName
        initLayout: 'isInitLayout',
        horizontal: 'isHorizontal',
        layoutInstant: 'isLayoutInstant',
        originLeft: 'isOriginLeft',
        originTop: 'isOriginTop',
        resize: 'isResizeBound',
        resizeContainer: 'isResizingContainer'
    };

    proto._create = function() {
        // get items from children
        this.reloadItems();
        // elements that affect layout, but are not laid out
        this.stamps = [];
        this.stamp( this.options.stamp );
        // set container style
        utils.extend( this.element.style, this.options.containerStyle );

        // bind resize method
        var canBindResize = this._getOption('resize');
        if ( canBindResize ) {
            this.bindResize();
        }
    };

// goes through all children again and gets bricks in proper order
    proto.reloadItems = function() {
        // collection of item elements
        this.items = this._itemize( this.element.children );
    };


    /**
     * turn elements into Outlayer.Items to be used in layout
     * @param {Array or NodeList or HTMLElement} elems
     * @returns {Array} items - collection of new Outlayer Items
     */
    proto._itemize = function( elems ) {

        var itemElems = this._filterFindItemElements( elems );
        var Item = this.constructor.Item;

        // create new Outlayer Items for collection
        var items = [];
        for ( var i=0; i < itemElems.length; i++ ) {
            var elem = itemElems[i];
            var item = new Item( elem, this );
            items.push( item );
        }

        return items;
    };

    /**
     * get item elements to be used in layout
     * @param {Array or NodeList or HTMLElement} elems
     * @returns {Array} items - item elements
     */
    proto._filterFindItemElements = function( elems ) {
        return utils.filterFindElements( elems, this.options.itemSelector );
    };

    /**
     * getter method for getting item elements
     * @returns {Array} elems - collection of item elements
     */
    proto.getItemElements = function() {
        return this.items.map( function( item ) {
            return item.element;
        });
    };

// ----- init & layout ----- //

    /**
     * lays out all items
     */
    proto.layout = function() {
        this._resetLayout();
        this._manageStamps();

        // don't animate first layout
        var layoutInstant = this._getOption('layoutInstant');
        var isInstant = layoutInstant !== undefined ?
            layoutInstant : !this._isLayoutInited;
        this.layoutItems( this.items, isInstant );

        // flag for initalized
        this._isLayoutInited = true;
    };

// _init is alias for layout
    proto._init = proto.layout;

    /**
     * logic before any new layout
     */
    proto._resetLayout = function() {
        this.getSize();
    };


    proto.getSize = function() {
        this.size = getSize( this.element );
    };

    /**
     * get measurement from option, for columnWidth, rowHeight, gutter
     * if option is String -> get element from selector string, & get size of element
     * if option is Element -> get size of element
     * else use option as a number
     *
     * @param {String} measurement
     * @param {String} size - width or height
     * @private
     */
    proto._getMeasurement = function( measurement, size ) {
        var option = this.options[ measurement ];
        var elem;
        if ( !option ) {
            // default to 0
            this[ measurement ] = 0;
        } else {
            // use option as an element
            if ( typeof option == 'string' ) {
                elem = this.element.querySelector( option );
            } else if ( option instanceof HTMLElement ) {
                elem = option;
            }
            // use size of element, if element
            this[ measurement ] = elem ? getSize( elem )[ size ] : option;
        }
    };

    /**
     * layout a collection of item elements
     * @api public
     */
    proto.layoutItems = function( items, isInstant ) {
        items = this._getItemsForLayout( items );

        this._layoutItems( items, isInstant );

        this._postLayout();
    };

    /**
     * get the items to be laid out
     * you may want to skip over some items
     * @param {Array} items
     * @returns {Array} items
     */
    proto._getItemsForLayout = function( items ) {
        return items.filter( function( item ) {
            return !item.isIgnored;
        });
    };

    /**
     * layout items
     * @param {Array} items
     * @param {Boolean} isInstant
     */
    proto._layoutItems = function( items, isInstant ) {
        this._emitCompleteOnItems( 'layout', items );

        if ( !items || !items.length ) {
            // no items, emit event with empty array
            return;
        }

        var queue = [];

        items.forEach( function( item ) {
            // get x/y object from method
            var position = this._getItemLayoutPosition( item );
            // enqueue
            position.item = item;
            position.isInstant = isInstant || item.isLayoutInstant;
            queue.push( position );
        }, this );

        this._processLayoutQueue( queue );
    };

    /**
     * get item layout position
     * @param {Outlayer.Item} item
     * @returns {Object} x and y position
     */
    proto._getItemLayoutPosition = function( /* item */ ) {
        return {
            x: 0,
            y: 0
        };
    };

    /**
     * iterate over array and position each item
     * Reason being - separating this logic prevents 'layout invalidation'
     * thx @paul_irish
     * @param {Array} queue
     */
    proto._processLayoutQueue = function( queue ) {
        this.updateStagger();
        queue.forEach( function( obj, i ) {
            this._positionItem( obj.item, obj.x, obj.y, obj.isInstant, i );
        }, this );
    };

// set stagger from option in milliseconds number
    proto.updateStagger = function() {
        var stagger = this.options.stagger;
        if ( stagger === null || stagger === undefined ) {
            this.stagger = 0;
            return;
        }
        this.stagger = getMilliseconds( stagger );
        return this.stagger;
    };

    /**
     * Sets position of item in DOM
     * @param {Outlayer.Item} item
     * @param {Number} x - horizontal position
     * @param {Number} y - vertical position
     * @param {Boolean} isInstant - disables transitions
     */
    proto._positionItem = function( item, x, y, isInstant, i ) {
        if ( isInstant ) {
            // if not transition, just set CSS
            item.goTo( x, y );
        } else {
            item.stagger( i * this.stagger );
            item.moveTo( x, y );
        }
    };

    /**
     * Any logic you want to do after each layout,
     * i.e. size the container
     */
    proto._postLayout = function() {
        this.resizeContainer();
    };

    proto.resizeContainer = function() {
        var isResizingContainer = this._getOption('resizeContainer');
        if ( !isResizingContainer ) {
            return;
        }
        var size = this._getContainerSize();
        if ( size ) {
            this._setContainerMeasure( size.width, true );
            this._setContainerMeasure( size.height, false );
        }
    };

    /**
     * Sets width or height of container if returned
     * @returns {Object} size
     *   @param {Number} width
     *   @param {Number} height
     */
    proto._getContainerSize = noop;

    /**
     * @param {Number} measure - size of width or height
     * @param {Boolean} isWidth
     */
    proto._setContainerMeasure = function( measure, isWidth ) {
        if ( measure === undefined ) {
            return;
        }

        var elemSize = this.size;
        // add padding and border width if border box
        if ( elemSize.isBorderBox ) {
            measure += isWidth ? elemSize.paddingLeft + elemSize.paddingRight +
                elemSize.borderLeftWidth + elemSize.borderRightWidth :
                elemSize.paddingBottom + elemSize.paddingTop +
                elemSize.borderTopWidth + elemSize.borderBottomWidth;
        }

        measure = Math.max( measure, 0 );
        this.element.style[ isWidth ? 'width' : 'height' ] = measure + 'px';
    };

    /**
     * emit eventComplete on a collection of items events
     * @param {String} eventName
     * @param {Array} items - Outlayer.Items
     */
    proto._emitCompleteOnItems = function( eventName, items ) {
        var _this = this;
        function onComplete() {
            _this.dispatchEvent( eventName + 'Complete', null, [ items ] );
        }

        var count = items.length;
        if ( !items || !count ) {
            onComplete();
            return;
        }

        var doneCount = 0;
        function tick() {
            doneCount++;
            if ( doneCount == count ) {
                onComplete();
            }
        }

        // bind callback
        items.forEach( function( item ) {
            item.once( eventName, tick );
        });
    };

    /**
     * emits events via EvEmitter and jQuery events
     * @param {String} type - name of event
     * @param {Event} event - original event
     * @param {Array} args - extra arguments
     */
    proto.dispatchEvent = function( type, event, args ) {
        // add original event to arguments
        var emitArgs = event ? [ event ].concat( args ) : args;
        this.emitEvent( type, emitArgs );

        if ( jQuery ) {
            // set this.$element
            this.$element = this.$element || jQuery( this.element );
            if ( event ) {
                // create jQuery event
                var $event = jQuery.Event( event );
                $event.type = type;
                this.$element.trigger( $event, args );
            } else {
                // just trigger with type if no event available
                this.$element.trigger( type, args );
            }
        }
    };

// -------------------------- ignore & stamps -------------------------- //


    /**
     * keep item in collection, but do not lay it out
     * ignored items do not get skipped in layout
     * @param {Element} elem
     */
    proto.ignore = function( elem ) {
        var item = this.getItem( elem );
        if ( item ) {
            item.isIgnored = true;
        }
    };

    /**
     * return item to layout collection
     * @param {Element} elem
     */
    proto.unignore = function( elem ) {
        var item = this.getItem( elem );
        if ( item ) {
            delete item.isIgnored;
        }
    };

    /**
     * adds elements to stamps
     * @param {NodeList, Array, Element, or String} elems
     */
    proto.stamp = function( elems ) {
        elems = this._find( elems );
        if ( !elems ) {
            return;
        }

        this.stamps = this.stamps.concat( elems );
        // ignore
        elems.forEach( this.ignore, this );
    };

    /**
     * removes elements to stamps
     * @param {NodeList, Array, or Element} elems
     */
    proto.unstamp = function( elems ) {
        elems = this._find( elems );
        if ( !elems ){
            return;
        }

        elems.forEach( function( elem ) {
            // filter out removed stamp elements
            utils.removeFrom( this.stamps, elem );
            this.unignore( elem );
        }, this );
    };

    /**
     * finds child elements
     * @param {NodeList, Array, Element, or String} elems
     * @returns {Array} elems
     */
    proto._find = function( elems ) {
        if ( !elems ) {
            return;
        }
        // if string, use argument as selector string
        if ( typeof elems == 'string' ) {
            elems = this.element.querySelectorAll( elems );
        }
        elems = utils.makeArray( elems );
        return elems;
    };

    proto._manageStamps = function() {
        if ( !this.stamps || !this.stamps.length ) {
            return;
        }

        this._getBoundingRect();

        this.stamps.forEach( this._manageStamp, this );
    };

// update boundingLeft / Top
    proto._getBoundingRect = function() {
        // get bounding rect for container element
        var boundingRect = this.element.getBoundingClientRect();
        var size = this.size;
        this._boundingRect = {
            left: boundingRect.left + size.paddingLeft + size.borderLeftWidth,
            top: boundingRect.top + size.paddingTop + size.borderTopWidth,
            right: boundingRect.right - ( size.paddingRight + size.borderRightWidth ),
            bottom: boundingRect.bottom - ( size.paddingBottom + size.borderBottomWidth )
        };
    };

    /**
     * @param {Element} stamp
     **/
    proto._manageStamp = noop;

    /**
     * get x/y position of element relative to container element
     * @param {Element} elem
     * @returns {Object} offset - has left, top, right, bottom
     */
    proto._getElementOffset = function( elem ) {
        var boundingRect = elem.getBoundingClientRect();
        var thisRect = this._boundingRect;
        var size = getSize( elem );
        var offset = {
            left: boundingRect.left - thisRect.left - size.marginLeft,
            top: boundingRect.top - thisRect.top - size.marginTop,
            right: thisRect.right - boundingRect.right - size.marginRight,
            bottom: thisRect.bottom - boundingRect.bottom - size.marginBottom
        };
        return offset;
    };

// -------------------------- resize -------------------------- //

// enable event handlers for listeners
// i.e. resize -> onresize
    proto.handleEvent = utils.handleEvent;

    /**
     * Bind layout to window resizing
     */
    proto.bindResize = function() {
        window.addEventListener( 'resize', this );
        this.isResizeBound = true;
    };

    /**
     * Unbind layout to window resizing
     */
    proto.unbindResize = function() {
        window.removeEventListener( 'resize', this );
        this.isResizeBound = false;
    };

    proto.onresize = function() {
        this.resize();
    };

    utils.debounceMethod( Outlayer, 'onresize', 100 );

    proto.resize = function() {
        // don't trigger if size did not change
        // or if resize was unbound. See #9
        if ( !this.isResizeBound || !this.needsResizeLayout() ) {
            return;
        }

        this.layout();
    };

    /**
     * check if layout is needed post layout
     * @returns Boolean
     */
    proto.needsResizeLayout = function() {
        var size = getSize( this.element );
        // check that this.size and size are there
        // IE8 triggers resize on body size change, so they might not be
        var hasSizes = this.size && size;
        return hasSizes && size.innerWidth !== this.size.innerWidth;
    };

// -------------------------- methods -------------------------- //

    /**
     * add items to Outlayer instance
     * @param {Array or NodeList or Element} elems
     * @returns {Array} items - Outlayer.Items
     **/
    proto.addItems = function( elems ) {
        var items = this._itemize( elems );
        // add items to collection
        if ( items.length ) {
            this.items = this.items.concat( items );
        }
        return items;
    };

    /**
     * Layout newly-appended item elements
     * @param {Array or NodeList or Element} elems
     */
    proto.appended = function( elems ) {
        var items = this.addItems( elems );
        if ( !items.length ) {
            return;
        }
        // layout and reveal just the new items
        this.layoutItems( items, true );
        this.reveal( items );
    };

    /**
     * Layout prepended elements
     * @param {Array or NodeList or Element} elems
     */
    proto.prepended = function( elems ) {
        var items = this._itemize( elems );
        if ( !items.length ) {
            return;
        }
        // add items to beginning of collection
        var previousItems = this.items.slice(0);
        this.items = items.concat( previousItems );
        // start new layout
        this._resetLayout();
        this._manageStamps();
        // layout new stuff without transition
        this.layoutItems( items, true );
        this.reveal( items );
        // layout previous items
        this.layoutItems( previousItems );
    };

    /**
     * reveal a collection of items
     * @param {Array of Outlayer.Items} items
     */
    proto.reveal = function( items ) {
        this._emitCompleteOnItems( 'reveal', items );
        if ( !items || !items.length ) {
            return;
        }
        var stagger = this.updateStagger();
        items.forEach( function( item, i ) {
            item.stagger( i * stagger );
            item.reveal();
        });
    };

    /**
     * hide a collection of items
     * @param {Array of Outlayer.Items} items
     */
    proto.hide = function( items ) {
        this._emitCompleteOnItems( 'hide', items );
        if ( !items || !items.length ) {
            return;
        }
        var stagger = this.updateStagger();
        items.forEach( function( item, i ) {
            item.stagger( i * stagger );
            item.hide();
        });
    };

    /**
     * reveal item elements
     * @param {Array}, {Element}, {NodeList} items
     */
    proto.revealItemElements = function( elems ) {
        var items = this.getItems( elems );
        this.reveal( items );
    };

    /**
     * hide item elements
     * @param {Array}, {Element}, {NodeList} items
     */
    proto.hideItemElements = function( elems ) {
        var items = this.getItems( elems );
        this.hide( items );
    };

    /**
     * get Outlayer.Item, given an Element
     * @param {Element} elem
     * @param {Function} callback
     * @returns {Outlayer.Item} item
     */
    proto.getItem = function( elem ) {
        // loop through items to get the one that matches
        for ( var i=0; i < this.items.length; i++ ) {
            var item = this.items[i];
            if ( item.element == elem ) {
                // return item
                return item;
            }
        }
    };

    /**
     * get collection of Outlayer.Items, given Elements
     * @param {Array} elems
     * @returns {Array} items - Outlayer.Items
     */
    proto.getItems = function( elems ) {
        elems = utils.makeArray( elems );
        var items = [];
        elems.forEach( function( elem ) {
            var item = this.getItem( elem );
            if ( item ) {
                items.push( item );
            }
        }, this );

        return items;
    };

    /**
     * remove element(s) from instance and DOM
     * @param {Array or NodeList or Element} elems
     */
    proto.remove = function( elems ) {
        var removeItems = this.getItems( elems );

        this._emitCompleteOnItems( 'remove', removeItems );

        // bail if no items to remove
        if ( !removeItems || !removeItems.length ) {
            return;
        }

        removeItems.forEach( function( item ) {
            item.remove();
            // remove item from collection
            utils.removeFrom( this.items, item );
        }, this );
    };

// ----- destroy ----- //

// remove and disable Outlayer instance
    proto.destroy = function() {
        // clean up dynamic styles
        var style = this.element.style;
        // style.height = '';
        // style.position = '';
        // style.width = '';
        // destroy items
        this.items.forEach( function( item ) {
            item.destroy();
        });

        this.unbindResize();

        var id = this.element.outlayerGUID;
        delete instances[ id ]; // remove reference to instance by id
        delete this.element.outlayerGUID;
        // remove data for jQuery
        if ( jQuery ) {
            jQuery.removeData( this.element, this.constructor.namespace );
        }

    };

// -------------------------- data -------------------------- //

    /**
     * get Outlayer instance from element
     * @param {Element} elem
     * @returns {Outlayer}
     */
    Outlayer.data = function( elem ) {
        elem = utils.getQueryElement( elem );
        var id = elem && elem.outlayerGUID;
        return id && instances[ id ];
    };


// -------------------------- create Outlayer class -------------------------- //

    /**
     * create a layout class
     * @param {String} namespace
     */
    Outlayer.create = function( namespace, options ) {
        // sub-class Outlayer
        var Layout = subclass( Outlayer );
        // apply new options and compatOptions
        Layout.defaults = utils.extend( {}, Outlayer.defaults );
        utils.extend( Layout.defaults, options );
        Layout.compatOptions = utils.extend( {}, Outlayer.compatOptions  );

        Layout.namespace = namespace;

        Layout.data = Outlayer.data;

        // sub-class Item
        Layout.Item = subclass( Item );

        // -------------------------- declarative -------------------------- //

        utils.htmlInit( Layout, namespace );

        // -------------------------- jQuery bridge -------------------------- //

        // make into jQuery plugin
        if ( jQuery && jQuery.bridget ) {
            jQuery.bridget( namespace, Layout );
        }

        return Layout;
    };

    function subclass( Parent ) {
        function SubClass() {
            Parent.apply( this, arguments );
        }

        SubClass.prototype = Object.create( Parent.prototype );
        SubClass.prototype.constructor = SubClass;

        return SubClass;
    }

// ----- helpers ----- //

// how many milliseconds are in each unit
    var msUnits = {
        ms: 1,
        s: 1000
    };

// munge time-like parameter into millisecond number
// '0.4s' -> 40
    function getMilliseconds( time ) {
        if ( typeof time == 'number' ) {
            return time;
        }
        var matches = time.match( /(^\d*\.?\d*)(\w*)/ );
        var num = matches && matches[1];
        var unit = matches && matches[2];
        if ( !num.length ) {
            return 0;
        }
        num = parseFloat( num );
        var mult = msUnits[ unit ] || 1;
        return num * mult;
    }

// ----- fin ----- //

// back in global
    Outlayer.Item = Item;

    return Outlayer;

}));

/**
 * Isotope Item
 **/

( function( window, factory ) {
    // universal module definition
    /* jshint strict: false */ /*globals define, module, require */
    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( 'isotope/js/item',[
                'outlayer/outlayer'
            ],
            factory );
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory(
            require('outlayer')
        );
    } else {
        // browser global
        window.Isotope = window.Isotope || {};
        window.Isotope.Item = factory(
            window.Outlayer
        );
    }

}( window, function factory( Outlayer ) {
    'use strict';

// -------------------------- Item -------------------------- //

// sub-class Outlayer Item
    function Item() {
        Outlayer.Item.apply( this, arguments );
    }

    var proto = Item.prototype = Object.create( Outlayer.Item.prototype );

    var _create = proto._create;
    proto._create = function() {
        // assign id, used for original-order sorting
        this.id = this.layout.itemGUID++;
        _create.call( this );
        this.sortData = {};
    };

    proto.updateSortData = function() {
        if ( this.isIgnored ) {
            return;
        }
        // default sorters
        this.sortData.id = this.id;
        // for backward compatibility
        this.sortData['original-order'] = this.id;
        this.sortData.random = Math.random();
        // go thru getSortData obj and apply the sorters
        var getSortData = this.layout.options.getSortData;
        var sorters = this.layout._sorters;
        for ( var key in getSortData ) {
            var sorter = sorters[ key ];
            this.sortData[ key ] = sorter( this.element, this );
        }
    };

    var _destroy = proto.destroy;
    proto.destroy = function() {
        // call super
        _destroy.apply( this, arguments );
        // reset display, #741
        this.css({
            display: ''
        });
    };

    return Item;

}));

/**
 * Isotope LayoutMode
 */

( function( window, factory ) {
    // universal module definition
    /* jshint strict: false */ /*globals define, module, require */
    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( 'isotope/js/layout-mode',[
                'get-size/get-size',
                'outlayer/outlayer'
            ],
            factory );
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory(
            require('get-size'),
            require('outlayer')
        );
    } else {
        // browser global
        window.Isotope = window.Isotope || {};
        window.Isotope.LayoutMode = factory(
            window.getSize,
            window.Outlayer
        );
    }

}( window, function factory( getSize, Outlayer ) {
    'use strict';

    // layout mode class
    function LayoutMode( isotope ) {
        this.isotope = isotope;
        // link properties
        if ( isotope ) {
            this.options = isotope.options[ this.namespace ];
            this.element = isotope.element;
            this.items = isotope.filteredItems;
            this.size = isotope.size;
        }
    }

    var proto = LayoutMode.prototype;

    /**
     * some methods should just defer to default Outlayer method
     * and reference the Isotope instance as `this`
     **/
    var facadeMethods = [
        '_resetLayout',
        '_getItemLayoutPosition',
        '_manageStamp',
        '_getContainerSize',
        '_getElementOffset',
        'needsResizeLayout',
        '_getOption'
    ];

    facadeMethods.forEach( function( methodName ) {
        proto[ methodName ] = function() {
            return Outlayer.prototype[ methodName ].apply( this.isotope, arguments );
        };
    });

    // -----  ----- //

    // for horizontal layout modes, check vertical size
    proto.needsVerticalResizeLayout = function() {
        // don't trigger if size did not change
        var size = getSize( this.isotope.element );
        // check that this.size and size are there
        // IE8 triggers resize on body size change, so they might not be
        var hasSizes = this.isotope.size && size;
        return hasSizes && size.innerHeight != this.isotope.size.innerHeight;
    };

    // ----- measurements ----- //

    proto._getMeasurement = function() {
        this.isotope._getMeasurement.apply( this, arguments );
    };

    proto.getColumnWidth = function() {
        this.getSegmentSize( 'column', 'Width' );
    };

    proto.getRowHeight = function() {
        this.getSegmentSize( 'row', 'Height' );
    };

    /**
     * get columnWidth or rowHeight
     * segment: 'column' or 'row'
     * size 'Width' or 'Height'
     **/
    proto.getSegmentSize = function( segment, size ) {
        var segmentName = segment + size;
        var outerSize = 'outer' + size;
        // columnWidth / outerWidth // rowHeight / outerHeight
        this._getMeasurement( segmentName, outerSize );
        // got rowHeight or columnWidth, we can chill
        if ( this[ segmentName ] ) {
            return;
        }
        // fall back to item of first element
        var firstItemSize = this.getFirstItemSize();
        this[ segmentName ] = firstItemSize && firstItemSize[ outerSize ] ||
            // or size of container
            this.isotope.size[ 'inner' + size ];
    };

    proto.getFirstItemSize = function() {
        var firstItem = this.isotope.filteredItems[0];
        return firstItem && firstItem.element && getSize( firstItem.element );
    };

    // ----- methods that should reference isotope ----- //

    proto.layout = function() {
        this.isotope.layout.apply( this.isotope, arguments );
    };

    proto.getSize = function() {
        this.isotope.getSize();
        this.size = this.isotope.size;
    };

    // -------------------------- create -------------------------- //

    LayoutMode.modes = {};

    LayoutMode.create = function( namespace, options ) {

        function Mode() {
            LayoutMode.apply( this, arguments );
        }

        Mode.prototype = Object.create( proto );
        Mode.prototype.constructor = Mode;

        // default options
        if ( options ) {
            Mode.options = options;
        }

        Mode.prototype.namespace = namespace;
        // register in Isotope
        LayoutMode.modes[ namespace ] = Mode;

        return Mode;
    };

    return LayoutMode;

}));

/*!
 * Masonry v4.1.1
 * Cascading grid layout library
 * http://masonry.desandro.com
 * MIT License
 * by David DeSandro
 */

( function( window, factory ) {
    // universal module definition
    /* jshint strict: false */ /*globals define, module, require */
    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( 'masonry/masonry',[
                'outlayer/outlayer',
                'get-size/get-size'
            ],
            factory );
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory(
            require('outlayer'),
            require('get-size')
        );
    } else {
        // browser global
        window.Masonry = factory(
            window.Outlayer,
            window.getSize
        );
    }

}( window, function factory( Outlayer, getSize ) {



// -------------------------- masonryDefinition -------------------------- //

    // create an Outlayer layout class
    var Masonry = Outlayer.create('masonry');
    // isFitWidth -> fitWidth
    Masonry.compatOptions.fitWidth = 'isFitWidth';

    Masonry.prototype._resetLayout = function() {
        this.getSize();
        this._getMeasurement( 'columnWidth', 'outerWidth' );
        this._getMeasurement( 'gutter', 'outerWidth' );
        this.measureColumns();

        // reset column Y
        this.colYs = [];
        for ( var i=0; i < this.cols; i++ ) {
            this.colYs.push( 0 );
        }

        this.maxY = 0;
    };

    Masonry.prototype.measureColumns = function() {
        this.getContainerWidth();
        // if columnWidth is 0, default to outerWidth of first item
        if ( !this.columnWidth ) {
            var firstItem = this.items[0];
            var firstItemElem = firstItem && firstItem.element;
            // columnWidth fall back to item of first element
            this.columnWidth = firstItemElem && getSize( firstItemElem ).outerWidth ||
                // if first elem has no width, default to size of container
                this.containerWidth;
        }

        var columnWidth = this.columnWidth += this.gutter;

        // calculate columns
        var containerWidth = this.containerWidth + this.gutter;
        var cols = containerWidth / columnWidth;
        // fix rounding errors, typically with gutters
        var excess = columnWidth - containerWidth % columnWidth;
        // if overshoot is less than a pixel, round up, otherwise floor it
        var mathMethod = excess && excess < 1 ? 'round' : 'floor';
        cols = Math[ mathMethod ]( cols );
        this.cols = Math.max( cols, 1 );
    };

    Masonry.prototype.getContainerWidth = function() {
        // container is parent if fit width
        var isFitWidth = this._getOption('fitWidth');
        var container = isFitWidth ? this.element.parentNode : this.element;
        // check that this.size and size are there
        // IE8 triggers resize on body size change, so they might not be
        var size = getSize( container );
        this.containerWidth = size && size.innerWidth;
    };

    Masonry.prototype._getItemLayoutPosition = function( item ) {
        item.getSize();
        // how many columns does this brick span
        var remainder = item.size.outerWidth % this.columnWidth;
        var mathMethod = remainder && remainder < 1 ? 'round' : 'ceil';
        // round if off by 1 pixel, otherwise use ceil
        var colSpan = Math[ mathMethod ]( item.size.outerWidth / this.columnWidth );
        colSpan = Math.min( colSpan, this.cols );

        var colGroup = this._getColGroup( colSpan );
        // get the minimum Y value from the columns
        var minimumY = Math.min.apply( Math, colGroup );
        var shortColIndex = colGroup.indexOf( minimumY );

        // position the brick
        var position = {
            x: this.columnWidth * shortColIndex,
            y: minimumY
        };

        // apply setHeight to necessary columns
        var setHeight = minimumY + item.size.outerHeight;
        var setSpan = this.cols + 1 - colGroup.length;
        for ( var i = 0; i < setSpan; i++ ) {
            this.colYs[ shortColIndex + i ] = setHeight;
        }

        return position;
    };

    /**
     * @param {Number} colSpan - number of columns the element spans
     * @returns {Array} colGroup
     */
    Masonry.prototype._getColGroup = function( colSpan ) {
        if ( colSpan < 2 ) {
            // if brick spans only one column, use all the column Ys
            return this.colYs;
        }

        var colGroup = [];
        // how many different places could this brick fit horizontally
        var groupCount = this.cols + 1 - colSpan;
        // for each group potential horizontal position
        for ( var i = 0; i < groupCount; i++ ) {
            // make an array of colY values for that one group
            var groupColYs = this.colYs.slice( i, i + colSpan );
            // and get the max value of the array
            colGroup[i] = Math.max.apply( Math, groupColYs );
        }
        return colGroup;
    };

    Masonry.prototype._manageStamp = function( stamp ) {
        var stampSize = getSize( stamp );
        var offset = this._getElementOffset( stamp );
        // get the columns that this stamp affects
        var isOriginLeft = this._getOption('originLeft');
        var firstX = isOriginLeft ? offset.left : offset.right;
        var lastX = firstX + stampSize.outerWidth;
        var firstCol = Math.floor( firstX / this.columnWidth );
        firstCol = Math.max( 0, firstCol );
        var lastCol = Math.floor( lastX / this.columnWidth );
        // lastCol should not go over if multiple of columnWidth #425
        lastCol -= lastX % this.columnWidth ? 0 : 1;
        lastCol = Math.min( this.cols - 1, lastCol );
        // set colYs to bottom of the stamp

        var isOriginTop = this._getOption('originTop');
        var stampMaxY = ( isOriginTop ? offset.top : offset.bottom ) +
            stampSize.outerHeight;
        for ( var i = firstCol; i <= lastCol; i++ ) {
            this.colYs[i] = Math.max( stampMaxY, this.colYs[i] );
        }
    };

    Masonry.prototype._getContainerSize = function() {
        this.maxY = Math.max.apply( Math, this.colYs );
        var size = {
            height: this.maxY
        };

        if ( this._getOption('fitWidth') ) {
            size.width = this._getContainerFitWidth();
        }

        return size;
    };

    Masonry.prototype._getContainerFitWidth = function() {
        var unusedCols = 0;
        // count unused columns
        var i = this.cols;
        while ( --i ) {
            if ( this.colYs[i] !== 0 ) {
                break;
            }
            unusedCols++;
        }
        // fit container to columns that have been used
        return ( this.cols - unusedCols ) * this.columnWidth - this.gutter;
    };

    Masonry.prototype.needsResizeLayout = function() {
        var previousWidth = this.containerWidth;
        this.getContainerWidth();
        return previousWidth != this.containerWidth;
    };

    return Masonry;

}));

/*!
 * Masonry layout mode
 * sub-classes Masonry
 * http://masonry.desandro.com
 */

( function( window, factory ) {
    // universal module definition
    /* jshint strict: false */ /*globals define, module, require */
    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( 'isotope/js/layout-modes/masonry',[
                '../layout-mode',
                'masonry/masonry'
            ],
            factory );
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory(
            require('../layout-mode'),
            require('masonry-layout')
        );
    } else {
        // browser global
        factory(
            window.Isotope.LayoutMode,
            window.Masonry
        );
    }

}( window, function factory( LayoutMode, Masonry ) {
    'use strict';

// -------------------------- masonryDefinition -------------------------- //

    // create an Outlayer layout class
    var MasonryMode = LayoutMode.create('masonry');

    var proto = MasonryMode.prototype;

    var keepModeMethods = {
        _getElementOffset: true,
        layout: true,
        _getMeasurement: true
    };

    // inherit Masonry prototype
    for ( var method in Masonry.prototype ) {
        // do not inherit mode methods
        if ( !keepModeMethods[ method ] ) {
            proto[ method ] = Masonry.prototype[ method ];
        }
    }

    var measureColumns = proto.measureColumns;
    proto.measureColumns = function() {
        // set items, used if measuring first item
        this.items = this.isotope.filteredItems;
        measureColumns.call( this );
    };

    // point to mode options for fitWidth
    var _getOption = proto._getOption;
    proto._getOption = function( option ) {
        if ( option == 'fitWidth' ) {
            return this.options.isFitWidth !== undefined ?
                this.options.isFitWidth : this.options.fitWidth;
        }
        return _getOption.apply( this.isotope, arguments );
    };

    return MasonryMode;

}));










/*!
 * Isotope v3.0.3
 *
 * Licensed GPLv3 for open source use
 * or Isotope Commercial License for commercial use
 *
 * http://isotope.metafizzy.co
 * Copyright 2017 Metafizzy
 */

( function( window, factory ) {
    // universal module definition
    /* jshint strict: false */ /*globals define, module, require */
    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( [
                'outlayer/outlayer',
                'get-size/get-size',
                'desandro-matches-selector/matches-selector',
                'fizzy-ui-utils/utils',
                'isotope/js/item',
                'isotope/js/layout-mode',
                // include default layout modes
                'isotope/js/layout-modes/masonry',
                'isotope/js/layout-modes/fit-rows',
                'isotope/js/layout-modes/vertical'
            ],
            function( Outlayer, getSize, matchesSelector, utils, Item, LayoutMode ) {
                return factory( window, Outlayer, getSize, matchesSelector, utils, Item, LayoutMode );
            });
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory(
            window,
            require('outlayer'),
            require('get-size'),
            require('desandro-matches-selector'),
            require('fizzy-ui-utils'),
            require('isotope/js/item'),
            require('isotope/js/layout-mode'),
            // include default layout modes
            require('isotope/js/layout-modes/masonry'),
            require('isotope/js/layout-modes/fit-rows'),
            require('isotope/js/layout-modes/vertical')
        );
    } else {
        // browser global
        window.Isotope = factory(
            window,
            window.Outlayer,
            window.getSize,
            window.matchesSelector,
            window.fizzyUIUtils,
            window.Isotope.Item,
            window.Isotope.LayoutMode
        );
    }

}( window, function factory( window, Outlayer, getSize, matchesSelector, utils,
                             Item, LayoutMode ) {



// -------------------------- vars -------------------------- //

    var jQuery = window.jQuery;

// -------------------------- helpers -------------------------- //

    var trim = String.prototype.trim ?
        function( str ) {
            return str.trim();
        } :
        function( str ) {
            return str.replace( /^\s+|\s+$/g, '' );
        };

// -------------------------- isotopeDefinition -------------------------- //

    // create an Outlayer layout class
    var Isotope = Outlayer.create( 'isotope', {
        layoutMode: 'masonry',
        isJQueryFiltering: true,
        sortAscending: true
    });

    Isotope.Item = Item;
    Isotope.LayoutMode = LayoutMode;

    var proto = Isotope.prototype;

    proto._create = function() {
        this.itemGUID = 0;
        // functions that sort items
        this._sorters = {};
        this._getSorters();
        // call super
        Outlayer.prototype._create.call( this );

        // create layout modes
        this.modes = {};
        // start filteredItems with all items
        this.filteredItems = this.items;
        // keep of track of sortBys
        this.sortHistory = [ 'original-order' ];
        // create from registered layout modes
        for ( var name in LayoutMode.modes ) {
            this._initLayoutMode( name );
        }
    };

    proto.reloadItems = function() {
        // reset item ID counter
        this.itemGUID = 0;
        // call super
        Outlayer.prototype.reloadItems.call( this );
    };

    proto._itemize = function() {
        var items = Outlayer.prototype._itemize.apply( this, arguments );
        // assign ID for original-order
        for ( var i=0; i < items.length; i++ ) {
            var item = items[i];
            item.id = this.itemGUID++;
        }
        this._updateItemsSortData( items );
        return items;
    };


    // -------------------------- layout -------------------------- //

    proto._initLayoutMode = function( name ) {
        var Mode = LayoutMode.modes[ name ];
        // set mode options
        // HACK extend initial options, back-fill in default options
        var initialOpts = this.options[ name ] || {};
        this.options[ name ] = Mode.options ?
            utils.extend( Mode.options, initialOpts ) : initialOpts;
        // init layout mode instance
        this.modes[ name ] = new Mode( this );
    };


    proto.layout = function() {
        // if first time doing layout, do all magic
        if ( !this._isLayoutInited && this._getOption('initLayout') ) {
            this.arrange();
            return;
        }
        this._layout();
    };

    // private method to be used in layout() & magic()
    proto._layout = function() {
        // don't animate first layout
        var isInstant = this._getIsInstant();
        // layout flow
        this._resetLayout();
        this._manageStamps();
        this.layoutItems( this.filteredItems, isInstant );

        // flag for initalized
        this._isLayoutInited = true;
    };

    // filter + sort + layout
    proto.arrange = function( opts ) {
        // set any options pass
        this.option( opts );
        this._getIsInstant();
        // filter, sort, and layout

        // filter
        var filtered = this._filter( this.items );
        this.filteredItems = filtered.matches;

        this._bindArrangeComplete();

        if ( this._isInstant ) {
            this._noTransition( this._hideReveal, [ filtered ] );
        } else {
            this._hideReveal( filtered );
        }

        this._sort();
        this._layout();
    };
    // alias to _init for main plugin method
    proto._init = proto.arrange;

    proto._hideReveal = function( filtered ) {
        this.reveal( filtered.needReveal );
        this.hide( filtered.needHide );
    };

    // HACK
    // Don't animate/transition first layout
    // Or don't animate/transition other layouts
    proto._getIsInstant = function() {
        var isLayoutInstant = this._getOption('layoutInstant');
        var isInstant = isLayoutInstant !== undefined ? isLayoutInstant :
            !this._isLayoutInited;
        this._isInstant = isInstant;
        return isInstant;
    };

    // listen for layoutComplete, hideComplete and revealComplete
    // to trigger arrangeComplete
    proto._bindArrangeComplete = function() {
        // listen for 3 events to trigger arrangeComplete
        var isLayoutComplete, isHideComplete, isRevealComplete;
        var _this = this;
        function arrangeParallelCallback() {
            if ( isLayoutComplete && isHideComplete && isRevealComplete ) {
                _this.dispatchEvent( 'arrangeComplete', null, [ _this.filteredItems ] );
            }
        }
        this.once( 'layoutComplete', function() {
            isLayoutComplete = true;
            arrangeParallelCallback();
        });
        this.once( 'hideComplete', function() {
            isHideComplete = true;
            arrangeParallelCallback();
        });
        this.once( 'revealComplete', function() {
            isRevealComplete = true;
            arrangeParallelCallback();
        });
    };

    // -------------------------- filter -------------------------- //

    proto._filter = function( items ) {
        var filter = this.options.filter;
        filter = filter || '*';
        var matches = [];
        var hiddenMatched = [];
        var visibleUnmatched = [];

        var test = this._getFilterTest( filter );

        // test each item
        for ( var i=0; i < items.length; i++ ) {
            var item = items[i];
            if ( item.isIgnored ) {
                continue;
            }
            // add item to either matched or unmatched group
            var isMatched = test( item );
            // item.isFilterMatched = isMatched;
            // add to matches if its a match
            if ( isMatched ) {
                matches.push( item );
            }
            // add to additional group if item needs to be hidden or revealed
            if ( isMatched && item.isHidden ) {
                hiddenMatched.push( item );
            } else if ( !isMatched && !item.isHidden ) {
                visibleUnmatched.push( item );
            }
        }

        // return collections of items to be manipulated
        return {
            matches: matches,
            needReveal: hiddenMatched,
            needHide: visibleUnmatched
        };
    };

    // get a jQuery, function, or a matchesSelector test given the filter
    proto._getFilterTest = function( filter ) {
        if ( jQuery && this.options.isJQueryFiltering ) {
            // use jQuery
            return function( item ) {
                return jQuery( item.element ).is( filter );
            };
        }
        if ( typeof filter == 'function' ) {
            // use filter as function
            return function( item ) {
                return filter( item.element );
            };
        }
        // default, use filter as selector string
        return function( item ) {
            return matchesSelector( item.element, filter );
        };
    };

    // -------------------------- sorting -------------------------- //

    /**
     * @params {Array} elems
     * @public
     */
    proto.updateSortData = function( elems ) {
        // get items
        var items;
        if ( elems ) {
            elems = utils.makeArray( elems );
            items = this.getItems( elems );
        } else {
            // update all items if no elems provided
            items = this.items;
        }

        this._getSorters();
        this._updateItemsSortData( items );
    };

    proto._getSorters = function() {
        var getSortData = this.options.getSortData;
        for ( var key in getSortData ) {
            var sorter = getSortData[ key ];
            this._sorters[ key ] = mungeSorter( sorter );
        }
    };

    /**
     * @params {Array} items - of Isotope.Items
     * @private
     */
    proto._updateItemsSortData = function( items ) {
        // do not update if no items
        var len = items && items.length;

        for ( var i=0; len && i < len; i++ ) {
            var item = items[i];
            item.updateSortData();
        }
    };

    // ----- munge sorter ----- //

    // encapsulate this, as we just need mungeSorter
    // other functions in here are just for munging
    var mungeSorter = ( function() {
        // add a magic layer to sorters for convienent shorthands
        // `.foo-bar` will use the text of .foo-bar querySelector
        // `[foo-bar]` will use attribute
        // you can also add parser
        // `.foo-bar parseInt` will parse that as a number
        function mungeSorter( sorter ) {
            // if not a string, return function or whatever it is
            if ( typeof sorter != 'string' ) {
                return sorter;
            }
            // parse the sorter string
            var args = trim( sorter ).split(' ');
            var query = args[0];
            // check if query looks like [an-attribute]
            var attrMatch = query.match( /^\[(.+)\]$/ );
            var attr = attrMatch && attrMatch[1];
            var getValue = getValueGetter( attr, query );
            // use second argument as a parser
            var parser = Isotope.sortDataParsers[ args[1] ];
            // parse the value, if there was a parser
            sorter = parser ? function( elem ) {
                    return elem && parser( getValue( elem ) );
                } :
                // otherwise just return value
                function( elem ) {
                    return elem && getValue( elem );
                };

            return sorter;
        }

        // get an attribute getter, or get text of the querySelector
        function getValueGetter( attr, query ) {
            // if query looks like [foo-bar], get attribute
            if ( attr ) {
                return function getAttribute( elem ) {
                    return elem.getAttribute( attr );
                };
            }

            // otherwise, assume its a querySelector, and get its text
            return function getChildText( elem ) {
                var child = elem.querySelector( query );
                return child && child.textContent;
            };
        }

        return mungeSorter;
    })();

    // parsers used in getSortData shortcut strings
    Isotope.sortDataParsers = {
        'parseInt': function( val ) {
            return parseInt( val, 10 );
        },
        'parseFloat': function( val ) {
            return parseFloat( val );
        }
    };

    // ----- sort method ----- //

    // sort filteredItem order
    proto._sort = function() {
        if ( !this.options.sortBy ) {
            return;
        }
        // keep track of sortBy History
        var sortBys = utils.makeArray( this.options.sortBy );
        if ( !this._getIsSameSortBy( sortBys ) ) {
            // concat all sortBy and sortHistory, add to front, oldest goes in last
            this.sortHistory = sortBys.concat( this.sortHistory );
        }
        // sort magic
        var itemSorter = getItemSorter( this.sortHistory, this.options.sortAscending );
        this.filteredItems.sort( itemSorter );
    };

    // check if sortBys is same as start of sortHistory
    proto._getIsSameSortBy = function( sortBys ) {
        for ( var i=0; i < sortBys.length; i++ ) {
            if ( sortBys[i] != this.sortHistory[i] ) {
                return false;
            }
        }
        return true;
    };

    // returns a function used for sorting
    function getItemSorter( sortBys, sortAsc ) {
        return function sorter( itemA, itemB ) {
            // cycle through all sortKeys
            for ( var i = 0; i < sortBys.length; i++ ) {
                var sortBy = sortBys[i];
                var a = itemA.sortData[ sortBy ];
                var b = itemB.sortData[ sortBy ];
                if ( a > b || a < b ) {
                    // if sortAsc is an object, use the value given the sortBy key
                    var isAscending = sortAsc[ sortBy ] !== undefined ? sortAsc[ sortBy ] : sortAsc;
                    var direction = isAscending ? 1 : -1;
                    return ( a > b ? 1 : -1 ) * direction;
                }
            }
            return 0;
        };
    }

    // -------------------------- methods -------------------------- //

    // get layout mode
    proto._mode = function() {
        var layoutMode = this.options.layoutMode;
        var mode = this.modes[ layoutMode ];
        if ( !mode ) {
            // TODO console.error
            throw new Error( 'No layout mode: ' + layoutMode );
        }
        // HACK sync mode's options
        // any options set after init for layout mode need to be synced
        mode.options = this.options[ layoutMode ];
        return mode;
    };

    proto._resetLayout = function() {
        // trigger original reset layout
        Outlayer.prototype._resetLayout.call( this );
        this._mode()._resetLayout();
    };

    proto._getItemLayoutPosition = function( item  ) {
        return this._mode()._getItemLayoutPosition( item );
    };

    proto._manageStamp = function( stamp ) {
        this._mode()._manageStamp( stamp );
    };

    proto._getContainerSize = function() {
        return this._mode()._getContainerSize();
    };

    proto.needsResizeLayout = function() {
        return this._mode().needsResizeLayout();
    };

    // -------------------------- adding & removing -------------------------- //

    // HEADS UP overwrites default Outlayer appended
    proto.appended = function( elems ) {
        var items = this.addItems( elems );
        if ( !items.length ) {
            return;
        }
        // filter, layout, reveal new items
        var filteredItems = this._filterRevealAdded( items );
        // add to filteredItems
        this.filteredItems = this.filteredItems.concat( filteredItems );
    };

    // HEADS UP overwrites default Outlayer prepended
    proto.prepended = function( elems ) {
        var items = this._itemize( elems );
        if ( !items.length ) {
            return;
        }
        // start new layout
        this._resetLayout();
        this._manageStamps();
        // filter, layout, reveal new items
        var filteredItems = this._filterRevealAdded( items );
        // layout previous items
        this.layoutItems( this.filteredItems );
        // add to items and filteredItems
        this.filteredItems = filteredItems.concat( this.filteredItems );
        this.items = items.concat( this.items );
    };

    proto._filterRevealAdded = function( items ) {
        var filtered = this._filter( items );
        this.hide( filtered.needHide );
        // reveal all new items
        this.reveal( filtered.matches );
        // layout new items, no transition
        this.layoutItems( filtered.matches, true );
        return filtered.matches;
    };

    /**
     * Filter, sort, and layout newly-appended item elements
     * @param {Array or NodeList or Element} elems
     */
    proto.insert = function( elems ) {
        var items = this.addItems( elems );
        if ( !items.length ) {
            return;
        }
        // append item elements
        var i, item;
        var len = items.length;
        for ( i=0; i < len; i++ ) {
            item = items[i];
            this.element.appendChild( item.element );
        }
        // filter new stuff
        var filteredInsertItems = this._filter( items ).matches;
        // set flag
        for ( i=0; i < len; i++ ) {
            items[i].isLayoutInstant = true;
        }
        this.arrange();
        // reset flag
        for ( i=0; i < len; i++ ) {
            delete items[i].isLayoutInstant;
        }
        this.reveal( filteredInsertItems );
    };

    var _remove = proto.remove;
    proto.remove = function( elems ) {
        elems = utils.makeArray( elems );
        var removeItems = this.getItems( elems );
        // do regular thing
        _remove.call( this, elems );
        // bail if no items to remove
        var len = removeItems && removeItems.length;
        // remove elems from filteredItems
        for ( var i=0; len && i < len; i++ ) {
            var item = removeItems[i];
            // remove item from collection
            utils.removeFrom( this.filteredItems, item );
        }
    };

    proto.shuffle = function() {
        // update random sortData
        for ( var i=0; i < this.items.length; i++ ) {
            var item = this.items[i];
            item.sortData.random = Math.random();
        }
        this.options.sortBy = 'random';
        this._sort();
        this._layout();
    };

    /**
     * trigger fn without transition
     * kind of hacky to have this in the first place
     * @param {Function} fn
     * @param {Array} args
     * @returns ret
     * @private
     */
    proto._noTransition = function( fn, args ) {
        // save transitionDuration before disabling
        var transitionDuration = this.options.transitionDuration;
        // disable transition
        this.options.transitionDuration = 0;
        // do it
        var returnValue = fn.apply( this, args );
        // re-enable transition for reveal
        this.options.transitionDuration = transitionDuration;
        return returnValue;
    };

    // ----- helper methods ----- //

    /**
     * getter method for getting filtered item elements
     * @returns {Array} elems - collection of item elements
     */
    proto.getFilteredItemElements = function() {
        return this.filteredItems.map( function( item ) {
            return item.element;
        });
    };

    // -----  ----- //

    return Isotope;

}));








/*!
 * Packery layout mode PACKAGED v2.0.0
 * sub-classes Packery
 */

/**
 * Rect
 * low-level utility class for basic geometry
 */

( function( window, factory ) {
    // universal module definition
    /* jshint strict: false */ /* globals define, module */
    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( 'packery/js/rect',factory );
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory();
    } else {
        // browser global
        window.Packery = window.Packery || {};
        window.Packery.Rect = factory();
    }

}( window, function factory() {


// -------------------------- Rect -------------------------- //

    function Rect( props ) {
        // extend properties from defaults
        for ( var prop in Rect.defaults ) {
            this[ prop ] = Rect.defaults[ prop ];
        }

        for ( prop in props ) {
            this[ prop ] = props[ prop ];
        }

    }

    Rect.defaults = {
        x: 0,
        y: 0,
        width: 0,
        height: 0
    };

    var proto = Rect.prototype;

    /**
     * Determines whether or not this rectangle wholly encloses another rectangle or point.
     * @param {Rect} rect
     * @returns {Boolean}
     **/
    proto.contains = function( rect ) {
        // points don't have width or height
        var otherWidth = rect.width || 0;
        var otherHeight = rect.height || 0;
        return this.x <= rect.x &&
            this.y <= rect.y &&
            this.x + this.width >= rect.x + otherWidth &&
            this.y + this.height >= rect.y + otherHeight;
    };

    /**
     * Determines whether or not the rectangle intersects with another.
     * @param {Rect} rect
     * @returns {Boolean}
     **/
    proto.overlaps = function( rect ) {
        var thisRight = this.x + this.width;
        var thisBottom = this.y + this.height;
        var rectRight = rect.x + rect.width;
        var rectBottom = rect.y + rect.height;

        // http://stackoverflow.com/a/306332
        return this.x < rectRight &&
            thisRight > rect.x &&
            this.y < rectBottom &&
            thisBottom > rect.y;
    };

    /**
     * @param {Rect} rect - the overlapping rect
     * @returns {Array} freeRects - rects representing the area around the rect
     **/
    proto.getMaximalFreeRects = function( rect ) {

        // if no intersection, return false
        if ( !this.overlaps( rect ) ) {
            return false;
        }

        var freeRects = [];
        var freeRect;

        var thisRight = this.x + this.width;
        var thisBottom = this.y + this.height;
        var rectRight = rect.x + rect.width;
        var rectBottom = rect.y + rect.height;

        // top
        if ( this.y < rect.y ) {
            freeRect = new Rect({
                x: this.x,
                y: this.y,
                width: this.width,
                height: rect.y - this.y
            });
            freeRects.push( freeRect );
        }

        // right
        if ( thisRight > rectRight ) {
            freeRect = new Rect({
                x: rectRight,
                y: this.y,
                width: thisRight - rectRight,
                height: this.height
            });
            freeRects.push( freeRect );
        }

        // bottom
        if ( thisBottom > rectBottom ) {
            freeRect = new Rect({
                x: this.x,
                y: rectBottom,
                width: this.width,
                height: thisBottom - rectBottom
            });
            freeRects.push( freeRect );
        }

        // left
        if ( this.x < rect.x ) {
            freeRect = new Rect({
                x: this.x,
                y: this.y,
                width: rect.x - this.x,
                height: this.height
            });
            freeRects.push( freeRect );
        }

        return freeRects;
    };

    proto.canFit = function( rect ) {
        return this.width >= rect.width && this.height >= rect.height;
    };

    return Rect;

}));

/**
 * Packer
 * bin-packing algorithm
 */

( function( window, factory ) {
    // universal module definition
    /* jshint strict: false */ /* globals define, module, require */
    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( 'packery/js/packer',[ './rect' ], factory );
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory(
            require('./rect')
        );
    } else {
        // browser global
        var Packery = window.Packery = window.Packery || {};
        Packery.Packer = factory( Packery.Rect );
    }

}( window, function factory( Rect ) {


// -------------------------- Packer -------------------------- //

    /**
     * @param {Number} width
     * @param {Number} height
     * @param {String} sortDirection
     *   topLeft for vertical, leftTop for horizontal
     */
    function Packer( width, height, sortDirection ) {
        this.width = width || 0;
        this.height = height || 0;
        this.sortDirection = sortDirection || 'downwardLeftToRight';

        this.reset();
    }

    var proto = Packer.prototype;

    proto.reset = function() {
        this.spaces = [];

        var initialSpace = new Rect({
            x: 0,
            y: 0,
            width: this.width,
            height: this.height
        });

        this.spaces.push( initialSpace );
        // set sorter
        this.sorter = sorters[ this.sortDirection ] || sorters.downwardLeftToRight;
    };

// change x and y of rect to fit with in Packer's available spaces
    proto.pack = function( rect ) {
        for ( var i=0; i < this.spaces.length; i++ ) {
            var space = this.spaces[i];
            if ( space.canFit( rect ) ) {
                this.placeInSpace( rect, space );
                break;
            }
        }
    };

    proto.columnPack = function( rect ) {
        for ( var i=0; i < this.spaces.length; i++ ) {
            var space = this.spaces[i];
            var canFitInSpaceColumn = space.x <= rect.x &&
                space.x + space.width >= rect.x + rect.width &&
                space.height >= rect.height - 0.01; // fudge number for rounding error
            if ( canFitInSpaceColumn ) {
                rect.y = space.y;
                this.placed( rect );
                break;
            }
        }
    };

    proto.rowPack = function( rect ) {
        for ( var i=0; i < this.spaces.length; i++ ) {
            var space = this.spaces[i];
            var canFitInSpaceRow = space.y <= rect.y &&
                space.y + space.height >= rect.y + rect.height &&
                space.width >= rect.width - 0.01; // fudge number for rounding error
            if ( canFitInSpaceRow ) {
                rect.x = space.x;
                this.placed( rect );
                break;
            }
        }
    };

    proto.placeInSpace = function( rect, space ) {
        // place rect in space
        rect.x = space.x;
        rect.y = space.y;

        this.placed( rect );
    };

// update spaces with placed rect
    proto.placed = function( rect ) {
        // update spaces
        var revisedSpaces = [];
        for ( var i=0; i < this.spaces.length; i++ ) {
            var space = this.spaces[i];
            var newSpaces = space.getMaximalFreeRects( rect );
            // add either the original space or the new spaces to the revised spaces
            if ( newSpaces ) {
                revisedSpaces.push.apply( revisedSpaces, newSpaces );
            } else {
                revisedSpaces.push( space );
            }
        }

        this.spaces = revisedSpaces;

        this.mergeSortSpaces();
    };

    proto.mergeSortSpaces = function() {
        // remove redundant spaces
        Packer.mergeRects( this.spaces );
        this.spaces.sort( this.sorter );
    };

// add a space back
    proto.addSpace = function( rect ) {
        this.spaces.push( rect );
        this.mergeSortSpaces();
    };

// -------------------------- utility functions -------------------------- //

    /**
     * Remove redundant rectangle from array of rectangles
     * @param {Array} rects: an array of Rects
     * @returns {Array} rects: an array of Rects
     **/
    Packer.mergeRects = function( rects ) {
        var i = 0;
        var rect = rects[i];

        rectLoop:
            while ( rect ) {
                var j = 0;
                var compareRect = rects[ i + j ];

                while ( compareRect ) {
                    if  ( compareRect == rect ) {
                        j++; // next
                    } else if ( compareRect.contains( rect ) ) {
                        // remove rect
                        rects.splice( i, 1 );
                        rect = rects[i]; // set next rect
                        continue rectLoop; // bail on compareLoop
                    } else if ( rect.contains( compareRect ) ) {
                        // remove compareRect
                        rects.splice( i + j, 1 );
                    } else {
                        j++;
                    }
                    compareRect = rects[ i + j ]; // set next compareRect
                }
                i++;
                rect = rects[i];
            }

        return rects;
    };


// -------------------------- sorters -------------------------- //

// functions for sorting rects in order
    var sorters = {
        // top down, then left to right
        downwardLeftToRight: function( a, b ) {
            return a.y - b.y || a.x - b.x;
        },
        // left to right, then top down
        rightwardTopToBottom: function( a, b ) {
            return a.x - b.x || a.y - b.y;
        }
    };


// --------------------------  -------------------------- //

    return Packer;

}));

/**
 * Packery Item Element
 **/

( function( window, factory ) {
    // universal module definition
    /* jshint strict: false */ /* globals define, module, require */
    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( 'packery/js/item',[
                'outlayer/outlayer',
                './rect'
            ],
            factory );
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory(
            require('outlayer'),
            require('./rect')
        );
    } else {
        // browser global
        window.Packery.Item = factory(
            window.Outlayer,
            window.Packery.Rect
        );
    }

}( window, function factory( Outlayer, Rect ) {


// -------------------------- Item -------------------------- //

    var docElemStyle = document.documentElement.style;

    var transformProperty = typeof docElemStyle.transform == 'string' ?
        'transform' : 'WebkitTransform';

// sub-class Item
    var Item = function PackeryItem() {
        Outlayer.Item.apply( this, arguments );
    };

    var proto = Item.prototype = Object.create( Outlayer.Item.prototype );

    var __create = proto._create;
    proto._create = function() {
        // call default _create logic
        __create.call( this );
        this.rect = new Rect();
    };

    var _moveTo = proto.moveTo;
    proto.moveTo = function( x, y ) {
        // don't shift 1px while dragging
        var dx = Math.abs( this.position.x - x );
        var dy = Math.abs( this.position.y - y );

        var canHackGoTo = this.layout.dragItemCount && !this.isPlacing &&
            !this.isTransitioning && dx < 1 && dy < 1;
        if ( canHackGoTo ) {
            this.goTo( x, y );
            return;
        }
        _moveTo.apply( this, arguments );
    };

// -------------------------- placing -------------------------- //

    proto.enablePlacing = function() {
        this.removeTransitionStyles();
        // remove transform property from transition
        if ( this.isTransitioning && transformProperty ) {
            this.element.style[ transformProperty ] = 'none';
        }
        this.isTransitioning = false;
        this.getSize();
        this.layout._setRectSize( this.element, this.rect );
        this.isPlacing = true;
    };

    proto.disablePlacing = function() {
        this.isPlacing = false;
    };

// -----  ----- //

// remove element from DOM
    proto.removeElem = function() {
        this.element.parentNode.removeChild( this.element );
        // add space back to packer
        this.layout.packer.addSpace( this.rect );
        this.emitEvent( 'remove', [ this ] );
    };

// ----- dropPlaceholder ----- //

    proto.showDropPlaceholder = function() {
        var dropPlaceholder = this.dropPlaceholder;
        if ( !dropPlaceholder ) {
            // create dropPlaceholder
            dropPlaceholder = this.dropPlaceholder = document.createElement('div');
            dropPlaceholder.className = 'packery-drop-placeholder';
            dropPlaceholder.style.position = 'absolute';
        }

        dropPlaceholder.style.width = this.size.width + 'px';
        dropPlaceholder.style.height = this.size.height + 'px';
        this.positionDropPlaceholder();
        this.layout.element.appendChild( dropPlaceholder );
    };

    proto.positionDropPlaceholder = function() {
        this.dropPlaceholder.style[ transformProperty ] = 'translate(' +
            this.rect.x + 'px, ' + this.rect.y + 'px)';
    };

    proto.hideDropPlaceholder = function() {
        this.layout.element.removeChild( this.dropPlaceholder );
    };

// -----  ----- //

    return Item;

}));

/*!
 * Packery v2.0.0
 * Gapless, draggable grid layouts
 *
 * Licensed GPLv3 for open source use
 * or Packery Commercial License for commercial use
 *
 * http://packery.metafizzy.co
 * Copyright 2016 Metafizzy
 */

( function( window, factory ) {
    // universal module definition
    /* jshint strict: false */ /* globals define, module, require */
    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( 'packery/js/packery',[
                'get-size/get-size',
                'outlayer/outlayer',
                './rect',
                './packer',
                './item'
            ],
            factory );
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory(
            require('get-size'),
            require('outlayer'),
            require('./rect'),
            require('./packer'),
            require('./item')
        );
    } else {
        // browser global
        window.Packery = factory(
            window.getSize,
            window.Outlayer,
            window.Packery.Rect,
            window.Packery.Packer,
            window.Packery.Item
        );
    }

}( window, function factory( getSize, Outlayer, Rect, Packer, Item ) {


// ----- Rect ----- //

// allow for pixel rounding errors IE8-IE11 & Firefox; #227
    Rect.prototype.canFit = function( rect ) {
        return this.width >= rect.width - 1 && this.height >= rect.height - 1;
    };

// -------------------------- Packery -------------------------- //

// create an Outlayer layout class
    var Packery = Outlayer.create('packery');
    Packery.Item = Item;

    var proto = Packery.prototype;

    proto._create = function() {
        // call super
        Outlayer.prototype._create.call( this );

        // initial properties
        this.packer = new Packer();
        // packer for drop targets
        this.shiftPacker = new Packer();
        this.isEnabled = true;

        this.dragItemCount = 0;

        // create drag handlers
        var _this = this;
        this.handleDraggabilly = {
            dragStart: function() {
                _this.itemDragStart( this.element );
            },
            dragMove: function() {
                _this.itemDragMove( this.element, this.position.x, this.position.y );
            },
            dragEnd: function() {
                _this.itemDragEnd( this.element );
            }
        };

        this.handleUIDraggable = {
            start: function handleUIDraggableStart( event, ui ) {
                // HTML5 may trigger dragstart, dismiss HTML5 dragging
                if ( !ui ) {
                    return;
                }
                _this.itemDragStart( event.currentTarget );
            },
            drag: function handleUIDraggableDrag( event, ui ) {
                if ( !ui ) {
                    return;
                }
                _this.itemDragMove( event.currentTarget, ui.position.left, ui.position.top );
            },
            stop: function handleUIDraggableStop( event, ui ) {
                if ( !ui ) {
                    return;
                }
                _this.itemDragEnd( event.currentTarget );
            }
        };

    };


// ----- init & layout ----- //

    /**
     * logic before any new layout
     */
    proto._resetLayout = function() {
        this.getSize();

        this._getMeasurements();

        // reset packer
        var width, height, sortDirection;
        // packer settings, if horizontal or vertical
        if ( this._getOption('horizontal') ) {
            width = Infinity;
            height = this.size.innerHeight + this.gutter;
            sortDirection = 'rightwardTopToBottom';
        } else {
            width = this.size.innerWidth + this.gutter;
            height = Infinity;
            sortDirection = 'downwardLeftToRight';
        }

        this.packer.width = this.shiftPacker.width = width;
        this.packer.height = this.shiftPacker.height = height;
        this.packer.sortDirection = this.shiftPacker.sortDirection = sortDirection;

        this.packer.reset();

        // layout
        this.maxY = 0;
        this.maxX = 0;
    };

    /**
     * update columnWidth, rowHeight, & gutter
     * @private
     */
    proto._getMeasurements = function() {
        this._getMeasurement( 'columnWidth', 'width' );
        this._getMeasurement( 'rowHeight', 'height' );
        this._getMeasurement( 'gutter', 'width' );
    };

    proto._getItemLayoutPosition = function( item ) {
        this._setRectSize( item.element, item.rect );
        if ( this.isShifting || this.dragItemCount > 0 ) {
            var packMethod = this._getPackMethod();
            this.packer[ packMethod ]( item.rect );
        } else {
            this.packer.pack( item.rect );
        }

        this._setMaxXY( item.rect );
        return item.rect;
    };

    proto.shiftLayout = function() {
        this.isShifting = true;
        this.layout();
        delete this.isShifting;
    };

    proto._getPackMethod = function() {
        return this._getOption('horizontal') ? 'rowPack' : 'columnPack';
    };


    /**
     * set max X and Y value, for size of container
     * @param {Packery.Rect} rect
     * @private
     */
    proto._setMaxXY = function( rect ) {
        this.maxX = Math.max( rect.x + rect.width, this.maxX );
        this.maxY = Math.max( rect.y + rect.height, this.maxY );
    };

    /**
     * set the width and height of a rect, applying columnWidth and rowHeight
     * @param {Element} elem
     * @param {Packery.Rect} rect
     */
    proto._setRectSize = function( elem, rect ) {
        var size = getSize( elem );
        var w = size.outerWidth;
        var h = size.outerHeight;
        // size for columnWidth and rowHeight, if available
        // only check if size is non-zero, #177
        if ( w || h ) {
            w = this._applyGridGutter( w, this.columnWidth );
            h = this._applyGridGutter( h, this.rowHeight );
        }
        // rect must fit in packer
        rect.width = Math.min( w, this.packer.width );
        rect.height = Math.min( h, this.packer.height );
    };

    /**
     * fits item to columnWidth/rowHeight and adds gutter
     * @param {Number} measurement - item width or height
     * @param {Number} gridSize - columnWidth or rowHeight
     * @returns measurement
     */
    proto._applyGridGutter = function( measurement, gridSize ) {
        // just add gutter if no gridSize
        if ( !gridSize ) {
            return measurement + this.gutter;
        }
        gridSize += this.gutter;
        // fit item to columnWidth/rowHeight
        var remainder = measurement % gridSize;
        var mathMethod = remainder && remainder < 1 ? 'round' : 'ceil';
        measurement = Math[ mathMethod ]( measurement / gridSize ) * gridSize;
        return measurement;
    };

    proto._getContainerSize = function() {
        if ( this._getOption('horizontal') ) {
            return {
                width: this.maxX - this.gutter
            };
        } else {
            return {
                height: this.maxY - this.gutter
            };
        }
    };


// -------------------------- stamp -------------------------- //

    /**
     * makes space for element
     * @param {Element} elem
     */
    proto._manageStamp = function( elem ) {

        var item = this.getItem( elem );
        var rect;
        if ( item && item.isPlacing ) {
            rect = item.rect;
        } else {
            var offset = this._getElementOffset( elem );
            rect = new Rect({
                x: this._getOption('originLeft') ? offset.left : offset.right,
                y: this._getOption('originTop') ? offset.top : offset.bottom
            });
        }

        this._setRectSize( elem, rect );
        // save its space in the packer
        this.packer.placed( rect );
        this._setMaxXY( rect );
    };

// -------------------------- methods -------------------------- //

    function verticalSorter( a, b ) {
        return a.position.y - b.position.y || a.position.x - b.position.x;
    }

    function horizontalSorter( a, b ) {
        return a.position.x - b.position.x || a.position.y - b.position.y;
    }

    proto.sortItemsByPosition = function() {
        var sorter = this._getOption('horizontal') ? horizontalSorter : verticalSorter;
        this.items.sort( sorter );
    };

    /**
     * Fit item element in its current position
     * Packery will position elements around it
     * useful for expanding elements
     *
     * @param {Element} elem
     * @param {Number} x - horizontal destination position, optional
     * @param {Number} y - vertical destination position, optional
     */
    proto.fit = function( elem, x, y ) {
        var item = this.getItem( elem );
        if ( !item ) {
            return;
        }

        // stamp item to get it out of layout
        this.stamp( item.element );
        // set placing flag
        item.enablePlacing();
        this.updateShiftTargets( item );
        // fall back to current position for fitting
        x = x === undefined ? item.rect.x: x;
        y = y === undefined ? item.rect.y: y;
        // position it best at its destination
        this.shift( item, x, y );
        this._bindFitEvents( item );
        item.moveTo( item.rect.x, item.rect.y );
        // layout everything else
        this.shiftLayout();
        // return back to regularly scheduled programming
        this.unstamp( item.element );
        this.sortItemsByPosition();
        item.disablePlacing();
    };

    /**
     * emit event when item is fit and other items are laid out
     * @param {Packery.Item} item
     * @private
     */
    proto._bindFitEvents = function( item ) {
        var _this = this;
        var ticks = 0;
        function onLayout() {
            ticks++;
            if ( ticks != 2 ) {
                return;
            }
            _this.dispatchEvent( 'fitComplete', null, [ item ] );
        }
        // when item is laid out
        item.once( 'layout', onLayout );
        // when all items are laid out
        this.once( 'layoutComplete', onLayout );
    };

// -------------------------- resize -------------------------- //

// debounced, layout on resize
    proto.resize = function() {
        // don't trigger if size did not change
        // or if resize was unbound. See #285, outlayer#9
        if ( !this.isResizeBound || !this.needsResizeLayout() ) {
            return;
        }

        if ( this.options.shiftPercentResize ) {
            this.resizeShiftPercentLayout();
        } else {
            this.layout();
        }
    };

    /**
     * check if layout is needed post layout
     * @returns Boolean
     */
    proto.needsResizeLayout = function() {
        var size = getSize( this.element );
        var innerSize = this._getOption('horizontal') ? 'innerHeight' : 'innerWidth';
        return size[ innerSize ] != this.size[ innerSize ];
    };

    proto.resizeShiftPercentLayout = function() {
        var items = this._getItemsForLayout( this.items );

        var isHorizontal = this._getOption('horizontal');
        var coord = isHorizontal ? 'y' : 'x';
        var measure = isHorizontal ? 'height' : 'width';
        var segmentName = isHorizontal ? 'rowHeight' : 'columnWidth';
        var innerSize = isHorizontal ? 'innerHeight' : 'innerWidth';

        // proportional re-align items
        var previousSegment = this[ segmentName ];
        previousSegment = previousSegment && previousSegment + this.gutter;

        if ( previousSegment ) {
            this._getMeasurements();
            var currentSegment = this[ segmentName ] + this.gutter;
            items.forEach( function( item ) {
                var seg = Math.round( item.rect[ coord ] / previousSegment );
                item.rect[ coord ] = seg * currentSegment;
            });
        } else {
            var currentSize = getSize( this.element )[ innerSize ] + this.gutter;
            var previousSize = this.packer[ measure ];
            items.forEach( function( item ) {
                item.rect[ coord ] = ( item.rect[ coord ] / previousSize ) * currentSize;
            });
        }

        this.shiftLayout();
    };

// -------------------------- drag -------------------------- //

    /**
     * handle an item drag start event
     * @param {Element} elem
     */
    proto.itemDragStart = function( elem ) {
        if ( !this.isEnabled ) {
            return;
        }
        this.stamp( elem );
        // this.ignore( elem );
        var item = this.getItem( elem );
        if ( !item ) {
            return;
        }

        item.enablePlacing();
        item.showDropPlaceholder();
        this.dragItemCount++;
        this.updateShiftTargets( item );
    };

    proto.updateShiftTargets = function( dropItem ) {
        this.shiftPacker.reset();

        // pack stamps
        this._getBoundingRect();
        var isOriginLeft = this._getOption('originLeft');
        var isOriginTop = this._getOption('originTop');
        this.stamps.forEach( function( stamp ) {
            // ignore dragged item
            var item = this.getItem( stamp );
            if ( item && item.isPlacing ) {
                return;
            }
            var offset = this._getElementOffset( stamp );
            var rect = new Rect({
                x: isOriginLeft ? offset.left : offset.right,
                y: isOriginTop ? offset.top : offset.bottom
            });
            this._setRectSize( stamp, rect );
            // save its space in the packer
            this.shiftPacker.placed( rect );
        }, this );

        // reset shiftTargets
        var isHorizontal = this._getOption('horizontal');
        var segmentName = isHorizontal ? 'rowHeight' : 'columnWidth';
        var measure = isHorizontal ? 'height' : 'width';

        this.shiftTargetKeys = [];
        this.shiftTargets = [];
        var boundsSize;
        var segment = this[ segmentName ];
        segment = segment && segment + this.gutter;

        if ( segment ) {
            var segmentSpan = Math.ceil( dropItem.rect[ measure ] / segment );
            var segs = Math.floor( ( this.shiftPacker[ measure ] + this.gutter ) / segment );
            boundsSize = ( segs - segmentSpan ) * segment;
            // add targets on top
            for ( var i=0; i < segs; i++ ) {
                this._addShiftTarget( i * segment, 0, boundsSize );
            }
        } else {
            boundsSize = ( this.shiftPacker[ measure ] + this.gutter ) - dropItem.rect[ measure ];
            this._addShiftTarget( 0, 0, boundsSize );
        }

        // pack each item to measure where shiftTargets are
        var items = this._getItemsForLayout( this.items );
        var packMethod = this._getPackMethod();
        items.forEach( function( item ) {
            var rect = item.rect;
            this._setRectSize( item.element, rect );
            this.shiftPacker[ packMethod ]( rect );

            // add top left corner
            this._addShiftTarget( rect.x, rect.y, boundsSize );
            // add bottom left / top right corner
            var cornerX = isHorizontal ? rect.x + rect.width : rect.x;
            var cornerY = isHorizontal ? rect.y : rect.y + rect.height;
            this._addShiftTarget( cornerX, cornerY, boundsSize );

            if ( segment ) {
                // add targets for each column on bottom / row on right
                var segSpan = Math.round( rect[ measure ] / segment );
                for ( var i=1; i < segSpan; i++ ) {
                    var segX = isHorizontal ? cornerX : rect.x + segment * i;
                    var segY = isHorizontal ? rect.y + segment * i : cornerY;
                    this._addShiftTarget( segX, segY, boundsSize );
                }
            }
        }, this );

    };

    proto._addShiftTarget = function( x, y, boundsSize ) {
        var checkCoord = this._getOption('horizontal') ? y : x;
        if ( checkCoord !== 0 && checkCoord > boundsSize ) {
            return;
        }
        // create string for a key, easier to keep track of what targets
        var key = x + ',' + y;
        var hasKey = this.shiftTargetKeys.indexOf( key ) != -1;
        if ( hasKey ) {
            return;
        }
        this.shiftTargetKeys.push( key );
        this.shiftTargets.push({ x: x, y: y });
    };

// -------------------------- drop -------------------------- //

    proto.shift = function( item, x, y ) {
        var shiftPosition;
        var minDistance = Infinity;
        var position = { x: x, y: y };
        this.shiftTargets.forEach( function( target ) {
            var distance = getDistance( target, position );
            if ( distance < minDistance ) {
                shiftPosition = target;
                minDistance = distance;
            }
        });
        item.rect.x = shiftPosition.x;
        item.rect.y = shiftPosition.y;
    };

    function getDistance( a, b ) {
        var dx = b.x - a.x;
        var dy = b.y - a.y;
        return Math.sqrt( dx * dx + dy * dy );
    }

// -------------------------- drag move -------------------------- //

    var DRAG_THROTTLE_TIME = 120;

    /**
     * handle an item drag move event
     * @param {Element} elem
     * @param {Number} x - horizontal change in position
     * @param {Number} y - vertical change in position
     */
    proto.itemDragMove = function( elem, x, y ) {
        var item = this.isEnabled && this.getItem( elem );
        if ( !item ) {
            return;
        }

        x -= this.size.paddingLeft;
        y -= this.size.paddingTop;

        var _this = this;
        function onDrag() {
            _this.shift( item, x, y );
            item.positionDropPlaceholder();
            _this.layout();
        }

        // throttle
        var now = new Date();
        if ( this._itemDragTime && now - this._itemDragTime < DRAG_THROTTLE_TIME ) {
            clearTimeout( this.dragTimeout );
            this.dragTimeout = setTimeout( onDrag, DRAG_THROTTLE_TIME );
        } else {
            onDrag();
            this._itemDragTime = now;
        }
    };

// -------------------------- drag end -------------------------- //

    /**
     * handle an item drag end event
     * @param {Element} elem
     */
    proto.itemDragEnd = function( elem ) {
        var item = this.isEnabled && this.getItem( elem );
        if ( !item ) {
            return;
        }

        clearTimeout( this.dragTimeout );
        item.element.classList.add('is-positioning-post-drag');

        var completeCount = 0;
        var _this = this;
        function onDragEndLayoutComplete() {
            completeCount++;
            if ( completeCount != 2 ) {
                return;
            }
            // reset drag item
            item.element.classList.remove('is-positioning-post-drag');
            item.hideDropPlaceholder();
            _this.dispatchEvent( 'dragItemPositioned', null, [ item ] );
        }

        item.once( 'layout', onDragEndLayoutComplete );
        this.once( 'layoutComplete', onDragEndLayoutComplete );
        item.moveTo( item.rect.x, item.rect.y );
        this.layout();
        this.dragItemCount = Math.max( 0, this.dragItemCount - 1 );
        this.sortItemsByPosition();
        item.disablePlacing();
        this.unstamp( item.element );
    };

    /**
     * binds Draggabilly events
     * @param {Draggabilly} draggie
     */
    proto.bindDraggabillyEvents = function( draggie ) {
        this._bindDraggabillyEvents( draggie, 'on' );
    };

    proto.unbindDraggabillyEvents = function( draggie ) {
        this._bindDraggabillyEvents( draggie, 'off' );
    };

    proto._bindDraggabillyEvents = function( draggie, method ) {
        var handlers = this.handleDraggabilly;
        draggie[ method ]( 'dragStart', handlers.dragStart );
        draggie[ method ]( 'dragMove', handlers.dragMove );
        draggie[ method ]( 'dragEnd', handlers.dragEnd );
    };

    /**
     * binds jQuery UI Draggable events
     * @param {jQuery} $elems
     */
    proto.bindUIDraggableEvents = function( $elems ) {
        this._bindUIDraggableEvents( $elems, 'on' );
    };

    proto.unbindUIDraggableEvents = function( $elems ) {
        this._bindUIDraggableEvents( $elems, 'off' );
    };

    proto._bindUIDraggableEvents = function( $elems, method ) {
        var handlers = this.handleUIDraggable;
        $elems
            [ method ]( 'dragstart', handlers.start )
            [ method ]( 'drag', handlers.drag )
            [ method ]( 'dragstop', handlers.stop );
    };

// ----- destroy ----- //

    var _destroy = proto.destroy;
    proto.destroy = function() {
        _destroy.apply( this, arguments );
        // disable flag; prevent drag events from triggering. #72
        this.isEnabled = false;
    };

// -----  ----- //

    Packery.Rect = Rect;
    Packery.Packer = Packer;

    return Packery;

}));

/*!
 * Packery layout mode v2.0.0
 * sub-classes Packery
 */

/*jshint browser: true, strict: true, undef: true, unused: true */

( function( window, factory ) {

    // universal module definition
    if ( typeof define == 'function' && define.amd ) {
        // AMD
        define( [
                'isotope/js/layout-mode',
                'packery/js/packery'
            ],
            factory );
    } else if ( typeof module == 'object' && module.exports ) {
        // CommonJS
        module.exports = factory(
            require('isotope-layout/js/layout-mode'),
            require('packery')
        );
    } else {
        // browser global
        factory(
            window.Isotope.LayoutMode,
            window.Packery
        );
    }

}( window, function factor( LayoutMode, Packery ) {


    // create an Outlayer layout class
    var PackeryMode = LayoutMode.create('packery');
    var proto = PackeryMode.prototype;

    var keepModeMethods = {
        _getElementOffset: true,
        _getMeasurement: true
    };

    // inherit Packery prototype
    for ( var method in Packery.prototype ) {
        // do not inherit mode methods
        if ( !keepModeMethods[ method ] ) {
            proto[ method ] = Packery.prototype[ method ];
        }
    }

    // set packer in _resetLayout
    var _resetLayout = proto._resetLayout;
    proto._resetLayout = function() {
        this.packer = this.packer || new Packery.Packer();
        this.shiftPacker = this.shiftPacker || new Packery.Packer();
        _resetLayout.apply( this, arguments );
    };

    var _getItemLayoutPosition = proto._getItemLayoutPosition;
    proto._getItemLayoutPosition = function( item ) {
        // set packery rect
        item.rect = item.rect || new Packery.Rect();
        return _getItemLayoutPosition.call( this, item );
    };

    // needsResizeLayout for vertical or horizontal
    var _needsResizeLayout = proto.needsResizeLayout;
    proto.needsResizeLayout = function() {
        if ( this._getOption('horizontal') ) {
            return this.needsVerticalResizeLayout();
        } else {
            return _needsResizeLayout.call( this );
        }
    };

    // point to mode options for horizontal
    var _getOption = proto._getOption;
    proto._getOption = function( option ) {
        if ( option == 'horizontal' ) {
            return this.options.isHorizontal !== undefined ?
                this.options.isHorizontal : this.options.horizontal;
        }
        return _getOption.apply( this.isotope, arguments );
    };

    return PackeryMode;

}));