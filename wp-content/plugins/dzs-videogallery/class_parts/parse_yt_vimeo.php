<?php


function dzsvg_parse_youtube_video($id, $pargs = array(), &$fout=null){

	global $dzsvg;

	$margs = array(
		'max_videos' => '5',
		'enable_outernav_video_author' => 'off',
		'striptags' => 'off',
		'get_full_description' => 'off',
		'type' => 'detect',
	);



	$response = '';


	$lab_cacher = 'dzsvg_cache_ytvideos';
	$cacher = get_option($lab_cacher);

	$cached = false;


	$foutarr = array();


	if ($cacher == false || is_array($cacher) == false || $dzsvg->mainoptions['disable_api_caching'] == 'on') {
		$cached = false;
	} else {



		$ik = -1;
		$i = 0;
		for ($i = 0; $i < count($cacher); $i++) {
			if ($cacher[$i]['id'] == $id) {
				if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 144000) {
					$ik = $i;

//                                echo 'yabebe';
					$cached = true;
					break;
				}
			}
		}


		if($cached) {
			$response= $cacher[$ik]['response'];
		}

	}




	if ($dzsvg->mainoptions['debug_mode'] == 'on') {
		if($fout!=null){

		}
		$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('video single youtube', 'dzsvg') . '</div>
<div class="toggle-content">';
		$fout.='cached - ( ' . $cached . ' )  cacher is...<br>';
		$fout.=(print_rr($cacher, array('echo' => false, 'encode_html' => true)));
		$fout.= '</div></div>';
	}


	if($response==''){

		$target_file = 'https://www.googleapis.com/youtube/v3/videos?part=snippet%2Cstatistics&id=' . $id . '&key=' . $dzsvg->mainoptions['youtube_api_key'] . '&type=channel&part=snippet';



		$response = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $dzsvg->mainoptions['force_file_get_contents']));




		$auxa34 = array('id' => $id, 'response' => $response, 'time' => $_SERVER['REQUEST_TIME']
		);

		$found_cached = false;

		if (!is_array($cacher)) {
			$cacher = array();
		} else {


			foreach ($cacher as $lab => $cach) {
				if ($cach['id'] == $id) {
					$found_cached = true;

					$cacher[$lab] = $auxa34;

					update_option($lab_cacher, $cacher);

//                                        print_r($cacher);
					break;
				}
			}


		}

		if ($found_cached == false) {

			array_push($cacher, $auxa34);

//                                            print_r($cacher);

			update_option($lab_cacher, $cacher);
		}


	}


	$obj = json_decode($response);


	if ($obj && is_object($obj)) {

		if(isset($obj->items) && isset($obj->items[0]) && isset($obj->items[0]->snippet) && isset($obj->items[0]->snippet->description)){

			$foutarr['description'] = $obj->items[0]->snippet->description;
			$foutarr['menuDescription'] = $obj->items[0]->snippet->description;
		}

		if(isset($obj->items) && isset($obj->items[0]) && isset($obj->items[0]->statistics) && isset($obj->items[0]->statistics->viewCount)){

			$foutarr['views'] = $obj->items[0]->statistics->viewCount;
		}
	}

//        print_r($obj);



	return $foutarr;




}
function yt_get_more_video_details($vid){

	global $dzsvg;
	if($dzsvg->mainoptions['youtube_hide_non_embeddable']=='on'){

		$video_details_arr = array();


//									echo 'ceva';

		$option_name_for_vid = 'dzsvg_youtube_video_details_'.$vid;

		$auxcachvid = get_option($option_name_for_vid);
		if($auxcachvid && $dzsvg->mainoptions['disable_api_caching']!='on'){

//			                                echo '$auxcachvid - '.print_r($auxcachvid,true);


			try{
				$auxcachvid_ar = json_decode($auxcachvid,true);
				$video_details_arr = $auxcachvid_ar['status'];

//											echo 'from cached';
			}catch(Exception $e){

			}
		}else{

			$target_file='https://www.googleapis.com/youtube/v3/videos?part=snippet,status&id='.$vid.'&key=' . $dzsvg->mainoptions['youtube_api_key'] ;
			$idas = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $dzsvg->mainoptions['force_file_get_contents']));

			try{
				$idas_arr = json_decode($idas,true);

//				print_rr($idas_arr);


				$auxa34 = array(
					'status' => $idas_arr,
					'time' => $_SERVER['REQUEST_TIME'],
				);

				$video_details_arr  = $idas_arr;
				update_option($option_name_for_vid, json_encode($auxa34));

			}catch(Exception $e){

			}


		}

//		                                print_rr($video_details_arr);
//	                                	echo 'isset [['.(property_exists($video_details_arr->items)).']]';
//	                                	echo 'whaaa [['.(is_array($video_details_arr['items'])).']]';
//	                                	echo 'whaaa [['.($video_details_arr['items'][0]['status']['embeddable']!==1).']]';
//	                                	echo 'embeddable [['.($video_details_arr['items'][0]['status']['embeddable']).']]';


		return $video_details_arr;


	}
	return false;

}
function dzsvg_parse_yt($link, $pargs = array(), &$fout=null){


	global $dzsvg;


	$margs = array(
		'max_videos' => '5',
		'enable_outernav_video_author' => 'off',
		'enable_outernav_video_date' => 'off',
		'striptags' => 'off',
		'get_full_description' => 'off',
		'is_id' => 'on',
		'type' => 'detect',
		'subtype' => 'detect',
		'query' => '',
	);

	if (!is_array($pargs)) {
		$pargs = array();
	}

	$margs = array_merge($margs, $pargs);

	$its = array();


	$subtype = '';


	$link = str_replace('&amp;','&',$link);

	if($margs['subtype']!='detect'){
		$subtype = $margs['subtype'];
	}
	if(strpos($link,'youtube.com/c/')!==false){
//        echo 'whaaaaaa';
		$subtype='user_channel';
	}
	if(strpos($link,'youtube.com/channel/')!==false){
//        echo 'whaaaaaa';
		$subtype='user_channel';
		$margs['is_id']='on';
	}
	if(strpos($link,'youtube.com/user/')!==false){
		$subtype='user_channel';

		$margs['is_id']='off';
	}


//	echo '$link - '.$link.' ... <br>'."\n";
//	echo 'strpos($link,\'&list=\') - '.strpos($link,'list=');
	if(strpos($link,'youtube.com/playlist')!==false || strpos($link,'list=')!==false){
		$subtype='playlist';

//		echo 'yes it s PLAYLIST';
	}
	if(strpos($link,'youtube.com/results')!==false){
		$subtype='search';
	}

	if($margs['max_videos']==''){
		$margs['max_videos'] = '30';
	}

	$targetfeed = '';

	if(strpos($link,'/')!==false){
		$q_strings = explode('/',$link);

//    print_r($q_strings);

		if($subtype=='user_channel'){

			$targetfeed = $q_strings[count($q_strings)-1];

//        echo $targetfeed;
		}
		if($subtype=='playlist'){

			$targetfeed = DZSHelpers::get_query_arg($link, 'list');

//            echo 'targetfeed  from playlist - '.$targetfeed.' ||| ';
		}
		if($subtype=='search'){

			$targetfeed = DZSHelpers::get_query_arg($link, 'search_query');

//        echo $targetfeed;
		}

	}else{
		$targetfeed = $link;
	}

	if($margs['query']){
		if($targetfeed==''){
			$targetfeed = $margs['query'];
		}
	}



//    print_r($dzsvg->mainoptions);
	if ($dzsvg->mainoptions['debug_mode'] == 'on') {
		if($fout!=null){

		}
		$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('parse_yt settings', 'dzsvg') . '</div>
<div class="toggle-content">';
		$fout.='debug mode: target file ( ' . '$link - '.$link . ' ) <br>';
		$fout.='debug mode:' . 'feed type ( $subtype )  - '.$subtype . ' ) <br>';
		$fout.='debug mode: target file ( ' . '$targetfeed - '.$targetfeed . ' ) <br>';
		$fout.='debug mode: $obj - ' . print_rr($margs, array('echo'=>false, 'encode_html'=>true) )  . '<br>';
		$fout.= '</div></div>';


	}




	$max_videos = $margs['max_videos'];
	$original_max_videos = $margs['max_videos'];


	if($max_videos=='all'){
		$max_videos = 50;
	}


	// --- user channel
	if($subtype=='user_channel') {


		// -- start



		$cacher = get_option('dzsvg_cache_ytuserchannel');

		$cached = false;


		if ($cacher == false || is_array($cacher) == false || $dzsvg->mainoptions['disable_api_caching'] == 'on') {
			$cached = false;
		} else {

//                print_r($cacher);


			$ik = -1;
			$i = 0;
			for ($i = 0; $i < count($cacher); $i++) {
				if ($cacher[$i]['id'] == $targetfeed) {
					if(isset($cacher[$i]['maxlen']) && $cacher[$i]['maxlen'] == $original_max_videos) {
						if ( $_SERVER['REQUEST_TIME'] - $cacher[ $i ]['time'] < 7200 ) {
							$ik = $i;

//                                echo 'yabebe';
							$cached = true;
							break;
						}
					}
				}
			}


			if($cached) {
				foreach ($cacher[$ik]['items'] as $lab => $item) {
					if ($lab === 'settings') {
						continue;
					}

					$its[$lab] = $item;
				}

				return $its;
			}

		}



		$sw_use_search = false;


		$str_nextPageToken = '';
		$channel_id = $targetfeed;

		$target_file = 'https://www.googleapis.com/youtube/v3/search?q=' . $targetfeed . '&key=' . $dzsvg->mainoptions['youtube_api_key'] . '&type=channel&part=snippet';




		$ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $dzsvg->mainoptions['force_file_get_contents']));



		if ($dzsvg->mainoptions['debug_mode'] == 'on') {
			if($fout!=null){

			}
			$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('first search for channel id', 'dzsvg') . '</div>
<div class="toggle-content">';
			$fout.='debug mode: target file ( ' . $target_file . ' )  ida is is...<br>';
			$fout.= '</div></div>';
			wp_enqueue_style('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.css');
			wp_enqueue_script('dzstoggle', $dzsvg->thepath . 'dzstoggle/dzstoggle.js');
		}


		$i = 0;

		if ($ida) {

			$obj = json_decode($ida);


			if ($dzsvg->mainoptions['debug_mode'] == 'on') {
//                echo 'debug mode: is not nicename is ON, obj is is...<br>';
//                print_r($obj);
//                echo '<br/>';
			}


			if ($obj && is_object($obj)) {


				if (isset($obj->items[0]->id->channelId)) {

//                        array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">'.__('This is dirty').'</div>');

					$channel_id = $obj->items[0]->id->channelId;

					$sw_use_search = true;
				} else {

					array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('Cannot access channel ID, this is feed - ') . $target_file . '</div>');
//					echo 'ceva33 '; print_rr($ida);
					try {

						if (isset($obj->error)) {
							if ($obj->error->errors[0]) {


								array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . $obj->error->errors[0]->message . '</div>');
								if (strpos($obj->error->errors[0]->message, 'per-IP or per-Referer restriction') !== false) {

									array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __("Suggestion - go to Video Gallery > Settings and enter your YouTube API Key") . '</div>');
								} else {

								}
							}
						}

//                                    $arr = json_decode(DZSHelpers::($target_file));
//
//                                    print_r($arr);
					} catch (Exception $err) {

					}
				}
			} else {

				array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('Object is not JSON...') . '</div>');
			}
		}








		$breaker = 0;
		$nextPageToken = 'none';

		while ($breaker < 10 || $nextPageToken !== '') {


			$str_nextPageToken = '';

			if ($nextPageToken && $nextPageToken != 'none') {
				$str_nextPageToken = '&pageToken=' . $nextPageToken;
			}


			if ($dzsvg->mainoptions['youtube_api_key'] == '') {
				$dzsvg->mainoptions['youtube_api_key'] = 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4';
			}


			if($sw_use_search){

				$target_file = 'https://www.googleapis.com/youtube/v3/search?key=' . $dzsvg->mainoptions['youtube_api_key'] . '&channelId=' . $channel_id . '&part=snippet&order=date&type=video' . $str_nextPageToken . '&maxResults=' . $max_videos;
			}else{

				if($margs['is_id']=='on'){

					$target_file = 'https://www.googleapis.com/youtube/v3/channels?key=' . $dzsvg->mainoptions['youtube_api_key'] . '&id=' . $channel_id . '&part=snippet' . $str_nextPageToken . '&maxResults=' . $max_videos;

				}else{

					$target_file = 'https://www.googleapis.com/youtube/v3/channels?key=' . $dzsvg->mainoptions['youtube_api_key'] . '&forUsername=' . $channel_id . '&part=snippet' . $str_nextPageToken . '&maxResults=' . $max_videos;
				}

			}




//                        echo $target_file;

			$ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $dzsvg->mainoptions['force_file_get_contents']));


			if ($ida) {

				$obj = json_decode($ida);

//				print_rr($obj);


				if ($dzsvg->mainoptions['debug_mode'] == 'on') {
					$fout.= 'fout on';
					$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('then we know the real query  ', 'dzsvg') . '</div>
<div class="toggle-content">';
					$fout.=' youtube user channel - let us see the actual channel id targetfile - ' . $target_file . ' <br>';
					$fout.=' channelId - <strong>' . $channel_id . '</strong> <br>';
					$fout.='debug mode: $obj - ' . print_rr($obj, array('echo'=>false, 'encode_html'=>true) )  . '<br>';
					$fout.= '</div></div>';
				}


				if($obj && is_object($obj) && isset($obj->error)  && isset($obj->error->message)){

					echo '<div class="error">'.$obj->error->message.'</div>';
				}


				// -- still channel
				if ($obj && is_object($obj)) {

//                                        print_r($obj);

					if (isset($obj->items[0]->id->videoId)) {


						foreach ($obj->items as $ytitem) {
//                    print_r($ytitem); echo $ytitem->id->videoId;


							if (isset($ytitem->id->videoId) == false) {
								echo 'this does not have id ? ';
								continue;
							}

							$vid =$ytitem->id->videoId;




							$video_details_arr = yt_get_more_video_details($vid);

							if($dzsvg->mainoptions['youtube_hide_non_embeddable']=='on' && $video_details_arr && isset($video_details_arr['items'])&& $video_details_arr['items'][0]['status']['embeddable']!=1){

//		                                	echo "SKIP IT";
								continue;
							}


							$its[$i]['source'] = $vid;
							$its[$i]['thumbnail'] = $ytitem->snippet->thumbnails->medium->url;
							$its[$i]['type'] = "youtube";
							$its[$i]['permalink'] = "https://www.youtube.com/watch?v=".$its[$i]['source'];

							$aux = $ytitem->snippet->title;
							$lb = array('"', "\r\n", "\n", "\r", "&", "", "`", '???', "'", '');
							$aux = str_replace($lb, ' ', $aux);
							$its[$i]['title'] = $aux;

							$aux = $ytitem->snippet->description;
							$lb = array("\r\n", "\n", "\r");
							$aux = str_replace($lb, '<br>', $aux);
							$lb = array('"');
							$aux = str_replace($lb, '&quot;', $aux);
							$lb = array("'");
							$aux = str_replace($lb, '&#39;', $aux);




							$auxcontent = '<p>' . str_replace(array("\r\n", "\n", "\r"), '</p><p>', $aux) . '</p>';

							$its[$i]['description'] = $auxcontent;
							$its[$i]['menuDescription'] = $auxcontent;



							if($video_details_arr && isset($video_details_arr['items'])&& $video_details_arr['items'][0]['snippet']['description']){

								$its[$i]['description'] = $video_details_arr['items'][0]['snippet']['description'];
								$its[$i]['menuDescription'] = $video_details_arr['items'][0]['snippet']['description'];
							}

							if ($margs['enable_outernav_video_author'] == 'on') {
//                        echo 'ceva';
							}
							if ($margs['enable_outernav_video_date'] == 'on') {
//                        echo 'ceva';
							}
							$its[$i]['uploader'] = $ytitem->snippet->channelTitle;
							$its[$i]['upload_date'] = $ytitem->snippet->publishedAt;



							if($margs['get_full_description']=='on'){
								$arr = dzsvg_parse_youtube_video($its[$i]['source'], $margs, $fout);


								if(is_array($arr)){
									$its[$i] = array_merge($its[$i], $arr);
								}
//                                            print_r($its[$i]);
							}

							$i++;

//                                            if ($i > $max_videos + 1){ break; }

						}


					} else {

						array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('No videos to be found - ') . $target_file . '</div>');
					}
//                                print_r($obj);
				} else {

					array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('Object channel is not JSON...') . '</div>');
				}
			} else {

				array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('Cannot get info from YouTube API about channel - ') . $target_file . '</div>');
			}


			if ($max_videos === 'all') {

				if (isset($obj->nextPageToken) && $obj->nextPageToken) {
					$nextPageToken = $obj->nextPageToken;
				} else {

					$nextPageToken = '';
					break;
				}

			} else {
				$nextPageToken = '';
				break;
			}

			$breaker++;
		}



		$sw34 = false; // -- true if added to cache
		$auxa34 = array(
			'id' => $targetfeed,
			'items' => $its,
			'time' => $_SERVER['REQUEST_TIME']
			, 'maxlen' => $original_max_videos

		);


		$cacher = false;
		if (!is_array($cacher)) {
			$cacher = array();
		} else {


			foreach ($cacher as $lab => $cach) {
				if ($cach['id'] == $targetfeed) {
					$sw34 = true;

					$cacher[$lab] = $auxa34;

					update_option('dzsvg_cache_ytuserchannel', $cacher);

//                                        print_r($cacher);
					break;
				}
			}


		}

		if ($sw34 == false) {

			array_push($cacher, $auxa34);

//                                            print_r($cacher);

			update_option('dzsvg_cache_ytuserchannel', $cacher);
		}



	}
	// --- END user channel



	// --- youtube playlist
	if($subtype=='playlist') {


		$len = count($its) - 1;
		for ($i = 0; $i < $len; $i++) {
			unset($its[$i]);
		}



		$cacher = get_option('dzsvg_cache_ytplaylist');

		$cached = false;
		$found_for_cache = false;


		if ($cacher == false || is_array($cacher) == false || $dzsvg->mainoptions['disable_api_caching'] == 'on') {
			$cached = false;
		} else {

//                print_r($cacher);


			$ik = -1;
			$i = 0;
			for ($i = 0; $i < count($cacher); $i++) {
				if ($cacher[$i]['id'] == $targetfeed) {
					if(isset($cacher[$i]['maxlen']) && $cacher[$i]['maxlen'] == $max_videos){
						if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 7200) {
							$ik = $i;

//                                echo 'yabebe';
							$cached = true;
							break;
						}
					}

				}
			}


			if($cached){

				foreach ($cacher[$ik]['items'] as $lab => $item) {
					if ($lab === 'settings') {
						continue;
					}

					$its[$lab] = $item;

//                        print_r($item);
//                        echo 'from cache';
				}

			}
		}



		if ($dzsvg->mainoptions['debug_mode'] == 'on') {
			echo 'is cached - '.$cached.' | ';
		}



		// -- youtube playlist
		if(!$cached){
			if (isset($max_videos) == false || $max_videos == '') {
				$max_videos = 50;
			}
			$yf_maxi = $max_videos;

			if ($max_videos == 'all') {
				$yf_maxi = 50;
			}



			$breaker = 0;

			$i_for_its = 0;
			$nextPageToken = 'none';

			while ($breaker < 10 || $nextPageToken !== '') {


				$str_nextPageToken = '';

				if ($nextPageToken && $nextPageToken != 'none') {
					$str_nextPageToken = '&pageToken=' . $nextPageToken;
				}

//                echo '$breaker is '.$breaker;

				if($dzsvg->mainoptions['youtube_api_key']==''){
					$dzsvg->mainoptions['youtube_api_key'] = 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4';
				}




				$target_file='https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&type=video&videoEmbeddable=true&playlistId=' . $targetfeed . '&key=' . $dzsvg->mainoptions['youtube_api_key'] . '' . $str_nextPageToken . '&maxResults='.$yf_maxi;

//                    echo $target_file;


				if ($dzsvg->mainoptions['debug_mode'] == 'on') {
					echo 'target file - '.$target_file;
				}
//                    echo 'target file - '.$target_file;


				$ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $dzsvg->mainoptions['force_file_get_contents']));

