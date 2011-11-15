<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<link rel="stylesheet" href="<?php echo plugins_url();?>/buddystream/extentions/default/slickswitch.css" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="<?php echo plugins_url();?>/buddystream/extentions/default/jquery.slickswitch.js" type="text/javascript"></script>

<?php echo buddystreamTabLoader('flickr'); ?>

<?php

    $arraySwitches = array(
        'buddystream_flickr_hide_sitewide',
        'bs_flickr_album'
    );

  if ($_POST) {
      update_site_option('bs_flickr_api_key', trim(strip_tags($_POST['bs_flickr_api_key'])));
      update_site_option('bs_flickr_user_settings_maximport', trim(strip_tags(strtolower($_POST['bs_flickr_user_settings_maximport']))));
      
      foreach($arraySwitches as $switch){
        update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));    
      }
      
      echo '<div class="buddystream_info_box_green">' . __('Settings saved.', 'buddystream_flickr') . '</div>';
   }
?>

        <div class="buddystream_info_box">
         <?php _e('flickr settings description','buddystream_flickr'); ?>
         <a href="http://www.flickr.com/services/api/misc.api_keys.html" target="_new">http://www.flickr.com/services/api/misc.api_keys.html</a>
        </div>

      <form method="post" action="">
          <table class="buddystream_table" cellspacing="0">
            
            <tr class="header">
                <td colspan="2"><?php _e('Flickr API', 'buddystream_flickr');?></td>
            </tr>
              
            <tr>
                <td><?php _e('API key:', 'buddystream_flickr');?></td>
                <td><input type="text" name="bs_flickr_api_key" value="<?php echo get_site_option('bs_flickr_api_key'); ?>" size="50" /></td>
            </tr>
              
            <tr class="header">
                <td colspan="2"><?php _e('User options', 'buddystream_flickr');?></td>
            </tr>
            
             <tr>
                <td><?php _e( 'Show  Flickr album on user profile page?', 'buddystream_flickr' );?></td>
                <td><input class="switch icons" type="checkbox" name="bs_flickr_album" id="bs_flickr_album"/></td>
            </tr>

            <tr class="odd">
                <td><?php _e( 'Hide Flickr photos on the sidewide activity stream?', 'buddystream_flickr' );?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_flickr_hide_sitewide" id="buddystream_flickr_hide_sitewide"/></td>
            </tr>

            <tr>
                <td><?php _e('Maximum number of photos imported per user, per day (empty = unlimited):', 'buddystream_flickr'); ?></td>
                <td><input type="text" name="bs_flickr_user_settings_maximport" value="<?php echo get_site_option('bs_flickr_user_settings_maximport'); ?>" size="5" /></td>
            </tr>
         </table>
          
         <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes') ?>" /></p>
    </form>

<script type="text/javascript">
    $(".switch").slickswitch();
</script>

<?php
foreach($arraySwitches as $switch){
     if(get_site_option($switch)){
        echo'
        <script>
            $("#'.$switch.'").slickswitch("toggleOn"); 
        </script>
        ';
     }else{
        echo'
        <script>
            $("#'.$switch.'").slickswitch("toggleOff"); 
        </script>
        ';
     }
}
?>