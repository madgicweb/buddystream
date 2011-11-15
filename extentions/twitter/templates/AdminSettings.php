<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<link rel="stylesheet" href="<?php echo plugins_url();?>/buddystream/extentions/default/slickswitch.css" type="text/css" />
<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.4/jquery.min.js"></script>
<script src="<?php echo plugins_url();?>/buddystream/extentions/default/jquery.slickswitch.js" type="text/javascript"></script>

<?php echo buddystreamTabloader('twitter'); ?>

<?php

$arraySwitches = array(
    'tweetstream_user_settings_syncbp',
    'buddystream_twitter_hide_sitewide',
    'buddystream_twitter_share',
    'buddystream_twitter_share_counter'
);

  if ($_POST) {
      update_site_option('tweetstream_consumer_key', trim($_POST['tweetstream_consumer_key']));
      update_site_option('tweetstream_consumer_secret', trim($_POST['tweetstream_consumer_secret']));
      update_site_option('tweetstream_user_settings_maximport', trim(strip_tags(strtolower($_POST['tweetstream_user_settings_maximport']))));
      
      foreach($arraySwitches as $switch){
         update_site_option($switch, trim(strip_tags(strtolower($_POST[$switch]))));    
      }

      //backward compatable
      $user_metas_old = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->usermeta where meta_key='tweetstream_token';"));
      foreach ($user_metas_old as $user_meta_old) {
        update_user_meta($user_meta_old->user_id, 'tweetstream_stamp',date('dmYHis'));
      }
      //backward compatable
      echo '<div class="buddystream_info_box_green">' . __('Settings saved.', 'buddystream_twitter') . '</div>';
   }
?>

        <div class="buddystream_info_box">
         <?php echo str_replace("#ROOTDOMAIN",$bp->root_domain,__('twitter settings description', 'buddystream_twitter')); ?>
        </div>

      <form method="post" action="">
          <table class="buddystream_table" cellspacing="0">
            
            <tr class="header">
                <td colspan="2"><?php _e('Twitter API','buddystream_twitter');?></td>
            </tr>
              
            <tr>
                <td><?php _e('Consumer key:', 'buddystream_twitter');?></td>
                <td><input type="text" name="tweetstream_consumer_key" value="<?php echo get_site_option('tweetstream_consumer_key'); ?>" size="50" /></td>
             </tr>

              <tr class="odd">
                  <td><?php _e('Consumer secret key:', 'buddystream_twitter');?></td>
                   <td><input type="text" name="tweetstream_consumer_secret" value="<?php echo get_site_option('tweetstream_consumer_secret'); ?>" size="50" /></td>
              </tr>

              <?php if(get_site_option('tweetstream_consumer_key') && get_site_option('tweetstream_consumer_secret')){ ?>

            <tr class="header">
                <td colspan="2"><?php _e('User options','buddystream_twitter');?></td>
            </tr>

            <tr>
                <td><?php _e( 'Hide tweets on the sidewide activity stream?', 'buddystream_twitter' );?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_twitter_hide_sitewide" id="buddystream_twitter_hide_sitewide"/></td>
            </tr>

            <tr valign="top" class="odd">
                <td><?php _e('Allow users to sync Twitter to you website?', 'buddystream_twitter');?></td>
                <td><input class="switch icons" type="checkbox" name="tweetstream_user_settings_syncbp" id="tweetstream_user_settings_syncbp"/></td>
            </tr>

            <tr valign="top">
                <td><?php _e('Maximum Tweets to be imported per user, per day (empty = unlimited tweets import):', 'buddystream_twitter'); ?></th>
                <td><input type="text" name="tweetstream_user_settings_maximport" value="<?php echo get_site_option('tweetstream_user_settings_maximport'); ?>" size="5" /></td>
            </tr>
            
            <tr class="header">
                <td colspan="2"><?php _e('Extra options','buddystream_twitter');?></td>
            </tr>
            
            <tr>
                <td><?php _e( 'Show Twitter share button?', 'buddystream_twitter' );?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_twitter_share" id="buddystream_twitter_share"/></td>
            </tr>
            
             <tr class="odd">
                <td><?php _e('Show counter?', 'buddystream_twitter'); ?></td>
                <td><input class="switch icons" type="checkbox" name="buddystream_twitter_share_counter" id="buddystream_twitter_share_counter"/></td>
            </tr>
            
            <?php } ?>

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