//            echo 'ceva'.$ida;

				if ($ida) {

					$obj = json_decode($ida);



                    if ($dzsvg->mainoptions['debug_mode'] == 'on') {
                        print_rr($ida);
                    }

                    if ($obj && is_object($obj)) {
//                            print_rr($obj);


                        if (isset($obj->error) && isset($obj->error->errors) ) {

                            echo '<div class="error">';

                            echo $obj->error->errors[0]->message;
                            echo '<br>'.(esc_html__("Original request",'dzsvg')).' - '.$target_file;
                            echo '</div>';
                        }

//                                        print_rr($obj);

						if (isset($obj->items[0]->snippet->resourceId->videoId)) {


							foreach ($obj->items as $ytitem) {
//                                echo 'yt item --- ';print_r($ytitem);


								$vid = '';
								if (isset($ytitem->snippet->resourceId->videoId) == false) {

									echo 'this does not have id ? ';
									continue;
								}

								$vid = $ytitem->snippet->resourceId->videoId;




								$video_details_arr = yt_get_more_video_details($vid);

								if($dzsvg->mainoptions['youtube_hide_non_embeddable']=='on' && $video_details_arr && isset($video_details_arr['items'])&& $video_details_arr['items'][0]['status']['embeddable']!=1){

//		                                	echo "SKIP IT";
									continue;
								}


								// -- still playlist
//								print_rr($dzsvg->mainoptions);


								$its[$i_for_its]['source'] = $ytitem->snippet->resourceId->videoId;

								if(isset($ytitem->snippet->thumbnails)){

									$its[$i_for_its]['thumbnail'] = $ytitem->snippet->thumbnails->medium->url;
								}
								$its[$i_for_its]['type'] = "youtube";
								$its[$i_for_its]['permalink'] = "https://www.youtube.com/watch?v=".$its[$i_for_its]['source'];

								$aux = $ytitem->snippet->title;
								$lb = array('"', "\r\n", "\n", "\r", "&", "", "`", '???', "'", '');
								$aux = str_replace($lb, ' ', $aux);
								$its[$i_for_its]['title'] = $aux;

								$aux = $ytitem->snippet->description;
								$lb = array("\r\n","\n","\r");
								$aux = str_replace($lb,'<br>',$aux);
								$lb = array('"');
								$aux = str_replace($lb,'&quot;',$aux);
								$lb = array("'");
								$aux = str_replace($lb,'&#39;',$aux);


								$auxcontent = '<p>' . str_replace(array("\r\n", "\n", "\r"), '</p><p>', $aux) . '</p>';

								$its[$i_for_its]['description'] = $auxcontent;
								$its[$i_for_its]['menuDescription'] = $auxcontent;

								if ($margs['enable_outernav_video_author'] == 'on') {
//                        echo 'ceva';
								}
								if ($margs['enable_outernav_video_date'] == 'on') {
//                        echo 'ceva';
								}
								$its[$i]['upload_date'] = $ytitem->snippet->publishedAt;
								$its[$i_for_its]['uploader'] = $ytitem->snippet->channelTitle;




								$i_for_its++;


							}

							$found_for_cache=true;


						} else {

							array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('No youtube playlist videos to be found - maybe API key not set ? This is the feed - '.$target_file) . '</div>');

							try{

								if(isset($obj->error)){
									if($obj->error->errors[0]){


										array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' .$obj->error->errors[0]->message . '</div>');
										if(strpos($obj->error->errors[0]->message, 'per-IP or per-Referer restriction')!==false){

											array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __("Suggestion - go to Video Gallery > Settings and enter your YouTube API Key") . '</div>');
										}else{

										}
									}
								}

//                                    $arr = json_decode(DZSHelpers::($target_file));
//
//                                    print_r($arr);
							}catch(Exception $err){

							}
						}
					}






					if ($max_videos === 'all') {

						if (isset($obj->nextPageToken) && $obj->nextPageToken) {
							$nextPageToken = $obj->nextPageToken;
						} else {

							$nextPageToken = '';
							break;
						}

					} else {
						$nextPageToken = '';
						break;
					}


				}
				$breaker++;
			}





			if ($dzsvg->mainoptions['debug_mode'] == 'on') {
//		                $fout.= 'fout on';
				$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('playlist query -', 'dzsvg') . $targetfeed.'</div>
<div class="toggle-content">';

				$fout.=' $cached - (' . $cached . ')<br>  - ';
				$fout.=' $found_for_cache - (' . $found_for_cache . ')<br>  - ';
				$fout.='debug mode: $its - ' . print_rr($its, array('echo'=>false, 'encode_html'=>true) )  . '<br>';
				$fout.= '</div></div>';



				wp_enqueue_style('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.css');
				wp_enqueue_script('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.js');



			}

			if($found_for_cache){

				$sw34 = false;
				$auxa34 = array(
					'id' => $targetfeed
					, 'items' => $its
					, 'time' => $_SERVER['REQUEST_TIME']
					, 'maxlen' => $max_videos

				);

				if (!is_array($cacher)) {
					$cacher = array();
				} else {


					foreach ($cacher as $lab => $cach) {
						if ($cach['id'] == $targetfeed) {
							$sw34 = true;

							$cacher[$lab] = $auxa34;

							update_option('dzsvg_cache_ytplaylist', $cacher);

//                                        print_r($cacher);
							break;
						}
					}


				}

				if ($sw34 == false) {

					array_push($cacher, $auxa34);

//                                            print_r($cacher);

					update_option('dzsvg_cache_ytplaylist', $cacher);
				}
			}
		}


	}
	// --- END youtube playlist



	// --- youtube search query
	if($subtype=='search') {




		$len = count($its) - 1;
		for ($i = 0; $i < $len; $i++) {
			unset($its[$i]);
		}





		$cacher = get_option('dzsvg_cache_ytkeywords');

		$cached = false;
		$found_for_cache = false;


		if ($cacher == false || is_array($cacher) == false || $dzsvg->mainoptions['disable_api_caching'] == 'on') {
			$cached = false;
		} else {

//                print_r($cacher);


			$ik = -1;
			$i = 0;
			for ($i = 0; $i < count($cacher); $i++) {
				if ($cacher[$i]['id'] == $targetfeed) {
					if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 3600) {
						$ik = $i;

//                                echo 'yabebe';
						$cached = true;
						break;
					}
				}
			}


			if($cached){

				foreach ($cacher[$ik]['items'] as $lab => $item) {
					if ($lab === 'settings') {
						continue;
					}

					$its[$lab] = $item;

//                        print_r($item);
//                        echo 'from cache';
				}

			}
		}







		//-- youtube search
		if(!$cached){
			if (isset($max_videos) == false || $max_videos == '') {
				$max_videos = 50;
			}
			$yf_maxi = $max_videos;

			if ($max_videos == 'all') {
				$yf_maxi = 50;
			}



			$breaker = 0;

			$i_for_its = 0;
			$nextPageToken = 'none';

			while ($breaker < 5 || $nextPageToken !== '') {


				$str_nextPageToken = '';

				if ($nextPageToken && $nextPageToken != 'none') {
					$str_nextPageToken = '&pageToken=' . $nextPageToken;
				}

//                echo '$breaker is '.$breaker;


				$targetfeed = str_replace(' ','+',$targetfeed);


				if($dzsvg->mainoptions['youtube_api_key']==''){
					$dzsvg->mainoptions['youtube_api_key'] = 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4';
				}

				$target_file='https://www.googleapis.com/youtube/v3/search?part=snippet&q=' . $targetfeed . '&type=video&key=' . $dzsvg->mainoptions['youtube_api_key'] . $str_nextPageToken.'&videoEmbeddable=true&maxResults='.$yf_maxi;


				$ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $dzsvg->mainoptions['force_file_get_contents']));

