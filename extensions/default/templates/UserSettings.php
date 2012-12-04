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
        $buddyStreamExtension = new BuddyStreamExtensions();
        foreach($buddyStreamExtension->getExtensionsConfigs() as $extension){
            if(get_site_option('buddystream_'.$extension['name'].'_power') == "on" && get_site_option('buddystream_'.$extension['name'].'_setup') && ! $extension['parent'] ){
                echo '<li><a href="?network=' . $extension['name'] . '">'.ucfirst($extension['displayname']).'</a></li>';
                $activeExtensions[] = $extension['name'];
            }
        }
        ?>
    </ul>
</div>
<br/><br/>
<div>

<?php
if(isset($_GET['network'])){
    include(BP_BUDDYSTREAM_DIR."/extensions/".$_GET['network']."/templates/UserSettings.php");
}
?>
</div>