<?php

/**
 * Add Facebook sharing button
 */
function buddystreamFacebookSharing()
{
    global $bp;


    if(get_site_option("buddystream_facebook_export") && get_site_option("buddystream_facebookWall_export")){
        if (get_site_option("facestream_application_id")) {
            if (get_user_meta($bp->loggedin_user->id, 'facestream_session_key', 1)) {

                if(get_user_meta($bp->loggedin_user->id, 'buddystream_facebook_reauth', 1)){
                    echo '<a href="' . $bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=facebook"><span class="buddystream_share_button facebook" id="' . __('You need to re-authenticate on Facebook.', 'buddystream_facebook') . '"></span></a>';
                }else{
                    echo '<span class="buddystream_share_button facebook" onclick="facebook_addTag()" id="' . __('Also post this to my Facebook wall.', 'buddystream_facebook') . '"></span>';
                }

            }
        }
    }

    if(get_site_option("buddystream_facebook_export") && get_site_option("buddystream_facebookPages_export")){
        if (get_site_option("facestream_application_id") && get_user_meta($bp->loggedin_user->id, 'buddystream_facebook_pages', 1) && get_user_meta($bp->loggedin_user->id, 'buddystream_facebook_syncpage', 1)) {
            if (get_user_meta($bp->loggedin_user->id, 'buddystream_facebook_pageid', 1)) {

                if(get_user_meta($bp->loggedin_user->id, 'buddystream_facebook_reauth', 1)){
                    echo '<a href="'.$bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=facebook"><span class="buddystream_share_button facebookpage" id="' . __('You need to re-authenticate on Facebook.', 'buddystream_facebook') . '"></span></a>';
                }else{
                    echo '<span class="buddystream_share_button facebookpage" onclick="facebookpage_addTag()" id="' . __('Also post this to my Facebook page.', 'buddystream_facebook') . '"></span>';
                }

            }
        }
    }
}

/**
 * Post update to Facebook
 */
function buddystreamFacebookPostUpdate($content = "", $shortLink = "", $user_id = 0)
{

    global $bp;

    $buddyStreamFilters = new BuddyStreamFilters();

    //strip out location tag
    $content = str_replace("#location", "", $content);

    //handle oauth calls
    $buddyStreamOAuth = new BuddyStreamOAuth();

    //figure out if where to post to (wall/page or both)
    $arrayContent = explode(" ", $content);
    if (in_array("#facebook", $arrayContent)) {

        $buddyStreamOAuth->setRequestType('POST');

        $buddyStreamOAuth->setParameters(array(
            'client_id'     => get_site_option("facestream_application_id"),
            'client_secret' => get_site_option("facestream_application_secret"),
            'access_token'  => str_replace("&expires", "", get_user_meta($user_id, 'facestream_session_key', 1)),
            'message'       => $buddyStreamFilters->filterPostContent($content, $shortLink)
        ));

        $buddyStreamOAuth->oAuthRequest('https://graph.facebook.com/me/feed');
    }

    $arrayContent = explode(" ", $content);
    if (in_array("#facebookpage", $arrayContent)) {

        //get the pages for this user  
        $facebookPages = get_user_meta($user_id, 'buddystream_facebook_pages', 1);

        //explode the pages
        $facebookPagesArray = explode(',', $facebookPages);

        foreach ($facebookPagesArray as $page) {

            //explode page details
            $pageArray = explode(':', $page);

            $buddyStreamOAuth->setRequestType('POST');

            $buddyStreamOAuth->setParameters(array(
                'client_id'     => get_site_option("facestream_application_id"),
                'client_secret' => get_site_option("facestream_application_secret"),
                'access_token'  => $pageArray[1],
                'message'       => $buddyStreamFilters->filterPostContent($content, $shortLink)
            ));

            $buddyStreamOAuth->oAuthRequest('https://graph.facebook.com/' . $pageArray[0] . '/feed');
        }
    }
}


/**
 * Get all users with integration
 * @return mixed
 */
function buddystreamFacebookUsers(){

    global $wpdb;
    return $wpdb->get_results("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='facestream_session_key';");
}


/**
 * Count imported items for user
 * @param $user_id
 * @return int
 */
function buddystreamFacebookCountItems($user_id){

    global $wpdb,$bp;
    return count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=".$user_id." AND type='facebook';"));
}


/**
 * Import on for user
 * @param $user_id
 * @return mixed
 */
function buddystreamFacebookImportOn($user_id){
    return get_user_meta($user_id, 'facestream_synctoac', 1);
}


/**
 * Reset user
 * @param $user_id
 * @return int
 */
function buddystreamFacebookResetUser($user_id){
    
    delete_user_meta($user_id, "facestream_lastupdate");
    delete_user_meta($user_id, "facestream_counterdate");
    delete_user_meta($user_id, "facestream_tokensecret");
    delete_user_meta($user_id, "facestream_synctoac");
    delete_user_meta($user_id, "facestream_counterdate");
    delete_user_meta($user_id, "facestream_daycounter");
    delete_user_meta($user_id, "facestream_filtergood");
    delete_user_meta($user_id, "facestream_filterbad");
    delete_user_meta($user_id, "facestream_user_id");
    delete_user_meta($user_id, "facestream_session_key");

    delete_user_meta($user_id, 'facestream_session_key');
    delete_user_meta($user_id, 'facestream_synctoac');
    delete_user_meta($user_id, 'buddystream_facebook_syncpage');
    delete_user_meta($user_id, 'buddystream_facebook_syncalbum');
    delete_user_meta($user_id, 'facestream_synctoac');
    delete_user_meta($user_id, 'facestream_filtermentions');
    delete_user_meta($user_id, 'facestream_filtergood');
    delete_user_meta($user_id, 'facestream_filterbad');
    delete_user_meta($user_id, 'facestream_user_id');
    delete_user_meta($user_id, 'buddystream_facebook_privacy_friends');
}


/**
 *
 * Page loader functions
 *
 */

function buddystream_facebook()
{

    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('facebook');
}