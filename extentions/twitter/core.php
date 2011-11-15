<?php
/**
 * Add sharing button
 */

function buddystreamTwitterSharing(){
 global $bp;
    if (get_site_option("tweetstream_consumer_key")) {
        if (get_user_meta($bp->loggedin_user->id, 'tweetstream_token',1)) {
            echo'<div class="twitter_share_button"
            onclick="twitter_addTag()">
            <img src="'.plugins_url().'/buddystream/extentions/twitter/icon.png" title="'.__('to twitter', 'buddystream_lang').'"> '.__('to twitter', 'buddystream_twitter').'
            </div>';

            $max_message = __("You\'ve reached the max. amount of characters for a Tweet.  The Message will appear truncated on Twitter.", "buddystream_twitter");
            echo '<div class="twitter_share_counter">140</div>';

        }
    }
}

/**
 * Replace all twitpic and yfroc images for real thumbnails
 */

add_filter( 'bp_get_activity_content','BuddystreamTwitterImages',5 );
add_filter( 'bp_get_activity_content_body','BuddystreamTwitterImages',5 );

function BuddystreamTwitterImages($text) {
 
    if(bp_get_activity_type() == 'twitter'){
        $text = preg_replace('#http://twitpic.com/([a-z0-9_]+)#i', '<a href="http://twitpic.com/\\1" target="_blank" rel="external"><img width="60" src="http://twitpic.com/show/mini/\\1" /></a>', $text);
        $text = preg_replace('#http://yfrog.com/([a-z0-9_]+)#i', '<a href="http://yfrog.com/\\1" target="_blank" rel="external"><img width="60" src="http://yfrog.com/\\1.th.jpg" /></a>', $text);
        $text = preg_replace('#http://yfrog.us/([a-z0-9_]+)#i', '<a href="http://yfrog.us/\\1" target="_blank" rel="external"><img width="60" src="http://yfrog.us/\\1:frame" /></a>', $text);
    }
    
  return $text;
}


/**
 * If set Tweet button
 */

add_action('bp_activity_entry_meta','BuddystreamTweetButton',9);
add_action('bp_group_forum_topic_meta', 'BuddystreamTweetButton',9);

function BuddystreamTweetButton() {
    if(get_site_option("buddystream_twitter_share")){
        
    if(get_site_option("buddystream_twitter_share_counter")){  
        $dataCount = "horizontal";
    }else{
        $dataCount = "none";
    }
        
        echo '<div class="twitter_share_container">
                <a href="http://twitter.com/share" class="twitter-share-button" data-url="'.bp_get_activity_thread_permalink().'" data-count="'.$dataCount.'">Tweet</a><script type="text/javascript" src="http://platform.twitter.com/widgets.js"></script>  
              </div>';
    }   
}

/**
 * Post update to Twitter
 */

function buddystreamTwitterPostUpdate($content = "", $shortLink = "", $user_id = 0) {
    
    global $bp;
    
    //handle custom twitter data
    $twitter = new BuddystreamTwitter;
    $twitter->setShortLink($shortLink);
    $twitter->setPostContent($content);
        
    //handle oauth calls
    $buddystreamOAuth = new BuddyStreamOAuth();
    $buddystreamOAuth->setRequestTokenUrl('http://api.twitter.com/oauth/request_token');
    $buddystreamOAuth->setAccessTokenUrl('http://api.twitter.com/oauth/access_token');
    $buddystreamOAuth->setAuthorizeUrl('https://api.twitter.com/oauth/authorize');
    $buddystreamOAuth->setCallbackUrl($bp->root_domain);
    $buddystreamOAuth->setConsumerKey(get_site_option("tweetstream_consumer_key"));
    $buddystreamOAuth->setConsumerSecret(get_site_option("tweetstream_consumer_secret"));
    $buddystreamOAuth->setAccessToken(get_user_meta($bp->loggedin_user->id,'tweetstream_token', 1));
    $buddystreamOAuth->setAccessTokenSecret(get_user_meta($bp->loggedin_user->id,'tweetstream_tokensecret', 1));
    $buddystreamOAuth->setRequestType('POST');
    $buddystreamOAuth->setPostData(array('status' => $twitter->getPostContent()));
    $buddystreamOAuth->oAuthRequest('https://api.twitter.com/1/statuses/update.json');
}

/**
 * Add javascript and stylesheet file for Twitter
 */
wp_enqueue_script('buddystreamtwitter', plugins_url() . '/buddystream/extentions/twitter/main.js');
wp_enqueue_style('buddystreamtwitter', plugins_url() . '/buddystream/extentions/twitter/style.css');

/**
 * 
 * Page loader functions 
 *
 */

function buddystream_twitter(){
    buddystreamPageLoader('twitter');
}

/**
 * User settings
 */

function buddystream_twitter_user_settings()
{
   buddystreamUserPageLoader('twitter','settings');
}

function buddystream_twitter_settings_screen_title()
{
    __('Twitter', 'buddystream_twitter');
}

function buddystream_twitter_settings_screen_content()
{
    global $bp;
    include "templates/UserSettings.php";
}
?>
