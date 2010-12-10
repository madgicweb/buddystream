<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/css/buddystream.css';?>" type="text/css" />
<br/>
<?php include "AdminMenu.php"; ?>

<?php
  if ($_POST) {

      update_site_option('facestream_application_id', trim(strip_tags($_POST['facestream_application_id'])));
      update_site_option('facestream_application_secret', trim(strip_tags($_POST['facestream_application_secret'])));
      update_site_option('facestream_user_settings_syncbp', trim(strip_tags(strtolower($_POST['facestream_user_settings_syncbp']))));
      update_site_option('facestream_user_settings_maximport', trim(strip_tags(strtolower($_POST['facestream_user_settings_maximport']))));
      update_site_option('facestream_user_settings_syncupdatesbp',trim(strip_tags(strtolower($_POST['facestream_user_settings_syncupdatesbp']))));
      update_site_option('facestream_user_settings_synclinksbp',trim(strip_tags(strtolower($_POST['facestream_user_settings_synclinksbp']))));
      update_site_option('facestream_user_settings_syncvideosbp',trim(strip_tags(strtolower($_POST['facestream_user_settings_syncvideosbp']))));
      update_site_option('facestream_user_settings_syncphotosbp',trim(strip_tags(strtolower($_POST['facestream_user_settings_syncphotosbp']))));
      update_site_option('bs_facebook_hide_sitewide',trim(strip_tags(strtolower($_POST['bs_facebook_hide_sitewide']))));

      //backward compatable
      $user_metas_old = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->usermeta where meta_key='facestream_session_key';"));
      foreach ($user_metas_old as $user_meta_old) {
        update_usermeta($user_meta_old->user_id, 'facestream_stamp',date('dmYHis'));
      }
    //backward compatable


      echo '<div class="updated" style="margin-top:50px;"><p><strong>' . __('Settings saved.', 'buddystream_lang') . '</strong></p></div>';
   }
?>

