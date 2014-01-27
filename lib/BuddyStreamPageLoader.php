<?php


/**
 * Setup user navigation for the extensions
 *
 */

function buddystream_setup_nav()
{
    global $bp;
        bp_core_new_subnav_item(
            array(
                'name' => __('Social networks', 'buddystream_lang'),
                'slug' => 'buddystream-networks',
                'parent_url' => $bp->loggedin_user->domain . BP_SETTINGS_SLUG.'/',
                'parent_slug' => BP_SETTINGS_SLUG,
                'screen_function' => 'buddystream_default_user_settings',
                'position' => 10,
                'user_has_access' => bp_is_my_profile ()
                )
        );
   
}
buddystream_setup_nav();

/**
 * User settings
 */

function buddystream_default_user_settings()
{
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->userPageLoader('default','settings');
}

function buddystream_default_settings_screen_title()
{
    __('Social networks', 'buddystream_facebook');
}

function buddystream_default_settings_screen_content()
{
    global $bp;
    include BP_BUDDYSTREAM_DIR."/extensions/default/templates/UserSettings.php";
}

/**
 * Setup admin navigation for the extensions
 *
 */

add_action('admin_menu', 'buddystreamAdmin');
add_action('network_admin_menu', 'buddystreamAdmin');

function buddystreamAdmin() {

    if (!is_super_admin()) {
	    return false;
    }

    /**
     * Load the BuddyStream menu into the admin
     */
    
    add_menu_page(
        __('Info', 'buddystream'), 
        __('BuddyStream', 'buddystream'), 
        'manage_options',
        'buddystream_admin', 
        'buddystream_welcome',
        BP_BUDDYSTREAM_URL."/images/buddystream_icon.png"
     );
    

    /**
     * Load the extensions into the BuddyStream admin menu.
     */
    $buddyStreamExtensions = new BuddyStreamExtensions();
    foreach($buddyStreamExtensions->getExtensionsConfigs() as $extension){
        if (get_site_option("buddystream_".$extension['name']."_power")) {

            if( ! isset($extension['parent']) ){

                add_submenu_page(
                    'buddystream_admin',
                    __(ucfirst($extension['displayname']), 'buddystream_'.$extension['name']),
                    __(ucfirst($extension['displayname']), 'buddystream_'.$extension['name']),
                    'manage_options',
                    'buddystream_'.$extension['name'],
                    'buddystream_'.$extension['name']
                );

            }
        }
    }

    return true;
}

/**
 * Add albums page for network albums (grouped)
 */

function buddystream_profile_navigation(){
    
     global $bp;
     if(get_site_option('buddystream_social_albums') == "on"){
         
        if(get_site_option('buddystream_social_albums_profile_navigation') == "on"){
            bp_core_new_subnav_item( 
                array(
                    'name' => __( 'Social albums', 'buddystream_lang' ),
                    'slug' => 'social-album',
                    'parent_url' => $bp->displayed_user->domain . $bp->profile->slug . '/', 
                    'parent_slug' => $bp->profile->slug, 
                    'screen_function' => 'buddystream_default_album',
                    'position' => 80 
                    ) 
            );
        }else{
            bp_core_new_nav_item( 
                    array(
                        'name' => __( 'Social albums', 'buddystream_lang' ),
                        'slug' => 'social-album',
                        'position' => 80,
                        'screen_function' => 'buddystream_default_album'
                    )
            );   
        }
     }
}

buddystream_profile_navigation();

function buddystream_default_album(){
    $buddyStreamExtensions = new BuddyStreamExtensions();
    $buddyStreamExtensions->userPageLoader('default','album');
}

function buddystream_default_album_screen_title()
{
    __('Social albums', 'buddystream_lang');
}

function buddystream_default_album_screen_content()
{
    global $bp;
    include BP_BUDDYSTREAM_DIR."/extensions/default/templates/SocialAlbum.php";
}



/**
 * User settings
 */

function buddystream_facebook_user_settings()
{
   $buddyStreamExtensions = new BuddyStreamExtensions();
   $buddyStreamExtensions->userPageLoader('facebook','settings');
}

function buddystream_facebook_settings_screen_title()
{
    __('Facebook', 'buddystream_facebook');
}

function buddystream_facebook_settings_screen_content()
{
    global $bp;
    include BP_BUDDYSTREAM_DIR."/extensions/facebook/templates/UserSettings.php";
}

/**
 * Global BuddyStream pages
 *
 */

function buddystream_welcome()
{
     if ( ! isset($_GET["settings"])) {
        include BP_BUDDYSTREAM_DIR."/extensions/default/templates/Dashboard.php";
     } else if ($_GET["settings"] == "admin") {
        include BP_BUDDYSTREAM_DIR."/extensions/default/templates/Dashboard.php";
     } else if ($_GET["settings"] == "cronjob") {
         include BP_BUDDYSTREAM_DIR."/extensions/default/templates/Cronjob.php";
     } else if ($_GET["settings"] == "powercentral") {
         include BP_BUDDYSTREAM_DIR."/extensions/default/templates/Powercentral.php";}
     else if ($_GET["settings"] == "synccentral") {
         include BP_BUDDYSTREAM_DIR."/extensions/default/templates/Synccentral.php";
     } else if ($_GET["settings"] == "general") {
         include BP_BUDDYSTREAM_DIR."/extensions/default/templates/General.php";
     } else if ($_GET["settings"] == "log") {
         include BP_BUDDYSTREAM_DIR."/extensions/default/templates/Log.php";
     }
}