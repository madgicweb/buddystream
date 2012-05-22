<?php
/*
Plugin Name: BuddyStream
Plugin URI: http://www.buddystream.net
Description: BuddyStream
Version: 2.5.12
Author: Peter Hofman
Author URI: http://www.buddystream.net
*/

// Copyright (c) 2010/2011/2012 Buddystream.net All rights reserved.
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

    //define plugin version and installed value
    define('BP_BUDDYSTREAM_VERSION', '2.5.12');
    define('BP_BUDDYSTREAM_IS_INSTALLED', 1);

    //first load translations
    buddyStreamLoadTranslations();

    //initialize the database if needed
    buddyStreamInitDatabase();
    
    //initialize settings if needed
    buddyStreamInitSettings();
    
    //now initialize the core
    include_once('lib/BuddyStreamCurl.php');
    include_once('lib/BuddyStreamOAuth.php');
    include_once('lib/BuddyStreamExtentions.php');
    include_once('lib/BuddyStreamFilters.php');
    include_once('lib/BuddyStreamLog.php');
    include_once('lib/BuddyStreamSupport.php');
    include_once('lib/BuddyStreamPageLoader.php');
    include_once('lib/BuddyStreamCore.php');
}

function buddyStreamInitSettings(){

    if( ! get_site_option('buddystream_init_settings') != 'BP_BUDDYSTREAM_VERSION'){
        
        if(!get_site_option('buddystream_sharebox')){
            update_site_option('buddystream_sharebox', 'on');
        }
        
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

        dbDelta($buddystreamSql);
        unset($buddystreamSql);

        update_site_option("buddystream_installed_version", "1");
    }
    
}

function buddyStreamLoadTranslations() {
    
    if (file_exists( WP_PLUGIN_DIR . "/buddystream/languages/buddystream-" . get_locale() . ".mo")) {
        load_textdomain('buddystream_lang', WP_PLUGIN_DIR."/buddystream/languages/buddystream-" . get_locale().".mo");
    }else{
        load_textdomain('buddystream_lang', WP_PLUGIN_DIR."/buddystream/languages/buddystream-en_US.mo");
    }
    
    $handle = opendir(WP_PLUGIN_DIR . "/buddystream/extentions");
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && $file != ".DS_Store") {
                if (file_exists(WP_PLUGIN_DIR . "/buddystream/extentions/".$file."/languages/buddystream_".$file."-".get_locale().".mo")) {
                    load_textdomain('buddystream_' . $file, WP_PLUGIN_DIR."/buddystream/extentions/".$file."/languages/buddystream_".$file."-".get_locale().".mo");
                }else{
                    load_textdomain('buddystream_' . $file, WP_PLUGIN_DIR."/buddystream/extentions/".$file."/languages/buddystream_".$file."-en_US.mo");
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