//            echo 'ceva'.$ida;

				if ($ida) {

					$obj = json_decode($ida);


					if ($obj && is_object($obj)) {
//                                print_r($obj);



						if (isset($obj->items[0]->id->videoId)) {


							foreach ($obj->items as $ytitem) {
//                                print_r($ytitem);


								if (isset($ytitem->id->videoId) == false) {
									echo 'this does not have id ? ';
									continue;
								}
								$its[$i_for_its]['source'] = $ytitem->id->videoId;
								$its[$i_for_its]['thethumb'] = $ytitem->snippet->thumbnails->medium->url;
								$its[$i_for_its]['type'] = "youtube";
								$its[$i_for_its]['permalink'] = "https://www.youtube.com/watch?v=".$its[$i_for_its]['source'];

								$aux = $ytitem->snippet->title;
								$lb = array('"', "\r\n", "\n", "\r", "&", "", "`", '???', "'", '');
								$aux = str_replace($lb, ' ', $aux);
								$its[$i_for_its]['title'] = $aux;

								$aux = $ytitem->snippet->description;
								$lb = array("\r\n","\n","\r");
								$aux = str_replace($lb,'<br>',$aux);
								$lb = array('"');
								$aux = str_replace($lb,'&quot;',$aux);
								$lb = array("'");
								$aux = str_replace($lb,'&#39;',$aux);


								$auxcontent = '<p>' . str_replace(array("\r\n", "\n", "\r"), '</p><p>', $aux) . '</p>';

								$its[$i_for_its]['description'] = $auxcontent;
								$its[$i_for_its]['menuDescription'] = $auxcontent;

								if ($margs['enable_outernav_video_author'] == 'on') {
//                        echo 'ceva';
								}
								$its[$i_for_its]['uploader'] = $ytitem->snippet->channelTitle;
								$its[$i]['upload_date'] = $ytitem->snippet->publishedAt;
								if ($margs['enable_outernav_video_date'] == 'on') {
//                        echo 'ceva';
								}

								$i_for_its++;

								$found_for_cache = true;

							}


						} else {

							array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('No youtube keyboard videos to be found') . '</div>');
						}

					}






					if ($max_videos === 'all') {

						if (isset($obj->nextPageToken) && $obj->nextPageToken) {
							$nextPageToken = $obj->nextPageToken;
						} else {

							$nextPageToken = '';
							break;
						}

					} else {
						$nextPageToken = '';
						break;
					}


				}else{

					array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('No youtube keyboards ida found '.$target_file) . '</div>');
				}
				$breaker++;
			}







			if($found_for_cache){

				$sw34 = false;
				$auxa34 = array(
					'id' => $targetfeed
					, 'items' => $its
					, 'time' => $_SERVER['REQUEST_TIME']
					, 'maxlen' => $max_videos

				);

				if (!is_array($cacher)) {
					$cacher = array();
				} else {


					foreach ($cacher as $lab => $cach) {
						if ($cach['id'] == $targetfeed) {
							$sw34 = true;

							$cacher[$lab] = $auxa34;

							update_option('dzsvg_cache_ytkeywords', $cacher);

//                                        print_r($cacher);
							break;
						}
					}


				}


				if ($sw34 == false) {

					array_push($cacher, $auxa34);

//                                            print_r($cacher);

					update_option('dzsvg_cache_ytkeywords', $cacher);
				}
			}



		}
		// -- end not cached




		if ($dzsvg->mainoptions['debug_mode'] == 'on') {
//		                $fout.= 'fout on';
			$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('search results query', 'dzsvg') . '</div>
<div class="toggle-content">';
			$fout.=' youtube user channel - let us see the actual channel id targetfile - ' . $target_file . ' <br>';
			$fout.=' cached - (' . $cached . ')<br>  - ';
			$fout.=' $found_for_cache - (' . $found_for_cache . ')<br>  - ';
			$fout.='debug mode: $its - ' . print_rr($its, array('echo'=>false, 'encode_html'=>true) )  . '<br>';
			$fout.= '</div></div>';



			wp_enqueue_style('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.css');
			wp_enqueue_script('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.js');



		}



	}
	// --- END youtube search query





	return $its;
}


function dzsvg_parse_vimeo($link, $pargs = array(), &$fout=null){


	global $dzsvg;


	$margs = array(
		'max_videos' => '5',
		'enable_outernav_video_author' => 'off',
		'striptags' => 'off',
		'vimeo_sort' => 'default',
		'type' => 'detect',
	);

	if (!is_array($pargs)) {
		$pargs = array();
	}

	$margs = array_merge($margs, $pargs);

	$its = array();


	$type = '';
	$from_logged_in_api = false; // -- this will establish if the feed is from the logged in api
	$original_max_videos = '';



	if($margs['type']=='detect'){
		if(strpos($link,'vimeo.com/album')!==false){
			$type='album';
		}elseif(strpos($link,'vimeo.com/channels')!==false){

			$type='channel';
		}else{

			$type='user';
		}
	}else{
		$type = $margs['type'];
	}





	if($type==''){
		$type='user';
	}


//    echo 'type - '.$type;


//    echo $type;


	$targetfeed = '';
	$q_strings = explode('/',$link);

	if($q_strings[count($q_strings)-1]==''){
	   unset($q_strings[count($q_strings)-1]);
    }

//    print_r($q_strings);

	if($type=='album'){

		$targetfeed = $q_strings[count($q_strings)-1];

//        echo $targetfeed;
	}
	if($type=='channel'){

		$targetfeed = $q_strings[count($q_strings)-1];

//        echo $targetfeed;
	}
	if($type=='user'){

		$targetfeed = $q_strings[count($q_strings)-1];


		if($targetfeed=='videos'){

			$targetfeed = $q_strings[count($q_strings)-2];
		}
//        echo 'targetfeed - '.$targetfeed;
	}



//    print_rr($margs);
//    echo '<br>link - '.$link;
//    echo '<br>type - '.$type;

	$max_videos = $margs['max_videos'];



	// --- vimeo album
	if($type=='album') {





		$cacher = get_option('dzsvg_cache_vmalbum');

		$cached = false;


		if ($cacher == false || is_array($cacher) == false || $dzsvg->mainoptions['disable_api_caching'] == 'on') {
			$cached = false;
		} else {

//                print_r($cacher);


			$ik = -1;
			$i = 0;
			for ($i = 0; $i < count($cacher); $i++) {
				if ($cacher[$i]['id'] == $targetfeed) {
					if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < intval($dzsvg->mainoptions['cache_time'])) {
						$ik = $i;

//                                echo 'yabebe';
						$cached = true;
						break;
					}
				}
			}


			if($cached) {
				foreach ($cacher[$ik]['items'] as $lab => $item) {
					if ($lab === 'settings') {
						continue;
					}

					$its[$lab] = $item;
				}
			}

		}

		// -- finished checking if cached






		$max_videos = $margs['max_videos'];

		if($max_videos==='all'){
			$max_videos = 50;
		}

		if ($dzsvg->mainoptions['debug_mode'] == 'on') {


			echo '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('starting parse from parse_yt_vimeo', 'dzsvg') . '</div>
<div class="toggle-content">';
			echo'cached - ( ' . $cached . ' )  <br>';
			echo'margs -<br>';
			echo (print_rr($margs, array('echo' => false, 'encode_html' => true)));
			echo '</div></div>';
		}




		$breaker = 1;
		$vimeo_response = null;
		$nextPageToken = 'start';

		$i_for_its = 0;


		// -- vimeo album
		if($cached==false){



			while ($breaker < 10 && $nextPageToken !== '') {

				$target_file = "http://vimeo.com/api/v2/album/".$targetfeed."/videos.json";

				$ida = '';
				if ($dzsvg->mainoptions['vimeo_api_client_id'] != '' && $dzsvg->mainoptions['vimeo_api_client_secret'] != '' && $dzsvg->mainoptions['vimeo_api_access_token'] != '' ) {



					if (!class_exists('Vimeo')) {
						require_once(dirname(dirname(__FILE__)).'/vimeoapi/vimeo.php');
					}

					$vimeo_id = $dzsvg->mainoptions['vimeo_api_user_id']; // Get from https://vimeo.com/settings, must be in the form of user123456
					$consumer_key = $dzsvg->mainoptions['vimeo_api_client_id'];
					$consumer_secret = $dzsvg->mainoptions['vimeo_api_client_secret'];
					$token = $dzsvg->mainoptions['vimeo_api_access_token'];


					$sort_call = '';

					$page_call = '';

//					echo 'margs - ';print_rr($margs);
					if($margs['vimeo_sort'] &&$margs['vimeo_sort']!='default'){
						$sort_call.='&sort='.$margs['vimeo_sort'];
					}

					if($nextPageToken && $nextPageToken!='start'){
						$page_call = '&page='.$breaker;
					}


					// -- album




					if($max_videos==''){
						$max_videos='25';
					}

					// Do an authentication call
					$vimeo = new Vimeo($consumer_key,$consumer_secret);
					$vimeo->setToken($token); // -- $token_secret
					$request = '/albums/'.$targetfeed.'/videos?per_page='.$max_videos.$sort_call.$page_call;
					$vimeo_response = $vimeo->request($request);


					if ($dzsvg->mainoptions['debug_mode'] == 'on') {


						echo '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('video response', 'dzsvg') . '</div>
<div class="toggle-content">';
						echo'cached - ( ' . $cached . ' )  <br>';
						echo'api call request - ( ' . $request . ' ) <br>';
						echo (print_rr($vimeo_response, array('echo' => false, 'encode_html' => true)));
						echo '</div></div>';
					}

					if ($vimeo_response['status'] != 200) {
						error_log('dzsvg.php line 4023: '.$vimeo_response['body']['message']);

						if(isset($vimeo_response['body']['error'])){

							array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . $vimeo_response['body']['error']. '</div>');
						}
					}
					if (isset($vimeo_response['body']['data'])) {
						$ida = $vimeo_response['body']['data'];
					}
					$from_logged_in_api = true;
				} else {
					$ida = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $dzsvg->mainoptions['force_file_get_contents']));
					$from_logged_in_api = false;
				}



				if ($dzsvg->mainoptions['debug_mode'] == 'on') {
					echo 'debug mode: mode vimeo album target file - '.$targetfeed
					     .'<br>cached - '.$cached.'<br>vimeo_response is:';
//                print_r($ida);
				}


				$jida = $ida;
