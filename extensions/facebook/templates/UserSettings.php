<?php

global $bp;


if ($_GET['getfriends'] == 'true') {


    //Handle the OAuth requests
    $buddyStreamOAuth = new BuddyStreamOAuth();
    $buddyStreamOAuth->setParameters(
        array('client_id' => get_site_option("facestream_application_id"),
            'client_secret' => get_site_option("facestream_application_secret"),
            'access_token' => str_replace("&expires", "", get_user_meta(1, 'facestream_session_key', 1))));

    $friends = $buddyStreamOAuth->oAuthRequest('https://graph.facebook.com/me/friends');
    $friends = json_decode($friends);

//    echo "<pre>";
//    var_dump($friends);


    $parameters = array(
        'app_id' => $facebook->getAppId(),
        'to' => $facebookUserId,
        'link' => 'http://google.nl/',
        'redirect_uri' => 'http://my.app.url/callback'
    );
    $url = 'http://www.facebook.com/dialog/send?'.http_build_query($parameters);


    echo "URL: ".$url;




}


//reset
if ($_GET['reset'] == 'true') {
    delete_user_meta($bp->loggedin_user->id, 'facestream_session_key');
    delete_user_meta($bp->loggedin_user->id, 'facestream_synctoac');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_facebook_syncpage');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_facebook_syncalbum');
    delete_user_meta($bp->loggedin_user->id, 'facestream_synctoac');
    delete_user_meta($bp->loggedin_user->id, 'facestream_filtermentions');
    delete_user_meta($bp->loggedin_user->id, 'facestream_user_id');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_facebook_privacy_friends');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_facebook_pages');
    delete_user_meta($bp->loggedin_user->id, 'buddystream_facebook_albums');
	
	//for other plugins
    do_action('buddystream_facebook_deleted');
}

