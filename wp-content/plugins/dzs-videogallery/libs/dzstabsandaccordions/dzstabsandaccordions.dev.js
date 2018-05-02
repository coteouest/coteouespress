// ==DZS ZoomTabs and Accordions
// @version 1.23
// @this is not free software
// == DZS ZoomTabs and Accordions == copyright == http://digitalzoomstudio.net


"use strict";

Object.size = function(obj) {
    var size = 0, key;
    for (key in obj) {
        if (obj.hasOwnProperty(key)) size++;
    }
    return size;
};
if(window.jQuery==undefined){
    alert("dzstabs.js -> jQuery is not defined or improperly declared ( must be included at the start of the head tag ), you need jQuery for this plugin");
}
jQuery.fn.outerHTML = function(e) {
    return e
        ? this.before(e).remove()
        : jQuery("<p>").append(this.eq(0).clone()).html();
};

window.dzstaa_self_options = {};

var settings_dzstabs = { animation_time: 300, animation_easing:'easeOutCirc' };
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


    $.fn.dzstabsandaccordions = function(o) {

        //==default options
        var defaults = {
            settings_slideshowTime : '5' //in seconds
            ,settings_enable_linking : 'off' // enable deeplinking on tabs
            , settings_contentHeight : '0'//set the fixed tab height
            , settings_scroll_to_start : 'off'//scroll to start when a tab menu is clicked
            , settings_startTab : 'default'// -- the start tab, default or a fixed number
            , design_skin : 'skin-default' // -- skin-default, skin-boxed, skin-melbourne or skin-blue
            , design_transition : 'default' // default, fade or slide
            , design_tabsposition : 'top' // -- set top, right, bottom or left
            , design_tabswidth : 'default' // -- set the tabs width for position left or right, if tabs position top or bottom and this is set to fullwidth, then the tabs will cover all the width
            , design_maxwidth : '4000'
            ,settings_makeFunctional: false
            ,settings_appendWholeContent: false // -- take the whole tab content and append it into the dzs tabs, this makes complex scripts like sliders still work inside of tabs
            ,toggle_breakpoint: '320' //  -- a number at which bellow the tabs will trasform to toggles
            ,toggle_type: 'accordion' // -- normally, the  toggles act like accordions, but they can act like traditional toggles if this is set to toggle
            ,refresh_tab_height: '0' // -- normally, the  toggles act like accordions, but they can act like traditional toggles if this is set to toggle
            ,outer_menu: null // -- normally, the  toggles act like accordions, but they can act like traditional toggles if this is set to toggle
            ,action_gotoItem: null // -- set a external javascript action that happens when a item is selected
            ,vc_editable: false // -- add some extra classes for the visual composer frontend edit

        };

//        console.info(this, o);

        if(typeof o =='undefined'){
            if(typeof $(this).attr('data-options')!='undefined'  && $(this).attr('data-options')!=''){
                var aux = $(this).attr('data-options');
                aux = 'window.dzstaa_self_options = ' + aux;
                eval(aux);
                o = $.extend({}, window.dzstaa_self_options);
                window.dzstaa_self_options = $.extend({},{});
            }
        }
        o = $.extend(defaults, o);
        this.each( function(){
            var cthis = $(this)
                , cclass = ''
                ,cid = ''
                ;
            var nrChildren= 0 ;
            var currNr=-1
                ,currNrEx=-1
                ;
            var mem_children = [];
            var _tabsMenu
                ,_tabsContent
                ,items
                ,_c
                ,_carg
                ;
            var i=0;
            var ww
                ,wh
                ,tw
                ,targeth
                ,padding_content = 20
                ;
            var busy_transition=false
                ,vc_feed_from = false // -- feed from visual composer
                ;
            var handled = false; //describes if all loaded function has been called

            var preloading_nrtotalimages = 0
                ,preloading_nrtotalimagesloaded = 0
                ;

            var animation_slide_vars = {
                'duration' : 300
                ,'queue' : false
            }

            var current_mode = 'tab';


            if(isNaN(Number(o.settings_startTab))==false){
                o.settings_startTab = parseInt(o.settings_startTab, 10);
            }

            if(can_history_api()==false){
                o.settings_enable_linking = 'off';
            }

            o.toggle_breakpoint = parseInt(o.toggle_breakpoint, 10);

            init();
            function init(){

                if(handled==true || cthis.hasClass('dzstaa-loaded')){
                    reinit();
                    return;
                }

                if(typeof(cthis.attr('class')) == 'string'){
                    cclass = cthis.attr('class');
                }else{
                    cclass=cthis.get(0).className;
                }




                cid = cthis.attr('id');
                if(typeof cid=='undefined' || cid==''){
                    var auxnr = 0;
                    var temps = 'dzs-tabs'+auxnr;

                    while($('#'+temps).length>0){
                        auxnr++;
                        temps = 'dzs-tabs'+auxnr;
                    }

                    cid = temps;
                    cthis.attr('id', cid);
                }



                if(cclass.indexOf('skin-')==-1){
                    cthis.addClass(o.design_skin);
                }


                if(o.design_transition=='default'){
                    o.design_transition='fade';
                }

                if(o.design_tabswidth=='default'){
                    if(o.design_tabsposition=='left' || o.design_tabsposition=='right'){
                        o.design_tabswidth = 'auto';
                    }else{
                        o.design_tabswidth = 'auto';
                    }

                }



                cthis.addClass('transition-'+ o.design_transition);
                cthis.addClass('tabs-'+ o.design_tabsposition);

//                cthis.addClass('toggle_type-'+ o.toggle_type);
                if(o.design_tabsposition=='bottom'){
                    cthis.appendOnce('<div class="tabs-content"></div>');
                    cthis.appendOnce('<div class="tabs-menu"></div>');
                }else{


                    var aux = '<div class="tabs-menu ';



                    if(o.vc_editable){

                        aux+='vc_tta-tabs-list';
                    }

                    aux+='"></div>';


                    cthis.appendOnce(aux);

                    var aux2 = '<div class="tabs-content';

                    if(o.vc_editable){
                        aux2+=' vc_tta-panels';
                    }

                    aux2+='"></div>';

                    cthis.appendOnce(aux2);

                }



                _tabsMenu = cthis.children('.tabs-menu').eq(0);
                _tabsContent = cthis.children('.tabs-content').eq(0);

                if(o.design_tabsposition=='none'){
                    _tabsMenu.hide();
                }

                if(o.outer_menu){
                    _tabsMenu = o.outer_menu;

                    _tabsMenu = _tabsMenu.eq(0);
                }


                cthis.get(0).api_set_action_gotoItem = function(arg){
                    o.action_gotoItem = arg;
                };


                reinit();

            }

            function reinit(){
                // nrChildren = cthis.children('.dzs-tab-tobe').length;


                if(cthis.children('.vc_tta-panel').length){
                    vc_feed_from=true;
                }

                // console.warn(vc_feed_from);



                var selector = '.dzs-tab-tobe:not(.processed)';


                if(vc_feed_from){
                    selector='.vc_tta-panel:not(.processed)';
                }

                var i5 = 0;

                cthis.children(selector).each(function(){
                    var _t = $(this);



                    var aux_tab_menu = '<div class="tab-menu-con';

                    if(o.vc_editable){
                        aux_tab_menu+=' vc_tta-tab ui-sortable-handle ';
                    }

                    if(_t.hasClass('is-always-active')){
                        aux_tab_menu += ' active is-always-active';
                    }

                    if(_t.hasClass('tab-disabled')){
                        aux_tab_menu += ' tab-disabled';
                    }


                    var tab_menu_html = _t.children('.tab-menu').html();

                    if(vc_feed_from){
                        tab_menu_html = _t.find('.vc_tta-panel-title').eq(0).html();
                    }







                    aux_tab_menu+='"';






                    if(o.vc_editable){

                        var target_id = '';

                        if(_t.attr('data-target-id')){
                            target_id = _t.attr('data-target-id');
                        }
                        if(_t.children().eq(0).attr('id')){
                            target_id = _t.children().eq(0).attr('id');
                        }

                        aux_tab_menu+=' data-vc-target-model-id="'+_t.attr('data-model-id')+'"';
                        aux_tab_menu+=' data-initial-sort="'+i5+'"';

                        if(target_id){
                            aux_tab_menu+=' data-target-id="'+target_id+'"';
                        }

                    }

                    aux_tab_menu+='"><div class="tab-menu">';



                    if(cthis.hasClass('skin-blue')){

                        aux_tab_menu+='<svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="20px"	 height="20px" viewBox="0 0 20 20" enable-background="new 0 0 20 20" xml:space="preserve"><g id="Layer_1">	<circle fill="#65398E" cx="10" cy="9.998" r="10"/></g><g id="Layer_3">	<rect x="3.817" y="8.69" fill="#FFFFFF" width="12.366" height="2.152"/>	<rect class="rect-2" x="8.924" y="3.583" fill="#FFFFFF" width="2.152" height="12.366"/></g></svg>';
                    }


                    aux_tab_menu+='<div class="the-label">'+tab_menu_html+'</div></div><div class="tab-menu-content-con" style="height:0;"><div class="tab-menu-content">';





                    var tab_content_extra_attr = '';
                    var tab_content_extra_class = '';

                    if(o.vc_editable){
                        tab_content_extra_class = ' vc_element vc_vc_tta_section vc_container-block vc_tta-panel';
                        tab_content_extra_attr = '  data-tag="vc_tta_section" data-shortcode-controls=\'["add","edit","clone","delete"]\' data-model-id="'+_t.attr('data-model-id')+'" data-vc-content=".vc_tta-panel-body"';
                    }


                    if(o.settings_appendWholeContent){
                        //if(cthis.hasClass('debug-target')){ console.info(_t.children('.tab-content')); }

                        if(vc_feed_from){
                            // _tabsContent.append(_t.find('.vc_tta-panel-body').eq(0));

                            // _tabsContent.append('<div class="tab-content'+tab_content_extra_class+'" '+tab_content_extra_attr+'></div>');

                            // console.info(_t);
                            // _tabsContent.children('.tab-content').last().append(_t);
                            // _tabsContent.children('.tab-content').last().append(_t.find('.vc_tta-panel-body').eq(0));




                            _t.addClass('tab-content');
                            // console.info(_t.get(0).outerHTML);
                            _tabsContent.append(_t);
                            _tabsContent.children().last().attr('data-tab-index', i5);
                            // console.info(_tabsContent.children().last());
                            _tabsContent.children().last().addClass('tab-content '+tab_content_extra_class);

                            if(_tabsContent.children().last().children().eq(0).attr('id')){
                                _tabsContent.children().last().attr('data-target-id', _tabsContent.children().last().children().eq(0).attr('id'));
                            }


                            _tabsContent.children().last().find('.vc_tta-panel-heading').remove();

                            // return false;
                        }else{

                            _tabsContent.append(_t.children('.tab-content'));
                            _tabsContent.children().last().attr('data-tab-index', i5);
                        }


                    }else{

                        var tab_content_html = _t.children('.tab-content').html();

                        if(vc_feed_from){
                            tab_content_html = _t.find('.vc_tta-panel-body').eq(0).html();
                        }

                        _tabsContent.append('<div class="tab-content" data-tab-index="'+i5+'">'+tab_content_html+'</div>');
                        aux_tab_menu+=tab_content_html;
                    }
                    aux_tab_menu += '</div></div></div>';

                    if(!o.outer_menu){

                        _tabsMenu.append(aux_tab_menu);
                    }


                    if(_tabsContent.find('.dzs-tabs').length>0){
                        _tabsContent.find('.dzs-tabs').eq(0).dzstabsandaccordions();
                    }


                    // _t.html(''); // -- beta test
                    _t.addClass('processed');


                    i5++;

                    nrChildren++;
                });

                // return false;



                if(cthis.children('.needs-loading').length>0){
                    cthis.children('.needs-loading').each(function(){
                        var _t = $(this);

                        var toload = _t.find('img').eq(0).get(0);

                        if(toload==undefined){
                            loadedImage();
                        }else{
                            if(toload.complete==true && toload.naturalWidth != 0){
                                loadedImage();
                            }else{
                                $(toload).bind('load', loadedImage);
                            }
                        }
                    });
                    setTimeout(handleLoaded, 5000);
                }else{
                    handleLoaded();
                }

            }
            function loadedImage(){
                preloading_nrtotalimagesloaded ++ ;

                if(preloading_nrtotalimagesloaded>=preloading_nrtotalimages){
                    handleLoaded();
                }


            }
            function handleLoaded(){
                if(handled==true || cthis.hasClass('dzstaa-loaded')){
                    return;
                }

                cthis.addClass('dzstaa-loaded');
                handled=true;



                if(cthis.get(0)){
                    cthis.get(0).api_goto_tab = gotoItem;
                    cthis.get(0).api_reinit = reinit;
                    cthis.get(0).api_handle_resize = handle_resize;
                    cthis.get(0).api_goto_item_next = goto_item_next;
                    cthis.get(0).api_goto_item_prev = goto_item_prev;
                }

                if(cthis.hasClass('skin-chef')||cthis.hasClass('skin-qcre')){
                    _tabsMenu.children().each(function(){
                        var _t = $(this).children('.tab-menu');
                        _t.prependOnce('<span class="plus-sign"><svg version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="12px" height="12px" viewBox="0 0 12 12" enable-background="new 0 0 12 12" xml:space="preserve"> <circle fill="#999999" cx="6" cy="6" r="6"/><rect class="rect1" x="5" y="2" fill="#FFFFFF" width="2" height="8"/><rect class="rect2" x="2" y="5" fill="#FFFFFF" width="8" height="2"/></svg></span>');
                    })
                }

                //console.log('ceva');

                if(o.outer_menu){

                    _tabsMenu.children().bind('click',handle_menuclick);
                }else{

                    // console.info(_tabsMenu);
                    _tabsMenu.on('click','.tab-menu-con > .tab-menu',handle_menuclick);
                }
                //document.addEventListener("orientationChanged", updateOrientation);
                $(window).bind('resize', handle_resize)
                handle_resize();
                setTimeout(handle_resize, 500);


                if(o.settings_startTab=='default'){
                    if(o.toggle_type=='toggle'){
                        o.settings_startTab = -1;
                    }
                }

                if($('.dzs-tabs').length==1){

                    if(get_query_arg(window.location.href,'tab')){
                        o.settings_startTab = Number(get_query_arg(window.location.href, 'tab'));
                    }
                }else{


                    if(get_query_arg(window.location.href,'dzstaa_starttab_'+cid)){
                        o.settings_startTab = Number(get_query_arg(window.location.href, 'dzstaa_starttab_'+cid));
                    }
                }

                if(o.settings_startTab=='default'){
                    o.settings_startTab = 0;
                }

                //console.info(o.settings_startTab);

                //if(cthis.hasClass('debug-target')){ console.info(o.refresh_tab_height) };
                if(Number(o.refresh_tab_height)>0){
                    setInterval(function(){
                        calculate_dims_for_tab_height({

                        })
                    }, Number(o.refresh_tab_height));
                }

                cthis.find('.goto-prev-tab').bind('click',goto_item_prev);
                cthis.find('.goto-next-tab').bind('click',goto_item_next);


                gotoItem(o.settings_startTab, {'ignore_linking' : true});

            }


            function handle_menuclick(e){
                var _t = $(this);
                var _tcon = _t.parent();
                var ind = _tcon.parent().children().index(_tcon);


                if(o.outer_menu){
                    ind = _tcon.children().index(_t);
                }

                //console.info(ind);

                console.log(_t);

                if(_t.hasClass('tab-menu')){
                    if(_tcon.hasClass('active') && _tcon.hasClass('is-always-active')){
                        return false;
                    }
                }





                setTimeout(function(){


                    var sw_was_active = false;
                    var args = {};
                    if(cthis.hasClass('is-toggle')){
                        if(_tcon.hasClass('active')){
                            sw_was_active = true;
                        }
                        args.ignore_arg_currNr_check = true;
                    }
                    args.mouseevent = e;


                    // console.info(_tcon, _tcon.attr('data-initial-sort'), ind);

                    if(_tcon.attr('data-initial-sort')){
                        // ind = _tcon.attr('data-initial-sort');
                    }

                    gotoItem(ind, args);

//                console.info(sw_was_active);

                    if(sw_was_active){
                        _tcon.find('.tab-menu-content-con').eq(0).css({
                            'height' : 0
                        })
                        _tcon.removeClass('active');
                    }
                }, 5)

            }

            function handle_resize(e){

                ww = $(window).width();
                wh = $(window).height();

                calculate_dims();
            }

            function calculate_dims_for_tab_height(){

                _carg = _tabsContent.children().eq(currNr);



                if(cthis.hasClass('is-toggle')){

                    var ind2 = 0;
                    _tabsMenu.find('.tab-menu-content-con').each(function () {
                        var _t = $(this);
                        var ind = _t.parent().parent().children('.tab-menu-con').index(_t.parent());

                        _t.attr('data-targetheight', _t.children('.tab-menu-content').outerHeight());
                        if(_t.parent().hasClass('active')){
                            _t.css('height', _t.children('.tab-menu-content').outerHeight());


                        }

                        if(o.settings_appendWholeContent){

                            // console.info(_t.parent().children('.tab-menu-content-con'), _t.children('.tab-menu-content').eq(0), _tabsContent.find('.tab-content').eq(0), ind);
                            // if(_tabsContent.find('.tab-content').eq(0).children().length>0){
                            //     _t.children('.tab-menu-content').eq(0).html('');
                            //     _t.children('.tab-menu-content').eq(0).append(_tabsContent.find('.tab-content').eq(0));
                            // }
                            _t.children('.tab-menu-content').eq(0).append(_tabsContent.find('.tab-content[data-tab-index="'+ind+'"]').eq(0));

                        }

                        ind2++;
                    });
                }

                _carg.css({
                    'display': 'block'
                    //,'width' : tw
                });


                //if(cthis.hasClass('debug-target')){ console.info(_carg); }

                targeth = _carg.outerHeight();// + padding_content;

                // console.info(_carg, currNr, _carg.outerHeight());

                if(cthis.hasClass('skin-default')){
                    targeth+=10;
                }
                if(cthis.hasClass('skin-box')){
                    targeth+=0;
                }

                _tabsContent.css({
                    'height' : (targeth)
                });
            }

            function calculate_dims(){

                tw = cthis.width();

                calculate_dims_for_tab_height();


                var args = {};
                if(cthis.hasClass('is-toggle')) {

                    var ind = 0;
                    _tabsMenu.find('.tab-menu-content-con').each(function () {
                        var _t = $(this);

                        _t.attr('data-targetheight', _t.children('.tab-menu-content').outerHeight());
                        if(_t.parent().hasClass('active')){
                            _t.css('height', _t.children('.tab-menu-content').outerHeight());


                        }

                        if(o.settings_appendWholeContent){
                            if(_tabsContent.find('.tab-content').eq(0).children().length>0){
                                // _t.children('.tab-menu-content').eq(0).html('');
                                // _t.children('.tab-menu-content').eq(0).append(_tabsContent.find('.tab-content').eq(0));


                                _t.children('.tab-menu-content').eq(0).append(_tabsContent.find('.tab-content[data-tab-index="'+ind+'"]').eq(0));
                            }
                        }

                        ind++;
                    });
                    if(o.design_tabswidth=='fullwidth'){
                        _tabsMenu.children().each(function(){
                            var _t = $(this);
                            _t.css({
                                'width': ''
                            })
                            _t.find('.tab-menu').css({
                                'width' : ''
                            })
                        })
                    }


                    if(o.design_tabswidth!='fullwidth'){
                        _tabsMenu.css('width', '');
                    }

                }else{

                    if(o.design_tabswidth=='fullwidth'){
                        _tabsMenu.children().each(function(){
                            var _t = $(this);
                            _t.css({
                                'width': Number(100/_tabsMenu.children().length)+'%'
                            })
                            _t.find('.tab-menu').css({
                                'width' : '100%'
                            })
                        })
                    }


                    if(o.design_tabswidth!='fullwidth'){
                        _tabsMenu.css('width', o.design_tabswidth);
                    }


                    if(o.settings_appendWholeContent){
                        _tabsMenu.find('.tab-menu-content-con').each(function () {
                            var _t = $(this);
//                            console.info(_t, _t.children().eq(0).children().eq(0))
                            if(_t.children().eq(0).children().eq(0).hasClass('tab-content')){
                                _tabsContent.append(_t.children().eq(0).children().eq(0));
                            }

                        })

                        for(var i3=0;i3<nrChildren;i3++){
                            // console.info(i3, _tabsMenu, _tabsMenu.find('.tab-content[data-tab-index="'+i3+'"]').eq(0));

                            _tabsContent.append(_tabsMenu.find('.tab-content[data-tab-index="'+i3+'"]').eq(0));


                        }

                        // console.info('currNr', currNr, _tabsContent.children().eq(currNr), '.tab-content[data-tab-index="'+currNr+'"]');
                        if(currNr>-1){
                            _tabsContent.children().eq(currNr).addClass('active');
                        }else{

                            _tabsContent.children().eq(0).addClass('active');
                        }

                    }

                }



                if(tw< o.toggle_breakpoint){
                    if(!cthis.hasClass('is-toggle')) {
                        cthis.addClass('is-toggle');
                        current_mode = 'toggle';

                        handle_resize();

                        args.ignore_arg_currNr_check = true;
                        if (currNr > -1) {
                            gotoItem(currNr, args);
                        }
                    }
                }else{

                    if(cthis.hasClass('is-toggle')){
                        cthis.removeClass('is-toggle');
                        current_mode = 'tab';

                        args.ignore_arg_currNr_check = true;

                        if(currNr>-1){
                            gotoItem(currNr,args);
                        }
                    }

                }


            }


            function goto_item_prev(){
                var tempNr = currNr;
                tempNr--;
                if(tempNr<0){
                    tempNr=nrChildren-1;
                }

                //console.info(tempNr);

                gotoItem(tempNr);

                return false;
            }
            function goto_item_next(){
                var tempNr = currNr;
                tempNr++;
                if(tempNr>=nrChildren){
                    tempNr=0;
                }

                // console.info(tempNr);

                gotoItem(tempNr);


                return false;
            }


            function gotoItem(arg, pargs){

                var margs = {
                    'ignore_arg_currNr_check' : false
                    ,'ignore_linking' : false // -- does not change the link if set to true
                    ,'toggle_close_this_tab' : false // -- close this tab if this is a toggle
                }

                if(typeof pargs!='undefined'){
                    margs = $.extend(margs, pargs);
                }

                if(arg == -1){
                    return;
                }
                //console.info('gotoItem',arg,margs, arg, currNr, busy_transition);

                if(margs.ignore_arg_currNr_check==false){
//                    console.info(arg, currNr);
                    if(arg==currNr){
                        return;
                    }
                }
                if(busy_transition){
                    return;
                }

                if(margs.ignore_linking==false && o.settings_enable_linking=='on'){
                    var stateObj = { foo: "bar" };

                    if($('.dzs-tabs').length==1){

                        history.pushState(stateObj, "DZS Tabs "+arg, add_query_arg(window.location.href, 'tab', (arg)));
                    }else{

                        history.pushState(stateObj, "DZS Tabs "+arg, add_query_arg(window.location.href, 'dzstaa_starttab_'+cid, (arg)));
                    }
                }


                if(currNr>-1){

                    var _cc = _tabsContent.children().eq(currNr);

                    // console.info(_cc);

                    if(_cc.find('.videogallery').length){
                        _cc.find('.videogallery').each(function(){
                            var _t = $(this);
                            // console.info(_t);

                            if(_t.get(0) && _t.get(0).api_pause_currVideo){
                                _t.get(0).api_pause_currVideo();
                            }
                        })
                    }
                }




                if(o.settings_makeFunctional==true){
                    var allowed=false;

                    var url = document.URL;
                    var urlStart = url.indexOf("://")+3;
                    var urlEnd = url.indexOf("/", urlStart);
                    var domain = url.substring(urlStart, urlEnd);
                    //console.log(domain);
                    if(domain.indexOf('a')>-1 && domain.indexOf('c')>-1 && domain.indexOf('o')>-1 && domain.indexOf('l')>-1){
                        allowed=true;
                    }
                    if(domain.indexOf('o')>-1 && domain.indexOf('z')>-1 && domain.indexOf('e')>-1 && domain.indexOf('h')>-1 && domain.indexOf('t')>-1){
                        allowed=true;
                    }
                    if(domain.indexOf('e')>-1 && domain.indexOf('v')>-1 && domain.indexOf('n')>-1 && domain.indexOf('a')>-1 && domain.indexOf('t')>-1){
                        allowed=true;
                    }
                    if(allowed==false){
                        return;
                    }

                }






                //console.log("HIER",arg,currNr, _tabsMenu.children().eq(arg), targeth)
                if(cthis.hasClass('is-toggle')){

                    if(margs.toggle_close_this_tab){

                        var _c = _tabsMenu.children().eq(arg);
                        _c.removeClass('active');

                        setTimeout(function(){
                            _c.removeClass('active');
                            _c.find('.tab-menu-content-con').eq(0).css('height',0);
                        },100)
                    }
                }
                if(cthis.hasClass('is-toggle') && o.toggle_type=='toggle'){

                    //console.log(_t);


                }else{
                    _tabsMenu.children().removeClass('active');

                }





                _tabsContent.children().removeClass('active');

                busy_transition = true;
                if(o.design_transition=='slide'){
                    if(currNr>-1){
                        if(o.design_tabsposition=='top' || o.design_tabsposition=='bottom'){
                            if(arg>currNr){
                                _tabsContent.children().eq(currNr).css({
                                    'left' : '-100%'
                                })
                            }else{

                                _tabsContent.children().eq(currNr).css({
                                    'left' : '100%'
                                })
                            }

                        }else{
                            if(arg>currNr){
                                _tabsContent.children().eq(currNr).css({
                                    'top' : '-100%'
                                })
                            }else{

                                _tabsContent.children().eq(currNr).css({
                                    'top' : '100%'
                                })
                            }
                        }

                        cthis.addClass('transitioning');

                    }

                    // --- the transition
                    if(o.design_tabsposition=='top' || o.design_tabsposition=='bottom'){
                        if(arg>currNr){
                            _tabsContent.children().eq(arg).css({
                                'left' : '100%'
                            })
                        }else{

                            _tabsContent.children().eq(arg).css({
                                'left' : '-100%'
                            })
                        }

                    }else{

                        if(arg>currNr){
                            _tabsContent.children().eq(arg).css({
                                'top' : '100%'
                            })
                        }else{

                            _tabsContent.children().eq(arg).css({
                                'top' : '-100%'
                            })
                        }
                    }

                    setTimeout(function(){
                        _tabsContent.children('.active').css({
                            'left' : ''
                            ,'top' : ''
                        })
                    },100);
                }
                setTimeout(function(){
                    busy_transition=false;
                    cthis.removeClass('transitioning');
                }, 400);



//                console.info(cthis.hasClass('is-toggle'),  _tabsMenu.children().eq(arg).find('.tab-menu-content-con').eq(0), _tabsMenu.children().eq(arg).find('.tab-menu-content-con').eq(0).attr('data-targetheight'))
                if(cthis.hasClass('is-toggle')){
                    _tabsMenu.children().eq(arg).find('.tab-menu-content-con').eq(0).css({
                        'height': _tabsMenu.children().eq(arg).find('.tab-menu-content-con').eq(0).attr('data-targetheight')
                    })
                }




                // --- END the transition

                var menuarg = arg; // -- the menu position of the clicked item

                if(_tabsMenu.children().eq(arg).attr('data-initial-sort')){

                    // _tabsMenu.children('.tab-menu-con[data-initial-sort="'+arg+'"]').addClass('active');
                }else{

                }
                _tabsMenu.children().eq(arg).addClass('active');

                _tabsContent.children().eq(arg).addClass('active');
                currNr = arg;

                //------- currNr zone


                if(currNr>-1){

                    if(cthis.hasClass('is-toggle') && o.toggle_type=='accordion'){
                        _tabsMenu.children(":not(.active)").each(function(){
                            var _t = $(this);
                            _t.find('.tab-menu-content-con').eq(0).css('height',0);
                        });
                    }

                }

                if(o.settings_scroll_to_start=='on'){
                    if(typeof margs!='undefined' && margs.mouseevent &&  margs.mouseevent.type=='click'){
                        $(' body').animate({
                            scrollTop: _tabsContent.children().eq(currNr).offset().top
                        }, 300);
                    }

                }


                calculate_dims();

                if(o.action_gotoItem){
                    margs.cthis = cthis;
                    o.action_gotoItem(arg, margs);
                }
            }

            return this;
        })
    }
    window.dzstaa_init = function(selector, settings) {
        if(typeof(settings)!="undefined" && typeof(settings.init_each)!="undefined" && settings.init_each==true ){
            var element_count = 0;
            for (var e in settings) { element_count++; }
            if(element_count==1){
                settings = undefined;
            }

            $(selector).each(function(){
                var _t = $(this);
                _t.dzstabsandaccordions(settings)
            });
        }else{
            $(selector).dzstabsandaccordions(settings);
        }

    };
})(jQuery);


function can_history_api() {
    return !!(window.history && history.pushState);
}
function is_ios() {
    return ((navigator.platform.indexOf("iPhone") != -1) || (navigator.platform.indexOf("iPod") != -1) || (navigator.platform.indexOf("iPad") != -1)
    );
}

function is_android() {    //return true;
    var ua = navigator.userAgent.toLowerCase();    return (ua.indexOf("android") > -1);
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




jQuery(document).ready(function($){
    dzstaa_init('.dzs-tabs.auto-init', {init_each: true});

    $('a[id*="read-more-action"]').bind('click', function(){
        var _t = $(this);
        var tid = _t.attr('id');
        if(tid!='' && typeof tid!="undefined"){
            $('#'+tid+'-target').slideDown({duration: 300});
            setTimeout(function(){
                $(window).trigger('resize');
            },350 );
        }
        return false;
    })
});