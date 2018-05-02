<?php


function dzsvg_analytics_dashboard_content(){
    global $dzsvg;






    $dzsvg->analytics_get();
    if($dzsvg->analytics_views==false){
        $dzsvg->analytics_views = array();
    }
    if($dzsvg->analytics_minutes==false){
        $dzsvg->analytics_minutes = array();
    }

//    print_r($dzsvg->analytics_minutes);

    $str_views = '';
    $str_minutes = '';

//    print_r($dzsvg->analytics_views);

    $added_view = false;



    $videos_views = array();

    // -- sample data

//
//    for($i=30; $i>=2; $i--){
//
//        $date_aux = date("Y-m-d", time() - 60 * 60 * (24*$i));
//        $views = rand(500,1000);
//
//
//        $aux = array(
//            'video_title' => '',
//            'views' => $views,
//            'date' => $date_aux,
//            'country' => '',
//        );
//
//        $dzsvg->analytics_views[30-$i] = $aux;
//
//
//        update_option('dzsvg_analytics_views', $dzsvg->analytics_views);
//    }

//
//    for($i=30; $i>=2; $i--){
//
//        $date_aux = date("Y-m-d", time() - 60 * 60 * (24*$i));
//        $views = rand(0,1000);
//
//
//        array_push($dzsvg->analytics_minutes, array(
//            'video_title' => '',
//            'seconds' => $views,
//            'date' => $date_aux,
//            'country' => '',
//        ));
//
//
//        update_option('dzsvg_analytics_minutes', $dzsvg->analytics_minutes);
//    }




    $locs_array = array();

















    if( (isset($_GET['action']) && $_GET['action']=='dzsvg_show_analytics_for_video') == false ) {

	    $arr = array(
		    'labels'=>array(__('Track'),__('Views'),__('Likes')),
		    'lastdays'=>array(),
        )
            ;

	    for ( $i = 15; $i >= 0; $i --) {


	        $day_label = date("d M", time() - 60 * 60 * 24 * $i);




//	            echo 'hmm-'.$yesterday.'-'.$days_2;

//                $yesterday = 'ceva';
//	            $days_2 = 'ceva2';

		    // -- chart

		    $trackid = '0';


		    $aux = 		array(

			    $day_label,
			    $dzsvg->mysql_get_track_activity($trackid, array(
				    'get_last'=>'day',
				    'day_start'=>($i+1),
				    'day_end'=>($i),
				    'type'=>'view',
				    'get_count'=>'off',
			    )),
			    $dzsvg->mysql_get_track_activity($trackid, array(
				    'get_last'=>'day',
				    'day_start'=>($i+1),
				    'day_end'=>($i),
				    'type'=>'like',
				    'get_count'=>'off',
			    )),
		    );

		    array_push($arr['lastdays'],$aux);



		    ;
	    }
	    ?>

        <div class="hidden-data" style="display: none;"><?php echo json_encode($arr); ?></div>





        <?php

	    $arr = array(
		    'labels'=>array(__('Track'),__('Minutes')),
		    'lastdays'=>array(),
	    )
	    ;

	    for ( $i = 15; $i >= 0; $i --) {


		    $day_label = date("d M", time() - 60 * 60 * 24 * $i);




//	            echo 'hmm-'.$yesterday.'-'.$days_2;

//                $yesterday = 'ceva';
//	            $days_2 = 'ceva2';

		    // -- chart

		    $trackid = '0';


		    $aux = 		array(

			    $day_label,
			    $dzsvg->mysql_get_track_activity($trackid, array(
				    'get_last'=>'day',
				    'day_start'=>($i+1),
				    'day_end'=>($i),
				    'type'=>'timewatched',
				    'get_count'=>'off',
			    )),

		    );

		    array_push($arr['lastdays'],$aux);



		    ;
	    }
	    ?>


        <div class="hidden-data-timewatched" style="display: none;"><?php echo json_encode($arr); ?></div>

        <div id="chart_div"></div>
        <div id="chart_div-timewatched"></div>





        <script>
            google.charts.load('current', {packages: ['corechart', 'bar','geochart']});
            google.charts.setOnLoadCallback(drawAnnotations);



            function parse_arr_to_google_charts_data(resp_arr, pargs){




                var margs = {
                    target_attribute: 'name'
                    ,multiplier: 1
                }


                if(pargs){
                    margs = jQuery.extend(margs,pargs);
                }
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

                            val4 = parseInt(parseFloat(val4)*margs.multiplier);
                        }
                        // val = parseFloat(val);

                        if(isNaN(val4)==false){
                            resp_arr['lastdays'][i][j] = val4;
                        }
                        arr[i+1].push(resp_arr['lastdays'][i][j]);
                    }

                }

                return arr;
            }

            function drawAnnotations() {

                var $ = jQuery;
			    <?php

			    if($str_minutes==''){
				    $str_minutes=0;
			    }

			    ?>






                var auxr = /<div class="hidden-data".*?>(.*?)<\/div>/g;
                var aux = auxr.exec($('body').html());
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



                var arr = parse_arr_to_google_charts_data(resp_arr);



                 console.info('stats arr - ',arr);
                var data = google.visualization.arrayToDataTable(arr);


                var options = {
                    title: '',
                    annotations: {
                        alwaysOutside: true,
                        textStyle: {
                            fontSize: 14,
                            color: '#222',
                            auraColor: 'none'
                        }
                    },
                    hAxis: {
                        title: 'Date',
                        format: 'Y-m-d'
                    },
                    vAxis: {
                        title: 'Plays and likes'
                    }
                };



                var chart = new google.visualization.ColumnChart(document.getElementById('chart_div'));
                chart.draw(data, options);












                var auxr = /<div class="hidden-data-timewatched".*?>(.*?)<\/div>/g;
                var aux = auxr.exec($('body').html());
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


                arr = parse_arr_to_google_charts_data(resp_arr, {
                    multiplier: 1/60
                });



                console.info('stats arr - ',arr);
                data = google.visualization.arrayToDataTable(arr);


                options = {
                    title: '',
                    annotations: {
                        alwaysOutside: true,
                        textStyle: {
                            fontSize: 14,
                            color: '#222',
                            auraColor: 'none'
                        }
                    },
                    colors: ['#e0cd5f', '#e6693e', '#ec8f6e', '#f3b49f', '#f6c7b6']
                    ,hAxis: {
                        title: 'Date',
                        format: 'Y-m-d'
                    },
                    vAxis: {
                        title: 'Minutes watched'
                    }
                };



                var chart2 = new google.visualization.ColumnChart(document.getElementById('chart_div-timewatched'));
                chart2.draw(data, options);




                return false;







            }
        </script>


        <?php

    }



    if( (isset($_GET['action']) && $_GET['action']=='dzsvg_show_analytics_for_video') == 'dadada' ){



    for($i=30; $i>=0; $i--){



        $date_aux = date("Y-m-d", time() - 60 * 60 * (24*$i));




        // -- @views
        $views = 0;


        foreach($dzsvg->analytics_views as $av){
//            print_r($av);

            if($date_aux == $av['date']){

                $views+=$av['views'];


                $sw_found = false;
                foreach($videos_views as $lab => $vv){
                    if($vv['video_title']==$av['video_title']){

                        $videos_views[$lab]['views']+=$av['views'];

                        $sw_found = true;
                        break;
                    }
                }

                if(!$sw_found){
                    array_push($videos_views, array(
                        'video_title' => $av['video_title'],
                        'views' => $av['views'],
                        'seconds' => '0',
                    ));
                }
            }


            if($dzsvg->mainoptions['analytics_enable_location']=='on'){

                if(isset($av['country'])){
                    if(isset($locs_array[$av['country']])){

                        $locs_array[$av['country']] += $av['views'];
                    }else{

                        $locs_array[$av['country']] = $av['views'];
                    }
                }

            }
        }

        if($views>0){
            $str_views.=',';

            if($date_aux && $views){

                $str_views.='["'.$date_aux.'", '.$views.']';
            }else{

                $str_views.='[\''.date("Y-n-j").'\',0]';
            }


            $added_view = true;
        }


        // -- @minutes
        $views = 0;
        foreach($dzsvg->analytics_minutes as $av){

            if($date_aux == $av['date']){

                $views+=$av['seconds'];


                $sw_found = false;
                foreach($videos_views as $lab => $vv){
                    if($vv['video_title']==$av['video_title']){

                        $videos_views[$lab]['seconds']+=$av['seconds'];

                        $sw_found = true;
                        break;
                    }
                }

                if(!$sw_found){
                    array_push($videos_views, array(
                        'video_title' => $av['video_title'],
                        'views' => '0',
                        'seconds' => $av['seconds'],
                    ));
                }
            }
        }


//        echo $views;

//        echo ' views - '.$views;
        if($views>0){
            $str_minutes.=',';

            $str_minutes.='["'.$date_aux.'", '.intval($views/60).']';

            $added_view = true;
        }else{

            $str_minutes.=',';
            $str_minutes.='["'.$date_aux.'", '.'0'.']';
        }


        // -- tbc minutes will go here as well


    }

//    print_r($videos_views);
//        print_r($locs_array);

        $str_locs = '';

        if($dzsvg->mainoptions['analytics_enable_location']=='on'){
            foreach($locs_array as $lab => $val){

                if($val>0){
                    $str_locs.=',';

                    $str_locs.='["'.$lab.'", '.$val.']';

                    $added_view = true;
                }
            }
        }


    ?>
    <h4><?php echo ("Views"); ?></h4>
<div id="chart_div"></div>
    <br>
    <br>
    <h4><?php echo ("Minutes Viewed"); ?></h4>
<div id="chart_div2"></div>
        <?php if($dzsvg->mainoptions['analytics_enable_location']=='on'){
            ?>

    <br>
    <br>
        <h4><?php echo ("Geo Map"); ?></h4>
        <div id="regions_div"></div>
            <?php
        }
            ?>

    <br>
    <br>



<?php
}

}