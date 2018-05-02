var coll_buffer=0;
var func_output='';



function htmlEncode(arg){
    return jQuery('<div/>').text(arg).html();
}

function htmlDecode(value){
    return jQuery('<div/>').html(arg).text();
}

function get_shortcode_attr(arg, argtext){

    // console.warn("HMM");

    var regex_aattr = new RegExp(' '+arg+'="(.*?)"');



    //console.log(regex_aattr, argtext);

    var aux = regex_aattr.exec(argtext);

    if(arg=='cat'){

        // console.warn('aux - ',aux);
    }
    if(arg=='mode'){

        console.warn('mode .. aux - ',aux);
    }
    if(aux){
        var foutobj = {'full' : aux[0], 'val' : aux[1]};
        return foutobj;
    }



    return false;
}


// -- tbc

var dzsvg_arr_params_mode_video_gallery = []
var dzsvg_arr_params = []

var dzsvg_standard_options = [
    'mode_gallery_view_nav_type'
    , 'orderby'
    , 'order'
    , 'vimeo_link'
    , 'facebook_link'
    , 'count'
    , 'mode_zfolio_default_cat'
    , 'mode_zfolio_categories_are_links'
    , 'mode_zfolio_categories_are_links_ajax'
    ,'mode_zfolio_show_filters'
    ,'mode_zfolio_title_links_to'
];


var mode_gallery_view_options = [
    'mode_gallery_view_gallery_skin',
    'mode_gallery_view_set_responsive_ratio_to_detect',
    'mode_gallery_view_width',
    'mode_gallery_view_height',
    'mode_gallery_view_autoplay',
    'mode_gallery_view_html5designmiw',
    'mode_gallery_view_html5designmih',
    'mode_gallery_view_menuposition',
    'mode_gallery_view_analytics_enable',
    'mode_gallery_view_autoplaynext',
    'mode_gallery_view_nav_type',
    'mode_gallery_view_nav_space',
    'mode_gallery_view_disable_video_title',
    'mode_gallery_view_logo',
    'mode_gallery_view_logoLink',
    'mode_gallery_view_playorder',
    'mode_gallery_view_design_navigationuseeasing',
    'mode_gallery_view_enable_search_field',
    'mode_gallery_view_settings_enable_linking'
        ,'mode_gallery_view_autoplay_ad'
        ,'mode_gallery_view_embedbutton'
]
;

