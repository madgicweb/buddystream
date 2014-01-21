<?php

global $bp;

if ($_GET['reset'] == 'true') {
    delete_user_meta($bp->loggedin_user->id, 'buddystream_instagram_token');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_instagram_tokensecret');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_instagram_tokensecret_temp');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_instagram_token_temp');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_instagram_synctoac');
}


if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'buddystream_instagram_synctoac', $_POST['buddystream_instagram_synctoac']);
 
    //achievements plugins
    update_user_meta($bp->loggedin_user->id, 'buddystream_instagram_achievements', $_POST['buddystream_instagram_achievements']);

    $message = __('Settings saved', 'buddystream_instagram');
}

//put some options into variables
$buddystream_instagram_synctoac = get_user_meta($bp->loggedin_user->id, 'buddystream_instagram_synctoac', 1);

//achievements plugin
$buddystream_instagram_achievements = get_user_meta($bp->loggedin_user->id, 'buddystream_instagram_achievements', 1);

if (get_user_meta($bp->loggedin_user->id, 'buddystream_instagram_token', 1)) {
    echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'settings/buddystream-networks/?network=instagram" method="post">
        <h3>' . __('Instagram Settings', 'buddystream_instagram') . '</h3>';
    ?>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!get_site_option('buddystream_instagram_import')) {
        _e('There are no settings available.</br></br>', 'buddystream_instagram');
    } else {
        ?>

        <table class="table table-striped" cellspacing="0">
            <thead>
            <tr>
                <th><?php echo __('Synchronize Instagram to my activity stream?', 'buddystream_instagram'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="radio" name="buddystream_instagram_synctoac"
                           value="1" <?php if ($buddystream_instagram_synctoac == 1) {
                        echo 'checked';
                    } ?> />
                   <?php echo __('Yes', 'buddystream_instagram'); ?>

                    <input type="radio" name="buddystream_instagram_synctoac"
                           value="0" <?php if ($buddystream_instagram_synctoac == 0) {
                        echo 'checked';
                    } ?> />
                    <?php echo __('No', 'buddystream_instagram'); ?>
                </td>
            </tr>
            </tbody>
        </table>

        <?php if (defined('ACHIEVEMENTS_IS_INSTALLED')) { ?>
            <table class="table table-striped" cellspacing="0">
                <thead>
                <tr>
                    <th><?php echo __('Send achievements unlock to my instagram', 'buddystream_instagram');?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <input type="radio" name="buddystream_instagram_achievements" id="buddystream_instagram_achievements"
                               value="1" <?php if ($buddystream_instagram_achievements == 1) {
                            echo'checked';
                        }?>> <?php echo __('Yes', 'buddsytream_lang'); ?>
                        <input type="radio" name="buddystream_instagram_achievements" id="buddystream_instagram_achievements"
                               value="0" <?php if ($buddystream_instagram_achievements == 0) {
                            echo'checked';
                        }?>> <?php echo __('No', 'buddsytream_lang'); ?>
                    </td>
                </tr>
                </tbody>
            </table>
        <?php } ?>

    <?php } ?>

    <br/><br/>

    <input type="submit" class="buddystream_save_button" value="<?php echo __('Save settings', 'buddystream_lang');?>">

    <?php if (get_user_meta($bp->loggedin_user->id, 'buddystream_instagram_token', 1)): ?>
        <a href="?network=instagram&reset=true"
           class="buddystream_reset_button"><?php echo __('Remove Instagram synchronization.', 'buddystream_facebook');?></a>
    <?php endif; ?>
    </form>

<?php
} else {

    echo '<h3>' . __('Instagram setup</h3>
                 You may setup you Instagram intergration over here.<br/>
                 Before you can begin using Instagram with this site you must authorize on Instagram by clicking the link below.', 'buddystream_instagram') . '<br/><br/>';

    //get the redirect url for the user
    $redirectUrl = "https://api.instagram.com/oauth/authorize/?client_id=".get_site_option("buddystream_instagram_consumer_key")."&response_type=code&redirect_uri=".site_url()."/?network=instagram";

    if ($redirectUrl) {
        echo '<a href="' . $redirectUrl . '" class="buddystream_authorize_button">' . __('Click here to start authorization', 'buddystream_instagram') . '</a><br/><br/>';
    } else {
        _e('There is a problem with the authentication service at this moment please come back in a while.', 'buddystream_instagram');
    }
}