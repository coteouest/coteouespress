
"use strict";

jQuery(document).ready(function($){


    function get_query_arg(purl, key){
        //console.log(purl, key)
        //console.info(purl);
        if(purl.indexOf(key+'=')>-1){
            //faconsole.log('testtt');
            var regexS = "[?&]"+key + "(.+?)(?=&|$)";
            var regex = new RegExp(regexS);
            var regtest = regex.exec(purl);


            //console.info(regex, regtest);
            if(regtest != null){
                //var splitterS = regtest;


                if(regtest[1]){
                    var aux = regtest[1].replace( /=/g, '');
                    return aux;
                }else{
                    return '';
                }


            }
            //$('.zoombox').eq
        }
    }
    // -- we ll create queue calls so that we send ajax only once

    var inter_queued_calls = 0;

    var ajax_queue = [];

    var inter_send_to_ajax = 0;

    var term_id = 0;
    var saving = false;

    var _feedbacker = $('.feedbacker').eq(0);

    var _sliderItems = $('.dzsvg-slider-items').eq(0);
    var _slidersCon = $('.dzsvg-sliders-con').eq(0);


    var page = 'slider_single';

    if(get_query_arg(window.location.href,'taxonomy')=='dzsvg_sliders' && get_query_arg(window.location.href,'post_type')=='dzsvideo'  && (typeof get_query_arg(window.location.href,'tag_ID')=='undefined' || typeof get_query_arg(window.location.href,'tag_ID')=='') ){

        page = 'slider_multiple';
    }

    _feedbacker.fadeOut('fast');



    var slider_term_id = 0;
    var slider_term_slug = '';
    if(page=='slider_single'){
        slider_term_id = $('.dzsvg-sliders-con').eq(0).attr('data-term_id')
        slider_term_slug = $('.dzsvg-sliders-con').eq(0).attr('data-term-slug')
    }



    console.info('get_query_arg(window.location.href,\'taxonomy\') - ',get_query_arg(window.location.href,'taxonomy'));
    console.info('get_query_arg(window.location.href,\'post_type\') - ',get_query_arg(window.location.href,'post_type'));
    console.info('page - ',page);
    console.info('get_query_arg(window.location.href,\'tag_ID\') - ',get_query_arg(window.location.href,'tag_ID'));


    setTimeout(function(){

        $('body').addClass('sliders-loaded');
    },600);

    if(page=='slider_multiple'){

        $('body').addClass('page-slider-multiple');
        var _colContainer = $('#col-container');


        _colContainer.before('<div class="sliders-con"></div>');
        _colContainer.after('<div class="add-slider-con"></div>');

        var _slidersCon = _colContainer.prev();
        var addSliderCon = _colContainer.next();

        _slidersCon.append(_colContainer.find('#col-right').eq(0));

        $('#footer-thankyou').hide();
        $('.dzsvg-sliders-con').hide();
        console.info('_slidersCon - ',_slidersCon);

        _slidersCon.find('.row-actions > .edit > a').css('margin-right', '15px');
        _slidersCon.find('.row-actions > .edit > a').wrapInner('<span class="the-text"></span>')


        _slidersCon.find('.row-actions > .edit > a').addClass('dzs-button btn-style-default skinvariation-border-radius-more btn-padding-medium text-strong color-normal-highlight color-over-dark font-size-small');


        $('#screen-meta-links').prepend('<div id="import-options-link-wrap" class="hide-if-no-js screen-meta-toggle">\n' +
            '\t\t\t<button type="button" id="show-settings-link" class="button show-settings" aria-controls="screen-options-wrap" aria-expanded="false">Import</button>\n' +
            '\t\t\t</div>');

        // -- end slider multiple

        $('#screen-options-wrap').after($('.import-slider-form'));
    }

    if(page=='slider_single'){
        $('body').addClass('page-slider-single');
        $('.dzsvg-sliders').before($('#edittag').eq(0));




        $('#edittag').prepend($('.tag-options-con').eq(0));
        $('.form-table:not(.custom-form-table)').addClass('sa-category-main');


        var _sa_categoryMain = $('.sa-category-main').eq(0);

        _sa_categoryMain.find('tr').eq(1).after('<div class="clear"></div>');
        _sa_categoryMain.find('.term-description-wrap').eq(0).after('<div class="clear"></div>');



        $('.tab-content-cat-main').append(_sa_categoryMain);

        dzstaa_init('#tabs-box');
        dzstaa_init('.dzs-tabs-meta-item',{
            init_each:true
        });



    }


    console.info('sliders page - ',page);

    setTimeout(function(){

        $('.slider-status').removeClass('empty');
    },300);
    setTimeout(function(){

        $('.slider-status').removeClass('loading');
    },500);
    setTimeout(function(){

        // -- we place this here so that it won't fire with no reason ;)
        $(document).on('change','input.setting-field,select.setting-field,textarea.setting-field,*[name=dzsvg_meta_featured_media]', handle_change);
        $(document).on('keyup','input.setting-field,select.setting-field,textarea.setting-field', handle_change);


        $('.slider-status').addClass('empty');
    },1000);

    //console.info('ceva');
    $(document).on('change.sliders_admin','*[name=the_post_title]', handle_change);
    $(document).on('click.sliders_admin','.slider-item, .slider-item > .divimage, .add-btn-new, .add-btn-existing-media, .delete-btn,.clone-item-btn, #import-options-link-wrap', handle_mouse);


    window.onbeforeunload = function() {
        if(saving){

            return "Are you sure you want to navigate away?";
        }
    }

    setTimeout(function(){


        // console.info('get_query_arg - ', get_query_arg)
        // console.info('get_query_arg(window.location.href,\'taxonomy\') - ', get_query_arg(window.location.href,'taxonomy'))




        if(page=='slider_single' && get_query_arg(window.location.href,'taxonomy')=='dzsvg_sliders'){


            // console.info('$(\'#ajax-response\') - ',$('#ajax-response'));
            // $('#wpbody-content .wrap').eq(0).append($('.dzsvg-sliders-con').eq(0));
            $('.wrap').eq(0).append($('.dzsvg-sliders-con').eq(0));
        }

        // console.info($('.dzsvg-slider-items'), $('#wpbody-content .wrap'), $('.dzsvg-sliders-con'))

        _sliderItems.sortable({
            placeholder: "ui-state-highlight"
            ,items: ".slider-item"
            ,stop: function( event, ui ) {
                console.info('stop sortable',event,ui);

                var arr_order = [];
                var i = 1;
                _sliderItems.children().each(function(){
                    var _t = $(this);
                    var aux = {
                        'id':_t.attr('data-id')
                        ,'order': i++
                    }

                    arr_order.push(aux);


                })



                var queue_call = {
                    'type':'set_meta_order'
                    ,'items':arr_order
                    ,'term_id':slider_term_id
                }


                ajax_queue.push(queue_call);

                //console.info(arr_order);

                prepare_send_queue_calls();
            }
        });
    },500);







    function handle_change(e) {
        var _t = $(this);

        var _con = null;


        if (e.type == 'change' || e.type=='keyup') {
            // console.info('changed',_t);

            if(_t.parent().parent().parent().parent().hasClass('slider-item')){
                _con = _t.parent().parent().parent().parent();
            }
            if(_t.parent().parent().parent().parent().parent().hasClass('slider-item')){
                _con = _t.parent().parent().parent().parent().parent();
            }

            if(_t.parent().parent().parent().parent().parent().parent().hasClass('slider-item')){
                _con = _t.parent().parent().parent().parent().parent().parent();
            }
            if(_t.parent().parent().parent().parent().parent().parent().parent().hasClass('slider-item')){
                _con = _t.parent().parent().parent().parent().parent().parent().parent();
            }
            if(_t.parent().parent().parent().parent().parent().parent().parent().parent().hasClass('slider-item')){
                _con = _t.parent().parent().parent().parent().parent().parent().parent().parent();
            }
            if(_t.parent().parent().parent().parent().parent().parent().parent().parent().parent().hasClass('slider-item')){
                _con = _t.parent().parent().parent().parent().parent().parent().parent().parent().parent();
            }


            console.info('change _t -  - ',_t,'_con - ',_con);

            if(_t.attr('name')=='dzsvg_meta_featured_media'){



                var _thumbinp = _con.find('*[name=dzsvg_meta_thumb]');

                // console.info('changed - ', _t,_con,_thumbinp.val());
                // console.info('_thumbinp.val() - ', _thumbinp.val());
                if(_thumbinp.val()==''){
                    _con.find('.refresh-main-thumb').trigger('click');
                }

                // return false;
            }
            if(_t.attr('name')=='the_post_title'){


                _con.find('.slider-item--title').html(_t.val());

            }


            // -- change the thumbnail
            if(String(_t.attr('name')).indexOf('meta_thumb')>-1){


                // console.info("HIER ",_t.val());
                _con.find('.divimage').eq(0).css({
                    'background-image':'url('+_t.val()+')'
                });

            }

            if(_con){
                var id = _con.attr('data-id');

                // console.info('id - ',id);
                // console.info('_t - ',_t);




                var queue_call = {
                    'type':'set_meta'
                    ,'item_id':id
                    ,'lab':_t.attr('name')
                    ,'val':_t.val()
                }


                var sw_found_and_set = false;
                for(var lab in ajax_queue){
                    var val = ajax_queue[lab];

                    console.groupCollapsed('test queue call override')
                    console.info('val - ', val,id,_t.attr('name'));
                    console.groupEnd();


                    if(val.type=='set_meta'){
                        if(val.item_id==id) {
                            if (val.lab == _t.attr('name')) {
                                ajax_queue[lab].val=_t.val();
                                sw_found_and_set =true;
                            }
                        }
                    }
                }

                if(sw_found_and_set==false){

                    ajax_queue.push(queue_call);
                }

                //console.info(arr_order);

                prepare_send_queue_calls();
            }
        }
    }


    function handle_mouse(e){
        var _t = $(this);

        if(e.type=='click'){

            // console.info('handle_mouse','click',_t);
            if(_t.attr('id')=='import-options-link-wrap'){


                var _c = $('#screen-options-wrap');


                if(_t.hasClass('active')==false){

                    $('.import-slider-form').show();
                    $('#screen-meta').slideDown('fast');
                    $('#screen-options-link-wrap').fadeOut('fast');

                    // _c.slideDown('fast');


                    // _c.addClass('preparing-transitioning-in');
                    // setTimeout(function(){
                    //
                    //     _c.css({
                    //         'display':'block'
                    //     })
                    //     _c.addClass('transitioning-in');
                    // },1000);

                    _t.addClass('active');
                }else{

                    $('#screen-meta').slideUp('fast');
                    $('.import-slider-form').fadeOut('fast');
                    $('#screen-options-link-wrap').fadeIn('fast');


                    // _c.slideUp('fast');

                    _t.removeClass('active');
                }
            }
            if(_t.hasClass('delete-btn')){



                console.info("DELETE",_t);



                var queue_call = {
                    'type':'delete_item'
                    ,'id':_t.parent().attr('data-id')
                    ,'term_slug':slider_term_slug


                }
                ajax_queue.push(queue_call);


                prepare_send_queue_calls(10);

                _t.parent().remove();


                return false;
            }
            if(_t.hasClass('clone-item-btn')){



                console.info("DELETE",_t);



                var queue_call = {
                    'type':'duplicate_item'
                    ,'id':_t.parent().attr('data-id')
                    ,'term_slug':slider_term_slug


                }
                ajax_queue.push(queue_call);


                prepare_send_queue_calls(10);




                return false;
            }
            if(_t.hasClass('add-btn--icon')){




                //_sliderItems.append($('.slider-item--placeholder').eq(0).clone())
                var queue_call = {
                    'type':'create_item'
                    ,'term_id':$('.dzsvg-sliders-con').eq(0).attr('data-term_id')
                    ,'term_name':$('.dzsvg-sliders-con').eq(0).attr('data-term-slug')
                }
                queue_call['dzsvg_meta_order_'+slider_term_id] = 1+_sliderItems.children().length+0;
                ajax_queue.push(queue_call);


                prepare_send_queue_calls(10);


            }
            if(_t.hasClass('add-btn-new')){




                //_sliderItems.append($('.slider-item--placeholder').eq(0).clone())
                var queue_call = {
                    'type':'create_item'
                    ,'term_id':$('.dzsvg-sliders-con').eq(0).attr('data-term_id')
                    ,'term_slug':$('.dzsvg-sliders-con').eq(0).attr('data-term-slug')
                }
                queue_call['dzsvg_meta_order_'+slider_term_id] = 1+_sliderItems.children().length+0;
                ajax_queue.push(queue_call);


                prepare_send_queue_calls(10);


            }
            if(_t.hasClass('add-btn-existing-media')){




                var _t = $(this);
                var _targetInput = _t.prev();

                var searched_type = '';

                if(_t.hasClass('upload-type-audio') || _targetInput.hasClass('upload-type-audio')){
                    searched_type = 'audio';
                }
                if(_targetInput.hasClass('upload-type-video')){
                    searched_type = 'video';
                }
                if(_targetInput.hasClass('upload-type-image')){
                    searched_type = 'image';
                }

                console.info(searched_type);

                var frame = wp.media.frames.dzsp_addimage = wp.media({
                    title: "Insert Media"
                    ,multiple:true
                    ,library: {
                        type: searched_type
                    },

                    // Customize the submit button.
                    button: {
                        // Set the text of the button.
                        text: "Insert Media",
                        close: true
                    }
                });

                // When an image is selected, run a callback.
                frame.on( 'select', function(arg1,arg2) {
                    // Grab the selected attachment.


                    console.info(frame, arg1,arg2);

                    // TODO: add code here

                    var selection = frame.state().get('selection');


                    console.warn(selection);


                    var i_sel = 0;
                    selection.map( function( attachment ) {
                        attachment = attachment.toJSON();


                        console.info(attachment);
                        //...one commented line, that was to add files into HTML structure - works     perfect, but only once



                        var queue_call = {
                            'type':'create_item'
                            ,'term_id':$('.dzsvg-sliders-con').eq(0).attr('data-term_id')
                            ,'term_slug':$('.dzsvg-sliders-con').eq(0).attr('data-term-slug')
                            ,'post_title':attachment.title
                            ,'dzsvg_meta_item_source':attachment.url
                        }

                        queue_call['dzsvg_meta_order_'+slider_term_id] = 1+_sliderItems.children().length+i_sel;
                        ajax_queue.push(queue_call);

                        i_sel++;


                    });
                    prepare_send_queue_calls(10);

                    

//            frame.close();
                });

                // Finally, open the modal.
                frame.open();

                e.stopPropagation();
                e.preventDefault();
                return false;


            }
            if(_t.hasClass('slider-item')){


                if(_t.hasClass('tooltip-open')){

                }else{

                    $('.slider-item').removeClass('tooltip-open').find('.dzstooltip').removeClass('active');

                    _t.addClass('tooltip-open');
                    _t.find('.dzstooltip').addClass('active');
                }

            }

            console.info(_t);
            if(_t.hasClass('divimage')){


                if(_t.parent().hasClass('slider-item')){

                    var _par = _t.parent();
                    if(_par.hasClass('tooltip-open')){

                        _par.removeClass('tooltip-open');
                        _par.find('.dzstooltip').removeClass('active');
                        return false;
                    }
                }

            }
        }
    }

    function show_feedback(arg, pargs) {


        var margs = {
            extra_class: ''
        }


        if (pargs) {
            margs = $.extend(margs, pargs);
        }



        var theclass = 'feedbacker ' + margs.extra_class;

        if (margs.extra_class == '') {
            //console.info(arg.indexOf('success - '));
            if (arg.indexOf('success - ') == 0) {
                arg = arg.substr(10);
            }
            if (arg.indexOf('error - ') == 0) {
                arg = arg.substr(8);
                theclass = 'feedbacker is-error';
            }
        }

        _feedbacker.attr('class', theclass);

        _feedbacker.html(arg);
        _feedbacker.fadeIn('fast');


        setTimeout(function() {

            _feedbacker.fadeOut('slow');
        }, 2000);

    }


    function send_queue_calls(){


        $('.slider-status').removeClass('empty');

        var arg = JSON.stringify(ajax_queue);
        var data = {
            action: 'dzsvg_send_queue_from_sliders_admin'
            ,the_term_id: _slidersCon.attr('data-term-id')
            ,postdata: arg
        };


        jQuery.ajax({
            type: "POST",
            url: window.ajaxurl,
            data: data,
            success: function(response) {

                response = parse_response(response);
                // console.warn(response);


                if(response.report_message){
                    if(window){

                        show_feedback(response.report_message);
                    }
                }


                if(response.items){
                    for(var i in response.items){
                        var cach = response.items[i];

                        if(cach.type == 'create_item'){

                            if(cach.original_request=='duplicate_item'){


                                $('.slider-item[data-id="'+cach.original_post_id+'"]').after(cach.str);


                            }else{

                                _sliderItems.append(cach.str);
                            }


                            dzstaa_init('.dzs-tabs-meta-item',{
                                init_each:true
                            });


                            dzssel_init('select.dzs-style-me', {init_each: true});
                        }
                    }

                }

                $('.slider-status').addClass('empty');
                saving = false;
                ajax_queue = [];
            },
            error:function(arg){
                if(typeof window.console != "undefined" ){ console.warn('Got this from the server / error: ' + arg); };
                //ajax_queue = [];
            }
        });
    }
    function parse_response(response) {

        var arg = {};
        try{
            arg = JSON.parse(response);


        }catch(err){
            console.log('did not parse',response);
        }

        return arg;
    }

    function prepare_send_queue_calls(customdelay){


        var delay = 2000;
        if(typeof customdelay=='undefined'){

            delay = 2000;
        }else{
            delay = customdelay;
        }

        // console.info('delay - ',delay);
        saving = true;
        clearTimeout(inter_send_to_ajax);
        inter_send_to_ajax = setTimeout(send_queue_calls,delay);
    }



    $(document).on('change.dzsvg_sliders_admin','*[data-aux-name]', function(){

        var _t = $(this);

        if(_t.attr('data-aux-name')=='feed_mode'){

            _t.parent().parent().parent().attr('data-feed_mode',_t.val());

            if(_t.val()=='manual'){
                $('.dzs-tabs-meta-item').each(function(){
                    var _t = $(this);

                    if(_t.get(0) && _t.get(0).api_handle_resize){

                        // console.info('_t.get(0).api_handle_resize - ',_t.get(0).api_handle_resize, _t);
                        setTimeout(function(_t2){
                            _t2.get(0).api_handle_resize();
                        }, 500,_t);

                    }
                })
            }
        }

        var _ca = $('*[name="term_meta['+_t.attr('data-aux-name')+']"]');
        console.info('_ca - ',_ca);
        _ca.val(_t.val());
    });




});