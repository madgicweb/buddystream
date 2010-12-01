<?php
/*
Plugin Name: BuddyStream
Plugin URI:
Description: BuddyStream
Version: 1.0.3
Author: Peter Hofman
Author URI: http://www.buddystream.net
*/

// Copyright (c) 2010 Buddystream.net All rights reserved.
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
    require 'buddystreamFunctions.php';
    zend_framework_init();
    
    try {
        Zend_Session::start();
    } catch(Zend_Session_Exception $e) {
        @session_start();
    }

}

function zend_framework_init()
{
	set_include_path(get_include_path() . PATH_SEPARATOR . dirname(__FILE__) );
	zend_framework_register_autoload();
}

function zend_framework_register_autoload()
{
	require_once 'Zend/Loader/Autoloader.php';
	$autoloader = Zend_Loader_Autoloader::getInstance();
}

add_action('bp_init', 'buddystream_init');