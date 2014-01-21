<?php

/**
 * filter to show last fm items on main activity, or user activity
 */

add_filter('bp_ajax_querystring', 'bs_filter_query', 999, 2);
function bs_filter_query($qs, $object)
{

    global $bp;
    if (preg_match("/lastfm/i", $qs)) {
        return $qs . "&show_hidden=true&scope=lastfm";
    }

    return $qs;
}

/**
 *
 * Page loader functions
 *
 */

function buddystream_lastfm()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('lastfm');
}


/**
 * Get all users with integration
 * @return mixed
 */
function buddystreamLastfmUsers(){

    global $wpdb;
    return $wpdb->get_results("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='bs_lastfm_username';");
}


/**
 * Count imported items for user
 * @param $user_id
 * @return int
 */
function buddystreamLastfmCountItems($user_id){

    global $wpdb,$bp;
    return count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=".$user_id." AND type='lastfm';"));
}


/**
 * Import on for user
 * @return bool
 */
function buddystreamLastfmImportOn(){
    return true;
}


/**
 * Reset user
 * @param $user_id
 * @return int
 */
function buddystreamLastfmResetUser($user_id){
    delete_user_meta($user_id, "bs_lastfm_username");
}