//back from facebook with code
if (isset($_GET['code'])) {

    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setAccessTokenUrl('https://graph.facebook.com/oauth/access_token');
    $buddystreamOAuth->setCallbackUrl($bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-facebook');
    $buddystreamOAuth->setParameters(
        array(
            'redirect_uri' => $bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=facebook',
            'client_id' => get_site_option("facestream_application_id"),
            'client_secret' => get_site_option("facestream_application_secret"),
            'code' => $_GET['code']));

    //get accesstoken and save it
    $accessToken = $buddystreamOAuth->accessToken(true);

    //create tokenarray from output
    $tokenArray = explode("=",$accessToken);

    update_user_meta($bp->loggedin_user->id, 'facestream_session_key', str_replace("&expires", "", $tokenArray[1]));
    update_user_meta($bp->loggedin_user->id, 'facestream_synctoac', 1);
    update_user_meta($bp->loggedin_user->id, 'buddystream_facebook_syncpage', 1);
    update_user_meta($bp->loggedin_user->id, 'buddystream_facebook_syncalbum', 1);
    delete_user_meta($bp->loggedin_user->id, "buddystream_facebook_reauth", true);

    //for other plugins
    do_action('buddystream_facebook_activated');
}


//save user settings
if ($_POST['submit']) {

    //wall
    update_user_meta($bp->loggedin_user->id, 'facestream_synctoac', $_POST['facestream_synctoac']);
    update_user_meta($bp->loggedin_user->id, 'buddystream_facebook_syncpage', $_POST['buddystream_facebook_syncpage']);
    update_user_meta($bp->loggedin_user->id, 'buddystream_facebook_syncalbum', $_POST['buddystream_facebook_syncalbum']);
    update_user_meta($bp->loggedin_user->id, 'buddystream_facebook_privacy_friends', $_POST['buddystream_facebook_privacy_friends']);

    //check if we have pages selection is posted
    if ($_POST['buddystream_facebook_pages']) {
        delete_user_meta($bp->loggedin_user->id, 'buddystream_facebook_pages');
        update_user_meta($bp->loggedin_user->id, 'buddystream_facebook_pages', implode(',', $_POST['buddystream_facebook_pages']));
    }

    //check if we have albums selection is posted
    if ($_POST['buddystream_facebook_albums']) {
        delete_user_meta($bp->loggedin_user->id, 'buddystream_facebook_albums');
        update_user_meta($bp->loggedin_user->id, 'buddystream_facebook_albums', implode(',', $_POST['buddystream_facebook_albums']));
    }

    //achievement plugin
    update_user_meta($bp->loggedin_user->id, 'facestream_achievements', $_POST['facestream_achievements']);

    $message= "Settings saved";
}


//put some options into variables
$facestream_synctoac = get_user_meta($bp->loggedin_user->id, 'facestream_synctoac', 1);
$buddystream_facebook_syncpage = get_user_meta($bp->loggedin_user->id, 'buddystream_facebook_syncpage', 1);
$buddystream_facebook_syncalbum = get_user_meta($bp->loggedin_user->id, 'buddystream_facebook_syncalbum', 1);
$buddystream_facebook_privacy_friends = get_user_meta($bp->loggedin_user->id, 'buddystream_facebook_privacy_friends', 1);

//achievement plugin
$facestream_achievements = get_user_meta($bp->loggedin_user->id, 'facestream_achievements', 1);


//we have a authorisation
if (get_user_meta($bp->loggedin_user->id, 'facestream_session_key', 1) && ! get_user_meta($bp->loggedin_user->id, 'buddystream_facebook_reauth', 1)) {

    echo '<form id="settings_form" action="' . $bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=facebook" method="post">';


    if( ! get_site_option("buddystream_facebook_import") ){
        _e('There are no settings available.</br></br>', 'buddystream_facebook');
?>

        <?php if (get_user_meta($bp->loggedin_user->id, 'facestream_session_key', 1)): ?>
            <a href="?network=facebook&reset=true"
               class="buddystream_reset_button"><?php echo __('Remove Facebook synchronization.', 'buddystream_facebook');?></a>
        <?php endif; ?>

        <?php
    } else {

        ?>
        <?php if ($message): ?>
            <div class="alert alert-success"><?php echo $message; ?></div>
        <?php endif; ?>


        <?php if (get_site_option("buddystream_facebookWall_power") && get_site_option("buddystream_facebookWall_import")): ?>

            <h3><?php _e('Facebook Wall', 'buddystream_facebook'); ?></h3>

            <table class="table table-striped" cellspacing="0">
                <thead>
                    <tr>
                        <th><?php _e('Synchronize my Facebook wall to my activity stream:', 'buddystream_facebook'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="radio" name="facestream_synctoac" value="1" <?php if ($facestream_synctoac == 1) { echo 'checked';} ?> />
                             <?php _e('Yes', 'buddystream_facebook');?>

                            <input type="radio" name="facestream_synctoac" value="0" <?php if ($facestream_synctoac == 0) { echo 'checked'; } ?> />
                            <?php _e('No', 'buddystream_facebook');?>
                        </td>
                    </tr>
                </tbody>
            </table>

            <table class="table table-striped" cellspacing="0">
                <thead>
                    <tr>
                        <th><?php _e('Import also items with privacy settings "Friends only":', 'buddystream_facebook'); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="radio" name="buddystream_facebook_privacy_friends" value="1" <?php if ($buddystream_facebook_privacy_friends == 1) { echo 'checked'; } ?> />
                            <?php _e('Yes', 'buddystream_facebook');?>

                            <input type="radio" name="buddystream_facebook_privacy_friends" value="0" <?php if ($buddystream_facebook_privacy_friends == 0) { echo 'checked';  } ?> />
                            <?php _e('No', 'buddystream_facebook');?>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php endif; ?>


        <?php if (get_site_option('buddystream_facebookPages_import') && get_site_option("buddystream_facebookPages_power")){
            if(get_user_meta($bp->loggedin_user->id, 'buddystream_facebook_syncpage', 1)){ ?>

                <?php echo'<h3>' . __('Facebook page(s)', 'buddystream_facebook') . '</h3>'; ?>
                <table class="table table-striped" cellspacing="0">
                    <thead>
                        <tr>
                            <th><?php _e('Synchronize my Facebook Page(s) to my activity stream:', 'buddystream_facebook'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>
                                <input type="radio" name="buddystream_facebook_syncpage"
                                        value="1" <?php if ($buddystream_facebook_syncpage == 1) {
                                    echo 'checked';
                                } ?> />
                                <?php _e('Yes', 'buddystream_facebook');?>

                                <input type="radio" name="buddystream_facebook_syncpage"
                                       value="0" <?php if ($buddystream_facebook_syncpage == 0) {
                                    echo 'checked';
                                } ?> />
                                <?php _e('No', 'buddystream_facebook');?></td>
                        </tr>
                    </tbody>
                </table>

                <?php _e('facebook pages description', 'buddystream_facebook'); ?><br/><br/>

                <table class="table table-striped" cellspacing="0">
                    <tbody>
                        <?php
                        //Handle the OAuth requests
                        $buddystreamOAuth = new BuddyStreamOAuth();
                        $buddystreamOAuth->setParameters(
                            array('client_id' => get_site_option("facestream_application_id"),
                                'client_secret' => get_site_option("facestream_application_secret"),
                                'access_token' => get_user_meta($bp->loggedin_user->id, 'facestream_session_key', 1)));

                        $items = $buddystreamOAuth->oAuthRequest('https://graph.facebook.com/me/accounts');
                        $items = json_decode($items);

                        //get saved pages
                        $savedFacebookPages = get_user_meta($bp->loggedin_user->id, 'buddystream_facebook_pages', 1);
                        $savedFacebookPages = explode(',', $savedFacebookPages);
												
						$idsOfPages = array();
						foreach ($savedFacebookPages as $value) {
							$idPage = explode(':', $value);
							array_push($idsOfPages, $idPage[0]);
						}

                        if ($items->data) {
                            foreach ($items->data as $page) {								
                                if ($page->category != "Application") {

                                    $checked = "";
                                    if (in_array($page->id, $idsOfPages)) {
                                        $checked = "checked";
									
                                    }

                                    echo '<tr><td><input type="checkbox" name="buddystream_facebook_pages[]" value="' . $page->id . ':' . $page->access_token . '" ' . $checked . '/> ' . $page->name . '</td></tr>';
                                }
                            }
                        }
                        ?>
                    </tbody>
                </table>

                <?php
            }
        }
        ?>


        <?php if (get_site_option('buddystream_facebookAlbums_import') && get_site_option("buddystream_facebookAlbums_power")) { ?>

            <?php if (get_user_meta($bp->loggedin_user->id, 'buddystream_facebook_syncalbum', 1)) { ?>

                <?php echo'<h3>' . __('Facebook Album(s) Settings', 'buddystream_facebook') . '</h3>'; ?>

                <table class="table table-striped" cellspacing="0">
                    <thead>
                    <tr>
                        <th><?php _e('Synchronize my Facebook albums photos to my activity stream:', 'buddystream_facebook'); ?></th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr>
                        <td>
                            <input type="radio" name="buddystream_facebook_syncalbum"
                                   value="1" <?php if ($buddystream_facebook_syncalbum == 1) {
                                echo 'checked';
                            } ?> />
                            <?php _e('Yes', 'buddystream_facebook');?>

                            <input type="radio" name="buddystream_facebook_syncalbum"
                                   value="0" <?php if ($buddystream_facebook_syncalbum == 0) {
                                echo 'checked';
                            } ?> />
                            <?php _e('No', 'buddystream_facebook');?>
                        </td>
                    </tr>
                    </tbody>
                </table>

            <?php _e('facebook albums description', 'buddystream_facebook'); ?><br/><br/>

                <table class="table table-striped" cellspacing="0">
                    <tbody>

                        <?php

                        //Handle the OAuth requests
                        $buddystreamOAuth = new BuddyStreamOAuth();
                        $buddystreamOAuth->setParameters(
                            array('client_id' => get_site_option("facestream_application_id"),
                                'client_secret' => get_site_option("facestream_application_secret"),
                                'access_token' => get_user_meta($bp->loggedin_user->id, 'facestream_session_key', 1)));

                        $photoAlbums = $buddystreamOAuth->oAuthRequest('https://graph.facebook.com/me/albums');
                        $photoAlbums = json_decode($photoAlbums);

                        //get saved pages
                        $savedFacebookAlbums = get_user_meta($bp->loggedin_user->id, 'buddystream_facebook_albums', 1);
                        $savedFacebookAlbums = explode(',', $savedFacebookAlbums);

                        if($photoAlbums->data){
                            foreach ($photoAlbums->data as $album) {
                                $checked = "";
                                if (in_array($album->id, $savedFacebookAlbums)) {
                                    $checked = "checked";
                                }

                                echo'<tr><td><input type="checkbox" name="buddystream_facebook_albums[]" value="' . $album->id . '" ' . $checked . '/> ' . $album->name . '</td></tr>';
                            }
                        }
                        ?>
                        </tbody>
                </table>

                <?php
            }
        }
        ?>

        <?php if (defined('ACHIEVEMENTS_IS_INSTALLED')) { ?>
            <table class="table table-striped" cellspacing="0">
                <thead>
                    <tr>
                        <th><?php _e('Send achievements unlock to my facebook', 'buddystream_facebook');?></th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>
                            <input type="radio" name="facestream_achievements"
                                   value="1" <?php if (get_user_meta($bp->loggedin_user->id, 'facestream_achievements', 1) == 1) {
                                echo'checked';
                            }?>> <?php _e('Yes', 'buddystream_facebook');?>
                            <input type="radio" name="facestream_achievements"
                                   value="0" <?php if (get_user_meta($bp->loggedin_user->id, 'facestream_achievements', 1) == 0) {
                                echo'checked';
                            }?>> <?php _e('No', 'buddystream_facebook');?>
                        </td>
                    </tr>
                </tbody>
            </table>
        <?php } ?>

        <br/>
        <input type="submit" name="submit" class="buddystream_save_button"
               value="<?php echo __('Save settings', 'buddystream_facebook');?>">

        <?php if (get_user_meta($bp->loggedin_user->id, 'facestream_session_key', 1)): ?>
            <a href="?network=facebook&reset=true"
               class="buddystream_reset_button"><?php echo __('Remove Facebook synchronization.', 'buddystream_facebook');?></a>
        <?php endif; ?>

    <?php } ?>



    </form>

<?php

} else {

    echo '<h3>' . __('Facebook setup', 'buddystream_facebook') . '</h3>';
    _e('facebook user persmission description', 'buddystream_facebook');
    echo '<br/><br/>';

    $redirectUrl = 'https://www.facebook.com/dialog/oauth?client_id=' . get_site_option("facestream_application_id") . '&redirect_uri=' . urlencode($bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=facebook') . '&scope=publish_actions,manage_pages,user_photos,user_posts,user_about_me,public_profile,email';
    echo '<a href="' . $redirectUrl . '" class="buddystream_authorize_button">' . __('Authorize with Facebook', 'buddystream_facebook') . '</a><br/><br/>';
}