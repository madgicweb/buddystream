<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<?php echo buddystreamTabLoader('flickr'); ?>

<?php
if ($_GET['user_id']) {
  if ($_GET['action'] == "reset") {
      if ($_GET['confirmed'] == "1") {
          delete_user_meta($_GET['user_id'], "bs_flickr_username");
          echo ' <div id="message" class="buddystream_info_box_green fade">
         ' . __('"User integration has been reset. Note: User will have to reconnect their integration if desired."', 'buddystream_flickr') . '
          </div>';
      } else {
          //show message
          echo ' <div id="message" class="buddystream_info_box_green fade">
            ' . __('Are you sure ?', 'buddystream_flickr') . '
            <a href="?page=buddystream_flickr&settings=users&action=reset&user_id=' . $_GET['user_id'] . '&confirmed=1">Yes</a> | <a href="?page=buddystream_flickr&settings=users">No</a>
            </div>';
      }
  }
}
?>

<div class="buddystream_info_box"><?php _e('flickr users description','buddystream_flickr');?></div>

<table class="buddystream_table" cellspacing="0">
      <tr class="header">
          <td width="30"></td>
          <td>Username</td>
          <td><?php _e('Flickr', 'buddystream_flickr'); ?></td>
          <td><?php _e('Photos imported', 'buddystream_flickr'); ?></td>
          <td><?php _e('Reset user', 'buddystream_flickr'); ?></td>
      </tr>
  
    <?php
        //get all users who have set-up there flickr
          $rowClass = "even";
          $user_metas = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='bs_flickr_username';"));
          if ($user_metas) {
              foreach ($user_metas as $user_meta) {

                  //get userdata
                  $user_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE id=$user_meta->user_id;"));
                  $user_data = $user_data[0];
                  $flickr_profile = get_user_meta($user_data->ID, 'bs_flickr_username',1);

                  //count imported tweets
                  $imported_photos = count($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=$user_meta->user_id AND type='flickr';")));
                  echo "
                    <tr class='".$rowClass."'>
                        <td>" . get_avatar($user_data->ID, 32) . "</td>
                        <td><a href='" . $bp->root_domain . "/" . BP_MEMBERS_SLUG . "/" . $user_data->user_login . "'>" . $user_data->user_login . "</a></td>
                        <td><a href='http://www.flickr.com/" . $flickr_profile . "' title='http://www.flickr.com/" . $flickr_profile . "' target='_blanc'>http://www.flickr.com/" . $flickr_profile . "</a></td>
                        <td>" . $imported_photos . "</td>
                        <td><a href='?page=buddystream_flickr&settings=users&action=reset&user_id=" . $user_data->ID . "'>Reset</a></td>
                    </tr>
                    ";
                  
                  if($rowClass == "even"){ $rowClass = "odd"; }else{ $rowClass = "even"; }
              }
          }
        ?>
  </table>
</div>