jQuery(document).ready(function($){



    if(typeof(dzsvg_settings)!='undefined' && dzsvg_settings.startSetup!=''){
        top.dzsvg_startinit = dzsvg_settings.startSetup;
    }

    console.info('startinit is '+top.dzsvg_startinit);

    var coll_buffer=0;
    var fout='';





    // console.warn(top.dzsvg_startinit);
    // ---- some custom code for initing the generator ( previous values )
    if(typeof top.dzsvg_startinit!='undefined' && top.dzsvg_startinit!=''){


        var arr_settings = [
            'mode'
            ,'cat'
            ,'type'
            , 'desc_count'
            , 'linking_type'
        ];

        // console.warn(arr_settings);
        arr_settings = arr_settings.concat(dzsvg_standard_options);
        // console.warn(arr_settings);
        arr_settings = arr_settings.concat(mode_gallery_view_options);

        $('.dzsvg-admin').append('<div class="misc-initSetup"><h5>Start Setup</h5></h5><p>'+htmlEncode(top.dzsvg_startinit)+'</p></div>');


        var res;
        var lab='';

        // console.warn(arr_settings);
        for(var key in arr_settings){

            // console.info(key);
            lab = arr_settings[key];
            res = get_shortcode_attr(lab, top.dzsvg_startinit);
           // console.info(res, lab, top.dzsp_startinit);
            if(res){
                if(lab=='id'){
                    lab = 'dzsvg_selectid';
                }
                if(lab=='db'){
                    lab = 'dzsvg_selectdb';
                }
                if(lab=='cat'){


                    // WARNING: do not settimeout this

                    var _targetf = $('input[name="cat"]');


                    // console.error('res - ',res);
                    var targetarr = String(res.val).split(',');

                    // console.info(targetarr);


                    $('input[name="cat_checkbox[]"]').each(function(){
                        "use strict";



                        var _t2 = $(this);

                        if(targetarr.indexOf(_t2.val())>-1){
                            _t2.prop('checked',true);
                        }else{

                            _t2.prop('checked',false);
                        }

                    })


                    console.error("CAT -> ",res.val);
                    _targetf.val(res.val);
                }else{

                    // console.info(lab);
                    if(lab=='type' || lab=='mode'){
                        console.warn('changing now', lab, res);
                    }

                    var _c = $('*[name="'+lab+'"]');

                    _c.val(res['val']);
                    _c.trigger('change');

                    // console.info(lab);
                    if(lab=='type' || lab=='mode'){
                        console.info(lab, '_c - ', _c, res.val);
                    }

                    if(_c && _c.get(0) && _c.get(0).nodeName){
                        // console.info(_c.get(0).nodeName)

                        if(_c.get(0).nodeName=='INPUT' && _c.attr('type')=='checkbox'){
                            if(_c.val() == res['val']){
                                _c.prop('checked',true);
                            }
                        }
                    }

                    if(_c.hasClass('dzs-style-me')){
                        if(_c.get(0)&&_c.get(0).api_recheck_value_from_input){
                            _c.get(0).api_recheck_value_from_input();
                        }
                    }
                }
            }
        }
    }



    var _feedbacker = $('.feedbacker');

    _feedbacker.fadeOut("slow");
    setTimeout(reskin_select, 10);
    $('#insert_tests').unbind('click');
    $('#insert_tests').bind('click', click_insert_tests);

    $(document).delegate('.import-sample,.close-notice', 'click', handle_mouse);
    $(document).delegate('form.import-sample-galleries,form.import-sample-items', 'submit', handle_submit);
    $(document).delegate('select[name=mode],select[name=type],select[name=linking_type], .dzs-dependency-field', 'change', handle_submit);
    $(document).on('change','input[name="cat_checkbox[]"]',  handle_submit);

    //,input[name="cat"]

    if($('.dzsvg-notice--preview').length){
        setTimeout(function(){
            "use strict";
            $("html, body").animate({ scrollTop: $('.dzsvg-notice--preview').eq(0).offset().top }, 1000);

            $('.dzsvg-notice--preview').parent().trigger('click');
        },1000);
    }
    setTimeout(function(){
        "use strict";


    },1000);

    $('select[name=dzsvg_selectdb]').bind('change', change_selectdb);



    $('select[name=mode],select[name=type],select[name=linking_type]').trigger('change');

    function handle_mouse(e){
        var _t = $(this);

        if(e.type=='click'){
            console.info(_t);

            if(_t.hasClass('close-notice')){


                if(_t.parent().parent().hasClass('dzsvg-notice')){
                    var _con = _t.parent();





                    var data = {
                        action: 'dzsvg_delete_notice'
                        ,postdata: _con.parent().attr('data-lab')
                    };


                    jQuery.ajax({
                        type: "POST",
                        url: ajaxurl,
                        data: data,
                        success: function(response) {
                            if(typeof window.console != "undefined" ){ console.log('Ajax - submit view - ' + response); }

                            //console.info(response);
                            show_notice(response);

                            _con.fadeOut('fast');


                            // _t.addClass('active-showing').removeClass('import-sample-items').addClass('remove-sample-items');


                        },
                        error:function(arg){
                            if(typeof window.console != "undefined" ){ console.warn('Got this from the server: ' + arg); };
                        }
                    });



                }
            }
            if(_t.hasClass('import-sample')){

                var fout = '';
                if(_t.hasClass('import-showcase-sample-1')){

                     fout = '<div>[dzs_videoshowcase type="video_items" mode="zfolio" mode_zfolio_skin="skin-alba" mode_zfolio_gap="1px" mode_zfolio_layout="5columns" mode_zfolio_enable_special_layout="on" count="5" desc_count="default" linking_type="direct_link" vpconfig="default"]</div> <div class="" style="max-width: 1170px; margin: 10px auto;"> <div style="float:left; width: 66.66%; padding-right: 30px; box-sizing: border-box;">[dzs_videoshowcase type="video_items" mode="list" count="5" desc_count="default" linking_type="default" vpconfig="default"]</div> <div style="float:left; width: 33.33%; box-sizing: border-box;">[dzs_videoshowcase type="video_items" mode="list-2" count="5" desc_count="default" linking_type="default" vpconfig="default"]</div> </div> ';
                }
                if(_t.hasClass('import-showcase-sample-2')){

                     fout = '[dzs_videoshowcase type="vimeo" mode="zfolio" mode_zfolio_skin="skin-forwall" mode_zfolio_gap="30px" mode_zfolio_layout="3columns" mode_gallery_view_nav_type="thumbs" orderby="none" order="DESC" vimeo_link="https://vimeo.com/user5137664" count="5" desc_count="default" linking_type="zoombox" vpconfig="default"]';
                }
                if(_t.hasClass('import-showcase-sample-3')){

                     fout = '[dzs_videoshowcase type="video_items" mode="zfolio" mode_zfolio_skin="skin-forwall" mode_zfolio_gap="30px" mode_zfolio_layout="3columns" cats="'+window.dzsvg_showcase_options.sampledata_cats[0]+','+window.dzsvg_showcase_options.sampledata_cats[1]+'" count="5" desc_count="default" linking_type="zoombox" vpconfig="default" mode_zfolio_show_filters="on" from_sample="on"]';
                }



                tinymce_add_content(fout);
                return false;
            }
        }
    }

    function check_dependency_settings(){
        $('*[data-dependency]').each(function(){
            var _t = $(this);


            // console.info(_t);
            var dep_arr = JSON.parse(_t.attr('data-dependency'));

            // console.warn(dep_arr);

            if(dep_arr[0]){
                var _c = $('*[name="'+dep_arr[0].lab+'"]').eq(0);

                // console.info(_c, dep_arr[0].val);

                var sw_show = false;

                for(var i3 in dep_arr[0].val){
                    if(_c.val() == dep_arr[0].val[i3]){
                        sw_show=true;
                        break;

                    }
                }

                if(sw_show){
                    _t.show();
                }else{
                    _t.hide();
                }


            }
        })
    }

    function handle_submit(e){
        var _t = $(this);

        if(e.type=='change'){
            // console.info(_t);
            if(_t.attr('name')=='mode'){
                var _con = _t.parent().parent().parent();
                _con.removeClass('mode-scrollmenu mode-list mode-ullist mode-featured mode-scroller mode-list-2 mode-zfolio mode-gallery_view');

                _con.addClass('mode-'+_t.val());
            }
            if(_t.attr('name')=='type'){
                var _con = _t.parent().parent().parent();
                _con.removeClass('type-video_items type-youtube type-vimeo type-facebook');

                _con.addClass('type-'+_t.val());
            }
            if(_t.attr('name')=='linking_type'){
                var _con = _t.parent().parent().parent();
                _con.removeClass('linking_type-default linking_type-zoombox linking_type-direct_link linking_type-vg_change');

                _con.addClass('linking_type-'+_t.val());
            }
            if(_t.attr('name')=='cat_checkbox[]'){
                // console.info('ceva');


                var _targetf = $('input[name="cat"]').val();

                var targetval = '';
                $('input[name="cat_checkbox[]"]').each(function(){
                    "use strict";

                    var _t2 = $(this);

                    if(_t2.prop('checked')){
                        if(targetval){
                            targetval+=',';
                        }

                        targetval+=_t2.val();
                    }

                })
                $('input[name="cat"]').val(targetval);

            }
            if(_t.hasClass('dzs-dependency-field')){
                // console.info("ceva");
                check_dependency_settings();
            }
        }
        if(e.type=='submit'){
            // console.info(_t);

            if(_t.hasClass('import-sample-items')){

                var data = {
                    action: 'dzsvg_import_sample_items'
                    ,postdata: _t.serialize()
                };

                if(_t.hasClass('active-showing')){
                    data.action = 'dzsvg_remove_sample_items';
                }


                jQuery.ajax({
                    type: "POST",
                    url: ajaxurl,
                    data: data,
                    success: function(response) {
                        if(typeof window.console != "undefined" ){ console.log('Ajax - submit view - ' + response); }

                        //console.info(response);
                        show_notice(response);

                        _t.toggleClass('active-showing');
                        // _t.addClass('active-showing').removeClass('import-sample-items').addClass('remove-sample-items');


                    },
                    error:function(arg){
                        if(typeof window.console != "undefined" ){ console.warn('Got this from the server: ' + arg); };
                    }
                });

                return false;
            }
        }
    }



    function show_notice(response){


        if(response.indexOf('error -')==0){
            _feedbacker.addClass('is-error');
            _feedbacker.html(response.substr(7));
            _feedbacker.fadeIn('fast');

            setTimeout(function(){

                _feedbacker.fadeOut('slow');
            },1500)
        }
        if(response.indexOf('success -')==0){
            _feedbacker.removeClass('is-error');
            _feedbacker.html(response.substr(9));
            _feedbacker.fadeIn('fast');

            setTimeout(function(){

                _feedbacker.fadeOut('slow');
            },1500)
        }
    }
});
function change_selectdb(e){
    var _t = jQuery(this);

    //console.info(_t.val());



    jQuery('#save-ajax-loading').css('opacity', '1');
    var mainarray = _t.val();
    var data = {
        action: 'dzsvg_get_db_gals',
        postdata: mainarray
    };
    jQuery('.saveconfirmer').html('Options saved.');
    jQuery('.saveconfirmer').fadeIn('fast').delay(2000).fadeOut('fast');
    jQuery.post(ajaxurl, data, function(response) {
        if(window.console !=undefined ){  console.log('Got this from the server: ' + response); }
        jQuery('#save-ajax-loading').css('opacity', '0');

        var aux = '';
        var auxa = response.split(';');
        for(i=0;i<auxa.length;i++){
            aux+='<option>'+auxa[i]+'</option>'
        }
        $('select[name=dzsvg_selectid]').html(aux);
        $('select[name=dzsvg_selectid]').trigger('change');

    });

    return false;

}


