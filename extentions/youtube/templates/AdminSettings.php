<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<link rel="stylesheet" href="<?php echo plugins_url();?>/buddystream/extentions/default/slickswitch.css" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="<?php echo plugins_url();?>/buddystream/extentions/default/jquery.slickswitch.js" type="text/javascript"></script>

<?php echo buddystreamTabLoader('youtube'); ?>

<?php
 $arraySwitches = array(
        'buddystream_youtube_hide_sitewide',
        'buddystream_youtube_album'
    );

  if ($_POST) {
      update_site_option('bs_youtube_user_settings_maximport', trim(strip_tags(strtolower($_POST['bs_youtube_user_settings_maximport']))));

       foreach($arraySwitches as $switch){
        update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));    
      }
      
      echo '<div class="buddystream_info_box_green">' . __('Settings saved.', 'buddystream_youtube') . '</div>';
   }
?>

        <div class="buddystream_info_box">
              <?php 
              _e('youtube settings description','buddystream_youtube'); ?>
        </div>

      <form method="post" action="">
          <table class="buddystream_table" cellspacing="0">          
            
            <tr class="header">
                <td colspan="2"><?php _e('User options', 'buddystream_youtube');?></td>
            </tr>

             <tr>
                <td><?php _e( 'Show YouTube album on user profile page?', 'buddystream_youtube' );?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_youtube_album" id="buddystream_youtube_album"/></td>
            </tr>
            
            <tr class="odd">
                <td><?php _e( 'Hide YouTube videos on the sitewide activity stream?', 'buddystream_youtube' );?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_youtube_hide_sitewide" id="buddystream_youtube_hide_sitewide"/></td>
            </tr>

            <tr>
                <td><?php _e('Maximum number of videos to import per user, per day (empty - unlimited):', 'buddystream_youtube'); ?></td>
                <td><input type="text" name="bs_youtube_user_settings_maximport" value="<?php echo get_site_option('bs_youtube_user_settings_maximport'); ?>" size="5" /></td>
            </tr>
            
        </table>
       <p class="submit"><input type="submit" class="button-primary" value="<?php _e('Save Changes','buddystream_youtube') ?>" /></p>
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