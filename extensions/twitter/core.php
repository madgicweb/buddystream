<?php
/**
 * Add sharing button
 */

function buddystreamTwitterSharing()
{
    global $bp;
    if (get_site_option("tweetstream_consumer_key")  && get_site_option("buddystream_twitter_export")) {
        if (get_user_meta($bp->loggedin_user->id, 'tweetstream_token', 1)) {


            if(get_user_meta($bp->loggedin_user->id, 'buddystream_linkedin_reauth', 1)){
                echo '<a href="' . $bp->loggedin_user->domain . BP_SETTINGS_SLUG . '/buddystream-networks/?network=twitter"><span class="buddystream_share_button twitter" id="' . __('You need to re-authenticate on Facebook.', 'buddystream_twitter') . '"></span></a>';
            }else{
                echo'<span class="buddystream_share_button twitter" onclick="twitter_addTag()" id="' . __('Also post this to my Twitter account.', 'buddystream_twitter') . '"></span>';
            }

            $max_message = __("You\'ve reached the max. amount of characters for a Tweet.  The Message will appear truncated on Twitter.", "buddystream_twitter");
            echo '<div class="twitter_share_counterbox" style="display:none;">
                    <div class="twitter_share_counter">140</div>
                  </div>';

        }
    }
}


/**
 * Replace all twitpic and yfroc images for real thumbnails
 */

add_filter('bp_get_activity_content', 'BuddystreamTwitterImages', 5);
add_filter('bp_get_activity_content_body', 'BuddystreamTwitterImages', 5);

function BuddystreamTwitterImages($text)
{

    if (bp_get_activity_type() == 'twitter') {
        $text = preg_replace('#http://twitpic.com/([a-z0-9_]+)#i', '<a href="http://twitpic.com/\\1" target="_blank" rel="external"><img width="60" src="http://twitpic.com/show/mini/\\1" /></a>', $text);
        $text = preg_replace('#http://yfrog.com/([a-z0-9_]+)#i', '<a href="http://yfrog.com/\\1" target="_blank" rel="external"><img width="60" src="http://yfrog.com/\\1.th.jpg" /></a>', $text);
        $text = preg_replace('#http://yfrog.us/([a-z0-9_]+)#i', '<a href="http://yfrog.us/\\1" target="_blank" rel="external"><img width="60" src="http://yfrog.us/\\1:frame" /></a>', $text);
    }

    return $text;
}

/**
 * Post update to Twitter
 */

function buddystreamTwitterPostUpdate($content = "", $shortLink = "", $user_id = 0)
{

    global $bp;
    $buddyStreamFilters = new BuddyStreamFilters();

    //check for location
    $lat = null; $long = null;

    if (preg_match("/#location/i", $content)) {
        if(isset($_COOKIE["buddystream_location"])){
            $arrLocation = explode("#",$_COOKIE["buddystream_location"]);
            $lat         = $arrLocation[0];
            $long        = $arrLocation[1];
        }
    }

    //strip out location tag
    $content = str_replace("#location", "", $content);

    $buddyStreamOAuth = new BuddyStreamOAuth();

    $buddyStreamOAuth->setRequestTokenUrl('https://api.twitter.com/oauth/request_token');
    $buddyStreamOAuth->setAccessTokenUrl('https://api.twitter.com/oauth/access_token');
    $buddyStreamOAuth->setAuthorizeUrl('https://api.twitter.com/oauth/authorize');

    $buddyStreamOAuth->setConsumerKey(get_site_option("tweetstream_consumer_key"));
    $buddyStreamOAuth->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));

    $buddyStreamOAuth->setAccessToken(get_user_meta($bp->loggedin_user->id, 'tweetstream_token', 1));
    $buddyStreamOAuth->setAccessTokenSecret(get_user_meta($bp->loggedin_user->id, 'tweetstream_tokensecret', 1));

    $buddyStreamOAuth->setParameters(array('status' => $buddyStreamFilters->filterPostContent($content, $shortLink, 140),'lat' => $lat, 'long' => $long));
    $buddyStreamOAuth->setRequestType("POST");

    $response = $buddyStreamOAuth->oAuthRequest('https://api.twitter.com/1.1/statuses/update.json');
    $response = json_decode($response);

    buddyStreamAddToImportLog($bp->loggedin_user->id, $response->id_str, 'twitter');
}

/**
 *
 * Page loader functions
 *
 */

function buddystream_twitter()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->pageLoader('twitter');
}



/**
 * Get all users with integration
 * @return mixed
 */
function buddystreamTwitterUsers(){

    global $wpdb;
    return $wpdb->get_results("SELECT user_id FROM " . $wpdb->usermeta . " WHERE meta_key='tweetstream_token';");
}


/**
 * Count imported items for user
 * @param $user_id
 * @return int
 */
function buddystreamTwitterCountItems($user_id){

    global $wpdb,$bp;
    return count($wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE user_id=".$user_id." AND type='twitter';"));
}


/**
 * Import on for user
 * @param $user_id
 * @return mixed
 */
function buddystreamTwitterImportOn($user_id){
    return get_user_meta($user_id, 'tweetstream_synctoac', 1);
}


/**
 * Reset user
 * @param $user_id
 * @return int
 */
function buddystreamTwitterResetUser($user_id){

    delete_user_meta($user_id, "tweetstream_tweetstream_synctoac");
    delete_user_meta($user_id, "tweetstream_screenname");
    delete_user_meta($user_id, "tweetstream_lastupdate");
    delete_user_meta($user_id, "tweetstream_deletetweet");
    delete_user_meta($user_id, "tweetstream_checkboxon");
    delete_user_meta($user_id, "tweetstream_counterdate");
    delete_user_meta($user_id, "tweetstream_tokensecret");
    delete_user_meta($user_id, "tweetstream_filtermentions");
    delete_user_meta($user_id, "tweetstream_synctoac");
    delete_user_meta($user_id, "tweetstream_counterdate");
    delete_user_meta($user_id, "tweetstream_checkboxon");
    delete_user_meta($user_id, "tweetstream_daycounter");
    delete_user_meta($user_id, "tweetstream_deletetweet");
    delete_user_meta($user_id, "tweetstream_filtergood");
    delete_user_meta($user_id, "tweetstream_filterbad");
    delete_user_meta($user_id, "tweetstream_filtertoactivity");
    delete_user_meta($user_id, "tweetstream_filtertotwitter");
    delete_user_meta($user_id, "tweetstream_profilelink");
    delete_user_meta($user_id, "tweetstream_token");

}
