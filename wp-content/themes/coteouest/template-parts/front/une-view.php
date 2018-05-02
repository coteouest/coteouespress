<div class="tab-pane fade in active" id="tab1">

    <div class="row">
        <?php
            do_action('une_querie_frontpage', $region = 'Afrique');
        ?>
    </div>  
    
</div>

<div class="tab-pane fade in" id="tab2">

    <div class="row">
        <?php 
            do_action('une_querie_frontpage', $region = 'Reste du monde'); 
        ?>
    </div>
    
</div>