<?php
/**
 * Add javascript and stylesheet file for Youtube
 */

wp_enqueue_style('buddystreamyoutube', plugins_url() . '/buddystream/extensions/youtube/style.css');



/**
 * Replace all embed urls into new embed urls (old content)
 */

add_filter( 'bp_get_activity_content','BuddystreamYoutubeEmbed', 8);
add_filter( 'bp_get_activity_content_body','BuddystreamYoutubeEmbed', 8);
function BuddystreamYoutubeEmbed($text) {
    
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