<?php
/**
 * Add javascript and stylesheet file for Youtube
 */

wp_enqueue_style('buddystreamyoutube', plugins_url() . '/buddystream/extentions/youtube/style.css');

/**
 * 
 * Page loader functions 
 *
 */

function buddystream_youtube()
{
   buddystreamPageLoader('youtube');
}

function buddystream_youtube_user_settings()
{
    buddystreamUserPageLoader('youtube');
}

function buddystream_youtube_settings_screen_title()
{
    __('Youtube', 'tweetstream_lang');
}

function buddystream_youtube_settings_screen_content()
{
    global $bp;
    include "templates/UserSettings.php";
}

?>