//        if (is_array($ida)) {
//            $jida = json_encode($ida);
//        }

				if($from_logged_in_api){

					if(is_array($ida)){

					}else{

						$ida = (array) $ida;
					}


					$idar = array_merge(array(), $ida);
//                print_r($idar);


					// -- authentificated CALL





					if(is_array($idar) && count($idar)){

						foreach ($idar as $item){


							if(is_object($item)){
//                        echo 'cev23a';
								$item = (array) $item;
							}
//                    print_r($item);

							$auxa = array();
							if(isset($item['uri'])){
								$auxa = explode('/',$item['uri']);
							}
							if(isset($item['url'])){
								$auxa = explode('/',$item['url']);
							}
							$its[$i_for_its]['source'] = $auxa[count($auxa) - 1];

//                    print_r($item['pictures']);





							$vimeo_quality_ind = 2;

							if($dzsvg->mainoptions['vimeo_thumb_quality']=='medium'){

								$vimeo_quality_ind = 3;
							}

							if($dzsvg->mainoptions['vimeo_thumb_quality']=='high'){

								$vimeo_quality_ind = 4;
							}

							if(is_object($item['pictures'])){
								$item['pictures'] = (array) $item['pictures'];
								if(is_object($item['pictures']['sizes'])){
									$item['pictures']['sizes'] = (array) $item['pictures']['sizes'];
								}

								if(is_object($item['pictures']['sizes'][$vimeo_quality_ind])){
									$item['pictures']['sizes'][$vimeo_quality_ind] = (array) $item['pictures']['sizes'][$vimeo_quality_ind];
								}
								$its[$i_for_its]['thumbnail'] = $item['pictures']['sizes'][$vimeo_quality_ind]['link'];
							}else{

//							    echo 'its - '; print_rr($its);

//                                echo 'item - '; print_rr($item);
//                        if(isset($item['thumbnail_medium'])){
//
//                            $its[$i]['thethumb'] = $item['thumbnail_medium'];
//                        }
								if(isset($item['thumbnail_large'])){

									$its[$i_for_its]['thumbnail'] = $item['thumbnail_large'];
								}
								if(isset($item['pictures']) && isset($item['pictures']['sizes']) ){


                                    if(isset($item['pictures']['sizes'][$vimeo_quality_ind]) && isset($item['pictures']['sizes'][$vimeo_quality_ind]['link'])){

                                        $its[$i_for_its]['thumbnail'] = $item['pictures']['sizes'][$vimeo_quality_ind]['link'];
                                    }else{
                                        if(isset($item['pictures']['sizes'][$vimeo_quality_ind-1]) && isset($item['pictures']['sizes'][$vimeo_quality_ind-1]['link'])){

                                            $its[$i_for_its]['thumbnail'] = $item['pictures']['sizes'][$vimeo_quality_ind-1]['link'];
                                        }else{
                                            if(isset($item['pictures']['sizes'][$vimeo_quality_ind-2]) && isset($item['pictures']['sizes'][$vimeo_quality_ind-2]['link'])){

                                                $its[$i_for_its]['thumbnail'] = $item['pictures']['sizes'][$vimeo_quality_ind-2]['link'];
                                            }
                                        }
                                    }

								$its[$i_for_its]['thethumb'] = $its[$i_for_its]['thumbnail'];
//                        echo $its[$i]['thethumb'];


                                }
							}
							$its[$i_for_its]['type'] = "vimeo";
							$its[$i_for_its]['permalink'] = "https://vimeo.com/".$its[$i_for_its]['source'];


//				echo '$i_for_its - '; print_rr($its[$i_for_its]);
							if(isset($item['name'])){
								$aux = $item['name'];

							}
							if(isset($item['title'])){
								$aux = $item['title'];
							}




							$lb = array('"',"\r\n","\n","\r","&","`",'???',"'");
							$aux = str_replace($lb,' ',$aux);
							$its[$i_for_its]['title'] = $aux;


							$aux = $item['description'];
							if($margs['striptags']=='on'){
								$aux = strip_tags($aux);
							}
							$lb = array("\r\n","\n","\r");
							$aux = str_replace($lb,'<br>',$aux);
							$lb = array('"');
							$aux = str_replace($lb,'&quot;',$aux);
							$lb = array("'");
							$aux = str_replace($lb,'&#39;',$aux);
							$its[$i_for_its]['description'] = $aux;
							$its[$i_for_its]['menuDescription'] = $aux;
							$i_for_its++;
						}
					}else{

						array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('No items found ? This is the feed - '.$target_file) . '</div>');

					}
				}else{

					// -- simple call

					if (!is_object($ida) && !is_array($ida)) {
						$idar = json_decode($ida); // -- vmuser
					} else {
						$idar = $ida;
					}



					if(is_array($idar) && count($idar)){

						$i_for_its=0;
						foreach ($idar as $item){


							$its[$i_for_its]['source'] = $item->id;
							$its[$i_for_its]['thumbnail'] = $item->thumbnail_medium;





							if($dzsvg->mainoptions['vimeo_thumb_quality']=='high'){

								$its[$i_for_its]['thumbnail'] = $item->thumbnail_large;
							}


							$its[$i_for_its]['type'] = "vimeo";

							$aux = $item->title;
							$lb = array('"',"\r\n","\n","\r","&","`",'???',"'");
							$aux = str_replace($lb,' ',$aux);
							$its[$i_for_its]['title'] = $aux;

							$aux = $item->description;
							$lb = array("\r\n","\n","\r","&",'???');
							$aux = str_replace($lb,' ',$aux);
							$lb = array('"');
							$aux = str_replace($lb,'&quot;',$aux);
							$lb = array("'");
							$aux = str_replace($lb,'&#39;',$aux);
							$its[$i_for_its]['menuDescription'] = $aux;


							$i_for_its++;
						}
					}else{

						array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('No items found ? This is the feed - '.$target_file) . '</div>');

					}
				}











				if ($margs['max_videos'] === 'all') {



					if($from_logged_in_api){


//                        print_r($vimeo_response);
						if($vimeo_response['body']['paging']['next']){
							$nextPageToken = $vimeo_response['body']['paging']['next'];
						}else{
							$nextPageToken = '';
							break;
						}
					}else{

						$nextPageToken = '';
						break;
					}


//                    if (isset($obj->nextPageToken) && $obj->nextPageToken) {
//                        $nextPageToken = $obj->nextPageToken;
//                    } else {
//
//                        $nextPageToken = '';
//                        break;
//                    }

				} else {
					$nextPageToken = '';
					break;
				}



				$breaker++;
			}




		}




		if ($dzsvg->mainoptions['debug_mode'] == 'on') {


			echo '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('finished adding items vimeo album ( $its ) ', 'dzsvg') . '</div>
<div class="toggle-content">';

			echo'$its -<br>';
			echo (print_rr($margs, array('echo' => false, 'encode_html' => true)));
			echo '</div></div>';
		}





		// -- finished adding items



		if ($dzsvg->mainoptions['disable_api_caching'] != 'on') {
			$cache_mainaux = array();
			$cache_aux = array(
				'items' => $its
				,'id' => $targetfeed
				,'time' => $_SERVER['REQUEST_TIME']
				,'from_logged_in_api' => $from_logged_in_api
				,'maxlen' => $margs['max_videos']
			);
			array_push($cache_mainaux,$cache_aux);
			update_option('dzsvg_cache_vmalbum',$cache_mainaux);
		}



	}




	// --- END vimeo album




	// -- vimeo CHANNEL
	if($type=='channel') {



//        echo 'what';


		$cacher = get_option('dzsvg_cache_vmchannel');

		$cached = false;
		$from_logged_in_api = false;

		if ($cacher == false || is_array($cacher) == false || $dzsvg->mainoptions['disable_api_caching'] == 'on') {
			$cached = false;
		} else {

//                print_r($cacher);


			$ik = -1;
			$i = 0;
			for ($i = 0; $i < count($cacher); $i++) {

//            	print_rr('$margs');
//            	print_rr($margs);
//            	print_rr($cacher);
//            	print_rr('$targetfeed - '.$targetfeed);


				if ($cacher[$i]['id'] == $targetfeed) {


//	                echo 'we enter here first';
//
//	                print_rr(' $cacher[$i][\'maxlen\'] - ( '.$cacher[$i]['maxlen'].' )') ;
//	                print_rr( ' $max_videos - ( '.$max_videos.' )') ;
//	                print_rr( ' $cacher[$i][\'maxlen_from_margs\'] - ( '.$cacher[$i]['maxlen_from_margs'].' )') ;
//	                print_rr( ' $margs[\'maxlen\'] - ( '.$margs['max_videos'].' )') ;


//	                echo ' ( isset($cacher[$i][\'maxlen\']) && $cacher[$i][\'maxlen\'] == $max_videos) - ( '.(( isset($cacher[$i]['maxlen']) && $cacher[$i]['maxlen'] == $max_videos)).' )';
//	                echo ' ( isset($cacher[$i][\'maxlen_from_margs\']) && $cacher[$i][\'maxlen_from_margs\'] == $margs[\'maxlen\']) - ( '.( isset($cacher[$i]['maxlen_from_margs']) && $cacher[$i]['maxlen_from_margs'] == $margs['max_videos']).' )';


					if( ( isset($cacher[$i]['maxlen']) && $cacher[$i]['maxlen'] == $max_videos) || ( isset($cacher[$i]['maxlen_from_margs']) && $cacher[$i]['maxlen_from_margs'] == $margs['max_videos']) ) {

//                    	echo 'we enter here';
						if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < intval($dzsvg->mainoptions['cache_time'])) {
							$ik = $i;

//                                echo 'yabebe';
							$cached = true;
							break;
						}
					}
				}
			}


//            print_rr($cacher);
			if($cached) {
				foreach ($cacher[$ik]['items'] as $lab => $item) {
					if ($lab === 'settings') {
						continue;
					}

					$its[$lab] = $item;
				}
			}

		}

		// -- finished checking if cached

//        echo 'cached - '.$cached;




		if ($dzsvg->mainoptions['debug_mode'] == 'on') {


			// -- debug call


			$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . sprintf(__('mode vimeo %s - check cached', 'dzsvg'),$type) . '</div>
<div class="toggle-content">';
			$fout.='cached - ( ' . $cached . ' )  .<br>';



			echo '$margs - let s show $margs -> <br>';
			$fout.='<br>vimeo_channel - $margs is..->'.(print_rr($margs, array('echo' => false, 'encode_html' => true)));

			if($cached){
				echo 'cached - let s show prize -> <br>';
				$fout.='<br>$its is..->'.(print_rr($vimeo_response, array('echo' => false, 'encode_html' => true)));

			}else{

				echo 'not cached  -why ?-> <br>';
				$fout.='<br>$cacher is..->'.(print_rr($cacher, array('echo' => false, 'encode_html' => true)));
				$fout.='<br>we look for max_videos '.(print_rr($max_videos, array('echo' => false, 'encode_html' => true)));
				$fout.='<br>we look for '.(print_rr($max_videos, array('echo' => false, 'encode_html' => true)));
			}
			$fout.= '</div></div>';
			wp_enqueue_style('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.css');
			wp_enqueue_script('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.js');
		}




		//-- vimeo channel




		$breaker = 1;
		$vimeo_response = null;
		$nextPageToken = 'start';

		$i_for_its = 0;


		if($cached==false){

			while ($breaker < 10 && $nextPageToken !== '') {
				$target_file = "https://vimeo.com/api/v2/channel/".$targetfeed."/videos.json";

				$ida = '';
				if ($dzsvg->mainoptions['vimeo_api_client_id'] != '' && $dzsvg->mainoptions['vimeo_api_client_secret'] != '' && $dzsvg->mainoptions['vimeo_api_access_token'] != '' ) {

					$from_logged_in_api = true;


					if (!class_exists('Vimeo')) {
						require_once(dirname(dirname(__FILE__)).'/vimeoapi/vimeo.php');
					}

					$vimeo_id = $dzsvg->mainoptions['vimeo_api_user_id']; // Get from https://vimeo.com/settings, must be in the form of user123456
					$consumer_key = $dzsvg->mainoptions['vimeo_api_client_id'];
					$consumer_secret = $dzsvg->mainoptions['vimeo_api_client_secret'];
					$token = $dzsvg->mainoptions['vimeo_api_access_token'];


					// -- sanitizing
					if($max_videos ==''){
						$max_videos = '25';
					}



					$original_max_videos = $max_videos;

					if($max_videos==='all'){
						$max_videos = 96;
					}



					$sort_call = '';

					$page_call = '';

					if($margs['vimeo_sort'] &&$margs['vimeo_sort']!='default'){
						$sort_call.='&sort='.$margs['vimeo_sort'];
					}

					if($nextPageToken && $nextPageToken!='start'){
						$page_call = '&page='.$breaker;
					}



					// Do an authentication call
					$vimeo = new Vimeo($consumer_key,$consumer_secret);
					$vimeo->setToken($token); //,$token_secret
//                $vimeo_response = $vimeo->request('/channels/'.$targetfeed.'/videos?per_page='.$max_videos);
					$request = '/channels/'.$targetfeed.'/videos?per_page='.$max_videos.$sort_call.$page_call;
					$vimeo_response = $vimeo->request($request);



					if ($dzsvg->mainoptions['debug_mode'] == 'on') {


						// -- debug call


						$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . sprintf(__('mode vimeo %s - making autetificated call', 'dzsvg'),$type) . '</div>
<div class="toggle-content">';
						$fout.='cached - ( ' . $cached . ' )  .<br>';
						$fout.='$max_videos - ( ' . $max_videos . ' )  .<br>';
						$fout.='<br>cacher is..->'.(print_rr($vimeo_response, array('echo' => false, 'encode_html' => true)));
						$fout.= '</div></div>';
						wp_enqueue_style('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.css');
						wp_enqueue_script('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.js');
					}






					if ($vimeo_response['status'] != 200) {


						try{
							error_log('dzsvg.php line 4023: '.$vimeo_response['body']['message']);



							if(isset($vimeo_response['body']['error'])){

								array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . $vimeo_response['body']['error']. '</div>');
							}

						}catch(Exception $err){

							$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . sprintf(__('mode vimeo %s - making autetificated call', 'dzsvg'),$type) . '</div>
<div class="toggle-content">';
							$fout.='cached - ( ' . $cached . ' )  cacher is...<br>';
							$fout.=(print_rr($vimeo_response, array('echo' => false, 'encode_html' => true)));
							$fout.=(print_rr($err, array('echo' => false, 'encode_html' => true)));
							$fout.= '</div></div>';



						}

						/*
						*/
					}
					if (isset($vimeo_response['body']['data'])) {
						$ida = $vimeo_response['body']['data'];
					}
					$from_logged_in_api = true;
				} else {
					$ida = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $dzsvg->mainoptions['force_file_get_contents']));
					$from_logged_in_api = false;




					if ($dzsvg->mainoptions['debug_mode'] == 'on') {




						$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . sprintf(__('trying to feed from non authentificated call', 'dzsvg')) . '</div>
<div class="toggle-content">';
						$fout.='cached - ( ' . $cached . ' )  cacher is...<br>';
						$fout.='$target_file - '.(print_rr($target_file, array('echo' => false, 'encode_html' => true)));
						$fout.='ida - '.(print_rr($ida, array('echo' => false, 'encode_html' => true)));
						$fout.='file_get_contents - '.(DZSHelpers::get_contents($target_file));
						$fout.= '</div></div>';
					}
				}



				if ($dzsvg->mainoptions['debug_mode'] == 'on') {
					echo 'debug mode: mode vimeo album target file - '.$targetfeed
					     .'<br>cached - '.$cached.'<br>vimeo_response is:';
//                print_r($ida);
				}


				$jida = $ida;
