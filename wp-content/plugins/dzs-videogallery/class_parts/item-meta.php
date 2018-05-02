<?php
global $post, $wp_version;

// -- no id insert
$struct_uploader = '<div class="dzs-wordpress-uploader ">
<a href="#" class="button-secondary">' . __('Upload', 'dzsvp') . '</a>
</div>';
?>
<div class="select-hidden-con">
    <?php
    $lab_nonce = 'dzsap_meta_nonce';
    echo '<input type="hidden" name="'.$lab_nonce.'" value="'.wp_create_nonce($lab_nonce).'"/>';
    ?>



</div>



<?php

foreach ($this->options_item_meta as $lab => $oim2){



	$oim = array_merge(array(
		'category'=>'',
		'extraattr'=>'',
		'default'=>'',
	), $oim2);


    ?>
    <div class="setting <?php
    $option_name = $oim['name'];

    if($option_name=='the_post_title' || $option_name=='the_post_content'){
        continue;
    }

    if($oim['type']=='attach'){
        ?>setting-upload<?php
    }

    ?>">
        <h5 class="setting-label"><?php echo $oim['title']; ?></h5>


        <?php

        if($oim['type']=='attach'){
            ?><span class="uploader-preview"></span><?php
        }

        ?>

        <?php

        $val = $oim['default'];
//        print_rr($oim);
//        echo 'is_int($post->ID) - ('.is_int($post->ID).')';

//        print_rr($post);
        if(isset($post->ID) && is_int(intval($post->ID))){

//            print_rr($oim);

	        if(isset($oim['default']) && $oim['default']){
//		        error_log("VAL DEFAULT".$val);

//			        error_log(print_rr(get_post_meta($po->ID, $option_name),true));
		        $aux = get_post_meta($post->ID, $option_name);
		        if(get_post_meta($post->ID, $option_name)){

			        if(isset($aux[0])){
				        $val = $aux[0];
			        }
		        }
	        }else{

//	            error_log("VAL NOT DEFAULT".$val);
		        $val = get_post_meta($post->ID, $option_name, true);
	        }
        }


        $class = 'setting-field medium';

        if($oim['type']=='attach'){
            $class.=' uploader-target';
        }


        if($oim['type']=='attach') {
            echo DZSHelpers::generate_input_text($option_name, array(
                'class' => $class,
                'seekval' => $val,
            ));
        }
        if($oim['type']=='text') {
            echo DZSHelpers::generate_input_text($option_name, array(
                'class' => $class,
                'seekval' => $val,
            ));
        }
        if($oim['type']=='textarea') {
            echo DZSHelpers::generate_input_textarea($option_name, array(
                'class' => $class,
                'seekval' => $val,
                'extraattr' => $oim['extraattr'],
            ));
        }
        if($oim['type']=='select') {


            $class = 'dzs-style-me skin-beige';

            if(isset($oim['select_type']) && $oim['select_type']){
                $class.=' '.$oim['select_type'];
            }

            echo DZSHelpers::generate_select($option_name, array(
                'class' => $class,
                'seekval' => $val,
                'options' => $oim['choices'],
            ));

            if(isset($oim['select_type']) && $oim['select_type']=='opener-listbuttons'){

                echo '<ul class="dzs-style-me-feeder">';

                foreach ($oim['choices_html'] as $oim_html){

                    echo '<li>';
                    echo $oim_html;
                    echo '</li>';
                }

                echo '</ul>';
            }


        }

        if($oim['type']=='attach') {
            echo $struct_uploader;
        }

        if(isset($oim['extra_html_after_input']) && $oim['extra_html_after_input']){
            echo $oim['extra_html_after_input'];
        }
        if(isset($oim['sidenote']) && $oim['sidenote']){
            echo '<div class="sidenote">'.$oim['sidenote'].'</div>';
        }

        ?>

    </div>

<?php



}
?>


