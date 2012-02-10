<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/admin.css';?>" type="text/css"/>
<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/slickswitch.css';?>" type="text/css" />
<script src="<?php echo plugins_url();?>/buddystream/extentions/default/jquery.slickswitch.js" type="text/javascript"></script>

<?php include "AdminMenu.php"; 

    $arraySwitches = array(
        'buddystream_social_albums',
        'buddystream_group_sharing',
        'buddystream_sharebox'
    );

    //save the settings
    if($_POST){
       foreach($arraySwitches as $switch){
        update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));    
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
              
           <tr class="odd">
                <td><?php _e( 'Enable social abums feature.', 'buddystream_lang' );?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_social_albums" id="buddystream_social_albums"/></td>
           </tr>

           <tr class="even">
               <td><?php _e( 'Enable sharing on groups and forums.', 'buddystream_lang' );?></td>
               <td><input class="switch icons" type="checkbox" name="buddystream_group_sharing" id="buddystream_group_sharing"/></td>
           </tr>
           
           <tr class="odd">
               <td><?php _e( 'Enable ShareBox feature.', 'buddystream_lang' );?></td>
               <td><input class="switch icons" type="checkbox" name="buddystream_sharebox" id="buddystream_sharebox"/></td>
           </tr>

        </table>
       <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes'); ?>" /></p>
    </form>
<?
//flip switches
 $runscript = "";
foreach ($arraySwitches as $switch) {
     if(get_site_option($switch)){
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
</script>