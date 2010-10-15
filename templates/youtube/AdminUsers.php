<br/>
<?php include "AdminMenu.php"; ?>

<div class="wrap"><br />                         
<h2 style="float: left; line-height: 5px; padding-left: 5px;"><?php echo __('Users', 'buddystream_lang'); ?></h2>
<br /><br /><br />

<?php
if ($_GET['user_id']) {
  if ($_GET['action'] == "reset") {
      if ($_GET['confirmed'] == "1") {
          delete_user_meta($_GET['user_id'], "bs_youtube_username");

          echo ' <div id="message" class="updated fade">
          <p>' . __('"User integration has been reset. Note: User will have to reconnect their integration if desired."', 'buddystream_lang') . '</p>
          </div>';
      } else {
          //show message
          echo ' <div id="message" class="updated fade">
            <p>' . __('Are you sure ?', 'buddystream_lang') . '
            <a href="?page=buddystream_youtube&settings=users&action=reset&user_id=' . $_GET['user_id'] . '&confirmed=1">Yes</a> | <a href="?page=tweetstream-users">No</a></p>
            </div>';
      }
  }
}

echo __('Below is a list of users whom are using YouTube.You may reset their YouTube settings here. <br>
<b>Note: </b>  When you reset a user, they will need to input their YouTube username again.
Any YouTube video history that has already been imported WILL NOT be deleted. ', 'buddystream_lang');
?>
<br><br>
<table class="widefat fixed" cellspacing="0">

  <thead>
      <tr class="thead">
          <th scope="col" id="cb" class="manage-column column-cb check-column" style=""></th>
          <th scope="col" id="username" class="manage-column column-username" style="">Username</th>
          <th scope="col" id="email" class="manage-column column-name" style=""><?= __('Youtube', 'buddystream_lang'); ?></th>
          <th scope="col" id="role" class="manage-column column-role" style=""><?= __('Video history', 'buddystream_lang'); ?></th>
      </tr>
  </thead>

  <tfoot>
      <tr class="thead">
          <th scope="col" id="cb" class="manage-column column-cb check-column" style=""></th>
          <th scope="col" id="username" class="manage-column column-username" style="">Username</th>
          <th scope="col" id="email" class="manage-column column-name" style=""><?= __('Youtube', 'buddystream_lang'); ?></th>
          <th scope="col" id="role" class="manage-column column-role" style=""><?= __('Video history imported', 'buddystream_lang'); ?></th>
      </tr>
  </tfoot>
  <tbody id="users" class="list:user user-list">
<?php
//get all users who have set-up there youtube

          $user_metas = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='bs_youtube_username';"));
          if ($user_metas) {
              foreach ($user_metas as $user_meta) {

                //  get userdata
                  $user_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE id=$user_meta->user_id;"));
                  $user_data = $user_data[0];
                  $youtube_profile = get_usermeta($user_data->ID, 'bs_youtube_username');

                //  count imported history
                  $imported_history = count($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=$user_meta->user_id AND type='youtube';")));
                  echo "
                    <tr id='user-29'>
                        <th scope='row' class='check-column'></th>
                        <td class='username column-username'>
                        " . get_avatar($user_data->ID, 32) . "
                            <strong><a href='" . $bp->root_domain . "/" . BP_MEMBERS_SLUG . "/" . $user_data->user_login . "'>" . $user_data->user_login . "</a></strong><br />
                            <span class='delete'><a href='?page=buddystream_youtube&settings=users&action=reset&user_id=" . $user_data->ID . "'>Reset</a></span></div>
                        </td>
                        
                        <td class='email column-email'>
                            <a href='http://www.youtube.com/" . $youtube_profile . "' title='http://www.youtube.com/" . $youtube_profile . "' target='_blanc'>http://www.youtube.com/" . $youtube_profile . "</a>
                        </td>

                        <td class='posts column-posts num'>" . $imported_history . "</td>
                        </tr>
                    ";
              }
          }
        ?>
      </tbody>
  </table>
</div>