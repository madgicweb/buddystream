<?php



/**
 * When we come back from instagram
 */

if (isset($_GET['code']) && isset($_GET['network']) && $_GET['network'] == "instagram") {


    global $bp;

    $buddystreamCurl = new BuddyStreamCurl();

    $postData = array(
        'grant_type' => 'authorization_code',
        'code' => $_GET['code'],
        'redirect_uri' => site_url().'/?network=instagram',
        'client_id' => get_site_option("buddystream_instagram_consumer_key"),
        'client_secret' => get_site_option("buddystream_instagram_consumer_secret")
    );

    $accessToken = $buddystreamCurl->getPostContentFromUrl('https://api.instagram.com/oauth/access_token',$postData);
    $accessToken = json_decode($accessToken);

    update_user_meta($bp->loggedin_user->id, 'buddystream_instagram_token', $accessToken->access_token);
    update_user_meta($bp->loggedin_user->id, 'buddystream_instagram_synctoac', 1);
    update_user_meta($bp->loggedin_user->id, 'buddystream_instagram_id', $accessToken->user->id);

    //for other plugins
    do_action('buddystream_instagram_activated');


    @header("location:".$bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=instagram');
    die;

}



/**
 *
 * Page loader functions
 *
 */

function buddystream_instagram()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('Instagram');
}


/**
 * Get all users with integration
 * @return mixed
 */
function buddystreamInstagramUsers(){

    global $wpdb;
    return $wpdb->get_results("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='buddystream_instagram_token';");
}


/**
 * Count imported items for  user
 * @param $user_id
 * @return int
 */
function buddystreamInstagramCountItems($user_id){

    global $wpdb,$bp;
    return count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=".$user_id." AND type='instagram';"));
}


/**
 * Import on for user
 * @param $user_id
 * @return mixed
 */
function buddystreamInstagramImportOn($user_id){
    return get_user_meta($user_id, 'buddystream_instagram_synctoac', 1);
}


/**
 * Reset user
 * @param $user_id
 * @return int
 */
function buddystreamInstagramResetUser($user_id){
    delete_user_meta($user_id, 'buddystream_instagram_token');
    delete_user_meta($user_id, 'buddystream_instagram_tokensecret');
    delete_user_meta($user_id, 'buddystream_instagram_tokensecret_temp');
    delete_user_meta($user_id, 'buddystream_instagram_token_temp');
    delete_user_meta($user_id, 'buddystream_instagram_synctoac');
}