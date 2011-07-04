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


/**
 * User pages
 */

function buddystream_youtube_profile_navigation(){
    
     global $bp;
     if(get_site_option('buddystream_youtube_album') == 'on' && get_user_meta($bp->displayed_user->id, 'bs_youtube_username',1)){
     
          bp_core_new_nav_item( 
                array(
                    'name' => __( 'Youtube album', 'buddystream_youtube' ),
                    'slug' => 'youtubealbum',
                    'position' => 80,
                    'screen_function' => 'buddystream_youtube_album'
                )
          );     
      }
}
buddystream_youtube_profile_navigation();

/**
 * Album
 */

function buddystream_youtube_album(){
    buddystreamUserPageLoader('youtube','album');
}

function buddystream_youtube_album_screen_title()
{
    __('Youtube', 'buddystream_youtube');
}

function buddystream_youtube_album_screen_content()
{
    global $bp;
    include "templates/UserAlbum.php";
}

/**
 * User settings
 */

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
