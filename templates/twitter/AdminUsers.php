<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/css/buddystream.css';?>" type="text/css" />
<br/>
<?php include "AdminMenu.php"; ?>

<div class="wrap"><br />                         
<h2 style="float: left; line-height: 5px; padding-left: 5px;"><?php echo __('Users', 'buddystream_lang'); ?></h2>
<br /><br /><br />

<?php
if ($_GET['user_id']) {
  if ($_GET['action'] == "reset") {
      if ($_GET['confirmed'] == "1") {
          delete_user_meta($_GET['user_id'], "tweetstream_tweetstream_synctoac");
          delete_user_meta($_GET['user_id'], "tweetstream_mention");
          delete_user_meta($_GET['user_id'], "tweetstream_lastupdate");
          delete_user_meta($_GET['user_id'], "tweetstream_deletetweet");
          delete_user_meta($_GET['user_id'], "tweetstream_checkboxon");
          delete_user_meta($_GET['user_id'], "tweetstream_counterdate");
          delete_user_meta($_GET['user_id'], "tweetstream_tokensecret");
          delete_user_meta($_GET['user_id'], "tweetstream_filtermentions");
          delete_user_meta($_GET['user_id'], "tweetstream_synctoac");
          delete_user_meta($_GET['user_id'], "tweetstream_counterdate");
          delete_user_meta($_GET['user_id'], "tweetstream_checkboxon");
          delete_user_meta($_GET['user_id'], "tweetstream_daycounter");
          delete_user_meta($_GET['user_id'], "tweetstream_deletetweet");
          delete_user_meta($_GET['user_id'], "tweetstream_filtergood");
          delete_user_meta($_GET['user_id'], "tweetstream_filterbad");
          delete_user_meta($_GET['user_id'], "tweetstream_filtertoactivity");
          delete_user_meta($_GET['user_id'], "tweetstream_filtertotwitter");
          delete_user_meta($_GET['user_id'], "tweetstream_profilelink");
          delete_user_meta($_GET['user_id'], "tweetstream_screenname");
          delete_user_meta($_GET['user_id'], "tweetstream_token");

          echo ' <div id="message" class="updated fade">
          <p>' . __('"User integration has been reset. Note: User will have to reconnect their integration if desired."', 'buddystream_lang') . '</p>
          </div>';
      } else {
          //show message
          echo ' <div id="message" class="updated fade">
            <p>' . __('Are you sure ?', 'buddystream_lang') . '
            <a href="?page=buddystream_twitter&settings=users&action=reset&user_id=' . $_GET['user_id'] . '&confirmed=1">Yes</a> | <a href="?page=tweetstream-users">No</a></p>
            </div>';
      }
  }
}
?>
<div class="bs_info_box">
<?

echo __('Below is a list of all users whom are using Twitter. You may reset there Twitter settings here. <br>
        <b>Note:</b> When you reset a user they need to re-authenticate with Twitter.
        Tweets already imported WILL NOT be deleted!', 'buddystream_lang');
?></div><br><br>
<table class="widefat fixed" cellspacing="0">

  <thead>
      <tr class="thead">
          <th scope="col" id="cb" class="manage-column column-cb check-column" style=""></th>
          <th scope="col" id="username" class="manage-column column-username" style="">Username</th>
          <th scope="col" id="email" class="manage-column column-email" style="">E-mail</th>
          <th scope="col" id="email" class="manage-column column-name" style=""><?php echo __('Twitter', 'buddystream_lang'); ?></th>
          <th scope="col" id="role" class="manage-column column-role" style=""><?php echo __('Tweets imported', 'buddystream_lang'); ?></th>
      </tr>
  </thead>

  <tfoot>
      <tr class="thead">
          <th scope="col" id="cb" class="manage-column column-cb check-column" style=""></th>
          <th scope="col" id="username" class="manage-column column-username" style="">Username</th>
          <th scope="col" id="email" class="manage-column column-email" style="">E-mail</th>
          <th scope="col" id="email" class="manage-column column-name" style=""><?php echo __('Twitter', 'buddystream_lang'); ?></th>
          <th scope="col" id="role" class="manage-column column-role" style=""><?php echo __('Tweets imported', 'buddystream_lang'); ?></th>
      </tr>
  </tfoot>
  <tbody id="users" class="list:user user-list">
<?php
//get all users who have set-up there tweetstream

          $user_metas = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='tweetstream_token';"));
          if ($user_metas) {
              foreach ($user_metas as $user_meta) {

                //  get userdata
                  $user_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE id=$user_meta->user_id;"));
                  $user_data = $user_data[0];


                  $twitter_profile = str_replace("@", "", get_usermeta($user_data->ID, 'tweetstream_mention'));

                //  count imported tweets
                  $imported_tweets = count($wpdb->get_results($wpdb->prepare("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=$user_meta->user_id AND type='tweet';")));
                  echo "
                    <tr id='user-29'>
                        <th scope='row' class='check-column'></th>
                        <td class='username column-username'>
                        " . get_avatar($user_data->ID, 32) . "
                            <strong><a href='" . $bp->root_domain . "/" . BP_MEMBERS_SLUG . "/" . $user_data->user_login . "'>" . $user_data->user_login . "</a></strong><br />
                            <span class='delete'><a href='?page=buddystream_twitter&settings=users&action=reset&user_id=" . $user_data->ID . "'>Reset</a></span></div>
                        </td>
                        <td class='email column-email'>
                            <a href='mailto:" . $user_data->user_email . "' title='E-mail: " . $user_data->user_email . "'>" . $user_data->user_email . "</a>
                        </td>

                        <td class='email column-email'>
                            <a href='http://www.twitter.com/" . $twitter_profile . "' title='http://www.twitter.com/" . $twitter_profile . "' target='_blanc'>http://www.twitter.com/" . $twitter_profile . "</a>
                        </td>

                        <td class='posts column-posts num'>" . $imported_tweets . "</td>
                        </tr>
                    ";
              }
          }
        ?>
      </tbody>
  </table>
</div>

              
              