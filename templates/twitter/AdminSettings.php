<br/>
<?php include "AdminMenu.php"; ?>

<?php
  if ($_POST) {
      update_site_option('tweetstream_consumer_key', trim(strip_tags($_POST['tweetstream_consumer_key'])));
      update_site_option('tweetstream_consumer_secret', trim(strip_tags($_POST['tweetstream_consumer_secret'])));
      update_site_option('tweetstream_user_settings_syncbp', trim(strip_tags(strtolower($_POST['tweetstream_user_settings_syncbp']))));
      update_site_option('tweetstream_user_settings_maximport', trim(strip_tags(strtolower($_POST['tweetstream_user_settings_maximport']))));
      update_site_option('bs_youtube_hide_sitewide', trim(strip_tags(strtolower($_POST['bs_youtube_hide_sitewide']))));

      //backward compatable
      $user_metas_old = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->usermeta where meta_key='tweetstream_token';"));
      foreach ($user_metas_old as $user_meta_old) {
        update_usermeta($user_meta_old->user_id, 'tweetstream_stamp',date('dmYHis'));
      }
      //backward compatable
      echo '<div class="updated" style="margin-top:50px;"><p><strong>' . __('Settings saved.', 'buddystream_lang') . '</strong></p></div>';
   }
?>

<div class="wrap"><br/>
        <h2 style="float: left; line-height: 5px; padding-left: 5px;">
            <?php echo __('Twitter API Settings'); ?>
        </h2>
        <br />

      <form method="post" action="">
          <table class="form-table">
            <tr>
                <td colspan="2" scope="row">
                     <?php echo __('For BuddyStream to work you will need to get an API key from Twitter.<br /><br />
                     To get a API key, follow these steps:', 'buddystream_lang');?>
                    <br>
                    <?php echo __('1. Go to', 'buddystream_lang');?>
                    "<a href="http:www.twitter.com/apps" target="_blank">http:www.twitter.com/apps</a>"
                     <?php echo __('and login with your Twitter account.<br />','buddystream_lang');?>

                     <?php echo __('2. Create a new app wich has read/write settings (important), type browser and a callback url to: ', 'buddystream_lang'); ?>
                     <b><?php echo $bp->root_domain; ?></b><br />

                     <?php echo __('3. Fill in the consumer key and consumer secret below.', 'buddystream_lang'); ?><br />
                </td>
            </tr>
            <tr valign="top">
                <th scope="row"><?php echo __('Consumer key:', 'buddystream_lang');?></th>
                   <td>
                       <input type="text" name="tweetstream_consumer_key" value="<?php echo get_site_option('tweetstream_consumer_key'); ?>" size="50" />
                   </td>
              </tr>

              <tr valign="top">
                  <th scope="row"><?php echo __('Consumer secret key:', 'buddystream_lang');?></th>
                    <td>
                        <input type="text" name="tweetstream_consumer_secret" value="<?php echo get_site_option('tweetstream_consumer_secret'); ?>" size="50" />
                    </td>
              </tr>

            <tr valign="top">
                <th scope="row"><h2><?php echo __('User options', 'buddystream_lang');?></h2></th>
                <td></td>
            </tr>


            <tr valign="top">
            <th><?php echo __( 'Hide tweets on the sidewide activity stream?', 'buddystream_lang' );?></th>
            <th>
            <input type="radio" name="bs_twitter_hide_sitewide" id="facestream_user_settings_syncupdatesbp" value="1" <?php if(get_site_option('bs_twitter_hide_sitewide')==1){echo'checked';}?>> <?php echo __( 'Yes', 'buddystream_lang' );?>
            <input type="radio" name="bs_twitter_hide_sitewide" id="facestream_user_settings_syncupdatesbp" value="0" <?php if(get_site_option('bs_twitter_hide_sitewide')==0){echo'checked';}?>> <?php echo __( 'No', 'buddystream_lang' );?>
            </th>


            <tr valign="top">
                <th><?php echo __('Allow users to sync Twitter to you website?', 'buddystream_lang');?></th>
                <th>
                    <input type="radio" name="tweetstream_user_settings_syncbp" id="buddystream_user_settings_syncbp" value="0"
                    <?php
                       if (get_site_option('tweetstream_user_settings_syncbp') == 0) {
                           echo 'checked';
                       }
                    ?>>

                    <?php echo __('Yes', 'buddystream_lang');?>
                        <input type="radio" name="tweetstream_user_settings_syncbp" id="buddystream_user_settings_syncbp" value="1"
                        <?php if (get_site_option('tweetstream_user_settings_syncbp') == 1) { echo 'checked'; }?>>
                        <?php echo __('No', 'buddystream_lang');?>
                </th>
            </tr>

            <tr valign="top">
                <th><?php echo __('Maximum Tweets to be imported per user, per day (empty = unlimited tweets import):', 'buddystream_lang'); ?></th>
                <th>
                    <input type="text" name="tweetstream_user_settings_maximport" value="<?php echo get_site_option('tweetstream_user_settings_maximport'); ?>" size="5" />
                </th>
            </tr>
        </table>
       <p class="submit"><input type="submit" class="button-primary" value="<?php echo __('Save Changes') ?>" /></p>
    </form>
</div>