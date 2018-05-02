

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


jQuery(document).ready(function($){
  "use strict";
    var cthis = null;

    var the_player_id = 1;

    if (window.dzsuploader_single_init) {
        //console.info('ceva', $('span:not(.for-clone-item) .dzs-single-upload'));
        window.dzsuploader_single_init('.dzs-single-upload', {
            action_file_uploaded: action_file_uploaded
            , action_file_upload_start: action_file_upload_start
        });
    }


    $(document).on('change', '.submit-track-form select[name=type],.submit-track-form *[name=source]', handle_change);
    $(document).on('submit', 'form.submit-track-form', handle_change);
    $(document).on('click', '.cancel-upload-btn , .submit-track-form .btn-submit, .submit-track-form .single-submit-for-main-media', handle_click);


    // console.info("HMM");
    $(document).on('click', '.btn-like',click_like);

    $('.shortcode-upload').addClass('loaded');



    function click_like(){
        var _t = $(this);
        cthis = _t.parent().parent();

        the_player_id = $('.from-parse-videoitem').eq(0).attr('data-postid');

        console.info('click_like',cthis);

        if(cthis.hasClass('mainvp-con')==false){ console.info(cthis, ' not mainvp-con'); return false;  }
        // if(cthis.has(_t).length==0){  return; }

//        console.info(cthis, cthis.has(_t).length, _t.hasClass('active'));
        if(_t.hasClass('active')){
            ajax_retract_like();
        }else{
            ajax_submit_like();
        }
    }








    function ajax_submit_like(argp){
        //only handles ajax call + result
        var mainarg = argp;
        var data = {
            action: 'dzsvp_submit_like',
            postdata: mainarg,
            playerid: the_player_id
        };


        if(cthis.hasClass('mainvp-con')==false){ console.info(cthis, ' not mainvp-con'); return false;  }else{ };


        $.ajax({
            type: "POST",
            url: dzsvg_settings.site_url+'index.php?dzsvg_action=dzsvp_submit_like',
            data: data,
            success: function(response) {
                if(typeof window.console != "undefined" ){ console.log('Got this from the server: ' + response); }

                cthis.find('.btn-like').addClass('active');
                var auxlikes = cthis.find('.counter-likes .the-number').html();
                auxlikes = parseInt(auxlikes,10);
                auxlikes++;
                cthis.find('.counter-likes .the-number').html(auxlikes);
            },
            error:function(arg){
                if(typeof window.console != "undefined" ){ console.log('Got this from the server: ' + arg, arg); };



                cthis.find('.btn-like').addClass('active');
                var auxlikes = cthis.find('.counter-likes .the-number').html();
                auxlikes = parseInt(auxlikes,10);
                auxlikes++;
                cthis.find('.counter-likes .the-number').html(auxlikes);
            }
        });
    }
    function ajax_retract_like(argp){
        //only handles ajax call + result
        var mainarg = argp;
        var data = {
            action: 'dzsvp_retract_like',
            postdata: mainarg,
            playerid: the_player_id
        };



        $.ajax({
            type: "POST",
            url: dzsvg_settings.site_url+'index.php?dzsvg_action=dzsvp_retract_like',
            data: data,
            success: function(response) {
                if(typeof window.console != "undefined" ){ console.log('Got this from the server: ' + response); }

                cthis.find('.btn-like').removeClass('active');
                var auxlikes = cthis.find('.counter-likes .the-number').html();
                auxlikes = parseInt(auxlikes,10);
                auxlikes--;
                cthis.find('.counter-likes .the-number').html(auxlikes);
            },
            error:function(arg){
                if(typeof window.console != "undefined" ){ console.log('Got this from the server: ' + arg, arg); };

                cthis.find('.btn-like').removeClass('active');
                var auxlikes = cthis.find('.counter-likes .the-number').html();
                auxlikes = parseInt(auxlikes,10);
                auxlikes--;
                cthis.find('.counter-likes .the-number').html(auxlikes);
            }
        });
    }



    function show_notice(response) {


        var _feedbacker = $('.feedbacker').eq(0);



        console.warn(_feedbacker);

        if(typeof response=='object'){
            if(response.report=='success'){

                _feedbacker.removeClass('is-error');
                _feedbacker.addClass('active');
                _feedbacker.html(response.text);
                _feedbacker.fadeIn('fast');

                setTimeout(function () {

                    _feedbacker.fadeOut('slow');
                    _feedbacker.removeClass('active');
                }, 1500)
            }
            if(response.report=='error'){

                _feedbacker.addClass('is-error');
                _feedbacker.html(response.text);
                _feedbacker.fadeIn('fast');
                _feedbacker.addClass('active');

                setTimeout(function () {

                    _feedbacker.fadeOut('slow');
                    _feedbacker.removeClass('active');
                }, 1500)
            }
        }else{
            if (response.indexOf('error -') == 0) {
                _feedbacker.addClass('is-error');
                _feedbacker.html(response.substr(7));
                // _feedbacker.fadeIn('fast');
                _feedbacker.addClass('active');

                setTimeout(function () {

                    // _feedbacker.fadeOut('slow');
                    _feedbacker.removeClass('active');
                }, 1500)
            }
            if (response.indexOf('success -') == 0) {
                _feedbacker.removeClass('is-error');
                _feedbacker.html(response.substr(9));
                // _feedbacker.fadeIn('fast');

                _feedbacker.addClass('active');
                setTimeout(function () {

                    // _feedbacker.fadeOut('slow');
                    _feedbacker.removeClass('active');
                }, 1500)
            }
        }


    }


    function handle_change(e) {

        var _t = $(this);
        var _con = null;
        // console.info('change - ',_t);

        if (e.type == 'submit') {


            if(_t.hasClass('submit-track-form')){

                console.info('trying to submit');


                if(_t.find('*[name=source]').eq(0).val()==''){


                    show_notice("success - Source field cannot be blank");
                    return false;

                }

                if(_t.find('*[name=title]').eq(0).val()==''){


                    show_notice("success - Title field cannot be blank");
                    return false;

                }

            }
        }
        if (e.type == 'change') {
            if (_t.attr('name') == 'is_buyable') {
                //console.info(_t, _t.prop('checked'));

                if (_t.prop('checked')) {

                    $('.price-conglomerate').addClass('active');
                } else {
                    // -- you can see typing is slow now ... lets see later...
                    $('.price-conglomerate').removeClass('active');
                }
            }
            if (_t.attr('name') == 'thumbnail') {
                //console.info(_t, _t.prop('checked'));

                if (_t.val()) {
                    if (_t.parent().find('.preview-thumb-con').length > 0) {
                        var _cach = _t.parent().find('.preview-thumb-con').eq(0);

                        _cach.addClass('has-image');
                        _cach.css('background-image', 'url(' + _t.val() + ')');
                    }
                }
            }
            if (_t.attr('name') == 'type') {
                //console.info(_t, _t.prop('checked'));

                if (_t.parent().parent().hasClass('submit-track-form')) {
                    _con = _t.parent().parent();


                    _con.removeClass('type-video type-youtube  type-vimeo ');

                    _con.addClass('type-' + _t.val());

                    var uploader_type = _t.val();
                    if (_t.val() == 'video') {
                        //_con.find('.the-real-uploader').removeAttr('multiple');
                    }

                    if (_t.val() == 'youtube') {
                        //_con.find('.the-real-uploader').attr('multiple', '');
                    }
                }
            }
            if (_t.attr('name') == 'source') {
                var _con = null;


                if(_t.parent().parent().parent().hasClass('submit-track-form')){
                    _con = _t.parent().parent().parent();
                }
                if(_con.hasClass('type-youtube') || _con.hasClass('type-vimeo')){
                    // return false;
                }


                upload_hide_upload_field(_t, {'call_from': 'change source'});
            }
        }
    }




    function cancel_submit(_t){


        var _c = $('.dzs-upload-con').eq(0);

        _c.removeClass('disabling');
        _c.css('height', 'auto');

//                console.info(_c, _c.height());

        var _con = null;

        if(_t.parent().parent().parent().hasClass('submit-track-form')){
            _con = _t.parent().parent().parent();
        }


        if(_con){
            _con.removeClass('phase2');
            //_con.slideUp('fast');
        }

        var _cach = _t.parent().parent();
        if(_cach.hasClass('parameters-con')){
            _cach.find('.main-upload-options-con').eq(0).removeClass('active').slideUp('fast');
        }

    }
    function handle_click(e) {

        var _t = $(this);
        var _con = null;
        //console.info(_t);

        if (e.type == 'click') {


            if (_t.hasClass('cancel-upload-btn')) {
                //console.log('ceva');

                cancel_submit(_t);


                return false;

            }
            if (_t.hasClass('btn-submit')) {
                //console.log('ceva');



                var _c = $('.id-upload-mp3').eq(0);
                upload_hide_upload_field(_c, {'call_from': 'btn-submit'});
                return false;
            }
            if (_t.hasClass('single-submit-for-main-media')) {
                //console.log('ceva');



                var _c = $('.id-upload-mp3').eq(0);
                upload_hide_upload_field(_c, {'call_from': 'submit_for_main_media'});
                return false;
            }


        }
    }






    function init_tinymces(_con){


        // console.info(_con);

        _con.find('.with-tinymce').each(function(){
            var _t = $(this);
            // console.info(_t);

            var _con = _t.parent().parent().parent().parent();



            var trackid = (_con.find('*[name=track_id]').eq(0).val());

            // console.warn(trackid);
            _t.attr('id','fortinymce'+trackid);
            init_try_tinymce(_t);
        })
    }

    function upload_hide_upload_field(arg, pargs) {



        var margs = {
            'call_from':'default'
        }


        if(pargs){
            margs = $.extend(margs,pargs);
        }



        var _t = arg;
        var _con = null;
        var type = 'video';
        var _mainUploadOptionsCon = null;

        if (_t.parent().parent().parent().hasClass('submit-track-form')) {
            _con = _t.parent().parent().parent();


            _mainUploadOptionsCon = _con.find('.main-upload-options-con');


        }

        var tval = '';

        if(_t.val){
            tval = _t.val();
        }


        console.info('upload_hide_upload_field - ',arg, margs,' new val - ',tval, ' _t  - ', _t, _t.prop('checked'), _con);









        if(_con){


            var source = _con.find('*[name=source]').eq(0).val();



            if(source){

            }else{



                if(_con.hasClass('type-youtube') || _con.hasClass('type-vimeo')){

                    show_notice('error - '+'Input a id or link')
                    return false;
                }
            }




            if(_con.hasClass('type-youtube')){
                type = 'youtube';
            }

            if(_con.hasClass('type-vimeo')){
                type = 'vimeo';
            }

            // - subitting
            _mainUploadOptionsCon.addClass('active');
            _mainUploadOptionsCon.show();

            var ch = _mainUploadOptionsCon.height();

            _mainUploadOptionsCon.css('height','0');


            _mainUploadOptionsCon.animate({
                'height': ch
            }, {
                queue: false
                , duration: 300
                ,complete:function(){
                    $(this).css('height','auto');
                }
            });


            var _auxcon = _con;
            setTimeout(function(){

                // console.info(_auxcon.find('.dzs-tabs').get(0));

                if(_auxcon.find('.dzs-tabs').get(0).api_handle_resize){

                    _auxcon.find('.dzs-tabs').get(0).api_handle_resize();
                }
            },50);
            setTimeout(function(){

                // console.info(_auxcon.find('.dzs-tabs').get(0));

                if(_auxcon.find('.dzs-tabs').get(0).api_handle_resize){

                    _auxcon.find('.dzs-tabs').get(0).api_handle_resize();
                    _auxcon.find('.dzs-tabs').eq(0).find('.tab-content').eq(0).addClass('active');
                }
            },150);
            setTimeout(function(){

                _auxcon.addClass('phase2');
            },100);




            if(window.dzsvp_try_to_generate_image=='on'){



                $('.dzs-single-upload-preview-img').addClass('generating-thumb');


                // source = _auxcon.find('*[name=source]').eq(0).val();


                if(type=='video'){



                    var aux43 = '<div class="screenshot-canvas-con';


                    if(window.dzsvg_settings && window.dzsvg_settings.debug_mode=='on'){
                        aux43+=' debug-mode';
                    }



                    aux43+='"><video width="600" height="400" src="'+source+'"></video><canvas width="600" height="400"></canvas></div>';



                    _auxcon.after(aux43);


                    var _c = _auxcon.next();
                    var canvas = _c.find('canvas').get(0);
                    var ctx    = canvas.getContext('2d');
                    var video  = _c.find('video').get(0);

                    _c.find('video').get(0).currentTime = 5;


                    setTimeout(function(){

                        ctx.drawImage(video, 0, 0);



                        var xhr = null;

                        var canvasData = canvas.toDataURL("image/png");
                        var xmlHttpReq = false;
                        if (window.XMLHttpRequest) {
                            xhr = new XMLHttpRequest();
                        }

                        xhr.open('POST', dzsvg_settings.dzsvg_site_url+'?dzsvg_action=savescreenshot&name='+source, false);
                        xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                        xhr.onreadystatechange = function() {
                            // console.log(ajax.responseText);

                            var response = xhr.responseText;

                            if (xhr.readyState==4 && xhr.status==200){

                                _auxcon.find('*[name=thumbnail]').val(response);
                                _auxcon.find('*[name=thumbnail]').trigger('change');
                                _auxcon.find('.dzs-single-upload-preview-img').css('background-image', 'url('+response+')');
                                $('.dzs-single-upload-preview-img').removeClass('generating-thumb');

                                $('.screenshot-canvas-con:not(.debug-mode)').remove();

                            }
                        }
                        xhr.send("imgData="+canvasData);

                    },4500);

                }



                if(type=='youtube'){



                    if(source.indexOf('youtube.com')>-1){
                        source = get_query_arg(source, 'v');
                    }else{
                        if(source.indexOf('youtu.be/')>-1){
                            source = source.replace('https://youtu.be/','');
                        }
                    }


                    var response = 'https://img.youtube.com/vi/' + source + '/0.jpg';
                    // $thumb = "";

                    _auxcon.find('*[name=thumbnail]').val(response);
                    _auxcon.find('*[name=thumbnail]').trigger('change');
                    _auxcon.find('.dzs-single-upload-preview-img').css('background-image', 'url('+response+')');
                    $('.dzs-single-upload-preview-img').removeClass('generating-thumb');


                }
                if(type=='vimeo'){

                    source = source.replace('https://vimeo.com/','');
                    var data = {
                        action: 'nothing'
                    };


                    $.ajax({
                        type: "POST",
                        url: dzsvg_settings.dzsvg_site_url+'?dzsvg_action=get_vimeo_thumb&vimeo_id='+source,
                        data: data,
                        success: function (response) {
                            if (typeof window.console != "undefined") {
                                console.log('Got this from the server: ' + response);
                            }

                            var _auxcon = $(document);

                            console.info("_con.find('*[name=thumbnail]') - ",_con.find('*[name=thumbnail]'));
                            _auxcon.find('*[name=thumbnail]').val(response);
                            _auxcon.find('*[name=thumbnail]').trigger('change');
                            _auxcon.find('.dzs-single-upload-preview-img').css('background-image', 'url('+response+')');
                            $('.dzs-single-upload-preview-img').removeClass('generating-thumb');

                        }
                    })
                }

            }


        }

        if (_t.parent().hasClass('dzs-upload-con')) {
            _con = _t.parent();

            _con.addClass('disabling');

            _con.animate({
                'height': 0
            }, {
                queue: false
                , duration: 300
            });
        }
    }
    function init_try_tinymce(_c){

        if(_c.hasClass('tinymce-activated')){
            return false;
        }

        if(window.tinyMCE){


            console.info("HMM trying to init tinymce");
            tinyMCE.baseURL = window.dzsvp_plugin_url+'tinymce';
            tinyMCE.init({
                selector: '#'+_c.attr('id')
                ,base: window.dzsvp_plugin_url+'tinymce/'
                ,menubar: false
                ,toolbar: 'styleselect | bold italic | link image code bullist numlist'
                ,plugins: 'code,lists,link,textcolor,wordcount'
                ,selection_toolbar: 'bold italic | quicklink h2 h3 blockquote code fontsize '
            });

            _c.addClass('tinymce-activated');


        }else{

            if(window.tinymce_trying_to_load!=true){

                window.tinymce_trying_to_load = true;

                $.getScript(window.dzsvp_plugin_url+'tinymce/tinymce.min.js', function (data, textStatus, jqxhr) {

                    init_try_tinymce(_c);
                })
            }
        }
    }





    function action_file_upload_start(pfile, pargs) {


        var uploader_type='video';

        console.info('action_file_upload_start from PORTAL uploader_type ( ' + uploader_type + ' ) - ', pfile, pargs);


        if (uploader_type == 'video') {
            var filename = String(pfile.name).toLowerCase();

            console.warn("String(filename) - ",String(filename));
            console.warn("String(filename).indexOf('.m4v') - ",String(filename).indexOf('.m4v'));
            console.warn("String(filename).indexOf('.mov') - ",String(filename).indexOf('.mov'));



            if (String(filename).indexOf('.mp4') > -1 || String(filename).indexOf('.m4v') > -1 || String(filename).indexOf('.mov') > -1) {
                upload_hide_upload_field($('input[name="source"]'), {'call_from': 'action_file_upload_start'});
                $('.main-upload-options').addClass('loader-active');
                window.dzs_uploader_force_progress($('.main-upload-options'));
            }else{

                show_notice('error - '+'Only videos are allowed!')

                window.dzsuploader_stop = true;

            }
            var name = String(filename);
            name = name.replace('.mp3', '');
            name = name.replace('.mp4', '');
            name = name.replace('.m4v', '');
            name = name.replace('.mov', '');


            if(pargs.cthis.prev().hasClass('id-upload-mp3')){

                $('*[name="title"]').val(name);
            }
        }

        if (uploader_type == 'album') {
            if (String(filename).indexOf('.mp3') > -1) {


                var name = String(filename);
                name = name.replace('.mp3', '');

                $('*[name="title"]').val(name);

                upload_hide_upload_field($('input[name="source"]'),  {'call_from': 'change_album'});


                $('.upload-track-options-con').append('<div class="upload-track-options"><input type="hidden" name="track_source[]"><div class="preloader-bar dzs-upload--progress--barprog"></div><div class="handle-con"><i class="fa fa-bars"></i></div><div class="input-con"><input type="text" name="track_title[]"> </div><div class="delete-track-con"><i class="fa fa-times"></i></div></div>');
                var _c = $('.upload-track-options-con').find('.upload-track-options').last();
                _c.addClass('loader-active');
                window.dzs_uploader_force_progress(_c);

                _c.find('input[name*="track_title"]').val(name);
            }
        }



        var _c = $('.main-upload-options').eq(0);


        console.info('_C - ',_c);
        init_tinymces(_c);

        _c.css('height', 'auto');

        var h = (_c.height());


        _c.css('height','1px');

        setTimeout(function(){
            _c.animate({
                'height':h
            },{
                queue:false
                ,duration: 300
                ,complete:function(){
                    $(this).css('height', 'auto');
                }
            });

            _c.addClass('main-option-active');
        },100);
        _c.addClass('main-option-active');


        //show_notice(arg);
        //


    }



    function action_file_uploaded(argresp, pargs, matches) {

        var uploader_type = 'video';
        console.info('action_file_uploaded from PORTAL', argresp, pargs);





        if(argresp.report=='error'){
            show_notice(argresp);






            $('.cancel-upload-btn').eq(0).trigger('click');



        }


        // _c2.find('input[name*="track_source"]').eq(0).val(pargs.final_location);

        if (uploader_type == 'album') {

            var name = String(pargs.file.name);
            name = name.replace('.mp3', '');

            var _c = $('.upload-track-options-con').eq(0);
            _c.find('input[name*="track_title"]').each(function(){
                var _t2 = $(this);

                if(name==_t2.val()){
                    var _c2 = _t2.parent().parent();

                    _c2.find('input[name*="track_source"]').eq(0).val(pargs.final_location);
                }

                //console.info('testing name', name, _t2.val());
            })
        }




        var _c = $('.main-upload-options').eq(0);
        _c.addClass('main-option-active');





        //show_notice(arg);
        //

    }
    $('.simple-fade-carousel').each(function(){
        var cthis = $(this);
        var currNr = 0 ;
        var time_interval = 5000;
        var int_changer = 0;

        cthis.children().eq(currNr).addClass('active');

        int_changer = setInterval(function(){
            currNr++;
            if(currNr>=cthis.children().length){
                currNr=0;
            };
            cthis.children().removeClass('active');
            cthis.children().eq(currNr).addClass('active');

        }, time_interval);

    })











    function load_statistics(_con){


        if(window.google && window.google.charts ){


            if(window.google.visualization){






                console.info("NOW APPLYING", _con.find('.stats-btn').eq(0).attr('data-playerid'));

                var data = {
                    action: 'dzsvg_ajax_get_statistics_html',
                    postdata: _con.find('.stats-btn').eq(0).attr('data-playerid')
                };


                $.ajax({
                    type: "POST",
                    url: window.dzsvg_site_url+'/?dzsvg_action=load_charts_html',
                    data: data,
                    success: function (response) {
                        if (typeof window.console != "undefined") {
                            console.groupCollapsed('Submit message Got this from the server:');
                            console.log(' ' + response);
                            console.groupEnd();
                        }


                        _con.append('<div class="stats-container">'+response+'</div>')

                        setTimeout(function(){

                            var _c = _con.find('.stats-container');
                            // _c.css('height','auto');
                            // var auxh = (_c.outerHeight());

                            // _c.css('height',0);

                            // console.info('try to get real height - ', _c.get(0).scrollHeight)
                            _c.addClass('loaded');



                            var auxr = /<div class="hidden-data">(.*?)<\/div>/g;
                            var aux = auxr.exec(response);
                            // console.log('aux - ',aux);

                            var aux_resp = '';
                            if(aux[1]){
                                aux_resp = aux[1];
                            }





                            var resp_arr = [];
                            // console.info(aux_resp);

                            try{
                                resp_arr = JSON.parse(aux_resp);
                            }catch(err){

                            }
                            // console.warn(resp_arr);



                            var arr = [

                            ];


                            arr[0] = [];
                            for(var i in resp_arr['labels']){


                                // console.info('i - ',i, resp_arr['labels'][i]);

                                arr[0].push(resp_arr['labels'][i]);
                            }
                            for(var i in resp_arr['lastdays']){





                                i=parseInt(i,10);

                                arr[i+1] = [];
                                for(var j in resp_arr['lastdays'][i]){

                                    j=parseInt(j,10);
                                    // console.info('i - ',i);
                                    // console.info('j - ',j);

                                    // console.info('j - ',j, resp_arr['lastdays'][i][j]);

                                    var val4 = (resp_arr['lastdays'][i][j]);

                                    if(j!=0){

                                        val4 = parseFloat(val4);
                                    }
                                    // val = parseFloat(val);

                                    if(isNaN(val4)==false){
                                        resp_arr['lastdays'][i][j] = val4;
                                    }
                                    arr[i+1].push(resp_arr['lastdays'][i][j]);
                                }

                            }



                            // console.info('stats arr - ',arr);
                            var data = google.visualization.arrayToDataTable(arr);

                            var options = {

                                backgroundColor: '#444444'
                                ,height: '300'
                                ,legend: { position: 'top', maxLines: 1 }
                                ,chart: {
                                    title: 'Track Performance'
                                    ,backgroundColor: '#444444'
                                }
                                ,chartArea:{
                                    backgroundColor: '#444444'
                                }
                                ,tooltip: { isHtml: true }
                            };
                            // var material = new google.charts.Bar(_con.find('.trackchart').get(0));
                            // material.draw(data, google.charts.Bar.convertOptions(options));

                            var chart = new google.visualization.AreaChart(_con.find('.trackchart').get(0));
                            chart.draw(data, options);













                            auxr = /<div class="hidden-data-time-watched">(.*?)<\/div>/g;

                            aux = auxr.exec(response);
                            // console.log('aux - ',aux);

                            aux_resp = '';
                            if(aux[1]){
                                aux_resp = aux[1];
                            }





                            resp_arr = [];
                            // console.info(aux_resp);

                            try{
                                resp_arr = JSON.parse(aux_resp);
                            }catch(err){

                            }
                            // console.warn(resp_arr);



                            arr = [

                            ];


                            arr[0] = [];
                            for(var i in resp_arr['labels']){


                                // console.info('i - ',i, resp_arr['labels'][i]);

                                arr[0].push(resp_arr['labels'][i]);
                            }
                            for(var i in resp_arr['lastdays']){





                                i=parseInt(i,10);

                                arr[i+1] = [];
                                for(var j in resp_arr['lastdays'][i]){

                                    j=parseInt(j,10);
                                    // console.info('i - ',i);
                                    // console.info('j - ',j);

                                    // console.info('j - ',j, resp_arr['lastdays'][i][j]);

                                    var val4 = (resp_arr['lastdays'][i][j]);

                                    if(j!=0){

                                        val4 = parseInt((parseFloat(val4) / 60),10);
                                    }
                                    // val = parseFloat(val);

                                    if(isNaN(val4)==false){
                                        resp_arr['lastdays'][i][j] = val4;
                                    }
                                    arr[i+1].push(resp_arr['lastdays'][i][j]);
                                }

                            }



                            // console.info('stats arr - ',arr);
                            data = google.visualization.arrayToDataTable(arr);

                            options = {

                                color: '#bcb36b'
                                , colors: ['#e0d365', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6']
                                ,backgroundColor: '#444444'
                                ,height: '300'
                                ,legend: { position: 'top', maxLines: 3 }
                                ,bar:{ groupWidth: "70%" }
                                ,chart: {
                                    title: 'Track Performance'
                                    ,backgroundColor: '#444444'
                                }
                                ,chartArea:{
                                    backgroundColor: '#444444'
                                }
                                ,tooltip: { isHtml: true }
                            };
                            // var material = new google.charts.Bar(_con.find('.trackchart').get(0));
                            // material.draw(data, google.charts.Bar.convertOptions(options));

                            // chart = new google.visualization.AreaChart(_con.find('.trackchart-time-watched').get(0));
                            // chart.draw(data, options);


                            var chart2 = new google.visualization.ColumnChart(_con.find('.trackchart-time-watched').get(0));
                            chart2.draw(data, options);















                            auxr = /<div class="hidden-data-month-viewed">(.*?)<\/div>/g;

                            aux = auxr.exec(response);
                            // console.log('aux - ',aux);

                            aux_resp = '';
                            if(aux[1]){
                                aux_resp = aux[1];
                            }





                            resp_arr = [];
                            // console.info(aux_resp);

                            try{
                                resp_arr = JSON.parse(aux_resp);
                            }catch(err){

                            }
                            // console.warn(resp_arr);



                            arr = [

                            ];


                            arr[0] = [];
                            for(var i in resp_arr['labels']){


                                // console.info('i - ',i, resp_arr['labels'][i]);

                                arr[0].push(resp_arr['labels'][i]);
                            }
                            for(var i in resp_arr['lastdays']){





                                i=parseInt(i,10);

                                arr[i+1] = [];
                                for(var j in resp_arr['lastdays'][i]){

                                    j=parseInt(j,10);
                                    // console.info('i - ',i);
                                    // console.info('j - ',j);

                                    // console.info('j - ',j, resp_arr['lastdays'][i][j]);

                                    var val4 = (resp_arr['lastdays'][i][j]);

                                    if(j!=0){

                                        val4 = parseFloat(val4) ;
                                    }
                                    // val = parseFloat(val);

                                    if(isNaN(val4)==false){
                                        resp_arr['lastdays'][i][j] = val4;
                                    }
                                    arr[i+1].push(resp_arr['lastdays'][i][j]);
                                }

                            }



                            // console.info('stats arr - ',arr);
                            data = google.visualization.arrayToDataTable(arr);

                            options = {

                                color: '#bcb36b'
                                , colors: ['#66a4e0', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6']
                                ,backgroundColor: '#444444'
                                ,height: '300'
                                ,legend: { position: 'top', maxLines: 3 }
                                ,bar:{ groupWidth: "70%" }
                                ,chart: {
                                    title: 'Track Performance'
                                    ,backgroundColor: '#444444'
                                }
                                ,chartArea:{
                                    backgroundColor: '#444444'
                                }
                                ,tooltip: { isHtml: true }
                            };
                            // var material = new google.charts.Bar(_con.find('.trackchart').get(0));
                            // material.draw(data, google.charts.Bar.convertOptions(options));

                            // chart = new google.visualization.AreaChart(_con.find('.trackchart-time-watched').get(0));
                            // chart.draw(data, options);


                            var chart3 = new google.visualization.ColumnChart(_con.find('.trackchart-month-viewed').get(0));
                            chart3.draw(data, options);







                            _c.slideDown("fast");

                            setTimeout(function(){

                                $(this).css('height','auto');
                            },400);




                            // _c.animate({
                            //     'height': auxh
                            // },{
                            //     queue:false
                            //     ,duration: 300
                            //     ,complete: function(){
                            //     }
                            // })
                        },100);


                    },
                    error: function (arg) {
                        if (typeof window.console != "undefined") {
                            console.log('Got this from the server: ' + arg, arg);
                        }
                        ;

                    }
                });





            }else{
                google.charts.load('current', {packages: ['corechart', 'bar']});
                google.charts.setOnLoadCallback(function(){
                    load_statistics(_con);
                });
            }






        }else{

            if(window.dzsvg_loading_google_charts){




            }else{



                var url = 'https://www.gstatic.com/charts/loader.js';
                //console.warn(scripts[i23], baseUrl, url);






                // console.info('load wavesurfer');
                $.ajax({
                    url: url,
                    dataType: "script",
                    success: function(arg) {
                        //console.info(arg);

                        // cthis.append('')





                        console.info('loaded charts');




                    }
                });


                window.dzsvg_loading_google_charts = true;
            }

            setTimeout(function(){
                load_statistics(_con)
            },1000);
        }

    }





    function draw_chart_for_con(_con) {
        var data = google.visualization.arrayToDataTable(
        );
    }




    $(document).on('click','.stats-btn', handle_mouse);
    function handle_mouse(e){

        var _t = $(this);
        if (_t.hasClass('stats-btn')) {



            var _con = _t.parent();

            if(_t.hasClass('disabled')){
                return false;
            }
            _t.addClass('disabled');
            setTimeout(function(){
                _t.removeClass('disabled')
            },2000)

            if(_con.find('.stats-container').length){

                _t.removeClass('active');
                _con.find('.stats-container').each(function(){
                    var _t2 = $(this);
                    _t2.addClass('transitioning-out').removeClass('loaded');

                    // _t2.animate({
                    //     'height':0
                    // },{queue:false
                    // ,duration: 300})


                    _t2.slideUp("fast");


                    setTimeout(function(){
                        _con.find('.stats-container.transitioning-out').remove()
                    },400)
                })
            }else{

                _t.addClass('active');
                load_statistics(_con);
            }

        }
    }
});



