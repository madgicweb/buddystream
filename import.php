<?php
/**
 * Import for BuddyStream
 */
set_time_limit(900);
ini_set('max_execution_time', 900);

//load the WordPress loader
$currentPpath = getcwd();
$seekingRoot  = pathinfo($currentPpath);
$incPath      = str_replace('wp-content/plugins','',$seekingRoot['dirname']);

ini_set('include_path', $incPath);
include('wp-load.php');

//include the needed core files
include_once('lib/BuddyStreamCurl.php');
include_once('lib/BuddyStreamOAuth.php');
include_once('lib/BuddyStreamLog.php');
include_once('lib/BuddyStreamExtensions.php');
include_once('lib/BuddyStreamFilters.php');
include_once('lib/BuddyStreamSupport.php');
include_once('lib/BuddyStreamPageLoader.php');
include_once('lib/BuddyStreamCore.php');

//if we are ran from the BuddyStream cronservice save the new uniqueKey
if (isset($_GET['uniqueKey'])) {
    update_site_option("buddystream_cronservices_uniquekey", $_GET['uniqueKey']);
}

//since BuddyStream 2.5.08
if ( ! get_site_option('buddystream_fix_2508')) {

    //get all activity items from BuddyStream and user id in front of secondary_item_id
    global $bp, $wpdb;
    $items = $wpdb->get_results("SELECT * FROM " . $bp->activity->table_name . " WHERE secondary_item_id != '' and (
    type = 'youtube'
    OR type = 'vimeo'
    OR type = 'rss'
    OR type = 'twitter'
    OR type = 'facebook'
    OR type = 'soundcloud'
    OR type = 'googleplus'
    OR type = 'linkedin'
    OR type = 'lastfm'
    OR type = 'flickr'
    OR type = 'googlebuzz'
    ) ;");

    foreach ($items as $item) {
        $wpdb->query("UPDATE " . $bp->activity->table_name . " SET secondary_item_id = '" . $item->user_id . "_" . $item->secondary_item_id . "' WHERE id='" . $item->id . "';");
    }

    update_site_option('buddystream_fix_2508', 1);
}

//if network set skip auto loading network import and run the set network
if( $_GET['network'] ){
    $importer = $_GET['network'];
}

if( ! $_GET['network'] ){

    //directory of extensions
    $handle = opendir(BP_BUDDYSTREAM_DIR."/extensions");

    //loop extensions so we can add active extensions to the import loop
    if ($handle) {
        while (false !== ($file = readdir($handle))) {
            if ($file != "." && $file != ".." && $file != ".DS_Store") {
                if (file_exists(BP_BUDDYSTREAM_DIR."/extensions/" . $file . "/import.php")) {
                    if (get_site_option("buddystream_" . $file . "_power")) {
                        $extensions[] = $file;
                    }
                }
            }
        }
    }

    //save importers to database
    update_site_option("buddystream_importers", implode(",", $extensions));

    //check if there is a import queue, if empty reset
    if (get_site_option("buddystream_importers_queue") == "") {
        update_site_option("buddystream_importers_queue", implode(",", $extensions));
    }

    //start the import (one per time)
    $importers = get_site_option("buddystream_importers_queue");
    $importers = explode(",", $importers);
    $importer = current($importers);

    //remove importer form queue before starting real import
    unset($importers[0]);
    update_site_option("buddystream_importers_queue", implode(",", $importers));
}


//start the importer for real 
if (file_exists(BP_BUDDYSTREAM_DIR."/extensions/" . $importer . "/import.php")) {
    if (get_site_option("buddystream_" . $importer . "_power")) {

        include_once(BP_BUDDYSTREAM_DIR."/extensions/" . $importer . "/import.php");

        if (function_exists("Buddystream" . ucfirst($importer) . "ImportStart")) {


            $numberOfItems = call_user_func("Buddystream" . ucfirst($importer) . "ImportStart");

            //create return array
            $infoArray = array(
                'executed' => true,
                'date' => date('d-m-y H:i'),
                'network' => $importer,
                'items' => $numberOfItems
            );

            echo json_encode($infoArray);
        }
    }
}