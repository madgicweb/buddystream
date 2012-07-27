<h3>
<?php if(isset($_GET['album'])){
    echo ucfirst($_GET['album']) . " " . __('albums', 'buddystream_lang');
}else{
    _e('Social Albums', 'buddystream_lang');
}
?>
</h3>

<div class="buddystream_album_navigation_links">
    <ul>
        <?php

        //get the active
        $activeExtensions = array();
        foreach(BuddyStreamExtensions::getExtensionsConfigs() as $extension){
            if(get_site_option('buddystream_'.$extension['name'].'_power') == "on" && get_site_option('buddystream_'.$extension['name'].'_album') == "on"){
                echo '<li><a href="?album=' . $extension['name'] . '">'.ucfirst($extension['displayname']).'</a></li>';
                $activeExtensions[] = $extension['name'];
            }
        }
        ?>
    </ul>
</div>

<?php
//what album to show
$album = "";
if(!isset($_GET['album'])){
    $album = implode(",", $activeExtensions);
}else{
    $album = $_GET['album'];
}

$searchTerms  = '';
if($album == 'facebook'){
    $searchTerms = 'photo';
}

?>


<?php if( bp_has_activities('object='.$album.'&per_page=35&search_terms='.$searchTerms)): ?>
   
<?php if(bp_get_activity_pagination_links()): ?>
    <div class="buddystream_album_navigation">
        <?php echo bp_get_activity_pagination_links(); ?>
    </div>
<?php endif; ?>

<div class="buddystream_album">
    <?php    
        while ( bp_activities() ) { 
            bp_the_activity(); 
            
            if($_GET['album'] == "soundcloud"){
                $content = bp_get_activity_content_body();
                echo $content."<br>";
            }else{
                $content = strip_tags(bp_get_activity_content_body(),"<a><img>");
                echo $content;
            }
            
            
        }
    ?>
</div>

<?php if(bp_get_activity_pagination_links()): ?>
    <div class="buddystream_album_navigation">
        <?php echo bp_get_activity_pagination_links(); ?>
    </div>
<?php endif; ?>

<?php else: ?>
    <?php  _e('Nothing found (yet)','buddystream_lang'); ?>
<?php endif; ?>