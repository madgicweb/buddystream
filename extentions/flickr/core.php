<?php
/**
 * Add stylesheet for Flickr
 */

wp_enqueue_style('buddystreamflickr', plugins_url() . '/buddystream/extentions/flickr/style.css');


/**
 * 
 * Page loader functions 
 *
 */

function buddystream_flickr(){
    buddystreamPageLoader('flickr');
}

function buddystream_flickr_user_settings()
{
   buddystreamUserPageLoader('flickr');
}

function buddystream_flickr_settings_screen_title()
{
    __('Flickr', 'buddystream_flickr');
}

function buddystream_flickr_settings_screen_content()
{
    global $bp;
    include "templates/UserSettings.php";
}
?>
