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

/**
 * User pages
 */

function buddystream_flickr_profile_navigation(){
    
     global $bp;
     if(get_site_option('bs_flickr_album') == 'on' && get_user_meta($bp->displayed_user->id, 'bs_flickr_username',1)){
     
          bp_core_new_nav_item( 
                array(
                    'name' => __( 'Flickr album', 'buddystream_flickr' ),
                    'slug' => 'flickralbum',
                    'position' => 80,
                    'screen_function' => 'buddystream_flickr_album'
                )
          );     
      }
}
buddystream_flickr_profile_navigation();


/**
 * Album
 */

function buddystream_flickr_album(){
    buddystreamUserPageLoader('flickr','album');
}

function buddystream_flickr_album_screen_title()
{
    __('Flickr', 'buddystream_flickr');
}

function buddystream_flickr_album_screen_content()
{
    global $bp;
    include "templates/UserAlbum.php";
}

/**
 * User settings
 */

function buddystream_flickr_user_settings()
{
   buddystreamUserPageLoader('flickr','settings');
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
