
<br/>
<?php include "AdminMenu.php"; ?>

<?php
  if ($_POST) {
      update_site_option('bs_youtube_user_settings_maximport', trim(strip_tags(strtolower($_POST['bs_youtube_user_settings_maximport']))));
      update_site_option('bs_youtube_hide_sitewide', trim(strip_tags(strtolower($_POST['bs_youtube_hide_sitewide']))));
      echo '<div class="updated" style="margin-top:50px;"><p><strong>' . __('Settings saved.', 'buddystream_lang') . '</strong></p></div>';
   }
?>

<div class="wrap"><br/>
        <h2 style="float: left; line-height: 5px; padding-left: 5px;">
            <?php echo __('YouTube API'); ?>
        </h2>
        <br /><br />

      <form method="post" action="">
          <table class="form-table">
            <tr>
                <td colspan="2" scope="row">
                     <?php echo __('A YouTube API Key or connection is NOT required to get the users video histories. '); ?><br />
                </td>
            </tr>
          
            <tr valign="top">
                <th scope="row"><h2><?php echo __('User options', 'buddystream_lang');?></h2></th>
                <td></td>
            </tr>

            <tr valign="top">
            <th><?php echo __( 'Hide YouTube videos on the sitewide activity stream?', 'buddystream_lang' );?></th>
            <th>
            <input type="radio" name="bs_youtube_hide_sitewide" id="facestream_user_settings_syncupdatesbp" value="1" <?php if(get_site_option('bs_youtube_hide_sitewide')==1){echo'checked';}?>> <?php echo __( 'Yes', 'buddystream_lang' );?>
            <input type="radio" name="bs_youtube_hide_sitewide" id="facestream_user_settings_syncupdatesbp" value="0" <?php if(get_site_option('bs_youtube_hide_sitewide')==0){echo'checked';}?>> <?php echo __( 'No', 'buddystream_lang' );?>
            </th>


            <tr valign="top">
                <th><?php echo __('Maximum number of videos to import per user, per day (empty - unlimited):', 'buddystream_lang'); ?></th>
                <th>
                    <input type="text" name="bs_youtube_user_settings_maximport" value="<?php echo get_site_option('bs_youtube_user_settings_maximport'); ?>" size="5" />
                </th>
            </tr>
        </table>
       <p class="submit"><input type="submit" class="button-primary" value="<?php echo __('Save Changes') ?>" /></p>
    </form>
</div>