<div class="wrap"><br/>
        <h2 style="float: left; line-height: 5px; padding-left: 5px;">
            <?php echo __('Facebook API'); ?>
        </h2>
        <br /><br /><br/>

        
        <div class="bs_info_box">
         <?php echo __('For the Facebook intergration to work with BuddyStream you will need to get an Application ID and Key from Facebook.<br> You may get one by adding the Facebook Developer application here:', 'buddystream_lang'); ?><br>
         <a href="http://developers.facebook.com/setup/" target="_new" title="Facebook">http://developers.facebook.com/setup/</a>
        </div>


        
      <form method="post" action="">
          <table class="form-table">
           
              <tr valign="top" <? if(get_site_option('facestream_application_id')==""){ echo 'class="bs_error_box"'; } ?>>
                <th scope="row"><?php echo __('Application ID:', 'buddystream_lang');?></th>
                   <td>
                       <input type="text" name="facestream_application_id" value="<?php echo get_site_option('facestream_application_id'); ?>" size="50" />
                   </td>
              </tr>
           
              <tr valign="top" <? if(get_site_option('facestream_application_secret')==""){ echo 'class="bs_error_box"'; } ?>>
                  <th scope="row"><?php echo __('Application secret:', 'buddystream_lang');?></th>
                    <td>
                        <input type="text" name="facestream_application_secret" value="<?php echo get_site_option('facestream_application_secret'); ?>" size="50" />
                    </td>
              </tr>


              <? if(get_site_option('facestream_application_secret')!="" && get_site_option('facestream_application_id')!="") { ?>


            <tr valign="top">
                <th scope="row"><h2><?php echo __('User options', 'buddystream_lang');?></h2></th>
                <td></td>
            </tr>

            <tr valign="top">
            <th><?php echo __( 'Hide Facebook items on the sidewide activity stream?', 'buddystream_lang' );?></th>
            <th>
            <input type="radio" name="bs_facebook_hide_sitewide" id="facestream_user_settings_syncupdatesbp" value="1" <?php if(get_site_option('bs_facebook_hide_sitewide')==1){echo'checked';}?>> <label for="yes"><?php echo __( 'Yes', 'buddystream_lang' );?></label>
            <input type="radio" name="bs_facebook_hide_sitewide" id="facestream_user_settings_syncupdatesbp" value="0" <?php if(get_site_option('bs_facebook_hide_sitewide')==0){echo'checked';}?>> <label for="no"><?php echo __( 'No', 'buddystream_lang' );?></label>
            </th>


            <tr valign="top">
                <th><?php echo __('Allow users to sync to BuddyPress?', 'buddystream_lang');?></th>
                <th>
                    <input type="radio" name="facestream_user_settings_syncbp" id="buddystream_user_settings_syncbp" value="0"
                    <?php
                       if (get_site_option('facestream_user_settings_syncbp') == 0) {
                           echo 'checked';
                       }
                    ?>>

                    <?php echo __('Yes', 'buddystream_lang');?>
                        <input type="radio" name="facestream_user_settings_syncbp" id="buddystream_user_settings_syncbp" value="1"
                        <?php if (get_site_option('facestream_user_settings_syncbp') == 1) { echo 'checked'; }?>>
                        <?php echo __('No', 'buddystream_lang');?>
                </th>
            </tr>


                    <tr valign="top">
            <th><?php echo __( 'Allow users to sync updates to BuddyPress?', 'buddystream_lang' );?></th>
            <th>
            <input type="radio" name="facestream_user_settings_syncupdatesbp" id="facestream_user_settings_syncupdatesbp" value="0" <?php if(get_site_option('facestream_user_settings_syncupdatesbp')==0){echo'checked';}?>> <label for="yes"><?php echo __( 'Yes', 'buddystream_lang' );?></label>
			<input type="radio" name="facestream_user_settings_syncupdatesbp" id="facestream_user_settings_syncupdatesbp" value="1" <?php if(get_site_option('facestream_user_settings_syncupdatesbp')==1){echo'checked';}?>> <label for="no"><?php echo __( 'No', 'buddystream_lang' );?></label>
            </th>
        </tr>

        <tr valign="top">
            <th><?php echo __( 'Allow users to sync links to BuddyPress?', 'buddystream_lang' );?></th>
            <th>
            <input type="radio" name="facestream_user_settings_synclinksbp" id="facestream_user_settings_synclinksbp" value="0" <?php if(get_site_option('facestream_user_settings_synclinksbp')==0){echo'checked';}?>> <label for="yes"><?php echo __( 'Yes', 'buddystream_lang' );?></label>
			<input type="radio" name="facestream_user_settings_synclinksbp" id="facestream_user_settings_synclinksbp" value="1" <?php if(get_site_option('facestream_user_settings_synclinksbp')==1){echo'checked';}?>> <label for="no"><?php echo __( 'No', 'buddystream_lang' );?></label>
            </th>
        </tr>

        <tr valign="top">
            <th><?php echo __( 'Allow users to sync photos to BuddyPress?', 'buddystream_lang' );?></th>
            <th>
            <input type="radio" name="facestream_user_settings_syncphotosbp" id="facestream_user_settings_syncphotosbp" value="0" <?php if(get_site_option('facestream_user_settings_syncphotosbp')==0){echo'checked';}?>> <label for="yes"><?php echo __( 'Yes', 'buddystream_lang' );?></label>
			<input type="radio" name="facestream_user_settings_syncphotosbp" id="facestream_user_settings_syncphotosbp" value="1" <?php if(get_site_option('facestream_user_settings_syncphotosbp')==1){echo'checked';}?>> <label for="no"><?php echo __( 'No', 'buddystream_lang' );?></label>
            </th>
        </tr>

        <tr valign="top">
            <th><?php echo __( 'Allow users to sync videos to BuddyPress?', 'buddystream_lang' );?></th>
            <th>
            <input type="radio" name="facestream_user_settings_syncvideosbp" id="facestream_user_settings_syncvideosbp" value="0" <?php if(get_site_option('facestream_user_settings_syncvideosbp')==0){echo'checked';}?>> <label for="yes"><?php echo __( 'Yes', 'buddystream_lang' );?></label>
			<input type="radio" name="facestream_user_settings_syncvideosbp" id="facestream_user_settings_syncvideosbp" value="1" <?php if(get_site_option('facestream_user_settings_syncvideosbp')==1){echo'checked';}?>> <label for="no"><?php echo __( 'No', 'buddystream_lang' );?></label>
            </th>
        </tr>

            <tr valign="top">
                <th><?php echo __('Maximum amount of Facebook items (total) to be imported per user, per day (empty = unlimited):', 'buddystream_lang'); ?></th>
                <th>
                    <input type="text" name="facestream_user_settings_maximport" value="<?php echo get_site_option('facestream_user_settings_maximport'); ?>" size="5" />
                </th>
            </tr>
            <? } ?>

        </table>
       <p class="submit"><input type="submit" class="button-primary" value="<?php echo __('Save Changes') ?>" /></p>
    </form>
</div>