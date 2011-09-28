<?php
/*
Plugin Name: BuddyStream
Plugin URI: http://www.buddystream.net
Description: BuddyStream
Version: 2.1.6
Author: Peter Hofman
Author URI: http://www.buddystream.net
*/

// Copyright (c) 2010/2011 Buddystream.net All rights reserved.
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
    
    //first load translations
    buddystream_load_translations();

    //initialize the database if needed
    buddystream_init_database();
    
    //now initialize the core
    require_once('core.php');
    
    //initialize the upgrade procedure if needed
    buddystream_init_update();
    
    //now initialize the zend framework
    buddystream_zend_framework_init();
}


function buddystream_init_update(){
   
    if(get_site_option("buddystream_installed_version") != "2.1.2"){
        
        global $wpdb;
        
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
        
        $buddystreamSql = 'UPDATE '.$wpdb->base_prefix.'bp_activity SET action = REPLACE(action,"&nbsp;"," ")
        WHERE component="twitter"
        OR component="facebook"
        OR component="youtube"
        OR component="googlebuzz"
        OR component="flickr"
        OR component="rss"
        OR component="lastfm"
        OR component="linkedin";
        
        UPDATE '.$wpdb->base_prefix.'bp_activity SET content = REPLACE(content,"&nbsp;"," ")
        WHERE component="twitter"
        OR component="facebook"
        OR component="youtube"
        OR component="googlebuzz"
        OR component="flickr"
        OR component="rss"
        OR component="lastfm"
        OR component="linkedin";';

        dbDelta($buddystreamSql);
        update_site_option("buddystream_installed_version", "2.1.2");
   }
    
    
}

function buddystream_init_database(){
    
   if(!get_site_option("buddystream_installed_version")){

        global $wpdb;
        require_once(ABSPATH . 'wp-admin/includes/upgrade.php');

        $buddystreamSql = "CREATE TABLE IF NOT EXISTS ".$wpdb->base_prefix."buddystream_log (
          `id` int(11) NOT NULL auto_increment,
          `date` timestamp NOT NULL default CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP,
          `type` text NOT NULL,
          `message` text NOT NULL,
          PRIMARY KEY  (`id`)
        );";

        dbDelta($buddystreamSql);
        unset($buddystreamSql);

        update_site_option("buddystream_installed_version","1");
    }
    
}

function buddystream_load_translations() {
    
    if (file_exists( WP_PLUGIN_DIR."/buddystream/languages/buddystream-".get_locale().".mo")) {
        load_textdomain('buddystream_lang', WP_PLUGIN_DIR."/buddystream/languages/buddystream-".get_locale().".mo");
    }else{
        load_textdomain('buddystream_lang', WP_PLUGIN_DIR."/buddystream/languages/buddystream-en_US.mo");
    }
    
    $handle = opendir(WP_PLUGIN_DIR . "/buddystream/extentions");
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != "..") {
                if (file_exists(WP_PLUGIN_DIR."/buddystream/extentions/".$file."/languages/buddystream_".$file."-".get_locale().".mo")) {
                    load_textdomain('buddystream_'.$file, WP_PLUGIN_DIR."/buddystream/extentions/".$file."/languages/buddystream_".$file."-".get_locale().".mo");
                }else{
                    load_textdomain('buddystream_'.$file, WP_PLUGIN_DIR."/buddystream/extentions/".$file."/languages/buddystream_".$file."-en_US.mo");
                }
            }
        }
    }
}

function buddystream_zend_framework_init() {
	set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) );
	buddystream_zend_framework_register_autoload();
}

function buddystream_zend_framework_register_autoload() {
	require_once 'Zend/Loader/Autoloader.php';
	$autoloader = Zend_Loader_Autoloader::getInstance();
}

add_action('bp_init', 'buddystream_init',4);
