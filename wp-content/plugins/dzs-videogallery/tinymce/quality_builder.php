<?php
function dzsvg_quality_builder(){
?>

<div class="wrap">

    <?php

    $start = '';

    if(isset($_GET['qualitystart']) && $_GET['qualitystart']){
        $start = $_GET['qualitystart'];
    }

    $start = str_replace('{{quot}}','"',$start);
    $start = str_replace('{{patend}}',']',$start);
?>


    <script>
        window.quality_builder_start_array = '<?php echo ($start); ?>';
    </script>




    <P class="sidenote"><?php echo __("Click the bar to submit ads at custom time "); ?></P>
    <form  class="dzsvg-quality-builder" method="post">
        <div class="video-containers">
        <div class="video-container is-sampler">


            <h3>Quality</h3>
            <div class="setting">
                <h5><?php echo __("Source"); ?></h5>
                <div class="flex-con-for-upload">
                    <input class="upload-type-video upload-prop-id upload-target-prev remove-disable" disabled name="source[]"> <button class="dzsvg-wordpress-uploader button-secondary"><?php echo __("Upload"); ?></button>
                </div>

            </div>

            <div class="setting">
                <h5><?php echo __("Label"); ?></h5>

                <input class="upload-type-video upload-prop-id remove-disable" disabled name="label[]">
            </div>

        </div>



        </div>
    <button class="button-secondary add-quality"><?php echo __("Add Quality"); ?></button>

        <br>
        <br>
        <button class="button-primary"><?php echo __("Submit Ads"); ?></button>

    </form>
        <div class="output"></div>
</div><?php

}