//        if (is_array($ida)) {
//            $jida = json_encode($ida);
//        }


				dzsvg_parse_vimeo_its($its,$from_logged_in_api,$ida,$i_for_its,$margs);











				if ($margs['max_videos'] === 'all') {



					if($from_logged_in_api){


//                        print_r($vimeo_response);
						if($vimeo_response['body']['paging']['next']){
							$nextPageToken = $vimeo_response['body']['paging']['next'];
						}else{
							$nextPageToken = '';
							break;
						}
					}else{

						$nextPageToken = '';
						break;
					}


//                    if (isset($obj->nextPageToken) && $obj->nextPageToken) {
//                        $nextPageToken = $obj->nextPageToken;
//                    } else {
//
//                        $nextPageToken = '';
//                        break;
//                    }

				} else {
					$nextPageToken = '';
					break;
				}



				$breaker++;
			}




		}







		// -- finished adding items
		if ($dzsvg->mainoptions['disable_api_caching'] != 'on') {
			$cache_mainaux = array();
			$cache_aux = array(
				'items' => $its
				,'id' => $targetfeed
				,'time' => $_SERVER['REQUEST_TIME']
				,'from_logged_in_api' => $from_logged_in_api
				,'maxlen' => $original_max_videos
				,'maxlen_from_margs' => $margs['max_videos']
			);
			array_push($cache_mainaux,$cache_aux);
			update_option('dzsvg_cache_vmchannel',$cache_mainaux);
		}



	}
	// --- END vimeo channel















	// start vmuser / start vimeo user


	// -- vimeo user CHANNEL
	if($type=='user') {



		//        echo 'what';


		$cacher = get_option('dzsvg_cache_vmuserchannel');

		$cached = false;
		$from_logged_in_api = false;

		if ($cacher == false || is_array($cacher) == false || $dzsvg->mainoptions['disable_api_caching'] == 'on') {
			$cached = false;
		} else {

			//                print_r($cacher);


			$ik = -1;
			$i = 0;
			for ($i = 0; $i < count($cacher); $i++) {


				if ($cacher[$i]['id'] == $targetfeed) {



					if( ( isset($cacher[$i]['maxlen']) && $cacher[$i]['maxlen'] == $max_videos) || ( isset($cacher[$i]['maxlen_from_margs']) && $cacher[$i]['maxlen_from_margs'] == $margs['max_videos']) ) {

						//                    	echo 'we enter here';
						if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < intval($dzsvg->mainoptions['cache_time'])) {
							$ik = $i;

							//                                echo 'yabebe';
							$cached = true;
							break;
						}
					}
				}
			}


			//            print_rr($cacher);
			if($cached) {
				foreach ($cacher[$ik]['items'] as $lab => $item) {
					if ($lab === 'settings') {
						continue;
					}

					$its[$lab] = $item;
				}
			}

		}

		// -- finished checking if cached

		//        echo 'cached - '.$cached;




		if ($dzsvg->mainoptions['debug_mode'] == 'on') {


			// -- debug call


			$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . sprintf(__('mode vimeo %s - check cached', 'dzsvg'),$type) . '</div>
<div class="toggle-content">';
			$fout.='cached - ( ' . $cached . ' )  .<br>';



			echo '$margs - let s show $margs -> <br>';
			$fout.='<br>vimeo_channel - $margs is..->'.(print_rr($margs, array('echo' => false, 'encode_html' => true)));

			if($cached){
				echo 'cached - let s show prize -> <br>';
				$fout.='<br>$its is..->'.(print_rr($vimeo_response, array('echo' => false, 'encode_html' => true)));

			}else{

				echo 'not cached  -why ?-> <br>';
				$fout.='<br>$cacher is..->'.(print_rr($cacher, array('echo' => false, 'encode_html' => true)));
				$fout.='<br>we look for max_videos '.(print_rr($max_videos, array('echo' => false, 'encode_html' => true)));
				$fout.='<br>we look for '.(print_rr($max_videos, array('echo' => false, 'encode_html' => true)));
			}
			$fout.= '</div></div>';
			wp_enqueue_style('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.css');
			wp_enqueue_script('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.js');
		}




		//-- vimeo user channel




		$breaker = 1;
		$vimeo_response = null;
		$nextPageToken = 'start';

		$i_for_its = 0;


		if($cached==false){

			while ($breaker < 10 && $nextPageToken !== '') {
				$target_file = "https://vimeo.com/api/v2/".$targetfeed."/videos.json";

				$ida = '';
				if ($dzsvg->mainoptions['vimeo_api_client_id'] != '' && $dzsvg->mainoptions['vimeo_api_client_secret'] != '' && $dzsvg->mainoptions['vimeo_api_access_token'] != '' ) {

					$from_logged_in_api = true;


					if (!class_exists('Vimeo')) {
						require_once(dirname(dirname(__FILE__)).'/vimeoapi/vimeo.php');
					}

					$vimeo_id = $dzsvg->mainoptions['vimeo_api_user_id']; // Get from https://vimeo.com/settings, must be in the form of user123456
					$consumer_key = $dzsvg->mainoptions['vimeo_api_client_id'];
					$consumer_secret = $dzsvg->mainoptions['vimeo_api_client_secret'];
					$token = $dzsvg->mainoptions['vimeo_api_access_token'];


					// -- sanitizing
					if($max_videos ==''){
						$max_videos = '25';
					}



					$original_max_videos = $max_videos;

					if($max_videos==='all'){
						$max_videos = 96;
					}



					$sort_call = '';

					$page_call = '';

					if($margs['vimeo_sort'] &&$margs['vimeo_sort']!='default'){
						$sort_call.='&sort='.$margs['vimeo_sort'];
					}

					if($nextPageToken && $nextPageToken!='start'){
						$page_call = '&page='.$breaker;
					}



					// Do an authentication call
					$vimeo = new Vimeo($consumer_key,$consumer_secret);
					$vimeo->setToken($token); //,$token_secret
					//                $vimeo_response = $vimeo->request('/channels/'.$targetfeed.'/videos?per_page='.$max_videos);
					$request = '/users/'.$targetfeed.'/videos?per_page='.$max_videos.$sort_call.$page_call;
					$vimeo_response = $vimeo->request($request);



					if ($dzsvg->mainoptions['debug_mode'] == 'on') {


						// -- debug call


						$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . sprintf(__('mode vimeo %s - making authentificated call', 'dzsvg'),$type) . '</div>
<div class="toggle-content">';
						$fout.='cached - ( ' . $cached . ' )  .<br>';
						$fout.='$max_videos - ( ' . $max_videos . ' )  .<br>';
						$fout.='<br>cacher is..->'.(print_rr($vimeo_response, array('echo' => false, 'encode_html' => true)));
						$fout.= '</div></div>';
						wp_enqueue_style('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.css');
						wp_enqueue_script('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.js');
					}






					if ($vimeo_response['status'] != 200) {


						try{
							error_log('dzsvg.php line 4023: '.$vimeo_response['body']['message']);



							if(isset($vimeo_response['body']['error'])){

								array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . $vimeo_response['body']['error']. '</div>');
							}

						}catch(Exception $err){

							$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . sprintf(__('mode vimeo %s - making autetificated call', 'dzsvg'),$type) . '</div>
<div class="toggle-content">';
							$fout.='cached - ( ' . $cached . ' )  cacher is...<br>';
							$fout.=(print_rr($vimeo_response, array('echo' => false, 'encode_html' => true)));
							$fout.=(print_rr($err, array('echo' => false, 'encode_html' => true)));
							$fout.= '</div></div>';



						}

						/*
						*/
					}
					if (isset($vimeo_response['body']['data'])) {
						$ida = $vimeo_response['body']['data'];
					}
					$from_logged_in_api = true;
				} else {
					$ida = DZSHelpers::get_contents($target_file,array('force_file_get_contents' => $dzsvg->mainoptions['force_file_get_contents']));
					$from_logged_in_api = false;




					if ($dzsvg->mainoptions['debug_mode'] == 'on') {




						$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . sprintf(__('trying to feed from non authentificated call', 'dzsvg')) . '</div>
<div class="toggle-content">';
						$fout.='cached - ( ' . $cached . ' )  cacher is...<br>';
						$fout.='$target_file - '.(print_rr($target_file, array('echo' => false, 'encode_html' => true)));
						$fout.='ida - '.(print_rr($ida, array('echo' => false, 'encode_html' => true)));
						$fout.='file_get_contents - '.(DZSHelpers::get_contents($target_file));
						$fout.= '</div></div>';
					}
				}



				if ($dzsvg->mainoptions['debug_mode'] == 'on') {
					echo 'debug mode: mode vimeo album target file - '.$targetfeed
					     .'<br>cached - '.$cached.'<br>vimeo_response is:';
					//                print_r($ida);
				}


				$jida = $ida;
				//        if (is_array($ida)) {
				//            $jida = json_encode($ida);
				//        }


				dzsvg_parse_vimeo_its($its,$from_logged_in_api,$ida,$i_for_its,$margs);











				if ($margs['max_videos'] === 'all') {



					if($from_logged_in_api){


						//                        print_r($vimeo_response);
						if($vimeo_response['body']['paging']['next']){
							$nextPageToken = $vimeo_response['body']['paging']['next'];
						}else{
							$nextPageToken = '';
							break;
						}
					}else{

						$nextPageToken = '';
						break;
					}


					//                    if (isset($obj->nextPageToken) && $obj->nextPageToken) {
					//                        $nextPageToken = $obj->nextPageToken;
					//                    } else {
					//
					//                        $nextPageToken = '';
					//                        break;
					//                    }

				} else {
					$nextPageToken = '';
					break;
				}



				$breaker++;
			}




		}







		// -- finished adding items
		if ($dzsvg->mainoptions['disable_api_caching'] != 'on') {
			$cache_mainaux = array();
			$cache_aux = array(
				'items' => $its
				,'id' => $targetfeed
				,'time' => $_SERVER['REQUEST_TIME']
				,'from_logged_in_api' => $from_logged_in_api
				,'maxlen' => $original_max_videos
				,'maxlen_from_margs' => $margs['max_videos']
			);
			array_push($cache_mainaux,$cache_aux);
			update_option('dzsvg_cache_vmuserchannel',$cache_mainaux);
		}



	}



	// --- END vimeo user channel






	return $its;
}

