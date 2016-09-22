<?php

if ($_GET['reset'] == 'true') {
    delete_user_meta($bp->loggedin_user->id, 'soundcloud_access_token');
    delete_user_meta($bp->loggedin_user->id, 'soundcloud_expires_in');
    delete_user_meta($bp->loggedin_user->id, 'soundcloud_refresh_token');
    delete_user_meta($bp->loggedin_user->id, 'soundcloud_id');
    delete_user_meta($bp->loggedin_user->id, 'soundcloud_permalink');
    //for other plugins
    do_action('buddystream_soundcloud_deleted');
}

if (!get_user_meta($bp->loggedin_user->id, 'soundcloud_access_token', 1)) {

    echo '<h3>' . __('Soundcloud setup</h3>
             You may setup you Soundcloud intergration over here.<br>
             Before you can begin using Soundcloud with this site you must authorize on Soundcloud by clicking the link below.', 'buddystream_soundcloud') . '<br><br>';

    $redirectUrl = 'https://soundcloud.com/connect/?client_id=' . get_site_option("soundcloud_client_id") . '&redirect_uri=' . $bp->root_domain . '/?buddystream_auth=soundcloud&scope=non-expiring&response_type=code';
    echo '<a href="' . $redirectUrl . '" class="buddystream_authorize_button">' . __('Click here to start authorization', 'buddystream_soundcloud') . '</a><br/><br/>';

} else {

    echo '<h3>' . __('Soundcloud setup', 'buddystream_soundcloud') . '</h3>
                   ' . __('You are succefully connected to Soundcloud!', 'buddystream_soundcloud') . '<br/><br/>';

    if (get_user_meta($bp->loggedin_user->id, 'soundcloud_access_token', 1)) {
        echo '<a href="?network=soundcloud&reset=true" class="buddystream_reset_button">' . __('Remove Soundcloud synchronization.', 'buddystream_soundcloud') . '</a>';
    }
}