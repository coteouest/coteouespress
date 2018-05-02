//console.log('ceva');

window.htmleditor_sel = 'notset';
window.mceeditor_sel = 'notset';
window.dzsvg_widget_shortcode = null;

jQuery(document).ready(function($){
    if(typeof(dzsvg_settings)=='undefined'){
        if(window.console){ console.log('dzsvg_settings not defined'); };
        return;
    }



    $('#wp-content-media-buttons').append('<a class="shortcode_opener" id="dzsvg_shortcode" style="cursor:pointer; display: inline-block; vertical-align: middle;width:auto; height:28px; margin-right: 5px; background-color: #ffffff; color: #726b6b; padding-right: 10px; border: 1px solid rgba(0,0,0,0.3); border-radius:3px; line-height: 1; font-size:13px; padding-left:0;"><i class="" style="  background-size:cover; background-repeat: no-repeat; background-position: center center; background-image: url('+dzsvg_settings.the_url+'tinymce/img/shortcodes-small-retina.png); width:28px; height: 28px; display:inline-block;  vertical-align: middle; margin-right: 5px; " ></i> <span style="display: inline-block; vertical-align: middle; font-size: 12px; font-weight: bold;">'+window.dzsvg_settings.translate_add_videogallery+'</span></a>');

    $('#wp-content-media-buttons').append('<a class="shortcode_opener" id="dzsvg_shortcode_addvideoshowcase" style="cursor:pointer; display: inline-block; vertical-align: middle;width:auto; height:28px; margin-right: 5px; background-color: #ffffff; color: #726b6b; padding-right: 10px; border: 1px solid rgba(0,0,0,0.3); border-radius:3px; line-height: 1; font-size:13px; padding-left:0;"><i class="" style="  background-size:cover; background-repeat: no-repeat; background-position: center center; background-image: url('+dzsvg_settings.the_url+'tinymce/img/shortcodes-small-retina.png); width:28px; height: 28px; display:inline-block;  vertical-align: middle; margin-right: 5px; " ></i> <span style="display: inline-block; vertical-align: middle; font-size: 12px; font-weight: bold;">'+window.dzsvg_settings.translate_add_videoshowcase+'</span></a>');



    /*

    $('#wp-content-media-buttons').append('<a class="shortcode_opener" id="dzsvg_shortcode_addvideoplayer" style="cursor:pointer; display: inline-block; vertical-align: middle; background-size:cover; background-repeat: no-repeat; background-position: center center; width:28px; height:28px; background-image: url('+dzsvg_settings.thepath+'tinymce/img/shortcodes-small-addvideoplayer-retina.png);"></a>');
    //$('#dzsvg_shortcode').bind('click');


    */
    $('#dzsvg_shortcode').bind('click', function(){
        //tb_show('ZSVG Shortcodes', dzsvg_settings.thepath + 'tinymce/popupiframe.php?width=630&height=800');


        var parsel = '';
        if(jQuery('#wp-content-wrap').hasClass('tmce-active') && window.tinyMCE ){

            //console.log(window.tinyMCE.activeEditor);
            var ed = window.tinyMCE.activeEditor;
            var sel=ed.selection.getContent();

            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.mceeditor_sel = sel;
            }else{
                window.mceeditor_sel = '';
            }
            //console.log(aux);


            window.htmleditor_sel = 'notset';


        }else{




            var textarea = document.getElementById("content");
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            var sel = textarea.value.substring(start, end);

            //console.log(sel);

            //textarea.value = 'ceva';
            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.htmleditor_sel = sel;
            }else{
                window.htmleditor_sel = '';
            }

            window.mceeditor_sel = 'notset';
        }


        window.open_ultibox(null, {suggested_width: 1200, suggested_height: 700,forcenodeeplink: 'on', dims_scaling: 'fill', source:dzsvg_settings.shortcode_generator_url+parsel, type: 'iframe'});
    })


    $('#dzsvg_shortcode_addvideoshowcase').bind('click', function(){
        //tb_show('ZSVG Shortcodes', dzsvg_settings.thepath + 'tinymce/popupiframe.php?width=630&height=800');


        var parsel = '';


        console.info(jQuery('#wp-content-wrap').hasClass('tmce-active'), window.tinyMCE, window.tinyMCE.activeEditor)
        if(jQuery('#wp-content-wrap').hasClass('tmce-active') && window.tinyMCE ){

            //console.log(window.tinyMCE.activeEditor);
            var ed = window.tinyMCE.activeEditor;
            var sel=ed.selection.getContent();

            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.mceeditor_sel = sel;
            }else{
                window.mceeditor_sel = '';
            }
            console.log(sel);


            window.htmleditor_sel = 'notset';


        }else{




            var textarea = document.getElementById("content");
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            var sel = textarea.value.substring(start, end);

            //console.log(sel);

            //textarea.value = 'ceva';
            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.htmleditor_sel = sel;
            }else{
                window.htmleditor_sel = '';
            }

            window.mceeditor_sel = 'notset';
        }






        window.open_ultibox(null, {suggested_width: 1200, suggested_height: 700,forcenodeeplink: 'on', dims_scaling: 'fill', source:dzsvg_settings.shortcode_showcase_generator_url+parsel, type: 'iframe'});
    });



    $('#dzsvg_shortcode_addvideoplayer').bind('click', function(){
            //console.log('click');

            frame = wp.media.frames.dzsvg_addplayer = wp.media({
                // Set the title of the modal.
                title: "Insert Video Player",

                // Tell the modal to show only images.
                library: {
                    type: 'video'
                },

                // Customize the submit button.
                button: {
                    // Set the text of the button.
                    text: "Insert Video",
                    // Tell the button not to close the modal, since we're
                    // going to refresh the page when the image is selected.
                    close: false
                }
            });

            // When an image is selected, run a callback.
            frame.on( 'select', function() {
                // Grab the selected attachment.
                var attachment = frame.state().get('selection').first();

                //console.log(attachment.attributes, $('*[name*="video-player-config"]'));
                var arg = '[dzs_video source="'+attachment.attributes.url+'" config="'+$('*[name*="video-player-config"]').val()+'" height="'+$('*[name*="video-player-height"]').val()+'" responsive_ratio="off"]';
                    if(typeof(top.dzsvg_receiver)=='function'){
                        top.dzsvg_receiver(arg);
                    }
                    frame.close();
            });

            // Finally, open the modal.
            frame.open();
    });



    $(document).delegate('.btn-shortcode-generator-dzsvg-showcase','click', function(){
        var _t = $(this);
        var parsel = '';

        console.info(_t.prev());

        if(_t.prev().hasClass('shortcode-generator-target')){

            window.dzsvg_widget_shortcode = _t.prev();
            parsel+='&sel=' + encodeURIComponent(_t.prev().val());
        }



        window.open_ultibox(null, {suggested_width: 1200, suggested_height: 700,forcenodeeplink: 'on', dims_scaling: 'fill', source:dzsvg_settings.shortcode_showcase_generator_url+parsel, type: 'iframe'});

        return false;
    })








    $('#wp-content-media-buttons').append('<button type="button" id="dzsvg-shortcode-generator-player" class="dzs-shortcode-button button " data-editor="content"><span class="the-icon"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="100px" height="100px" viewBox="-50 -49 100 100" enable-background="new -50 -49 100 100" xml:space="preserve"> <g> <path d="M0.919-46.588c-9.584,0-18.294,2.895-26.031,7.576l-8.591,7.014l-5.725,7.043l8.51-9.707 C-40.988-25.8-47.44-13.066-47.44,1.448c0,2.417,0.324,4.188,0.324,6.606l-0.286-5.194l1.333,9.228l0,0 c5.081,21.92,24.098,37.558,46.988,37.558c26.601,0,48.207-21.759,48.207-48.197C49.286-24.992,27.521-46.588,0.919-46.588z M0.919,45.458c-20.311,0-37.634-14.19-42.554-33.046c-0.324-1.617-0.485-2.74-0.809-4.197c-0.162-2.255-0.324-4.187-0.324-6.605 c0-14.989,7.585-28.21,18.949-36.271c2.656-1.609,5.32-3.226,8.052-4.188c5.159-2.265,10.963-3.226,16.685-3.226 c24.267,0,43.771,19.664,43.771,43.846C44.69,25.947,25.187,45.458,0.919,45.458z"/> <path d="M19.137-0.168L-6.171-15.637c-0.647-0.323-1.447-0.323-2.095,0c-0.562,0.315-1.047,1.286-1.047,1.933v22.08v4.036v4.835 c0,0.962,0.485,1.607,1.208,2.256c0.324,0,0.486,0,0.971,0c0.478,0,0.799,0,1.123,0L19.298,3.38 c0.484-0.323,0.808-0.809,0.808-1.932C20.105,0.64,19.782,0.153,19.137-0.168z M0.603,9.672l-4.433,2.74l-1.294,0.962v-0.962V8.055 v-18.056L13.661,1.771L0.603,9.672z"/> </g> </svg></span> <span class="the-label"> '+dzsvg_settings.translate_add_player+'</span></button>');







    $('#dzsvg-shortcode-generator-player').bind('click', function(){
        //tb_show('ZSVG Shortcodes', dzsrst_settings.thepath + 'tinymce/popupiframe.php?width=630&height=800');


        var parsel = '';
        if(jQuery('#wp-content-wrap').hasClass('tmce-active') && window.tinyMCE ){

            //console.log(window.tinyMCE.activeEditor);
            var ed = window.tinyMCE.activeEditor;
            var sel=ed.selection.getContent();

            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.mceeditor_sel = sel;
            }else{
                window.mceeditor_sel = '';
            }
            //console.log(aux);


            window.htmleditor_sel = 'notset';


        }else{




            var textarea = document.getElementById("content");
            var start = textarea.selectionStart;
            var end = textarea.selectionEnd;
            var sel = textarea.value.substring(start, end);

            //console.log(sel);

            //textarea.value = 'ceva';
            if(sel!=''){
                parsel+='&sel=' + encodeURIComponent(sel);
                window.htmleditor_sel = sel;
            }else{
                window.htmleditor_sel = '';
            }

            window.mceeditor_sel = 'notset';
        }

        window.open_ultibox(null,{

            type: 'iframe'
            ,source: dzsvg_settings.shortcode_generator_player_url + parsel
            ,scaling: 'fill' // -- this is the under description
            ,suggested_width: 800 // -- this is the under description
            ,suggested_height: 600 // -- this is the under description
            ,item: null // -- we can pass the items from here too

        })

        return false;
    })


})