function dzsvg_parse_vimeo_its(&$its, $from_logged_in_api, $ida,&$i_for_its,&$margs){


	global $dzsvg;

//	$its = array();

	if($from_logged_in_api){

		if(is_array($ida)){

		}else{

			$ida = (array) $ida;
		}


		$idar = array_merge(array(), $ida);
//                print_r($idar);


		// -- authentificated CALL





//		print_rr($margs);

		if(is_array($idar) && count($idar)){

			foreach ($idar as $item){


				if(is_object($item)){
//                        echo 'cev23a';
					$item = (array) $item;
				}
//                    print_r($item);

				$auxa = array();
				if(isset($item['uri'])){
					$auxa = explode('/',$item['uri']);
				}
				if(isset($item['url'])){
					$auxa = explode('/',$item['url']);
				}
				$its[$i_for_its]['source'] = $auxa[count($auxa) - 1];

//                    print_r($item['pictures']);





				$vimeo_quality_ind = 2;

				if($dzsvg->mainoptions['vimeo_thumb_quality']=='medium'){

					$vimeo_quality_ind = 3;
				}

				if($dzsvg->mainoptions['vimeo_thumb_quality']=='high'){

					$vimeo_quality_ind = 4;
				}

				if(is_object($item['pictures'])){
					$item['pictures'] = (array) $item['pictures'];
					if(is_object($item['pictures']['sizes'])){
						$item['pictures']['sizes'] = (array) $item['pictures']['sizes'];
					}

					if(is_object($item['pictures']['sizes'][$vimeo_quality_ind])){
						$item['pictures']['sizes'][$vimeo_quality_ind] = (array) $item['pictures']['sizes'][$vimeo_quality_ind];
					}
					$its[$i_for_its]['thumbnail'] = $item['pictures']['sizes'][$vimeo_quality_ind]['link'];
				}else{

//                        if(isset($item['thumbnail_medium'])){
//
//                            $its[$i]['thethumb'] = $item['thumbnail_medium'];
//                        }
					if(isset($item['thumbnail_large'])){

						$its[$i_for_its]['thumbnail'] = $item['thumbnail_large'];
					}
					if(isset($item['pictures']['sizes'][$vimeo_quality_ind]['link'])){

						$its[$i_for_its]['thumbnail'] = $item['pictures']['sizes'][$vimeo_quality_ind]['link'];
					}

					$its[$i_for_its]['thethumb'] = $its[$i_for_its]['thumbnail'];
//                        echo $its[$i]['thethumb'];
				}
				$its[$i_for_its]['type'] = "vimeo";

				$its[$i_for_its]['permalink'] = "https://vimeo.com/".$its[$i_for_its]['source'];

//				echo '$i_for_its - '; print_rr($its[$i_for_its]);



				if(isset($item['name'])){
					$aux = $item['name'];

				}
				if(isset($item['title'])){
					$aux = $item['title'];
				}




				$lb = array('"',"\r\n","\n","\r","&","`",'???',"'");
				$aux = str_replace($lb,' ',$aux);
				$its[$i_for_its]['title'] = $aux;

				$its[$i_for_its]['all_description'] = $item['description'];

				$aux = wp_kses($item['description'],$dzsvg->allowed_tags);

				if(isset($margs['desc_count']) && $margs['desc_count'] && $margs['desc_count']!='all'){
					$aux = DZSHelpers::wp_get_excerpt(-1,array(
						'maxlen' => $margs['desc_count'],
						'content' => $item['description'],
						'aftercutcontent_html'=>' [ ... ] ',
					));
				}


				$its[$i_for_its]['description'] = $aux;
				$its[$i_for_its]['menuDescription'] = $aux;
				$i_for_its++;
			}
		}else{


		}
	}else{

		// -- simple call

		if (!is_object($ida) && !is_array($ida)) {
			$idar = json_decode($ida); // -- vmuser
		} else {
			$idar = $ida;
		}



		if(is_array($idar) && count($idar)){

			$i_for_its=0;
			foreach ($idar as $item){




				if ($dzsvg->mainoptions['debug_mode'] == 'on') {

					echo '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('item - ', 'dzsvg') . $i_for_its.'</div>
<div class="toggle-content">';

					echo  (print_rr($item, array('echo' => false, 'encode_html' => true)));
					echo  '</div></div>';
				}




				$its[$i_for_its]['source'] = $item->id;
				$its[$i_for_its]['type'] = "vimeo";



				if($dzsvg->mainoptions['vimeo_thumb_quality']=='high'){

					$its[$i_for_its]['thumbnail'] = $item->thumbnail_large;
				}

				$aux = $item->title;
				$lb = array('"',"\r\n","\n","\r","&","`",'???',"'");
				$aux = str_replace($lb,' ',$aux);
				$its[$i_for_its]['title'] = $aux;

				$aux = $item->description;
				$lb = array("\r\n","\n","\r","&",'???');
				$aux = str_replace($lb,' ',$aux);
				$lb = array('"');
				$aux = str_replace($lb,'&quot;',$aux);
				$lb = array("'");
				$aux = str_replace($lb,'&#39;',$aux);
				$its[$i_for_its]['menuDescription'] = $aux;


				$i_for_its++;
			}
		}else{



		}
	}

//	return $its;
}






























//print_rr($this);


//	define('FACEBOOK_SDK_V4_SRC_DIR',(dirname(__FILE__).'/Facebook'));
//	include_once "Facebook/autoload.php";
//	include_once $dzsvg->base_path."facebook/fa.php";


function GetUserIDFromUsername($username) {
	// For some reason, changing the user agent does expose the user's UID
	$options  = array('http' => array('user_agent' => 'some_obscure_browser'));
	$context  = stream_context_create($options);
	$fbsite = file_get_contents('https://www.facebook.com/' . $username, false, $context);

	// ID is exposed in some piece of JS code, so we'll just extract it
	$fbIDPattern = '/\"entity_id\":\"(\d+)\"/';
	if (!preg_match($fbIDPattern, $fbsite, $matches)) {
		throw new Exception('Unofficial API is broken or user not found');
	}
	return $matches[1];
}





