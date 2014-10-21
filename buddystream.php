<?php
/*
Plugin Name: BuddyStream
Plugin URI: http://www.buddystream.net
Description: BuddyStream
Version: 3.2.7
Author: Peter Hofman
Author URI: http://www.buddystream.net
*/

// Copyright (c) 2010/2011/2012/2013/2014 Buddystream.net All rights reserved.
//
// Released under the GPL license
// http://www.opensource.org/licenses/gpl-license.php
//
// This is an add-on for Buddypress
// http://buddypress.org/
//
// **********************************************************************
// This program is distributed in the hope that it will be useful, but
// WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
// **********************************************************************

/*
 * Only load code that needs BuddyPress
 * to run once BP is loaded and initialized.
 */
function buddystream_init()
{
    global $bp;

    //define plugin version and installed value
    define('BP_BUDDYSTREAM_VERSION', '3.2.7');
    define('BP_BUDDYSTREAM_IS_INSTALLED', 1);
    define('BP_BUDDYSTREAM_DIR', dirname(__FILE__));
    define('BP_BUDDYSTREAM_URL', plugin_dir_url(__FILE__));
    define('BP_BUDDYSTREAM_IS_PREMIUM', 0);

    //first load translations
    buddyStreamLoadTranslations();

    //initialize the database if needed
    buddyStreamInitDatabase();

    //initialize settings if needed
    buddyStreamInitSettings();

    //now initialize the core
    include_once('lib/BuddyStreamCurl.php');
    include_once('lib/BuddyStreamOAuth.php');
    include_once('lib/BuddyStreamLog.php');
    include_once('lib/BuddyStreamExtensions.php');
    include_once('lib/BuddyStreamFilters.php');
    include_once('lib/BuddyStreamSupport.php');
    include_once('lib/BuddyStreamPageLoader.php');
    include_once('lib/BuddyStreamCore.php');

    //turn all syncing on
    buddyStreamInitSync();
}

/**
 * Initialise sync settings for the first time (3.0)
 */

function buddyStreamInitSync(){

    if( ! get_site_option("buddystream_321")){

        if(get_site_option('buddystream_license_key')){

            global $bp;
            require_once (ABSPATH . WPINC . '/class-feed.php');

            $url   = "http://buddystream.net/cronservice/check.php?licensekey="
                . get_site_option('buddystream_license_key')
                . "&domain=" . str_replace("http://", "", $bp->root_domain)
                . "&contenturl=" . WP_CONTENT_URL
                . "&output=rss"
                . "&validate=" . md5(date('Ymd'));

            @fetch_feed($url);
        }

        update_site_option("buddystream_321", "true");
    }


    if( ! get_site_option("buddystream_30")){

        $buddyStreamExtensions =  new BuddyStreamExtensions();

        foreach ($buddyStreamExtensions->getExtensionsConfigs() as $extension) {
            if (is_array($extension) && !$extension['parent'] && $extension['synctypes']) {
                $arrSyncTypes = explode(",", str_replace(" ","",$extension['synctypes']));
                foreach($arrSyncTypes as $syncType){
                    update_site_option('buddystream_' . $extension['name'] . '_'.$syncType, 'on');
                }
            }

            //get parent subextensions
            $subExtensions = $buddyStreamExtensions->getExtensionsWithParent($extension['name']);
            foreach ($subExtensions as $subExtension) {
                if($subExtension['synctypes']){
                    $arrSyncTypes = explode(",", str_replace(" ","",$subExtension['synctypes']));
                    foreach($arrSyncTypes as $syncType){
                        update_site_option('buddystream_' . $subExtension['name'] . '_'.$syncType, 'on');
                    }
                }
            }
        }
        update_site_option("buddystream_30", "true");

    }
}


/**
 * Initialise default settings
 */
