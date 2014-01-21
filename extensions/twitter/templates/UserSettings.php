<?php

global $bp;

if ($_GET['reset'] == 'true') {
    delete_user_meta($bp->loggedin_user->id, 'tweetstream_token');
    delete_user_meta($bp->loggedin_user->id, 'tweetstream_tokensecret');
    delete_user_meta($bp->loggedin_user->id, 'tweetstream_tokensecret_temp');
    delete_user_meta($bp->loggedin_user->id, 'tweetstream_token_temp');
    delete_user_meta($bp->loggedin_user->id, 'tweetstream_mention');
    delete_user_meta($bp->loggedin_user->id, 'tweetstream_synctoac');
    delete_user_meta($bp->loggedin_user->id, 'tweetstream_synctoac');
    delete_user_meta($bp->loggedin_user->id, 'tweetstream_filtermentions');
}

if (isset($_GET['oauth_token'])) {



    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setAccessTokenUrl('https://api.twitter.com/oauth/access_token');
    $buddystreamOAuth->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=twitter');

    $buddystreamOAuth->setRequestToken(get_user_meta($bp->loggedin_user->id, 'tweetstream_token_temp', 1));
    $buddystreamOAuth->setRequestTokenSecret(get_user_meta($bp->loggedin_user->id, 'tweetstream_token_secret_temp', 1));

    $buddystreamOAuth->setConsumerKey(get_site_option("tweetstream_consumer_key"));
    $buddystreamOAuth->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));

    $buddystreamOAuth->setParameters(
        array(
          'oauth_verifier' => $_GET['oauth_verifier']
        )
    );

    //get accesstoken and save it
    $accessToken = $buddystreamOAuth->accessToken(true);

    //create tokenarray from output
    $outputArray = explode("&",$accessToken);
    $tokenArray = explode("=",$outputArray[0]);
    $tokenSecretArray = explode("=",$outputArray[1]);

    update_user_meta($bp->loggedin_user->id, 'tweetstream_token', '' . $tokenArray[1] . '');
    update_user_meta($bp->loggedin_user->id, 'tweetstream_tokensecret', '' .  $tokenSecretArray[1] . '');
    update_user_meta($bp->loggedin_user->id, 'tweetstream_synctoac', 1);
    delete_user_meta($bp->loggedin_user->id, "buddystream_twitter_reauth", true);
    //for other plugins
    do_action('buddystream_twitter_activated');

}

if ($_POST) {
    update_user_meta($bp->loggedin_user->id, 'tweetstream_synctoac', $_POST['tweetstream_synctoac']);
    update_user_meta($bp->loggedin_user->id, 'tweetstream_filtermentions', $_POST['tweetstream_filtermentions']);

    //achievements plugins
    update_user_meta($bp->loggedin_user->id, 'tweetstream_achievements', $_POST['tweetstream_achievements']);

    $message = __('Settings saved', 'buddystream_twitter');
}

//put some options into variables
$tweetstream_synctoac = get_user_meta($bp->loggedin_user->id, 'tweetstream_synctoac', 1);
$tweetstream_filtermentions = get_user_meta($bp->loggedin_user->id, 'tweetstream_filtermentions', 1);

//achievements plugin
$tweetstream_achievements = get_user_meta($bp->loggedin_user->id, 'tweetstream_achievements', 1);

if (get_user_meta($bp->loggedin_user->id, 'tweetstream_token', 1) && ! get_user_meta($bp->loggedin_user->id, 'buddystream_twitter_reauth', 1)) {
    echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . 'settings/buddystream-networks/?network=twitter" method="post">
        <h3>' . __('Twitter Settings', 'buddystream_twitter') . '</h3>';
    ?>

    <?php if ($message): ?>
        <div class="alert alert-success"><?php echo $message; ?></div>
    <?php endif; ?>

    <?php if (!get_site_option('buddystream_twitter_import')) {
        _e('There are no settings available.</br></br>', 'buddystream_twitter');
    } else {
        ?>


        <table class="table table-striped" cellspacing="0">
            <thead>
            <tr>
                <th><?php echo __('Synchronize Tweets to my activity stream?', 'buddystream_twitter'); ?></th>
            </tr>
            </thead>
            <tbody>
            <tr>
                <td>
                    <input type="radio" name="tweetstream_synctoac"
                           value="1" <?php if ($tweetstream_synctoac == 1) {
                        echo 'checked';
                    } ?> />
                   <?php echo __('Yes', 'buddystream_twitter'); ?>

                    <input type="radio" name="tweetstream_synctoac"
                           value="0" <?php if ($tweetstream_synctoac == 0) {
                        echo 'checked';
                    } ?> />
                    <?php echo __('No', 'buddystream_twitter'); ?>
                </td>
            </tr>
            </tbody>
        </table>

        <?php if (defined('ACHIEVEMENTS_IS_INSTALLED')) { ?>

            <table class="table table-striped" cellspacing="0">
                <thead>
                <tr>
                    <th><?php echo __('Send achievements unlock to my twitter', 'buddystream_twitter');?></th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td>
                        <input type="radio" name="tweetstream_achievements" id="tweetstream_achievements"
                               value="1" <?php if ($tweetstream_achievements == 1) {
                            echo'checked';
                        }?>> <?php echo __('Yes', 'buddsytream_lang'); ?>
                        <input type="radio" name="tweetstream_achievements" id="tweetstream_achievements"
                               value="0" <?php if ($tweetstream_achievements == 0) {
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

    <?php if (get_user_meta($bp->loggedin_user->id, 'tweetstream_token', 1)): ?>
        <a href="?network=twitter&reset=true"
           class="buddystream_reset_button"><?php echo __('Remove Twitter synchronization.', 'buddystream_facebook');?></a>
    <?php endif; ?>
    </form>

<?php
} else {

    echo '<h3>' . __('Twitter setup</h3>
                 You may setup you twitter intergration over here.<br/>
                 Before you can begin using Twitter with this site you must authorize on Twitter by clicking the link below.', 'buddystream_twitter') . '<br/><br/>';

    //oauth
    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setRequestTokenUrl('https://api.twitter.com/oauth/request_token');
    $buddystreamOAuth->setAccessTokenUrl('https://api.twitter.com/oauth/access_token');
    $buddystreamOAuth->setAuthorizeUrl('https://api.twitter.com/oauth/authorize');

    $buddystreamOAuth->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=twitter');
    $buddystreamOAuth->setConsumerKey(get_site_option("tweetstream_consumer_key"));
    $buddystreamOAuth->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));

    //get request token and save it for later use.
    $requestToken = $buddystreamOAuth->requestToken();
    $buddystreamOAuth->setRequestToken($requestToken['oauth_token']);
    $buddystreamOAuth->setRequestTokenSecret($requestToken['oauth_token_secret']);

    update_user_meta($bp->loggedin_user->id, 'tweetstream_token_temp', '' . $requestToken['oauth_token'] . '');
    update_user_meta($bp->loggedin_user->id, 'tweetstream_token_secret_temp', '' . $requestToken['oauth_token_secret'] . '');

    //get the redirect url for the user
    $redirectUrl = $buddystreamOAuth->getRedirectUrl();
    if ($redirectUrl) {
        echo '<a href="' . $redirectUrl . '" class="buddystream_authorize_button">' . __('Click here to start authorization', 'buddystream_twitter') . '</a><br/><br/>';
    } else {
        _e('There is a problem with the authentication service at this moment please come back in a while.', 'buddystream_twitter');
    }
}