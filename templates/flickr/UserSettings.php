<?php
if ($_POST) {
    update_usermeta($bp->loggedin_user->id, 'bs_flickr_username', $_POST['bs_flickr_username']);
    echo '<div id="message" class="updated fade">
            <p>' . __('Settings saved', 'buddystream_lang') . '</p>
        </div>';
    }

    $bs_flickr_username = get_usermeta($bp->loggedin_user->id, 'bs_flickr_username');

    if ($bs_flickr_username) {
      do_action('buddystream_flickr_activated');
    }
?>

    <form id="settings_form" action="<?php echo  $bp->loggedin_user->domain; ?>settings/buddystream-flickr/" method="post">
        <h3><?php echo __('Flickr Settings', 'buddystream_lang')?></h3>
        <?php echo __('Flickr username', 'buddystream_lang');?><br/>
        <input type="text" name="bs_flickr_username" value="<?php echo $bs_flickr_username; ?>" size="50" /><br/><br/>
       <input type="submit" value="<?php echo __('Save settings', 'buddystream_lang');?>">
    </form>