function buddyStreamInitSettings(){

    if( ! get_site_option('buddystream_init_settings') != 'BP_BUDDYSTREAM_VERSION'){
        

        if(!get_site_option('buddystream_social_albums')){
            update_site_option('buddystream_social_albums', 'on');
        }

        if(!get_site_option('buddystream_group_sharing')){
            update_site_option('buddystream_group_sharing', 'on');
        }
        
        update_site_option('buddystream_init_settings', BP_BUDDYSTREAM_VERSION);
    }

    if( ! get_site_option('buddystream_2512')) {
        update_site_option('buddystream_facebook_privacy_setting', 'on');
        update_site_option('buddystream_2512', '1');
    }
}

/**
 * Initialise database tables needed for plugin.
 */
function buddyStreamInitDatabase(){
    
   if( ! get_site_option("buddystream_installed_version")){

        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $buddystreamSql = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "buddystream_log (
          `id` int(11) NOT NULL auto_increment,
          `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
          `type` text NOT NULL,
          `message` text NOT NULL,
          PRIMARY KEY  (`id`)
        );";

        @dbDelta($buddystreamSql);
        unset($buddystreamSql);

       $buddystreamSql = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "buddystream_imports (
          `id` int(11) NOT NULL auto_increment,
          `item_id` varchar(255) NOT NULL,
          PRIMARY KEY  (`id`)
        );";

       @dbDelta($buddystreamSql);
       unset($buddystreamSql);

        update_site_option("buddystream_installed_version", "1");
    }

   if( ! get_site_option('buddystream_26')) {

        global $wpdb,$bp;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $buddystreamSql = "CREATE TABLE IF NOT EXISTS " . $wpdb->base_prefix . "buddystream_imports (
          `id` int(11) NOT NULL auto_increment,
          `item_id` varchar(255) NOT NULL,
          PRIMARY KEY  (`id`)
        );";

        @dbDelta($buddystreamSql);
        unset($buddystreamSql);

       //now get all activity items with a secondary id adn add it to them buddystream imports table
       $items = $wpdb->get_results("SELECT * FROM ".$bp->activity->table_name." WHERE secondary_item_id != ''");

       foreach($items as $item){

           $item_id = str_replace($item->user_id."_", "", $item->secondary_item_id);
           $item_id = $item->user_id."-".$item_id."-".$item->component;

           $wpdb->query("INSERT INTO ".$wpdb->base_prefix."buddystream_imports set item_id='".$item_id."'");
       }

        update_site_option('buddystream_26', '1');
   }
}

/*
 * Load the translation files for the plugin and extensions
 */
function buddyStreamLoadTranslations() {
    
    if (file_exists( BP_BUDDYSTREAM_DIR."/languages/buddystream-" . get_locale() . ".mo")) {
        load_textdomain('buddystream_lang', BP_BUDDYSTREAM_DIR."/languages/buddystream-" . get_locale().".mo");
    }else{
        load_textdomain('buddystream_lang', BP_BUDDYSTREAM_DIR."/languages/buddystream-en_US.mo");
    }

    $handle = opendir(dirname(__FILE__)."/extensions");
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && $file != ".DS_Store") {
                if (file_exists(BP_BUDDYSTREAM_DIR."/extensions/".$file."/languages/buddystream_".$file."-".get_locale().".mo")) {
                    load_textdomain('buddystream_' . $file, BP_BUDDYSTREAM_DIR."/extensions/".$file."/languages/buddystream_".$file."-".get_locale().".mo");
                }else{
                    load_textdomain('buddystream_' . $file, BP_BUDDYSTREAM_DIR."/extensions/".$file."/languages/buddystream_".$file."-en_US.mo");
                }
            }
        }
    }
}

add_action('bp_init', 'buddystream_init', 4);

/**
 * Add the BuddyStream Connect Widget
 */
add_action('widgets_init', 'buddystream_connect_widget');
function buddystream_connect_widget() {
    include_once('lib/BuddyStreamWidgets.php');
    register_widget('BuddyStream_Connect_Widget');
}
