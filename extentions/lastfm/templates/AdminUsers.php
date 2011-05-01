<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<?php echo buddystreamTabLoader('lastfm'); ?>


<?php
if ($_GET['user_id']) {
  if ($_GET['action'] == "reset") {
      if ($_GET['confirmed'] == "1") {
          delete_user_meta($_GET['user_id'], "bs_lastfm_username");

          echo ' <div id="message" class="buddystream_info_box_green fade">
          ' . __('"User integration has been reset. Note: User will have to reconnect their integration if desired."', 'buddystream_lastfm') . '
          </div>';
      } else {
          //show message
          echo ' <div id="message" class="buddystream_info_box_green fade">
            ' . __('Are you sure ?', 'buddystream_lastfm') . '
            <a href="?page=buddystream_lastfm&settings=users&action=reset&user_id=' . $_GET['user_id'] . '&confirmed=1">Yes</a> | <a href="?page=buddystream_lastfm&settings=users">No</a>
            </div>';
      }
  }
}
?>

<div class="buddystream_info_box">
<?php _e('lastfm users description', 'buddystream_lastfm');
//echo __('Below is a list of users whom are using Last.fm. You may reset their Last.fm settings here.<br>
//    <b>Note:</b> When you reset a user, they will need to fill in their Last.fm username again.<br>
//    Any Last.fm songs history that have already been imported WILL NOT be deleted.', 'buddystream_lastfm');
?>
</div>

<table class="buddystream_table" cellspacing="0">

      <tr class="header">
          <td width="30"></td>
          <td><?php _e('Username', 'buddystream_lastfm'); ?></td>
          <td><?php _e('Last.fm', 'buddystream_lastfm'); ?></td>
          <td><?php _e('Song history', 'buddystream_lastfm'); ?></td>
          <td><?php _e('Reset user', 'buddystream_lastm'); ?></td>
      </tr>
  
<?php
//get all users who have set-up there lastfm
          $rowClass = "even";
          $user_metas = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='bs_lastfm_username';"));
          if ($user_metas) {
              foreach ($user_metas as $user_meta) {

                //  get userdata
                  $user_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE id=$user_meta->user_id;"));
                  $user_data = $user_data[0];
                  $lastfm_profile = get_user_meta($user_data->ID, 'bs_lastfm_username',1);

                //  count imported history
                  $imported_history = count($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=$user_meta->user_id AND type='lastfm';")));
                  echo "
                    <tr class=".$rowClass.">
                        <td>" . get_avatar($user_data->ID, 32) . "</td>
                        <td><a href='" . $bp->root_domain . "/" . BP_MEMBERS_SLUG . "/" . $user_data->user_login . "'>" . $user_data->user_login . "</a></td>
                        <td><a href='http://www.lastfm.com/user/" . $lastfm_profile . "' title='http://www.lastfm.com/user/" . $lastfm_profile . "' target='_blanc'>http://www.lastfm.com/user/" . $lastfm_profile . "</a></td>
                        <td>" . $imported_history . "</td>
                        <td><a href='?page=buddystream_lastfm&settings=users&action=reset&user_id=" . $user_data->ID . "'>Reset</a></td>
                    </tr>
                    ";
              }
                                
              if($rowClass == "even"){
                  $rowClass = "odd";
              }else{
                  $rowClass = "even";
              }
          }
        ?>
  </table>