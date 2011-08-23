<?php
/**
 * Import for BuddyStream
 */


set_time_limit (0);
ini_set( 'max_execution_time', 900 );


$incPath = str_replace("/wp-content/plugins/buddystream","",getcwd());

ini_set('include_path', $incPath);
include('wp-load.php');

//if we are ran from the BuddyStream cronservice save the new uniqueKey
if($_GET['uniqueKey']){
    update_site_option("buddystream_cronservices_uniquekey", $_GET['uniqueKey']);       
}

//directory of extentions
$handle = opendir(WP_PLUGIN_DIR . "/buddystream/extentions");

//loop extentions so we can add active extentions to the import loop
if ($handle) {
    while (false !== ($file = readdir($handle))) {
        if ($file != "." && $file != "..") {
            if (file_exists(WP_PLUGIN_DIR."/buddystream/extentions/".$file."/import.php")) {
                if (get_site_option("buddystream_".$file."_power")) {
                        $extentions[] = $file;
                }
            }
         }
    }
}

//save importers to database
update_site_option("buddystream_importers", implode(",",$extentions));

//check if there is a import queue, if empty reset
if(get_site_option("buddystream_importers_queue") == ""){
    update_site_option("buddystream_importers_queue", implode(",",$extentions));
}

//start the import (one per time)
$importers = get_site_option("buddystream_importers_queue");
$importers = explode(",", $importers);
$importer  = current($importers);
echo "<pre>Imported: ".$importer."</pre>";

//remove importer form queue before starting real import
unset($importers[0]);
update_site_option("buddystream_importers_queue", implode(",",$importers));

//start the importer for real 
if (file_exists(WP_PLUGIN_DIR."/buddystream/extentions/".$importer."/import.php")) {
    if (get_site_option("buddystream_".$importer."_power")) {
        include_once(WP_PLUGIN_DIR."/buddystream/extentions/".$importer."/import.php");
        if (function_exists("Buddystream".ucfirst($importer)."ImportStart")) {
            call_user_func("Buddystream".ucfirst($importer)."ImportStart");
        }
    }
}