<?php
if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'buddystream_flickr_username', $_POST['buddystream_flickr_username']);
    echo '<div id="message" class="updated fade">
            <p>' . __('Settings saved', 'buddystream_lang') . '</p>
        </div>';
    }

    $buddystream_flickr_username = get_user_meta($bp->loggedin_user->id, 'buddystream_flickr_username', 1);

    if ($buddystream_flickr_username) {
      do_action('buddystream_flickr_activated');
    }
?>

   <?php if(buddystreamCheckNetwork("http://www.facebook.com")) { ?>
    <form id="settings_form" action="<?php echo  $bp->loggedin_user->domain; ?>settings/buddystream-flickr/" method="post">
        <h3><?php _e('Flickr Settings', 'buddystream_flickr')?></h3>
        <?php _e('Flickr username', 'buddystream_flickr');?><br/>
        <input type="text" name="buddystream_flickr_username" value="<?php echo $buddystream_flickr_username; ?>" size="50" /><br/><br/>
       <input type="submit" value="<?php echo __('Save settings', 'buddystream_flickr');?>">
    </form>
   <?php
       }else{
          _e('Flickr is currently offline please come back in a while.', 'buddystream_flickr');
       } 
   ?>