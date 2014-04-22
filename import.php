<?php
/**
 * Import for BuddyStream
 */

set_time_limit(900);
ini_set('max_execution_time', 900);

//load the WordPress loader
$currentPpath = getcwd();
$seekingRoot  = pathinfo($currentPpath);

$arrayRoot   = explode(DIRECTORY_SEPARATOR, $seekingRoot['dirname']);
$arrayRoot   = array_reverse($arrayRoot);

$incPath      = str_replace($arrayRoot[1].'/plugins','',$seekingRoot['dirname']);
$incPath      = str_replace("\\".$arrayRoot[1]."\plugins", "", $incPath);
$incPath      = str_replace($arrayRoot[1].'/plugins','',$seekingRoot['dirname']);
$incPath      = str_replace("\\".$arrayRoot[1]."\plugins", "", $incPath);

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

    //check if imports are turned on
    $importQueue = array();
    foreach($extensions as $extension){

        if(get_site_option("buddystream_".$extension."_import")){
            $importQueue[] = $extension;
        }
    }

    //check if there is a import queue, if empty reset
    if (get_site_option("buddystream_importers_queue") == "") {
        update_site_option("buddystream_importers_queue", implode(",", $importQueue));
    }

    //start the import (one per time)
    $importers = get_site_option("buddystream_importers_queue");
    $importers = explode(",", $importers);
    $importer  = current($importers);

    //remove importer from queue before starting real import
    unset($importers[0]);
    update_site_option("buddystream_importers_queue", implode(",", $importers));
}


//start the importer for real 
if (file_exists(BP_BUDDYSTREAM_DIR."/extensions/" . $importer . "/import.php")) {

    if (get_site_option("buddystream_" . $importer . "_power") && get_site_option("buddystream_" . $importer . "_import")) {

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