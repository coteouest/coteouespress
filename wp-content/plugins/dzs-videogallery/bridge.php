<?php

require_once('get_wp.php');
//print_r($dzsvg);
?>
<!doctype html>
<html>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $dzsvg->thepath; ?>videogallery/vplayer.css"/>
<!--<link rel="stylesheet" type="text/css" href="--><?php //echo $dzsvg->thepath; ?><!--front-dzsvp.css"/>-->

    <?php
    if ($dzsvg->mainoptions['extra_css']) {
        echo '<style>';
        echo $dzsvg->mainoptions['extra_css'];
        echo '</style>';
    }
    ?>

</head>
<body>
<?php

if(isset($_GET['id'])){
    
$args = array(
    'id' => $_GET['id'],
);

        
        if(isset($_GET['db'])){
            $args['db'] = $_GET['db'];
        };
echo $dzsvg->show_shortcode($args);

}


if(isset($_GET['dzsvideo'])){
    $po = get_post($_GET['dzsvideo']);
    echo $dzsvg->parse_videoitem($po,array(
        'disable_meta' => 'on'
    ));
}
if(isset($_GET['video_args'])){


    $video_args_str = $_GET['video_args'];

//    echo $video_args_str;

    $video_args = json_decode(stripslashes($video_args_str),true);

//    print_rr($video_args);

    echo $dzsvg->shortcode_player($video_args);


//    echo $dzsvg->parse_videoitem($po,array(
//        'disable_meta' => 'on'
//    ));
}



if ($dzsvg->mainoptions['disable_fontawesome'] != 'on') {

    ?><link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css"/><?php
}


?>
<script type="text/javascript" src="<?php echo $dzsvg->thepath; ?>videogallery/vplayer.js"></script>
<script type="text/javascript" src="<?php echo $dzsvg->thepath; ?>libs/dzsscroller/scroller.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $dzsvg->thepath; ?>libs/dzsscroller/scroller.css"/>
</body>
</html>