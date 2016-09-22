<?php

/**
 * Authorization for Soundcloud
 */

include("lib/soundcloud.php");







add_action('bp_init', 'buddystream_soundcloudCode', 1);
function buddystream_soundcloudCode()
{
    global $bp;

    $buddyStreamLog = new BuddyStreamLog();

    if (isset($_GET['buddystream_auth']) && $_GET['buddystream_auth'] == 'soundcloud') {

        $soundcloud = new BuddystreamSoundcloud(
            get_site_option("soundcloud_client_id"),
            get_site_option("soundcloud_client_secret"),
            $bp->root_domain . "/?buddystream_auth=soundcloud"
        );

        try {
            $soundcloudToken = $soundcloud->accessToken($_GET['code'],array(),array(CURLOPT_SSL_VERIFYPEER => false,CURLOPT_SSL_VERIFYHOST => false,));
        } catch (Services_Soundcloud_Invalid_Http_Response_Code_Exception $e) {
            $buddyStreamLog->log("Soundcloud : " . $e->getMessage(), 'error');
        }

        if ($soundcloudToken['access_token']) {
            update_user_meta($bp->loggedin_user->id, 'soundcloud_access_token', $soundcloudToken['access_token']);
            update_user_meta($bp->loggedin_user->id, 'soundcloud_expires_in', $soundcloudToken['expires_in'] + time());
            update_user_meta($bp->loggedin_user->id, 'soundcloud_refresh_token', $soundcloudToken['refresh_token']);
        }

        $user = json_decode($soundcloud->get('me',array(),array(CURLOPT_SSL_VERIFYPEER => false,CURLOPT_SSL_VERIFYHOST => false)), true);
        update_user_meta($bp->loggedin_user->id, 'soundcloud_id', $user['id']);
        update_user_meta($bp->loggedin_user->id, 'soundcloud_permalink', $user['permalink']);
        
        //for other plugins
        do_action('buddystream_soundcloud_activated');        
        
        wp_redirect($bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=soundcloud');
        exit;
    }
}

/**
 * Replace all track url into real players
 */

add_filter('bp_get_activity_content', 'BuddystreamSoundcloudPlayers', 9);
add_filter('bp_get_activity_content_body', 'BuddystreamSoundcloudPlayers', 9);
function BuddystreamSoundcloudPlayers($text)
{

    if (strpos($text, 'api.soundcloud.com') > 0) {
        $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
        if (preg_match($reg_exUrl, $text, $url)) {

            $text = strip_tags($text);
            $text = preg_replace($reg_exUrl, '<embed allowscriptaccess="always" height="81" src=http://player.soundcloud.com/player.swf?url=' . strip_tags(str_replace('"', '', $url[0])) . '&enable_api=true&object_id=myPlayer" type="application/x-shockwave-flash" width="100%" name="myPlayer"></embed>', $text);
        }
    }

    return $text;
}


/**
 * Get all users with integration
 * @return mixed
 */
function buddystreamSoundcloudUsers(){

    global $wpdb;
    return $wpdb->get_results("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='soundcloud_access_token';");
}


/**
 * Count imported items for user
 * @param $user_id
 * @return int
 */
function buddystreamSoundcloudCountItems($user_id){

    global $wpdb,$bp;
    return count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=".$user_id." AND type='soundcloud';"));
}


/**
 * Import on for user
 * @param $user_id
 * @return mixed
 */
function buddystreamSoundcloudImportOn(){
    return true;
}


/**
 * Reset user
 * @param $user_id
 * @return int
 */
function buddystreamSoundcloudResetUser($user_id){
    delete_user_meta($user_id, "soundcloud_access_token");
}


/**
 *
 * Page loader functions
 *
 */
function buddystream_soundcloud()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('soundcloud');
}