<?php

$incPath = str_replace("/wp-content/plugins/buddystream/extentions/default/templates","",getcwd());
ini_set('include_path', $incPath);
include('wp-load.php');

?>

<div class="buddystream_sharebox">
<h3><?php _e('Sharebox', 'buddystream_lang');?></h3>
<br/>

<blockquote>
    <h4><i>"<?php echo $_GET['content']; ?>"</i></h4>
</blockquote>
<br/>

<?php

foreach(BuddyStreamExtentions::getExtentionsConfigs() as $extention){
    if(get_site_option("buddystream_".$extention['name']."_share") == "on"){
        call_user_func_array('buddystream_'.$extention['name'].'_sharebutton',array('content' => $_GET['content'], 'link' => $_GET['link']));
    }
}

?>

</div>