<h3>
<?php if(!isset($_GET['network'])){
    echo "<h3>".__('Social networks', 'buddystream_lang')."</h3>";
    echo __('Social networks setup description', 'buddystream_lang')."<br/><br/>";
}
?>
</h3>

<div class="buddystream_album_navigation_links">
    <ul>
        <?php

        //get the active
        foreach(BuddyStreamExtentions::getExtentionsConfigs() as $extention){
            if(get_site_option('buddystream_'.$extention['name'].'_power') == "on" && get_site_option('buddystream_'.$extention['name'].'_setup')){
                echo '<li><a href="?network=' . $extention['name'] . '">'.ucfirst($extention['displayname']).'</a></li>';         
                $activeExtentions[] = $extention['name'];
            }
        }
        ?>
    </ul>
</div>
<br/><br/>
<div>

<?php
if(isset($_GET['network'])){
    include(WP_PLUGIN_DIR."/buddystream/extentions/".$_GET['network']."/templates/UserSettings.php");
}
?>
</div>