function tinymce_add_content(arg){
    //console.log('tinymce_add_content()', arg);
    if(top==window){
        jQuery('.shortcode-output').text(arg);
    }else{


        if(top.dzsvg_widget_shortcode){
            top.dzsvg_widget_shortcode.val(arg);

            top.dzsvg_widget_shortcode = null;

            console.info(top.close_zoombox2);
            if(top.close_zoombox2){
                top.close_zoombox2();
            }
        }else{

            console.info(top.dzsvg_receiver);
            if(typeof(top.dzsvg_receiver)=='function'){
                top.dzsvg_receiver(arg);
            }
        }

    }

}

function click_insert_tests(e){

    //console.info('click_insert_tests');
    //console.log(jQuery('#mainsettings').serialize());
    prepare_fout();
    tinymce_add_content(fout);
    return false;
}

function add_attribute_to_shortcode(lab){
    var $ = jQuery;

    var _c = $('*[name='+lab+']');
    var _par = null;



    // console.info('lab - ',lab, _par)
    if(_c.parent().hasClass('setting')){
        _par = _c.parent();
    }
    if(_c.parent().parent().hasClass('setting')){
        _par = _c.parent().parent();
    }

    if(_par){
        if(_par.css('display')=='none'){
            return '';
        }
    }

    var fout2 = '';
    if(_c.val()){

        if(_c.attr('type')=='checkbox'){
            if(_c.prop('checked')){

                fout2+=' '+lab+'="' + _c.val() + '"';
            }else{

            }
        }else{

            fout2+=' '+lab+'="' + _c.val() + '"';
        }

    }

    return fout2;
}
var fout = '';
function prepare_fout(){
    var $ = jQuery;
    fout='';
    fout+='[dzs_videoshowcase';
    var _c
        ,_c2
        ,lab=''
        ,val=''
        ;
    /*
     _c = $('input[name=settings_width]');
     if(_c.val()!=''){
     fout+=' width=' + _c.val() + '';
     }
     _c = $('input[name=settings_height]');
     if(_c.val()!=''){
     fout+=' height=' + _c.val() + '';
     }
     */

        
    lab = 'type';
    _c = $('select[name='+lab+']');
    val = _c.val();
    if(val){
        fout+=' '+lab+'="' + val + '"';


        if(val=='video_gallery'){

            lab = 'dzsvg_selectid';
            _c = $('*[name='+lab+']');
            // console.info("HMM DADA", val, _c);
            val = _c.val();
            if(val){


                fout+=' '+lab+'="' + val + '"';
            }
        }
    }


    lab = 'mode';
    _c = $('select[name='+lab+']');
    val = _c.val();
    if(val){
        fout+=' '+lab+'="' + val + '"';



        if(val=='video_gallery'){

            lab = 'dzsvg_selectid';
            _c = $('*[name='+lab+']');
            // console.info("HMM DADA", val, _c);
            val = _c.val();
            if(val){


                fout+=' '+lab+'="' + val + '"';
            }
        }

        if(val=='scrollmenu'){



            lab = 'mode_scrollmenu_height';
            _c = $('*[name='+lab+']');
            if(_c.val()){
                fout+=' '+lab+'="' + _c.val() + '"';
            }
        }

        if(val=='zfolio'){


            lab = 'mode_zfolio_skin';
            _c = $('*[name='+lab+']');
            if(_c.val()){
                fout+=' '+lab+'="' + _c.val() + '"';
            }
            lab = 'mode_zfolio_gap';
            _c = $('*[name='+lab+']');
            if(_c.val()){
                fout+=' '+lab+'="' + _c.val() + '"';
            }
            lab = 'mode_zfolio_layout';
            _c = $('*[name='+lab+']');
            if(_c.val()){
                fout+=' '+lab+'="' + _c.val() + '"';
            }


            lab = 'mode_zfolio_enable_special_layout';
            _c = $('*[name='+lab+']');
            if(_c.prop('checked')){
                fout+=' '+lab+'="' + _c.val() + '"';
            }

        }

        if(val=='list'){


            lab = 'mode_list_enable_view_count';
            _c = $('*[name='+lab+']');
            if(_c.val()){
                fout+=' '+lab+'="' + _c.val() + '"';
            }

        }



        if(val=='gallery_view'){


            lab = 'mode_gallery_view_nav_type';
            _c = $('*[name='+lab+']');
            if(_c.val()){
                fout+=' '+lab+'="' + _c.val() + '"';
            }


            for(var i2 in mode_gallery_view_options){

                console.info( 'lab - ',mode_gallery_view_options[i2]);
                fout+=add_attribute_to_shortcode(mode_gallery_view_options[i2]);
            }

        }

    }


    for(var i2 in dzsvg_standard_options){

        // console.info( 'lab - ',dzsvg_standard_options[i2]);
        fout+=add_attribute_to_shortcode(dzsvg_standard_options[i2]);
    }

    lab = 'cat[]';
    lab = 'cat';
    _c = $('*[name="'+lab+'"]');


    var str_cat = '';
    var str_cat = _c.val();

    _c.each(function(){

        /*
        var _t = $(this);

        // console.info(_t);

        if(_t.prop('checked')){

            str_cat+=_t.val()+',';
        }

        */

    });

    if(str_cat){
        fout+=' cat="'+str_cat+'"';
    }




    lab = 'desc_count';
    _c = $('*[name='+lab+']');
    if(_c.val() && _c.val()!='default'){
        fout+=' '+lab+'="' + _c.val() + '"';
    }

    lab = 'youtube_link';
    _c = $('*[name='+lab+']');
    if(_c.val()!='' && _c.val()!='main'){
        fout+=' '+lab+'="' + _c.val() + '"';
    }


    lab = 'vimeo';
    _c = $('*[name='+lab+']');
    if(_c.val()){
        fout+=' '+lab+'="' + _c.val() + '"';
    }

    lab = 'max_videos';
    _c = $('*[name='+lab+']');
    if(_c.val()){
        fout+=' '+lab+'="' + _c.val() + '"';
    }
    lab = 'linking_type';
    _c = $('*[name='+lab+']');
    if(_c.val() && _c.val()!='default'){
        fout+=' '+lab+'="' + _c.val() + '"';
    }
    lab = 'vpconfig';
    _c = $('*[name='+lab+']');
    if(_c.val() && _c.val()!='default'){
        fout+=' '+lab+'="' + _c.val() + '"';
    }


    // if($('select[name=dzsvg_settings_separation_mode]').val()!='normal'){
    //     _c = $('select[name=dzsvg_settings_separation_mode]');
    //     if(_c.val()!=''){
    //         fout+=' settings_separation_mode="' + _c.val() + '"';
    //     }
    //     _c = $('input[name=dzsvg_settings_separation_pages_number]');
    //     if(_c.val()!=''){
    //         fout+=' settings_separation_pages_number="' + _c.val() + '"';
    //     }
    // }

    fout+=']';
}