function dzsvg_parse_facebook($link, $pargs = array(), &$fout=null){


	global $dzsvg;


	$margs = array(
		'maxlen' => '50',
		'enable_outernav_video_author' => 'off',
		'enable_outernav_video_date' => 'off',
		'striptags' => 'off',
		'get_full_description' => 'off',
		'type' => 'detect',
		'subtype' => 'detect',
		'query' => '',
	);

	if (!is_array($pargs)) {
		$pargs = array();
	}

	$margs = array_merge($margs, $pargs);

	$its = array();


	$subtype = '';



	if($margs['maxlen']==''){
		$margs['maxlen'] = '30';
	}



	$max_videos = $margs['maxlen'];
	$original_max_videos = $max_videos;

	$id = 'raywilliamjohnson';

	$cached = false;





	$cacher = get_option('dzsvg_cache_facebook_'.$dzsvg->clean($id));

	if ($cacher == false || is_array($cacher) == false || $dzsvg->mainoptions['disable_api_caching'] == 'on') {
		$cached = false;
	} else {



		$ik = -1;
		$i = 0;
		if( ( isset($cacher[$max_videos]['maxlen']) && $cacher[$max_videos]['maxlen'] == $margs['maxlen']) ) {

			//                    	echo 'we enter here';
			if ($_SERVER['REQUEST_TIME'] - $cacher[$max_videos]['time'] < intval($dzsvg->mainoptions['cache_time'])) {
				$ik = $i;

				//                                echo 'yabebe';
				$cached = true;
			}
		}


//		            echo 'cached - '.$cached;
//		            print_rr($cacher);
		if($cached) {
			foreach ($cacher[$max_videos]['items'] as $lab => $item) {
				if ($lab === 'settings') {
					continue;
				}

				$its[$lab] = $item;
			}
		}

	}



	if($cached==false){
		$app_id = $dzsvg->mainoptions['facebook_app_id'];
		$app_secret = $dzsvg->mainoptions['facebook_app_secret'];


		$posts = null;
		$response = null;

		if($app_id && $app_secret){

			require_once 'src/Facebook/autoload.php'; // change path as needed

			$fb = new \Facebook\Facebook([
				'app_id' => $app_id,
				'app_secret' => $app_secret,
				'default_graph_version' => 'v2.10',
				//'default_access_token' => '{access-token}', // optional
			]);

// Use one of the helper classes to get a Facebook\Authentication\AccessToken entity.
//   $helper = $fb->getRedirectLoginHelper();
//   $helper = $fb->getJavaScriptHelper();
//   $helper = $fb->getCanvasHelper();
//   $helper = $fb->getPageTabHelper();





//			print_rr($margs);
			$id = $margs['facebook_source'];
//		$id = '100000085825669';


			if($link){
				$id = $link;
			}


			$id_arr = explode('/',$id);


			if($id_arr[count($id_arr)-1]){
				$id = $id_arr[count($id_arr)-1];
			}else{

				$id = $id_arr[count($id_arr)-2];
			}

//			print_rr($id_arr);





			$accessToken = $dzsvg->mainoptions['facebook_access_token'];
			$helper = $fb->getRedirectLoginHelper();

//	$accessToken = '';


			// pictures - https://graph.facebook.com/10155310281108386/thumbnails?access_token=EAAVcLm1RBJ0BAEbWGcoKhkPMMa6ZCKJvz5nnRn1fd2NnoxPIP2AjTQ35OahjZBWBCiqxS3MPCu04cDNApFywZC8koVPgZB8mglpuMzKqIPgBMTRf4FcVC3TldZBrgNZC4BVZBoO8ZBZB2cDZBbVgRMOQgbDdN6AZAzh1gQEcEGQofs9OAZDZD

			if(isset($_GET['state'])){

				$_SESSION['FBRLH_state']=$_GET['state'];
			}












			if($accessToken){

			}else{


				// here we will try to save access toekn
				try {
					$accessToken = $helper->getAccessToken();
				} catch (Facebook\Exceptions\FacebookResponseException $e) {
					// When Graph returns an error
					echo 'Graph 2975 returned an error: ' . $e->getMessage();

				} catch (Facebook\Exceptions\FacebookSDKException $e) {
					// When validation fails or other local issues
					echo 'redirect-from-facebook.php - Facebook 26 SDK returned an error: ' . $e->getMessage().'...'.print_rr($e,true);
					print_rr("__GET__".print_rr($_GET,true). "__SESSION__" .print_rr($_SESSION,true));
					print_rr($helper->getPersistentDataHandler());
					print_rr($helper->getError());
//		exit;
				}
			}











			if($accessToken){


				if (! isset($accessToken)) {
					if ($helper->getError()) {
						header('HTTP/1.0 401 Unauthorized');
						echo "Error: " . $helper->getError() . "\n";
						echo "Error Code: " . $helper->getErrorCode() . "\n";
						echo "Error Reason: " . $helper->getErrorReason() . "\n";
						echo "Error Description: " . $helper->getErrorDescription() . "\n";
					} else {
//				header('HTTP/1.0 400 Bad Request');
						echo 'Bad request';
					}
//			exit;
				}






				$from_logged_in_api = true;

				try {
					// Returns a `Facebook\FacebookResponse` object

					// TODO: we don't need thumbnails for now thumbnails,


					// ?fields=title,picture,description,source,embeddable,embed_html

					$response = $fb->get(
						'/'.$id.'/videos?fields=title,picture,description,source,embeddable',
						$accessToken
					);
				} catch(Facebook\Exceptions\FacebookResponseException $e) {
					echo 'Graph 3183 returned an error ( id - '.$id.' ): ' . $e->getMessage();
					print_rr($response);
//					print_rr($fb);
//			exit;
				} catch(Facebook\Exceptions\FacebookSDKException $e) {
					echo 'Facebook SDK returned an error: ' . $e->getMessage();
//			exit;
				}


// Or if you have the latest dev version of the official SDK

				if ($dzsvg->mainoptions['debug_mode'] == 'on') {
					// -- debug call

					$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . sprintf(__('facebook response 3', 'dzsvg')) . '</div>
<div class="toggle-content">';

					$fout.='<br>$response is..->'.(print_rr($response, array('echo' => false, 'encode_html' => true)));
					$fout.= '</div></div>';
					wp_enqueue_style('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.css');
					wp_enqueue_script('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.js');
				}




				try{


					if($response){

						$graphEdge = $response->getGraphEdge();
//			print_rr($graphEdge);
						$posts = $graphEdge->asArray();
//			print_rr($posts);
//			$totalCount = $graphEdge->getTotalCount();

					}


















//			echo 'totalCount - '.$totalCount;
// Returns: 10
				}catch(Exception $e){



					try{

						
						$graphNode = $response->getGraphNode();




						print_rr($graphNode);








//			echo 'totalCount - '.$totalCount;
// Returns: 10
					}catch(Exception $e){
						error_log("facebook api error".print_r($e,true));
					}



				}
//		$graphNode = $response->getGraphNode();
//		print_rr($graphNode);











				if ($dzsvg->mainoptions['debug_mode'] == 'on') {


					// -- debug call


					$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . sprintf(__('facebook $posts', 'dzsvg')) . '</div>
<div class="toggle-content">';

					$fout.='<br>id is..->'.(print_rr($id, array('echo' => false, 'encode_html' => true)));
					$fout.='<br>$posts is..->'.(print_rr($posts, array('echo' => false, 'encode_html' => true)));
					$fout.= '</div></div>';
					wp_enqueue_style('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.css');
					wp_enqueue_script('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.js');
				}




				if($posts){
					$breaker = 1;
					$vimeo_response = null;
					$nextPageToken = 'start';

					$i_for_its = 0;




					while ($breaker < 10 && $nextPageToken !== '') {


						$ida = '';
						$from_logged_in_api = true;






						// -- sanitizing
						if($max_videos ==''){
							$max_videos = '25';
						}




						if($max_videos==='all'){
							$max_videos = 96;
						}



						$sort_call = '';

						$page_call = '';








						if (isset($vimeo_response['body']['data'])) {
							$ida = $vimeo_response['body']['data'];
						}
						$from_logged_in_api = true;




						if ($dzsvg->mainoptions['debug_mode'] == 'on') {
							echo 'debug mode: mode vimeo album target file - '.$id
							     .'<br>cached - '.$cached.'<br>vimeo_response is:';
							//                print_r($ida);
						}







						$idar = $posts;
						if(is_array($idar) && count($idar)){

							foreach ($idar as $item){


								if(is_object($item)){
//                        echo 'cev23a';
									$item = (array) $item;
								}
//                    print_r($item);

								$auxa = array();
								if(isset($item['uri'])){
									$auxa = explode('/',$item['uri']);
								}
								if(isset($item['url'])){
									$auxa = explode('/',$item['url']);
								}
								$its[$i_for_its]['source'] = $item['source'];

//                    print_r($item['pictures']);



								$its[$i_for_its]['thumbnail'] = $item['picture'];
								$its[$i_for_its]['thethumb'] = $item['picture'];





								$its[$i_for_its]['type'] = "video";

								if(isset($item['description'])){
									$its[$i_for_its]['description'] = $item['description'];
								}



								$aux = '';
								if(isset($item['title'])){
									$aux = $item['title'];
								}else{
									$aux = 'title';
								}




								$lb = array('"',"\r\n","\n","\r","&","`",'???',"'");
								$aux = str_replace($lb,' ',$aux);
								$its[$i_for_its]['title'] = $aux;


								// -- description

								$aux = 'description';
								if(isset($item['description'])) {
									$aux = $item['description'];
								}else{
									$aux = 'description';
								}
								if($margs['striptags']=='on'){
									$aux = strip_tags($aux);


									$lb = array("\r\n","\n","\r");
									$aux = str_replace($lb,'<br>',$aux);
									$lb = array('"');
									$aux = str_replace($lb,'&quot;',$aux);
									$lb = array("'");
									$aux = str_replace($lb,'&#39;',$aux);
									$its[$i_for_its]['description'] = $aux;
									$its[$i_for_its]['menuDescription'] = $aux;
								}
								$i_for_its++;
							}
						}else{


						}


						$jida = $ida;
						//        if (is_array($ida)) {
						//            $jida = json_encode($ida);
						//        }








						$nextPageToken = '';


						$breaker++;
					}


				}












				// -- finished adding items
				if ($dzsvg->mainoptions['disable_api_caching'] != 'on') {
					$cache_mainaux = array();
					$cache_aux = array(
						'items' => $its
						,'time' => $_SERVER['REQUEST_TIME']
						,'from_logged_in_api' => $from_logged_in_api
						,'maxlen' => $original_max_videos
						,'maxlen_from_margs' => $margs['max_videos']
					);
					$cache_mainaux[$original_max_videos] = $cache_aux;
					update_option('dzsvg_cache_facebook_'.$dzsvg->clean($id),$cache_mainaux);
				}













//		try {
//			// Returns a `Facebook\FacebookResponse` object
//			$response = $fb->get(
//				'10155310281108386?fields=description,title,thumbnails,source,picture',
//				$accessToken
//			);
//		} catch(Facebook\Exceptions\FacebookResponseException $e) {
//			echo 'Graph returned an error: ' . $e->getMessage();
//		} catch(Facebook\Exceptions\FacebookSDKException $e) {
//			echo 'Facebook SDK returned an error: ' . $e->getMessage();
//		}
//		$graphNode = $response->getGraphNode();
//		print_rr($graphNode->asArray());
				/* handle the result */


//		echo 'ceva';
			}else{

				$helper = $fb->getRedirectLoginHelper();

				$permissions = ['email']; // Optional permissions
				$loginUrl = $helper->getLoginUrl(dzs_curr_url(), $permissions);


				if(isset($_SESSION) && is_array($_SESSION)){

					foreach ($_SESSION as $k=>$v) {
						if(strpos($k, "FBRLH_")!==FALSE) {
							if(!setcookie($k, $v)) {
								//what??
							} else {
								$_COOKIE[$k]=$v;
							}
						}
					}
				}

				echo '<a href="' . htmlspecialchars($loginUrl) . '">Log in with Facebook!</a>';
			}

		}else{
			echo '<div class="warning">'.esc_html__("You need to set up your facebook api in Video Gallery > Settings > Facebook",'dzsvg').'</div>';
		}



	}



	if ($dzsvg->mainoptions['debug_mode'] == 'on') {


		// -- debug call


		$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . sprintf(__('mode vimeo %s - making authentificated call - facebook', 'dzsvg'),'facebook') . '</div>
<div class="toggle-content">';
		$fout.='api call - ( ' . '/'.$id.'/videos?fields=picture,description' . ' )  .<br>';
		$fout.='margs - ( ' . print_rr($margs, array('echo' => false, 'encode_html' => true)) . ' )  .<br>';
		$fout.='cached - ( ' . $cached . ' )  .<br>';
		$fout.='$max_videos - ( ' . $max_videos . ' )  .<br>';
		$fout.='<br>cacher is..->'.(print_rr($posts, array('echo' => false, 'encode_html' => true)));
		$fout.= '</div></div>';
		wp_enqueue_style('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.css');
		wp_enqueue_script('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.js');
	}





//	if (isset($accessToken)) {
//		// Logged in!
//
////		echo '$accessToken - '.$accessToken;
//
//		$_SESSION['facebook_access_token'] = (string)$accessToken;
//
//	} elseif ($helper->getError()) {
//		// The user denied the request
////		header("Location:index.php?err=or");
////		exit;
//	}


//	print_rr($helper);



	return $its;

	$targetfeed = '';

	if(strpos($link,'/')!==false){
		$q_strings = explode('/',$link);

//    print_r($q_strings);

		if($subtype=='user_channel'){

			$targetfeed = $q_strings[count($q_strings)-1];

//        echo $targetfeed;
		}
		if($subtype=='playlist'){

			$targetfeed = DZSHelpers::get_query_arg($link, 'list');

//        echo 'targetfeed - '.$targetfeed.' ||| ';
		}
		if($subtype=='search'){

			$targetfeed = DZSHelpers::get_query_arg($link, 'search_query');

//        echo $targetfeed;
		}

	}else{
		$targetfeed = $link;
	}

	if($margs['query']){
		if($targetfeed==''){
			$targetfeed = $margs['query'];
		}
	}



//    print_r($dzsvg->mainoptions);
	if ($dzsvg->mainoptions['debug_mode'] == 'on') {
		if($fout!=null){

		}
		$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('first settings', 'dzsvg') . '</div>
<div class="toggle-content">';
		$fout.='debug mode: target file ( ' . '$link - '.$link . ' ) <br>';
		$fout.='debug mode: target file ( ' . 'feed type - '.$subtype . ' ) <br>';
		$fout.='debug mode: target file ( ' . '$targetfeed - '.$targetfeed . ' ) <br>';
		$fout.='debug mode: $obj - ' . print_rr($margs, array('echo'=>false, 'encode_html'=>true) )  . '<br>';
		$fout.= '</div></div>';


	}




	$max_videos = $margs['max_videos'];


	if($max_videos=='all'){
		$max_videos = 50;
	}


	// --- user channel
	if($subtype=='user_channel') {





		$cacher = get_option('dzsvg_cache_ytuserchannel');

		$cached = false;


		if ($cacher == false || is_array($cacher) == false || $dzsvg->mainoptions['disable_api_caching'] == 'on') {
			$cached = false;
		} else {

//                print_r($cacher);


			$ik = -1;
			$i = 0;
			for ($i = 0; $i < count($cacher); $i++) {
				if ($cacher[$i]['id'] == $targetfeed) {
					if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 7200) {
						$ik = $i;

//                                echo 'yabebe';
						$cached = true;
						break;
					}
				}
			}


			if($cached) {
				foreach ($cacher[$ik]['items'] as $lab => $item) {
					if ($lab === 'settings') {
						continue;
					}

					$its[$lab] = $item;
				}

				return $its;
			}

		}





		$target_file = 'https://www.googleapis.com/youtube/v3/search?q=' . $targetfeed . '&key=' . $dzsvg->mainoptions['youtube_api_key'] . '&type=channel&part=snippet';




		$ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $dzsvg->mainoptions['force_file_get_contents']));



		if ($dzsvg->mainoptions['debug_mode'] == 'on') {
			if($fout!=null){

			}
			$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('first search for channel id', 'dzsvg') . '</div>
<div class="toggle-content">';
			$fout.='debug mode: target file ( ' . $target_file . ' )  ida is is...<br>';
			$fout.= '</div></div>';
			wp_enqueue_style('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.css');
			wp_enqueue_script('dzstoggle', $dzsvg->thepath . 'dzstoggle/dzstoggle.js');
		}


		$i = 0;

		if ($ida) {

			$obj = json_decode($ida);


			if ($dzsvg->mainoptions['debug_mode'] == 'on') {
//                echo 'debug mode: is not nicename is ON, obj is is...<br>';
//                print_r($obj);
//                echo '<br/>';
			}


			if ($obj && is_object($obj)) {


				if (isset($obj->items[0]->id->channelId)) {

//                        array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">'.__('This is dirty').'</div>');

					$channel_id = $obj->items[0]->id->channelId;


					$breaker = 0;
					$nextPageToken = 'none';

					while ($breaker < 10 || $nextPageToken !== '') {


						$str_nextPageToken = '';

						if ($nextPageToken && $nextPageToken != 'none') {
							$str_nextPageToken = '&pageToken=' . $nextPageToken;
						}


						if ($dzsvg->mainoptions['youtube_api_key'] == '') {
							$dzsvg->mainoptions['youtube_api_key'] = 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4';
						}

						$target_file = 'https://www.googleapis.com/youtube/v3/search?key=' . $dzsvg->mainoptions['youtube_api_key'] . '&channelId=' . $channel_id . '&part=snippet&order=date&type=video' . $str_nextPageToken . '&maxResults=' . $max_videos;


//                        echo $target_file;

						$ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $dzsvg->mainoptions['force_file_get_contents']));


						if ($ida) {

							$obj = json_decode($ida);

//print_r($obj);


							if ($dzsvg->mainoptions['debug_mode'] == 'on') {
								$fout.= 'fout on';
								$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('then we know the real query  ', 'dzsvg') . '</div>
<div class="toggle-content">';
								$fout.=' youtube user channel - let us see the actual channel id targetfile - ' . $target_file . ' <br>';
								$fout.=' channelId - <strong>' . $channel_id . '</strong> <br>';
								$fout.='debug mode: $obj - ' . print_rr($obj, array('echo'=>false, 'encode_html'=>true) )  . '<br>';
								$fout.= '</div></div>';
							}


							if($obj && is_object($obj) && isset($obj->error)  && isset($obj->error->message)){

								echo '<div class="error">'.$obj->error->message.'</div>';
							}

							if ($obj && is_object($obj)) {

//                                        print_r($obj);

								if (isset($obj->items[0]->id->videoId)) {


									foreach ($obj->items as $ytitem) {
//                    print_r($ytitem); echo $ytitem->id->videoId;


										if (isset($ytitem->id->videoId) == false) {
											echo 'this does not have id ? ';
											continue;
										}
										$its[$i]['source'] = $ytitem->id->videoId;
										$its[$i]['thumbnail'] = $ytitem->snippet->thumbnails->medium->url;
										$its[$i]['type'] = "youtube";
										$its[$i]['permalink'] = "https://www.youtube.com/watch?v=".$its[$i]['source'];

										$aux = $ytitem->snippet->title;
										$lb = array('"', "\r\n", "\n", "\r", "&", "", "`", '???', "'", '');
										$aux = str_replace($lb, ' ', $aux);
										$its[$i]['title'] = $aux;

										$aux = $ytitem->snippet->description;
										$lb = array("\r\n", "\n", "\r");
										$aux = str_replace($lb, '<br>', $aux);
										$lb = array('"');
										$aux = str_replace($lb, '&quot;', $aux);
										$lb = array("'");
										$aux = str_replace($lb, '&#39;', $aux);


										$auxcontent = '<p>' . str_replace(array("\r\n", "\n", "\r"), '</p><p>', $aux) . '</p>';

										$its[$i]['description'] = $auxcontent;
										$its[$i]['menuDescription'] = $auxcontent;

										if ($margs['enable_outernav_video_author'] == 'on') {
//                        echo 'ceva';
										}
										if ($margs['enable_outernav_video_date'] == 'on') {
//                        echo 'ceva';
										}
										$its[$i]['uploader'] = $ytitem->snippet->channelTitle;
										$its[$i]['upload_date'] = $ytitem->snippet->publishedAt;



										if($margs['get_full_description']=='on'){
											$arr = dzsvg_parse_youtube_video($its[$i]['source'], $margs, $fout);


											if(is_array($arr)){
												$its[$i] = array_merge($its[$i], $arr);
											}
//                                            print_r($its[$i]);
										}

										$i++;

//                                            if ($i > $max_videos + 1){ break; }

									}


								} else {

									array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('No videos to be found - ') . $target_file . '</div>');
								}
//                                print_r($obj);
							} else {

								array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('Object channel is not JSON...') . '</div>');
							}
						} else {

							array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('Cannot get info from YouTube API about channel - ') . $target_file . '</div>');
						}


						if ($max_videos === 'all') {

							if (isset($obj->nextPageToken) && $obj->nextPageToken) {
								$nextPageToken = $obj->nextPageToken;
							} else {

								$nextPageToken = '';
								break;
							}

						} else {
							$nextPageToken = '';
							break;
						}

						$breaker++;
					}



					$sw34 = false; // -- true if added to cache
					$auxa34 = array('id' => $targetfeed,
					                'items' => $its,
					                'time' => $_SERVER['REQUEST_TIME']
					                , 'maxlen' => $max_videos

					);


					$cacher = false;
					if (!is_array($cacher)) {
						$cacher = array();
					} else {


						foreach ($cacher as $lab => $cach) {
							if ($cach['id'] == $targetfeed) {
								$sw34 = true;

								$cacher[$lab] = $auxa34;

								update_option('dzsvg_cache_ytuserchannel', $cacher);

//                                        print_r($cacher);
								break;
							}
						}


					}

					if ($sw34 == false) {

						array_push($cacher, $auxa34);

//                                            print_r($cacher);

						update_option('dzsvg_cache_ytuserchannel', $cacher);
					}


				} else {

					array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('Cannot access channel ID, this is feed - ') . $target_file . '</div>');
					try {

						if (isset($obj->error)) {
							if ($obj->error->errors[0]) {


								array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . $obj->error->errors[0]->message . '</div>');
								if (strpos($obj->error->errors[0]->message, 'per-IP or per-Referer restriction') !== false) {

									array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __("Suggestion - go to Video Gallery > Settings and enter your YouTube API Key") . '</div>');
								} else {

								}
							}
						}

