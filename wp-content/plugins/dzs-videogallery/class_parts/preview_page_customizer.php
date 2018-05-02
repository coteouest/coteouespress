<?php
wp_enqueue_script('preseter', $this->thepath . 'assets/preseter/preseter.js');
wp_enqueue_style('preseter', $this->thepath . 'assets/preseter/preseter.css');
echo '<div class="preseter"><div class="the-icon"></div>
<div class="the-content"><h3>Quick Config</h3>
<form method="GET">
<div class="setting">
<div class="alabel">Menu Position:</div>
<div class="select-wrapper"><span>right</span><select name="opt3" class="textinput short"><option>right</option><option>down</option><option>up</option><option>left</option><option>none</option></select></div>
</div>
<div class="setting">
<div class="alabel">Autoplay:</div>
<div class="select-wrapper"><span>on</span><select name="opt4" class="textinput short"><option value="on">' . __('on', 'dzsvg') . '</option><option value="off">' . __('off', 'dzsvg') . '</option></select></div>
</div>
<div class="setting type_all">
    <div class="setting-label">' . __('Feed From', 'dzsvg') . '</div>
    <div class="select-wrapper"><span>normal</span><select class="textinput styleme" name="feedfrom">
        <option>ytuserchannel</option>
        <option>ytkeywords</option>
        <option>ytplaylist</option>
        <option>vmuserchannel</option>
        <option>vmchannel</option>
    </select></div>
</div>
<div class="setting">
    <div class="alabel">Target Feed User</div>
    <div class="sidenote">Or playlist ID if you have selected playlist in the dropdown</div>
    <input type="text" name="opt6" value="digitalzoomstudio"/>
</div>
<div class="setting">
    <input type="submit" class="button-primary" name="submiter" value="Submit"/>
</div>
</form>
</div><!--end the-content-->
</div>';
if (isset($_GET['opt3'])) {
	$its['settings']['nav_type'] = 'none';
	$its['settings']['menuposition'] = $_GET['opt3'];
	$its['settings']['autoplay'] = $_GET['opt4'];
	$its['settings']['feedfrom'] = $_GET['feedfrom'];
	$its['settings']['youtubefeed_user'] = $_GET['opt6'];
	$its['settings']['ytkeywords_source'] = $_GET['opt6'];
	$its['settings']['ytplaylist_source'] = $_GET['opt6'];
	$its['settings']['vimeofeed_user'] = $_GET['opt6'];
	$its['settings']['vimeofeed_channel'] = $_GET['opt6'];
}