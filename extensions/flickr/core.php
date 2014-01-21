<?php

/**
 * Page loader
*/
function buddystream_flickr()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('flickr');
}


/**
 * Get all users with flickr integration
 * @return mixed
 */
function buddystreamFlickrUsers(){

    global $wpdb;
    return $wpdb->get_results("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='bs_flickr_username';");
}

/**
 * Import on for user
 * @param $user_id
 * @return mixed
 */
function buddystreamFlickrImportOn($user_id){
    return true;
}


/**
 * Count imported items for  user
 * @param $user_id
 * @return int
 */
function buddystreamFlickrCountItems($user_id){

    global $wpdb,$bp;
    return count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=".$user_id." AND type='flickr';"));
}


/**
 * Reset user
 * @param $user_id
 * @return int
 */
function buddystreamFlickrResetUser($user_id){
    delete_user_meta($user_id, "bs_flickr_username");
}