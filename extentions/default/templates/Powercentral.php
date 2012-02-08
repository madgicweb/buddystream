<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/admin.css';?>" type="text/css" />
<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/slickswitch.css';?>" type="text/css" />
<script src="<?php echo plugins_url();?>/buddystream/extentions/default/jquery.slickswitch.js" type="text/javascript"></script>

<?php include "AdminMenu.php"; ?>

<form id="settings_form" action="" method="post">

<?php
    global $bp;
    if($_POST){ 
        
        //reset the importer queue
        update_site_option("buddystream_importers_queue", "");
        
        //set the new importer queue
        foreach (BuddyStreamExtentions::getExtentionsConfigs() as $extention) {
            if(is_array($extention) && isset($_POST['buddystream_'.$extention['name'].'_power']) && $_POST['buddystream_'.$extention['name'].'_power'] == "on"){
                $importerQueue[] = $extention['name'];
            }
        }
        
        update_site_option("buddystream_importers_queue", implode(",", $importerQueue));
        
        echo '<div class="buddystream_info_box_green">' . __('Settings saved.', 'buddystream') . '</div>'; 
    }
?>
    
    <div class="buddystream_info_box">
        <?php _e('powercentral description','buddystream_lang'); ?>       
    </div>
    
    
        <div class="metabox-holder">    
            <?php
            //loop throught extentions directory and get all extentions
            foreach (BuddyStreamExtentions::getExtentionsConfigs() as $extention) {

                if(is_array($extention)){
                    
                    //define vars
                    define('buddystream_'.$extention['name'].'_power', "");
                    
                    if($_POST){
                        delete_site_option('buddystream_'.$extention['name'].'_power');
                        update_site_option('buddystream_'.$extention['name'].'_power', trim($_POST['buddystream_'.$extention['name'].'_power']));
                    }

                    echo '
                       <div class="postbox" style="float:left; width:200px; margin-right:20px;">
                            <div><h3 style="cursor:default; font-family:arial; font-size:13px; font-weight:bold;"><span class="admin_icon '.$extention['name'].'"></span> '.__(ucfirst($extention['displayname']), 'buddystream').'</h3>
                                <div class="inside" style="padding:10px;">
                                    <input id="buddystream_'.$extention['name'].'" class="switch icons" type="checkbox" name="buddystream_'.$extention['name'].'_power" />
                                </div>
                            </div>
                        </div>
                    ';
                }
            }
            ?>
            </div>
        

 <?php
//flip switches
 $runscript = "";
foreach (BuddyStreamExtentions::getExtentionsConfigs() as $extention) {
     if(get_site_option('buddystream_'.$extention['name'].'_power')){
         $runscript .= 'jQuery("#buddystream_'.$extention['name'].'").slickswitch("toggleOn");';
     }else{
         $runscript .= 'jQuery("#buddystream_'.$extention['name'].'").slickswitch("toggleOff");';
     }
}
?>

<div style="float:left; clear:both;">
    <input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
    <input type="button" onclick="buddystreamTurnAllOn()" class="button-primary" value="<?php _e('Turn all on','buddystream_lang') ?>" />
    <input type="button" onclick="buddystreamTurnAllOff()" class="button-primary" value="<?php _e('Turn all off','buddystream_lang') ?>" />
</div>
<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery(".switch").slickswitch();
        <?php echo $runscript;?>
    });
    
    function buddystreamTurnAllOn(){
    <?php
        foreach (BuddyStreamExtentions::getExtentionsConfigs() as $extention) {
            echo 'jQuery("#buddystream_'.$extention['name'].'").slickswitch("toggleOn");';
        }
     ?>
    }
    
    function buddystreamTurnAllOff(){
    <?php
        foreach (BuddyStreamExtentions::getExtentionsConfigs() as $extention) {
            echo 'jQuery("#buddystream_'.$extention['name'].'").slickswitch("toggleOff");';
        }
     ?>
    }
    
</script>