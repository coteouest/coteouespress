<?php



$this->options_item_meta = array(


	array(
		'name'=>'the_post_title',
		'type'=>'text',
		'title'=>__("Title"),
		'only_for'=>array('sliders_admin'),
		'sidenote'=>__("the title of the song"),
	),




    array(
        'name'=>'dzsvg_meta_item_type',
        'type'=>'select',
        'select_type'=>'opener-listbuttons',
        'title'=>__("Type"),
        'sidenote'=>__("select the type of media"),
        'setting_extra_classes'=>' setting-for-item-type rounded-highlight',
        'choices'=>array(
            array(
                'label'=>__("Self Hosted"),
                'value'=>'video',
            ),
            array(
                'label'=>__("youtube"),
                'value'=>'youtube',
            ),
            array(
                'label'=>__("vimeo"),
                'value'=>'vimeo',
            ),
            array(
                'label'=>__("inline"),
                'value'=>'inline',
            ),
        ),
        'choices_html'=>array(
            '<span class="option-con"><img src="'.$this->base_url.'admin/img/type_video.png"/><span class="option-label">'.__("Self Hosted").'</span></span>',
            '<span class="option-con"><img src="'.$this->base_url.'admin/img/type_youtube.png"/><span class="option-label">'.__("YouTube").'</span></span>',
            '<span class="option-con"><img src="'.$this->base_url.'admin/img/type_vimeo.png"/><span class="option-label">'.__("Vimeo").'</span></span>',
            '<span class="option-con"><img src="'.$this->base_url.'admin/img/type_inline.png"/><span class="option-label">'.__("Inline").'</span></span>',
        ),


    ),



    array(
        'name'=>'dzsvg_meta_featured_media',
        'type'=>'attach',
        'title'=>__("Video"),
        'dom_type' => 'textarea',
        'sidenote'=>__("input a self hosted video or youtube link or vimeo link"),
        'setting_extra_classes'=>' setting-for-source',
    ),


    array(
        'name'=>'dzsvg_meta_thumb',
        'type'=>'attach',
        'title'=>__("Thumbnail"),
        'sidenote'=>__("This will replace the default wordpress thumbnail"),
        'extra_html_after_input'=>'<button style="display: inline-block; vertical-align: top;" class="refresh-main-thumb button-secondary">Auto Generate</button>',
    ),


	array(
		'name'=>'the_post_content',
		'type'=>'textarea',
		'title'=>__("Description"),
		'extraattr'=>' rows="2"',
		'sidenote'=>__("the video description"),
	),

	array(
		'name'=>'dzsvg_meta_menu_description',
		'type'=>'textarea',
		'title'=>__("Menu Description"),
		'extraattr'=>' rows="2"',
		'sidenote'=>__("the menu description"),
		'default'=>'as_description',
	),

    array(
        'name'=>'dzsvg_meta_extra_classes',
        'type'=>'text',
        'category'=>'extra_html',
        'title'=>__("Extra Classes"),
        'sidenote'=>__("extra html classes applied to the player"),
    ),

	array(
		'name'=>'dzsvg_meta_play_from',
		'type'=>'text',
		'category'=>'misc',
		'title'=>__("Play from"),
		'sidenote'=>__("choose a number of seconds from which the track to play from ( for example if set \"70\" then the track will start to play from 1 minute and 10 seconds ) or input \"last\" for the track to play at the last position where it was.",'dzsap'),
	),

    array(
        'name'=>'dzsvg_meta_adarray',
        'type'=>'text',
        'category'=>'misc',
        'title'=>__("Manage Ads"),
        'sidenote'=>sprintf(__("construct an ad sequence ")),

        'extra_html_after_input'=>'<a class=" button-secondary quick-edit-adarray" href="#" style="cursor:pointer;">'.__("Edit Ads").'</a>',
    ),


	array(
		'name'=>'dzsvg_meta_loop',
		'type'=>'select',
		'category'=>'misc',
		'select_type'=>'',
		'title'=>__("Loop"),
		'sidenote'=>__("loop the video when it ends"),
		'setting_extra_classes'=>'',
		'choices'=>array(
			array(
				'label'=>__("Disable"),
				'value'=>'off',
			),
			array(
				'label'=>__("Enable"),
				'value'=>'on',
			),
		),


	),


	array(
		'name'=>'dzsvg_meta_is_360',
		'type'=>'select',
		'category'=>'misc',
		'select_type'=>'',
		'title'=>__("is 360 ? "),
		'sidenote'=>__("is 360 video ? "),
		'setting_extra_classes'=>'',
		'choices'=>array(
			array(
				'label'=>__("No"),
				'value'=>'off',
			),
			array(
				'label'=>__("Yes"),
				'value'=>'on',
			),
		),


	),



    array(
        'name'=>'dzsvg_meta_subtitle',
        'type'=>'attach',
        'category'=>'misc',
        'title'=>__("Subtitle"),
        'sidenote'=>__("a optional subtitle file"),
        'extra_html_after_input'=>'',
    ),




	array(
		'name'=>'dzsvg_meta_responsive_ratio',
		'type'=>'text',
		'category'=>'misc',
		'title'=>__("Responsive ratio"),
		'sidenote'=>__("set a responsive ratio height/ratio 0.5 means that the player height will resize to 0.5 of the gallery width / or just set it to \"detect\" and it will autocalculate the ratios if it is a self hosted mp4",'dzsvg'),
	),





);