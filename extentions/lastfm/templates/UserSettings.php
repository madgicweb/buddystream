<?php
if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'bs_lastfm_username', $_POST['bs_lastfm_username']);
    echo '<div id="message" class="updated fade">
            <p>' . __('Settings saved', 'buddystream_lastfm') . '</p>
        </div>';
    }

    $bs_lastfm_username = get_user_meta($bp->loggedin_user->id, 'bs_lastfm_username',1);
    if ($bs_lastfm_username) {
      do_action('buddystream_lastfm_activated');
    }
?>

    <form id="settings_form" action="<?php echo  $bp->loggedin_user->domain.BP_SETTINGS_SLUG; ?>/buddystream-lastfm/" method="post">
        <h3><?php echo __('Last.fm Settings', 'buddystream_lastfm')?></h3>
        <?php echo __('Last.fm username', 'buddystream_lastfm');?><br/>
        <input type="text" name="bs_lastfm_username" value="<?php echo $bs_lastfm_username; ?>" size="50" /><br/><br/>
       <input type="submit" value="<?php echo __('Save settings', 'buddystream_lastfm');?>">
    </form>