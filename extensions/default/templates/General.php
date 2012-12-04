<link rel="stylesheet" href="<?php echo BP_BUDDYSTREAM_URL.'/extensions/default/admin.css';?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo BP_BUDDYSTREAM_URL.'/extensions/default/slickswitch.css';?>" type="text/css" />
<script src="<?php echo BP_BUDDYSTREAM_URL.'/extensions/default/jquery.slickswitch.js';?>" type="text/javascript"></script>

<?php include "AdminMenu.php"; 

    $arraySwitches = array(
        'buddystream_social_albums',
        'buddystream_social_albums_profile_navigation',
        'buddystream_group_sharing',
        'buddystream_sharebox',
        'buddystream_nocss'
    );

    //save the settings
    if($_POST['submit']){  
      foreach($arraySwitches as $switch){
        delete_site_option($switch);
        update_site_option($switch, $_POST[$switch]);    
      }

      echo '<div class="buddystream_info_box_green">' . __('Settings saved.', 'buddystream_lang') . '</div>';
    }
    ?>

    <div class="buddystream_info_box">       
    <?php _e('general settings description','buddystream_lang'); ?> 
    </div>

    <form method="post" action="">
          <table class="buddystream_table" cellspacing="0">

           <tr class="header">
               <td colspan="2"><?php _e('General settings', 'buddystream_lang');?></td>
           </tr>
              
           <tr class="even">
                <td><?php _e( 'Enable social abums feature.', 'buddystream_lang' );?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_social_albums" id="buddystream_social_albums"/></td>
           </tr>
           
           <tr class="odd">
               <td><?php _e( 'Move social albums under profile navigation.', 'buddystream_lang' );?></td>
               <td><input class="switch icons" type="checkbox" name="buddystream_social_albums_profile_navigation" id="buddystream_social_albums_profile_navigation"/></td>
           </tr>

           <tr class="even">
               <td><?php _e( 'Enable sharing on groups and forums.', 'buddystream_lang' );?></td>
               <td><input class="switch icons" type="checkbox" name="buddystream_group_sharing" id="buddystream_group_sharing"/></td>
           </tr>
           
           <tr class="odd">
               <td><?php _e( 'Enable ShareBox feature.', 'buddystream_lang' );?></td>
               <td><input class="switch icons" type="checkbox" name="buddystream_sharebox" id="buddystream_sharebox"/></td>
           </tr>

          <tr class="even">
              <td><?php _e( 'Disable all BuddyStream CSS includes.', 'buddystream_lang' );?></td>
              <td><input class="switch icons" type="checkbox" name="buddystream_nocss" id="buddystream_nocss"/></td>
          </tr>
           

        </table>
      
        <div style="float:left; clear:both;">
            <p>
                <input type="submit" name="submit" class="button-primary" value="<?php _e('Save Changes') ?>" />
                <input type="button" onclick="buddystreamTurnAllOn()" class="button-primary" value="<?php _e('Turn all on','buddystream_lang') ?>" />
                <input type="button" onclick="buddystreamTurnAllOff()" class="button-primary" value="<?php _e('Turn all off','buddystream_lang') ?>" />
            </p>
        </div>
    </form>
<?
//flip switches
$runscript = "";
foreach ($arraySwitches as $switch) {
     if(get_site_option($switch) == "on"){
         $runscript .= 'jQuery("#'.$switch.'").slickswitch("toggleOn");';
     }else{
         $runscript .= 'jQuery("#'.$switch.'").slickswitch("toggleOff");';
     }
}
?>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery(".switch").slickswitch();
        <?php echo $runscript;?>
    });
    
    function buddystreamTurnAllOn(){
    <?php
        foreach ($arraySwitches as $switch) {
            echo 'jQuery("#'.$switch.'").slickswitch("toggleOn");';
        }
     ?>
    }
    
    function buddystreamTurnAllOff(){
    <?php
        foreach ($arraySwitches as $switch) {
            echo 'jQuery("#'.$switch.'").slickswitch("toggleOff");';
        }
     ?>
    }
    
</script>