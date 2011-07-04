<link rel="stylesheet" href="<?php echo plugins_url() . '/buddystream/extentions/default/style.css';?>" type="text/css" />
<?php echo buddystreamTabloader('twitter'); ?>

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

          echo ' <div id="message" class="buddystream_info_box fade">
          ' . __('"User integration has been reset. Note: User will have to reconnect their integration if desired."', 'buddystream_twitter') . '
          </div>';
      } else {
          //show message
          echo ' <div id="message" class="buddystream_info_box_green fade">
            ' . __('Are you sure ?', 'buddystream_twitter') . '
            <a href="?page=buddystream_twitter&settings=users&action=reset&user_id=' . $_GET['user_id'] . '&confirmed=1">Yes</a> | <a href="?page=buddystream_twitter&settings=users">No</a>
            </div>';
      }
  }
}
?>
<div class="buddystream_info_box">
<?php _e('twitter users description','buddystream_twitter');?></div>

<table class="buddystream_table" cellspacing="0">
   <tr class="header">
      <td></th>
      <td><?php _e('Username', 'buddystream_twitter'); ?></td>
      <td><?php _e('Email', 'buddystream_twitter'); ?></td>
      <td><?php _e('Twitter', 'buddystream_twitter'); ?></td>
      <td><?php _e('Tweets imported', 'buddystream_twitter'); ?></td>
      <td><?php _e('Reset user', 'buddystream_twitter'); ?></td>
  </tr>
  
<?php
//get all users who have set-up there tweetstream
          $rowClass = "even";
          $user_metas = $wpdb->get_results($wpdb->prepare("SELECT user_id FROM $wpdb->usermeta WHERE meta_key='tweetstream_token';"));
          if ($user_metas) {
              foreach ($user_metas as $user_meta) {

                  //get userdata
                  $user_data = $wpdb->get_results($wpdb->prepare("SELECT * FROM $wpdb->users WHERE id=$user_meta->user_id;"));
                  $user_data = $user_data[0];
                  $twitter_profile = str_replace("@", "", get_user_meta($user_data->ID, 'tweetstream_mention',1));

                  //count imported tweets
                  if(get_user_meta($user_meta->user_id, 'tweetstream_synctoac', 1)) {
                      $imported_tweets = count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=$user_meta->user_id AND type='twitter';"));
                  } else {
                      $imported_tweets = "Import turned off by user";
                  }
                  
                  echo "
                    <tr class='".$rowClass."'>
                        <td>" . get_avatar($user_data->ID, 32) . "</td>
                        <td><a href='" . $bp->root_domain . "/" . BP_MEMBERS_SLUG . "/" . $user_data->user_login . "'>" . $user_data->user_login . "</a></td>
                        <td><a href='mailto:" . $user_data->user_email . "' title='E-mail: " . $user_data->user_email . "'>" . $user_data->user_email . "</a></td>
                        <td><a href='http://www.twitter.com/" . $twitter_profile . "' title='http://www.twitter.com/" . $twitter_profile . "' target='_blanc'>http://www.twitter.com/" . $twitter_profile . "</a></td>
                        <td>" . $imported_tweets . "</td>
                        <td><a href='?page=buddystream_twitter&settings=users&action=reset&user_id=" . $user_data->ID . "'>Reset</a></td>
                    </tr>
                    ";
               
                  if($rowClass == "even"){
                      $rowClass = "odd";
                  }else{
                      $rowClass = "even";
                  }
              }
          }
        ?>
      </tbody>
  </table>
</div>

              
              