//                                    $arr = json_decode(DZSHelpers::($target_file));
//
//                                    print_r($arr);
					} catch (Exception $err) {

					}
				}
			} else {

				array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('Object is not JSON...') . '</div>');
			}
		}

	}
	// --- END user channel



	// --- youtube playlist
	if($subtype=='playlist') {


		$len = count($its) - 1;
		for ($i = 0; $i < $len; $i++) {
			unset($its[$i]);
		}



		$cacher = get_option('dzsvg_cache_ytplaylist');

		$cached = false;
		$found_for_cache = false;


		if ($cacher == false || is_array($cacher) == false || $dzsvg->mainoptions['disable_api_caching'] == 'on') {
			$cached = false;
		} else {

//                print_r($cacher);


			$ik = -1;
			$i = 0;
			for ($i = 0; $i < count($cacher); $i++) {
				if ($cacher[$i]['id'] == $targetfeed) {
					if(isset($cacher[$i]['maxlen']) && $cacher[$i]['maxlen'] == $max_videos){
						if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 7200) {
							$ik = $i;

//                                echo 'yabebe';
							$cached = true;
							break;
						}
					}

				}
			}


			if($cached){

				foreach ($cacher[$ik]['items'] as $lab => $item) {
					if ($lab === 'settings') {
						continue;
					}

					$its[$lab] = $item;

//                        print_r($item);
//                        echo 'from cache';
				}

			}
		}



		if ($dzsvg->mainoptions['debug_mode'] == 'on') {
			echo 'is cached - '.$cached.' | ';
		}



		// -- youtube playlist
		if(!$cached){
			if (isset($max_videos) == false || $max_videos == '') {
				$max_videos = 50;
			}
			$yf_maxi = $max_videos;

			if ($max_videos == 'all') {
				$yf_maxi = 50;
			}



			$breaker = 0;

			$i_for_its = 0;
			$nextPageToken = 'none';

			while ($breaker < 10 || $nextPageToken !== '') {


				$str_nextPageToken = '';

				if ($nextPageToken && $nextPageToken != 'none') {
					$str_nextPageToken = '&pageToken=' . $nextPageToken;
				}

//                echo '$breaker is '.$breaker;

				if($dzsvg->mainoptions['youtube_api_key']==''){
					$dzsvg->mainoptions['youtube_api_key'] = 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4';
				}


				$target_file='https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=' . $targetfeed . '&key=' . $dzsvg->mainoptions['youtube_api_key'] . '' . $str_nextPageToken . '&maxResults='.$yf_maxi;

//                    echo $target_file;


				if ($dzsvg->mainoptions['debug_mode'] == 'on') {
					echo 'target file - '.$target_file;
				}
//                    echo 'target file - '.$target_file;


				$ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $dzsvg->mainoptions['force_file_get_contents']));

//            echo 'ceva'.$ida;

				if ($ida) {

					$obj = json_decode($ida);


					if ($obj && is_object($obj)) {
//                            print_r($obj);


						if ($obj && is_object($obj)) {

//                                        print_r($obj);

							if (isset($obj->items[0]->snippet->resourceId->videoId)) {


								foreach ($obj->items as $ytitem) {
//                                echo 'yt item --- ';print_r($ytitem);


									if (isset($ytitem->snippet->resourceId->videoId) == false) {
										echo 'this does not have id ? ';
										continue;
									}



									$its[$i_for_its]['source'] = $ytitem->snippet->resourceId->videoId;

									if(isset($ytitem->snippet->thumbnails)){

										$its[$i_for_its]['thumbnail'] = $ytitem->snippet->thumbnails->medium->url;
									}
									$its[$i_for_its]['type'] = "youtube";
									$its[$i_for_its]['permalink'] = "https://www.youtube.com/watch?v=".$its[$i_for_its]['source'];

									$aux = $ytitem->snippet->title;
									$lb = array('"', "\r\n", "\n", "\r", "&", "", "`", '???', "'", '');
									$aux = str_replace($lb, ' ', $aux);
									$its[$i_for_its]['title'] = $aux;

									$aux = $ytitem->snippet->description;
									$lb = array("\r\n","\n","\r");
									$aux = str_replace($lb,'<br>',$aux);
									$lb = array('"');
									$aux = str_replace($lb,'&quot;',$aux);
									$lb = array("'");
									$aux = str_replace($lb,'&#39;',$aux);


									$auxcontent = '<p>' . str_replace(array("\r\n", "\n", "\r"), '</p><p>', $aux) . '</p>';

									$its[$i_for_its]['description'] = $auxcontent;
									$its[$i_for_its]['menuDescription'] = $auxcontent;

									if ($margs['enable_outernav_video_author'] == 'on') {
//                        echo 'ceva';
									}
									if ($margs['enable_outernav_video_date'] == 'on') {
//                        echo 'ceva';
									}
									$its[$i]['upload_date'] = $ytitem->snippet->publishedAt;
									$its[$i_for_its]['uploader'] = $ytitem->snippet->channelTitle;

									$i_for_its++;


								}

								$found_for_cache=true;


							} else {

								array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('No youtube playlist videos to be found - maybe API key not set ? This is the feed - '.$target_file) . '</div>');

								try{

									if(isset($obj->error)){
										if($obj->error->errors[0]){


											array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' .$obj->error->errors[0]->message . '</div>');
											if(strpos($obj->error->errors[0]->message, 'per-IP or per-Referer restriction')!==false){

												array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __("Suggestion - go to Video Gallery > Settings and enter your YouTube API Key") . '</div>');
											}else{

											}
										}
									}

//                                    $arr = json_decode(DZSHelpers::($target_file));
//
//                                    print_r($arr);
								}catch(Exception $err){

								}
							}
						}
					}






					if ($max_videos === 'all') {

						if (isset($obj->nextPageToken) && $obj->nextPageToken) {
							$nextPageToken = $obj->nextPageToken;
						} else {

							$nextPageToken = '';
							break;
						}

					} else {
						$nextPageToken = '';
						break;
					}


				}
				$breaker++;
			}





			if($found_for_cache){

				$sw34 = false;
				$auxa34 = array(
					'id' => $targetfeed
					, 'items' => $its
					, 'time' => $_SERVER['REQUEST_TIME']
					, 'maxlen' => $max_videos

				);

				if (!is_array($cacher)) {
					$cacher = array();
				} else {


					foreach ($cacher as $lab => $cach) {
						if ($cach['id'] == $targetfeed) {
							$sw34 = true;

							$cacher[$lab] = $auxa34;

							update_option('dzsvg_cache_ytplaylist', $cacher);

//                                        print_r($cacher);
							break;
						}
					}


				}

				if ($sw34 == false) {

					array_push($cacher, $auxa34);

//                                            print_r($cacher);

					update_option('dzsvg_cache_ytplaylist', $cacher);
				}
			}
		}


	}
	// --- END youtube playlist



	// --- youtube search query
	if($subtype=='search') {




		$len = count($its) - 1;
		for ($i = 0; $i < $len; $i++) {
			unset($its[$i]);
		}





		$cacher = get_option('dzsvg_cache_ytkeywords');

		$cached = false;
		$found_for_cache = false;


		if ($cacher == false || is_array($cacher) == false || $dzsvg->mainoptions['disable_api_caching'] == 'on') {
			$cached = false;
		} else {

//                print_r($cacher);


			$ik = -1;
			$i = 0;
			for ($i = 0; $i < count($cacher); $i++) {
				if ($cacher[$i]['id'] == $targetfeed) {
					if ($_SERVER['REQUEST_TIME'] - $cacher[$i]['time'] < 3600) {
						$ik = $i;

//                                echo 'yabebe';
						$cached = true;
						break;
					}
				}
			}


			if($cached){

				foreach ($cacher[$ik]['items'] as $lab => $item) {
					if ($lab === 'settings') {
						continue;
					}

					$its[$lab] = $item;

//                        print_r($item);
//                        echo 'from cache';
				}

			}
		}







		//-- youtube search
		if(!$cached){
			if (isset($max_videos) == false || $max_videos == '') {
				$max_videos = 50;
			}
			$yf_maxi = $max_videos;

			if ($max_videos == 'all') {
				$yf_maxi = 50;
			}



			$breaker = 0;

			$i_for_its = 0;
			$nextPageToken = 'none';

			while ($breaker < 5 || $nextPageToken !== '') {


				$str_nextPageToken = '';

				if ($nextPageToken && $nextPageToken != 'none') {
					$str_nextPageToken = '&pageToken=' . $nextPageToken;
				}

//                echo '$breaker is '.$breaker;


				$targetfeed = str_replace(' ','+',$targetfeed);


				if($dzsvg->mainoptions['youtube_api_key']==''){
					$dzsvg->mainoptions['youtube_api_key'] = 'AIzaSyCtrnD7ll8wyyro5f1LitPggaSKvYFIvU4';
				}

				$target_file='https://www.googleapis.com/youtube/v3/search?part=snippet&q=' . $targetfeed . '&type=video&key=' . $dzsvg->mainoptions['youtube_api_key'] . $str_nextPageToken.'&videoEmbeddable=true&maxResults='.$yf_maxi;


				$ida = DZSHelpers::get_contents($target_file, array('force_file_get_contents' => $dzsvg->mainoptions['force_file_get_contents']));

//            echo 'ceva'.$ida;

				if ($ida) {

					$obj = json_decode($ida);


					if ($obj && is_object($obj)) {
//                                print_r($obj);



						if (isset($obj->items[0]->id->videoId)) {


							foreach ($obj->items as $ytitem) {
//                                print_r($ytitem);


								if (isset($ytitem->id->videoId) == false) {
									echo 'this does not have id ? ';
									continue;
								}
								$its[$i_for_its]['source'] = $ytitem->id->videoId;
								$its[$i_for_its]['thethumb'] = $ytitem->snippet->thumbnails->medium->url;
								$its[$i_for_its]['type'] = "youtube";
								$its[$i_for_its]['permalink'] = "https://www.youtube.com/watch?v=".$its[$i_for_its]['source'];

								$aux = $ytitem->snippet->title;
								$lb = array('"', "\r\n", "\n", "\r", "&", "", "`", '???', "'", '');
								$aux = str_replace($lb, ' ', $aux);
								$its[$i_for_its]['title'] = $aux;

								$aux = $ytitem->snippet->description;
								$lb = array("\r\n","\n","\r");
								$aux = str_replace($lb,'<br>',$aux);
								$lb = array('"');
								$aux = str_replace($lb,'&quot;',$aux);
								$lb = array("'");
								$aux = str_replace($lb,'&#39;',$aux);


								$auxcontent = '<p>' . str_replace(array("\r\n", "\n", "\r"), '</p><p>', $aux) . '</p>';

								$its[$i_for_its]['description'] = $auxcontent;
								$its[$i_for_its]['menuDescription'] = $auxcontent;

								if ($margs['enable_outernav_video_author'] == 'on') {
//                        echo 'ceva';
								}
								$its[$i_for_its]['uploader'] = $ytitem->snippet->channelTitle;
								$its[$i]['upload_date'] = $ytitem->snippet->publishedAt;
								if ($margs['enable_outernav_video_date'] == 'on') {
//                        echo 'ceva';
								}

								$i_for_its++;

								$found_for_cache = true;

							}


						} else {

							array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('No youtube keyboard videos to be found') . '</div>');
						}

					}






					if ($max_videos === 'all') {

						if (isset($obj->nextPageToken) && $obj->nextPageToken) {
							$nextPageToken = $obj->nextPageToken;
						} else {

							$nextPageToken = '';
							break;
						}

					} else {
						$nextPageToken = '';
						break;
					}


				}else{

					array_push($dzsvg->arr_api_errors, '<div class="dzsvg-error">' . __('No youtube keyboards ida found '.$target_file) . '</div>');
				}
				$breaker++;
			}







			if($found_for_cache){

				$sw34 = false;
				$auxa34 = array(
					'id' => $targetfeed
					, 'items' => $its
					, 'time' => $_SERVER['REQUEST_TIME']
					, 'maxlen' => $max_videos

				);

				if (!is_array($cacher)) {
					$cacher = array();
				} else {


					foreach ($cacher as $lab => $cach) {
						if ($cach['id'] == $targetfeed) {
							$sw34 = true;

							$cacher[$lab] = $auxa34;

							update_option('dzsvg_cache_ytkeywords', $cacher);

//                                        print_r($cacher);
							break;
						}
					}


				}


				if ($sw34 == false) {

					array_push($cacher, $auxa34);

//                                            print_r($cacher);

					update_option('dzsvg_cache_ytkeywords', $cacher);
				}
			}



		}
		// -- end not cached




		if ($dzsvg->mainoptions['debug_mode'] == 'on') {
//		                $fout.= 'fout on';
			$fout.= '<div class="dzstoggle toggle1" rel="">
<div class="toggle-title" style="">' . __('search results query', 'dzsvg') . '</div>
<div class="toggle-content">';
			$fout.=' youtube user channel - let us see the actual channel id targetfile - ' . $target_file . ' <br>';
			$fout.=' cached - (' . $cached . ')<br>  - ';
			$fout.=' $found_for_cache - (' . $found_for_cache . ')<br>  - ';
			$fout.='debug mode: $its - ' . print_rr($its, array('echo'=>false, 'encode_html'=>true) )  . '<br>';
			$fout.= '</div></div>';



			wp_enqueue_style('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.css');
			wp_enqueue_script('dzstoggle', $dzsvg->base_url . 'dzstoggle/dzstoggle.js');



		}



	}
	// --- END youtube search query





	return $its;
}