function sc_toggle_change(){
    var $ = jQuery.noConflict();
    //var $t = $(this);

    var type = 'toggle';
    var params = '?type=' + type;
    for(i=0;i<$('.sc-toggle').length;i++){
        var $cach = $('.sc-toggle').eq(i);
        var val = $cach.val();
        if($cach.hasClass('color'))
            val = val.substr(1);
        params+='&opt' + (i+1) + '=' + val;
    }
    // console.log(params);
    $('.sc-toggle-frame').attr('src' , window.theme_url + 'tinymce/preview.php' + params);

}
function sc_boxes_change(){
    //var $t = $(this);

    var type = 'box';
    var params = '?type=' + type;
    for(i=0;i<$('.sc-box').length;i++){
        var $cach = $('.sc-box').eq(i);
        var val = $cach.val();
        params+='&opt' + (i+1) + '=' + val;
    }
    //console.log(params);
    $('.sc-box-frame').attr('src' , window.theme_url + 'tinymce/preview.php' + params);

}



function reskin_select(){
    for(i=0;i<jQuery('select').length;i++){
        var _cache = jQuery('select').eq(i);
        //console.log(_cache.parent().attr('class'));

        if(_cache.hasClass('styleme')==false || _cache.parent().hasClass('select_wrapper') || _cache.parent().hasClass('select-wrapper')){
            continue;
        }
        var sel = (_cache.find(':selected'));
        _cache.wrap('<div class="select-wrapper"></div>')
        _cache.parent().prepend('<span>' + sel.text() + '</span>')
    }
    //jQuery('.select-wrapper select').unbind();
    jQuery('.select-wrapper select').unbind('change',change_select);
    jQuery('.select-wrapper select').bind('change',change_select);
}

function change_select(){
    var selval = (jQuery(this).find(':selected').text());
    jQuery(this).parent().children('span').text(selval);
}