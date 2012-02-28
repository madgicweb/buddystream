<?php

if(isset($_GET['reset'])){
    delete_user_meta($bp->loggedin_user->id, 'bs_flickr_username');
}


if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'bs_flickr_username', $_POST['bs_flickr_username']);
    echo '<div class="buddystream_message">
            ' . __('Settings saved', 'buddystream_lang') . '
        </div>';
    }

    $bs_flickr_username = get_user_meta($bp->loggedin_user->id, 'bs_flickr_username', 1);

    if ($buddystream_flickr_username) {
      do_action('buddystream_flickr_activated');
    }
?>

    <form id="settings_form" action="<?php echo  $bp->loggedin_user->domain . BP_SETTINGS_SLUG; ?>/buddystream-networks/?network=flickr" method="post">
        <h3><?php _e('Flickr Settings', 'buddystream_flickr')?></h3>
        <?php _e('Flickr username', 'buddystream_flickr');?><br/>
        <input type="text" name="bs_flickr_username" value="<?php echo $bs_flickr_username; ?>" size="50" /><br/><br/>
         <input type="submit" class="buddystream_save_button" value="<?php echo __('Save settings', 'buddystream_lang');?>">
        
        <?php if($bs_flickr_username != ""): ?>
            <a href="?network=flickr&reset=true" class="buddystream_reset_button"><?php echo __('Remove Flickr synchronization.','buddystream_facebook');?></a> 
        <?php endif; ?>
    </form>