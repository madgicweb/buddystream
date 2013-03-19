<?php


if (isset($_GET['reset'])) {
    delete_user_meta($bp->loggedin_user->id, 'bs_youtube_username');
}

if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'bs_youtube_username', $_POST['bs_youtube_username']);
    $message =  __('Settings saved', 'buddystream_lang');
}

$bs_youtube_username = get_user_meta($bp->loggedin_user->id, 'bs_youtube_username', 1);
if ($bs_youtube_username) {
    do_action('buddystream_youtube_activated');
}
?>

<form id="settings_form"
      action="<?php echo  $bp->loggedin_user->domain . BP_SETTINGS_SLUG; ?>/buddystream-networks/?network=youtube"
      method="post">

    <h3><?php echo __('Youtube Settings', 'buddystream_lang')?></h3>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <table class="table table-striped" cellspacing="0">
        <thead>
        <tr>
            <th> <?php echo __('Youtube username', 'buddystream_lang');?></th>
        </tr>
        </thead>
        <tbody>
        <tr>
            <td>
                <input type="text" name="bs_youtube_username" value="<?php echo $bs_youtube_username; ?>" size="50"/><br/><br/>
            </td>
        </tr>
        </tbody>
    </table>

    <input type="submit" class="buddystream_save_button" value="<?php echo __('Save settings', 'buddystream_lang');?>">

    <?php if ($bs_youtube_username != ""): ?>
        <a href="?network=youtube&reset=true"
           class="buddystream_reset_button"><?php echo __('Remove Youtube synchronization.', 'buddystream_facebook');?></a>
    <?php endif; ?>
</form>