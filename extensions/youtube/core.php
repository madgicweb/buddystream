<?php

/**
 * Replace all embed urls into new embed urls (old content)
 */

add_filter('bp_get_activity_content', 'BuddystreamYoutubeEmbed', 8);
add_filter('bp_get_activity_content_body', 'BuddystreamYoutubeEmbed', 8);
function BuddystreamYoutubeEmbed($text)
{

    $return = "";
    $return = $text;
    $return = str_replace('watch/?v=', 'embed/', $return);

    return $return;
}


/**
 *
 * Page loader functions
 *
 */

function buddystream_youtube()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('youtube');
}


/**
 * Get all users with integration
 * @return mixed
 */
function buddystreamYoutubeUsers(){

    global $wpdb;
    return $wpdb->get_results("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='bs_youtube_username';");
}


/**
 * Count imported items for user
 * @param $user_id
 * @return int
 */
function buddystreamYoutubeCountItems($user_id){

    global $wpdb,$bp;
    return count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=".$user_id." AND type='youtube';"));
}


/**
 * Import on for user
 * @param $user_id
 * @return mixed
 */
function buddystreamYoutubeImportOn(){
    return true;
}


/**
 * Reset user
 * @param $user_id
 * @return int
 */
function buddystreamYoutubeResetUser($user_id){
    delete_user_meta($user_id, "bs_youtube_username");
}
