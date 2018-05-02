function htmlEntities(str) {
    return String(str).replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/"/g, '&quot;');
}
jQuery(document).ready(function($) {


    var dragelement = null;

    var cthis = $('.dzsvg-quality-builder').eq(0);

    // cthis.on('mousedown', '.quality-marker > .icon',handle_mouse);
    // cthis.on('click', '.scrub-bg, .dzstooltip--content > .delete-btn',handle_mouse);
    // cthis.on('mousemove',handle_mouse);
    cthis.on('submit',handle_submit);
    // $(document).on('mouseup', '.quality-marker',handle_mouse);
    $(document).on('click', '.add-quality',handle_mouse);






    var start_array = [];

    console.info(window.quality_builder_start_array)
    if(window.quality_builder_start_array){
        // window.quality_builder_start_array = String(window.quality_builder_start_array).replace(/(<iframe.*?src=)(\\".*?)(\\")(.*?<\/iframe>)/g, "$1\\$2\\$3$4");
        window.quality_builder_start_array = String(window.quality_builder_start_array).replace(/(<iframe.*?src=)(".*?)(")(.*?<\/iframe>)/g, "$1\\$2\\$3$4");
        console.info('transformed - ',window.quality_builder_start_array)
        try{
            start_array = JSON.parse(window.quality_builder_start_array);
        }catch(err){

            console.info('parse error - ',err);
        }
    }

    console.info(start_array);
    for(var i2 in start_array){

        generate_quality_marker(start_array[i2])
    }



    // generate_quality_marker({
    //
    //     source:''
    //     ,type:'image'
    //     ,time:'0.75'
    //     ,quality_link:''
    //     ,skip_delay:''
    // })


    function generate_quality_marker(pargs){


        var margs = {
            source:''
            ,label:''
        }


        if(pargs){
            margs = $.extend(margs,pargs);
        }




        var _con = $('.video-containers');

        _con.append($('.is-sampler').clone());


        var _c = _con.children().last();


        setTimeout(function(){
            "use strict";

            _c.removeClass('is-sampler');
        },50);

        _c.find('.remove-disable').attr('disabled','');
        _c.find('.remove-disable').prop('disabled',false);


        for(var lab in margs){


            // console.warn(lab,margs[lab]);
            // console.warn(_c.find('*[name="'+lab+'"]'));
            _c.find('*[name="'+lab+'[]"]').val(margs[lab]);


        }

    }


    function handle_mouse(e){
        var _t = $(this);

        if(e.type=='mousedown'){

            dragelement = _t.parent();

        }
        if(e.type=='mousemove'){

            var mx = e.clientX - cthis.offset().left;

            if(dragelement){
                var rat = mx/_t.width();

                console.info(dragelement, mx, rat);
                dragelement.css({
                    'left':rat*100+'%'
                })

                dragelement.find('input[name="time[]"]').val(Number(rat).toFixed(3));
            }

        }
        if(e.type=='mouseup'){
            dragelement = null;

        }
        if(e.type=='click'){


            if(_t.hasClass('add-quality')){

                console.info("HIER");

                generate_quality_marker({
                    time: rat
                })

                return false;
            }

            if(_t.hasClass('delete-btn')){

                _t.parent().parent().parent().remove();
            }

        }
    }


    function handle_submit(e){
        var _t = $(this);

        if(e.type=='submit'){

            if(_t.hasClass('dzsvg-quality-builder')){


                var mainarray = _t.serialize();

                var data = {
                    action: 'dzsvg_ajax_json_encode_quality'
                    ,postdata: mainarray
                };


                var ajaxurl = '';
                if(window.ajaxurl){

                    ajaxurl =window.ajaxurl;
                }else{

                    ajaxurl="ajax_json_encode_quality.php";
                }

                jQuery.post(ajaxurl, data, function(response) {
                    if(window.console ){
                        console.log('Got this from the server: ' + response);
                    }

                    $('.output').text(response);
                    console.info(parent.quality_target_field, parent.close_ultibox)
                    if(parent.quality_target_field){
                        parent.quality_target_field.val(response);
                    }

                    if(parent.close_ultibox){
                        parent.close_ultibox();
                    }
                });






                return false;
            }

        }
    }
});
