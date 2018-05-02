jQuery(document).ready(function($){
    //return;
     // Create the media frame.

    setTimeout(reskin_select, 10);
    $(document).undelegate(".select-wrapper select", "change");
    $(document).delegate(".select-wrapper select", "change",  change_select);


    $(document).on('click','.quick-edit-adarray, .quick-edit-qualityarray', handle_mouse);
    $(document).on('change','.wpb-input[name="db"]', handle_input);
    $(document).on('submit','.delete-all-settings', handle_input);


    $(document).on('change.dzsdepe', '.dzs-dependency-field,*[name="0-settings-vpconfig"]',handle_change);



    setTimeout(function(){
//        console.info(jQuery(".refresh-thumbnail-yt-vim"))

        // console.info('ia', $('.dzs-dependency-field'));
        $('.dzs-dependency-field').trigger('change');
    }, 1000);




    function handle_change(e){

        //console.info(e);

        var _t = $(this);
        if(_t.hasClass('dzs-dependency-field')){
            // console.info("ceva");
            check_dependency_settings();
        }


        if(_t.attr('name')=='0-settings-vpconfig'){


            var ind = 0;

            _t.children().each(function(){
                var _t2 = $(this);

                // console.info(_t2);
                if(_t2.prop('selected')){
                    ind = _t2.parent().children().index(_t2) - 1;
                    return false;
                }
            });

            $('#quick-edit').attr('href', add_query_arg($('#quick-edit').attr('href'),'currslider',ind));
            // $('#quick-edit').attr('href', add_query_arg($('#quick-edit').attr('href'),'dbname',$('*[name=dzsvg_selectdb]').val()));
            // console.info(ind);

        }

    }




    function check_dependency_settings(){
        $('*[data-dependency]').each(function(){
            var _t = $(this);


            //console.info(_t, _t.attr('data-dependency'));


            var margs = {
                target_attribute: 'name'
            }


            var str_dependency = _t.attr('data-dependency');
            str_dependency = str_dependency.replace(/{{quot}}/g, '"');
            var dep_arr = []


            try{
                dep_arr = JSON.parse(str_dependency);

                var target_attribute = margs.target_attribute;

                var target_con = $(document);

                //console.warn(dep_arr);

                if(dep_arr[0]){
                    var _c = null;


                    if(dep_arr[0].lab){
                        _c = $('*[name="'+dep_arr[0].lab+'"]:not(.fake-input)').eq(0);
                    }
                    if(dep_arr[0].label){
                        _c = $('*[name="'+dep_arr[0].label+'"]:not(.fake-input)').eq(0);
                    }
                    if(dep_arr[0].element){
                        _c = $('*[name="'+dep_arr[0].element+'"]:not(.fake-input)').eq(0);
                    }



                    // console.info('_c - ',_c, dep_arr[0].label, dep_arr,str_dependency);


                    if(_c){

                        var cval = _c.val();

                        // console.info(_c, dep_arr[0].val);

                        var sw_show = false;


                        if(dep_arr[0].val){

                            for(var i3 in dep_arr[0].val) {
                                if (_c.val() == dep_arr[0].val[i3]) {
                                    sw_show = true;
                                    break;

                                }
                            }
                        }

                        if(dep_arr.relation){



                            // console.error(dep_arr.relation);

                            for(var i in dep_arr){
                                if(i=='relation'){
                                    continue;
                                }


                                if(dep_arr[i].value){
                                    if(dep_arr.relation=='AND'){
                                        sw_show=false;
                                    }



                                    if(dep_arr[0].element){
                                        _c = target_con.find('*['+target_attribute+'="'+dep_arr[i].element+'"]:not(.fake-input)').eq(0);
                                    }


                                    for(var i3 in dep_arr[i].value) {


                                        // console.info('_c.val() -  ',_c.val(), dep_arr[i].value[i3]);
                                        if (_c.val() == dep_arr[i].value[i3]) {


                                            if(_c.attr('type')=='checkbox'){
                                                if(_c.val() == dep_arr[i].value[i3] && _c.prop('checked')){

                                                    sw_show = true;
                                                }
                                            }else{

                                                sw_show = true;
                                            }

                                            break;

                                        }


                                        if(dep_arr[i].value[i3]=='anything_but_blank' && cval){

                                            sw_show=true;
                                            break;
                                        }
                                    }

                                    if(dep_arr.relation=='AND'){
                                        if(sw_show==false){
                                            break;
                                        }
                                    }
                                    // console.info('sw_show - ',sw_show);
                                }

                            }

                        }else{

                            if(dep_arr[0].value){

                                for(var i3 in dep_arr[0].value) {
                                    if (_c.val() == dep_arr[0].value[i3]) {


                                        if(_c.attr('type')=='checkbox'){
                                            if(_c.val() == dep_arr[0].value[i3] && _c.prop('checked')){

                                                sw_show = true;
                                            }
                                        }else{

                                            sw_show = true;
                                        }

                                        break;

                                    }


                                    if(dep_arr[0].value[i3]=='anything_but_blank' && cval){

                                        sw_show=true;
                                        break;
                                    }
                                }
                            }
                        }

                        if(sw_show){
                            _t.show();
                        }else{
                            _t.hide();
                        }
                    }


                }
            }catch(err){
                console.error('json dependency error - ',str_dependency);
                console.error(err);
            }
        })
    }


    function handle_mouse(e) {
        var _t = $(this);


        if (e.type == 'click') {



            if(_t.hasClass('quick-edit-adarray')){
                // console.info("ceva");
                var url3 = dzsvg_settings.ad_builder_url;


                window.ad_target_field = _t.prev();
                if(_t.prev().val()){
                    url3+='&adstart='+encodeURIComponent(_t.prev().val());
                }

                window.open_ultibox(null,{


                    type:'iframe'
                    ,source: url3
                    ,scaling:'fill' // -- this is the under description
                    ,suggested_width:'800' // -- this is the under description
                    ,suggested_height:'700' // -- this is the under description
                    ,item: null // -- we can pass the items from here too

                });


                return false;
            }

            if(_t.hasClass('quick-edit-qualityarray')){
                // console.info("ceva");
                var url3 = dzsvg_settings.quality_builder_url;


                window.quality_target_field = _t.prev();
                if(_t.prev().val()){
                    url3+='&qualitystart='+encodeURIComponent(_t.prev().val());
                }

                window.open_ultibox(null,{


                    type:'iframe'
                    ,source: url3
                    ,scaling:'fill' // -- this is the under description
                    ,suggested_width:'800' // -- this is the under description
                    ,suggested_height:'700' // -- this is the under description
                    ,item: null // -- we can pass the items from here too

                });


                return false;
            }
        }
    }

    function handle_input(e){
        var _t = $(this);


        if(e.type=='change'){
            if(_t.hasClass('wpb-input')){

                var mainarray = _t.val();
                var data = {
                    action: 'dzsvg_get_db_gals',
                    postdata: mainarray
                };
                jQuery.post(ajaxurl, data, function(response) {
                    if(window.console !=undefined ){  console.log('Got this from the server: ' + response); }
                    jQuery('#save-ajax-loading').css('opacity', '0');

                    var aux = '';
                    var auxa = response.split(';');
                    for(i=0;i<auxa.length;i++){
                        aux+='<option>'+auxa[i]+'</option>'
                    }
                    jQuery('.wpb-input[name=id]').html(aux);
                    jQuery('.wpb-input[name=id]').trigger('change');
                    jQuery('.wpb-input[name=slider]').html(aux);
                    jQuery('.wpb-input[name=slider]').trigger('change');

                });
            }
        }
        if(e.type=='submit'){
            if(_t.hasClass('delete-all-settings')){


                var r = confirm("Are you sure you want to delete all video gallery data ? ");

                if(r){

                }else{
                    return false;
                }
            }
        }
    }







    $(document).off('click.dzswup','.dzs-wordpress-uploader');
    $(document).on('click.dzswup','.dzs-wordpress-uploader', function(e){
        var _t = $(this);
        var _targetInput = _t.prev();

        var searched_type = '';

        if(_targetInput.hasClass('upload-type-audio')){
            searched_type = 'audio';
        }
        if(_targetInput.hasClass('upload-type-video')){
            searched_type = 'video';
        }
        if(_targetInput.hasClass('upload-type-image')){
            searched_type = 'image';
        }


        frame = wp.media.frames.dzsp_addimage = wp.media({
            title: "Insert Media",
            library: {
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
        frame.on( 'select', function() {
            // Grab the selected attachment.
            var attachment = frame.state().get('selection').first();

            //console.log(attachment.attributes.url);
            var arg = attachment.attributes.url;

            // console.info(attachment);
            if(_t.hasClass('insert-id')){
                arg = attachment.attributes.id;
            }

            _targetInput.val(arg);
            _targetInput.trigger('change');
            // _targetInput.trigger('keyup');

            // console.info('_targetInput - ',_targetInput);
//            frame.close();
        });

        // Finally, open the modal.
        frame.open();

        e.stopPropagation();
        e.preventDefault();
        return false;
    });










    // console.info('hmm - ',$('.dzsvg-wordpress-uploader'));

    $(document).off('click','.dzsvg-wordpress-uploader');
    $(document).on('click','.dzsvg-wordpress-uploader', function(e){
        var _t = $(this);
        var _targetInput = _t.prev();
        var _targetInputTitle = null;


        var _con = _t.parent();


        if(_con.find('.upload-target-prev').length){
            _targetInput = _con.find('.upload-target-prev').eq(0);
        }
        if(_con.find('.upload-target-title').length){
            _targetInputTitle = _con.find('.upload-target-title').eq(0);
        }

        var searched_type = '';

        if (_targetInput.hasClass('upload-type-audio')) {
            searched_type = 'audio';
        }
        if (_targetInput.hasClass('upload-type-image')) {
            searched_type = 'image';
        }
        if (_targetInput.hasClass('upload-type-video')) {
            searched_type = 'video';
        }


        if (typeof wp != 'undefined' && typeof wp.media != 'undefined') {
            var uploader_frame = wp.media.frames.dzsap_addplayer = wp.media({
                // Set the title of the modal.
                title: "Insert Media Modal",
                multiple: true,
                // Tell the modal to show only images.
                library: {
                    type: searched_type
                },

                // Customize the submit button.
                button: {
                    // Set the text of the button.
                    text: "Insert Media",
                    // Tell the button not to close the modal, since we're
                    // going to refresh the page when the image is selected.
                    close: false
                }
            });

            // When an image is selected, run a callback.
            uploader_frame.on('select', function () {
                //console.info(uploader_frame.state().get('selection'), uploader_frame.state().get('selection').length, uploader_frame.state().get('selection')._source);
                var attachment = uploader_frame.state().get('selection').first();

                //console.log(attachment.attributes, $('*[name*="video-player-config"]'));


                if (_targetInput.hasClass('upload-prop-id')) {
                    _targetInput.val(attachment.attributes.id);
                } else {
                    _targetInput.val(attachment.attributes.url);

                }


                if(_targetInputTitle){
                    _targetInputTitle.val(attachment.attributes.title);
                }


                _targetInput.trigger('change');
                uploader_frame.close();
            });

            // Finally, open the modal.
            uploader_frame.open();
        }

        return false;
    });





    $(document).off('click','.dzs-btn-add-media-att');
    $(document).on('click','.dzs-btn-add-media-att',  function(){
        var _t = $(this);

        var args = {
            title: 'Add Item',
            button: {
                text: 'Select'
            },
            multiple: false
        };

        if(_t.attr('data-library_type')){
            args.library = {
                'type':_t.attr('data-library_type')
            }
        }

        console.info(_t);

        var item_gallery_frame = wp.media.frames.downloadable_file = wp.media(args);

        item_gallery_frame.on( 'select', function() {

            var selection = item_gallery_frame.state().get('selection');
            selection = selection.toJSON();

            var ik=0;
            for(ik=0;ik<selection.length;ik++){

                var _c = selection[ik];
                //console.info(_c);
                if(_c.id==undefined){
                    continue;
                }

                if(_t.hasClass('button-setting-input-url')){

                    _t.parent().parent().find('input').eq(0).val(_c.url);
                }else{

                    _t.parent().parent().find('input').eq(0).val(_c.id);
                }


                _t.parent().parent().find('input').eq(0).trigger('change');

            }
        });



        // Finally, open the modal.
        item_gallery_frame.open();

        return false;
    });



    function change_select(){
        var selval = ($(this).find(':selected').text());
        $(this).parent().children('span').text(selval);
    }
    function reskin_select(){
        for(i=0;i<$('select').length;i++){
            var _cache = $('select').eq(i);
            //console.log(_cache.parent().attr('class'));

            if(_cache.hasClass('styleme')==false || _cache.parent().hasClass('select_wrapper') || _cache.parent().hasClass('select-wrapper')){
                continue;
            }
            var sel = (_cache.find(':selected'));
            _cache.wrap('<div class="select-wrapper"></div>')
            _cache.parent().prepend('<span>' + sel.text() + '</span>')
        }



    }


    var aux =window.location.href;


    if(aux.indexOf('plugins.php')>-1 || (aux.indexOf('index.php')>-1 && aux.indexOf('?')==-1 ) ){



        setTimeout(function(){
            jQuery.get( "http://zoomthe.me/cronjobs/cache/dzsvg_get_version.static.html", function( data ) {

//            console.info(data);
                var newvrs = Number(data);
                if(newvrs > Number(dzsvg_settings.version)){

                    console.info('newvrs - ',newvrs, Number(dzsvg_settings.version));


                    if($('#welcome-panel').length){


                        var _c = $('#welcome-panel');


                        _c.before('<div id="welcome-panel" class="dzsvg-update-available welcome-panel">\n' +
                            '<input type="hidden" id="welcomepanelnonce" name="welcomepanelnonce" value="5855c9d8b6">\t\t<a class="welcome-panel-close" href="http://devsite/wpfactory/dzsvg/wp-admin/?welcome=0" aria-label="Dismiss the welcome panel">Dismiss</a>\n' +
                            '\t\t\t<div class="welcome-panel-content"><a class="update-available" href="admin.php?page=dzsvg-autoupdater" aria-label="Update video gallery">Update available</a> for <strong>Video Gallery Wordpress</strong>\n' +

                            '\t</div>\n' +
                            '\t\t</div>')


                    }else{
                        if($('tr[data-slug=dzs-video-gallery]').length){

                            var _c = $('tr[data-slug=dzs-video-gallery]').eq(0);

                            _c.find('.row-actions').after('<div class="row-actions visible"><span class="deactivate"><a class="update-available" href="admin.php?page=dzsvg-autoupdater" aria-label="Update video gallery">Update available</a></span></div>');
                        }else{

                            if(jQuery('.version-number').length){
                                jQuery('.version-number').append('<span class="new-version info-con" style="width: auto;"> <span class="new-version-text">/ new version '+data+'</span><div class="sidenote">Download the new version by going to your CodeCanyon accound and accessing the Downloads tab.</div></div> </span>')

                                if($('#the-list > #dzs-video-gallery').next().hasClass('plugin-update-tr')==false){
                                    $('#the-list > #dzs-video-gallery').addClass('update');
                                    $('#the-list > #dzs-video-gallery').after('<tr class="plugin-update-tr"><td colspan="3" class="plugin-update colspanchange"><div class="update-message">There is a new version of DZS Video Gallery available. <form action="admin.php?page=dzsvg-autoupdater" class="mainsettings" method="post"> &nbsp; <br> <button class="button-primary" name="action" value="dzsvg_update_request">Update</button></form></td></tr>');
                                }
                            }
                        }

                    }

                }
            });
        }, 300);
    }

    if(aux.indexOf('&dzsvg_purchase_remove_binded=on')>-1){

        aux = aux.replace('&dzsvg_purchase_remove_binded=on','');
        var stateObj = { foo: "bar" };
        if(history){

            history.pushState(stateObj, null, aux);
        }
    }


















    $(document).on('click', '.refresh-main-thumb',function(){

        var _t = $(this);
        var _con = _t.parent().parent();



        console.info('_t - ', _t);
        console.warn('_con - ', _con);




        if(_con.hasClass('select-hidden-con')){

            if(_con.hasClass('mode_youtube')){
//            console.info(_con.find('.main-thumb').eq(0))
                if(_con.find('.main-thumb').eq(0).val()==''){
                    _con.find('.main-thumb').eq(0).val('http://img.youtube.com/vi/'+_con.find('.main-source').eq(0).val()+'/0.jpg');
                    _con.find('.main-thumb').eq(0).trigger('change');
                }
            }
            if(_con.hasClass('mode_vimeo')){
                if(_con.find('.main-thumb').eq(0).val()==''){
                    //_con.find('.main-thumb').eq(0).val('http://img.youtube.com/vi/'+_t.val()+'/0.jpg');



                    var data = {
                        action: 'get_vimeothumb',
                        postdata: _con.find('.main-source').eq(0).val()
                    };

                    jQuery.post(ajaxurl, data, function(response) {
                        //console.log(response);
                        if(window.console !=undefined ){
                            //console.log(response);
                        }
                        if(response.substr(0,6)=='error:'){
                            //console.log('ceva');
                            jQuery('.import-error').html(response.substr(7));
                            jQuery('.import-error').fadeIn('fast').delay(5000).fadeOut('slow');
                            return false;
                        }
                        _con.find('.main-thumb').eq(0).val(response);
                        _con.find('.main-thumb').eq(0).trigger('change');
                    });
                }
            };
        }else{
            _con = _t.parent().parent().parent();

            if(_con.hasClass('tab-content')){
                var type = _con.find('*[name=dzsvg_meta_item_type]').eq(0).val();

                // console.warn('type - ',type);

                if(type=='vimeo'){

                    var _thumbfield = _con.find('*[name=dzsvg_meta_thumb]').eq(0);

                    if(_thumbfield.val()==''){
                        //_con.find('.main-thumb').eq(0).val('http://img.youtube.com/vi/'+_t.val()+'/0.jpg');



                        var data = {
                            action: 'get_vimeothumb',
                            postdata: _con.find('*[name=dzsvg_meta_featured_media]').eq(0).val()
                        };

                        jQuery.post(ajaxurl, data, function(response) {
                            //console.log(response);
                            if(window.console !=undefined ){
                                //console.log(response);
                            }
                            if(response.substr(0,6)=='error:'){
                                //console.log('ceva');
                                jQuery('.import-error').html(response.substr(7));
                                jQuery('.import-error').fadeIn('fast').delay(5000).fadeOut('slow');
                                return false;
                            }
                            _thumbfield.val(response).trigger('change');

                        });
                    }
                }

                if(type=='youtube'){

                    var _thumbfield = _con.find('*[name=dzsvg_meta_thumb]').eq(0);

                    if(_thumbfield.val()==''){
                        //_con.find('.main-thumb').eq(0).val('http://img.youtube.com/vi/'+_t.val()+'/0.jpg');

                        var mainsrc = _con.find('*[name=dzsvg_meta_featured_media]').eq(0).val();


                        _thumbfield.val('http://img.youtube.com/vi/'+sanitize_to_youtube_id(mainsrc)+'/0.jpg').trigger('change');

                    }
                }
            }
        }

        return false;
    })


    function sanitize_to_youtube_id(arg){

        if(String(arg).indexOf('youtube.com/watch')){

            var dataSrc = arg;
            var auxa = String(dataSrc).split('youtube.com/watch?v=');
//                            console.info(auxa);

            console.info('auxa - ',auxa);
            if(auxa[1]){

                dataSrc = auxa[1];
                if(auxa[1].indexOf('&')>-1){
                    var auxb = String(auxa[1]).split('&');
                    console.info('auxb - ',auxb);
                    dataSrc = auxb[0];
                }



                return dataSrc;
            }
        }

        return arg;
    }



    function con_generate_buttons(){
        $('#generate-upload-page').bind('click', function(){
            var _t = $(this);

            _t.css('opacity',0.5);



            var data = {
                action: 'dzsvp_insert_upload_page'
                ,postdata: '1'
            };
            $.post(ajaxurl, data, function(response) {
                if(window.console != undefined){
                    console.log('Got this from the server: ' + response);
                }

                $('select[name=dzsvp_page_upload]').prepend('<optgroup label="Generated Pages"><option value="'+response+'">Upload</option></optgroup>')

                $('select[name=dzsvp_page_upload]').find('option').eq(0).prop('selected',true);
                $('select[name=dzsvp_page_upload]').trigger('change');

                _t.parent().parent().remove();

            });

            return false;
        })
    }

    con_generate_buttons();
    extra_skin_hiddenselect();

});





function extra_skin_hiddenselect(){
    for(i=0;i<jQuery('.select-hidden-metastyle').length;i++){
        var _t = jQuery('.select-hidden-metastyle').eq(i);
        if(_t.hasClass('inited')){
            continue;
        }
        //console.log(_t);
        _t.addClass('inited');
        _t.children('select').eq(0).bind('change', change_selecthidden);
        change_selecthidden(null, _t.children('select').eq(0));
        _t.find('.an-option').bind('click', click_anoption);
    }
    function change_selecthidden(e, arg){
        var _c = jQuery(this);
        if(arg!=undefined){
            _c = arg;
        }
        var _con = _c.parent();
        var selind = _c.children().index(_c.children(':selected'));
        var _slidercon = _con.parent().parent();
        //console.log(selind);
        _con.find('.an-option').removeClass('active');
        _con.find('.an-option').eq(selind).addClass('active');
        //console.log(_con);
        do_changemainsliderclass(_slidercon, selind);
    }
    function click_anoption(e){
        var _c = jQuery(this);
        var ind = _c.parent().children().index(_c);
        var _con = _c.parent().parent();
        var _slidercon = _con.parent().parent();
        _c.parent().children().removeClass('active');
        _c.addClass('active');
        _con.children('select').eq(0).children().removeAttr('selected');
        _con.children('select').eq(0).children().eq(ind).attr('selected', 'selected');
        do_changemainsliderclass(_slidercon, ind);
        //console.log(_c, ind, _con, _slidercon);
    }
    function do_changemainsliderclass(arg, argval){
        //extra function - handmade
        //console.log(arg, argval, arg.find('.mainsetting').eq(0).children().eq(argval).val());

        if(arg.hasClass('select-hidden-con')){
            arg.removeClass('mode_thumb'); arg.removeClass('mode_gallery');  arg.removeClass('mode_audio'); arg.removeClass('mode_video'); arg.removeClass('mode_youtube'); arg.removeClass('mode_vimeo'); arg.removeClass('mode_link'); arg.removeClass('mode_testimonial'); arg.removeClass('mode_link'); arg.removeClass('mode_twitter');

            arg.addClass('mode_' + arg.find('.mainsetting').eq(0).children().eq(argval).val());

        }
        if(arg.hasClass('item-settings-con')){
            arg.removeClass('type_youtube'); arg.removeClass('type_normal'); arg.removeClass('type_vimeo'); arg.removeClass('type_audio'); arg.removeClass('type_image'); arg.removeClass('type_link');

            if(argval==0){
                arg.addClass('mode_youtube')
            }
            if(argval==1){
                arg.addClass('mode_normal')
            }
            if(argval==2){
                arg.addClass('mode_vimeo')
            }
            if(argval==3){
                arg.addClass('mode_audio')
            }
            if(argval==4){
                arg.addClass('mode_image')
            }
            if(argval==5){
                arg.addClass('mode_link')
            }
        }
    }

}
