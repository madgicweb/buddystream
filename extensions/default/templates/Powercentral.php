<link rel="stylesheet" href="<?php echo BP_BUDDYSTREAM_URL.'/extensions/default/admin.css';?>" type="text/css" />
<link rel="stylesheet" href="<?php echo BP_BUDDYSTREAM_URL.'/extensions/default/slickswitch.css';?>" type="text/css" />
<script src="<?php echo BP_BUDDYSTREAM_URL.'/extensions/default/jquery.slickswitch.js';?>" type="text/javascript"></script>

<?php include "AdminMenu.php"; ?>

<form id="settings_form" action="" method="post">

<?php
    global $bp;
    $buddyStreamExtensions = new BuddyStreamExtensions();

    if($_POST['submit']){ 
        
        //reset the importer queue
        update_site_option("buddystream_importers_queue", "");
        
        //set the new importer queue
        foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {
            if(is_array($extension) && isset($_POST['buddystream_'.$extension['name'].'_power']) && $_POST['buddystream_'.$extension['name'].'_power'] == "on"){
                $importerQueue[] = $extension['name'];
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
            //loop throught extensions directory and get all extensions
            foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {

                if(is_array($extension) && ! $extension['parent']){

                        //define vars
                        define('buddystream_'.$extension['name'].'_power', "");

                        if($_POST){
                            delete_site_option('buddystream_'.$extension['name'].'_power');
                            update_site_option('buddystream_'.$extension['name'].'_power', trim($_POST['buddystream_'.$extension['name'].'_power']));
                        }

                        echo '
                           <div class="postbox" style="float:left; width:200px; margin-right:20px;">
                                <div><h3 style="cursor:default; font-family:arial; font-size:13px; font-weight:bold;"><span class="admin_icon '.$extension['name'].'"></span> '.__(ucfirst($extension['displayname']), 'buddystream').'</h3>
                                    <div class="inside" style="padding:10px;">
                                        <input id="buddystream_'.$extension['name'].'" class="switch icons" type="checkbox" name="buddystream_'.$extension['name'].'_power" /> Core';

                                        //get parent subextensions
                                        $subExtensions = $buddyStreamExtensions->getExtensionsWithParent($extension['name']);
                                        foreach($subExtensions as $subExtension){

                                            if($_POST){
                                                delete_site_option('buddystream_'.$subExtension['name'].'_power');
                                                update_site_option('buddystream_'.$subExtension['name'].'_power', trim($_POST['buddystream_'.$subExtension['name'].'_power']));
                                            }

                                            define('buddystream_'.$subExtension['name'].'_power', "");
                                            echo '<br/><br/><input id="buddystream_'.$subExtension['name'].'" class="switch icons" type="checkbox" name="buddystream_'.$subExtension['name'].'_power" /> '.str_replace($extension['name'],'',$subExtension['displayname']);
                                        }

                                        echo '
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
foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {
     if(get_site_option('buddystream_'.$extension['name'].'_power')){
         $runscript .= 'jQuery("#buddystream_'.$extension['name'].'").slickswitch("toggleOn");';
     }else{
         $runscript .= 'jQuery("#buddystream_'.$extension['name'].'").slickswitch("toggleOff");';
     }
}
?>

<div style="float:left; clear:both;">
    <input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
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
        foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {
            echo 'jQuery("#buddystream_'.$extension['name'].'").slickswitch("toggleOn");';
        }
     ?>
    }
    
    function buddystreamTurnAllOff(){
    <?php
        foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {
            echo 'jQuery("#buddystream_'.$extension['name'].'").slickswitch("toggleOff");';
        }
     ?>
    }
    
</script>